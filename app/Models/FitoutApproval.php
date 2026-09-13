<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FitoutApproval extends Model
{
    use SoftDeletes;

    protected $table = 'fitout_approvals';

    protected $fillable = [
        'uuid',
        'fitout_request_id',
        'fitout_document_id',
        'approval_level',
        'department_id',
        'approver_id',
        'approval_type',
        'approval_status',
        'status',
        'submitted_at',
        'action_at',
        'comments',
        'rejection_reason',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'action_at' => 'datetime',
    ];

    public function fitoutRequest()
    {
        return $this->belongsTo(
            FitoutRequest::class,
            'fitout_request_id'
        );
    }

    public function document()
    {
        return $this->belongsTo(
            FitoutDocument::class,
            'fitout_document_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Approver
    |--------------------------------------------------------------------------
    */

    public function approver()
    {
        return $this->belongsTo(
            User::class,
            'approver_id'
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