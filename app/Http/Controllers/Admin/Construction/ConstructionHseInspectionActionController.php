<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionHseInspection;
use App\Models\ConstructionHseInspectionAction;
use App\Models\ConstructionHseInspectionFinding;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConstructionHseInspectionActionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project,
        ConstructionHseInspection $inspection,
        ConstructionHseInspectionFinding $finding
    ): View {

        $this->validateFindingRelation(
            $project,
            $inspection,
            $finding
        );

        $actions = $finding
            ->actions()
            ->with([
                'responsibleUser',
                'completedBy',
                'verifiedBy',
            ])
            ->latest('id')
            ->get();

        return view(
            'construction.hse.inspection-actions.index',
            [
                'project' => $project,
                'inspection' => $inspection,
                'finding' => $finding,
                'actions' => $actions,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        Project $project,
        ConstructionHseInspection $inspection,
        ConstructionHseInspectionFinding $finding
    ): View {

        $this->validateFindingRelation(
            $project,
            $inspection,
            $finding
        );

        $actionNumber =
            $this->generateActionNumber();

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'construction.hse.inspection-actions.create',
            [
                'project' => $project,
                'inspection' => $inspection,
                'finding' => $finding,
                'actionNumber' => $actionNumber,
                'users' => $users,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Project $project,
        ConstructionHseInspection $inspection,
        ConstructionHseInspectionFinding $finding
    ): RedirectResponse {

        $this->validateFindingRelation(
            $project,
            $inspection,
            $finding
        );

        $validated = $request->validate([

            'action_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'action_description' => [
                'required',
                'string',
            ],

            'responsible_user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'responsible_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'status' => [
                'required',
                'in:Open,In Progress,Completed,Closed',
            ],

            'completion_remarks' => [
                'nullable',
                'string',
            ],

            'verification_status' => [
                'nullable',
                'in:Pending,Verified,Rejected',
            ],

            'verified_date' => [
                'nullable',
                'date',
            ],

            'verification_remarks' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Action Number
        |--------------------------------------------------------------------------
        */

        $validated['action_number'] =
            $this->generateActionNumber();


        /*
        |--------------------------------------------------------------------------
        | Finding
        |--------------------------------------------------------------------------
        */

        $validated[
            'construction_hse_inspection_finding_id'
        ] = $finding->id;


        /*
        |--------------------------------------------------------------------------
        | Responsible User
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['responsible_user_id']
            )
        ) {

            $responsibleUser =
                User::find(
                    $validated['responsible_user_id']
                );

            $validated['responsible_name'] =
                $responsibleUser?->name;
        }


        /*
        |--------------------------------------------------------------------------
        | Default Verification
        |--------------------------------------------------------------------------
        */

        if (
            empty(
                $validated['verification_status']
            )
        ) {

            $validated['verification_status'] =
                'Pending';
        }


        /*
        |--------------------------------------------------------------------------
        | Completion
        |--------------------------------------------------------------------------
        */

        if (
            $validated['status'] === 'Completed'
            ||
            $validated['status'] === 'Closed'
        ) {

            $validated['completed_date'] =
                $validated['completed_date']
                ?? now()->toDateString();

            $validated['completed_by'] =
                Auth::id();
        }


        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $validated['created_by'] =
            Auth::id();

        $validated['updated_by'] =
            Auth::id();


        ConstructionHseInspectionAction::create(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Update Finding Status
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $finding->status,
                [
                    'Verified',
                    'Closed',
                ],
                true
            )
        ) {

            $finding->update([
                'status' => 'Action Required',
                'updated_by' => Auth::id(),
            ]);
        }


        return redirect()
            ->route(
                'admin.projects.construction.hse.inspections.findings.actions.index',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                    'finding' => $finding,
                ]
            )
            ->with(
                'success',
                'Inspection corrective action created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionHseInspection $inspection,
        ConstructionHseInspectionFinding $finding,
        ConstructionHseInspectionAction $action
    ): View {

        $this->validateActionRelation(
            $project,
            $inspection,
            $finding,
            $action
        );

        $action->load([
            'responsibleUser',
            'completedBy',
            'verifiedBy',
            'creator',
            'updater',
        ]);

        return view(
            'construction.hse.inspection-actions.show',
            [
                'project' => $project,
                'inspection' => $inspection,
                'finding' => $finding,
                'action' => $action,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ConstructionHseInspection $inspection,
        ConstructionHseInspectionFinding $finding,
        ConstructionHseInspectionAction $action
    ): View {

        $this->validateActionRelation(
            $project,
            $inspection,
            $finding,
            $action
        );

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'construction.hse.inspection-actions.edit',
            [
                'project' => $project,
                'inspection' => $inspection,
                'finding' => $finding,
                'action' => $action,
                'users' => $users,
            ]
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
        ConstructionHseInspection $inspection,
        ConstructionHseInspectionFinding $finding,
        ConstructionHseInspectionAction $action
    ): RedirectResponse {

        $this->validateActionRelation(
            $project,
            $inspection,
            $finding,
            $action
        );

        $validated = $request->validate([

            'action_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'action_description' => [
                'required',
                'string',
            ],

            'responsible_user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'responsible_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'status' => [
                'required',
                'in:Open,In Progress,Completed,Closed',
            ],

            'completed_date' => [
                'nullable',
                'date',
            ],

            'completion_remarks' => [
                'nullable',
                'string',
            ],

            'verification_status' => [
                'nullable',
                'in:Pending,Verified,Rejected',
            ],

            'verified_date' => [
                'nullable',
                'date',
            ],

            'verification_remarks' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Responsible User
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['responsible_user_id']
            )
        ) {

            $responsibleUser =
                User::find(
                    $validated['responsible_user_id']
                );

            $validated['responsible_name'] =
                $responsibleUser?->name;
        }


        /*
        |--------------------------------------------------------------------------
        | Completion
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $validated['status'],
                [
                    'Completed',
                    'Closed',
                ],
                true
            )
        ) {

            $validated['completed_date'] =
                $validated['completed_date']
                ?? now()->toDateString();

            $validated['completed_by'] =
                $action->completed_by
                ?? Auth::id();
        }


        /*
        |--------------------------------------------------------------------------
        | Verification
        |--------------------------------------------------------------------------
        */

        if (
            $validated['verification_status'] ===
            'Verified'
        ) {

            $validated['verified_date'] =
                $validated['verified_date']
                ?? now()->toDateString();

            $validated['verified_by'] =
                $action->verified_by
                ?? Auth::id();
        }


        $validated['updated_by'] =
            Auth::id();


        $action->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.construction.hse.inspections.findings.actions.show',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                    'finding' => $finding,
                    'action' => $action,
                ]
            )
            ->with(
                'success',
                'Inspection corrective action updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionHseInspection $inspection,
        ConstructionHseInspectionFinding $finding,
        ConstructionHseInspectionAction $action
    ): RedirectResponse {

        $this->validateActionRelation(
            $project,
            $inspection,
            $finding,
            $action
        );


        if (
            $action->status === 'Closed'
        ) {

            return back()
                ->with(
                    'error',
                    'A closed action cannot be deleted.'
                );
        }


        $action->delete();


        /*
        |--------------------------------------------------------------------------
        | Recalculate Finding Status
        |--------------------------------------------------------------------------
        */

        if (
            !$finding->actions()->exists()
            &&
            $finding->status === 'Action Required'
        ) {

            $finding->update([
                'status' => 'Open',
                'updated_by' => Auth::id(),
            ]);
        }


        return redirect()
            ->route(
                'admin.projects.construction.hse.inspections.findings.actions.index',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                    'finding' => $finding,
                ]
            )
            ->with(
                'success',
                'Inspection corrective action deleted successfully.'
            );
    }

    /*
	|--------------------------------------------------------------------------
	| INSPECTION ACTION INDEX
	|--------------------------------------------------------------------------
	*/

	public function inspectionIndex(
	    Project $project,
	    ConstructionHseInspection $inspection
	): View {

	    /*
	    |--------------------------------------------------------------------------
	    | Validate Inspection
	    |--------------------------------------------------------------------------
	    */

	    abort_unless(
	        (int) $inspection->project_id ===
	        (int) $project->id,
	        404
	    );


	    /*
	    |--------------------------------------------------------------------------
	    | Get All Corrective Actions
	    |--------------------------------------------------------------------------
	    |
	    | Actions belong to findings, so we retrieve all actions whose
	    | finding belongs to this inspection.
	    |
	    */

	    $actions = ConstructionHseInspectionAction::query()

	        ->whereHas(
	            'finding',
	            function ($query) use ($inspection) {

	                $query->where(
	                    'construction_hse_inspection_id',
	                    $inspection->id
	                );

	            }
	        )

	        ->with([
	            'finding',
	            'responsibleUser',
	            'completedBy',
	            'verifiedBy',
	        ])

	        ->latest('id')

	        ->get();


	    /*
	    |--------------------------------------------------------------------------
	    | Return View
	    |--------------------------------------------------------------------------
	    */

	    return view(
	        'construction.hse.inspection-actions.inspection-index',
	        [
	            'project' => $project,
	            'inspection' => $inspection,
	            'actions' => $actions,
	        ]
	    );
	}


    /*
    |--------------------------------------------------------------------------
    | GENERATE ACTION NUMBER
    |--------------------------------------------------------------------------
    */

    private function generateActionNumber(): string
    {
        $lastAction =
            ConstructionHseInspectionAction::query()
                ->orderByDesc('id')
                ->first();

        $nextNumber =
            $lastAction
                ? $lastAction->id + 1
                : 1;

        return 'HSE-IACT-' .
            str_pad(
                $nextNumber,
                4,
                '0',
                STR_PAD_LEFT
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE FINDING RELATION
    |--------------------------------------------------------------------------
    */

    private function validateFindingRelation(
        Project $project,
        ConstructionHseInspection $inspection,
        ConstructionHseInspectionFinding $finding
    ): void {

        abort_unless(
            (int) $inspection->project_id ===
            (int) $project->id,
            404
        );

        abort_unless(
            (int) $finding->construction_hse_inspection_id ===
            (int) $inspection->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE ACTION RELATION
    |--------------------------------------------------------------------------
    */

    private function validateActionRelation(
        Project $project,
        ConstructionHseInspection $inspection,
        ConstructionHseInspectionFinding $finding,
        ConstructionHseInspectionAction $action
    ): void {

        $this->validateFindingRelation(
            $project,
            $inspection,
            $finding
        );

        abort_unless(
            (int) $action->construction_hse_inspection_finding_id ===
            (int) $finding->id,
            404
        );
    }
}