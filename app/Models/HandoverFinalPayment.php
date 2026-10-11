<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HandoverFinalPayment extends Model
{
    protected $table = 'handover_final_payments';

    protected $fillable = [
        'project_id',
        'handover_project_id',
        'final_account_id',
        'payment_no',

        'approved_amount',
        'amount_paid',
        'balance_amount',

        'payment_date',
        'payment_method',
        'transaction_reference',
        'status',

        'prepared_by',
        'submitted_by',
        'reviewed_by',
        'approved_by',
        'paid_by',

        'prepared_at',
        'submitted_at',
        'reviewed_at',
        'approved_at',
        'paid_at',

        'rejection_reason',

        'payment_remarks',
        'finance_remarks',
        'remarks',

        'payment_document_path',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'approved_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance_amount' => 'decimal:2',

        'payment_date' => 'date',

        'prepared_at' => 'datetime',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }

    public function handover(): BelongsTo
    {
        return $this->belongsTo(
            HandoverProject::class,
            'handover_project_id'
        );
    }

    public function finalAccount(): BelongsTo
    {
        return $this->belongsTo(
            HandoverFinalAccount::class,
            'final_account_id'
        );
    }

    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'prepared_by'
        );
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'submitted_by'
        );
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by'
        );
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }

    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'paid_by'
        );
    }

}