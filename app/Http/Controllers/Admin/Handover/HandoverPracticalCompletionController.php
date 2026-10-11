<?php

namespace App\Http\Controllers\Admin\Handover;

use App\Http\Controllers\Controller;
use App\Models\HandoverPracticalCompletion;
use App\Models\HandoverProject;
use App\Models\Project;
use App\Services\CommissioningAuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HandoverPracticalCompletionController extends Controller
{
    /**
     * Practical Completion dashboard.
     */
    public function index(Project $project): View|RedirectResponse
    {
        $handover = $this->getHandover($project);

        if (!$handover) {
            return redirect()
                ->route('admin.projects.handover.index', $project)
                ->with('error', 'Please start Handover & Closeout first.');
        }

        $completion = HandoverPracticalCompletion::where(
                'handover_project_id',
                $handover->id
            )
            ->latest('id')
            ->first();

        /*
         * Create the Practical Completion record automatically
         * when the module is opened for the first time.
         */
        if (!$completion) {
            $completion = HandoverPracticalCompletion::create([
                'project_id'         => $project->id,
                'handover_project_id' => $handover->id,
                'completion_no'      => $this->generateCompletionNo($project),
                'status'             => 'Not Ready',
                'readiness_percentage' => 0,
                'created_by'         => auth()->id(),
                'updated_by'         => auth()->id(),
            ]);

            CommissioningAuditLogService::created(
                $completion,
                'Practical Completion record created.'
            );
        }

        $this->evaluateReadiness($completion);

        $completion->refresh();

        $requirements = $handover->requirements()
            ->orderBy('id')
            ->get();

        $mandatoryRequirements = $requirements->where(
            'is_mandatory',
            true
        );

        $completedRequirements = $mandatoryRequirements
            ->whereIn('status', ['Completed', 'Waived'])
            ->count();

        $totalRequirements = $mandatoryRequirements->count();

        $pendingRequirements = $mandatoryRequirements
            ->whereNotIn('status', ['Completed', 'Waived'])
            ->count();

        $readinessPercentage = $totalRequirements > 0
            ? round(
                ($completedRequirements / $totalRequirements) * 100,
                2
            )
            : 0;

        return view(
            'admin.handover.practical-completion.index',
            compact(
                'project',
                'handover',
                'completion',
                'requirements',
                'mandatoryRequirements',
                'completedRequirements',
                'totalRequirements',
                'pendingRequirements',
                'readinessPercentage'
            )
        );
    }

    /**
     * Submit Practical Completion.
     */
    public function submit(
        Project $project,
        HandoverPracticalCompletion $completion
    ): RedirectResponse {
        $this->validateCompletionProject($project, $completion);

        $this->evaluateReadiness($completion);

        $completion->refresh();

        if ($completion->status !== 'Ready for Submission') {
            return back()->with(
                'error',
                'Practical Completion is not ready for submission. All mandatory handover requirements must be completed or waived.'
            );
        }

        DB::transaction(function () use ($completion) {

            $completion->update([
                'status'         => 'Submitted',
                'submitted_date' => now()->toDateString(),
                'submitted_by'   => auth()->id(),
                'submitted_at'   => now(),
                'updated_by'     => auth()->id(),
            ]);

            CommissioningAuditLogService::submitted(
                $completion,
                'Practical Completion submitted for review.'
            );
        });

        return back()->with(
            'success',
            'Practical Completion submitted successfully.'
        );
    }

    /**
     * Start review.
     */
    public function review(
        Project $project,
        HandoverPracticalCompletion $completion
    ): RedirectResponse {
        $this->validateCompletionProject($project, $completion);

        if ($completion->status !== 'Submitted') {
            return back()->with(
                'error',
                'Only submitted Practical Completion records can be reviewed.'
            );
        }

        DB::transaction(function () use ($completion) {

            $completion->update([
                'status'      => 'Under Review',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'updated_by'  => auth()->id(),
            ]);

            CommissioningAuditLogService::action(
                'review_started',
                $completion,
                'Practical Completion review started.'
            );
        });

        return back()->with(
            'success',
            'Practical Completion is now under review.'
        );
    }

    /**
     * Approve Practical Completion.
     */
    public function approve(
        Project $project,
        HandoverPracticalCompletion $completion
    ): RedirectResponse {
        $this->validateCompletionProject($project, $completion);

        if ($completion->status !== 'Under Review') {
            return back()->with(
                'error',
                'Only Practical Completion records under review can be approved.'
            );
        }

        /*
         * Re-check readiness before approval.
         * This prevents approval if a mandatory requirement
         * was changed after submission.
         */
        $this->evaluateReadiness($completion);

        $completion->refresh();

        if ((float) $completion->readiness_percentage < 100) {
            return back()->with(
                'error',
                'Practical Completion cannot be approved because mandatory handover requirements are incomplete.'
            );
        }

        DB::transaction(function () use ($completion) {

            $completion->update([
                'status'        => 'Approved',
                'approved_date' => now()->toDateString(),
                'approved_by'   => auth()->id(),
                'approved_at'   => now(),
                'updated_by'    => auth()->id(),
            ]);

            /*
             * Update main handover status.
             */
            $handover = $completion->handover;

            if ($handover) {
                $handover->update([
                    'status'    => 'Approved',
                    'updated_by' => auth()->id(),
                ]);
            }

            CommissioningAuditLogService::approved(
                $completion,
                'Practical Completion approved.'
            );
        });

        return back()->with(
            'success',
            'Practical Completion approved successfully.'
        );
    }

    /**
     * Reject Practical Completion.
     */
    public function reject(
        Request $request,
        Project $project,
        HandoverPracticalCompletion $completion
    ): RedirectResponse {
        $this->validateCompletionProject($project, $completion);

        if ($completion->status !== 'Under Review') {
            return back()->with(
                'error',
                'Only Practical Completion records under review can be rejected.'
            );
        }

        $validated = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        DB::transaction(function () use (
            $completion,
            $validated
        ) {

            $completion->update([
                'status'           => 'Rejected',
                'rejection_reason' => $validated['rejection_reason'],
                'updated_by'       => auth()->id(),
            ]);

            CommissioningAuditLogService::rejected(
                $completion,
                'Practical Completion rejected. Reason: ' .
                $validated['rejection_reason']
            );
        });

        return back()->with(
            'success',
            'Practical Completion rejected and returned for rectification.'
        );
    }

    /**
     * Update completion statement / remarks.
     *
     * Editing is allowed before submission and after rejection.
     */
    public function update(
        Request $request,
        Project $project,
        HandoverPracticalCompletion $completion
    ): RedirectResponse {
        $this->validateCompletionProject($project, $completion);

        if (!in_array(
            $completion->status,
            ['Not Ready', 'Ready for Submission', 'Rejected']
        )) {
            return back()->with(
                'error',
                'Practical Completion cannot be edited in its current status.'
            );
        }

        $validated = $request->validate([
            'planned_date' => [
                'nullable',
                'date',
            ],
            'completion_statement' => [
                'nullable',
                'string',
                'max:10000',
            ],
            'remarks' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $oldValues = $completion->getAttributes();

        $completion->update([
            'planned_date'        => $validated['planned_date'] ?? null,
            'completion_statement' =>
                $validated['completion_statement'] ?? null,
            'remarks'             => $validated['remarks'] ?? null,
            'updated_by'          => auth()->id(),

            /*
             * Rejected record goes back into readiness evaluation.
             */
            'status'              => 'Not Ready',
            'rejection_reason'    => null,
        ]);

        $this->evaluateReadiness($completion);

        CommissioningAuditLogService::updated(
            $completion,
            $oldValues,
            'Practical Completion details updated.'
        );

        return back()->with(
            'success',
            'Practical Completion details updated.'
        );
    }

    /**
     * Evaluate Practical Completion readiness from
     * existing Handover Requirements.
     */
    protected function evaluateReadiness(
        HandoverPracticalCompletion $completion
    ): void {
        $handover = $completion->handover;

        if (!$handover) {
            return;
        }

        $requirements = $handover->requirements()
            ->where('is_mandatory', true)
            ->get();

        $total = $requirements->count();

        if ($total === 0) {
            $percentage = 0;
            $ready = false;
        } else {

            $completed = $requirements
                ->whereIn(
                    'status',
                    ['Completed', 'Waived']
                )
                ->count();

            $percentage = round(
                ($completed / $total) * 100,
                2
            );

            $ready = $completed === $total;
        }

        /*
         * Do not overwrite workflow statuses such as
         * Submitted / Under Review / Approved.
         */
        if (in_array(
            $completion->status,
            ['Submitted', 'Under Review', 'Approved']
        )) {
            $completion->update([
                'readiness_percentage' => $percentage,
                'requirements_complete' => $ready,
                'updated_by' => auth()->id(),
            ]);

            return;
        }

        $completion->update([
            'readiness_percentage' => $percentage,

            /*
             * These flags provide a quick snapshot in DB.
             */
            'commissioning_complete' =>
                $this->requirementCompleted(
                    $requirements,
                    'COMM-COMP'
                ),

            'snagging_complete' =>
                $this->requirementCompleted(
                    $requirements,
                    'SNAG-COMP'
                ),

            'defects_complete' =>
                $this->requirementCompleted(
                    $requirements,
                    'DEFECT-COMP'
                ),

            'documents_complete' =>
                $this->requirementsCompleted(
                    $requirements,
                    [
                        'OM-MANUAL',
                        'AS-BUILT',
                        'WARRANTY',
                        'AUTH-APPROVAL',
                        'TRAINING',
                    ]
                ),

            'requirements_complete' => $ready,

            'status' => $ready
                ? 'Ready for Submission'
                : 'Not Ready',

            'updated_by' => auth()->id(),
        ]);
    }

    /**
     * Check one requirement by code.
     */
    protected function requirementCompleted(
        $requirements,
        string $code
    ): bool {
        $requirement = $requirements->firstWhere(
            'requirement_code',
            $code
        );

        if (!$requirement) {
            return false;
        }

        return in_array(
            $requirement->status,
            ['Completed', 'Waived']
        );
    }

    /**
     * Check multiple requirements.
     */
    protected function requirementsCompleted(
        $requirements,
        array $codes
    ): bool {
        foreach ($codes as $code) {

            if (!$this->requirementCompleted(
                $requirements,
                $code
            )) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get project handover.
     */
    protected function getHandover(
        Project $project
    ): ?HandoverProject {
        return HandoverProject::where(
            'project_id',
            $project->id
        )->latest('id')->first();
    }

    /**
     * Validate route model belongs to project.
     */
    protected function validateCompletionProject(
        Project $project,
        HandoverPracticalCompletion $completion
    ): void {
        abort_unless(
            (int) $completion->project_id === (int) $project->id,
            404
        );
    }

    /**
     * Generate completion number.
     */
    protected function generateCompletionNo(
        Project $project
    ): string {
        $count = HandoverPracticalCompletion::where(
            'project_id',
            $project->id
        )->count() + 1;

        return 'PC-' .
            str_pad(
                $project->id,
                4,
                '0',
                STR_PAD_LEFT
            ) .
            '-' .
            str_pad(
                $count,
                3,
                '0',
                STR_PAD_LEFT
            );
    }
}