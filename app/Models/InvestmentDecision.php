<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentDecision extends Model
{
    use HasFactory;

    protected $table = 'investment_decisions';

    protected $fillable = [

        'feasibility_assessment_id',

        'decision_number',

        'title',

        'status',

        'decision',

        'decision_date',

        'decision_by',

        /*
        |--------------------------------------------------------------------------
        | Scores
        |--------------------------------------------------------------------------
        */

        'financial_score',
        'technical_score',
        'environmental_score',
        'legal_score',
        'location_score',
        'market_score',
        'risk_score',
        'investment_score',
        'overall_score',

        /*
        |--------------------------------------------------------------------------
        | Investment
        |--------------------------------------------------------------------------
        */

        'investment_recommendation',
        'investment_priority',
        'recommended_investment',
        'approved_investment',

        'expected_roi',
        'expected_irr',
        'expected_npv',
        'expected_payback_period',

        /*
        |--------------------------------------------------------------------------
        | Conditions
        |--------------------------------------------------------------------------
        */

        'approval_conditions',
        'pre_investment_conditions',
        'risk_conditions',
        'financial_conditions',
        'legal_conditions',
        'technical_conditions',

        /*
        |--------------------------------------------------------------------------
        | Rationale
        |--------------------------------------------------------------------------
        */

        'key_strengths',
        'key_weaknesses',
        'key_opportunities',
        'key_risks',
        'decision_rationale',

        /*
        |--------------------------------------------------------------------------
        | Committee
        |--------------------------------------------------------------------------
        */

        'committee_name',
        'committee_members',
        'committee_notes',

        /*
        |--------------------------------------------------------------------------
        | Final
        |--------------------------------------------------------------------------
        */

        'final_recommendation',
        'management_comments',

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'decision_date' => 'date',

    ];


    public function feasibilityAssessment()
    {
        return $this->belongsTo(
            FeasibilityAssessment::class
        );
    }

    public function projects()
	{
	    return $this->hasMany(
	        Project::class,
	        'investment_decision_id'
	    );
	}

	public function isApproved(): bool
	{
	    return
	        $this->status === 'Approved'
	        ||
	        in_array(
	            $this->decision,
	            [
	                'Go',
	                'Conditional Go',
	            ],
	            true
	        );
	}

	public function project()
	{
	    return $this->hasOne(
	        Project::class,
	        'investment_decision_id'
	    );
	}
}