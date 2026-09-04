<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Http\Controllers\Controller;
use App\Http\Requests\BuildingRequest;
use App\Models\Mall;
use App\Repositories\BuildingRepositoryInterface;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    protected BuildingRepositoryInterface $buildings;

    public function __construct(
        BuildingRepositoryInterface $buildings
    ) {
        $this->buildings = $buildings;

        $this->middleware('auth');
    }

    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $buildings = $this->buildings->all([
            'search'  => $request->get('search'),
            'mall_id' => $request->get('mall_id'),
            'status'  => $request->get('status'),
        ]);

        $malls = Mall::orderBy('mall_name')
            ->pluck('mall_name', 'id');

        return view(
            'admin.assets.buildings.index',
            compact(
                'buildings',
                'malls'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $malls = Mall::orderBy('mall_name')
            ->pluck('mall_name', 'id');

        return view(
            'admin.assets.buildings.create',
            compact('malls')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(
        BuildingRequest $request
    ) {
        $data = $request->validated();

        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        $building = $this->buildings->create($data);

        return redirect()
            ->route(
                'admin.assets.buildings.show',
                $building->id
            )
            ->with(
                'success',
                'Building created successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $building = $this->buildings->find($id);

        if (!$building) {
            abort(
                404,
                'Building not found.'
            );
        }

        return view(
            'admin.assets.buildings.show',
            compact('building')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $building = $this->buildings->find($id);

        if (!$building) {
            abort(
                404,
                'Building not found.'
            );
        }

        $malls = Mall::orderBy('mall_name')
            ->pluck('mall_name', 'id');

        return view(
            'admin.assets.buildings.edit',
            compact(
                'building',
                'malls'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        BuildingRequest $request,
        $id
    ) {
        $building = $this->buildings->find($id);

        if (!$building) {
            abort(
                404,
                'Building not found.'
            );
        }

        $data = $request->validated();

        $data['updated_by'] = auth()->id();

        $this->buildings->update(
            $building,
            $data
        );

        return redirect()
            ->route(
                'admin.assets.buildings.show',
                $building->id
            )
            ->with(
                'success',
                'Building updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $building = $this->buildings->find($id);

        if (!$building) {
            abort(
                404,
                'Building not found.'
            );
        }

        $this->buildings->delete($building);

        return redirect()
            ->route(
                'admin.assets.buildings.index'
            )
            ->with(
                'success',
                'Building deleted successfully.'
            );
    }
}