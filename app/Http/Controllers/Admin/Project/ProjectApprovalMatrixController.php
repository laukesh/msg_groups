<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectApprovalMatrix;
use App\Models\ProjectGovernance;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectApprovalMatrixController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $approvalMatrices = ProjectApprovalMatrix::where(
            'project_id',
            $project->id
        )
            ->with([
                'governance',
                'authorityUser',
            ])
            ->orderBy('approval_sequence')
            ->orderBy('id')
            ->get();

        return view(
            'projects.approval-matrix.index',
            compact(
                'project',
                'approvalMatrices'
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


        $lastCode = ProjectApprovalMatrix::where(
            'project_id',
            $project->id
        )
            ->orderByDesc('id')
            ->value('approval_code');


        if ($lastCode) {

            preg_match(
                '/(\d+)$/',
                $lastCode,
                $matches
            );

            $nextNumber =
                isset($matches[1])
                    ? ((int) $matches[1]) + 1
                    : 1;

        } else {

            $nextNumber = 1;
        }


        $approvalCode =
            'APR-' .
            $project->id .
            '-' .
            str_pad(
                $nextNumber,
                3,
                '0',
                STR_PAD_LEFT
            );


        return view(
            'projects.approval-matrix.create',
            compact(
                'project',
                'governances',
                'users',
                'approvalCode'
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

            'approval_code' => [
                'required',
                'string',
                'max:100',
                'unique:project_approval_matrix,approval_code',
            ],

            'project_governance_id' => [
                'nullable',
                'integer',
                'exists:project_governance,id',
            ],

            'approval_type' => [
                'required',
                'string',
                'max:150',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'authority_role' => [
                'required',
                'string',
                'max:150',
            ],

            'authority_user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'minimum_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'maximum_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'approval_sequence' => [
                'required',
                'integer',
                'min:1',
            ],

            'requires_multiple_approvals' => [
                'nullable',
                'boolean',
            ],

            'is_mandatory' => [
                'nullable',
                'boolean',
            ],

            'status' => [
                'required',
                'in:Draft,Active,Inactive',
            ],

            'effective_date' => [
                'nullable',
                'date',
            ],

            'expiry_date' => [
                'nullable',
                'date',
                'after_or_equal:effective_date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate governance belongs to project
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
        | Validate amount range
        |--------------------------------------------------------------------------
        */

        if (
            isset($validated['minimum_amount']) &&
            isset($validated['maximum_amount']) &&
            $validated['maximum_amount'] <
            $validated['minimum_amount']
        ) {

            return back()
                ->withErrors([
                    'maximum_amount' =>
                        'Maximum amount must be greater than or equal to minimum amount.',
                ])
                ->withInput();
        }


        $approvalMatrix =
            ProjectApprovalMatrix::create([

                'project_id' =>
                    $project->id,

                'project_governance_id' =>
                    $validated['project_governance_id']
                    ?? null,

                'approval_code' =>
                    $validated['approval_code'],

                'approval_type' =>
                    $validated['approval_type'],

                'description' =>
                    $validated['description']
                    ?? null,

                'authority_role' =>
                    $validated['authority_role'],

                'authority_user_id' =>
                    $validated['authority_user_id']
                    ?? null,

                'minimum_amount' =>
                    $validated['minimum_amount']
                    ?? null,

                'maximum_amount' =>
                    $validated['maximum_amount']
                    ?? null,

                'currency' =>
                    $validated['currency'],

                'approval_sequence' =>
                    $validated['approval_sequence'],

                'requires_multiple_approvals' =>
                    $request->boolean(
                        'requires_multiple_approvals'
                    ),

                'is_mandatory' =>
                    $request->boolean(
                        'is_mandatory'
                    ),

                'status' =>
                    $validated['status'],

                'effective_date' =>
                    $validated['effective_date']
                    ?? null,

                'expiry_date' =>
                    $validated['expiry_date']
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
                'admin.projects.approval-matrix.show',
                [
                    'project' =>
                        $project->id,

                    'approvalMatrix' =>
                        $approvalMatrix->id,
                ]
            )
            ->with(
                'success',
                'Approval matrix rule created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ProjectApprovalMatrix $approvalMatrix
    ): View {

        $this->validateOwnership(
            $project,
            $approvalMatrix
        );


        $approvalMatrix->load([
            'governance',
            'authorityUser',
        ]);


        return view(
            'projects.approval-matrix.show',
            compact(
                'project',
                'approvalMatrix'
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
        ProjectApprovalMatrix $approvalMatrix
    ): View {

        $this->validateOwnership(
            $project,
            $approvalMatrix
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
            'projects.approval-matrix.edit',
            compact(
                'project',
                'approvalMatrix',
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
        ProjectApprovalMatrix $approvalMatrix
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $approvalMatrix
        );


        $validated = $request->validate([

            'project_governance_id' => [
                'nullable',
                'integer',
                'exists:project_governance,id',
            ],

            'approval_type' => [
                'required',
                'string',
                'max:150',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'authority_role' => [
                'required',
                'string',
                'max:150',
            ],

            'authority_user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'minimum_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'maximum_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'approval_sequence' => [
                'required',
                'integer',
                'min:1',
            ],

            'requires_multiple_approvals' => [
                'nullable',
                'boolean',
            ],

            'is_mandatory' => [
                'nullable',
                'boolean',
            ],

            'status' => [
                'required',
                'in:Draft,Active,Inactive',
            ],

            'effective_date' => [
                'nullable',
                'date',
            ],

            'expiry_date' => [
                'nullable',
                'date',
                'after_or_equal:effective_date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate governance belongs to project
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
        | Validate amount range
        |--------------------------------------------------------------------------
        */

        if (
            isset($validated['minimum_amount']) &&
            isset($validated['maximum_amount']) &&
            $validated['maximum_amount'] <
            $validated['minimum_amount']
        ) {

            return back()
                ->withErrors([
                    'maximum_amount' =>
                        'Maximum amount must be greater than or equal to minimum amount.',
                ])
                ->withInput();
        }


        $approvalMatrix->update([

            'project_governance_id' =>
                $validated['project_governance_id']
                ?? null,

            'approval_type' =>
                $validated['approval_type'],

            'description' =>
                $validated['description']
                ?? null,

            'authority_role' =>
                $validated['authority_role'],

            'authority_user_id' =>
                $validated['authority_user_id']
                ?? null,

            'minimum_amount' =>
                $validated['minimum_amount']
                ?? null,

            'maximum_amount' =>
                $validated['maximum_amount']
                ?? null,

            'currency' =>
                $validated['currency'],

            'approval_sequence' =>
                $validated['approval_sequence'],

            'requires_multiple_approvals' =>
                $request->boolean(
                    'requires_multiple_approvals'
                ),

            'is_mandatory' =>
                $request->boolean(
                    'is_mandatory'
                ),

            'status' =>
                $validated['status'],

            'effective_date' =>
                $validated['effective_date']
                ?? null,

            'expiry_date' =>
                $validated['expiry_date']
                ?? null,

            'remarks' =>
                $validated['remarks']
                ?? null,

            'updated_by' =>
                auth()->id(),

        ]);


        return redirect()
            ->route(
                'admin.projects.approval-matrix.show',
                [
                    'project' =>
                        $project->id,

                    'approvalMatrix' =>
                        $approvalMatrix->id,
                ]
            )
            ->with(
                'success',
                'Approval matrix rule updated successfully.'
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
        ProjectApprovalMatrix $approvalMatrix
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $approvalMatrix
        );


        $validated = $request->validate([

            'status' => [
                'required',
                'in:Draft,Active,Inactive',
            ],

        ]);


        $approvalMatrix->update([

            'status' =>
                $validated['status'],

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Approval matrix status updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ProjectApprovalMatrix $approvalMatrix
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $approvalMatrix
        );


        $approvalMatrix->delete();


        return redirect()
            ->route(
                'admin.projects.approval-matrix.index',
                [
                    'project' =>
                        $project->id,
                ]
            )
            ->with(
                'success',
                'Approval matrix rule deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | OWNERSHIP
    |--------------------------------------------------------------------------
    */

    protected function validateOwnership(
        Project $project,
        ProjectApprovalMatrix $approvalMatrix
    ): void {

        abort_unless(
            (int) $approvalMatrix->project_id ===
            (int) $project->id,
            404
        );
    }
}