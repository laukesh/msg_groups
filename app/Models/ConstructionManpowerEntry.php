<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConstructionManpowerEntry extends Model
{
    use SoftDeletes;

    protected $table = 'construction_manpower_entries';

    protected $fillable = [
        'manpower_id',
        'project_id',
        'construction_work_order_id',
        'manpower_assignment_id',
        'entry_number',
        'entry_date',
        'attendance_status',
        'regular_hours',
        'overtime_hours',
        'total_hours',
        'daily_rate',
        'overtime_rate',
        'total_cost',
        'work_description',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'regular_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'total_hours' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'overtime_rate' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function manpower()
    {
        return $this->belongsTo(
            ConstructionManpower::class,
            'manpower_id'
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

    public function assignment()
    {
        return $this->belongsTo(
            ConstructionManpowerAssignment::class,
            'manpower_assignment_id'
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