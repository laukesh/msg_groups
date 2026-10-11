<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConstructionManpowerAssignment extends Model
{
    use SoftDeletes;

    protected $table = 'construction_manpower_assignments';

    protected $fillable = [
        'manpower_id',
        'project_id',
        'construction_work_order_id',
        'assignment_number',
        'assignment_date',
        'release_date',
        'role',
        'daily_rate',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'assignment_date' => 'date',
        'release_date' => 'date',
        'daily_rate' => 'decimal:2',
    ];

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

    public function entries()
    {
        return $this->hasMany(
            ConstructionManpowerEntry::class,
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