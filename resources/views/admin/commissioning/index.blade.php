@extends('layouts.app')

@section('title', 'Commissioning Management')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PROJECT HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Commissioning Management
            </h4>

            <div class="text-muted">
                {{ $project->project_code ?? $project->project_number ?? '' }}
                -
                {{ $project->project_name ?? 'Project' }}
            </div>

        </div>

        <a
            href="{{ route(
                'admin.commissioning.index'
            ) }}"
            class="btn btn-outline-secondary"
        >
            <i class="fas fa-arrow-left me-1"></i>
            Back
        </a>

    </div>

    {{-- =========================================================
        SUMMARY CARDS
    ========================================================== --}}
    <div class="row g-3 mb-4">


        {{-- Scopes --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Commissioning Scopes
                    </div>

                    <div class="fs-3 fw-bold mt-1">
                        {{ $totalScopes }}
                    </div>

                    <div class="small text-success">
                        {{ $completedScopes }} Completed
                    </div>

                </div>

            </div>

        </div>


        {{-- Test Plans --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Test Plans
                    </div>

                    <div class="fs-3 fw-bold mt-1">
                        {{ $totalPlans }}
                    </div>

                    <div class="small text-success">
                        {{ $approvedPlans }} Approved
                    </div>

                </div>

            </div>

        </div>


        {{-- Tests --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Tests
                    </div>

                    <div class="fs-3 fw-bold mt-1">
                        {{ $totalTests }}
                    </div>

                    <div class="small text-warning">
                        {{ $pendingTests }} Pending
                    </div>

                </div>

            </div>

        </div>


        {{-- Passed --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Tests Passed
                    </div>

                    <div class="fs-3 fw-bold text-success mt-1">
                        {{ $passedTests }}
                    </div>

                    <div class="small text-muted">
                        Successful
                    </div>

                </div>

            </div>

        </div>


        {{-- Failed --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Tests Failed
                    </div>

                    <div class="fs-3 fw-bold
                        {{ $failedTests > 0 ? 'text-danger' : 'text-success' }}
                        mt-1"
                    >
                        {{ $failedTests }}
                    </div>

                    <div class="small text-muted">
                        Requires resolution
                    </div>

                </div>

            </div>

        </div>


        {{-- Certificates --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Certificates
                    </div>

                    <div class="fs-3 fw-bold mt-1">
                        {{ $approvedCertificates }}
                    </div>

                    <div class="small text-muted">
                        of {{ $totalCertificates }} Approved
                    </div>

                </div>

            </div>

        </div>

    </div>
    {{-- =========================================================
        COMMISSIONING MANAGEMENT MODULES
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <strong>
                Commissioning Management
            </strong>

        </div>

        <div class="card-body">

            <div class="row g-3">


                {{-- =================================================
                    DASHBOARD
                ================================================== --}}
                <div class="col-xl-4 col-lg-4 col-md-4">

                    <div class="card h-100 border">

                        <div class="card-body">

                            <h6 class="fw-semibold">
                                <i class="fas fa-chart-line me-2 text-primary"></i>
                                Project Dashboard
                            </h6>

                            <p class="text-muted small mb-3">
                                Overall commissioning progress, testing
                                status and handover readiness.
                            </p>

                            <a
                                href="{{ route(
                                    'admin.projects.commissioning.index',
                                    ['project' => $project->id]
                                ) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Open Module
                            </a>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    SCOPES
                ================================================== --}}
                <div class="col-xl-4 col-lg-4 col-md-4">

                    <div class="card h-100 border">

                        <div class="card-body">

                            <h6 class="fw-semibold">
                                <i class="fas fa-layer-group me-2 text-success"></i>
                                Commissioning Scopes
                            </h6>

                            <p class="text-muted small mb-3">
                                Manage commissioning scopes linked with
                                Construction Work Orders.
                            </p>

                            <a
                                href="{{ route(
                                    'admin.projects.commissioning.scopes.index',
                                    ['project' => $project->id]
                                ) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Open Module
                            </a>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    TEST PLANS
                ================================================== --}}
                <div class="col-xl-4 col-lg-4 col-md-4">

                    <div class="card h-100 border">

                        <div class="card-body">

                            <h6 class="fw-semibold">
                                <i class="fas fa-clipboard-list me-2 text-info"></i>
                                Test Plans
                            </h6>

                            <p class="text-muted small mb-3">
                                Prepare, manage and approve commissioning
                                test plans.
                            </p>

                            <a
                                href="{{ route(
                                    'admin.projects.commissioning.test-plans.index',
                                    ['project' => $project->id]
                                ) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Open Module
                            </a>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    TEST EXECUTIONS
                ================================================== --}}
                <div class="col-xl-4 col-lg-4 col-md-4">

                    <div class="card h-100 border">

                        <div class="card-body">

                            <h6 class="fw-semibold">
                                <i class="fas fa-vial me-2 text-warning"></i>
                                Test Executions
                            </h6>

                            <p class="text-muted small mb-3">
                                Record test execution, results,
                                measurements and retesting.
                            </p>

                            <a
                                href="{{ route(
                                    'admin.projects.commissioning.tests.index',
                                    ['project' => $project->id]
                                ) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Open Module
                            </a>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    CERTIFICATES
                ================================================== --}}
                <div class="col-xl-4 col-lg-4 col-md-4">

                    <div class="card h-100 border">

                        <div class="card-body">

                            <h6 class="fw-semibold">
                                <i class="fas fa-certificate me-2 text-danger"></i>
                                Certificates
                            </h6>

                            <p class="text-muted small mb-3">
                                Manage commissioning certificates,
                                submissions and approvals.
                            </p>

                            <a
                                href="{{ route(
                                    'admin.projects.commissioning.certificates.index',
                                    ['project' => $project->id]
                                ) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Open Module
                            </a>

                        </div>

                    </div>

                </div>

                {{-- =================================================
                    Documents & Evidence
                ================================================== --}}
                <div class="col-xl-4 col-lg-4 col-md-4">

                    <div class="card h-100 border">

                        <div class="card-body">

                            <h6 class="fw-semibold">
                                
                                 <i class="fas fa-folder me-2"></i>
                                Documents & Evidence
                            </h6>

                            <p class="text-muted small mb-3">
                                Test reports, certificates, photos and supporting documents
                            </p>

                            <a
                                href="{{ route(
                                    'admin.projects.commissioning.documents.index',
                                    ['project' => $project->id]
                                ) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Open Module
                            </a>

                        </div>

                    </div>

                </div>


                <div class="col-xl-4 col-lg-4 col-md-4">

                    <div class="card h-100 border">

                        <div class="card-body">

                            <h6 class="fw-semibold">
                                <i class="fas fa-history me-2 text-primary"></i>
                                Commissioning Audit Log
                            </h6>

                            <p class="text-muted small mb-3">
                                View the complete history of commissioning activities,
                                workflow changes, approvals, rejections, and user actions.
                            </p>

                            <a href="{{ route('admin.projects.commissioning.audit.index', $project) }}"
                               class="btn btn-sm btn-outline-primary">
                                Open Module
                            </a>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    HANDOVER READINESS
                ================================================== --}}
                <div class="col-xl-4 col-lg-4 col-md-4">

                    <div class="card h-100 border">

                        <div class="card-body">

                            <h6 class="fw-semibold">
                                <i class="fas fa-check-circle me-2 text-success"></i>
                                Handover Readiness
                            </h6>

                            <p class="text-muted small mb-3">
                                Check whether commissioning requirements
                                are complete before Handover & Closeout.
                            </p>

                            <a
                                href="#handover-readiness"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Open Module
                            </a>

                        </div>

                    </div>

                </div>


            </div>

        </div>

    </div>





    {{-- =========================================================
        PROGRESS + HANDOVER READINESS
    ========================================================== --}}

    @php

        $testCompletion = $totalTests > 0
            ? (($passedTests + $failedTests) / $totalTests) * 100
            : 0;

        $certificateProgress = $totalCertificates > 0
            ? ($approvedCertificates / $totalCertificates) * 100
            : 0;

        $testPassRate = $totalTests > 0
            ? ($passedTests / $totalTests) * 100
            : 0;

    @endphp


    <div class="row g-3 mb-4">


        {{-- =====================================================
            COMMISSIONING PROGRESS
        ====================================================== --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <strong>
                        Commissioning Progress
                    </strong>

                </div>

                <div class="card-body">


                    {{-- Construction / Scope Progress --}}
                    <div class="mb-4">

                        <div class="d-flex justify-content-between mb-1">

                            <span>
                                Scope / Construction Progress
                            </span>

                            <strong>
                                {{ number_format($scopeProgress, 1) }}%
                            </strong>

                        </div>

                        <div
                            class="progress"
                            style="height: 10px;"
                        >

                            <div
                                class="progress-bar"
                                style="width: {{ min(100, max(0, $scopeProgress)) }}%"
                            ></div>

                        </div>

                    </div>


                    {{-- Test Execution --}}
                    <div class="mb-4">

                        <div class="d-flex justify-content-between mb-1">

                            <span>
                                Test Execution
                            </span>

                            <strong>
                                {{ number_format($testCompletion, 1) }}%
                            </strong>

                        </div>

                        <div
                            class="progress"
                            style="height: 10px;"
                        >

                            <div
                                class="progress-bar"
                                style="width: {{ min(100, max(0, $testCompletion)) }}%"
                            ></div>

                        </div>

                    </div>


                    {{-- Certificate Approval --}}
                    <div>

                        <div class="d-flex justify-content-between mb-1">

                            <span>
                                Certificate Approval
                            </span>

                            <strong>
                                {{ number_format($certificateProgress, 1) }}%
                            </strong>

                        </div>

                        <div
                            class="progress"
                            style="height: 10px;"
                        >

                            <div
                                class="progress-bar"
                                style="width: {{ min(100, max(0, $certificateProgress)) }}%"
                            ></div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            HANDOVER READINESS
        ====================================================== --}}
        <div
            class="col-lg-4"
            id="handover-readiness"
        >

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <strong>
                        Handover Readiness
                    </strong>

                </div>

                <div class="card-body">


                    <div class="text-center mb-4">

                        @if($readyForHandover)

                            <div class="display-5 text-success">

                                <i class="fas fa-check-circle"></i>

                            </div>

                            <h5 class="text-success mt-2 mb-0">
                                Commissioning Ready
                            </h5>

                        @else

                            <div class="display-5 text-warning">

                                <i class="fas fa-exclamation-circle"></i>

                            </div>

                            <h5 class="text-warning mt-2 mb-0">
                                Not Ready
                            </h5>

                        @endif

                    </div>


                    <div class="small">


                        <div class="d-flex justify-content-between mb-3">

                            <span>
                                Scopes Complete
                            </span>

                            <strong>
                                {{ $completedScopes }}/{{ $totalScopes }}
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                            <span>
                                Pending Tests
                            </span>

                            <strong
                                class="{{ $pendingTests > 0
                                    ? 'text-warning'
                                    : 'text-success' }}"
                            >
                                {{ $pendingTests }}
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                            <span>
                                Failed Tests
                            </span>

                            <strong
                                class="{{ $failedTests > 0
                                    ? 'text-danger'
                                    : 'text-success' }}"
                            >
                                {{ $failedTests }}
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between">

                            <span>
                                Certificates Approved
                            </span>

                            <strong>
                                {{ $approvedCertificates }}/{{ $totalCertificates }}
                            </strong>

                        </div>

                    </div>


                    @if($readyForHandover)

                        <div class="alert alert-success mt-4 mb-0">

                            <small>
                                All commissioning-level requirements
                                currently satisfy the readiness gate.
                            </small>

                        </div>

                    @else

                        <div class="alert alert-warning mt-4 mb-0">

                            <small>
                                Complete pending scopes, tests or
                                certificates before commissioning is
                                considered ready.
                            </small>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        COMMISSIONING BY WORK ORDER
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Commissioning Progress by Work Order
                    </strong>

                    <div class="text-muted small">
                        Construction Work Orders linked to commissioning scopes.
                    </div>

                </div>

                <a
                    href="{{ route(
                        'admin.projects.commissioning.scopes.index',
                        ['project' => $project->id]
                    ) }}"
                    class="btn btn-sm btn-outline-primary"
                >
                    View All
                </a>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-3">
                                Work Order
                            </th>

                            <th>
                                Scope
                            </th>

                            <th>
                                Construction Progress
                            </th>

                            <th>
                                Test Plans
                            </th>

                            <th>
                                Tests
                            </th>

                            <th>
                                Certificates
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($scopes as $scope)

                            @php

                                $progress = min(
                                    100,
                                    max(
                                        0,
                                        (float) (
                                            $scope->progress_percentage ?? 0
                                        )
                                    )
                                );

                                $statusClass = match($scope->status) {

                                    'Completed',
                                    'Accepted'
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

                            <tr>

                                <td class="ps-3">

                                    <strong>
                                        {{ $scope->workOrder->work_order_number ?? '-' }}
                                    </strong>

                                    <div class="small text-muted">
                                        {{ $scope->workOrder->work_order_title ?? '' }}
                                    </div>

                                </td>


                                <td>

                                    <strong>
                                        {{ $scope->scope_code }}
                                    </strong>

                                    <div class="small text-muted">
                                        {{ $scope->scope_name }}
                                    </div>

                                </td>


                                <td style="min-width:180px">

                                    <div class="d-flex justify-content-between mb-1">

                                        <small>
                                            Progress
                                        </small>

                                        <small>
                                            {{ number_format($progress, 1) }}%
                                        </small>

                                    </div>

                                    <div
                                        class="progress"
                                        style="height:7px"
                                    >

                                        <div
                                            class="progress-bar"
                                            style="width: {{ $progress }}%"
                                        ></div>

                                    </div>

                                </td>


                                <td>

                                    <span class="badge bg-light text-dark border">

                                        {{ $scope->test_plans_count }}

                                    </span>

                                </td>


                                <td>

                                    <span class="badge bg-light text-dark border">

                                        {{ $scope->tests_count }}

                                    </span>

                                </td>


                                <td>

                                    <span class="badge bg-light text-dark border">

                                        {{ $scope->certificates_count }}

                                    </span>

                                </td>


                                <td>

                                    <span class="badge {{ $statusClass }}">

                                        {{ $scope->status }}

                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted mb-2">

                                        <i class="fas fa-layer-group fs-2"></i>

                                    </div>

                                    <div class="fw-semibold">
                                        No commissioning scopes found.
                                    </div>

                                    <div class="small text-muted mb-3">
                                        Create a commissioning scope from
                                        an existing Construction Work Order.
                                    </div>

                                    <a
                                        href="{{ route(
                                            'admin.projects.commissioning.scopes.create',
                                            ['project' => $project->id]
                                        ) }}"
                                        class="btn btn-sm btn-primary"
                                    >
                                        <i class="fas fa-plus me-1"></i>
                                        Add Commissioning Scope
                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =========================================================
        TEST SUMMARY
    ========================================================== --}}
    <div class="row g-3 mb-4">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <strong>
                        Test Execution Summary
                    </strong>

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-3">

                            <div class="border rounded p-3 text-center">

                                <div class="text-muted small">
                                    Total
                                </div>

                                <div class="fs-3 fw-bold">
                                    {{ $totalTests }}
                                </div>

                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="border rounded p-3 text-center">

                                <div class="text-muted small">
                                    Passed
                                </div>

                                <div class="fs-3 fw-bold text-success">
                                    {{ $passedTests }}
                                </div>

                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="border rounded p-3 text-center">

                                <div class="text-muted small">
                                    Failed
                                </div>

                                <div class="fs-3 fw-bold text-danger">
                                    {{ $failedTests }}
                                </div>

                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="border rounded p-3 text-center">

                                <div class="text-muted small">
                                    Pending
                                </div>

                                <div class="fs-3 fw-bold text-warning">
                                    {{ $pendingTests }}
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="mt-4">

                        <div class="d-flex justify-content-between mb-1">

                            <span>
                                Test Pass Rate
                            </span>

                            <strong>
                                {{ number_format($testPassRate, 1) }}%
                            </strong>

                        </div>

                        <div
                            class="progress"
                            style="height:8px"
                        >

                            <div
                                class="progress-bar"
                                style="width: {{ min(100, max(0, $testPassRate)) }}%"
                            ></div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Quick Actions --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <strong>
                        Quick Actions
                    </strong>

                </div>

                <div class="card-body">

                    <div class="d-grid gap-2">

                        <a
                            href="{{ route(
                                'admin.projects.commissioning.scopes.create',
                                ['project' => $project->id]
                            ) }}"
                            class="btn btn-outline-primary"
                        >
                            <i class="fas fa-layer-group me-1"></i>
                            Add Commissioning Scope
                        </a>


                        <a
                            href="{{ route(
                                'admin.projects.commissioning.test-plans.create',
                                ['project' => $project->id]
                            ) }}"
                            class="btn btn-outline-info"
                        >
                            <i class="fas fa-clipboard-list me-1"></i>
                            Create Test Plan
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

    </div>

</div>

@endsection