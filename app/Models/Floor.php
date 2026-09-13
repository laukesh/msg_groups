<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;

    protected $table = 'floors';

    protected $fillable = [
        'building_id',
        'floor_code',
        'floor_name',
        'floor_number',
        'floor_no',
        'total_units',
        'rentable_area',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'floor_number'  => 'integer',
        'floor_no'      => 'integer',
        'total_units'   => 'integer',
        'rentable_area' => 'decimal:2',
        'created_by'    => 'integer',
        'updated_by'    => 'integer',
    ];

    /**
     * Building
     */
    public function building()
    {
        return $this->belongsTo(
            Building::class,
            'building_id'
        );
    }

    /**
     * Zones
     */
    public function zones()
    {
        return $this->hasMany(
            Zone::class,
            'floor_id'
        );
    }

    /**
     * Units
     */
    public function units()
    {
        return $this->hasMany(
            Unit::class,
            'floor_id'
        );
    }

    /**
     * Created by user
     */
    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /**
     * Updated by user
     */
    public function updater()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
}