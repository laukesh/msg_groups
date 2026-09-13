<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ProcurementTenderBidder extends Model
{
    protected $table = 'procurement_tender_bidders';

    protected $fillable = [
        'procurement_tender_id',
        'procurement_bidder_id',
        'bidder_reference_no',
        'invitation_date',
        'registration_date',
        'participation_status',
        'prequalification_required',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'invitation_date' => 'date',
        'registration_date' => 'date',
        'prequalification_required' => 'boolean',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementTender::class,
            'procurement_tender_id'
        );
    }

    public function bidder(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementBidder::class,
            'procurement_bidder_id'
        );
    }

    public function prequalification(): HasOne
    {
        return $this->hasOne(
            ProcurementPrequalification::class,
            'procurement_tender_bidder_id'
        );
    }
    
    public function submission(): HasOne
    {
        return $this->hasOne(
            ProcurementTenderSubmission::class,
            'procurement_tender_bidder_id'
        );
    }
}
