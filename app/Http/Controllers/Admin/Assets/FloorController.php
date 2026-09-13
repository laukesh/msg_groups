<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Http\Controllers\Controller;
use App\Http\Requests\FloorRequest;
use App\Models\Building;
use App\Repositories\FloorRepositoryInterface;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    protected FloorRepositoryInterface $floors;

    public function __construct(
        FloorRepositoryInterface $floors
    ) {
        $this->floors = $floors;

        $this->middleware('auth');
    }

    /**
     * Display floors.
     */
    public function index(Request $request)
    {
        $floors = $this->floors->all([
            'search' => $request->get('search'),
            'building_id' => $request->get('building_id'),
        ]);

        return view(
            'admin.assets.floors.index',
            compact('floors')
        );
    }

    /**
     * Create floor.
     */
    public function create()
    {
        $buildings = Building::orderBy(
            'building_name'
        )->pluck(
            'building_name',
            'id'
        );

        return view(
            'admin.assets.floors.create',
            compact('buildings')
        );
    }

    /**
     * Store floor.
     */
    public function store(FloorRequest $request)
    {
        $data = $request->validated();

        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        $floor = $this->floors->create($data);

        return redirect()
            ->route(
                'admin.assets.floors.show',
                $floor->id
            )
            ->with(
                'success',
                'Floor created successfully.'
            );
    }

    /**
     * Show floor.
     */
    public function show($id)
    {
        $floor = $this->floors->find($id);

        if (!$floor) {
            abort(404, 'Floor not found.');
        }

        return view(
            'admin.assets.floors.show',
            compact('floor')
        );
    }

    /**
     * Edit floor.
     */
    public function edit($id)
    {
        $floor = $this->floors->find($id);

        if (!$floor) {
            abort(404, 'Floor not found.');
        }

        $buildings = Building::orderBy(
            'building_name'
        )->pluck(
            'building_name',
            'id'
        );

        return view(
            'admin.assets.floors.edit',
            compact(
                'floor',
                'buildings'
            )
        );
    }

    /**
     * Update floor.
     */
    public function update(
        FloorRequest $request,
        $id
    ) {
        $floor = $this->floors->find($id);

        if (!$floor) {
            abort(404, 'Floor not found.');
        }

        $data = $request->validated();

        unset($data['created_by']);

        $data['updated_by'] = auth()->id();

        $this->floors->update(
            $floor,
            $data
        );

        return redirect()
            ->route(
                'admin.assets.floors.show',
                $floor->id
            )
            ->with(
                'success',
                'Floor updated successfully.'
            );
    }

    /**
     * Delete floor.
     */
    public function destroy($id)
    {
        $floor = $this->floors->find($id);

        if (!$floor) {
            abort(404, 'Floor not found.');
        }

        $this->floors->delete($floor);

        return redirect()
            ->route('admin.assets.floors.index')
            ->with(
                'success',
                'Floor deleted successfully.'
            );
    }
}