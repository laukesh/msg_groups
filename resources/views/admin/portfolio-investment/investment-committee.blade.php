@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Investment Committee</h4>
            <p class="text-muted mb-0">
                Review investment decisions, approvals and committee recommendations.
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
                        Total Decisions
                    </div>

                    <h2 class="fw-bold mt-2 mb-0">
                        {{ $summary['total_decisions'] }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4 col-sm-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Pending Review
                    </div>

                    <h2 class="fw-bold text-warning mt-2 mb-0">
                        {{ $summary['pending_decisions'] }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4 col-sm-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Approved
                    </div>

                    <h2 class="fw-bold text-success mt-2 mb-0">
                        {{ $summary['approved_decisions'] }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4 col-sm-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Rejected
                    </div>

                    <h2 class="fw-bold text-danger mt-2 mb-0">
                        {{ $summary['rejected_decisions'] }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4 col-sm-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Approved Investment
                    </div>

                    <h5 class="fw-bold amount-value mt-2 mb-0">
                        ₹ {{ number_format($summary['total_approved_investment'], 2) }}
                    </h5>

                    <small class="text-muted">
                        ₹ {{ number_format($summary['total_approved_investment'] / 10000000, 2) }} Cr
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4 col-sm-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Average Score
                    </div>

                    <h2 class="fw-bold text-primary mt-2 mb-0">
                        {{ $summary['average_score'] !== null
                            ? number_format($summary['average_score'], 2)
                            : '-' }}
                    </h2>
                </div>
            </div>
        </div>

    </div>

    {{-- Committee Decisions --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">
            <h5 class="mb-0">Investment Committee Decisions</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Decision No.</th>
                            <th>Decision Title</th>
                            <th>Land</th>
                            <th>Committee</th>
                            <th>Decision Date</th>
                            <th>Status</th>
                            <th>Recommendation</th>
                            <th>Priority</th>
                            <th>Approved Investment</th>
                            <th>Expected IRR</th>
                            <th>Score</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($decisions as $index => $decision)

                            @php
                                $status = strtolower((string) $decision->status);

                                $statusClass = match($status) {
                                    'approved', 'approved with conditions' => 'success',
                                    'rejected', 'declined' => 'danger',
                                    'pending', 'under review', 'in progress' => 'warning',
                                    default => 'secondary',
                                };
                            @endphp

                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <strong>
                                        {{ $decision->decision_number }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $decision->title ?? '-' }}

                                    @if($decision->feasibility_title)
                                        <br>
                                        <small class="text-muted">
                                            {{ $decision->feasibility_title }}
                                        </small>
                                    @endif
                                </td>

                                <td>
                                    {{ $decision->land_name }}

                                    <br>

                                    <small class="text-muted">
                                        {{ $decision->land_code }}
                                    </small>
                                </td>

                                <td>
                                    {{ $decision->committee_name ?? '-' }}
                                </td>

                                <td>
                                    {{ $decision->decision_date
                                        ? \Carbon\Carbon::parse($decision->decision_date)->format('d-m-Y')
                                        : '-' }}
                                </td>

                                <td>
                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ $decision->status ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    {{ $decision->investment_recommendation ?? '-' }}
                                </td>

                                <td>
                                    {{ $decision->investment_priority ?? '-' }}
                                </td>

                                <td>
                                    ₹ {{ number_format((float) ($decision->approved_investment ?? 0), 2) }}
                                </td>

                                <td>
                                    {{ $decision->expected_irr !== null
                                        ? number_format($decision->expected_irr, 2) . '%'
                                        : '-' }}
                                </td>

                                <td>
                                    {{ $decision->overall_score ?? '-' }}
                                </td>

                                <td>
                                    <a href="{{ route('admin.land.lands.show', $decision->land_id) }}"
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
                                    No investment committee decisions found.
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
        font-size: 19px;
        white-space: nowrap;
    }
</style>

@endsection