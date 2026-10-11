@extends('layouts.app')

@section('title', 'Asset Lifecycle KPI')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="ri-refresh-line me-2"></i>
                Asset Lifecycle KPI
            </h4>

            <p class="text-muted mb-0">
                Asset lifecycle, renewal, replacement and remaining-life overview
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">

            <a href="{{ route('admin.assets.reports.core-kpis.index') }}"
               class="btn btn-outline-secondary">
                <i class="ri-arrow-left-line me-1"></i>
                Core KPIs
            </a>

            <a href="{{ route('admin.assets.operations.lifecycle.index') }}"
               class="btn btn-outline-primary">
                <i class="ri-refresh-line me-1"></i>
                Lifecycle Management
            </a>

            <button type="button"
                    class="btn btn-outline-dark"
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
                                {{ number_format((int) ($totalAssets ?? 0)) }}
                            </h3>

                        </div>

                        <div class="fs-2 text-primary">
                            <i class="ri-building-4-line"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Lifecycle Records --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                Lifecycle Records
                            </div>

                            <h3 class="mb-0 mt-2">
                                {{ number_format((int) ($totalLifecycleRecords ?? 0)) }}
                            </h3>

                        </div>

                        <div class="fs-2 text-info">
                            <i class="ri-file-list-3-line"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Renewal / Replacement Due --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                Renewal / Replacement Due
                            </div>

                            <h3 class="mb-0 mt-2">
                                {{ number_format((int) ($renewalDueAssets ?? 0)) }}
                            </h3>

                        </div>

                        <div class="fs-2 text-warning">
                            <i class="ri-alarm-warning-line"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- End of Life --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                End of Life
                            </div>

                            <h3 class="mb-0 mt-2">
                                {{ number_format((int) ($endOfLifeAssets ?? 0)) }}
                            </h3>

                        </div>

                        <div class="fs-2 text-danger">
                            <i class="ri-close-circle-line"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        LIFECYCLE HEALTH
    ========================================================== --}}
    <div class="row g-4 mb-4">

        {{-- Lifecycle Coverage --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0">
                        <i class="ri-dashboard-3-line me-2"></i>
                        Lifecycle Coverage
                    </h5>

                </div>

                <div class="card-body">

                    @php
                        $total =
                            (int) ($totalAssets ?? 0);

                        $records =
                            (int) ($assetsWithLifecycle ?? 0);

                        $coverage =
                            $total > 0
                                ? ($records / $total) * 100
                                : 0;

                        $coverage =
                            min(max($coverage, 0), 100);
                    @endphp

                    <div class="d-flex justify-content-between align-items-center mb-2">

                        <span class="text-muted">
                            Assets with Lifecycle Planning
                        </span>

                        <strong>
                            {{ number_format($coverage, 2) }}%
                        </strong>

                    </div>

                    <div class="progress"
                         style="height: 22px;">

                        <div class="progress-bar"
                             role="progressbar"
                             style="width: {{ $coverage }}%;"
                             aria-valuenow="{{ $coverage }}"
                             aria-valuemin="0"
                             aria-valuemax="100">

                            {{ number_format($coverage, 1) }}%

                        </div>

                    </div>

                    <div class="row g-3 mt-4">

                        {{-- Planned --}}
                        <div class="col-md-4">

                            <div class="border rounded p-3 h-100">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div class="text-muted small">
                                        Planned
                                    </div>

                                    <i class="ri-calendar-line text-primary"></i>

                                </div>

                                <div class="fs-4 fw-semibold mt-1">
                                    {{ number_format((int) ($plannedAssets ?? 0)) }}
                                </div>

                            </div>

                        </div>


                        {{-- Renewal --}}
                        <div class="col-md-4">

                            <div class="border rounded p-3 h-100">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div class="text-muted small">
                                        Renewal
                                    </div>

                                    <i class="ri-loop-right-line text-warning"></i>

                                </div>

                                <div class="fs-4 fw-semibold mt-1">
                                    {{ number_format((int) ($renewalAssets ?? 0)) }}
                                </div>

                            </div>

                        </div>


                        {{-- Replacement --}}
                        <div class="col-md-4">

                            <div class="border rounded p-3 h-100">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div class="text-muted small">
                                        Replacement
                                    </div>

                                    <i class="ri-refresh-line text-info"></i>

                                </div>

                                <div class="fs-4 fw-semibold mt-1">
                                    {{ number_format((int) ($replacementAssets ?? 0)) }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Lifecycle Distribution --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0">
                        <i class="ri-pie-chart-line me-2"></i>
                        Lifecycle Distribution
                    </h5>

                </div>

                <div class="card-body">

                    @php

                        $distribution =
                            $lifecycleDistribution ?? collect();

                        /*
                         * Controller normally returns:
                         *
                         * [
                         *     ['stage' => 'Planned', 'total' => 10],
                         *     ['stage' => 'Renewal', 'total' => 5],
                         * ]
                         *
                         * Normalize both Collection and array formats.
                         */
                        if ($distribution instanceof \Illuminate\Support\Collection) {
                            $distribution = $distribution->all();
                        }

                        $distribution =
                            is_array($distribution)
                                ? $distribution
                                : [];

                        $distributionTotal = 0;

                        foreach ($distribution as $item) {

                            if (is_array($item)) {
                                $distributionTotal +=
                                    (int) ($item['total'] ?? 0);
                            } elseif (is_object($item)) {
                                $distributionTotal +=
                                    (int) ($item->total ?? 0);
                            }

                        }

                        $distributionTotal =
                            max($distributionTotal, 1);

                    @endphp


                    @forelse($distribution as $item)

                        @php

                            $label =
                                is_array($item)
                                    ? ($item['stage'] ?? 'Unknown')
                                    : ($item->stage ?? 'Unknown');

                            $count =
                                is_array($item)
                                    ? (int) ($item['total'] ?? 0)
                                    : (int) ($item->total ?? 0);

                            $percentage =
                                ($count / $distributionTotal) * 100;

                            $percentage =
                                min(max($percentage, 0), 100);

                        @endphp


                        <div class="mb-3">

                            <div class="d-flex justify-content-between align-items-center mb-1">

                                <span class="fw-medium">

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
                                     role="progressbar"
                                     style="width: {{ $percentage }}%;"
                                     aria-valuenow="{{ $percentage }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                </div>

                            </div>


                            <small class="text-muted">
                                {{ number_format($percentage, 1) }}%
                            </small>

                        </div>

                    @empty

                        <div class="text-center text-muted py-4">

                            <i class="ri-inbox-line fs-1 d-block mb-2"></i>

                            No lifecycle distribution available.

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        LIFECYCLE BY INDUSTRY
    ========================================================== --}}
    @if(
        isset($lifecycleByIndustry) &&
        $lifecycleByIndustry &&
        count($lifecycleByIndustry)
    )

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="mb-0">
                    <i class="ri-bar-chart-grouped-line me-2"></i>
                    Lifecycle by Industry Profile
                </h5>

                <small class="text-muted">
                    Lifecycle planning grouped by industry operating profile
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
                                    Lifecycle Records
                                </th>

                                <th class="text-center">
                                    Coverage
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($lifecycleByIndustry as $index => $industry)

                                @php

                                    $industryAssets =
                                        (int) data_get(
                                            $industry,
                                            'assets',
                                            data_get(
                                                $industry,
                                                'total_assets',
                                                0
                                            )
                                        );

                                    $lifecycleCount =
                                        (int) data_get(
                                            $industry,
                                            'lifecycle_records',
                                            0
                                        );

                                    $industryCoverage =
                                        $industryAssets > 0
                                            ? ($lifecycleCount / $industryAssets) * 100
                                            : 0;

                                    $industryCoverage =
                                        min(
                                            max(
                                                $industryCoverage,
                                                0
                                            ),
                                            100
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

                                        {{ number_format(
                                            $industryAssets
                                        ) }}

                                    </td>


                                    <td class="text-center">

                                        {{ number_format(
                                            $lifecycleCount
                                        ) }}

                                    </td>


                                    <td class="text-center">

                                        <span class="fw-semibold">

                                            {{ number_format(
                                                $industryCoverage,
                                                2
                                            ) }}%

                                        </span>

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
        UPCOMING LIFECYCLE EVENTS
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                <div>

                    <h5 class="mb-0">
                        <i class="ri-calendar-schedule-line me-2"></i>
                        Upcoming Lifecycle Events
                    </h5>

                    <small class="text-muted">
                        Renewal, replacement and end-of-life activities
                    </small>

                </div>

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
                                Lifecycle Stage
                            </th>

                            <th>
                                Event Date
                            </th>

                            <th class="text-center">
                                Remaining
                            </th>

                            <th class="text-center">
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($upcomingLifecycle ?? [] as $lifecycle)

                            @php

                                /*
                                 * Prefer normalized event_date generated
                                 * by the controller.
                                 */
                                $eventDate =
                                    $lifecycle->event_date
                                    ?? $lifecycle->planned_date
                                    ?? $lifecycle->renewal_date
                                    ?? $lifecycle->replacement_date
                                    ?? $lifecycle->end_of_life_date
                                    ?? null;

                                $daysRemaining = null;

                                if ($eventDate) {

                                    try {

                                        $daysRemaining =
                                            now()->diffInDays(
                                                \Carbon\Carbon::parse(
                                                    $eventDate
                                                ),
                                                false
                                            );

                                    } catch (\Throwable $e) {

                                        $daysRemaining = null;

                                    }

                                }


                                $stage =
                                    $lifecycle->normalized_stage
                                    ?? $lifecycle->lifecycle_stage
                                    ?? $lifecycle->stage
                                    ?? $lifecycle->status
                                    ?? 'Planned';


                                $asset =
                                    $lifecycle->asset ?? null;


                                $assetCode =
                                    $asset->asset_code
                                    ?? $lifecycle->asset_code
                                    ?? '—';


                                $assetName =
                                    $asset->asset_name
                                    ?? $lifecycle->asset_name
                                    ?? '—';


                                $industryProfile =
                                    optional(
                                        $asset
                                    )->industryProfile;


                                $industryName =
                                    optional(
                                        $industryProfile
                                    )->profile_name
                                    ?? '—';

                            @endphp


                            <tr>

                                {{-- Asset Code --}}
                                <td>

                                    <span class="fw-semibold">

                                        {{ $assetCode }}

                                    </span>

                                </td>


                                {{-- Asset Name --}}
                                <td>

                                    {{ $assetName }}

                                </td>


                                {{-- Industry --}}
                                <td>

                                    {{ $industryName }}

                                </td>


                                {{-- Stage --}}
                                <td>

                                    <span class="badge bg-light text-dark">

                                        {{ ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $stage
                                            )
                                        ) }}

                                    </span>

                                </td>


                                {{-- Event Date --}}
                                <td>

                                    @if($eventDate)

                                        @try

                                            {{ \Carbon\Carbon::parse(
                                                $eventDate
                                            )->format('d M Y') }}

                                        @catch(\Throwable $e)

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endtry

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Remaining --}}
                                <td class="text-center">

                                    @if($daysRemaining === null)

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @elseif($daysRemaining < 0)

                                        <span class="badge bg-danger">
                                            Overdue
                                        </span>

                                    @elseif($daysRemaining === 0)

                                        <span class="badge bg-danger">
                                            Today
                                        </span>

                                    @elseif($daysRemaining <= 30)

                                        <span class="badge bg-warning text-dark">

                                            {{ number_format(
                                                $daysRemaining
                                            ) }}

                                            {{ $daysRemaining == 1 ? 'day' : 'days' }}

                                        </span>

                                    @else

                                        <span class="badge bg-success">

                                            {{ number_format(
                                                $daysRemaining
                                            ) }}

                                            {{ $daysRemaining == 1 ? 'day' : 'days' }}

                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td class="text-center">

                                    @if(
                                        $daysRemaining !== null &&
                                        $daysRemaining < 0
                                    )

                                        <span class="badge bg-danger">
                                            Action Required
                                        </span>

                                    @elseif(
                                        $daysRemaining !== null &&
                                        $daysRemaining <= 30
                                    )

                                        <span class="badge bg-warning text-dark">
                                            Due Soon
                                        </span>

                                    @elseif(
                                        $daysRemaining !== null &&
                                        $daysRemaining <= 90
                                    )

                                        <span class="badge bg-info text-dark">
                                            Upcoming
                                        </span>

                                    @else

                                        <span class="badge bg-success">
                                            Planned
                                        </span>

                                    @endif

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td colspan="7"
                                    class="text-center py-5 text-muted">

                                    <i class="ri-calendar-check-line fs-1 d-block mb-2"></i>

                                    <div class="fw-semibold">
                                        No upcoming lifecycle events found.
                                    </div>

                                    <small>
                                        Future renewal, replacement or
                                        end-of-life activities will appear here.
                                    </small>

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

        .table {
            font-size: 11px !important;
        }

        .progress {
            border: 1px solid #ddd;
        }

    }

</style>

@endsection