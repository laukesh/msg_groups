@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Portfolio Monitoring & Performance</h4>
            <p class="text-muted mb-0">
                Monitor investment performance, project execution and portfolio progress
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
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted mb-2">
                        Total Assets
                    </div>

                    <h3 class="mb-0">
                        {{ number_format($stats['total_assets']) }}
                    </h3>
                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted mb-2">
                        Approved Assets
                    </div>

                    <h3 class="mb-0 text-success">
                        {{ number_format($stats['approved_assets']) }}
                    </h3>
                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted mb-2">
                        Projects Under Execution
                    </div>

                    <h3 class="mb-0 text-primary">
                        {{ number_format($stats['projects_under_execution']) }}
                    </h3>
                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted mb-2">
                        Average Project Progress
                    </div>

                    <h3 class="mb-0 text-info">
                        {{ number_format($stats['average_progress'], 2) }}%
                    </h3>
                </div>
            </div>
        </div>

    </div>


    {{-- Financial Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted mb-2">
                        Expected Revenue
                    </div>

                    <h4 class="text-success mb-0">
                        {{ number_format($stats['total_expected_revenue'], 2) }}
                    </h4>
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted mb-2">
                        Expected Profit
                    </div>

                    <h4 class="text-primary mb-0">
                        {{ number_format($stats['total_expected_profit'], 2) }}
                    </h4>
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted mb-2">
                        Completed Projects
                    </div>

                    <h4 class="text-dark mb-0">
                        {{ number_format($stats['completed_projects']) }}
                    </h4>
                </div>
            </div>
        </div>

    </div>


    {{-- Performance Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                Asset Performance Register
            </h5>

            <span class="badge bg-light text-dark">
                {{ $performance->count() }} Records
            </span>
        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Asset</th>
                            <th>Project</th>
                            <th>Decision</th>
                            <th>Project Status</th>
                            <th>Progress</th>
                            <th>Risk</th>
                            <th>Expected Revenue</th>
                            <th>Expected Profit</th>
                        </tr>
                    </thead>


                    <tbody>

                        @forelse($performance as $index => $item)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>


                                <td>
                                    <strong>
                                        {{ $item->land_name ?? 'N/A' }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ $item->land_code ?? '' }}
                                    </small>
                                </td>


                                <td>
                                    @if($item->project_id)
                                        <strong>
                                            {{ $item->project_name ?? 'Project' }}
                                        </strong>

                                        <br>

                                        <small class="text-muted">
                                            {{ $item->project_code ?? '' }}
                                        </small>
                                    @else
                                        <span class="text-muted">
                                            Not Created
                                        </span>
                                    @endif
                                </td>


                                <td>
                                    @if($item->decision_status)
                                        <span class="badge bg-success">
                                            {{ $item->decision_status }}
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            Pending
                                        </span>
                                    @endif
                                </td>


                                <td>
                                    @if($item->project_status)
                                        <span class="badge bg-info">
                                            {{ $item->project_status }}
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            Not Started
                                        </span>
                                    @endif
                                </td>


                                <td style="min-width: 150px;">

                                    @php
                                        $progress = (float) ($item->progress_percentage ?? 0);
                                    @endphp

                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar"
                                             role="progressbar"
                                             style="width: {{ min($progress, 100) }}%;">
                                        </div>
                                    </div>

                                    <small class="text-muted">
                                        {{ number_format($progress, 2) }}%
                                    </small>

                                </td>


                                <td>
                                    @php
                                        $risk = strtolower(
                                            (string) $item->investment_risk_rating
                                        );
                                    @endphp

                                    @if(in_array($risk, ['high', 'very high', 'critical']))
                                        <span class="badge bg-danger">
                                            {{ $item->investment_risk_rating }}
                                        </span>
                                    @elseif($risk === 'medium')
                                        <span class="badge bg-warning text-dark">
                                            {{ $item->investment_risk_rating }}
                                        </span>
                                    @elseif($risk)
                                        <span class="badge bg-success">
                                            {{ $item->investment_risk_rating }}
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            N/A
                                        </span>
                                    @endif
                                </td>


                                <td class="text-end">
                                    {{ number_format((float) ($item->expected_revenue ?? 0), 2) }}
                                </td>


                                <td class="text-end">
                                    {{ number_format((float) ($item->expected_profit ?? 0), 2) }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="9"
                                    class="text-center text-muted py-4">
                                    No portfolio performance records found.
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