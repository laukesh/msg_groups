<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketStudy extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'feasibility_assessment_id',

        'study_number',

        'title',

        'study_date',

        'study_period',

        'market_location',

        'market_segment',

        'market_overview',

        'market_trends',

        'target_market',

        'market_size',

        'growth_rate',

        'growth_outlook',

        'key_drivers',

        'key_constraints',

        'key_assumptions',

        'key_findings',

        'recommendation',

        'status',

        'created_by',

        'updated_by',

    ];


    protected $casts = [

        'study_date' => 'date',

        'market_size' => 'decimal:2',

        'growth_rate' => 'decimal:2',

    ];


    public function feasibilityAssessment()
    {
        return $this->belongsTo(
            FeasibilityAssessment::class,
            'feasibility_assessment_id'
        );
    }


    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    public function updatedBy()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
}