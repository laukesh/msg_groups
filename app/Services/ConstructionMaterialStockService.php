<?php

namespace App\Services;

use App\Models\ConstructionMaterialReceipt;
use App\Models\ConstructionMaterialStock;
use App\Models\ConstructionMaterialTransaction;
use Illuminate\Support\Facades\DB;

class ConstructionMaterialStockService
{
    /**
     * Post accepted material receipt into project stock.
     */
    public function postReceipt(ConstructionMaterialReceipt $receipt): void
    {
        DB::transaction(function () use ($receipt) {

            /*
             * Load complete relationship chain:
             *
             * Receipt
             *   -> Delivery
             *      -> Material Request
             *          -> Work Order
             *      -> Items
             */
            $receipt->load([
                'items.material',
                'delivery.materialRequest.workOrder',
            ]);

            /*
             * Resolve Work Order from:
             *
             * Receipt
             * → Delivery
             * → Material Request
             * → Work Order
             */
            $workOrderId = null;

            if (
                $receipt->delivery &&
                $receipt->delivery->materialRequest &&
                $receipt->delivery->materialRequest->workOrder
            ) {
                $workOrderId =
                    $receipt->delivery
                        ->materialRequest
                        ->workOrder
                        ->id;
            }

            foreach ($receipt->items as $item) {

                /*
                 * Only accepted quantity should enter stock.
                 */
                $acceptedQuantity = (float) $item->accepted_quantity;

                if ($acceptedQuantity <= 0) {
                    continue;
                }

                /*
                 * Check whether this receipt item has
                 * already been posted to stock.
                 *
                 * This prevents duplicate stock posting.
                 */
                $alreadyPosted = ConstructionMaterialTransaction::query()
                    ->where(
                        'reference_type',
                        ConstructionMaterialReceipt::class
                    )
                    ->where(
                        'reference_id',
                        $receipt->id
                    )
                    ->where(
                        'material_id',
                        $item->material_id
                    )
                    ->where(function ($query) use ($item) {

                        if ($item->batch_number !== null) {
                            $query->where(
                                'batch_number',
                                $item->batch_number
                            );
                        } else {
                            $query->whereNull('batch_number');
                        }

                    })
                    ->exists();

                if ($alreadyPosted) {
                    continue;
                }

                /*
                 * Find or create project stock.
                 *
                 * Batch is part of the stock identity.
                 */
                $stock = ConstructionMaterialStock::query()
                    ->where('project_id', $receipt->project_id)
                    ->where('material_id', $item->material_id)
                    ->where(function ($query) use ($item) {

                        if ($item->batch_number !== null) {
                            $query->where(
                                'batch_number',
                                $item->batch_number
                            );
                        } else {
                            $query->whereNull('batch_number');
                        }

                    })
                    ->lockForUpdate()
                    ->first();

                if (!$stock) {

                    $stock = ConstructionMaterialStock::create([
                        'project_id' => $receipt->project_id,
                        'material_id' => $item->material_id,
                        'batch_number' => $item->batch_number,
                        'unit' => $item->unit,
                        'quantity' => 0,
                        'reserved_quantity' => 0,
                        'available_quantity' => 0,
                        'reorder_level' => 0,
                        'last_transaction_at' => null,
                    ]);
                }

                /*
                 * Update stock.
                 */
                $stock->quantity =
                    (float) $stock->quantity
                    + $acceptedQuantity;

                $stock->available_quantity =
                    (float) $stock->available_quantity
                    + $acceptedQuantity;

                $stock->last_transaction_at =
                    now();

                $stock->save();

                /*
                 * Create stock transaction.
                 */
                ConstructionMaterialTransaction::create([
                    'project_id' =>
                        $receipt->project_id,

                    'material_id' =>
                        $item->material_id,

                    'stock_id' =>
                        $stock->id,

                    'transaction_number' =>
                        $this->generateTransactionNumber(),

                    'transaction_type' =>
                        'Receipt',

                    'transaction_date' =>
                        $receipt->receipt_date
                        ?? now(),

                    'quantity' =>
                        $acceptedQuantity,

                    'unit' =>
                        $item->unit,

                    /*
                     * Keep the model class internally for
                     * reliable audit/reference tracking.
                     */
                    'reference_type' =>
                        ConstructionMaterialReceipt::class,

                    'reference_id' =>
                        $receipt->id,

                    /*
                     * IMPORTANT:
                     * Store the Work Order ID resolved from
                     * Receipt → Delivery → Request → Work Order.
                     */
                    'construction_work_order_id' =>
                        $workOrderId,

                    'batch_number' =>
                        $item->batch_number,

                    'remarks' =>
                        'Material receipt '
                        . $receipt->receipt_number,

                    'created_by' =>
                        auth()->id(),
                ]);
            }
        });
    }


    /**
     * Generate stock transaction number.
     */
    protected function generateTransactionNumber(): string
    {
        $nextNumber =
            (ConstructionMaterialTransaction::max('id') ?? 0) + 1;

        return 'MT-'
            . now()->format('Y')
            . '-'
            . str_pad(
                $nextNumber,
                6,
                '0',
                STR_PAD_LEFT
            );
    }
}