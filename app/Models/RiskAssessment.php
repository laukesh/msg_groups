<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskAssessment extends Model
{
    use HasFactory;

    protected $table = 'risk_assessments';

    protected $fillable = [

        'feasibility_assessment_id',

        'analysis_number',

        'title',

        'status',

        /*
        |--------------------------------------------------------------------------
        | Overall Risk
        |--------------------------------------------------------------------------
        */

        'overall_risk_rating',
        'overall_risk_score',
        'risk_summary',

        /*
        |--------------------------------------------------------------------------
        | Market
        |--------------------------------------------------------------------------
        */

        'market_risk_rating',
        'market_risk_score',
        'market_risk_details',
        'market_risk_mitigation',

        /*
        |--------------------------------------------------------------------------
        | Land
        |--------------------------------------------------------------------------
        */

        'land_risk_rating',
        'land_risk_score',
        'land_risk_details',
        'land_risk_mitigation',

        /*
        |--------------------------------------------------------------------------
        | Technical / Construction
        |--------------------------------------------------------------------------
        */

        'technical_risk_rating',
        'technical_risk_score',
        'technical_risk_details',
        'technical_risk_mitigation',

        'construction_risk_rating',
        'construction_risk_score',
        'construction_risk_details',
        'construction_risk_mitigation',

        /*
        |--------------------------------------------------------------------------
        | Financial
        |--------------------------------------------------------------------------
        */

        'financial_risk_rating',
        'financial_risk_score',
        'financial_risk_details',
        'financial_risk_mitigation',

        /*
        |--------------------------------------------------------------------------
        | Legal / Regulatory
        |--------------------------------------------------------------------------
        */

        'legal_risk_rating',
        'legal_risk_score',
        'legal_risk_details',
        'legal_risk_mitigation',

        'regulatory_risk_rating',
        'regulatory_risk_score',
        'regulatory_risk_details',
        'regulatory_risk_mitigation',

        /*
        |--------------------------------------------------------------------------
        | Environmental
        |--------------------------------------------------------------------------
        */

        'environmental_risk_rating',
        'environmental_risk_score',
        'environmental_risk_details',
        'environmental_risk_mitigation',

        /*
        |--------------------------------------------------------------------------
        | Operational
        |--------------------------------------------------------------------------
        */

        'operational_risk_rating',
        'operational_risk_score',
        'operational_risk_details',
        'operational_risk_mitigation',

        /*
        |--------------------------------------------------------------------------
        | Funding
        |--------------------------------------------------------------------------
        */

        'funding_risk_rating',
        'funding_risk_score',
        'funding_risk_details',
        'funding_risk_mitigation',

        /*
        |--------------------------------------------------------------------------
        | Execution
        |--------------------------------------------------------------------------
        */

        'execution_risk_rating',
        'execution_risk_score',
        'execution_risk_details',
        'execution_risk_mitigation',

        /*
        |--------------------------------------------------------------------------
        | Schedule
        |--------------------------------------------------------------------------
        */

        'schedule_risk_rating',
        'schedule_risk_score',
        'schedule_risk_details',
        'schedule_risk_mitigation',

        /*
        |--------------------------------------------------------------------------
        | Economic
        |--------------------------------------------------------------------------
        */

        'economic_risk_rating',
        'economic_risk_score',
        'economic_risk_details',
        'economic_risk_mitigation',

        /*
        |--------------------------------------------------------------------------
        | Political
        |--------------------------------------------------------------------------
        */

        'political_risk_rating',
        'political_risk_score',
        'political_risk_details',
        'political_risk_mitigation',

        /*
        |--------------------------------------------------------------------------
        | Force Majeure
        |--------------------------------------------------------------------------
        */

        'force_majeure_risk_rating',
        'force_majeure_risk_score',
        'force_majeure_risk_details',
        'force_majeure_risk_mitigation',

        /*
        |--------------------------------------------------------------------------
        | Key Risks
        |--------------------------------------------------------------------------
        */

        'key_risks',
        'critical_risks',
        'risk_priorities',

        /*
        |--------------------------------------------------------------------------
        | Mitigation
        |--------------------------------------------------------------------------
        */

        'mitigation_strategy',
        'contingency_plan',
        'risk_monitoring_plan',

        /*
        |--------------------------------------------------------------------------
        | Findings
        |--------------------------------------------------------------------------
        */

        'key_risk_findings',
        'recommendation',

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