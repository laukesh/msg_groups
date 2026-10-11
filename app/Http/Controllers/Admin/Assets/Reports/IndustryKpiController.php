<?php

namespace App\Http\Controllers\Admin\Assets\Reports;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\IndustryOperatingProfile as IndustryProfile;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class IndustryKpiController extends Controller
{
    /**
     * Industry KPI dashboard.
     */
    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | Industry Profiles
        |--------------------------------------------------------------------------
        */

        $profiles = IndustryProfile::query()
            ->orderBy('profile_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Build KPI Summary
        |--------------------------------------------------------------------------
        */

        $summaries = $profiles->map(
            function ($profile) {

                $query = Asset::query()
                    ->where(
                        'industry_profile_id',
                        $profile->id
                    );

                $total = (clone $query)->count();

                $active = (clone $query)
                    ->where('status', 'Active')
                    ->count();

                $critical = (clone $query)
                    ->where('condition', 'Critical')
                    ->count();

                $poor = (clone $query)
                    ->where('condition', 'Poor')
                    ->count();

                $value = (clone $query)
                    ->sum('purchase_cost');

                return [
                    'profile' => $profile,
                    'total' => $total,
                    'active' => $active,
                    'critical' => $critical,
                    'poor' => $poor,
                    'value' => $value,
                    'active_rate' => $total > 0
                        ? round(
                            ($active / $total) * 100,
                            2
                        )
                        : 0,
                ];
            }
        );

        return view(
            'admin.assets.assets.reports.industry-kpis.index',
            compact(
                'profiles',
                'summaries'
            )
        );
    }

