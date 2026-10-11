@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Portfolio & Investment Overview</h4>

            <p class="text-muted mb-0">
                Consolidated portfolio, acquisition and investment performance
            </p>
        </div>

        <div class="d-flex gap-2">

            <button type="button"
                    class="btn btn-outline-secondary"
                    onclick="window.print()">
                <i class="ri-printer-line me-1"></i>
                Print
            </button>

            <a href="{{ route('admin.land.lands.index') }}"
               class="btn btn-primary">
                <i class="ri-landscape-line me-1"></i>
                View Land Bank
            </a>

        </div>

    </div>


    {{-- ============================================================
         ROW 1: PORTFOLIO SNAPSHOT
    ============================================================= --}}

    <h5 class="mb-3">Portfolio Snapshot</h5>

    <div class="row g-3 mb-4">

        {{-- Total Portfolio Assets --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <p class="text-muted mb-1">
                                Total Portfolio Assets
                            </p>

                            <h3 class="mb-0">
                                {{ number_format($stats['total_portfolio_assets']) }}
                            </h3>

                            <small class="text-muted">
                                Total land assets
                            </small>
                        </div>

                        <i class="ri-landscape-line fs-2 text-primary"></i>

                    </div>

                </div>
            </div>
        </div>


        {{-- Total Land Area --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <p class="text-muted mb-1">
                                Total Land Area
                            </p>

                            <h3 class="mb-0">
                                {{ number_format($stats['total_land_area'], 2) }}
                            </h3>

                            <small class="text-muted">
                                As recorded in land bank
                            </small>
                        </div>

                        <i class="ri-ruler-line fs-2 text-info"></i>

                    </div>

                </div>
            </div>
        </div>


        {{-- Total Asset Value --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <p class="text-muted mb-1">
                                Total Asset Value
                            </p>

                            <h3 class="mb-0 text-secondary">
                                {{ $stats['total_asset_value'] !== null
                                    ? '₹ ' . number_format($stats['total_asset_value'], 2)
                                    : 'N/A'
                                }}
                            </h3>

                            <small class="text-muted">
                                Valuation data required
                            </small>
                        </div>

                        <i class="ri-building-4-line fs-2 text-success"></i>

                    </div>

                </div>
            </div>
        </div>


        {{-- Total Acquisition Cost --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <p class="text-muted mb-1">
                                Total Acquisition Cost
                            </p>

                            <h3 class="mb-0">
                                ₹ {{ number_format($stats['total_acquisition_cost'], 2) }}
                            </h3>

                            <small class="text-muted">
                                Recorded acquisition costs
                            </small>
                        </div>

                        <i class="ri-money-rupee-circle-line fs-2 text-warning"></i>

                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- ============================================================
         ROW 2: ACQUISITION & INVESTMENT PIPELINE
    ============================================================= --}}

    <h5 class="mb-3">Acquisition & Investment Pipeline</h5>

    <div class="row g-3 mb-4">

        {{-- Pipeline Value --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <p class="text-muted mb-1">
                                Pipeline Value
                            </p>

                            <h3 class="mb-0 text-secondary">
                                {{ $stats['pipeline_value'] !== null
                                    ? '₹ ' . number_format($stats['pipeline_value'], 2)
                                    : 'N/A'
                                }}
                            </h3>

                            <small class="text-muted">
                                Pipeline valuation required
                            </small>
                        </div>

                        <i class="ri-funds-line fs-2 text-primary"></i>

                    </div>

                </div>
            </div>
        </div>


        {{-- Active Opportunities --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <p class="text-muted mb-1">
                                Active Opportunities
                            </p>

                            <h3 class="mb-0">
                                {{ number_format($stats['active_opportunities']) }}
                            </h3>

                            <small class="text-muted">
                                Registered land opportunities
                            </small>
                        </div>

                        <i class="ri-lightbulb-line fs-2 text-primary"></i>

                    </div>

                </div>
            </div>
        </div>


        {{-- Assets Under Due Diligence --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <p class="text-muted mb-1">
                                Assets Under Due Diligence
                            </p>

                            <h3 class="mb-0">
                                {{ number_format($stats['assets_under_due_diligence']) }}
                            </h3>

                            <small class="text-muted">
                                Pending or in progress
                            </small>
                        </div>

                        <i class="ri-file-search-line fs-2 text-warning"></i>

                    </div>

                </div>
            </div>
        </div>


        {{-- Pending Investment Decisions --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <p class="text-muted mb-1">
                                Pending Investment Decisions
                            </p>

                            <h3 class="mb-0">
                                {{ number_format($stats['pending_investment_decisions']) }}
                            </h3>

                            <small class="text-muted">
                                Awaiting review or approval
                            </small>
                        </div>

                        <i class="ri-time-line fs-2 text-danger"></i>

                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- ============================================================
         ROW 3: FINANCIAL OUTLOOK
    ============================================================= --}}

    <h5 class="mb-3">Financial Outlook</h5>

    <div class="row g-3 mb-4">

        {{-- Total Development Cost --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <p class="text-muted mb-1">
                        Total Development Cost
                    </p>

                    <h3 class="mb-0 text-secondary">
                        {{ $stats['total_development_cost'] !== null
                            ? '₹ ' . number_format($stats['total_development_cost'], 2)
                            : 'N/A'
                        }}
                    </h3>

                    <small class="text-muted">
                        Development cost source required
                    </small>

                </div>
            </div>
        </div>


        {{-- Forecast Revenue --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <p class="text-muted mb-1">
                        Forecast Revenue
                    </p>

                    <h3 class="mb-0 text-secondary">
                        {{ $stats['forecast_revenue'] !== null
                            ? '₹ ' . number_format($stats['forecast_revenue'], 2)
                            : 'N/A'
                        }}
                    </h3>

                    <small class="text-muted">
                        Revenue forecast source required
                    </small>

                </div>
            </div>
        </div>


        {{-- Forecast NOI --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <p class="text-muted mb-1">
                        Forecast NOI
                    </p>

                    <h3 class="mb-0 text-secondary">
                        {{ $stats['forecast_noi'] !== null
                            ? '₹ ' . number_format($stats['forecast_noi'], 2)
                            : 'N/A'
                        }}
                    </h3>

                    <small class="text-muted">
                        NOI forecast source required
                    </small>

                </div>
            </div>
        </div>


        {{-- Planned Capital --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <p class="text-muted mb-1">
                        Planned Capital
                    </p>

                    <h3 class="mb-0 text-secondary">
                        {{ $stats['planned_capital'] !== null
                            ? '₹ ' . number_format($stats['planned_capital'], 2)
                            : 'N/A'
                        }}
                    </h3>

                    <small class="text-muted">
                        Capital planning source required
                    </small>

                </div>
            </div>
        </div>

    </div>


    {{-- ============================================================
         ROW 4: INVESTMENT RETURNS
    ============================================================= --}}

    <h5 class="mb-3">Investment Returns</h5>

    <div class="row g-3 mb-4">

        {{-- IRR --}}
        <div class="col-xl col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <p class="text-muted mb-1">IRR</p>

                    <h3 class="mb-0 text-secondary">
                        {{ $stats['irr'] !== null
                            ? number_format($stats['irr'], 2) . '%'
                            : 'N/A'
                        }}
                    </h3>

                    <small class="text-muted">
                        Internal Rate of Return
                    </small>

                </div>
            </div>
        </div>


        {{-- ROI --}}
        <div class="col-xl col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <p class="text-muted mb-1">ROI</p>

                    <h3 class="mb-0 text-secondary">
                        {{ $stats['roi'] !== null
                            ? number_format($stats['roi'], 2) . '%'
                            : 'N/A'
                        }}
                    </h3>

                    <small class="text-muted">
                        Return on Investment
                    </small>

                </div>
            </div>
        </div>


        {{-- NPV --}}
        <div class="col-xl col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <p class="text-muted mb-1">NPV</p>

                    <h3 class="mb-0 text-secondary">
                        {{ $stats['npv'] !== null
                            ? '₹ ' . number_format($stats['npv'], 2)
                            : 'N/A'
                        }}
                    </h3>

                    <small class="text-muted">
                        Net Present Value
                    </small>

                </div>
            </div>
        </div>


        {{-- Yield --}}
        <div class="col-xl col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <p class="text-muted mb-1">Yield</p>

                    <h3 class="mb-0 text-secondary">
                        {{ $stats['yield'] !== null
                            ? number_format($stats['yield'], 2) . '%'
                            : 'N/A'
                        }}
                    </h3>

                    <small class="text-muted">
                        Investment yield
                    </small>

                </div>
            </div>
        </div>


        {{-- Payback Period --}}
        <div class="col-xl col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <p class="text-muted mb-1">Payback Period</p>

                    <h3 class="mb-0 text-secondary">
                        {{ $stats['payback_period'] !== null
                            ? number_format($stats['payback_period'], 2)
                            : 'N/A'
                        }}
                    </h3>

                    <small class="text-muted">
                        Payback period
                    </small>

                </div>
            </div>
        </div>

    </div>


    {{-- ============================================================
         EXISTING INVESTMENT SUMMARY
    ============================================================= --}}

    <h5 class="mb-3">Investment Evaluation Summary</h5>

    <div class="row g-3 mb-4">

        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">
                        Feasibility Assessments
                    </p>

                    <h3 class="mb-0">
                        {{ number_format($stats['total_feasibility']) }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">
                        Investment Analyses
                    </p>

                    <h3 class="mb-0">
                        {{ number_format($stats['total_investment_analyses']) }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">
                        Approved Investment
                    </p>

                    <h3 class="mb-0 text-success">
                        ₹ {{ number_format($stats['approved_investment'], 2) }}
                    </h3>
                </div>
            </div>
        </div>

    </div>


    {{-- ============================================================
         ACQUISITION PIPELINE & LAND TYPE SUMMARY
    ============================================================= --}}

    <div class="row g-4 mb-4">

        {{-- Acquisition Pipeline --}}
        <div class="col-xl-5">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">
                    <h5 class="mb-0">Acquisition Pipeline</h5>
                </div>

                <div class="card-body">

                    @forelse($acquisitionPipeline as $pipeline)

                        @php
                            $pipelinePercentage = $stats['total_portfolio_assets'] > 0
                                ? ($pipeline->total / $stats['total_portfolio_assets']) * 100
                                : 0;
                        @endphp

                        <div class="mb-3">

                            <div class="d-flex justify-content-between align-items-center mb-1">

                                <span>
                                    {{ $pipeline->acquisition_status ?: 'Not Specified' }}
                                </span>

                                <span class="badge bg-primary">
                                    {{ $pipeline->total }}
                                </span>

                            </div>

                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar"
                                     role="progressbar"
                                     style="width: {{ $pipelinePercentage }}%;">
                                </div>
                            </div>

                        </div>

                    @empty

                        <div class="text-muted text-center py-4">
                            No acquisition pipeline data available.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- Land Type Summary --}}
        <div class="col-xl-7">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">
                    <h5 class="mb-0">Land Type Distribution</h5>
                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Land Type</th>
                                    <th>Total Lands</th>
                                    <th>Total Area</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($landTypeSummary as $index => $landType)

                                    <tr>
                                        <td>{{ $index + 1 }}</td>

                                        <td>
                                            {{ $landType->land_type ?: 'Not Specified' }}
                                        </td>

                                        <td>
                                            {{ number_format($landType->total) }}
                                        </td>

                                        <td>
                                            {{ number_format($landType->total_area, 2) }}
                                        </td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="4"
                                            class="text-center text-muted py-4">
                                            No land type data available.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         RECENT INVESTMENT DECISIONS
    ============================================================= --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">
            <h5 class="mb-0">Recent Investment Decisions</h5>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>Decision No.</th>
                            <th>Title</th>
                            <th>Land</th>
                            <th>Status</th>
                            <th>Decision Date</th>
                            <th>Approved Amount</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($recentDecisions as $decision)

                            <tr>

                                <td>
                                    {{ $decision->decision_number ?: '—' }}
                                </td>

                                <td>
                                    {{ $decision->title ?: '—' }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $decision->land_code ?: '—' }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ $decision->land_name ?: '—' }}
                                    </small>
                                </td>

                                <td>

                                    @php
                                        $statusClass = match (strtolower((string) $decision->status)) {
                                            'approved' => 'success',
                                            'rejected' => 'danger',
                                            'pending', 'under review' => 'warning',
                                            default => 'secondary',
                                        };
                                    @endphp

                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ $decision->status ?: '—' }}
                                    </span>

                                </td>

                                <td>
                                    {{ $decision->decision_date
                                        ? \Carbon\Carbon::parse($decision->decision_date)->format('d-m-Y')
                                        : '—'
                                    }}
                                </td>

                                <td>
                                    ₹ {{ number_format($decision->approved_investment ?? 0, 2) }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6"
                                    class="text-center text-muted py-4">
                                    No investment decisions found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection