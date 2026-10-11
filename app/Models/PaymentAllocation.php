<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentAllocation extends Model
{
    use SoftDeletes;

    protected $table = 'payment_allocations';

    protected $fillable = [
        'uuid',
        'payment_id',
        'invoice_id',
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

    public function payment()
    {
        return $this->belongsTo(
            RentPayment::class,
            'payment_id'
        );
    }

    public function invoice()
    {
        return $this->belongsTo(
            Invoice::class,
            'invoice_id'
        );
    }

    public function itemAllocations()
	{
	    return $this->hasMany(
	        InvoiceItemAllocation::class,
	        'payment_allocation_id'
	    );
	}
}