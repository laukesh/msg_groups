<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcurementPurchaseOrder extends Model
{
    protected $table = 'procurement_purchase_orders';

    protected $fillable = [

        'procurement_award_id',
        'procurement_contract_id',
        'procurement_tender_id',
        'project_id',

        'po_number',
        'po_title',

        'po_date',
        'expected_delivery_date',

        'supplier_name',

        'currency',

        'subtotal_amount',
        'tax_amount',
        'discount_amount',
        'total_amount',

        'delivery_address',

        'payment_terms',
        'delivery_terms',
        'terms_and_conditions',

        'status',

        'submitted_by',
        'submitted_at',

        'approved_by',
        'approved_at',
        'approval_remarks',

        'issued_by',
        'issued_at',

        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'po_date' => 'date',

        'expected_delivery_date' => 'date',

        'subtotal_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',

        'submitted_at' => 'datetime',

        'approved_at' => 'datetime',

        'issued_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | Award
    |--------------------------------------------------------------------------
    */

    public function award(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementAward::class,
            'procurement_award_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Contract
    |--------------------------------------------------------------------------
    */

    public function contract(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementContract::class,
            'procurement_contract_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Tender
    |--------------------------------------------------------------------------
    */

    public function tender(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementTender::class,
            'procurement_tender_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Purchase Order Items
    |--------------------------------------------------------------------------
    */

    public function items(): HasMany
    {
        return $this->hasMany(
            ProcurementPurchaseOrderItem::class,
            'procurement_purchase_order_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Calculate Subtotal
    |--------------------------------------------------------------------------
    */

    public function calculateSubtotal(): float
    {
        return (float) $this->items->sum(
            fn ($item) => (float) $item->line_total
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Recalculate Totals
    |--------------------------------------------------------------------------
    */

    public function recalculateTotals(): void
    {
        $subtotal = 0;
        $tax = 0;
        $discount = 0;

        foreach ($this->items as $item) {

            $subtotal += (float) $item->quantity
                * (float) $item->unit_price;

            $tax += (float) $item->tax_amount;

            $discount += (float) $item->discount_amount;
        }


        $total = $subtotal
            + $tax
            - $discount;


        $this->update([

            'subtotal_amount' => $subtotal,

            'tax_amount' => $tax,

            'discount_amount' => $discount,

            'total_amount' => $total,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isDraft(): bool
    {
        return $this->status === 'Draft';
    }


    public function isSubmitted(): bool
    {
        return $this->status === 'Submitted';
    }


    public function isApproved(): bool
    {
        return $this->status === 'Approved';
    }


    public function isIssued(): bool
    {
        return $this->status === 'Issued';
    }

    /*
	|--------------------------------------------------------------------------
	| Deliveries
	|--------------------------------------------------------------------------
	*/

	public function deliveries(): HasMany
	{
	    return $this->hasMany(
	        ProcurementDelivery::class,
	        'procurement_purchase_order_id'
	    );
	}

	public function project()
	{
	    return $this->belongsTo(
	        Project::class,
	        'project_id'
	    );
	}

	public function materialTrackings()
	{
	    return $this->hasMany(
	        ProcurementMaterialTracking::class,
	        'procurement_purchase_order_id'
	    );
	}

    /*
    |--------------------------------------------------------------------------
    | Contract Invoices
    |--------------------------------------------------------------------------
    */

    public function invoices(): HasMany
    {
        return $this->hasMany(
            ProcurementContractInvoice::class,
            'procurement_contract_id',
            'procurement_contract_id'
        );
    }
}