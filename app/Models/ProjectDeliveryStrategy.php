<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectDeliveryStrategy extends Model
{
    protected $table = 'project_delivery_strategies';

    protected $fillable = [

        'project_id',

        'strategy_number',
        'title',

        'version_number',

        'status',

        'delivery_model',

        'delivery_approach',

        'implementation_strategy',

        'project_packaging_strategy',

        'responsibility_matrix',

        'key_milestones',

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