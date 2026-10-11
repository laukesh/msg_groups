@extends('layouts.app')

@section('title', 'Occupancy KPI Report')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="ri-building-line me-2"></i>
                Occupancy KPI
            </h4>
            <p class="text-muted mb-0">
                Asset occupancy and vacancy performance overview
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


    {{-- Summary Cards --}}
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


        {{-- Occupied --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">
                                Occupied
                            </div>

                            <h3 class="mb-0 mt-2">
                                {{ number_format($occupiedAssets ?? 0) }}
                            </h3>
                        </div>

                        <div class="fs-2 text-success">
                            <i class="ri-checkbox-circle-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Vacant --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">
                                Vacant
                            </div>

                            <h3 class="mb-0 mt-2">
                                {{ number_format($vacantAssets ?? 0) }}
                            </h3>
                        </div>

                        <div class="fs-2 text-warning">
                            <i class="ri-home-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Occupancy Rate --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">
                                Occupancy Rate
                            </div>

                            <h3 class="mb-0 mt-2">
                                {{ number_format($occupancyRate ?? 0, 2) }}%
                            </h3>
                        </div>

                        <div class="fs-2 text-info">
                            <i class="ri-pie-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- KPI Progress --}}
    <div class="row g-4 mb-4">

        {{-- Occupancy Rate --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="ri-bar-chart-line me-2"></i>
                        Occupancy Performance
                    </h5>
                </div>

                <div class="card-body">

                    @php
                        $rate = (float) ($occupancyRate ?? 0);
                        $target = (float) ($occupancyTarget ?? 0);

                        $progress = min(max($rate, 0), 100);

                        $targetDifference = $rate - $target;
                    @endphp

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">
                            Current Occupancy
                        </span>

                        <strong>
                            {{ number_format($rate, 2) }}%
                        </strong>
                    </div>

                    <div class="progress"
                         style="height: 18px;">

                        <div class="progress-bar"
                             role="progressbar"
                             style="width: {{ $progress }}%;"
                             aria-valuenow="{{ $progress }}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                            {{ number_format($rate, 1) }}%
                        </div>

                    </div>


                    @if($target > 0)

                        <div class="mt-3 d-flex justify-content-between">

                            <span class="text-muted">
                                Target
                            </span>

                            <strong>
                                {{ number_format($target, 2) }}%
                            </strong>

                        </div>

                        <div class="mt-2">

                            @if($targetDifference >= 0)

                                <span class="badge bg-success">
                                    <i class="ri-arrow-up-line"></i>
                                    {{ number_format(abs($targetDifference), 2) }}%
                                    above target
                                </span>

                            @else

                                <span class="badge bg-warning text-dark">
                                    <i class="ri-arrow-down-line"></i>
                                    {{ number_format(abs($targetDifference), 2) }}%
                                    below target
                                </span>

                            @endif

                        </div>

                    @endif

                </div>
            </div>
        </div>


        {{-- Occupancy Breakdown --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="ri-pie-chart-2-line me-2"></i>
                        Breakdown
                    </h5>
                </div>

                <div class="card-body">

                    @php
                        $total = max((int) ($totalAssets ?? 0), 1);

                        $occupiedPercentage =
                            (($occupiedAssets ?? 0) / $total) * 100;

                        $vacantPercentage =
                            (($vacantAssets ?? 0) / $total) * 100;
                    @endphp


                    <div class="mb-4">

                        <div class="d-flex justify-content-between mb-1">
                            <span>
                                Occupied
                            </span>

                            <strong>
                                {{ number_format($occupiedPercentage, 2) }}%
                            </strong>
                        </div>

                        <div class="progress"
                             style="height: 10px;">

                            <div class="progress-bar bg-success"
                                 style="width: {{ min($occupiedPercentage, 100) }}%;">
                            </div>

                        </div>

                    </div>


                    <div>

                        <div class="d-flex justify-content-between mb-1">
                            <span>
                                Vacant
                            </span>

                            <strong>
                                {{ number_format($vacantPercentage, 2) }}%
                            </strong>
                        </div>

                        <div class="progress"
                             style="height: 10px;">

                            <div class="progress-bar bg-warning"
                                 style="width: {{ min($vacantPercentage, 100) }}%;">
                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>


    {{-- Industry / Profile Breakdown --}}
    @if(isset($occupancyByIndustry) && count($occupancyByIndustry))

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="mb-0">
                            <i class="ri-bar-chart-grouped-line me-2"></i>
                            Occupancy by Industry Profile
                        </h5>

                        <small class="text-muted">
                            Occupancy performance grouped by operating profile
                        </small>
                    </div>

                </div>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th>#</th>
                                <th>Industry Profile</th>
                                <th class="text-center">
                                    Total Assets
                                </th>
                                <th class="text-center">
                                    Occupied
                                </th>
                                <th class="text-center">
                                    Vacant
                                </th>
                                <th class="text-center">
                                    Occupancy Rate
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

                            @foreach($occupancyByIndustry as $index => $industry)

                                @php
                                    $industryTotal =
                                        (int) data_get($industry, 'total_assets', 0);

                                    $industryOccupied =
                                        (int) data_get($industry, 'occupied_assets', 0);

                                    $industryVacant =
                                        (int) data_get($industry, 'vacant_assets', 0);

                                    $industryRate =
                                        (float) data_get($industry, 'occupancy_rate', 0);

                                    $industryTarget =
                                        (float) data_get($industry, 'occupancy_target', 0);
                                @endphp

                                <tr>

                                    <td>
                                        {{ $index + 1 }}
                                    </td>

                                    <td>

                                        <div class="fw-semibold">
                                            {{ data_get($industry, 'profile_name', 'N/A') }}
                                        </div>

                                        @if(data_get($industry, 'industry_type'))
                                            <small class="text-muted">
                                                {{ data_get($industry, 'industry_type') }}
                                            </small>
                                        @endif

                                    </td>


                                    <td class="text-center">
                                        {{ number_format($industryTotal) }}
                                    </td>


                                    <td class="text-center text-success fw-semibold">
                                        {{ number_format($industryOccupied) }}
                                    </td>


                                    <td class="text-center text-warning fw-semibold">
                                        {{ number_format($industryVacant) }}
                                    </td>


                                    <td class="text-center">

                                        <strong>
                                            {{ number_format($industryRate, 2) }}%
                                        </strong>

                                    </td>


                                    <td class="text-center">

                                        @if($industryTarget > 0)
                                            {{ number_format($industryTarget, 2) }}%
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif

                                    </td>


                                    <td class="text-center">

                                        @if($industryTarget > 0)

                                            @if($industryRate >= $industryTarget)

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


    {{-- Asset Detail --}}
    @if(isset($assets) && $assets instanceof \Illuminate\Contracts\Pagination\Paginator)

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 py-3">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                    <div>
                        <h5 class="mb-0">
                            <i class="ri-list-check-2 me-2"></i>
                            Asset Occupancy Details
                        </h5>
                    </div>

                    <span class="badge bg-light text-dark">
                        {{ $assets->total() }} Assets
                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th>Asset Code</th>
                                <th>Asset Name</th>
                                <th>Industry Profile</th>
                                <th>Building</th>
                                <th>Unit</th>
                                <th class="text-center">
                                    Occupancy
                                </th>
                            </tr>

                        </thead>


                        <tbody>

                            @forelse($assets as $asset)

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
                                        {{ optional($asset->industryProfile)->profile_name ?? '—' }}
                                    </td>


                                    <td>
                                        {{ optional($asset->building)->name ?? '—' }}
                                    </td>


                                    <td>
                                        {{ optional($asset->unit)->name ?? '—' }}
                                    </td>


                                    <td class="text-center">

                                        @php
                                            $status = strtolower(
                                                (string) ($asset->occupancy_status ?? $asset->status ?? '')
                                            );
                                        @endphp

                                        @if($status === 'occupied')

                                            <span class="badge bg-success">
                                                Occupied
                                            </span>

                                        @elseif($status === 'vacant')

                                            <span class="badge bg-warning text-dark">
                                                Vacant
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                {{ $asset->occupancy_status ?? 'Not Recorded' }}
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6"
                                        class="text-center py-5 text-muted">

                                        <i class="ri-inbox-line fs-1 d-block mb-2"></i>

                                        No occupancy records found.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            @if($assets->hasPages())

                <div class="card-footer bg-white border-0">

                    {{ $assets->links() }}

                </div>

            @endif

        </div>

    @endif

</div>


{{-- Print CSS --}}
<style>
    @media print {

        .sidebar,
        .navbar,
        .topbar,
        .btn,
        .pagination {
            display: none !important;
        }

        .container-fluid {
            width: 100% !important;
            max-width: 100% !important;
        }

        .card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }

        body {
            background: #fff !important;
        }
    }
</style>

@endsection