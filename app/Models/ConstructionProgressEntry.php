<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionProgressEntry extends Model
{
    protected $table =
        'construction_progress_entries';


    protected $fillable = [

        'project_id',

        'construction_schedule_activity_id',

        'construction_work_order_id',

        'progress_number',

        'progress_date',

        'planned_progress_percentage',

        'actual_progress_percentage',

        'quantity_planned',

        'quantity_executed',

        'unit',

        'manpower_count',

        'status',

        'remarks',

        'issues_constraints',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'progress_date' =>
            'date',

        'planned_progress_percentage' =>
            'decimal:2',

        'actual_progress_percentage' =>
            'decimal:2',

        'quantity_planned' =>
            'decimal:4',

        'quantity_executed' =>
            'decimal:4',

        'manpower_count' =>
            'integer',
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
    | Schedule Activity
    |--------------------------------------------------------------------------
    */

    public function activity(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionScheduleActivity::class,
            'construction_schedule_activity_id'
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


    public function isRejected(): bool
    {
        return $this->status === 'Rejected';
    }
}