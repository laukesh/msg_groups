<?php

namespace App\Http\Controllers\Admin\Feasibility;

use App\Http\Controllers\Controller;
use App\Models\Land;
use App\Models\FeasibilityAssessment;
use App\Models\RiskAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RiskAssessmentController extends Controller
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

        $riskAssessments = $feasibilityAssessment
            ->riskAssessments()
            ->latest('id')
            ->paginate(15);

        return view(
            'feasibility.risk-assessments.index',
            compact(
                'land',
                'feasibilityAssessment',
                'riskAssessments'
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
            'feasibility.risk-assessments.create',
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
            | Overall Risk
            |--------------------------------------------------------------------------
            */

            'overall_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'overall_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'risk_summary' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Market Risk
            |--------------------------------------------------------------------------
            */

            'market_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'market_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'market_risk_details' => [
                'nullable',
                'string',
            ],

            'market_risk_mitigation' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Land Risk
            |--------------------------------------------------------------------------
            */

            'land_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'land_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'land_risk_details' => [
                'nullable',
                'string',
            ],

            'land_risk_mitigation' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Technical Risk
            |--------------------------------------------------------------------------
            */

            'technical_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'technical_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'technical_risk_details' => [
                'nullable',
                'string',
            ],

            'technical_risk_mitigation' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Construction Risk
            |--------------------------------------------------------------------------
            */

            'construction_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'construction_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'construction_risk_details' => [
                'nullable',
                'string',
            ],

            'construction_risk_mitigation' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Financial Risk
            |--------------------------------------------------------------------------
            */

            'financial_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'financial_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'financial_risk_details' => [
                'nullable',
                'string',
            ],

            'financial_risk_mitigation' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Legal Risk
            |--------------------------------------------------------------------------
            */

            'legal_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'legal_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'legal_risk_details' => [
                'nullable',
                'string',
            ],

            'legal_risk_mitigation' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Regulatory Risk
            |--------------------------------------------------------------------------
            */

            'regulatory_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'regulatory_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'regulatory_risk_details' => [
                'nullable',
                'string',
            ],

            'regulatory_risk_mitigation' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Environmental Risk
            |--------------------------------------------------------------------------
            */

            'environmental_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'environmental_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'environmental_risk_details' => [
                'nullable',
                'string',
            ],

            'environmental_risk_mitigation' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Operational Risk
            |--------------------------------------------------------------------------
            */

            'operational_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'operational_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'operational_risk_details' => [
                'nullable',
                'string',
            ],

            'operational_risk_mitigation' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Funding Risk
            |--------------------------------------------------------------------------
            */

            'funding_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'funding_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'funding_risk_details' => [
                'nullable',
                'string',
            ],

            'funding_risk_mitigation' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Execution Risk
            |--------------------------------------------------------------------------
            */

            'execution_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'execution_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'execution_risk_details' => [
                'nullable',
                'string',
            ],

            'execution_risk_mitigation' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Schedule Risk
            |--------------------------------------------------------------------------
            */

            'schedule_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'schedule_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'schedule_risk_details' => [
                'nullable',
                'string',
            ],

            'schedule_risk_mitigation' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Economic Risk
            |--------------------------------------------------------------------------
            */

            'economic_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'economic_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'economic_risk_details' => [
                'nullable',
                'string',
            ],

            'economic_risk_mitigation' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Political Risk
            |--------------------------------------------------------------------------
            */

            'political_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'political_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'political_risk_details' => [
                'nullable',
                'string',
            ],

            'political_risk_mitigation' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Force Majeure Risk
            |--------------------------------------------------------------------------
            */

            'force_majeure_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'force_majeure_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'force_majeure_risk_details' => [
                'nullable',
                'string',
            ],

            'force_majeure_risk_mitigation' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Key Risks
            |--------------------------------------------------------------------------
            */

            'key_risks' => [
                'nullable',
                'string',
            ],

            'critical_risks' => [
                'nullable',
                'string',
            ],

            'risk_priorities' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Mitigation
            |--------------------------------------------------------------------------
            */

            'mitigation_strategy' => [
                'nullable',
                'string',
            ],

            'contingency_plan' => [
                'nullable',
                'string',
            ],

            'risk_monitoring_plan' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Findings
            |--------------------------------------------------------------------------
            */

            'key_risk_findings' => [
                'nullable',
                'string',
            ],

            'recommendation' => [
                'nullable',
                'string',
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
            'RA-' .
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

        $riskAssessment =
            RiskAssessment::create(
                $validated
            );


        return redirect()->route(
            'admin.land.lands.feasibility-assessments.risk-assessments.show',
            [
                'land' =>
                    $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,

                'riskAssessment' =>
                    $riskAssessment->id,
            ]
        )->with(
            'success',
            'Risk assessment created successfully.'
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
        RiskAssessment $riskAssessment
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $riskAssessment
        );

        return view(
            'feasibility.risk-assessments.show',
            compact(
                'land',
                'feasibilityAssessment',
                'riskAssessment'
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
        RiskAssessment $riskAssessment
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $riskAssessment
        );

        return view(
            'feasibility.risk-assessments.edit',
            compact(
                'land',
                'feasibilityAssessment',
                'riskAssessment'
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
        RiskAssessment $riskAssessment
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $riskAssessment
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
            | Overall Risk
            |--------------------------------------------------------------------------
            */

            'overall_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'overall_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'risk_summary' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Risk Categories
            |--------------------------------------------------------------------------
            */

            'market_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'market_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'market_risk_details' => [
                'nullable',
                'string',
            ],

            'market_risk_mitigation' => [
                'nullable',
                'string',
            ],


            'land_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'land_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'land_risk_details' => [
                'nullable',
                'string',
            ],

            'land_risk_mitigation' => [
                'nullable',
                'string',
            ],


            'technical_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'technical_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'technical_risk_details' => [
                'nullable',
                'string',
            ],

            'technical_risk_mitigation' => [
                'nullable',
                'string',
            ],


            'construction_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'construction_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'construction_risk_details' => [
                'nullable',
                'string',
            ],

            'construction_risk_mitigation' => [
                'nullable',
                'string',
            ],


            'financial_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'financial_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'financial_risk_details' => [
                'nullable',
                'string',
            ],

            'financial_risk_mitigation' => [
                'nullable',
                'string',
            ],


            'legal_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'legal_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'legal_risk_details' => [
                'nullable',
                'string',
            ],

            'legal_risk_mitigation' => [
                'nullable',
                'string',
            ],


            'regulatory_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'regulatory_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'regulatory_risk_details' => [
                'nullable',
                'string',
            ],

            'regulatory_risk_mitigation' => [
                'nullable',
                'string',
            ],


            'environmental_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'environmental_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'environmental_risk_details' => [
                'nullable',
                'string',
            ],

            'environmental_risk_mitigation' => [
                'nullable',
                'string',
            ],


            'operational_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'operational_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'operational_risk_details' => [
                'nullable',
                'string',
            ],

            'operational_risk_mitigation' => [
                'nullable',
                'string',
            ],


            'funding_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'funding_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'funding_risk_details' => [
                'nullable',
                'string',
            ],

            'funding_risk_mitigation' => [
                'nullable',
                'string',
            ],


            'execution_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'execution_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'execution_risk_details' => [
                'nullable',
                'string',
            ],

            'execution_risk_mitigation' => [
                'nullable',
                'string',
            ],


            'schedule_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'schedule_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'schedule_risk_details' => [
                'nullable',
                'string',
            ],

            'schedule_risk_mitigation' => [
                'nullable',
                'string',
            ],


            'economic_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'economic_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'economic_risk_details' => [
                'nullable',
                'string',
            ],

            'economic_risk_mitigation' => [
                'nullable',
                'string',
            ],


            'political_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'political_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'political_risk_details' => [
                'nullable',
                'string',
            ],

            'political_risk_mitigation' => [
                'nullable',
                'string',
            ],


            'force_majeure_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'force_majeure_risk_score' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'force_majeure_risk_details' => [
                'nullable',
                'string',
            ],

            'force_majeure_risk_mitigation' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Key Risks
            |--------------------------------------------------------------------------
            */

            'key_risks' => [
                'nullable',
                'string',
            ],

            'critical_risks' => [
                'nullable',
                'string',
            ],

            'risk_priorities' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Mitigation
            |--------------------------------------------------------------------------
            */

            'mitigation_strategy' => [
                'nullable',
                'string',
            ],

            'contingency_plan' => [
                'nullable',
                'string',
            ],

            'risk_monitoring_plan' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Findings
            |--------------------------------------------------------------------------
            */

            'key_risk_findings' => [
                'nullable',
                'string',
            ],

            'recommendation' => [
                'nullable',
                'string',
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

        $riskAssessment->update(
            $validated
        );


        return redirect()->route(
            'admin.land.lands.feasibility-assessments.risk-assessments.show',
            [
                'land' =>
                    $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,

                'riskAssessment' =>
                    $riskAssessment->id,
            ]
        )->with(
            'success',
            'Risk assessment updated successfully.'
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
        RiskAssessment $riskAssessment
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $riskAssessment
        );

        $riskAssessment->delete();

        return redirect()->route(
            'admin.land.lands.feasibility-assessments.risk-assessments.index',
            [
                'land' =>
                    $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,
            ]
        )->with(
            'success',
            'Risk assessment deleted successfully.'
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
    | Validate Assessment -> Risk Assessment
    |--------------------------------------------------------------------------
    */

    private function validateBelongsToAssessment(
        FeasibilityAssessment $feasibilityAssessment,
        RiskAssessment $riskAssessment
    ): void {
        abort_unless(
            (int) $riskAssessment
                ->feasibility_assessment_id ===
            (int) $feasibilityAssessment->id,
            404
        );
    }
}