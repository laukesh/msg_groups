<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterSchedule extends Model
{
    protected $fillable = [

        'project_id',

        'schedule_number',
        'title',

        'baseline_start_date',
        'baseline_completion_date',

        'current_start_date',
        'current_completion_date',

        'planned_progress',
        'actual_progress',

        'status',

        'baseline_date',
        'approved_date',
        'approved_by',

        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'baseline_start_date' =>
            'date',

        'baseline_completion_date' =>
            'date',

        'current_start_date' =>
            'date',

        'current_completion_date' =>
            'date',

        'baseline_date' =>
            'date',

        'approved_date' =>
            'date',

        'planned_progress' =>
            'decimal:2',

        'actual_progress' =>
            'decimal:2',
    ];


    /*
    |--------------------------------------------------------------------------
    | Project
    |--------------------------------------------------------------------------
    */

    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Activities
    |--------------------------------------------------------------------------
    */

    public function activities(): HasMany
    {
        return $this->hasMany(
            MasterScheduleActivity::class,
            'master_schedule_id'
        );
    }
}