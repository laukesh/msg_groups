@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                HSE Incident Register
            </h3>

            <p class="text-muted mb-0">

                {{ $project->project_code ?? '—' }}

                -

                {{ $project->project_name ?? 'Project' }}

            </p>

        </div>


        <div>

            <a
                href="{{ route(
                    'admin.projects.construction.hse.incidents.create',
                    $project
                ) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-circle me-1"></i>
                Report Incident
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
        ERROR MESSAGE
    ========================================================== --}}

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


    {{-- =========================================================
        SUMMARY CARDS
    ========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- Total --}}

        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Total Incidents
                            </div>

                            <h3 class="mb-0">
                                {{ $summary['total'] }}
                            </h3>

                        </div>

                        <div class="text-primary fs-2">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Open / Reported --}}

        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Reported
                    </div>

                    <h3 class="mb-0">
                        {{ $summary['reported'] }}
                    </h3>

                </div>

            </div>

        </div>


        {{-- Investigation --}}

        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Under Investigation
                    </div>

                    <h3 class="mb-0">
                        {{ $summary['under_investigation'] }}
                    </h3>

                </div>

            </div>

        </div>


        {{-- Closed --}}

        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Closed
                    </div>

                    <h3 class="mb-0">
                        {{ $summary['closed'] }}
                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        SECONDARY SUMMARY
    ========================================================== --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card border-danger h-100">

                <div class="card-body">

                    <div class="text-danger small">
                        Critical Incidents
                    </div>

                    <h4 class="mb-0 text-danger">
                        {{ $summary['critical'] }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-warning h-100">

                <div class="card-body">

                    <div class="text-warning small">
                        High Severity
                    </div>

                    <h4 class="mb-0">
                        {{ $summary['high'] }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-info h-100">

                <div class="card-body">

                    <div class="text-info small">
                        Incidents With Injury
                    </div>

                    <h4 class="mb-0">
                        {{ $summary['injuries'] }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-secondary h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Work Stoppage
                    </div>

                    <h4 class="mb-0">
                        {{ $summary['work_stopped'] }}
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
                    'admin.projects.construction.hse.incidents.index',
                    $project
                ) }}"
            >

                <div class="row g-3">

                    {{-- Search --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Incident number, location, type..."
                        >

                    </div>


                    {{-- Status --}}

                    <div class="col-md-2">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All
                            </option>

                            @foreach([
                                'Reported',
                                'Under Investigation',
                                'Investigation Completed',
                                'Actions Assigned',
                                'Actions Completed',
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


                    {{-- Incident Type --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Incident Type
                        </label>

                        <select
                            name="incident_type"
                            class="form-select"
                        >

                            <option value="">
                                All Incident Types
                            </option>

                            @foreach([
                                'Accident',
                                'Near Miss',
                                'First Aid',
                                'Medical Treatment',
                                'Lost Time Injury',
                                'Property Damage',
                                'Fire',
                                'Environmental',
                                'Vehicle',
                                'Equipment',
                                'Other'
                            ] as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(
                                        request('incident_type') === $type
                                    )
                                >
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Date From --}}

                    <div class="col-md-3">

                        <label class="form-label">
                            Date From
                        </label>

                        <input
                            type="date"
                            name="date_from"
                            class="form-control"
                            value="{{ request('date_from') }}"
                        >

                    </div>


                    {{-- Date To --}}

                    <div class="col-md-3">

                        <label class="form-label">
                            Date To
                        </label>

                        <input
                            type="date"
                            name="date_to"
                            class="form-control"
                            value="{{ request('date_to') }}"
                        >

                    </div>


                    {{-- Buttons --}}

                    <div class="col-md-6 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary me-2"
                        >
                            <i class="bi bi-search me-1"></i>
                            Search
                        </button>


                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.incidents.index',
                                $project
                            ) }}"
                            class="btn btn-outline-secondary"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        INCIDENT TABLE
    ========================================================== --}}

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Incident Records
            </strong>

            <span class="text-muted small">

                {{ $incidents->total() }}

                record(s)

            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Incident
                            </th>

                            <th>
                                Date / Time
                            </th>

                            <th>
                                Location
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Severity
                            </th>

                            <th>
                                Contractor
                            </th>

                            <th>
                                Injury
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="150">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse(
                        $incidents
                        as $incident
                    )

                        <tr>

                            {{-- Incident --}}

                            <td>

                                <strong>
                                    {{ $incident->incident_number }}
                                </strong>

                                <div class="small text-muted">

                                    Reported by:

                                    {{ $incident->reported_by_name ?? '—' }}

                                </div>

                            </td>


                            {{-- Date / Time --}}

                            <td>

                                <div>

                                    {{ $incident->incident_date
                                        ? $incident->incident_date->format('d-m-Y')
                                        : '—'
                                    }}

                                </div>

                                @if($incident->incident_time)

                                    <small class="text-muted">

                                        {{ \Carbon\Carbon::parse(
                                            $incident->incident_time
                                        )->format('h:i A') }}

                                    </small>

                                @endif

                            </td>


                            {{-- Location --}}

                            <td>

                                {{ $incident->location }}

                            </td>


                            {{-- Type --}}

                            <td>

                                {{ $incident->incident_type }}

                            </td>


                            {{-- Severity --}}

                            <td>

                                @switch($incident->severity)

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

                                        <span class="badge bg-info text-dark">
                                            Medium
                                        </span>

                                        @break

                                    @default

                                        <span class="badge bg-success">
                                            Low
                                        </span>

                                @endswitch

                            </td>


                            {{-- Contractor --}}

                            <td>

                                {{ $incident->contractor?->company_name
                                    ?? '—'
                                }}

                            </td>


                            {{-- Injury --}}

                            <td>

                                @if($incident->injury_occurred)

                                    <span class="badge bg-danger">

                                        <i class="bi bi-heart-pulse me-1"></i>

                                        Yes

                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        No
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}

                            <td>

                                @switch($incident->status)

                                    @case('Reported')

                                        <span class="badge bg-primary">
                                            Reported
                                        </span>

                                        @break

                                    @case('Under Investigation')

                                        <span class="badge bg-warning text-dark">
                                            Under Investigation
                                        </span>

                                        @break

                                    @case('Investigation Completed')

                                        <span class="badge bg-info text-dark">
                                            Investigation Completed
                                        </span>

                                        @break

                                    @case('Actions Assigned')

                                        <span class="badge bg-secondary">
                                            Actions Assigned
                                        </span>

                                        @break

                                    @case('Actions Completed')

                                        <span class="badge bg-primary">
                                            Actions Completed
                                        </span>

                                        @break

                                    @case('Verified')

                                        <span class="badge bg-success">
                                            Verified
                                        </span>

                                        @break

                                    @case('Closed')

                                        <span class="badge bg-dark">
                                            Closed
                                        </span>

                                        @break

                                    @default

                                        <span class="badge bg-secondary">
                                            {{ $incident->status }}
                                        </span>

                                @endswitch

                            </td>


                            {{-- Actions --}}

                            <td>

                                <a
                                    href="{{ route(
                                        'admin.projects.construction.hse.incidents.show',
                                        [
                                            'project' => $project,
                                            'incident' => $incident
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary"
                                    title="View Incident"
                                >
                                    <i class="fa fa-eye"></i>
                                </a>


                                @if($incident->status !== 'Closed')

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.incidents.edit',
                                            [
                                                'project' => $project,
                                                'incident' => $incident
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-secondary"
                                        title="Edit Incident"
                                    >
                                        <i class="fa fa-edit"></i>
                                    </a>

                                @endif


                                @if($incident->status === 'Reported')

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'admin.projects.construction.hse.incidents.destroy',
                                            [
                                                'project' => $project,
                                                'incident' => $incident
                                            ]
                                        ) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this incident?');"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Delete"
                                        >
                                            <i class="fa fa-trash"></i>
                                        </button>

                                    </form>

                                @endif

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
                                        class="bi bi-exclamation-triangle fs-1 text-muted"
                                    ></i>

                                </div>

                                <h5>
                                    No incidents found
                                </h5>

                                <p class="text-muted mb-3">

                                    No HSE incidents have been
                                    reported for this project.

                                </p>


                                <a
                                    href="{{ route(
                                        'admin.projects.construction.hse.incidents.create',
                                        $project
                                    ) }}"
                                    class="btn btn-primary"
                                >

                                    <i class="bi bi-plus-circle me-1"></i>

                                    Report First Incident

                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}

        @if($incidents->hasPages())

            <div class="card-footer">

                {{ $incidents->links() }}

            </div>

        @endif

    </div>

</div>

@endsection