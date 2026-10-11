<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProcurementDelivery;
use App\Models\ProcurementDeliveryItem;
use App\Models\ProcurementPurchaseOrder;
use App\Models\ProcurementPurchaseOrderItem;
use App\Models\ProcurementTender;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProcurementDeliveryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementPurchaseOrder $purchaseOrder
    ): View {

        $this->validatePurchaseOrderOwnership(
            $project,
            $procurementTender,
            $purchaseOrder
        );

        $purchaseOrder->load([
            'award',
            'contract',
        ]);

        $deliveries = ProcurementDelivery::query()
            ->where('project_id', $project->id)
            ->where(
                'procurement_purchase_order_id',
                $purchaseOrder->id
            )
            ->with([
                'items',
            ])
            ->latest('id')
            ->paginate(15);

        return view(
            'procurement.deliveries.index',
            compact(
                'project',
                'procurementTender',
                'purchaseOrder',
                'deliveries'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementPurchaseOrder $purchaseOrder
    ): View {

        $this->validatePurchaseOrderOwnership(
            $project,
            $procurementTender,
            $purchaseOrder
        );

        /*
        |--------------------------------------------------------------------------
        | Delivery can only be created for Issued PO
        |--------------------------------------------------------------------------
        */

        if ($purchaseOrder->status !== 'Issued') {
            abort(
                422,
                'Delivery can only be created against an Issued Purchase Order.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Load PO Items
        |--------------------------------------------------------------------------
        */

        $purchaseOrder->load([
            'items.deliveryItems',
            'award',
            'contract',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Calculate Outstanding Quantity
        |--------------------------------------------------------------------------
        */

        $items = $purchaseOrder->items->map(
            function ($item) {

                $previouslyDelivered =
                    (float) $item->deliveryItems->sum(
                        fn ($deliveryItem) =>
                            (float) $deliveryItem->delivered_quantity
                    );

                $orderedQuantity =
                    (float) $item->quantity;

                $pendingQuantity = max(
                    0,
                    $orderedQuantity - $previouslyDelivered
                );

                $item->previously_delivered_quantity =
                    $previouslyDelivered;

                $item->pending_quantity =
                    $pendingQuantity;

                return $item;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Only Items With Pending Quantity
        |--------------------------------------------------------------------------
        */

        $items = $items->filter(
            fn ($item) =>
                $item->pending_quantity > 0
        );


        return view(
            'procurement.deliveries.create',
            compact(
                'project',
                'procurementTender',
                'purchaseOrder',
                'items'
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
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementPurchaseOrder $purchaseOrder
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Validate Purchase Order Ownership
        |--------------------------------------------------------------------------
        */

        $this->validatePurchaseOrderOwnership(
            $project,
            $procurementTender,
            $purchaseOrder
        );


        /*
        |--------------------------------------------------------------------------
        | PO Must Be Issued
        |--------------------------------------------------------------------------
        */

        if ($purchaseOrder->status !== 'Issued') {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Delivery can only be recorded against an Issued Purchase Order.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'delivery_date' => [
                'required',
                'date',
            ],

            'challan_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'challan_date' => [
                'nullable',
                'date',
            ],

            'vehicle_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'transporter_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'delivery_address' => [
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

            'items.*.purchase_order_item_id' => [
                'required',
                'integer',
                'exists:procurement_purchase_order_items,id',
            ],

            'items.*.delivered_quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'items.*.accepted_quantity' => [
                'nullable',
                'numeric',
                'gte:0',
            ],

            'items.*.rejected_quantity' => [
                'nullable',
                'numeric',
                'gte:0',
            ],

            'items.*.remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Create Delivery
        |--------------------------------------------------------------------------
        */

        try {

            $delivery = DB::transaction(
                function () use (
                    $validated,
                    $project,
                    $procurementTender,
                    $purchaseOrder
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Generate Globally Unique Delivery Number
                    |--------------------------------------------------------------------------
                    |
                    | Database has:
                    |
                    | uk_delivery_number
                    |
                    | Therefore delivery_number must be unique across ALL projects.
                    |
                    */

                    $year = now()->format('Y');

                    $prefix = 'DEL-' . $year . '-';


                    /*
                    |--------------------------------------------------------------------------
                    | Find Latest Delivery Number Globally
                    |--------------------------------------------------------------------------
                    */

                    $latestDelivery = ProcurementDelivery::query()
                        ->where(
                            'delivery_number',
                            'like',
                            $prefix . '%'
                        )
                        ->orderByDesc('id')
                        ->lockForUpdate()
                        ->first();


                    /*
                    |--------------------------------------------------------------------------
                    | Determine Next Number
                    |--------------------------------------------------------------------------
                    */

                    if ($latestDelivery) {

                        $lastNumber = 0;

                        if (
                            preg_match(
                                '/^DEL-' . $year . '-(\d+)$/',
                                $latestDelivery->delivery_number,
                                $matches
                            )
                        ) {
                            $lastNumber = (int) $matches[1];
                        }

                        $nextNumber = $lastNumber + 1;

                    } else {

                        $nextNumber = 1;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Generate Delivery Number
                    |--------------------------------------------------------------------------
                    */

                    do {

                        $deliveryNumber =
                            $prefix .
                            str_pad(
                                $nextNumber,
                                6,
                                '0',
                                STR_PAD_LEFT
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | Check Globally
                        |--------------------------------------------------------------------------
                        */

                        $exists = ProcurementDelivery::query()
                            ->where(
                                'delivery_number',
                                $deliveryNumber
                            )
                            ->exists();


                        if ($exists) {
                            $nextNumber++;
                        }

                    } while ($exists);


                    /*
                    |--------------------------------------------------------------------------
                    | Create Delivery Header
                    |--------------------------------------------------------------------------
                    */

                    $delivery = ProcurementDelivery::create([

                        'project_id' =>
                            $project->id,

                        'procurement_purchase_order_id' =>
                            $purchaseOrder->id,

                        'procurement_tender_id' =>
                            $procurementTender->id,

                        'procurement_contract_id' =>
                            $purchaseOrder->procurement_contract_id,

                        'delivery_number' =>
                            $deliveryNumber,

                        'delivery_date' =>
                            $validated['delivery_date'],

                        'supplier_name' =>
                            $purchaseOrder->supplier_name,

                        'challan_number' =>
                            $validated['challan_number'] ?? null,

                        'challan_date' =>
                            $validated['challan_date'] ?? null,

                        'vehicle_number' =>
                            $validated['vehicle_number'] ?? null,

                        'transporter_name' =>
                            $validated['transporter_name'] ?? null,

                        'delivery_address' =>
                            $validated['delivery_address']
                            ?? $purchaseOrder->delivery_address,

                        'received_by' =>
                            auth()->id(),

                        'received_at' =>
                            now(),

                        'status' =>
                            'Received',

                        'remarks' =>
                            $validated['remarks'] ?? null,

                        'created_by' =>
                            auth()->id(),

                        'updated_by' =>
                            auth()->id(),
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | Create Delivery Items
                    |--------------------------------------------------------------------------
                    */

                    foreach (
                        $validated['items'] as $itemData
                    ) {

                        /*
                        |--------------------------------------------------------------------------
                        | Get PO Item
                        |--------------------------------------------------------------------------
                        */

                        $poItem =
                            ProcurementPurchaseOrderItem::query()
                                ->where(
                                    'id',
                                    $itemData['purchase_order_item_id']
                                )
                                ->where(
                                    'procurement_purchase_order_id',
                                    $purchaseOrder->id
                                )
                                ->with([
                                    'deliveryItems',
                                ])
                                ->first();


                        /*
                        |--------------------------------------------------------------------------
                        | Validate PO Item
                        |--------------------------------------------------------------------------
                        */

                        if (!$poItem) {

                            throw new \RuntimeException(
                                'Invalid Purchase Order item selected.'
                            );
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Ordered Quantity
                        |--------------------------------------------------------------------------
                        */

                        $orderedQuantity =
                            (float) $poItem->quantity;


                        /*
                        |--------------------------------------------------------------------------
                        | Previously Delivered
                        |--------------------------------------------------------------------------
                        */

                        $previouslyDelivered =
                            (float) $poItem
                                ->deliveryItems
                                ->sum(
                                    fn ($deliveryItem) =>
                                        (float)
                                        $deliveryItem
                                            ->delivered_quantity
                                );


                        /*
                        |--------------------------------------------------------------------------
                        | Current Delivery
                        |--------------------------------------------------------------------------
                        */

                        $deliveredQuantity =
                            (float)
                            $itemData['delivered_quantity'];


                        /*
                        |--------------------------------------------------------------------------
                        | Remaining Quantity
                        |--------------------------------------------------------------------------
                        */

                        $remainingQuantity =
                            max(
                                0,
                                $orderedQuantity
                                -
                                $previouslyDelivered
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | Prevent Over Delivery
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $deliveredQuantity
                            >
                            $remainingQuantity
                        ) {

                            throw new \RuntimeException(
                                'Delivery quantity for '
                                . $poItem->item_name
                                . ' cannot exceed the remaining quantity of '
                                . number_format(
                                    $remainingQuantity,
                                    3
                                )
                                . ' '
                                . $poItem->unit
                                . '.'
                            );
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Accepted Quantity
                        |--------------------------------------------------------------------------
                        */

                        $acceptedQuantity =
                            (float)
                            (
                                $itemData[
                                    'accepted_quantity'
                                ] ?? 0
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | Rejected Quantity
                        |--------------------------------------------------------------------------
                        */

                        $rejectedQuantity =
                            (float)
                            (
                                $itemData[
                                    'rejected_quantity'
                                ] ?? 0
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | Validate Accepted + Rejected
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $acceptedQuantity
                            +
                            $rejectedQuantity
                            >
                            $deliveredQuantity
                        ) {

                            throw new \RuntimeException(
                                'Accepted quantity plus rejected quantity '
                                . 'cannot exceed delivered quantity for '
                                . $poItem->item_name
                                . '.'
                            );
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Create Delivery Item
                        |--------------------------------------------------------------------------
                        */

                        ProcurementDeliveryItem::create([

                            'procurement_delivery_id' =>
                                $delivery->id,

                            'procurement_purchase_order_item_id' =>
                                $poItem->id,

                            'ordered_quantity' =>
                                $orderedQuantity,

                            'previously_delivered_quantity' =>
                                $previouslyDelivered,

                            'delivered_quantity' =>
                                $deliveredQuantity,

                            'accepted_quantity' =>
                                $acceptedQuantity,

                            'rejected_quantity' =>
                                $rejectedQuantity,

                            'unit' =>
                                $poItem->unit,

                            'remarks' =>
                                $itemData['remarks'] ?? null,

                            'created_by' =>
                                auth()->id(),

                            'updated_by' =>
                                auth()->id(),
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Return Delivery
                    |--------------------------------------------------------------------------
                    */

                    return $delivery;
                }
            );


            /*
            |--------------------------------------------------------------------------
            | Update PO Delivery Status
            |--------------------------------------------------------------------------
            */

            $this->updatePurchaseOrderDeliveryStatus(
                $purchaseOrder
            );


            /*
            |--------------------------------------------------------------------------
            | Redirect
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route(
                    'admin.projects.procurement.tenders.purchase-orders.deliveries.show',
                    [
                        'project' =>
                            $project,

                        'procurementTender' =>
                            $procurementTender,

                        'purchaseOrder' =>
                            $purchaseOrder,

                        'delivery' =>
                            $delivery,
                    ]
                )
                ->with(
                    'success',
                    'Delivery '
                    . $delivery->delivery_number
                    . ' recorded successfully.'
                );

        } catch (\RuntimeException $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementPurchaseOrder $purchaseOrder,
        ProcurementDelivery $delivery
    ): View {

        $this->validatePurchaseOrderOwnership(
            $project,
            $procurementTender,
            $purchaseOrder
        );


        /*
        |--------------------------------------------------------------------------
        | Validate Delivery Ownership
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $delivery->project_id === $project->id
            &&
            $delivery->procurement_purchase_order_id
                === $purchaseOrder->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Load Delivery
        |--------------------------------------------------------------------------
        */

        $delivery->load([
            'purchaseOrder',
            'contract',
            'tender',
            'items.purchaseOrderItem',
        ]);


        return view(
            'procurement.deliveries.show',
            compact(
                'project',
                'procurementTender',
                'purchaseOrder',
                'delivery'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update PO Delivery Status
    |--------------------------------------------------------------------------
    */

    private function updatePurchaseOrderDeliveryStatus(
        ProcurementPurchaseOrder $purchaseOrder
    ): void {

        $purchaseOrder->load([
            'items.deliveryItems',
        ]);


        $totalOrdered = 0;
        $totalDelivered = 0;


        foreach (
            $purchaseOrder->items as $item
        ) {

            $ordered =
                (float) $item->quantity;

            $delivered =
                (float)
                $item->deliveryItems->sum(
                    fn ($deliveryItem) =>
                        (float)
                        $deliveryItem->delivered_quantity
                );

            $totalOrdered += $ordered;

            $totalDelivered += $delivered;
        }


        if ($totalDelivered <= 0) {
            return;
        }


        if (
            $totalDelivered >= $totalOrdered
        ) {

            $purchaseOrder->update([

                'status' =>
                    'Fully Delivered',

                'updated_by' =>
                    auth()->id(),

            ]);

        } else {

            $purchaseOrder->update([

                'status' =>
                    'Partially Delivered',

                'updated_by' =>
                    auth()->id(),

            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Validate PO Ownership
    |--------------------------------------------------------------------------
    */

    private function validatePurchaseOrderOwnership(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementPurchaseOrder $purchaseOrder
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Tender must belong to Project
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $procurementTender->project_id === $project->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Purchase Order must belong to Project + Tender
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $purchaseOrder->project_id === $project->id
            &&
            $purchaseOrder->procurement_tender_id
                === $procurementTender->id,
            404
        );
    }
}