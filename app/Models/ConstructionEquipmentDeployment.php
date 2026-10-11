<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConstructionEquipmentDeployment extends Model
{
    use SoftDeletes;

    protected $table = 'construction_equipment_deployments';

    protected $fillable = [
        'equipment_id',
        'project_id',
        'construction_work_order_id',
        'deployment_number',
        'deployment_date',
        'return_date',
        'operator_id',
        'location',
        'status',
        'starting_meter',
        'ending_meter',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'deployment_date' => 'date',
        'return_date' => 'date',
        'starting_meter' => 'decimal:2',
        'ending_meter' => 'decimal:2',
    ];

    public function equipment()
    {
        return $this->belongsTo(
            ConstructionEquipment::class,
            'equipment_id'
        );
    }

    public function project()
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }

    public function workOrder()
    {
        return $this->belongsTo(
            ConstructionWorkOrder::class,
            'construction_work_order_id'
        );
    }

    public function operator()
    {
        return $this->belongsTo(
            User::class,
            'operator_id'
        );
    }

    public function usageLogs()
    {
        return $this->hasMany(
            ConstructionEquipmentUsageLog::class,
            'equipment_deployment_id'
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