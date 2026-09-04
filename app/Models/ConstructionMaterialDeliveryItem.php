<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionMaterialDeliveryItem extends Model
{
    use HasFactory;

    protected $table = 'construction_material_delivery_items';

    protected $fillable = [
        'material_delivery_id',
        'material_id',
        'ordered_quantity',
        'delivered_quantity',
        'unit',
        'batch_number',
        'remarks',
    ];

    protected $casts = [
        'ordered_quantity' => 'decimal:4',
        'delivered_quantity' => 'decimal:4',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionMaterialDelivery::class,
            'material_delivery_id'
        );
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionMaterial::class,
            'material_id'
        );
    }
}