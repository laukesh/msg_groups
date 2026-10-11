<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetLifecycle extends Model
{
    protected $table = 'asset_lifecycles';

    protected $fillable = [
        'asset_id',
        'lifecycle_stage',
        'lifecycle_status',
        'priority',

        'acquisition_date',
        'commissioning_date',

        'expected_life_years',
        'remaining_life_years',

        'planned_renewal_date',
        'planned_replacement_date',
        'actual_replacement_date',

        'acquisition_cost',
        'current_book_value',
        'estimated_replacement_cost',
        'residual_value',

        'depreciation_percentage',
        'lifecycle_score',

        'recommended_action',

        'last_reviewed_at',
        'next_review_date',

        'responsible_user_id',

        'remarks',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'acquisition_date' => 'date',
        'commissioning_date' => 'date',
        'planned_renewal_date' => 'date',
        'planned_replacement_date' => 'date',
        'actual_replacement_date' => 'date',
        'last_reviewed_at' => 'date',
        'next_review_date' => 'date',

        'expected_life_years' => 'decimal:2',
        'remaining_life_years' => 'decimal:2',

        'acquisition_cost' => 'decimal:2',
        'current_book_value' => 'decimal:2',
        'estimated_replacement_cost' => 'decimal:2',
        'residual_value' => 'decimal:2',

        'depreciation_percentage' => 'decimal:2',
        'lifecycle_score' => 'decimal:2',
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
    | Responsible User
    |--------------------------------------------------------------------------
    */

    public function responsibleUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Updated By
    |--------------------------------------------------------------------------
    */

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}