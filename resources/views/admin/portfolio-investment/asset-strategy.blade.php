@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Asset Strategy & Business Plans</h4>
            <p class="text-muted mb-0">
                Strategic planning, development direction and investment alignment
            </p>
        </div>

        <div>
            <a href="{{ route('admin.portfolio-investment.overview') }}"
               class="btn btn-outline-secondary">
                <i class="ri-arrow-left-line"></i>
                Portfolio Overview
            </a>
        </div>
    </div>


    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted mb-2">
                        Total Strategic Plans
                    </div>

                    <h3 class="mb-0">
                        {{ number_format($stats['total_plans']) }}
                    </h3>
                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted mb-2">
                        Active Plans
                    </div>

                    <h3 class="mb-0 text-primary">
                        {{ number_format($stats['active_plans']) }}
                    </h3>
                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted mb-2">
                        Approved Plans
                    </div>

                    <h3 class="mb-0 text-success">
                        {{ number_format($stats['approved_plans']) }}
                    </h3>
                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted mb-2">
                        High Risk Plans
                    </div>

                    <h3 class="mb-0 text-danger">
                        {{ number_format($stats['high_risk_plans']) }}
                    </h3>
                </div>
            </div>
        </div>

    </div>


    {{-- Financial Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted mb-2">
                        Total Expected Revenue
                    </div>

                    <h4 class="text-success mb-0">
                        {{ number_format($stats['total_expected_revenue'], 2) }}
                    </h4>
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted mb-2">
                        Total Expected Profit
                    </div>

                    <h4 class="text-primary mb-0">
                        {{ number_format($stats['total_expected_profit'], 2) }}
                    </h4>
                </div>
            </div>
        </div>

    </div>


    {{-- Strategic Plans Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                Strategic Asset Plans
            </h5>

            <span class="badge bg-light text-dark">
                {{ $plans->count() }} Records
            </span>
        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Asset / Land</th>
                            <th>Project Concept</th>
                            <th>Development Type</th>
                            <th>Feasibility Status</th>
                            <th>Investment Decision</th>
                            <th>Risk</th>
                            <th>Expected Revenue</th>
                            <th>Expected Profit</th>
                            <th>Action</th>
                        </tr>
                    </thead>


                    <tbody>

                        @forelse($plans as $index => $plan)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>


                                <td>
                                    <strong>
                                        {{ $plan->land_name ?? 'N/A' }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ $plan->land_code ?? '' }}
                                    </small>
                                </td>


                                <td>
                                    <strong>
                                        {{ $plan->title ?? 'Untitled Plan' }}
                                    </strong>

                                    @if(!empty($plan->project_concept))
                                        <br>
                                        <small class="text-muted">
                                            {{ \Illuminate\Support\Str::limit($plan->project_concept, 100) }}
                                        </small>
                                    @endif
                                </td>


                                <td>
                                    {{ $plan->development_type ?? 'N/A' }}
                                </td>


                                <td>
                                    @php
                                        $feasibilityStatus = strtolower(
                                            (string) $plan->feasibility_status
                                        );
                                    @endphp

                                    @if(in_array($feasibilityStatus, ['approved', 'completed']))
                                        <span class="badge bg-success">
                                            {{ $plan->feasibility_status }}
                                        </span>
                                    @elseif(in_array($feasibilityStatus, ['under review', 'in progress']))
                                        <span class="badge bg-warning text-dark">
                                            {{ $plan->feasibility_status }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ $plan->feasibility_status ?? 'N/A' }}
                                        </span>
                                    @endif
                                </td>


                                <td>
                                    @if($plan->decision_status)
                                        @php
                                            $decisionStatus = strtolower(
                                                (string) $plan->decision_status
                                            );
                                        @endphp

                                        @if(in_array($decisionStatus, ['approved', 'approve']))
                                            <span class="badge bg-success">
                                                {{ $plan->decision_status }}
                                            </span>
                                        @elseif(in_array($decisionStatus, ['rejected', 'reject']))
                                            <span class="badge bg-danger">
                                                {{ $plan->decision_status }}
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                {{ $plan->decision_status }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted">
                                            Not Available
                                        </span>
                                    @endif
                                </td>


                                <td>
                                    @php
                                        $risk = strtolower(
                                            (string) $plan->investment_risk_rating
                                        );
                                    @endphp

                                    @if(in_array($risk, ['high', 'very high', 'critical']))
                                        <span class="badge bg-danger">
                                            {{ $plan->investment_risk_rating }}
                                        </span>
                                    @elseif($risk === 'medium')
                                        <span class="badge bg-warning text-dark">
                                            {{ $plan->investment_risk_rating }}
                                        </span>
                                    @elseif($risk)
                                        <span class="badge bg-success">
                                            {{ $plan->investment_risk_rating }}
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            N/A
                                        </span>
                                    @endif
                                </td>


                                <td class="text-end">
                                    {{ number_format((float) ($plan->expected_revenue ?? 0), 2) }}
                                </td>


                                <td class="text-end">
                                    {{ number_format((float) ($plan->expected_profit ?? 0), 2) }}
                                </td>


                                <td>
                                    <a href="{{ url()->previous() }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="ri-eye-line"></i>
                                        View
                                    </a>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    No strategic planning records found.
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