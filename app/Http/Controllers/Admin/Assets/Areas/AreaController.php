<?php

namespace App\Http\Controllers\Admin\Assets\Areas;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Floor;
use App\Models\Mall;
use App\Models\PropertyArea;
use App\Models\Unit;
use App\Models\Zone;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    /**
     * Display a listing of property areas.
     *
     * GET
     * admin/assets/areas
     */
    public function index(Request $request)
    {
        $areas = PropertyArea::query()
            ->with([
                'revisions' => function ($query) {
                    $query->latest('revision_number');
                },
            ])

            // Search
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = trim($request->search);

                    $query->where(function ($q) use ($search) {
                        $q->where(
                            'area_type_id',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'area_category',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'measurement_unit',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'measurement_method',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'measurement_standard',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'remarks',
                            'like',
                            "%{$search}%"
                        );
                    });
                }
            )

            // Property filters
            ->when(
                $request->filled('mall_id'),
                fn ($query) =>
                    $query->where(
                        'mall_id',
                        $request->mall_id
                    )
            )

            ->when(
                $request->filled('building_id'),
                fn ($query) =>
                    $query->where(
                        'building_id',
                        $request->building_id
                    )
            )

            ->when(
                $request->filled('floor_id'),
                fn ($query) =>
                    $query->where(
                        'floor_id',
                        $request->floor_id
                    )
            )

            ->when(
                $request->filled('zone_id'),
                fn ($query) =>
                    $query->where(
                        'zone_id',
                        $request->zone_id
                    )
            )

            ->when(
                $request->filled('unit_id'),
                fn ($query) =>
                    $query->where(
                        'unit_id',
                        $request->unit_id
                    )
            )

            // Area filters
            ->when(
                $request->filled('area_type_id'),
                fn ($query) =>
                    $query->where(
                        'area_type_id',
                        $request->area_type_id
                    )
            )

            ->when(
                $request->filled('area_category'),
                fn ($query) =>
                    $query->where(
                        'area_category',
                        $request->area_category
                    )
            )

            ->when(
                $request->filled('status'),
                fn ($query) =>
                    $query->where(
                        'status',
                        $request->status
                    )
            )

            // Sorting
            ->latest('id')

            // Pagination
            ->paginate(25)

            ->withQueryString();
 /*
    |--------------------------------------------------------------------------
    | Load Mall Dropdown
    |--------------------------------------------------------------------------
    */

    $malls = Mall::query()
        ->orderBy('mall_name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Load Building Dropdown
    |--------------------------------------------------------------------------
    */

    $buildings = Building::query()
        ->orderBy('building_name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Load Floor Dropdown
    |--------------------------------------------------------------------------
    */

    $floors = Floor::query()
        ->orderBy('floor_name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Load Zone Dropdown
    |--------------------------------------------------------------------------
    */

    $zones = Zone::query()
        ->orderBy('zone_name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Load Unit Dropdown
    |--------------------------------------------------------------------------
    */

    $units = Unit::query()
        ->orderBy('id')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Return Create View
    |--------------------------------------------------------------------------
    */

        return view(
            'admin.assets.areas.index',
            compact('areas', 'malls',
            'buildings',
            'floors',
            'zones',
            'units')
        );
    }

    /**
     * Show the form for creating a new property area.
     *
     * GET
     * admin/assets/areas/create
     */
   /**
 * Show the form for creating a new property area.
 *
 * GET:
 * admin/assets/areas/create
 */
public function create()
{
    /*
    |--------------------------------------------------------------------------
    | Load Mall Dropdown
    |--------------------------------------------------------------------------
    */

    $malls = Mall::query()
        ->orderBy('mall_name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Load Building Dropdown
    |--------------------------------------------------------------------------
    */

    $buildings = Building::query()
        ->orderBy('building_name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Load Floor Dropdown
    |--------------------------------------------------------------------------
    */

    $floors = Floor::query()
        ->orderBy('floor_name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Load Zone Dropdown
    |--------------------------------------------------------------------------
    */

    $zones = Zone::query()
        ->orderBy('zone_name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Load Unit Dropdown
    |--------------------------------------------------------------------------
    */

    $units = Unit::query()
        ->orderBy('id')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Return Create View
    |--------------------------------------------------------------------------
    */

    return view(
        'admin.assets.areas.create',
        compact(
            'malls',
            'buildings',
            'floors',
            'zones',
            'units'
        )
    );
}
    /**
     * Store a newly created property area.
     *
     * POST
     * admin/assets/areas
     */
    public function store(Request $request)
    {
        $validated = $this->validateArea(
            $request
        );

        /*
        |--------------------------------------------------------------------------
        | Default Status
        |--------------------------------------------------------------------------
        */

        $validated['status'] =
            $validated['status']
            ?? 'ACTIVE';

        /*
        |--------------------------------------------------------------------------
        | Create Area
        |--------------------------------------------------------------------------
        */

        $area = PropertyArea::create(
            $validated
        );

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.assets.areas.show',
                [
                    'area' => $area->id,
                ]
            )
            ->with(
                'success',
                'Property area created successfully.'
            );
    }

    /**
     * Display the specified property area.
     *
     * GET
     * admin/assets/areas/{area}
     */
    public function show(
        PropertyArea $area
    ) {
        /*
        |--------------------------------------------------------------------------
        | Load Relationships
        |--------------------------------------------------------------------------
        */

        $area->load([
            'revisions' => function ($query) {
                $query->latest(
                    'revision_number'
                );
            },
        ]);

        return view(
            'admin.assets.areas.show',
            compact('area')
        );
    }

    /**
     * Show the form for editing the specified property area.
     *
     * GET
     * admin/assets/areas/{area}/edit
     */
    public function edit(PropertyArea $area)
    {
        $malls = Mall::query()
            ->latest('id')
            ->get();

        $buildings = Building::query()
            ->latest('id')
            ->get();

        $floors = Floor::query()
            ->latest('id')
            ->get();

        $zones = Zone::query()
            ->latest('id')
            ->get();

        $units = Unit::query()
            ->latest('id')
            ->get();

        return view(
            'admin.assets.areas.edit',
            compact(
                'area',
                'malls',
                'buildings',
                'floors',
                'zones',
                'units'
            )
        );
    }

    /**
 * Update the specified property area.
 *
 * PUT
 * admin/assets/areas/{area}
 */
public function update(
    Request $request,
    PropertyArea $area
) {
    //dd($request->all());
    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([

        /*
        |--------------------------------------------------------------------------
        | Property Hierarchy
        |--------------------------------------------------------------------------
        */

        'mall_id' => [
            'required',
            'integer',
            'exists:malls,id',
        ],

        'building_id' => [
            'nullable',
            'integer',
            'exists:buildings,id',
        ],

        'floor_id' => [
            'nullable',
            'integer',
            'exists:floors,id',
        ],

        'zone_id' => [
            'nullable',
            'integer',
            'exists:zones,id',
        ],

        'unit_id' => [
            'nullable',
            'integer',
            'exists:units,id',
        ],


        /*
        |--------------------------------------------------------------------------
        | Area Classification
        |--------------------------------------------------------------------------
        */

        'area_type_id' => [
            'required',
            'string',
            'max:50',
        ],

        'area_category' => [
            'nullable',
            'string',
            'max:50',
        ],


        /*
        |--------------------------------------------------------------------------
        | Area Measurements
        |--------------------------------------------------------------------------
        */

        'gross_area' => [
            'nullable',
            'numeric',
            'min:0',
        ],

        'net_area' => [
            'nullable',
            'numeric',
            'min:0',
        ],

        'usable_area' => [
            'nullable',
            'numeric',
            'min:0',
        ],

        'rentable_area' => [
            'nullable',
            'numeric',
            'min:0',
        ],

        'chargeable_area' => [
            'nullable',
            'numeric',
            'min:0',
        ],

        'common_area' => [
            'nullable',
            'numeric',
            'min:0',
        ],

        'service_area' => [
            'nullable',
            'numeric',
            'min:0',
        ],


        /*
        |--------------------------------------------------------------------------
        | Measurement Information
        |--------------------------------------------------------------------------
        */

        'measurement_unit' => [
            'required',
            'string',
            'max:20',
        ],

        'measurement_date' => [
            'nullable',
            'date',
        ],

        'effective_from' => [
            'nullable',
            'date',
        ],

        'effective_to' => [
            'nullable',
            'date',
            'after_or_equal:effective_from',
        ],

        'measurement_method' => [
            'nullable',
            'string',
            'max:100',
        ],

        'measurement_standard' => [
            'nullable',
            'string',
            'max:100',
        ],


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        'status' => [
            'nullable',
            'string',
            'max:30',
        ],


        /*
        |--------------------------------------------------------------------------
        | Remarks
        |--------------------------------------------------------------------------
        */

        'remarks' => [
            'nullable',
            'string',
        ],

    ]);


    /*
    |--------------------------------------------------------------------------
    | Normalize Empty Dropdown Values
    |--------------------------------------------------------------------------
    |
    | HTML select fields normally submit an empty string when the user
    | selects "-- Select --". Convert those values to NULL before update.
    |
    */

    $nullableFields = [
        'building_id',
        'floor_id',
        'zone_id',
        'unit_id',
        'area_category',
        'gross_area',
        'net_area',
        'usable_area',
        'rentable_area',
        'chargeable_area',
        'common_area',
        'service_area',
        'measurement_date',
        'effective_from',
        'effective_to',
        'measurement_method',
        'measurement_standard',
        'remarks',
    ];

    foreach ($nullableFields as $field) {

        if (
            array_key_exists($field, $validated)
            && $validated[$field] === ''
        ) {
            $validated[$field] = null;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Default Status
    |--------------------------------------------------------------------------
    */

    if (
        !isset($validated['status'])
        || $validated['status'] === ''
    ) {
        $validated['status'] = $area->status ?? 'ACTIVE';
    }


    /*
    |--------------------------------------------------------------------------
    | Update Area
    |--------------------------------------------------------------------------
    */

    $area->update($validated);


    /*
    |--------------------------------------------------------------------------
    | Refresh Model
    |--------------------------------------------------------------------------
    */

    $area->refresh();


    /*
    |--------------------------------------------------------------------------
    | Redirect
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route(
            'admin.assets.areas.show',
            [
                'area' => $area->id,
            ]
        )
        ->with(
            'success',
            'Property area updated successfully.'
        );
}

    /**
     * Delete the specified property area.
     *
     * DELETE
     * admin/assets/areas/{area}
     */
    public function destroy(
        PropertyArea $area
    ) {
        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        |
        | PropertyArea should use SoftDeletes if you want
        | this to be reversible.
        |
        */

        $area->delete();

        return redirect()
            ->route(
                'admin.assets.areas.index'
            )
            ->with(
                'success',
                'Property area deleted successfully.'
            );
    }

    /**
     * Validate property area data.
     *
     * @param Request $request
     * @param bool $isUpdate
     * @return array
     */
    protected function validateArea(
        Request $request,
        bool $isUpdate = false
    ): array {
        return $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Property Hierarchy
            |--------------------------------------------------------------------------
            */

            'mall_id' => [
                $isUpdate
                    ? 'sometimes'
                    : 'required',

                'integer',

                'exists:malls,id',
            ],

            'building_id' => [
                'nullable',
                'integer',
                'exists:buildings,id',
            ],

            'floor_id' => [
                'nullable',
                'integer',
                'exists:floors,id',
            ],

            'zone_id' => [
                'nullable',
                'integer',
                'exists:zones,id',
            ],

            'unit_id' => [
                'nullable',
                'integer',
                'exists:units,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | Area Classification
            |--------------------------------------------------------------------------
            */

            'area_type_id' => [
                $isUpdate
                    ? 'sometimes'
                    : 'required',

                'string',
                'max:50',
            ],

            'area_category' => [
                'nullable',
                'string',
                'max:50',
            ],

            /*
            |--------------------------------------------------------------------------
            | Area Measurements
            |--------------------------------------------------------------------------
            */

            'gross_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'net_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'usable_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'rentable_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'chargeable_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'common_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'service_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | Measurement Unit
            |--------------------------------------------------------------------------
            */

            'measurement_unit' => [
                $isUpdate
                    ? 'sometimes'
                    : 'required',

                'string',
                'max:20',
            ],

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            'measurement_date' => [
                'nullable',
                'date',
            ],

            'effective_from' => [
                'nullable',
                'date',
            ],

            'effective_to' => [
                'nullable',
                'date',
                'after_or_equal:effective_from',
            ],

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' => [
                'nullable',
                'string',
                'max:30',
            ],

            /*
            |--------------------------------------------------------------------------
            | Measurement Details
            |--------------------------------------------------------------------------
            */

            'measurement_method' => [
                'nullable',
                'string',
                'max:100',
            ],

            'measurement_standard' => [
                'nullable',
                'string',
                'max:100',
            ],

            /*
            |--------------------------------------------------------------------------
            | Remarks
            |--------------------------------------------------------------------------
            */

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);
    }
}