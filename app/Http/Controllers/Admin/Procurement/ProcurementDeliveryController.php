<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
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
        ProcurementTender $procurementTender,
        ProcurementPurchaseOrder $purchaseOrder
    ): View {

        $this->validatePurchaseOrderOwnership(
            $procurementTender,
            $purchaseOrder
        );

        $deliveries = ProcurementDelivery::query()
            ->with([
                'items',
            ])
            ->where(
                'procurement_purchase_order_id',
                $purchaseOrder->id
            )
            ->latest('id')
            ->paginate(15);

        return view(
            'procurement.deliveries.index',
            compact(
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
        ProcurementTender $procurementTender,
        ProcurementPurchaseOrder $purchaseOrder
    ): View {

        $this->validatePurchaseOrderOwnership(
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
                    (float) $item
                        ->deliveryItems
                        ->sum(
                            fn ($deliveryItem) =>
                                (float)
                                $deliveryItem
                                    ->delivered_quantity
                        );


                $orderedQuantity =
                    (float) $item->quantity;


                $pendingQuantity =
                    max(
                        0,
                        $orderedQuantity
                        -
                        $previouslyDelivered
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
        ProcurementTender $procurementTender,
        ProcurementPurchaseOrder $purchaseOrder
    ): RedirectResponse {

        $this->validatePurchaseOrderOwnership(
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

        $delivery = DB::transaction(
            function () use (
                $validated,
                $procurementTender,
                $purchaseOrder
            ) {

                /*
                |--------------------------------------------------------------------------
                | Generate Delivery Number
                |--------------------------------------------------------------------------
                */

                $year =
                    now()->format('Y');

                $prefix =
                    'DEL-' . $year . '-';


                $latestDelivery =
                    ProcurementDelivery::query()
                        ->where(
                            'delivery_number',
                            'like',
                            $prefix . '%'
                        )
                        ->orderByDesc('id')
                        ->lockForUpdate()
                        ->first();


                if ($latestDelivery) {

                    $lastNumber =
                        (int) str_replace(
                            $prefix,
                            '',
                            $latestDelivery
                                ->delivery_number
                        );

                    $nextNumber =
                        $lastNumber + 1;

                } else {

                    $nextNumber = 1;
                }


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
                | Extra Safety
                |--------------------------------------------------------------------------
                */

                while (
                    ProcurementDelivery::query()
                        ->where(
                            'delivery_number',
                            $deliveryNumber
                        )
                        ->exists()
                ) {

                    $nextNumber++;

                    $deliveryNumber =
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
                | Create Delivery Header
                |--------------------------------------------------------------------------
                */

                $delivery =
                    ProcurementDelivery::create([

                        'procurement_purchase_order_id' =>
                            $purchaseOrder->id,

                        'procurement_tender_id' =>
                            $procurementTender->id,

                        'procurement_contract_id' =>
                            $purchaseOrder
                                ->procurement_contract_id,

                        'delivery_number' =>
                            $deliveryNumber,

                        'delivery_date' =>
                            $validated[
                                'delivery_date'
                            ],

                        'supplier_name' =>
                            $purchaseOrder
                                ->supplier_name,

                        'challan_number' =>
                            $validated[
                                'challan_number'
                            ] ?? null,

                        'challan_date' =>
                            $validated[
                                'challan_date'
                            ] ?? null,

                        'vehicle_number' =>
                            $validated[
                                'vehicle_number'
                            ] ?? null,

                        'transporter_name' =>
                            $validated[
                                'transporter_name'
                            ] ?? null,

                        'delivery_address' =>
                            $validated[
                                'delivery_address'
                            ]
                            ??
                            $purchaseOrder
                                ->delivery_address,

                        'received_by' =>
                            auth()->id(),

                        'received_at' =>
                            now(),

                        'status' =>
                            'Received',

                        'remarks' =>
                            $validated[
                                'remarks'
                            ] ?? null,

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
                    $validated['items']
                    as $itemData
                ) {

                    $poItem =
                        ProcurementPurchaseOrderItem::query()
                            ->where(
                                'id',
                                $itemData[
                                    'purchase_order_item_id'
                                ]
                            )
                            ->where(
                                'procurement_purchase_order_id',
                                $purchaseOrder->id
                            )
                            ->with([
                                'deliveryItems',
                            ])
                            ->first();


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
                        (float)
                        $poItem
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
                        $itemData[
                            'delivered_quantity'
                        ];


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
                            . $poItem->item_name . '.'
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
                            $itemData[
                                'remarks'
                            ] ?? null,

                        'created_by' =>
                            auth()->id(),

                        'updated_by' =>
                            auth()->id(),
                    ]);
                }


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


        return redirect()
            ->route(
                'admin.procurement.tenders.purchase-orders.deliveries.show',
                [
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
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        ProcurementTender $procurementTender,
        ProcurementPurchaseOrder $purchaseOrder,
        ProcurementDelivery $delivery
    ): View {

        $this->validatePurchaseOrderOwnership(
            $procurementTender,
            $purchaseOrder
        );


        abort_unless(
            $delivery->procurement_purchase_order_id
                === $purchaseOrder->id,
            404
        );


        $delivery->load([
            'purchaseOrder',
            'contract',
            'tender',
            'items.purchaseOrderItem',
        ]);


        return view(
            'procurement.deliveries.show',
            compact(
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
            $purchaseOrder->items
            as $item
        ) {

            $ordered =
                (float) $item->quantity;


            $delivered =
                (float)
                $item
                    ->deliveryItems
                    ->sum(
                        fn ($deliveryItem) =>
                            (float)
                            $deliveryItem
                                ->delivered_quantity
                    );


            $totalOrdered +=
                $ordered;

            $totalDelivered +=
                $delivered;
        }


        if (
            $totalDelivered <= 0
        ) {

            return;
        }


        if (
            $totalDelivered
            >=
            $totalOrdered
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