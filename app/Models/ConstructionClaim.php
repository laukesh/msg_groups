<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConstructionClaim extends Model
{
    use SoftDeletes;

    protected $table = 'construction_claims';

    protected $fillable = [
        'project_id',
        'procurement_contract_id',
        'construction_work_order_id',
        'claim_number',
        'claim_type',
        'claim_date',
        'event_date',
        'claimant_type',
        'claimant_name',
        'subject',
        'description',
        'justification',
        'claimed_amount',
        'claimed_days',
        'assessed_amount',
        'assessed_days',
        'approved_amount',
        'approved_days',
        'status',
        'priority',
        'assessment_remarks',
        'approval_remarks',
        'rejection_remarks',
        'closed_date',
        'closed_by',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'claim_date' => 'date',
        'event_date' => 'date',
        'closed_date' => 'date',

        'claimed_amount' => 'decimal:2',
        'assessed_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',

        'claimed_days' => 'integer',
        'assessed_days' => 'integer',
        'approved_days' => 'integer',
    ];

    public function project()
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }

    public function procurementContract()
    {
        return $this->belongsTo(
            ProcurementContract::class,
            'procurement_contract_id'
        );
    }

    public function workOrder()
    {
        return $this->belongsTo(
            ConstructionWorkOrder::class,
            'construction_work_order_id'
        );
    }

    public function documents()
    {
        return $this->hasMany(
            ConstructionClaimDocument::class,
            'construction_claim_id'
        );
    }

    public function history()
    {
        return $this->hasMany(
            ConstructionClaimHistory::class,
            'construction_claim_id'
        )->orderByDesc('performed_at');
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

    public function closedBy()
    {
        return $this->belongsTo(
            User::class,
            'closed_by'
        );
    }
}