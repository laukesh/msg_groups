<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConstructionRisk extends Model
{
    use SoftDeletes;

    protected $table = 'construction_risks';

    protected $fillable = [

        'project_id',

        'construction_work_order_id',

        'construction_schedule_activity_id',

        'risk_number',

        'risk_title',

        'risk_category',

        'risk_date',

        'identified_by',

        'risk_description',

        'risk_cause',

        'potential_impact',

        'potential_cost_impact',

        'potential_delay_days',

        'probability',

        'impact_level',

        'risk_score',

        'risk_rating',

        'response_strategy',

        'response_plan',

        'owner_type',

        'owner_name',

        'target_resolution_date',

        'residual_probability',

        'residual_impact_level',

        'residual_risk_score',

        'residual_risk_rating',

        'status',

        'priority',

        'closed_date',

        'closed_by',

        'closure_remarks',

        'remarks',

        'created_by',

        'updated_by',
    ];

    protected $casts = [

        'risk_date' => 'date',

        'target_resolution_date' => 'date',

        'closed_date' => 'date',

        'potential_cost_impact' => 'decimal:2',

        'potential_delay_days' => 'integer',

        'risk_score' => 'integer',

        'residual_risk_score' => 'integer',

        'created_at' => 'datetime',

        'updated_at' => 'datetime',

        'deleted_at' => 'datetime',
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
    | Schedule Activity
    |--------------------------------------------------------------------------
    */

    public function scheduleActivity(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionScheduleActivity::class,
            'construction_schedule_activity_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Identified By
    |--------------------------------------------------------------------------
    */

    public function identifiedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'identified_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Closed By
    |--------------------------------------------------------------------------
    */

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'closed_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Created By
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
    | Updated By
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
    | Risk Actions
    |--------------------------------------------------------------------------
    */

    public function actions(): HasMany
    {
        return $this->hasMany(
            ConstructionRiskAction::class,
            'construction_risk_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Documents
    |--------------------------------------------------------------------------
    */

    public function documents(): HasMany
    {
        return $this->hasMany(
            ConstructionRiskDocument::class,
            'construction_risk_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | History
    |--------------------------------------------------------------------------
    */

    public function history(): HasMany
    {
        return $this->hasMany(
            ConstructionRiskHistory::class,
            'construction_risk_id'
        )->latest('performed_at');
    }
}