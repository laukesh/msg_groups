<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConstructionEquipmentUsageLog extends Model
{
    use SoftDeletes;

    protected $table = 'construction_equipment_usage_logs';

    protected $fillable = [
        'equipment_id',
        'project_id',
        'construction_work_order_id',
        'equipment_deployment_id',
        'usage_number',
        'usage_date',
        'operator_id',
        'opening_meter',
        'closing_meter',
        'operating_hours',
        'idle_hours',
        'fuel_consumed',
        'fuel_unit',
        'work_description',
        'breakdown_hours',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'usage_date' => 'date',
        'opening_meter' => 'decimal:2',
        'closing_meter' => 'decimal:2',
        'operating_hours' => 'decimal:2',
        'idle_hours' => 'decimal:2',
        'fuel_consumed' => 'decimal:4',
        'breakdown_hours' => 'decimal:2',
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
    | Project
    |--------------------------------------------------------------------------
    */

    public function project()
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Work Order
    |--------------------------------------------------------------------------
    */

    public function workOrder()
    {
        return $this->belongsTo(
            ConstructionWorkOrder::class,
            'construction_work_order_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Deployment
    |--------------------------------------------------------------------------
    */

    public function deployment()
    {
        return $this->belongsTo(
            ConstructionEquipmentDeployment::class,
            'equipment_deployment_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Operator
    |--------------------------------------------------------------------------
    */

    public function operator()
    {
        return $this->belongsTo(
            User::class,
            'operator_id'
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