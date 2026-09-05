@extends('layouts.app')

@section('title', 'Asset Performance')

@section('content')

<style>
    .performance-dashboard {
        padding: 24px 0 50px;
    }

    .performance-hero {
        border-radius: 20px;
        padding: 25px;
        background: linear-gradient(
            135deg,
            #ffffff 0%,
            #f7f9fc 100%
        );
        border: 1px solid #e8ecf2;
        box-shadow: 0 8px 30px rgba(0,0,0,.05);
    }

    .hero-icon {
        width: 52px;
        height: 52px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eef4ff;
        color: #0d6efd;
        font-size: 24px;
    }

    .metric-card {
        background: #fff;
        border: 1px solid #e8ecf2;
        border-radius: 17px;
        padding: 20px;
        height: 100%;
        box-shadow: 0 5px 22px rgba(0,0,0,.04);
    }

    .metric-label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .6px;
        color: #7a8491;
    }

    .metric-value {
        font-size: 27px;
        font-weight: 800;
        margin-top: 7px;
        color: #17202a;
    }

    .metric-icon {
        width: 43px;
        height: 43px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
    }

    .section-card {
        background: #fff;
        border: 1px solid #e8ecf2;
        border-radius: 18px;
        box-shadow: 0 5px 22px rgba(0,0,0,.04);
        overflow: hidden;
    }

    .section-header {
        padding: 18px 20px;
        border-bottom: 1px solid #edf0f4;
    }

    .section-body {
        padding: 20px;
    }

    .table thead th {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #6c757d;
        white-space: nowrap;
    }

    .table tbody td {
        vertical-align: middle;
    }

    .asset-name {
        font-weight: 700;
        color: #202831;
    }

    .small-muted {
        color: #8a94a1;
        font-size: 12px;
    }

    .filter-card {
        background: #fff;
        border: 1px solid #e8ecf2;
        border-radius: 18px;
        padding: 18px;
        box-shadow: 0 5px 22px rgba(0,0,0,.03);
    }

    @media(max-width: 767px) {
        .performance-dashboard {
            padding-top: 15px;
        }

        .performance-hero {
            padding: 18px;
        }

        .metric-value {
            font-size: 23px;
        }
    }
</style>

