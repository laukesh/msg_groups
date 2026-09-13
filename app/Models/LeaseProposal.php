<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaseProposal extends Model
{
    use HasFactory;

    protected $table = 'lease_proposals';

    protected $fillable = [
        'uuid',
        'proposal_no',
        'tenant_id',
        'proposal_title',
        'proposal_date',
        'expected_start_date',
        'expected_end_date',
        'proposal_status',
        'remarks',
        'rejection_reason',
        'valid_until',
        'lease_start_date',
        'lease_end_date',
        'lease_period_months',
        'security_deposit',
        'monthly_rent',
        'cam_amount',
        'fitout_period_days',
        'rent_free_days',
        'escalation_percentage',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'proposal_date' => 'date',
        'valid_until' => 'date',
        'lease_start_date' => 'date',
        'lease_end_date' => 'date',

        'security_deposit' => 'decimal:2',
        'monthly_rent' => 'decimal:2',
        'cam_amount' => 'decimal:2',
        'escalation_percentage' => 'decimal:2',

        'approved_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Tenant
    |--------------------------------------------------------------------------
    */

    public function tenant()
    {
        return $this->belongsTo( Tenant::class, 'tenant_id' );
    }

    /*
    |--------------------------------------------------------------------------
    | Proposal Units
    |--------------------------------------------------------------------------
    */

    public function units()
    {
        return $this->hasMany(  ProposalUnit::class, 'lease_proposal_id' );
    }

    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */

    public function createdBy()
    {
        return $this->belongsTo( User::class, 'created_by' );
    }

    /*
    |--------------------------------------------------------------------------
    | Updated By
    |--------------------------------------------------------------------------
    */

    public function updatedBy()
    {
        return $this->belongsTo( User::class, 'updated_by');
    }

    public function proposalUnits()
    {
        return $this->hasMany(
            ProposalUnit::class,
            'proposal_id'
        );
    }

    public function leaseAgreement()
    {
        return $this->hasOne(
            LeaseAgreement::class,
            'lease_proposal_id'
        );
    }
}