<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaseApproval extends Model
{
    use SoftDeletes;

    protected $table = 'lease_approvals';

    protected $fillable = [
        'approval_number',
        'tenant_id',
        'unit_id',
        'loi_id',
        'lease_summary',
        'proposed_rent',
        'agreed_rent',
        'service_charge',
        'security_deposit',
        'lease_term_months',
        'proposed_start_date',
        'proposed_end_date',
        'deviations',
        'exceptions',
        'credit_check_status',
        'credit_risk_rating',
        'credit_check_notes',
        'authority_matrix',
        'conditions',
        'sla_days',
        'submitted_at',
        'due_at',
        'decided_at',
        'status',
        'final_decision',
        'final_notes',
        'attachment_path',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'proposed_start_date' => 'date',
        'proposed_end_date' => 'date',
        'submitted_at' => 'datetime',
        'due_at' => 'datetime',
        'decided_at' => 'datetime',
        'authority_matrix' => 'array',
        'proposed_rent' => 'decimal:2',
        'agreed_rent' => 'decimal:2',
        'service_charge' => 'decimal:2',
        'security_deposit' => 'decimal:2',
    ];

    public function steps()
    {
        return $this->hasMany(LeaseApprovalStep::class, 'lease_approval_id')
            ->orderBy('id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function loi()
    {
        return $this->belongsTo(TenantLoi::class, 'loi_id');
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_at !== null
            && $this->due_at->isPast()
            && in_array($this->status, ['PENDING_APPROVAL', 'IN_REVIEW'], true);
    }
}
