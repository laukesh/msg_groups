<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectContractStrategy extends Model
{
    protected $table = 'project_contract_strategies';

    protected $fillable = [

        'project_id',

        'strategy_number',
        'title',

        'version_number',
        'status',

        'contracting_model',
        'contract_type',

        'commercial_model',

        'contract_packaging',

        'payment_strategy',

        'risk_allocation_strategy',

        'performance_security_strategy',

        'retention_strategy',

        'liquidated_damages_strategy',

        'insurance_strategy',

        'variation_change_strategy',

        'claims_strategy',

        'dispute_resolution_strategy',

        'defect_liability_strategy',

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


    /**
     * Project
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }
}