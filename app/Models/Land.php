<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Land extends Model
{
    use SoftDeletes;

    protected $fillable = [
                'land_code',
                'land_name',
                'opportunity_id',
                'acquisition_status',
                'land_type',
                'description',

                'address_line1',
                'address_line2',
                'locality',
                'city',
                'state',
                'country',
                'postal_code',
                'latitude',
                'longitude',

                'total_area',
                'area_unit',
                'acquisition_date',

                'remarks',

                'created_by',
                'updated_by',
            ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'total_area' => 'decimal:4',
        'acquisition_date' => 'date',
    ];

    public function opportunity()
    {
        return $this->belongsTo(
            LandOpportunity::class,
            'opportunity_id'
        );
    }

    public function owners()
    {
        return $this->hasMany(
            LandOwner::class,
            'land_id'
        );
    }

    public function plots()
    {
        return $this->hasMany(
            LandPlot::class,
            'land_id'
        );
    }

    public function zonings()
    {
        return $this->hasMany(
            LandZoning::class,
            'land_id'
        );
    }

    public function developmentRights()
    {
        return $this->hasMany(
            LandDevelopmentRight::class,
            'land_id'
        );
    }

    public function dueDiligences()
    {
        return $this->hasMany(
            LandDueDiligence::class,
            'land_id'
        );
    }

    public function acquisitionCosts()
    {
        return $this->hasMany(
            LandAcquisitionCost::class,
            'land_id'
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

    public function legalDueDiligences()
    {
        return $this->hasMany(
            LandDueDiligence::class,
            'land_id'
        );
    }

    public function acquisitionStatusHistories()
    {
        return $this->hasMany(
            LandAcquisitionStatusHistory::class,
            'land_id'
        )->latest('changed_at');
    }

    public function documents()
    {
        return $this->morphMany(
            Document::class,
            'documentable'
        );
    }

    public function feasibilityAssessments()
    {
        return $this->hasMany(
            FeasibilityAssessment::class,
            'land_id'
        );
    }

    public function projects()
    {
        return $this->hasMany(
            Project::class,
            'land_id'
        );
    }
}