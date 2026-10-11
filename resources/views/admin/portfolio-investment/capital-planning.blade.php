@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Capital Planning</h4>
            <p class="text-muted mb-0">
                Review recommended, approved and pending investment capital.
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

        <div class="col-xl-4 col-md-4 col-sm-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Total Capital Plans
                    </div>

                    <h2 class="fw-bold mt-2 mb-0">
                        {{ $summary['total_plans'] }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4 col-sm-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Recommended Capital
                    </div>

                    <h5 class="fw-bold amount-value mt-2">
                        ₹ {{ number_format($summary['total_recommended'], 2) }}
                    </h5>

                    <small class="text-muted">
                        ₹ {{ number_format($summary['total_recommended'] / 10000000, 2) }} Cr
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4 col-sm-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Approved Capital
                    </div>

                    <h5 class="fw-bold text-success amount-value mt-2">
                        ₹ {{ number_format($summary['total_approved'], 2) }}
                    </h5>

                    <small class="text-muted">
                        ₹ {{ number_format($summary['total_approved'] / 10000000, 2) }} Cr
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4 col-sm-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Pending Capital
                    </div>

                    <h5 class="fw-bold text-warning amount-value mt-2">
                        ₹ {{ number_format($summary['pending_capital'], 2) }}
                    </h5>

                    <small class="text-muted">
                        ₹ {{ number_format($summary['pending_capital'] / 10000000, 2) }} Cr
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4 col-sm-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        High Priority
                    </div>

                    <h2 class="fw-bold text-danger mt-2 mb-0">
                        {{ $summary['high_priority'] }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4 col-sm-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Average Expected IRR
                    </div>

                    <h2 class="fw-bold text-primary mt-2 mb-0">
                        {{ $summary['average_irr'] !== null
                            ? number_format($summary['average_irr'], 2) . '%'
                            : '-' }}
                    </h2>
                </div>
            </div>
        </div>

    </div>

    {{-- Priority Summary --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">
            <h5 class="mb-0">Capital Requirement by Priority</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>Priority</th>
                            <th>Number of Plans</th>
                            <th>Recommended Capital</th>
                            <th>Approved Capital</th>
                            <th>Pending Capital</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($prioritySummary as $priority => $data)

                            @php
                                $pending = max(
                                    0,
                                    $data['recommended'] - $data['approved']
                                );
                            @endphp

                            <tr>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $priority }}
                                    </span>
                                </td>

                                <td>
                                    {{ $data['count'] }}
                                </td>

                                <td>
                                    ₹ {{ number_format($data['recommended'], 2) }}
                                </td>

                                <td class="text-success">
                                    ₹ {{ number_format($data['approved'], 2) }}
                                </td>

                                <td class="text-warning">
                                    ₹ {{ number_format($pending, 2) }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="5"
                                    class="text-center text-muted py-4">
                                    No capital planning data found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    {{-- Capital Planning Details --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">
            <h5 class="mb-0">Investment Capital Requirements</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Decision No.</th>
                            <th>Investment Title</th>
                            <th>Land</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Recommended Capital</th>
                            <th>Approved Capital</th>
                            <th>Pending Capital</th>
                            <th>Expected IRR</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($capitalPlans as $index => $plan)

                            @php
                                $recommended = (float) ($plan->recommended_investment ?? 0);
                                $approved = (float) ($plan->approved_investment ?? 0);
                                $pending = max(0, $recommended - $approved);
                            @endphp

                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <strong>
                                        {{ $plan->decision_number }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $plan->title ?? '-' }}
                                </td>

                                <td>
                                    {{ $plan->land_name }}

                                    <br>

                                    <small class="text-muted">
                                        {{ $plan->land_code }}
                                    </small>
                                </td>

                                <td>
                                    {{ $plan->investment_priority ?? '-' }}
                                </td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $plan->status ?? '-' }}
                                    </span>
                                </td>

                                <td class="text-nowrap">
                                    ₹ {{ number_format($recommended, 2) }}
                                </td>

                                <td class="text-success text-nowrap">
                                    ₹ {{ number_format($approved, 2) }}
                                </td>

                                <td class="text-warning text-nowrap">
                                    ₹ {{ number_format($pending, 2) }}
                                </td>

                                <td class="text-nowrap">
                                    {{ $plan->expected_irr !== null
                                        ? number_format($plan->expected_irr, 2) . '%'
                                        : '-' }}
                                </td>

                                <td>
                                    <a href="{{ route('admin.land.lands.show', $plan->land_id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="ri-eye-line"></i>
                                        View
                                    </a>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="11"
                                    class="text-center text-muted py-4">
                                    No capital planning records found.
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
    .amount-value {
        font-size: 18px;
        white-space: nowrap;
    }
</style>

@endsection