<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetIncome;
use App\Models\AssetExpense;
use Illuminate\Http\Request;

class EconomicDashboardController extends Controller
{
    /**
     * Economic Dashboard
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Get Asset ID
        |--------------------------------------------------------------------------
        | Supports:
        | ?asset=6
        | ?asset_id=6
        |--------------------------------------------------------------------------
        */

        $assetId = $request->integer('asset');
      
        if (!$assetId) {
            $assetId = $request->integer('asset_id');
        }

        /*
        |--------------------------------------------------------------------------
        | Selected Asset
        |--------------------------------------------------------------------------
        */

        $asset = null;

        if ($assetId) {
            $asset = Asset::with('vendor')->findOrFail($assetId);
        }

        /*
        |--------------------------------------------------------------------------
        | Income Query
        |--------------------------------------------------------------------------
        */

        $incomeQuery = AssetIncome::query();

        /*
        |--------------------------------------------------------------------------
        | Expense Query
        |--------------------------------------------------------------------------
        */

        $expenseQuery = AssetExpense::query();

        /*
        |--------------------------------------------------------------------------
        | Filter By Asset
        |--------------------------------------------------------------------------
        */

        if ($assetId) {
            $incomeQuery->where('asset_id', $assetId);

            $expenseQuery->where('asset_id', $assetId);
        }

        /*
        |--------------------------------------------------------------------------
        | From Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {

            $incomeQuery->whereDate(
                'income_date',
                '>=',
                $request->from_date
            );

            $expenseQuery->whereDate(
                'expense_date',
                '>=',
                $request->from_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | To Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('to_date')) {

            $incomeQuery->whereDate(
                'income_date',
                '<=',
                $request->to_date
            );

            $expenseQuery->whereDate(
                'expense_date',
                '<=',
                $request->to_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Total Income
        |--------------------------------------------------------------------------
        */

        $totalIncome = (clone $incomeQuery)
            ->whereIn('status', [
                'received',
                'paid',
                'approved',
            ])
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | Operating Expenses
        |--------------------------------------------------------------------------
        */

        $operatingExpenses = (clone $expenseQuery)
            ->where('is_operating_expense', true)
            ->whereIn('status', [
                'paid',
                'approved',
            ])
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | Net Operating Income
        |--------------------------------------------------------------------------
        */

        $noi = $totalIncome - $operatingExpenses;

        /*
        |--------------------------------------------------------------------------
        | Asset Investment
        |--------------------------------------------------------------------------
        */

        $investment = 0;

        if ($asset) {

            $investment = (float) (
                $asset->purchase_price
                ?? $asset->acquisition_cost
                ?? $asset->cost
                ?? 0
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ROI
        |--------------------------------------------------------------------------
        */

        $roi = 0;

        if ($investment > 0) {
            $roi = ($noi / $investment) * 100;
        }

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.assets.economic-dashboard.index',
            compact(
                'asset',
                'assetId',
                'totalIncome',
                'operatingExpenses',
                'noi',
                'investment',
                'roi'
            )
        );
    }
}