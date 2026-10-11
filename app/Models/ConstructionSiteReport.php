<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionSiteReport extends Model
{
    protected $table = 'construction_site_reports';

    protected $fillable = [

        'project_id',

        'construction_work_order_id',

        'report_number',

        'report_date',

        'report_type',

        'prepared_by',

        'weather_condition',

        'temperature',

        'site_condition',

        'overall_progress',

        'work_summary',

        'activities_completed',

        'activities_planned',

        'manpower_summary',

        'equipment_summary',

        'material_summary',

        'safety_observations',

        'quality_observations',

        'delays',

        'issues',

        'corrective_actions',

        'visitors',

        'instructions',

        'remarks',

        'status',

        'submitted_by',

        'submitted_at',

        'approved_by',

        'approved_at',

        'approval_remarks',

        'created_by',

        'updated_by',
    ];


    protected $casts = [

        'report_date' =>
            'date',

        'overall_progress' =>
            'decimal:2',

        'submitted_at' =>
            'datetime',

        'approved_at' =>
            'datetime',
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
    | Work Order
    |--------------------------------------------------------------------------
    */

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionWorkOrder::class,
            'construction_work_order_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Prepared By
    |--------------------------------------------------------------------------
    */

    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'prepared_by'
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
    | Creator
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
    | Updater
    |--------------------------------------------------------------------------
    */

    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isDraft(): bool
    {
        return $this->status === 'Draft';
    }


    public function isSubmitted(): bool
    {
        return $this->status === 'Submitted';
    }


    public function isApproved(): bool
    {
        return $this->status === 'Approved';
    }


    public function canEdit(): bool
    {
        return in_array(
            $this->status,
            [
                'Draft',
                'Revision Required',
            ],
            true
        );
    }
}