<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectDecisionRegister;
use App\Models\ProjectGovernance;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class ProjectDecisionRegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $decisions = ProjectDecisionRegister::where(
            'project_id',
            $project->id
        )
            ->with([
                'governance',
                'decisionMaker',
                'implementationOwner',
            ])
            ->orderByDesc('decision_date')
            ->orderByDesc('id')
            ->get();

        return view(
            'projects.decision-register.index',
            compact(
                'project',
                'decisions'
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
        $governances = ProjectGovernance::where(
            'project_id',
            $project->id
        )
            ->orderByDesc('id')
            ->get();


        $users = User::query()
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Generate Decision Number
        |--------------------------------------------------------------------------
        */

        $lastNumber = ProjectDecisionRegister::where(
            'project_id',
            $project->id
        )
            ->orderByDesc('id')
            ->value('decision_number');


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


        $decisionNumber =
            'DEC-' .
            $project->id .
            '-' .
            str_pad(
                $nextNumber,
                3,
                '0',
                STR_PAD_LEFT
            );


        return view(
            'projects.decision-register.create',
            compact(
                'project',
                'governances',
                'users',
                'decisionNumber'
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

            'decision_number' => [
                'required',
                'string',
                'max:100',

                Rule::unique(
                    'project_decision_register',
                    'decision_number'
                )->where(
                    fn ($query) =>
                        $query->where(
                            'project_id',
                            $project->id
                        )
                ),
            ],

            'project_governance_id' => [
                'nullable',
                'integer',
                'exists:project_governance,id',
            ],

            'decision_date' => [
                'required',
                'date',
            ],

            'decision_type' => [
                'required',
                'string',
                'max:150',
            ],

            'subject' => [
                'required',
                'string',
                'max:255',
            ],

            'decision' => [
                'required',
                'string',
            ],

            'rationale' => [
                'nullable',
                'string',
            ],

            'decision_maker_role' => [
                'nullable',
                'string',
                'max:150',
            ],

            'decision_maker_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'impact_description' => [
                'nullable',
                'string',
            ],

            'financial_impact' => [
                'nullable',
                'numeric',
            ],

            'schedule_impact_days' => [
                'nullable',
                'integer',
            ],

            'status' => [
                'required',
                'in:Draft,Approved,Implemented,Superseded,Cancelled',
            ],

            'implementation_required' => [
                'nullable',
                'boolean',
            ],

            'implementation_owner_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'implementation_due_date' => [
                'nullable',
                'date',
            ],

            'implemented_date' => [
                'nullable',
                'date',
            ],

            'reference_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Governance Ownership
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['project_governance_id']
            )
        ) {

            $governanceExists =
                ProjectGovernance::where(
                    'id',
                    $validated['project_governance_id']
                )
                ->where(
                    'project_id',
                    $project->id
                )
                ->exists();


            if (!$governanceExists) {

                return back()
                    ->withErrors([
                        'project_governance_id' =>
                            'The selected governance framework does not belong to this project.',
                    ])
                    ->withInput();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Implementation Owner
        |--------------------------------------------------------------------------
        */

        if (
            empty(
                $validated['implementation_required']
            )
        ) {

            $validated['implementation_owner_id'] = null;

            $validated['implementation_due_date'] = null;

            $validated['implemented_date'] = null;
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Implemented Date
        |--------------------------------------------------------------------------
        */

        if (
            $validated['status'] === 'Implemented' &&
            empty($validated['implemented_date'])
        ) {

            return back()
                ->withErrors([
                    'implemented_date' =>
                        'Implemented date is required when the decision status is Implemented.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Create Decision
        |--------------------------------------------------------------------------
        */

        $decision =
            ProjectDecisionRegister::create([

                'project_id' =>
                    $project->id,

                'project_governance_id' =>
                    $validated['project_governance_id']
                    ?? null,

                'decision_number' =>
                    $validated['decision_number'],

                'decision_date' =>
                    $validated['decision_date'],

                'decision_type' =>
                    $validated['decision_type'],

                'subject' =>
                    $validated['subject'],

                'decision' =>
                    $validated['decision'],

                'rationale' =>
                    $validated['rationale']
                    ?? null,

                'decision_maker_role' =>
                    $validated['decision_maker_role']
                    ?? null,

                'decision_maker_id' =>
                    $validated['decision_maker_id']
                    ?? null,

                'priority' =>
                    $validated['priority'],

                'impact_description' =>
                    $validated['impact_description']
                    ?? null,

                'financial_impact' =>
                    $validated['financial_impact']
                    ?? null,

                'schedule_impact_days' =>
                    $validated['schedule_impact_days']
                    ?? null,

                'status' =>
                    $validated['status'],

                'implementation_required' =>
                    $request->boolean(
                        'implementation_required'
                    ),

                'implementation_owner_id' =>
                    $validated['implementation_owner_id']
                    ?? null,

                'implementation_due_date' =>
                    $validated['implementation_due_date']
                    ?? null,

                'implemented_date' =>
                    $validated['implemented_date']
                    ?? null,

                'reference_number' =>
                    $validated['reference_number']
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
                'admin.projects.decision-register.show',
                [
                    'project' =>
                        $project->id,

                    'decision' =>
                        $decision->id,
                ]
            )
            ->with(
                'success',
                'Decision registered successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ProjectDecisionRegister $decision
    ): View {

        $this->validateOwnership(
            $project,
            $decision
        );


        $decision->load([
            'governance',
            'decisionMaker',
            'implementationOwner',
        ]);


        return view(
            'projects.decision-register.show',
            compact(
                'project',
                'decision'
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
        ProjectDecisionRegister $decision
    ): View {

        $this->validateOwnership(
            $project,
            $decision
        );


        $governances = ProjectGovernance::where(
            'project_id',
            $project->id
        )
            ->orderByDesc('id')
            ->get();


        $users = User::query()
            ->orderBy('name')
            ->get();


        return view(
            'projects.decision-register.edit',
            compact(
                'project',
                'decision',
                'governances',
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
        ProjectDecisionRegister $decision
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $decision
        );


        $validated = $request->validate([

            'project_governance_id' => [
                'nullable',
                'integer',
                'exists:project_governance,id',
            ],

            'decision_date' => [
                'required',
                'date',
            ],

            'decision_type' => [
                'required',
                'string',
                'max:150',
            ],

            'subject' => [
                'required',
                'string',
                'max:255',
            ],

            'decision' => [
                'required',
                'string',
            ],

            'rationale' => [
                'nullable',
                'string',
            ],

            'decision_maker_role' => [
                'nullable',
                'string',
                'max:150',
            ],

            'decision_maker_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'impact_description' => [
                'nullable',
                'string',
            ],

            'financial_impact' => [
                'nullable',
                'numeric',
            ],

            'schedule_impact_days' => [
                'nullable',
                'integer',
            ],

            'status' => [
                'required',
                'in:Draft,Approved,Implemented,Superseded,Cancelled',
            ],

            'implementation_required' => [
                'nullable',
                'boolean',
            ],

            'implementation_owner_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'implementation_due_date' => [
                'nullable',
                'date',
            ],

            'implemented_date' => [
                'nullable',
                'date',
            ],

            'reference_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Governance Ownership
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['project_governance_id']
            )
        ) {

            $governanceExists =
                ProjectGovernance::where(
                    'id',
                    $validated['project_governance_id']
                )
                ->where(
                    'project_id',
                    $project->id
                )
                ->exists();


            if (!$governanceExists) {

                return back()
                    ->withErrors([
                        'project_governance_id' =>
                            'The selected governance framework does not belong to this project.',
                    ])
                    ->withInput();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Implementation Logic
        |--------------------------------------------------------------------------
        */

        $implementationRequired =
            $request->boolean(
                'implementation_required'
            );


        if (!$implementationRequired) {

            $validated['implementation_owner_id'] = null;

            $validated['implementation_due_date'] = null;

            $validated['implemented_date'] = null;
        }


        /*
        |--------------------------------------------------------------------------
        | Implemented Status Validation
        |--------------------------------------------------------------------------
        */

        if (
            $validated['status'] === 'Implemented' &&
            empty($validated['implemented_date'])
        ) {

            return back()
                ->withErrors([
                    'implemented_date' =>
                        'Implemented date is required when the decision status is Implemented.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $decision->update([

            'project_governance_id' =>
                $validated['project_governance_id']
                ?? null,

            'decision_date' =>
                $validated['decision_date'],

            'decision_type' =>
                $validated['decision_type'],

            'subject' =>
                $validated['subject'],

            'decision' =>
                $validated['decision'],

            'rationale' =>
                $validated['rationale']
                ?? null,

            'decision_maker_role' =>
                $validated['decision_maker_role']
                ?? null,

            'decision_maker_id' =>
                $validated['decision_maker_id']
                ?? null,

            'priority' =>
                $validated['priority'],

            'impact_description' =>
                $validated['impact_description']
                ?? null,

            'financial_impact' =>
                $validated['financial_impact']
                ?? null,

            'schedule_impact_days' =>
                $validated['schedule_impact_days']
                ?? null,

            'status' =>
                $validated['status'],

            'implementation_required' =>
                $implementationRequired,

            'implementation_owner_id' =>
                $validated['implementation_owner_id']
                ?? null,

            'implementation_due_date' =>
                $validated['implementation_due_date']
                ?? null,

            'implemented_date' =>
                $validated['implemented_date']
                ?? null,

            'reference_number' =>
                $validated['reference_number']
                ?? null,

            'remarks' =>
                $validated['remarks']
                ?? null,

            'updated_by' =>
                auth()->id(),

        ]);


        return redirect()
            ->route(
                'admin.projects.decision-register.show',
                [
                    'project' =>
                        $project->id,

                    'decision' =>
                        $decision->id,
                ]
            )
            ->with(
                'success',
                'Decision updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ProjectDecisionRegister $decision
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $decision
        );


        $decision->delete();


        return redirect()
            ->route(
                'admin.projects.decision-register.index',
                [
                    'project' =>
                        $project->id,
                ]
            )
            ->with(
                'success',
                'Decision deleted successfully.'
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
        ProjectDecisionRegister $decision
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $decision
        );


        $validated = $request->validate([

            'status' => [
                'required',
                'in:Draft,Approved,Implemented,Superseded,Cancelled',
            ],

        ]);


        $decision->update([

            'status' =>
                $validated['status'],

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Decision status updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | OWNERSHIP
    |--------------------------------------------------------------------------
    */

    protected function validateOwnership(
        Project $project,
        ProjectDecisionRegister $decision
    ): void {

        abort_unless(
            (int) $decision->project_id ===
            (int) $project->id,
            404
        );
    }
}