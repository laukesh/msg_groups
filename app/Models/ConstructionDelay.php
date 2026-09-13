<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConstructionDelay extends Model
{
    use SoftDeletes;

    protected $table = 'construction_delays';

    protected $fillable = [
        'project_id',
        'construction_work_order_id',
        'construction_schedule_activity_id',
        'construction_claim_id',

        'delay_number',
        'delay_type',
        'delay_title',
        'delay_date',
        'start_date',
        'end_date',

        'reported_days',
        'assessed_days',
        'approved_days',
        'excusable_days',
        'compensable_days',

        'claimant_type',
        'claimant_name',

        'responsible_party_type',
        'responsible_party_name',

        'description',
        'cause',
        'impact_description',
        'schedule_impact',

        'cost_impact',
        'assessed_cost_impact',
        'approved_cost_impact',

        'eot_requested_days',
        'eot_assessed_days',
        'eot_approved_days',

        'assessment_remarks',
        'approval_remarks',
        'rejection_remarks',

        'status',
        'priority',

        'is_excusable',
        'is_compensable',

        'reported_by',
        'assessed_by',
        'assessment_date',

        'approved_by',
        'approval_date',

        'rejected_by',
        'rejection_date',

        'closed_by',
        'closed_date',

        'remarks',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'delay_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',

        'assessment_date' => 'datetime',
        'approval_date' => 'datetime',
        'rejection_date' => 'datetime',

        'closed_date' => 'date',

        'cost_impact' => 'decimal:2',
        'assessed_cost_impact' => 'decimal:2',
        'approved_cost_impact' => 'decimal:2',

        'is_excusable' => 'boolean',
        'is_compensable' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }

    public function workOrder()
    {
        return $this->belongsTo(
            ConstructionWorkOrder::class,
            'construction_work_order_id'
        );
    }

    public function scheduleActivity()
    {
        return $this->belongsTo(
            ConstructionScheduleActivity::class,
            'construction_schedule_activity_id'
        );
    }

    public function claim()
    {
        return $this->belongsTo(
            ConstructionClaim::class,
            'construction_claim_id'
        );
    }

    public function documents()
    {
        return $this->hasMany(
            ConstructionDelayDocument::class,
            'construction_delay_id'
        );
    }

    public function history()
    {
        return $this->hasMany(
            ConstructionDelayHistory::class,
            'construction_delay_id'
        )->orderByDesc('performed_at');
    }

    public function reportedBy()
    {
        return $this->belongsTo(
            User::class,
            'reported_by'
        );
    }

    public function assessedBy()
    {
        return $this->belongsTo(
            User::class,
            'assessed_by'
        );
    }

    public function approvedBy()
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }

    public function rejectedBy()
    {
        return $this->belongsTo(
            User::class,
            'rejected_by'
        );
    }

    public function closedBy()
    {
        return $this->belongsTo(
            User::class,
            'closed_by'
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
}