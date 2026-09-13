<?php

namespace App\Http\Controllers\Admin\Handover;

use App\Http\Controllers\Controller;
use App\Models\HandoverProject;
use App\Models\HandoverRequirement;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HandoverRequirementController extends Controller
{
    /**
     * List requirements.
     */
    public function index(
        Request $request,
        Project $project
    ): View|RedirectResponse {

        $handover = HandoverProject::where(
            'project_id',
            $project->id
        )
        ->latest('id')
        ->first();

        if (!$handover) {

            return redirect()
                ->route(
                    'admin.projects.handover.index',
                    $project
                )
                ->with(
                    'error',
                    'Please start the Handover process before managing requirements.'
                );
        }

        $query = HandoverRequirement::where(
            'handover_project_id',
            $handover->id
        );

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'title',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'requirement_code',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'requirement_type',
                    'like',
                    "%{$search}%"
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        if ($request->filled('requirement_type')) {

            $query->where(
                'requirement_type',
                $request->requirement_type
            );
        }


        if ($request->filled('priority')) {

            $query->where(
                'priority',
                $request->priority
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Requirements
        |--------------------------------------------------------------------------
        */

        $requirements = $query
            ->with([
                'responsibleUser',
                'completedBy',
            ])
            ->orderByRaw(
                "FIELD(priority, 'Critical', 'High', 'Medium', 'Low')"
            )
            ->orderBy('id')
            ->paginate(20)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | KPI
        |--------------------------------------------------------------------------
        */

        $baseQuery = HandoverRequirement::where(
            'handover_project_id',
            $handover->id
        );

        $totalRequirements = (clone $baseQuery)->count();

        $completedRequirements = (clone $baseQuery)
            ->where('status', 'Completed')
            ->count();

        $pendingRequirements = (clone $baseQuery)
            ->where('status', 'Pending')
            ->count();

        $inProgressRequirements = (clone $baseQuery)
            ->where('status', 'In Progress')
            ->count();

        $rejectedRequirements = (clone $baseQuery)
            ->where('status', 'Rejected')
            ->count();

        $waivedRequirements = (clone $baseQuery)
            ->where('status', 'Waived')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Dropdown Values
        |--------------------------------------------------------------------------
        */

        $types = [
            'Construction',
            'Commissioning',
            'Snagging',
            'Defects',
            'Documentation',
            'O&M',
            'As-Built',
            'Warranty',
            'Authority Approval',
            'Training',
            'Asset',
            'Financial',
            'Certificate',
            'Other',
        ];

        $statuses = [
            'Pending',
            'In Progress',
            'Submitted',
            'Under Review',
            'Completed',
            'Rejected',
            'Waived',
        ];

        $priorities = [
            'Low',
            'Medium',
            'High',
            'Critical',
        ];


        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        $users = \App\Models\User::query()
            ->orderBy('name')
            ->get();


        return view(
            'admin.handover.requirements.index',
            compact(
                'project',
                'handover',
                'requirements',
                'totalRequirements',
                'completedRequirements',
                'pendingRequirements',
                'inProgressRequirements',
                'rejectedRequirements',
                'waivedRequirements',
                'types',
                'statuses',
                'priorities',
                'users'
            )
        );
    }


    /**
     * Store requirement.
     */
    public function store(
        Request $request,
        Project $project
    ): RedirectResponse {

        $handover = $this->getHandover($project);

        if (!$handover) {

            return back()
                ->with('error', 'Handover process has not been started.');
        }


        $validated = $request->validate([

            'requirement_code' => [
                'required',
                'string',
                'max:80',
            ],

            'requirement_type' => [
                'required',
                'in:Construction,Commissioning,Snagging,Defects,Documentation,O&M,As-Built,Warranty,Authority Approval,Training,Asset,Financial,Certificate,Other',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'source_module' => [
                'nullable',
                'string',
                'max:100',
            ],

            'source_id' => [
                'nullable',
                'integer',
            ],

            'responsible_user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $exists = HandoverRequirement::where(
            'handover_project_id',
            $handover->id
        )
        ->where(
            'requirement_code',
            $validated['requirement_code']
        )
        ->exists();

        if ($exists) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Requirement code already exists for this handover.'
                );
        }


        HandoverRequirement::create([

            'handover_project_id' =>
                $handover->id,

            'project_id' =>
                $project->id,

            'requirement_code' =>
                $validated['requirement_code'],

            'requirement_type' =>
                $validated['requirement_type'],

            'title' =>
                $validated['title'],

            'description' =>
                $validated['description'] ?? null,

            'source_module' =>
                $validated['source_module'] ?? null,

            'source_id' =>
                $validated['source_id'] ?? null,

            'responsible_user_id' =>
                $validated['responsible_user_id'] ?? null,

            'due_date' =>
                $validated['due_date'] ?? null,

            'priority' =>
                $validated['priority'],

            'is_mandatory' =>
                $request->boolean('is_mandatory'),

            'status' =>
                'Pending',

            'remarks' =>
                $validated['remarks'] ?? null,

            'created_by' =>
                Auth::id(),

            'updated_by' =>
                Auth::id(),

        ]);


        return redirect()
            ->route(
                'admin.projects.handover.requirements.index',
                $project
            )
            ->with(
                'success',
                'Handover requirement added successfully.'
            );
    }


    /**
     * Update requirement.
     */
    public function update(
        Request $request,
        Project $project,
        HandoverRequirement $requirement
    ): RedirectResponse {

        $this->validateRequirementBelongsToProject(
            $requirement,
            $project
        );


        /*
        |--------------------------------------------------------------------------
        | Only editable statuses
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $requirement->status,
                [
                    'Submitted',
                    'Under Review',
                    'Completed',
                    'Waived',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'This requirement cannot be edited in its current status.'
                );
        }


        $validated = $request->validate([

            'requirement_code' => [
                'required',
                'string',
                'max:80',
            ],

            'requirement_type' => [
                'required',
                'in:Construction,Commissioning,Snagging,Defects,Documentation,O&M,As-Built,Warranty,Authority Approval,Training,Asset,Financial,Certificate,Other',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'source_module' => [
                'nullable',
                'string',
                'max:100',
            ],

            'source_id' => [
                'nullable',
                'integer',
            ],

            'responsible_user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $duplicate = HandoverRequirement::where(
            'handover_project_id',
            $requirement->handover_project_id
        )
        ->where(
            'requirement_code',
            $validated['requirement_code']
        )
        ->where(
            'id',
            '!=',
            $requirement->id
        )
        ->exists();

        if ($duplicate) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Requirement code already exists for this handover.'
                );
        }


        $requirement->update([

            'requirement_code' =>
                $validated['requirement_code'],

            'requirement_type' =>
                $validated['requirement_type'],

            'title' =>
                $validated['title'],

            'description' =>
                $validated['description'] ?? null,

            'source_module' =>
                $validated['source_module'] ?? null,

            'source_id' =>
                $validated['source_id'] ?? null,

            'responsible_user_id' =>
                $validated['responsible_user_id'] ?? null,

            'due_date' =>
                $validated['due_date'] ?? null,

            'priority' =>
                $validated['priority'],

            'is_mandatory' =>
                $request->boolean('is_mandatory'),

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                Auth::id(),

        ]);


        return back()
            ->with(
                'success',
                'Requirement updated successfully.'
            );
    }


    /**
     * Delete requirement.
     */
    public function destroy(
        Project $project,
        HandoverRequirement $requirement
    ): RedirectResponse {

        $this->validateRequirementBelongsToProject(
            $requirement,
            $project
        );


        if (
            in_array(
                $requirement->status,
                [
                    'Submitted',
                    'Under Review',
                    'Completed',
                    'Waived',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'This requirement cannot be deleted in its current status.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Protect mandatory system requirements
        |--------------------------------------------------------------------------
        */

        $systemCodes = [
            'CONST-COMP',
            'COMM-COMP',
            'SNAG-COMP',
            'DEFECT-COMP',
            'OM-MANUAL',
            'AS-BUILT',
            'WARRANTY',
            'AUTH-APPROVAL',
            'TRAINING',
            'ASSET-REG',
            'FINAL-ACCOUNT',
            'FINAL-PAYMENT',
        ];


        if (
            $requirement->is_mandatory
            &&
            in_array(
                $requirement->requirement_code,
                $systemCodes,
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'System mandatory requirements cannot be deleted.'
                );
        }


        $requirement->delete();


        return back()
            ->with(
                'success',
                'Requirement deleted successfully.'
            );
    }


    /**
     * Start requirement.
     *
     * Pending / Rejected → In Progress
     */
    public function start(
        Project $project,
        HandoverRequirement $requirement
    ): RedirectResponse {

        $this->validateRequirementBelongsToProject(
            $requirement,
            $project
        );


        if (
            !in_array(
                $requirement->status,
                [
                    'Pending',
                    'Rejected',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'Only Pending or Rejected requirements can be started.'
                );
        }


        $oldStatus = $requirement->status;

        $requirement->update([

            'status' => 'In Progress',

            'updated_by' => Auth::id(),

        ]);


        return back()
            ->with(
                'success',
                "Requirement moved from {$oldStatus} to In Progress."
            );
    }


    /**
     * Submit requirement.
     *
     * In Progress → Submitted
     */
    public function submit(
        Project $project,
        HandoverRequirement $requirement
    ): RedirectResponse {

        $this->validateRequirementBelongsToProject(
            $requirement,
            $project
        );


        if ($requirement->status !== 'In Progress') {

            return back()
                ->with(
                    'error',
                    'Only In Progress requirements can be submitted.'
                );
        }


        $requirement->update([

            'status' => 'Submitted',

            'updated_by' => Auth::id(),

        ]);


        return back()
            ->with(
                'success',
                'Requirement submitted successfully.'
            );
    }


    /**
     * Send requirement for review.
     *
     * Submitted → Under Review
     */
    public function review(
        Project $project,
        HandoverRequirement $requirement
    ): RedirectResponse {

        $this->validateRequirementBelongsToProject(
            $requirement,
            $project
        );


        if ($requirement->status !== 'Submitted') {

            return back()
                ->with(
                    'error',
                    'Only Submitted requirements can be sent for review.'
                );
        }


        $requirement->update([

            'status' => 'Under Review',

            'updated_by' => Auth::id(),

        ]);


        return back()
            ->with(
                'success',
                'Requirement moved to Under Review.'
            );
    }


    /**
     * Complete requirement.
     *
     * Under Review → Completed
     */
    public function complete(
        Project $project,
        HandoverRequirement $requirement
    ): RedirectResponse {

        $this->validateRequirementBelongsToProject(
            $requirement,
            $project
        );


        if ($requirement->status !== 'Under Review') {

            return back()
                ->with(
                    'error',
                    'Only requirements Under Review can be completed.'
                );
        }


        $requirement->update([

            'status' => 'Completed',

            'completed_by' => Auth::id(),

            'completed_at' => now(),

            'updated_by' => Auth::id(),

        ]);


        return back()
            ->with(
                'success',
                'Requirement completed successfully.'
            );
    }


    /**
     * Reject requirement.
     *
     * Under Review → Rejected
     */
    public function reject(
        Request $request,
        Project $project,
        HandoverRequirement $requirement
    ): RedirectResponse {

        $this->validateRequirementBelongsToProject(
            $requirement,
            $project
        );


        if ($requirement->status !== 'Under Review') {

            return back()
                ->with(
                    'error',
                    'Only requirements Under Review can be rejected.'
                );
        }


        $validated = $request->validate([

            'rejection_reason' => [
                'required',
                'string',
                'max:2000',
            ],

        ]);


        $remarks = trim(
            ($requirement->remarks ?? '')
            . "\n\nRejection Reason: "
            . $validated['rejection_reason']
        );


        $requirement->update([

            'status' => 'Rejected',

            'remarks' => $remarks,

            'updated_by' => Auth::id(),

        ]);


        return back()
            ->with(
                'success',
                'Requirement rejected.'
            );
    }


    /**
     * Waive requirement.
     *
     * Pending / Rejected → Waived
     */
    public function waive(
        Request $request,
        Project $project,
        HandoverRequirement $requirement
    ): RedirectResponse {

        $this->validateRequirementBelongsToProject(
            $requirement,
            $project
        );


        if (
            !in_array(
                $requirement->status,
                [
                    'Pending',
                    'Rejected',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'Only Pending or Rejected requirements can be waived.'
                );
        }


        $validated = $request->validate([

            'waiver_reason' => [
                'required',
                'string',
                'max:2000',
            ],

        ]);


        $remarks = trim(
            ($requirement->remarks ?? '')
            . "\n\nWaiver Reason: "
            . $validated['waiver_reason']
        );


        $requirement->update([

            'status' => 'Waived',

            'remarks' => $remarks,

            'completed_by' => Auth::id(),

            'completed_at' => now(),

            'updated_by' => Auth::id(),

        ]);


        return back()
            ->with(
                'success',
                'Requirement waived successfully.'
            );
    }


    /**
     * Get project's handover.
     */
    protected function getHandover(
        Project $project
    ): ?HandoverProject {

        return HandoverProject::where(
            'project_id',
            $project->id
        )
        ->latest('id')
        ->first();
    }


    /**
     * Ensure requirement belongs to project.
     */
    protected function validateRequirementBelongsToProject(
        HandoverRequirement $requirement,
        Project $project
    ): void {

        abort_unless(
            (int) $requirement->project_id === (int) $project->id,
            404
        );
    }
}