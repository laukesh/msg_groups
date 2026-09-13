@extends('layouts.app')

@section('title', 'Commissioning Test Plan')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-2">

                <h4 class="mb-0">
                    {{ $plan->test_plan_no }}
                </h4>

                @php

                    $statusClass = match($plan->status) {

                        'Draft'
                            => 'bg-secondary',

                        'Submitted'
                            => 'bg-warning text-dark',

                        'Approved'
                            => 'bg-success',

                        'In Progress'
                            => 'bg-info text-dark',

                        'Completed'
                            => 'bg-primary',

                        'Rejected'
                            => 'bg-danger',

                        'On Hold'
                            => 'bg-dark',

                        default
                            => 'bg-secondary',

                    };

                @endphp

                <span class="badge {{ $statusClass }}">
                    {{ $plan->status }}
                </span>

            </div>


            <div class="text-muted">

                {{ $plan->title }}

            </div>


            @if($plan->scope)

                <div class="small text-muted mt-1">

                    <i class="bi bi-diagram-3 me-1"></i>

                    {{ $plan->scope->scope_code }}

                    @if($plan->scope->scope_name)
                        - {{ $plan->scope->scope_name }}
                    @endif

                </div>

            @endif

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.commissioning.test-plans.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>


    {{-- =========================================================
         FLASH MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
         ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <div class="fw-semibold mb-2">

                <i class="bi bi-exclamation-triangle me-2"></i>

                Action could not be completed.

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
         WORKFLOW ACTIONS
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>
                <i class="bi bi-diagram-3 me-2"></i>
                Workflow
            </strong>

        </div>


        <div class="card-body">

            <div class="d-flex flex-wrap gap-2">


                {{-- =================================================
                     DRAFT / REJECTED
                ================================================== --}}
                @if(
                    in_array(
                        $plan->status,
                        ['Draft', 'Rejected'],
                        true
                    )
                )

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.commissioning.test-plans.submit',
                            [$project, $plan]
                        ) }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="bi bi-send me-1"></i>

                            Submit for Approval

                        </button>

                    </form>

                @endif


                {{-- =================================================
                     SUBMITTED
                ================================================== --}}
                @if($plan->status === 'Submitted')

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.commissioning.test-plans.approve',
                            [$project, $plan]
                        ) }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-success"
                        >

                            <i class="bi bi-check-circle me-1"></i>

                            Approve

                        </button>

                    </form>


                    <button
                        type="button"
                        class="btn btn-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#rejectPlanModal"
                    >

                        <i class="bi bi-x-circle me-1"></i>

                        Reject

                    </button>

                @endif


                {{-- =================================================
                     APPROVED
                ================================================== --}}
                @if($plan->status === 'Approved')

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.commissioning.test-plans.start',
                            [$project, $plan]
                        ) }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="bi bi-play-circle me-1"></i>

                            Start Execution

                        </button>

                    </form>


                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.commissioning.test-plans.hold',
                            [$project, $plan]
                        ) }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-warning"
                        >

                            <i class="bi bi-pause-circle me-1"></i>

                            Put On Hold

                        </button>

                    </form>

                @endif


                {{-- =================================================
                     IN PROGRESS
                ================================================== --}}
                @if($plan->status === 'In Progress')

                    <a
                        href="{{ route(
                            'admin.projects.commissioning.tests.create',
                            $project
                        ) }}?test_plan_id={{ $plan->id }}"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-plus-circle me-1"></i>

                        Record Test

                    </a>


                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.commissioning.test-plans.complete',
                            [$project, $plan]
                        ) }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-success"
                        >

                            <i class="bi bi-check2-circle me-1"></i>

                            Complete Plan

                        </button>

                    </form>


                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.commissioning.test-plans.hold',
                            [$project, $plan]
                        ) }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-warning"
                        >

                            <i class="bi bi-pause-circle me-1"></i>

                            Put On Hold

                        </button>

                    </form>

                @endif


                {{-- =================================================
                     ON HOLD
                ================================================== --}}
                @if($plan->status === 'On Hold')

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.commissioning.test-plans.resume',
                            [$project, $plan]
                        ) }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="bi bi-play-circle me-1"></i>

                            Resume

                        </button>

                    </form>

                @endif


                {{-- =================================================
                     EDIT
                ================================================== --}}
                @if(
                    in_array(
                        $plan->status,
                        [
                            'Draft',
                            'Rejected',
                            'On Hold'
                        ],
                        true
                    )
                )

                    <a
                        href="{{ route(
                            'admin.projects.commissioning.test-plans.edit',
                            [$project, $plan]
                        ) }}"
                        class="btn btn-outline-primary"
                    >

                        <i class="bi bi-pencil me-1"></i>

                        Edit

                    </a>

                @endif


                {{-- =================================================
                     DELETE
                ================================================== --}}
                @if(
                    in_array(
                        $plan->status,
                        [
                            'Draft',
                            'Rejected'
                        ],
                        true
                    )
                )

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.commissioning.test-plans.destroy',
                            [$project, $plan]
                        ) }}"
                        onsubmit="return confirm(
                            'Are you sure you want to delete this Test Plan?'
                        );"
                    >

                        @csrf

                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn btn-outline-danger"
                        >

                            <i class="bi bi-trash me-1"></i>

                            Delete

                        </button>

                    </form>

                @endif

            </div>

        </div>

    </div>


    {{-- =========================================================
         TEST STATISTICS
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- Total Tests --}}
        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Tests
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $totalTests }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Completed --}}
        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Completed
                    </div>

                    <div class="fs-3 fw-bold text-primary">
                        {{ $completedTests }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Passed --}}
        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Passed
                    </div>

                    <div class="fs-3 fw-bold text-success">
                        {{ $passedTests }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Failed --}}
        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Failed
                    </div>

                    <div class="fs-3 fw-bold text-danger">
                        {{ $failedTests }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Retest --}}
        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Retest Required
                    </div>

                    <div class="fs-3 fw-bold text-warning">
                        {{ $retestTests }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Pass Rate --}}
        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Pass Rate
                    </div>

                    <div class="fs-3 fw-bold text-success">
                        {{ $passRate }}%
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         MAIN INFORMATION
    ========================================================== --}}
    <div class="row g-4">


        {{-- =====================================================
             LEFT
        ====================================================== --}}
        <div class="col-lg-8">


            {{-- Test Plan Details --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <strong>
                        Test Plan Details
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        {{-- Scope --}}
                        <div class="col-md-6">

                            <div class="text-muted small">
                                Commissioning Scope
                            </div>

                            @if($plan->scope)

                                <div class="fw-semibold">

                                    {{ $plan->scope->scope_code }}

                                </div>

                                <div class="small text-muted">

                                    {{ $plan->scope->scope_name }}

                                </div>

                            @else

                                <span class="text-muted">
                                    -
                                </span>

                            @endif

                        </div>


                        {{-- Work Order --}}
                        <div class="col-md-6">

                            <div class="text-muted small">
                                Work Order
                            </div>

                            @if($plan->scope?->workOrder)

                                <div class="fw-semibold">

                                    {{ $plan->scope->workOrder->work_order_no
                                        ?? $plan->scope->workOrder->id }}

                                </div>

                                @if(
                                    isset(
                                        $plan->scope->workOrder->title
                                    )
                                )

                                    <div class="small text-muted">

                                        {{ $plan->scope->workOrder->title }}

                                    </div>

                                @endif

                            @else

                                <span class="text-muted">
                                    -
                                </span>

                            @endif

                        </div>


                        {{-- Discipline --}}
                        <div class="col-md-4">

                            <div class="text-muted small">
                                Discipline
                            </div>

                            <div class="fw-semibold">
                                {{ $plan->discipline ?: '-' }}
                            </div>

                        </div>


                        {{-- Test Type --}}
                        <div class="col-md-4">

                            <div class="text-muted small">
                                Test Type
                            </div>

                            <div class="fw-semibold">
                                {{ $plan->test_type ?: '-' }}
                            </div>

                        </div>


                        {{-- Responsible --}}
                        <div class="col-md-4">

                            <div class="text-muted small">
                                Responsible Person
                            </div>

                            <div class="fw-semibold">

                                {{ $plan->responsibleUser?->name ?? '-' }}

                            </div>

                        </div>


                        {{-- Planned Start --}}
                        <div class="col-md-6">

                            <div class="text-muted small">
                                Planned Start
                            </div>

                            <div class="fw-semibold">

                                {{ $plan->planned_start_date
                                    ? $plan->planned_start_date->format('d M Y')
                                    : '-' }}

                            </div>

                        </div>


                        {{-- Planned Completion --}}
                        <div class="col-md-6">

                            <div class="text-muted small">
                                Planned Completion
                            </div>

                            <div class="fw-semibold">

                                {{ $plan->planned_completion_date
                                    ? $plan->planned_completion_date->format('d M Y')
                                    : '-' }}

                            </div>

                        </div>


                        {{-- Purpose --}}
                        <div class="col-12">

                            <div class="text-muted small mb-1">
                                Purpose
                            </div>

                            <div class="border rounded p-3 bg-light">

                                {!! nl2br(
                                    e($plan->purpose ?: 'Not specified.')
                                ) !!}

                            </div>

                        </div>


                        {{-- Description --}}
                        <div class="col-12">

                            <div class="text-muted small mb-1">
                                Description
                            </div>

                            <div class="border rounded p-3 bg-light">

                                {!! nl2br(
                                    e($plan->description ?: 'Not specified.')
                                ) !!}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Test Procedure --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <strong>
                        Testing Requirements
                    </strong>

                </div>


                <div class="card-body">

                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            Prerequisites
                        </div>

                        <div class="border rounded p-3">

                            {!! nl2br(
                                e(
                                    $plan->prerequisites
                                    ?: 'No prerequisites specified.'
                                )
                            ) !!}

                        </div>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            Test Procedure
                        </div>

                        <div class="border rounded p-3">

                            {!! nl2br(
                                e(
                                    $plan->test_procedure
                                    ?: 'No test procedure specified.'
                                )
                            ) !!}

                        </div>

                    </div>


                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Required Instruments
                            </div>

                            <div class="border rounded p-3">

                                {!! nl2br(
                                    e(
                                        $plan->required_instruments
                                        ?: 'Not specified.'
                                    )
                                ) !!}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Required Personnel
                            </div>

                            <div class="border rounded p-3">

                                {!! nl2br(
                                    e(
                                        $plan->required_personnel
                                        ?: 'Not specified.'
                                    )
                                ) !!}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Test Executions --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <div class="d-flex justify-content-between align-items-center">

                        <strong>
                            Test Executions
                        </strong>

                        @if(
                            in_array(
                                $plan->status,
                                [
                                    'Approved',
                                    'In Progress'
                                ],
                                true
                            )
                        )

                            <a
                                href="{{ route(
                                    'admin.projects.commissioning.tests.create',
                                    $project
                                ) }}?test_plan_id={{ $plan->id }}"
                                class="btn btn-sm btn-primary"
                            >

                                <i class="bi bi-plus-lg me-1"></i>
                                Record Test

                            </a>

                        @endif

                    </div>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>
                                        Test No.
                                    </th>

                                    <th>
                                        Date
                                    </th>

                                    <th>
                                        Test Type
                                    </th>

                                    <th>
                                        Result
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th width="80">
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                            @forelse($plan->tests as $test)

                                <tr>

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $test->test_no }}

                                        </div>

                                        @if($test->location)

                                            <small class="text-muted">

                                                {{ $test->location }}

                                            </small>

                                        @endif

                                    </td>


                                    <td>

                                        {{ $test->test_date
                                            ? $test->test_date->format('d M Y')
                                            : '-' }}

                                    </td>


                                    <td>

                                        {{ $test->test_type ?: '-' }}

                                    </td>


                                    <td>

                                        @php

                                            $resultClass = match(
                                                $test->result
                                            ) {

                                                'Pass'
                                                    => 'bg-success',

                                                'Fail'
                                                    => 'bg-danger',

                                                'Conditional Pass'
                                                    => 'bg-warning text-dark',

                                                default
                                                    => 'bg-secondary',

                                            };

                                        @endphp

                                        <span
                                            class="badge {{ $resultClass }}"
                                        >
                                            {{ $test->result }}
                                        </span>

                                    </td>


                                    <td>

                                        @php

                                            $testStatusClass = match(
                                                $test->status
                                            ) {

                                                'Completed'
                                                    => 'bg-success',

                                                'In Progress'
                                                    => 'bg-info text-dark',

                                                'Scheduled'
                                                    => 'bg-primary',

                                                'Retest Required'
                                                    => 'bg-warning text-dark',

                                                'Cancelled'
                                                    => 'bg-danger',

                                                default
                                                    => 'bg-secondary',

                                            };

                                        @endphp

                                        <span
                                            class="badge {{ $testStatusClass }}"
                                        >
                                            {{ $test->status }}
                                        </span>

                                    </td>


                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.projects.commissioning.tests.show',
                                                [$project, $test]
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            title="View Test"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="6"
                                        class="text-center py-5 text-muted"
                                    >

                                        <i
                                            class="bi bi-clipboard-x fs-2 d-block mb-2"
                                        ></i>

                                        No test executions recorded yet.

                                    </td>

                                </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             RIGHT SIDEBAR
        ====================================================== --}}
        <div class="col-lg-4">


            {{-- Witness Requirements --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <strong>
                        Witness Requirements
                    </strong>

                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">

                        <span>
                            Witness Required
                        </span>

                        @if($plan->witness_required)

                            <span class="badge bg-success">
                                Yes
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                No
                            </span>

                        @endif

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span>
                            Client Witness
                        </span>

                        @if($plan->client_witness_required)

                            <span class="badge bg-success">
                                Required
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                Not Required
                            </span>

                        @endif

                    </div>


                    <div class="d-flex justify-content-between">

                        <span>
                            Consultant Witness
                        </span>

                        @if($plan->consultant_witness_required)

                            <span class="badge bg-success">
                                Required
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                Not Required
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Workflow Information --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <strong>
                        Workflow Information
                    </strong>

                </div>


                <div class="card-body">


                    {{-- Created --}}
                    <div class="mb-3">

                        <div class="text-muted small">
                            Created By
                        </div>

                        <div class="fw-semibold">
                            {{ $plan->createdBy?->name ?? '-' }}
                        </div>

                        @if($plan->created_at)

                            <small class="text-muted">

                                {{ $plan->created_at->format(
                                    'd M Y h:i A'
                                ) }}

                            </small>

                        @endif

                    </div>


                    {{-- Submitted --}}
                    <div class="mb-3">

                        <div class="text-muted small">
                            Submitted By
                        </div>

                        <div class="fw-semibold">
                            {{ $plan->submittedBy?->name ?? '-' }}
                        </div>

                        @if($plan->submitted_at)

                            <small class="text-muted">

                                {{ $plan->submitted_at->format(
                                    'd M Y h:i A'
                                ) }}

                            </small>

                        @endif

                    </div>


                    {{-- Approved --}}
                    <div class="mb-3">

                        <div class="text-muted small">
                            Approved By
                        </div>

                        <div class="fw-semibold">
                            {{ $plan->approvedBy?->name ?? '-' }}
                        </div>

                        @if($plan->approved_at)

                            <small class="text-muted">

                                {{ $plan->approved_at->format(
                                    'd M Y h:i A'
                                ) }}

                            </small>

                        @endif

                    </div>


                    {{-- Rejected --}}
                    @if($plan->rejectedBy || $plan->rejection_reason)

                        <div class="mb-0">

                            <div class="text-muted small">
                                Rejected By
                            </div>

                            <div class="fw-semibold text-danger">

                                {{ $plan->rejectedBy?->name ?? '-' }}

                            </div>

                            @if($plan->rejected_at)

                                <small class="text-muted">

                                    {{ $plan->rejected_at->format(
                                        'd M Y h:i A'
                                    ) }}

                                </small>

                            @endif


                            @if($plan->rejection_reason)

                                <div class="alert alert-danger mt-3 mb-0">

                                    <div class="fw-semibold mb-1">
                                        Rejection Reason
                                    </div>

                                    <div class="small">

                                        {!! nl2br(
                                            e(
                                                $plan->rejection_reason
                                            )
                                        ) !!}

                                    </div>

                                </div>

                            @endif

                        </div>

                    @endif

                </div>

            </div>


            {{-- Pass Rate --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <strong>
                        Test Progress
                    </strong>

                </div>


                <div class="card-body">

                    <div class="d-flex justify-content-between mb-2">

                        <span>
                            Completion
                        </span>

                        <strong>

                            @if($totalTests > 0)

                                {{ round(
                                    ($completedTests / $totalTests) * 100,
                                    1
                                ) }}%

                            @else

                                0%

                            @endif

                        </strong>

                    </div>


                    <div class="progress mb-4" style="height: 8px;">

                        <div
                            class="progress-bar"
                            role="progressbar"
                            style="width:
                                {{ $totalTests > 0
                                    ? ($completedTests / $totalTests) * 100
                                    : 0
                                }}%;"
                        ></div>

                    </div>


                    <div class="d-flex justify-content-between mb-2">

                        <span>
                            Pass Rate
                        </span>

                        <strong class="text-success">
                            {{ $passRate }}%
                        </strong>

                    </div>


                    <div class="progress" style="height: 8px;">

                        <div
                            class="progress-bar bg-success"
                            role="progressbar"
                            style="width: {{ $passRate }}%;"
                        ></div>

                    </div>

                </div>

            </div>


            {{-- Workflow Guidance --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <strong>
                        Workflow Guide
                    </strong>

                </div>


                <div class="card-body">

                    <div class="small">

                        <div class="d-flex mb-3">

                            <span class="badge bg-secondary me-2">
                                1
                            </span>

                            <div>
                                Create Test Plan as Draft.
                            </div>

                        </div>


                        <div class="d-flex mb-3">

                            <span class="badge bg-warning text-dark me-2">
                                2
                            </span>

                            <div>
                                Submit the completed plan for approval.
                            </div>

                        </div>


                        <div class="d-flex mb-3">

                            <span class="badge bg-success me-2">
                                3
                            </span>

                            <div>
                                Approved plans can proceed to execution.
                            </div>

                        </div>


                        <div class="d-flex mb-3">

                            <span class="badge bg-info text-dark me-2">
                                4
                            </span>

                            <div>
                                Record and complete commissioning tests.
                            </div>

                        </div>


                        <div class="d-flex">

                            <span class="badge bg-primary me-2">
                                5
                            </span>

                            <div>
                                Complete the Test Plan after all required
                                tests are successfully resolved.
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =============================================================
     REJECT MODAL
============================================================== --}}
@if($plan->status === 'Submitted')

    <div
        class="modal fade"
        id="rejectPlanModal"
        tabindex="-1"
        aria-labelledby="rejectPlanModalLabel"
        aria-hidden="true"
    >

        <div class="modal-dialog">

            <div class="modal-content">

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.commissioning.test-plans.reject',
                        [$project, $plan]
                    ) }}"
                >

                    @csrf


                    <div class="modal-header">

                        <h5
                            class="modal-title"
                            id="rejectPlanModalLabel"
                        >
                            Reject Test Plan
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>

                    </div>


                    <div class="modal-body">

                        <div class="alert alert-warning">

                            <i class="bi bi-exclamation-triangle me-2"></i>

                            Please provide a clear reason for rejection.
                            The Test Plan can then be corrected and
                            resubmitted.

                        </div>


                        <label class="form-label">

                            Rejection Reason

                            <span class="text-danger">*</span>

                        </label>


                        <textarea
                            name="rejection_reason"
                            class="form-control"
                            rows="5"
                            required
                            placeholder="Enter rejection reason..."
                        ></textarea>

                    </div>


                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal"
                        >
                            Cancel
                        </button>


                        <button
                            type="submit"
                            class="btn btn-danger"
                        >

                            <i class="bi bi-x-circle me-1"></i>

                            Reject Test Plan

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endif

@endsection