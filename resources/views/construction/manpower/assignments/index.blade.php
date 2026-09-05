@extends('layouts.app')

@section('title', 'Manpower Assignments')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Manpower Assignments
            </h4>

            <div class="text-muted">
                {{ $project->project_number }}
                -
                {{ $project->project_name }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.manpower.assignments.create',
                $project
            ) }}"
               class="btn btn-success">

                + Assign Manpower

            </a>

            <a href="{{ route(
                'admin.projects.construction.manpower.index',
                $project
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-people"></i>
                Manpower

            </a>

            <a href="{{ url(
                '/admin/projects/' .
                $project->id .
                '/construction'
            ) }}"
               class="btn btn-outline-secondary">

                ← Construction Dashboard

            </a>

        </div>

    </div>


    {{-- Alerts --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif

    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    {{-- Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <div class="text-muted small">
                        Total Assignments
                    </div>

                    <h3 class="mb-0">
                        {{ $summary['total'] }}
                    </h3>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <div class="text-muted small">
                        Planned
                    </div>

                    <h3 class="mb-0 text-warning">
                        {{ $summary['planned'] }}
                    </h3>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <div class="text-muted small">
                        Active
                    </div>

                    <h3 class="mb-0 text-success">
                        {{ $summary['active'] }}
                    </h3>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <div class="text-muted small">
                        Released
                    </div>

                    <h3 class="mb-0 text-secondary">
                        {{ $summary['released'] }}
                    </h3>

                </div>
            </div>
        </div>

    </div>


    {{-- Filters --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Assignment no, manpower, trade..."
                        >

                    </div>

                    <div class="col-md-2">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status" class="form-select">

                            <option value="">
                                All
                            </option>

                            @foreach([
                                'Planned',
                                'Active',
                                'Released',
                                'Cancelled'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(request('status') === $status)
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-2">

                        <label class="form-label">
                            From Date
                        </label>

                        <input
                            type="date"
                            name="from_date"
                            class="form-control"
                            value="{{ request('from_date') }}"
                        >

                    </div>

                    <div class="col-md-2">

                        <label class="form-label">
                            To Date
                        </label>

                        <input
                            type="date"
                            name="to_date"
                            class="form-control"
                            value="{{ request('to_date') }}"
                        >

                    </div>

                    <div class="col-md-2 d-flex align-items-end">

                        <button class="btn btn-primary w-100">
                            Filter
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Table --}}
    <div class="card shadow-sm border-0">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>Assignment No.</th>

                            <th>Manpower</th>

                            <th>Type / Trade</th>

                            <th>Work Order</th>

                            <th>Role</th>

                            <th>Assignment Date</th>

                            <th>Rate / Day</th>

                            <th>Status</th>

                            <th width="100">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($assignments as $assignment)

                            <tr>

                                <td>

                                    <strong>
                                        {{ $assignment->assignment_number }}
                                    </strong>

                                </td>

                                <td>

                                    @if($assignment->manpower)

                                        <div>
                                            {{ $assignment->manpower->manpower_name }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $assignment->manpower->manpower_code }}
                                        </small>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    {{ $assignment->manpower?->manpower_type ?? '—' }}

                                    @if($assignment->manpower?->trade)

                                        <br>

                                        <small class="text-muted">
                                            {{ $assignment->manpower->trade }}
                                        </small>

                                    @endif

                                </td>

                                <td>

                                    {{ $assignment->workOrder?->work_order_number ?? '—' }}

                                </td>

                                <td>
                                    {{ $assignment->role ?: '—' }}
                                </td>

                                <td>

                                    {{ optional(
                                        $assignment->assignment_date
                                    )->format('d M Y') }}

                                </td>

                                <td>

                                    ${{ number_format(
                                        $assignment->daily_rate ?? 0,
                                        2
                                    ) }}

                                </td>

                                <td>

                                    @php

                                        $badge = match(
                                            $assignment->status
                                        ) {

                                            'Planned' =>
                                                'bg-warning text-dark',

                                            'Active' =>
                                                'bg-success',

                                            'Released' =>
                                                'bg-secondary',

                                            'Cancelled' =>
                                                'bg-danger',

                                            default =>
                                                'bg-light text-dark',
                                        };

                                    @endphp

                                    <span class="badge {{ $badge }}">
                                        {{ $assignment->status }}
                                    </span>

                                </td>

                                <td>

                                    <a href="{{ route(
                                        'admin.projects.construction.manpower.assignments.show',
                                        [
                                            'project' => $project->id,
                                            'assignment' => $assignment->id
                                        ]
                                    ) }}"
                                       class="btn btn-sm btn-outline-primary">

                                        View

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center py-5 text-muted"
                                >

                                    No manpower assignments found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if($assignments->hasPages())

            <div class="card-footer bg-white">

                {{ $assignments->links() }}

            </div>

        @endif

    </div>

</div>

@endsection