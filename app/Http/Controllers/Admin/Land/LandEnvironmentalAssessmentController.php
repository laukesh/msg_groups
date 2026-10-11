<?php

namespace App\Http\Controllers\Admin\Land;

use App\Http\Controllers\Controller;
use App\Models\Land;
use App\Models\LandDueDiligence;
use Illuminate\Http\Request;

class LandEnvironmentalAssessmentController extends Controller
{
    /**
     * Display environmental assessments.
     */
    public function index(Land $land)
    {
        $dueDiligences = $land->dueDiligences()
            ->where('type', 'Environmental')
            ->latest('id')
            ->paginate(15);

        return view(
            'land-acquisition.environmental-assessments.index',
            compact(
                'land',
                'dueDiligences'
            )
        );
    }


    /**
     * Show create form.
     */
    public function create(Land $land)
    {
        return view(
            'land-acquisition.environmental-assessments.create',
            compact('land')
        );
    }


    /**
     * Store environmental assessment.
     */
    public function store(
        Request $request,
        Land $land
    ) {
        $validated = $request->validate([

            'reference_no' => [
                'nullable',
                'string',
                'max:100'
            ],

            'assessment_date' => [
                'nullable',
                'date'
            ],

            'conducted_by' => [
                'nullable',
                'string',
                'max:255'
            ],

            'status' => [
                'required',
                'string',
                'max:50'
            ],

            'summary' => [
                'nullable',
                'string'
            ],

            'findings' => [
                'nullable',
                'string'
            ],

            'recommendations' => [
                'nullable',
                'string'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],

        ]);


        $validated['land_id'] = $land->id;

        $validated['type'] = 'Environmental';

        $validated['created_by'] = auth()->id();


        LandDueDiligence::create(
            $validated
        );


        return redirect()
            ->route(
                'admin.land.lands.show',
                $land
            )
            ->with(
                'success',
                'Environmental assessment added successfully.'
            );
    }


    /**
     * Display environmental assessment.
     */
    public function show(
        Land $land,
        LandDueDiligence $dueDiligence
    ) {
        $this->validateBelongsToLand(
            $land,
            $dueDiligence
        );

        abort_unless(
            $dueDiligence->type === 'Environmental',
            404
        );


        return view(
            'land-acquisition.environmental-assessments.show',
            compact(
                'land',
                'dueDiligence'
            )
        );
    }


    /**
     * Show edit form.
     */
    public function edit(
        Land $land,
        LandDueDiligence $dueDiligence
    ) {
        $this->validateBelongsToLand(
            $land,
            $dueDiligence
        );

        abort_unless(
            $dueDiligence->type === 'Environmental',
            404
        );


        return view(
            'land-acquisition.environmental-assessments.edit',
            compact(
                'land',
                'dueDiligence'
            )
        );
    }


    /**
     * Update environmental assessment.
     */
    public function update(
        Request $request,
        Land $land,
        LandDueDiligence $dueDiligence
    ) {
        $this->validateBelongsToLand(
            $land,
            $dueDiligence
        );

        abort_unless(
            $dueDiligence->type === 'Environmental',
            404
        );


        $validated = $request->validate([

            'reference_no' => [
                'nullable',
                'string',
                'max:100'
            ],

            'assessment_date' => [
                'nullable',
                'date'
            ],

            'conducted_by' => [
                'nullable',
                'string',
                'max:255'
            ],

            'status' => [
                'required',
                'string',
                'max:50'
            ],

            'summary' => [
                'nullable',
                'string'
            ],

            'findings' => [
                'nullable',
                'string'
            ],

            'recommendations' => [
                'nullable',
                'string'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],

        ]);


        $validated['type'] = 'Environmental';

        $validated['updated_by'] = auth()->id();


        $dueDiligence->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.land.lands.show',
                $land
            )
            ->with(
                'success',
                'Environmental assessment updated successfully.'
            );
    }


    /**
     * Delete environmental assessment.
     */
    public function destroy(
        Land $land,
        LandDueDiligence $dueDiligence
    ) {
        $this->validateBelongsToLand(
            $land,
            $dueDiligence
        );

        abort_unless(
            $dueDiligence->type === 'Environmental',
            404
        );


        $dueDiligence->delete();


        return redirect()
            ->route(
                'admin.land.lands.show',
                $land
            )
            ->with(
                'success',
                'Environmental assessment deleted successfully.'
            );
    }


    /**
     * Validate record belongs to land.
     */
    private function validateBelongsToLand(
        Land $land,
        LandDueDiligence $dueDiligence
    ): void {
        abort_unless(
            (int) $dueDiligence->land_id === (int) $land->id,
            404
        );
    }
}
