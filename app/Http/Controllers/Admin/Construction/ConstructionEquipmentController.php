<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionEquipment;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConstructionEquipmentController extends Controller
{
    public function index(
        Request $request,
        Project $project
    ): View {

        $query = ConstructionEquipment::query()
            ->with([
                'deployments' => function ($query) use ($project) {
                    $query->where('project_id', $project->id)
                        ->where('status', 'Deployed')
                        ->latest('id');
                },
            ]);

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'equipment_code',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'equipment_name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'registration_number',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'serial_number',
                    'like',
                    "%{$search}%"
                );

            });
        }

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('category')) {

            $query->where(
                'category',
                $request->category
            );
        }

        $equipment = $query
            ->orderBy('equipment_name')
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Dashboard Summary
        |--------------------------------------------------------------------------
        */

        $summaryQuery = ConstructionEquipment::query();

        $totalEquipment = $summaryQuery->count();

        $availableEquipment = (clone $summaryQuery)
            ->where('status', 'Available')
            ->count();

        $deployedEquipment = (clone $summaryQuery)
            ->where('status', 'Deployed')
            ->count();

        $maintenanceEquipment = (clone $summaryQuery)
            ->whereIn(
                'status',
                [
                    'Under Maintenance',
                    'Breakdown',
                ]
            )
            ->count();

        $categories = ConstructionEquipment::query()
            ->whereNotNull('category')
            ->where('category', '<>', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');


        return view(
            'construction.equipment.index',
            compact(
                'project',
                'equipment',
                'totalEquipment',
                'availableEquipment',
                'deployedEquipment',
                'maintenanceEquipment',
                'categories'
            )
        );
    }


    public function create(
        Project $project
    ): View {

        return view(
            'construction.equipment.create',
            compact('project')
        );
    }


    public function store(
        Request $request,
        Project $project
    ) {

        $validated = $request->validate([

            'equipment_name' =>
                'required|string|max:150',

            'category' =>
                'nullable|string|max:100',

            'ownership_type' =>
                'required|in:Owned,Hired,Leased',

            'make' =>
                'nullable|string|max:100',

            'model' =>
                'nullable|string|max:100',

            'serial_number' =>
                'nullable|string|max:100',

            'registration_number' =>
                'nullable|string|max:100',

            'capacity' =>
                'nullable|numeric|min:0',

            'capacity_unit' =>
                'nullable|string|max:50',

            'purchase_date' =>
                'nullable|date',

            'purchase_value' =>
                'nullable|numeric|min:0',

            'hire_rate' =>
                'nullable|numeric|min:0',

            'hire_rate_unit' =>
                'nullable|string|max:50',

            'status' =>
                'required|in:Available,Deployed,Under Maintenance,Breakdown,Retired',

            'description' =>
                'nullable|string',

            'remarks' =>
                'nullable|string',
        ]);


        $validated['equipment_code'] =
            $this->generateEquipmentCode();

        $validated['created_by'] =
            auth()->id();

        $equipment =
            ConstructionEquipment::create(
                $validated
            );


        return redirect()
            ->route(
                'admin.projects.construction.equipment.show',
                [
                    'project' => $project,
                    'equipment' => $equipment,
                ]
            )
            ->with(
                'success',
                'Equipment created successfully.'
            );
    }


    public function show(
        Project $project,
        ConstructionEquipment $equipment
    ): View {

        $equipment->load([
            'deployments' => function ($query) use ($project) {

                $query->where(
                    'project_id',
                    $project->id
                )
                ->with([
                    'workOrder',
                    'operator',
                ])
                ->latest('deployment_date');
            },

            'usageLogs' => function ($query) use ($project) {

                $query->where(
                    'project_id',
                    $project->id
                )
                ->with([
                    'workOrder',
                    'operator',
                ])
                ->latest('usage_date')
                ->limit(20);
            },

            'maintenanceRecords' => function ($query) {

                $query
                    ->latest('maintenance_date')
                    ->limit(20);
            },
        ]);


        $totalOperatingHours =
            $equipment->usageLogs
                ->sum('operating_hours');

        $totalIdleHours =
            $equipment->usageLogs
                ->sum('idle_hours');

        $totalMaintenanceCost =
            $equipment->maintenanceRecords
                ->sum('cost');


        return view(
            'construction.equipment.show',
            compact(
                'project',
                'equipment',
                'totalOperatingHours',
                'totalIdleHours',
                'totalMaintenanceCost'
            )
        );
    }


    public function edit(
        Project $project,
        ConstructionEquipment $equipment
    ): View {

        return view(
            'construction.equipment.edit',
            compact(
                'project',
                'equipment'
            )
        );
    }


    public function update(
        Request $request,
        Project $project,
        ConstructionEquipment $equipment
    ) {

        $validated = $request->validate([

            'equipment_name' =>
                'required|string|max:150',

            'category' =>
                'nullable|string|max:100',

            'ownership_type' =>
                'required|in:Owned,Hired,Leased',

            'make' =>
                'nullable|string|max:100',

            'model' =>
                'nullable|string|max:100',

            'serial_number' =>
                'nullable|string|max:100',

            'registration_number' =>
                'nullable|string|max:100',

            'capacity' =>
                'nullable|numeric|min:0',

            'capacity_unit' =>
                'nullable|string|max:50',

            'purchase_date' =>
                'nullable|date',

            'purchase_value' =>
                'nullable|numeric|min:0',

            'hire_rate' =>
                'nullable|numeric|min:0',

            'hire_rate_unit' =>
                'nullable|string|max:50',

            'status' =>
                'required|in:Available,Deployed,Under Maintenance,Breakdown,Retired',

            'description' =>
                'nullable|string',

            'remarks' =>
                'nullable|string',
        ]);


        $validated['updated_by'] =
            auth()->id();

        $equipment->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.construction.equipment.show',
                [
                    'project' => $project,
                    'equipment' => $equipment,
                ]
            )
            ->with(
                'success',
                'Equipment updated successfully.'
            );
    }


    public function destroy(
        Project $project,
        ConstructionEquipment $equipment
    ) {

        if (
            $equipment->deployments()
                ->whereIn(
                    'status',
                    [
                        'Planned',
                        'Deployed',
                    ]
                )
                ->exists()
        ) {

            return back()
                ->with(
                    'error',
                    'Equipment cannot be deleted while it is deployed.'
                );
        }

        $equipment->delete();

        return redirect()
            ->route(
                'admin.projects.construction.equipment.index',
                $project
            )
            ->with(
                'success',
                'Equipment deleted successfully.'
            );
    }


    protected function generateEquipmentCode(): string
    {
        $next =
            (ConstructionEquipment::withTrashed()->max('id') ?? 0)
            + 1;

        return 'EQP-'
            . str_pad(
                $next,
                6,
                '0',
                STR_PAD_LEFT
            );
    }
}