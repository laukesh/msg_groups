<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementBidComparison;
use App\Models\ProcurementNegotiation;
use App\Models\ProcurementNegotiationItem;
use App\Models\ProcurementTender;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProcurementNegotiationController extends Controller
{

    /**
     * Prevent negotiation changes after Tender LOA is issued.
     */
    private function ensureTenderNegotiationEditable(
        ProcurementTender $procurementTender
    ): void {
        $loaIssued = $procurementTender
            ->awards()
            ->whereIn('status', [
                'LOA Issued',
            ])
            ->exists();

        if ($loaIssued) {
            abort(
                403,
                'Negotiations cannot be modified after the Tender LOA has been issued.'
            );
        }
    }
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(
        ProcurementTender $procurementTender
    ): View {

        $negotiations = ProcurementNegotiation::query()
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            )
            ->with([
                'bidComparison',
                'submission.tenderBidder.bidder',
                'items',
            ])
            ->latest('id')
            ->get();


        return view(
            'procurement.negotiations.index',
            compact(
                'procurementTender',
                'negotiations'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(
        ProcurementTender $procurementTender
    ): View {

         $this->ensureTenderNegotiationEditable(
            $procurementTender
        );

        /*
         * Only Completed / Approved Bid Comparisons
         * are eligible for Negotiation.
         */
        $comparisons = ProcurementBidComparison::query()
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            )
            ->with([
                'recommendedSubmission.tenderBidder.bidder',
                'items',
            ])
            ->whereIn(
                'status',
                [
                    'Completed',
                    'Approved',
                ]
            )
            ->latest('id')
            ->get();


        return view(
            'procurement.negotiations.create',
            compact(
                'procurementTender',
                'comparisons'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        ProcurementTender $procurementTender
    ): RedirectResponse {

        $this->ensureTenderNegotiationEditable(
            $procurementTender
        );

        $validated = $request->validate([

            'procurement_bid_comparison_id' => [
                'required',
                'exists:procurement_bid_comparisons,id',
            ],

            'negotiation_title' => [
                'required',
                'string',
                'max:255',
            ],

            'negotiation_date' => [
                'nullable',
                'date',
            ],

            'negotiation_type' => [
                'nullable',
                'string',
                'max:100',
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

            'round_date' => [
                'nullable',
                'date',
            ],

            'bidder_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'negotiated_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'discount_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'bidder_comments' => [
                'nullable',
                'string',
            ],

            'evaluator_comments' => [
                'nullable',
                'string',
            ],

            'round_remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Verify Bid Comparison
        |--------------------------------------------------------------------------
        */

        $comparison = ProcurementBidComparison::query()
            ->where(
                'id',
                $validated['procurement_bid_comparison_id']
            )
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            )
            ->with([
                'recommendedSubmission.tenderBidder.bidder',
            ])
            ->first();


        if (!$comparison) {

            return back()
                ->withInput()
                ->withErrors([
                    'procurement_bid_comparison_id' =>
                        'Invalid Bid Comparison selected.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Only Completed / Approved Comparison
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $comparison->status,
                [
                    'Completed',
                    'Approved',
                ],
                true
            )
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'procurement_bid_comparison_id' =>
                        'Only Completed or Approved Bid Comparisons can be negotiated.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Recommended Submission
        |--------------------------------------------------------------------------
        */

        $submission =
            $comparison->recommendedSubmission;


        if (!$submission) {

            return back()
                ->withInput()
                ->withErrors([
                    'procurement_bid_comparison_id' =>
                        'The selected Bid Comparison has no recommended bidder.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Recommended Comparison Item
        |--------------------------------------------------------------------------
        */

        $recommendedItem =
            $comparison
                ->items()
                ->where(
                    'procurement_tender_submission_id',
                    $submission->id
                )
                ->first();


        if (!$recommendedItem) {

            return back()
                ->withInput()
                ->withErrors([
                    'procurement_bid_comparison_id' =>
                        'Recommended bidder is not present in the comparison.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Currency
        |--------------------------------------------------------------------------
        */

        $currency =
            $recommendedItem->currency
            ?: 'INR';


        /*
        |--------------------------------------------------------------------------
        | Bidder Name
        |--------------------------------------------------------------------------
        */

        $bidderName =
            $submission
                ->tenderBidder
                ?->bidder
                ?->company_name
            ?? 'Unknown Bidder';


        /*
        |--------------------------------------------------------------------------
        | Negotiated Amount
        |--------------------------------------------------------------------------
        */

        $negotiatedAmount =
            (float)
            $validated['negotiated_amount'];


        /*
        |--------------------------------------------------------------------------
        | Discount
        |--------------------------------------------------------------------------
        */

        $discountAmount =
            (float)
            (
                $validated['discount_amount']
                ?? 0
            );


        /*
        |--------------------------------------------------------------------------
        | Final Amount
        |--------------------------------------------------------------------------
        |
        | Negotiated Amount is treated as the final
        | negotiated amount.
        |
        */

        $finalAmount =
            $negotiatedAmount;


        /*
        |--------------------------------------------------------------------------
        | Generate Negotiation Number
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | NEG-T100-001
        | NEG-T100-002
        | NEG-T100-003
        |
        */

        $tenderNumber =
            $procurementTender->tender_number
            ?: 'TENDER-' . $procurementTender->id;


        /*
         * Remove spaces and special characters
         * from Tender Number for document number.
         */
        $tenderNumberForCode =
            strtoupper(
                preg_replace(
                    '/[^A-Za-z0-9]/',
                    '',
                    $tenderNumber
                )
            );


        /*
         * Find the last negotiation for this Tender.
         */
        $lastNegotiation =
            ProcurementNegotiation::query()
                ->where(
                    'procurement_tender_id',
                    $procurementTender->id
                )
                ->latest('id')
                ->first();


        /*
         * Determine next sequence.
         */
        $nextSequence = 1;


        if ($lastNegotiation) {

            /*
             * Try to extract the last 3 digits
             * from an existing negotiation number.
             *
             * Example:
             *
             * NEG-T100-005
             *
             * becomes:
             *
             * 005
             */
            if (
                preg_match(
                    '/(\d{3})$/',
                    $lastNegotiation->negotiation_number,
                    $matches
                )
            ) {

                $nextSequence =
                    ((int) $matches[1]) + 1;
            }
        }


        /*
         * Final generated number.
         */
        $negotiationNumber =
            'NEG-'
            . $tenderNumberForCode
            . '-'
            . str_pad(
                $nextSequence,
                3,
                '0',
                STR_PAD_LEFT
            );


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Number
        |--------------------------------------------------------------------------
        */

        while (
            ProcurementNegotiation::query()
                ->where(
                    'negotiation_number',
                    $negotiationNumber
                )
                ->exists()
        ) {

            $nextSequence++;

            $negotiationNumber =
                'NEG-'
                . $tenderNumberForCode
                . '-'
                . str_pad(
                    $nextSequence,
                    3,
                    '0',
                    STR_PAD_LEFT
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Negotiation + First Round
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $validated,
                $procurementTender,
                $comparison,
                $submission,
                $recommendedItem,
                $bidderName,
                $currency,
                $finalAmount,
                $negotiationNumber
            ) {

                /*
                |--------------------------------------------------------------------------
                | Create Negotiation
                |--------------------------------------------------------------------------
                */

                $negotiation =
                    ProcurementNegotiation::create([

                        'procurement_tender_id' =>
                            $procurementTender->id,

                        'procurement_bid_comparison_id' =>
                            $comparison->id,

                        'procurement_tender_submission_id' =>
                            $submission->id,

                        'negotiation_number' =>
                            $negotiationNumber,

                        'negotiation_title' =>
                            $validated['negotiation_title'],

                        'negotiation_date' =>
                            $validated['negotiation_date']
                            ?? null,

                        'negotiation_type' =>
                            $validated['negotiation_type']
                            ?? null,

                        'bidder_name' =>
                            $bidderName,

                        'original_amount' =>
                            $recommendedItem
                                ->final_evaluated_amount,

                        'negotiated_amount' =>
                            $finalAmount,

                        'final_amount' =>
                            $finalAmount,

                        'currency' =>
                            $currency,

                        'outcome' =>
                            'Open',

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
                |--------------------------------------------------------------------------
                | First Negotiation Round
                |--------------------------------------------------------------------------
                */

                ProcurementNegotiationItem::create([

                    'procurement_negotiation_id' =>
                        $negotiation->id,

                    'round_number' =>
                        1,

                    'round_date' =>
                        $validated['round_date']
                        ?? $validated['negotiation_date']
                        ?? null,

                    'bidder_amount' =>
                        $validated['bidder_amount'],

                    'negotiated_amount' =>
                        $validated['negotiated_amount'],

                    'discount_amount' =>
                        $validated['discount_amount']
                        ?? 0,

                    'final_amount' =>
                        $finalAmount,

                    'currency' =>
                        $currency,

                    'negotiation_status' =>
                        'Open',

                    'bidder_comments' =>
                        $validated['bidder_comments']
                        ?? null,

                    'evaluator_comments' =>
                        $validated['evaluator_comments']
                        ?? null,

                    'remarks' =>
                        $validated['round_remarks']
                        ?? null,

                    'created_by' =>
                        auth()->id(),
                ]);
            }
        );


        return redirect()
            ->route(
                'admin.procurement.tenders.negotiations.index',
                $procurementTender
            )
            ->with(
                'success',
                'Negotiation '
                . $negotiationNumber
                . ' created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        ProcurementTender $procurementTender,
        ProcurementNegotiation $negotiation
    ): View {

        abort_unless(
            $negotiation->procurement_tender_id
            === $procurementTender->id,
            404
        );


        $negotiation->load([
            'tender',
            'bidComparison',
            'submission.tenderBidder.bidder',
            'items',
        ]);


        return view(
            'procurement.negotiations.show',
            compact(
                'procurementTender',
                'negotiation'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Edit Negotiation
    |--------------------------------------------------------------------------
    */

    public function edit(
        ProcurementTender $procurementTender,
        ProcurementNegotiation $negotiation
    ): View {

        abort_unless(
            $negotiation->procurement_tender_id ===
                $procurementTender->id,
            404
        );

        $this->ensureTenderNegotiationEditable(
            $procurementTender
        );

        $negotiation->load([
            'bidComparison',
            'submission.tenderBidder.bidder',
            'items',
        ]);

        /*
         * Approved and Rejected negotiations are locked.
         */
        if (
            in_array(
                $negotiation->status,
                [
                    'Approved',
                    'Rejected',
                ],
                true
            )
        ) {
            return redirect()
                ->route(
                    'admin.procurement.tenders.negotiations.show',
                    [
                        'procurementTender' =>
                            $procurementTender,

                        'negotiation' =>
                            $negotiation,
                    ]
                )
                ->with(
                    'error',
                    'This negotiation cannot be edited because it is already '
                    . $negotiation->status . '.'
                );
        }

        return view(
            'procurement.negotiations.edit',
            compact(
                'procurementTender',
                'negotiation'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Negotiation
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        ProcurementTender $procurementTender,
        ProcurementNegotiation $negotiation
    ): RedirectResponse {

        abort_unless(
            $negotiation->procurement_tender_id ===
                $procurementTender->id,
            404
        );

        $this->ensureTenderNegotiationEditable(
            $procurementTender
        );


        /*
         * Approved and Rejected negotiations are locked.
         */
        if (
            in_array(
                $negotiation->status,
                [
                    'Approved',
                    'Rejected',
                ],
                true
            )
        ) {
            return back()->with(
                'error',
                'This negotiation cannot be edited because it is already '
                . $negotiation->status . '.'
            );
        }


        $validated = $request->validate([

            'negotiation_title' => [
                'required',
                'string',
                'max:255',
            ],

            'negotiation_date' => [
                'nullable',
                'date',
            ],

            'negotiation_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            /*
             * IMPORTANT:
             * Approved is NOT an editable status.
             */
            'status' => [
                'required',
                'in:Draft,Under Review,Completed,Rejected',
            ],

            /*
             * Approved is NOT an outcome.
             */
            'outcome' => [
                'required',
                'in:Open,Agreed,Not Agreed,Rejected',
            ],

            'summary' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Completed requires Agreed
        |--------------------------------------------------------------------------
        */

        if (
            $validated['status'] === 'Completed'
            &&
            $validated['outcome'] !== 'Agreed'
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'outcome' =>
                        'A Completed negotiation must have an Agreed outcome.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Agreed requires latest round = Agreed
        |--------------------------------------------------------------------------
        */

        if ($validated['outcome'] === 'Agreed') {

            $latestRound = $negotiation
                ->items()
                ->orderByDesc('round_number')
                ->first();


            if (!$latestRound) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'outcome' =>
                            'The negotiation cannot be marked as Agreed '
                            . 'because no negotiation round exists.',
                    ]);
            }


            if (
                $latestRound->negotiation_status !==
                'Agreed'
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'outcome' =>
                            'The latest negotiation round must be marked '
                            . 'as Agreed before the negotiation outcome '
                            . 'can be Agreed.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Rejected validation
        |--------------------------------------------------------------------------
        */

        if (
            $validated['status'] === 'Rejected'
            &&
            $validated['outcome'] !== 'Rejected'
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'outcome' =>
                        'A Rejected negotiation must have a Rejected outcome.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $negotiation->update([

            'negotiation_title' =>
                $validated['negotiation_title'],

            'negotiation_date' =>
                $validated['negotiation_date'] ?? null,

            'negotiation_type' =>
                $validated['negotiation_type'] ?? null,

            'status' =>
                $validated['status'],

            'outcome' =>
                $validated['outcome'],

            'summary' =>
                $validated['summary'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.procurement.tenders.negotiations.show',
                [
                    'procurementTender' =>
                        $procurementTender,

                    'negotiation' =>
                        $negotiation,
                ]
            )
            ->with(
                'success',
                'Negotiation updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Create Round
    |--------------------------------------------------------------------------
    */

    public function createRound(
        ProcurementTender $procurementTender,
        ProcurementNegotiation $negotiation
    ): View {

        abort_unless(
            $negotiation->procurement_tender_id
            === $procurementTender->id,
            404
        );

        $this->ensureTenderNegotiationEditable(
            $procurementTender
        );


        /*
         * Do not allow new rounds after
         * negotiation has been closed.
         */
        if (
            in_array(
                $negotiation->status,
                [
                    'Completed',
                    'Approved',
                    'Rejected',
                ],
                true
            )
        ) {

            return redirect()
                ->route(
                    'admin.procurement.tenders.negotiations.show',
                    [
                        'procurementTender' =>
                            $procurementTender,

                        'negotiation' =>
                            $negotiation,
                    ]
                )
                ->with(
                    'error',
                    'No additional rounds can be added because this negotiation is already closed.'
                );
        }


        $negotiation->load([
            'submission.tenderBidder.bidder',
            'items',
        ]);


        /*
         * Calculate next round.
         */
        $nextRound =
            (
                (int)
                $negotiation
                    ->items
                    ->max('round_number')
            ) + 1;


        /*
         * Previous round.
         */
        $lastRound =
            $negotiation
                ->items
                ->sortByDesc('round_number')
                ->first();


        $previousAmount =
            $lastRound
                ?->final_amount
            ?? $negotiation->final_amount;


        return view(
            'procurement.negotiations.rounds.create',
            compact(
                'procurementTender',
                'negotiation',
                'nextRound',
                'previousAmount'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Round
    |--------------------------------------------------------------------------
    */

    public function storeRound(
        Request $request,
        ProcurementTender $procurementTender,
        ProcurementNegotiation $negotiation
    ): RedirectResponse {

        abort_unless(
            $negotiation->procurement_tender_id
            === $procurementTender->id,
            404
        );

        $this->ensureTenderNegotiationEditable(
            $procurementTender
        );


        if (
            in_array(
                $negotiation->status,
                [
                    'Completed',
                    'Approved',
                    'Rejected',
                ],
                true
            )
        ) {

            return back()->with(
                'error',
                'No additional rounds can be added because this negotiation is closed.'
            );
        }


        $validated = $request->validate([

            'round_date' => [
                'nullable',
                'date',
            ],

            'bidder_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'negotiated_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'discount_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'negotiation_status' => [
                'required',
                'in:Open,In Progress,Agreed,Rejected',
            ],

            'bidder_comments' => [
                'nullable',
                'string',
            ],

            'evaluator_comments' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
         * Get last round.
         */
        $lastRound =
            $negotiation
                ->items()
                ->orderByDesc('round_number')
                ->first();


        $nextRound =
            ($lastRound?->round_number ?? 0) + 1;


        /*
         * New negotiated amount becomes
         * current final amount.
         */
        $finalAmount =
            (float)
            $validated['negotiated_amount'];


        DB::transaction(
            function () use (
                $validated,
                $negotiation,
                $nextRound,
                $finalAmount
            ) {

                ProcurementNegotiationItem::create([

                    'procurement_negotiation_id' =>
                        $negotiation->id,

                    'round_number' =>
                        $nextRound,

                    'round_date' =>
                        $validated['round_date']
                        ?? now()->format('Y-m-d'),

                    'bidder_amount' =>
                        $validated['bidder_amount'],

                    'negotiated_amount' =>
                        $validated['negotiated_amount'],

                    'discount_amount' =>
                        $validated['discount_amount']
                        ?? 0,

                    'final_amount' =>
                        $finalAmount,

                    'currency' =>
                        $negotiation->currency,

                    'negotiation_status' =>
                        $validated['negotiation_status'],

                    'bidder_comments' =>
                        $validated['bidder_comments']
                        ?? null,

                    'evaluator_comments' =>
                        $validated['evaluator_comments']
                        ?? null,

                    'remarks' =>
                        $validated['remarks']
                        ?? null,

                    'created_by' =>
                        auth()->id(),
                ]);


                /*
                 * Update current negotiation amount.
                 */
                $negotiation->update([

                    'negotiated_amount' =>
                        $finalAmount,

                    'final_amount' =>
                        $finalAmount,

                    'updated_by' =>
                        auth()->id(),
                ]);


                /*
                 * If agreed, update outcome.
                 */
                if (
                    $validated['negotiation_status']
                    === 'Agreed'
                ) {

                    $negotiation->update([

                        'outcome' =>
                            'Agreed',

                    ]);
                }
            }
        );


        return redirect()
            ->route(
                'admin.procurement.tenders.negotiations.show',
                [
                    'procurementTender' =>
                        $procurementTender,

                    'negotiation' =>
                        $negotiation,
                ]
            )
            ->with(
                'success',
                'Negotiation Round '
                . $nextRound
                . ' added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Finalize
    |--------------------------------------------------------------------------
    */

    public function finalize(
        ProcurementTender $procurementTender,
        ProcurementNegotiation $negotiation
    ): RedirectResponse {

        abort_unless(
            $negotiation->procurement_tender_id
            === $procurementTender->id,
            404
        );
        $this->ensureTenderNegotiationEditable(
            $procurementTender
        );


        /*
         * Only Draft / Under Review
         * negotiations can be finalized.
         */
        if (
            !in_array(
                $negotiation->status,
                [
                    'Draft',
                    'Under Review',
                ],
                true
            )
        ) {

            return back()->with(
                'error',
                'This negotiation cannot be finalized in its current status.'
            );
        }


        $lastRound =
            $negotiation
                ->items()
                ->orderByDesc('round_number')
                ->first();


        if (!$lastRound) {

            return back()->with(
                'error',
                'Negotiation cannot be finalized because no negotiation round exists.'
            );
        }


        /*
         * Latest round must be Agreed.
         */
        if (
            $lastRound->negotiation_status
            !== 'Agreed'
        ) {

            return back()->with(
                'error',
                'The latest negotiation round must be marked as Agreed before finalization.'
            );
        }


        DB::transaction(
            function () use (
                $negotiation,
                $lastRound
            ) {

                $negotiation->update([

                    'negotiated_amount' =>
                        $lastRound->negotiated_amount,

                    'final_amount' =>
                        $lastRound->final_amount,

                    'outcome' =>
                        'Agreed',

                    'status' =>
                        'Completed',

                    'updated_by' =>
                        auth()->id(),

                ]);
            }
        );


        return back()->with(
            'success',
            'Negotiation finalized successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Approve
    |--------------------------------------------------------------------------
    */

    public function approve(
        ProcurementTender $procurementTender,
        ProcurementNegotiation $negotiation
    ): RedirectResponse {

        abort_unless(
            $negotiation->procurement_tender_id
            === $procurementTender->id,
            404
        );
        $this->ensureTenderNegotiationEditable(
            $procurementTender
        );


        /*
         * Only Completed negotiations
         * can be approved.
         */
        if (
            $negotiation->status !== 'Completed'
        ) {

            return back()->with(
                'error',
                'Only completed negotiations can be approved.'
            );
        }


        $negotiation->update([

            'status' =>
                'Approved',

            'outcome' =>
                'Approved',

            'approved_by' =>
                auth()->id(),

            'approval_date' =>
                now()->format('Y-m-d'),

            'updated_by' =>
                auth()->id(),

        ]);


        return back()->with(
            'success',
            'Negotiation approved successfully.'
        );
    }


    public function editRound(
        ProcurementTender $procurementTender,
        ProcurementNegotiation $negotiation,
        ProcurementNegotiationItem $item
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Verify negotiation belongs to tender
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $negotiation->procurement_tender_id ===
                $procurementTender->id,
            404
        );
        $this->ensureTenderNegotiationEditable(
            $procurementTender
        );


        /*
        |--------------------------------------------------------------------------
        | Verify round belongs to negotiation
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $item->procurement_negotiation_id ===
                $negotiation->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Completed / Approved negotiations are locked
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $negotiation->status,
                [
                    'Completed',
                    'Approved',
                    'Rejected',
                ],
                true
            )
        ) {

            return redirect()
                ->route(
                    'admin.procurement.tenders.negotiations.show',
                    [
                        'procurementTender' =>
                            $procurementTender,

                        'negotiation' =>
                            $negotiation,
                    ]
                )
                ->with(
                    'error',
                    'Rounds cannot be edited after the negotiation is '
                    . $negotiation->status . '.'
                );
        }


        $negotiation->load([
            'submission.tenderBidder.bidder',
        ]);


        return view(
            'procurement.negotiations.rounds.edit',
            compact(
                'procurementTender',
                'negotiation',
                'item'
            )
        );
    }

    public function updateRound(
        Request $request,
        ProcurementTender $procurementTender,
        ProcurementNegotiation $negotiation,
        ProcurementNegotiationItem $item
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Verify negotiation belongs to tender
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $negotiation->procurement_tender_id ===
                $procurementTender->id,
            404
        );
        $this->ensureTenderNegotiationEditable(
            $procurementTender
        );


        /*
        |--------------------------------------------------------------------------
        | Verify round belongs to negotiation
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $item->procurement_negotiation_id ===
                $negotiation->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Completed / Approved negotiations are locked
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $negotiation->status,
                [
                    'Completed',
                    'Approved',
                    'Rejected',
                ],
                true
            )
        ) {

            return back()->with(
                'error',
                'This negotiation is already '
                . $negotiation->status
                . ' and its rounds cannot be edited.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'round_date' => [
                'required',
                'date',
            ],

            'bidder_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'negotiated_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'discount_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'final_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'negotiation_status' => [
                'required',
                'in:In Progress,Agreed,Rejected',
            ],

            'bidder_comments' => [
                'nullable',
                'string',
            ],

            'evaluator_comments' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Agreed validation
        |--------------------------------------------------------------------------
        */

        if (
            $validated['negotiation_status'] ===
            'Agreed'
        ) {

            if (
                (float) $validated['final_amount'] <= 0
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'final_amount' =>
                            'Final amount must be greater than zero '
                            . 'when the round is Agreed.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Update round
        |--------------------------------------------------------------------------
        */

        $item->update([

            /*
             * round_number intentionally NOT updated.
             */
            'round_date' =>
                $validated['round_date'],

            'bidder_amount' =>
                $validated['bidder_amount'],

            'negotiated_amount' =>
                $validated['negotiated_amount'],

            'discount_amount' =>
                $validated['discount_amount'] ?? 0,

            'final_amount' =>
                $validated['final_amount'],

            'currency' =>
                $validated['currency'],

            'negotiation_status' =>
                $validated['negotiation_status'],

            'bidder_comments' =>
                $validated['bidder_comments'] ?? null,

            'evaluator_comments' =>
                $validated['evaluator_comments'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


        /*
        |--------------------------------------------------------------------------
        | Synchronize negotiation amounts
        |--------------------------------------------------------------------------
        */

        $latestRound = $negotiation
            ->items()
            ->orderByDesc('round_number')
            ->first();


        if ($latestRound) {

            $negotiation->update([

                'negotiated_amount' =>
                    $latestRound->negotiated_amount,

                'final_amount' =>
                    $latestRound->final_amount,

                'currency' =>
                    $latestRound->currency,

                'updated_by' =>
                    auth()->id(),
            ]);
        }


        return redirect()
            ->route(
                'admin.procurement.tenders.negotiations.show',
                [
                    'procurementTender' =>
                        $procurementTender,

                    'negotiation' =>
                        $negotiation,
                ]
            )
            ->with(
                'success',
                'Negotiation Round '
                . $item->round_number
                . ' updated successfully.'
            );
    }


}