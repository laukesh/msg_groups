@extends('layouts.app')

@section('title', 'Manpower Details')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Manpower Details
            </h4>

            <div class="text-muted">

                {{ $project->project_number }}
                -
                {{ $project->project_name }}

                <span class="mx-1">|</span>

                {{ $manpower->manpower_code }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.manpower.assignments.index',
                $project
            ) }}"
               class="btn btn-outline-primary">

                <i class="bi bi-person-check"></i>
                Assignments

            </a>

            <a href="{{ route(
                'admin.projects.construction.manpower.edit',
                [
                    'project' => $project,
                    'manpower' => $manpower
                ]
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-pencil"></i>
                Edit

            </a>

            <a href="{{ route(
                'admin.projects.construction.manpower.index',
                $project
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Back to Manpower

            </a>

        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    {{-- Main Information --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <strong>
                Manpower Information
            </strong>

        </div>

        <div class="card-body">

            <div class="row g-4">

                {{-- Code --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Manpower Code
                    </div>

                    <div class="fw-semibold">
                        {{ $manpower->manpower_code }}
                    </div>

                </div>


                {{-- Name --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Name
                    </div>

                    <div class="fw-semibold">
                        {{ $manpower->manpower_name }}
                    </div>

                </div>


                {{-- Type --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Type
                    </div>

                    <div>
                        {{ $manpower->manpower_type }}
                    </div>

                </div>


                {{-- Trade --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Trade
                    </div>

                    <div>
                        {{ $manpower->trade ?? '—' }}
                    </div>

                </div>


                {{-- Employment --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Employment Type
                    </div>

                    <div>
                        {{ $manpower->employment_type }}
                    </div>

                </div>


                {{-- Phone --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Phone
                    </div>

                    <div>
                        {{ $manpower->phone ?? '—' }}
                    </div>

                </div>


                {{-- Joining Date --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Joining Date
                    </div>

                    <div>
                        {{ optional(
                            $manpower->joining_date
                        )->format('d M Y') ?? '—' }}
                    </div>

                </div>


                {{-- Status --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Status
                    </div>

                    @if($manpower->status === 'Active')

                        <span class="badge bg-success">
                            Active
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            Inactive
                        </span>

                    @endif

                </div>


                {{-- Project --}}
                <div class="col-md-6">

                    <div class="text-muted small">
                        Project
                    </div>

                    <div class="fw-semibold">
                        {{ $project->project_number }}
                        -
                        {{ $project->project_name }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Project Assignments --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3 d-flex justify-content-between">

            <strong>
                Project Assignments
            </strong>

            <span class="badge bg-light text-dark">

                {{ $manpower->assignments->count() }}

            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Assignment No.
                            </th>

                            <th>
                                Work Order
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Role
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse(
                            $manpower->assignments
                            as $assignment
                        )

                            <tr>

                                <td>

                                    {{ $assignment->assignment_number }}

                                </td>


                                <td>

                                    {{ $assignment->workOrder?->work_order_number ?? '—' }}

                                </td>


                                <td>

                                    {{ optional(
                                        $assignment->assignment_date
                                    )->format('d M Y') }}

                                </td>


                                <td>

                                    {{ $assignment->role ?? '—' }}

                                </td>


                                <td>

                                    @php

                                        $assignmentBadge = match(
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

                                    <span
                                        class="badge {{ $assignmentBadge }}"
                                    >

                                        {{ $assignment->status }}

                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center py-4 text-muted"
                                >

                                    No assignments found for this project.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Daily Entries --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3 d-flex justify-content-between">

            <strong>
                Daily Manpower Entries
            </strong>

            <span class="badge bg-light text-dark">

                {{ $manpower->entries->count() }}

            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Entry No.
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Attendance
                            </th>

                            <th>
                                Hours
                            </th>

                            <th>
                                Cost
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse(
                            $manpower->entries
                            as $entry
                        )

                            <tr>

                                <td>

                                    {{ $entry->entry_number }}

                                </td>


                                <td>

                                    {{ optional(
                                        $entry->entry_date
                                    )->format('d M Y') }}

                                </td>


                                <td>

                                    {{ $entry->attendance_status }}

                                </td>


                                <td>

                                    {{ number_format(
                                        $entry->total_hours,
                                        2
                                    ) }}

                                </td>


                                <td>

                                    ${{ number_format(
                                        $entry->total_cost,
                                        2
                                    ) }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center py-4 text-muted"
                                >

                                    No daily entries found for this project.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Remarks --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <strong>
                Remarks
            </strong>

        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $manpower->remarks ?? '—'
                )
            ) !!}

        </div>

    </div>

</div>

@endsection