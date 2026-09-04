<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\InvestmentDecision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display all projects.
     */
    public function index()
    {
        $projects = Project::with([
            'land',
            'feasibilityAssessment',
            'investmentDecision',
        ])
            ->latest('id')
            ->get();

        return view(
            'projects.index',
            compact('projects')
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
                'required',
                'string',
                'max:50',
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

            'project_status' => [
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