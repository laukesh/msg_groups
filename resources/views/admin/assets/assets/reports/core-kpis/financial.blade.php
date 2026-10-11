@extends('layouts.app')

@section('title', 'Financial KPI Report')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="ri-money-dollar-circle-line me-2"></i>
                Asset Financial KPI
            </h4>

            <p class="text-muted mb-0">
                Revenue, operating expenses, NOI, CAPEX and investment performance
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.assets.reports.core-kpis.index') }}"
               class="btn btn-outline-secondary">
                <i class="ri-arrow-left-line me-1"></i>
                Core KPIs
            </a>

            <button type="button"
                    class="btn btn-outline-primary"
                    onclick="window.print()">
                <i class="ri-printer-line me-1"></i>
                Print
            </button>

        </div>

    </div>


    {{-- =========================================================
        SUMMARY CARDS
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- Total Revenue --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                Total Revenue
                            </div>

                            <h3 class="mb-0 mt-2">
                                {{ number_format(
                                    (float) ($totalRevenue ?? 0),
                                    2
                                ) }}
                            </h3>

                        </div>

                        <div class="fs-2 text-success">
                            <i class="ri-money-dollar-circle-line"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Operating Expenses --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                Operating Expenses
                            </div>

                            <h3 class="mb-0 mt-2">
                                {{ number_format(
                                    (float) ($totalExpenses ?? 0),
                                    2
                                ) }}
                            </h3>

                        </div>

                        <div class="fs-2 text-danger">
                            <i class="ri-wallet-3-line"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- NOI --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                Net Operating Income
                            </div>

                            <h3 class="mb-0 mt-2">

                                {{ number_format(
                                    (float) ($noi ?? 0),
                                    2
                                ) }}

                            </h3>

                        </div>

                        <div class="fs-2 text-primary">
                            <i class="ri-funds-line"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ROI --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                ROI
                            </div>

                            <h3 class="mb-0 mt-2">

                                {{ number_format(
                                    (float) ($roi ?? 0),
                                    2
                                ) }}%

                            </h3>

                        </div>

                        <div class="fs-2 text-info">
                            <i class="ri-line-chart-line"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FINANCIAL OVERVIEW
    ========================================================== --}}
    <div class="row g-4 mb-4">

        {{-- Revenue / Expense / NOI --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0">
                        <i class="ri-bar-chart-grouped-line me-2"></i>
                        Financial Overview
                    </h5>

                </div>


                <div class="card-body">

                    @php

                        $revenue =
                            (float) ($totalRevenue ?? 0);

                        $expenses =
                            (float) ($totalExpenses ?? 0);

                        $netIncome =
                            (float) ($noi ?? ($revenue - $expenses));

                        $maxFinancialValue =
                            max(
                                abs($revenue),
                                abs($expenses),
                                abs($netIncome),
                                1
                            );

                        $revenuePercentage =
                            ($revenue / $maxFinancialValue) * 100;

                        $expensePercentage =
                            ($expenses / $maxFinancialValue) * 100;

                        $noiPercentage =
                            (abs($netIncome) / $maxFinancialValue) * 100;

                    @endphp


                    {{-- Revenue --}}
                    <div class="mb-4">

                        <div class="d-flex justify-content-between mb-1">

                            <span class="text-muted">
                                Revenue
                            </span>

                            <strong>
                                {{ number_format($revenue, 2) }}
                            </strong>

                        </div>

                        <div class="progress"
                             style="height: 12px;">

                            <div class="progress-bar bg-success"
                                 style="width: {{ min($revenuePercentage, 100) }}%;">
                            </div>

                        </div>

                    </div>


                    {{-- Expenses --}}
                    <div class="mb-4">

                        <div class="d-flex justify-content-between mb-1">

                            <span class="text-muted">
                                Operating Expenses
                            </span>

                            <strong>
                                {{ number_format($expenses, 2) }}
                            </strong>

                        </div>

                        <div class="progress"
                             style="height: 12px;">

                            <div class="progress-bar bg-danger"
                                 style="width: {{ min($expensePercentage, 100) }}%;">
                            </div>

                        </div>

                    </div>


                    {{-- NOI --}}
                    <div>

                        <div class="d-flex justify-content-between mb-1">

                            <span class="text-muted">
                                Net Operating Income
                            </span>

                            <strong>
                                {{ number_format($netIncome, 2) }}
                            </strong>

                        </div>

                        <div class="progress"
                             style="height: 12px;">

                            <div class="progress-bar"
                                 style="width: {{ min($noiPercentage, 100) }}%;">
                            </div>

                        </div>

                    </div>


                    {{-- NOI Margin --}}
                    @php

                        $noiMargin =
                            $revenue > 0
                                ? ($netIncome / $revenue) * 100
                                : 0;

                    @endphp


                    <div class="mt-4 p-3 bg-light rounded">

                        <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                NOI Margin
                            </span>

                            <strong>
                                {{ number_format($noiMargin, 2) }}%
                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Financial Ratios --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0">
                        <i class="ri-pie-chart-line me-2"></i>
                        Financial Ratios
                    </h5>

                </div>


                <div class="card-body">

                    @php

                        $expenseRatio =
                            $revenue > 0
                                ? ($expenses / $revenue) * 100
                                : 0;

                        $profitMargin =
                            $revenue > 0
                                ? ($netIncome / $revenue) * 100
                                : 0;

                        $capex =
                            (float) ($totalCapex ?? 0);

                        $capexToRevenue =
                            $revenue > 0
                                ? ($capex / $revenue) * 100
                                : 0;

                    @endphp


                    {{-- Expense Ratio --}}
                    <div class="mb-4">

                        <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                Expense Ratio
                            </span>

                            <strong>
                                {{ number_format(
                                    $expenseRatio,
                                    2
                                ) }}%
                            </strong>

                        </div>

                        <div class="progress mt-2"
                             style="height: 8px;">

                            <div class="progress-bar bg-danger"
                                 style="width: {{ min(max($expenseRatio, 0), 100) }}%;">
                            </div>

                        </div>

                    </div>


                    {{-- NOI Margin --}}
                    <div class="mb-4">

                        <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                NOI Margin
                            </span>

                            <strong>
                                {{ number_format(
                                    $profitMargin,
                                    2
                                ) }}%
                            </strong>

                        </div>

                        <div class="progress mt-2"
                             style="height: 8px;">

                            <div class="progress-bar"
                                 style="width: {{ min(max($profitMargin, 0), 100) }}%;">
                            </div>

                        </div>

                    </div>


                    {{-- CAPEX --}}
                    <div class="mb-4">

                        <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                CAPEX
                            </span>

                            <strong>
                                {{ number_format(
                                    $capex,
                                    2
                                ) }}
                            </strong>

                        </div>

                    </div>


                    {{-- CAPEX Ratio --}}
                    <div>

                        <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                CAPEX / Revenue
                            </span>

                            <strong>
                                {{ number_format(
                                    $capexToRevenue,
                                    2
                                ) }}%
                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FINANCIAL BY INDUSTRY
    ========================================================== --}}
    @if(isset($financialByIndustry) && count($financialByIndustry))

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="mb-0">
                    <i class="ri-bar-chart-grouped-line me-2"></i>
                    Financial Performance by Industry
                </h5>

                <small class="text-muted">
                    Revenue, expenses and NOI grouped by industry operating profile
                </small>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>
                                    Industry Profile
                                </th>

                                <th class="text-center">
                                    Assets
                                </th>

                                <th class="text-end">
                                    Revenue
                                </th>

                                <th class="text-end">
                                    Expenses
                                </th>

                                <th class="text-end">
                                    NOI
                                </th>

                                <th class="text-center">
                                    NOI Margin
                                </th>

                                <th class="text-center">
                                    ROI
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($financialByIndustry as $index => $industry)

                                @php

                                    $industryAssets =
                                        (int) data_get(
                                            $industry,
                                            'total_assets',
                                            0
                                        );

                                    $industryRevenue =
                                        (float) data_get(
                                            $industry,
                                            'revenue',
                                            0
                                        );

                                    $industryExpenses =
                                        (float) data_get(
                                            $industry,
                                            'expenses',
                                            0
                                        );

                                    $industryNoi =
                                        (float) data_get(
                                            $industry,
                                            'noi',
                                            $industryRevenue -
                                            $industryExpenses
                                        );

                                    $industryRoi =
                                        (float) data_get(
                                            $industry,
                                            'roi',
                                            0
                                        );

                                    $industryMargin =
                                        $industryRevenue > 0
                                            ? (
                                                $industryNoi /
                                                $industryRevenue
                                            ) * 100
                                            : 0;

                                @endphp


                                <tr>

                                    <td>
                                        {{ $index + 1 }}
                                    </td>


                                    <td>

                                        <div class="fw-semibold">

                                            {{ data_get(
                                                $industry,
                                                'profile_name',
                                                'N/A'
                                            ) }}

                                        </div>


                                        @if(data_get($industry, 'industry_type'))

                                            <small class="text-muted">

                                                {{ data_get(
                                                    $industry,
                                                    'industry_type'
                                                ) }}

                                            </small>

                                        @endif

                                    </td>


                                    <td class="text-center">
                                        {{ number_format($industryAssets) }}
                                    </td>


                                    <td class="text-end text-success fw-semibold">

                                        {{ number_format(
                                            $industryRevenue,
                                            2
                                        ) }}

                                    </td>


                                    <td class="text-end text-danger">

                                        {{ number_format(
                                            $industryExpenses,
                                            2
                                        ) }}

                                    </td>


                                    <td class="text-end fw-semibold">

                                        {{ number_format(
                                            $industryNoi,
                                            2
                                        ) }}

                                    </td>


                                    <td class="text-center">

                                        {{ number_format(
                                            $industryMargin,
                                            2
                                        ) }}%

                                    </td>


                                    <td class="text-center">

                                        {{ number_format(
                                            $industryRoi,
                                            2
                                        ) }}%

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        ASSET FINANCIAL DETAILS
    ========================================================== --}}
    @if(isset($assets))

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 py-3">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                    <div>

                        <h5 class="mb-0">
                            <i class="ri-list-check-2 me-2"></i>
                            Asset Financial Details
                        </h5>

                        <small class="text-muted">
                            Financial summary linked to each asset
                        </small>

                    </div>


                    @if(method_exists($assets, 'total'))

                        <span class="badge bg-light text-dark">

                            {{ number_format(
                                $assets->total()
                            ) }}

                            Assets

                        </span>

                    @endif

                </div>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Asset Code
                                </th>

                                <th>
                                    Asset Name
                                </th>

                                <th>
                                    Industry Profile
                                </th>

                                <th class="text-end">
                                    Revenue
                                </th>

                                <th class="text-end">
                                    Expenses
                                </th>

                                <th class="text-end">
                                    NOI
                                </th>

                                <th class="text-end">
                                    CAPEX
                                </th>

                                <th class="text-center">
                                    ROI
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($assets as $asset)

                                @php

                                    /*
                                     * The controller can attach these
                                     * calculated values to the Asset model:
                                     *
                                     * financial_revenue
                                     * financial_expenses
                                     * financial_noi
                                     * financial_capex
                                     * financial_roi
                                     */

                                    $assetRevenue =
                                        (float) (
                                            $asset->financial_revenue
                                            ?? 0
                                        );

                                    $assetExpenses =
                                        (float) (
                                            $asset->financial_expenses
                                            ?? 0
                                        );

                                    $assetNoi =
                                        (float) (
                                            $asset->financial_noi
                                            ?? (
                                                $assetRevenue -
                                                $assetExpenses
                                            )
                                        );

                                    $assetCapex =
                                        (float) (
                                            $asset->financial_capex
                                            ?? 0
                                        );

                                    $assetRoi =
                                        (float) (
                                            $asset->financial_roi
                                            ?? 0
                                        );

                                @endphp


                                <tr>

                                    {{-- Asset Code --}}
                                    <td>

                                        <span class="fw-semibold">
                                            {{ $asset->asset_code ?? '—' }}
                                        </span>

                                    </td>


                                    {{-- Asset Name --}}
                                    <td>
                                        {{ $asset->asset_name ?? '—' }}
                                    </td>


                                    {{-- Industry --}}
                                    <td>

                                        {{ optional(
                                            $asset->industryProfile
                                        )->profile_name ?? '—' }}

                                    </td>


                                    {{-- Revenue --}}
                                    <td class="text-end text-success">

                                        {{ number_format(
                                            $assetRevenue,
                                            2
                                        ) }}

                                    </td>


                                    {{-- Expenses --}}
                                    <td class="text-end text-danger">

                                        {{ number_format(
                                            $assetExpenses,
                                            2
                                        ) }}

                                    </td>


                                    {{-- NOI --}}
                                    <td class="text-end fw-semibold">

                                        {{ number_format(
                                            $assetNoi,
                                            2
                                        ) }}

                                    </td>


                                    {{-- CAPEX --}}
                                    <td class="text-end">

                                        {{ number_format(
                                            $assetCapex,
                                            2
                                        ) }}

                                    </td>


                                    {{-- ROI --}}
                                    <td class="text-center">

                                        @if($assetRoi > 0)

                                            <span class="badge bg-success">

                                                {{ number_format(
                                                    $assetRoi,
                                                    2
                                                ) }}%

                                            </span>

                                        @elseif($assetRoi < 0)

                                            <span class="badge bg-danger">

                                                {{ number_format(
                                                    $assetRoi,
                                                    2
                                                ) }}%

                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                0.00%
                                            </span>

                                        @endif

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td colspan="8"
                                        class="text-center py-5 text-muted">

                                        <i class="ri-inbox-line fs-1 d-block mb-2"></i>

                                        No financial records found.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- Pagination --}}
            @if(
                method_exists($assets, 'hasPages')
                && $assets->hasPages()
            )

                <div class="card-footer bg-white border-0">

                    {{ $assets->links() }}

                </div>

            @endif

        </div>

    @endif

</div>


{{-- =========================================================
    PRINT CSS
========================================================== --}}
<style>

    @media print {

        .sidebar,
        .navbar,
        .topbar,
        .btn,
        .pagination {
            display: none !important;
        }

        body {
            background: #ffffff !important;
        }

        .container-fluid {
            width: 100% !important;
            max-width: 100% !important;
        }

        .card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }

    }

</style>

@endsection