<?php

namespace App\Http\Controllers\Admin\Feasibility;

use App\Http\Controllers\Controller;
use App\Models\DemandSupplyAnalysis;
use App\Models\FeasibilityAssessment;
use App\Models\Land;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DemandSupplyAnalysisController extends Controller
{
    public function index(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ) {
        $analyses = $feasibilityAssessment
            ->demandSupplyAnalyses()
            ->latest('id')
            ->paginate(15);

        return view(
            'feasibility.demand-supply-analyses.index',
            [
                'land' => $land,
                'feasibilityAssessment' =>
                    $feasibilityAssessment,
                'analyses' => $analyses,
            ]
        );
    }


    public function create(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ) {
        return view(
            'feasibility.demand-supply-analyses.create',
            [
                'land' => $land,
                'feasibilityAssessment' =>
                    $feasibilityAssessment,
            ]
        );
    }


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

            'market_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'demand_assessment' => [
                'nullable',
                'string',
            ],

            'current_demand' => [
                'nullable',
                'numeric',
            ],

            'projected_demand' => [
                'nullable',
                'numeric',
            ],

            'demand_growth_rate' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'current_supply' => [
                'nullable',
                'numeric',
            ],

            'future_supply' => [
                'nullable',
                'numeric',
            ],

            'supply_pipeline' => [
                'nullable',
                'string',
            ],

            'demand_supply_gap' => [
                'nullable',
                'numeric',
            ],

            'occupancy_rate' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'utilization_rate' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'target_customer_demand' => [
                'nullable',
                'string',
            ],

            'competitor_supply' => [
                'nullable',
                'string',
            ],

            'market_capacity' => [
                'nullable',
                'numeric',
            ],

            'forecast_period' => [
                'nullable',
                'string',
                'max:100',
            ],

            'forecast_demand' => [
                'nullable',
                'numeric',
            ],

            'key_drivers' => [
                'nullable',
                'string',
            ],

            'key_constraints' => [
                'nullable',
                'string',
            ],

            'key_findings' => [
                'nullable',
                'string',
            ],

            'recommendation' => [
                'nullable',
                'string',
            ],

            'overall_demand_supply_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],
        ]);


        $validated['feasibility_assessment_id'] =
            $feasibilityAssessment->id;


        $validated['analysis_number'] =
            'DS-' .
            now()->format('YmdHis') .
            '-' .
            Str::upper(
                Str::random(4)
            );


        $validated['status'] = 'Draft';


        $validated['created_by'] =
            auth()->id();


        $analysis =
            DemandSupplyAnalysis::create(
                $validated
            );


        return redirect()->route(
            'admin.land.lands.feasibility-assessments.demand-supply-analyses.show',
            [
                'land' => $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,

                'demandSupplyAnalysis' =>
                    $analysis->id,
            ]
        )->with(
            'success',
            'Demand & Supply Analysis created successfully.'
        );
    }


    public function show(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        DemandSupplyAnalysis $demandSupplyAnalysis
    ) {
        return view(
            'feasibility.demand-supply-analyses.show',
            [
                'land' => $land,

                'feasibilityAssessment' =>
                    $feasibilityAssessment,

                'analysis' =>
                    $demandSupplyAnalysis,
            ]
        );
    }


    public function edit(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        DemandSupplyAnalysis $demandSupplyAnalysis
    ) {
        return view(
            'feasibility.demand-supply-analyses.edit',
            [
                'land' => $land,

                'feasibilityAssessment' =>
                    $feasibilityAssessment,

                'analysis' =>
                    $demandSupplyAnalysis,
            ]
        );
    }


    public function update(
        Request $request,
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        DemandSupplyAnalysis $demandSupplyAnalysis
    ) {
        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'market_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'demand_assessment' => [
                'nullable',
                'string',
            ],

            'current_demand' => [
                'nullable',
                'numeric',
            ],

            'projected_demand' => [
                'nullable',
                'numeric',
            ],

            'demand_growth_rate' => [
                'nullable',
                'numeric',
            ],

            'current_supply' => [
                'nullable',
                'numeric',
            ],

            'future_supply' => [
                'nullable',
                'numeric',
            ],

            'supply_pipeline' => [
                'nullable',
                'string',
            ],

            'demand_supply_gap' => [
                'nullable',
                'numeric',
            ],

            'occupancy_rate' => [
                'nullable',
                'numeric',
            ],

            'utilization_rate' => [
                'nullable',
                'numeric',
            ],

            'target_customer_demand' => [
                'nullable',
                'string',
            ],

            'competitor_supply' => [
                'nullable',
                'string',
            ],

            'market_capacity' => [
                'nullable',
                'numeric',
            ],

            'forecast_period' => [
                'nullable',
                'string',
                'max:100',
            ],

            'forecast_demand' => [
                'nullable',
                'numeric',
            ],

            'key_drivers' => [
                'nullable',
                'string',
            ],

            'key_constraints' => [
                'nullable',
                'string',
            ],

            'key_findings' => [
                'nullable',
                'string',
            ],

            'recommendation' => [
                'nullable',
                'string',
            ],

            'overall_demand_supply_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'status' => [
                'required',
                'string',
                'max:50',
            ],
        ]);


        $validated['updated_by'] =
            auth()->id();


        $demandSupplyAnalysis->update(
            $validated
        );


        return redirect()->route(
            'admin.land.lands.feasibility-assessments.demand-supply-analyses.show',
            [
                'land' => $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,

                'demandSupplyAnalysis' =>
                    $demandSupplyAnalysis->id,
            ]
        )->with(
            'success',
            'Demand & Supply Analysis updated successfully.'
        );
    }


    public function destroy(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        DemandSupplyAnalysis $demandSupplyAnalysis
    ) {
        $demandSupplyAnalysis->delete();


        return redirect()->route(
            'admin.land.lands.feasibility-assessments.demand-supply-analyses.index',
            [
                'land' => $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,
            ]
        )->with(
            'success',
            'Demand & Supply Analysis deleted successfully.'
        );
    }
}