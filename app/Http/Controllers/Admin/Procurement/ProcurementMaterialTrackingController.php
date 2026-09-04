<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementMaterialTracking;
use App\Models\ProcurementMaterialTrackingItem;
use App\Models\ProcurementPurchaseOrder;
use App\Models\ProcurementTender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcurementMaterialTrackingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        ProcurementTender $procurementTender,
        ProcurementPurchaseOrder $purchaseOrder
    ) {
        $trackings = ProcurementMaterialTracking::with([
            'project',
            'items',
        ])
            ->where(
                'procurement_purchase_order_id',
                $purchaseOrder->id
            )
            ->latest('id')
            ->paginate(15);

        return view(
            'procurement.material-trackings.index',
            compact(
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
        ProcurementTender $procurementTender,
        ProcurementPurchaseOrder $purchaseOrder
    ) {
        /*
        |--------------------------------------------------------------------------
        | Project is required for Material Tracking
        |--------------------------------------------------------------------------
        */

        if (!$purchaseOrder->project_id) {
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
                    'error',
                    'This Purchase Order is not linked to a Project.'
                );
        }


        /*
		|--------------------------------------------------------------------------
		| Load PO + Delivery + Existing Material Tracking
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
		        | Only show material having remaining quantity
		        |--------------------------------------------------------------------------
		        */

		        if ($remainingQuantity > 0) {

		            /*
		            |--------------------------------------------------------------------------
		            | Attach calculated values for Blade
		            |--------------------------------------------------------------------------
		            */

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
        ProcurementTender $procurementTender,
        ProcurementPurchaseOrder $purchaseOrder
    ) {

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
        | Verify PO belongs to current Tender
        |--------------------------------------------------------------------------
        */

        if (
            (int)
            $purchaseOrder->procurement_tender_id
            !==
            (int)
            $procurementTender->id
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | Project is mandatory
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
			| Check Delivery Items and Calculate Tracking
			|--------------------------------------------------------------------------
			*/

			$trackingItems = [];

			foreach ($validated['items'] as $item) {

			    $deliveryItem = \App\Models\ProcurementDeliveryItem::with([
			        'purchaseOrderItem',
			        'delivery',
			        'materialTrackingItems',
			    ])->findOrFail(
			        $item['delivery_item_id']
			    );


			    /*
			    |--------------------------------------------------------------------------
			    | Verify Delivery
			    |--------------------------------------------------------------------------
			    */

			    if (!$deliveryItem->delivery) {
			        throw new \Exception(
			            'Invalid delivery item.'
			        );
			    }


			    if (
			        (int) $deliveryItem->delivery->procurement_purchase_order_id
			        !==
			        (int) $purchaseOrder->id
			    ) {
			        throw new \Exception(
			            'Delivery item does not belong to this Purchase Order.'
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
			        $deliveryItem->materialTrackingItems
			            ->sum('accepted_quantity');


			    /*
			    |--------------------------------------------------------------------------
			    | Remaining Quantity Available for Tracking
			    |--------------------------------------------------------------------------
			    */

			    $remainingQuantity =
			        max(
			            0,
			            $acceptedQuantity
			            -
			            (float) $alreadyTrackedQuantity
			        );


			    /*
			    |--------------------------------------------------------------------------
			    | Do not create duplicate tracking
			    |--------------------------------------------------------------------------
			    */

			    if ($remainingQuantity <= 0) {

			        throw new \Exception(
			            'Material from delivery item "' .
			            ($deliveryItem->purchaseOrderItem?->item_name ?? 'Unknown') .
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
			        $issuedQuantity > $remainingQuantity
			    ) {

			        throw new \Exception(
			            'Issued quantity cannot exceed the remaining accepted quantity for "' .
			            ($deliveryItem->purchaseOrderItem?->item_name ?? 'Unknown') .
			            '". Available: ' .
			            number_format($remainingQuantity, 3)
			        );
			    }


			    /*
			    |--------------------------------------------------------------------------
			    | Validate Consumed Quantity
			    |--------------------------------------------------------------------------
			    */

			    if (
			        $consumedQuantity > $issuedQuantity
			    ) {

			        throw new \Exception(
			            'Consumed quantity cannot exceed issued quantity for "' .
			            ($deliveryItem->purchaseOrderItem?->item_name ?? 'Unknown') .
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
			    | Determine Status
			    |--------------------------------------------------------------------------
			    */

			    if ($consumedQuantity > 0 && $balanceQuantity <= 0) {

			        $itemStatus = 'Consumed';

			    } elseif ($issuedQuantity > 0 && $balanceQuantity <= 0) {

			        $itemStatus = 'Issued';

			    } elseif ($issuedQuantity > 0) {

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
			| Determine Overall Tracking Status
			|--------------------------------------------------------------------------
			*/

			$statuses =
			    collect($trackingItems)
			        ->pluck('status');


			if ($statuses->every(
			    fn ($status) => $status === 'Consumed'
			)) {

			    $trackingStatus = 'Consumed';

			} elseif ($statuses->every(
			    fn ($status) => $status === 'Issued'
			)) {

			    $trackingStatus = 'Issued';

			} elseif (
			    $statuses->contains('Issued') ||
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
			            $purchaseOrder->project_id,

			        'procurement_purchase_order_id' =>
			            $purchaseOrder->id,

			        'procurement_tender_id' =>
			            $purchaseOrder->procurement_tender_id,

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


            DB::commit();


            return redirect()
                ->route(
                    'admin.procurement.tenders.purchase-orders.material-trackings.show',
                    [
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
        ProcurementTender $procurementTender,
        ProcurementPurchaseOrder $purchaseOrder,
        ProcurementMaterialTracking $materialTracking
    ) {

        /*
        |--------------------------------------------------------------------------
        | Security
        |--------------------------------------------------------------------------
        */

        if (
            (int)
            $materialTracking
                ->procurement_purchase_order_id
            !==
            (int)
            $purchaseOrder->id
        ) {

            abort(404);
        }


        $materialTracking->load([
            'project',
            'purchaseOrder',
            'items.purchaseOrderItem',
            'items.deliveryItem.delivery',
        ]);


        return view(
            'procurement.material-trackings.show',
            compact(
                'procurementTender',
                'purchaseOrder',
                'materialTracking'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE TRACKING NUMBER
    |--------------------------------------------------------------------------
    */

    private function generateTrackingNumber(): string
    {
        $year = now()->format('Y');

        $last =
            ProcurementMaterialTracking::where(
                'tracking_number',
                'like',
                'MAT-' . $year . '-%'
            )
            ->orderByDesc('id')
            ->first();


        if (!$last) {

            $number = 1;

        } else {

            $parts =
                explode(
                    '-',
                    $last->tracking_number
                );

            $number =
                ((int)
                end($parts))
                + 1;
        }


        return sprintf(
            'MAT-%s-%06d',
            $year,
            $number
        );
    }
}