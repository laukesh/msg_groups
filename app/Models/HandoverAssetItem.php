<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HandoverAssetItem extends Model
{
    protected $table = 'handover_asset_items';

    protected $fillable = [
        'asset_handover_id',
        'project_id',
        'handover_project_id',
        'asset_id',
        'asset_code',
        'asset_name',
        'asset_category',
        'asset_type',
        'location',
        'building',
        'floor',
        'zone',
        'unit',
        'quantity',
        'condition_status',
        'commissioning_status',
        'warranty_available',
        'warranty_expiry_date',
        'documents_available',
        'status',
        'verified_by',
        'verified_at',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'warranty_available' => 'boolean',
        'documents_available' => 'boolean',
        'warranty_expiry_date' => 'date',
        'verified_at' => 'datetime',
    ];

    public function assetHandover(): BelongsTo
    {
        return $this->belongsTo(
            HandoverAssetHandover::class,
            'asset_handover_id'
        );
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }

    public function handover(): BelongsTo
    {
        return $this->belongsTo(
            HandoverProject::class,
            'handover_project_id'
        );
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }

    /*
     * Keep this relation nullable because asset_id may be NULL
     * until a new operational asset is created.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(
            Asset::class,
            'asset_id'
        );
    }
}