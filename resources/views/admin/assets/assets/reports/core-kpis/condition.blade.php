@extends('layouts.app')

@section('title', 'Asset Condition KPI')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="ri-heart-pulse-line me-2"></i>
                Asset Condition KPI
            </h4>

            <p class="text-muted mb-0">
                Asset condition, inspection and maintenance health overview
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


    {{-- ============================================================
        SUMMARY CARDS
    ============================================================ --}}
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


        {{-- Inspected Assets --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                Inspected Assets
                            </div>

                            <h3 class="mb-0 mt-2">
                                {{ number_format($inspectedAssets ?? 0) }}
                            </h3>

                        </div>

                        <div class="fs-2 text-success">
                            <i class="ri-checkbox-circle-line"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Critical --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                Critical Assets
                            </div>

                            <h3 class="mb-0 mt-2">
                                {{ number_format($criticalAssets ?? 0) }}
                            </h3>

                        </div>

                        <div class="fs-2 text-danger">
                            <i class="ri-error-warning-line"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Average Score --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                Average Condition Score
                            </div>

                            <h3 class="mb-0 mt-2">
                                {{ number_format($averageConditionScore ?? 0, 2) }}
                            </h3>

                        </div>

                        <div class="fs-2 text-info">
                            <i class="ri-bar-chart-2-line"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        CONDITION HEALTH
    ============================================================ --}}
    <div class="row g-4 mb-4">

        {{-- Overall Condition --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0">
                        <i class="ri-dashboard-line me-2"></i>
                        Overall Condition Health
                    </h5>

                </div>

                <div class="card-body">

                    @php

                        $averageScore =
                            (float) ($averageConditionScore ?? 0);

                        $maxScore =
                            (float) ($maximumConditionScore ?? 5);

                        $conditionPercentage =
                            $maxScore > 0
                                ? ($averageScore / $maxScore) * 100
                                : 0;

                        $conditionPercentage =
                            min(max($conditionPercentage, 0), 100);

                    @endphp


                    <div class="d-flex justify-content-between mb-2">

                        <span class="text-muted">
                            Average Condition Score
                        </span>

                        <strong>
                            {{ number_format($averageScore, 2) }}
                            /
                            {{ number_format($maxScore, 2) }}
                        </strong>

                    </div>


                    <div class="progress"
                         style="height: 22px;">

                        <div class="progress-bar"
                             role="progressbar"
                             style="width: {{ $conditionPercentage }}%;"
                             aria-valuenow="{{ $conditionPercentage }}"
                             aria-valuemin="0"
                             aria-valuemax="100">

                            {{ number_format($conditionPercentage, 1) }}%

                        </div>

                    </div>


                    @if(isset($conditionTarget) && $conditionTarget !== null)

                        <div class="mt-4">

                            <div class="d-flex justify-content-between">

                                <span class="text-muted">
                                    Target Score
                                </span>

                                <strong>
                                    {{ number_format($conditionTarget, 2) }}
                                </strong>

                            </div>

                            @php
                                $difference =
                                    $averageScore - (float) $conditionTarget;
                            @endphp

                            <div class="mt-2">

                                @if($difference >= 0)

                                    <span class="badge bg-success">
                                        <i class="ri-arrow-up-line"></i>
                                        {{ number_format(abs($difference), 2) }}
                                        above target
                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">
                                        <i class="ri-arrow-down-line"></i>
                                        {{ number_format(abs($difference), 2) }}
                                        below target
                                    </span>

                                @endif

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- Condition Distribution --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0">
                        <i class="ri-pie-chart-line me-2"></i>
                        Condition Distribution
                    </h5>

                </div>

                <div class="card-body">

                    @php

                        $distribution = $conditionDistribution ?? [];

                        $distribution = is_array($distribution)
                            ? $distribution
                            : $distribution->toArray();

                    @endphp


                    @forelse($distribution as $label => $count)

                        @php
                            $count = (int) $count;

                            $total =
                                max((int) ($inspectedAssets ?? 0), 1);

                            $percentage =
                                ($count / $total) * 100;
                        @endphp

                        <div class="mb-3">

                            <div class="d-flex justify-content-between mb-1">

                                <span>
                                    {{ ucfirst(str_replace('_', ' ', $label)) }}
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
                                {{ number_format($percentage, 1) }}%
                            </small>

                        </div>

                    @empty

                        <div class="text-center text-muted py-4">

                            <i class="ri-inbox-line fs-1 d-block mb-2"></i>

                            No condition distribution available.

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        CONDITION BY INDUSTRY
    ============================================================ --}}
    @if(isset($conditionByIndustry) && count($conditionByIndustry))

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="mb-0">
                    <i class="ri-bar-chart-grouped-line me-2"></i>
                    Condition by Industry Profile
                </h5>

                <small class="text-muted">
                    Latest condition inspection for assets grouped by industry profile
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
                                    Inspected
                                </th>

                                <th class="text-center">
                                    Average Score
                                </th>

                                <th class="text-center">
                                    Critical
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

                            @foreach($conditionByIndustry as $index => $industry)

                                @php

                                    $industryAssets =
                                        (int) data_get(
                                            $industry,
                                            'total_assets',
                                            0
                                        );

                                    $industryInspected =
                                        (int) data_get(
                                            $industry,
                                            'inspected_assets',
                                            0
                                        );

                                    $industryScore =
                                        (float) data_get(
                                            $industry,
                                            'average_condition_score',
                                            0
                                        );

                                    $industryCritical =
                                        (int) data_get(
                                            $industry,
                                            'critical_assets',
                                            0
                                        );

                                    $industryTarget =
                                        (float) data_get(
                                            $industry,
                                            'condition_target',
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
                                        {{ number_format($industryAssets) }}
                                    </td>


                                    <td class="text-center">
                                        {{ number_format($industryInspected) }}
                                    </td>


                                    <td class="text-center">

                                        <strong>

                                            {{ number_format(
                                                $industryScore,
                                                2
                                            ) }}

                                        </strong>

                                    </td>


                                    <td class="text-center">

                                        @if($industryCritical > 0)

                                            <span class="badge bg-danger">

                                                {{ number_format(
                                                    $industryCritical
                                                ) }}

                                            </span>

                                        @else

                                            <span class="badge bg-success">
                                                0
                                            </span>

                                        @endif

                                    </td>


                                    <td class="text-center">

                                        @if($industryTarget > 0)

                                            {{ number_format(
                                                $industryTarget,
                                                2
                                            ) }}

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    <td class="text-center">

                                        @if($industryTarget > 0)

                                            @if($industryScore >= $industryTarget)

                                                <span class="badge bg-success">
                                                    On Target
                                                </span>

                                            @else

                                                <span class="badge bg-warning text-dark">
                                                    Below Target
                                                </span>

                                            @endif

                                        @else

                                            <span class="badge bg-secondary">
                                                No Target
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


    {{-- ============================================================
        ASSET CONDITION DETAILS
    ============================================================ --}}
    @if(isset($assets))

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 py-3">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                    <div>

                        <h5 class="mb-0">
                            <i class="ri-list-check-2 me-2"></i>
                            Asset Condition Details
                        </h5>

                        <small class="text-muted">
                            Latest recorded condition inspection
                        </small>

                    </div>

                    @if(method_exists($assets, 'total'))

                        <span class="badge bg-light text-dark">
                            {{ number_format($assets->total()) }}
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
                                    Inspection Date
                                </th>

                                <th class="text-center">
                                    Score
                                </th>

                                <th class="text-center">
                                    Structural
                                </th>

                                <th class="text-center">
                                    Electrical
                                </th>

                                <th class="text-center">
                                    Mechanical
                                </th>

                                <th class="text-center">
                                    Safety
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($assets as $asset)

                                @php

                                    /*
                                     * The controller should attach the latest
                                     * condition record as:
                                     *
                                     * $asset->latestCondition
                                     */

                                    $condition =
                                        $asset->latestCondition ?? null;

                                @endphp


                                <tr>

                                    <td>

                                        <span class="fw-semibold">
                                            {{ $asset->asset_code ?? '—' }}
                                        </span>

                                    </td>


                                    <td>
                                        {{ $asset->asset_name ?? '—' }}
                                    </td>


                                    <td>

                                        {{ optional(
                                            $asset->industryProfile
                                        )->profile_name ?? '—' }}

                                    </td>


                                    <td>

                                        @if($condition?->inspection_date)

                                            {{ \Carbon\Carbon::parse(
                                                $condition->inspection_date
                                            )->format('d M Y') }}

                                        @else

                                            <span class="text-muted">
                                                Not Inspected
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Score --}}
                                    <td class="text-center">

                                        @if($condition)

                                            <span class="badge bg-primary">

                                                {{ number_format(
                                                    (float) $condition->condition_score,
                                                    2
                                                ) }}

                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                N/A
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Structural --}}
                                    <td class="text-center">

                                        {{ $condition->structural_condition ?? '—' }}

                                    </td>


                                    {{-- Electrical --}}
                                    <td class="text-center">

                                        {{ $condition->electrical_condition ?? '—' }}

                                    </td>


                                    {{-- Mechanical --}}
                                    <td class="text-center">

                                        {{ $condition->mechanical_condition ?? '—' }}

                                    </td>


                                    {{-- Safety --}}
                                    <td class="text-center">

                                        {{ $condition->safety_condition ?? '—' }}

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td colspan="9"
                                        class="text-center py-5 text-muted">

                                        <i class="ri-inbox-line fs-1 d-block mb-2"></i>

                                        No asset condition records found.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- Pagination --}}
            @if(method_exists($assets, 'hasPages') && $assets->hasPages())

                <div class="card-footer bg-white border-0">

                    {{ $assets->links() }}

                </div>

            @endif

        </div>

    @endif

</div>


{{-- ================================================================
    PRINT CSS
================================================================ --}}
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