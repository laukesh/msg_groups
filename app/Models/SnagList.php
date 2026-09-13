<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SnagList extends Model
{
    use SoftDeletes;

    protected $table = 'snag_lists';

    protected $fillable = [
        'uuid',
        'fitout_request_id',
        'inspection_id',
        'fitout_stage_id',
        'contractor_id',
        'snag_number',
        'title',
        'description',
        'priority',
        'category',
        'location',
        'reported_date',
        'due_date',
        'assigned_to',
        'status',
        'corrective_action',
        'resolved_date',
        'resolved_by',
        'verification_comments',
        'verified_by',
        'verified_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'reported_date' => 'date',
        'due_date' => 'date',
        'resolved_date' => 'date',
        'verified_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | Fit-Out Request
    |--------------------------------------------------------------------------
    */

    public function fitoutRequest()
    {
        return $this->belongsTo(
            FitoutRequest::class,
            'fitout_request_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Inspection
    |--------------------------------------------------------------------------
    */

    public function inspection()
    {
        return $this->belongsTo(
            Inspection::class,
            'inspection_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Fit-Out Stage
    |--------------------------------------------------------------------------
    */

    public function fitoutStage()
    {
        return $this->belongsTo(
            FitoutStage::class,
            'fitout_stage_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Contractor
    |--------------------------------------------------------------------------
    */

    public function contractor()
    {
        return $this->belongsTo(
            FitoutContractor::class,
            'contractor_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Assigned User
    |--------------------------------------------------------------------------
    */

    public function assignedTo()
    {
        return $this->belongsTo(
            User::class,
            'assigned_to'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Resolved By
    |--------------------------------------------------------------------------
    */

    public function resolvedBy()
    {
        return $this->belongsTo(
            User::class,
            'resolved_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Verified By
    |--------------------------------------------------------------------------
    */

    public function verifiedBy()
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
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