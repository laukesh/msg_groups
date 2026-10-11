<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenantLoi extends Model
{
    use SoftDeletes;

    protected $table = 'tenant_lois';

    protected $fillable = [
        'loi_number',
        'tenant_id',
        'unit_id',
        'title',
        'key_terms',
        'conditions',
        'proposed_rent',
        'service_charge',
        'security_deposit',
        'proposed_area',
        'lease_term_months',
        'issue_date',
        'expiry_date',
        'acceptance_date',
        'status',
        'approval_status',
        'approved_by',
        'approved_at',
        'approval_notes',
        'acceptance_status',
        'acceptance_notes',
        'attachment_path',
        'rejection_reason',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'acceptance_date' => 'date',
        'approved_at' => 'datetime',
        'proposed_rent' => 'decimal:2',
        'service_charge' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'proposed_area' => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
