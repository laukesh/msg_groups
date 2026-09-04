<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Http\Controllers\Controller;
use App\Http\Requests\ZoneRequest;
use App\Models\Floor;
use App\Repositories\ZoneRepositoryInterface;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    protected ZoneRepositoryInterface $zones;

    public function __construct(
        ZoneRepositoryInterface $zones
    ) {
        $this->zones = $zones;

        $this->middleware('auth');
    }

    /**
     * Display zones.
     */
    public function index(Request $request)
    {
        $zones = $this->zones->all([
            'search'   => $request->get('search'),
            'floor_id' => $request->get('floor_id'),
        ]);

        return view(
            'admin.assets.zones.index',
            compact('zones')
        );
    }

    /**
     * Create zone.
     */
    public function create()
    {
        $floors = Floor::with('building')
            ->orderBy('building_id')
            ->orderBy('floor_number')
            ->get();

        return view(
            'admin.assets.zones.create',
            compact('floors')
        );
    }

    /**
     * Store zone.
     */
    public function store(ZoneRequest $request)
    {
        $data = $request->validated();

        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        $zone = $this->zones->create($data);

        return redirect()
            ->route(
                'admin.assets.zones.show',
                $zone->id
            )
            ->with(
                'success',
                'Zone created successfully.'
            );
    }

    /**
     * Show zone.
     */
    public function show($id)
    {
        $zone = $this->zones->find($id);

        if (!$zone) {
            abort(404, 'Zone not found.');
        }

        return view(
            'admin.assets.zones.show',
            compact('zone')
        );
    }

    /**
     * Edit zone.
     */
    public function edit($id)
    {
        $zone = $this->zones->find($id);

        if (!$zone) {
            abort(404, 'Zone not found.');
        }

        $floors = Floor::with('building')
            ->orderBy('building_id')
            ->orderBy('floor_number')
            ->get();

        return view(
            'admin.assets.zones.edit',
            compact(
                'zone',
                'floors'
            )
        );
    }

    /**
     * Update zone.
     */
    public function update(
        ZoneRequest $request,
        $id
    ) {
        $zone = $this->zones->find($id);

        if (!$zone) {
            abort(404, 'Zone not found.');
        }

        $data = $request->validated();

        unset($data['created_by']);

        $data['updated_by'] = auth()->id();

        $this->zones->update(
            $zone,
            $data
        );

        return redirect()
            ->route(
                'admin.assets.zones.show',
                $zone->id
            )
            ->with(
                'success',
                'Zone updated successfully.'
            );
    }

    /**
     * Delete zone.
     */
    public function destroy($id)
    {
        $zone = $this->zones->find($id);

        if (!$zone) {
            abort(404, 'Zone not found.');
        }

        $this->zones->delete($zone);

        return redirect()
            ->route('admin.assets.zones.index')
            ->with(
                'success',
                'Zone deleted successfully.'
            );
    }
}