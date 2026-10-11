<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeasingActivity extends Model
{
    use SoftDeletes;

    protected $table = 'leasing_activities';

    protected $fillable = [
        'prospect_id',
        'activity_type',
        'subject',
        'description',
        'activity_date',
        'outcome',
        'next_action',
        'next_action_date',
        'assigned_to',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'activity_date' => 'datetime',
        'next_action_date' => 'datetime',
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
    | Assigned Leasing Officer
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


    /*
    |--------------------------------------------------------------------------
    | Activity Type Label
    |--------------------------------------------------------------------------
    */

    public function getActivityTypeLabelAttribute()
    {
        return ucfirst(
            str_replace(
                '_',
                ' ',
                strtolower($this->activity_type ?? 'NOTE')
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Formatted Activity Date
    |--------------------------------------------------------------------------
    */

    public function getActivityDateLabelAttribute()
    {
        return $this->activity_date
            ? $this->activity_date->format('d M Y, h:i A')
            : '-';
    }


    /*
    |--------------------------------------------------------------------------
    | Next Action Date Label
    |--------------------------------------------------------------------------
    */

    public function getNextActionDateLabelAttribute()
    {
        return $this->next_action_date
            ? $this->next_action_date->format('d M Y, h:i A')
            : '-';
    }
}