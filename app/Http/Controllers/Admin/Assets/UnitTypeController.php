<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Http\Controllers\Controller;
use App\Repositories\UnitTypeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UnitTypeController extends Controller
{
    protected UnitTypeRepositoryInterface $repository;

    public function __construct(
        UnitTypeRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * Display a listing of unit types.
     */
    public function index(Request $request)
    {
        $unitTypes = $this->repository->all([
            'search' => $request->input('search'),
            'status' => $request->input('status'),
        ]);

        return view(
            'admin.assets.unit_types.index',
            compact('unitTypes')
        );
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view(
            'admin.assets.unit_types.create'
        );
    }

    /**
     * Store unit type.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'type_name' => [
                'required',
                'string',
                'max:150',
                'unique:unit_types,type_name',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'status' => [
                'required',
                'in:0,1',
            ],

        ], [
            'type_name.unique' =>
                'This unit type already exists.',
        ]);

        $validated['created_by'] = auth()->id();

        $this->repository->create($validated);

        return redirect()
            ->route('admin.assets.unit-types.index')
            ->with(
                'success',
                'Unit type created successfully.'
            );
    }

    /**
     * Display unit type.
     */
    public function show(int $id)
    {
        $unitType = $this->repository->find($id);

        return view(
            'admin.assets.unit_types.show',
            compact('unitType')
        );
    }

    /**
     * Show edit form.
     */
    public function edit(int $id)
    {
        $unitType = $this->repository->find($id);

        return view(
            'admin.assets.unit_types.edit',
            compact('unitType')
        );
    }

    /**
     * Update unit type.
     */
    public function update(
        Request $request,
        int $id
    ) {
        $validated = $request->validate([

            'type_name' => [
                'required',
                'string',
                'max:150',

                Rule::unique(
                    'unit_types',
                    'type_name'
                )->ignore($id),
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'status' => [
                'required',
                'in:0,1',
            ],

        ], [
            'type_name.unique' =>
                'This unit type already exists.',
        ]);

        $validated['updated_by'] = auth()->id();

        $this->repository->update(
            $id,
            $validated
        );

        return redirect()
            ->route('admin.assets.unit-types.index')
            ->with(
                'success',
                'Unit type updated successfully.'
            );
    }

    /**
     * Delete unit type.
     */
    public function destroy(int $id)
    {
        $this->repository->delete($id);

        return redirect()
            ->route('admin.assets.unit-types.index')
            ->with(
                'success',
                'Unit type deleted successfully.'
            );
    }
}