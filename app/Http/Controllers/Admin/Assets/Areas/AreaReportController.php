<?php

namespace App\Http\Controllers\Admin\Assets\Areas;

use App\Http\Controllers\Controller;
use App\Models\PropertyArea;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AreaReportController extends Controller
{
    /**
     * Area summary report.
     */
    public function summary(Request $request): View
    {
        $query = $this->baseQuery($request);

        $summary = (clone $query)
            ->selectRaw('COUNT(*) as total_properties')
            ->selectRaw('COALESCE(SUM(gross_area), 0) as total_gross_area')
            ->selectRaw('COALESCE(SUM(net_area), 0) as total_net_area')
            ->selectRaw('COALESCE(SUM(usable_area), 0) as total_usable_area')
            ->selectRaw('COALESCE(SUM(rentable_area), 0) as total_rentable_area')
            ->selectRaw('COALESCE(SUM(chargeable_area), 0) as total_chargeable_area')
            ->selectRaw('COALESCE(SUM(common_area), 0) as total_common_area')
            ->selectRaw('COALESCE(SUM(service_area), 0) as total_service_area')
            ->first();

        return view('admin.assets.areas.reports.summary', compact(
            'summary'
        ));
    }

    /**
     * Gross Leasable Area report.
     */
    public function gla(Request $request): View
    {
        $areas = $this->baseQuery($request)
            ->whereNotNull('gross_area')
            ->with([
                'mall:id,mall_name',
                'building:id,building_name',
                'floor:id,floor_name',
                'unit:id,unit_no',
            ])
            ->select([
                'id',
                'mall_id',
                'building_id',
                'floor_id',
                'zone_id',
                'unit_id',
                'area_type_id',
                'area_category',
                'gross_area',
                'measurement_unit',
                'measurement_date',
                'effective_from',
                'effective_to',
            ])
            ->orderBy('mall_id')
            ->orderBy('building_id')
            ->orderBy('floor_id')
            ->orderBy('unit_id')
            ->paginate(50)
            ->withQueryString();

        return view('admin.assets.areas.reports.gla', compact(
            'areas'
        ));
    }

    /**
     * Net Leasable Area report.
     */
    public function nla(Request $request): View
    {
        $areas = $this->baseQuery($request)
            ->whereNotNull('net_area')
            ->with([
                'mall:id,mall_name',
                'building:id,building_name',
                'floor:id,floor_name',
                'unit:id,unit_no',
            ])
            ->select([
                'id',
                'mall_id',
                'building_id',
                'floor_id',
                'zone_id',
                'unit_id',
                'area_type_id',
                'area_category',
                'net_area',
                'measurement_unit',
                'measurement_date',
                'effective_from',
                'effective_to',
            ])
            ->orderBy('mall_id')
            ->orderBy('building_id')
            ->orderBy('floor_id')
            ->orderBy('unit_id')
            ->paginate(50)
            ->withQueryString();

        return view('admin.assets.areas.reports.nla', compact(
            'areas'
        ));
    }

    /**
     * Occupancy report.
     *
     * This calculates occupancy from rentable areas.
     *
     * If your application has a dedicated lease/tenancy table,
     * replace the occupied-area calculation with that relationship.
     */
    public function occupancy(Request $request): View
{
    /*
    |--------------------------------------------------------------------------
    | Base Query
    |--------------------------------------------------------------------------
    */

    $query = $this->baseQuery($request);


    /*
    |--------------------------------------------------------------------------
    | Total Rentable Area
    |--------------------------------------------------------------------------
    */

    $totalRentableArea = (clone $query)
        ->whereNotNull('rentable_area')
        ->sum('rentable_area');


    /*
    |--------------------------------------------------------------------------
    | Occupied Area
    |--------------------------------------------------------------------------
    |
    | Change this calculation according to your actual Unit / Lease
    | occupancy structure.
    |
    */

    $occupiedArea = (clone $query)
        ->whereNotNull('rentable_area')
        ->whereHas('unit', function ($q) {
            $q->whereIn('status', [
                'OCCUPIED',
                'occupied',
                'ACTIVE',
                'active',
            ]);
        })
        ->sum('rentable_area');


    /*
    |--------------------------------------------------------------------------
    | Overall Occupancy Rate
    |--------------------------------------------------------------------------
    */

    $occupancyRate = $totalRentableArea > 0
        ? ($occupiedArea / $totalRentableArea) * 100
        : 0;


    /*
    |--------------------------------------------------------------------------
    | Area Records
    |--------------------------------------------------------------------------
    */

    $areas = $query
        ->whereNotNull('rentable_area')
        ->with([
            'mall:id,mall_name',
            'building:id,building_name',
            'floor:id,floor_name',

            // Include the actual unit fields used by your units table.
            'unit:id,unit_no,status',
        ])
        ->select([
            'id',
            'mall_id',
            'building_id',
            'floor_id',
            'zone_id',
            'unit_id',
            'area_type_id',
            'rentable_area',
            'measurement_unit',
        ])
        ->orderBy('mall_id')
        ->orderBy('building_id')
        ->orderBy('floor_id')
        ->orderBy('unit_id')
        ->paginate(50)
        ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | Return View
    |--------------------------------------------------------------------------
    */

    return view(
        'admin.assets.areas.reports.occupancy',
        compact(
            'areas',
            'totalRentableArea',
            'occupiedArea',
            'occupancyRate'
        )
    );
}

    /**
     * Base query shared by all reports.
     */
    protected function baseQuery(Request $request): Builder
    {
        $query = PropertyArea::query()
            ->whereNull('deleted_at');

        /*
         * Only current/active measurements by default.
         */
        if (!$request->boolean('include_inactive')) {
            $query->where('status', 'ACTIVE');
        }

        if ($request->filled('mall_id')) {
            $query->where('mall_id', $request->input('mall_id'));
        }

        if ($request->filled('building_id')) {
            $query->where('building_id', $request->input('building_id'));
        }

        if ($request->filled('floor_id')) {
            $query->where('floor_id', $request->input('floor_id'));
        }

        if ($request->filled('zone_id')) {
            $query->where('zone_id', $request->input('zone_id'));
        }

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->input('unit_id'));
        }

        if ($request->filled('area_type_id')) {
            $query->where('area_type_id', $request->input('area_type_id'));
        }

        if ($request->filled('area_category')) {
            $query->where(
                'area_category',
                $request->input('area_category')
            );
        }

        if ($request->filled('measurement_unit')) {
            $query->where(
                'measurement_unit',
                $request->input('measurement_unit')
            );
        }

        if ($request->filled('measurement_date_from')) {
            $query->whereDate(
                'measurement_date',
                '>=',
                $request->input('measurement_date_from')
            );
        }

        if ($request->filled('measurement_date_to')) {
            $query->whereDate(
                'measurement_date',
                '<=',
                $request->input('measurement_date_to')
            );
        }

        if ($request->filled('effective_from')) {
            $query->whereDate(
                'effective_from',
                '>=',
                $request->input('effective_from')
            );
        }

        if ($request->filled('effective_to')) {
            $query->whereDate(
                'effective_to',
                '<=',
                $request->input('effective_to')
            );
        }

        return $query;
    }
}
