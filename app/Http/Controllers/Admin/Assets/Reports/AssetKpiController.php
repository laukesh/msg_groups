<?php

namespace App\Http\Controllers\Admin\Assets\Reports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Models\Asset;


class AssetKpiController extends Controller
{
    /**
     * Core Asset KPI Dashboard.
     */
    public function index(Request $request): View
    {
        $assetQuery = Asset::query();

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('building_id')) {
            $assetQuery->where(
                'building_id',
                $request->building_id
            );
        }

        if ($request->filled('floor_id')) {
            $assetQuery->where(
                'floor_id',
                $request->floor_id
            );
        }

        if ($request->filled('asset_category')) {
            $assetQuery->where(
                'asset_category',
                $request->asset_category
            );
        }

        if ($request->filled('status')) {
            $assetQuery->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Core Asset KPIs
        |--------------------------------------------------------------------------
        */

        $totalAssets = (clone $assetQuery)->count();

        $activeAssets = (clone $assetQuery)
            ->where('status', 'Active')
            ->count();

        $inactiveAssets = (clone $assetQuery)
            ->where('status', '!=', 'Active')
            ->count();

        $totalAssetValue = (clone $assetQuery)
            ->sum('purchase_cost');

        /*
        |--------------------------------------------------------------------------
        | Condition
        |--------------------------------------------------------------------------
        */

        $excellentAssets = (clone $assetQuery)
            ->where('condition', 'Excellent')
            ->count();

        $goodAssets = (clone $assetQuery)
            ->where('condition', 'Good')
            ->count();

        $fairAssets = (clone $assetQuery)
            ->where('condition', 'Fair')
            ->count();

        $poorAssets = (clone $assetQuery)
            ->where('condition', 'Poor')
            ->count();

        $criticalAssets = (clone $assetQuery)
            ->where('condition', 'Critical')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Percentages
        |--------------------------------------------------------------------------
        */

        $activeRate = $totalAssets > 0
            ? round(($activeAssets / $totalAssets) * 100, 2)
            : 0;

        $criticalRate = $totalAssets > 0
            ? round(($criticalAssets / $totalAssets) * 100, 2)
            : 0;

        $poorOrCritical = $poorAssets + $criticalAssets;

        $poorCriticalRate = $totalAssets > 0
            ? round(($poorOrCritical / $totalAssets) * 100, 2)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Category Distribution
        |--------------------------------------------------------------------------
        */

        $categoryDistribution = (clone $assetQuery)
            ->select(
                'asset_category',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('asset_category')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Status Distribution
        |--------------------------------------------------------------------------
        */

        $statusDistribution = (clone $assetQuery)
            ->select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recent Assets
        |--------------------------------------------------------------------------
        */

        $recentAssets = (clone $assetQuery)
            ->latest('id')
            ->limit(10)
            ->get();

        return view(
            'admin.assets.assets.reports.core-kpis.index',
            compact(
                'totalAssets',
                'activeAssets',
                'inactiveAssets',
                'totalAssetValue',
                'excellentAssets',
                'goodAssets',
                'fairAssets',
                'poorAssets',
                'criticalAssets',
                'activeRate',
                'criticalRate',
                'poorCriticalRate',
                'categoryDistribution',
                'statusDistribution',
                'recentAssets'
            )
        );
    }

    /**
     * Asset register KPI details.
     */
    public function assets(Request $request): View
    {
        $query = Asset::query();

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'asset_code',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'asset_name',
                    'like',
                    "%{$search}%"
                );

            });
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('condition')) {
            $query->where(
                'condition',
                $request->condition
            );
        }

        $assets = $query
            ->orderBy('asset_name')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.assets.assets.reports.core-kpis.assets',
            compact('assets')
        );
    }

    /**
     * Occupancy KPI.
     *
     * Uses the existing occupancy table/model when available.
     */
    public function occupancy(Request $request): View
    {
        $assets = Asset::query()
            ->when(
                $request->filled('building_id'),
                fn ($q) => $q->where(
                    'building_id',
                    $request->building_id
                )
            )
            ->get();

        $totalAssets = $assets->count();

        /*
        |--------------------------------------------------------------------------
        | Occupancy is calculated from the Asset status where no separate
        | occupancy aggregation is required.
        |--------------------------------------------------------------------------
        */

        $occupied = $assets->filter(function ($asset) {
            return strtolower(
                (string) $asset->status
            ) === 'occupied';
        })->count();

        $vacant = $assets->filter(function ($asset) {
            return strtolower(
                (string) $asset->status
            ) === 'vacant';
        })->count();

        $occupancyRate = $totalAssets > 0
            ? round(($occupied / $totalAssets) * 100, 2)
            : 0;
      //dd($assets);
        return view(
            'admin.assets.assets.reports.core-kpis.occupancy',
            compact(
                'assets',
                'totalAssets',
                'occupied',
                'vacant',
                'occupancyRate'
            )
        );
    }

    /**
     * Condition KPI.
     */
    public function condition(Request $request): View
    {
        $query = Asset::query();

        if ($request->filled('condition')) {
            $query->where(
                'condition',
                $request->condition
            );
        }

        $conditions = [
            'Excellent',
            'Good',
            'Fair',
            'Poor',
            'Critical',
        ];

        $conditionStats = [];

        foreach ($conditions as $condition) {

            $conditionStats[$condition] =
                (clone $query)
                    ->where('condition', $condition)
                    ->count();
        }

        $totalAssets = $query->count();

        $atRiskAssets =
            ($conditionStats['Poor'] ?? 0)
            +
            ($conditionStats['Critical'] ?? 0);

        $atRiskRate = $totalAssets > 0
            ? round(
                ($atRiskAssets / $totalAssets) * 100,
                2
            )
            : 0;
            $assets = Asset::query()
    ->with([
        'industryProfile',
        'latestCondition',
    ])
    ->paginate(20)
    ->withQueryString();

        return view(
            'admin.assets.assets.reports.core-kpis.condition',
            compact(
                'conditionStats',
                'totalAssets',
                'atRiskAssets',
                'assets',
                'atRiskRate'
            )
        );
    }

    /**
     * Performance KPI.
     */
    public function performance(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | Existing Asset Performance module
        |--------------------------------------------------------------------------
        |
        | Keep this dashboard independent from the performance relationship
        | until the exact performance schema is finalized.
        |
        */

        $assets = Asset::query()
            ->when(
                $request->filled('status'),
                fn ($q) => $q->where(
                    'status',
                    $request->status
                )
            )
            ->orderBy('asset_name')
            ->paginate(20)
            ->withQueryString();

        $totalAssets = Asset::count();

        $activeAssets = Asset::where(
            'status',
            'Active'
        )->count();

        $availabilityRate = $totalAssets > 0
            ? round(
                ($activeAssets / $totalAssets) * 100,
                2
            )
            : 0;

        return view(
            'admin.assets.assets.reports.core-kpis.performance',
            compact(
                'assets',
                'totalAssets',
                'activeAssets',
                'availabilityRate'
            )
        );
    }

    /**
     * Financial KPI.
     */
    public function financial(Request $request): View
    {
        $assets = Asset::query()
            ->orderBy('asset_name')
            ->paginate(20)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Asset Value
        |--------------------------------------------------------------------------
        */

        $assetValue = Asset::sum('purchase_cost');

        /*
        |--------------------------------------------------------------------------
        | Existing Financial Tables
        |--------------------------------------------------------------------------
        |
        | These tables already exist in the user's system.
        |
        */

        $income = DB::table('asset_incomes')
            ->sum('amount');

        $expenses = DB::table('asset_expenses')
            ->sum('amount');

        $noi = $income - $expenses;

        $roi = $assetValue > 0
            ? round(
                ($noi / $assetValue) * 100,
                2
            )
            : 0;

        return view(
            'admin.assets.assets.reports.core-kpis.financial',
            compact(
                'assets',
                'assetValue',
                'income',
                'expenses',
                'noi',
                'roi'
            )
        );
    }

    
