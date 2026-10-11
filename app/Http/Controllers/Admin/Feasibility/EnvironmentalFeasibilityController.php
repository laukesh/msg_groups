<?php

namespace App\Http\Controllers\Admin\Feasibility;

use App\Http\Controllers\Controller;
use App\Models\Land;
use App\Models\FeasibilityAssessment;
use App\Models\EnvironmentalFeasibility;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EnvironmentalFeasibilityController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        $environmentalFeasibilities =
            $feasibilityAssessment
                ->environmentalFeasibilities()
                ->latest('id')
                ->paginate(15);

        return view(
            'feasibility.environmental-feasibilities.index',
            compact(
                'land',
                'feasibilityAssessment',
                'environmentalFeasibilities'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        return view(
            'feasibility.environmental-feasibilities.create',
            compact(
                'land',
                'feasibilityAssessment'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Basic
            |--------------------------------------------------------------------------
            */

            'title' => [
                'required',
                'string',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | Environmental Assessment
            |--------------------------------------------------------------------------
            */

            'environmental_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'environmental_overview' => [
                'nullable',
                'string',
            ],

            'environmental_impact_assessment_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'environmental_impact_assessment_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Air
            |--------------------------------------------------------------------------
            */

            'air_quality_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'air_quality_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Water
            |--------------------------------------------------------------------------
            */

            'water_environment_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'water_environment_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Soil
            |--------------------------------------------------------------------------
            */

            'soil_environment_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'soil_environment_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Noise
            |--------------------------------------------------------------------------
            */

            'noise_pollution_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'noise_pollution_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Ecology
            |--------------------------------------------------------------------------
            */

            'ecological_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'ecological_details' => [
                'nullable',
                'string',
            ],

            'biodiversity_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'biodiversity_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Trees / Green Cover
            |--------------------------------------------------------------------------
            */

            'tree_cutting_required' => [
                'nullable',
                'string',
                'max:100',
            ],

            'tree_cutting_details' => [
                'nullable',
                'string',
            ],

            'green_cover_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'green_cover_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Waste Management
            |--------------------------------------------------------------------------
            */

            'solid_waste_management_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'solid_waste_management_details' => [
                'nullable',
                'string',
            ],

            'hazardous_waste_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'hazardous_waste_details' => [
                'nullable',
                'string',
            ],

            'construction_waste_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'construction_waste_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Pollution Control
            |--------------------------------------------------------------------------
            */

            'pollution_control_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'pollution_control_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Climate
            |--------------------------------------------------------------------------
            */

            'climate_impact_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'climate_impact_details' => [
                'nullable',
                'string',
            ],

            'climate_resilience_measures' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Sustainability
            |--------------------------------------------------------------------------
            */

            'sustainability_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'sustainability_details' => [
                'nullable',
                'string',
            ],

            'green_building_potential' => [
                'nullable',
                'string',
                'max:100',
            ],

            'green_building_details' => [
                'nullable',
                'string',
            ],

            'renewable_energy_potential' => [
                'nullable',
                'string',
                'max:100',
            ],

            'renewable_energy_details' => [
                'nullable',
                'string',
            ],

            'water_conservation_potential' => [
                'nullable',
                'string',
                'max:100',
            ],

            'water_conservation_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Regulatory
            |--------------------------------------------------------------------------
            */

            'environmental_clearance_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'environmental_clearance_details' => [
                'nullable',
                'string',
            ],

            'applicable_environmental_laws' => [
                'nullable',
                'string',
            ],

            'required_environmental_approvals' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Risks
            |--------------------------------------------------------------------------
            */

            'environmental_risks' => [
                'nullable',
                'string',
            ],

            'mitigation_measures' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Findings
            |--------------------------------------------------------------------------
            */

            'key_environmental_findings' => [
                'nullable',
                'string',
            ],

            'recommendation' => [
                'nullable',
                'string',
            ],

            'overall_environmental_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],
        ]);


        $validated['feasibility_assessment_id'] =
            $feasibilityAssessment->id;


        /*
        |--------------------------------------------------------------------------
        | Analysis Number
        |--------------------------------------------------------------------------
        */

        $validated['analysis_number'] =
            'EF-' .
            now()->format('YmdHis') .
            '-' .
            Str::upper(
                Str::random(4)
            );


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        $validated['status'] = 'Draft';


        /*
        |--------------------------------------------------------------------------
        | Created By
        |--------------------------------------------------------------------------
        */

        $validated['created_by'] =
            auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        $environmentalFeasibility =
            EnvironmentalFeasibility::create(
                $validated
            );


        return redirect()->route(
            'admin.land.lands.feasibility-assessments.environmental-feasibilities.show',
            [
                'land' =>
                    $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,

                'environmentalFeasibility' =>
                    $environmentalFeasibility->id,
            ]
        )->with(
            'success',
            'Environmental feasibility created successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        EnvironmentalFeasibility $environmentalFeasibility
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $environmentalFeasibility
        );

        return view(
            'feasibility.environmental-feasibilities.show',
            compact(
                'land',
                'feasibilityAssessment',
                'environmentalFeasibility'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        EnvironmentalFeasibility $environmentalFeasibility
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $environmentalFeasibility
        );

        return view(
            'feasibility.environmental-feasibilities.edit',
            compact(
                'land',
                'feasibilityAssessment',
                'environmentalFeasibility'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        EnvironmentalFeasibility $environmentalFeasibility
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $environmentalFeasibility
        );

        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Basic
            |--------------------------------------------------------------------------
            */

            'title' => [
                'required',
                'string',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | Environmental Assessment
            |--------------------------------------------------------------------------
            */

            'environmental_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'environmental_overview' => [
                'nullable',
                'string',
            ],

            'environmental_impact_assessment_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'environmental_impact_assessment_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Air
            |--------------------------------------------------------------------------
            */

            'air_quality_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'air_quality_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Water
            |--------------------------------------------------------------------------
            */

            'water_environment_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'water_environment_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Soil
            |--------------------------------------------------------------------------
            */

            'soil_environment_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'soil_environment_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Noise
            |--------------------------------------------------------------------------
            */

            'noise_pollution_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'noise_pollution_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Ecology
            |--------------------------------------------------------------------------
            */

            'ecological_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'ecological_details' => [
                'nullable',
                'string',
            ],

            'biodiversity_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'biodiversity_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Trees / Green Cover
            |--------------------------------------------------------------------------
            */

            'tree_cutting_required' => [
                'nullable',
                'string',
                'max:100',
            ],

            'tree_cutting_details' => [
                'nullable',
                'string',
            ],

            'green_cover_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'green_cover_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Waste Management
            |--------------------------------------------------------------------------
            */

            'solid_waste_management_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'solid_waste_management_details' => [
                'nullable',
                'string',
            ],

            'hazardous_waste_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'hazardous_waste_details' => [
                'nullable',
                'string',
            ],

            'construction_waste_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'construction_waste_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Pollution
            |--------------------------------------------------------------------------
            */

            'pollution_control_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'pollution_control_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Climate
            |--------------------------------------------------------------------------
            */

            'climate_impact_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'climate_impact_details' => [
                'nullable',
                'string',
            ],

            'climate_resilience_measures' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Sustainability
            |--------------------------------------------------------------------------
            */

            'sustainability_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'sustainability_details' => [
                'nullable',
                'string',
            ],

            'green_building_potential' => [
                'nullable',
                'string',
                'max:100',
            ],

            'green_building_details' => [
                'nullable',
                'string',
            ],

            'renewable_energy_potential' => [
                'nullable',
                'string',
                'max:100',
            ],

            'renewable_energy_details' => [
                'nullable',
                'string',
            ],

            'water_conservation_potential' => [
                'nullable',
                'string',
                'max:100',
            ],

            'water_conservation_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Regulatory
            |--------------------------------------------------------------------------
            */

            'environmental_clearance_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'environmental_clearance_details' => [
                'nullable',
                'string',
            ],

            'applicable_environmental_laws' => [
                'nullable',
                'string',
            ],

            'required_environmental_approvals' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Risks
            |--------------------------------------------------------------------------
            */

            'environmental_risks' => [
                'nullable',
                'string',
            ],

            'mitigation_measures' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Findings
            |--------------------------------------------------------------------------
            */

            'key_environmental_findings' => [
                'nullable',
                'string',
            ],

            'recommendation' => [
                'nullable',
                'string',
            ],

            'overall_environmental_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' => [
                'required',
                'string',
                'max:50',
            ],
        ]);


        $validated['updated_by'] =
            auth()->id();


        $environmentalFeasibility->update(
            $validated
        );


        return redirect()->route(
            'admin.land.lands.feasibility-assessments.environmental-feasibilities.show',
            [
                'land' =>
                    $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,

                'environmentalFeasibility' =>
                    $environmentalFeasibility->id,
            ]
        )->with(
            'success',
            'Environmental feasibility updated successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        EnvironmentalFeasibility $environmentalFeasibility
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $environmentalFeasibility
        );

        $environmentalFeasibility->delete();

        return redirect()->route(
            'admin.land.lands.feasibility-assessments.environmental-feasibilities.index',
            [
                'land' =>
                    $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,
            ]
        )->with(
            'success',
            'Environmental feasibility deleted successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Land -> Assessment
    |--------------------------------------------------------------------------
    */

    private function validateLandAssessment(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ): void {
        abort_unless(
            (int) $feasibilityAssessment->land_id ===
            (int) $land->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Assessment -> Environmental Feasibility
    |--------------------------------------------------------------------------
    */

    private function validateBelongsToAssessment(
        FeasibilityAssessment $feasibilityAssessment,
        EnvironmentalFeasibility $environmentalFeasibility
    ): void {
        abort_unless(
            (int) $environmentalFeasibility
                ->feasibility_assessment_id ===
            (int) $feasibilityAssessment->id,
            404
        );
    }
}