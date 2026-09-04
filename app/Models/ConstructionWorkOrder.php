<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConstructionWorkOrder extends Model
{
    protected $table = 'construction_work_orders';

    protected $fillable = [

        'project_id',
        'procurement_contract_id',

        'work_order_number',
        'work_order_title',

        'work_order_type',

        'issue_date',
        'start_date',
        'expected_completion_date',
        'actual_completion_date',

        'work_order_value',
        'currency',

        'scope_of_work',

        'priority',

        'status',

        'assigned_to',

        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'issue_date' =>
            'date',

        'start_date' =>
            'date',

        'expected_completion_date' =>
            'date',

        'actual_completion_date' =>
            'date',

        'work_order_value' =>
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
    | Procurement Contract
    |--------------------------------------------------------------------------
    */

    public function contract(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementContract::class,
            'procurement_contract_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Assigned User
    |--------------------------------------------------------------------------
    */

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_to'
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

    public function progressUpdates(): HasMany
	{
	    return $this->hasMany(
	        ConstructionProgressUpdate::class,
	        'construction_work_order_id'
	    );
	}

    public function siteIssues(): HasMany
    {
        return $this->hasMany(
            ConstructionSiteIssue::class,
            'construction_work_order_id'
        );
    }

    public function siteReports(): HasMany
    {
        return $this->hasMany(
            ConstructionSiteReport::class,
            'construction_work_order_id'
        );
    }

    public function scheduleActivities(): HasMany
    {
        return $this->hasMany(
            ConstructionScheduleActivity::class,
            'construction_work_order_id'
        );
    }

    public function progressEntries(): HasMany
    {
        return $this->hasMany(
            ConstructionProgressEntry::class,
            'construction_work_order_id'
        );
    }

    public function otherCosts(): HasMany
    {
        return $this->hasMany(
            ConstructionOtherCost::class,
            'construction_work_order_id'
        );
    }

    public function variations(): HasMany
    {
        return $this->hasMany(
            ConstructionVariation::class,
            'construction_work_order_id'
        );
    }

    public function materialRequests(): HasMany
    {
        return $this->hasMany(
            ConstructionMaterialRequest::class,
            'construction_work_order_id'
        );
    }
}