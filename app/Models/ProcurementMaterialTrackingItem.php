<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementMaterialTrackingItem extends Model
{
    use HasFactory;

    protected $table = 'procurement_material_tracking_items';

    protected $fillable = [
        'procurement_material_tracking_id',
        'procurement_purchase_order_item_id',
        'procurement_delivery_item_id',
        'material_name',
        'item_code',
        'unit',
        'ordered_quantity',
        'received_quantity',
        'accepted_quantity',
        'rejected_quantity',
        'issued_quantity',
        'consumed_quantity',
        'balance_quantity',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'ordered_quantity'              => 'decimal:3',
        'received_quantity'             => 'decimal:3',
        'accepted_quantity'             => 'decimal:3',
        'rejected_quantity'             => 'decimal:3',
        'issued_quantity'               => 'decimal:3',
        'consumed_quantity'             => 'decimal:3',
        'balance_quantity'              => 'decimal:3',
    ];


    /*
    |--------------------------------------------------------------------------
    | Material Tracking
    |--------------------------------------------------------------------------
    */

    public function tracking()
    {
        return $this->belongsTo(
            ProcurementMaterialTracking::class,
            'procurement_material_tracking_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Purchase Order Item
    |--------------------------------------------------------------------------
    */

    public function purchaseOrderItem()
    {
        return $this->belongsTo(
            ProcurementPurchaseOrderItem::class,
            'procurement_purchase_order_item_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Delivery Item
    |--------------------------------------------------------------------------
    */

    public function deliveryItem()
    {
        return $this->belongsTo(
            ProcurementDeliveryItem::class,
            'procurement_delivery_item_id'
        );
    }
}