    /**
     * Show one industry KPI dashboard.
     */
    public function show(
        IndustryProfile $industry_profile,
        Request $request
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */

        $profile = $industry_profile;

        /*
        |--------------------------------------------------------------------------
        | Assets
        |--------------------------------------------------------------------------
        */

        $assetQuery = Asset::query()
            ->where(
                'industry_profile_id',
                $profile->id
            );

        if ($request->filled('status')) {

            $assetQuery->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('condition')) {

            $assetQuery->where(
                'condition',
                $request->condition
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Core Metrics
        |--------------------------------------------------------------------------
        */

        $totalAssets = (clone $assetQuery)->count();

        $activeAssets = (clone $assetQuery)
            ->where('status', 'Active')
            ->count();

        $criticalAssets = (clone $assetQuery)
            ->where('condition', 'Critical')
            ->count();

        $poorAssets = (clone $assetQuery)
            ->where('condition', 'Poor')
            ->count();

        $assetValue = (clone $assetQuery)
            ->sum('purchase_cost');

        /*
        |--------------------------------------------------------------------------
        | Profile Targets
        |--------------------------------------------------------------------------
        */

        $targets = [
            'occupancy' => $profile->occupancy_target,
            'performance' => $profile->performance_target,
            'utilization' => $profile->utilization_target,
            'availability' => $profile->availability_target,
            'efficiency' => $profile->efficiency_target,
        ];

        /*
        |--------------------------------------------------------------------------
        | Industry Type
        |--------------------------------------------------------------------------
        */

        $industryType = strtolower(
            (string) $profile->industry_type
        );

        /*
        |--------------------------------------------------------------------------
        | Industry KPI Configuration
        |--------------------------------------------------------------------------
        */

        $industryKpis =
            $this->getIndustryKpis(
                $industryType,
                $assetQuery,
                $profile
            );

        /*
        |--------------------------------------------------------------------------
        | Assets
        |--------------------------------------------------------------------------
        */

        $assets = (clone $assetQuery)
            ->orderBy('asset_name')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.assets.assets.reports.industry-kpis.show',
            compact(
                'profile',
                'totalAssets',
                'activeAssets',
                'criticalAssets',
                'poorAssets',
                'assetValue',
                'targets',
                'industryType',
                'industryKpis',
                'assets'
            )
        );
    }

    /**
     * Build industry-specific KPI values.
     */
    private function getIndustryKpis(
        string $industryType,
        $assetQuery,
        IndustryProfile $profile
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Commercial / Retail
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $industryType,
                [
                    'commercial',
                    'commercial_retail',
                    'retail',
                ],
                true
            )
        ) {

            return [
                'occupancy_target' =>
                    $profile->occupancy_target,

                'vacancy_threshold' =>
                    $profile->vacancy_threshold,

                'performance_target' =>
                    $profile->performance_target,

                'availability_target' =>
                    $profile->availability_target,

                'primary_kpi' =>
                    $profile->primary_kpi,

                'secondary_kpi' =>
                    $profile->secondary_kpi,

                'metrics' => [
                    'Asset Count' =>
                        (clone $assetQuery)->count(),

                    'Active Assets' =>
                        (clone $assetQuery)
                            ->where(
                                'status',
                                'Active'
                            )
                            ->count(),

                    'Critical Assets' =>
                        (clone $assetQuery)
                            ->where(
                                'condition',
                                'Critical'
                            )
                            ->count(),
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Hospitality
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $industryType,
                [
                    'hospitality',
                    'hotel',
                    'hotels',
                ],
                true
            )
        ) {

            return [
                'occupancy_target' =>
                    $profile->occupancy_target,

                'performance_target' =>
                    $profile->performance_target,

                'availability_target' =>
                    $profile->availability_target,

                'utilization_target' =>
                    $profile->utilization_target,

                'primary_kpi' =>
                    $profile->primary_kpi,

                'secondary_kpi' =>
                    $profile->secondary_kpi,

                'metrics' => [
                    'Asset Count' =>
                        (clone $assetQuery)->count(),

                    'Available Assets' =>
                        (clone $assetQuery)
                            ->where(
                                'status',
                                'Active'
                            )
                            ->count(),

                    'Critical Assets' =>
                        (clone $assetQuery)
                            ->where(
                                'condition',
                                'Critical'
                            )
                            ->count(),
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Residential / Housing
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $industryType,
                [
                    'residential',
                    'residential_housing',
                    'housing',
                ],
                true
            )
        ) {

            return [
                'occupancy_target' =>
                    $profile->occupancy_target,

                'vacancy_threshold' =>
                    $profile->vacancy_threshold,

                'performance_target' =>
                    $profile->performance_target,

                'primary_kpi' =>
                    $profile->primary_kpi,

                'secondary_kpi' =>
                    $profile->secondary_kpi,

                'metrics' => [
                    'Total Units / Assets' =>
                        (clone $assetQuery)->count(),

                    'Active Units' =>
                        (clone $assetQuery)
                            ->where(
                                'status',
                                'Active'
                            )
                            ->count(),

                    'Vacant Units' =>
                        (clone $assetQuery)
                            ->where(
                                'status',
                                'Vacant'
                            )
                            ->count(),
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Industrial / Logistics
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $industryType,
                [
                    'industrial',
                    'industrial_logistics',
                    'logistics',
                ],
                true
            )
        ) {

            return [
                'utilization_target' =>
                    $profile->utilization_target,

                'availability_target' =>
                    $profile->availability_target,

                'efficiency_target' =>
                    $profile->efficiency_target,

                'performance_target' =>
                    $profile->performance_target,

                'primary_kpi' =>
                    $profile->primary_kpi,

                'secondary_kpi' =>
                    $profile->secondary_kpi,

                'metrics' => [
                    'Total Assets' =>
                        (clone $assetQuery)->count(),

                    'Available Assets' =>
                        (clone $assetQuery)
                            ->where(
                                'status',
                                'Active'
                            )
                            ->count(),

                    'Poor / Critical Assets' =>
                        (clone $assetQuery)
                            ->whereIn(
                                'condition',
                                [
                                    'Poor',
                                    'Critical',
                                ]
                            )
                            ->count(),
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Community / Institutional
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $industryType,
                [
                    'community',
                    'community_institutional',
                    'institutional',
                ],
                true
            )
        ) {

            return [
                'availability_target' =>
                    $profile->availability_target,

                'performance_target' =>
                    $profile->performance_target,

                'condition_warning_threshold' =>
                    $profile->condition_warning_threshold,

                'condition_critical_threshold' =>
                    $profile->condition_critical_threshold,

                'primary_kpi' =>
                    $profile->primary_kpi,

                'secondary_kpi' =>
                    $profile->secondary_kpi,

                'metrics' => [
                    'Total Assets' =>
                        (clone $assetQuery)->count(),

                    'Active Assets' =>
                        (clone $assetQuery)
                            ->where(
                                'status',
                                'Active'
                            )
                            ->count(),

                    'Critical Assets' =>
                        (clone $assetQuery)
                            ->where(
                                'condition',
                                'Critical'
                            )
                            ->count(),
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Generic Industry
        |--------------------------------------------------------------------------
        */

        return [
            'performance_target' =>
                $profile->performance_target,

            'availability_target' =>
                $profile->availability_target,

            'primary_kpi' =>
                $profile->primary_kpi,

            'secondary_kpi' =>
                $profile->secondary_kpi,

            'metrics' => [
                'Total Assets' =>
                    (clone $assetQuery)->count(),

                'Active Assets' =>
                    (clone $assetQuery)
                        ->where(
                            'status',
                            'Active'
                        )
                        ->count(),

                'Critical Assets' =>
                    (clone $assetQuery)
                        ->where(
                            'condition',
                            'Critical'
                        )
                        ->count(),
            ],
        ];
    }
}