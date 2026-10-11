<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcurementDeliveryItem extends Model
{
    protected $table = 'procurement_delivery_items';

    protected $fillable = [

        'procurement_delivery_id',

        'procurement_purchase_order_item_id',

        'ordered_quantity',
        'previously_delivered_quantity',
        'delivered_quantity',

        'accepted_quantity',
        'rejected_quantity',

        'unit',

        'remarks',

        'created_by',
        'updated_by',
    ];

    protected $casts = [

        'ordered_quantity' =>
            'decimal:3',

        'previously_delivered_quantity' =>
            'decimal:3',

        'delivered_quantity' =>
            'decimal:3',

        'accepted_quantity' =>
            'decimal:3',

        'rejected_quantity' =>
            'decimal:3',
    ];


    /*
    |--------------------------------------------------------------------------
    | Delivery
    |--------------------------------------------------------------------------
    */

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementDelivery::class,
            'procurement_delivery_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Purchase Order Item
    |--------------------------------------------------------------------------
    */

    public function purchaseOrderItem(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementPurchaseOrderItem::class,
            'procurement_purchase_order_item_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Pending Quantity
    |--------------------------------------------------------------------------
    */

    public function getPendingQuantityAttribute(): float
    {
        return max(
            0,
            (float) $this->ordered_quantity
            -
            (float) $this->previously_delivered_quantity
            -
            (float) $this->delivered_quantity
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Net Accepted Quantity
    |--------------------------------------------------------------------------
    */

    public function getNetAcceptedQuantityAttribute(): float
    {
        return max(
            0,
            (float) $this->accepted_quantity
        );
    }

    public function materialTrackingItems()
	{
	    return $this->hasMany(
	        ProcurementMaterialTrackingItem::class,
	        'procurement_delivery_item_id'
	    );
	}
}