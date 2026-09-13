@extends('layouts.app')

@section('title', 'Commissioning Test - ' . $test->test_no)

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <h4 class="mb-0">
                    {{ $test->test_no }}
                </h4>

                @php

                    $resultClass = match($test->result) {
                        'Pass' => 'success',
                        'Fail' => 'danger',
                        'Conditional Pass' => 'warning',
                        default => 'secondary',
                    };

                    $statusClass = match($test->status) {
                        'Completed' => 'success',
                        'In Progress' => 'primary',
                        'Retest Required' => 'warning',
                        'Cancelled' => 'dark',
                        default => 'secondary',
                    };

                @endphp

                <span class="badge bg-{{ $resultClass }}">
                    {{ $test->result }}
                </span>

                <span class="badge bg-{{ $statusClass }}">
                    {{ $test->status }}
                </span>

            </div>

            <div class="text-muted">

                {{ $project->project_code ?? '' }}

                @if($test->scope)
                    · {{ $test->scope->scope_name }}
                @endif

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.projects.commissioning.tests.index', $project) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

            @if($test->status !== 'Completed')

                <a
                    href="{{ route('admin.projects.commissioning.tests.edit', [$project, $test]) }}"
                    class="btn btn-primary"
                >
                    <i class="bi bi-pencil me-1"></i>
                    Edit
                </a>

            @endif

        </div>

    </div>


    {{-- FLASH --}}
    @if(session('success'))

        <div class="alert alert-success">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}
        </div>

    @endif

    @if(session('error'))

        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-1"></i>
            {{ session('error') }}
        </div>

    @endif


    {{-- RETEST ALERT --}}
    @if($test->parentTest)

        <div class="alert alert-warning">

            <i class="bi bi-arrow-repeat me-1"></i>

            This is a retest of

            <a
                href="{{ route('admin.projects.commissioning.tests.show', [$project, $test->parentTest]) }}"
                class="fw-semibold"
            >
                {{ $test->parentTest->test_no }}
            </a>

        </div>

    @endif


    {{-- RESULT SUMMARY --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Result
                    </div>

                    <div class="fs-4 fw-bold text-{{ $resultClass }}">
                        {{ $test->result }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Status
                    </div>

                    <div class="fs-4 fw-bold">
                        {{ $test->status }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Retests
                    </div>

                    <div class="fs-4 fw-bold">
                        {{ $totalRetests }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Retest Result
                    </div>

                    <div class="fs-4 fw-bold">

                        @if($passedRetests)
                            <span class="text-success">
                                {{ $passedRetests }} Passed
                            </span>
                        @elseif($failedRetests)
                            <span class="text-danger">
                                {{ $failedRetests }} Failed
                            </span>
                        @else
                            —
                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">

        {{-- LEFT --}}
        <div class="col-lg-8">


            {{-- TEST INFORMATION --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-info-circle me-1"></i>
                        Test Information
                    </h6>

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Test Number
                            </div>

                            <div class="fw-semibold">
                                {{ $test->test_no }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Test Date
                            </div>

                            <div>
                                {{ $test->test_date?->format('d M Y') ?? '—' }}

                                @if($test->test_time)
                                    {{ \Carbon\Carbon::parse($test->test_time)->format('h:i A') }}
                                @endif
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Commissioning Scope
                            </div>

                            @if($test->scope)

                                <a
                                    href="{{ route('admin.projects.commissioning.scopes.show', [$project, $test->scope]) }}"
                                    class="fw-semibold text-decoration-none"
                                >
                                    {{ $test->scope->scope_code }}
                                </a>

                                <div class="small text-muted">
                                    {{ $test->scope->scope_name }}
                                </div>

                            @else
                                —
                            @endif

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Test Plan
                            </div>

                            @if($test->plan)

                                <a
                                    href="{{ route('admin.projects.commissioning.test-plans.show', [$project, $test->plan]) }}"
                                    class="fw-semibold text-decoration-none"
                                >
                                    {{ $test->plan->test_plan_no }}
                                </a>

                                <div class="small text-muted">
                                    {{ $test->plan->title }}
                                </div>

                            @else
                                —
                            @endif

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Test Type
                            </div>

                            <div>
                                {{ $test->test_type ?: '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Location
                            </div>

                            <div>
                                {{ $test->location ?: '—' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- RESULTS --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-clipboard-data me-1"></i>
                        Test Results
                    </h6>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Expected Result
                            </div>

                            <div class="border rounded p-3 bg-light">

                                {!! nl2br(e($test->expected_result ?: 'Not specified')) !!}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Actual Result
                            </div>

                            <div class="border rounded p-3">

                                {!! nl2br(e($test->actual_result ?: 'Not recorded')) !!}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Measured Value
                            </div>

                            <div class="fs-5 fw-semibold">

                                {{ $test->measured_value ?: '—' }}

                                @if($test->measured_unit)
                                    {{ $test->measured_unit }}
                                @endif

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Result
                            </div>

                            <span class="badge bg-{{ $resultClass }}">
                                {{ $test->result }}
                            </span>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Retest Required
                            </div>

                            @if($test->retest_required)

                                <span class="badge bg-warning text-dark">
                                    Yes
                                </span>

                            @else

                                <span class="badge bg-success">
                                    No
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- EXECUTION --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-person-check me-1"></i>
                        Execution Details
                    </h6>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Performed By
                            </div>

                            <div>
                                {{ $test->performedBy?->name ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Witnessed By
                            </div>

                            <div>
                                {{ $test->witnessedBy?->name ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Consultant Representative
                            </div>

                            <div>
                                {{ $test->consultant_representative ?: '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Client Representative
                            </div>

                            <div>
                                {{ $test->client_representative ?: '—' }}
                            </div>

                        </div>


                        <div class="col-12">

                            <div class="text-muted small">
                                Site Condition
                            </div>

                            <div class="border rounded p-3">

                                {!! nl2br(e($test->site_condition ?: '—')) !!}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- RETEST HISTORY --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white d-flex justify-content-between">

                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-arrow-repeat me-1"></i>
                        Retest History
                    </h6>

                    @if(
                        $test->result === 'Fail'
                        || $test->retest_required
                        || $test->status === 'Retest Required'
                    )

                        <a
                            href="{{ route('admin.projects.commissioning.tests.retest.create', [$project, $test]) }}"
                            class="btn btn-sm btn-warning"
                        >
                            <i class="bi bi-plus-lg me-1"></i>
                            Create Retest
                        </a>

                    @endif

                </div>


                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th>Test No</th>
                                <th>Date</th>
                                <th>Result</th>
                                <th>Status</th>
                                <th></th>
                            </tr>

                        </thead>

                        <tbody>

                        @forelse($retests as $retest)

                            <tr>

                                <td class="fw-semibold">
                                    {{ $retest->test_no }}
                                </td>

                                <td>
                                    {{ $retest->test_date?->format('d M Y') ?? '—' }}
                                </td>

                                <td>

                                    @php

                                        $rClass = match($retest->result) {
                                            'Pass' => 'success',
                                            'Fail' => 'danger',
                                            'Conditional Pass' => 'warning',
                                            default => 'secondary',
                                        };

                                    @endphp

                                    <span class="badge bg-{{ $rClass }}">
                                        {{ $retest->result }}
                                    </span>

                                </td>

                                <td>
                                    {{ $retest->status }}
                                </td>

                                <td class="text-end">

                                    <a
                                        href="{{ route('admin.projects.commissioning.tests.show', [$project, $retest]) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center py-4 text-muted"
                                >
                                    No retests recorded.
                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- REMARKS --}}
            @if($test->remarks)

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white">

                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-chat-left-text me-1"></i>
                            Remarks
                        </h6>

                    </div>

                    <div class="card-body">

                        {!! nl2br(e($test->remarks)) !!}

                    </div>

                </div>

            @endif

        </div>


        {{-- RIGHT --}}
        <div class="col-lg-4">


            {{-- WORKFLOW --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-diagram-3 me-1"></i>
                        Test Workflow
                    </h6>

                </div>

                <div class="card-body">

                    <div class="d-flex align-items-start gap-3 mb-3">

                        <div class="rounded-circle bg-light p-2">
                            <i class="bi bi-calendar-check"></i>
                        </div>

                        <div>
                            <div class="fw-semibold">
                                Test Scheduled
                            </div>

                            <div class="small text-muted">
                                {{ $test->test_date?->format('d M Y') ?? 'Date not set' }}
                            </div>
                        </div>

                    </div>


                    <div class="d-flex align-items-start gap-3 mb-3">

                        <div class="rounded-circle bg-light p-2">
                            <i class="bi bi-play-circle"></i>
                        </div>

                        <div>

                            <div class="fw-semibold">
                                Execution
                            </div>

                            <div class="small text-muted">
                                {{ $test->status }}
                            </div>

                        </div>

                    </div>


                    <div class="d-flex align-items-start gap-3">

                        <div class="rounded-circle bg-light p-2">
                            <i class="bi bi-check-circle"></i>
                        </div>

                        <div>

                            <div class="fw-semibold">
                                Result
                            </div>

                            <div class="small text-muted">
                                {{ $test->result }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- RETEST STATUS --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-arrow-repeat me-1"></i>
                        Retest Status
                    </h6>

                </div>

                <div class="card-body">

                    @if($test->parentTest)

                        <div class="mb-3">

                            <div class="text-muted small">
                                Parent Test
                            </div>

                            <a
                                href="{{ route('admin.projects.commissioning.tests.show', [$project, $test->parentTest]) }}"
                                class="fw-semibold"
                            >
                                {{ $test->parentTest->test_no }}
                            </a>

                        </div>

                    @endif


                    <div class="mb-3">

                        <div class="text-muted small">
                            Retests Performed
                        </div>

                        <div class="fs-4 fw-bold">
                            {{ $totalRetests }}
                        </div>

                    </div>


                    @if($latestRetest)

                        <div>

                            <div class="text-muted small">
                                Latest Retest
                            </div>

                            <a
                                href="{{ route('admin.projects.commissioning.tests.show', [$project, $latestRetest]) }}"
                                class="fw-semibold"
                            >
                                {{ $latestRetest->test_no }}
                            </a>

                            <div class="small text-muted">
                                {{ $latestRetest->result }}
                            </div>

                        </div>

                    @endif

                </div>

            </div>


            {{-- AUDIT --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-clock-history me-1"></i>
                        Record Information
                    </h6>

                </div>

                <div class="card-body small">

                    <div class="mb-3">

                        <div class="text-muted">
                            Created By
                        </div>

                        <div class="fw-semibold">
                            {{ $test->createdBy?->name ?? 'System' }}
                        </div>

                        <div class="text-muted">
                            {{ $test->created_at?->format('d M Y h:i A') }}
                        </div>

                    </div>


                    <div>

                        <div class="text-muted">
                            Last Updated
                        </div>

                        <div>
                            {{ $test->updated_at?->format('d M Y h:i A') }}
                        </div>

                        <div class="text-muted">
                            {{ $test->updatedBy?->name ?? 'System' }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- ACTIONS --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h6 class="mb-0 fw-semibold">
                        Quick Actions
                    </h6>

                </div>

                <div class="card-body d-grid gap-2">

                    @if(
                        $test->result === 'Fail'
                        || $test->retest_required
                        || $test->status === 'Retest Required'
                    )

                        <a
                            href="{{ route('admin.projects.commissioning.tests.retest.create', [$project, $test]) }}"
                            class="btn btn-warning"
                        >
                            <i class="bi bi-arrow-repeat me-1"></i>
                            Create Retest
                        </a>

                    @endif


                    @if($test->status !== 'Completed')

                        <a
                            href="{{ route('admin.projects.commissioning.tests.edit', [$project, $test]) }}"
                            class="btn btn-outline-primary"
                        >
                            <i class="bi bi-pencil me-1"></i>
                            Edit Test
                        </a>

                    @endif


                    <a
                        href="{{ route('admin.projects.commissioning.test-plans.index', $project) }}"
                        class="btn btn-outline-secondary"
                    >
                        <i class="bi bi-clipboard-check me-1"></i>
                        Test Plans
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection