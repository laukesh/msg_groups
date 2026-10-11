@extends('layouts.app')

@section('title', 'Snagging / Punch List')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        HEADER
    ============================================================ --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Snagging / Punch List
            </h4>

            <div class="text-muted">
                {{ $project->project_code ?? '' }}
                -
                {{ $project->project_name ?? $project->name ?? '' }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
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
                data-bs-target="#addSnagModal">

                <i class="ri-add-line"></i>
                Add Snag

            </button>

        </div>

    </div>


    {{-- ============================================================
        ALERTS
    ============================================================ --}}

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

            <strong>Please fix the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- ============================================================
        KPI CARDS
    ============================================================ --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Snags
                    </div>

                    <h3 class="mb-0 mt-1">
                        {{ $totalSnags }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Open
                    </div>

                    <h3 class="mb-0 mt-1 text-warning">
                        {{ $openSnags }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Closed
                    </div>

                    <h3 class="mb-0 mt-1 text-success">
                        {{ $closedSnags }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Critical
                    </div>

                    <h3 class="mb-0 mt-1 text-danger">
                        {{ $criticalSnags }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Verification
                    </div>

                    <h3 class="mb-0 mt-1 text-info">
                        {{ $verificationSnags }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Closure
                    </div>

                    <h3 class="mb-0 mt-1">
                        {{ number_format($closurePercentage, 2) }}%
                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        PROGRESS
    ============================================================ --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between mb-2">

                <span class="fw-semibold">
                    Snag Closure Progress
                </span>

                <span>
                    {{ $closedSnags }} / {{ $totalSnags }}
                </span>

            </div>

            <div class="progress" style="height: 8px;">

                <div
                    class="progress-bar"
                    role="progressbar"
                    style="width: {{ $closurePercentage }}%">
                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        FILTERS
    ============================================================ --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3">

                    <div class="col-lg-3">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Snag no, title, location...">

                    </div>


                    <div class="col-lg-2">

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
                                    @selected(request('status') === $status)>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-2">

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
                                    @selected(request('priority') === $priority)>

                                    {{ $priority }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-2">

                        <label class="form-label">
                            Category
                        </label>

                        <select
                            name="category"
                            class="form-select">

                            <option value="">
                                All Categories
                            </option>

                            @foreach($categories as $category)

                                <option
                                    value="{{ $category }}"
                                    @selected(request('category') === $category)>

                                    {{ $category }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-2">

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
                                    @selected(request('discipline') === $discipline)>

                                    {{ $discipline }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-1 d-flex align-items-end">

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


    {{-- ============================================================
        TABLE
    ============================================================ --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="mb-0">
                        Snag / Punch List
                    </h5>

                    <small class="text-muted">
                        Handover:
                        {{ $handover->handover_no }}
                    </small>
                </div>

                <span class="badge bg-light text-dark">
                    {{ $snags->total() }} Records
                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th width="120">
                            Snag No.
                        </th>

                        <th>
                            Snag
                        </th>

                        <th>
                            Location
                        </th>

                        <th>
                            Procurement Contract
                        </th>

                        <th>
                            Priority
                        </th>

                        <th>
                            Due Date
                        </th>

                        <th>
                            Assigned To
                        </th>

                        <th>
                            Status
                        </th>

                        <th width="70">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse($snags as $snag)

                    <tr>

                        {{-- Snag No --}}

                        <td>

                            <span class="fw-semibold">
                                {{ $snag->snag_no }}
                            </span>

                            @if($snag->category)

                                <div class="small text-muted">
                                    {{ $snag->category }}
                                </div>

                            @endif

                        </td>


                        {{-- Snag --}}

                        <td>

                            <div class="fw-semibold">
                                {{ $snag->title }}
                            </div>

                            @if($snag->discipline)

                                <span class="badge bg-light text-dark mt-1">
                                    {{ $snag->discipline }}
                                </span>

                            @endif

                            @if($snag->description)

                                <div class="small text-muted mt-1">
                                    {{ \Illuminate\Support\Str::limit(
                                        $snag->description,
                                        80
                                    ) }}
                                </div>

                            @endif

                        </td>


                        {{-- Location --}}

                        <td>

                            @if($snag->location)
                                <div>
                                    {{ $snag->location }}
                                </div>
                            @endif

                            @if($snag->building)
                                <small class="text-muted">
                                    Building: {{ $snag->building }}
                                </small>
                            @endif

                            @if($snag->floor)
                                <small class="text-muted d-block">
                                    Floor: {{ $snag->floor }}
                                </small>
                            @endif

                            @if($snag->zone)
                                <small class="text-muted d-block">
                                    Zone: {{ $snag->zone }}
                                </small>
                            @endif

                            @if($snag->unit)
                                <small class="text-muted d-block">
                                    Unit: {{ $snag->unit }}
                                </small>
                            @endif

                            @if(
                                !$snag->location &&
                                !$snag->building &&
                                !$snag->floor &&
                                !$snag->zone &&
                                !$snag->unit
                            )
                                -
                            @endif

                        </td>


                        {{-- Procurement Contract --}}

                        <td>

                            @if($snag->procurementContract)

                                <div class="fw-semibold">

                                    {{ $snag->procurementContract->contract_number }}

                                </div>

                                @if($snag->procurementContract->contract_title)

                                    <div class="small text-muted">

                                        {{ $snag->procurementContract->contract_title }}

                                    </div>

                                @endif

                                @if($snag->procurementContract->bidder_name)

                                    <div class="small">

                                        <i class="ri-building-line"></i>

                                        {{ $snag->procurementContract->bidder_name }}

                                    </div>

                                @endif

                            @else

                                <span class="text-muted">
                                    Not linked
                                </span>

                            @endif

                        </td>


                        {{-- Priority --}}

                        <td>

                            @php

                                $priorityClass = match($snag->priority) {

                                    'Critical' => 'bg-danger',

                                    'High' => 'bg-warning text-dark',

                                    'Medium' => 'bg-info',

                                    default => 'bg-secondary',

                                };

                            @endphp

                            <span class="badge {{ $priorityClass }}">
                                {{ $snag->priority }}
                            </span>

                        </td>


                        {{-- Due Date --}}

                        <td>

                            @if($snag->due_date)

                                <span class="

                                    @if(
                                        $snag->due_date->isPast() &&
                                        $snag->status !== 'Closed'
                                    )
                                        text-danger fw-semibold
                                    @endif

                                ">

                                    {{ $snag->due_date->format('d M Y') }}

                                </span>

                                @if(
                                    $snag->due_date->isPast() &&
                                    $snag->status !== 'Closed'
                                )

                                    <div class="small text-danger">
                                        Overdue
                                    </div>

                                @endif

                            @else

                                -

                            @endif

                        </td>


                        {{-- Assigned --}}

                        <td>

                            {{ $snag->assignedTo?->name ?? '-' }}

                        </td>


                        {{-- Status --}}

                        <td>

                            @php

                                $statusClass = match($snag->status) {

                                    'Open' =>
                                        'bg-secondary',

                                    'Assigned' =>
                                        'bg-primary',

                                    'In Progress' =>
                                        'bg-warning text-dark',

                                    'Rectification Submitted' =>
                                        'bg-info',

                                    'Under Verification' =>
                                        'bg-dark',

                                    'Rejected' =>
                                        'bg-danger',

                                    'Closed' =>
                                        'bg-success',

                                    default =>
                                        'bg-secondary',

                                };

                            @endphp

                            <span class="badge {{ $statusClass }}">
                                {{ $snag->status }}
                            </span>

                        </td>


                        {{-- Actions --}}

                        <td>

                            <div class="dropdown">

                                <button
                                    class="btn btn-sm btn-light"
                                    type="button"
                                    data-bs-toggle="dropdown">

                                    <i class="ri-more-2-fill"></i>

                                </button>


                                <ul class="dropdown-menu dropdown-menu-end">

                                    @if(in_array($snag->status, [
                                        'Open',
                                        'Assigned',
                                        'Rejected'
                                    ]))

                                        <li>

                                            <button
                                                class="dropdown-item"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editSnagModal{{ $snag->id }}">

                                                <i class="ri-edit-line me-2"></i>
                                                Edit

                                            </button>

                                        </li>

                                    @endif


                                    @if(in_array($snag->status, [
                                        'Open',
                                        'Rejected'
                                    ]))

                                        <li>

                                            <button
                                                class="dropdown-item"
                                                data-bs-toggle="modal"
                                                data-bs-target="#assignSnagModal{{ $snag->id }}">

                                                <i class="ri-user-add-line me-2"></i>
                                                Assign

                                            </button>

                                        </li>

                                    @endif


                                    @if($snag->status === 'Assigned')

                                        <li>

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.handover.snags.start',
                                                    [$project, $snag]
                                                ) }}">

                                                @csrf

                                                <button class="dropdown-item">

                                                    <i class="ri-play-line me-2"></i>
                                                    Start Rectification

                                                </button>

                                            </form>

                                        </li>

                                    @endif


                                    @if($snag->status === 'In Progress')

                                        <li>

                                            <button
                                                class="dropdown-item"
                                                data-bs-toggle="modal"
                                                data-bs-target="#rectificationModal{{ $snag->id }}">

                                                <i class="ri-send-plane-line me-2"></i>
                                                Submit Rectification

                                            </button>

                                        </li>

                                    @endif


                                    @if($snag->status === 'Rectification Submitted')

                                        <li>

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.handover.snags.verify',
                                                    [$project, $snag]
                                                ) }}">

                                                @csrf

                                                <button class="dropdown-item">

                                                    <i class="ri-search-eye-line me-2"></i>
                                                    Start Verification

                                                </button>

                                            </form>

                                        </li>

                                    @endif


                                    @if($snag->status === 'Under Verification')

                                        <li>

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.handover.snags.close',
                                                    [$project, $snag]
                                                ) }}">

                                                @csrf

                                                <button
                                                    class="dropdown-item text-success"
                                                    onclick="return confirm('Close this snag after verification?')">

                                                    <i class="ri-checkbox-circle-line me-2"></i>
                                                    Close Snag

                                                </button>

                                            </form>

                                        </li>


                                        <li>

                                            <button
                                                class="dropdown-item text-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#rejectSnagModal{{ $snag->id }}">

                                                <i class="ri-close-circle-line me-2"></i>
                                                Reject Verification

                                            </button>

                                        </li>

                                    @endif


                                    @if(in_array($snag->status, [
                                        'Open',
                                        'Assigned',
                                        'Rejected'
                                    ]))

                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>

                                        <li>

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.handover.snags.destroy',
                                                    [$project, $snag]
                                                ) }}">

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    class="dropdown-item text-danger"
                                                    onclick="return confirm('Delete this snag?')">

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
                            colspan="9"
                            class="text-center py-5">

                            <div class="text-muted">

                                <i
                                    class="ri-checkbox-blank-circle-line fs-1">
                                </i>

                                <h6 class="mt-3">
                                    No snag / punch list items found
                                </h6>

                                <p class="mb-0">
                                    Add a snag to start tracking handover defects.
                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}

        @if($snags->hasPages())

            <div class="card-footer bg-white">

                {{ $snags->links() }}

            </div>

        @endif

    </div>

