<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnitTypeRequest;
use App\Repositories\UnitTypeRepositoryInterface;
use Illuminate\Http\Request;

class UnitTypeController extends Controller
{
    protected UnitTypeRepositoryInterface $unitTypes;

    public function __construct(
        UnitTypeRepositoryInterface $unitTypes
    ) {
        $this->unitTypes = $unitTypes;

        $this->middleware('auth');
    }

    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $unitTypes = $this->unitTypes->all([
            'search' => $request->get('search'),
            'status' => $request->get('status'),
        ]);

        return view(
            'admin.assets.unit_types.index',
            compact('unitTypes')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view(
            'admin.unit_types.create'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */
    public function store(UnitTypeRequest $request)
    {
        $data = $request->validated();

        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        $unitType = $this->unitTypes->create($data);

        return redirect()
            ->route(
                'admin.assets.unit_types.show',
                $unitType->id
            )
            ->with(
                'success',
                'Unit type created successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $unitType = $this->unitTypes->find($id);

        if (!$unitType) {
            abort(404, 'Unit type not found.');
        }

        return view(
            'admin.assets.unit_types.show',
            compact('unitType')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $unitType = $this->unitTypes->find($id);

        if (!$unitType) {
            abort(404, 'Unit type not found.');
        }

        return view(
            'admin.assets.unit_types.edit',
            compact('unitType')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */
    public function update(
        UnitTypeRequest $request,
        $id
    ) {
        $unitType = $this->unitTypes->find($id);

        if (!$unitType) {
            abort(404, 'Unit type not found.');
        }

        $data = $request->validated();

        $data['updated_by'] = auth()->id();

        $this->unitTypes->update(
            $unitType,
            $data
        );

        return redirect()
            ->route(
                'admin.assets.unit_types.show',
                $unitType->id
            )
            ->with(
                'success',
                'Unit type updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $unitType = $this->unitTypes->find($id);

        if (!$unitType) {
            abort(404, 'Unit type not found.');
        }

        $this->unitTypes->delete($unitType);

        return redirect()
            ->route('admin.assets.unit_types.index')
            ->with(
                'success',
                'Unit type deleted successfully.'
            );
    }
}