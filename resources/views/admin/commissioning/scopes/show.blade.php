@extends('layouts.app')

@section('title', 'Commissioning Scope')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                {{ $scope->scope_code }}
                -
                {{ $scope->scope_name }}
            </h4>

            <div class="text-muted">

                {{ $project->project_number ?? $project->project_code ?? '' }}

                -

                {{ $project->project_name ?? 'Project' }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.commissioning.scopes.index',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="fas fa-arrow-left me-1"></i>
                Back
            </a>


            <a
                href="{{ route(
                    'admin.projects.commissioning.scopes.edit',
                    [
                        'project' => $project->id,
                        'scope' => $scope->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                <i class="fas fa-edit me-1"></i>
                Edit Scope
            </a>

        </div>

    </div>


    {{-- =========================================================
        SUMMARY CARDS
    ========================================================== --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Construction Progress
                    </div>

                    <div class="fs-3 fw-bold mt-1">
                        {{ number_format(
                            min(
                                100,
                                max(
                                    0,
                                    (float) ($scope->progress_percentage ?? 0)
                                )
                            ),
                            1
                        ) }}%
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Test Plans
                    </div>

                    <div class="fs-3 fw-bold mt-1">
                        {{ $testPlanCount }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Tests
                    </div>

                    <div class="fs-3 fw-bold mt-1">
                        {{ $testCount }}
                    </div>

                    <div class="small">

                        <span class="text-success">
                            {{ $passedTests }} Passed
                        </span>

                        @if($failedTests > 0)

                            <span class="text-danger ms-2">
                                {{ $failedTests }} Failed
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Certificates
                    </div>

                    <div class="fs-3 fw-bold mt-1">
                        {{ $approvedCertificates }}/{{ $certificateCount }}
                    </div>

                    <div class="small text-muted">
                        Approved
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        SCOPE INFORMATION
    ========================================================== --}}
    <div class="row g-3 mb-4">


        {{-- Scope Details --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <strong>
                        Commissioning Scope Details
                    </strong>

                </div>

                <div class="card-body">

                    <div class="row g-4">


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Scope Code
                            </div>

                            <div class="fw-semibold">
                                {{ $scope->scope_code }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Scope Type
                            </div>

                            <div class="fw-semibold">
                                {{ $scope->scope_type ?: '-' }}
                            </div>

                        </div>


                        <div class="col-md-8">

                            <div class="text-muted small">
                                Scope Name
                            </div>

                            <div class="fw-semibold">
                                {{ $scope->scope_name }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Location
                            </div>

                            <div class="fw-semibold">
                                {{ $scope->location ?: '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Status
                            </div>

                            @php

                                $statusClass = match($scope->status) {

                                    'Accepted',
                                    'Completed'
                                        => 'bg-success-subtle text-success',

                                    'Testing'
                                        => 'bg-info-subtle text-info',

                                    'In Progress'
                                        => 'bg-primary-subtle text-primary',

                                    'On Hold'
                                        => 'bg-warning-subtle text-warning',

                                    default
                                        => 'bg-secondary-subtle text-secondary',

                                };

                            @endphp

                            <span class="badge {{ $statusClass }}">
                                {{ $scope->status }}
                            </span>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Construction Work Order
                            </div>

                            @if($scope->workOrder)

                                <div class="fw-semibold">
                                    {{ $scope->workOrder->work_order_number }}
                                </div>

                                <div class="small text-muted">
                                    {{ $scope->workOrder->work_order_title }}
                                </div>

                            @else

                                <span class="text-muted">
                                    -
                                </span>

                            @endif

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Planned Start
                            </div>

                            <div>
                                {{ $scope->planned_start_date
                                    ? $scope->planned_start_date->format('d-m-Y')
                                    : '-' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Planned Completion
                            </div>

                            <div>
                                {{ $scope->planned_completion_date
                                    ? $scope->planned_completion_date->format('d-m-Y')
                                    : '-' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Actual Completion
                            </div>

                            <div>
                                {{ $scope->actual_completion_date
                                    ? $scope->actual_completion_date->format('d-m-Y')
                                    : '-' }}
                            </div>

                        </div>


                        <div class="col-12">

                            <div class="text-muted small">
                                Remarks
                            </div>

                            <div class="mt-1">

                                {!! $scope->remarks
                                    ? nl2br(e($scope->remarks))
                                    : '<span class="text-muted">No remarks provided.</span>' !!}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Test Status --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <strong>
                        Test Status
                    </strong>

                </div>

                <div class="card-body">


                    <div class="text-center mb-4">

                        <div class="display-6 fw-bold">
                            {{ number_format($testPassRate, 1) }}%
                        </div>

                        <div class="text-muted">
                            Test Pass Rate
                        </div>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span>
                            Total Tests
                        </span>

                        <strong>
                            {{ $testCount }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span>
                            Passed
                        </span>

                        <strong class="text-success">
                            {{ $passedTests }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span>
                            Failed
                        </span>

                        <strong class="text-danger">
                            {{ $failedTests }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between">

                        <span>
                            Pending
                        </span>

                        <strong class="text-warning">
                            {{ $pendingTests }}
                        </strong>

                    </div>


                    <div class="progress mt-4" style="height:8px">

                        <div
                            class="progress-bar bg-success"
                            style="width: {{ $testPassRate }}%"
                        ></div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        TEST PLANS
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Test Plans
                </strong>

                <a
                    href="{{ route(
                        'admin.projects.commissioning.test-plans.create',
                        ['project' => $project->id]
                    ) }}"
                    class="btn btn-sm btn-primary"
                >
                    <i class="fas fa-plus me-1"></i>
                    Add Test Plan
                </a>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-3">
                                Test Plan No.
                            </th>

                            <th>
                                Test Type
                            </th>

                            <th>
                                Planned Date
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($testPlans as $plan)

                            <tr>

                                <td class="ps-3 fw-semibold">
                                    {{ $plan->test_plan_no }}
                                </td>

                                <td>
                                    {{ $plan->test_type ?? '-' }}
                                </td>

                                <td>
                                    {{ $plan->planned_date
                                        ? $plan->planned_date->format('d-m-Y')
                                        : '-' }}
                                </td>

                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $plan->status }}
                                    </span>
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="text-center text-muted py-4"
                                >
                                    No test plans created for this scope.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =========================================================
        TEST EXECUTIONS
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>
                Test Executions
            </strong>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-3">
                                Test No.
                            </th>

                            <th>
                                Test Plan
                            </th>

                            <th>
                                Test Date
                            </th>

                            <th>
                                Result
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($tests as $test)

                            <tr>

                                <td class="ps-3 fw-semibold">
                                    {{ $test->test_no }}
                                </td>

                                <td>
                                    {{ $test->plan->test_plan_no ?? '-' }}
                                </td>

                                <td>
                                    {{ $test->test_date
                                        ? $test->test_date->format('d-m-Y')
                                        : '-' }}
                                </td>

                                <td>

                                    @if($test->result === 'Pass')

                                        <span class="badge bg-success">
                                            Pass
                                        </span>

                                    @elseif($test->result === 'Fail')

                                        <span class="badge bg-danger">
                                            Fail
                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">
                                            {{ $test->result ?? 'Pending' }}
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <span class="badge bg-light text-dark border">
                                        {{ $test->status }}
                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center text-muted py-4"
                                >
                                    No test executions recorded.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =========================================================
        CERTIFICATES
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>
                Commissioning Certificates
            </strong>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-3">
                                Certificate No.
                            </th>

                            <th>
                                Certificate Type
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($certificates as $certificate)

                            <tr>

                                <td class="ps-3 fw-semibold">
                                    {{ $certificate->certificate_no }}
                                </td>

                                <td>
                                    {{ $certificate->certificate_type ?? '-' }}
                                </td>

                                <td>
                                    {{ $certificate->certificate_date
                                        ? $certificate->certificate_date->format('d-m-Y')
                                        : '-' }}
                                </td>

                                <td>

                                    @if($certificate->status === 'Approved')

                                        <span class="badge bg-success">
                                            Approved
                                        </span>

                                    @else

                                        <span class="badge bg-light text-dark border">
                                            {{ $certificate->status }}
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="text-center text-muted py-4"
                                >
                                    No commissioning certificates recorded.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =========================================================
        QUICK ACTIONS
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <strong>
                Quick Actions
            </strong>

        </div>

        <div class="card-body">

            <div class="d-flex flex-wrap gap-2">

                <a
                    href="{{ route(
                        'admin.projects.commissioning.test-plans.create',
                        ['project' => $project->id]
                    ) }}"
                    class="btn btn-outline-primary"
                >
                    <i class="fas fa-clipboard-list me-1"></i>
                    Add Test Plan
                </a>


                <a
                    href="{{ route(
                        'admin.projects.commissioning.tests.create',
                        ['project' => $project->id]
                    ) }}"
                    class="btn btn-outline-success"
                >
                    <i class="fas fa-vial me-1"></i>
                    Record Test
                </a>


                <a
                    href="{{ route(
                        'admin.projects.commissioning.certificates.create',
                        ['project' => $project->id]
                    ) }}"
                    class="btn btn-outline-warning"
                >
                    <i class="fas fa-certificate me-1"></i>
                    Add Certificate
                </a>

            </div>

        </div>

    </div>

</div>

@endsection