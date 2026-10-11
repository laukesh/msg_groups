<?php

namespace App\Http\Controllers\Admin\Leasing;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class SpaceAvailabilityController extends Controller
{
    /**
     * Space Availability Inventory
     *
     * Real-time inventory of leasable spaces including:
     * - Unit
     * - Mall
     * - Building
     * - Floor
     * - Zone
     * - Category / Unit Type
     * - Area
     * - Asking Rent
     * - Status
     * - Fit-out information
     */
    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */

        $query = DB::table('units')
            ->leftJoin('malls', 'malls.id', '=', 'units.mall_id')
            ->leftJoin('buildings', 'buildings.id', '=', 'units.building_id')
            ->leftJoin('floors', 'floors.id', '=', 'units.floor_id')
            ->leftJoin('zones', 'zones.id', '=', 'units.zone_id')
            ->leftJoin('unit_types', 'unit_types.id', '=', 'units.unit_type_id')
            ->leftJoin('unit_statuses', 'unit_statuses.id', '=', 'units.unit_status_id')
            ->whereNull('units.deleted_at');


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {

                $q->where(
                    'units.unit_no',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'units.shop_name',
                    'like',
                    "%{$search}%"
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Mall
        |--------------------------------------------------------------------------
        */

        $query->when(
            $request->filled('mall_id'),
            fn ($q) => $q->where(
                'units.mall_id',
                $request->input('mall_id')
            )
        );


        /*
        |--------------------------------------------------------------------------
        | Building
        |--------------------------------------------------------------------------
        */

        $query->when(
            $request->filled('building_id'),
            fn ($q) => $q->where(
                'units.building_id',
                $request->input('building_id')
            )
        );


        /*
        |--------------------------------------------------------------------------
        | Floor
        |--------------------------------------------------------------------------
        */

        $query->when(
            $request->filled('floor_id'),
            fn ($q) => $q->where(
                'units.floor_id',
                $request->input('floor_id')
            )
        );


        /*
        |--------------------------------------------------------------------------
        | Zone
        |--------------------------------------------------------------------------
        */

        $query->when(
            $request->filled('zone_id'),
            fn ($q) => $q->where(
                'units.zone_id',
                $request->input('zone_id')
            )
        );


        /*
        |--------------------------------------------------------------------------
        | Unit Type / Category
        |--------------------------------------------------------------------------
        */

        $query->when(
            $request->filled('unit_type_id'),
            fn ($q) => $q->where(
                'units.unit_type_id',
                $request->input('unit_type_id')
            )
        );


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        $query->when(
            $request->filled('status'),
            fn ($q) => $q->where(
                'units.current_status',
                $request->input('status')
            )
        );


        /*
        |--------------------------------------------------------------------------
        | Minimum Area
        |--------------------------------------------------------------------------
        */

        $query->when(
            $request->filled('area_min'),
            fn ($q) => $q->where(
                'units.carpet_area',
                '>=',
                $request->input('area_min')
            )
        );


        /*
        |--------------------------------------------------------------------------
        | Maximum Area
        |--------------------------------------------------------------------------
        */

        $query->when(
            $request->filled('area_max'),
            fn ($q) => $q->where(
                'units.carpet_area',
                '<=',
                $request->input('area_max')
            )
        );


        /*
        |--------------------------------------------------------------------------
        | Minimum Rent
        |--------------------------------------------------------------------------
        */

        $query->when(
            $request->filled('rent_min'),
            fn ($q) => $q->where(
                'units.monthly_rent',
                '>=',
                $request->input('rent_min')
            )
        );


        /*
        |--------------------------------------------------------------------------
        | Maximum Rent
        |--------------------------------------------------------------------------
        */

        $query->when(
            $request->filled('rent_max'),
            fn ($q) => $q->where(
                'units.monthly_rent',
                '<=',
                $request->input('rent_max')
            )
        );


        /*
        |--------------------------------------------------------------------------
        | KPI
        |--------------------------------------------------------------------------
        */

        $totalSpaces = (clone $query)->count('units.id');


        $availableSpaces = (clone $query)
            ->whereIn(
                'units.current_status',
                [
                    'Vacant',
                    'Available',
                ]
            )
            ->count('units.id');


        $occupiedSpaces = (clone $query)
            ->where(
                'units.current_status',
                'Occupied'
            )
            ->count('units.id');


        $reservedSpaces = (clone $query)
            ->where(
                'units.current_status',
                'Reserved'
            )
            ->count('units.id');


        $fitoutSpaces = (clone $query)
            ->whereIn(
                'units.current_status',
                [
                    'Under Renovation',
                    'Under Fit-Out',
                ]
            )
            ->count('units.id');


        $totalArea = (clone $query)
            ->sum('units.carpet_area');


        $availableArea = (clone $query)
            ->whereIn(
                'units.current_status',
                [
                    'Vacant',
                    'Available',
                ]
            )
            ->sum('units.carpet_area');


        /*
        |--------------------------------------------------------------------------
        | Inventory
        |--------------------------------------------------------------------------
        */

        $areas = (clone $query)
            ->select([
                'units.id',
                'units.mall_id',
                'units.building_id',
                'units.floor_id',
                'units.zone_id',
                'units.unit_type_id',
                'units.unit_status_id',

                'units.unit_no',
                'units.current_status',
                'units.shop_name',

                'units.carpet_area',
                'units.builtup_area',
                'units.frontage',

                'units.monthly_rent',
                'units.security_deposit',

                'units.remarks',

                'malls.mall_name',

                'buildings.building_name',

                'floors.floor_name',

                'zones.zone_name',

                'unit_types.type_name',

                'unit_statuses.status_name',
                'unit_statuses.color_code',
            ])
            ->orderBy('units.mall_id')
            ->orderBy('units.building_id')
            ->orderBy('units.floor_id')
            ->orderBy('units.unit_no')
            ->paginate(50)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Filter Dropdowns
        |--------------------------------------------------------------------------
        */

        $malls = DB::table('malls')
            ->whereNull('deleted_at')
            ->orderBy('mall_name')
            ->get([
                'id',
                'mall_name',
            ]);


        $buildings = DB::table('buildings')
            ->whereNull('deleted_at')
            ->orderBy('building_name')
            ->get([
                'id',
                'building_name',
            ]);


        $floors = DB::table('floors')
            ->whereNull('deleted_at')
            ->orderBy('floor_name')
            ->get([
                'id',
                'floor_name',
            ]);


        $zones = DB::table('zones')
            ->whereNull('deleted_at')
            ->orderBy('zone_name')
            ->get([
                'id',
                'zone_name',
            ]);


        $unitTypes = DB::table('unit_types')
            ->whereNull('deleted_at')
            ->orderBy('type_name')
            ->get([
                'id',
                'type_name',
            ]);


        $statuses = DB::table('unit_statuses')
            ->whereNull('deleted_at')
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->get([
                'id',
                'status_name',
                'color_code',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.leasing.space-availability.index',
            compact(
                'areas',
                'totalSpaces',
                'availableSpaces',
                'occupiedSpaces',
                'reservedSpaces',
                'fitoutSpaces',
                'totalArea',
                'availableArea',
                'malls',
                'buildings',
                'floors',
                'zones',
                'unitTypes',
                'statuses'
            )
        );
    }


    /**
     * Show complete availability details.
     */
    public function show(Unit $unit): View
    {
        /*
        |--------------------------------------------------------------------------
        | Unit Details
        |--------------------------------------------------------------------------
        */

        $unitData = DB::table('units')
            ->leftJoin(
                'malls',
                'malls.id',
                '=',
                'units.mall_id'
            )
            ->leftJoin(
                'buildings',
                'buildings.id',
                '=',
                'units.building_id'
            )
            ->leftJoin(
                'floors',
                'floors.id',
                '=',
                'units.floor_id'
            )
            ->leftJoin(
                'zones',
                'zones.id',
                '=',
                'units.zone_id'
            )
            ->leftJoin(
                'unit_types',
                'unit_types.id',
                '=',
                'units.unit_type_id'
            )
            ->leftJoin(
                'unit_statuses',
                'unit_statuses.id',
                '=',
                'units.unit_status_id'
            )
            ->where(
                'units.id',
                $unit->id
            )
            ->whereNull('units.deleted_at')
            ->select([
                'units.*',

                'malls.mall_name',
                'buildings.building_name',
                'floors.floor_name',
                'zones.zone_name',

                'unit_types.type_name',

                'unit_statuses.status_name',
                'unit_statuses.color_code',
            ])
            ->first();


        abort_if(
            !$unitData,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Floor Plans
        |--------------------------------------------------------------------------
        */

        $floorPlans = DB::table('unit_documents')
            ->where(
                'unit_id',
                $unit->id
            )
            ->whereNull('deleted_at')
            ->where(function ($q) {

                $q->where(
                    'document_type',
                    'like',
                    '%floor%'
                )
                ->orWhere(
                    'document_name',
                    'like',
                    '%floor%'
                )
                ->orWhere(
                    'document_type',
                    'like',
                    '%plan%'
                )
                ->orWhere(
                    'document_name',
                    'like',
                    '%plan%'
                );

            })
            ->orderByDesc('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Fit-Out Requests
        |--------------------------------------------------------------------------
        */

        $fitouts = DB::table('fitout_requests')
            ->where(
                'unit_id',
                $unit->id
            )
            ->whereNull('deleted_at')
            ->orderByDesc('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Rent / Service Charge
        |--------------------------------------------------------------------------
        */

        $rentSchedules = DB::table('rent_schedules')
            ->join(
                'lease_agreements',
                'lease_agreements.id',
                '=',
                'rent_schedules.lease_agreement_id'
            )
            ->where(
                'lease_agreements.unit_id',
                $unit->id
            )
            ->whereNull('rent_schedules.deleted_at')
            ->orderByDesc('rent_schedules.period_start')
            ->limit(12)
            ->get([
                'rent_schedules.*',
            ]);


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.leasing.space-availability.show',
            compact(
                'unitData',
                'floorPlans',
                'fitouts',
                'rentSchedules'
            )
        );
    }


    /**
     * Export Space Availability as CSV.
     */
    public function export(Request $request): Response
    {
        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */

        $query = DB::table('units')
            ->leftJoin(
                'malls',
                'malls.id',
                '=',
                'units.mall_id'
            )
            ->leftJoin(
                'buildings',
                'buildings.id',
                '=',
                'units.building_id'
            )
            ->leftJoin(
                'floors',
                'floors.id',
                '=',
                'units.floor_id'
            )
            ->leftJoin(
                'zones',
                'zones.id',
                '=',
                'units.zone_id'
            )
            ->leftJoin(
                'unit_types',
                'unit_types.id',
                '=',
                'units.unit_type_id'
            )
            ->leftJoin(
                'unit_statuses',
                'unit_statuses.id',
                '=',
                'units.unit_status_id'
            )
            ->whereNull('units.deleted_at');


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {

                $q->where(
                    'units.unit_no',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'units.shop_name',
                    'like',
                    "%{$search}%"
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        $query->when(
            $request->filled('mall_id'),
            fn ($q) => $q->where(
                'units.mall_id',
                $request->input('mall_id')
            )
        );

        $query->when(
            $request->filled('building_id'),
            fn ($q) => $q->where(
                'units.building_id',
                $request->input('building_id')
            )
        );

        $query->when(
            $request->filled('floor_id'),
            fn ($q) => $q->where(
                'units.floor_id',
                $request->input('floor_id')
            )
        );

        $query->when(
            $request->filled('zone_id'),
            fn ($q) => $q->where(
                'units.zone_id',
                $request->input('zone_id')
            )
        );

        $query->when(
            $request->filled('unit_type_id'),
            fn ($q) => $q->where(
                'units.unit_type_id',
                $request->input('unit_type_id')
            )
        );

        $query->when(
            $request->filled('status'),
            fn ($q) => $q->where(
                'units.current_status',
                $request->input('status')
            )
        );

        $query->when(
            $request->filled('area_min'),
            fn ($q) => $q->where(
                'units.carpet_area',
                '>=',
                $request->input('area_min')
            )
        );

        $query->when(
            $request->filled('area_max'),
            fn ($q) => $q->where(
                'units.carpet_area',
                '<=',
                $request->input('area_max')
            )
        );

        $query->when(
            $request->filled('rent_min'),
            fn ($q) => $q->where(
                'units.monthly_rent',
                '>=',
                $request->input('rent_min')
            )
        );

        $query->when(
            $request->filled('rent_max'),
            fn ($q) => $q->where(
                'units.monthly_rent',
                '<=',
                $request->input('rent_max')
            )
        );


        /*
        |--------------------------------------------------------------------------
        | Get Data
        |--------------------------------------------------------------------------
        */

        $rows = $query
            ->orderBy('units.mall_id')
            ->orderBy('units.building_id')
            ->orderBy('units.floor_id')
            ->orderBy('units.unit_no')
            ->get([
                'units.unit_no',

                'malls.mall_name',

                'buildings.building_name',

                'floors.floor_name',

                'zones.zone_name',

                'unit_types.type_name',

                'units.carpet_area',
                'units.builtup_area',
                'units.frontage',

                'units.monthly_rent',
                'units.security_deposit',

                'units.current_status',
            ]);


        /*
        |--------------------------------------------------------------------------
        | CSV
        |--------------------------------------------------------------------------
        */

        $handle = fopen('php://temp', 'r+');

        /*
        | UTF-8 BOM
        */
        fwrite(
            $handle,
            "\xEF\xBB\xBF"
        );


        fputcsv(
            $handle,
            [
                'Unit',
                'Mall',
                'Building',
                'Floor',
                'Zone',
                'Category',
                'Carpet Area',
                'Built-up Area',
                'Frontage',
                'Asking Rent',
                'Security Deposit',
                'Status',
            ]
        );


        foreach ($rows as $row) {

            fputcsv(
                $handle,
                [
                    $row->unit_no ?? '',
                    $row->mall_name ?? '',
                    $row->building_name ?? '',
                    $row->floor_name ?? '',
                    $row->zone_name ?? '',
                    $row->type_name ?? '',

                    $row->carpet_area !== null
                        ? number_format(
                            (float) $row->carpet_area,
                            2,
                            '.',
                            ''
                        )
                        : '',

                    $row->builtup_area !== null
                        ? number_format(
                            (float) $row->builtup_area,
                            2,
                            '.',
                            ''
                        )
                        : '',

                    $row->frontage ?? '',

                    $row->monthly_rent !== null
                        ? number_format(
                            (float) $row->monthly_rent,
                            2,
                            '.',
                            ''
                        )
                        : '',

                    $row->security_deposit !== null
                        ? number_format(
                            (float) $row->security_deposit,
                            2,
                            '.',
                            ''
                        )
                        : '',

                    $row->current_status ?? '',
                ]
            );
        }


        rewind($handle);

        $csv = stream_get_contents($handle);

        fclose($handle);


        /*
        |--------------------------------------------------------------------------
        | Download
        |--------------------------------------------------------------------------
        */

        return response(
            $csv,
            200,
            [
                'Content-Type' =>
                    'text/csv; charset=UTF-8',

                'Content-Disposition' =>
                    'attachment; filename="space-availability-' .
                    now()->format('Y-m-d-His') .
                    '.csv',

                'Cache-Control' =>
                    'no-store, no-cache, must-revalidate',
            ]
        );
    }
}