@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Valuation</h4>
            <p class="text-muted mb-0">
                Review project, investment and exit valuations.
            </p>
        </div>

        <a href="{{ route('admin.portfolio-investment.overview') }}"
           class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line"></i>
            Portfolio Overview
        </a>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-50 valuation-card">
                <div class="card-body">
                    <div class="text-muted small">
                        Total Valuations
                    </div>

                    <h2 class="fw-bold mt-2 mb-0">
                        {{ $summary['total_valuations'] }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-50 valuation-card">
                <div class="card-body">
                    <div class="text-muted small">
                        Project Valuation
                    </div>

                    <h4 class="fw-bold amount-value mt-2">
                        ₹ {{ number_format($summary['total_project_valuation'], 2) }}
                    </h4>

                    <small class="text-muted">
                        ₹ {{ number_format($summary['total_project_valuation'] / 10000000, 2) }} Cr
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-50 valuation-card">
                <div class="card-body">
                    <div class="text-muted small">
                        Investment Valuation
                    </div>

                    <h4 class="fw-bold amount-value mt-2">
                        ₹ {{ number_format($summary['total_investment_valuation'], 2) }}
                    </h4>

                    <small class="text-muted">
                        ₹ {{ number_format($summary['total_investment_valuation'] / 10000000, 2) }} Cr
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-50 valuation-card">
                <div class="card-body">
                    <div class="text-muted small">
                        Expected Exit Value
                    </div>

                    <h4 class="fw-bold text-success amount-value mt-2">
                        ₹ {{ number_format($summary['total_expected_exit_value'], 2) }}
                    </h4>

                    <small class="text-muted">
                        ₹ {{ number_format($summary['total_expected_exit_value'] / 10000000, 2) }} Cr
                    </small>
                </div>
            </div>
        </div>

    </div>

    {{-- Valuation Listing --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Valuation Records</h5>

            <span class="badge bg-primary">
                {{ $valuations->count() }} Records
            </span>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle text-nowrap">

                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Analysis No.</th>
                            <th>Title</th>
                            <th>Land</th>
                            <th>Status</th>
                            <th>Project Valuation</th>
                            <th>Investment Valuation</th>
                            <th>Exit Valuation</th>
                            <th>Expected Exit Value</th>
                            <th>Exit Strategy</th>
                            <th>Exit Period</th>
                            <th>Risk Rating</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($valuations as $index => $valuation)

                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <strong>
                                        {{ $valuation->analysis_number }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $valuation->title ?? '-' }}

                                    @if($valuation->feasibility_title)
                                        <br>
                                        <small class="text-muted">
                                            {{ $valuation->feasibility_title }}
                                        </small>
                                    @endif
                                </td>

                                <td>
                                    {{ $valuation->land_name }}

                                    <br>

                                    <small class="text-muted">
                                        {{ $valuation->land_code }}
                                    </small>
                                </td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $valuation->status ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    ₹ {{ number_format((float) ($valuation->project_valuation ?? 0), 2) }}
                                </td>

                                <td>
                                    ₹ {{ number_format((float) ($valuation->investment_valuation ?? 0), 2) }}
                                </td>

                                <td>
                                    ₹ {{ number_format((float) ($valuation->exit_valuation ?? 0), 2) }}
                                </td>

                                <td class="text-success">
                                    ₹ {{ number_format((float) ($valuation->expected_exit_value ?? 0), 2) }}
                                </td>

                                <td>
                                    {{ $valuation->exit_strategy ?? '-' }}
                                </td>

                                <td>
                                    {{ $valuation->exit_period ?? '-' }}
                                </td>

                                <td>
                                    {{ $valuation->investment_risk_rating ?? '-' }}
                                </td>

                                <td>
                                    <a href="{{ route('admin.land.lands.show', $valuation->land_id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="ri-eye-line"></i>
                                        View
                                    </a>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="13"
                                    class="text-center text-muted py-4">
                                    No valuation records found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

<style>
    .valuation-card {
        border-radius: 12px;
        min-height: 160px;
    }

    .amount-value {
        font-size: 20px;
        white-space: nowrap;
    }
</style>

@endsection