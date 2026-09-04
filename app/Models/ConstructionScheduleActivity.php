<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConstructionScheduleActivity extends Model
{
    protected $table =
        'construction_schedule_activities';


    protected $fillable = [

        'project_id',

        'construction_work_order_id',

        'activity_code',
        'activity_name',

        'wbs_code',
        'phase',

        'description',

        'planned_start_date',
        'planned_finish_date',

        'actual_start_date',
        'actual_finish_date',

        'duration_days',

        'progress_percentage',

        'predecessor_activity_id',

        'responsible_user_id',

        'priority',

        'status',

        'delay_days',

        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'planned_start_date' =>
            'date',

        'planned_finish_date' =>
            'date',

        'actual_start_date' =>
            'date',

        'actual_finish_date' =>
            'date',

        'progress_percentage' =>
            'decimal:2',

        'duration_days' =>
            'integer',

        'delay_days' =>
            'integer',
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
    | Work Order
    |--------------------------------------------------------------------------
    */

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionWorkOrder::class,
            'construction_work_order_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Predecessor
    |--------------------------------------------------------------------------
    */

    public function predecessor(): BelongsTo
    {
        return $this->belongsTo(
            self::class,
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
            self::class,
            'predecessor_activity_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Responsible User
    |--------------------------------------------------------------------------
    */

    public function responsibleUser(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'responsible_user_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Creator
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
    | Updater
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
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isNotStarted(): bool
    {
        return $this->status === 'Not Started';
    }


    public function isInProgress(): bool
    {
        return $this->status === 'In Progress';
    }


    public function isCompleted(): bool
    {
        return $this->status === 'Completed';
    }


    public function isDelayed(): bool
    {
        return $this->status === 'Delayed'
            || $this->delay_days > 0;
    }


    public function canEdit(): bool
    {
        return $this->status !== 'Cancelled';
    }

    /*
	|--------------------------------------------------------------------------
	| Progress Entries
	|--------------------------------------------------------------------------
	*/

	public function progressEntries(): HasMany
	{
	    return $this->hasMany(
	        ConstructionProgressEntry::class,
	        'construction_schedule_activity_id'
	    );
	}
}