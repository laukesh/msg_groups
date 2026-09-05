<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionMaterialDelivery;
use App\Models\ConstructionMaterialReceipt;
use App\Models\Project;
use App\Services\ConstructionMaterialStockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConstructionMaterialReceiptController extends Controller
{
    /**
     * Material Receipt List
     */
    public function index(
        Project $project,
        Request $request
    ): View {
        $query = ConstructionMaterialReceipt::query()
            ->where('project_id', $project->id)
            ->with([
                'delivery',
                'delivery.materialRequest',
                'items.material',
                'receivedBy',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'receipt_number',
                    'like',
                    '%' . $search . '%'
                );

                $q->orWhereHas(
                    'delivery',
                    function ($deliveryQuery) use ($search) {

                        $deliveryQuery
                            ->where(
                                'delivery_number',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'challan_number',
                                'like',
                                '%' . $search . '%'
                            );
                    }
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $receipts = $query
            ->orderByDesc('receipt_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'construction.materials.receipts.index',
            compact(
                'project',
                'receipts'
            )
        );
    }


    /**
     * Create Material Receipt
     *
     * Receipt is created from a Material Delivery.
     */
    public function create(
        Project $project,
        ConstructionMaterialDelivery $materialDelivery
    ): View {
        /*
        |--------------------------------------------------------------------------
        | Validate Delivery Belongs to Project
        |--------------------------------------------------------------------------
        */

        $this->validateProjectDelivery(
            $project,
            $materialDelivery
        );

        /*
        |--------------------------------------------------------------------------
        | Delivery Must Be Delivered / Received
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $materialDelivery->status,
                [
                    'Delivered',
                    'Received',
                ],
                true
            )
        ) {
            abort(
                403,
                'Only delivered material can be received.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Active Receipt
        |--------------------------------------------------------------------------
        */

        $receiptExists = $materialDelivery
            ->receipts()
            ->whereNotIn(
                'status',
                [
                    'Cancelled',
                    'Rejected',
                ]
            )
            ->exists();

        if ($receiptExists) {

            return redirect()
                ->route(
                    'admin.projects.construction.materials.deliveries.show',
                    [
                        'project' =>
                            $project->id,

                        'materialDelivery' =>
                            $materialDelivery->id,
                    ]
                )
                ->with(
                    'error',
                    'A material receipt already exists for this delivery.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Load Delivery Data
        |--------------------------------------------------------------------------
        */

        $materialDelivery->load([
            'items.material',
            'materialRequest',
            'materialRequest.workOrder',
        ]);

        return view(
            'construction.materials.receipts.create',
            compact(
                'project',
                'materialDelivery'
            )
        );
    }


    /**
     * Store Material Receipt
     */
    public function store(
        Request $request,
        Project $project,
        ConstructionMaterialStockService $stockService
    ) {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'material_delivery_id' => [
                'required',
                'integer',
                'exists:construction_material_deliveries,id',
            ],

            'receipt_date' => [
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

            'items.*.material_id' => [
                'required',
                'integer',
                'exists:construction_materials,id',
            ],

            'items.*.delivered_quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'items.*.accepted_quantity' => [
                'required',
                'numeric',
                'gte:0',
            ],

            'items.*.rejected_quantity' => [
                'required',
                'numeric',
                'gte:0',
            ],

            'items.*.unit' => [
                'required',
                'string',
                'max:50',
            ],

            'items.*.batch_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'items.*.inspection_required' => [
                'nullable',
                'boolean',
            ],

            'items.*.remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get Delivery
        |--------------------------------------------------------------------------
        */

        $delivery = ConstructionMaterialDelivery::query()
            ->with([
                'items',
            ])
            ->findOrFail(
                $validated['material_delivery_id']
            );

        /*
        |--------------------------------------------------------------------------
        | Validate Delivery Belongs to Project
        |--------------------------------------------------------------------------
        */

        $this->validateProjectDelivery(
            $project,
            $delivery
        );

        /*
        |--------------------------------------------------------------------------
        | Validate Delivery Status
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $delivery->status,
                [
                    'Delivered',
                    'Received',
                ],
                true
            )
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'material_delivery_id' =>
                        'This delivery cannot be received in its current status.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Active Receipt
        |--------------------------------------------------------------------------
        */

        $receiptExists = $delivery
            ->receipts()
            ->whereNotIn(
                'status',
                [
                    'Cancelled',
                    'Rejected',
                ]
            )
            ->exists();

        if ($receiptExists) {

            return back()
                ->withInput()
                ->withErrors([
                    'material_delivery_id' =>
                        'A material receipt already exists for this delivery.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Delivery Items
        |--------------------------------------------------------------------------
        */

        $deliveryItems = $delivery
            ->items
            ->keyBy('material_id');

        /*
        |--------------------------------------------------------------------------
        | Validate Receipt Items
        |--------------------------------------------------------------------------
        */

        foreach (
            $validated['items']
            as $item
        ) {

            /*
            |--------------------------------------------------------------------------
            | Material Must Exist in Delivery
            |--------------------------------------------------------------------------
            */

            if (
                !$deliveryItems->has(
                    $item['material_id']
                )
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'items' =>
                            'Selected material does not belong to this delivery.',
                    ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Delivered Quantity
            |--------------------------------------------------------------------------
            */

            $deliveredQuantity =
                (float) $item['delivered_quantity'];

            $acceptedQuantity =
                (float) $item['accepted_quantity'];

            $rejectedQuantity =
                (float) $item['rejected_quantity'];

            /*
            |--------------------------------------------------------------------------
            | Accepted + Rejected Must Equal Delivered
            |--------------------------------------------------------------------------
            */

            $totalReceived =
                $acceptedQuantity +
                $rejectedQuantity;

            if (
                abs(
                    $totalReceived -
                    $deliveredQuantity
                ) > 0.0001
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'items' =>
                            'Accepted quantity plus rejected quantity must equal delivered quantity.',
                    ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Cannot Exceed Delivery Quantity
            |--------------------------------------------------------------------------
            */

            $deliveryItem =
                $deliveryItems->get(
                    $item['material_id']
                );

            $actualDeliveredQuantity =
                (float) $deliveryItem->delivered_quantity;

            if (
                $deliveredQuantity >
                $actualDeliveredQuantity
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'items' =>
                            'Receipt quantity cannot exceed delivered quantity.',
                    ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Accepted Quantity Cannot Exceed Delivered
            |--------------------------------------------------------------------------
            */

            if (
                $acceptedQuantity >
                $deliveredQuantity
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'items' =>
                            'Accepted quantity cannot exceed delivered quantity.',
                    ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Rejected Quantity Cannot Exceed Delivered
            |--------------------------------------------------------------------------
            */

            if (
                $rejectedQuantity >
                $deliveredQuantity
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'items' =>
                            'Rejected quantity cannot exceed delivered quantity.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Determine Receipt Status
        |--------------------------------------------------------------------------
        */

        $totalAccepted = collect(
            $validated['items']
        )->sum(
            fn ($item) =>
                (float) $item['accepted_quantity']
        );

        $totalRejected = collect(
            $validated['items']
        )->sum(
            fn ($item) =>
                (float) $item['rejected_quantity']
        );

        $status = 'Received';

        /*
        |--------------------------------------------------------------------------
        | Status Rules
        |--------------------------------------------------------------------------
        */

        if (
            $totalAccepted > 0 &&
            $totalRejected > 0
        ) {

            $status = 'Partially Accepted';

        } elseif (
            $totalRejected > 0 &&
            $totalAccepted <= 0
        ) {

            $status = 'Rejected';

        } elseif (
            $totalAccepted > 0 &&
            $totalRejected <= 0
        ) {

            $status = 'Accepted';
        }

        /*
        |--------------------------------------------------------------------------
        | Save Receipt
        |--------------------------------------------------------------------------
        */

        $receipt = DB::transaction(
            function () use (
                $validated,
                $project,
                $delivery,
                $status
            ) {

                /*
                |--------------------------------------------------------------------------
                | Create Receipt
                |--------------------------------------------------------------------------
                */

                $receipt =
                    new ConstructionMaterialReceipt();

                $receipt->project_id =
                    $project->id;

                $receipt->material_delivery_id =
                    $delivery->id;

                $receipt->receipt_number =
                    $this->generateReceiptNumber();

                $receipt->receipt_date =
                    $validated['receipt_date'];

                $receipt->received_by =
                    Auth::id();

                $receipt->status =
                    $status;

                $receipt->remarks =
                    $validated['remarks'] ?? null;

                $receipt->created_by =
                    Auth::id();

                $receipt->save();

                /*
                |--------------------------------------------------------------------------
                | Receipt Items
                |--------------------------------------------------------------------------
                */

                foreach (
                    $validated['items']
                    as $item
                ) {

                    $receipt->items()->create([

                        'material_id' =>
                            $item['material_id'],

                        'delivered_quantity' =>
                            $item['delivered_quantity'],

                        'accepted_quantity' =>
                            $item['accepted_quantity'],

                        'rejected_quantity' =>
                            $item['rejected_quantity'],

                        'unit' =>
                            $item['unit'],

                        'batch_number' =>
                            $item['batch_number']
                            ?? null,

                        'inspection_required' =>
                            isset(
                                $item['inspection_required']
                            )
                                ? (bool)
                                    $item['inspection_required']
                                : true,

                        'remarks' =>
                            $item['remarks']
                            ?? null,
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Update Delivery Status
                |--------------------------------------------------------------------------
                */

                if (
                    $delivery->status !==
                    'Received'
                ) {

                    $delivery->status =
                        'Received';

                    $delivery->updated_by =
                        Auth::id();

                    $delivery->save();
                }

                return $receipt;
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Post Accepted Quantity to Stock
        |--------------------------------------------------------------------------
        |
        | Only accepted quantity is added to stock.
        |
        | Example:
        |
        | Delivered = 100
        | Accepted  = 95
        | Rejected  = 5
        |
        | Stock +95
        |
        */

        if (
            in_array(
                $receipt->status,
                [
                    'Accepted',
                    'Partially Accepted',
                ],
                true
            )
        ) {

            $stockService->postReceipt(
                $receipt
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.projects.construction.materials.receipts.show',
                [
                    'project' =>
                        $project->id,

                    'materialReceipt' =>
                        $receipt->id,
                ]
            )
            ->with(
                'success',
                'Material receipt recorded successfully.'
            );
    }


    /**
     * Show Receipt
     */
    public function show(
        Project $project,
        ConstructionMaterialReceipt $materialReceipt
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Validate Project
        |--------------------------------------------------------------------------
        */

        $this->validateProjectReceipt(
            $project,
            $materialReceipt
        );

        /*
        |--------------------------------------------------------------------------
        | Load Relationships
        |--------------------------------------------------------------------------
        */

        $materialReceipt->load([
            'delivery',
            'delivery.materialRequest',
            'delivery.materialRequest.workOrder',
            'items.material',
            'receivedBy',
            'creator',
            'updater',
        ]);

        return view(
            'construction.materials.receipts.show',
            compact(
                'project',
                'materialReceipt'
            )
        );
    }


    /**
     * Move Receipt to Inspection
     */
    public function inspect(
        Project $project,
        ConstructionMaterialReceipt $materialReceipt
    ) {

        /*
        |--------------------------------------------------------------------------
        | Validate Project
        |--------------------------------------------------------------------------
        */

        $this->validateProjectReceipt(
            $project,
            $materialReceipt
        );

        /*
        |--------------------------------------------------------------------------
        | Status Validation
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $materialReceipt->status,
                [
                    'Received',
                    'Draft',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'This receipt cannot be moved to inspection from its current status.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $materialReceipt->status =
            'Under Inspection';

        $materialReceipt->updated_by =
            Auth::id();

        $materialReceipt->save();

        return back()
            ->with(
                'success',
                'Material receipt moved to inspection.'
            );
    }


    /**
     * Cancel Receipt
     */
    public function cancel(
        Project $project,
        ConstructionMaterialReceipt $materialReceipt
    ) {

        /*
        |--------------------------------------------------------------------------
        | Validate Project
        |--------------------------------------------------------------------------
        */

        $this->validateProjectReceipt(
            $project,
            $materialReceipt
        );

        /*
        |--------------------------------------------------------------------------
        | Cannot Cancel Accepted Receipt
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $materialReceipt->status,
                [
                    'Accepted',
                    'Partially Accepted',
                    'Cancelled',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'This receipt cannot be cancelled.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Cancel
        |--------------------------------------------------------------------------
        */

        $materialReceipt->status =
            'Cancelled';

        $materialReceipt->updated_by =
            Auth::id();

        $materialReceipt->save();

        return back()
            ->with(
                'success',
                'Material receipt cancelled successfully.'
            );
    }


    /**
     * Validate Delivery Belongs to Project
     */
    private function validateProjectDelivery(
        Project $project,
        ConstructionMaterialDelivery $delivery
    ): void {

        if (
            (int) $delivery->project_id !==
            (int) $project->id
        ) {
            abort(404);
        }
    }


    /**
     * Validate Receipt Belongs to Project
     */
    private function validateProjectReceipt(
        Project $project,
        ConstructionMaterialReceipt $receipt
    ): void {

        if (
            (int) $receipt->project_id !==
            (int) $project->id
        ) {
            abort(404);
        }
    }


    /**
     * Generate Receipt Number
     *
     * Format:
     *
     * REC-2026-000001
     */
    private function generateReceiptNumber(): string
    {
        $lastId =
            ConstructionMaterialReceipt::withTrashed()
                ->max('id');

        $nextId =
            ((int) $lastId) + 1;

        return 'REC-' .
            date('Y') .
            '-' .
            str_pad(
                $nextId,
                6,
                '0',
                STR_PAD_LEFT
            );
    }
}