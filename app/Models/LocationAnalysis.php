<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationAnalysis extends Model
{
    use HasFactory;


    protected $fillable = [

        'feasibility_assessment_id',

        'analysis_number',

        'title',

        'location_type',

        'accessibility',

        'road_connectivity',

        'public_transport',

        'visibility',

        'surrounding_development',

        'nearby_landmarks',

        'competition',

        'demographics',

        'catchment_area',

        'location_advantages',

        'location_constraints',

        'overall_location_score',

        'recommendation',

        'status',

        'created_by',

        'updated_by',

    ];


    protected $casts = [

        'overall_location_score' => 'decimal:2',

    ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

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