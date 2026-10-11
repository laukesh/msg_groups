@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>

            <h4 class="mb-1">

                <i class="ri-bar-chart-grouped-line me-1"></i>

                {{ $profile->profile_name }}

            </h4>

            <p class="text-muted mb-0">

                Industry-specific asset KPI dashboard.

                @if($profile->profile_code)
                    <span class="ms-2">
                        {{ $profile->profile_code }}
                    </span>
                @endif

            </p>

        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.assets.operations.industry-profiles.show',
                [
                    'industry_profile' => $profile->getKey()
                ]
            ) }}"
               class="btn btn-outline-info">

                <i class="ri-eye-line me-1"></i>
                Operating Profile

            </a>

            <a href="{{ route(
                'admin.assets.reports.industry-kpis.index'
            ) }}"
               class="btn btn-light border">

                <i class="ri-arrow-left-line me-1"></i>
                All Industries

            </a>

        </div>

    </div>


    {{-- KPI CARDS --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Assets
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ number_format($totalAssets) }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Active Assets
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ number_format($activeAssets) }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Critical Assets
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ number_format($criticalAssets) }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Asset Value
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ number_format($assetValue, 2) }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- TARGETS --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">

            <strong>
                <i class="ri-target-line me-1"></i>
                Operating Targets
            </strong>

        </div>

        <div class="card-body">

            <div class="row g-3">

                @foreach($targets as $name => $value)

                    @if(!is_null($value))

                        <div class="col-xl-3 col-md-6">

                            <div class="border rounded p-3">

                                <div class="text-muted small text-capitalize">

                                    {{ str_replace('_', ' ', $name) }}

                                </div>

                                <div class="fs-4 fw-semibold">

                                    {{ number_format((float) $value, 2) }}%

                                </div>

                            </div>

                        </div>

                    @endif

                @endforeach

            </div>

        </div>

    </div>


    {{-- INDUSTRY METRICS --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">

            <strong>
                <i class="ri-dashboard-line me-1"></i>
                Industry KPI Metrics
            </strong>

        </div>

        <div class="card-body">

            <div class="row g-3">

                @foreach($industryKpis['metrics'] ?? [] as $metric => $value)

                    <div class="col-xl-4 col-md-6">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small">
                                {{ $metric }}
                            </div>

                            <div class="fs-4 fw-semibold">
                                {{ is_numeric($value)
                                    ? number_format($value, 2)
                                    : $value
                                }}
                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>


    {{-- PRIMARY / SECONDARY KPI --}}
    <div class="row g-3 mb-4">

        <div class="col-md-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Primary KPI
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{ $profile->primary_kpi ?: 'Not configured' }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Secondary KPI
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{ $profile->secondary_kpi ?: 'Not configured' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ASSET LIST --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white d-flex justify-content-between">

            <strong>

                <i class="ri-building-line me-1"></i>

                Industry Assets

            </strong>

            <span class="badge bg-primary">

                {{ $assets->total() }}

            </span>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>Code</th>
                        <th>Asset</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Condition</th>
                        <th class="text-end">Action</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($assets as $asset)

                        <tr>

                            <td>
                                {{ $asset->asset_code }}
                            </td>

                            <td>

                                <div class="fw-semibold">
                                    {{ $asset->asset_name }}
                                </div>

                            </td>

                            <td>
                                {{ $asset->asset_category ?: '-' }}
                            </td>

                            <td>
                                {{ $asset->status ?: '-' }}
                            </td>

                            <td>
                                {{ $asset->condition ?: '-' }}
                            </td>

                            <td class="text-end">

                                @if(Route::has('admin.assets.assets.show'))

                                    <a href="{{ route(
                                        'admin.assets.assets.show',
                                        [
                                            'asset' => $asset->getKey()
                                        ]
                                    ) }}"
                                       class="btn btn-sm btn-outline-info">

                                        <i class="ri-eye-line"></i>

                                    </a>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center text-muted py-4">

                                No assets assigned to this industry profile.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($assets->hasPages())

            <div class="card-footer bg-white">

                {{ $assets->links() }}

            </div>

        @endif

    </div>

</div>

@endsection