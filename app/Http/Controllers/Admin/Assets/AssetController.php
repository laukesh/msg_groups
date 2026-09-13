<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Http\Controllers\Controller;
use App\Models\AssetCategory;
use App\Models\Building;
use App\Models\Department;
use App\Models\Floor;
use App\Models\User;
use App\Models\Unit;
//use App\Models\Vendor;
use App\Models\Zone;
use App\Repositories\AssetRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AssetController extends Controller
{
    public function __construct(
        protected AssetRepositoryInterface $assetRepository
    ) {
    }

   public function index(Request $request)
   { 
    $filters = $request->only([
        'search',
        'status',
        'asset_category',
        'building_id',
        'floor_id',
        'zone_id',
        'unit_id',
    ]);

        $items = $this->assetRepository->paginate($filters);

        $assetCategories = \App\Models\AssetCategory::query()
            ->orderBy('category_name')
            ->pluck('category_name', 'id');
 // dd($items);
        return view('admin.assets.assets.index', compact(
            'items',
            'assetCategories'
        ));

    }

    public function create()
    {
        $assetCategories = AssetCategory::query()
            ->orderBy('category_name')
            ->pluck('category_name', 'id');

        $buildings = Building::query()
            ->orderBy('building_name')
            ->pluck('building_name', 'id');

        $floors = Floor::query()
            ->orderBy('floor_name')
            ->pluck('floor_name', 'id');

        $zones = Zone::query()
            ->orderBy('zone_name')
            ->pluck('zone_name', 'id');

        $units = Unit::query()
            ->orderBy('unit_no')
            ->pluck('unit_no', 'id');

        $departments = Department::query()
            ->orderBy('department_name')
            ->pluck('department_name', 'id');

        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'Vendor');
        })->pluck('name', 'id');
        $vendors = User::role('Vendor')
            ->orderBy('name')
            ->pluck('name', 'id');

        // $vendors = Vendor::query()
        //     ->orderBy('name')
        //     ->pluck('name', 'id');

        return view(
            'admin.assets.assets.create',
            compact(
                'assetCategories',
                'buildings',
                'floors',
                'zones',
                'units',
                'departments',
                'users',
                'vendors'
            )
        );
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        $validated['uuid'] = (string) Str::uuid();
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $this->assetRepository->create($validated);

        return redirect()
            ->route('admin.assets.assets.index')
            ->with('success', 'Asset created successfully.');
    }

    public function show(int $id)
    {
        $asset = $this->assetRepository->find($id);
       $summary = $this->assetRepository
            ->getEconomicSummary($id);
        return view(
            'admin.assets.assets.show',
            compact('asset', 'summary')
        );
    }

  public function edit(int $id)
    {
        $asset = $this->assetRepository->find($id);
     
        abort_if(!$asset, 404, 'Asset not found.');

        $assetCategories = AssetCategory::where('is_active', 1)
            ->pluck('category_name', 'id');

        $units = Unit::pluck('unit_no', 'id');
        $buildings = Building::pluck('building_name', 'id');
        $floors = Floor::pluck('floor_name', 'id');
        $zones = Zone::pluck('zone_name', 'id');
        $departments = Department::pluck('department_name', 'id');
       $users = User::whereDoesntHave('roles', function ($query) {
           $query->where('name', 'Vendor');
            })->pluck('name', 'id');
        $vendors = User::role('Vendor')->pluck('name', 'id');

        return view('admin.assets.assets.edit', compact(
            'asset',
            'assetCategories',
            'units',
            'buildings',
            'floors',
            'zones',
            'departments',
            'users',
            'vendors'
        ));
    }

    public function update(Request $request, int $id)
    {
        $validated = $this->validateData(
            $request,
            $id
        );

        $validated['updated_by'] = auth()->id();

        $this->assetRepository->update(
            $id,
            $validated
        );

        return redirect()
            ->route(
                'admin.assets.assets.show',
                $id
            )
            ->with(
                'success',
                'Asset updated successfully.'
            );
    }

    public function destroy(int $id)
    {
        $this->assetRepository->delete($id);

        return redirect()
            ->route('admin.assets.assets.index')
            ->with(
                'success',
                'Asset deleted successfully.'
            );
    }

    protected function validateData(
        Request $request,
        ?int $id = null
    ): array {
        return $request->validate([

            'asset_code' => [
                'required',
                'string',
                'max:100',
                'unique:assets,asset_code,' . $id,
            ],

            'asset_name' => [
                'required',
                'string',
                'max:255',
            ],

            'asset_category' => [
                'nullable',
                'string',
                'max:100',
            ],

            'asset_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'serial_number' => [
                'nullable',
                'string',
                'max:150',
            ],

            'model_number' => [
                'nullable',
                'string',
                'max:150',
            ],

            'manufacturer' => [
                'nullable',
                'string',
                'max:150',
            ],

            'unit_id' => [
                'nullable',
                'integer',
                'exists:units,id',
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

            'location_description' => [
                'nullable',
                'string',
                'max:500',
            ],

            'department_id' => [
                'nullable',
                'integer',
                'exists:departments,id',
            ],

            'assigned_to' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'vendor_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'purchase_date' => [
                'nullable',
                'date',
            ],

            'installation_date' => [
                'nullable',
                'date',
            ],

            'warranty_start_date' => [
                'nullable',
                'date',
            ],

            'warranty_end_date' => [
                'nullable',
                'date',
                'after_or_equal:warranty_start_date',
            ],

            'purchase_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'useful_life_years' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'status' => [
                'required',
                'string',
                'max:50',
            ],

            'conditions' => [
                'nullable',
                'string',
                'max:100',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);
    }
}