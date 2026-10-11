<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetIncome;
use App\Models\AssetExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetPerformanceController extends Controller
{
    /**
     * Overall Asset Performance Dashboard
     */
    public function index(Request $request)
    {
        $assets = Asset::query()
            ->orderBy('id', 'desc')
            ->get();

        $assetId = $request->integer('asset');

        $selectedAsset = null;

        if ($assetId) {
            $selectedAsset = Asset::findOrFail($assetId);
        }

        /*
        |--------------------------------------------------------------------------
        | Income
        |--------------------------------------------------------------------------
        */

        $incomeQuery = AssetIncome::query();

        if ($assetId) {
            $incomeQuery->where('asset_id', $assetId);
        }

        if ($request->filled('from_date')) {
            $incomeQuery->whereDate(
                'income_date',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {
            $incomeQuery->whereDate(
                'income_date',
                '<=',
                $request->to_date
            );
        }

        $totalIncome = (float) $incomeQuery
            ->whereIn('status', [
                'received',
                'paid',
                'approved',
            ])
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | Expenses
        |--------------------------------------------------------------------------
        */

        $expenseQuery = AssetExpense::query();

        if ($assetId) {
            $expenseQuery->where('asset_id', $assetId);
        }

        if ($request->filled('from_date')) {
            $expenseQuery->whereDate(
                'expense_date',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {
            $expenseQuery->whereDate(
                'expense_date',
                '<=',
                $request->to_date
            );
        }

        $totalExpense = (float) $expenseQuery
            ->whereIn('status', [
                'paid',
                'approved',
            ])
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | NOI
        |--------------------------------------------------------------------------
        */

        $noi = $totalIncome - $totalExpense;

        /*
        |--------------------------------------------------------------------------
        | Investment
        |--------------------------------------------------------------------------
        */

        $investment = 0;

        if ($selectedAsset) {
            $investment = (float) (
                $selectedAsset->purchase_price
                ?? $selectedAsset->acquisition_cost
                ?? $selectedAsset->cost
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
        | Profit Margin
        |--------------------------------------------------------------------------
        */

        $profitMargin = 0;

        if ($totalIncome > 0) {
            $profitMargin = ($noi / $totalIncome) * 100;
        }

        /*
        |--------------------------------------------------------------------------
        | Asset Performance Table
        |--------------------------------------------------------------------------
        */

        $performance = Asset::query()
            ->with('vendor')
            ->get()
            ->map(function ($asset) {

                $income = (float) AssetIncome::where(
                    'asset_id',
                    $asset->id
                )
                    ->whereIn('status', [
                        'received',
                        'paid',
                        'approved',
                    ])
                    ->sum('amount');

                $expense = (float) AssetExpense::where(
                    'asset_id',
                    $asset->id
                )
                    ->whereIn('status', [
                        'paid',
                        'approved',
                    ])
                    ->sum('amount');

                $noi = $income - $expense;

                $investment = (float) (
                    $asset->purchase_price
                    ?? $asset->acquisition_cost
                    ?? $asset->cost
                    ?? 0
                );

                $roi = $investment > 0
                    ? ($noi / $investment) * 100
                    : 0;

                $margin = $income > 0
                    ? ($noi / $income) * 100
                    : 0;

                return (object) [
                    'asset'       => $asset,
                    'income'      => $income,
                    'expense'     => $expense,
                    'noi'         => $noi,
                    'investment'  => $investment,
                    'roi'         => $roi,
                    'margin'      => $margin,
                ];
            })
            ->sortByDesc('roi')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Monthly Chart
        |--------------------------------------------------------------------------
        */

        $monthlyIncomeQuery = AssetIncome::query()
            ->select(
                DB::raw("DATE_FORMAT(income_date, '%Y-%m') as month"),
                DB::raw('SUM(amount) as total')
            )
            ->whereIn('status', [
                'received',
                'paid',
                'approved',
            ]);

        $monthlyExpenseQuery = AssetExpense::query()
            ->select(
                DB::raw("DATE_FORMAT(expense_date, '%Y-%m') as month"),
                DB::raw('SUM(amount) as total')
            )
            ->whereIn('status', [
                'paid',
                'approved',
            ]);

        if ($assetId) {
            $monthlyIncomeQuery->where(
                'asset_id',
                $assetId
            );

            $monthlyExpenseQuery->where(
                'asset_id',
                $assetId
            );
        }

        if ($request->filled('from_date')) {
            $monthlyIncomeQuery->whereDate(
                'income_date',
                '>=',
                $request->from_date
            );

            $monthlyExpenseQuery->whereDate(
                'expense_date',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {
            $monthlyIncomeQuery->whereDate(
                'income_date',
                '<=',
                $request->to_date
            );

            $monthlyExpenseQuery->whereDate(
                'expense_date',
                '<=',
                $request->to_date
            );
        }

        $monthlyIncome = $monthlyIncomeQuery
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $monthlyExpense = $monthlyExpenseQuery
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $months = collect(
            $monthlyIncome
                ->keys()
                ->merge($monthlyExpense->keys())
                ->unique()
                ->sort()
                ->values()
        );

        $chartData = $months->map(function ($month) use (
            $monthlyIncome,
            $monthlyExpense
        ) {
            return [
                'month'   => $month,
                'income'  => (float) ($monthlyIncome[$month] ?? 0),
                'expense' => (float) ($monthlyExpense[$month] ?? 0),
                'noi'     => (float) (
                    ($monthlyIncome[$month] ?? 0)
                    -
                    ($monthlyExpense[$month] ?? 0)
                ),
            ];
        });

        return view(
            'admin.assets.performance.index',
            compact(
                'assets',
                'selectedAsset',
                'assetId',
                'totalIncome',
                'totalExpense',
                'noi',
                'investment',
                'roi',
                'profitMargin',
                'performance',
                'chartData'
            )
        );
    }


    /**
     * Individual Asset Performance
     */
    public function show(Asset $asset)
    {
        $income = (float) AssetIncome::where(
            'asset_id',
            $asset->id
        )
            ->whereIn('status', [
                'received',
                'paid',
                'approved',
            ])
            ->sum('amount');

        $expense = (float) AssetExpense::where(
            'asset_id',
            $asset->id
        )
            ->whereIn('status', [
                'paid',
                'approved',
            ])
            ->sum('amount');

        $noi = $income - $expense;

        $investment = (float) (
            $asset->purchase_price
            ?? $asset->acquisition_cost
            ?? $asset->cost
            ?? 0
        );

        $roi = $investment > 0
            ? ($noi / $investment) * 100
            : 0;

        $profitMargin = $income > 0
            ? ($noi / $income) * 100
            : 0;

        return view(
            'admin.assets.performance.show',
            compact(
                'asset',
                'income',
                'expense',
                'noi',
                'investment',
                'roi',
                'profitMargin'
            )
        );
    }
}