<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteVisit extends Model
{
    use SoftDeletes;

    protected $table = 'site_visits';

    protected $fillable = [
        'prospect_id',
        'unit_id',
        'visit_date',
        'attendees',
        'feedback',
        'interest',
        'follow_up_date',
        'assigned_to',
        'status',
        'outcome',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
        'follow_up_date' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Prospect
    |--------------------------------------------------------------------------
    */

    public function prospect()
    {
        return $this->belongsTo(
            LeasingProspect::class,
            'prospect_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Unit
    |--------------------------------------------------------------------------
    */

    public function unit()
    {
        return $this->belongsTo(
            Unit::class,
            'unit_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Assigned Leasing Officer
    |--------------------------------------------------------------------------
    */

    public function assignedOfficer()
    {
        return $this->belongsTo(
            User::class,
            'assigned_to'
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