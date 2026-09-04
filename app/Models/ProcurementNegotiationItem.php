<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcurementNegotiationItem extends Model
{
    protected $table = 'procurement_negotiation_items';

    protected $fillable = [
        'procurement_negotiation_id',

        'round_number',
        'round_date',

        'bidder_amount',
        'negotiated_amount',
        'discount_amount',
        'final_amount',

        'currency',

        'negotiation_status',

        'bidder_comments',
        'evaluator_comments',
        'remarks',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'round_date' => 'date',

        'bidder_amount' => 'decimal:2',
        'negotiated_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',

        'round_number' => 'integer',
    ];

    public function negotiation(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementNegotiation::class,
            'procurement_negotiation_id'
        );
    }
}