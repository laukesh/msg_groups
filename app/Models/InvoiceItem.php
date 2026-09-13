<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model
{
    use SoftDeletes;

    protected $table = 'invoice_items';

    protected $fillable = [
        'invoice_id',
        'charge_type_id',
        'item_description',
        'quantity',
        'unit',
        'rate',
        'taxable_amount',
        'tax_percentage',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'rate' => 'decimal:2',
        'taxable_amount' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(
            Invoice::class,
            'invoice_id'
        );
    }

    public function chargeType()
    {
        return $this->belongsTo(
            ChargeType::class,
            'charge_type_id'
        );
    }

    public function allocations()
    {
        return $this->hasMany(
            InvoiceItemAllocation::class,
            'invoice_item_id'
        );
    }
}