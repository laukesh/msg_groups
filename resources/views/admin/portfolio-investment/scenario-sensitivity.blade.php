@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Scenario & Sensitivity Analysis</h4>
            <p class="text-muted mb-0">
                Compare investment returns under different assumptions.
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
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Total Analyses
                    </div>

                    <h2 class="fw-bold mt-2 mb-0">
                        {{ $summary['total_analyses'] }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Average Base Case IRR
                    </div>

                    <h2 class="fw-bold mt-2 mb-0 text-primary">
                        {{ $summary['average_base_irr'] !== null
                            ? number_format($summary['average_base_irr'], 2) . '%'
                            : '-' }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Average Optimistic IRR
                    </div>

                    <h2 class="fw-bold mt-2 mb-0 text-success">
                        {{ $summary['average_optimistic_irr'] !== null
                            ? number_format($summary['average_optimistic_irr'], 2) . '%'
                            : '-' }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        High Risk Analyses
                    </div>

                    <h2 class="fw-bold mt-2 mb-0 text-danger">
                        {{ $summary['high_risk_count'] }}
                    </h2>
                </div>
            </div>
        </div>

    </div>

    {{-- Scenario Comparison --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">
            <h5 class="mb-0">Scenario Return Comparison</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Analysis No.</th>
                            <th>Appraisal</th>
                            <th>Land</th>
                            <th>Base Case IRR</th>
                            <th>Optimistic IRR</th>
                            <th>Pessimistic IRR</th>
                            <th>Risk Rating</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($analyses as $index => $analysis)

                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <strong>
                                        {{ $analysis->analysis_number }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $analysis->title ?? '-' }}
                                </td>

                                <td>
                                    {{ $analysis->land_name }}

                                    <br>

                                    <small class="text-muted">
                                        {{ $analysis->land_code }}
                                    </small>
                                </td>

                                <td>
                                    <span class="badge bg-primary">
                                        {{ $analysis->base_case_irr !== null
                                            ? number_format($analysis->base_case_irr, 2) . '%'
                                            : '-' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-success">
                                        {{ $analysis->optimistic_case_irr !== null
                                            ? number_format($analysis->optimistic_case_irr, 2) . '%'
                                            : '-' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-danger">
                                        {{ $analysis->pessimistic_case_irr !== null
                                            ? number_format($analysis->pessimistic_case_irr, 2) . '%'
                                            : '-' }}
                                    </span>
                                </td>

                                <td>
                                    @php
                                        $risk = strtolower(
                                            (string) $analysis->investment_risk_rating
                                        );

                                        $riskClass = match($risk) {
                                            'high', 'very high', 'critical' => 'danger',
                                            'medium', 'moderate' => 'warning',
                                            'low' => 'success',
                                            default => 'secondary',
                                        };
                                    @endphp

                                    <span class="badge bg-{{ $riskClass }}">
                                        {{ $analysis->investment_risk_rating ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $analysis->status ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    <a href="{{ route('admin.land.lands.show', $analysis->land_id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="ri-eye-line"></i>
                                        View
                                    </a>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="10"
                                    class="text-center text-muted py-4">
                                    No scenario analysis records found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    {{-- Sensitivity Details --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">
            <h5 class="mb-0">Sensitivity Factors</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Analysis No.</th>
                            <th>Revenue Sensitivity</th>
                            <th>Cost Sensitivity</th>
                            <th>Price Sensitivity</th>
                            <th>Interest Rate Sensitivity</th>
                            <th>Investment Attractiveness</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($analyses as $index => $analysis)

                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    {{ $analysis->analysis_number }}
                                </td>

                                <td>
                                    {{ $analysis->revenue_sensitivity ?? '-' }}
                                </td>

                                <td>
                                    {{ $analysis->cost_sensitivity ?? '-' }}
                                </td>

                                <td>
                                    {{ $analysis->price_sensitivity ?? '-' }}
                                </td>

                                <td>
                                    {{ $analysis->interest_rate_sensitivity ?? '-' }}
                                </td>

                                <td>
                                    {{ $analysis->investment_attractiveness ?? '-' }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="7"
                                    class="text-center text-muted py-4">
                                    No sensitivity data available.
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