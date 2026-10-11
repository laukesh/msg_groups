<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProcurementTender;
use App\Models\ProcurementTenderSubmission;
use App\Models\ProcurementTechnicalEvaluation;
use App\Models\ProcurementCommercialEvaluation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProcurementCommercialEvaluationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project,
        ProcurementTender $procurementTender
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Verify Tender belongs to Project
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $procurementTender->project_id === (int) $project->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Get Commercial Evaluations
        |--------------------------------------------------------------------------
        */

        $evaluations = $procurementTender
            ->commercialEvaluations()
            ->where(
                'project_id',
                $project->id
            )
            ->with([
                'project',
                'submission.tenderBidder.bidder',
                'technicalEvaluation',
            ])
            ->latest()
            ->get();


        return view(
            'procurement.commercial-evaluations.index',
            compact(
                'project',
                'procurementTender',
                'evaluations'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(
        Project $project,
        ProcurementTender $procurementTender
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Verify Tender belongs to Project
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $procurementTender->project_id === (int) $project->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Already Evaluated Submissions
        |--------------------------------------------------------------------------
        */

        $evaluatedSubmissionIds = $procurementTender
            ->commercialEvaluations()
            ->where(
                'project_id',
                $project->id
            )
            ->pluck(
                'procurement_tender_submission_id'
            );


        /*
        |--------------------------------------------------------------------------
        | Get Technically Qualified Submissions
        |--------------------------------------------------------------------------
        */

        $availableSubmissions = ProcurementTenderSubmission::query()
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            )
            ->whereNotIn(
                'id',
                $evaluatedSubmissionIds
            )
            ->whereHas(
                'technicalEvaluation',
                function ($query) use ($procurementTender) {

                    $query
                        ->where(
                            'procurement_tender_id',
                            $procurementTender->id
                        )
                        ->where(
                            'result',
                            'Qualified'
                        );
                }
            )
            ->with([
                'tenderBidder.bidder',
                'technicalEvaluation',
            ])
            ->get();


        return view(
            'procurement.commercial-evaluations.create',
            compact(
                'project',
                'procurementTender',
                'availableSubmissions'
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
        Project $project,
        ProcurementTender $procurementTender
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Verify Tender belongs to Project
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $procurementTender->project_id === (int) $project->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'procurement_tender_submission_id' => [
                'required',
                'integer',
                'exists:procurement_tender_submissions,id',
            ],

            'evaluation_date' => [
                'nullable',
                'date',
            ],

            'evaluator_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'evaluated_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'tax_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'discount_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'final_evaluated_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'price_score' => [
                'required',
                'numeric',
                'min:0',
            ],

            'maximum_price_score' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'commercial_compliance' => [
                'required',
                'in:Pending,Compliant,Partially Compliant,Non-Compliant',
            ],

            'evaluation_summary' => [
                'nullable',
                'string',
            ],

            'strengths' => [
                'nullable',
                'string',
            ],

            'weaknesses' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Draft,Under Evaluation,Completed,Approved,Rejected',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Verify Submission belongs to Tender AND Project
        |--------------------------------------------------------------------------
        */

        $submission = ProcurementTenderSubmission::query()
            ->where(
                'id',
                $validated['procurement_tender_submission_id']
            )
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Get Technical Evaluation
        |--------------------------------------------------------------------------
        */

        $technicalEvaluation =
            ProcurementTechnicalEvaluation::query()
                ->where(
                    'procurement_tender_submission_id',
                    $submission->id
                )
                ->where(
                    'procurement_tender_id',
                    $procurementTender->id
                )
                ->where(
                    'result',
                    'Qualified'
                )
                ->first();


        if (!$technicalEvaluation) {

            return back()
                ->withInput()
                ->withErrors([
                    'procurement_tender_submission_id' =>
                        'Only technically qualified submissions can undergo Commercial Evaluation.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Commercial Evaluation
        |--------------------------------------------------------------------------
        */

        $alreadyExists =
            ProcurementCommercialEvaluation::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->where(
                    'procurement_tender_id',
                    $procurementTender->id
                )
                ->where(
                    'procurement_tender_submission_id',
                    $submission->id
                )
                ->exists();


        if ($alreadyExists) {

            return back()
                ->withInput()
                ->withErrors([
                    'procurement_tender_submission_id' =>
                        'This submission already has a Commercial Evaluation.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Price Score Validation
        |--------------------------------------------------------------------------
        */

        if (
            (float) $validated['price_score']
            >
            (float) $validated['maximum_price_score']
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'price_score' =>
                        'Price score cannot exceed maximum price score.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Generate Evaluation Number
        |--------------------------------------------------------------------------
        */

        $evaluationNumber =
            $this->generateEvaluationNumber();


        /*
        |--------------------------------------------------------------------------
        | Calculate Result
        |--------------------------------------------------------------------------
        */

        $result = $this->calculateResult(
            (float) $validated['price_score'],
            (float) $validated['maximum_price_score'],
            $validated['commercial_compliance'],
            $validated['status']
        );


        /*
        |--------------------------------------------------------------------------
        | Create Commercial Evaluation
        |--------------------------------------------------------------------------
        */

        $evaluation =
            ProcurementCommercialEvaluation::create([

                /*
                | Project
                */
                'project_id' =>
                    $project->id,

                /*
                | Procurement Relationships
                */
                'procurement_tender_id' =>
                    $procurementTender->id,

                'procurement_tender_submission_id' =>
                    $submission->id,

                'procurement_technical_evaluation_id' =>
                    $technicalEvaluation->id,

                /*
                | Evaluation
                */
                'evaluation_number' =>
                    $evaluationNumber,

                'evaluation_date' =>
                    $validated['evaluation_date']
                    ?? now()->toDateString(),

                /*
                | Evaluator
                */
                'evaluator_id' =>
                    auth()->id(),

                'evaluator_name' =>
                    $validated['evaluator_name']
                    ?? auth()->user()?->name,

                /*
                | Financial
                */
                'quoted_amount' =>
                    $submission->quoted_amount,

                'evaluated_amount' =>
                    $validated['evaluated_amount'],

                'tax_amount' =>
                    $validated['tax_amount']
                    ?? 0,

                'discount_amount' =>
                    $validated['discount_amount']
                    ?? 0,

                'final_evaluated_amount' =>
                    $validated['final_evaluated_amount'],

                'currency' =>
                    $submission->currency,

                /*
                | Scoring
                */
                'price_score' =>
                    $validated['price_score'],

                'maximum_price_score' =>
                    $validated['maximum_price_score'],

                'commercial_compliance' =>
                    $validated['commercial_compliance'],

                'result' =>
                    $result,

                /*
                | Description
                */
                'evaluation_summary' =>
                    $validated['evaluation_summary']
                    ?? null,

                'strengths' =>
                    $validated['strengths']
                    ?? null,

                'weaknesses' =>
                    $validated['weaknesses']
                    ?? null,

                'remarks' =>
                    $validated['remarks']
                    ?? null,

                /*
                | Status
                */
                'status' =>
                    $validated['status'],

                'evaluated_at' =>
                    in_array(
                        $validated['status'],
                        [
                            'Completed',
                            'Approved',
                        ],
                        true
                    )
                        ? now()
                        : null,

                /*
                | Audit
                */
                'created_by' =>
                    auth()->id(),
            ]);


        return redirect()
            ->route(
                'admin.projects.procurement.tenders.commercial-evaluations.show',
                [
                    'project' =>
                        $project,

                    'procurementTender' =>
                        $procurementTender,

                    'evaluation' =>
                        $evaluation,
                ]
            )
            ->with(
                'success',
                'Commercial evaluation created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementCommercialEvaluation $evaluation
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Verify Relationships
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $procurementTender->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $evaluation->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $evaluation->procurement_tender_id ===
                (int) $procurementTender->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Load Relationships
        |--------------------------------------------------------------------------
        */

        $evaluation->load([
            'project',
            'tender',
            'submission.tenderBidder.bidder',
            'technicalEvaluation',
        ]);


        return view(
            'procurement.commercial-evaluations.show',
            compact(
                'project',
                'procurementTender',
                'evaluation'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementCommercialEvaluation $evaluation
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Verify Relationships
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $procurementTender->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $evaluation->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $evaluation->procurement_tender_id ===
                (int) $procurementTender->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Load Relationships
        |--------------------------------------------------------------------------
        */

        $evaluation->load([
            'project',
            'submission.tenderBidder.bidder',
            'technicalEvaluation',
        ]);


        return view(
            'procurement.commercial-evaluations.edit',
            compact(
                'project',
                'procurementTender',
                'evaluation'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementCommercialEvaluation $evaluation
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Verify Relationships
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $procurementTender->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $evaluation->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $evaluation->procurement_tender_id ===
                (int) $procurementTender->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'evaluation_date' => [
                'nullable',
                'date',
            ],

            'evaluator_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'evaluated_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'tax_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'discount_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'final_evaluated_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'price_score' => [
                'required',
                'numeric',
                'min:0',
            ],

            'maximum_price_score' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'commercial_compliance' => [
                'required',
                'in:Pending,Compliant,Partially Compliant,Non-Compliant',
            ],

            'evaluation_summary' => [
                'nullable',
                'string',
            ],

            'strengths' => [
                'nullable',
                'string',
            ],

            'weaknesses' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Draft,Under Evaluation,Completed,Approved,Rejected',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Price Score Validation
        |--------------------------------------------------------------------------
        */

        if (
            (float) $validated['price_score']
            >
            (float) $validated['maximum_price_score']
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'price_score' =>
                        'Price score cannot exceed maximum price score.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Result
        |--------------------------------------------------------------------------
        */

        $result = $this->calculateResult(
            (float) $validated['price_score'],
            (float) $validated['maximum_price_score'],
            $validated['commercial_compliance'],
            $validated['status']
        );


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $evaluation->update([

            'evaluation_date' =>
                $validated['evaluation_date']
                ?? null,

            'evaluator_name' =>
                $validated['evaluator_name']
                ?? null,

            'evaluated_amount' =>
                $validated['evaluated_amount'],

            'tax_amount' =>
                $validated['tax_amount']
                ?? 0,

            'discount_amount' =>
                $validated['discount_amount']
                ?? 0,

            'final_evaluated_amount' =>
                $validated['final_evaluated_amount'],

            'price_score' =>
                $validated['price_score'],

            'maximum_price_score' =>
                $validated['maximum_price_score'],

            'commercial_compliance' =>
                $validated['commercial_compliance'],

            'result' =>
                $result,

            'evaluation_summary' =>
                $validated['evaluation_summary']
                ?? null,

            'strengths' =>
                $validated['strengths']
                ?? null,

            'weaknesses' =>
                $validated['weaknesses']
                ?? null,

            'remarks' =>
                $validated['remarks']
                ?? null,

            'status' =>
                $validated['status'],

            'evaluated_at' =>
                in_array(
                    $validated['status'],
                    [
                        'Completed',
                        'Approved',
                    ],
                    true
                )
                    ? now()
                    : null,

            'updated_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.projects.procurement.tenders.commercial-evaluations.show',
                [
                    'project' =>
                        $project,

                    'procurementTender' =>
                        $procurementTender,

                    'evaluation' =>
                        $evaluation,
                ]
            )
            ->with(
                'success',
                'Commercial evaluation updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementCommercialEvaluation $evaluation
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Verify Relationships
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $procurementTender->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $evaluation->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $evaluation->procurement_tender_id ===
                (int) $procurementTender->id,
            404
        );


        $evaluation->delete();


        return redirect()
            ->route(
                'admin.projects.procurement.tenders.commercial-evaluations.index',
                [
                    'project' =>
                        $project,

                    'procurementTender' =>
                        $procurementTender,
                ]
            )
            ->with(
                'success',
                'Commercial evaluation deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Evaluation Number
    |--------------------------------------------------------------------------
    */

    private function generateEvaluationNumber(): string
    {
        $year = now()->format('Y');

        $prefix = 'CE-' . $year . '-';


        $lastEvaluation =
            ProcurementCommercialEvaluation::query()
                ->where(
                    'evaluation_number',
                    'like',
                    $prefix . '%'
                )
                ->orderByDesc('id')
                ->first();


        if (!$lastEvaluation) {

            $nextNumber = 1;

        } else {

            $lastNumber =
                (int) str_replace(
                    $prefix,
                    '',
                    $lastEvaluation->evaluation_number
                );

            $nextNumber = $lastNumber + 1;
        }


        return $prefix
            . str_pad(
                $nextNumber,
                4,
                '0',
                STR_PAD_LEFT
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Calculate Result
    |--------------------------------------------------------------------------
    */

    private function calculateResult(
        float $priceScore,
        float $maximumPriceScore,
        string $compliance,
        string $status
    ): string {

        if (
            !in_array(
                $status,
                [
                    'Completed',
                    'Approved',
                ],
                true
            )
        ) {

            return 'Pending';
        }


        if ($compliance === 'Non-Compliant') {

            return 'Not Qualified';
        }


        if ($compliance !== 'Compliant') {

            return 'Not Qualified';
        }


        if ($priceScore < 0) {

            return 'Not Qualified';
        }


        return 'Qualified';
    }
}