<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Repositories\AssetIncomeRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\AssetIncome;
class AssetIncomeController extends Controller
{
    public function __construct(
        protected AssetIncomeRepositoryInterface $incomes
    ) {
        $this->middleware('permission:incomes.view')
            ->only(['index']);

        $this->middleware('permission:incomes.create')
            ->only(['create', 'store']);

        $this->middleware('permission:incomes.edit')
            ->only(['edit', 'update']);

        $this->middleware('permission:incomes.delete')
            ->only(['destroy']);
    }

    /**
     * Display asset incomes.
     */
    public function index(Request $request, ?int $asset = null)
    {
        if ($asset) {
            $assetModel = Asset::findOrFail($asset);

            $incomes = $this->incomes->paginateByAsset(
                $asset,
                [
                    'search' => $request->input('search'),
                    'status' => $request->input('status'),
                ]
            );
        } else {
            $assetModel = null;

            $incomes = $this->incomes->paginate(
                10,
                [
                    'search' => $request->input('search'),
                    'status' => $request->input('status'),
                ]
            );
        }

        return view('admin.assets.incomes.index', [
            'asset'   => $assetModel,
            'incomes' => $incomes,
        ]);
    }

    /**
     * Show create income form.
     */
    public function create(int $asset)
    {
        $assetModel = Asset::findOrFail($asset);

        return view('admin.assets.incomes.create', [
            'asset' => $assetModel,
        ]);
    }

    /**
     * Store asset income.
     */
    public function store(Request $request, int $asset)
    {
        Asset::findOrFail($asset);

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

        $data['asset_id'] = $asset;
        $data['created_by'] = auth()->id();

        $this->incomes->create($data);

        return redirect()
            ->route('admin.assets.incomes.index', [
                'asset' => $asset,
            ])
            ->with('success', 'Asset income added successfully.');
    }

    /**
     * Show edit income form.
     */
    public function edit(int $asset, int $income)
    {
        $assetModel = Asset::findOrFail($asset);

        $incomeModel = $this->incomes->find($income);

        abort_unless(
            $incomeModel &&
            (int) $incomeModel->asset_id === (int) $asset,
            404
        );

        return view('admin.assets.incomes.edit', [
            'asset'  => $assetModel,
            'income' => $incomeModel,
        ]);
    }

    /**
     * Update asset income.
     */
    public function update(
        Request $request,
        int $asset,
        int $income
    ) {
        $incomeModel = $this->incomes->find($income);

        abort_unless(
            $incomeModel &&
            (int) $incomeModel->asset_id === (int) $asset,
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

        $this->incomes->update($income, $data);

        return redirect()
            ->route('admin.assets.incomes.index', [
                'asset' => $asset,
            ])
            ->with('success', 'Asset income updated successfully.');
    }

    /**
     * Delete asset income.
     */
    public function destroy(int $asset, int $income)
    {
        $incomeModel = $this->incomes->find($income);

        abort_unless(
            $incomeModel &&
            (int) $incomeModel->asset_id === (int) $asset,
            404
        );

        $this->incomes->delete($income);

        return redirect()
            ->route('admin.assets.incomes.index', [
                'asset' => $asset,
            ])
            ->with('success', 'Asset income deleted successfully.');
    }
}