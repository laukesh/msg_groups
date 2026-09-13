<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaseTermination extends Model
{
    protected $table = 'lease_terminations';

    protected $fillable = [
        'lease_agreement_id',
        'termination_no',
        'termination_type',
        'request_date',
        'notice_date',
        'effective_date',
        'reason',
        'outstanding_amount',
        'penalty_amount',
        'damage_charges',
        'refundable_deposit',
        'final_settlement_amount',
        'inspection_status',
        'handover_status',
        'termination_status',
        'approved_by',
        'approved_at',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'request_date' => 'date',
        'notice_date' => 'date',
        'effective_date' => 'date',
        'approved_at' => 'datetime',

        'outstanding_amount' => 'decimal:2',
        'penalty_amount' => 'decimal:2',
        'damage_charges' => 'decimal:2',
        'refundable_deposit' => 'decimal:2',
        'final_settlement_amount' => 'decimal:2',
    ];


    public function agreement()
    {
        return $this->belongsTo(
            LeaseAgreement::class,
            'lease_agreement_id'
        );
    }


    public function approver()
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }
}