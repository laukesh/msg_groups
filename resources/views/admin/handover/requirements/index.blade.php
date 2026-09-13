@extends('layouts.app')

@section('title', 'Handover Requirements')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
         ALERTS
    ============================================================ --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="ri-checkbox-circle-line me-1"></i>

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

            <i class="ri-error-warning-line me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ============================================================
         HEADER
    ============================================================ --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Handover Requirements
            </h4>

            <div class="text-muted">

                {{ $project->project_name ?? $project->name ?? 'Project' }}

                <span class="mx-2">•</span>

                {{ $handover->handover_no }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.handover.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary">

                <i class="ri-arrow-left-line me-1"></i>

                Handover Dashboard

            </a>


            <button
                type="button"
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#addRequirementModal">

                <i class="ri-add-line me-1"></i>

                Add Requirement

            </button>

        </div>

    </div>


    {{-- ============================================================
         KPI
    ============================================================ --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        TOTAL
                    </div>

                    <h4 class="fw-bold mb-0 mt-2">
                        {{ $totalRequirements }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        COMPLETED
                    </div>

                    <h4 class="fw-bold text-success mb-0 mt-2">
                        {{ $completedRequirements }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        IN PROGRESS
                    </div>

                    <h4 class="fw-bold text-primary mb-0 mt-2">
                        {{ $inProgressRequirements }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        PENDING
                    </div>

                    <h4 class="fw-bold text-warning mb-0 mt-2">
                        {{ $pendingRequirements }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        REJECTED
                    </div>

                    <h4 class="fw-bold text-danger mb-0 mt-2">
                        {{ $rejectedRequirements }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        WAIVED
                    </div>

                    <h4 class="fw-bold text-secondary mb-0 mt-2">
                        {{ $waivedRequirements }}
                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         FILTERS
    ============================================================ --}}

    <div class="card border mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route(
                    'admin.projects.handover.requirements.index',
                    $project
                ) }}">

                <div class="row g-2">

                    <div class="col-lg-4">

                        <label class="form-label small fw-semibold">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Code, title or type...">

                    </div>


                    <div class="col-lg-2">

                        <label class="form-label small fw-semibold">
                            Type
                        </label>

                        <select
                            name="requirement_type"
                            class="form-select">

                            <option value="">
                                All Types
                            </option>

                            @foreach($types as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(
                                        request('requirement_type') === $type
                                    )>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-2">

                        <label class="form-label small fw-semibold">
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


                    <div class="col-lg-2">

                        <label class="form-label small fw-semibold">
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


                    <div class="col-lg-2 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary">

                            <i class="ri-filter-3-line me-1"></i>

                            Filter

                        </button>


                        <a
                            href="{{ route(
                                'admin.projects.handover.requirements.index',
                                $project
                            ) }}"
                            class="btn btn-light">

                            Clear

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ============================================================
         TABLE
    ============================================================ --}}

    <div class="card border shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h6 class="mb-1 fw-semibold">
                        Requirements
                    </h6>

                    <small class="text-muted">
                        Manage project handover and closeout requirements.
                    </small>

                </div>

                <span class="badge bg-light text-dark">

                    {{ $requirements->total() }}

                    Records

                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th width="50">
                            #
                        </th>

                        <th>
                            Requirement
                        </th>

                        <th>
                            Type
                        </th>

                        <th>
                            Responsible
                        </th>

                        <th>
                            Due Date
                        </th>

                        <th>
                            Priority
                        </th>

                        <th>
                            Status
                        </th>

                        <th
                            width="210"
                            class="text-end">

                            Action

                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($requirements as $requirement)

                        <tr>

                            <td>

                                {{ $requirements->firstItem() + $loop->index }}

                            </td>


                            {{-- Requirement --}}

                            <td>

                                <div class="fw-semibold">

                                    {{ $requirement->title }}

                                </div>

                                <small class="text-muted">

                                    {{ $requirement->requirement_code }}

                                </small>

                                @if($requirement->is_mandatory)

                                    <span
                                        class="badge bg-danger-subtle text-danger ms-1">

                                        Mandatory

                                    </span>

                                @endif

                            </td>


                            {{-- Type --}}

                            <td>

                                <span class="badge bg-light text-dark">

                                    {{ $requirement->requirement_type }}

                                </span>

                            </td>


                            {{-- Responsible --}}

                            <td>

                                @if($requirement->responsibleUser)

                                    {{ $requirement->responsibleUser->name }}

                                @else

                                    <span class="text-muted">
                                        Not Assigned
                                    </span>

                                @endif

                            </td>


                            {{-- Due Date --}}

                            <td>

                                @if($requirement->due_date)

                                    {{ $requirement->due_date->format('d-m-Y') }}

                                    @if(
                                        $requirement->due_date->isPast()
                                        &&
                                        !in_array(
                                            $requirement->status,
                                            ['Completed', 'Waived']
                                        )
                                    )

                                        <span
                                            class="badge bg-danger-subtle text-danger">

                                            Overdue

                                        </span>

                                    @endif

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Priority --}}

                            <td>

                                @switch($requirement->priority)

                                    @case('Critical')

                                        <span class="badge bg-danger">
                                            Critical
                                        </span>

                                        @break

                                    @case('High')

                                        <span class="badge bg-warning text-dark">
                                            High
                                        </span>

                                        @break

                                    @case('Medium')

                                        <span class="badge bg-primary-subtle text-primary">
                                            Medium
                                        </span>

                                        @break

                                    @default

                                        <span class="badge bg-light text-dark">
                                            Low
                                        </span>

                                @endswitch

                            </td>


                            {{-- Status --}}

                            <td>

                                @switch($requirement->status)

                                    @case('Completed')

                                        <span class="badge bg-success-subtle text-success">

                                            <i class="ri-checkbox-circle-line me-1"></i>

                                            Completed

                                        </span>

                                        @break

                                    @case('In Progress')

                                        <span class="badge bg-primary-subtle text-primary">

                                            <i class="ri-loader-4-line me-1"></i>

                                            In Progress

                                        </span>

                                        @break

                                    @case('Submitted')

                                        <span class="badge bg-info-subtle text-info">

                                            <i class="ri-send-plane-line me-1"></i>

                                            Submitted

                                        </span>

                                        @break

                                    @case('Under Review')

                                        <span class="badge bg-info-subtle text-info">

                                            <i class="ri-search-eye-line me-1"></i>

                                            Under Review

                                        </span>

                                        @break

                                    @case('Rejected')

                                        <span class="badge bg-danger-subtle text-danger">

                                            <i class="ri-close-circle-line me-1"></i>

                                            Rejected

                                        </span>

                                        @break

                                    @case('Waived')

                                        <span class="badge bg-secondary-subtle text-secondary">

                                            <i class="ri-forbid-2-line me-1"></i>

                                            Waived

                                        </span>

                                        @break

                                    @default

                                        <span class="badge bg-warning-subtle text-warning">

                                            <i class="ri-time-line me-1"></i>

                                            Pending

                                        </span>

                                @endswitch

                            </td>


                            {{-- Actions --}}

                            <td class="text-end">

                                <div class="dropdown">

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                        data-bs-toggle="dropdown">

                                        Actions

                                    </button>


                                    <ul class="dropdown-menu dropdown-menu-end">


                                        {{-- View/Edit --}}

                                        @if(
                                            !in_array(
                                                $requirement->status,
                                                [
                                                    'Submitted',
                                                    'Under Review',
                                                    'Completed',
                                                    'Waived'
                                                ],
                                                true
                                            )
                                        )

                                            <li>

                                                <button
                                                    type="button"
                                                    class="dropdown-item"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editRequirementModal{{ $requirement->id }}">

                                                    <i class="ri-edit-line me-2"></i>

                                                    Edit

                                                </button>

                                            </li>

                                        @endif


                                        {{-- Pending / Rejected --}}

                                        @if(
                                            in_array(
                                                $requirement->status,
                                                ['Pending', 'Rejected'],
                                                true
                                            )
                                        )

                                            <li>

                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.projects.handover.requirements.start',
                                                        [$project, $requirement]
                                                    ) }}">

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="dropdown-item">

                                                        <i class="ri-play-line me-2"></i>

                                                        Start

                                                    </button>

                                                </form>

                                            </li>


                                            <li>

                                                <button
                                                    type="button"
                                                    class="dropdown-item"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#waiveRequirementModal{{ $requirement->id }}">

                                                    <i class="ri-forbid-2-line me-2"></i>

                                                    Waive

                                                </button>

                                            </li>

                                        @endif


                                        {{-- In Progress --}}

                                        @if(
                                            $requirement->status === 'In Progress'
                                        )

                                            <li>

                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.projects.handover.requirements.submit',
                                                        [$project, $requirement]
                                                    ) }}">

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="dropdown-item">

                                                        <i class="ri-send-plane-line me-2"></i>

                                                        Submit

                                                    </button>

                                                </form>

                                            </li>

                                        @endif


                                        {{-- Submitted --}}

                                        @if(
                                            $requirement->status === 'Submitted'
                                        )

                                            <li>

                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.projects.handover.requirements.review',
                                                        [$project, $requirement]
                                                    ) }}">

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="dropdown-item">

                                                        <i class="ri-search-eye-line me-2"></i>

                                                        Start Review

                                                    </button>

                                                </form>

                                            </li>

                                        @endif


                                        {{-- Under Review --}}

                                        @if(
                                            $requirement->status === 'Under Review'
                                        )

                                            <li>

                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.projects.handover.requirements.complete',
                                                        [$project, $requirement]
                                                    ) }}">

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="dropdown-item text-success">

                                                        <i class="ri-checkbox-circle-line me-2"></i>

                                                        Complete

                                                    </button>

                                                </form>

                                            </li>


                                            <li>

                                                <button
                                                    type="button"
                                                    class="dropdown-item text-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#rejectRequirementModal{{ $requirement->id }}">

                                                    <i class="ri-close-circle-line me-2"></i>

                                                    Reject

                                                </button>

                                            </li>

                                        @endif


                                        {{-- Delete --}}

                                        @if(
                                            !in_array(
                                                $requirement->status,
                                                [
                                                    'Submitted',
                                                    'Under Review',
                                                    'Completed',
                                                    'Waived'
                                                ],
                                                true
                                            )
                                            &&
                                            !(
                                                $requirement->is_mandatory
                                                &&
                                                in_array(
                                                    $requirement->requirement_code,
                                                    [
                                                        'CONST-COMP',
                                                        'COMM-COMP',
                                                        'SNAG-COMP',
                                                        'DEFECT-COMP',
                                                        'OM-MANUAL',
                                                        'AS-BUILT',
                                                        'WARRANTY',
                                                        'AUTH-APPROVAL',
                                                        'TRAINING',
                                                        'ASSET-REG',
                                                        'FINAL-ACCOUNT',
                                                        'FINAL-PAYMENT'
                                                    ],
                                                    true
                                                )
                                            )
                                        )

                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>

                                            <li>

                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.projects.handover.requirements.destroy',
                                                        [$project, $requirement]
                                                    ) }}"
                                                    onsubmit="return confirm('Are you sure you want to delete this requirement?');">

                                                    @csrf

                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="dropdown-item text-danger">

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


                        {{-- ==================================================
                             EDIT MODAL
                        ================================================== --}}

                        @if(
                            !in_array(
                                $requirement->status,
                                [
                                    'Submitted',
                                    'Under Review',
                                    'Completed',
                                    'Waived'
                                ],
                                true
                            )
                        )

                            <div
                                class="modal fade"
                                id="editRequirementModal{{ $requirement->id }}"
                                tabindex="-1">

                                <div class="modal-dialog modal-lg modal-dialog-centered">

                                    <div class="modal-content">

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.projects.handover.requirements.update',
                                                [$project, $requirement]
                                            ) }}">

                                            @csrf

                                            @method('PUT')


                                            <div class="modal-header">

                                                <h5 class="modal-title">
                                                    Edit Requirement
                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal">
                                                </button>

                                            </div>


                                            <div class="modal-body">

                                                <div class="row g-3">

                                                    <div class="col-md-4">

                                                        <label class="form-label">
                                                            Requirement Code
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="requirement_code"
                                                            class="form-control"
                                                            value="{{ $requirement->requirement_code }}"
                                                            required>

                                                    </div>


                                                    <div class="col-md-4">

                                                        <label class="form-label">
                                                            Type
                                                        </label>

                                                        <select
                                                            name="requirement_type"
                                                            class="form-select"
                                                            required>

                                                            @foreach($types as $type)

                                                                <option
                                                                    value="{{ $type }}"
                                                                    @selected(
                                                                        $requirement->requirement_type === $type
                                                                    )>

                                                                    {{ $type }}

                                                                </option>

                                                            @endforeach

                                                        </select>

                                                    </div>


                                                    <div class="col-md-4">

                                                        <label class="form-label">
                                                            Priority
                                                        </label>

                                                        <select
                                                            name="priority"
                                                            class="form-select"
                                                            required>

                                                            @foreach($priorities as $priority)

                                                                <option
                                                                    value="{{ $priority }}"
                                                                    @selected(
                                                                        $requirement->priority === $priority
                                                                    )>

                                                                    {{ $priority }}

                                                                </option>

                                                            @endforeach

                                                        </select>

                                                    </div>


                                                    <div class="col-12">

                                                        <label class="form-label">
                                                            Title
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="title"
                                                            class="form-control"
                                                            value="{{ $requirement->title }}"
                                                            required>

                                                    </div>


                                                    <div class="col-12">

                                                        <label class="form-label">
                                                            Description
                                                        </label>

                                                        <textarea
                                                            name="description"
                                                            rows="3"
                                                            class="form-control">{{ $requirement->description }}</textarea>

                                                    </div>


                                                    <div class="col-md-6">

                                                        <label class="form-label">
                                                            Source Module
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="source_module"
                                                            class="form-control"
                                                            value="{{ $requirement->source_module }}">

                                                    </div>


                                                    <div class="col-md-6">

                                                        <label class="form-label">
                                                            Source ID
                                                        </label>

                                                        <input
                                                            type="number"
                                                            name="source_id"
                                                            class="form-control"
                                                            value="{{ $requirement->source_id }}">

                                                    </div>


                                                    <div class="col-md-4">

                                                        <label class="form-label">
                                                            Responsible User
                                                        </label>

                                                        <select
                                                            name="responsible_user_id"
                                                            class="form-select">

                                                            <option value="">
                                                                Not Assigned
                                                            </option>

                                                            @foreach($users as $user)

                                                                <option
                                                                    value="{{ $user->id }}"
                                                                    @selected(
                                                                        $requirement->responsible_user_id == $user->id
                                                                    )>

                                                                    {{ $user->name }}

                                                                </option>

                                                            @endforeach

                                                        </select>

                                                    </div>


                                                    <div class="col-md-4">

                                                        <label class="form-label">
                                                            Due Date
                                                        </label>

                                                        <input
                                                            type="date"
                                                            name="due_date"
                                                            class="form-control"
                                                            value="{{ $requirement->due_date?->format('Y-m-d') }}">

                                                    </div>


                                                    <div class="col-md-4">

                                                        <label class="form-label d-block">
                                                            Requirement
                                                        </label>

                                                        <div class="form-check mt-2">

                                                            <input
                                                                type="checkbox"
                                                                name="is_mandatory"
                                                                value="1"
                                                                class="form-check-input"
                                                                id="mandatory{{ $requirement->id }}"
                                                                @checked($requirement->is_mandatory)>

                                                            <label
                                                                class="form-check-label"
                                                                for="mandatory{{ $requirement->id }}">

                                                                Mandatory

                                                            </label>

                                                        </div>

                                                    </div>


                                                    <div class="col-12">

                                                        <label class="form-label">
                                                            Remarks
                                                        </label>

                                                        <textarea
                                                            name="remarks"
                                                            rows="2"
                                                            class="form-control">{{ $requirement->remarks }}</textarea>

                                                    </div>

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

                                                    <i class="ri-save-line me-1"></i>

                                                    Update Requirement

                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- ==================================================
                             REJECT MODAL
                        ================================================== --}}

                        @if($requirement->status === 'Under Review')

                            <div
                                class="modal fade"
                                id="rejectRequirementModal{{ $requirement->id }}"
                                tabindex="-1">

                                <div class="modal-dialog modal-dialog-centered">

                                    <div class="modal-content">

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.projects.handover.requirements.reject',
                                                [$project, $requirement]
                                            ) }}">

                                            @csrf


                                            <div class="modal-header">

                                                <h5 class="modal-title">
                                                    Reject Requirement
                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal">
                                                </button>

                                            </div>


                                            <div class="modal-body">

                                                <div class="mb-3">

                                                    <div class="fw-semibold">
                                                        {{ $requirement->title }}
                                                    </div>

                                                    <small class="text-muted">
                                                        {{ $requirement->requirement_code }}
                                                    </small>

                                                </div>


                                                <label class="form-label">
                                                    Rejection Reason
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <textarea
                                                    name="rejection_reason"
                                                    class="form-control"
                                                    rows="4"
                                                    required
                                                    placeholder="Enter reason for rejection..."></textarea>

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

                                                    Reject Requirement

                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- ==================================================
                             WAIVE MODAL
                        ================================================== --}}

                        @if(
                            in_array(
                                $requirement->status,
                                ['Pending', 'Rejected'],
                                true
                            )
                        )

                            <div
                                class="modal fade"
                                id="waiveRequirementModal{{ $requirement->id }}"
                                tabindex="-1">

                                <div class="modal-dialog modal-dialog-centered">

                                    <div class="modal-content">

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.projects.handover.requirements.waive',
                                                [$project, $requirement]
                                            ) }}">

                                            @csrf


                                            <div class="modal-header">

                                                <h5 class="modal-title">
                                                    Waive Requirement
                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal">
                                                </button>

                                            </div>


                                            <div class="modal-body">

                                                <div class="alert alert-warning">

                                                    You are about to waive:

                                                    <strong>
                                                        {{ $requirement->title }}
                                                    </strong>

                                                </div>


                                                <label class="form-label">

                                                    Waiver Reason

                                                    <span class="text-danger">
                                                        *
                                                    </span>

                                                </label>

                                                <textarea
                                                    name="waiver_reason"
                                                    class="form-control"
                                                    rows="4"
                                                    required
                                                    placeholder="Enter reason for waiving this requirement..."></textarea>

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
                                                    class="btn btn-secondary">

                                                    Waive Requirement

                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @endif

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center py-5">

                                <div class="text-muted">

                                    <i class="ri-list-check-3 fs-1 d-block mb-2"></i>

                                    No requirements found.

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}

        @if($requirements->hasPages())

            <div class="card-footer bg-white">

                {{ $requirements->links() }}

            </div>

        @endif

    </div>

