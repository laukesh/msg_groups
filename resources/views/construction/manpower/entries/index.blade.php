@extends('layouts.app')

@section('title', 'Daily Manpower Entries')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Daily Manpower Entries
            </h4>

            <div class="text-muted">
                {{ $project->project_number }}
                -
                {{ $project->project_name }}
            </div>
        </div>

        <div class="d-flex gap-2">

            {{-- Manpower --}}
            <a href="{{ route(
                'admin.projects.construction.manpower.index',
                $project
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-people"></i>
                Manpower

            </a>

            {{-- Assignments --}}
            <a href="{{ route(
                'admin.projects.construction.manpower.assignments.index',
                $project
            ) }}"
               class="btn btn-outline-primary">

                <i class="bi bi-person-check"></i>
                Assignments

            </a>

            {{-- Add Entry --}}
            <a href="{{ route(
                'admin.projects.construction.manpower.entries.create',
                $project
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-lg"></i>
                Daily Entry

            </a>

        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Entries
                    </div>

                    <div class="fs-4 fw-bold">
                        {{ $summary['total_entries'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Present
                    </div>

                    <div class="fs-4 fw-bold text-success">
                        {{ $summary['present'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Hours
                    </div>

                    <div class="fs-4 fw-bold">
                        {{ number_format($summary['total_hours'], 2) }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Labour Cost
                    </div>

                    <div class="fs-4 fw-bold">
                        ${{ number_format($summary['total_cost'], 2) }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Filters --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0">
                Filters
            </h6>

        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route(
                      'admin.projects.construction.manpower.entries.index',
                      $project
                  ) }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Entry no / manpower">

                    </div>


                    {{-- Manpower --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Manpower
                        </label>

                        <select name="manpower_id"
                                class="form-select">

                            <option value="">
                                All Manpower
                            </option>

                            @foreach($manpower as $person)

                                <option value="{{ $person->id }}"
                                    {{ request('manpower_id') == $person->id ? 'selected' : '' }}>

                                    {{ $person->manpower_code }}
                                    -
                                    {{ $person->manpower_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Attendance --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            Attendance
                        </label>

                        <select name="attendance_status"
                                class="form-select">

                            <option value="">
                                All
                            </option>

                            <option value="Present"
                                {{ request('attendance_status') == 'Present' ? 'selected' : '' }}>
                                Present
                            </option>

                            <option value="Absent"
                                {{ request('attendance_status') == 'Absent' ? 'selected' : '' }}>
                                Absent
                            </option>

                            <option value="Half Day"
                                {{ request('attendance_status') == 'Half Day' ? 'selected' : '' }}>
                                Half Day
                            </option>

                            <option value="Leave"
                                {{ request('attendance_status') == 'Leave' ? 'selected' : '' }}>
                                Leave
                            </option>

                        </select>

                    </div>


                    {{-- From Date --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            From Date
                        </label>

                        <input type="date"
                               name="from_date"
                               class="form-control"
                               value="{{ request('from_date') }}">

                    </div>


                    {{-- To Date --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            To Date
                        </label>

                        <input type="date"
                               name="to_date"
                               class="form-control"
                               value="{{ request('to_date') }}">

                    </div>


                    {{-- Buttons --}}
                    <div class="col-12">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="bi bi-search"></i>
                            Filter

                        </button>

                        <a href="{{ route(
                            'admin.projects.construction.manpower.entries.index',
                            $project
                        ) }}"
                           class="btn btn-outline-secondary">

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Entries --}}
    <div class="card shadow-sm">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            <h6 class="mb-0">
                Daily Entries
            </h6>

            <span class="text-muted small">
                {{ $entries->total() }} records
            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Entry No.
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Manpower
                            </th>

                            <th>
                                Assignment
                            </th>

                            <th>
                                Work Order
                            </th>

                            <th>
                                Attendance
                            </th>

                            <th class="text-end">
                                Hours
                            </th>

                            <th class="text-end">
                                Cost
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($entries as $entry)

                            <tr>

                                {{-- Entry Number --}}
                                <td>

                                    <div class="fw-semibold">
                                        {{ $entry->entry_number }}
                                    </div>

                                </td>


                                {{-- Date --}}
                                <td>

                                    {{ optional(
                                        $entry->entry_date
                                    )->format('d M Y') }}

                                </td>


                                {{-- Manpower --}}
                                <td>

                                    @if($entry->manpower)

                                        <div class="fw-semibold">
                                            {{ $entry->manpower->manpower_name }}
                                        </div>

                                        <div class="text-muted small">
                                            {{ $entry->manpower->manpower_code }}

                                            @if($entry->manpower->trade)
                                                · {{ $entry->manpower->trade }}
                                            @endif
                                        </div>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Assignment --}}
                                <td>

                                    @if($entry->assignment)

                                        <a href="{{ route(
                                            'admin.projects.construction.manpower.assignments.show',
                                            [
                                                'project' => $project,
                                                'assignment' => $entry->assignment
                                            ]
                                        ) }}"
                                           class="text-decoration-none">

                                            {{ $entry->assignment->assignment_number }}

                                        </a>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Work Order --}}
                                <td>

                                    @if($entry->workOrder)

                                        {{ $entry->workOrder->work_order_number }}

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Attendance --}}
                                <td>

                                    @if($entry->attendance_status === 'Present')

                                        <span class="badge bg-success">
                                            Present
                                        </span>

                                    @elseif($entry->attendance_status === 'Absent')

                                        <span class="badge bg-danger">
                                            Absent
                                        </span>

                                    @elseif($entry->attendance_status === 'Half Day')

                                        <span class="badge bg-warning text-dark">
                                            Half Day
                                        </span>

                                    @elseif($entry->attendance_status === 'Leave')

                                        <span class="badge bg-secondary">
                                            Leave
                                        </span>

                                    @endif

                                </td>


                                {{-- Hours --}}
                                <td class="text-end">

                                    <div class="fw-semibold">

                                        {{ number_format(
                                            $entry->total_hours,
                                            2
                                        ) }}

                                    </div>

                                    <div class="text-muted small">

                                        Reg:
                                        {{ number_format(
                                            $entry->regular_hours,
                                            2
                                        ) }}

                                        · OT:
                                        {{ number_format(
                                            $entry->overtime_hours,
                                            2
                                        ) }}

                                    </div>

                                </td>


                                {{-- Cost --}}
                                <td class="text-end">

                                    ${{ number_format(
                                        $entry->total_cost,
                                        2
                                    ) }}

                                </td>


                                {{-- Action --}}
                                <td class="text-end">

                                    <a href="{{ route(
                                        'admin.projects.construction.manpower.entries.show',
                                        [
                                            'project' => $project,
                                            'entry' => $entry
                                        ]
                                    ) }}"
                                       class="btn btn-sm btn-outline-primary">

                                        View

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>

                                        No daily manpower entries found.

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($entries->hasPages())

            <div class="card-footer bg-white">

                {{ $entries->links() }}

            </div>

        @endif

    </div>

</div>

@endsection