@extends('layouts.app')

@section('title', 'Daily Manpower Entry')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Daily Manpower Entry
            </h4>

            <div class="text-muted">
                {{ $entry->entry_number }}
                ·
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

            {{-- Assignment --}}
            @if($entry->assignment)

                <a href="{{ route(
                    'admin.projects.construction.manpower.assignments.show',
                    [
                        'project' => $project,
                        'assignment' => $entry->assignment
                    ]
                ) }}"
                   class="btn btn-outline-primary">

                    <i class="bi bi-person-check"></i>
                    Assignment

                </a>

            @endif

            {{-- Edit --}}
            <a href="{{ route(
                'admin.projects.construction.manpower.entries.edit',
                [
                    'project' => $project,
                    'entry' => $entry
                ]
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-pencil"></i>
                Edit

            </a>

            {{-- Back --}}
            <a href="{{ route(
                'admin.projects.construction.manpower.entries.index',
                $project
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Daily Entries

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


    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        {{-- Manpower --}}
        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Manpower
                    </div>

                    @if($entry->manpower)

                        <div class="fw-semibold">
                            {{ $entry->manpower->manpower_name }}
                        </div>

                        <div class="text-muted small">
                            {{ $entry->manpower->manpower_code }}
                        </div>

                    @else

                        <span class="text-muted">
                            —
                        </span>

                    @endif

                </div>

            </div>

        </div>


        {{-- Entry Date --}}
        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Entry Date
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{ optional(
                            $entry->entry_date
                        )->format('d M Y') }}

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Hours --}}
        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Hours
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{ number_format(
                            $entry->total_hours,
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Cost --}}
        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Cost
                    </div>

                    <div class="fs-5 fw-semibold">

                        ${{ number_format(
                            $entry->total_cost,
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Entry Details --}}
    <div class="row g-4">


        {{-- Left --}}
        <div class="col-lg-8">

            {{-- Attendance --}}
            <div class="card shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Attendance & Working Hours
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        {{-- Attendance --}}
                        <div class="col-md-4">

                            <div class="text-muted small">
                                Attendance Status
                            </div>

                            <div class="mt-1">

                                @if($entry->attendance_status === 'Present')

                                    <span class="badge bg-success fs-6">
                                        Present
                                    </span>

                                @elseif($entry->attendance_status === 'Absent')

                                    <span class="badge bg-danger fs-6">
                                        Absent
                                    </span>

                                @elseif($entry->attendance_status === 'Half Day')

                                    <span class="badge bg-warning text-dark fs-6">
                                        Half Day
                                    </span>

                                @elseif($entry->attendance_status === 'Leave')

                                    <span class="badge bg-secondary fs-6">
                                        Leave
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- Regular Hours --}}
                        <div class="col-md-4">

                            <div class="text-muted small">
                                Regular Hours
                            </div>

                            <div class="fs-5 fw-semibold">

                                {{ number_format(
                                    $entry->regular_hours,
                                    2
                                ) }}

                            </div>

                        </div>


                        {{-- Overtime --}}
                        <div class="col-md-4">

                            <div class="text-muted small">
                                Overtime Hours
                            </div>

                            <div class="fs-5 fw-semibold">

                                {{ number_format(
                                    $entry->overtime_hours,
                                    2
                                ) }}

                            </div>

                        </div>


                        {{-- Total --}}
                        <div class="col-md-4">

                            <div class="text-muted small">
                                Total Hours
                            </div>

                            <div class="fs-5 fw-semibold">

                                {{ number_format(
                                    $entry->total_hours,
                                    2
                                ) }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Cost Details --}}
            <div class="card shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Cost Details
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        {{-- Daily Rate --}}
                        <div class="col-md-4">

                            <div class="text-muted small">
                                Daily Rate
                            </div>

                            <div class="fs-5 fw-semibold">

                                ${{ number_format(
                                    $entry->daily_rate,
                                    2
                                ) }}

                            </div>

                        </div>


                        {{-- Overtime Rate --}}
                        <div class="col-md-4">

                            <div class="text-muted small">
                                Overtime Rate / Hour
                            </div>

                            <div class="fs-5 fw-semibold">

                                ${{ number_format(
                                    $entry->overtime_rate,
                                    2
                                ) }}

                            </div>

                        </div>


                        {{-- Total Cost --}}
                        <div class="col-md-4">

                            <div class="text-muted small">
                                Total Cost
                            </div>

                            <div class="fs-5 fw-bold">

                                ${{ number_format(
                                    $entry->total_cost,
                                    2
                                ) }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Work Details --}}
            <div class="card shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Work Details
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        {{-- Work Order --}}
                        <div class="col-md-6">

                            <div class="text-muted small">
                                Work Order
                            </div>

                            @if($entry->workOrder)

                                <div class="fw-semibold">

                                    {{ $entry->workOrder->work_order_number }}

                                </div>

                                @if(!empty($entry->workOrder->work_order_title))

                                    <div class="text-muted small">

                                        {{ $entry->workOrder->work_order_title }}

                                    </div>

                                @endif

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </div>


                        {{-- Assignment --}}
                        <div class="col-md-6">

                            <div class="text-muted small">
                                Manpower Assignment
                            </div>

                            @if($entry->assignment)

                                <a href="{{ route(
                                    'admin.projects.construction.manpower.assignments.show',
                                    [
                                        'project' => $project,
                                        'assignment' => $entry->assignment
                                    ]
                                ) }}"
                                   class="fw-semibold text-decoration-none">

                                    {{ $entry->assignment->assignment_number }}

                                </a>

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </div>


                        {{-- Work Description --}}
                        <div class="col-12">

                            <div class="text-muted small mb-1">
                                Work Description
                            </div>

                            @if($entry->work_description)

                                <div>
                                    {!! nl2br(e($entry->work_description)) !!}
                                </div>

                            @else

                                <span class="text-muted">
                                    No work description provided.
                                </span>

                            @endif

                        </div>


                        {{-- Remarks --}}
                        <div class="col-12">

                            <div class="text-muted small mb-1">
                                Remarks
                            </div>

                            @if($entry->remarks)

                                <div>
                                    {!! nl2br(e($entry->remarks)) !!}
                                </div>

                            @else

                                <span class="text-muted">
                                    No remarks.
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Right --}}
        <div class="col-lg-4">


            {{-- Manpower Card --}}
            <div class="card shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h6 class="mb-0">
                        Manpower
                    </h6>

                </div>


                <div class="card-body">

                    @if($entry->manpower)

                        <div class="mb-3">

                            <div class="text-muted small">
                                Name
                            </div>

                            <div class="fw-semibold">

                                {{ $entry->manpower->manpower_name }}

                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="text-muted small">
                                Code
                            </div>

                            <div>

                                {{ $entry->manpower->manpower_code }}

                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="text-muted small">
                                Type
                            </div>

                            <div>

                                {{ $entry->manpower->manpower_type ?? '—' }}

                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="text-muted small">
                                Trade
                            </div>

                            <div>

                                {{ $entry->manpower->trade ?? '—' }}

                            </div>

                        </div>


                        <a href="{{ route(
                            'admin.projects.construction.manpower.show',
                            [
                                'project' => $project,
                                'manpower' => $entry->manpower
                            ]
                        ) }}"
                           class="btn btn-sm btn-outline-primary w-100">

                            View Manpower

                        </a>

                    @else

                        <span class="text-muted">
                            Manpower record not found.
                        </span>

                    @endif

                </div>

            </div>


            {{-- Assignment Card --}}
            <div class="card shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h6 class="mb-0">
                        Assignment
                    </h6>

                </div>


                <div class="card-body">

                    @if($entry->assignment)

                        <div class="mb-3">

                            <div class="text-muted small">
                                Assignment Number
                            </div>

                            <div class="fw-semibold">

                                {{ $entry->assignment->assignment_number }}

                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="text-muted small">
                                Assignment Date
                            </div>

                            <div>

                                {{ optional(
                                    $entry->assignment->assignment_date
                                )->format('d M Y') }}

                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="text-muted small">
                                Role
                            </div>

                            <div>

                                {{ $entry->assignment->role ?? '—' }}

                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="text-muted small">
                                Assignment Status
                            </div>

                            <div>

                                @if($entry->assignment->status === 'Active')

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @elseif($entry->assignment->status === 'Released')

                                    <span class="badge bg-secondary">
                                        Released
                                    </span>

                                @elseif($entry->assignment->status === 'Cancelled')

                                    <span class="badge bg-danger">
                                        Cancelled
                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">
                                        {{ $entry->assignment->status }}
                                    </span>

                                @endif

                            </div>

                        </div>


                        <a href="{{ route(
                            'admin.projects.construction.manpower.assignments.show',
                            [
                                'project' => $project,
                                'assignment' => $entry->assignment
                            ]
                        ) }}"
                           class="btn btn-sm btn-outline-primary w-100">

                            View Assignment

                        </a>

                    @else

                        <span class="text-muted">
                            Assignment record not found.
                        </span>

                    @endif

                </div>

            </div>


            {{-- Audit --}}
            <div class="card shadow-sm">

                <div class="card-header bg-white">

                    <h6 class="mb-0">
                        Audit Information
                    </h6>

                </div>


                <div class="card-body">

                    <div class="mb-3">

                        <div class="text-muted small">
                            Created By
                        </div>

                        <div>

                            {{ $entry->creator->name ?? 'System' }}

                        </div>

                    </div>


                    <div class="mb-3">

                        <div class="text-muted small">
                            Created At
                        </div>

                        <div>

                            {{ optional(
                                $entry->created_at
                            )->format('d M Y H:i') }}

                        </div>

                    </div>


                    <div>

                        <div class="text-muted small">
                            Last Updated
                        </div>

                        <div>

                            {{ optional(
                                $entry->updated_at
                            )->format('d M Y H:i') }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Delete --}}
    <div class="card shadow-sm mt-4 border-danger">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h6 class="mb-1">
                        Delete Daily Entry
                    </h6>

                    <p class="text-muted mb-0">
                        This will remove the entry from normal daily manpower records.
                    </p>

                </div>


                <form method="POST"
                      action="{{ route(
                          'admin.projects.construction.manpower.entries.destroy',
                          [
                              'project' => $project,
                              'entry' => $entry
                          ]
                      ) }}"
                      onsubmit="return confirm(
                          'Are you sure you want to delete this daily manpower entry?'
                      );">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="btn btn-outline-danger">

                        <i class="bi bi-trash"></i>
                        Delete Entry

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection