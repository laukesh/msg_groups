<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\User;
use App\Models\AssetExpense;
use App\Repositories\AssetExpenseRepositoryInterface;
use Illuminate\Http\Request;

class AssetExpenseController extends Controller
{
    protected AssetExpenseRepositoryInterface $repository;

    public function __construct(
        AssetExpenseRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * Display expenses for an asset.
     */
    public function index(
        Request $request,
        int $asset
    ) {
        $asset = Asset::findOrFail($asset);

        $filters = [
            'asset_id' => $asset->id,
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'is_operating_expense' =>
                $request->input('is_operating_expense'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
        ];

        $expenses = $this->repository->getByAsset(
            $asset->id,
            $filters,
            15
        );
        $vendors = User::select('id', 'name')->get();


        return view(
            'admin.assets.expenses.index',
            compact(
                'asset',
                'expenses','vendors'
            )
        );
    }

    /**
     * Show create form.
     */
    public function create(int $asset)
    {
        $asset = Asset::findOrFail($asset);
        $vendors = User::select('id', 'name')->get();
        return view(
            'admin.assets.expenses.create',
            compact('asset', 'vendors')
        );
    }

    /**
     * Store expense.
     */
    public function store(
        Request $request,
        int $asset
    ) {
        $asset = Asset::findOrFail($asset);

        $validated = $request->validate([
            'expense_date' => [
                'required',
                'date',
            ],

            'expense_type' => [
                'required',
                'string',
                'max:100',
            ],

            'vendor_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'is_operating_expense' => [
                'nullable',
                'boolean',
            ],

            'status' => [
                'required',
                'string',
                'max:50',
            ],
        ]);

        $validated['asset_id'] = $asset->id;

        $validated['is_operating_expense'] =
            $request->boolean(
                'is_operating_expense'
            );

        $this->repository->create($validated);

        return redirect()
            ->route(
                'admin.assets.expenses.index',
                $asset->id
            )
            ->with(
                'success',
                'Asset expense created successfully.'
            );
    }

    /**
     * Display expense.
     */
    public function show(
        int $asset,
        int $expense
    ) {
        $asset = Asset::findOrFail($asset);

        $expenseModel = $this->repository->find(
            $expense
        );

        abort_if(
            !$expenseModel,
            404
        );

        /*
        | Make sure expense belongs to this asset.
        */
        abort_if(
            (int) $expenseModel->asset_id !==
            (int) $asset->id,
            404
        );

        return view(
            'admin.assets.expenses.show',
            [
                'asset' => $asset,
                'expense' => $expenseModel,
            ]
        );
    }

    /**
     * Show edit form.
     */
    public function edit(
        int $asset,
        int $expense
    ) {
        $asset = Asset::findOrFail($asset);

        $expenseModel = $this->repository->find(
            $expense
        );

        abort_if(
            !$expenseModel,
            404
        );

        abort_if(
            (int) $expenseModel->asset_id !==
            (int) $asset->id,
            404
        );

        return view(
            'admin.assets.expenses.edit',
            [
                'asset' => $asset,
                'expense' => $expenseModel,
            ]
        );
    }

    /**
     * Update expense.
     */
    public function update(
        Request $request,
        int $asset,
        int $expense
    ) {
        $asset = Asset::findOrFail($asset);

        $expenseModel = $this->repository->find(
            $expense
        );

        abort_if(
            !$expenseModel,
            404
        );

        abort_if(
            (int) $expenseModel->asset_id !==
            (int) $asset->id,
            404
        );

        $validated = $request->validate([
            'expense_date' => [
                'required',
                'date',
            ],

            'expense_type' => [
                'required',
                'string',
                'max:100',
            ],

            'vendor_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'is_operating_expense' => [
                'nullable',
                'boolean',
            ],

            'status' => [
                'required',
                'string',
                'max:50',
            ],
        ]);

        $validated['asset_id'] = $asset->id;

        $validated['is_operating_expense'] =
            $request->boolean(
                'is_operating_expense'
            );

        $this->repository->update(
            $expense,
            $validated
        );

        return redirect()
            ->route(
                'admin.assets.expenses.index',
                $asset->id
            )
            ->with(
                'success',
                'Asset expense updated successfully.'
            );
    }

    /**
     * Delete expense.
     */
    public function destroy(
        int $asset,
        int $expense
    ) {
        $asset = Asset::findOrFail($asset);

        $expenseModel = $this->repository->find(
            $expense
        );

        abort_if(
            !$expenseModel,
            404
        );

        abort_if(
            (int) $expenseModel->asset_id !==
            (int) $asset->id,
            404
        );

        $this->repository->delete(
            $expense
        );

        return redirect()
            ->route(
                'admin.assets.expenses.index',
                $asset->id
            )
            ->with(
                'success',
                'Asset expense deleted successfully.'
            );
    }
}