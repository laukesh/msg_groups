<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentAnalysis extends Model
{
    use HasFactory;

    protected $table = 'investment_analyses';

    protected $fillable = [

        'feasibility_assessment_id',

        'analysis_number',

        'title',

        'status',

        /*
        |--------------------------------------------------------------------------
        | Investment Requirement
        |--------------------------------------------------------------------------
        */

        'total_investment',
        'initial_investment',
        'working_capital',
        'reserve_requirement',
        'contingency_reserve',

        /*
        |--------------------------------------------------------------------------
        | Equity & Debt
        |--------------------------------------------------------------------------
        */

        'equity_investment',
        'debt_investment',
        'promoter_contribution',
        'external_investment',

        /*
        |--------------------------------------------------------------------------
        | Returns
        |--------------------------------------------------------------------------
        */

        'expected_revenue',
        'expected_profit',
        'expected_cash_flow',

        'roi',
        'irr',
        'npv',
        'payback_period',
        'profit_margin',
        'investment_multiple',

        /*
        |--------------------------------------------------------------------------
        | Valuation
        |--------------------------------------------------------------------------
        */

        'project_valuation',
        'investment_valuation',
        'exit_valuation',
        'expected_exit_value',

        /*
        |--------------------------------------------------------------------------
        | Exit
        |--------------------------------------------------------------------------
        */

        'exit_strategy',
        'exit_period',
        'exit_assumptions',

        /*
        |--------------------------------------------------------------------------
        | Scenarios
        |--------------------------------------------------------------------------
        */

        'base_case_return',
        'optimistic_case_return',
        'pessimistic_case_return',

        'base_case_irr',
        'optimistic_case_irr',
        'pessimistic_case_irr',

        /*
        |--------------------------------------------------------------------------
        | Sensitivity
        |--------------------------------------------------------------------------
        */

        'revenue_sensitivity',
        'cost_sensitivity',
        'price_sensitivity',
        'interest_rate_sensitivity',

        /*
        |--------------------------------------------------------------------------
        | Investor Assessment
        |--------------------------------------------------------------------------
        */

        'investment_attractiveness',
        'investment_risk_rating',
        'investment_horizon',
        'investor_profile',

        /*
        |--------------------------------------------------------------------------
        | Investment Conditions
        |--------------------------------------------------------------------------
        */

        'minimum_investment',
        'recommended_investment',
        'maximum_investment',

        /*
        |--------------------------------------------------------------------------
        | Findings
        |--------------------------------------------------------------------------
        */

        'investment_strengths',
        'investment_weaknesses',
        'investment_opportunities',
        'investment_threats',

        'key_investment_findings',
        'investment_risks',

        'recommendation',

        'overall_investment_score',

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        'created_by',
        'updated_by',
    ];


    public function feasibilityAssessment()
    {
        return $this->belongsTo(
            FeasibilityAssessment::class
        );
    }
}