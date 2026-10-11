<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetPerformance extends Model
{
    protected $table = 'asset_performances';

    protected $fillable = [
        'asset_id',
        'performance_date',
        'occupancy_percentage',
        'rental_income',
        'operating_expense',
        'noi',
        'roi_percentage',
        'utilization_percentage',
        'downtime_days',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'performance_date' => 'date',
        'occupancy_percentage' => 'decimal:2',
        'rental_income' => 'decimal:2',
        'operating_expense' => 'decimal:2',
        'noi' => 'decimal:2',
        'roi_percentage' => 'decimal:2',
        'utilization_percentage' => 'decimal:2',
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