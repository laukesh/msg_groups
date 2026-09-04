<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConstructionProgressUpdate extends Model
{
    protected $table = 'construction_progress_updates';

    protected $fillable = [

        'project_id',
        'construction_work_order_id',

        'progress_number',

        'progress_date',

        'progress_percentage',

        'planned_percentage',

        'physical_progress',
        'financial_progress',

        'status',

        'work_description',

        'issues',
        'corrective_action',
        'next_action',

        'weather_condition',

        'remarks',

        'reported_by',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'progress_date' =>
            'date',

        'progress_percentage' =>
            'decimal:2',

        'planned_percentage' =>
            'decimal:2',

        'physical_progress' =>
            'decimal:2',

        'financial_progress' =>
            'decimal:2',
    ];


    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }


    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionWorkOrder::class,
            'construction_work_order_id'
        );
    }


    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'reported_by'
        );
    }


    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
    
    public function siteIssues(): HasMany
    {
        return $this->hasMany(
            ConstructionSiteIssue::class,
            'construction_progress_update_id'
        );
    }
}