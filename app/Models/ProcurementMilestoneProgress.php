<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcurementMilestoneProgress extends Model
{
    protected $table = 'procurement_milestone_progress';

    protected $fillable = [

        'procurement_contract_id',
        'procurement_contract_milestone_id',

        'progress_date',

        'progress_percentage',
        'previous_progress_percentage',

        'progress_description',

        'work_completed',
        'work_pending',

        'issues',
        'corrective_action',

        'status',

        'submitted_by',
        'submitted_at',

        'verified_by',
        'verified_at',

        'verification_remarks',

        'created_by',
        'updated_by',
    ];

    protected $casts = [

        'progress_date' => 'date',

        'progress_percentage' => 'decimal:2',
        'previous_progress_percentage' => 'decimal:2',

        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
    ];


    public function contract(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementContract::class,
            'procurement_contract_id'
        );
    }


    public function milestone(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementContractMilestone::class,
            'procurement_contract_milestone_id'
        );
    }
}