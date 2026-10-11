<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcurementPurchaseOrderItem extends Model
{
    protected $table = 'procurement_purchase_order_items';


    protected $fillable = [

        'procurement_purchase_order_id',

        'item_code',
        'item_name',
        'description',

        'quantity',
        'unit',

        'unit_price',

        'tax_percentage',
        'tax_amount',

        'discount_amount',

        'line_total',

        'required_delivery_date',

        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'quantity' => 'decimal:3',

        'unit_price' => 'decimal:2',

        'tax_percentage' => 'decimal:2',

        'tax_amount' => 'decimal:2',

        'discount_amount' => 'decimal:2',

        'line_total' => 'decimal:2',

        'required_delivery_date' => 'date',
    ];


    /*
    |--------------------------------------------------------------------------
    | Purchase Order
    |--------------------------------------------------------------------------
    */

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementPurchaseOrder::class,
            'procurement_purchase_order_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Calculate Line Total
    |--------------------------------------------------------------------------
    */

    public function calculateLineTotal(): float
    {
        $quantity =
            (float) $this->quantity;

        $unitPrice =
            (float) $this->unit_price;

        $tax =
            (float) $this->tax_amount;

        $discount =
            (float) $this->discount_amount;


        return (
            $quantity * $unitPrice
        )
        + $tax
        - $discount;
    }


    /*
    |--------------------------------------------------------------------------
    | Calculate Tax
    |--------------------------------------------------------------------------
    */

    public function calculateTaxAmount(): float
    {
        $baseAmount =
            (float) $this->quantity
            * (float) $this->unit_price;


        return $baseAmount
            * ((float) $this->tax_percentage / 100);
    }


    /*
    |--------------------------------------------------------------------------
    | Prepare Line Total
    |--------------------------------------------------------------------------
    */

    public function recalculate(): void
    {
        $this->tax_amount =
            $this->calculateTaxAmount();


        $this->line_total =
            $this->calculateLineTotal();
    }

    /*
    |--------------------------------------------------------------------------
    | Deliveries
    |--------------------------------------------------------------------------
    */

    public function deliveryItems(): HasMany
    {
        return $this->hasMany(
            ProcurementDeliveryItem::class,
            'procurement_purchase_order_item_id'
        );
    }

    public function materialTrackingItems()
    {
        return $this->hasMany(
            ProcurementMaterialTrackingItem::class,
            'procurement_purchase_order_item_id'
        );
    }
}