<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandSupplyAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [

        'feasibility_assessment_id',

        'analysis_number',
        'title',

        'market_type',

        'demand_assessment',
        'current_demand',
        'projected_demand',
        'demand_growth_rate',

        'current_supply',
        'future_supply',
        'supply_pipeline',

        'demand_supply_gap',

        'occupancy_rate',
        'utilization_rate',

        'target_customer_demand',
        'competitor_supply',

        'market_capacity',

        'forecast_period',
        'forecast_demand',

        'key_drivers',
        'key_constraints',

        'key_findings',
        'recommendation',

        'overall_demand_supply_score',

        'status',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'current_demand' =>
            'decimal:2',

        'projected_demand' =>
            'decimal:2',

        'demand_growth_rate' =>
            'decimal:2',

        'current_supply' =>
            'decimal:2',

        'future_supply' =>
            'decimal:2',

        'demand_supply_gap' =>
            'decimal:2',

        'occupancy_rate' =>
            'decimal:2',

        'utilization_rate' =>
            'decimal:2',

        'market_capacity' =>
            'decimal:2',

        'forecast_demand' =>
            'decimal:2',

        'overall_demand_supply_score' =>
            'decimal:2',
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