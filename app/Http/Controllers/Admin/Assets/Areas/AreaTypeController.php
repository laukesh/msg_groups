<?php

namespace App\Http\Controllers\Admin\Assets\Areas;

use App\Http\Controllers\Controller;
use App\Models\AreaType;
use App\Models\PropertyArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaTypeController extends Controller
{
    public function index()
    {
        return response()->json(
            AreaType::query()
                ->orderBy('name')
                ->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:50', 'unique:area_types,name'],
            'category'    => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'status'      => ['nullable', 'string', 'max:30'],
        ]);

        $type = AreaType::create($validated);

        return response()->json([
            'message' => 'Area type created successfully.',
            'data'    => $type,
        ], 201);
    }

    public function show(AreaType $areaType)
    {
        return response()->json([
            'data' => $areaType,
        ]);
    }

    public function update(Request $request, AreaType $areaType)
    {
        $validated = $request->validate([
            'name'        => ['sometimes', 'string', 'max:50'],
            'category'    => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'status'      => ['sometimes', 'string', 'max:30'],
        ]);

        $areaType->update($validated);

        return response()->json([
            'message' => 'Area type updated successfully.',
            'data'    => $areaType->fresh(),
        ]);
    }

    public function destroy(AreaType $areaType)
    {
        $areaType->delete();

        return response()->json([
            'message' => 'Area type deleted successfully.',
        ]);
    }
}


/*
|--------------------------------------------------------------------------
| AreaReportController.php
|--------------------------------------------------------------------------
*/

class AreaReportController extends Controller
{
    /**
     * Area summary.
     */
    public function summary(Request $request)
    {
        $query = PropertyArea::query()
            ->where('status', 'ACTIVE')
            ->whereNull('deleted_at');

        if ($request->filled('mall_id')) {
            $query->where('mall_id', $request->mall_id);
        }

        $summary = $query->selectRaw('
            COUNT(*) AS total_properties,
            COALESCE(SUM(gross_area), 0) AS total_gross_area,
            COALESCE(SUM(net_area), 0) AS total_net_area,
            COALESCE(SUM(usable_area), 0) AS total_usable_area,
            COALESCE(SUM(rentable_area), 0) AS total_rentable_area,
            COALESCE(SUM(chargeable_area), 0) AS total_chargeable_area,
            COALESCE(SUM(common_area), 0) AS total_common_area,
            COALESCE(SUM(service_area), 0) AS total_service_area
        ')
        ->first();

        return response()->json([
            'data' => $summary,
        ]);
    }

    /**
     * Area summary by mall.
     */
    public function byMall()
    {
        $data = PropertyArea::query()
            ->where('status', 'ACTIVE')
            ->whereNull('deleted_at')
            ->select(
                'mall_id',
                DB::raw('COUNT(*) AS total_properties'),
                DB::raw('SUM(gross_area) AS total_gross_area'),
                DB::raw('SUM(net_area) AS total_net_area'),
                DB::raw('SUM(rentable_area) AS total_rentable_area'),
                DB::raw('SUM(chargeable_area) AS total_chargeable_area')
            )
            ->groupBy('mall_id')
            ->orderBy('mall_id')
            ->get();

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Area summary by building.
     */
    public function byBuilding(Request $request)
    {
        $data = PropertyArea::query()
            ->where('status', 'ACTIVE')
            ->whereNull('deleted_at')
            ->when(
                $request->mall_id,
                fn ($query, $mallId) => $query->where('mall_id', $mallId)
            )
            ->select(
                'building_id',
                DB::raw('COUNT(*) AS total_properties'),
                DB::raw('SUM(gross_area) AS total_gross_area'),
                DB::raw('SUM(net_area) AS total_net_area'),
                DB::raw('SUM(rentable_area) AS total_rentable_area'),
                DB::raw('SUM(chargeable_area) AS total_chargeable_area')
            )
            ->groupBy('building_id')
            ->orderBy('building_id')
            ->get();

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Revision report.
     */
    public function revisions(Request $request)
    {
        $query = \App\Models\AreaRevision::query()
            ->with('propertyArea')
            ->latest('revision_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('property_area_id')) {
            $query->where(
                'property_area_id',
                $request->property_area_id
            );
        }

        return response()->json(
            $query->paginate(25)
        );
    }
}
