<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcurementPackage extends Model
{
    protected $table = 'procurement_packages';

    protected $fillable = [

        'procurement_plan_id',
        'project_budget_id',
        'project_id',

        'package_number',
        'package_title',

        'package_type',

        'description',
        'scope_of_work',

        'estimated_value',
        'currency',

        'procurement_method',

        'planned_tender_date',
        'planned_award_date',

        'planned_start_date',
        'planned_completion_date',

        'status',

        'responsible_user_id',
        'responsible_name',

        'remarks',

        'created_by',
        'updated_by',
    ];

    protected $casts = [

        'estimated_value' => 'decimal:2',

        'planned_tender_date' => 'date',
        'planned_award_date' => 'date',

        'planned_start_date' => 'date',
        'planned_completion_date' => 'date',
    ];


    /*
    |--------------------------------------------------------------------------
    | Procurement Plan
    |--------------------------------------------------------------------------
    */

    public function procurementPlan(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementPlan::class,
            'procurement_plan_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Project Budget
    |--------------------------------------------------------------------------
    */

    public function budget(): BelongsTo
    {
        return $this->belongsTo(
            ProjectBudget::class,
            'project_budget_id'
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
    | Tenders
    |--------------------------------------------------------------------------
    */

    public function tenders(): HasMany
    {
        return $this->hasMany(
            ProcurementTender::class,
            'procurement_package_id'
        );
    }

    public function projectBudget(): BelongsTo
    {
        return $this->belongsTo(
            ProjectBudget::class,
            'project_budget_id'
        );
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }
}