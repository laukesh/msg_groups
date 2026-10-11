@extends('layouts.app')

@section('title', 'Asset Performance KPI')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="ri-line-chart-line me-2"></i>
                Asset Performance KPI
            </h4>

            <p class="text-muted mb-0">
                Asset performance, utilization and operational efficiency overview
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

        {{-- Total Assets --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                Total Assets
                            </div>

                            <h3 class="mb-0 mt-2">
                                {{ number_format($totalAssets ?? 0) }}
                            </h3>

                        </div>

                        <div class="fs-2 text-primary">
                            <i class="ri-building-4-line"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Recorded Performance --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                Performance Records
                            </div>

                            <h3 class="mb-0 mt-2">
                                {{ number_format($performanceRecords ?? 0) }}
                            </h3>

                        </div>

                        <div class="fs-2 text-success">
                            <i class="ri-file-chart-line"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Average Performance --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                Average Performance
                            </div>

                            <h3 class="mb-0 mt-2">

                                {{ number_format(
                                    $averagePerformance ?? 0,
                                    2
                                ) }}%

                            </h3>

                        </div>

                        <div class="fs-2 text-info">
                            <i class="ri-speed-up-line"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Below Target --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                Below Target
                            </div>

                            <h3 class="mb-0 mt-2">
                                {{ number_format($belowTargetAssets ?? 0) }}
                            </h3>

                        </div>

                        <div class="fs-2 text-warning">
                            <i class="ri-alert-line"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PERFORMANCE OVERVIEW
    ========================================================== --}}
    <div class="row g-4 mb-4">

        {{-- Performance Score --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0">
                        <i class="ri-dashboard-3-line me-2"></i>
                        Overall Performance
                    </h5>

                </div>

                <div class="card-body">

                    @php

                        $performance =
                            (float) ($averagePerformance ?? 0);

                        $performance =
                            min(max($performance, 0), 100);

                        $target =
                            (float) ($performanceTarget ?? 0);

                    @endphp


                    <div class="d-flex justify-content-between mb-2">

                        <span class="text-muted">
                            Current Performance
                        </span>

                        <strong>
                            {{ number_format($performance, 2) }}%
                        </strong>

                    </div>


                    <div class="progress"
                         style="height: 22px;">

                        <div class="progress-bar"
                             role="progressbar"
                             style="width: {{ $performance }}%;"
                             aria-valuenow="{{ $performance }}"
                             aria-valuemin="0"
                             aria-valuemax="100">

                            {{ number_format($performance, 1) }}%

                        </div>

                    </div>


                    @if($target > 0)

                        @php
                            $difference =
                                $performance - $target;
                        @endphp

                        <div class="mt-4">

                            <div class="d-flex justify-content-between">

                                <span class="text-muted">
                                    Performance Target
                                </span>

                                <strong>
                                    {{ number_format($target, 2) }}%
                                </strong>

                            </div>


                            <div class="mt-2">

                                @if($difference >= 0)

                                    <span class="badge bg-success">

                                        <i class="ri-arrow-up-line"></i>

                                        {{ number_format(
                                            abs($difference),
                                            2
                                        ) }}%

                                        above target

                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">

                                        <i class="ri-arrow-down-line"></i>

                                        {{ number_format(
                                            abs($difference),
                                            2
                                        ) }}%

                                        below target

                                    </span>

                                @endif

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- Performance Distribution --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0">
                        <i class="ri-pie-chart-line me-2"></i>
                        Performance Distribution
                    </h5>

                </div>

                <div class="card-body">

                    @php

                        $distribution =
                            $performanceDistribution ?? [];

                        if ($distribution instanceof \Illuminate\Support\Collection) {
                            $distribution =
                                $distribution->toArray();
                        }

                    @endphp


                    @forelse($distribution as $label => $count)

                        @php

                            $count = (int) $count;

                            $total =
                                max(
                                    (int) ($performanceRecords ?? 0),
                                    1
                                );

                            $percentage =
                                ($count / $total) * 100;

                        @endphp


                        <div class="mb-3">

                            <div class="d-flex justify-content-between mb-1">

                                <span>
                                    {{ ucfirst(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $label
                                        )
                                    ) }}
                                </span>

                                <strong>
                                    {{ number_format($count) }}
                                </strong>

                            </div>


                            <div class="progress"
                                 style="height: 8px;">

                                <div class="progress-bar"
                                     style="width: {{ min($percentage, 100) }}%;">
                                </div>

                            </div>


                            <small class="text-muted">

                                {{ number_format(
                                    $percentage,
                                    1
                                ) }}%

                            </small>

                        </div>

                    @empty

                        <div class="text-center text-muted py-4">

                            <i class="ri-inbox-line fs-1 d-block mb-2"></i>

                            No performance distribution available.

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        INDUSTRY PERFORMANCE
    ========================================================== --}}
    @if(isset($performanceByIndustry) && count($performanceByIndustry))

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="mb-0">
                    <i class="ri-bar-chart-grouped-line me-2"></i>
                    Performance by Industry Profile
                </h5>

                <small class="text-muted">
                    Asset performance grouped by industry operating profile
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

                                <th class="text-center">
                                    Records
                                </th>

                                <th class="text-center">
                                    Average
                                </th>

                                <th class="text-center">
                                    Utilization
                                </th>

                                <th class="text-center">
                                    Target
                                </th>

                                <th class="text-center">
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($performanceByIndustry as $index => $industry)

                                @php

                                    $total =
                                        (int) data_get(
                                            $industry,
                                            'total_assets',
                                            0
                                        );

                                    $records =
                                        (int) data_get(
                                            $industry,
                                            'performance_records',
                                            0
                                        );

                                    $average =
                                        (float) data_get(
                                            $industry,
                                            'average_performance',
                                            0
                                        );

                                    $utilization =
                                        (float) data_get(
                                            $industry,
                                            'utilization_rate',
                                            0
                                        );

                                    $target =
                                        (float) data_get(
                                            $industry,
                                            'performance_target',
                                            0
                                        );

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
                                        {{ number_format($total) }}
                                    </td>


                                    <td class="text-center">
                                        {{ number_format($records) }}
                                    </td>


                                    <td class="text-center">

                                        <strong>

                                            {{ number_format(
                                                $average,
                                                2
                                            ) }}%

                                        </strong>

                                    </td>


                                    <td class="text-center">

                                        {{ number_format(
                                            $utilization,
                                            2
                                        ) }}%

                                    </td>


                                    <td class="text-center">

                                        @if($target > 0)

                                            {{ number_format(
                                                $target,
                                                2
                                            ) }}%

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    <td class="text-center">

                                        @if($target <= 0)

                                            <span class="badge bg-secondary">
                                                No Target
                                            </span>

                                        @elseif($average >= $target)

                                            <span class="badge bg-success">
                                                On Target
                                            </span>

                                        @else

                                            <span class="badge bg-warning text-dark">
                                                Below Target
                                            </span>

                                        @endif

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
        ASSET PERFORMANCE DETAILS
    ========================================================== --}}
    @if(isset($assets))

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 py-3">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                    <div>

                        <h5 class="mb-0">
                            <i class="ri-list-check-2 me-2"></i>
                            Asset Performance Details
                        </h5>

                        <small class="text-muted">
                            Latest available performance record for each asset
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

                                <th>
                                    Performance Date
                                </th>

                                <th class="text-center">
                                    Performance
                                </th>

                                <th class="text-center">
                                    Utilization
                                </th>

                                <th class="text-center">
                                    Availability
                                </th>

                                <th class="text-center">
                                    Efficiency
                                </th>

                                <th class="text-center">
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($assets as $asset)

                                @php

                                    /*
                                     * Expected relationship:
                                     *
                                     * Asset
                                     *   -> latestPerformance
                                     *
                                     * This keeps the report connected
                                     * to the existing performance module.
                                     */

                                    $performanceRecord =
                                        $asset->latestPerformance ?? null;


                                    $score =
                                        $performanceRecord
                                            ? (float) (
                                                $performanceRecord
                                                    ->performance_score
                                                ?? $performanceRecord
                                                    ->performance
                                                ?? 0
                                            )
                                            : 0;


                                    $utilization =
                                        $performanceRecord
                                            ? (float) (
                                                $performanceRecord
                                                    ->utilization_rate
                                                ?? $performanceRecord
                                                    ->utilization
                                                ?? 0
                                            )
                                            : 0;


                                    $availability =
                                        $performanceRecord
                                            ? (float) (
                                                $performanceRecord
                                                    ->availability_rate
                                                ?? $performanceRecord
                                                    ->availability
                                                ?? 0
                                            )
                                            : 0;


                                    $efficiency =
                                        $performanceRecord
                                            ? (float) (
                                                $performanceRecord
                                                    ->efficiency_rate
                                                ?? $performanceRecord
                                                    ->efficiency
                                                ?? 0
                                            )
                                            : 0;


                                    $date =
                                        $performanceRecord
                                            ? (
                                                $performanceRecord
                                                    ->performance_date
                                                ?? $performanceRecord
                                                    ->assessment_date
                                                ?? $performanceRecord
                                                    ->created_at
                                            )
                                            : null;

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


                                    {{-- Date --}}
                                    <td>

                                        @if($date)

                                            {{ \Carbon\Carbon::parse(
                                                $date
                                            )->format('d M Y') }}

                                        @else

                                            <span class="text-muted">
                                                Not Recorded
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Performance --}}
                                    <td class="text-center">

                                        @if($performanceRecord)

                                            <span class="badge bg-primary">

                                                {{ number_format(
                                                    $score,
                                                    2
                                                ) }}%

                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                N/A
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Utilization --}}
                                    <td class="text-center">

                                        @if($performanceRecord)

                                            {{ number_format(
                                                $utilization,
                                                2
                                            ) }}%

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Availability --}}
                                    <td class="text-center">

                                        @if($performanceRecord)

                                            {{ number_format(
                                                $availability,
                                                2
                                            ) }}%

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Efficiency --}}
                                    <td class="text-center">

                                        @if($performanceRecord)

                                            {{ number_format(
                                                $efficiency,
                                                2
                                            ) }}%

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Status --}}
                                    <td class="text-center">

                                        @if(!$performanceRecord)

                                            <span class="badge bg-secondary">
                                                Not Recorded
                                            </span>

                                        @elseif($target > 0 && $score >= $target)

                                            <span class="badge bg-success">
                                                On Target
                                            </span>

                                        @elseif($target > 0)

                                            <span class="badge bg-warning text-dark">
                                                Below Target
                                            </span>

                                        @else

                                            <span class="badge bg-info">
                                                Recorded
                                            </span>

                                        @endif

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td colspan="9"
                                        class="text-center py-5 text-muted">

                                        <i class="ri-inbox-line fs-1 d-block mb-2"></i>

                                        No asset performance records found.

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