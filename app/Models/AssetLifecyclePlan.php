<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetLifecyclePlan extends Model
{
    protected $table = 'asset_lifecycle_plans';

    protected $fillable = [
        'asset_id',
        'lifecycle_stage',
        'planned_action',
        'planned_year',
        'expected_life_years',
        'current_age_years',
        'remaining_life_years',
        'replacement_cost',
        'estimated_value',
        'priority',
        'status',
        'planned_start_date',
        'planned_completion_date',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'planned_start_date' => 'date',
        'planned_completion_date' => 'date',
        'replacement_cost' => 'decimal:2',
        'estimated_value' => 'decimal:2',
        'current_age_years' => 'decimal:2',
        'remaining_life_years' => 'decimal:2',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
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