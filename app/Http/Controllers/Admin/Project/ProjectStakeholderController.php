<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectStakeholder;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectStakeholderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $stakeholders = ProjectStakeholder::where(
            'project_id',
            $project->id
        )
            ->with('stakeholderOwner')
            ->orderByRaw("
                CASE priority
                    WHEN 'Critical' THEN 1
                    WHEN 'High' THEN 2
                    WHEN 'Medium' THEN 3
                    WHEN 'Low' THEN 4
                    ELSE 5
                END
            ")
            ->orderBy('stakeholder_name')
            ->get();


        $summary = [

            'total' =>
                $stakeholders->count(),

            'active' =>
                $stakeholders
                    ->where('status', 'Active')
                    ->count(),

            'inactive' =>
                $stakeholders
                    ->where('status', 'Inactive')
                    ->count(),

            'critical' =>
                $stakeholders
                    ->where('priority', 'Critical')
                    ->count(),

            'high' =>
                $stakeholders
                    ->where('priority', 'High')
                    ->count(),

            'high_influence' =>
                $stakeholders
                    ->whereIn('influence_level', [
                        'High',
                        'Very High',
                    ])
                    ->count(),

            'high_interest' =>
                $stakeholders
                    ->whereIn('interest_level', [
                        'High',
                        'Very High',
                    ])
                    ->count(),
        ];


        return view(
            'projects.stakeholders.index',
            compact(
                'project',
                'stakeholders',
                'summary'
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
        $lastNumber = ProjectStakeholder::where(
            'project_id',
            $project->id
        )
            ->orderByDesc('id')
            ->value('stakeholder_number');


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


        $stakeholderNumber =
            'STK-' .
            $project->id .
            '-' .
            str_pad(
                $nextNumber,
                3,
                '0',
                STR_PAD_LEFT
            );


        $stakeholderOwners = User::query()
            ->orderBy('name')
            ->get();


        return view(
            'projects.stakeholders.create',
            compact(
                'project',
                'stakeholderNumber',
                'stakeholderOwners'
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

            'stakeholder_number' => [
                'required',
                'string',
                'max:100',
                'unique:project_stakeholders,stakeholder_number',
            ],

            'stakeholder_name' => [
                'required',
                'string',
                'max:255',
            ],

            'stakeholder_type' => [
                'required',
                'in:Internal,External,Government,Regulatory,Investor,Lender,Landowner,Customer,Community,Contractor,Consultant,Supplier,Partner,Other',
            ],

            'organization_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'role' => [
                'nullable',
                'string',
                'max:255',
            ],

            'contact_person' => [
                'nullable',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'influence_level' => [
                'required',
                'in:Very Low,Low,Medium,High,Very High',
            ],

            'interest_level' => [
                'required',
                'in:Very Low,Low,Medium,High,Very High',
            ],

            'engagement_level' => [
                'required',
                'in:Unaware,Resistant,Neutral,Supportive,Leading',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'stakeholder_needs' => [
                'nullable',
                'string',
            ],

            'expectations' => [
                'nullable',
                'string',
            ],

            'concerns' => [
                'nullable',
                'string',
            ],

            'engagement_strategy' => [
                'nullable',
                'string',
            ],

            'communication_requirements' => [
                'nullable',
                'string',
            ],

            'communication_frequency' => [
                'required',
                'in:As Required,Weekly,Fortnightly,Monthly,Quarterly',
            ],

            'stakeholder_owner_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'status' => [
                'required',
                'in:Active,Inactive',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $stakeholder =
            ProjectStakeholder::create([

                'project_id' =>
                    $project->id,

                'stakeholder_number' =>
                    $validated['stakeholder_number'],

                'stakeholder_name' =>
                    $validated['stakeholder_name'],

                'stakeholder_type' =>
                    $validated['stakeholder_type'],

                'organization_name' =>
                    $validated['organization_name']
                    ?? null,

                'role' =>
                    $validated['role']
                    ?? null,

                'contact_person' =>
                    $validated['contact_person']
                    ?? null,

                'email' =>
                    $validated['email']
                    ?? null,

                'phone' =>
                    $validated['phone']
                    ?? null,

                'influence_level' =>
                    $validated['influence_level'],

                'interest_level' =>
                    $validated['interest_level'],

                'engagement_level' =>
                    $validated['engagement_level'],

                'priority' =>
                    $validated['priority'],

                'stakeholder_needs' =>
                    $validated['stakeholder_needs']
                    ?? null,

                'expectations' =>
                    $validated['expectations']
                    ?? null,

                'concerns' =>
                    $validated['concerns']
                    ?? null,

                'engagement_strategy' =>
                    $validated['engagement_strategy']
                    ?? null,

                'communication_requirements' =>
                    $validated['communication_requirements']
                    ?? null,

                'communication_frequency' =>
                    $validated['communication_frequency'],

                'stakeholder_owner_id' =>
                    $validated['stakeholder_owner_id']
                    ?? null,

                'status' =>
                    $validated['status'],

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
                'admin.projects.stakeholders.show',
                [
                    'project' =>
                        $project->id,

                    'stakeholder' =>
                        $stakeholder->id,
                ]
            )
            ->with(
                'success',
                'Stakeholder created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ProjectStakeholder $stakeholder
    ): View {

        $this->validateOwnership(
            $project,
            $stakeholder
        );


        $stakeholder->load(
            'stakeholderOwner'
        );


        return view(
            'projects.stakeholders.show',
            compact(
                'project',
                'stakeholder'
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
        ProjectStakeholder $stakeholder
    ): View {

        $this->validateOwnership(
            $project,
            $stakeholder
        );


        $stakeholderOwners =
            User::query()
                ->orderBy('name')
                ->get();


        return view(
            'projects.stakeholders.edit',
            compact(
                'project',
                'stakeholder',
                'stakeholderOwners'
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
        ProjectStakeholder $stakeholder
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $stakeholder
        );


        $validated = $request->validate([

            'stakeholder_name' => [
                'required',
                'string',
                'max:255',
            ],

            'stakeholder_type' => [
                'required',
                'in:Internal,External,Government,Regulatory,Investor,Lender,Landowner,Customer,Community,Contractor,Consultant,Supplier,Partner,Other',
            ],

            'organization_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'role' => [
                'nullable',
                'string',
                'max:255',
            ],

            'contact_person' => [
                'nullable',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'influence_level' => [
                'required',
                'in:Very Low,Low,Medium,High,Very High',
            ],

            'interest_level' => [
                'required',
                'in:Very Low,Low,Medium,High,Very High',
            ],

            'engagement_level' => [
                'required',
                'in:Unaware,Resistant,Neutral,Supportive,Leading',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'stakeholder_needs' => [
                'nullable',
                'string',
            ],

            'expectations' => [
                'nullable',
                'string',
            ],

            'concerns' => [
                'nullable',
                'string',
            ],

            'engagement_strategy' => [
                'nullable',
                'string',
            ],

            'communication_requirements' => [
                'nullable',
                'string',
            ],

            'communication_frequency' => [
                'required',
                'in:As Required,Weekly,Fortnightly,Monthly,Quarterly',
            ],

            'stakeholder_owner_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'status' => [
                'required',
                'in:Active,Inactive',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $stakeholder->update([

            'stakeholder_name' =>
                $validated['stakeholder_name'],

            'stakeholder_type' =>
                $validated['stakeholder_type'],

            'organization_name' =>
                $validated['organization_name']
                ?? null,

            'role' =>
                $validated['role']
                ?? null,

            'contact_person' =>
                $validated['contact_person']
                ?? null,

            'email' =>
                $validated['email']
                ?? null,

            'phone' =>
                $validated['phone']
                ?? null,

            'influence_level' =>
                $validated['influence_level'],

            'interest_level' =>
                $validated['interest_level'],

            'engagement_level' =>
                $validated['engagement_level'],

            'priority' =>
                $validated['priority'],

            'stakeholder_needs' =>
                $validated['stakeholder_needs']
                ?? null,

            'expectations' =>
                $validated['expectations']
                ?? null,

            'concerns' =>
                $validated['concerns']
                ?? null,

            'engagement_strategy' =>
                $validated['engagement_strategy']
                ?? null,

            'communication_requirements' =>
                $validated['communication_requirements']
                ?? null,

            'communication_frequency' =>
                $validated['communication_frequency'],

            'stakeholder_owner_id' =>
                $validated['stakeholder_owner_id']
                ?? null,

            'status' =>
                $validated['status'],

            'remarks' =>
                $validated['remarks']
                ?? null,

            'updated_by' =>
                auth()->id(),

        ]);


        return redirect()
            ->route(
                'admin.projects.stakeholders.show',
                [
                    'project' =>
                        $project->id,

                    'stakeholder' =>
                        $stakeholder->id,
                ]
            )
            ->with(
                'success',
                'Stakeholder updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CHANGE STATUS
    |--------------------------------------------------------------------------
    */

    public function changeStatus(
        Request $request,
        Project $project,
        ProjectStakeholder $stakeholder
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $stakeholder
        );


        $validated = $request->validate([

            'status' => [
                'required',
                'in:Active,Inactive',
            ],

        ]);


        $stakeholder->update([

            'status' =>
                $validated['status'],

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Stakeholder status updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ProjectStakeholder $stakeholder
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $stakeholder
        );


        $stakeholder->delete();


        return redirect()
            ->route(
                'admin.projects.stakeholders.index',
                [
                    'project' =>
                        $project->id,
                ]
            )
            ->with(
                'success',
                'Stakeholder deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | OWNERSHIP
    |--------------------------------------------------------------------------
    */

    protected function validateOwnership(
        Project $project,
        ProjectStakeholder $stakeholder
    ): void {

        abort_unless(
            (int) $stakeholder->project_id ===
            (int) $project->id,
            404
        );
    }
}