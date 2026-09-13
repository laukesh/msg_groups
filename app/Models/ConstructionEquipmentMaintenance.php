<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConstructionEquipmentMaintenance extends Model
{
    use SoftDeletes;

    protected $table = 'construction_equipment_maintenance';

    protected $fillable = [
        'equipment_id',
        'maintenance_number',
        'maintenance_type',
        'scheduled_date',
        'maintenance_date',
        'meter_reading',
        'issue_description',
        'work_performed',
        'maintenance_vendor',
        'cost',
        'status',
        'next_service_date',
        'next_service_meter',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'maintenance_date' => 'date',
        'next_service_date' => 'date',
        'meter_reading' => 'decimal:2',
        'cost' => 'decimal:2',
        'next_service_meter' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Equipment
    |--------------------------------------------------------------------------
    */

    public function equipment()
    {
        return $this->belongsTo(
            ConstructionEquipment::class,
            'equipment_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */

    public function creator()
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

    public function updater()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
}