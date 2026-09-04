<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcurementNegotiation extends Model
{
    protected $table = 'procurement_negotiations';

    protected $fillable = [
        'procurement_tender_id',
        'procurement_bid_comparison_id',
        'procurement_tender_submission_id',

        'negotiation_number',
        'negotiation_title',
        'negotiation_date',
        'negotiation_type',

        'bidder_name',

        'original_amount',
        'negotiated_amount',
        'final_amount',

        'currency',

        'outcome',
        'status',

        'prepared_by',
        'reviewed_by',
        'approved_by',
        'approval_date',

        'summary',
        'remarks',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'negotiation_date' => 'date',
        'approval_date' => 'date',

        'original_amount' => 'decimal:2',
        'negotiated_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementTender::class,
            'procurement_tender_id'
        );
    }

    public function bidComparison(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementBidComparison::class,
            'procurement_bid_comparison_id'
        );
    }

    public function submission(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementTenderSubmission::class,
            'procurement_tender_submission_id'
        );
    }

    public function items(): HasMany
    {
        return $this->hasMany(
            ProcurementNegotiationItem::class,
            'procurement_negotiation_id'
        );
    }

    public function awards(): HasMany
    {
        return $this->hasMany(
            ProcurementAward::class,
            'procurement_negotiation_id'
        );
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(
            ProcurementContract::class,
            'procurement_negotiation_id'
        );
    }
}