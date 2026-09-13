<?php

namespace App\Http\Controllers\Admin\Land;

use App\Http\Controllers\Controller;
use App\Models\Land;
use App\Models\LandOwner;
use Illuminate\Http\Request;

class LandOwnerController extends Controller
{
    /**
     * Display owners for a land.
     */
    public function index(Land $land)
    {
        $owners = $land->owners()
            ->latest('id')
            ->paginate(15);

        return view(
            'land-acquisition.owners.index',
            compact('land', 'owners')
        );
    }


    /**
     * Show create owner form.
     */
    public function create(Land $land)
    {
        return view(
            'land-acquisition.owners.create',
            compact('land')
        );
    }


    /**
     * Store owner.
     */
    public function store(
        Request $request,
        Land $land
    ) {

        $validated = $request->validate([

            'owner_type' => [
                'required',
                'string',
                'max:50'
            ],

            'owner_name' => [
                'required',
                'string',
                'max:255'
            ],

            'ownership_percentage' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100'
            ],

            'ownership_start_date' => [
                'nullable',
                'date'
            ],

            'ownership_end_date' => [
                'nullable',
                'date',
                'after_or_equal:ownership_start_date'
            ],

            'title_reference' => [
                'nullable',
                'string',
                'max:255'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Ownership Percentage Validation
        |--------------------------------------------------------------------------
        */

        if (
            isset($validated['ownership_percentage']) &&
            $validated['ownership_percentage'] > 100
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'ownership_percentage' =>
                        'Ownership percentage cannot exceed 100%.'
                ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $validated['land_id'] = $land->id;
        $validated['created_by'] = auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Create Owner
        |--------------------------------------------------------------------------
        */

        $owner = LandOwner::create($validated);


        return redirect()
            ->route(
                'admin.land.lands.show',
                $land
            )
            ->with(
                'success',
                'Land owner added successfully.'
            );
    }


    /**
     * Display owner.
     */
    public function show(
        Land $land,
        LandOwner $owner
    ) {

        $this->validateOwnerBelongsToLand(
            $land,
            $owner
        );

        return view(
            'land-acquisition.owners.show',
            compact('land', 'owner')
        );
    }


    /**
     * Show edit form.
     */
    public function edit(
        Land $land,
        LandOwner $owner
    ) {

        $this->validateOwnerBelongsToLand(
            $land,
            $owner
        );

        return view(
            'land-acquisition.owners.edit',
            compact('land', 'owner')
        );
    }


    /**
     * Update owner.
     */
    public function update(
        Request $request,
        Land $land,
        LandOwner $owner
    ) {

        $this->validateOwnerBelongsToLand(
            $land,
            $owner
        );


        $validated = $request->validate([

            'owner_type' => [
                'required',
                'string',
                'max:50'
            ],

            'owner_name' => [
                'required',
                'string',
                'max:255'
            ],

            'ownership_percentage' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100'
            ],

            'ownership_start_date' => [
                'nullable',
                'date'
            ],

            'ownership_end_date' => [
                'nullable',
                'date',
                'after_or_equal:ownership_start_date'
            ],

            'title_reference' => [
                'nullable',
                'string',
                'max:255'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],

        ]);


        $validated['updated_by'] = auth()->id();


        $owner->update($validated);


        return redirect()
            ->route(
                'admin.land.lands.show',
                $land
            )
            ->with(
                'success',
                'Land owner updated successfully.'
            );
    }


    /**
     * Delete owner.
     */
    public function destroy(
        Land $land,
        LandOwner $owner
    ) {

        $this->validateOwnerBelongsToLand(
            $land,
            $owner
        );


        $owner->delete();


        return redirect()
            ->route(
                'admin.land.lands.show',
                $land
            )
            ->with(
                'success',
                'Land owner deleted successfully.'
            );
    }


    /**
     * Ensure owner belongs to land.
     */
    private function validateOwnerBelongsToLand(
        Land $land,
        LandOwner $owner
    ): void {

        abort_unless(
            $owner->land_id === $land->id,
            404
        );
    }
}