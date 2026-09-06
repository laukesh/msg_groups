<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\InvestmentDecision;
use App\Models\ProjectStatusHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display all development projects.
     */
    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | Base Project Query
        |--------------------------------------------------------------------------
        */

        $baseQuery = Project::query();


        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        // Search
        if ($request->filled('search')) {

            $search = trim($request->search);

            $baseQuery->where(function ($query) use ($search) {

                $query->where(
                    'project_number',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'project_code',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'project_name',
                    'like',
                    '%' . $search . '%'
                );

            });
        }


        // Project Stage
        if ($request->filled('project_stage')) {

            $baseQuery->where(
                'project_stage',
                $request->project_stage
            );
        }


        // Project Status
        if ($request->filled('project_status')) {

            $baseQuery->where(
                'project_status',
                $request->project_status
            );
        }


        // Project Priority
        if ($request->filled('project_priority')) {

            $baseQuery->where(
                'project_priority',
                $request->project_priority
            );
        }


        // Project Type
        if ($request->filled('project_type')) {

            $baseQuery->where(
                'project_type',
                $request->project_type
            );
        }


        // Start Date From
        if ($request->filled('start_date_from')) {

            $baseQuery->whereDate(
                'project_start_date',
                '>=',
                $request->start_date_from
            );
        }


        // Start Date To
        if ($request->filled('start_date_to')) {

            $baseQuery->whereDate(
                'project_start_date',
                '<=',
                $request->start_date_to
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Project List
        |--------------------------------------------------------------------------
        */

        $projects = (clone $baseQuery)
            ->with([
                'land',
                'feasibilityAssessment',
                'investmentDecision',
            ])
            ->latest('id')
            ->paginate(20)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Dashboard Statistics
        |--------------------------------------------------------------------------
        |
        | These counts are intentionally calculated from the complete
        | project register, not only the current pagination page.
        |
        */

        $totalProjects = Project::count();

        $activeProjects = Project::where(
            'project_status',
            'Active'
        )->count();

        $onHoldProjects = Project::where(
            'project_status',
            'On Hold'
        )->count();

        $delayedProjects = Project::where(
            'project_status',
            'Delayed'
        )->count();

        $completedProjects = Project::where(
            'project_status',
            'Completed'
        )->count();

        $draftProjects = Project::where(
            'project_status',
            'Draft'
        )->count();

        /*
        |--------------------------------------------------------------------------
        | Priority Statistics
        |--------------------------------------------------------------------------
        */

        $highPriorityProjects = Project::where(
            'project_priority',
            'High'
        )->count();

        $mediumPriorityProjects = Project::where(
            'project_priority',
            'Medium'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Filter Dropdown Options
        |--------------------------------------------------------------------------
        */

        $projectStages = Project::query()
            ->whereNotNull('project_stage')
            ->where('project_stage', '!=', '')
            ->distinct()
            ->orderBy('project_stage')
            ->pluck('project_stage');


        $projectTypes = Project::query()
            ->whereNotNull('project_type')
            ->where('project_type', '!=', '')
            ->distinct()
            ->orderBy('project_type')
            ->pluck('project_type');


        $projectPriorities = [
            'Low',
            'Medium',
            'High',
            'Critical',
        ];


        $projectStatuses = [
            'Draft',
            'Pending Approval',
            'Approved',
            'Active',
            'On Hold',
            'Delayed',
            'Completed',
            'Cancelled',
            'Closed',
        ];


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'projects.index',
            compact(
                'projects',
                'totalProjects',
                'activeProjects',
                'onHoldProjects',
                'delayedProjects',
                'completedProjects',
                'draftProjects',
                'highPriorityProjects',
                'mediumPriorityProjects',
                'projectStages',
                'projectTypes',
                'projectPriorities',
                'projectStatuses'
            )
        );
    }


    /**
     * Show Project Setup form.
     */
    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | Only approved Investment Decisions
        |--------------------------------------------------------------------------
        */

        $investmentDecisions = InvestmentDecision::with([
            'feasibilityAssessment.land',
        ])
            ->where(function ($query) {

                $query->where(
                    'status',
                    'Approved'
                )->orWhereIn(
                    'decision',
                    [
                        'Go',
                        'Conditional Go',
                    ]
                );

            })
            ->whereDoesntHave('project')
            ->latest('id')
            ->get();


        return view(
            'projects.create',
            compact('investmentDecisions')
        );
    }


    /**
     * Store Project.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Investment Decision
            |--------------------------------------------------------------------------
            */

            'investment_decision_id' => [
                'required',
                'integer',
                'exists:investment_decisions,id',
            ],


            /*
            |--------------------------------------------------------------------------
            | Project Identity
            |--------------------------------------------------------------------------
            */

            'project_name' => [
                'required',
                'string',
                'max:255',
            ],

            'project_code' => [
                'nullable',
                'string',
                'max:50',
                'unique:projects,project_code',
            ],

            'project_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'project_description' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Lifecycle
            |--------------------------------------------------------------------------
            */

            'project_stage' => [
                'required',
                'string',
                'max:50',
            ],

            'project_status' => [
                'nullable',
                'string',
                'in:Draft',
            ],

            'project_priority' => [
                'nullable',
                'string',
                'max:50',
            ],


            /*
            |--------------------------------------------------------------------------
            | Responsibility
            |--------------------------------------------------------------------------
            */

            'project_sponsor_id' => [
                'nullable',
                'integer',
            ],

            'project_director_id' => [
                'nullable',
                'integer',
            ],

            'project_manager_id' => [
                'nullable',
                'integer',
            ],

            'development_manager_id' => [
                'nullable',
                'integer',
            ],


            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            'approval_date' => [
                'nullable',
                'date',
            ],

            'project_initiation_date' => [
                'nullable',
                'date',
            ],

            'project_start_date' => [
                'nullable',
                'date',
            ],

            'planned_completion_date' => [
                'nullable',
                'date',
                'after_or_equal:project_start_date',
            ],

            'actual_completion_date' => [
                'nullable',
                'date',
            ],


            /*
            |--------------------------------------------------------------------------
            | Scope
            |--------------------------------------------------------------------------
            */

            'development_objective' => [
                'nullable',
                'string',
            ],

            'scope_summary' => [
                'nullable',
                'string',
            ],

            'development_scope' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Area
            |--------------------------------------------------------------------------
            */

            'development_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'planned_gla' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'planned_nla' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'planned_leasable_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | Other
            |--------------------------------------------------------------------------
            */

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Get Investment Decision
        |--------------------------------------------------------------------------
        */

        $investmentDecision =
            InvestmentDecision::with([
                'feasibilityAssessment.land',
            ])
            ->findOrFail(
                $validated['investment_decision_id']
            );


        /*
        |--------------------------------------------------------------------------
        | Investment Approval Gate
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $investmentDecision->isApproved(),
            403,
            'Project cannot be created because the Investment Decision is not approved.'
        );


        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate Project
        |--------------------------------------------------------------------------
        */

        if (
            Project::where(
                'investment_decision_id',
                $investmentDecision->id
            )->exists()
        ) {

            return redirect()
                ->route('admin.projects.index')
                ->with(
                    'error',
                    'A project already exists for this Investment Decision.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Parent Records
        |--------------------------------------------------------------------------
        */

        $feasibilityAssessment =
            $investmentDecision
                ->feasibilityAssessment;

        $land =
            $feasibilityAssessment
                ->land;


        /*
        |--------------------------------------------------------------------------
        | Create Project
        |--------------------------------------------------------------------------
        */

        $project = DB::transaction(
            function () use (
                $validated,
                $investmentDecision,
                $feasibilityAssessment,
                $land
            ) {

                $data = $validated;

                // Every newly created project must enter the workflow in Draft.
                $data['project_status'] = 'Draft';


                /*
                |--------------------------------------------------------------------------
                | Lifecycle References
                |--------------------------------------------------------------------------
                */

                $data['land_id'] =
                    $land->id;

                $data['feasibility_assessment_id'] =
                    $feasibilityAssessment->id;

                $data['investment_decision_id'] =
                    $investmentDecision->id;


                /*
                |--------------------------------------------------------------------------
                | Project Number
                |--------------------------------------------------------------------------
                */

                $data['project_number'] =
                    'PRJ-' .
                    now()->format('YmdHis') .
                    '-' .
                    Str::upper(
                        Str::random(4)
                    );


                /*
                |--------------------------------------------------------------------------
                | Audit
                |--------------------------------------------------------------------------
                */

                $data['created_by'] =
                    auth()->id();


                return Project::create(
                    $data
                );
            }
        );


        return redirect()
            ->route(
                'admin.projects.show',
                [
                    'project' =>
                        $project->id,
                ]
            )
            ->with(
                'success',
                'Project created successfully.'
            );
    }


    /**
     * Display Project.
     */
    public function show(Project $project)
    {
        $project->load([
            'land',
            'feasibilityAssessment',
            'investmentDecision',
            'fundingPlans',
            'deliveryStrategies',
            'procurementStrategies',
            'contractStrategies',
            'risks',
            'stakeholders',
            'governance',
            'governanceMeetings',
            'statusHistories.performedBy',
        ]);

        return view(
            'projects.show',
            compact('project')
        );
    }


    /**
     * Show Edit form.
     */
    public function edit(Project $project)
    {
        return view(
            'projects.edit',
            compact('project')
        );
    }


    /**
     * Update Project.
     */
    public function update(
        Request $request,
        Project $project
    ) {

        $validated = $request->validate([

            'project_name' => [
                'required',
                'string',
                'max:255',
            ],

            'project_code' => [
                'nullable',
                'string',
                'max:50',
                'unique:projects,project_code,' .
                    $project->id,
            ],

            'project_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'project_description' => [
                'nullable',
                'string',
            ],

            'project_stage' => [
                'required',
                'string',
                'max:50',
            ],

            'project_priority' => [
                'nullable',
                'string',
                'max:50',
            ],

            'project_sponsor_id' => [
                'nullable',
                'integer',
            ],

            'project_director_id' => [
                'nullable',
                'integer',
            ],

            'project_manager_id' => [
                'nullable',
                'integer',
            ],

            'development_manager_id' => [
                'nullable',
                'integer',
            ],

            'approval_date' => [
                'nullable',
                'date',
            ],

            'project_initiation_date' => [
                'nullable',
                'date',
            ],

            'project_start_date' => [
                'nullable',
                'date',
            ],

            'planned_completion_date' => [
                'nullable',
                'date',
                'after_or_equal:project_start_date',
            ],

            'actual_completion_date' => [
                'nullable',
                'date',
            ],

            'development_objective' => [
                'nullable',
                'string',
            ],

            'scope_summary' => [
                'nullable',
                'string',
            ],

            'development_scope' => [
                'nullable',
                'string',
            ],

            'development_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'planned_gla' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'planned_nla' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'planned_leasable_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $validated['updated_by'] =
            auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Do not allow lifecycle references to be changed
        |--------------------------------------------------------------------------
        */

        unset(
            $validated['investment_decision_id'],
            $validated['land_id'],
            $validated['feasibility_assessment_id']
        );


        $project->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.show',
                [
                    'project' =>
                        $project->id,
                ]
            )
            ->with(
                'success',
                'Project updated successfully.'
            );
    }


    /**
     * Submit Draft project for approval.
     */
    public function submit(Project $project): RedirectResponse
    {
        return $this->transitionProjectStatus(
            $project,
            'Pending Approval',
            'Submit for Approval',
            ['Draft']
        );
    }

    /**
     * Approve project.
     */
    public function approve(Project $project): RedirectResponse
    {
        return $this->transitionProjectStatus(
            $project,
            'Approved',
            'Approve Project',
            ['Pending Approval'],
            ['approval_date' => now()->toDateString()]
        );
    }

    /**
     * Reject project and return it to Draft.
     */
    public function reject(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'remarks' => ['required', 'string', 'max:5000'],
        ]);

        return $this->transitionProjectStatus(
            $project,
            'Draft',
            'Reject Project',
            ['Pending Approval'],
            [],
            $validated['remarks']
        );
    }

    /**
     * Start an approved project.
     */
    public function start(Project $project): RedirectResponse
    {
        return $this->transitionProjectStatus(
            $project,
            'Active',
            'Start Project',
            ['Approved'],
            ['project_start_date' => now()->toDateString(), 'project_initiation_date' => now()->toDateString()]
        );
    }

    /**
     * Put an active/delayed project on hold.
     */
    public function hold(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'remarks' => ['nullable', 'string', 'max:5000'],
        ]);

        return $this->transitionProjectStatus(
            $project,
            'On Hold',
            'Put On Hold',
            ['Active', 'Delayed'],
            [],
            $validated['remarks'] ?? null
        );
    }

    /**
     * Resume a project from On Hold.
     */
    public function resume(Project $project): RedirectResponse
    {
        return $this->transitionProjectStatus(
            $project,
            'Active',
            'Resume Project',
            ['On Hold']
        );
    }

    /**
     * Mark an active project as delayed.
     */
    public function delay(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'remarks' => ['required', 'string', 'max:5000'],
        ]);

        return $this->transitionProjectStatus(
            $project,
            'Delayed',
            'Mark Delayed',
            ['Active'],
            [],
            $validated['remarks']
        );
    }

    /**
     * Resolve a delayed project back to Active.
     */
    public function resolveDelay(Project $project): RedirectResponse
    {
        return $this->transitionProjectStatus(
            $project,
            'Active',
            'Resolve Delay',
            ['Delayed']
        );
    }

    /**
     * Complete the project.
     */
    public function complete(Project $project): RedirectResponse
    {
        return $this->transitionProjectStatus(
            $project,
            'Completed',
            'Complete Project',
            ['Active', 'Delayed'],
            ['actual_completion_date' => now()->toDateString()]
        );
    }

    /**
     * Cancel the project.
     */
    public function cancel(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'remarks' => ['required', 'string', 'max:5000'],
        ]);

        return $this->transitionProjectStatus(
            $project,
            'Cancelled',
            'Cancel Project',
            ['Draft', 'Pending Approval', 'Approved', 'Active', 'On Hold', 'Delayed'],
            [],
            $validated['remarks']
        );
    }

    /**
     * Close a completed project.
     */
    public function close(Project $project): RedirectResponse
    {
        return $this->transitionProjectStatus(
            $project,
            'Closed',
            'Close Project',
            ['Completed']
        );
    }

    /**
     * Change project status and record workflow history.
     */
    protected function transitionProjectStatus(
        Project $project,
        string $toStatus,
        string $action,
        array $allowedFromStatuses,
        array $attributes = [],
        ?string $remarks = null
    ): RedirectResponse {
        if (!in_array($project->project_status, $allowedFromStatuses, true)) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    "Project cannot be moved from {$project->project_status} to {$toStatus}."
                );
        }

        $fromStatus = $project->project_status;

        DB::transaction(function () use (
            $project,
            $fromStatus,
            $toStatus,
            $action,
            $attributes,
            $remarks
        ) {
            $project->update(array_merge(
                $attributes,
                [
                    'project_status' => $toStatus,
                    'updated_by' => auth()->id(),
                ]
            ));

            ProjectStatusHistory::create([
                'project_id' => $project->id,
                'from_status' => $fromStatus,
                'to_status' => $toStatus,
                'action' => $action,
                'remarks' => $remarks,
                'performed_by' => auth()->id(),
                'performed_at' => now(),
            ]);
        });

        return redirect()
            ->route('admin.projects.show', ['project' => $project->id])
            ->with('success', "Project status changed to {$toStatus} successfully.");
    }

    /**
     * Delete Project.
     */
    public function destroy(Project $project)
    {
        if (
            !in_array(
                $project->project_status,
                [
                    'Draft',
                    'Cancelled',
                ],
                true
            )
        ) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Only Draft or Cancelled projects can be deleted.'
                );
        }


        $project->delete();


        return redirect()
            ->route(
                'admin.projects.index'
            )
            ->with(
                'success',
                'Project deleted successfully.'
            );
    }
}