<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItemAllocation extends Model
{
    use SoftDeletes;

    protected $table = 'invoice_item_allocations';

    protected $fillable = [
        'uuid',
        'payment_allocation_id',
        'invoice_item_id',
        'allocation_date',
        'allocated_amount',
        'allocation_status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'allocation_date' => 'date',
        'allocated_amount' => 'decimal:2',
    ];


    public function paymentAllocation()
    {
        return $this->belongsTo(
            PaymentAllocation::class,
            'payment_allocation_id'
        );
    }


    public function invoiceItem()
    {
        return $this->belongsTo(
            InvoiceItem::class,
            'invoice_item_id'
        );
    }
}