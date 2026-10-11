<?php

namespace App\Models\Operations;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetPerformance extends Model
{
    protected $table = 'asset_performances';

    protected $fillable = [
        'asset_id',
        'performance_date',
        'performance_score',
        'availability_percentage',
        'utilization_percentage',
        'efficiency_percentage',
        'downtime_hours',
        'operating_hours',
        'maintenance_cost',
        'operating_cost',
        'revenue_generated',
        'target_value',
        'actual_value',
        'performance_status',
        'inspector_id',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'performance_date' => 'date',

        'performance_score' => 'decimal:2',

        'availability_percentage' => 'decimal:2',

        'utilization_percentage' => 'decimal:2',

        'efficiency_percentage' => 'decimal:2',

        'downtime_hours' => 'decimal:2',

        'operating_hours' => 'decimal:2',

        'maintenance_cost' => 'decimal:2',

        'operating_cost' => 'decimal:2',

        'revenue_generated' => 'decimal:2',

        'target_value' => 'decimal:2',

        'actual_value' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Asset
    |--------------------------------------------------------------------------
    */

    public function asset(): BelongsTo
    {
        return $this->belongsTo(
            Asset::class,
            'asset_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Inspector
    |--------------------------------------------------------------------------
    */

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'inspector_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Updated By
    |--------------------------------------------------------------------------
    */

    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
}