@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h4 class="mb-1">
                <i class="ri-dashboard-3-line me-1"></i>
                Core Asset KPIs
            </h4>

            <p class="text-muted mb-0">
                Asset portfolio performance, condition, availability and financial overview.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.assets.assets.index') }}"
               class="btn btn-light border">
                <i class="ri-building-line me-1"></i>
                Asset Register
            </a>

            <a href="{{ route('admin.assets.reports.industry-kpis.index') }}"
               class="btn btn-primary">
                <i class="ri-bar-chart-grouped-line me-1"></i>
                Industry KPIs
            </a>

        </div>

    </div>


    {{-- KPI CARDS --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <a href="{{ route('admin.assets.reports.core-kpis.assets') }}"
               class="text-decoration-none">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted small">
                            Total Assets
                        </div>

                        <h3 class="mb-1">
                            {{ number_format($totalAssets) }}
                        </h3>

                        <small class="text-primary">
                            View Asset Register
                            <i class="ri-arrow-right-line"></i>
                        </small>

                    </div>

                </div>

            </a>

        </div>


        <div class="col-xl-3 col-md-6">

            <a href="{{ route('admin.assets.reports.core-kpis.performance') }}"
               class="text-decoration-none">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted small">
                            Active Assets
                        </div>

                        <h3 class="mb-1">
                            {{ number_format($activeAssets) }}
                        </h3>

                        <small>
                            Availability:
                            {{ number_format($activeRate, 2) }}%
                        </small>

                    </div>

                </div>

            </a>

        </div>


        <div class="col-xl-3 col-md-6">

            <a href="{{ route('admin.assets.reports.core-kpis.condition') }}"
               class="text-decoration-none">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted small">
                            Critical Assets
                        </div>

                        <h3 class="mb-1">
                            {{ number_format($criticalAssets) }}
                        </h3>

                        <small>
                            {{ number_format($criticalRate, 2) }}%
                            of portfolio
                        </small>

                    </div>

                </div>

            </a>

        </div>


        <div class="col-xl-3 col-md-6">

            <a href="{{ route('admin.assets.reports.core-kpis.financial') }}"
               class="text-decoration-none">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted small">
                            Asset Value
                        </div>

                        <h3 class="mb-1">
                            {{ number_format($totalAssetValue, 2) }}
                        </h3>

                        <small>
                            Financial Overview
                            <i class="ri-arrow-right-line"></i>
                        </small>

                    </div>

                </div>

            </a>

        </div>

    </div>


    {{-- SECONDARY KPIs --}}
    <div class="row g-3 mb-4">

        <div class="col-lg-4">

            <a href="{{ route('admin.assets.reports.core-kpis.condition') }}"
               class="text-decoration-none">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header bg-white">
                        <strong>
                            <i class="ri-shield-check-line me-1"></i>
                            Asset Condition
                        </strong>
                    </div>

                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-2">
                            <span>Excellent</span>
                            <strong>{{ $excellentAssets }}</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Good</span>
                            <strong>{{ $goodAssets }}</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Fair</span>
                            <strong>{{ $fairAssets }}</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Poor</span>
                            <strong>{{ $poorAssets }}</strong>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span>Critical</span>
                            <strong>{{ $criticalAssets }}</strong>
                        </div>

                    </div>

                </div>

            </a>

        </div>


        <div class="col-lg-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white">
                    <strong>
                        <i class="ri-pie-chart-line me-1"></i>
                        Asset Categories
                    </strong>
                </div>

                <div class="card-body">

                    @forelse($categoryDistribution as $category)

                        <div class="d-flex justify-content-between mb-2">

                            <span>
                                {{ $category->asset_category ?: 'Unclassified' }}
                            </span>

                            <strong>
                                {{ number_format($category->total) }}
                            </strong>

                        </div>

                    @empty

                        <div class="text-muted">
                            No category data available.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        <div class="col-lg-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white">
                    <strong>
                        <i class="ri-bar-chart-line me-1"></i>
                        Asset Status
                    </strong>
                </div>

                <div class="card-body">

                    @forelse($statusDistribution as $status)

                        <div class="d-flex justify-content-between mb-2">

                            <span>
                                {{ $status->status ?: 'Unknown' }}
                            </span>

                            <strong>
                                {{ number_format($status->total) }}
                            </strong>

                        </div>

                    @empty

                        <div class="text-muted">
                            No status data available.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>


    {{-- REPORT NAVIGATION --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">

            <strong>
                <i class="ri-file-chart-line me-1"></i>
                Asset Reports
            </strong>

        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">

                    <a href="{{ route('admin.assets.reports.core-kpis.occupancy') }}"
                       class="btn btn-outline-primary w-100">

                        <i class="ri-home-office-line me-1"></i>
                        Occupancy

                    </a>

                </div>

                <div class="col-md-4">

                    <a href="{{ route('admin.assets.reports.core-kpis.condition') }}"
                       class="btn btn-outline-primary w-100">

                        <i class="ri-health-book-line me-1"></i>
                        Condition

                    </a>

                </div>

                <div class="col-md-4">

                    <a href="{{ route('admin.assets.reports.core-kpis.performance') }}"
                       class="btn btn-outline-primary w-100">

                        <i class="ri-speed-up-line me-1"></i>
                        Performance

                    </a>

                </div>

                <div class="col-md-4">

                    <a href="{{ route('admin.assets.reports.core-kpis.financial') }}"
                       class="btn btn-outline-primary w-100">

                        <i class="ri-money-dollar-circle-line me-1"></i>
                        Financial

                    </a>

                </div>

                <div class="col-md-4">

                    <a href="{{ route('admin.assets.reports.core-kpis.lifecycle') }}"
                       class="btn btn-outline-primary w-100">

                        <i class="ri-refresh-line me-1"></i>
                        Lifecycle

                    </a>

                </div>

                <div class="col-md-4">

                    <a href="{{ route('admin.assets.reports.industry-kpis.index') }}"
                       class="btn btn-outline-success w-100">

                        <i class="ri-bar-chart-grouped-line me-1"></i>
                        Industry KPIs

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection