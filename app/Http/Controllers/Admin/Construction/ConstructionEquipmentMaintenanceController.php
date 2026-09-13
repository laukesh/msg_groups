<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionEquipment;
use App\Models\ConstructionEquipmentMaintenance;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConstructionEquipmentMaintenanceController extends Controller
{
    /**
     * Maintenance listing.
     *
     * Maintenance is equipment-level, but displayed
     * inside the selected project construction module.
     */
    public function index(
        Request $request,
        Project $project
    ): View {

        $equipmentIds = ConstructionEquipmentMaintenance::query()
            ->whereHas('equipment', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->pluck('equipment_id');

        $query = ConstructionEquipmentMaintenance::query()
            ->whereIn('equipment_id', $equipmentIds)
            ->with([
                'equipment',
                'creator',
                'updater',
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
                    'maintenance_number',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'maintenance_vendor',
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
        | Maintenance Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('maintenance_type')) {

            $query->where(
                'maintenance_type',
                $request->maintenance_type
            );
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

        /*
        |--------------------------------------------------------------------------
        | Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {

            $query->whereDate(
                'maintenance_date',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {

            $query->whereDate(
                'maintenance_date',
                '<=',
                $request->to_date
            );
        }

        $maintenances = $query
            ->orderByDesc('maintenance_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summaryQuery =
            ConstructionEquipmentMaintenance::query()
                ->whereIn('equipment_id', $equipmentIds);

        $totalMaintenance =
            (clone $summaryQuery)->count();

        $scheduled =
            (clone $summaryQuery)
                ->where('status', 'Scheduled')
                ->count();

        $inProgress =
            (clone $summaryQuery)
                ->where('status', 'In Progress')
                ->count();

        $completed =
            (clone $summaryQuery)
                ->where('status', 'Completed')
                ->count();

        $totalCost =
            (clone $summaryQuery)
                ->sum('cost');

        return view(
            'construction.equipment.maintenance.index',
            compact(
                'project',
                'maintenances',
                'totalMaintenance',
                'scheduled',
                'inProgress',
                'completed',
                'totalCost'
            )
        );
    }


    /**
     * Create maintenance record.
     */
    public function create(
        Project $project
    ): View {

        $equipment = ConstructionEquipment::query()
            ->whereIn(
                'status',
                [
                    'Available',
                    'Deployed',
                    'Under Maintenance',
                    'Breakdown',
                ]
            )
            ->orderBy('equipment_name')
            ->get();

        return view(
            'construction.equipment.maintenance.create',
            compact(
                'project',
                'equipment'
            )
        );
    }


    /**
     * Store maintenance record.
     */
    public function store(
        Request $request,
        Project $project
    ) {

        $validated = $request->validate([

            'equipment_id' =>
                'required|exists:construction_equipment,id',

            'maintenance_type' =>
                'required|in:Preventive,Corrective,Breakdown,Inspection,Servicing',

            'scheduled_date' =>
                'nullable|date',

            'maintenance_date' =>
                'nullable|date',

            'meter_reading' =>
                'nullable|numeric|min:0',

            'issue_description' =>
                'nullable|string',

            'work_performed' =>
                'nullable|string',

            'maintenance_vendor' =>
                'nullable|string|max:150',

            'cost' =>
                'nullable|numeric|min:0',

            'next_service_date' =>
                'nullable|date',

            'next_service_meter' =>
                'nullable|numeric|min:0',

            'remarks' =>
                'nullable|string',
        ]);

        $equipment = ConstructionEquipment::query()
            ->findOrFail(
                $validated['equipment_id']
            );

        /*
        |--------------------------------------------------------------------------
        | Create Maintenance
        |--------------------------------------------------------------------------
        */

        $maintenance = DB::transaction(
            function () use (
                $validated,
                $equipment
            ) {

                return ConstructionEquipmentMaintenance::create([

                    'equipment_id' =>
                        $equipment->id,

                    'maintenance_number' =>
                        $this->generateMaintenanceNumber(),

                    'maintenance_type' =>
                        $validated['maintenance_type'],

                    'scheduled_date' =>
                        $validated['scheduled_date']
                        ?? null,

                    'maintenance_date' =>
                        $validated['maintenance_date']
                        ?? null,

                    'meter_reading' =>
                        $validated['meter_reading']
                        ?? null,

                    'issue_description' =>
                        $validated['issue_description']
                        ?? null,

                    'work_performed' =>
                        $validated['work_performed']
                        ?? null,

                    'maintenance_vendor' =>
                        $validated['maintenance_vendor']
                        ?? null,

                    'cost' =>
                        $validated['cost']
                        ?? 0,

                    'status' =>
                        'Scheduled',

                    'next_service_date' =>
                        $validated['next_service_date']
                        ?? null,

                    'next_service_meter' =>
                        $validated['next_service_meter']
                        ?? null,

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
                'admin.projects.construction.equipment.maintenance.show',
                [
                    'project' => $project,
                    'maintenance' => $maintenance,
                ]
            )
            ->with(
                'success',
                'Equipment maintenance record created successfully.'
            );
    }


    /**
     * Maintenance detail.
     */
    public function show(
        Project $project,
        ConstructionEquipmentMaintenance $maintenance
    ): View {

        $maintenance->load([
            'equipment',
            'creator',
            'updater',
        ]);

        return view(
            'construction.equipment.maintenance.show',
            compact(
                'project',
                'maintenance'
            )
        );
    }


    /**
     * Start maintenance.
     */
    public function start(
        Project $project,
        ConstructionEquipmentMaintenance $maintenance
    ) {

        if ($maintenance->status !== 'Scheduled') {

            return back()
                ->with(
                    'error',
                    'Only Scheduled maintenance can be started.'
                );
        }

        DB::transaction(function () use ($maintenance) {

            $equipment =
                ConstructionEquipment::query()
                    ->lockForUpdate()
                    ->findOrFail(
                        $maintenance->equipment_id
                    );

            $maintenance->status =
                'In Progress';

            $maintenance->updated_by =
                auth()->id();

            $maintenance->save();

            $equipment->status =
                'Under Maintenance';

            $equipment->updated_by =
                auth()->id();

            $equipment->save();
        });

        return back()
            ->with(
                'success',
                'Maintenance started successfully.'
            );
    }


    /**
     * Complete maintenance.
     */
    public function complete(
        Request $request,
        Project $project,
        ConstructionEquipmentMaintenance $maintenance
    ) {

        if ($maintenance->status !== 'In Progress') {

            return back()
                ->with(
                    'error',
                    'Only In Progress maintenance can be completed.'
                );
        }

        $validated = $request->validate([

            'maintenance_date' =>
                'required|date',

            'meter_reading' =>
                'nullable|numeric|min:0',

            'work_performed' =>
                'required|string',

            'maintenance_vendor' =>
                'nullable|string|max:150',

            'cost' =>
                'nullable|numeric|min:0',

            'next_service_date' =>
                'nullable|date',

            'next_service_meter' =>
                'nullable|numeric|min:0',

            'remarks' =>
                'nullable|string',
        ]);

        DB::transaction(function () use (
            $maintenance,
            $validated
        ) {

            $equipment =
                ConstructionEquipment::query()
                    ->lockForUpdate()
                    ->findOrFail(
                        $maintenance->equipment_id
                    );

            $maintenance->maintenance_date =
                $validated['maintenance_date'];

            $maintenance->meter_reading =
                $validated['meter_reading']
                ?? $maintenance->meter_reading;

            $maintenance->work_performed =
                $validated['work_performed'];

            $maintenance->maintenance_vendor =
                $validated['maintenance_vendor']
                ?? $maintenance->maintenance_vendor;

            $maintenance->cost =
                $validated['cost']
                ?? $maintenance->cost;

            $maintenance->next_service_date =
                $validated['next_service_date']
                ?? null;

            $maintenance->next_service_meter =
                $validated['next_service_meter']
                ?? null;

            $maintenance->status =
                'Completed';

            $maintenance->remarks =
                $validated['remarks']
                ?? $maintenance->remarks;

            $maintenance->updated_by =
                auth()->id();

            $maintenance->save();

            /*
            |--------------------------------------------------------------------------
            | Equipment becomes available after maintenance.
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
                'Maintenance completed successfully.'
            );
    }


    /**
     * Cancel maintenance.
     */
    public function cancel(
        Project $project,
        ConstructionEquipmentMaintenance $maintenance
    ) {

        if ($maintenance->status !== 'Scheduled') {

            return back()
                ->with(
                    'error',
                    'Only Scheduled maintenance can be cancelled.'
                );
        }

        $maintenance->status =
            'Cancelled';

        $maintenance->updated_by =
            auth()->id();

        $maintenance->save();

        return back()
            ->with(
                'success',
                'Maintenance cancelled successfully.'
            );
    }


    /**
     * Delete maintenance.
     */
    public function destroy(
        Project $project,
        ConstructionEquipmentMaintenance $maintenance
    ) {

        $maintenance->updated_by =
            auth()->id();

        $maintenance->save();

        $maintenance->delete();

        return redirect()
            ->route(
                'admin.projects.construction.equipment.maintenance.index',
                $project
            )
            ->with(
                'success',
                'Maintenance record deleted successfully.'
            );
    }


    /**
     * Generate maintenance number.
     */
    protected function generateMaintenanceNumber(): string
    {
        $next =
            (
                ConstructionEquipmentMaintenance
                    ::withTrashed()
                    ->max('id')
                ?? 0
            ) + 1;

        return 'MNT-'
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