<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvironmentalFeasibility extends Model
{
    use HasFactory;

    protected $table = 'environmental_feasibilities';

    protected $fillable = [

        'feasibility_assessment_id',

        'analysis_number',

        'title',

        'status',

        /*
        |--------------------------------------------------------------------------
        | Environmental Assessment
        |--------------------------------------------------------------------------
        */

        'environmental_status',
        'environmental_overview',

        'environmental_impact_assessment_status',
        'environmental_impact_assessment_details',

        /*
        |--------------------------------------------------------------------------
        | Air
        |--------------------------------------------------------------------------
        */

        'air_quality_status',
        'air_quality_details',

        /*
        |--------------------------------------------------------------------------
        | Water
        |--------------------------------------------------------------------------
        */

        'water_environment_status',
        'water_environment_details',

        /*
        |--------------------------------------------------------------------------
        | Soil
        |--------------------------------------------------------------------------
        */

        'soil_environment_status',
        'soil_environment_details',

        /*
        |--------------------------------------------------------------------------
        | Noise
        |--------------------------------------------------------------------------
        */

        'noise_pollution_status',
        'noise_pollution_details',

        /*
        |--------------------------------------------------------------------------
        | Ecology
        |--------------------------------------------------------------------------
        */

        'ecological_status',
        'ecological_details',

        'biodiversity_status',
        'biodiversity_details',

        /*
        |--------------------------------------------------------------------------
        | Trees / Green Cover
        |--------------------------------------------------------------------------
        */

        'tree_cutting_required',
        'tree_cutting_details',

        'green_cover_status',
        'green_cover_details',

        /*
        |--------------------------------------------------------------------------
        | Waste
        |--------------------------------------------------------------------------
        */

        'solid_waste_management_status',
        'solid_waste_management_details',

        'hazardous_waste_status',
        'hazardous_waste_details',

        'construction_waste_status',
        'construction_waste_details',

        /*
        |--------------------------------------------------------------------------
        | Pollution
        |--------------------------------------------------------------------------
        */

        'pollution_control_status',
        'pollution_control_details',

        /*
        |--------------------------------------------------------------------------
        | Climate
        |--------------------------------------------------------------------------
        */

        'climate_impact_status',
        'climate_impact_details',

        'climate_resilience_measures',

        /*
        |--------------------------------------------------------------------------
        | Sustainability
        |--------------------------------------------------------------------------
        */

        'sustainability_status',
        'sustainability_details',

        'green_building_potential',
        'green_building_details',

        'renewable_energy_potential',
        'renewable_energy_details',

        'water_conservation_potential',
        'water_conservation_details',

        /*
        |--------------------------------------------------------------------------
        | Regulatory
        |--------------------------------------------------------------------------
        */

        'environmental_clearance_status',
        'environmental_clearance_details',

        'applicable_environmental_laws',

        'required_environmental_approvals',

        /*
        |--------------------------------------------------------------------------
        | Risks
        |--------------------------------------------------------------------------
        */

        'environmental_risks',

        'mitigation_measures',

        /*
        |--------------------------------------------------------------------------
        | Findings
        |--------------------------------------------------------------------------
        */

        'key_environmental_findings',

        'recommendation',

        'overall_environmental_score',

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