<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionMaterialRequestItem extends Model
{
    use HasFactory;

    protected $table = 'construction_material_request_items';

    protected $fillable = [
        'material_request_id',
        'material_requirement_id',
        'material_id',
        'requested_quantity',
        'unit',
        'remarks',
    ];

    protected $casts = [
        'requested_quantity' => 'decimal:4',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function request(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionMaterialRequest::class,
            'material_request_id'
        );
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionMaterial::class,
            'material_id'
        );
    }

    public function materialRequest(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionMaterialRequest::class,
            'material_request_id'
        );
    }

    public function deliveries(): HasMany
	{
	    return $this->hasMany(
	        ConstructionMaterialDelivery::class,
	        'material_request_id'
	    );
	}

    public function materialRequirement(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionMaterialRequirement::class,
            'material_requirement_id'
        );
    }
}