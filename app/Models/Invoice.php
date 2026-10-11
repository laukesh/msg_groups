<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $table = 'invoices';

    protected $fillable = [
        'uuid',
        'invoice_no',
        'lease_agreement_id',
        'tenant_id',
        'invoice_type',
        'invoice_date',
        'billing_period_from',
        'billing_period_to',
        'due_date',
        'subtotal',
        'discount_amount',
        'taxable_amount',
        'tax_amount',
        'total_amount',
        'paid_amount',
        'balance_amount',
        'invoice_status',
        'remarks',
        'generated_by',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'billing_period_from' => 'date',
        'billing_period_to' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'taxable_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
    ];

    public function leaseAgreement()
    {
        return $this->belongsTo(
            LeaseAgreement::class,
            'lease_agreement_id'
        );
    }

    public function tenant()
    {
        return $this->belongsTo(
            Tenant::class,
            'tenant_id'
        );
    }

    public function items()
    {
        return $this->hasMany(
            InvoiceItem::class,
            'invoice_id'
        );
    }

    public function payments()
    {
        return $this->hasMany(
            RentPayment::class,
            'invoice_id'
        );
    }

    public function rentSchedule()
    {
        return $this->hasOne(
            RentSchedule::class,
            'invoice_id'
        );
    }
}