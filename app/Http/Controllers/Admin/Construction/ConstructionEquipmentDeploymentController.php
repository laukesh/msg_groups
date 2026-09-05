<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionEquipment;
use App\Models\ConstructionEquipmentDeployment;
use App\Models\ConstructionWorkOrder;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConstructionEquipmentDeploymentController extends Controller
{
    /**
     * Deployment list.
     */
    public function index(
        Request $request,
        Project $project
    ): View {

        $query = ConstructionEquipmentDeployment::query()
            ->where('project_id', $project->id)
            ->with([
                'equipment',
                'workOrder',
                'operator',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'deployment_number',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'location',
                    'like',
                    "%{$search}%"
                )
                ->orWhereHas(
                    'equipment',
                    function ($equipmentQuery) use ($search) {

                        $equipmentQuery
                            ->where(
                                'equipment_code',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'equipment_name',
                                'like',
                                "%{$search}%"
                            );
                    }
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        $deployments = $query
            ->orderByDesc('deployment_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalDeployments =
            ConstructionEquipmentDeployment::query()
                ->where('project_id', $project->id)
                ->count();

        $plannedDeployments =
            ConstructionEquipmentDeployment::query()
                ->where('project_id', $project->id)
                ->where('status', 'Planned')
                ->count();

        $activeDeployments =
            ConstructionEquipmentDeployment::query()
                ->where('project_id', $project->id)
                ->where('status', 'Deployed')
                ->count();

        $returnedDeployments =
            ConstructionEquipmentDeployment::query()
                ->where('project_id', $project->id)
                ->where('status', 'Returned')
                ->count();


        return view(
            'construction.equipment.deployments.index',
            compact(
                'project',
                'deployments',
                'totalDeployments',
                'plannedDeployments',
                'activeDeployments',
                'returnedDeployments'
            )
        );
    }


    /**
     * Create deployment.
     */
    public function create(
        Project $project
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Only available equipment can be deployed.
        |--------------------------------------------------------------------------
        */

        $equipment = ConstructionEquipment::query()
            ->where('status', 'Available')
            ->orderBy('equipment_name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Existing project work orders.
        |--------------------------------------------------------------------------
        */

        $workOrders = ConstructionWorkOrder::query()
            ->where('project_id', $project->id)
            ->orderByDesc('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Operators
        |--------------------------------------------------------------------------
        */

        $users = User::query()
            ->orderBy('name')
            ->get();


        return view(
            'construction.equipment.deployments.create',
            compact(
                'project',
                'equipment',
                'workOrders',
                'users'
            )
        );
    }


    /**
     * Store deployment.
     */
    public function store(
        Request $request,
        Project $project
    ) {

        $validated = $request->validate([

            'equipment_id' =>
                'required|exists:construction_equipment,id',

            'construction_work_order_id' =>
                'nullable|exists:construction_work_orders,id',

            'deployment_date' =>
                'required|date',

            'operator_id' =>
                'nullable|exists:users,id',

            'location' =>
                'nullable|string|max:255',

            'starting_meter' =>
                'nullable|numeric|min:0',

            'remarks' =>
                'nullable|string',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Equipment validation
        |--------------------------------------------------------------------------
        */

        $equipment = ConstructionEquipment::query()
            ->lockForUpdate()
            ->findOrFail(
                $validated['equipment_id']
            );


        if ($equipment->status !== 'Available') {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Selected equipment is not available for deployment.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Work Order must belong to this project.
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['construction_work_order_id']
            )
        ) {

            $workOrderExists =
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
                    ->exists();

            if (!$workOrderExists) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'construction_work_order_id' =>
                            'Selected Work Order does not belong to this project.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Create deployment
        |--------------------------------------------------------------------------
        */

        $deployment = DB::transaction(function () use (
            $validated,
            $project,
            $equipment
        ) {

            $deployment =
                ConstructionEquipmentDeployment::create([

                    'equipment_id' =>
                        $equipment->id,

                    'project_id' =>
                        $project->id,

                    'construction_work_order_id' =>
                        $validated[
                            'construction_work_order_id'
                        ] ?? null,

                    'deployment_number' =>
                        $this->generateDeploymentNumber(),

                    'deployment_date' =>
                        $validated['deployment_date'],

                    'operator_id' =>
                        $validated['operator_id']
                        ?? null,

                    'location' =>
                        $validated['location']
                        ?? null,

                    'status' =>
                        'Planned',

                    'starting_meter' =>
                        $validated['starting_meter']
                        ?? null,

                    'remarks' =>
                        $validated['remarks']
                        ?? null,

                    'created_by' =>
                        auth()->id(),
                ]);


            return $deployment;
        });


        return redirect()
            ->route(
                'admin.projects.construction.equipment.deployments.show',
                [
                    'project' => $project,
                    'deployment' => $deployment,
                ]
            )
            ->with(
                'success',
                'Equipment deployment created successfully.'
            );
    }


    /**
     * Deployment detail.
     */
    public function show(
        Project $project,
        ConstructionEquipmentDeployment $deployment
    ): View {

        abort_unless(
            $deployment->project_id == $project->id,
            404
        );


        $deployment->load([
            'equipment',
            'workOrder',
            'operator',
            'creator',
            'usageLogs' => function ($query) {

                $query
                    ->with([
                        'operator',
                        'workOrder',
                    ])
                    ->latest('usage_date')
                    ->latest('id');
            },
        ]);


        return view(
            'construction.equipment.deployments.show',
            compact(
                'project',
                'deployment'
            )
        );
    }


    /**
     * Deploy equipment.
     */
    public function deploy(
        Project $project,
        ConstructionEquipmentDeployment $deployment
    ) {

        abort_unless(
            $deployment->project_id == $project->id,
            404
        );


        if ($deployment->status !== 'Planned') {

            return back()
                ->with(
                    'error',
                    'Only Planned deployments can be deployed.'
                );
        }


        $updated = DB::transaction(function () use (
            $deployment
        ) {

            $equipment =
                ConstructionEquipment::query()
                    ->lockForUpdate()
                    ->findOrFail(
                        $deployment->equipment_id
                    );


            if ($equipment->status !== 'Available') {

                return false;
            }


            $deployment->status =
                'Deployed';

            $deployment->save();


            $equipment->status =
                'Deployed';

            $equipment->updated_by =
                auth()->id();

            $equipment->save();


            return true;
        });


        if (!$updated) {

            return back()
                ->with(
                    'error',
                    'Equipment is no longer available.'
                );
        }


        return back()
            ->with(
                'success',
                'Equipment deployed successfully.'
            );
    }


    /**
     * Return equipment.
     */
    public function returnEquipment(
        Request $request,
        Project $project,
        ConstructionEquipmentDeployment $deployment
    ) {

        abort_unless(
            $deployment->project_id == $project->id,
            404
        );


        if ($deployment->status !== 'Deployed') {

            return back()
                ->with(
                    'error',
                    'Only deployed equipment can be returned.'
                );
        }


        $validated = $request->validate([

            'return_date' =>
                'required|date|after_or_equal:deployment_date',

            'ending_meter' =>
                'nullable|numeric|min:0',

            'remarks' =>
                'nullable|string',
        ]);


        if (
            $validated['ending_meter'] !== null &&
            $deployment->starting_meter !== null &&
            $validated['ending_meter']
                < $deployment->starting_meter
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'ending_meter' =>
                        'Ending meter cannot be less than starting meter.',
                ]);
        }


        DB::transaction(function () use (
            $deployment,
            $validated
        ) {

            $equipment =
                ConstructionEquipment::query()
                    ->lockForUpdate()
                    ->findOrFail(
                        $deployment->equipment_id
                    );


            $deployment->return_date =
                $validated['return_date'];

            $deployment->ending_meter =
                $validated['ending_meter']
                ?? null;

            $deployment->status =
                'Returned';

            if (!empty($validated['remarks'])) {

                $deployment->remarks =
                    $validated['remarks'];
            }

            $deployment->updated_by =
                auth()->id();

            $deployment->save();


            /*
            |--------------------------------------------------------------------------
            | Equipment becomes available again.
            |--------------------------------------------------------------------------
            */

            $equipment->status =
                'Available';

            $equipment->updated_by =
                auth()->id();

            $equipment->save();
        });


        return back()
            ->with(
                'success',
                'Equipment returned successfully.'
            );
    }


    /**
     * Cancel planned deployment.
     */
    public function cancel(
        Project $project,
        ConstructionEquipmentDeployment $deployment
    ) {

        abort_unless(
            $deployment->project_id == $project->id,
            404
        );


        if ($deployment->status !== 'Planned') {

            return back()
                ->with(
                    'error',
                    'Only Planned deployments can be cancelled.'
                );
        }


        $deployment->status =
            'Cancelled';

        $deployment->updated_by =
            auth()->id();

        $deployment->save();


        return back()
            ->with(
                'success',
                'Equipment deployment cancelled.'
            );
    }


    /**
     * Generate deployment number.
     */
    protected function generateDeploymentNumber(): string
    {
        $next =
            (ConstructionEquipmentDeployment::withTrashed()->max('id') ?? 0)
            + 1;

        return 'DEP-'
            . now()->format('Y')
            . '-'
            . str_pad(
                $next,
                6,
                '0',
                STR_PAD_LEFT
            );
    }
}