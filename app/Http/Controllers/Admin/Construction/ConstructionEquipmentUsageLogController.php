<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionEquipment;
use App\Models\ConstructionEquipmentDeployment;
use App\Models\ConstructionEquipmentUsageLog;
use App\Models\ConstructionWorkOrder;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConstructionEquipmentUsageLogController extends Controller
{
    /**
     * Usage log listing.
     */
    public function index(
        Request $request,
        Project $project
    ): View {

        $query = ConstructionEquipmentUsageLog::query()
            ->where('project_id', $project->id)
            ->with([
                'equipment',
                'workOrder',
                'deployment',
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
                    'usage_number',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'work_description',
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
        | Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {

            $query->whereDate(
                'usage_date',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {

            $query->whereDate(
                'usage_date',
                '<=',
                $request->to_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Listing
        |--------------------------------------------------------------------------
        */

        $usageLogs = $query
            ->orderByDesc('usage_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summaryQuery = ConstructionEquipmentUsageLog::query()
            ->where('project_id', $project->id);

        $totalLogs = (clone $summaryQuery)->count();

        $totalOperatingHours = (clone $summaryQuery)
            ->sum('operating_hours');

        $totalIdleHours = (clone $summaryQuery)
            ->sum('idle_hours');

        $totalBreakdownHours = (clone $summaryQuery)
            ->sum('breakdown_hours');

        $totalFuelConsumed = (clone $summaryQuery)
            ->sum('fuel_consumed');

        return view(
            'construction.equipment.usage.index',
            compact(
                'project',
                'usageLogs',
                'totalLogs',
                'totalOperatingHours',
                'totalIdleHours',
                'totalBreakdownHours',
                'totalFuelConsumed'
            )
        );
    }


    /**
     * Create usage log.
     */
    public function create(
        Project $project
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Only deployed equipment
        |--------------------------------------------------------------------------
        */

        $deployments = ConstructionEquipmentDeployment::query()
            ->where('project_id', $project->id)
            ->where('status', 'Deployed')
            ->with([
                'equipment',
                'workOrder',
                'operator',
            ])
            ->orderByDesc('deployment_date')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Work Orders
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
            'construction.equipment.usage.create',
            compact(
                'project',
                'deployments',
                'workOrders',
                'users'
            )
        );
    }


    /**
     * Store usage log.
     */
    public function store(
        Request $request,
        Project $project
    ) {

        $validated = $request->validate([

            'equipment_deployment_id' =>
                'required|exists:construction_equipment_deployments,id',

            'construction_work_order_id' =>
                'nullable|exists:construction_work_orders,id',

            'usage_date' =>
                'required|date',

            'operator_id' =>
                'nullable|exists:users,id',

            'opening_meter' =>
                'nullable|numeric|min:0',

            'closing_meter' =>
                'nullable|numeric|min:0',

            'operating_hours' =>
                'nullable|numeric|min:0',

            'idle_hours' =>
                'nullable|numeric|min:0',

            'fuel_consumed' =>
                'nullable|numeric|min:0',

            'fuel_unit' =>
                'nullable|string|max:30',

            'work_description' =>
                'nullable|string',

            'breakdown_hours' =>
                'nullable|numeric|min:0',

            'remarks' =>
                'nullable|string',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Deployment
        |--------------------------------------------------------------------------
        */

        $deployment = ConstructionEquipmentDeployment::query()
            ->with([
                'equipment',
                'workOrder',
            ])
            ->where('id', $validated['equipment_deployment_id'])
            ->where('project_id', $project->id)
            ->firstOrFail();

        if ($deployment->status !== 'Deployed') {

            return back()
                ->withInput()
                ->withErrors([
                    'equipment_deployment_id' =>
                        'Only deployed equipment can have usage logs.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Work Order Validation
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
                        $validated['construction_work_order_id']
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
        | Meter Validation
        |--------------------------------------------------------------------------
        */

        if (
            $validated['closing_meter'] !== null
            && $validated['opening_meter'] !== null
            && $validated['closing_meter']
                < $validated['opening_meter']
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'closing_meter' =>
                        'Closing meter cannot be less than opening meter.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Operating Hours
        |--------------------------------------------------------------------------
        */

        $operatingHours =
            $validated['operating_hours']
            ?? 0;

        if (
            $operatingHours == 0
            && $validated['closing_meter'] !== null
            && $validated['opening_meter'] !== null
        ) {

            $operatingHours =
                $validated['closing_meter']
                - $validated['opening_meter'];
        }

        /*
        |--------------------------------------------------------------------------
        | Create Usage Log
        |--------------------------------------------------------------------------
        */

        $usageLog = DB::transaction(
            function () use (
                $validated,
                $project,
                $deployment,
                $operatingHours
            ) {

                return ConstructionEquipmentUsageLog::create([

                    'equipment_id' =>
                        $deployment->equipment_id,

                    'project_id' =>
                        $project->id,

                    'construction_work_order_id' =>
                        $validated[
                            'construction_work_order_id'
                        ] ?? $deployment->construction_work_order_id,

                    'equipment_deployment_id' =>
                        $deployment->id,

                    'usage_number' =>
                        $this->generateUsageNumber(),

                    'usage_date' =>
                        $validated['usage_date'],

                    'operator_id' =>
                        $validated['operator_id']
                        ?? $deployment->operator_id,

                    'opening_meter' =>
                        $validated['opening_meter']
                        ?? null,

                    'closing_meter' =>
                        $validated['closing_meter']
                        ?? null,

                    'operating_hours' =>
                        $operatingHours,

                    'idle_hours' =>
                        $validated['idle_hours']
                        ?? 0,

                    'fuel_consumed' =>
                        $validated['fuel_consumed']
                        ?? null,

                    'fuel_unit' =>
                        $validated['fuel_unit']
                        ?? null,

                    'work_description' =>
                        $validated['work_description']
                        ?? null,

                    'breakdown_hours' =>
                        $validated['breakdown_hours']
                        ?? 0,

                    'remarks' =>
                        $validated['remarks']
                        ?? null,

                    'created_by' =>
                        auth()->id(),
                ]);
            }
        );

        return redirect()
            ->route(
                'admin.projects.construction.equipment.usage.show',
                [
                    'project' => $project,
                    'usageLog' => $usageLog,
                ]
            )
            ->with(
                'success',
                'Equipment usage log created successfully.'
            );
    }


    /**
     * Usage detail.
     */
    public function show(
        Project $project,
        ConstructionEquipmentUsageLog $usageLog
    ): View {

        abort_unless(
            $usageLog->project_id == $project->id,
            404
        );

        $usageLog->load([
            'equipment',
            'project',
            'workOrder',
            'deployment',
            'operator',
            'creator',
            'updater',
        ]);

        return view(
            'construction.equipment.usage.show',
            compact(
                'project',
                'usageLog'
            )
        );
    }


    /**
     * Delete usage log.
     */
    public function destroy(
        Project $project,
        ConstructionEquipmentUsageLog $usageLog
    ) {

        abort_unless(
            $usageLog->project_id == $project->id,
            404
        );

        $usageLog->updated_by = auth()->id();
        $usageLog->save();

        $usageLog->delete();

        return redirect()
            ->route(
                'admin.projects.construction.equipment.usage.index',
                $project
            )
            ->with(
                'success',
                'Equipment usage log deleted successfully.'
            );
    }


    /**
     * Generate usage number.
     */
    protected function generateUsageNumber(): string
    {
        $next =
            (
                ConstructionEquipmentUsageLog
                    ::withTrashed()
                    ->max('id')
                ?? 0
            ) + 1;

        return 'USE-'
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