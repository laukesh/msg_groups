<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcurementAward extends Model
{
    protected $table = 'procurement_awards';

    protected $fillable = [
        'procurement_tender_id',
        'procurement_negotiation_id',
        'procurement_tender_submission_id',

        'award_number',
        'award_title',
        'award_date',

        'bidder_name',

        'awarded_amount',
        'currency',

        'award_type',
        'status',

        'loa_number',
        'loa_date',
        'acceptance_deadline',

        'contract_required',

        'responsible_user_id',

        'issued_by',
        'approved_by',
        'approval_date',

        'description',
        'terms_and_conditions',
        'remarks',

        'created_by',
        'updated_by',

        'submitted_by',
		'submitted_at',
		'approval_remarks',
		'loa_issued_by',
		'loa_issued_at',
    ];

    protected $casts = [
        'award_date' => 'date',
        'loa_date' => 'date',
        'acceptance_deadline' => 'date',
        'approval_date' => 'date',

        'awarded_amount' => 'decimal:2',

        'contract_required' => 'boolean',
        'submitted_at' => 'datetime',
		'loa_issued_at' => 'datetime',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementTender::class,
            'procurement_tender_id'
        );
    }

    public function negotiation(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementNegotiation::class,
            'procurement_negotiation_id'
        );
    }

    public function submission(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementTenderSubmission::class,
            'procurement_tender_submission_id'
        );
    }

    public function contracts(): HasMany
	{
	    return $this->hasMany(
	        ProcurementContract::class,
	        'procurement_award_id'
	    );
	}

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(
            ProcurementPurchaseOrder::class,
            'procurement_award_id'
        );
    }
}