<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ProcurementPlan extends Model
{
    protected $table = 'procurement_plans';

    protected $fillable = [

        'project_id',

        'procurement_strategy_id',

        'plan_number',
        'plan_title',

        'procurement_year',

        'description',
        'procurement_objective',

        'planned_start_date',
        'planned_completion_date',

        'total_estimated_value',
        'currency',

        'status',

        'prepared_by',
        'reviewed_by',
        'approved_by',

        'approval_date',

        'remarks',

        'created_by',
        'updated_by',

    ];


    protected $casts = [

        'procurement_year' => 'integer',

        'planned_start_date' => 'date',

        'planned_completion_date' => 'date',

        'approval_date' => 'date',

        'total_estimated_value' => 'decimal:2',

    ];


    /*
     * Procurement Plan → Project
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }


    /*
     * Procurement Plan → Project Procurement Strategy
     */
    public function procurementStrategy(): BelongsTo
    {
        return $this->belongsTo(
            ProjectProcurementStrategy::class,
            'procurement_strategy_id'
        );
    }


    /*
     * Prepared by
     */
    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'prepared_by'
        );
    }


    /*
     * Reviewed by
     */
    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by'
        );
    }


    /*
     * Approved by
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }


    /*
     * Created by
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    /*
     * Updated by
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    public function packages(): HasMany
	{
	    return $this->hasMany(
	        ProcurementPackage::class,
	        'procurement_plan_id'
	    );
	}

    public function tenders()
    {
        return $this->hasManyThrough(
            ProcurementTender::class,
            ProcurementPackage::class,
            'procurement_plan_id',      // packages → plans
            'procurement_package_id',   // tenders → packages
            'id',                       // plans.id
            'id'                        // packages.id
        );
    }
}