<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalFeasibility extends Model
{
    use HasFactory;

    protected $table = 'technical_feasibilities';


    protected $fillable = [

        'feasibility_assessment_id',

        'analysis_number',

        'title',

        'status',

        /*
        |--------------------------------------------------------------------------
        | Site Development
        |--------------------------------------------------------------------------
        */

        'site_development_status',
        'site_development_details',

        'site_topography',
        'site_topography_details',

        'soil_condition',
        'soil_condition_details',

        'geotechnical_status',
        'geotechnical_details',


        /*
        |--------------------------------------------------------------------------
        | Construction
        |--------------------------------------------------------------------------
        */

        'construction_feasibility_status',
        'construction_feasibility_details',

        'construction_method',
        'construction_method_details',

        'construction_period',

        'construction_constraints',


        /*
        |--------------------------------------------------------------------------
        | Infrastructure
        |--------------------------------------------------------------------------
        */

        'infrastructure_status',
        'infrastructure_details',

        'road_access_status',
        'road_access_details',

        'drainage_status',
        'drainage_details',

        'sewerage_status',
        'sewerage_details',


        /*
        |--------------------------------------------------------------------------
        | Utilities
        |--------------------------------------------------------------------------
        */

        'electricity_status',
        'electricity_details',

        'water_supply_status',
        'water_supply_details',

        'gas_supply_status',
        'gas_supply_details',

        'telecommunications_status',
        'telecommunications_details',


        /*
        |--------------------------------------------------------------------------
        | Access & Connectivity
        |--------------------------------------------------------------------------
        */

        'transportation_access_status',
        'transportation_access_details',

        'public_transport_status',
        'public_transport_details',


        /*
        |--------------------------------------------------------------------------
        | Development Parameters
        |--------------------------------------------------------------------------
        */

        'permissible_fsi',

        'permissible_ground_coverage',

        'permissible_height',

        'development_constraints',


        /*
        |--------------------------------------------------------------------------
        | Construction Technology
        |--------------------------------------------------------------------------
        */

        'technology_status',

        'technology_details',

        'proposed_construction_technology',


        /*
        |--------------------------------------------------------------------------
        | Implementation
        |--------------------------------------------------------------------------
        */

        'implementation_feasibility_status',

        'implementation_details',

        'estimated_implementation_period',


        /*
        |--------------------------------------------------------------------------
        | Risks
        |--------------------------------------------------------------------------
        */

        'technical_risks',

        'mitigation_measures',


        /*
        |--------------------------------------------------------------------------
        | Findings
        |--------------------------------------------------------------------------
        */

        'key_technical_findings',

        'recommendation',

        'overall_technical_score',


        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        'created_by',

        'updated_by',

    ];


    /*
    |--------------------------------------------------------------------------
    | Relationship
    |--------------------------------------------------------------------------
    */

    public function feasibilityAssessment()
    {
        return $this->belongsTo(
            FeasibilityAssessment::class
        );
    }
}