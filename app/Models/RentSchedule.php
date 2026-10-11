<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RentSchedule extends Model
{
    use SoftDeletes;

    protected $table = 'rent_schedules';

    protected $fillable = [
        'uuid',
        'lease_agreement_id',
        'invoice_id',
        'schedule_no',
        'billing_period',
        'period_start',
        'period_end',
        'due_date',
        'base_rent',
        'escalation_amount',
        'cam_amount',
        'utility_estimate',
        'discount_amount',
        'taxable_amount',
        'tax_amount',
        'total_amount',
        'invoice_generated',
        'payment_status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'due_date' => 'date',
        'base_rent' => 'decimal:2',
        'escalation_amount' => 'decimal:2',
        'cam_amount' => 'decimal:2',
        'utility_estimate' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'taxable_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function leaseAgreement()
    {
        return $this->belongsTo(
            LeaseAgreement::class,
            'lease_agreement_id'
        );
    }

    public function invoice()
    {
        return $this->belongsTo(
            Invoice::class,
            'invoice_id'
        );
    }
}