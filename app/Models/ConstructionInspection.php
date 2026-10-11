<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConstructionInspection extends Model
{
    protected $table = 'construction_inspections';

    protected $fillable = [

        'project_id',

        'procurement_contract_id',
        'work_order_id',
        'consultant_id',
        'schedule_activity_id',

        'inspection_number',
        'inspection_date',

        'inspection_type',

        'title',
        'description',

        'location',

        'planned_date',
        'scheduled_date',
        'conducted_date',

        'inspected_by',
        'witnessed_by',

        'priority',

        'status',

        'result',

        'observations',
        'non_conformance',
        'corrective_action',

        'corrective_action_due_date',
        'corrective_action_date',

        'reinspection_required',
        'reinspection_date',

        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'inspection_date' =>
            'date',

        'planned_date' =>
            'date',

        'scheduled_date' =>
            'date',

        'conducted_date' =>
            'date',

        'corrective_action_due_date' =>
            'date',

        'corrective_action_date' =>
            'date',

        'reinspection_date' =>
            'date',

        'reinspection_required' =>
            'boolean',
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
    | Inspector
    |--------------------------------------------------------------------------
    */

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'inspected_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Witnessed By
    |--------------------------------------------------------------------------
    */

    public function witness(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'witnessed_by'
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


    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isPlanned(): bool
    {
        return $this->status === 'Planned';
    }


    public function isScheduled(): bool
    {
        return $this->status === 'Scheduled';
    }


    public function isConducted(): bool
    {
        return $this->status === 'Conducted';
    }


    public function isPassed(): bool
    {
        return $this->result === 'Passed';
    }


    public function isFailed(): bool
    {
        return $this->result === 'Failed';
    }


    public function requiresReinspection(): bool
    {
        return (bool) $this->reinspection_required;
    }


    public function isClosed(): bool
    {
        return $this->status === 'Closed';
    }

    /*
    |--------------------------------------------------------------------------
    | Quality NCRs
    |--------------------------------------------------------------------------
    */

    public function ncrs(): HasMany
    {
        return $this->hasMany(
            ConstructionQualityNcr::class,
            'construction_inspection_id'
        );
    }
}