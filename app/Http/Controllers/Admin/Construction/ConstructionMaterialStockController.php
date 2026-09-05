<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionMaterialReceipt;
use App\Models\ConstructionMaterialRequest;
use App\Models\ConstructionMaterialDelivery;
use App\Models\ConstructionMaterialStock;
use App\Models\ConstructionMaterialTransaction;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConstructionMaterialStockController extends Controller
{
    /**
     * Stock list.
     */
    public function index(
        Request $request,
        Project $project
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Base Stock Query
        |--------------------------------------------------------------------------
        */

        $baseQuery = ConstructionMaterialStock::query()
            ->where('project_id', $project->id);


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $baseQuery->where(function ($query) use ($search) {

                $query
                    ->where('batch_number', 'like', "%{$search}%")
                    ->orWhereHas('material', function ($materialQuery) use ($search) {

                        $materialQuery
                            ->where(
                                'material_code',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'material_name',
                                'like',
                                "%{$search}%"
                            );

                    });

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        |
        | Calculate these before pagination so the cards represent the complete
        | result set, not just the records displayed on the current page.
        |
        */

        $summaryQuery = clone $baseQuery;

        $totalMaterials = (clone $summaryQuery)
            ->count();


        $totalQuantity = (clone $summaryQuery)
            ->sum('quantity');


        $totalAvailable = (clone $summaryQuery)
            ->sum('available_quantity');


        $lowStock = (clone $summaryQuery)
            ->where('reorder_level', '>', 0)
            ->whereColumn(
                'available_quantity',
                '<=',
                'reorder_level'
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Paginated Stock
        |--------------------------------------------------------------------------
        */

        $stocks = $baseQuery
            ->with([
                'material',
            ])
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'construction.materials.stock.index',
            compact(
                'project',
                'stocks',
                'totalMaterials',
                'totalQuantity',
                'totalAvailable',
                'lowStock'
            )
        );
    }


    /**
     * Stock detail.
     */
    public function show(
        Project $project,
        ConstructionMaterialStock $stock
    ): View {

        abort_unless(
            $stock->project_id == $project->id,
            404
        );

        $stock->load([
            'material',
        ]);

        $transactions = ConstructionMaterialTransaction::query()
            ->where('stock_id', $stock->id)
            ->with([
                'material',
                'workOrder',
                'creator',
            ])
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->get();

        return view(
            'construction.materials.stock.show',
            compact(
                'project',
                'stock',
                'transactions'
            )
        );
    }


    /**
     * Stock transactions.
     */
    public function transactions(
        Request $request,
        Project $project
    ): View {

        $query = ConstructionMaterialTransaction::query()
            ->where('project_id', $project->id)
            ->with([
                'material',
                'workOrder',
                'creator',
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
                    'transaction_number',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'batch_number',
                    'like',
                    "%{$search}%"
                )
                ->orWhereHas(
                    'material',
                    function ($materialQuery) use ($search) {

                        $materialQuery
                            ->where(
                                'material_code',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'material_name',
                                'like',
                                "%{$search}%"
                            );
                    }
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Transaction Type Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('transaction_type')) {

            $query->where(
                'transaction_type',
                $request->transaction_type
            );
        }


        $transactions = $query
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Resolve Reference Information
        |--------------------------------------------------------------------------
        |
        | We resolve the friendly reference here instead of doing database
        | queries inside the Blade.
        |
        */

        foreach ($transactions as $transaction) {

            $this->resolveReference(
                $transaction
            );
        }


        return view(
            'construction.materials.stock.transactions',
            compact(
                'project',
                'transactions'
            )
        );
    }


    /**
     * Transaction detail.
     */
    public function transactionShow(
        Project $project,
        ConstructionMaterialTransaction $transaction
    ): View {

        abort_unless(
            $transaction->project_id == $project->id,
            404
        );

        $transaction->load([
            'material',
            'workOrder',
            'creator',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Resolve Reference
        |--------------------------------------------------------------------------
        */

        $this->resolveReference(
            $transaction
        );


        return view(
            'construction.materials.stock.transaction-show',
            compact(
                'project',
                'transaction'
            )
        );
    }


    /**
     * Resolve friendly reference information.
     */
    protected function resolveReference(
        ConstructionMaterialTransaction $transaction
    ): void {

        $transaction->reference_label = '—';

        $transaction->reference_number = null;

        $transaction->reference_record = null;


        if (
            empty($transaction->reference_type) ||
            empty($transaction->reference_id)
        ) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Material Receipt
        |--------------------------------------------------------------------------
        */

        if (
            $transaction->reference_type
            === ConstructionMaterialReceipt::class
        ) {

            $reference =
                ConstructionMaterialReceipt::find(
                    $transaction->reference_id
                );

            $transaction->reference_label =
                'Material Receipt';

            $transaction->reference_record =
                $reference;

            $transaction->reference_number =
                $reference?->receipt_number;

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Material Request
        |--------------------------------------------------------------------------
        */

        if (
            $transaction->reference_type
            === ConstructionMaterialRequest::class
        ) {

            $reference =
                ConstructionMaterialRequest::find(
                    $transaction->reference_id
                );

            $transaction->reference_label =
                'Material Request';

            $transaction->reference_record =
                $reference;

            $transaction->reference_number =
                $reference?->request_number;

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Material Delivery
        |--------------------------------------------------------------------------
        */

        if (
            $transaction->reference_type
            === ConstructionMaterialDelivery::class
        ) {

            $reference =
                ConstructionMaterialDelivery::find(
                    $transaction->reference_id
                );

            $transaction->reference_label =
                'Material Delivery';

            $transaction->reference_record =
                $reference;

            $transaction->reference_number =
                $reference?->delivery_number;

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Unknown Reference
        |--------------------------------------------------------------------------
        */

        $transaction->reference_label =
            class_basename(
                $transaction->reference_type
            );

        $transaction->reference_number =
            '#' . $transaction->reference_id;
    }
}