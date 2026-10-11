<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
class HandoverFinalAccount extends Model
{
    protected $table = 'handover_final_accounts';

    protected $fillable = [
        'project_id',
        'handover_project_id',
        'final_account_no',
        'procurement_contract_id',

        'contract_value',
        'approved_variations',
        'approved_claims',

        'advance_payment',
        'advance_recovery',
        'retention_amount',
        'deductions',
        'other_adjustments',

        'gross_final_amount',
        'certified_amount',
        'amount_paid',
        'balance_payable',

        'status',

        'prepared_date',
        'submitted_date',
        'approved_date',

        'prepared_by',
        'submitted_by',
        'reviewed_by',
        'approved_by',

        'prepared_at',
        'submitted_at',
        'reviewed_at',
        'approved_at',

        'rejection_reason',
        'contractor_statement',
        'finance_remarks',
        'remarks',
        'document_path',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'contract_value'       => 'decimal:2',
        'approved_variations' => 'decimal:2',
        'approved_claims'     => 'decimal:2',

        'advance_payment'     => 'decimal:2',
        'advance_recovery'   => 'decimal:2',
        'retention_amount'   => 'decimal:2',
        'deductions'         => 'decimal:2',
        'other_adjustments'  => 'decimal:2',

        'gross_final_amount' => 'decimal:2',
        'certified_amount'   => 'decimal:2',
        'amount_paid'        => 'decimal:2',
        'balance_payable'    => 'decimal:2',

        'prepared_date' => 'date',
        'submitted_date' => 'date',
        'approved_date' => 'date',

        'prepared_at' => 'datetime',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function handover(): BelongsTo
    {
        return $this->belongsTo(
            HandoverProject::class,
            'handover_project_id'
        );
    }

    public function procurementContract(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementContract::class,
            'procurement_contract_id'
        );
    }

    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function handoverProject(): BelongsTo
    {
        return $this->belongsTo(
            HandoverProject::class,
            'handover_project_id'
        );
    }

    public function payment(): HasOne
    {
        return $this->hasOne(
            HandoverFinalPayment::class,
            'final_account_id'
        );
    }
}