<div class="container-fluid performance-dashboard">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="performance-hero mb-4">

        <div class="d-flex flex-column flex-lg-row
                    justify-content-between
                    align-items-lg-center gap-3">

            <div class="d-flex align-items-start gap-3">

                <div class="hero-icon">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>

                <div>

                    <div class="text-uppercase
                                small fw-bold text-muted mb-1"
                         style="letter-spacing:1px;">
                        Asset Management
                    </div>

                    <h3 class="fw-bold mb-1">
                        Asset Performance
                    </h3>

                    <p class="text-muted mb-0">
                        Monitor income, expenses, profitability,
                        NOI and ROI across your assets.
                    </p>

                </div>

            </div>

            <div>

                <a href="{{ route('admin.assets.assets.index') }}"
                   class="btn btn-outline-secondary btn-sm">

                    <i class="bi bi-box-seam me-1"></i>

                    Asset Register

                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
         FILTER
    ========================================================== --}}

    <div class="filter-card mb-4">

        <form method="GET"
              action="{{ route('admin.assets.performance.index') }}">

            <div class="row g-3 align-items-end">

                {{-- Asset --}}
                <div class="col-lg-4 col-md-6">

                    <label class="form-label fw-semibold small">
                        Asset
                    </label>

                    <select name="asset"
                            class="form-select">

                        <option value="">
                            All Assets
                        </option>

                        @foreach($assets as $asset)

                            <option value="{{ $asset->id }}"
                                @selected($assetId == $asset->id)>

                                {{ $asset->asset_code ?? 'ASSET-'.$asset->id }}

                                @if(!empty($asset->name))
                                    - {{ $asset->name }}
                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- From --}}
                <div class="col-lg-3 col-md-6">

                    <label class="form-label fw-semibold small">
                        From Date
                    </label>

                    <input type="date"
                           name="from_date"
                           class="form-control"
                           value="{{ request('from_date') }}">

                </div>


                {{-- To --}}
                <div class="col-lg-3 col-md-6">

                    <label class="form-label fw-semibold small">
                        To Date
                    </label>

                    <input type="date"
                           name="to_date"
                           class="form-control"
                           value="{{ request('to_date') }}">

                </div>


                <div class="col-lg-2 col-md-6 d-flex gap-2">

                    <button class="btn btn-primary w-100">

                        <i class="bi bi-funnel me-1"></i>

                        Filter

                    </button>

                    <a href="{{ route('admin.assets.performance.index') }}"
                       class="btn btn-light border">

                        <i class="bi bi-arrow-clockwise"></i>

                    </a>

                </div>

            </div>

        </form>

    </div>


    {{-- =========================================================
         SELECTED ASSET
    ========================================================== --}}

    @if($selectedAsset)

        <div class="alert alert-primary d-flex
                    justify-content-between
                    align-items-center mb-4">

            <div>

                <strong>
                    {{ $selectedAsset->asset_code
                        ?? 'Asset #'.$selectedAsset->id }}
                </strong>

                @if(!empty($selectedAsset->name))

                    <span class="ms-2">
                        {{ $selectedAsset->name }}
                    </span>

                @endif

            </div>

            <a href="{{ route(
                'admin.assets.performance.show',
                $selectedAsset->id
            ) }}"
               class="btn btn-sm btn-primary">

                View Details

            </a>

        </div>

    @endif


    {{-- =========================================================
         KPI CARDS
    ========================================================== --}}

    <div class="row g-4 mb-4">

        {{-- Income --}}
        <div class="col-xl-3 col-md-6">

            <div class="metric-card">

                <div class="d-flex
                            justify-content-between
                            align-items-center">

                    <div>

                        <div class="metric-label">
                            Total Income
                        </div>

                        <div class="metric-value">
                            ₹{{ number_format($totalIncome, 2) }}
                        </div>

                    </div>

                    <div class="metric-icon bg-success-subtle text-success">

                        <i class="bi bi-arrow-down-left"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- Expense --}}
        <div class="col-xl-3 col-md-6">

            <div class="metric-card">

                <div class="d-flex
                            justify-content-between
                            align-items-center">

                    <div>

                        <div class="metric-label">
                            Total Expenses
                        </div>

                        <div class="metric-value">
                            ₹{{ number_format($totalExpense, 2) }}
                        </div>

                    </div>

                    <div class="metric-icon bg-danger-subtle text-danger">

                        <i class="bi bi-arrow-up-right"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- NOI --}}
        <div class="col-xl-3 col-md-6">

            <div class="metric-card">

                <div class="d-flex
                            justify-content-between
                            align-items-center">

                    <div>

                        <div class="metric-label">
                            Net Operating Income
                        </div>

                        <div class="metric-value">
                            ₹{{ number_format($noi, 2) }}
                        </div>

                    </div>

                    <div class="metric-icon bg-primary-subtle text-primary">

                        <i class="bi bi-cash-stack"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- ROI --}}
        <div class="col-xl-3 col-md-6">

            <div class="metric-card">

                <div class="d-flex
                            justify-content-between
                            align-items-center">

                    <div>

                        <div class="metric-label">
                            ROI
                        </div>

                        <div class="metric-value">
                            {{ number_format($roi, 2) }}%
                        </div>

                    </div>

                    <div class="metric-icon bg-warning-subtle text-warning">

                        <i class="bi bi-percent"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         SECONDARY KPI
    ========================================================== --}}

    <div class="row g-4 mb-4">

        <div class="col-xl-4 col-md-6">

            <div class="metric-card">

                <div class="metric-label">
                    Total Investment
                </div>

                <div class="metric-value">
                    ₹{{ number_format($investment, 2) }}
                </div>

            </div>

        </div>


        <div class="col-xl-4 col-md-6">

            <div class="metric-card">

                <div class="metric-label">
                    Profit Margin
                </div>

                <div class="metric-value">
                    {{ number_format($profitMargin, 2) }}%
                </div>

            </div>

        </div>


        <div class="col-xl-4 col-md-6">

            <div class="metric-card">

                <div class="metric-label">
                    Performance Status
                </div>

                <div class="metric-value">

                    @if($roi >= 15)

                        <span class="badge bg-success fs-6">
                            Excellent
                        </span>

                    @elseif($roi >= 8)

                        <span class="badge bg-primary fs-6">
                            Good
                        </span>

                    @elseif($roi >= 0)

                        <span class="badge bg-warning text-dark fs-6">
                            Average
                        </span>

                    @else

                        <span class="badge bg-danger fs-6">
                            Loss
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         CHART
    ========================================================== --}}

    <div class="section-card mb-4">

        <div class="section-header">

            <h5 class="fw-bold mb-1">
                Income vs Expense
            </h5>

            <div class="small-muted">
                Monthly financial performance
            </div>

        </div>

        <div class="section-body">

            @if($chartData->count())

                <div
                    style="height:360px;"
                    id="performanceChart">
                </div>

            @else

                <div class="text-center
                            text-muted py-5">

                    <i class="bi bi-bar-chart fs-1"></i>

                    <div class="mt-2">
                        No financial data available.
                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
         ASSET PERFORMANCE TABLE
    ========================================================== --}}

    <div class="section-card">

        <div class="section-header
                    d-flex justify-content-between
                    align-items-center">

            <div>

                <h5 class="fw-bold mb-1">
                    Asset Performance Ranking
                </h5>

                <div class="small-muted">
                    Assets ranked by ROI
                </div>

            </div>

            <span class="badge bg-light text-dark">

                {{ $performance->count() }} Assets

            </span>

        </div>

        <div class="section-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>

                            <th class="ps-4">
                                #
                            </th>

                            <th>
                                Asset
                            </th>

                            <th>
                                Investment
                            </th>

                            <th>
                                Income
                            </th>

                            <th>
                                Expense
                            </th>

                            <th>
                                NOI
                            </th>

                            <th>
                                Margin
                            </th>

                            <th>
                                ROI
                            </th>

                            <th class="text-end pe-4">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($performance as $index => $row)

                            <tr>

                                <td class="ps-4">

                                    {{ $index + 1 }}

                                </td>

                                <td>

                                    <div class="asset-name">

                                        {{ $row->asset->asset_code
                                            ?? 'ASSET-'.$row->asset->id }}

                                    </div>

                                    @if(!empty($row->asset->name))

                                        <div class="small-muted">
                                            {{ $row->asset->name }}
                                        </div>

                                    @endif

                                </td>

                                <td>

                                    ₹{{ number_format(
                                        $row->investment,
                                        0
                                    ) }}

                                </td>

                                <td class="text-success">

                                    ₹{{ number_format(
                                        $row->income,
                                        0
                                    ) }}

                                </td>

                                <td class="text-danger">

                                    ₹{{ number_format(
                                        $row->expense,
                                        0
                                    ) }}

                                </td>

                                <td class="fw-bold">

                                    ₹{{ number_format(
                                        $row->noi,
                                        0
                                    ) }}

                                </td>

                                <td>

                                    {{ number_format(
                                        $row->margin,
                                        2
                                    ) }}%

                                </td>

                                <td>

                                    @if($row->roi >= 15)

                                        <span class="badge bg-success">

                                            {{ number_format(
                                                $row->roi,
                                                2
                                            ) }}%

                                        </span>

                                    @elseif($row->roi >= 8)

                                        <span class="badge bg-primary">

                                            {{ number_format(
                                                $row->roi,
                                                2
                                            ) }}%

                                        </span>

                                    @elseif($row->roi >= 0)

                                        <span class="badge bg-warning text-dark">

                                            {{ number_format(
                                                $row->roi,
                                                2
                                            ) }}%

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            {{ number_format(
                                                $row->roi,
                                                2
                                            ) }}%

                                        </span>

                                    @endif

                                </td>

                                <td class="text-end pe-4">

                                    <a href="{{ route(
                                        'admin.assets.performance.show',
                                        $row->asset->id
                                    ) }}"
                                       class="btn btn-sm
                                              btn-outline-primary">

                                        <i class="bi bi-eye"></i>

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center
                                           text-muted py-5">

                                    No assets found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     CHART.JS
