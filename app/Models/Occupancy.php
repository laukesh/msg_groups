<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Occupancy extends Model
{
    protected $table = 'occupancies';

    protected $fillable = [
        'asset_id',
        'occupancy_status',
        'occupancy_percentage',
        'tenant_name',
        'lease_start_date',
        'lease_end_date',
        'monthly_rent',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'lease_start_date' => 'date',
        'lease_end_date' => 'date',
        'occupancy_percentage' => 'decimal:2',
        'monthly_rent' => 'decimal:2',
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