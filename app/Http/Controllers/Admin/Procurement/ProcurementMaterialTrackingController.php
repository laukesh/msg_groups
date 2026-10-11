<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProcurementDeliveryItem;
use App\Models\ProcurementMaterialTracking;
use App\Models\ProcurementMaterialTrackingItem;
use App\Models\ProcurementPurchaseOrder;
use App\Models\ProcurementTender;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ProcurementMaterialTrackingController extends Controller
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
    ) {

        /*
        |--------------------------------------------------------------------------
        | Validate Project → Tender → Purchase Order
        |--------------------------------------------------------------------------
        */

        $this->validatePurchaseOrderOwnership(
            $project,
            $procurementTender,
            $purchaseOrder
        );


        /*
        |--------------------------------------------------------------------------
        | Get Material Trackings
        |--------------------------------------------------------------------------
        */

        $trackings = ProcurementMaterialTracking::with([
            'project',
            'items',
        ])
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            )
            ->where(
                'procurement_purchase_order_id',
                $purchaseOrder->id
            )
            ->latest('id')
            ->paginate(15);


        return view(
            'procurement.material-trackings.index',
            compact(
                'project',
                'procurementTender',
                'purchaseOrder',
                'trackings'
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
    ) {

        /*
        |--------------------------------------------------------------------------
        | Validate Project → Tender → Purchase Order
        |--------------------------------------------------------------------------
        */

        $this->validatePurchaseOrderOwnership(
            $project,
            $procurementTender,
            $purchaseOrder
        );


        /*
        |--------------------------------------------------------------------------
        | Project is Required
        |--------------------------------------------------------------------------
        */

        if (!$purchaseOrder->project_id) {

            return redirect()
                ->route(
                    'admin.projects.procurement.tenders.purchase-orders.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                        'purchaseOrder' => $purchaseOrder,
                    ]
                )
                ->with(
                    'error',
                    'This Purchase Order is not linked to a Project.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Load PO + Deliveries + Existing Tracking
        |--------------------------------------------------------------------------
        */

        $purchaseOrder->load([
            'project',
            'items',
            'deliveries.items.purchaseOrderItem',
            'deliveries.items.materialTrackingItems',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Prepare Available Delivery Items
        |--------------------------------------------------------------------------
        */

        $deliveryItems = collect();


        foreach ($purchaseOrder->deliveries as $delivery) {

            /*
            |--------------------------------------------------------------------------
            | Only use deliveries belonging to this project
            |--------------------------------------------------------------------------
            */

            if (
                (int) $delivery->project_id
                !==
                (int) $project->id
            ) {
                continue;
            }


            foreach ($delivery->items as $deliveryItem) {

                $acceptedQuantity =
                    (float) $deliveryItem->accepted_quantity;


                $alreadyTrackedQuantity =
                    (float) $deliveryItem
                        ->materialTrackingItems
                        ->sum('accepted_quantity');


                $remainingQuantity =
                    max(
                        0,
                        $acceptedQuantity
                        -
                        $alreadyTrackedQuantity
                    );


                /*
                |--------------------------------------------------------------------------
                | Only Show Material With Remaining Quantity
                |--------------------------------------------------------------------------
                */

                if ($remainingQuantity > 0) {

                    $deliveryItem->tracking_accepted_quantity =
                        $acceptedQuantity;

                    $deliveryItem->already_tracked_quantity =
                        $alreadyTrackedQuantity;

                    $deliveryItem->remaining_tracking_quantity =
                        $remainingQuantity;

                    $deliveryItems->push(
                        $deliveryItem
                    );
                }
            }
        }


        return view(
            'procurement.material-trackings.create',
            compact(
                'project',
                'procurementTender',
                'purchaseOrder',
                'deliveryItems'
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
    ) {

        /*
        |--------------------------------------------------------------------------
        | Validate Project → Tender → Purchase Order
        |--------------------------------------------------------------------------
        */

        $this->validatePurchaseOrderOwnership(
            $project,
            $procurementTender,
            $purchaseOrder
        );


        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'tracking_date' => [
                'required',
                'date',
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

            'items.*.delivery_item_id' => [
                'required',
                'integer',
            ],

            'items.*.issued_quantity' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'items.*.consumed_quantity' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'items.*.remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Project is Mandatory
        |--------------------------------------------------------------------------
        */

        if (!$purchaseOrder->project_id) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Purchase Order is not linked to a Project.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Begin Transaction
        |--------------------------------------------------------------------------
        */

        DB::beginTransaction();


        try {

            /*
            |--------------------------------------------------------------------------
            | Generate Tracking Number
            |--------------------------------------------------------------------------
            */

            $trackingNumber =
                $this->generateTrackingNumber();


            /*
            |--------------------------------------------------------------------------
            | Prepare Tracking Items
            |--------------------------------------------------------------------------
            */

            $trackingItems = [];


            foreach ($validated['items'] as $item) {

                /*
                |--------------------------------------------------------------------------
                | Get Delivery Item
                |--------------------------------------------------------------------------
                */

                $deliveryItem =
                    ProcurementDeliveryItem::with([
                        'purchaseOrderItem',
                        'delivery',
                        'materialTrackingItems',
                    ])
                    ->findOrFail(
                        $item['delivery_item_id']
                    );


                /*
                |--------------------------------------------------------------------------
                | Verify Delivery Exists
                |--------------------------------------------------------------------------
                */

                if (!$deliveryItem->delivery) {

                    throw new \Exception(
                        'Invalid delivery item.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Verify Delivery Belongs to PO
                |--------------------------------------------------------------------------
                */

                if (
                    (int) $deliveryItem
                        ->delivery
                        ->procurement_purchase_order_id
                    !==
                    (int) $purchaseOrder->id
                ) {

                    throw new \Exception(
                        'Delivery item does not belong to this Purchase Order.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Verify Delivery Belongs to Project
                |--------------------------------------------------------------------------
                */

                if (
                    (int) $deliveryItem
                        ->delivery
                        ->project_id
                    !==
                    (int) $project->id
                ) {

                    throw new \Exception(
                        'Delivery item does not belong to this Project.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Accepted Quantity
                |--------------------------------------------------------------------------
                */

                $acceptedQuantity =
                    (float) $deliveryItem->accepted_quantity;


                /*
                |--------------------------------------------------------------------------
                | Already Tracked Quantity
                |--------------------------------------------------------------------------
                */

                $alreadyTrackedQuantity =
                    (float) $deliveryItem
                        ->materialTrackingItems
                        ->sum('accepted_quantity');


                /*
                |--------------------------------------------------------------------------
                | Remaining Quantity
                |--------------------------------------------------------------------------
                */

                $remainingQuantity =
                    max(
                        0,
                        $acceptedQuantity
                        -
                        $alreadyTrackedQuantity
                    );


                /*
                |--------------------------------------------------------------------------
                | Prevent Duplicate Tracking
                |--------------------------------------------------------------------------
                */

                if ($remainingQuantity <= 0) {

                    throw new \Exception(
                        'Material from delivery item "' .
                        (
                            $deliveryItem
                                ->purchaseOrderItem
                                ?->item_name
                            ?? 'Unknown'
                        ) .
                        '" has already been completely tracked.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Issued Quantity
                |--------------------------------------------------------------------------
                */

                $issuedQuantity =
                    (float) (
                        $item['issued_quantity']
                        ?? 0
                    );


                /*
                |--------------------------------------------------------------------------
                | Consumed Quantity
                |--------------------------------------------------------------------------
                */

                $consumedQuantity =
                    (float) (
                        $item['consumed_quantity']
                        ?? 0
                    );


                /*
                |--------------------------------------------------------------------------
                | Validate Issued Quantity
                |--------------------------------------------------------------------------
                */

                if (
                    $issuedQuantity >
                    $remainingQuantity
                ) {

                    throw new \Exception(
                        'Issued quantity cannot exceed the remaining accepted quantity for "' .
                        (
                            $deliveryItem
                                ->purchaseOrderItem
                                ?->item_name
                            ?? 'Unknown'
                        ) .
                        '". Available: ' .
                        number_format(
                            $remainingQuantity,
                            3
                        )
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Validate Consumed Quantity
                |--------------------------------------------------------------------------
                */

                if (
                    $consumedQuantity >
                    $issuedQuantity
                ) {

                    throw new \Exception(
                        'Consumed quantity cannot exceed issued quantity for "' .
                        (
                            $deliveryItem
                                ->purchaseOrderItem
                                ?->item_name
                            ?? 'Unknown'
                        ) .
                        '".'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Balance
                |--------------------------------------------------------------------------
                */

                $balanceQuantity =
                    max(
                        0,
                        $remainingQuantity
                        -
                        $issuedQuantity
                    );


                /*
                |--------------------------------------------------------------------------
                | Determine Item Status
                |--------------------------------------------------------------------------
                */

                if (
                    $consumedQuantity > 0
                    &&
                    $balanceQuantity <= 0
                ) {

                    $itemStatus = 'Consumed';

                } elseif (
                    $issuedQuantity > 0
                    &&
                    $balanceQuantity <= 0
                ) {

                    $itemStatus = 'Issued';

                } elseif (
                    $issuedQuantity > 0
                ) {

                    $itemStatus = 'Partially Issued';

                } else {

                    $itemStatus = 'Available';
                }


                /*
                |--------------------------------------------------------------------------
                | Purchase Order Item
                |--------------------------------------------------------------------------
                */

                $purchaseOrderItem =
                    $deliveryItem->purchaseOrderItem;


                if (!$purchaseOrderItem) {

                    throw new \Exception(
                        'Purchase Order Item not found.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Store Prepared Item
                |--------------------------------------------------------------------------
                */

                $trackingItems[] = [

                    'deliveryItem' =>
                        $deliveryItem,

                    'purchaseOrderItem' =>
                        $purchaseOrderItem,

                    'acceptedQuantity' =>
                        $remainingQuantity,

                    'alreadyTrackedQuantity' =>
                        (float) $alreadyTrackedQuantity,

                    'issuedQuantity' =>
                        $issuedQuantity,

                    'consumedQuantity' =>
                        $consumedQuantity,

                    'balanceQuantity' =>
                        $balanceQuantity,

                    'status' =>
                        $itemStatus,

                    'remarks' =>
                        $item['remarks'] ?? null,
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | Make Sure At Least One Item Was Prepared
            |--------------------------------------------------------------------------
            */

            if (empty($trackingItems)) {

                throw new \Exception(
                    'No valid material was selected for tracking.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Determine Overall Tracking Status
            |--------------------------------------------------------------------------
            */

            $statuses =
                collect($trackingItems)
                    ->pluck('status');


            if (
                $statuses->every(
                    fn ($status) =>
                        $status === 'Consumed'
                )
            ) {

                $trackingStatus = 'Consumed';

            } elseif (
                $statuses->every(
                    fn ($status) =>
                        $status === 'Issued'
                )
            ) {

                $trackingStatus = 'Issued';

            } elseif (
                $statuses->contains('Issued')
                ||
                $statuses->contains('Partially Issued')
            ) {

                $trackingStatus = 'Partially Issued';

            } else {

                $trackingStatus = 'Available';
            }


            /*
            |--------------------------------------------------------------------------
            | Create Tracking Header
            |--------------------------------------------------------------------------
            */

            $tracking =
                ProcurementMaterialTracking::create([

                    'project_id' =>
                        $project->id,

                    'procurement_purchase_order_id' =>
                        $purchaseOrder->id,

                    'procurement_tender_id' =>
                        $procurementTender->id,

                    'procurement_contract_id' =>
                        $purchaseOrder->procurement_contract_id,

                    'tracking_number' =>
                        $trackingNumber,

                    'tracking_date' =>
                        $validated['tracking_date'],

                    'status' =>
                        $trackingStatus,

                    'remarks' =>
                        $validated['remarks'] ?? null,

                    'created_by' =>
                        auth()->id(),

                    'updated_by' =>
                        auth()->id(),
                ]);


            /*
            |--------------------------------------------------------------------------
            | Create Tracking Items
            |--------------------------------------------------------------------------
            */

            foreach ($trackingItems as $trackingItem) {

                $deliveryItem =
                    $trackingItem['deliveryItem'];

                $purchaseOrderItem =
                    $trackingItem['purchaseOrderItem'];


                ProcurementMaterialTrackingItem::create([

                    'procurement_material_tracking_id' =>
                        $tracking->id,

                    'procurement_purchase_order_item_id' =>
                        $purchaseOrderItem->id,

                    'procurement_delivery_item_id' =>
                        $deliveryItem->id,

                    'material_name' =>
                        $purchaseOrderItem->item_name,

                    'item_code' =>
                        $purchaseOrderItem->item_code,

                    'unit' =>
                        $deliveryItem->unit,

                    'ordered_quantity' =>
                        $deliveryItem->ordered_quantity,

                    'received_quantity' =>
                        $deliveryItem->delivered_quantity,

                    'accepted_quantity' =>
                        $trackingItem['acceptedQuantity'],

                    'rejected_quantity' =>
                        $deliveryItem->rejected_quantity,

                    'issued_quantity' =>
                        $trackingItem['issuedQuantity'],

                    'consumed_quantity' =>
                        $trackingItem['consumedQuantity'],

                    'balance_quantity' =>
                        $trackingItem['balanceQuantity'],

                    'remarks' =>
                        $trackingItem['remarks'],

                    'created_by' =>
                        auth()->id(),

                    'updated_by' =>
                        auth()->id(),
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Commit
            |--------------------------------------------------------------------------
            */

            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | Redirect
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route(
                    'admin.projects.procurement.tenders.purchase-orders.material-trackings.show',
                    [
                        'project' =>
                            $project,

                        'procurementTender' =>
                            $procurementTender,

                        'purchaseOrder' =>
                            $purchaseOrder,

                        'materialTracking' =>
                            $tracking,
                    ]
                )
                ->with(
                    'success',
                    'Material Tracking created successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

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
        ProcurementMaterialTracking $materialTracking
    ) {

        /*
        |--------------------------------------------------------------------------
        | Validate Project → Tender → PO
        |--------------------------------------------------------------------------
        */

        $this->validatePurchaseOrderOwnership(
            $project,
            $procurementTender,
            $purchaseOrder
        );


        /*
        |--------------------------------------------------------------------------
        | Validate Tracking Project
        |--------------------------------------------------------------------------
        */

        if (
            (int) $materialTracking->project_id
            !==
            (int) $project->id
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Tracking Purchase Order
        |--------------------------------------------------------------------------
        */

        if (
            (int) $materialTracking
                ->procurement_purchase_order_id
            !==
            (int) $purchaseOrder->id
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Tracking Tender
        |--------------------------------------------------------------------------
        */

        if (
            (int) $materialTracking
                ->procurement_tender_id
            !==
            (int) $procurementTender->id
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | Load Relationships
        |--------------------------------------------------------------------------
        */

        $materialTracking->load([
            'project',
            'purchaseOrder',
            'items.purchaseOrderItem',
            'items.deliveryItem.delivery',
        ]);


        return view(
            'procurement.material-trackings.show',
            compact(
                'project',
                'procurementTender',
                'purchaseOrder',
                'materialTracking'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE PURCHASE ORDER OWNERSHIP
    |--------------------------------------------------------------------------
    */

    private function validatePurchaseOrderOwnership(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementPurchaseOrder $purchaseOrder
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Tender Must Belong to Project
        |--------------------------------------------------------------------------
        */

        if (
            isset($procurementTender->project_id)
            &&
            (int) $procurementTender->project_id
            !==
            (int) $project->id
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | Purchase Order Must Belong to Tender
        |--------------------------------------------------------------------------
        */

        if (
            (int) $purchaseOrder->procurement_tender_id
            !==
            (int) $procurementTender->id
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | Purchase Order Must Belong to Project
        |--------------------------------------------------------------------------
        */

        if (
            (int) $purchaseOrder->project_id
            !==
            (int) $project->id
        ) {

            abort(404);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE TRACKING NUMBER
    |--------------------------------------------------------------------------
    */

    private function generateTrackingNumber(): string
    {
        $year = now()->format('Y');

        $prefix = 'MAT-' . $year . '-';


        /*
        |--------------------------------------------------------------------------
        | Find Latest Tracking Globally
        |--------------------------------------------------------------------------
        |
        | tracking_number should remain globally unique.
        |
        */

        $last =
            ProcurementMaterialTracking::query()
                ->where(
                    'tracking_number',
                    'like',
                    $prefix . '%'
                )
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();


        $number = 1;


        if (
            $last
            &&
            $last->tracking_number
        ) {

            if (
                preg_match(
                    '/^MAT-' . $year . '-(\d+)$/',
                    $last->tracking_number,
                    $matches
                )
            ) {

                $number =
                    ((int) $matches[1]) + 1;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Extra Safety
        |--------------------------------------------------------------------------
        */

        do {

            $trackingNumber =
                $prefix .
                str_pad(
                    $number,
                    6,
                    '0',
                    STR_PAD_LEFT
                );


            $exists =
                ProcurementMaterialTracking::query()
                    ->where(
                        'tracking_number',
                        $trackingNumber
                    )
                    ->exists();


            if ($exists) {
                $number++;
            }

        } while ($exists);


        return $trackingNumber;
    }
}