========================================================= --}}

@if($chartData->count())

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const chartElement =
        document.getElementById('performanceChart');

    if (!chartElement) {
        return;
    }

    const canvas =
        document.createElement('canvas');

    chartElement.appendChild(canvas);

    new Chart(canvas, {

        type: 'line',

        data: {

            labels: @json(
                $chartData->pluck('month')->values()
            ),

            datasets: [

                {
                    label: 'Income',

                    data: @json(
                        $chartData->pluck('income')->values()
                    ),

                    tension: 0.35,

                    fill: false
                },

                {
                    label: 'Expense',

                    data: @json(
                        $chartData->pluck('expense')->values()
                    ),

                    tension: 0.35,

                    fill: false
                },

                {
                    label: 'NOI',

                    data: @json(
                        $chartData->pluck('noi')->values()
                    ),

                    tension: 0.35,

                    fill: false
                }

            ]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            interaction: {
                mode: 'index',
                intersect: false
            },

            plugins: {

                legend: {
                    position: 'bottom'
                }

            },

            scales: {

                y: {

                    beginAtZero: true,

                    ticks: {

                        callback: function(value) {

                            return '₹' +
                                Number(value)
                                    .toLocaleString('en-IN');

                        }

                    }

                }

            }

        }

    });

});

</script>

@endif

@endsection