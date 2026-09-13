<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Repositories\AssetIncomeRepositoryInterface;
use Illuminate\Http\Request;

class AssetIncomeController extends Controller
{
    public function __construct(
        protected AssetIncomeRepositoryInterface $incomes
    ) {
       
    }

    public function index(
        Request $request,
        int $assetId
    ) {
        $asset = Asset::findOrFail($assetId);

        $incomes = $this->incomes->paginateByAsset(
            $assetId,
            [
                'search' => $request->search,
                'status' => $request->status,
            ]
        );

        return view(
            'admin.assets.incomes.index',
            compact(
                'asset',
                'incomes'
            )
        );
    }

    public function create(int $assetId)
    {
        $asset = Asset::findOrFail($assetId);

        return view(
            'admin.assets.incomes.create',
            compact('asset')
        );
    }

    public function store(
        Request $request,
        int $assetId
    ) {
        Asset::findOrFail($assetId);

        $data = $request->validate([
            'income_type' => [
                'required',
                'string',
                'max:100',
            ],

            'income_date' => [
                'required',
                'date',
            ],

            'billing_period_from' => [
                'nullable',
                'date',
            ],

            'billing_period_to' => [
                'nullable',
                'date',
                'after_or_equal:billing_period_from',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
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
        $data['created_by'] = auth()->id();

        $this->incomes->create($data);

        return redirect()
            ->route(
                'admin.assets.incomes.index',
                $assetId
            )
            ->with(
                'success',
                'Asset income added successfully.'
            );
    }

    public function edit(
        int $assetId,
        int $id
    ) {
        $asset = Asset::findOrFail($assetId);

        $income = $this->incomes->find($id);

        abort_unless(
            $income->asset_id == $assetId,
            404
        );

        return view(
            'admin.assets.incomes.edit',
            compact(
                'asset',
                'income'
            )
        );
    }

    public function update(
        Request $request,
        int $assetId,
        int $id
    ) {
        $income = $this->incomes->find($id);

        abort_unless(
            $income->asset_id == $assetId,
            404
        );

        $data = $request->validate([
            'income_type' => [
                'required',
                'string',
                'max:100',
            ],

            'income_date' => [
                'required',
                'date',
            ],

            'billing_period_from' => [
                'nullable',
                'date',
            ],

            'billing_period_to' => [
                'nullable',
                'date',
                'after_or_equal:billing_period_from',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
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

        $data['updated_by'] = auth()->id();

        $this->incomes->update(
            $id,
            $data
        );

        return redirect()
            ->route(
                'admin.assets.incomes.index',
                $assetId
            )
            ->with(
                'success',
                'Asset income updated successfully.'
            );
    }

    public function destroy(
        int $assetId,
        int $id
    ) {
        $income = $this->incomes->find($id);

        abort_unless(
            $income->asset_id == $assetId,
            404
        );

        $this->incomes->delete($id);

        return redirect()
            ->route(
                'admin.assets.incomes.index',
                $assetId
            )
            ->with(
                'success',
                'Asset income deleted successfully.'
            );
    }
}