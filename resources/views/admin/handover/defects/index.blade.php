@extends('layouts.app')

@section('title', 'Handover Defects')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Handover Defects
            </h4>

            <div class="text-muted">

                {{ $project->project_code ?? '' }}

                -

                {{ $project->project_name
                    ?? $project->name
                    ?? '' }}

            </div>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.handover.index',
                    $project
                ) }}"
                class="btn btn-light">

                <i class="ri-arrow-left-line"></i>
                Handover

            </a>

            <button
                type="button"
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#addDefectModal">

                <i class="ri-add-line"></i>
                Add Defect

            </button>

        </div>

    </div>


    {{-- =========================================================
         ALERTS
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
         KPI CARDS
    ========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- Total --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Total Defects
                            </div>

                            <h3 class="mb-0">
                                {{ $totalDefects }}
                            </h3>

                        </div>

                        <div
                            class="rounded bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                            style="width:45px;height:45px;">

                            <i class="ri-bug-line fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Open --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Outstanding
                            </div>

                            <h3 class="mb-0 text-warning">
                                {{ $openDefects }}
                            </h3>

                        </div>

                        <div
                            class="rounded bg-warning-subtle text-warning d-flex align-items-center justify-content-center"
                            style="width:45px;height:45px;">

                            <i class="ri-alert-line fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Closed --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Closed
                            </div>

                            <h3 class="mb-0 text-success">
                                {{ $closedDefects }}
                            </h3>

                        </div>

                        <div
                            class="rounded bg-success-subtle text-success d-flex align-items-center justify-content-center"
                            style="width:45px;height:45px;">

                            <i class="ri-checkbox-circle-line fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Critical --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Critical Outstanding
                            </div>

                            <h3 class="mb-0 text-danger">
                                {{ $criticalDefects }}
                            </h3>

                        </div>

                        <div
                            class="rounded bg-danger-subtle text-danger d-flex align-items-center justify-content-center"
                            style="width:45px;height:45px;">

                            <i class="ri-alarm-warning-line fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         SECONDARY KPI
    ========================================================== --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Under Verification
                    </div>

                    <h4 class="mb-0">
                        {{ $verificationDefects }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Rejected
                    </div>

                    <h4 class="mb-0 text-danger">
                        {{ $rejectedDefects }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Warranty Related
                    </div>

                    <h4 class="mb-0">
                        {{ $warrantyDefects }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Closure
                    </div>

                    <h4 class="mb-0">
                        {{ $closurePercentage }}%
                    </h4>

                    <div class="progress mt-2" style="height:6px;">

                        <div
                            class="progress-bar"
                            style="width: {{ $closurePercentage }}%">
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         FILTERS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3">

                    <div class="col-xl-3 col-md-6">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Defect no, title, location...">

                    </div>


                    <div class="col-xl-2 col-md-6">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select">

                            <option value="">
                                All Statuses
                            </option>

                            @foreach($statuses as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        request('status') === $status
                                    )>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-xl-2 col-md-6">

                        <label class="form-label">
                            Priority
                        </label>

                        <select
                            name="priority"
                            class="form-select">

                            <option value="">
                                All Priorities
                            </option>

                            @foreach($priorities as $priority)

                                <option
                                    value="{{ $priority }}"
                                    @selected(
                                        request('priority') === $priority
                                    )>

                                    {{ $priority }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-xl-2 col-md-6">

                        <label class="form-label">
                            Discipline
                        </label>

                        <select
                            name="discipline"
                            class="form-select">

                            <option value="">
                                All Disciplines
                            </option>

                            @foreach($disciplines as $discipline)

                                <option
                                    value="{{ $discipline }}"
                                    @selected(
                                        request('discipline') === $discipline
                                    )>

                                    {{ $discipline }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-xl-2 col-md-6">

                        <label class="form-label">
                            Warranty
                        </label>

                        <select
                            name="warranty_related"
                            class="form-select">

                            <option value="">
                                All
                            </option>

                            <option
                                value="1"
                                @selected(
                                    request('warranty_related') === '1'
                                )>

                                Warranty Related

                            </option>

                            <option
                                value="0"
                                @selected(
                                    request('warranty_related') === '0'
                                )>

                                Non-Warranty

                            </option>

                        </select>

                    </div>


                    <div class="col-xl-1 col-md-6 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            <i class="ri-search-line"></i>

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         DEFECT TABLE
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-1">
                        Defect Register
                    </h5>

                    <small class="text-muted">
                        {{ $handover->handover_no }}
                    </small>

                </div>

                <span class="badge bg-light text-dark">
                    {{ $defects->total() }} Records
                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>Defect</th>

                        <th>Location</th>

                        <th>Contract</th>

                        <th>Priority</th>

                        <th>Due Date</th>

                        <th>Assigned To</th>

                        <th>Status</th>

                        <th width="60">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse($defects as $defect)

                    <tr>

                        {{-- DEFECT --}}

                        <td>

                            <div class="fw-semibold">
                                {{ $defect->defect_no }}
                            </div>

                            <div>
                                {{ $defect->title }}
                            </div>

                            @if($defect->snag)

                                <small class="text-muted">

                                    <i class="ri-link"></i>

                                    {{ $defect->snag->snag_no }}

                                </small>

                            @endif

                        </td>


                        {{-- LOCATION --}}

                        <td>

                            @if($defect->location)
                                <div>
                                    {{ $defect->location }}
                                </div>
                            @endif

                            @if($defect->building)
                                <small class="text-muted">
                                    Building: {{ $defect->building }}
                                </small>
                            @endif

                            @if($defect->floor)
                                <small class="text-muted d-block">
                                    Floor: {{ $defect->floor }}
                                </small>
                            @endif

                            @if(
                                !$defect->location &&
                                !$defect->building &&
                                !$defect->floor
                            )
                                -
                            @endif

                        </td>


                        {{-- PROCUREMENT CONTRACT --}}

                        <td>

                            @if($defect->procurementContract)

                                <div class="fw-semibold">

                                    {{ $defect->procurementContract->contract_number }}

                                </div>

                                <small class="text-muted">

                                    {{ $defect->procurementContract->contract_title }}

                                </small>

                                @if(
                                    $defect->procurementContract->bidder_name
                                )

                                    <small class="text-muted d-block">

                                        {{ $defect->procurementContract->bidder_name }}

                                    </small>

                                @endif

                            @else

                                <span class="text-muted">
                                    Not linked
                                </span>

                            @endif

                        </td>


                        {{-- PRIORITY --}}

                        <td>

                            @php

                                $priorityClass = match(
                                    $defect->priority
                                ) {

                                    'Low' =>
                                        'bg-secondary',

                                    'Medium' =>
                                        'bg-info',

                                    'High' =>
                                        'bg-warning text-dark',

                                    'Critical' =>
                                        'bg-danger',

                                    default =>
                                        'bg-secondary',

                                };

                            @endphp

                            <span
                                class="badge {{ $priorityClass }}">

                                {{ $defect->priority }}

                            </span>

                            @if($defect->warranty_related)

                                <span
                                    class="badge bg-dark mt-1">

                                    Warranty

                                </span>

                            @endif

                        </td>


                        {{-- DUE DATE --}}

                        <td>

                            @if($defect->due_date)

                                <span
                                    class="
                                    {{ $defect->due_date->isPast()
                                        && $defect->status !== 'Closed'
                                        ? 'text-danger fw-semibold'
                                        : ''
                                    }}">

                                    {{ $defect->due_date->format(
                                        'd M Y'
                                    ) }}

                                </span>

                                @if(
                                    $defect->due_date->isPast()
                                    && $defect->status !== 'Closed'
                                )

                                    <small class="text-danger d-block">
                                        Overdue
                                    </small>

                                @endif

                            @else

                                -

                            @endif

                        </td>


                        {{-- ASSIGNED --}}

                        <td>

                            {{ $defect->assignedTo?->name ?? '-' }}

                        </td>


                        {{-- STATUS --}}

                        <td>

                            @php

                                $statusClass = match(
                                    $defect->status
                                ) {

                                    'Open' =>
                                        'bg-secondary',

                                    'Assigned' =>
                                        'bg-primary',

                                    'In Progress' =>
                                        'bg-info',

                                    'Rectification Submitted' =>
                                        'bg-warning text-dark',

                                    'Under Verification' =>
                                        'bg-primary',

                                    'Rejected' =>
                                        'bg-danger',

                                    'Closed' =>
                                        'bg-success',

                                    default =>
                                        'bg-secondary',

                                };

                            @endphp

                            <span
                                class="badge {{ $statusClass }}">

                                {{ $defect->status }}

                            </span>

                        </td>


                        {{-- ACTION --}}

                        <td>

                            <div class="dropdown">

                                <button
                                    type="button"
                                    class="btn btn-sm btn-light"
                                    data-bs-toggle="dropdown">

                                    <i class="ri-more-2-fill"></i>

                                </button>


                                <ul class="dropdown-menu dropdown-menu-end">


                                    {{-- VIEW ATTACHMENT --}}

                                    @if($defect->attachment_path)

                                        <li>

                                            <a
                                                href="{{ asset(
                                                    'storage/' .
                                                    $defect->attachment_path
                                                ) }}"
                                                target="_blank"
                                                class="dropdown-item">

                                                <i class="ri-attachment-line me-2"></i>

                                                View Attachment

                                            </a>

                                        </li>

                                    @endif


                                    {{-- EDIT --}}

                                    @if(in_array(
                                        $defect->status,
                                        [
                                            'Open',
                                            'Assigned',
                                            'Rejected'
                                        ]
                                    ))

                                        <li>

                                            <button
                                                type="button"
                                                class="dropdown-item"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editDefect{{ $defect->id }}">

                                                <i class="ri-edit-line me-2"></i>

                                                Edit

                                            </button>

                                        </li>

                                    @endif


                                    {{-- ASSIGN --}}

                                    @if(in_array(
                                        $defect->status,
                                        [
                                            'Open',
                                            'Rejected'
                                        ]
                                    ))

                                        <li>

                                            <button
                                                type="button"
                                                class="dropdown-item"
                                                data-bs-toggle="modal"
                                                data-bs-target="#assignDefect{{ $defect->id }}">

                                                <i class="ri-user-add-line me-2"></i>

                                                Assign

                                            </button>

                                        </li>

                                    @endif


                                    {{-- START --}}

                                    @if($defect->status === 'Assigned')

                                        <li>

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.handover.defects.start',
                                                    [$project, $defect]
                                                ) }}">

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="dropdown-item">

                                                    <i class="ri-play-line me-2"></i>

                                                    Start Rectification

                                                </button>

                                            </form>

                                        </li>

                                    @endif


                                    {{-- SUBMIT RECTIFICATION --}}

                                    @if($defect->status === 'In Progress')

                                        <li>

                                            <button
                                                type="button"
                                                class="dropdown-item"
                                                data-bs-toggle="modal"
                                                data-bs-target="#rectification{{ $defect->id }}">

                                                <i class="ri-send-plane-line me-2"></i>

                                                Submit Rectification

                                            </button>

                                        </li>

                                    @endif


                                    {{-- START VERIFICATION --}}

                                    @if(
                                        $defect->status ===
                                        'Rectification Submitted'
                                    )

                                        <li>

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.handover.defects.verify',
                                                    [$project, $defect]
                                                ) }}">

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="dropdown-item">

                                                    <i class="ri-search-eye-line me-2"></i>

                                                    Start Verification

                                                </button>

                                            </form>

                                        </li>

                                    @endif


                                    {{-- CLOSE --}}

                                    @if(
                                        $defect->status ===
                                        'Under Verification'
                                    )

                                        <li>

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.handover.defects.close',
                                                    [$project, $defect]
                                                ) }}">

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="dropdown-item text-success"
                                                    onclick="return confirm('Verify and close this defect?')">

                                                    <i class="ri-checkbox-circle-line me-2"></i>

                                                    Verify &amp; Close

                                                </button>

                                            </form>

                                        </li>


                                        <li>

                                            <button
                                                type="button"
                                                class="dropdown-item text-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#rejectDefect{{ $defect->id }}">

                                                <i class="ri-close-circle-line me-2"></i>

                                                Reject Verification

                                            </button>

                                        </li>

                                    @endif


                                    {{-- DELETE --}}

                                    @if(in_array(
                                        $defect->status,
                                        [
                                            'Open',
                                            'Assigned',
                                            'Rejected'
                                        ]
                                    ))

                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>

                                        <li>

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.handover.defects.destroy',
                                                    [$project, $defect]
                                                ) }}">

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="dropdown-item text-danger"
                                                    onclick="return confirm('Delete this defect?')">

                                                    <i class="ri-delete-bin-line me-2"></i>

                                                    Delete

                                                </button>

                                            </form>

                                        </li>

                                    @endif

                                </ul>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="text-center py-5">

                            <i
                                class="ri-bug-line fs-1 text-muted">
                            </i>

                            <h6 class="mt-3">
                                No defects found
                            </h6>

                            <p class="text-muted mb-0">
                                No handover defects have been recorded.
                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        @if($defects->hasPages())

            <div class="card-footer bg-white">

                {{ $defects->links() }}

            </div>

        @endif

    </div>