/**
 * Lifecycle KPI.
 */
public function lifecycle(Request $request): View
{
    /*
    |--------------------------------------------------------------------------
    | Basic Asset Counts
    |--------------------------------------------------------------------------
    */

    $totalAssets = DB::table('assets')->count();

    /*
    |--------------------------------------------------------------------------
    | Lifecycle Records
    |--------------------------------------------------------------------------
    */

    $lifecycleQuery = DB::table('asset_lifecycles')
        ->orderByDesc('id');

    $lifecycleRecords = $lifecycleQuery->paginate(20);

    $totalLifecycleRecords = DB::table('asset_lifecycles')->count();

    /*
    |--------------------------------------------------------------------------
    | Detect Lifecycle Columns
    |--------------------------------------------------------------------------
    |
    | This makes the controller safer if your existing lifecycle table uses
    | slightly different column names.
    |
    */

    $stageColumn = null;

    foreach ([
        'lifecycle_stage',
        'stage',
        'status',
    ] as $column) {
        if (Schema::hasColumn('asset_lifecycles', $column)) {
            $stageColumn = $column;
            break;
        }
    }

    $dateColumns = [
        'planned_date',
        'renewal_date',
        'replacement_date',
        'end_of_life_date',
    ];

    $availableDateColumns = collect($dateColumns)
        ->filter(function ($column) {
            return Schema::hasColumn('asset_lifecycles', $column);
        })
        ->values()
        ->all();

    /*
    |--------------------------------------------------------------------------
    | Lifecycle Asset Coverage
    |--------------------------------------------------------------------------
    */

    $assetsWithLifecycle = DB::table('asset_lifecycles')
        ->whereNotNull('asset_id')
        ->distinct('asset_id')
        ->count('asset_id');

    /*
    |--------------------------------------------------------------------------
    | Lifecycle Stage Counts
    |--------------------------------------------------------------------------
    */

    $plannedAssets = 0;
    $renewalAssets = 0;
    $replacementAssets = 0;

    if ($stageColumn) {
        $plannedAssets = DB::table('asset_lifecycles')
            ->whereRaw(
                'LOWER(`' . $stageColumn . '`) LIKE ?',
                ['%planned%']
            )
            ->count();

        $renewalAssets = DB::table('asset_lifecycles')
            ->whereRaw(
                'LOWER(`' . $stageColumn . '`) LIKE ?',
                ['%renew%']
            )
            ->count();

        $replacementAssets = DB::table('asset_lifecycles')
            ->whereRaw(
                'LOWER(`' . $stageColumn . '`) LIKE ?',
                ['%replacement%']
            )
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Renewal / Replacement / End-of-Life Dates
    |--------------------------------------------------------------------------
    */

    $renewalDueAssets = 0;
    $endOfLifeAssets = 0;

    $today = now()->startOfDay();

    if (Schema::hasColumn('asset_lifecycles', 'renewal_date')) {
        $renewalDueAssets = DB::table('asset_lifecycles')
            ->whereNotNull('renewal_date')
            ->whereDate('renewal_date', '<=', $today)
            ->count();
    }

    if (Schema::hasColumn('asset_lifecycles', 'replacement_date')) {
        $renewalDueAssets += DB::table('asset_lifecycles')
            ->whereNotNull('replacement_date')
            ->whereDate('replacement_date', '<=', $today)
            ->count();
    }

    if (Schema::hasColumn('asset_lifecycles', 'end_of_life_date')) {
        $endOfLifeAssets = DB::table('asset_lifecycles')
            ->whereNotNull('end_of_life_date')
            ->whereDate('end_of_life_date', '<=', $today)
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Lifecycle Distribution
    |--------------------------------------------------------------------------
    */

    $lifecycleDistribution = collect();

    if ($stageColumn) {
        $lifecycleDistribution = DB::table('asset_lifecycles')
            ->select(
                $stageColumn . ' as stage',
                DB::raw('COUNT(*) as total')
            )
            ->whereNotNull($stageColumn)
            ->groupBy($stageColumn)
            ->orderByDesc('total')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Lifecycle by Industry
    |--------------------------------------------------------------------------
    */

    $industryProfileTableExists =
        Schema::hasTable('industry_operating_profiles');

    $industryProfileColumnExists =
        Schema::hasColumn('assets', 'industry_profile_id');

    $lifecycleByIndustry = collect();

    if (
        $industryProfileTableExists &&
        $industryProfileColumnExists
    ) {
        $lifecycleByIndustry = DB::table('industry_operating_profiles as ip')
            ->leftJoin(
                'assets as a',
                'a.industry_profile_id',
                '=',
                'ip.id'
            )
            ->leftJoin(
                'asset_lifecycles as al',
                'al.asset_id',
                '=',
                'a.id'
            )
            ->select(
                'ip.id',
                'ip.profile_name',
                DB::raw('COUNT(DISTINCT a.id) as assets'),
                DB::raw('COUNT(al.id) as lifecycle_records')
            )
            ->groupBy(
                'ip.id',
                'ip.profile_name'
            )
            ->orderBy('ip.profile_name')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Upcoming Lifecycle Events
    |--------------------------------------------------------------------------
    */

    $upcomingLifecycle = collect();

    /*
     * We collect upcoming records from each available lifecycle date.
     */
    foreach ($availableDateColumns as $dateColumn) {

        $query = DB::table('asset_lifecycles as al')
            ->leftJoin(
                'assets as a',
                'a.id',
                '=',
                'al.asset_id'
            )
            ->select(
                'al.*',
                'a.asset_code',
                'a.asset_name'
            )
            ->whereNotNull("al.$dateColumn")
            ->whereDate(
                "al.$dateColumn",
                '>=',
                $today
            )
            ->whereDate(
                "al.$dateColumn",
                '<=',
                $today->copy()->addMonths(12)
            )
            ->orderBy("al.$dateColumn");

        foreach ($query->get() as $record) {
            /*
             * Normalize the date so the Blade view can consistently
             * use $lifecycle->event_date.
             */
            $record->event_date = $record->{$dateColumn};

            /*
             * Normalize the stage.
             */
            if ($stageColumn) {
                $record->normalized_stage =
                    $record->{$stageColumn};
            } else {
                $record->normalized_stage =
                    str_replace(
                        '_',
                        ' ',
                        $dateColumn
                    );
            }

            /*
             * Load a lightweight asset object compatible with:
             *
             * $lifecycle->asset
             * $lifecycle->asset->industryProfile
             */
            if ($record->asset_id) {

                $asset = DB::table('assets')
                    ->where('id', $record->asset_id)
                    ->first();

                if ($asset) {

                    if (
                        $industryProfileTableExists &&
                        $industryProfileColumnExists &&
                        !empty($asset->industry_profile_id)
                    ) {
                        $asset->industryProfile =
                            DB::table(
                                'industry_operating_profiles'
                            )
                            ->where(
                                'id',
                                $asset->industry_profile_id
                            )
                            ->first();
                    } else {
                        $asset->industryProfile = null;
                    }

                    $record->asset = $asset;
                } else {
                    $record->asset = null;
                }
            } else {
                $record->asset = null;
            }

            $upcomingLifecycle->push($record);
        }
    }

    /*
     * Remove duplicate lifecycle records when the same record has
     * multiple future dates.
     */
    $upcomingLifecycle = $upcomingLifecycle
        ->unique('id')
        ->sortBy('event_date')
        ->take(20)
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Lifecycle Coverage Percentage
    |--------------------------------------------------------------------------
    */

    $lifecycleCoverage = $totalAssets > 0
        ? round(
            ($assetsWithLifecycle / $totalAssets) * 100,
            2
        )
        : 0;

    /*
    |--------------------------------------------------------------------------
    | Return Lifecycle KPI View
    |--------------------------------------------------------------------------
    */

    return view(
        'admin.assets.assets.reports.core-kpis.lifecycle',
        compact(
            'totalAssets',
           
            'totalLifecycleRecords',
            'renewalDueAssets',
            'endOfLifeAssets',
            'assetsWithLifecycle',
            'plannedAssets',
            'renewalAssets',
            'replacementAssets',
            'lifecycleRecords',
            'lifecycleDistribution',
            'lifecycleByIndustry',
            'upcomingLifecycle',
            'lifecycleCoverage'
        )
    );
}
}