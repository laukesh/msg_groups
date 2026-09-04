<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Repositories\AssetExpenseRepositoryInterface;
use Illuminate\Http\Request;

class AssetExpenseController extends Controller
{
    public function __construct(
        protected AssetExpenseRepositoryInterface $expenses
    ) {
        $this->middleware('permission:expense.view')
            ->only(['index']);

        $this->middleware('permission:expense.create')
            ->only(['create', 'store']);

        $this->middleware('permission:expense.edit')
            ->only(['edit', 'update']);

        $this->middleware('permission:expense.delete')
            ->only(['destroy']);
    }

    public function index(
        Request $request,
        int $assetId
    ) {
        $asset = Asset::findOrFail($assetId);

        $expenses = $this->expenses->paginateByAsset(
            $assetId,
            [
                'search' => $request->search,
                'status' => $request->status,
            ]
        );

        return view(
            'admin.assets.expenses.index',
            compact(
                'asset',
                'expenses'
            )
        );
    }

    public function create(int $assetId)
    {
        $asset = Asset::findOrFail($assetId);

        return view(
            'admin.assets.expenses.create',
            compact('asset')
        );
    }

    public function store(
        Request $request,
        int $assetId
    ) {
        Asset::findOrFail($assetId);

        $data = $request->validate([
            'expense_type' => [
                'required',
                'string',
                'max:100',
            ],

            'expense_date' => [
                'required',
                'date',
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

            'is_operating_expense' => [
                'nullable',
                'boolean',
            ],

            'status' => [
                'required',
                'string',
                'max:30',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        $data['asset_id'] = $assetId;
        $data['is_operating_expense'] =
            $request->boolean('is_operating_expense');

        $data['created_by'] = auth()->id();

        $this->expenses->create($data);

        return redirect()
            ->route(
                'admin.assets.expenses.index',
                $assetId
            )
            ->with(
                'success',
                'Asset expense added successfully.'
            );
    }

    public function edit(
        int $assetId,
        int $id
    ) {
        $asset = Asset::findOrFail($assetId);

        $expense = $this->expenses->find($id);

        abort_unless(
            $expense->asset_id == $assetId,
            404
        );

        return view(
            'admin.assets.expenses.edit',
            compact(
                'asset',
                'expense'
            )
        );
    }

    public function update(
        Request $request,
        int $assetId,
        int $id
    ) {
        $expense = $this->expenses->find($id);

        abort_unless(
            $expense->asset_id == $assetId,
            404
        );

        $data = $request->validate([
            'expense_type' => [
                'required',
                'string',
                'max:100',
            ],

            'expense_date' => [
                'required',
                'date',
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

            'is_operating_expense' => [
                'nullable',
                'boolean',
            ],

            'status' => [
                'required',
                'string',
                'max:30',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        $data['is_operating_expense'] =
            $request->boolean('is_operating_expense');

        $data['updated_by'] = auth()->id();

        $this->expenses->update(
            $id,
            $data
        );

        return redirect()
            ->route(
                'admin.assets.expenses.index',
                $assetId
            )
            ->with(
                'success',
                'Asset expense updated successfully.'
            );
    }

    public function destroy(
        int $assetId,
        int $id
    ) {
        $expense = $this->expenses->find($id);

        abort_unless(
            $expense->asset_id == $assetId,
            404
        );

        $this->expenses->delete($id);

        return redirect()
            ->route(
                'admin.assets.expenses.index',
                $assetId
            )
            ->with(
                'success',
                'Asset expense deleted successfully.'
            );
    }
}