<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterScheduleActivity extends Model
{
    protected $fillable = [

        'master_schedule_id',

        'activity_code',
        'activity_name',

        'parent_activity_id',

        'sequence',

        'activity_type',

        'planned_start_date',
        'planned_end_date',

        'baseline_start_date',
        'baseline_end_date',

        'actual_start_date',
        'actual_end_date',

        'planned_duration_days',
        'actual_duration_days',

        'planned_progress',
        'actual_progress',

        'predecessor_activity_id',
        'dependency_type',

        'responsible_user_id',

        'status',

        'is_milestone',

        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'planned_start_date' =>
            'date',

        'planned_end_date' =>
            'date',

        'baseline_start_date' =>
            'date',

        'baseline_end_date' =>
            'date',

        'actual_start_date' =>
            'date',

        'actual_end_date' =>
            'date',

        'planned_progress' =>
            'decimal:2',

        'actual_progress' =>
            'decimal:2',

        'is_milestone' =>
            'boolean',
    ];


    /*
    |--------------------------------------------------------------------------
    | Master Schedule
    |--------------------------------------------------------------------------
    */

    public function masterSchedule(): BelongsTo
    {
        return $this->belongsTo(
            MasterSchedule::class,
            'master_schedule_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Parent Activity
    |--------------------------------------------------------------------------
    */

    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            MasterScheduleActivity::class,
            'parent_activity_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Child Activities
    |--------------------------------------------------------------------------
    */

    public function children(): HasMany
    {
        return $this->hasMany(
            MasterScheduleActivity::class,
            'parent_activity_id'
        )->orderBy('sequence');
    }


    /*
    |--------------------------------------------------------------------------
    | Predecessor
    |--------------------------------------------------------------------------
    */

    public function predecessor(): BelongsTo
    {
        return $this->belongsTo(
            MasterScheduleActivity::class,
            'predecessor_activity_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Successors
    |--------------------------------------------------------------------------
    */

    public function successors(): HasMany
    {
        return $this->hasMany(
            MasterScheduleActivity::class,
            'predecessor_activity_id'
        );
    }
}