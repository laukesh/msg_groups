<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementBidComparison;
use App\Models\ProcurementBidComparisonItem;
use App\Models\ProcurementCommercialEvaluation;
use App\Models\ProcurementTender;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProcurementBidComparisonController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        ProcurementTender $procurementTender
    ): View {

        $comparisons = ProcurementBidComparison::query()
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            )
            ->with([
                'recommendedSubmission.tenderBidder.bidder',
            ])
            ->latest('id')
            ->get();

        return view(
            'procurement.bid-comparisons.index',
            compact(
                'procurementTender',
                'comparisons'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        ProcurementTender $procurementTender
    ): View {


        /*
        |--------------------------------------------------------------------------
        | Tender Award Lock
        |--------------------------------------------------------------------------
        */

        $tenderAwarded = $procurementTender
            ->awards()
            ->whereIn('status', [
                'LOA Issued',
            ])
            ->exists();


        if ($tenderAwarded) {

            abort(
                403,
                'Bid Comparison cannot be created because the Tender LOA has already been issued.'
            );
        }

        /*
         * Only Commercial Evaluations with
         * result = Qualified are allowed.
         */
        $eligibleCommercialEvaluations =
            ProcurementCommercialEvaluation::query()
                ->where(
                    'procurement_tender_id',
                    $procurementTender->id
                )
                ->where(
                    'result',
                    'Qualified'
                )
                ->with([
                    'submission',
                    'submission.tenderBidder',
                    'submission.tenderBidder.bidder',
                    'technicalEvaluation',
                ])
                ->orderBy(
                    'final_evaluated_amount',
                    'asc'
                )
                ->get();


        return view(
            'procurement.bid-comparisons.create',
            compact(
                'procurementTender',
                'eligibleCommercialEvaluations'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        ProcurementTender $procurementTender
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Tender Award Lock
        |--------------------------------------------------------------------------
        */

        $tenderAwarded = $procurementTender
            ->awards()
            ->whereIn('status', [
                'LOA Issued',
            ])
            ->exists();


        if ($tenderAwarded) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Bid Comparison cannot be created because the Tender LOA has already been issued.'
                );
        }

        $validated = $request->validate([

            'comparison_title' => [
                'required',
                'string',
                'max:255',
            ],

            'comparison_date' => [
                'nullable',
                'date',
            ],

            'evaluation_basis' => [
                'required',
                'in:Lowest Evaluated Bid,Best Value,Combined Technical & Financial Score',
            ],

            'status' => [
                'required',
                'in:Draft,Under Review,Completed,Approved,Rejected',
            ],

            'summary' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

            'selected_evaluations' => [
                'required',
                'array',
                'min:1',
            ],

            'selected_evaluations.*' => [
                'integer',
            ],
        ]);


        /*
         * Fetch ONLY qualified commercial evaluations
         * belonging to this Tender.
         */
        $evaluations =
            ProcurementCommercialEvaluation::query()
                ->where(
                    'procurement_tender_id',
                    $procurementTender->id
                )
                ->where(
                    'result',
                    'Qualified'
                )
                ->whereIn(
                    'id',
                    $validated['selected_evaluations']
                )
                ->with([
                    'submission',
                    'submission.tenderBidder',
                    'submission.tenderBidder.bidder',
                    'technicalEvaluation',
                ])
                ->get();


        if ($evaluations->isEmpty()) {

            return back()
                ->withInput()
                ->withErrors([
                    'selected_evaluations' =>
                        'No qualified commercial evaluations were selected.',
                ]);
        }


        /*
         * Prevent invalid IDs from another Tender.
         */
        if (
            $evaluations->count()
            !== count(
                array_unique(
                    $validated['selected_evaluations']
                )
            )
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'selected_evaluations' =>
                        'One or more selected evaluations are invalid.',
                ]);
        }


        /*
         * Currency must be consistent.
         */
        $currencies = $evaluations
            ->pluck('currency')
            ->filter()
            ->unique();


        if ($currencies->count() > 1) {

            return back()
                ->withInput()
                ->withErrors([
                    'selected_evaluations' =>
                        'All selected bids must use the same currency.',
                ]);
        }


        /*
         * Rank bids by final evaluated amount.
         */
        $evaluations = $evaluations
            ->sortBy('final_evaluated_amount')
            ->values();


        $currency =
            $evaluations
                ->first()
                ->currency
                ?: 'INR';


        /*
         * Determine recommendation.
         */
        $recommendedEvaluation =
            $this->determineRecommendedEvaluation(
                $evaluations,
                $validated['evaluation_basis']
            );


        DB::transaction(function () use (
            $validated,
            $procurementTender,
            $evaluations,
            $currency,
            $recommendedEvaluation
        ) {

            /*
             * Generate Comparison Number.
             *
             * Example:
             * BC-T100-001
             * BC-T100-002
             */
            $comparisonNumber =
                $this->generateComparisonNumber(
                    $procurementTender
                );


            /*
             * Create Comparison.
             */
            $comparison =
                ProcurementBidComparison::create([

                    'procurement_tender_id' =>
                        $procurementTender->id,

                    'comparison_number' =>
                        $comparisonNumber,

                    'comparison_title' =>
                        $validated['comparison_title'],

                    'comparison_date' =>
                        $validated['comparison_date']
                        ?? now()->toDateString(),

                    'evaluation_basis' =>
                        $validated['evaluation_basis'],

                    'total_bidders' =>
                        $evaluations->count(),

                    'qualified_bidders' =>
                        $evaluations->count(),

                    'lowest_evaluated_amount' =>
                        $evaluations
                            ->min('final_evaluated_amount'),

                    'currency' =>
                        $currency,

                    'recommended_submission_id' =>
                        $recommendedEvaluation
                            ->procurement_tender_submission_id,

                    'status' =>
                        $validated['status'],

                    'summary' =>
                        $validated['summary']
                        ?? null,

                    'remarks' =>
                        $validated['remarks']
                        ?? null,

                    'prepared_by' =>
                        auth()->id(),

                    'created_by' =>
                        auth()->id(),
                ]);


            /*
             * Create comparison items.
             */
            foreach (
                $evaluations as $index => $evaluation
            ) {

                $technicalScore =
                    (float) (
                        $evaluation
                            ->technicalEvaluation
                            ?->technical_score
                        ?? 0
                    );


                $maximumTechnicalScore =
                    (float) (
                        $evaluation
                            ->technicalEvaluation
                            ?->maximum_score
                        ?? 100
                    );


                $priceScore =
                    (float) (
                        $evaluation->price_score
                        ?? 0
                    );


                $maximumPriceScore =
                    (float) (
                        $evaluation->maximum_price_score
                        ?? 100
                    );


                $technicalPercentage =
                    $maximumTechnicalScore > 0
                        ? (
                            $technicalScore
                            / $maximumTechnicalScore
                        ) * 100
                        : 0;


                $pricePercentage =
                    $maximumPriceScore > 0
                        ? (
                            $priceScore
                            / $maximumPriceScore
                        ) * 100
                        : 0;


                $overallScore =
                    (
                        $technicalPercentage * 0.50
                    )
                    +
                    (
                        $pricePercentage * 0.50
                    );


                $bidderName =
                    $evaluation
                        ->submission
                        ?->tenderBidder
                        ?->bidder
                        ?->company_name;


                ProcurementBidComparisonItem::create([

                    'procurement_bid_comparison_id' =>
                        $comparison->id,

                    'procurement_tender_submission_id' =>
                        $evaluation
                            ->procurement_tender_submission_id,

                    'procurement_technical_evaluation_id' =>
                        $evaluation
                            ->procurement_technical_evaluation_id,

                    'procurement_commercial_evaluation_id' =>
                        $evaluation->id,

                    'bidder_rank' =>
                        $index + 1,

                    'bidder_name' =>
                        $bidderName
                        ?: 'Unknown Bidder',

                    'quoted_amount' =>
                        $evaluation->quoted_amount,

                    'evaluated_amount' =>
                        $evaluation->evaluated_amount,

                    'tax_amount' =>
                        $evaluation->tax_amount,

                    'discount_amount' =>
                        $evaluation->discount_amount,

                    'final_evaluated_amount' =>
                        $evaluation->final_evaluated_amount,

                    'currency' =>
                        $evaluation->currency,

                    'technical_score' =>
                        $technicalScore,

                    'price_score' =>
                        $priceScore,

                    'overall_score' =>
                        round(
                            $overallScore,
                            2
                        ),

                    'commercial_compliance' =>
                        $evaluation->commercial_compliance,

                    'comparison_result' =>
                        'Included',

                    'is_recommended' =>
                        $evaluation->id
                        === $recommendedEvaluation->id,

                    'remarks' =>
                        null,
                ]);
            }
        });


        return redirect()
            ->route(
                'admin.procurement.tenders.bid-comparisons.index',
                $procurementTender
            )
            ->with(
                'success',
                'Bid Comparison created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        ProcurementTender $procurementTender,
        ProcurementBidComparison $comparison
    ): View {

        abort_unless(
            $comparison->procurement_tender_id
            === $procurementTender->id,
            404
        );


        $comparison->load([
            'recommendedSubmission.tenderBidder.bidder',
        ]);


        $latestNegotiation = $procurementTender
            ->negotiations()
            ->latest('id')
            ->first();


        $canStartNegotiation =
            $comparison->status === 'Completed'
            && $comparison->recommended_submission_id;


        return view(
            'procurement.bid-comparisons.show',
            compact(
                'procurementTender',
                'comparison',
                'latestNegotiation',
                'canStartNegotiation'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        ProcurementTender $procurementTender,
        ProcurementBidComparison $comparison
    ): View {


        /*
        |--------------------------------------------------------------------------
        | Tender Award Lock
        |--------------------------------------------------------------------------
        */

        $tenderAwarded = $procurementTender
            ->awards()
            ->whereIn('status', [
                'LOA Issued',
            ])
            ->exists();


        if ($tenderAwarded) {

            abort(
                403,
                'Bid Comparison cannot be edited because the Tender LOA has already been issued.'
            );
        }

        abort_unless(
            $comparison->procurement_tender_id
            === $procurementTender->id,
            404
        );


        $comparison->load([
            'items',
        ]);


        $eligibleCommercialEvaluations =
            ProcurementCommercialEvaluation::query()
                ->where(
                    'procurement_tender_id',
                    $procurementTender->id
                )
                ->where(
                    'result',
                    'Qualified'
                )
                ->with([
                    'submission',
                    'submission.tenderBidder',
                    'submission.tenderBidder.bidder',
                    'technicalEvaluation',
                ])
                ->orderBy(
                    'final_evaluated_amount'
                )
                ->get();


        $selectedEvaluationIds =
            $comparison
                ->items
                ->pluck(
                    'procurement_commercial_evaluation_id'
                )
                ->map(
                    fn ($id) => (int) $id
                )
                ->toArray();


        return view(
            'procurement.bid-comparisons.edit',
            compact(
                'procurementTender',
                'comparison',
                'eligibleCommercialEvaluations',
                'selectedEvaluationIds'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        ProcurementTender $procurementTender,
        ProcurementBidComparison $comparison
    ): RedirectResponse {

        abort_unless(
            $comparison->procurement_tender_id
            === $procurementTender->id,
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Tender Award Lock
        |--------------------------------------------------------------------------
        */

        $tenderAwarded = $procurementTender
            ->awards()
            ->whereIn('status', [
                'LOA Issued',
            ])
            ->exists();


        if ($tenderAwarded) {

            return redirect()
                ->route(
                    'admin.procurement.tenders.bid-comparisons.show',
                    [
                        'procurementTender' =>
                            $procurementTender,

                        'comparison' =>
                            $comparison,
                    ]
                )
                ->with(
                    'error',
                    'Bid Comparison cannot be updated because the Tender LOA has already been issued.'
                );
        }


        $validated = $request->validate([

            'comparison_title' => [
                'required',
                'string',
                'max:255',
            ],

            'comparison_date' => [
                'nullable',
                'date',
            ],

            'evaluation_basis' => [
                'required',
                'in:Lowest Evaluated Bid,Best Value,Combined Technical & Financial Score',
            ],

            'status' => [
                'required',
                'in:Draft,Under Review,Completed,Approved,Rejected',
            ],

            'summary' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

            'selected_evaluations' => [
                'required',
                'array',
                'min:1',
            ],

            'selected_evaluations.*' => [
                'integer',
            ],
        ]);


        $evaluations =
            ProcurementCommercialEvaluation::query()
                ->where(
                    'procurement_tender_id',
                    $procurementTender->id
                )
                ->where(
                    'result',
                    'Qualified'
                )
                ->whereIn(
                    'id',
                    $validated['selected_evaluations']
                )
                ->with([
                    'submission',
                    'submission.tenderBidder',
                    'submission.tenderBidder.bidder',
                    'technicalEvaluation',
                ])
                ->get();


        if ($evaluations->isEmpty()) {

            return back()
                ->withInput()
                ->withErrors([
                    'selected_evaluations' =>
                        'No qualified commercial evaluations were selected.',
                ]);
        }


        if (
            $evaluations->count()
            !== count(
                array_unique(
                    $validated['selected_evaluations']
                )
            )
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'selected_evaluations' =>
                        'One or more selected evaluations are invalid.',
                ]);
        }


        $currencies =
            $evaluations
                ->pluck('currency')
                ->filter()
                ->unique();


        if ($currencies->count() > 1) {

            return back()
                ->withInput()
                ->withErrors([
                    'selected_evaluations' =>
                        'All selected bids must use the same currency.',
                ]);
        }


        $evaluations =
            $evaluations
                ->sortBy('final_evaluated_amount')
                ->values();


        $recommendedEvaluation =
            $this->determineRecommendedEvaluation(
                $evaluations,
                $validated['evaluation_basis']
            );


        DB::transaction(function () use (
            $validated,
            $comparison,
            $evaluations,
            $recommendedEvaluation
        ) {

            $comparison->update([

                /*
                 * IMPORTANT:
                 * comparison_number is NOT updated.
                 */
                'comparison_title' =>
                    $validated['comparison_title'],

                'comparison_date' =>
                    $validated['comparison_date']
                    ?? null,

                'evaluation_basis' =>
                    $validated['evaluation_basis'],

                'total_bidders' =>
                    $evaluations->count(),

                'qualified_bidders' =>
                    $evaluations->count(),

                'lowest_evaluated_amount' =>
                    $evaluations
                        ->min('final_evaluated_amount'),

                'currency' =>
                    $evaluations
                        ->first()
                        ->currency
                    ?? 'INR',

                'recommended_submission_id' =>
                    $recommendedEvaluation
                        ->procurement_tender_submission_id,

                'status' =>
                    $validated['status'],

                'summary' =>
                    $validated['summary']
                    ?? null,

                'remarks' =>
                    $validated['remarks']
                    ?? null,

                'updated_by' =>
                    auth()->id(),
            ]);


            /*
             * Rebuild comparison items.
             */
            $comparison->items()->delete();


            foreach (
                $evaluations as $index => $evaluation
            ) {

                $technicalScore =
                    (float) (
                        $evaluation
                            ->technicalEvaluation
                            ?->technical_score
                        ?? 0
                    );


                $maximumTechnicalScore =
                    (float) (
                        $evaluation
                            ->technicalEvaluation
                            ?->maximum_score
                        ?? 100
                    );


                $priceScore =
                    (float) (
                        $evaluation->price_score
                        ?? 0
                    );


                $maximumPriceScore =
                    (float) (
                        $evaluation->maximum_price_score
                        ?? 100
                    );


                $technicalPercentage =
                    $maximumTechnicalScore > 0
                        ? (
                            $technicalScore
                            / $maximumTechnicalScore
                        ) * 100
                        : 0;


                $pricePercentage =
                    $maximumPriceScore > 0
                        ? (
                            $priceScore
                            / $maximumPriceScore
                        ) * 100
                        : 0;


                $overallScore =
                    (
                        $technicalPercentage * 0.50
                    )
                    +
                    (
                        $pricePercentage * 0.50
                    );


                ProcurementBidComparisonItem::create([

                    'procurement_bid_comparison_id' =>
                        $comparison->id,

                    'procurement_tender_submission_id' =>
                        $evaluation
                            ->procurement_tender_submission_id,

                    'procurement_technical_evaluation_id' =>
                        $evaluation
                            ->procurement_technical_evaluation_id,

                    'procurement_commercial_evaluation_id' =>
                        $evaluation->id,

                    'bidder_rank' =>
                        $index + 1,

                    'bidder_name' =>
                        $evaluation
                            ->submission
                            ?->tenderBidder
                            ?->bidder
                            ?->company_name
                        ?: 'Unknown Bidder',

                    'quoted_amount' =>
                        $evaluation->quoted_amount,

                    'evaluated_amount' =>
                        $evaluation->evaluated_amount,

                    'tax_amount' =>
                        $evaluation->tax_amount,

                    'discount_amount' =>
                        $evaluation->discount_amount,

                    'final_evaluated_amount' =>
                        $evaluation->final_evaluated_amount,

                    'currency' =>
                        $evaluation->currency,

                    'technical_score' =>
                        $technicalScore,

                    'price_score' =>
                        $priceScore,

                    'overall_score' =>
                        round(
                            $overallScore,
                            2
                        ),

                    'commercial_compliance' =>
                        $evaluation->commercial_compliance,

                    'comparison_result' =>
                        'Included',

                    'is_recommended' =>
                        $evaluation->id
                        === $recommendedEvaluation->id,
                ]);
            }
        });


        return redirect()
            ->route(
                'admin.procurement.tenders.bid-comparisons.show',
                [
                    'procurementTender' =>
                        $procurementTender,

                    'comparison' =>
                        $comparison,
                ]
            )
            ->with(
                'success',
                'Bid Comparison updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        ProcurementTender $procurementTender,
        ProcurementBidComparison $comparison
    ): RedirectResponse {

        abort_unless(
            $comparison->procurement_tender_id
            === $procurementTender->id,
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Tender Award Lock
        |--------------------------------------------------------------------------
        */

        $tenderAwarded = $procurementTender
            ->awards()
            ->whereIn('status', [
                'LOA Issued',
            ])
            ->exists();


        if ($tenderAwarded) {

            return back()
                ->with(
                    'error',
                    'Bid Comparison cannot be deleted because the Tender LOA has already been issued.'
                );
        }


        $comparison->delete();


        return redirect()
            ->route(
                'admin.procurement.tenders.bid-comparisons.index',
                $procurementTender
            )
            ->with(
                'success',
                'Bid Comparison deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE COMPARISON NUMBER
    |--------------------------------------------------------------------------
    */

    private function generateComparisonNumber(
        ProcurementTender $procurementTender
    ): string {

        $tenderNumber =
            preg_replace(
                '/[^A-Za-z0-9]+/',
                '',
                $procurementTender->tender_number
            );


        /*
         * Find the latest comparison for this tender.
         */
        $lastComparison =
            ProcurementBidComparison::query()
                ->where(
                    'procurement_tender_id',
                    $procurementTender->id
                )
                ->latest('id')
                ->first();


        if ($lastComparison) {

            preg_match(
                '/(\d+)$/',
                $lastComparison->comparison_number,
                $matches
            );

            $nextNumber =
                isset($matches[1])
                    ? ((int) $matches[1]) + 1
                    : 1;

        } else {

            $nextNumber = 1;
        }


        return sprintf(
            'BC-%s-%03d',
            $tenderNumber,
            $nextNumber
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RECOMMENDATION LOGIC
    |--------------------------------------------------------------------------
    */

    private function determineRecommendedEvaluation(
        $evaluations,
        string $basis
    ) {

        /*
         * Lowest Evaluated Bid
         */
        if (
            $basis === 'Lowest Evaluated Bid'
        ) {

            return $evaluations
                ->sortBy(
                    'final_evaluated_amount'
                )
                ->first();
        }


        /*
         * Combined Technical & Financial Score
         */
        if (
            $basis ===
            'Combined Technical & Financial Score'
        ) {

            return $evaluations
                ->sortByDesc(
                    function ($evaluation) {

                        $technicalScore =
                            (float) (
                                $evaluation
                                    ->technicalEvaluation
                                    ?->technical_score
                                ?? 0
                            );


                        $maximumTechnicalScore =
                            (float) (
                                $evaluation
                                    ->technicalEvaluation
                                    ?->maximum_score
                                ?? 100
                            );


                        $priceScore =
                            (float) (
                                $evaluation
                                    ->price_score
                                ?? 0
                            );


                        $maximumPriceScore =
                            (float) (
                                $evaluation
                                    ->maximum_price_score
                                ?? 100
                            );


                        $technicalPercentage =
                            $maximumTechnicalScore > 0
                                ? (
                                    $technicalScore
                                    /
                                    $maximumTechnicalScore
                                ) * 100
                                : 0;


                        $pricePercentage =
                            $maximumPriceScore > 0
                                ? (
                                    $priceScore
                                    /
                                    $maximumPriceScore
                                ) * 100
                                : 0;


                        return (
                            $technicalPercentage * 0.50
                        )
                        +
                        (
                            $pricePercentage * 0.50
                        );
                    }
                )
                ->first();
        }


        /*
         * Best Value
         *
         * For now, use highest combined
         * technical + price score.
         */
        return $evaluations
            ->sortByDesc(
                function ($evaluation) {

                    return
                        (float) (
                            $evaluation
                                ->technicalEvaluation
                                ?->technical_score
                            ?? 0
                        )
                        +
                        (float) (
                            $evaluation->price_score
                            ?? 0
                        );
                }
            )
            ->first();
    }
}