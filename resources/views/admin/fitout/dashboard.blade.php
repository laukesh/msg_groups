@extends('layouts.app')

@section('title', 'Fit-Out Dashboard')

@section('content')

<div class="container-fluid">

```
{{-- =========================================================
    HEADER
    ========================================================= --}}

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h4 class="mb-1">
            Fit-Out Management
        </h4>

        <div class="text-muted small">
            Live overview of fit-out progress, inspections, snags and handovers.
        </div>
    </div>

    <div class="d-flex gap-2">

        <a href="{{ route('admin.fitout.requests.create') }}"
           class="btn btn-primary btn-sm">

            <i class="bi bi-plus-lg me-1"></i>
            New Fit-Out

        </a>

        <a href="{{ route('admin.fitout.requests.index') }}"
           class="btn btn-outline-secondary btn-sm">

            All Requests

        </a>

    </div>

</div>


{{-- =========================================================
    FILTERS
    ========================================================= --}}

<div class="card mb-4">

    <div class="card-header bg-white">

        <strong>
            <i class="bi bi-funnel me-1"></i>
            Filters
        </strong>

    </div>

    <div class="card-body">

        <form method="GET"
              action="{{ route('admin.fitout.dashboard') }}">

            <div class="row g-3">

                {{-- Floor --}}
                <div class="col-xl-2 col-md-4">

                    <label class="form-label small fw-semibold">
                        Floor
                    </label>

                    <select name="floor_id"
                            class="form-select form-select-sm">

                        <option value="">
                            All Floors
                        </option>

                        @foreach($floors as $floor)

                            <option value="{{ $floor->id }}"
                                @selected($filters['floor_id'] == $floor->id)>

                                {{ $floor->floor_name }}

                                @if($floor->floor_code)
                                    ({{ $floor->floor_code }})
                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Unit --}}
                <div class="col-xl-2 col-md-4">

                    <label class="form-label small fw-semibold">
                        Unit
                    </label>

                    <select name="unit_id"
                            class="form-select form-select-sm">

                        <option value="">
                            All Units
                        </option>

                        @foreach($units as $unit)

                            <option value="{{ $unit->id }}"
                                @selected($filters['unit_id'] == $unit->id)>

                                {{ $unit->unit_no }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Contractor --}}
                <div class="col-xl-2 col-md-4">

                    <label class="form-label small fw-semibold">
                        Contractor
                    </label>

                    <select name="contractor_id"
                            class="form-select form-select-sm">

                        <option value="">
                            All Contractors
                        </option>

                        @foreach($contractors as $contractor)

                            <option value="{{ $contractor->id }}"
                                @selected($filters['contractor_id'] == $contractor->id)>

                                {{ $contractor->contractor_name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Status --}}
                <div class="col-xl-2 col-md-4">

                    <label class="form-label small fw-semibold">
                        Status
                    </label>

                    <select name="status"
                            class="form-select form-select-sm">

                        <option value="">
                            All Statuses
                        </option>

                        @foreach([
                            'Draft',
                            'Submitted',
                            'Under Review',
                            'Approved',
                            'Rejected',
                            'In Progress',
                            'Completed',
                            'Closed',
                        ] as $status)

                            <option value="{{ $status }}"
                                @selected($filters['status'] === $status)>

                                {{ $status }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- From --}}
                <div class="col-xl-2 col-md-4">

                    <label class="form-label small fw-semibold">
                        From
                    </label>

                    <input type="date"
                           name="date_from"
                           value="{{ $filters['date_from'] }}"
                           class="form-control form-control-sm">

                </div>


                {{-- To --}}
                <div class="col-xl-2 col-md-4">

                    <label class="form-label small fw-semibold">
                        To
                    </label>

                    <input type="date"
                           name="date_to"
                           value="{{ $filters['date_to'] }}"
                           class="form-control form-control-sm">

                </div>

            </div>


            <div class="d-flex justify-content-end gap-2 mt-3">

                <a href="{{ route('admin.fitout.dashboard') }}"
                   class="btn btn-sm btn-outline-secondary">

                    Reset

                </a>

                <button type="submit"
                        class="btn btn-sm btn-primary">

                    <i class="bi bi-funnel me-1"></i>
                    Apply Filters

                </button>

            </div>

        </form>


        {{-- Active Filters --}}
        @if(
            request()->filled('pipeline')
            || request()->filled('status')
            || request()->filled('floor_id')
            || request()->filled('unit_id')
            || request()->filled('contractor_id')
            || request()->filled('date_from')
            || request()->filled('date_to')
        )

            <div class="alert alert-light border mt-3 mb-0">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <strong>
                            <i class="bi bi-funnel me-1"></i>
                            Active Filters
                        </strong>

                        @if(request('pipeline'))

                            <span class="badge bg-primary ms-2">
                                {{ ucfirst(request('pipeline')) }}
                            </span>

                        @endif

                        @if(request('status'))

                            <span class="badge bg-secondary ms-1">
                                {{ request('status') }}
                            </span>

                        @endif

                        @if(request('floor_id'))

                            <span class="badge bg-secondary ms-1">
                                Floor selected
                            </span>

                        @endif

                        @if(request('unit_id'))

                            <span class="badge bg-secondary ms-1">
                                Unit selected
                            </span>

                        @endif

                        @if(request('contractor_id'))

                            <span class="badge bg-secondary ms-1">
                                Contractor selected
                            </span>

                        @endif

                    </div>


                    <a href="{{ route('admin.fitout.dashboard') }}"
                       class="btn btn-sm btn-link text-danger">

                        Clear all

                    </a>

                </div>

            </div>

        @endif

    </div>

</div>


{{-- =========================================================
    FIT-OUT AT A GLANCE
    ========================================================= --}}

<div class="mb-4">

    <div class="d-flex align-items-center mb-3">

        <h5 class="mb-0">
            Fit-Out at a Glance
        </h5>

    </div>


    <div class="row g-3">


        {{-- Total --}}
        <div class="col-xl-2 col-md-4 col-6">

            <a href="{{ route(
                'admin.fitout.dashboard',
                array_merge(
                    request()->query(),
                    [
                        'pipeline' => null,
                        'status' => null,
                    ]
                )
            ) }}"
               class="text-decoration-none">

                <div class="card h-100">

                    <div class="card-body">

                        <div class="text-muted small mb-2">
                            Total Fit-Outs
                        </div>

                        <h3 class="mb-1 text-dark">
                            {{ $totalFitouts }}
                        </h3>

                        <div class="text-muted small">
                            Registered requests
                        </div>

                    </div>

                </div>

            </a>

        </div>


        {{-- Approved --}}
        <div class="col-xl-2 col-md-4 col-6">

            <a href="{{ route(
                'admin.fitout.dashboard',
                array_merge(
                    request()->query(),
                    [
                        'pipeline' => null,
                        'status' => 'Approved',
                    ]
                )
            ) }}"
               class="text-decoration-none">

                <div class="card h-100">

                    <div class="card-body">

                        <div class="text-muted small mb-2">
                            Approved
                        </div>

                        <h3 class="mb-1 text-success">
                            {{ $approvedFitouts }}
                        </h3>

                        <div class="text-muted small">
                            Ready for fit-out
                        </div>

                    </div>

                </div>

            </a>

        </div>


        {{-- In Fit-Out --}}
        <div class="col-xl-2 col-md-4 col-6">

            <a href="{{ route(
                'admin.fitout.dashboard',
                array_merge(
                    request()->query(),
                    [
                        'pipeline' => null,
                        'status' => 'In Progress',
                    ]
                )
            ) }}"
               class="text-decoration-none">

                <div class="card h-100">

                    <div class="card-body">

                        <div class="text-muted small mb-2">
                            In Fit-Out
                        </div>

                        <h3 class="mb-1 text-primary">
                            {{ $inFitout }}
                        </h3>

                        <div class="text-muted small">
                            Works underway
                        </div>

                    </div>

                </div>

            </a>

        </div>


        {{-- Inspections --}}
        <div class="col-xl-2 col-md-4 col-6">

            <a href="{{ route('admin.fitout.inspections.index') }}"
               class="text-decoration-none">

                <div class="card h-100">

                    <div class="card-body">

                        <div class="text-muted small mb-2">
                            Inspections Due
                        </div>

                        <h3 class="mb-1 text-info">
                            {{ $inspectionsDue }}
                        </h3>

                        <div class="text-muted small">
                            Scheduled / in progress
                        </div>

                    </div>

                </div>

            </a>

        </div>


        {{-- Snags --}}
        <div class="col-xl-2 col-md-4 col-6">

            <a href="{{ route('admin.fitout.snags.index') }}"
               class="text-decoration-none">

                <div class="card h-100">

                    <div class="card-body">

                        <div class="text-muted small mb-2">
                            Open Snags
                        </div>

                        <h3 class="mb-1 text-danger">
                            {{ $openSnags }}
                        </h3>

                        <div class="text-muted small">
                            {{ $criticalSnags }} critical
                        </div>

                    </div>

                </div>

            </a>

        </div>


        {{-- Handovers --}}
        <div class="col-xl-2 col-md-4 col-6">

            <a href="{{ route('admin.fitout.handovers.index') }}"
               class="text-decoration-none">

                <div class="card h-100">

                    <div class="card-body">

                        <div class="text-muted small mb-2">
                            Handover Complete
                        </div>

                        <h3 class="mb-1 text-dark">
                            {{ $completedHandovers }}
                        </h3>

                        <div class="text-muted small">
                            Units handed over
                        </div>

                    </div>

                </div>

            </a>

        </div>

    </div>

</div>


{{-- =========================================================
    FIT-OUT PIPELINE
    ========================================================= --}}

<div class="card mb-4">

    <div class="card-header bg-white">

        <strong>
            Fit-Out Pipeline
        </strong>

    </div>

    <div class="card-body">

        <div class="row g-3">


            {{-- Start --}}
            <div class="col">

                <a href="{{ route(
                    'admin.fitout.dashboard',
                    array_merge(
                        request()->query(),
                        ['pipeline' => 'start']
                    )
                ) }}"
                   class="text-decoration-none">

                    <div class="border rounded p-3 h-100">

                        <div class="small text-muted mb-2">
                            Start
                        </div>

                        <h4 class="text-dark mb-1">
                            {{ $pipelineStart }}
                        </h4>

                        <div class="small text-muted">
                            Draft / Submitted
                        </div>

                    </div>

                </a>

            </div>


            {{-- Arrow --}}
            <div class="col-auto d-flex align-items-center text-muted">
                →
            </div>


            {{-- Approval --}}
            <div class="col">

                <a href="{{ route(
                    'admin.fitout.dashboard',
                    array_merge(
                        request()->query(),
                        ['pipeline' => 'approval']
                    )
                ) }}"
                   class="text-decoration-none">

                    <div class="border rounded p-3 h-100">

                        <div class="small text-muted mb-2">
                            1. Approval
                        </div>

                        <h4 class="text-primary mb-1">
                            {{ $pipelineApproval }}
                        </h4>

                        <div class="small text-muted">
                            Approval pending
                        </div>

                    </div>

                </a>

            </div>


            <div class="col-auto d-flex align-items-center text-muted">
                →
            </div>


            {{-- Fit-Out --}}
            <div class="col">

                <a href="{{ route(
                    'admin.fitout.dashboard',
                    array_merge(
                        request()->query(),
                        ['pipeline' => 'fitout']
                    )
                ) }}"
                   class="text-decoration-none">

                    <div class="border rounded p-3 h-100">

                        <div class="small text-muted mb-2">
                            2. Fit-Out
                        </div>

                        <h4 class="text-primary mb-1">
                            {{ $pipelineFitout }}
                        </h4>

                        <div class="small text-muted">
                            Active stages
                        </div>

                    </div>

                </a>

            </div>


            <div class="col-auto d-flex align-items-center text-muted">
                →
            </div>


            {{-- Inspection --}}
            <div class="col">

                <a href="{{ route(
                    'admin.fitout.dashboard',
                    array_merge(
                        request()->query(),
                        ['pipeline' => 'inspection']
                    )
                ) }}"
                   class="text-decoration-none">

                    <div class="border rounded p-3 h-100">

                        <div class="small text-muted mb-2">
                            3. Inspection
                        </div>

                        <h4 class="text-warning mb-1">
                            {{ $pipelineInspection }}
                        </h4>

                        <div class="small text-muted">
                            Scheduled / completed
                        </div>

                    </div>

                </a>

            </div>


            <div class="col-auto d-flex align-items-center text-muted">
                →
            </div>


            {{-- Snags --}}
            <div class="col">

                <a href="{{ route(
                    'admin.fitout.dashboard',
                    array_merge(
                        request()->query(),
                        ['pipeline' => 'snag']
                    )
                ) }}"
                   class="text-decoration-none">

                    <div class="border rounded p-3 h-100">

                        <div class="small text-muted mb-2">
                            4. Snags
                        </div>

                        <h4 class="text-danger mb-1">
                            {{ $pipelineSnag }}
                        </h4>

                        <div class="small text-muted">
                            Open issues
                        </div>

                    </div>

                </a>

            </div>


            <div class="col-auto d-flex align-items-center text-muted">
                →
            </div>


            {{-- Handover --}}
            <div class="col">

                <a href="{{ route(
                    'admin.fitout.dashboard',
                    array_merge(
                        request()->query(),
                        ['pipeline' => 'handover']
                    )
                ) }}"
                   class="text-decoration-none">

                    <div class="border rounded p-3 h-100">

                        <div class="small text-muted mb-2">
                            5. Handover
                        </div>

                        <h4 class="text-success mb-1">
                            {{ $pipelineHandover }}
                        </h4>

                        <div class="small text-muted">
                            Pending / completed
                        </div>

                    </div>

                </a>

            </div>

        </div>


        <div class="small text-muted mt-3">
            Each fit-out moves from request through approval,
            construction, inspection, snag clearance and handover.
        </div>

    </div>

</div>


{{-- =========================================================
    PROGRESS & FLOORS
    ========================================================= --}}

<div class="row g-4 mb-4">


    {{-- Progress --}}
    <div class="col-xl-7">

        <div class="card h-100">

            <div class="card-header bg-white">

                <strong>
                    Fit-Out Progress
                </strong>

            </div>

            <div class="card-body">

                <div class="text-muted small mb-4">
                    Average completion across active fit-out stages.
                </div>


                {{-- Overall --}}
                <div class="mb-4">

                    <div class="d-flex justify-content-between mb-1">

                        <span class="small">
                            Overall Completion
                        </span>

                        <strong class="small">
                            {{ $overallProgress }}%
                        </strong>

                    </div>

                    <div class="progress"
                         style="height: 8px;">

                        <div class="progress-bar bg-success"
                             role="progressbar"
                             style="width: {{ $overallProgress }}%">

                        </div>

                    </div>

                </div>


                {{-- Stages --}}
                @forelse($stageProgress as $stage)

                    <div class="mb-3">

                        <div class="d-flex justify-content-between mb-1">

                            <span class="small">
                                {{ $stage->stage_name }}
                            </span>

                            <strong class="small">
                                {{ $stage->progress }}%
                            </strong>

                        </div>

                        <div class="progress"
                             style="height: 7px;">

                            <div class="progress-bar"
                                 role="progressbar"
                                 style="width: {{ min(100, max(0, $stage->progress)) }}%">

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="text-muted small">
                        No stage progress available.
                    </div>

                @endforelse


                <hr>


                <div class="row text-center">

                    <div class="col">

                        <div class="small text-muted">
                            Pending
                        </div>

                        <strong>
                            {{ $stageStatus['Pending'] ?? 0 }}
                        </strong>

                    </div>


                    <div class="col">

                        <div class="small text-muted">
                            In Progress
                        </div>

                        <strong>
                            {{ $stageStatus['In Progress'] ?? 0 }}
                        </strong>

                    </div>


                    <div class="col">

                        <div class="small text-muted">
                            Completed
                        </div>

                        <strong class="text-success">
                            {{ $stageStatus['Completed'] ?? 0 }}
                        </strong>

                    </div>


                    <div class="col">

                        <div class="small text-muted">
                            On Hold
                        </div>

                        <strong class="text-warning">
                            {{ $stageStatus['On Hold'] ?? 0 }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Units by Floor --}}
    <div class="col-xl-5">

        <div class="card h-100">

            <div class="card-header bg-white">

                <strong>
                    Units by Floor
                </strong>

            </div>

            <div class="card-body">

                <div class="text-muted small mb-3">
                    Active fit-outs versus total units on each level.
                </div>


                @forelse($unitsByFloor as $floor)

                    @php

                        $totalUnits = (int) $floor->total_units;

                        $activeUnits = (int) (
                            $activeUnitsByFloor[$floor->id] ?? 0
                        );

                        $percentage = $totalUnits > 0
                            ? round(($activeUnits / $totalUnits) * 100)
                            : 0;

                    @endphp


                    <div class="mb-3">

                        <div class="d-flex justify-content-between mb-1">

                            <span class="small fw-semibold">
                                {{ $floor->floor_name }}
                            </span>

                            <span class="small text-muted">
                                {{ $activeUnits }} / {{ $totalUnits }}
                            </span>

                        </div>

                        <div class="progress"
                             style="height: 7px;">

                            <div class="progress-bar bg-warning"
                                 role="progressbar"
                                 style="width: {{ $percentage }}%">

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="text-muted small">
                        No floor data available.
                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
    ATTENTION REQUIRED
    ========================================================= --}}

<div class="mb-4">

    <h5 class="mb-3">
        Attention Required
    </h5>


    <div class="row g-4">


        {{-- Critical / High Snags --}}
        <div class="col-xl-4">

            <div class="card h-100">

                <div class="card-header bg-white">

                    <strong>
                        Critical & High Snags
                    </strong>

                </div>

                <div class="card-body">

                    <div class="text-muted small mb-3">
                        Issues requiring immediate attention.
                    </div>


                    @forelse($attentionSnags as $snag)

                        <div class="border-bottom pb-3 mb-3">

                            <div class="fw-semibold small">

                                {{ $snag->snag_number }}
                                -
                                {{ $snag->title }}

                            </div>

                            <div class="text-muted small mt-1">

                                Request:
                                {{ $snag->request_no }}

                                <span class="mx-1">|</span>

                                {{ $snag->priority }}

                                <span class="mx-1">|</span>

                                {{ $snag->status }}

                            </div>

                        </div>

                    @empty

                        <div class="text-muted small">
                            No critical or high priority snags.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- Delayed --}}
        <div class="col-xl-4">

            <div class="card h-100">

                <div class="card-header bg-white">

                    <strong>
                        Delayed Fit-Outs
                    </strong>

                </div>

                <div class="card-body">

                    <div class="text-muted small mb-3">
                        Proposed completion date has passed.
                    </div>


                    @forelse($delayedFitouts as $fitout)

                        <div class="border-bottom pb-3 mb-3">

                            <div class="d-flex justify-content-between">

                                <div>

                                    <div class="fw-semibold small">
                                        {{ $fitout->request_no }}
                                    </div>

                                    <div class="text-muted small mt-1">

                                        Planned end:
                                        {{
                                            \Carbon\Carbon::parse(
                                                $fitout->proposed_end_date
                                            )->format('d M Y')
                                        }}

                                    </div>

                                </div>

                                <span class="badge bg-danger">
                                    Delayed
                                </span>

                            </div>

                        </div>

                    @empty

                        <div class="text-muted small">
                            No delayed fit-outs.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- Upcoming Inspections --}}
        <div class="col-xl-4">

            <div class="card h-100">

                <div class="card-header bg-white">

                    <strong>
                        Upcoming Inspections
                    </strong>

                </div>

                <div class="card-body">

                    <div class="text-muted small mb-3">
                        Next inspections requiring attention.
                    </div>


                    @forelse($upcomingInspections as $inspection)

                        <div class="border-bottom pb-3 mb-3">

                            <div class="fw-semibold small">

                                {{ $inspection->inspection_number }}

                            </div>

                            <div class="text-muted small mt-1">

                                {{ $inspection->request_no }}

                                <span class="mx-1">|</span>

                                {{ $inspection->inspection_type }}

                                <br>

                                {{
                                    \Carbon\Carbon::parse(
                                        $inspection->scheduled_date
                                    )->format('d M Y')
                                }}

                            </div>

                        </div>

                    @empty

                        <div class="text-muted small">
                            No upcoming inspections.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
    FIT-OUT TRACKING
    ========================================================= --}}

<div class="card">

    <div class="card-header bg-white">

        <div class="d-flex justify-content-between align-items-center">

            <strong>
                Fit-Out Tracking
            </strong>

            <a href="{{ route('admin.fitout.requests.index') }}"
               class="btn btn-sm btn-outline-primary">

                View All

            </a>

        </div>

    </div>


    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover table-sm align-middle mb-0">

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
                            Type
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Start
                        </th>

                        <th>
                            End
                        </th>

                        <th class="text-end">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($fitouts as $fitout)

                        <tr>

                            <td>

                                <strong>
                                    {{ $fitout->request_no }}
                                </strong>

                            </td>


                            <td>
                                {{ $fitout->unit_id ?? '-' }}
                            </td>


                            <td>
                                {{ $fitout->tenant_id ?? '-' }}
                            </td>


                            <td>
                                {{ $fitout->fitout_type ?? '-' }}
                            </td>


                            <td>

                                @php

                                    $statusClass = match(
                                        $fitout->fitout_status
                                    ) {

                                        'Completed' =>
                                            'bg-success',

                                        'Approved',
                                        'In Progress' =>
                                            'bg-info text-dark',

                                        'Under Review',
                                        'Submitted' =>
                                            'bg-warning text-dark',

                                        'Rejected' =>
                                            'bg-danger',

                                        default =>
                                            'bg-secondary',

                                    };

                                @endphp


                                <span class="badge {{ $statusClass }}">

                                    {{ $fitout->fitout_status }}

                                </span>

                            </td>


                            <td>

                                {{
                                    $fitout->proposed_start_date
                                        ? \Carbon\Carbon::parse(
                                            $fitout->proposed_start_date
                                        )->format('d M Y')
                                        : '-'
                                }}

                            </td>


                            <td>

                                {{
                                    $fitout->proposed_end_date
                                        ? \Carbon\Carbon::parse(
                                            $fitout->proposed_end_date
                                        )->format('d M Y')
                                        : '-'
                                }}

                            </td>


                            <td class="text-end">

                                <a href="{{
                                    route(
                                        'admin.fitout.requests.show',
                                        $fitout->id
                                    )
                                }}"
                                   class="btn btn-sm btn-outline-primary">

                                    <i class="fas fa-arrow-right"></i>

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8"
                                class="text-center py-5 text-muted">

                                <i class="bi bi-search fs-3 d-block mb-2"></i>

                                No fit-out requests match the selected filters.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($fitouts->hasPages())

            <div class="p-3 border-top">

                {{ $fitouts->links() }}

            </div>

        @endif

    </div>

</div>

</div>

@endsection