</div>


{{-- ================================================================
    ADD SNAG MODAL
================================================================ --}}

<div
    class="modal fade"
    id="addSnagModal"
    tabindex="-1">

    <div class="modal-dialog modal-xl">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.handover.snags.store',
                    $project
                ) }}">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        Add Snag / Punch List Item
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    @include(
                        'admin.handover.snags.partials.form',
                        [
                            'snag' => null,
                            'contracts' => $contracts,
                            'users' => $users,
                            'modalId' => 'addSnagModal'
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
                        Create Snag

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ================================================================
    EDIT / ASSIGN / RECTIFICATION / REJECT MODALS
================================================================ --}}

@foreach($snags as $snag)

    {{-- EDIT --}}

    @if(in_array($snag->status, [
        'Open',
        'Assigned',
        'Rejected'
    ]))

        <div
            class="modal fade"
            id="editSnagModal{{ $snag->id }}"
            tabindex="-1">

            <div class="modal-dialog modal-xl">

                <div class="modal-content">

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.handover.snags.update',
                            [$project, $snag]
                        ) }}">

                        @csrf
                        @method('PUT')

                        <div class="modal-header">

                            <h5 class="modal-title">
                                Edit Snag
                                -
                                {{ $snag->snag_no }}
                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            @include(
                                'admin.handover.snags.partials.form',
                                [
                                    'snag' => $snag,
                                    'contracts' => $contracts,
                                    'users' => $users,
                                    'modalId' => 'editSnagModal'.$snag->id
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
                                Update Snag

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endif


    {{-- ASSIGN --}}

    @if(in_array($snag->status, [
        'Open',
        'Rejected'
    ]))

        <div
            class="modal fade"
            id="assignSnagModal{{ $snag->id }}"
            tabindex="-1">

            <div class="modal-dialog">

                <div class="modal-content">

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.handover.snags.assign',
                            [$project, $snag]
                        ) }}">

                        @csrf

                        <div class="modal-header">

                            <h5 class="modal-title">
                                Assign Snag
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
                                    Snag
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $snag->snag_no }} - {{ $snag->title }}"
                                    readonly>

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    Assigned To
                                    <span class="text-danger">*</span>
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
                                                $snag->assigned_to == $user->id
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


    {{-- RECTIFICATION --}}

    @if($snag->status === 'In Progress')

        <div
            class="modal fade"
            id="rectificationModal{{ $snag->id }}"
            tabindex="-1">

            <div class="modal-dialog modal-lg">

                <div class="modal-content">

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.handover.snags.submit-rectification',
                            [$project, $snag]
                        ) }}">

                        @csrf

                        <div class="modal-header">

                            <h5 class="modal-title">
                                Submit Rectification
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
                                    <span class="text-danger">*</span>
                                </label>

                                <textarea
                                    name="rectification_details"
                                    class="form-control"
                                    rows="5"
                                    required></textarea>

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    Attachment Path
                                </label>

                                <input
                                    type="text"
                                    name="attachment_path"
                                    class="form-control"
                                    placeholder="Optional file path">

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

                                Submit Rectification

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endif


    {{-- REJECT --}}

    @if($snag->status === 'Under Verification')

        <div
            class="modal fade"
            id="rejectSnagModal{{ $snag->id }}"
            tabindex="-1">

            <div class="modal-dialog">

                <div class="modal-content">

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.handover.snags.reject',
                            [$project, $snag]
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

                            <label class="form-label">
                                Rejection Reason
                                <span class="text-danger">*</span>
                            </label>

                            <textarea
                                name="rejection_reason"
                                class="form-control"
                                rows="4"
                                required></textarea>

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