<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaseRenewal extends Model
{
    use SoftDeletes;

    protected $table = 'lease_renewals';

    protected $fillable = [
        'lease_agreement_id',
        'renewal_no',
        'request_date',
        'current_expiry_date',
        'proposed_start_date',
        'proposed_end_date',
        'renewal_period_months',
        'current_rent',
        'proposed_rent',
        'proposed_security_deposit',
        'escalation_percentage',
        'negotiation_notes',
        'approval_status',
        'approved_by',
        'approved_at',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'request_date' => 'date',
        'current_expiry_date' => 'date',
        'proposed_start_date' => 'date',
        'proposed_end_date' => 'date',
        'approved_at' => 'datetime',
        'current_rent' => 'decimal:2',
        'proposed_rent' => 'decimal:2',
        'proposed_security_deposit' => 'decimal:2',
        'escalation_percentage' => 'decimal:2',
    ];


    /*
    |--------------------------------------------------------------------------
    | Lease Agreement
    |--------------------------------------------------------------------------
    */

    public function agreement()
    {
        return $this->belongsTo(
            LeaseAgreement::class,
            'lease_agreement_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Approved By
    |--------------------------------------------------------------------------
    */

    public function approvedBy()
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */

    public function createdBy()
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

    public function updatedBy()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
}