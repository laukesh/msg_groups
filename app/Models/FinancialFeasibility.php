<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialFeasibility extends Model
{
    use HasFactory;

    protected $fillable = [

        'feasibility_assessment_id',

        'analysis_number',
        'title',

        'land_cost',
        'construction_cost',
        'development_cost',
        'infrastructure_cost',
        'professional_fee',
        'approval_cost',
        'marketing_cost',
        'financing_cost',
        'contingency_cost',
        'other_project_cost',

        'total_project_cost',

        'sales_revenue',
        'rental_revenue',
        'other_revenue',
        'total_revenue',

        'operating_expenses',
        'maintenance_cost',
        'administrative_cost',
        'other_operating_cost',

        'net_operating_income',

        'gross_profit',
        'net_profit',
        'profit_margin',

        'roi',
        'irr',
        'npv',
        'payback_period',
        'dscr',

        'equity_contribution',
        'debt_financing',
        'interest_rate',
        'loan_tenure',

        'financial_assumptions',
        'cash_flow_summary',
        'sensitivity_analysis',

        'key_financial_findings',
        'financial_risks',
        'recommendation',

        'overall_financial_score',

        'status',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'land_cost' => 'decimal:2',
        'construction_cost' => 'decimal:2',
        'development_cost' => 'decimal:2',
        'infrastructure_cost' => 'decimal:2',
        'professional_fee' => 'decimal:2',
        'approval_cost' => 'decimal:2',
        'marketing_cost' => 'decimal:2',
        'financing_cost' => 'decimal:2',
        'contingency_cost' => 'decimal:2',
        'other_project_cost' => 'decimal:2',

        'total_project_cost' => 'decimal:2',

        'sales_revenue' => 'decimal:2',
        'rental_revenue' => 'decimal:2',
        'other_revenue' => 'decimal:2',
        'total_revenue' => 'decimal:2',

        'operating_expenses' => 'decimal:2',
        'maintenance_cost' => 'decimal:2',
        'administrative_cost' => 'decimal:2',
        'other_operating_cost' => 'decimal:2',

        'net_operating_income' => 'decimal:2',

        'gross_profit' => 'decimal:2',
        'net_profit' => 'decimal:2',
        'profit_margin' => 'decimal:2',

        'roi' => 'decimal:2',
        'irr' => 'decimal:2',
        'npv' => 'decimal:2',
        'payback_period' => 'decimal:2',
        'dscr' => 'decimal:2',

        'equity_contribution' => 'decimal:2',
        'debt_financing' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'loan_tenure' => 'decimal:2',

        'overall_financial_score' => 'decimal:2',
    ];


    public function feasibilityAssessment()
    {
        return $this->belongsTo(
            FeasibilityAssessment::class
        );
    }


    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    public function updater()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
}