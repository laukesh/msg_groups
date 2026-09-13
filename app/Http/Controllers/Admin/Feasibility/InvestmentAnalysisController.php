<?php

namespace App\Http\Controllers\Admin\Feasibility;

use App\Http\Controllers\Controller;
use App\Models\Land;
use App\Models\FeasibilityAssessment;
use App\Models\InvestmentAnalysis;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvestmentAnalysisController extends Controller
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

        $investmentAnalyses = $feasibilityAssessment
            ->investmentAnalyses()
            ->latest('id')
            ->paginate(15);

        return view(
            'feasibility.investment-analyses.index',
            compact(
                'land',
                'feasibilityAssessment',
                'investmentAnalyses'
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
            'feasibility.investment-analyses.create',
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
            | Investment Requirement
            |--------------------------------------------------------------------------
            */

            'total_investment' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'initial_investment' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'working_capital' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'reserve_requirement' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'contingency_reserve' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | Equity / Debt
            |--------------------------------------------------------------------------
            */

            'equity_investment' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'debt_investment' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'promoter_contribution' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'external_investment' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | Returns
            |--------------------------------------------------------------------------
            */

            'expected_revenue' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'expected_profit' => [
                'nullable',
                'numeric',
            ],

            'expected_cash_flow' => [
                'nullable',
                'numeric',
            ],

            'roi' => [
                'nullable',
                'numeric',
            ],

            'irr' => [
                'nullable',
                'numeric',
            ],

            'npv' => [
                'nullable',
                'numeric',
            ],

            'payback_period' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'profit_margin' => [
                'nullable',
                'numeric',
            ],

            'investment_multiple' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | Valuation
            |--------------------------------------------------------------------------
            */

            'project_valuation' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'investment_valuation' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'exit_valuation' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'expected_exit_value' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | Exit Strategy
            |--------------------------------------------------------------------------
            */

            'exit_strategy' => [
                'nullable',
                'string',
                'max:100',
            ],

            'exit_period' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'exit_assumptions' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Investment Scenarios
            |--------------------------------------------------------------------------
            */

            'base_case_return' => [
                'nullable',
                'numeric',
            ],

            'optimistic_case_return' => [
                'nullable',
                'numeric',
            ],

            'pessimistic_case_return' => [
                'nullable',
                'numeric',
            ],

            'base_case_irr' => [
                'nullable',
                'numeric',
            ],

            'optimistic_case_irr' => [
                'nullable',
                'numeric',
            ],

            'pessimistic_case_irr' => [
                'nullable',
                'numeric',
            ],


            /*
            |--------------------------------------------------------------------------
            | Sensitivity
            |--------------------------------------------------------------------------
            */

            'revenue_sensitivity' => [
                'nullable',
                'string',
            ],

            'cost_sensitivity' => [
                'nullable',
                'string',
            ],

            'price_sensitivity' => [
                'nullable',
                'string',
            ],

            'interest_rate_sensitivity' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Investor Assessment
            |--------------------------------------------------------------------------
            */

            'investment_attractiveness' => [
                'nullable',
                'string',
                'max:50',
            ],

            'investment_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'investment_horizon' => [
                'nullable',
                'string',
                'max:100',
            ],

            'investor_profile' => [
                'nullable',
                'string',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | Investment Conditions
            |--------------------------------------------------------------------------
            */

            'minimum_investment' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'recommended_investment' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'maximum_investment' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | Findings
            |--------------------------------------------------------------------------
            */

            'investment_strengths' => [
                'nullable',
                'string',
            ],

            'investment_weaknesses' => [
                'nullable',
                'string',
            ],

            'investment_opportunities' => [
                'nullable',
                'string',
            ],

            'investment_threats' => [
                'nullable',
                'string',
            ],

            'key_investment_findings' => [
                'nullable',
                'string',
            ],

            'investment_risks' => [
                'nullable',
                'string',
            ],

            'recommendation' => [
                'nullable',
                'string',
            ],

            'overall_investment_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Feasibility Assessment
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
            'IA-' .
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

        $investmentAnalysis =
            InvestmentAnalysis::create(
                $validated
            );


        return redirect()->route(
            'admin.land.lands.feasibility-assessments.investment-analyses.show',
            [
                'land' =>
                    $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,

                'investmentAnalysis' =>
                    $investmentAnalysis->id,
            ]
        )->with(
            'success',
            'Investment analysis created successfully.'
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
        InvestmentAnalysis $investmentAnalysis
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $investmentAnalysis
        );

        return view(
            'feasibility.investment-analyses.show',
            compact(
                'land',
                'feasibilityAssessment',
                'investmentAnalysis'
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
        InvestmentAnalysis $investmentAnalysis
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $investmentAnalysis
        );

        return view(
            'feasibility.investment-analyses.edit',
            compact(
                'land',
                'feasibilityAssessment',
                'investmentAnalysis'
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
        InvestmentAnalysis $investmentAnalysis
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $investmentAnalysis
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
            | Investment Requirement
            |--------------------------------------------------------------------------
            */

            'total_investment' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'initial_investment' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'working_capital' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'reserve_requirement' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'contingency_reserve' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | Equity / Debt
            |--------------------------------------------------------------------------
            */

            'equity_investment' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'debt_investment' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'promoter_contribution' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'external_investment' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | Returns
            |--------------------------------------------------------------------------
            */

            'expected_revenue' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'expected_profit' => [
                'nullable',
                'numeric',
            ],

            'expected_cash_flow' => [
                'nullable',
                'numeric',
            ],

            'roi' => [
                'nullable',
                'numeric',
            ],

            'irr' => [
                'nullable',
                'numeric',
            ],

            'npv' => [
                'nullable',
                'numeric',
            ],

            'payback_period' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'profit_margin' => [
                'nullable',
                'numeric',
            ],

            'investment_multiple' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | Valuation
            |--------------------------------------------------------------------------
            */

            'project_valuation' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'investment_valuation' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'exit_valuation' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'expected_exit_value' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | Exit
            |--------------------------------------------------------------------------
            */

            'exit_strategy' => [
                'nullable',
                'string',
                'max:100',
            ],

            'exit_period' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'exit_assumptions' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Scenarios
            |--------------------------------------------------------------------------
            */

            'base_case_return' => [
                'nullable',
                'numeric',
            ],

            'optimistic_case_return' => [
                'nullable',
                'numeric',
            ],

            'pessimistic_case_return' => [
                'nullable',
                'numeric',
            ],

            'base_case_irr' => [
                'nullable',
                'numeric',
            ],

            'optimistic_case_irr' => [
                'nullable',
                'numeric',
            ],

            'pessimistic_case_irr' => [
                'nullable',
                'numeric',
            ],


            /*
            |--------------------------------------------------------------------------
            | Sensitivity
            |--------------------------------------------------------------------------
            */

            'revenue_sensitivity' => [
                'nullable',
                'string',
            ],

            'cost_sensitivity' => [
                'nullable',
                'string',
            ],

            'price_sensitivity' => [
                'nullable',
                'string',
            ],

            'interest_rate_sensitivity' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Investor Assessment
            |--------------------------------------------------------------------------
            */

            'investment_attractiveness' => [
                'nullable',
                'string',
                'max:50',
            ],

            'investment_risk_rating' => [
                'nullable',
                'string',
                'max:50',
            ],

            'investment_horizon' => [
                'nullable',
                'string',
                'max:100',
            ],

            'investor_profile' => [
                'nullable',
                'string',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | Investment Conditions
            |--------------------------------------------------------------------------
            */

            'minimum_investment' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'recommended_investment' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'maximum_investment' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | Findings
            |--------------------------------------------------------------------------
            */

            'investment_strengths' => [
                'nullable',
                'string',
            ],

            'investment_weaknesses' => [
                'nullable',
                'string',
            ],

            'investment_opportunities' => [
                'nullable',
                'string',
            ],

            'investment_threats' => [
                'nullable',
                'string',
            ],

            'key_investment_findings' => [
                'nullable',
                'string',
            ],

            'investment_risks' => [
                'nullable',
                'string',
            ],

            'recommendation' => [
                'nullable',
                'string',
            ],

            'overall_investment_score' => [
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

        $investmentAnalysis->update(
            $validated
        );


        return redirect()->route(
            'admin.land.lands.feasibility-assessments.investment-analyses.show',
            [
                'land' =>
                    $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,

                'investmentAnalysis' =>
                    $investmentAnalysis->id,
            ]
        )->with(
            'success',
            'Investment analysis updated successfully.'
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
        InvestmentAnalysis $investmentAnalysis
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $investmentAnalysis
        );

        $investmentAnalysis->delete();

        return redirect()->route(
            'admin.land.lands.feasibility-assessments.investment-analyses.index',
            [
                'land' =>
                    $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,
            ]
        )->with(
            'success',
            'Investment analysis deleted successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Land -> Feasibility Assessment
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
    | Validate Investment Analysis -> Assessment
    |--------------------------------------------------------------------------
    */

    private function validateBelongsToAssessment(
        FeasibilityAssessment $feasibilityAssessment,
        InvestmentAnalysis $investmentAnalysis
    ): void {
        abort_unless(
            (int) $investmentAnalysis
                ->feasibility_assessment_id ===
            (int) $feasibilityAssessment->id,
            404
        );
    }
}