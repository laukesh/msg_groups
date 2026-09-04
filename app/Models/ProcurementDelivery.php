<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcurementDelivery extends Model
{
    protected $table = 'procurement_deliveries';

    protected $fillable = [
        'procurement_purchase_order_id',
        'procurement_tender_id',
        'procurement_contract_id',

        'delivery_number',
        'delivery_date',

        'supplier_name',

        'challan_number',
        'challan_date',

        'vehicle_number',
        'transporter_name',

        'delivery_address',

        'received_by',
        'received_at',

        'status',

        'remarks',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'challan_date' => 'date',

        'received_at' => 'datetime',
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
    | Tender
    |--------------------------------------------------------------------------
    */

    public function tender(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementTender::class,
            'procurement_tender_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Contract
    |--------------------------------------------------------------------------
    */

    public function contract(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementContract::class,
            'procurement_contract_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Delivery Items
    |--------------------------------------------------------------------------
    */

    public function items(): HasMany
    {
        return $this->hasMany(
            ProcurementDeliveryItem::class,
            'procurement_delivery_id'
        );
    }
}