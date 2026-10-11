<?php

namespace App\Http\Controllers\Admin\Feasibility;

use App\Http\Controllers\Controller;
use App\Models\FeasibilityAssessment;
use App\Models\InvestmentDecision;
use App\Models\Land;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvestmentDecisionController extends Controller
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

        $investmentDecisions = $feasibilityAssessment
            ->investmentDecisions()
            ->latest('id')
            ->paginate(15);

        return view(
            'feasibility.investment-decisions.index',
            compact(
                'land',
                'feasibilityAssessment',
                'investmentDecisions'
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
            'feasibility.investment-decisions.create',
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
            | Basic Information
            |--------------------------------------------------------------------------
            */

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                'string',
                'max:50',
            ],

            'decision' => [
                'nullable',
                'string',
                'max:50',
            ],

            'decision_date' => [
                'nullable',
                'date',
            ],

            'investment_recommendation' => [
                'nullable',
                'string',
                'max:100',
            ],

            'investment_priority' => [
                'nullable',
                'string',
                'max:50',
            ],


            /*
            |--------------------------------------------------------------------------
            | Scores
            |--------------------------------------------------------------------------
            */

            'financial_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'technical_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'environmental_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'legal_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'location_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'market_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'risk_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'investment_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'overall_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],


            /*
            |--------------------------------------------------------------------------
            | Investment
            |--------------------------------------------------------------------------
            */

            'recommended_investment' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'approved_investment' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'expected_roi' => [
                'nullable',
                'numeric',
            ],

            'expected_irr' => [
                'nullable',
                'numeric',
            ],

            'expected_npv' => [
                'nullable',
                'numeric',
            ],

            'expected_payback_period' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | Conditions
            |--------------------------------------------------------------------------
            */

            'approval_conditions' => [
                'nullable',
                'string',
            ],

            'pre_investment_conditions' => [
                'nullable',
                'string',
            ],

            'risk_conditions' => [
                'nullable',
                'string',
            ],

            'financial_conditions' => [
                'nullable',
                'string',
            ],

            'legal_conditions' => [
                'nullable',
                'string',
            ],

            'technical_conditions' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Decision Rationale
            |--------------------------------------------------------------------------
            */

            'key_strengths' => [
                'nullable',
                'string',
            ],

            'key_weaknesses' => [
                'nullable',
                'string',
            ],

            'key_opportunities' => [
                'nullable',
                'string',
            ],

            'key_risks' => [
                'nullable',
                'string',
            ],

            'decision_rationale' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Committee
            |--------------------------------------------------------------------------
            */

            'committee_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'committee_members' => [
                'nullable',
                'string',
            ],

            'committee_notes' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Final Recommendation
            |--------------------------------------------------------------------------
            */

            'final_recommendation' => [
                'nullable',
                'string',
            ],

            'management_comments' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Generate Decision Number
        |--------------------------------------------------------------------------
        */

        $validated['decision_number'] =
            'ID-' .
            now()->format('YmdHis') .
            '-' .
            Str::upper(
                Str::random(4)
            );


        /*
        |--------------------------------------------------------------------------
        | Relationship
        |--------------------------------------------------------------------------
        */

        $validated['feasibility_assessment_id'] =
            $feasibilityAssessment->id;


        /*
        |--------------------------------------------------------------------------
        | User
        |--------------------------------------------------------------------------
        */

        $validated['created_by'] =
            auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Decision By
        |--------------------------------------------------------------------------
        */

        if (
            !empty($validated['decision'])
            &&
            empty($validated['decision_by'])
        ) {
            $validated['decision_by'] =
                auth()->id();
        }


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        $investmentDecision =
            InvestmentDecision::create(
                $validated
            );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'admin.land.lands.feasibility-assessments.investment-decisions.show',
            [
                'land' =>
                    $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,

                'investmentDecision' =>
                    $investmentDecision->id,
            ]
        )->with(
            'success',
            'Investment decision created successfully.'
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
        InvestmentDecision $investmentDecision
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $investmentDecision
        );


        return view(
            'feasibility.investment-decisions.show',
            compact(
                'land',
                'feasibilityAssessment',
                'investmentDecision'
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
        InvestmentDecision $investmentDecision
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $investmentDecision
        );


        return view(
            'feasibility.investment-decisions.edit',
            compact(
                'land',
                'feasibilityAssessment',
                'investmentDecision'
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
        InvestmentDecision $investmentDecision
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $investmentDecision
        );


        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                'string',
                'max:50',
            ],

            'decision' => [
                'nullable',
                'string',
                'max:50',
            ],

            'decision_date' => [
                'nullable',
                'date',
            ],

            'investment_recommendation' => [
                'nullable',
                'string',
                'max:100',
            ],

            'investment_priority' => [
                'nullable',
                'string',
                'max:50',
            ],


            /*
            |--------------------------------------------------------------------------
            | Scores
            |--------------------------------------------------------------------------
            */

            'financial_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'technical_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'environmental_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'legal_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'location_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'market_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'risk_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'investment_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'overall_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],


            /*
            |--------------------------------------------------------------------------
            | Investment
            |--------------------------------------------------------------------------
            */

            'recommended_investment' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'approved_investment' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'expected_roi' => [
                'nullable',
                'numeric',
            ],

            'expected_irr' => [
                'nullable',
                'numeric',
            ],

            'expected_npv' => [
                'nullable',
                'numeric',
            ],

            'expected_payback_period' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | Conditions
            |--------------------------------------------------------------------------
            */

            'approval_conditions' => [
                'nullable',
                'string',
            ],

            'pre_investment_conditions' => [
                'nullable',
                'string',
            ],

            'risk_conditions' => [
                'nullable',
                'string',
            ],

            'financial_conditions' => [
                'nullable',
                'string',
            ],

            'legal_conditions' => [
                'nullable',
                'string',
            ],

            'technical_conditions' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Decision Rationale
            |--------------------------------------------------------------------------
            */

            'key_strengths' => [
                'nullable',
                'string',
            ],

            'key_weaknesses' => [
                'nullable',
                'string',
            ],

            'key_opportunities' => [
                'nullable',
                'string',
            ],

            'key_risks' => [
                'nullable',
                'string',
            ],

            'decision_rationale' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Committee
            |--------------------------------------------------------------------------
            */

            'committee_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'committee_members' => [
                'nullable',
                'string',
            ],

            'committee_notes' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Final Recommendation
            |--------------------------------------------------------------------------
            */

            'final_recommendation' => [
                'nullable',
                'string',
            ],

            'management_comments' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Decision By
        |--------------------------------------------------------------------------
        */

        if (
            !empty($validated['decision'])
        ) {
            $validated['decision_by'] =
                auth()->id();
        }


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

        $investmentDecision->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'admin.land.lands.feasibility-assessments.investment-decisions.show',
            [
                'land' =>
                    $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,

                'investmentDecision' =>
                    $investmentDecision->id,
            ]
        )->with(
            'success',
            'Investment decision updated successfully.'
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
        InvestmentDecision $investmentDecision
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $investmentDecision
        );


        $investmentDecision->delete();


        return redirect()->route(
            'admin.land.lands.feasibility-assessments.investment-decisions.index',
            [
                'land' =>
                    $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,
            ]
        )->with(
            'success',
            'Investment decision deleted successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Land & Assessment
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
    | Validate Decision Belongs To Assessment
    |--------------------------------------------------------------------------
    */

    private function validateBelongsToAssessment(
        FeasibilityAssessment $feasibilityAssessment,
        InvestmentDecision $investmentDecision
    ): void {
        abort_unless(
            (int) $investmentDecision
                ->feasibility_assessment_id ===
            (int) $feasibilityAssessment->id,
            404
        );
    }
}