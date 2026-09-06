<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionMaterial extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'construction_materials';

    protected $fillable = [
        'material_code',
        'material_name',
        'category',
        'specification',
        'unit',
        'description',
        'status',
        'created_by',
        'updated_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function requirements(): HasMany
    {
        return $this->hasMany(
            ConstructionMaterialRequirement::class,
            'material_id'
        );
    }

    public function requestItems(): HasMany
    {
        return $this->hasMany(
            ConstructionMaterialRequestItem::class,
            'material_id'
        );
    }

    public function deliveryItems(): HasMany
    {
        return $this->hasMany(
            ConstructionMaterialDeliveryItem::class,
            'material_id'
        );
    }

    public function receiptItems(): HasMany
    {
        return $this->hasMany(
            ConstructionMaterialReceiptItem::class,
            'material_id'
        );
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(
            ConstructionMaterialStock::class,
            'material_id'
        );
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(
            ConstructionMaterialTransaction::class,
            'material_id'
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    

}