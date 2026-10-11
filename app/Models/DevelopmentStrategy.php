<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevelopmentStrategy extends Model
{
    protected $fillable = [

        'project_id',

        'strategy_number',
        'title',

        'development_vision',
        'development_concept',
        'development_objectives',

        'development_type',
        'development_model',
        'development_approach',

        'target_market',
        'market_positioning',
        'competitive_strategy',

        'development_mix',
        'planned_gla',
        'planned_nla',
        'planned_leasable_area',

        'key_assumptions',
        'strategic_constraints',
        'key_opportunities',
        'key_challenges',

        'recommended_strategy',
        'strategic_rationale',

        'status',
        'strategy_date',
        'approval_date',
        'approved_by',

        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'strategy_date' => 'date',

        'approval_date' => 'date',

        'planned_gla' => 'decimal:2',

        'planned_nla' => 'decimal:2',

        'planned_leasable_area' => 'decimal:2',

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
}