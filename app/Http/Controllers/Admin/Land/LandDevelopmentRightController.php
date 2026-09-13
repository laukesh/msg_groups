<?php

namespace App\Http\Controllers\Admin\Land;

use App\Http\Controllers\Controller;
use App\Models\Land;
use App\Models\LandDevelopmentRight;
use Illuminate\Http\Request;

class LandDevelopmentRightController extends Controller
{
    /**
     * Display development rights for a land.
     */
    public function index(Land $land)
    {
        $developmentRights = $land->developmentRights()
            ->latest('id')
            ->paginate(15);

        return view(
            'land-acquisition.development-rights.index',
            compact('land', 'developmentRights')
        );
    }


    /**
     * Show create form.
     */
    public function create(Land $land)
    {
        return view(
            'land-acquisition.development-rights.create',
            compact('land')
        );
    }


    /**
     * Store development right.
     */
    public function store(
        Request $request,
        Land $land
    ) {
        $validated = $request->validate([

            'right_type' => [
                'required',
                'string',
                'max:150'
            ],

            'description' => [
                'nullable',
                'string'
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

            'reference_number' => [
                'nullable',
                'string',
                'max:150'
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


        LandDevelopmentRight::create(
            $validated
        );


        return redirect()
            ->route(
                'admin.land.lands.show',
                $land
            )
            ->with(
                'success',
                'Development right added successfully.'
            );
    }


    /**
     * Display development right.
     */
    public function show(
        Land $land,
        LandDevelopmentRight $developmentRight
    ) {
        $this->validateDevelopmentRightBelongsToLand(
            $land,
            $developmentRight
        );

        return view(
            'land-acquisition.development-rights.show',
            compact(
                'land',
                'developmentRight'
            )
        );
    }


    /**
     * Show edit form.
     */
    public function edit(
        Land $land,
        LandDevelopmentRight $developmentRight
    ) {
        $this->validateDevelopmentRightBelongsToLand(
            $land,
            $developmentRight
        );

        return view(
            'land-acquisition.development-rights.edit',
            compact(
                'land',
                'developmentRight'
            )
        );
    }


    /**
     * Update development right.
     */
    public function update(
        Request $request,
        Land $land,
        LandDevelopmentRight $developmentRight
    ) {
        $this->validateDevelopmentRightBelongsToLand(
            $land,
            $developmentRight
        );


        $validated = $request->validate([

            'right_type' => [
                'required',
                'string',
                'max:150'
            ],

            'description' => [
                'nullable',
                'string'
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

            'reference_number' => [
                'nullable',
                'string',
                'max:150'
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


        $developmentRight->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.land.lands.show',
                $land
            )
            ->with(
                'success',
                'Development right updated successfully.'
            );
    }


    /**
     * Delete development right.
     */
    public function destroy(
        Land $land,
        LandDevelopmentRight $developmentRight
    ) {
        $this->validateDevelopmentRightBelongsToLand(
            $land,
            $developmentRight
        );


        $developmentRight->delete();


        return redirect()
            ->route(
                'land.lands.show',
                $land
            )
            ->with(
                'success',
                'Development right deleted successfully.'
            );
    }


    /**
     * Ensure development right belongs to land.
     */
    private function validateDevelopmentRightBelongsToLand(
        Land $land,
        LandDevelopmentRight $developmentRight
    ): void {

        abort_unless(
            $developmentRight->land_id === $land->id,
            404
        );
    }
}