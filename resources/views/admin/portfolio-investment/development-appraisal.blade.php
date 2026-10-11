@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Development Appraisal</h4>
            <p class="text-muted mb-0">
                Financial evaluation of development opportunities.
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
                    <div class="text-muted small">Total Appraisals</div>
                    <h3 class="mb-0 mt-2">
                        {{ $summary['total_appraisals'] }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4 col-sm-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Investment</div>
                    <h5 class="mb-0 mt-2">
                        ₹ {{ number_format($summary['total_investment'], 2) }}
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4 col-sm-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Expected Revenue</div>
                    <h5 class="mb-0 mt-2">
                        ₹ {{ number_format($summary['expected_revenue'], 2) }}
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4 col-sm-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Expected Profit</div>
                    <h5 class="mb-0 mt-2">
                        ₹ {{ number_format($summary['expected_profit'], 2) }}
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4 col-sm-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Average ROI</div>
                    <h3 class="mb-0 mt-2">
                        {{ $summary['average_roi'] !== null
                            ? number_format($summary['average_roi'], 2) . '%'
                            : '-' }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4 col-sm-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Average IRR</div>
                    <h3 class="mb-0 mt-2">
                        {{ $summary['average_irr'] !== null
                            ? number_format($summary['average_irr'], 2) . '%'
                            : '-' }}
                    </h3>
                </div>
            </div>
        </div>

    </div>

    {{-- Appraisal Listing --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Development Appraisals</h5>

            <span class="badge bg-primary">
                {{ $appraisals->count() }} Records
            </span>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Analysis No.</th>
                            <th>Appraisal Title</th>
                            <th>Land</th>
                            <th>Status</th>
                            <th>Total Investment</th>
                            <th>Expected Revenue</th>
                            <th>Expected Profit</th>
                            <th>ROI</th>
                            <th>IRR</th>
                            <th>NPV</th>
                            <th>Payback</th>
                            <th>Risk Rating</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($appraisals as $index => $appraisal)

                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <strong>
                                        {{ $appraisal->analysis_number }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $appraisal->title ?? '-' }}

                                    @if($appraisal->feasibility_title)
                                        <br>
                                        <small class="text-muted">
                                            {{ $appraisal->feasibility_title }}
                                        </small>
                                    @endif
                                </td>

                                <td>
                                    {{ $appraisal->land_name }}

                                    <br>

                                    <small class="text-muted">
                                        {{ $appraisal->land_code }}
                                    </small>
                                </td>

                                <td>
                                    <span class="badge
                                        bg-{{ $appraisal->status === 'Approved'
                                            ? 'success'
                                            : ($appraisal->status === 'Draft'
                                                ? 'secondary'
                                                : 'warning') }}">
                                        {{ $appraisal->status }}
                                    </span>
                                </td>

                                <td>
                                    ₹ {{ number_format((float) ($appraisal->total_investment ?? 0), 2) }}
                                </td>

                                <td>
                                    ₹ {{ number_format((float) ($appraisal->expected_revenue ?? 0), 2) }}
                                </td>

                                <td>
                                    ₹ {{ number_format((float) ($appraisal->expected_profit ?? 0), 2) }}
                                </td>

                                <td>
                                    {{ $appraisal->roi !== null
                                        ? number_format($appraisal->roi, 2) . '%'
                                        : '-' }}
                                </td>

                                <td>
                                    {{ $appraisal->irr !== null
                                        ? number_format($appraisal->irr, 2) . '%'
                                        : '-' }}
                                </td>

                                <td>
                                    ₹ {{ number_format((float) ($appraisal->npv ?? 0), 2) }}
                                </td>

                                <td>
                                    {{ $appraisal->payback_period ?? '-' }}
                                </td>

                                <td>
                                    {{ $appraisal->investment_risk_rating ?? '-' }}
                                </td>

                                <td>
                                    <a href="{{ route('admin.land.lands.show', $appraisal->land_id) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="View Land">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="14" class="text-center text-muted py-4">
                                    No development appraisal records found.
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