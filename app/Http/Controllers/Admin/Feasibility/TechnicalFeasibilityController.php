<?php

namespace App\Http\Controllers\Admin\Feasibility;

use App\Http\Controllers\Controller;
use App\Models\Land;
use App\Models\FeasibilityAssessment;
use App\Models\TechnicalFeasibility;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TechnicalFeasibilityController extends Controller
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

        $technicalFeasibilities =
            $feasibilityAssessment
                ->technicalFeasibilities()
                ->latest('id')
                ->paginate(15);

        return view(
            'feasibility.technical-feasibilities.index',
            compact(
                'land',
                'feasibilityAssessment',
                'technicalFeasibilities'
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
            'feasibility.technical-feasibilities.create',
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
            | Site Development
            |--------------------------------------------------------------------------
            */

            'site_development_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'site_development_details' => [
                'nullable',
                'string',
            ],

            'site_topography' => [
                'nullable',
                'string',
                'max:150',
            ],

            'site_topography_details' => [
                'nullable',
                'string',
            ],

            'soil_condition' => [
                'nullable',
                'string',
                'max:150',
            ],

            'soil_condition_details' => [
                'nullable',
                'string',
            ],

            'geotechnical_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'geotechnical_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Construction
            |--------------------------------------------------------------------------
            */

            'construction_feasibility_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'construction_feasibility_details' => [
                'nullable',
                'string',
            ],

            'construction_method' => [
                'nullable',
                'string',
                'max:150',
            ],

            'construction_method_details' => [
                'nullable',
                'string',
            ],

            'construction_period' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'construction_constraints' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Infrastructure
            |--------------------------------------------------------------------------
            */

            'infrastructure_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'infrastructure_details' => [
                'nullable',
                'string',
            ],

            'road_access_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'road_access_details' => [
                'nullable',
                'string',
            ],

            'drainage_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'drainage_details' => [
                'nullable',
                'string',
            ],

            'sewerage_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'sewerage_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Utilities
            |--------------------------------------------------------------------------
            */

            'electricity_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'electricity_details' => [
                'nullable',
                'string',
            ],

            'water_supply_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'water_supply_details' => [
                'nullable',
                'string',
            ],

            'gas_supply_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'gas_supply_details' => [
                'nullable',
                'string',
            ],

            'telecommunications_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'telecommunications_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Access & Connectivity
            |--------------------------------------------------------------------------
            */

            'transportation_access_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'transportation_access_details' => [
                'nullable',
                'string',
            ],

            'public_transport_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'public_transport_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Development Parameters
            |--------------------------------------------------------------------------
            */

            'permissible_fsi' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'permissible_ground_coverage' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'permissible_height' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'development_constraints' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Construction Technology
            |--------------------------------------------------------------------------
            */

            'technology_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'technology_details' => [
                'nullable',
                'string',
            ],

            'proposed_construction_technology' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Implementation
            |--------------------------------------------------------------------------
            */

            'implementation_feasibility_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'implementation_details' => [
                'nullable',
                'string',
            ],

            'estimated_implementation_period' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | Risks
            |--------------------------------------------------------------------------
            */

            'technical_risks' => [
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

            'key_technical_findings' => [
                'nullable',
                'string',
            ],

            'recommendation' => [
                'nullable',
                'string',
            ],

            'overall_technical_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Assessment
        |--------------------------------------------------------------------------
        */

        $validated['feasibility_assessment_id'] =
            $feasibilityAssessment->id;


        /*
        |--------------------------------------------------------------------------
        | Analysis Number
        |--------------------------------------------------------------------------
        */

        $validated['analysis_number'] =
            'TF-' .
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

        $technicalFeasibility =
            TechnicalFeasibility::create(
                $validated
            );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'admin.land.lands.feasibility-assessments.technical-feasibilities.show',
            [
                'land' =>
                    $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,

                'technicalFeasibility' =>
                    $technicalFeasibility->id,
            ]
        )->with(
            'success',
            'Technical feasibility created successfully.'
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
        TechnicalFeasibility $technicalFeasibility
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $technicalFeasibility
        );

        return view(
            'feasibility.technical-feasibilities.show',
            compact(
                'land',
                'feasibilityAssessment',
                'technicalFeasibility'
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
        TechnicalFeasibility $technicalFeasibility
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $technicalFeasibility
        );

        return view(
            'feasibility.technical-feasibilities.edit',
            compact(
                'land',
                'feasibilityAssessment',
                'technicalFeasibility'
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
        TechnicalFeasibility $technicalFeasibility
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $technicalFeasibility
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
            | Site Development
            |--------------------------------------------------------------------------
            */

            'site_development_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'site_development_details' => [
                'nullable',
                'string',
            ],

            'site_topography' => [
                'nullable',
                'string',
                'max:150',
            ],

            'site_topography_details' => [
                'nullable',
                'string',
            ],

            'soil_condition' => [
                'nullable',
                'string',
                'max:150',
            ],

            'soil_condition_details' => [
                'nullable',
                'string',
            ],

            'geotechnical_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'geotechnical_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Construction
            |--------------------------------------------------------------------------
            */

            'construction_feasibility_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'construction_feasibility_details' => [
                'nullable',
                'string',
            ],

            'construction_method' => [
                'nullable',
                'string',
                'max:150',
            ],

            'construction_method_details' => [
                'nullable',
                'string',
            ],

            'construction_period' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'construction_constraints' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Infrastructure
            |--------------------------------------------------------------------------
            */

            'infrastructure_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'infrastructure_details' => [
                'nullable',
                'string',
            ],

            'road_access_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'road_access_details' => [
                'nullable',
                'string',
            ],

            'drainage_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'drainage_details' => [
                'nullable',
                'string',
            ],

            'sewerage_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'sewerage_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Utilities
            |--------------------------------------------------------------------------
            */

            'electricity_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'electricity_details' => [
                'nullable',
                'string',
            ],

            'water_supply_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'water_supply_details' => [
                'nullable',
                'string',
            ],

            'gas_supply_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'gas_supply_details' => [
                'nullable',
                'string',
            ],

            'telecommunications_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'telecommunications_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Access & Connectivity
            |--------------------------------------------------------------------------
            */

            'transportation_access_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'transportation_access_details' => [
                'nullable',
                'string',
            ],

            'public_transport_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'public_transport_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Development Parameters
            |--------------------------------------------------------------------------
            */

            'permissible_fsi' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'permissible_ground_coverage' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'permissible_height' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'development_constraints' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Construction Technology
            |--------------------------------------------------------------------------
            */

            'technology_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'technology_details' => [
                'nullable',
                'string',
            ],

            'proposed_construction_technology' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Implementation
            |--------------------------------------------------------------------------
            */

            'implementation_feasibility_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'implementation_details' => [
                'nullable',
                'string',
            ],

            'estimated_implementation_period' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | Risks
            |--------------------------------------------------------------------------
            */

            'technical_risks' => [
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

            'key_technical_findings' => [
                'nullable',
                'string',
            ],

            'recommendation' => [
                'nullable',
                'string',
            ],

            'overall_technical_score' => [
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


        /*
        |--------------------------------------------------------------------------
        | Updated By
        |--------------------------------------------------------------------------
        */

        $validated['updated_by'] =
            auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $technicalFeasibility->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'admin.land.lands.feasibility-assessments.technical-feasibilities.show',
            [
                'land' =>
                    $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,

                'technicalFeasibility' =>
                    $technicalFeasibility->id,
            ]
        )->with(
            'success',
            'Technical feasibility updated successfully.'
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
        TechnicalFeasibility $technicalFeasibility
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $technicalFeasibility
        );

        $technicalFeasibility->delete();

        return redirect()->route(
            'admin.land.lands.feasibility-assessments.technical-feasibilities.index',
            [
                'land' =>
                    $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,
            ]
        )->with(
            'success',
            'Technical feasibility deleted successfully.'
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
    | Validate Assessment -> Technical Feasibility
    |--------------------------------------------------------------------------
    */

    private function validateBelongsToAssessment(
        FeasibilityAssessment $feasibilityAssessment,
        TechnicalFeasibility $technicalFeasibility
    ): void {
        abort_unless(
            (int) $technicalFeasibility
                ->feasibility_assessment_id ===
            (int) $feasibilityAssessment->id,
            404
        );
    }
}