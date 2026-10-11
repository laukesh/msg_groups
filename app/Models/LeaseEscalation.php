<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaseEscalation extends Model
{
    use SoftDeletes;

    protected $table = 'lease_escalations';

    protected $fillable = [
        'lease_agreement_id',
        'escalation_no',
        'effective_from',
        'previous_rent',
        'escalation_type',
        'escalation_value',
        'revised_rent',
        'status',
        'remarks',
        'approved_by',
        'approved_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'effective_from' => 'date',
        'approved_at' => 'datetime',
        'previous_rent' => 'decimal:2',
        'escalation_value' => 'decimal:2',
        'revised_rent' => 'decimal:2',
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