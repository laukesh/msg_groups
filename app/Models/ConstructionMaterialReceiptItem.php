<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionMaterialReceiptItem extends Model
{
    use HasFactory;

    protected $table = 'construction_material_receipt_items';

    protected $fillable = [
        'material_receipt_id',
        'material_id',
        'delivered_quantity',
        'accepted_quantity',
        'rejected_quantity',
        'unit',
        'batch_number',
        'inspection_required',
        'remarks',
    ];

    protected $casts = [
        'delivered_quantity' => 'decimal:4',
        'accepted_quantity' => 'decimal:4',
        'rejected_quantity' => 'decimal:4',
        'inspection_required' => 'boolean',
    ];

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionMaterialReceipt::class,
            'material_receipt_id'
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