<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionSubmittal extends Model
{
    protected $table = 'construction_submittals';

    protected $fillable = [
        'project_id',

        'procurement_contract_id',
        'work_order_id',
        'consultant_id',
        'schedule_activity_id',

        'submittal_number',
        'submittal_date',
        'submittal_type',

        'title',
        'description',

        'submitted_by',
        'submitted_to',

        'document_reference',
        'revision_number',

        'submission_date',
        'review_due_date',
        'review_date',

        'status',

        'review_comments',
        'response',

        'approval_date',
        'approved_by',

        'location',
        'priority',

        'remarks',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'submittal_date'   => 'date',
        'submission_date'  => 'date',
        'review_due_date'  => 'date',
        'review_date'      => 'date',
        'approval_date'    => 'date',
    ];


    /*
    |--------------------------------------------------------------------------
    | Project
    |--------------------------------------------------------------------------
    */

    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Procurement Contract
    |--------------------------------------------------------------------------
    */

    public function contract(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementContract::class,
            'procurement_contract_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Work Order
    |--------------------------------------------------------------------------
    */

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionWorkOrder::class,
            'work_order_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Consultant
    |--------------------------------------------------------------------------
    */

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(
            ProjectConsultant::class,
            'consultant_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Schedule Activity
    |--------------------------------------------------------------------------
    */

    public function scheduleActivity(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionScheduleActivity::class,
            'schedule_activity_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Submitted By
    |--------------------------------------------------------------------------
    */

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'submitted_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Submitted To
    |--------------------------------------------------------------------------
    */

    public function submittedTo(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'submitted_to'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Approved By
    |--------------------------------------------------------------------------
    */

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
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

    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
}