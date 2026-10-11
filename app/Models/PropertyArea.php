<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyArea extends Model
{
    use SoftDeletes;

    protected $table = 'property_areas';

    protected $fillable = [
        'area_code',
        'area_name',
        'area_type_id',
        'area_category',

        'mall_id',
        'building_id',
        'floor_id',
        'zone_id',
        'unit_id',

        'gross_area',
        'net_area',
        'usable_area',
        'rentable_area',
        'chargeable_area',
        'common_area',
        'service_area',

        'measurement_unit',
        'measurement_date',
        'measurement_method',
        'measurement_standard',

        'effective_from',
        'effective_to',

        'status',

        'current_revision_id',

        'approved_by',
        'approved_at',

        'remarks',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'gross_area'      => 'decimal:2',
        'net_area'        => 'decimal:2',
        'usable_area'     => 'decimal:2',
        'rentable_area'   => 'decimal:2',
        'chargeable_area' => 'decimal:2',
        'common_area'     => 'decimal:2',
        'service_area'    => 'decimal:2',

        'measurement_date' => 'date',
        'effective_from'   => 'date',
        'effective_to'     => 'date',

        'approved_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | AREA TYPE
    |--------------------------------------------------------------------------
    */

    public function areaType(): BelongsTo
    {
        return $this->belongsTo(
            AreaType::class,
            'area_type_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PROPERTY / MALL
    |--------------------------------------------------------------------------
    */

    public function mall(): BelongsTo
    {
        return $this->belongsTo(
            Mall::class,
            'mall_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BUILDING
    |--------------------------------------------------------------------------
    */

    public function building(): BelongsTo
    {
        return $this->belongsTo(
            Building::class,
            'building_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FLOOR
    |--------------------------------------------------------------------------
    */

    public function floor(): BelongsTo
    {
        return $this->belongsTo(
            Floor::class,
            'floor_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ZONE
    |--------------------------------------------------------------------------
    */

    public function zone(): BelongsTo
    {
        return $this->belongsTo(
            Zone::class,
            'zone_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UNIT
    |--------------------------------------------------------------------------
    */

    public function unit(): BelongsTo
    {
        return $this->belongsTo(
            Unit::class,
            'unit_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AREA REVISIONS
    |--------------------------------------------------------------------------
    */
public function revisions(): HasMany
{
    return $this->hasMany(
        AreaRevision::class,
        'property_area_id',
        'id'
    );
}
    

    public function currentRevision(): BelongsTo
    {
        return $this->belongsTo(
            AreaRevision::class,
            'current_revision_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATED BY
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
    | UPDATED BY
    |--------------------------------------------------------------------------
    */

    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVED BY
    |--------------------------------------------------------------------------
    */

    public function approver(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }

    public function scopeForMall($query, $mallId)
    {
        return $query->where('mall_id', $mallId);
    }

    public function scopeForBuilding($query, $buildingId)
    {
        return $query->where('building_id', $buildingId);
    }

    public function scopeForFloor($query, $floorId)
    {
        return $query->where('floor_id', $floorId);
    }

    public function scopeForZone($query, $zoneId)
    {
        return $query->where('zone_id', $zoneId);
    }

    public function scopeForUnit($query, $unitId)
    {
        return $query->where('unit_id', $unitId);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getDisplayAreaAttribute()
    {
        return $this->net_area
            ?? $this->gross_area
            ?? $this->usable_area
            ?? $this->gross_area
            ?? 0;
    }

    public function getAreaTypeNameAttribute(): string
    {
        return $this->areaType?->name ?? '-';
    }

    public function getLocationNameAttribute(): string
    {
        if ($this->unit) {
            return $this->unit->unit_no ?? 'Unit';
        }

        if ($this->zone) {
            return $this->zone->zone_name ?? 'Zone';
        }

        if ($this->floor) {
            return $this->floor->floor_name ?? 'Floor';
        }

        if ($this->building) {
            return $this->building->building_name ?? 'Building';
        }

        if ($this->mall) {
            return $this->mall->mall_name ?? 'Mall';
        }

        return '-';
    }
}