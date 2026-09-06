@extends('layouts.app')

@section('title', 'Development Projects')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Development Projects
            </h3>

            <p class="text-muted mb-0">
                Project Setup & Development Planning
            </p>

        </div>

        <div>

            <a
                href="{{ route('admin.projects.create') }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-lg"></i>
                Create Project
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Messages --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

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

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('info'))

        <div class="alert alert-info alert-dismissible fade show">

            {{ session('info') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Dashboard KPI Cards --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <a
                href="{{ route('admin.projects.index') }}"
                class="text-decoration-none"
            >

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small mb-1">
                                    Total Projects
                                </div>

                                <h3 class="mb-0">
                                    {{ $totalProjects }}
                                </h3>

                            </div>

                            <div class="text-primary fs-3">
                                <i class="bi bi-building"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </a>

        </div>


        {{-- Active --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <a
                href="{{ route('admin.projects.index', ['project_status' => 'Active']) }}"
                class="text-decoration-none"
            >

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small mb-1">
                                    Active
                                </div>

                                <h3 class="mb-0 text-success">
                                    {{ $activeProjects }}
                                </h3>

                            </div>

                            <div class="text-success fs-3">
                                <i class="bi bi-play-circle"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </a>

        </div>


        {{-- Delayed --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <a
                href="{{ route('admin.projects.index', ['project_status' => 'Delayed']) }}"
                class="text-decoration-none"
            >

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small mb-1">
                                    Delayed
                                </div>

                                <h3 class="mb-0 text-danger">
                                    {{ $delayedProjects }}
                                </h3>

                            </div>

                            <div class="text-danger fs-3">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </a>

        </div>


        {{-- On Hold --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <a
                href="{{ route('admin.projects.index', ['project_status' => 'On Hold']) }}"
                class="text-decoration-none"
            >

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small mb-1">
                                    On Hold
                                </div>

                                <h3 class="mb-0 text-warning">
                                    {{ $onHoldProjects }}
                                </h3>

                            </div>

                            <div class="text-warning fs-3">
                                <i class="bi bi-pause-circle"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </a>

        </div>


        {{-- Completed --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <a
                href="{{ route('admin.projects.index', ['project_status' => 'Completed']) }}"
                class="text-decoration-none"
            >

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small mb-1">
                                    Completed
                                </div>

                                <h3 class="mb-0 text-success">
                                    {{ $completedProjects }}
                                </h3>

                            </div>

                            <div class="text-success fs-3">
                                <i class="bi bi-check-circle"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </a>

        </div>


        {{-- Draft --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <a
                href="{{ route('admin.projects.index', ['project_status' => 'Draft']) }}"
                class="text-decoration-none"
            >

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small mb-1">
                                    Draft
                                </div>

                                <h3 class="mb-0 text-secondary">
                                    {{ $draftProjects }}
                                </h3>

                            </div>

                            <div class="text-secondary fs-3">
                                <i class="bi bi-file-earmark"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Secondary Statistics --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        <div class="col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                High Priority Projects
                            </div>

                            <h4 class="mb-0 text-danger">
                                {{ $highPriorityProjects }}
                            </h4>

                        </div>

                        <a
                            href="{{ route('admin.projects.index', ['project_priority' => 'High']) }}"
                            class="btn btn-sm btn-outline-danger"
                        >
                            View High Priority
                        </a>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                Medium Priority Projects
                            </div>

                            <h4 class="mb-0 text-warning">
                                {{ $mediumPriorityProjects }}
                            </h4>

                        </div>

                        <a
                            href="{{ route('admin.projects.index', ['project_priority' => 'Medium']) }}"
                            class="btn btn-sm btn-outline-warning"
                        >
                            View Medium Priority
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Quick Filters --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex flex-wrap align-items-center gap-2">

                <span class="fw-semibold me-2">
                    Quick Filter:
                </span>

                <a
                    href="{{ route('admin.projects.index') }}"
                    class="btn btn-sm
                    {{ !request()->filled('project_status') && !request()->filled('project_priority')
                        ? 'btn-primary'
                        : 'btn-outline-primary' }}"
                >
                    All
                </a>

                <a
                    href="{{ route('admin.projects.index', ['project_status' => 'Active']) }}"
                    class="btn btn-sm
                    {{ request('project_status') === 'Active'
                        ? 'btn-success'
                        : 'btn-outline-success' }}"
                >
                    Active
                </a>

                <a
                    href="{{ route('admin.projects.index', ['project_status' => 'Delayed']) }}"
                    class="btn btn-sm
                    {{ request('project_status') === 'Delayed'
                        ? 'btn-danger'
                        : 'btn-outline-danger' }}"
                >
                    Delayed
                </a>

                <a
                    href="{{ route('admin.projects.index', ['project_status' => 'On Hold']) }}"
                    class="btn btn-sm
                    {{ request('project_status') === 'On Hold'
                        ? 'btn-warning'
                        : 'btn-outline-warning' }}"
                >
                    On Hold
                </a>

                <a
                    href="{{ route('admin.projects.index', ['project_status' => 'Completed']) }}"
                    class="btn btn-sm
                    {{ request('project_status') === 'Completed'
                        ? 'btn-success'
                        : 'btn-outline-success' }}"
                >
                    Completed
                </a>

                <a
                    href="{{ route('admin.projects.index', ['project_priority' => 'High']) }}"
                    class="btn btn-sm
                    {{ request('project_priority') === 'High'
                        ? 'btn-danger'
                        : 'btn-outline-danger' }}"
                >
                    High Priority
                </a>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Filter Form --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    <i class="bi bi-funnel"></i>
                    Filter Projects
                </strong>

                @if(request()->hasAny([
                    'search',
                    'project_stage',
                    'project_status',
                    'project_priority',
                    'project_type',
                    'start_date_from',
                    'start_date_to',
                ]))

                    <span class="badge bg-primary">
                        Filters Applied
                    </span>

                @endif

            </div>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.projects.index') }}"
            >

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-xl-4 col-lg-6">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Project no., code or name"
                        >

                    </div>


                    {{-- Stage --}}
                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label">
                            Stage
                        </label>

                        <select
                            name="project_stage"
                            class="form-select"
                        >

                            <option value="">
                                All Stages
                            </option>

                            @foreach($projectStages as $stage)

                                <option
                                    value="{{ $stage }}"
                                    @selected(request('project_stage') === $stage)
                                >
                                    {{ $stage }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="project_status"
                            class="form-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            @foreach($projectStatuses as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(request('project_status') === $status)
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Priority --}}
                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label">
                            Priority
                        </label>

                        <select
                            name="project_priority"
                            class="form-select"
                        >

                            <option value="">
                                All Priorities
                            </option>

                            @foreach($projectPriorities as $priority)

                                <option
                                    value="{{ $priority }}"
                                    @selected(request('project_priority') === $priority)
                                >
                                    {{ $priority }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Type --}}
                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label">
                            Project Type
                        </label>

                        <select
                            name="project_type"
                            class="form-select"
                        >

                            <option value="">
                                All Types
                            </option>

                            @foreach($projectTypes as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(request('project_type') === $type)
                                >
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Start From --}}
                    <div class="col-xl-3 col-lg-4 col-md-6">

                        <label class="form-label">
                            Start Date From
                        </label>

                        <input
                            type="date"
                            name="start_date_from"
                            class="form-control"
                            value="{{ request('start_date_from') }}"
                        >

                    </div>


                    {{-- Start To --}}
                    <div class="col-xl-3 col-lg-4 col-md-6">

                        <label class="form-label">
                            Start Date To
                        </label>

                        <input
                            type="date"
                            name="start_date_to"
                            class="form-control"
                            value="{{ request('start_date_to') }}"
                        >

                    </div>


                    {{-- Buttons --}}
                    <div class="col-xl-6 col-lg-4 col-md-12">

                        <label class="form-label d-block">
                            &nbsp;
                        </label>

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="bi bi-search"></i>
                                Apply Filter
                            </button>

                            <a
                                href="{{ route('admin.projects.index') }}"
                                class="btn btn-outline-secondary"
                            >
                                <i class="bi bi-arrow-counterclockwise"></i>
                                Reset
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Project Register --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Project Register
                    </strong>

                    <div class="small text-muted mt-1">

                        Showing
                        {{ $projects->firstItem() ?? 0 }}
                        -
                        {{ $projects->lastItem() ?? 0 }}

                        of

                        {{ $projects->total() }}

                        projects

                    </div>

                </div>

                @if(
                    request()->filled('search') ||
                    request()->filled('project_stage') ||
                    request()->filled('project_status') ||
                    request()->filled('project_priority') ||
                    request()->filled('project_type') ||
                    request()->filled('start_date_from') ||
                    request()->filled('start_date_to')
                )

                    <a
                        href="{{ route('admin.projects.index') }}"
                        class="btn btn-sm btn-outline-secondary"
                    >
                        Clear Filters
                    </a>

                @endif

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0 align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Project No.
                            </th>

                            <th>
                                Project Name
                            </th>

                            <th>
                                Land
                            </th>

                            <th>
                                Investment Decision
                            </th>

                            <th>
                                Stage
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Priority
                            </th>

                            <th>
                                Start Date
                            </th>

                            <th style="width:220px;">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($projects as $project)

                        <tr>

                            {{-- Project Number --}}
                            <td>

                                <strong>
                                    {{ $project->project_number }}
                                </strong>

                                @if($project->project_code)

                                    <div class="small text-muted">
                                        {{ $project->project_code }}
                                    </div>

                                @endif

                            </td>


                            {{-- Project Name --}}
                            <td>

                                <strong>
                                    {{ $project->project_name }}
                                </strong>

                                @if($project->project_type)

                                    <div class="small text-muted">
                                        {{ $project->project_type }}
                                    </div>

                                @endif

                            </td>


                            {{-- Land --}}
                            <td>

                                @if($project->land)

                                    {{ $project->land->land_name
                                        ?? $project->land->name
                                        ?? 'Land #' . $project->land_id }}

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Investment Decision --}}
                            <td>

                                @if($project->investmentDecision)

                                    {{
                                        $project
                                            ->investmentDecision
                                            ->decision_number
                                        ?? '-'
                                    }}

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Stage --}}
                            <td>

                                <span class="badge bg-info text-dark">
                                    {{ $project->project_stage }}
                                </span>

                            </td>


                            {{-- Status --}}
                            <td>

                                @switch($project->project_status)

                                    @case('Draft')

                                        <span class="badge bg-secondary">
                                            Draft
                                        </span>

                                        @break

                                    @case('Active')

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                        @break

                                    @case('On Hold')

                                        <span class="badge bg-warning text-dark">
                                            On Hold
                                        </span>

                                        @break

                                    @case('Delayed')

                                        <span class="badge bg-danger">
                                            Delayed
                                        </span>

                                        @break

                                    @case('Completed')

                                        <span class="badge bg-success">
                                            Completed
                                        </span>

                                        @break

                                    @case('Cancelled')

                                        <span class="badge bg-danger">
                                            Cancelled
                                        </span>

                                        @break

                                    @default

                                        <span class="badge bg-secondary">
                                            {{ $project->project_status }}
                                        </span>

                                @endswitch

                            </td>


                            {{-- Priority --}}
                            <td>

                                @if($project->project_priority)

                                    @if(
                                        $project->project_priority === 'High' ||
                                        $project->project_priority === 'Critical'
                                    )

                                        <span class="badge bg-danger">
                                            {{ $project->project_priority }}
                                        </span>

                                    @elseif(
                                        $project->project_priority === 'Medium'
                                    )

                                        <span class="badge bg-warning text-dark">
                                            Medium
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            {{ $project->project_priority }}
                                        </span>

                                    @endif

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Start Date --}}
                            <td>

                                @if($project->project_start_date)

                                    {{
                                        $project
                                            ->project_start_date
                                            ->format('d M Y')
                                    }}

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="d-flex gap-1 flex-wrap">

                                    <a
                                        href="{{ route(
                                            'admin.projects.show',
                                            [
                                                'project' => $project->id,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>


                                    <a
                                        href="{{ route(
                                            'admin.projects.edit',
                                            [
                                                'project' => $project->id,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-secondary"
                                    >
                                        Edit
                                    </a>


                                    @if(
                                        in_array(
                                            $project->project_status,
                                            [
                                                'Draft',
                                                'Cancelled'
                                            ],
                                            true
                                        )
                                    )

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.projects.destroy',
                                                [
                                                    'project' => $project->id,
                                                ]
                                            ) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this project?');"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                            >
                                                Delete
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="9"
                                class="text-center py-5"
                            >

                                <div class="mb-3">

                                    <i
                                        class="bi bi-building"
                                        style="font-size:40px;"
                                    ></i>

                                </div>

                                <div class="text-muted mb-3">

                                    No development projects found.

                                </div>

                                @if(
                                    request()->filled('search') ||
                                    request()->filled('project_stage') ||
                                    request()->filled('project_status') ||
                                    request()->filled('project_priority') ||
                                    request()->filled('project_type') ||
                                    request()->filled('start_date_from') ||
                                    request()->filled('start_date_to')
                                )

                                    <a
                                        href="{{ route('admin.projects.index') }}"
                                        class="btn btn-outline-secondary btn-sm"
                                    >
                                        Clear Filters
                                    </a>

                                @else

                                    <a
                                        href="{{ route('admin.projects.create') }}"
                                        class="btn btn-primary btn-sm"
                                    >
                                        + Create Project
                                    </a>

                                @endif

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Pagination --}}
        {{-- ===================================================== --}}

        @if($projects->hasPages())

            <div class="card-footer bg-white">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div class="small text-muted">

                        Page
                        {{ $projects->currentPage() }}
                        of
                        {{ $projects->lastPage() }}

                    </div>

                    <div>

                        {{ $projects->links() }}

                    </div>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection