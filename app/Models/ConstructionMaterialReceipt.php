<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConstructionMaterialReceipt extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'construction_material_receipts';

    protected $fillable = [
        'project_id',
        'material_delivery_id',
        'receipt_number',
        'receipt_date',
        'received_by',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'receipt_date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionMaterialDelivery::class,
            'material_delivery_id'
        );
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'received_by'
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    public function items(): HasMany
    {
        return $this->hasMany(
            ConstructionMaterialReceiptItem::class,
            'material_receipt_id'
        );
    }
}