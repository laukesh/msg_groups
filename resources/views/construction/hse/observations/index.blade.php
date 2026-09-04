@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                HSE Observation Register
            </h3>

            <div class="text-muted">
                {{ $project->project_code ?? '' }}
                @if($project->project_code && $project->project_name)
                    -
                @endif
                {{ $project->project_name ?? '' }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.hse.observations.create',
                    [
                        'project' => $project
                    ]
                ) }}"
                class="btn btn-primary"
            >

                <i class="bi bi-plus-lg me-1"></i>

                New Observation

            </a>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}

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


    {{-- =========================================================
        DASHBOARD SUMMARY
    ========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- Total --}}

        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Total Observations
                            </div>

                            <h3 class="mb-0">
                                {{ $summary['total'] }}
                            </h3>

                        </div>

                        <div class="fs-2 text-primary">
                            <i class="bi bi-clipboard-data"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Open --}}

        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Open
                            </div>

                            <h3 class="mb-0">
                                {{ $summary['open'] }}
                            </h3>

                        </div>

                        <div class="fs-2 text-secondary">
                            <i class="bi bi-folder2-open"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- In Progress --}}

        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                In Progress
                            </div>

                            <h3 class="mb-0">
                                {{ $summary['in_progress'] }}
                            </h3>

                        </div>

                        <div class="fs-2 text-warning">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Closed --}}

        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Closed
                            </div>

                            <h3 class="mb-0">
                                {{ $summary['closed'] }}
                            </h3>

                        </div>

                        <div class="fs-2 text-success">
                            <i class="bi bi-check-circle"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        SECONDARY SUMMARY
    ========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- Resolved --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-primary">

                <div class="card-body">

                    <div class="text-muted small">
                        Resolved
                    </div>

                    <h4 class="mb-0">
                        {{ $summary['resolved'] }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Verified --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-success">

                <div class="card-body">

                    <div class="text-muted small">
                        Verified
                    </div>

                    <h4 class="mb-0 text-success">
                        {{ $summary['verified'] }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Critical --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-danger">

                <div class="card-body">

                    <div class="text-muted small">
                        Critical
                    </div>

                    <h4 class="mb-0 text-danger">
                        {{ $summary['critical'] }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Overdue --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-danger">

                <div class="card-body">

                    <div class="text-muted small">
                        Overdue
                    </div>

                    <h4 class="mb-0 text-danger">
                        {{ $summary['overdue'] }}
                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FILTERS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Search & Filters
            </strong>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route(
                    'admin.projects.construction.hse.observations.index',
                    [
                        'project' => $project
                    ]
                ) }}"
            >

                <div class="row g-3 align-items-end">

                    {{-- Search --}}

                    <div class="col-md-5">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Observation no., location, category..."
                        >

                    </div>


                    {{-- Status --}}

                    <div class="col-md-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Statuses
                            </option>

                            @foreach([
                                'Open',
                                'In Progress',
                                'Resolved',
                                'Verified',
                                'Closed'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        request('status') === $status
                                    )
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Severity --}}

                    <div class="col-md-2">

                        <label class="form-label">
                            Severity
                        </label>

                        <select
                            name="severity"
                            class="form-select"
                        >

                            <option value="">
                                All
                            </option>

                            @foreach([
                                'Low',
                                'Medium',
                                'High',
                                'Critical'
                            ] as $severity)

                                <option
                                    value="{{ $severity }}"
                                    @selected(
                                        request('severity') === $severity
                                    )
                                >
                                    {{ $severity }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Buttons --}}

                    <div class="col-md-2">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="fa fa-search"></i>

                            </button>


                            <a
                                href="{{ route(
                                    'admin.projects.construction.hse.observations.index',
                                    [
                                        'project' => $project
                                    ]
                                ) }}"
                                class="btn btn-outline-secondary"
                            >

                                <i class="fa fa-x"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        OBSERVATION REGISTER
    ========================================================== --}}

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Observation Register
                </strong>

                <span class="text-muted small">

                    {{ $observations->total() }}
                    record(s)

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Observation No.
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Location
                            </th>

                            <th>
                                Category
                            </th>

                            <th>
                                Severity
                            </th>

                            <th>
                                Contractor
                            </th>

                            <th>
                                Reported By
                            </th>

                            <th>
                                Due Date
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="90">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse(
                        $observations
                        as $observation
                    )

                        <tr>

                            {{-- Observation Number --}}

                            <td>

                                <strong>

                                    {{ $observation->observation_number }}

                                </strong>

                            </td>


                            {{-- Date --}}

                            <td>

                                {{ $observation->observation_date
                                    ? $observation
                                        ->observation_date
                                        ->format('d-m-Y')
                                    : '-'
                                }}

                            </td>


                            {{-- Location --}}

                            <td>

                                {{ $observation->location }}

                            </td>


                            {{-- Category --}}

                            <td>

                                {{ $observation->category }}

                            </td>


                            {{-- Severity --}}

                            <td>

                                @if(
                                    $observation->severity === 'Critical'
                                )

                                    <span class="badge bg-danger">
                                        Critical
                                    </span>

                                @elseif(
                                    $observation->severity === 'High'
                                )

                                    <span class="badge bg-warning text-dark">
                                        High
                                    </span>

                                @elseif(
                                    $observation->severity === 'Medium'
                                )

                                    <span class="badge bg-info text-dark">
                                        Medium
                                    </span>

                                @else

                                    <span class="badge bg-success">
                                        Low
                                    </span>

                                @endif

                            </td>


                            {{-- Contractor --}}

                            <td>

                                @if($observation->contract)

                                    <div class="fw-semibold">
                                        {{ $observation->contract->bidder_name ?? '—' }}
                                    </div>

                                    <div class="small text-muted">
                                        {{ $observation->contract->contract_number }}
                                    </div>

                                @else

                                    —

                                @endif

                            </td>


                            {{-- Reported By --}}

                            <td>

                                {{ $observation->reporter?->name
                                    ?? $observation->reported_by_name
                                    ?? '-'
                                }}

                            </td>


                            {{-- Due Date --}}

                            <td>

                                @if($observation->due_date)

                                    {{ $observation
                                        ->due_date
                                        ->format('d-m-Y')
                                    }}


                                    @if(
                                        $observation->status !== 'Closed'
                                        &&
                                        $observation->due_date->isPast()
                                    )

                                        <br>

                                        <span class="badge bg-danger">
                                            Overdue
                                        </span>

                                    @endif

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Status --}}

                            <td>

                                @if(
                                    $observation->status === 'Closed'
                                )

                                    <span class="badge bg-dark">
                                        Closed
                                    </span>

                                @elseif(
                                    $observation->status === 'Verified'
                                )

                                    <span class="badge bg-success">
                                        Verified
                                    </span>

                                @elseif(
                                    $observation->status === 'Resolved'
                                )

                                    <span class="badge bg-primary">
                                        Resolved
                                    </span>

                                @elseif(
                                    $observation->status === 'In Progress'
                                )

                                    <span class="badge bg-warning text-dark">
                                        In Progress
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Open
                                    </span>

                                @endif

                            </td>


                            {{-- Action --}}

                            <td>

                                <a
                                    href="{{ route(
                                        'admin.projects.construction.hse.observations.show',
                                        [
                                            'project' => $project,
                                            'observation' => $observation,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary"
                                    title="View Observation"
                                >

                                    <i class="fa fa-eye"></i>

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="10"
                                class="text-center py-5"
                            >

                                <div class="text-muted mb-3">

                                    <i
                                        class="bi bi-clipboard-x fs-2"
                                    ></i>

                                    <br>

                                    No HSE observations found.

                                </div>


                                <a
                                    href="{{ route(
                                        'admin.projects.construction.hse.observations.create',
                                        [
                                            'project' => $project
                                        ]
                                    ) }}"
                                    class="btn btn-primary"
                                >

                                    <i class="bi bi-plus-lg me-1"></i>

                                    Create First Observation

                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
            PAGINATION
        ====================================================== --}}

        @if($observations->hasPages())

            <div class="card-footer">

                {{ $observations->links() }}

            </div>

        @endif

    </div>

</div>

@endsection