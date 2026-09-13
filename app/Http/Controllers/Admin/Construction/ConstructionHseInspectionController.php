<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionHseInspection;
use App\Models\Project;
use App\Models\ProcurementContract;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConstructionHseInspectionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project
    ): View {

        $inspections = ConstructionHseInspection::query()
            ->where('project_id', $project->id)
            ->with([
                'inspector',
                'creator',
            ])
            ->latest('id')
            ->get();

        return view(
            'construction.hse.inspections.index',
            [
                'project' => $project,
                'inspections' => $inspections,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        Project $project
    ): View {

        $inspectionNumber =
            $this->generateInspectionNumber();

        $users = User::query()
            ->orderBy('name')
            ->get();

        $contracts = ProcurementContract::query()
            ->orderBy('id', 'desc')
            ->get();

        return view(
            'construction.hse.inspections.create',
            [
                'project' => $project,
                'inspectionNumber' => $inspectionNumber,
                'users' => $users,
                'contracts' => $contracts,
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
        Project $project
    ): RedirectResponse {

        $validated = $request->validate([

            'inspection_date' => [
                'required',
                'date',
            ],

            'inspection_type' => [
                'required',
                'string',
                'max:100',
            ],

            'inspection_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'procurement_contract_id' => [
                'nullable',
                'integer',
                'exists:procurement_contracts,id',
            ],

            'inspector_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'inspector_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'contractor_id' => [
                'nullable',
                'integer',
            ],

            'scope' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Planned,In Progress,Completed,Findings Raised,Verified,Closed',
            ],

            'findings_summary' => [
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
        | Generate Number
        |--------------------------------------------------------------------------
        */

        $validated['inspection_number'] =
            $this->generateInspectionNumber();


        /*
        |--------------------------------------------------------------------------
        | Project
        |--------------------------------------------------------------------------
        */

        $validated['project_id'] =
            $project->id;


        /*
        |--------------------------------------------------------------------------
        | Inspector Name
        |--------------------------------------------------------------------------
        */

        if (
            !empty($validated['inspector_id'])
        ) {

            $inspector = User::find(
                $validated['inspector_id']
            );

            $validated['inspector_name'] =
                $inspector?->name;
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

        $inspection =
            ConstructionHseInspection::create(
                $validated
            );


        return redirect()
            ->route(
                'admin.projects.construction.hse.inspections.show',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                ]
            )
            ->with(
                'success',
                'HSE inspection created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionHseInspection $inspection
    ): View {

        $this->validateProjectRelation(
            $project,
            $inspection
        );


        $inspection->load([
            'inspector',
            'creator',
            'updater',
        ]);


        return view(
            'construction.hse.inspections.show',
            [
                'project' => $project,
                'inspection' => $inspection,
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
        ConstructionHseInspection $inspection
    ): View {

        $this->validateProjectRelation(
            $project,
            $inspection
        );


        $users = User::query()
            ->orderBy('name')
            ->get();


        $contracts = ProcurementContract::query()
            ->orderBy('id', 'desc')
            ->get();


        return view(
            'construction.hse.inspections.edit',
            [
                'project' => $project,
                'inspection' => $inspection,
                'users' => $users,
                'contracts' => $contracts,
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
        ConstructionHseInspection $inspection
    ): RedirectResponse {

        $this->validateProjectRelation(
            $project,
            $inspection
        );


        $validated = $request->validate([

            'inspection_date' => [
                'required',
                'date',
            ],

            'inspection_type' => [
                'required',
                'string',
                'max:100',
            ],

            'inspection_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'procurement_contract_id' => [
                'nullable',
                'integer',
                'exists:procurement_contracts,id',
            ],

            'inspector_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'inspector_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'contractor_id' => [
                'nullable',
                'integer',
            ],

            'scope' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Planned,In Progress,Completed,Findings Raised,Verified,Closed',
            ],

            'findings_summary' => [
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
        | Inspector Name
        |--------------------------------------------------------------------------
        */

        if (
            !empty($validated['inspector_id'])
        ) {

            $inspector = User::find(
                $validated['inspector_id']
            );

            $validated['inspector_name'] =
                $inspector?->name;
        }


        $validated['updated_by'] =
            Auth::id();


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $inspection->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.construction.hse.inspections.show',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                ]
            )
            ->with(
                'success',
                'HSE inspection updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionHseInspection $inspection
    ): RedirectResponse {

        $this->validateProjectRelation(
            $project,
            $inspection
        );


        /*
        |--------------------------------------------------------------------------
        | Do not delete closed inspections
        |--------------------------------------------------------------------------
        */

        if (
            $inspection->status === 'Closed'
        ) {

            return back()
                ->with(
                    'error',
                    'A closed inspection cannot be deleted.'
                );
        }


        $inspection->delete();


        return redirect()
            ->route(
                'admin.projects.construction.hse.inspections.index',
                [
                    'project' => $project,
                ]
            )
            ->with(
                'success',
                'HSE inspection deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE INSPECTION NUMBER
    |--------------------------------------------------------------------------
    */

    private function generateInspectionNumber(): string
    {
        $lastInspection =
            ConstructionHseInspection::query()
                ->orderByDesc('id')
                ->first();


        $nextNumber =
            $lastInspection
                ? $lastInspection->id + 1
                : 1;


        return 'HSE-INSP-' .
            str_pad(
                $nextNumber,
                4,
                '0',
                STR_PAD_LEFT
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PROJECT RELATION VALIDATION
    |--------------------------------------------------------------------------
    */

    private function validateProjectRelation(
        Project $project,
        ConstructionHseInspection $inspection
    ): void {

        abort_unless(
            (int) $inspection->project_id ===
            (int) $project->id,
            404
        );
    }
}