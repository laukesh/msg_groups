<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConstructionEquipment extends Model
{
    use SoftDeletes;

    protected $table = 'construction_equipment';

    protected $fillable = [
        'equipment_code',
        'equipment_name',
        'category',
        'ownership_type',
        'make',
        'model',
        'serial_number',
        'registration_number',
        'capacity',
        'capacity_unit',
        'purchase_date',
        'purchase_value',
        'hire_rate',
        'hire_rate_unit',
        'status',
        'description',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'capacity' => 'decimal:4',
        'purchase_value' => 'decimal:2',
        'hire_rate' => 'decimal:2',
    ];

    public function deployments()
    {
        return $this->hasMany(
            ConstructionEquipmentDeployment::class,
            'equipment_id'
        );
    }

    public function usageLogs()
    {
        return $this->hasMany(
            ConstructionEquipmentUsageLog::class,
            'equipment_id'
        );
    }

    public function maintenanceRecords()
    {
        return $this->hasMany(
            ConstructionEquipmentMaintenance::class,
            'equipment_id'
        );
    }

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function updater()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
}