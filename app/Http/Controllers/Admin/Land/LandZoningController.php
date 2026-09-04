<?php

namespace App\Http\Controllers\Admin\Land;

use App\Http\Controllers\Controller;
use App\Models\Land;
use App\Models\LandZoning;
use Illuminate\Http\Request;

class LandZoningController extends Controller
{
    /**
     * Display zoning records for a land.
     */
    public function index(Land $land)
    {
        $zonings = $land->zonings()
            ->latest('id')
            ->paginate(15);

        return view(
            'land-acquisition.zonings.index',
            compact('land', 'zonings')
        );
    }


    /**
     * Show create form.
     */
    public function create(Land $land)
    {
        return view(
            'land-acquisition.zonings.create',
            compact('land')
        );
    }


    /**
     * Store zoning record.
     */
    public function store(
        Request $request,
        Land $land
    ) {
        $validated = $request->validate([

            'zoning_code' => [
                'nullable',
                'string',
                'max:100'
            ],

            'zoning_type' => [
                'required',
                'string',
                'max:150'
            ],

            'permitted_use' => [
                'nullable',
                'string'
            ],

            'restrictions' => [
                'nullable',
                'string'
            ],

            'authority' => [
                'nullable',
                'string',
                'max:255'
            ],

            'effective_date' => [
                'nullable',
                'date'
            ],

            'expiry_date' => [
                'nullable',
                'date',
                'after_or_equal:effective_date'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],

        ]);


        $validated['land_id'] = $land->id;
        $validated['created_by'] = auth()->id();


        LandZoning::create($validated);


        return redirect()
            ->route(
                'admin.land.lands.show',
                $land
            )
            ->with(
                'success',
                'Zoning information added successfully.'
            );
    }


    /**
     * Display zoning record.
     */
    public function show(
        Land $land,
        LandZoning $zoning
    ) {
        $this->validateZoningBelongsToLand(
            $land,
            $zoning
        );

        return view(
            'land-acquisition.zonings.show',
            compact('land', 'zoning')
        );
    }


    /**
     * Show edit form.
     */
    public function edit(
        Land $land,
        LandZoning $zoning
    ) {
        $this->validateZoningBelongsToLand(
            $land,
            $zoning
        );

        return view(
            'land-acquisition.zonings.edit',
            compact('land', 'zoning')
        );
    }


    /**
     * Update zoning.
     */
    public function update(
        Request $request,
        Land $land,
        LandZoning $zoning
    ) {
        $this->validateZoningBelongsToLand(
            $land,
            $zoning
        );


        $validated = $request->validate([

            'zoning_code' => [
                'nullable',
                'string',
                'max:100'
            ],

            'zoning_type' => [
                'required',
                'string',
                'max:150'
            ],

            'permitted_use' => [
                'nullable',
                'string'
            ],

            'restrictions' => [
                'nullable',
                'string'
            ],

            'authority' => [
                'nullable',
                'string',
                'max:255'
            ],

            'effective_date' => [
                'nullable',
                'date'
            ],

            'expiry_date' => [
                'nullable',
                'date',
                'after_or_equal:effective_date'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],

        ]);


        $validated['updated_by'] = auth()->id();


        $zoning->update($validated);


        return redirect()
            ->route(
                'admin.land.lands.show',
                $land
            )
            ->with(
                'success',
                'Zoning information updated successfully.'
            );
    }


    /**
     * Delete zoning.
     */
    public function destroy(
        Land $land,
        LandZoning $zoning
    ) {
        $this->validateZoningBelongsToLand(
            $land,
            $zoning
        );


        $zoning->delete();


        return redirect()
            ->route(
                'admin.land.lands.show',
                $land
            )
            ->with(
                'success',
                'Zoning information deleted successfully.'
            );
    }


    /**
     * Ensure zoning belongs to land.
     */
    private function validateZoningBelongsToLand(
        Land $land,
        LandZoning $zoning
    ): void {
        abort_unless(
            $zoning->land_id === $land->id,
            404
        );
    }
}