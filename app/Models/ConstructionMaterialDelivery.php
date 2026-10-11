<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConstructionMaterialDelivery extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'construction_material_deliveries';

    protected $fillable = [
        'project_id',
        'material_request_id',
        'delivery_number',
        'delivery_date',
        'vehicle_number',
        'challan_number',
        'challan_date',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'challan_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }

    public function materialRequest(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionMaterialRequest::class,
            'material_request_id'
        );
    }

    public function items(): HasMany
    {
        return $this->hasMany(
            ConstructionMaterialDeliveryItem::class,
            'material_delivery_id'
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

    public function receipts(): HasMany
    {
        return $this->hasMany(
            ConstructionMaterialReceipt::class,
            'material_delivery_id'
        );
    }
}