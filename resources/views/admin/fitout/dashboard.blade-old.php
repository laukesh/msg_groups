@extends('layouts.app')

@section('title', 'Fit-Out Dashboard')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Fit-Out Dashboard
            </h4>

            <p class="text-muted mb-0">
                Monitor fit-out requests, stages, approvals,
                inspections, snags and handovers.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.fitout.requests.create') }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-circle me-1"></i>
                New Fit-Out
            </a>

            <a
                href="{{ route('admin.fitout.requests.index') }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-list me-1"></i>
                All Requests
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FLASH MESSAGES --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- MAIN KPI CARDS --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">


        {{-- Total --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Total Fit-Outs
                            </div>

                            <h3 class="mb-0 mt-2">
                                {{ $totalFitouts }}
                            </h3>

                        </div>

                        <div class="fs-2 text-primary">
                            <i class="bi bi-building"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- In Progress --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                In Progress
                            </div>

                            <h3 class="mb-0 mt-2">
                                {{ $inProgressFitouts }}
                            </h3>

                        </div>

                        <div class="fs-2 text-warning">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Completed --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Completed
                            </div>

                            <h3 class="mb-0 mt-2">
                                {{ $completedFitouts }}
                            </h3>

                        </div>

                        <div class="fs-2 text-success">
                            <i class="bi bi-check-circle"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Delayed --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Delayed
                            </div>

                            <h3 class="mb-0 mt-2 text-danger">
                                {{ $delayedFitouts }}
                            </h3>

                        </div>

                        <div class="fs-2 text-danger">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- REQUEST STATUS --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Fit-Out Request Status
                </h5>

                <a
                    href="{{ route('admin.fitout.requests.index') }}"
                    class="btn btn-sm btn-outline-primary"
                >
                    View All
                </a>

            </div>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- Draft --}}
                <div class="col-xl-2 col-md-4 col-6">

                    <div class="border rounded p-3 text-center">

                        <div class="text-muted small">
                            Draft
                        </div>

                        <h4 class="mb-0 mt-1">
                            {{ $draftFitouts }}
                        </h4>

                    </div>

                </div>


                {{-- Submitted --}}
                <div class="col-xl-2 col-md-4 col-6">

                    <div class="border rounded p-3 text-center">

                        <div class="text-muted small">
                            Submitted
                        </div>

                        <h4 class="mb-0 mt-1">
                            {{ \App\Models\FitoutRequest::where(
                                'fitout_status',
                                'Submitted'
                            )->count() }}
                        </h4>

                    </div>

                </div>


                {{-- Under Review --}}
                <div class="col-xl-2 col-md-4 col-6">

                    <div class="border rounded p-3 text-center">

                        <div class="text-muted small">
                            Under Review
                        </div>

                        <h4 class="mb-0 mt-1">
                            {{ $underReviewFitouts }}
                        </h4>

                    </div>

                </div>


                {{-- Approved --}}
                <div class="col-xl-2 col-md-4 col-6">

                    <div class="border rounded p-3 text-center">

                        <div class="text-muted small">
                            Approved
                        </div>

                        <h4 class="mb-0 mt-1">
                            {{ $approvedFitouts }}
                        </h4>

                    </div>

                </div>


                {{-- Rejected --}}
                <div class="col-xl-2 col-md-4 col-6">

                    <div class="border rounded p-3 text-center">

                        <div class="text-muted small">
                            Rejected
                        </div>

                        <h4 class="mb-0 mt-1 text-danger">
                            {{ $rejectedFitouts }}
                        </h4>

                    </div>

                </div>


                {{-- Closed --}}
                <div class="col-xl-2 col-md-4 col-6">

                    <div class="border rounded p-3 text-center">

                        <div class="text-muted small">
                            Closed
                        </div>

                        <h4 class="mb-0 mt-1">
                            {{ $closedFitouts }}
                        </h4>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SECONDARY KPI ROW --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">


        {{-- Stages --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h6 class="mb-3">
                        <i class="bi bi-diagram-3 me-1"></i>
                        Stages
                    </h6>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Pending</span>
                        <strong>{{ $pendingStages }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>In Progress</span>
                        <strong>{{ $inProgressStages }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Completed</span>
                        <strong>{{ $completedStages }}</strong>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span>On Hold</span>
                        <strong class="text-warning">
                            {{ $onHoldStages }}
                        </strong>
                    </div>

                </div>

            </div>

        </div>


        {{-- Documents --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h6 class="mb-3">
                        <i class="bi bi-file-earmark me-1"></i>
                        Documents
                    </h6>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Pending</span>
                        <strong>{{ $pendingDocuments }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Under Review</span>
                        <strong>{{ $underReviewDocuments }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Approved</span>
                        <strong class="text-success">
                            {{ $approvedDocuments }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span>Rejected</span>
                        <strong class="text-danger">
                            {{ $rejectedDocuments }}
                        </strong>
                    </div>

                </div>

            </div>

        </div>


        {{-- Inspections --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h6 class="mb-3">
                        <i class="bi bi-clipboard-check me-1"></i>
                        Inspections
                    </h6>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Scheduled</span>
                        <strong>{{ $scheduledInspections }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>In Progress</span>
                        <strong>{{ $inProgressInspections }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Completed</span>
                        <strong class="text-success">
                            {{ $completedInspections }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span>Failed</span>
                        <strong class="text-danger">
                            {{ $failedInspections }}
                        </strong>
                    </div>

                </div>

            </div>

        </div>


        {{-- Handovers --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h6 class="mb-3">
                        <i class="bi bi-door-open me-1"></i>
                        Handovers
                    </h6>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Pending</span>
                        <strong>{{ $pendingHandovers }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Scheduled</span>
                        <strong>{{ $scheduledHandovers }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>In Progress</span>
                        <strong>{{ $inProgressHandovers }}</strong>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span>Completed</span>
                        <strong class="text-success">
                            {{ $completedHandovers }}
                        </strong>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- MAIN CONTENT --}}
    {{-- ========================================================= --}}

    <div class="row g-4">


        {{-- ===================================================== --}}
        {{-- FIT-OUT TRACKING --}}
        {{-- ===================================================== --}}

        <div class="col-xl-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">
                            Fit-Out Tracking
                        </h5>

                        <span class="text-muted small">
                            Latest Requests
                        </span>

                    </div>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>
                                        Request
                                    </th>

                                    <th>
                                        Unit
                                    </th>

                                    <th>
                                        Tenant
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Progress
                                    </th>

                                    <th>
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($fitouts as $fitout)

                                    @php

                                        $statusClass = match(
                                            $fitout->fitout_status
                                        ) {

                                            'Draft' =>
                                                'bg-secondary',

                                            'Submitted' =>
                                                'bg-info text-dark',

                                            'Under Review' =>
                                                'bg-warning text-dark',

                                            'Approved' =>
                                                'bg-primary',

                                            'Rejected' =>
                                                'bg-danger',

                                            'In Progress' =>
                                                'bg-warning text-dark',

                                            'Completed' =>
                                                'bg-success',

                                            'Closed' =>
                                                'bg-dark',

                                            default =>
                                                'bg-secondary',

                                        };


                                        $completedStageCount =
                                            $fitout->stages
                                                ? $fitout->stages
                                                    ->where(
                                                        'stage_status',
                                                        'Completed'
                                                    )
                                                    ->count()
                                                : 0;

                                        $totalStageCount =
                                            $fitout->stages
                                                ? $fitout->stages->count()
                                                : 0;

                                        $progress =
                                            $totalStageCount > 0
                                                ? round(
                                                    (
                                                        $completedStageCount /
                                                        $totalStageCount
                                                    ) * 100
                                                )
                                                : 0;

                                    @endphp


                                    <tr>

                                        <td>

                                            <strong>
                                                {{ $fitout->request_no }}
                                            </strong>

                                            <div class="small text-muted">

                                                {{ $fitout->fitout_type }}

                                            </div>

                                        </td>


                                        <td>

                                            {{
                                                $fitout->unit->unit_no
                                                ?? '-'
                                            }}

                                        </td>


                                        <td>

                                            {{
                                                $fitout->tenant->company_name
                                                ??
                                                $fitout->tenant->tenant_name
                                                ??
                                                $fitout->tenant->name
                                                ??
                                                '-'
                                            }}

                                        </td>


                                        <td>

                                            <span
                                                class="badge {{ $statusClass }}"
                                            >
                                                {{ $fitout->fitout_status }}
                                            </span>

                                        </td>


                                        <td style="min-width:130px">

                                            <div class="small mb-1">

                                                {{ $progress }}%

                                            </div>

                                            <div
                                                class="progress"
                                                style="height:6px"
                                            >

                                                <div
                                                    class="progress-bar"
                                                    role="progressbar"
                                                    style="width: {{ $progress }}%"
                                                ></div>

                                            </div>

                                        </td>


                                        <td>

                                            <a
                                                href="{{ route(
                                                    'admin.fitout.requests.show',
                                                    $fitout->id
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
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

                                            No fit-out requests found.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>


                @if($fitouts->hasPages())

                    <div class="card-footer bg-white">

                        {{ $fitouts->links() }}

                    </div>

                @endif

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- PENDING APPROVALS --}}
        {{-- ===================================================== --}}

        <div class="col-xl-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <div class="d-flex justify-content-between">

                        <h5 class="mb-0">
                            Pending Approvals
                        </h5>

                        <span class="badge bg-warning text-dark">
                            {{ $pendingApprovals }}
                        </span>

                    </div>

                </div>


                <div class="card-body p-0">

                    @forelse($approvalQueue as $approval)

                        <div class="border-bottom p-3">

                            <div class="d-flex justify-content-between">

                                <strong>

                                    {{
                                        $approval
                                            ->fitoutRequest
                                            ->request_no
                                        ?? '-'
                                    }}

                                </strong>

                                <span class="badge bg-warning text-dark">

                                    {{ $approval->approval_type }}

                                </span>

                            </div>


                            <div class="small text-muted mt-1">

                                Approval Level:
                                {{ $approval->approval_level }}

                            </div>


                            <a
                                href="{{
                                    route(
                                        'admin.fitout.requests.show',
                                        $approval->fitout_request_id
                                    )
                                }}"
                                class="btn btn-sm btn-outline-primary mt-2"
                            >

                                Review

                            </a>

                        </div>

                    @empty

                        <div class="p-4 text-center text-muted">

                            <i class="bi bi-check-circle fs-3"></i>

                            <div class="mt-2">
                                No pending approvals.
                            </div>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SECOND ROW --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mt-1">


        {{-- ===================================================== --}}
        {{-- UPCOMING INSPECTIONS --}}
        {{-- ===================================================== --}}

        <div class="col-xl-6">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Upcoming Inspections
                    </h5>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>
                                        Inspection
                                    </th>

                                    <th>
                                        Type
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

                                @forelse(
                                    $upcomingInspections
                                    as $inspection
                                )

                                    <tr>

                                        <td>

                                            <strong>
                                                {{
                                                    $inspection
                                                        ->inspection_number
                                                }}
                                            </strong>

                                            <div class="small text-muted">

                                                {{
                                                    $inspection
                                                        ->fitoutRequest
                                                        ->request_no
                                                    ?? '-'
                                                }}

                                            </div>

                                        </td>


                                        <td>

                                            {{ $inspection->inspection_type }}

                                        </td>


                                        <td>

                                            {{
                                                $inspection
                                                    ->scheduled_date
                                                    ?->format('d M Y')
                                            }}

                                        </td>


                                        <td>

                                            <span class="badge bg-info text-dark">

                                                {{ $inspection->status }}

                                            </span>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="4"
                                            class="text-center py-4 text-muted"
                                        >

                                            No upcoming inspections.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- OPEN SNAGS --}}
        {{-- ===================================================== --}}

        <div class="col-xl-6">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <div class="d-flex justify-content-between">

                        <h5 class="mb-0">
                            Open Snags
                        </h5>

                        <div>

                            <span class="badge bg-danger me-1">
                                Critical {{ $criticalSnags }}
                            </span>

                            <span class="badge bg-warning text-dark">
                                High {{ $highSnags }}
                            </span>

                        </div>

                    </div>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>
                                        Snag
                                    </th>

                                    <th>
                                        Priority
                                    </th>

                                    <th>
                                        Due Date
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($recentSnags as $snag)

                                    @php

                                        $priorityClass = match(
                                            $snag->priority
                                        ) {

                                            'Critical' =>
                                                'bg-danger',

                                            'High' =>
                                                'bg-warning text-dark',

                                            'Medium' =>
                                                'bg-info text-dark',

                                            'Low' =>
                                                'bg-secondary',

                                            default =>
                                                'bg-secondary',

                                        };

                                    @endphp


                                    <tr>

                                        <td>

                                            <strong>
                                                {{ $snag->snag_number }}
                                            </strong>

                                            <div class="small text-muted">

                                                {{ $snag->title }}

                                            </div>

                                        </td>


                                        <td>

                                            <span
                                                class="badge {{ $priorityClass }}"
                                            >
                                                {{ $snag->priority }}
                                            </span>

                                        </td>


                                        <td>

                                            {{
                                                $snag->due_date
                                                    ?->format('d M Y')
                                                ?? '-'
                                            }}

                                        </td>


                                        <td>

                                            <span class="badge bg-secondary">

                                                {{ $snag->status }}

                                            </span>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="4"
                                            class="text-center py-4 text-muted"
                                        >

                                            No open snags.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- HANDOVERS --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm mt-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between">

                <h5 class="mb-0">
                    Recent Handovers
                </h5>

                <div class="small text-muted">

                    Completed:
                    <strong>
                        {{ $completedHandovers }}
                    </strong>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Handover
                            </th>

                            <th>
                                Request
                            </th>

                            <th>
                                Unit
                            </th>

                            <th>
                                Tenant
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($recentHandovers as $handover)

                            @php

                                $handoverClass = match(
                                    $handover->status
                                ) {

                                    'Pending' =>
                                        'bg-warning text-dark',

                                    'Scheduled' =>
                                        'bg-info text-dark',

                                    'In Progress' =>
                                        'bg-primary',

                                    'Accepted' =>
                                        'bg-success',

                                    'Completed' =>
                                        'bg-success',

                                    'Rejected' =>
                                        'bg-danger',

                                    'Cancelled' =>
                                        'bg-secondary',

                                    default =>
                                        'bg-secondary',

                                };

                            @endphp


                            <tr>

                                <td>

                                    <strong>
                                        {{ $handover->handover_number }}
                                    </strong>

                                </td>


                                <td>

                                    {{
                                        $handover
                                            ->fitoutRequest
                                            ->request_no
                                        ?? '-'
                                    }}

                                </td>


                                <td>

                                    {{
                                        $handover
                                            ->unit
                                            ->unit_no
                                        ?? '-'
                                    }}

                                </td>


                                <td>

                                    {{
                                        $handover
                                            ->tenant
                                            ->company_name
                                        ??
                                        $handover
                                            ->tenant
                                            ->tenant_name
                                        ??
                                        $handover
                                            ->tenant
                                            ->name
                                        ??
                                        '-'
                                    }}

                                </td>


                                <td>

                                    {{
                                        $handover
                                            ->handover_date
                                            ?->format('d M Y')
                                        ?? '-'
                                    }}

                                </td>


                                <td>

                                    <span
                                        class="badge {{ $handoverClass }}"
                                    >

                                        {{ $handover->status }}

                                    </span>

                                </td>


                                <td>

                                    <a
                                        href="{{
                                            route(
                                                'admin.fitout.handovers.show',
                                                $handover->id
                                            )
                                        }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >

                                        <i class="fas fa-eye"></i>

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5 text-muted"
                                >

                                    No handovers found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- QUICK ACTIONS --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm mt-4 mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Quick Actions
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-3">


                <div class="col-md-3">

                    <a
                        href="{{ route(
                            'admin.fitout.requests.create'
                        ) }}"
                        class="btn btn-outline-primary w-100 py-3"
                    >

                        <i class="bi bi-plus-circle fs-5 d-block mb-1"></i>

                        New Fit-Out Request

                    </a>

                </div>


                <div class="col-md-3">

                    <a
                        href="{{ route(
                            'admin.fitout.inspections.create'
                        ) }}"
                        class="btn btn-outline-primary w-100 py-3"
                    >

                        <i class="bi bi-clipboard-plus fs-5 d-block mb-1"></i>

                        Schedule Inspection

                    </a>

                </div>


                <div class="col-md-3">

                    <a
                        href="{{ route(
                            'admin.fitout.snags.create'
                        ) }}"
                        class="btn btn-outline-primary w-100 py-3"
                    >

                        <i class="bi bi-exclamation-square fs-5 d-block mb-1"></i>

                        Report Snag

                    </a>

                </div>


                <div class="col-md-3">

                    <a
                        href="{{ route(
                            'admin.fitout.handovers.create'
                        ) }}"
                        class="btn btn-outline-primary w-100 py-3"
                    >

                        <i class="bi bi-door-open fs-5 d-block mb-1"></i>

                        Create Handover

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection