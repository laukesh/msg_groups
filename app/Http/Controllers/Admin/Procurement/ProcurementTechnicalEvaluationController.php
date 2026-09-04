<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementTender;
use App\Models\ProcurementTenderSubmission;
use App\Models\ProcurementTechnicalEvaluation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProcurementTechnicalEvaluationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(
        ProcurementTender $procurementTender
    ): View {

        $evaluations = $procurementTender
            ->technicalEvaluations()
            ->with('submission.tenderBidder.bidder')
            ->latest()
            ->get();

        return view(
            'procurement.technical-evaluations.index',
            compact(
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
        ProcurementTender $procurementTender
    ): View {

        /*
         * Submissions which already have
         * a technical evaluation are excluded.
         */
        $evaluatedSubmissionIds = $procurementTender
            ->technicalEvaluations()
            ->pluck('procurement_tender_submission_id');


        /*
         * Only submitted / under review / accepted
         * submissions are available.
         */
        $availableSubmissions = $procurementTender
            ->submissions()
            ->with('tenderBidder.bidder')
            ->whereNotIn(
                'id',
                $evaluatedSubmissionIds
            )
            ->whereIn(
                'submission_status',
                [
                    'Submitted',
                    'Under Review',
                    'Accepted',
                ]
            )
            ->get();


        return view(
            'procurement.technical-evaluations.create',
            compact(
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
        ProcurementTender $procurementTender
    ): RedirectResponse {

        $validated = $request->validate([

            'procurement_tender_submission_id' => [
                'required',
                'integer',
                'exists:procurement_tender_submissions,id',
            ],

            /*
             * evaluation_number is intentionally NOT accepted
             * from the form anymore.
             */

            'evaluation_date' => [
                'nullable',
                'date',
            ],

            'evaluator_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'technical_score' => [
                'required',
                'numeric',
                'min:0',
            ],

            'maximum_score' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'passing_score' => [
                'required',
                'numeric',
                'min:0',
            ],

            'technical_compliance' => [
                'required',
                'in:Pending,Compliant,Partially Compliant,Non-Compliant',
            ],

            'strengths' => [
                'nullable',
                'string',
            ],

            'weaknesses' => [
                'nullable',
                'string',
            ],

            'evaluation_summary' => [
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
        | Verify Submission belongs to Tender
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
        | Prevent Duplicate Technical Evaluation
        |--------------------------------------------------------------------------
        */

        if (
            $submission
                ->technicalEvaluation()
                ->exists()
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'procurement_tender_submission_id' =>
                        'This submission already has a technical evaluation.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Score
        |--------------------------------------------------------------------------
        */

        if (
            $validated['technical_score']
            >
            $validated['maximum_score']
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'technical_score' =>
                        'Technical score cannot exceed maximum score.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Result
        |--------------------------------------------------------------------------
        */

        $result = $this->calculateResult(
            $validated['technical_score'],
            $validated['passing_score'],
            $validated['technical_compliance'],
            $validated['status']
        );


        /*
        |--------------------------------------------------------------------------
        | Generate Evaluation Number
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | TE-2026-0001
        | TE-2026-0002
        | TE-2026-0003
        |
        */

        $evaluationNumber = $this->generateEvaluationNumber();


        /*
        |--------------------------------------------------------------------------
        | Create Technical Evaluation
        |--------------------------------------------------------------------------
        */

        $evaluation = ProcurementTechnicalEvaluation::create([

            'procurement_tender_id' =>
                $procurementTender->id,

            'procurement_tender_submission_id' =>
                $submission->id,

            'evaluation_number' =>
                $evaluationNumber,

            'evaluation_date' =>
                $validated['evaluation_date']
                ?? now()->toDateString(),

            'evaluator_id' =>
                auth()->id(),

            'evaluator_name' =>
                $validated['evaluator_name']
                ?? auth()->user()?->name,

            'technical_score' =>
                $validated['technical_score'],

            'maximum_score' =>
                $validated['maximum_score'],

            'passing_score' =>
                $validated['passing_score'],

            'result' =>
                $result,

            'technical_compliance' =>
                $validated['technical_compliance'],

            'strengths' =>
                $validated['strengths']
                ?? null,

            'weaknesses' =>
                $validated['weaknesses']
                ?? null,

            'evaluation_summary' =>
                $validated['evaluation_summary']
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

            'created_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.procurement.tenders.technical-evaluations.show',
                [
                    'procurementTender' =>
                        $procurementTender,

                    'evaluation' =>
                        $evaluation,
                ]
            )
            ->with(
                'success',
                'Technical evaluation created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        ProcurementTender $procurementTender,
        ProcurementTechnicalEvaluation $evaluation
    ): View {

        abort_unless(
            $evaluation->procurement_tender_id
                === $procurementTender->id,
            404
        );


        $evaluation->load([
            'tender',
            'submission.tenderBidder.bidder',
        ]);


        return view(
            'procurement.technical-evaluations.show',
            compact(
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
        ProcurementTender $procurementTender,
        ProcurementTechnicalEvaluation $evaluation
    ): View {

        abort_unless(
            $evaluation->procurement_tender_id
                === $procurementTender->id,
            404
        );


        $evaluation->load([
            'submission.tenderBidder.bidder',
        ]);


        return view(
            'procurement.technical-evaluations.edit',
            compact(
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
        ProcurementTender $procurementTender,
        ProcurementTechnicalEvaluation $evaluation
    ): RedirectResponse {

        abort_unless(
            $evaluation->procurement_tender_id
                === $procurementTender->id,
            404
        );


        $validated = $request->validate([

            /*
             * Evaluation number is NOT editable.
             */

            'evaluation_date' => [
                'nullable',
                'date',
            ],

            'evaluator_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'technical_score' => [
                'required',
                'numeric',
                'min:0',
            ],

            'maximum_score' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'passing_score' => [
                'required',
                'numeric',
                'min:0',
            ],

            'technical_compliance' => [
                'required',
                'in:Pending,Compliant,Partially Compliant,Non-Compliant',
            ],

            'strengths' => [
                'nullable',
                'string',
            ],

            'weaknesses' => [
                'nullable',
                'string',
            ],

            'evaluation_summary' => [
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
        | Validate Score
        |--------------------------------------------------------------------------
        */

        if (
            $validated['technical_score']
            >
            $validated['maximum_score']
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'technical_score' =>
                        'Technical score cannot exceed maximum score.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Result
        |--------------------------------------------------------------------------
        */

        $result = $this->calculateResult(
            $validated['technical_score'],
            $validated['passing_score'],
            $validated['technical_compliance'],
            $validated['status']
        );


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        |
        | evaluation_number is deliberately NOT changed.
        |
        */

        $evaluation->update([

            'evaluation_date' =>
                $validated['evaluation_date']
                ?? null,

            'evaluator_name' =>
                $validated['evaluator_name']
                ?? null,

            'technical_score' =>
                $validated['technical_score'],

            'maximum_score' =>
                $validated['maximum_score'],

            'passing_score' =>
                $validated['passing_score'],

            'result' =>
                $result,

            'technical_compliance' =>
                $validated['technical_compliance'],

            'strengths' =>
                $validated['strengths']
                ?? null,

            'weaknesses' =>
                $validated['weaknesses']
                ?? null,

            'evaluation_summary' =>
                $validated['evaluation_summary']
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
                'admin.procurement.tenders.technical-evaluations.show',
                [
                    'procurementTender' =>
                        $procurementTender,

                    'evaluation' =>
                        $evaluation,
                ]
            )
            ->with(
                'success',
                'Technical evaluation updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        ProcurementTender $procurementTender,
        ProcurementTechnicalEvaluation $evaluation
    ): RedirectResponse {

        abort_unless(
            $evaluation->procurement_tender_id
                === $procurementTender->id,
            404
        );


        $evaluation->delete();


        return redirect()
            ->route(
                'admin.procurement.tenders.technical-evaluations.index',
                $procurementTender
            )
            ->with(
                'success',
                'Technical evaluation deleted successfully.'
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


        $lastEvaluation = ProcurementTechnicalEvaluation::query()
            ->where(
                'evaluation_number',
                'like',
                "TE-{$year}-%"
            )
            ->orderByDesc('id')
            ->first();


        if (!$lastEvaluation) {

            $nextNumber = 1;

        } else {

            $lastNumber = (int) substr(
                $lastEvaluation->evaluation_number,
                strrpos(
                    $lastEvaluation->evaluation_number,
                    '-'
                ) + 1
            );

            $nextNumber = $lastNumber + 1;
        }


        return sprintf(
            'TE-%s-%04d',
            $year,
            $nextNumber
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Calculate Result
    |--------------------------------------------------------------------------
    */

    private function calculateResult(
        float $technicalScore,
        float $passingScore,
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


        if (
            $compliance === 'Non-Compliant'
        ) {

            return 'Not Qualified';
        }


        if (
            $compliance !== 'Compliant'
        ) {

            return 'Not Qualified';
        }


        if (
            $technicalScore < $passingScore
        ) {

            return 'Not Qualified';
        }


        return 'Qualified';
    }
}