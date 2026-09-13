<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProposalUnit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'proposal_units';

    protected $fillable = [
        'proposal_id',
        'unit_id',
        'lease_proposal_id',
        'proposed_rent',
        'proposed_cam_rate',
        'proposed_security_deposit',
        'rent_free_days',
        'fitout_period_days',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'proposed_rent' => 'decimal:2',
        'proposed_cam_rate' => 'decimal:2',
        'proposed_security_deposit' => 'decimal:2',
        'rent_free_days' => 'integer',
        'fitout_period_days' => 'integer',
    ];

    /**
     * Proposal relationship
     */
    public function proposal()
    {
        return $this->belongsTo(
            LeaseProposal::class,
            'proposal_id'
        );
    }

    /**
     * Lease Proposal relationship
     */
    public function leaseProposal()
    {
        return $this->belongsTo(
            LeaseProposal::class,
            'lease_proposal_id'
        );
    }

    /**
     * Unit relationship
     */
    public function unit()
    {
        return $this->belongsTo(
            Unit::class,
            'unit_id'
        );
    }
}