</div>


{{-- ================================================================
     ADD REQUIREMENT MODAL
================================================================ --}}

<div
    class="modal fade"
    id="addRequirementModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.handover.requirements.store',
                    $project
                ) }}">

                @csrf


                <div class="modal-header">

                    <h5 class="modal-title">
                        Add Handover Requirement
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="row g-3">


                        <div class="col-md-4">

                            <label class="form-label">
                                Requirement Code
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="requirement_code"
                                class="form-control"
                                value="{{ old('requirement_code') }}"
                                placeholder="e.g. DOC-001"
                                required>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Requirement Type
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="requirement_type"
                                class="form-select"
                                required>

                                <option value="">
                                    Select Type
                                </option>

                                @foreach($types as $type)

                                    <option
                                        value="{{ $type }}"
                                        @selected(
                                            old('requirement_type') === $type
                                        )>

                                        {{ $type }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Priority
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="priority"
                                class="form-select"
                                required>

                                @foreach($priorities as $priority)

                                    <option
                                        value="{{ $priority }}"
                                        @selected(
                                            old(
                                                'priority',
                                                'Medium'
                                            ) === $priority
                                        )>

                                        {{ $priority }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Requirement Title
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="title"
                                class="form-control"
                                value="{{ old('title') }}"
                                placeholder="Enter requirement title"
                                required>

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea
                                name="description"
                                rows="3"
                                class="form-control"
                                placeholder="Describe the requirement...">{{ old('description') }}</textarea>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Source Module
                            </label>

                            <input
                                type="text"
                                name="source_module"
                                class="form-control"
                                value="{{ old('source_module') }}"
                                placeholder="e.g. Commissioning">

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Source ID
                            </label>

                            <input
                                type="number"
                                name="source_id"
                                class="form-control"
                                value="{{ old('source_id') }}"
                                placeholder="Optional source record ID">

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Responsible User
                            </label>

                            <select
                                name="responsible_user_id"
                                class="form-select">

                                <option value="">
                                    Not Assigned
                                </option>

                                @foreach($users as $user)

                                    <option
                                        value="{{ $user->id }}"
                                        @selected(
                                            old('responsible_user_id')
                                            == $user->id
                                        )>

                                        {{ $user->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Due Date
                            </label>

                            <input
                                type="date"
                                name="due_date"
                                class="form-control"
                                value="{{ old('due_date') }}">

                        </div>


                        <div class="col-md-4">

                            <label class="form-label d-block">
                                Requirement
                            </label>

                            <div class="form-check mt-2">

                                <input
                                    type="checkbox"
                                    name="is_mandatory"
                                    value="1"
                                    class="form-check-input"
                                    id="isMandatory"
                                    @checked(
                                        old(
                                            'is_mandatory',
                                            true
                                        )
                                    )>

                                <label
                                    class="form-check-label"
                                    for="isMandatory">

                                    Mandatory Requirement

                                </label>

                            </div>

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Remarks
                            </label>

                            <textarea
                                name="remarks"
                                rows="2"
                                class="form-control"
                                placeholder="Additional remarks...">{{ old('remarks') }}</textarea>

                        </div>

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

                        <i class="ri-save-line me-1"></i>

                        Save Requirement

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ================================================================
     AUTO OPEN ADD MODAL ON VALIDATION ERROR
================================================================ --}}

@if($errors->any())

    @push('scripts')

        <script>

            document.addEventListener(
                'DOMContentLoaded',
                function () {

                    const modalElement =
                        document.getElementById(
                            'addRequirementModal'
                        );

                    if (modalElement) {

                        const modal =
                            new bootstrap.Modal(
                                modalElement
                            );

                        modal.show();

                    }

                }
            );

        </script>

    @endpush

@endif

@endsection