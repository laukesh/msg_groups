<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetCondition extends Model
{
    protected $table = 'asset_conditions';

    protected $fillable = [
        'asset_id',
        'inspection_date',
        'condition',
        'condition_score',
        'structural_condition',
        'electrical_condition',
        'mechanical_condition',
        'safety_condition',
        'estimated_repair_cost',
        'recommended_action',
        'inspector_id',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'condition_score' => 'decimal:2',
        'estimated_repair_cost' => 'decimal:2',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_id');
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