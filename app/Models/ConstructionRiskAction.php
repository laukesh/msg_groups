<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionRiskAction extends Model
{
    use SoftDeletes;

    protected $table = 'construction_risk_actions';

    protected $fillable = [

        'construction_risk_id',

        'action_title',

        'action_description',

        'action_type',

        'assigned_to',

        'assigned_to_name',

        'target_date',

        'completion_date',

        'status',

        'priority',

        'remarks',

        'created_by',

        'updated_by',
    ];

    protected $casts = [

        'target_date' => 'date',

        'completion_date' => 'date',

        'created_at' => 'datetime',

        'updated_at' => 'datetime',

        'deleted_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | Risk
    |--------------------------------------------------------------------------
    */

    public function risk(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionRisk::class,
            'construction_risk_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Assigned User
    |--------------------------------------------------------------------------
    */

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_to'
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
}