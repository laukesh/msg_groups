@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h4 class="mb-1">
                <i class="ri-bar-chart-grouped-line me-1"></i>
                Industry-Specific KPIs
            </h4>

            <p class="text-muted mb-0">
                Monitor asset performance according to industry operating profiles.
            </p>
        </div>

        <a href="{{ route('admin.assets.reports.core-kpis.index') }}"
           class="btn btn-light border">

            <i class="ri-arrow-left-line me-1"></i>
            Core Asset KPIs

        </a>

    </div>


    <div class="row g-3">

        @forelse($summaries as $summary)

            @php
                $profile = $summary['profile'];
            @endphp

            <div class="col-xl-4 col-lg-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start mb-3">

                            <div>

                                <h5 class="mb-1">
                                    {{ $profile->profile_name }}
                                </h5>

                                <small class="text-muted">
                                    {{ $profile->profile_code }}
                                </small>

                            </div>

                            @if($profile->is_active)

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    Inactive
                                </span>

                            @endif

                        </div>


                        <div class="row g-3 mb-3">

                            <div class="col-6">

                                <div class="border rounded p-3">

                                    <div class="text-muted small">
                                        Assets
                                    </div>

                                    <div class="fs-4 fw-semibold">
                                        {{ number_format($summary['total']) }}
                                    </div>

                                </div>

                            </div>

                            <div class="col-6">

                                <div class="border rounded p-3">

                                    <div class="text-muted small">
                                        Active
                                    </div>

                                    <div class="fs-4 fw-semibold">
                                        {{ number_format($summary['active']) }}
                                    </div>

                                </div>

                            </div>

                            <div class="col-6">

                                <div class="border rounded p-3">

                                    <div class="text-muted small">
                                        Critical
                                    </div>

                                    <div class="fs-4 fw-semibold">
                                        {{ number_format($summary['critical']) }}
                                    </div>

                                </div>

                            </div>

                            <div class="col-6">

                                <div class="border rounded p-3">

                                    <div class="text-muted small">
                                        Active Rate
                                    </div>

                                    <div class="fs-4 fw-semibold">
                                        {{ number_format($summary['active_rate'], 2) }}%
                                    </div>

                                </div>

                            </div>

                        </div>


                        <a href="{{ route(
                            'admin.assets.reports.industry-kpis.show',
                            [
                                'industry_profile' => $profile->getKey()
                            ]
                        ) }}"
                           class="btn btn-primary w-100">

                            View Industry KPIs

                            <i class="ri-arrow-right-line ms-1"></i>

                        </a>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12">

                <div class="alert alert-info">

                    No industry operating profiles are available.

                    <a href="{{ route(
                        'admin.assets.operations.industry-profiles.create'
                    ) }}"
                       class="alert-link">

                        Create Industry Operating Profile

                    </a>

                </div>

            </div>

        @endforelse

    </div>

</div>

@endsection