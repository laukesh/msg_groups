@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Investment Reports</h4>
            <p class="text-muted mb-0">
                Portfolio-level investment and acquisition reporting
            </p>
        </div>

        <button type="button"
                class="btn btn-outline-secondary"
                onclick="window.print()">
            <i class="ri-printer-line me-1"></i>
            Print Report
        </button>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Total Lands</p>
                            <h3 class="mb-0">
                                {{ number_format($totalLands) }}
                            </h3>
                        </div>
                        <div class="avatar-sm bg-soft-primary rounded">
                            <i class="ri-landscape-line fs-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Total Land Area</p>
                            <h3 class="mb-0">
                                {{ number_format($totalLandArea, 2) }}
                            </h3>
                        </div>
                        <div class="avatar-sm bg-soft-success rounded">
                            <i class="ri-ruler-line fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted mb-1">
                                Acquisition Cost
                            </p>
                            <h3 class="mb-0">
                                ₹{{ number_format($totalAcquisitionCost, 2) }}
                            </h3>
                        </div>
                        <div class="avatar-sm bg-soft-warning rounded">
                            <i class="ri-money-rupee-circle-line fs-4 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted mb-1">
                                Feasibility Studies
                            </p>
                            <h3 class="mb-0">
                                {{ number_format($totalFeasibilityStudies) }}
                            </h3>
                        </div>
                        <div class="avatar-sm bg-soft-info rounded">
                            <i class="ri-file-chart-line fs-4 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Secondary Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        Investment Activity
                    </h5>

                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>Investment Analyses</span>
                        <strong>
                            {{ number_format($totalInvestmentAnalyses) }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between py-2">
                        <span>Investment Decisions</span>
                        <strong>
                            {{ number_format($totalInvestmentDecisions) }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- Acquisition Status --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        Acquisition Status
                    </h5>

                    @forelse($acquisitionStatus as $status)
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>
                                {{ $status->acquisition_status ?: 'Not Specified' }}
                            </span>

                            <span class="badge bg-primary">
                                {{ $status->total }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">
                            No acquisition status data available.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- Land Type Summary --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Land Type Summary</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Land Type</th>
                            <th>Total Lands</th>
                            <th>Total Area</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($landTypeSummary as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    {{ $item->land_type ?: 'Not Specified' }}
                                </td>
                                <td>{{ number_format($item->total) }}</td>
                                <td>{{ number_format($item->total_area, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No land type data available.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Investment Decisions --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">Recent Investment Decisions</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Land Code</th>
                            <th>Land Name</th>
                            <th>Assessment</th>
                            <th>Decision</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($recentInvestmentDecisions as $index => $decision)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    {{ $decision->land_code ?: '—' }}
                                </td>

                                <td>
                                    {{ $decision->land_name ?: '—' }}
                                </td>

                                <td>
                                    {{ $decision->assessment_name ?: '—' }}
                                </td>

                                <td>
                                    {{ $decision->decision ?: ($decision->status ?? '—') }}
                                </td>

                                <td>
                                    {{ !empty($decision->created_at)
                                        ? \Carbon\Carbon::parse($decision->created_at)->format('d-m-Y')
                                        : '—'
                                    }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No investment decisions available.
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