<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionMaterial;
use App\Models\ConstructionMaterialRequirement;
use App\Models\ConstructionWorkOrder;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConstructionMaterialRequirementController extends Controller
{
    /**
     * Material Requirements
     */
    public function index(
        Project $project,
        Request $request
    ): View {
        $query = ConstructionMaterialRequirement::query()
            ->where('project_id', $project->id)
            ->with([
                'material',
                'workOrder',
                'creator',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->search
            );

            $query->where(function ($q) use ($search) {

                $q->whereHas(
                    'material',
                    function ($materialQuery) use ($search) {

                        $materialQuery
                            ->where(
                                'material_code',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'material_name',
                                'like',
                                '%' . $search . '%'
                            );
                    }
                );

                $q->orWhereHas(
                    'workOrder',
                    function ($workOrderQuery) use ($search) {

                        $workOrderQuery
                            ->where(
                                'work_order_number',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'work_order_title',
                                'like',
                                '%' . $search . '%'
                            );
                    }
                );

                $q->orWhere(
                    'purpose',
                    'like',
                    '%' . $search . '%'
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $requirements = $query
            ->orderByDesc('required_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summaryQuery = ConstructionMaterialRequirement::query()
            ->where(
                'project_id',
                $project->id
            );

        $summary = [
            'total' => (clone $summaryQuery)->count(),

            'draft' => (clone $summaryQuery)
                ->where('status', 'Draft')
                ->count(),

            'requested' => (clone $summaryQuery)
                ->where('status', 'Requested')
                ->count(),

            'partially_fulfilled' => (clone $summaryQuery)
                ->where(
                    'status',
                    'Partially Fulfilled'
                )
                ->count(),

            'fulfilled' => (clone $summaryQuery)
                ->where('status', 'Fulfilled')
                ->count(),
        ];

        return view(
            'construction.materials.requirements.index',
            compact(
                'project',
                'requirements',
                'summary'
            )
        );
    }


    /**
     * Create Requirement
     */
    public function create(
        Project $project
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Active Materials
        |--------------------------------------------------------------------------
        */

        $materials = ConstructionMaterial::query()
            ->where('status', 'Active')
            ->orderBy('material_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Project Work Orders
        |--------------------------------------------------------------------------
        */

        $workOrders = ConstructionWorkOrder::query()
            ->where(
                'project_id',
                $project->id
            )
            ->orderBy(
                'work_order_number'
            )
            ->get();

        return view(
            'construction.materials.requirements.create',
            compact(
                'project',
                'materials',
                'workOrders'
            )
        );
    }


    /**
     * Store Requirement
     */
    public function store(
        Request $request,
        Project $project
    ) {

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'construction_work_order_id' => [
                'nullable',
                'integer',
                'exists:construction_work_orders,id',
            ],

            'material_id' => [
                'required',
                'integer',
                'exists:construction_materials,id',
            ],

            'required_quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'unit' => [
                'required',
                'string',
                'max:50',
            ],

            'required_date' => [
                'nullable',
                'date',
            ],

            'purpose' => [
                'nullable',
                'string',
                'max:255',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validate Work Order Belongs to Project
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['construction_work_order_id']
            )
        ) {

            $workOrder =
                ConstructionWorkOrder::query()
                    ->where(
                        'id',
                        $validated[
                            'construction_work_order_id'
                        ]
                    )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->first();

            if (!$workOrder) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'construction_work_order_id' =>
                            'Selected work order does not belong to this project.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Material Active
        |--------------------------------------------------------------------------
        */

        $material =
            ConstructionMaterial::query()
                ->where(
                    'id',
                    $validated['material_id']
                )
                ->where(
                    'status',
                    'Active'
                )
                ->first();

        if (!$material) {

            return back()
                ->withInput()
                ->withErrors([
                    'material_id' =>
                        'Selected material is not active.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Create Requirement
        |--------------------------------------------------------------------------
        */

        $requirement =
            new ConstructionMaterialRequirement();

        $requirement->project_id =
            $project->id;

        $requirement->construction_work_order_id =
            $validated[
                'construction_work_order_id'
            ] ?? null;

        $requirement->material_id =
            $validated['material_id'];

        $requirement->required_quantity =
            $validated['required_quantity'];

        $requirement->unit =
            $validated['unit'];

        $requirement->required_date =
            $validated['required_date']
            ?? null;

        $requirement->purpose =
            $validated['purpose']
            ?? null;

        $requirement->status =
            'Draft';

        $requirement->remarks =
            $validated['remarks']
            ?? null;

        $requirement->created_by =
            Auth::id();

        $requirement->save();

        return redirect()
            ->route(
                'admin.projects.construction.materials.requirements.show',
                [
                    'project' =>
                        $project->id,

                    'requirement' =>
                        $requirement->id,
                ]
            )
            ->with(
                'success',
                'Material requirement created successfully.'
            );
    }


    /**
     * Show Requirement
     */
    public function show(
        Project $project,
        ConstructionMaterialRequirement $requirement
    ): View {

        $this->validateProjectRequirement(
            $project,
            $requirement
        );

        $requirement->load([
            'material',
            'workOrder',
            'creator',
            'updater',
        ]);

        return view(
            'construction.materials.requirements.show',
            compact(
                'project',
                'requirement'
            )
        );
    }


    /**
     * Edit Requirement
     */
    public function edit(
        Project $project,
        ConstructionMaterialRequirement $requirement
    ): View {

        $this->validateProjectRequirement(
            $project,
            $requirement
        );

        /*
        |--------------------------------------------------------------------------
        | Only Draft / Requested Can Be Edited
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $requirement->status,
                [
                    'Draft',
                    'Requested',
                ],
                true
            )
        ) {

            return redirect()
                ->route(
                    'admin.projects.construction.materials.requirements.show',
                    [
                        'project' =>
                            $project->id,

                        'requirement' =>
                            $requirement->id,
                    ]
                )
                ->with(
                    'error',
                    'This requirement cannot be edited in its current status.'
                );
        }

        $materials = ConstructionMaterial::query()
            ->where('status', 'Active')
            ->orderBy('material_name')
            ->get();

        $workOrders = ConstructionWorkOrder::query()
            ->where(
                'project_id',
                $project->id
            )
            ->orderBy(
                'work_order_number'
            )
            ->get();

        return view(
            'construction.materials.requirements.edit',
            compact(
                'project',
                'requirement',
                'materials',
                'workOrders'
            )
        );
    }


    /**
     * Update Requirement
     */
    public function update(
        Request $request,
        Project $project,
        ConstructionMaterialRequirement $requirement
    ) {

        $this->validateProjectRequirement(
            $project,
            $requirement
        );

        /*
        |--------------------------------------------------------------------------
        | Status Validation
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $requirement->status,
                [
                    'Draft',
                    'Requested',
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

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'construction_work_order_id' => [
                'nullable',
                'integer',
                'exists:construction_work_orders,id',
            ],

            'material_id' => [
                'required',
                'integer',
                'exists:construction_materials,id',
            ],

            'required_quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'unit' => [
                'required',
                'string',
                'max:50',
            ],

            'required_date' => [
                'nullable',
                'date',
            ],

            'purpose' => [
                'nullable',
                'string',
                'max:255',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validate Work Order
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['construction_work_order_id']
            )
        ) {

            $workOrder =
                ConstructionWorkOrder::query()
                    ->where(
                        'id',
                        $validated[
                            'construction_work_order_id'
                        ]
                    )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->first();

            if (!$workOrder) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'construction_work_order_id' =>
                            'Selected work order does not belong to this project.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Material
        |--------------------------------------------------------------------------
        */

        $material =
            ConstructionMaterial::query()
                ->where(
                    'id',
                    $validated['material_id']
                )
                ->where(
                    'status',
                    'Active'
                )
                ->first();

        if (!$material) {

            return back()
                ->withInput()
                ->withErrors([
                    'material_id' =>
                        'Selected material is not active.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $requirement->construction_work_order_id =
            $validated[
                'construction_work_order_id'
            ] ?? null;

        $requirement->material_id =
            $validated['material_id'];

        $requirement->required_quantity =
            $validated['required_quantity'];

        $requirement->unit =
            $validated['unit'];

        $requirement->required_date =
            $validated['required_date']
            ?? null;

        $requirement->purpose =
            $validated['purpose']
            ?? null;

        $requirement->remarks =
            $validated['remarks']
            ?? null;

        $requirement->updated_by =
            Auth::id();

        $requirement->save();

        return redirect()
            ->route(
                'admin.projects.construction.materials.requirements.show',
                [
                    'project' =>
                        $project->id,

                    'requirement' =>
                        $requirement->id,
                ]
            )
            ->with(
                'success',
                'Material requirement updated successfully.'
            );
    }


    /**
     * Submit / Request Requirement
     */
    public function request(
        Project $project,
        ConstructionMaterialRequirement $requirement
    ) {

        $this->validateProjectRequirement(
            $project,
            $requirement
        );

        if (
            $requirement->status !==
            'Draft'
        ) {

            return back()
                ->with(
                    'error',
                    'Only draft requirements can be submitted.'
                );
        }

        $requirement->status =
            'Requested';

        $requirement->updated_by =
            Auth::id();

        $requirement->save();

        return back()
            ->with(
                'success',
                'Material requirement submitted successfully.'
            );
    }


    /**
     * Cancel Requirement
     */
    public function cancel(
        Project $project,
        ConstructionMaterialRequirement $requirement
    ) {

        $this->validateProjectRequirement(
            $project,
            $requirement
        );

        if (
            in_array(
                $requirement->status,
                [
                    'Fulfilled',
                    'Cancelled',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'This requirement cannot be cancelled.'
                );
        }

        $requirement->status =
            'Cancelled';

        $requirement->updated_by =
            Auth::id();

        $requirement->save();

        return back()
            ->with(
                'success',
                'Material requirement cancelled successfully.'
            );
    }


    /**
     * Validate Project
     */
    private function validateProjectRequirement(
        Project $project,
        ConstructionMaterialRequirement $requirement
    ): void {

        if (
            (int) $requirement->project_id !==
            (int) $project->id
        ) {
            abort(404);
        }
    }
}