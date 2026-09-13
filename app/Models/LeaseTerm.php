<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaseTerm extends Model
{
    use SoftDeletes;

    protected $table = 'lease_terms';

    protected $fillable = [
        'lease_agreement_id',
        'lock_in_period_months',
        'notice_period_days',
        'escalation_frequency',
        'escalation_percentage',
        'billing_cycle',
        'payment_due_days',
        'grace_period_days',
        'late_fee_type',
        'late_fee_value',
        'cam_calculation_method',
        'utility_billing_method',
        'maintenance_responsibility',
        'insurance_required',
        'subletting_allowed',
        'termination_clause',
        'special_terms',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'escalation_percentage' => 'decimal:2',
        'late_fee_value' => 'decimal:2',
        'lock_in_period_months' => 'integer',
        'notice_period_days' => 'integer',
        'payment_due_days' => 'integer',
        'grace_period_days' => 'integer',
    ];

    public function agreement()
    {
        return $this->belongsTo(
            LeaseAgreement::class,
            'lease_agreement_id'
        );
    }

    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function updatedBy()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
}