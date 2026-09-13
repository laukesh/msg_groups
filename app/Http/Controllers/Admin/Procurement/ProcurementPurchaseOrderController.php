<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementAward;
use App\Models\ProcurementPurchaseOrder;
use App\Models\ProcurementPurchaseOrderItem;
use App\Models\ProcurementTender;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProcurementPurchaseOrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        ProcurementTender $procurementTender
    ): View {

        $purchaseOrders =
            ProcurementPurchaseOrder::query()
                ->with([
                    'award',
                    'contract',
                    'items',
                ])
                ->where(
                    'procurement_tender_id',
                    $procurementTender->id
                )
                ->latest('id')
                ->paginate(15);

        return view(
            'procurement.purchase-orders.index',
            compact(
                'procurementTender',
                'purchaseOrders'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
	    ProcurementTender $procurementTender
	): View {

	    /*
	    |--------------------------------------------------------------------------
	    | Eligible Awards
	    |--------------------------------------------------------------------------
	    |
	    | Purchase Order can be created after the Award has reached
	    | LOA Issued stage.
	    |
	    */

	    $awards = ProcurementAward::query()
	        ->where(
	            'procurement_tender_id',
	            $procurementTender->id
	        )
	        ->where(
	            'status',
	            'LOA Issued'
	        )
	        ->with([
	            'tender',
	        ])
	        ->latest('id')
	        ->get();


	    /*
	    |--------------------------------------------------------------------------
	    | Existing Contracts
	    |--------------------------------------------------------------------------
	    */

	    $contracts = \App\Models\ProcurementContract::query()
	        ->where(
	            'procurement_tender_id',
	            $procurementTender->id
	        )
	        ->orderByDesc('id')
	        ->get();


	    return view(
	        'procurement.purchase-orders.create',
	        compact(
	            'procurementTender',
	            'awards',
	            'contracts'
	        )
	    );
	}


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
	    Request $request,
	    ProcurementTender $procurementTender
	): RedirectResponse {

		$procurementTender->load([
		    'procurementPackage.procurementPlan'
		]);

		$projectId = $procurementTender->procurementPackage->procurementPlan->project_id;
		//dd($projectId);
		if (!$projectId) {
		    return back()
		        ->withInput()
		        ->with('error', 'Unable to determine the Project linked to this procurement.');
		}

	    $validated = $request->validate([

	        'procurement_award_id' => [
	            'required',
	            'integer',
	            'exists:procurement_awards,id',
	        ],

	        'procurement_contract_id' => [
	            'nullable',
	            'integer',
	            'exists:procurement_contracts,id',
	        ],

	        'po_title' => [
	            'required',
	            'string',
	            'max:255',
	        ],

	        'po_date' => [
	            'nullable',
	            'date',
	        ],

	        'expected_delivery_date' => [
	            'nullable',
	            'date',
	        ],

	        'supplier_name' => [
	            'nullable',
	            'string',
	            'max:255',
	        ],

	        'currency' => [
	            'required',
	            'string',
	            'max:10',
	        ],

	        'delivery_address' => [
	            'nullable',
	            'string',
	        ],

	        'payment_terms' => [
	            'nullable',
	            'string',
	        ],

	        'delivery_terms' => [
	            'nullable',
	            'string',
	        ],

	        'terms_and_conditions' => [
	            'nullable',
	            'string',
	        ],

	        'remarks' => [
	            'nullable',
	            'string',
	        ],

	        'items' => [
	            'required',
	            'array',
	            'min:1',
	        ],

	        'items.*.item_code' => [
	            'nullable',
	            'string',
	            'max:100',
	        ],

	        'items.*.item_name' => [
	            'required',
	            'string',
	            'max:255',
	        ],

	        'items.*.description' => [
	            'nullable',
	            'string',
	        ],

	        'items.*.quantity' => [
	            'required',
	            'numeric',
	            'gt:0',
	        ],

	        'items.*.unit' => [
	            'required',
	            'string',
	            'max:50',
	        ],

	        'items.*.unit_price' => [
	            'required',
	            'numeric',
	            'gte:0',
	        ],

	        'items.*.tax_percentage' => [
	            'nullable',
	            'numeric',
	            'gte:0',
	        ],

	        'items.*.discount_amount' => [
	            'nullable',
	            'numeric',
	            'gte:0',
	        ],

	        'items.*.required_delivery_date' => [
	            'nullable',
	            'date',
	        ],

	        'items.*.remarks' => [
	            'nullable',
	            'string',
	        ],
	    ]);


	    /*
	    |--------------------------------------------------------------------------
	    | Validate Award
	    |--------------------------------------------------------------------------
	    */

	    $award = ProcurementAward::query()
	        ->where(
	            'id',
	            $validated['procurement_award_id']
	        )
	        ->where(
	            'procurement_tender_id',
	            $procurementTender->id
	        )
	        ->where(
	            'status',
	            'LOA Issued'
	        )
	        ->first();


	    if (!$award) {

	        return back()
	            ->withInput()
	            ->with(
	                'error',
	                'Invalid or ineligible procurement award. The award must be at LOA Issued stage.'
	            );
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Contract Required Validation
	    |--------------------------------------------------------------------------
	    */

	    if ($award->contract_required) {

	        if (
	            empty(
	                $validated['procurement_contract_id']
	            )
	        ) {

	            return back()
	                ->withInput()
	                ->with(
	                    'error',
	                    'A contract is required for this award before creating the Purchase Order.'
	                );
	        }
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Validate Contract
	    |--------------------------------------------------------------------------
	    */

	    if (
	        !empty(
	            $validated['procurement_contract_id']
	        )
	    ) {

	        $contractExists =
	            DB::table('procurement_contracts')
	                ->where(
	                    'id',
	                    $validated['procurement_contract_id']
	                )
	                ->where(
	                    'procurement_tender_id',
	                    $procurementTender->id
	                )
	                ->where(
	                    'procurement_award_id',
	                    $award->id
	                )
	                ->exists();


	        if (!$contractExists) {

	            return back()
	                ->withInput()
	                ->with(
	                    'error',
	                    'Selected contract does not belong to this award.'
	                );
	        }
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Create Purchase Order
	    |--------------------------------------------------------------------------
	    */

	    $purchaseOrder = DB::transaction(
		    function () use (
		        $validated,
		        $procurementTender,
		        $award,
		        $projectId
		    ) {

	            /*
	            |--------------------------------------------------------------------------
	            | Generate Unique PO Number
	            |--------------------------------------------------------------------------
	            */

	            $year = now()->format('Y');

	            $prefix = 'PO-' . $year . '-';


	            /*
	            |--------------------------------------------------------------------------
	            | Get Latest PO Number For Current Year
	            |--------------------------------------------------------------------------
	            */

	            $latestPO = ProcurementPurchaseOrder::query()
	                ->where(
	                    'po_number',
	                    'like',
	                    $prefix . '%'
	                )
	                ->orderByDesc('id')
	                ->lockForUpdate()
	                ->first();


	            if ($latestPO) {

	                $lastNumber = (int) str_replace(
	                    $prefix,
	                    '',
	                    $latestPO->po_number
	                );

	                $nextNumber =
	                    $lastNumber + 1;

	            } else {

	                $nextNumber = 1;
	            }


	            $poNumber =
	                $prefix .
	                str_pad(
	                    $nextNumber,
	                    6,
	                    '0',
	                    STR_PAD_LEFT
	                );


	            /*
	            |--------------------------------------------------------------------------
	            | Extra Safety Check
	            |--------------------------------------------------------------------------
	            */

	            while (
	                ProcurementPurchaseOrder::query()
	                    ->where(
	                        'po_number',
	                        $poNumber
	                    )
	                    ->exists()
	            ) {

	                $nextNumber++;

	                $poNumber =
	                    $prefix .
	                    str_pad(
	                        $nextNumber,
	                        6,
	                        '0',
	                        STR_PAD_LEFT
	                    );
	            }


	            /*
	            |--------------------------------------------------------------------------
	            | Create PO Header
	            |--------------------------------------------------------------------------
	            */

	            $purchaseOrder =
	                ProcurementPurchaseOrder::create([

	                    'procurement_award_id' =>
	                        $award->id,
	                    'project_id' => $projectId,

	                    'procurement_contract_id' =>
	                        $validated[
	                            'procurement_contract_id'
	                        ] ?? null,

	                    'procurement_tender_id' =>
	                        $procurementTender->id,

	                    'po_number' =>
	                        $poNumber,

	                    'po_title' =>
	                        $validated['po_title'],

	                    'po_date' =>
	                        $validated['po_date']
	                        ?? now()->format('Y-m-d'),

	                    'expected_delivery_date' =>
	                        $validated[
	                            'expected_delivery_date'
	                        ] ?? null,

	                    'supplier_name' =>
	                        $validated['supplier_name']
	                        ?? $award->bidder_name,

	                    'currency' =>
	                        $validated['currency'],

	                    'delivery_address' =>
	                        $validated['delivery_address']
	                        ?? null,

	                    'payment_terms' =>
	                        $validated['payment_terms']
	                        ?? null,

	                    'delivery_terms' =>
	                        $validated['delivery_terms']
	                        ?? null,

	                    'terms_and_conditions' =>
	                        $validated[
	                            'terms_and_conditions'
	                        ] ?? null,

	                    'status' =>
	                        'Draft',

	                    'remarks' =>
	                        $validated['remarks']
	                        ?? null,

	                    'created_by' =>
	                        auth()->id(),

	                    'updated_by' =>
	                        auth()->id(),
	                ]);


	            /*
	            |--------------------------------------------------------------------------
	            | Create PO Items
	            |--------------------------------------------------------------------------
	            */

	            foreach (
	                $validated['items']
	                as $itemData
	            ) {

	                $quantity =
	                    (float) $itemData['quantity'];

	                $unitPrice =
	                    (float) $itemData['unit_price'];

	                $taxPercentage =
	                    (float) (
	                        $itemData['tax_percentage']
	                        ?? 0
	                    );

	                $discountAmount =
	                    (float) (
	                        $itemData['discount_amount']
	                        ?? 0
	                    );


	                $baseAmount =
	                    $quantity * $unitPrice;


	                $taxAmount =
	                    $baseAmount *
	                    ($taxPercentage / 100);


	                $lineTotal =
	                    $baseAmount
	                    + $taxAmount
	                    - $discountAmount;


	                ProcurementPurchaseOrderItem::create([

	                    'procurement_purchase_order_id' =>
	                        $purchaseOrder->id,

	                    'item_code' =>
	                        $itemData['item_code']
	                        ?? null,

	                    'item_name' =>
	                        $itemData['item_name'],

	                    'description' =>
	                        $itemData['description']
	                        ?? null,

	                    'quantity' =>
	                        $quantity,

	                    'unit' =>
	                        $itemData['unit'],

	                    'unit_price' =>
	                        $unitPrice,

	                    'tax_percentage' =>
	                        $taxPercentage,

	                    'tax_amount' =>
	                        $taxAmount,

	                    'discount_amount' =>
	                        $discountAmount,

	                    'line_total' =>
	                        $lineTotal,

	                    'required_delivery_date' =>
	                        $itemData[
	                            'required_delivery_date'
	                        ] ?? null,

	                    'remarks' =>
	                        $itemData['remarks']
	                        ?? null,

	                    'created_by' =>
	                        auth()->id(),

	                    'updated_by' =>
	                        auth()->id(),
	                ]);
	            }


	            /*
	            |--------------------------------------------------------------------------
	            | Calculate PO Totals
	            |--------------------------------------------------------------------------
	            */

	            $items =
	                $purchaseOrder
	                    ->items()
	                    ->get();


	            $subtotal =
	                $items->sum(
	                    fn ($item) =>
	                        (float) $item->quantity
	                        * (float) $item->unit_price
	                );


	            $taxAmount =
	                $items->sum(
	                    fn ($item) =>
	                        (float) $item->tax_amount
	                );


	            $discountAmount =
	                $items->sum(
	                    fn ($item) =>
	                        (float) $item->discount_amount
	                );


	            $totalAmount =
	                $subtotal
	                + $taxAmount
	                - $discountAmount;


	            $purchaseOrder->update([

	                'subtotal_amount' =>
	                    $subtotal,

	                'tax_amount' =>
	                    $taxAmount,

	                'discount_amount' =>
	                    $discountAmount,

	                'total_amount' =>
	                    $totalAmount,
	            ]);


	            return $purchaseOrder;
	        }
	    );


	    return redirect()
	        ->route(
	            'admin.procurement.tenders.purchase-orders.show',
	            [
	                'procurementTender' =>
	                    $procurementTender,

	                'purchaseOrder' =>
	                    $purchaseOrder,
	            ]
	        )
	        ->with(
	            'success',
	            'Purchase Order '
	            . $purchaseOrder->po_number
	            . ' created successfully.'
	        );
	}


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        ProcurementTender $procurementTender,
        ProcurementPurchaseOrder $purchaseOrder
    ): View {

        abort_unless(
            $purchaseOrder->procurement_tender_id
                === $procurementTender->id,
            404
        );


        $purchaseOrder->load([
            'award',
            'contract',
            'tender',
            'items',
        ]);


        return view(
            'procurement.purchase-orders.show',
            compact(
                'procurementTender',
                'purchaseOrder'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    public function submit(
        ProcurementTender $procurementTender,
        ProcurementPurchaseOrder $purchaseOrder
    ): RedirectResponse {

        $this->validatePurchaseOrderOwnership(
            $procurementTender,
            $purchaseOrder
        );


        if ($purchaseOrder->status !== 'Draft') {

            return back()->with(
                'error',
                'Only Draft Purchase Orders can be submitted.'
            );
        }


        if ($purchaseOrder->items()->count() === 0) {

            return back()->with(
                'error',
                'Purchase Order must contain at least one item.'
            );
        }


        $purchaseOrder->update([

            'status' =>
                'Submitted',

            'submitted_by' =>
                auth()->id(),

            'submitted_at' =>
                now(),

            'updated_by' =>
                auth()->id(),

        ]);


        return back()->with(
            'success',
            'Purchase Order submitted for approval.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    public function approve(
        Request $request,
        ProcurementTender $procurementTender,
        ProcurementPurchaseOrder $purchaseOrder
    ): RedirectResponse {

        $this->validatePurchaseOrderOwnership(
            $procurementTender,
            $purchaseOrder
        );


        if ($purchaseOrder->status !== 'Submitted') {

            return back()->with(
                'error',
                'Only Submitted Purchase Orders can be approved.'
            );
        }


        $validated = $request->validate([

            'approval_remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $purchaseOrder->update([

            'status' =>
                'Approved',

            'approved_by' =>
                auth()->id(),

            'approved_at' =>
                now(),

            'approval_remarks' =>
                $validated['approval_remarks']
                ?? null,

            'updated_by' =>
                auth()->id(),

        ]);


        return back()->with(
            'success',
            'Purchase Order approved successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ISSUE
    |--------------------------------------------------------------------------
    */

    public function issue(
        ProcurementTender $procurementTender,
        ProcurementPurchaseOrder $purchaseOrder
    ): RedirectResponse {

        $this->validatePurchaseOrderOwnership(
            $procurementTender,
            $purchaseOrder
        );


        if ($purchaseOrder->status !== 'Approved') {

            return back()->with(
                'error',
                'Only Approved Purchase Orders can be issued.'
            );
        }


        $purchaseOrder->update([

            'status' =>
                'Issued',

            'issued_by' =>
                auth()->id(),

            'issued_at' =>
                now(),

            'updated_by' =>
                auth()->id(),

        ]);


        return back()->with(
            'success',
            'Purchase Order issued successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        ProcurementTender $procurementTender,
        ProcurementPurchaseOrder $purchaseOrder
    ): RedirectResponse {

        $this->validatePurchaseOrderOwnership(
            $procurementTender,
            $purchaseOrder
        );


        if ($purchaseOrder->status !== 'Draft') {

            return back()->with(
                'error',
                'Only Draft Purchase Orders can be deleted.'
            );
        }


        $purchaseOrder->delete();


        return redirect()
            ->route(
                'admin.procurement.tenders.purchase-orders.index',
                $procurementTender
            )
            ->with(
                'success',
                'Purchase Order deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Ownership Validation
    |--------------------------------------------------------------------------
    */

    private function validatePurchaseOrderOwnership(
        ProcurementTender $procurementTender,
        ProcurementPurchaseOrder $purchaseOrder
    ): void {

        abort_unless(
            $purchaseOrder->procurement_tender_id
                === $procurementTender->id,
            404
        );
    }
}