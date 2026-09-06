@extends('layouts.app')

@section('title', 'Manpower Assignment')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                {{ $assignment->assignment_number }}
            </h4>

            <div class="text-muted">

                {{ $project->project_number }}
                -
                {{ $project->project_name }}

            </div>

        </div>

        <div class="d-flex gap-2">

            @if($assignment->status === 'Planned')

                <a href="{{ route(
                    'admin.projects.construction.manpower.assignments.edit',
                    [
                        'project' => $project->id,
                        'assignment' => $assignment->id
                    ]
                ) }}"
                   class="btn btn-outline-primary">

                    Edit

                </a>

                <form method="POST"
                      action="{{ route(
                          'admin.projects.construction.manpower.assignments.activate',
                          [
                              'project' => $project->id,
                              'assignment' => $assignment->id
                          ]
                      ) }}">

                    @csrf

                    <button class="btn btn-success">
                        Activate
                    </button>

                </form>

                <form method="POST"
                      action="{{ route(
                          'admin.projects.construction.manpower.assignments.cancel',
                          [
                              'project' => $project->id,
                              'assignment' => $assignment->id
                          ]
                      ) }}">

                    @csrf

                    <button class="btn btn-outline-danger">
                        Cancel Assignment
                    </button>

                </form>

            @endif


            @if($assignment->status === 'Active')

                <form method="POST"
                      action="{{ route(
                          'admin.projects.construction.manpower.assignments.release',
                          [
                              'project' => $project->id,
                              'assignment' => $assignment->id
                          ]
                      ) }}">

                    @csrf

                    <input
                        type="hidden"
                        name="release_date"
                        value="{{ now()->format('Y-m-d') }}"
                    >

                    <button class="btn btn-warning">
                        Release
                    </button>

                </form>

            @endif

            <a href="{{ route(
                'admin.projects.construction.manpower.entries.create',
                [
                    'project' => $project,
                    'assignment_id' => $assignment->id
                ]
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-lg"></i>
                Add Daily Entry

            </a>


            <a href="{{ route(
                'admin.projects.construction.manpower.assignments.index',
                $project
            ) }}"
               class="btn btn-outline-secondary">

                ← Back

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

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Manpower
                    </div>

                    <h5 class="mb-1">

                        {{ $assignment->manpower?->manpower_name ?? '—' }}

                    </h5>

                    <small class="text-muted">

                        {{ $assignment->manpower?->manpower_code ?? '—' }}

                    </small>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Assignment Date
                    </div>

                    <h5 class="mb-0">

                        {{ optional(
                            $assignment->assignment_date
                        )->format('d M Y') }}

                    </h5>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Daily Rate
                    </div>

                    <h5 class="mb-0">

                        ${{ number_format(
                            $assignment->daily_rate ?? 0,
                            2
                        ) }}

                    </h5>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Status
                    </div>

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

                    <span class="badge {{ $badge }} mt-2">
                        {{ $assignment->status }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- Assignment Details --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Assignment Details
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Assignment Number
                    </div>

                    <strong>
                        {{ $assignment->assignment_number }}
                    </strong>

                </div>

                <div class="col-md-4">

                    <div class="text-muted small">
                        Manpower Type
                    </div>

                    <strong>
                        {{ $assignment->manpower?->manpower_type ?? '—' }}
                    </strong>

                </div>

                <div class="col-md-4">

                    <div class="text-muted small">
                        Trade
                    </div>

                    <strong>
                        {{ $assignment->manpower?->trade ?? '—' }}
                    </strong>

                </div>

                <div class="col-md-4">

                    <div class="text-muted small">
                        Employment Type
                    </div>

                    <strong>
                        {{ $assignment->manpower?->employment_type ?? '—' }}
                    </strong>

                </div>

                <div class="col-md-4">

                    <div class="text-muted small">
                        Work Order
                    </div>

                    <strong>
                        {{ $assignment->workOrder?->work_order_number ?? '—' }}
                    </strong>

                </div>

                <div class="col-md-4">

                    <div class="text-muted small">
                        Role
                    </div>

                    <strong>
                        {{ $assignment->role ?? '—' }}
                    </strong>

                </div>

                <div class="col-md-4">

                    <div class="text-muted small">
                        Assignment Date
                    </div>

                    <strong>
                        {{ optional(
                            $assignment->assignment_date
                        )->format('d M Y') }}
                    </strong>

                </div>

                <div class="col-md-4">

                    <div class="text-muted small">
                        Release Date
                    </div>

                    <strong>
                        {{ optional(
                            $assignment->release_date
                        )->format('d M Y') ?? '—' }}
                    </strong>

                </div>

                <div class="col-md-4">

                    <div class="text-muted small">
                        Created By
                    </div>

                    <strong>
                        {{ $assignment->creator?->name ?? '—' }}
                    </strong>

                </div>

                <div class="col-md-12">

                    <div class="text-muted small">
                        Remarks
                    </div>

                    <div>
                        {{ $assignment->remarks ?: '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Daily Entries --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white d-flex justify-content-between">

            <h5 class="mb-0">
                Daily Manpower Entries
            </h5>

            @if($assignment->status === 'Active')

                <a href="{{ url(
                    '/admin/projects/' .
                    $project->id .
                    '/construction/manpower/entries/create?assignment_id=' .
                    $assignment->id
                ) }}"
                   class="btn btn-sm btn-success">

                    + Daily Entry

                </a>

            @endif

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>Entry No.</th>

                            <th>Date</th>

                            <th>Attendance</th>

                            <th>Regular Hours</th>

                            <th>Overtime</th>

                            <th>Total Hours</th>

                            <th>Total Cost</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($assignment->entries as $entry)

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
                                        $entry->regular_hours,
                                        2
                                    ) }}
                                </td>

                                <td>
                                    {{ number_format(
                                        $entry->overtime_hours,
                                        2
                                    ) }}
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
                                    colspan="7"
                                    class="text-center py-4 text-muted"
                                >

                                    No daily manpower entries found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection