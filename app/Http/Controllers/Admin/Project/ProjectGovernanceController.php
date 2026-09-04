<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectGovernance;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectGovernanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $governances = ProjectGovernance::where(
            'project_id',
            $project->id
        )
            ->with([
                'projectSponsor',
                'projectDirector',
                'projectManager',
            ])
            ->orderByDesc('id')
            ->get();

        return view(
            'projects.governance.index',
            compact(
                'project',
                'governances'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(Project $project): View
    {
        $lastNumber = ProjectGovernance::where(
            'project_id',
            $project->id
        )
            ->orderByDesc('id')
            ->value('governance_number');


        if ($lastNumber) {

            preg_match(
                '/(\d+)$/',
                $lastNumber,
                $matches
            );

            $nextNumber =
                isset($matches[1])
                    ? ((int) $matches[1]) + 1
                    : 1;

        } else {

            $nextNumber = 1;
        }


        $governanceNumber =
            'GOV-' .
            $project->id .
            '-' .
            str_pad(
                $nextNumber,
                3,
                '0',
                STR_PAD_LEFT
            );


        $users = User::query()
            ->orderBy('name')
            ->get();


        return view(
            'projects.governance.create',
            compact(
                'project',
                'governanceNumber',
                'users'
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
        Project $project
    ): RedirectResponse {

        $validated = $request->validate([

            'governance_number' => [
                'required',
                'string',
                'max:100',
                'unique:project_governance,governance_number',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'governance_model' => [
                'required',
                'in:Corporate,Project-Based,Program-Based,Joint Venture,Public Private Partnership,Other',
            ],

            'project_sponsor_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'project_director_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'project_manager_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'governance_objective' => [
                'nullable',
                'string',
            ],

            'decision_making_framework' => [
                'nullable',
                'string',
            ],

            'escalation_framework' => [
                'nullable',
                'string',
            ],

            'approval_framework' => [
                'nullable',
                'string',
            ],

            'reporting_framework' => [
                'nullable',
                'string',
            ],

            'meeting_framework' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Draft,Active,Under Review,Superseded,Closed',
            ],

            'effective_date' => [
                'nullable',
                'date',
            ],

            'review_date' => [
                'nullable',
                'date',
                'after_or_equal:effective_date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $governance =
            ProjectGovernance::create([

                'project_id' =>
                    $project->id,

                'governance_number' =>
                    $validated['governance_number'],

                'title' =>
                    $validated['title'],

                'governance_model' =>
                    $validated['governance_model'],

                'project_sponsor_id' =>
                    $validated['project_sponsor_id']
                    ?? null,

                'project_director_id' =>
                    $validated['project_director_id']
                    ?? null,

                'project_manager_id' =>
                    $validated['project_manager_id']
                    ?? null,

                'governance_objective' =>
                    $validated['governance_objective']
                    ?? null,

                'decision_making_framework' =>
                    $validated['decision_making_framework']
                    ?? null,

                'escalation_framework' =>
                    $validated['escalation_framework']
                    ?? null,

                'approval_framework' =>
                    $validated['approval_framework']
                    ?? null,

                'reporting_framework' =>
                    $validated['reporting_framework']
                    ?? null,

                'meeting_framework' =>
                    $validated['meeting_framework']
                    ?? null,

                'status' =>
                    $validated['status'],

                'effective_date' =>
                    $validated['effective_date']
                    ?? null,

                'review_date' =>
                    $validated['review_date']
                    ?? null,

                'remarks' =>
                    $validated['remarks']
                    ?? null,

                'created_by' =>
                    auth()->id(),

                'updated_by' =>
                    auth()->id(),

            ]);


        return redirect()
            ->route(
                'admin.projects.governance.show',
                [
                    'project' =>
                        $project->id,

                    'governance' =>
                        $governance->id,
                ]
            )
            ->with(
                'success',
                'Project governance framework created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ProjectGovernance $governance
    ): View {

        $this->validateOwnership(
            $project,
            $governance
        );


        $governance->load([
            'projectSponsor',
            'projectDirector',
            'projectManager',
            'approvalMatrix.authorityUser',
            'decisionRegister.decisionMaker',
            'decisionRegister.implementationOwner',
        ]);


        return view(
            'projects.governance.show',
            compact(
                'project',
                'governance'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ProjectGovernance $governance
    ): View {

        $this->validateOwnership(
            $project,
            $governance
        );


        $users = User::query()
            ->orderBy('name')
            ->get();


        return view(
            'projects.governance.edit',
            compact(
                'project',
                'governance',
                'users'
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
        Project $project,
        ProjectGovernance $governance
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $governance
        );


        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'governance_model' => [
                'required',
                'in:Corporate,Project-Based,Program-Based,Joint Venture,Public Private Partnership,Other',
            ],

            'project_sponsor_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'project_director_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'project_manager_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'governance_objective' => [
                'nullable',
                'string',
            ],

            'decision_making_framework' => [
                'nullable',
                'string',
            ],

            'escalation_framework' => [
                'nullable',
                'string',
            ],

            'approval_framework' => [
                'nullable',
                'string',
            ],

            'reporting_framework' => [
                'nullable',
                'string',
            ],

            'meeting_framework' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Draft,Active,Under Review,Superseded,Closed',
            ],

            'effective_date' => [
                'nullable',
                'date',
            ],

            'review_date' => [
                'nullable',
                'date',
                'after_or_equal:effective_date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $governance->update([

            'title' =>
                $validated['title'],

            'governance_model' =>
                $validated['governance_model'],

            'project_sponsor_id' =>
                $validated['project_sponsor_id']
                ?? null,

            'project_director_id' =>
                $validated['project_director_id']
                ?? null,

            'project_manager_id' =>
                $validated['project_manager_id']
                ?? null,

            'governance_objective' =>
                $validated['governance_objective']
                ?? null,

            'decision_making_framework' =>
                $validated['decision_making_framework']
                ?? null,

            'escalation_framework' =>
                $validated['escalation_framework']
                ?? null,

            'approval_framework' =>
                $validated['approval_framework']
                ?? null,

            'reporting_framework' =>
                $validated['reporting_framework']
                ?? null,

            'meeting_framework' =>
                $validated['meeting_framework']
                ?? null,

            'status' =>
                $validated['status'],

            'effective_date' =>
                $validated['effective_date']
                ?? null,

            'review_date' =>
                $validated['review_date']
                ?? null,

            'remarks' =>
                $validated['remarks']
                ?? null,

            'updated_by' =>
                auth()->id(),

        ]);


        return redirect()
            ->route(
                'admin.projects.governance.show',
                [
                    'project' =>
                        $project->id,

                    'governance' =>
                        $governance->id,
                ]
            )
            ->with(
                'success',
                'Project governance framework updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    public function changeStatus(
        Request $request,
        Project $project,
        ProjectGovernance $governance
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $governance
        );


        $validated = $request->validate([

            'status' => [
                'required',
                'in:Draft,Active,Under Review,Superseded,Closed',
            ],

        ]);


        $governance->update([

            'status' =>
                $validated['status'],

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Governance status updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ProjectGovernance $governance
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $governance
        );


        $governance->delete();


        return redirect()
            ->route(
                'admin.projects.governance.index',
                [
                    'project' =>
                        $project->id,
                ]
            )
            ->with(
                'success',
                'Project governance framework deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | OWNERSHIP CHECK
    |--------------------------------------------------------------------------
    */

    protected function validateOwnership(
        Project $project,
        ProjectGovernance $governance
    ): void {

        abort_unless(
            (int) $governance->project_id ===
            (int) $project->id,
            404
        );
    }
}