</div>


{{-- =============================================================
     ADD DEFECT MODAL
============================================================= --}}

<div
    class="modal fade"
    id="addDefectModal"
    tabindex="-1">

    <div class="modal-dialog modal-xl">

        <div class="modal-content">

            <form
                method="POST"
                enctype="multipart/form-data"
                action="{{ route(
                    'admin.projects.handover.defects.store',
                    $project
                ) }}">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        Add Handover Defect
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    @include(
                        'admin.handover.defects.partials.form',
                        [
                            'defect' => null
                        ]
                    )

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="ri-save-line"></i>

                        Save Defect

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =============================================================
     MODALS FOR EACH DEFECT
============================================================= --}}

@foreach($defects as $defect)

    {{-- =========================================================
         EDIT
    ========================================================== --}}

    @if(in_array(
        $defect->status,
        [
            'Open',
            'Assigned',
            'Rejected'
        ]
    ))

        <div
            class="modal fade"
            id="editDefect{{ $defect->id }}"
            tabindex="-1">

            <div class="modal-dialog modal-xl">

                <div class="modal-content">

                    <form
                        method="POST"
                        enctype="multipart/form-data"
                        action="{{ route(
                            'admin.projects.handover.defects.update',
                            [$project, $defect]
                        ) }}">

                        @csrf
                        @method('PUT')

                        <div class="modal-header">

                            <h5 class="modal-title">

                                Edit Defect

                                <small class="text-muted">
                                    {{ $defect->defect_no }}
                                </small>

                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            @include(
                                'admin.handover.defects.partials.form',
                                [
                                    'defect' => $defect
                                ]
                            )

                        </div>

                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-light"
                                data-bs-dismiss="modal">

                                Cancel

                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary">

                                Update Defect

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
         ASSIGN
    ========================================================== --}}

    @if(in_array(
        $defect->status,
        [
            'Open',
            'Rejected'
        ]
    ))

        <div
            class="modal fade"
            id="assignDefect{{ $defect->id }}"
            tabindex="-1">

            <div class="modal-dialog">

                <div class="modal-content">

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.handover.defects.assign',
                            [$project, $defect]
                        ) }}">

                        @csrf

                        <div class="modal-header">

                            <h5 class="modal-title">
                                Assign Defect
                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            <div class="mb-3">

                                <label class="form-label">
                                    Defect
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $defect->defect_no }} - {{ $defect->title }}"
                                    readonly>

                            </div>


                            <div class="mb-3">

                                <label class="form-label">

                                    Assign To

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>

                                <select
                                    name="assigned_to"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        Select User
                                    </option>

                                    @foreach($users as $user)

                                        <option
                                            value="{{ $user->id }}"
                                            @selected(
                                                $defect->assigned_to == $user->id
                                            )>

                                            {{ $user->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-light"
                                data-bs-dismiss="modal">

                                Cancel

                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary">

                                Assign

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
         RECTIFICATION
    ========================================================== --}}

    @if($defect->status === 'In Progress')

        <div
            class="modal fade"
            id="rectification{{ $defect->id }}"
            tabindex="-1">

            <div class="modal-dialog modal-lg">

                <div class="modal-content">

                    <form
                        method="POST"
                        enctype="multipart/form-data"
                        action="{{ route(
                            'admin.projects.handover.defects.submit-rectification',
                            [$project, $defect]
                        ) }}">

                        @csrf

                        <div class="modal-header">

                            <h5 class="modal-title">

                                Submit Rectification

                                <small class="text-muted">
                                    {{ $defect->defect_no }}
                                </small>

                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            <div class="mb-3">

                                <label class="form-label">

                                    Rectification Details

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>

                                <textarea
                                    name="rectification_details"
                                    class="form-control"
                                    rows="6"
                                    required
                                    placeholder="Describe the corrective action completed..."></textarea>

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    Supporting Attachment
                                </label>

                                <input
                                    type="file"
                                    name="attachment"
                                    class="form-control">

                                <small class="text-muted">

                                    Upload completion photo, report,
                                    certificate or other evidence.

                                </small>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-light"
                                data-bs-dismiss="modal">

                                Cancel

                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary">

                                Submit for Verification

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
         REJECT
    ========================================================== --}}

    @if($defect->status === 'Under Verification')

        <div
            class="modal fade"
            id="rejectDefect{{ $defect->id }}"
            tabindex="-1">

            <div class="modal-dialog">

                <div class="modal-content">

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.handover.defects.reject',
                            [$project, $defect]
                        ) }}">

                        @csrf

                        <div class="modal-header">

                            <h5 class="modal-title text-danger">

                                Reject Verification

                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            <div class="mb-3">

                                <label class="form-label">

                                    Rejection Reason

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>

                                <textarea
                                    name="rejection_reason"
                                    class="form-control"
                                    rows="5"
                                    required
                                    placeholder="Explain why the rectification was rejected..."></textarea>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-light"
                                data-bs-dismiss="modal">

                                Cancel

                            </button>

                            <button
                                type="submit"
                                class="btn btn-danger">

                                Reject

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endif

@endforeach

@endsection