<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionHseInspection;
use App\Models\ConstructionHseInspectionFinding;
use App\Models\ConstructionHseInspectionItem;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConstructionHseInspectionFindingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project,
        ConstructionHseInspection $inspection
    ): View {

        $this->validateInspectionRelation(
            $project,
            $inspection
        );

        $findings = $inspection
            ->findings()
            ->with([
                'inspectionItem',
                'responsibleUser',
                'verifiedBy',
            ])
            ->latest('id')
            ->get();

        return view(
            'construction.hse.inspection-findings.index',
            [
                'project' => $project,
                'inspection' => $inspection,
                'findings' => $findings,
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
        ConstructionHseInspection $inspection
    ): View {

        $this->validateInspectionRelation(
            $project,
            $inspection
        );

        $findingNumber =
            $this->generateFindingNumber();


        $items = $inspection
            ->items()
            ->orderBy('id')
            ->get();


        $users = User::query()
            ->orderBy('name')
            ->get();


        return view(
            'construction.hse.inspection-findings.create',
            [
                'project' => $project,
                'inspection' => $inspection,
                'findingNumber' => $findingNumber,
                'items' => $items,
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
        ConstructionHseInspection $inspection
    ): RedirectResponse {

        $this->validateInspectionRelation(
            $project,
            $inspection
        );


        $validated = $request->validate([

            'construction_hse_inspection_item_id' => [
                'nullable',
                'integer',
            ],

            'finding_date' => [
                'required',
                'date',
            ],

            'finding_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'finding_title' => [
                'required',
                'string',
                'max:255',
            ],

            'finding_description' => [
                'required',
                'string',
            ],

            'severity' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'immediate_action' => [
                'nullable',
                'string',
            ],

            'recommended_action' => [
                'nullable',
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
                'in:Open,In Progress,Action Required,Resolved,Verified,Closed',
            ],

            'verification_status' => [
                'nullable',
                'in:Pending,Verified,Rejected',
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
        | Validate Checklist Item Belongs To Inspection
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'construction_hse_inspection_item_id'
                ]
            )
        ) {

            $itemExists =
                $inspection
                    ->items()
                    ->where(
                        'id',
                        $validated[
                            'construction_hse_inspection_item_id'
                        ]
                    )
                    ->exists();


            abort_unless(
                $itemExists,
                404
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Finding Number
        |--------------------------------------------------------------------------
        */

        $validated['finding_number'] =
            $this->generateFindingNumber();


        /*
        |--------------------------------------------------------------------------
        | Inspection
        |--------------------------------------------------------------------------
        */

        $validated[
            'construction_hse_inspection_id'
        ] = $inspection->id;


        /*
        |--------------------------------------------------------------------------
        | Responsible Name
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
        | Default Verification Status
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
        | Audit
        |--------------------------------------------------------------------------
        */

        $validated['created_by'] =
            Auth::id();

        $validated['updated_by'] =
            Auth::id();


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        $finding =
            ConstructionHseInspectionFinding::create(
                $validated
            );


        /*
        |--------------------------------------------------------------------------
        | Update Inspection Status
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $inspection->status,
                [
                    'Verified',
                    'Closed',
                ],
                true
            )
        ) {

            $inspection->update([
                'status' => 'Findings Raised',
                'updated_by' => Auth::id(),
            ]);
        }


        return redirect()
            ->route(
                'admin.projects.construction.hse.inspections.findings.index',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                ]
            )
            ->with(
                'success',
                'Inspection finding created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    /*public function show(
        Project $project,
        ConstructionHseInspection $inspection,
        ConstructionHseInspectionFinding $finding
    ): View {

        $this->validateFindingRelation(
            $project,
            $inspection,
            $finding
        );


        $finding->load([
            'inspectionItem',
            'responsibleUser',
            'verifiedBy',
            'creator',
            'updater',
        ]);


        return view(
            'construction.hse.inspection-findings.show',
            [
                'project' => $project,
                'inspection' => $inspection,
                'finding' => $finding,
            ]
        );
    }*/

    public function show(
        Project $project,
        ConstructionHseInspection $inspection,
        ConstructionHseInspectionFinding $finding
    ): View {

        $this->validateFindingRelation(
            $project,
            $inspection,
            $finding
        );

        $finding->load([
            'inspectionItem',
            'responsibleUser',
            'verifiedBy',
            'creator',
            'updater',
            'actions.responsibleUser',
        ]);

        return view(
            'construction.hse.inspection-findings.show',
            [
                'project' => $project,
                'inspection' => $inspection,
                'finding' => $finding,
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
        ConstructionHseInspectionFinding $finding
    ): View {

        $this->validateFindingRelation(
            $project,
            $inspection,
            $finding
        );


        $items = $inspection
            ->items()
            ->orderBy('id')
            ->get();


        $users = User::query()
            ->orderBy('name')
            ->get();


        return view(
            'construction.hse.inspection-findings.edit',
            [
                'project' => $project,
                'inspection' => $inspection,
                'finding' => $finding,
                'items' => $items,
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
        ConstructionHseInspectionFinding $finding
    ): RedirectResponse {

        $this->validateFindingRelation(
            $project,
            $inspection,
            $finding
        );


        $validated = $request->validate([

            'construction_hse_inspection_item_id' => [
                'nullable',
                'integer',
            ],

            'finding_date' => [
                'required',
                'date',
            ],

            'finding_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'finding_title' => [
                'required',
                'string',
                'max:255',
            ],

            'finding_description' => [
                'required',
                'string',
            ],

            'severity' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'immediate_action' => [
                'nullable',
                'string',
            ],

            'recommended_action' => [
                'nullable',
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
                'in:Open,In Progress,Action Required,Resolved,Verified,Closed',
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
        | Validate Checklist Item
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'construction_hse_inspection_item_id'
                ]
            )
        ) {

            $itemExists =
                $inspection
                    ->items()
                    ->where(
                        'id',
                        $validated[
                            'construction_hse_inspection_item_id'
                        ]
                    )
                    ->exists();


            abort_unless(
                $itemExists,
                404
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Responsible Name
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
        | Verification
        |--------------------------------------------------------------------------
        */

        if (
            ($validated['status'] ?? null) === 'Verified'
        ) {

            $validated['verification_status'] =
                'Verified';


            $validated['verified_date'] =
                $validated['verified_date']
                ?? now()->toDateString();


            $validated['verified_by'] =
                Auth::id();
        }


        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $validated['updated_by'] =
            Auth::id();


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $finding->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.construction.hse.inspections.findings.show',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                    'finding' => $finding,
                ]
            )
            ->with(
                'success',
                'Inspection finding updated successfully.'
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
        ConstructionHseInspectionFinding $finding
    ): RedirectResponse {

        $this->validateFindingRelation(
            $project,
            $inspection,
            $finding
        );


        if (
            $finding->status === 'Closed'
        ) {

            return back()
                ->with(
                    'error',
                    'A closed finding cannot be deleted.'
                );
        }


        $finding->delete();


        /*
        |--------------------------------------------------------------------------
        | Recalculate Inspection Status
        |--------------------------------------------------------------------------
        */

        if (
            !$inspection
                ->findings()
                ->exists()
            &&
            $inspection->status === 'Findings Raised'
        ) {

            $inspection->update([
                'status' => 'Completed',
                'updated_by' => Auth::id(),
            ]);
        }


        return redirect()
            ->route(
                'admin.projects.construction.hse.inspections.findings.index',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                ]
            )
            ->with(
                'success',
                'Inspection finding deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE FINDING NUMBER
    |--------------------------------------------------------------------------
    */

    private function generateFindingNumber(): string
    {
        $lastFinding =
            ConstructionHseInspectionFinding::query()
                ->orderByDesc('id')
                ->first();


        $nextNumber =
            $lastFinding
                ? $lastFinding->id + 1
                : 1;


        return 'HSE-FND-' .
            str_pad(
                $nextNumber,
                4,
                '0',
                STR_PAD_LEFT
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE INSPECTION
    |--------------------------------------------------------------------------
    */

    private function validateInspectionRelation(
        Project $project,
        ConstructionHseInspection $inspection
    ): void {

        abort_unless(
            (int) $inspection->project_id ===
            (int) $project->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE FINDING
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
}