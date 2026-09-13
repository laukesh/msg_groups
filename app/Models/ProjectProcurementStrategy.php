<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectProcurementStrategy extends Model
{
    protected $table = 'project_procurement_strategies';

    protected $fillable = [

        'project_id',

        'strategy_number',
        'title',

        'version_number',

        'status',

        'procurement_model',

        'procurement_approach',

        'procurement_packages',

        'sourcing_strategy',

        'tendering_strategy',

        'vendor_selection_criteria',

        'procurement_schedule',

        'assumptions',

        'constraints',

        'effective_date',

        'approved_date',

        'approved_by',

        'remarks',

        'created_by',
        'updated_by',

    ];

    protected $casts = [

        'version_number' => 'integer',

        'effective_date' => 'date',

        'approved_date' => 'date',

    ];


    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }
}