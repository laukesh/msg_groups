<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcurementMaterialTracking extends Model
{
    use HasFactory;

    protected $table = 'procurement_material_trackings';

    protected $fillable = [
        'project_id',
        'procurement_purchase_order_id',
        'procurement_tender_id',
        'procurement_contract_id',
        'tracking_number',
        'tracking_date',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tracking_date' => 'date',
    ];


    /*
    |--------------------------------------------------------------------------
    | Project
    |--------------------------------------------------------------------------
    */

    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }


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
    | Tracking Items
    |--------------------------------------------------------------------------
    */

    public function items(): HasMany
    {
        return $this->hasMany(
            ProcurementMaterialTrackingItem::class,
            'procurement_material_tracking_id'
        );
    }
}