<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UnitRepository;
use App\Models\Mall;
use App\Models\Building;
use App\Models\Floor;
use App\Models\Zone;
use App\Models\UnitType;
use App\Models\UnitStatus;

class UnitController extends Controller
{
    protected $repo;

    public function __construct(UnitRepository $repo)
    {
        $this->repo = $repo;

        $this->middleware('permission:units.view')->only(['index', 'show']);
        $this->middleware('permission:units.create')->only(['create', 'store']);
        $this->middleware('permission:units.edit')->only(['edit', 'update']);
        $this->middleware('permission:units.delete')->only(['destroy']);
    }

    public function index()
    {
        $units = $this->repo->paginate(20);

        return view('admin.assets.units.index', compact('units'));
    }

    public function create()
    {
        $malls = Mall::pluck('mall_name', 'id');
        $buildings = Building::pluck('building_name', 'id');
        $floors = Floor::pluck('floor_name', 'id');
        $zones = Zone::pluck('zone_name', 'id');
        $unitTypes = UnitType::pluck('type_name', 'id');
        $unitStatuses = UnitStatus::pluck('status_name', 'id');

        return view('admin.assets.units.create', compact('malls', 'buildings', 'floors', 'zones', 'unitTypes', 'unitStatuses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mall_id' => 'required|integer|exists:malls,id',
            'building_id' => 'required|integer|exists:buildings,id',
            'floor_id' => 'nullable|integer|exists:floors,id',
            'zone_id' => 'nullable|integer|exists:zones,id',
            'unit_type_id' => 'nullable|integer|exists:unit_types,id',
            'unit_status_id' => 'nullable|integer|exists:unit_statuses,id',
            'unit_no' => 'required|string|max:255',
            'shop_name' => 'nullable|string|max:255',
            'carpet_area' => 'nullable|numeric',
            'builtup_area' => 'nullable|numeric',
            'frontage' => 'nullable|numeric',
            'monthly_rent' => 'nullable|numeric',
            'security_deposit' => 'nullable|numeric',
            'remarks' => 'nullable|string',
            'status' => 'nullable|in:1,0'
        ]);

        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        $this->repo->create($data);

        return redirect()->route('admin.assets.units.index')->with('success', 'Unit created successfully.');
    }

    public function show($id)
    {
        $unit = $this->repo->find($id);

        return view('admin.assets.units.show', compact('unit'));
    }

    public function edit($id)
    {
        $unit = $this->repo->find($id);
        $malls = Mall::pluck('mall_name', 'id');
        $buildings = Building::pluck('building_name', 'id');
        $floors = Floor::pluck('floor_name', 'id');
        $zones = Zone::pluck('zone_name', 'id');
        $unitTypes = UnitType::pluck('type_name', 'id');
        $unitStatuses = UnitStatus::pluck('status_name', 'id');

        return view('admin.assets.units.edit', compact('unit', 'malls', 'buildings', 'floors', 'zones', 'unitTypes', 'unitStatuses'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'mall_id' => 'required|integer|exists:malls,id',
            'building_id' => 'required|integer|exists:buildings,id',
            'floor_id' => 'nullable|integer|exists:floors,id',
            'zone_id' => 'nullable|integer|exists:zones,id',
            'unit_type_id' => 'nullable|integer|exists:unit_types,id',
            'unit_status_id' => 'nullable|integer|exists:unit_statuses,id',
            'unit_no' => 'required|string|max:255',
            'shop_name' => 'nullable|string|max:255',
            'carpet_area' => 'nullable|numeric',
            'builtup_area' => 'nullable|numeric',
            'frontage' => 'nullable|numeric',
            'monthly_rent' => 'nullable|numeric',
            'security_deposit' => 'nullable|numeric',
            'remarks' => 'nullable|string',
            'status' => 'nullable|in:active,inactive'
        ]);

        $data['updated_by'] = auth()->id();

        $this->repo->update($id, $data);

        return redirect()->route('admin.assets.units.index')->with('success', 'Unit updated successfully.');
    }

    public function destroy($id)
    {
        $this->repo->delete($id);

        return redirect()->route('admin.assets.units.index')->with('success', 'Unit deleted successfully.');
    }
}
