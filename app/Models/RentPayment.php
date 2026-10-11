<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RentPayment extends Model
{
    use SoftDeletes;

    protected $table = 'rent_payments';

    protected $fillable = [
        'uuid',
        'payment_no',
        'tenant_id',
        'invoice_id',
        'payment_date',
        'payment_amount',
        'payment_mode',
        'bank_name',
        'cheque_no',
        'transaction_reference',
        'payment_status',
        'reconciliation_status',
        'received_by',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'payment_amount' => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(
            Tenant::class,
            'tenant_id'
        );
    }

    public function invoice()
    {
        return $this->belongsTo(
            Invoice::class,
            'invoice_id'
        );
    }

    public function allocations()
    {
        return $this->hasMany(
            PaymentAllocation::class,
            'payment_id'
        );
    }
}