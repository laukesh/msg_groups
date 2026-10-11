<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeasingProspect extends Model
{
    use SoftDeletes;

    protected $table = 'leasing_prospects';

    protected $fillable = [
        'company_name',
        'contact_name',
        'email',
        'phone',
        'brand_name',
        'category',
        'area_need',
        'budget',
        'preferred_location',
        'source',
        'status',
        'notes',
        'assigned_to',
        'next_follow_up_at',
        'created_by',
        'updated_by',
        'priority',
    ];

    protected $casts = [
        'area_need' => 'decimal:2',
        'budget' => 'decimal:2',
        'next_follow_up_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | Owner
    |--------------------------------------------------------------------------
    */

    public function assignedUser()
    {
        return $this->belongsTo(
            User::class,
            'assigned_to'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Owner Alias
    |
    | Allows Blade:
    | $prospect->owner
    |--------------------------------------------------------------------------
    */

    public function getOwnerAttribute()
    {
        return $this->assignedUser;
    }


    /*
    |--------------------------------------------------------------------------
    | Area Alias
    |
    | Allows Blade:
    | $prospect->area_required
    |--------------------------------------------------------------------------
    */

    public function getAreaRequiredAttribute()
    {
        return $this->area_need;
    }


    /*
    |--------------------------------------------------------------------------
    | Area Unit
    |--------------------------------------------------------------------------
    */

    public function getAreaUnitAttribute()
    {
        return 'sq.ft';
    }
    public function pipelines()
    {
        return $this->hasMany(
            LeasingPipeline::class,
            'prospect_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Budget Range
    |
    | Converts numeric budget to readable display.
    |--------------------------------------------------------------------------
    */

    public function getBudgetRangeAttribute()
    {
        if ($this->budget === null || $this->budget === '') {
            return '-';
        }

        return number_format(
            (float) $this->budget,
            2
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Status Label
    |--------------------------------------------------------------------------
    */

    public function getStatusLabelAttribute()
    {
        if (!$this->status) {
            return '-';
        }

        return ucfirst(
            str_replace(
                '_',
                ' ',
                strtolower($this->status)
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Follow-up Alias
    |
    | Allows Blade:
    | $prospect->next_follow_up
    |--------------------------------------------------------------------------
    */

    public function getNextFollowUpAttribute()
    {
        return $this->next_follow_up_at;
    }


    /*
    |--------------------------------------------------------------------------
    | Site Visits
    |--------------------------------------------------------------------------
    */

    public function siteVisits()
    {
        return $this->hasMany(
            SiteVisit::class,
            'prospect_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Activities
    |
    | Keep this only if your activities table/model exists.
    |--------------------------------------------------------------------------
    */

    public function activities()
    {
        return $this->hasMany(
            LeasingActivity::class,
            'prospect_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */

    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Updated By
    |--------------------------------------------------------------------------
    */

    public function updatedBy()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
}