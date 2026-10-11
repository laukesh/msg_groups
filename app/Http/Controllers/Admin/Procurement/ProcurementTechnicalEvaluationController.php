<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProcurementTender;
use App\Models\ProcurementTenderSubmission;
use App\Models\ProcurementTechnicalEvaluation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProcurementTechnicalEvaluationController extends Controller
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
        $this->validateTenderProject($project, $procurementTender);

        $evaluations = $procurementTender
            ->technicalEvaluations()
            ->where('project_id', $project->id)
            ->with([
                'submission.tenderBidder.bidder',
            ])
            ->latest()
            ->get();

        return view(
            'procurement.technical-evaluations.index',
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
        $this->validateTenderProject($project, $procurementTender);

        $evaluatedSubmissionIds = ProcurementTechnicalEvaluation::query()
            ->where('project_id', $project->id)
            ->where('procurement_tender_id', $procurementTender->id)
            ->pluck('procurement_tender_submission_id')
            ->toArray();

        $availableSubmissions = $procurementTender
            ->submissions()
            ->with([
                'tenderBidder.bidder',
            ])
            ->where('project_id', $project->id)
            ->whereNotIn(
                'id',
                $evaluatedSubmissionIds
            )
            ->whereIn('submission_status', [
                'Submitted',
                'Under Review',
                'Accepted',
            ])
            ->latest()
            ->get();

        return view(
            'procurement.technical-evaluations.create',
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
        $this->validateTenderProject($project, $procurementTender);

        $validated = $request->validate([
            'procurement_tender_submission_id' => [
                'required',
                'integer',
                'exists:procurement_tender_submissions,id',
            ],

            'evaluation_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'evaluation_date' => [
                'required',
                'date',
            ],

            'evaluator_id' => [
                'nullable',
                'integer',
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
                'nullable',
                'string',
                'max:100',
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
                'string',
                'max:50',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validate Submission
        |--------------------------------------------------------------------------
        */

        $submission = ProcurementTenderSubmission::query()
            ->where('id', $validated['procurement_tender_submission_id'])
            ->where('project_id', $project->id)
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            )
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Evaluation
        |--------------------------------------------------------------------------
        */

        $existingEvaluation = ProcurementTechnicalEvaluation::query()
            ->where('project_id', $project->id)
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            )
            ->where(
                'procurement_tender_submission_id',
                $submission->id
            )
            ->first();

        if ($existingEvaluation) {
            return redirect()
                ->route(
                    'admin.projects.procurement.tenders.technical-evaluations.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                        'evaluation' => $existingEvaluation,
                    ]
                )
                ->with(
                    'warning',
                    'Technical evaluation already exists for this submission.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Score Validation
        |--------------------------------------------------------------------------
        */

        if (
            (float) $validated['technical_score']
            >
            (float) $validated['maximum_score']
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'technical_score' =>
                        'Technical score cannot be greater than maximum score.',
                ]);
        }

        if (
            (float) $validated['passing_score']
            >
            (float) $validated['maximum_score']
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'passing_score' =>
                        'Passing score cannot be greater than maximum score.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Result
        |--------------------------------------------------------------------------
        */

        $result = $this->calculateResult(
            (float) $validated['technical_score'],
            (float) $validated['passing_score']
        );

        /*
        |--------------------------------------------------------------------------
        | Evaluation Number
        |--------------------------------------------------------------------------
        */

        $evaluationNumber = !empty(
            $validated['evaluation_number']
        )
            ? $validated['evaluation_number']
            : $this->generateEvaluationNumber();

        /*
        |--------------------------------------------------------------------------
        | Create Evaluation
        |--------------------------------------------------------------------------
        */

        $evaluation = ProcurementTechnicalEvaluation::create([
            'project_id' => $project->id,

            'procurement_tender_id' =>
                $procurementTender->id,

            'procurement_tender_submission_id' =>
                $submission->id,

            'evaluation_number' =>
                $evaluationNumber,

            'evaluation_date' =>
                $validated['evaluation_date'],

            'evaluator_id' =>
                $validated['evaluator_id'] ?? null,

            'evaluator_name' =>
                $validated['evaluator_name'] ?? null,

            'technical_score' =>
                $validated['technical_score'],

            'maximum_score' =>
                $validated['maximum_score'],

            'passing_score' =>
                $validated['passing_score'],

            'result' =>
                $result,

            'technical_compliance' =>
                $validated['technical_compliance'] ?? null,

            'strengths' =>
                $validated['strengths'] ?? null,

            'weaknesses' =>
                $validated['weaknesses'] ?? null,

            'evaluation_summary' =>
                $validated['evaluation_summary'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'submission_status' =>
                $validated['status'],

            'evaluated_at' =>
                now(),

            'created_by' =>
                auth()->id(),

            'updated_by' =>
                auth()->id(),
        ]);

        return redirect()
            ->route(
                'admin.projects.procurement.tenders.technical-evaluations.show',
                [
                    'project' => $project,
                    'procurementTender' => $procurementTender,
                    'evaluation' => $evaluation,
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
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementTechnicalEvaluation $evaluation
    ): View {
        $this->validateTenderProject(
            $project,
            $procurementTender
        );

        $this->validateEvaluation(
            $project,
            $procurementTender,
            $evaluation
        );

        $evaluation->load([
            'submission.tenderBidder.bidder',
            'commercialEvaluation',
            'bidComparisonItems',
        ]);

        return view(
            'procurement.technical-evaluations.show',
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
        ProcurementTechnicalEvaluation $evaluation
    ): View {
        $this->validateTenderProject(
            $project,
            $procurementTender
        );

        $this->validateEvaluation(
            $project,
            $procurementTender,
            $evaluation
        );

        $evaluation->load([
            'submission.tenderBidder.bidder',
        ]);

        return view(
            'procurement.technical-evaluations.edit',
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
        ProcurementTechnicalEvaluation $evaluation
    ): RedirectResponse {
        $this->validateTenderProject(
            $project,
            $procurementTender
        );

        $this->validateEvaluation(
            $project,
            $procurementTender,
            $evaluation
        );

        $validated = $request->validate([
            'evaluation_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'evaluation_date' => [
                'required',
                'date',
            ],

            'evaluator_id' => [
                'nullable',
                'integer',
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
                'nullable',
                'string',
                'max:100',
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
                'string',
                'max:50',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Score Validation
        |--------------------------------------------------------------------------
        */

        if (
            (float) $validated['technical_score']
            >
            (float) $validated['maximum_score']
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'technical_score' =>
                        'Technical score cannot be greater than maximum score.',
                ]);
        }

        if (
            (float) $validated['passing_score']
            >
            (float) $validated['maximum_score']
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'passing_score' =>
                        'Passing score cannot be greater than maximum score.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Result
        |--------------------------------------------------------------------------
        */

        $result = $this->calculateResult(
            (float) $validated['technical_score'],
            (float) $validated['passing_score']
        );

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $evaluation->update([
            'evaluation_number' =>
                $validated['evaluation_number']
                    ?? $evaluation->evaluation_number,

            'evaluation_date' =>
                $validated['evaluation_date'],

            'evaluator_id' =>
                $validated['evaluator_id'] ?? null,

            'evaluator_name' =>
                $validated['evaluator_name'] ?? null,

            'technical_score' =>
                $validated['technical_score'],

            'maximum_score' =>
                $validated['maximum_score'],

            'passing_score' =>
                $validated['passing_score'],

            'result' =>
                $result,

            'technical_compliance' =>
                $validated['technical_compliance'] ?? null,

            'strengths' =>
                $validated['strengths'] ?? null,

            'weaknesses' =>
                $validated['weaknesses'] ?? null,

            'evaluation_summary' =>
                $validated['evaluation_summary'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'submission_status' =>
                $validated['status'],

            'evaluated_at' =>
                now(),

            'updated_by' =>
                auth()->id(),
        ]);

        return redirect()
            ->route(
                'admin.projects.procurement.tenders.technical-evaluations.show',
                [
                    'project' => $project,
                    'procurementTender' => $procurementTender,
                    'evaluation' => $evaluation,
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
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementTechnicalEvaluation $evaluation
    ): RedirectResponse {
        $this->validateTenderProject(
            $project,
            $procurementTender
        );

        $this->validateEvaluation(
            $project,
            $procurementTender,
            $evaluation
        );

        $evaluation->delete();

        return redirect()
            ->route(
                'admin.projects.procurement.tenders.technical-evaluations.index',
                [
                    'project' => $project,
                    'procurementTender' => $procurementTender,
                ]
            )
            ->with(
                'success',
                'Technical evaluation deleted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Validate Tender → Project
    |--------------------------------------------------------------------------
    */

    private function validateTenderProject(
        Project $project,
        ProcurementTender $procurementTender
    ): void {
        abort_unless(
            (int) $procurementTender->project_id ===
            (int) $project->id,
            404
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Validate Evaluation → Project → Tender
    |--------------------------------------------------------------------------
    */

    private function validateEvaluation(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementTechnicalEvaluation $evaluation
    ): void {
        abort_unless(
            (int) $evaluation->project_id ===
            (int) $project->id,
            404
        );

        abort_unless(
            (int) $evaluation->procurement_tender_id ===
            (int) $procurementTender->id,
            404
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
            ->whereYear(
                'created_at',
                $year
            )
            ->orderByDesc('id')
            ->first();

        $nextNumber = $lastEvaluation
            ? ((int) substr(
                $lastEvaluation->evaluation_number,
                -4
            )) + 1
            : 1;

        return 'TE-' .
            $year .
            '-' .
            str_pad(
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
        float $technicalScore,
        float $passingScore
    ): string {
        return $technicalScore >= $passingScore
            ? 'Pass'
            : 'Fail';
    }
}