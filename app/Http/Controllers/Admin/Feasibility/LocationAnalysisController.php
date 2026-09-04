<?php

namespace App\Http\Controllers\Admin\Feasibility;

use App\Http\Controllers\Controller;
use App\Models\FeasibilityAssessment;
use App\Models\Land;
use App\Models\LocationAnalysis;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationAnalysisController extends Controller
{
    /**
     * Display location analyses.
     */
    public function index(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ) {
        $locationAnalyses = $feasibilityAssessment
            ->locationAnalyses()
            ->latest('id')
            ->paginate(15);


        return view(
            'feasibility.location-analyses.index',
            [
                'land' => $land,
                'feasibilityAssessment' => $feasibilityAssessment,
                'locationAnalyses' => $locationAnalyses,
            ]
        );
    }


    /**
     * Show create form.
     */
    public function create(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ) {
        return view(
            'feasibility.location-analyses.create',
            [
                'land' => $land,
                'feasibilityAssessment' => $feasibilityAssessment,
            ]
        );
    }


    /**
     * Store location analysis.
     */
    public function store(
        Request $request,
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ) {

        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'location_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'accessibility' => [
                'nullable',
                'string',
            ],

            'road_connectivity' => [
                'nullable',
                'string',
            ],

            'public_transport' => [
                'nullable',
                'string',
            ],

            'visibility' => [
                'nullable',
                'string',
            ],

            'surrounding_development' => [
                'nullable',
                'string',
            ],

            'nearby_landmarks' => [
                'nullable',
                'string',
            ],

            'competition' => [
                'nullable',
                'string',
            ],

            'demographics' => [
                'nullable',
                'string',
            ],

            'catchment_area' => [
                'nullable',
                'string',
            ],

            'location_advantages' => [
                'nullable',
                'string',
            ],

            'location_constraints' => [
                'nullable',
                'string',
            ],

            'overall_location_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'recommendation' => [
                'nullable',
                'string',
            ],

        ]);


        $validated['feasibility_assessment_id'] =
            $feasibilityAssessment->id;


        $validated['analysis_number'] =
            'LA-' .
            now()->format('YmdHis') .
            '-' .
            Str::upper(
                Str::random(4)
            );


        $validated['status'] = 'Draft';


        $validated['created_by'] =
            auth()->id();


        $locationAnalysis =
            LocationAnalysis::create(
                $validated
            );


        return redirect()->route(
            'admin.land.lands.feasibility-assessments.location-analyses.show',
            [
                'land' => $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,

                'locationAnalysis' =>
                    $locationAnalysis->id,
            ]
        )->with(
            'success',
            'Location analysis created successfully.'
        );
    }


    /**
     * Display location analysis.
     */
    public function show(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        LocationAnalysis $locationAnalysis
    ) {
        return view(
            'feasibility.location-analyses.show',
            [
                'land' => $land,

                'feasibilityAssessment' =>
                    $feasibilityAssessment,

                'locationAnalysis' =>
                    $locationAnalysis,
            ]
        );
    }


    /**
     * Show edit form.
     */
    public function edit(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        LocationAnalysis $locationAnalysis
    ) {
        return view(
            'feasibility.location-analyses.edit',
            [
                'land' => $land,

                'feasibilityAssessment' =>
                    $feasibilityAssessment,

                'locationAnalysis' =>
                    $locationAnalysis,
            ]
        );
    }


    /**
     * Update location analysis.
     */
    public function update(
        Request $request,
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        LocationAnalysis $locationAnalysis
    ) {

        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'location_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'accessibility' => [
                'nullable',
                'string',
            ],

            'road_connectivity' => [
                'nullable',
                'string',
            ],

            'public_transport' => [
                'nullable',
                'string',
            ],

            'visibility' => [
                'nullable',
                'string',
            ],

            'surrounding_development' => [
                'nullable',
                'string',
            ],

            'nearby_landmarks' => [
                'nullable',
                'string',
            ],

            'competition' => [
                'nullable',
                'string',
            ],

            'demographics' => [
                'nullable',
                'string',
            ],

            'catchment_area' => [
                'nullable',
                'string',
            ],

            'location_advantages' => [
                'nullable',
                'string',
            ],

            'location_constraints' => [
                'nullable',
                'string',
            ],

            'overall_location_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'recommendation' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'string',
                'max:50',
            ],

        ]);


        $validated['updated_by'] =
            auth()->id();


        $locationAnalysis->update(
            $validated
        );


        return redirect()->route(
            'admin.land.lands.feasibility-assessments.location-analyses.show',
            [
                'land' => $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,

                'locationAnalysis' =>
                    $locationAnalysis->id,
            ]
        )->with(
            'success',
            'Location analysis updated successfully.'
        );
    }


    /**
     * Delete location analysis.
     */
    public function destroy(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        LocationAnalysis $locationAnalysis
    ) {

        $locationAnalysis->delete();


        return redirect()->route(
            'admin.land.lands.feasibility-assessments.location-analyses.index',
            [
                'land' => $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,
            ]
        )->with(
            'success',
            'Location analysis deleted successfully.'
        );
    }
}