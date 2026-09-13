<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaseAgreement extends Model
{
    use SoftDeletes;

    protected $table = 'lease_agreements';

    protected $fillable = [
        'uuid',
        'agreement_no',
        'proposal_id',
        'tenant_id',
        'agreement_date',
        'lease_start_date',
        'lease_end_date',
        'lease_period_months',
        'rent_start_date',
        'handover_date',
        'fitout_start_date',
        'fitout_end_date',
        'rent_free_days',
        'security_deposit',
        'monthly_rent',
        'cam_amount',
        'utility_deposit',
        'billing_frequency',
        'payment_due_day',
        'agreement_status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'agreement_date' => 'date',
        'lease_start_date' => 'date',
        'lease_end_date' => 'date',
        'rent_start_date' => 'date',
        'handover_date' => 'date',
        'fitout_start_date' => 'date',
        'fitout_end_date' => 'date',

        'security_deposit' => 'decimal:2',
        'monthly_rent' => 'decimal:2',
        'cam_amount' => 'decimal:2',
        'utility_deposit' => 'decimal:2',
    ];


    public function tenant()
    {
        return $this->belongsTo(
            Tenant::class,
            'tenant_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Lease Proposal
    |--------------------------------------------------------------------------
    */

    public function leaseProposal()
    {
        return $this->belongsTo(
            LeaseProposal::class,
            'lease_proposal_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Agreement Units
    |--------------------------------------------------------------------------
    */

    public function agreementUnits()
    {
        return $this->hasMany(
            AgreementUnit::class,
            'agreement_id'
        );
    }

    public function proposal()
	{
	    return $this->belongsTo(
	        LeaseProposal::class,
	        'proposal_id'
	    );
	}

    public function leaseTerm()
    {
        return $this->hasOne(
            LeaseTerm::class,
            'lease_agreement_id'
        );
    }

    public function documents()
    {
        return $this->hasMany(
            LeaseDocument::class,
            'lease_agreement_id'
        );
    }

    public function terms()
    {
        return $this->hasOne(
            LeaseTerm::class,
            'lease_agreement_id'
        );
    }

    public function renewals()
    {
        return $this->hasMany(
            LeaseRenewal::class,
            'lease_agreement_id'
        );
    }

    public function units()
    {
        return $this->hasMany(
            AgreementUnit::class,
            'lease_agreement_id'
        );
    }

    public function escalations()
    {
        return $this->hasMany(
            LeaseEscalation::class,
            'lease_agreement_id'
        );
    }

    public function history()
    {
        return $this->hasMany(
            LeaseHistory::class,
            'lease_agreement_id'
        )->latest('activity_date');
    }

    public function terminations()
    {
        return $this->hasMany(
            LeaseTermination::class,
            'lease_agreement_id'
        )->latest('id');
    }

    public function rentSchedules()
    {
        return $this->hasMany(
            RentSchedule::class,
            'lease_agreement_id'
        );
    }
}