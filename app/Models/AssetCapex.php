<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetCapex extends Model
{
    protected $table = 'asset_capex';

    protected $fillable = [
        'asset_id',
        'capex_code',
        'title',
        'description',
        'category',
        'priority',
        'status',
        'planned_date',
        'start_date',
        'completion_date',
        'estimated_cost',
        'approved_budget',
        'actual_cost',
        'vendor_id',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'planned_date' => 'date',
        'start_date' => 'date',
        'completion_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'approved_budget' => 'decimal:2',
        'actual_cost' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Asset
    |--------------------------------------------------------------------------
    */

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Vendor - users table
    |--------------------------------------------------------------------------
    */

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Updated By
    |--------------------------------------------------------------------------
    */

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}