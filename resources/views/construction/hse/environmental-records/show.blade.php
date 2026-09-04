@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project:
                <strong>
                    {{ $project->project_code ?? '—' }}
                </strong>
            </div>

            <h3 class="mb-1">
                Environmental Record
            </h3>

            <div class="text-muted">

                Record:
                <strong>
                    {{ $record->record_number }}
                </strong>

                <span class="mx-1">•</span>

                {{ $record->record_title }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.hse.environmental.records.edit',
                    [
                        'project' => $project,
                        'record' => $record,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-pencil me-1"></i>
                Edit
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.environmental.records.index',
                    [
                        'project' => $project,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Records
            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Record Summary --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Record Summary</strong>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Record Number
                    </div>

                    <strong>
                        {{ $record->record_number }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Record Type
                    </div>

                    <strong>
                        {{ $record->record_type }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Monitoring Date
                    </div>

                    <strong>

                        {{ $record->monitoring_date
                            ? $record->monitoring_date->format('d-m-Y')
                            : '—'
                        }}

                        @if($record->monitoring_time)
                            {{ $record->monitoring_time }}
                        @endif

                    </strong>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Location
                    </div>

                    <strong>
                        {{ $record->location ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Monitoring Area
                    </div>

                    <strong>
                        {{ $record->monitoring_area ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Parameter
                    </div>

                    <strong>
                        {{ $record->environmental_parameter ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Measured Value
                    </div>

                    <strong>

                        @if($record->parameter_value !== null)

                            {{ $record->parameter_value }}

                            @if($record->unit)
                                {{ $record->unit }}
                            @endif

                        @else

                            —

                        @endif

                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Permissible Limit
                    </div>

                    <strong>

                        @if($record->limit_value !== null)

                            {{ $record->limit_value }}

                            @if($record->unit)
                                {{ $record->unit }}
                            @endif

                        @else

                            —

                        @endif

                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Compliance --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Compliance</strong>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Compliance Status
                    </div>

                    @switch($record->compliance_status)

                        @case('Compliant')

                            <span class="badge bg-success">
                                Compliant
                            </span>

                            @break

                        @case('Non-Compliant')

                            <span class="badge bg-danger">
                                Non-Compliant
                            </span>

                            @break

                        @case('Pending')

                            <span class="badge bg-warning text-dark">
                                Pending
                            </span>

                            @break

                        @default

                            <span class="badge bg-secondary">
                                {{ $record->compliance_status }}
                            </span>

                    @endswitch

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Record Status
                    </div>

                    <span class="badge bg-secondary">
                        {{ $record->status }}
                    </span>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Weather Condition
                    </div>

                    <strong>
                        {{ $record->weather_condition ?? '—' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Observation --}}

    @if($record->observation)

        <div class="card mb-4">

            <div class="card-header">
                <strong>Observation</strong>
            </div>

            <div
                class="card-body"
                style="white-space:pre-line;"
            >
                {{ $record->observation }}
            </div>

        </div>

    @endif


    {{-- Corrective Action --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Corrective Action</strong>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Corrective Action Required
                    </div>

                    @if($record->corrective_action_required)

                        <span class="badge bg-danger">
                            Yes
                        </span>

                    @else

                        <span class="badge bg-success">
                            No
                        </span>

                    @endif

                </div>


                <div class="col-md-8">

                    <div class="text-muted small">
                        Responsible Person
                    </div>

                    <strong>
                        {{ $record->responsiblePerson?->name
                            ?? $record->responsible_person_name
                            ?? '—'
                        }}
                    </strong>

                </div>


                <div class="col-12">

                    <div class="text-muted small">
                        Corrective Action Details
                    </div>

                    <div
                        style="white-space:pre-line;"
                    >
                        {{ $record->corrective_action ?? '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Remarks --}}

    @if($record->remarks)

        <div class="card mb-4">

            <div class="card-header">
                <strong>Remarks</strong>
            </div>

            <div
                class="card-body"
                style="white-space:pre-line;"
            >
                {{ $record->remarks }}
            </div>

        </div>

    @endif


    {{-- Audit Information --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Record Information</strong>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Created By
                    </div>

                    <strong>
                        {{ $record->creator?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Created At
                    </div>

                    <strong>

                        {{ $record->created_at
                            ? $record->created_at->format('d-m-Y H:i')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated By
                    </div>

                    <strong>
                        {{ $record->updater?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated At
                    </div>

                    <strong>

                        {{ $record->updated_at
                            ? $record->updated_at->format('d-m-Y H:i')
                            : '—'
                        }}

                    </strong>

                </div>

            </div>

        </div>

    </div>

    {{-- ============================================================
     ENVIRONMENTAL ACTIONS
    ============================================================ --}}

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Environmental Actions
            </strong>

            <a
                href="{{ route(
                    'admin.projects.construction.hse.environmental.actions.create',
                    [
                        'project' => $project,
                        'source' => 'record',
                        'source_id' => $record->id,
                    ]
                ) }}"
                class="btn btn-sm btn-primary"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Add Action
            </a>

        </div>

        <div class="card-body">

            @if($record->actions->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Action No.
                                </th>

                                <th>
                                    Title
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Priority
                                </th>

                                <th>
                                    Assigned To
                                </th>

                                <th>
                                    Due Date
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($record->actions as $action)

                                <tr>

                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.projects.construction.hse.environmental.actions.show',
                                                [
                                                    'project' => $project,
                                                    'action' => $action,
                                                ]
                                            ) }}"
                                            class="fw-semibold text-decoration-none"
                                        >
                                            {{ $action->action_number }}
                                        </a>

                                    </td>

                                    <td>
                                        {{ $action->action_title }}
                                    </td>

                                    <td>
                                        {{ $action->action_type }}
                                    </td>

                                    <td>

                                        @switch($action->priority)

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

                                    <td>
                                        {{ $action->assignee?->name ?? '—' }}
                                    </td>

                                    <td>

                                        @if($action->due_date)

                                            {{ $action->due_date->format('d-m-Y') }}

                                        @else

                                            —

                                        @endif

                                    </td>

                                    <td>

                                        @switch($action->status)

                                            @case('Open')

                                                <span class="badge bg-primary">
                                                    Open
                                                </span>

                                                @break

                                            @case('In Progress')

                                                <span class="badge bg-warning text-dark">
                                                    In Progress
                                                </span>

                                                @break

                                            @case('Completed')

                                                <span class="badge bg-success">
                                                    Completed
                                                </span>

                                                @break

                                            @case('Closed')

                                                <span class="badge bg-secondary">
                                                    Closed
                                                </span>

                                                @break

                                            @default

                                                <span class="badge bg-light text-dark">
                                                    {{ $action->status }}
                                                </span>

                                        @endswitch

                                    </td>

                                    <td class="text-end">

                                        <a
                                            href="{{ route(
                                                'admin.projects.construction.hse.environmental.actions.show',
                                                [
                                                    'project' => $project,
                                                    'action' => $action,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            View
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-4">

                    <div class="text-muted mb-3">
                        No environmental actions have been created for this record.
                    </div>

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.environmental.actions.create',
                            [
                                'project' => $project,
                                'source' => 'record',
                                'source_id' => $record->id,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        Add First Action
                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- Delete --}}

    <div class="d-flex justify-content-end">

        <form
            method="POST"
            action="{{ route(
                'admin.projects.construction.hse.environmental.records.destroy',
                [
                    'project' => $project,
                    'record' => $record,
                ]
            ) }}"
            onsubmit="return confirm(
                'Are you sure you want to delete this environmental record?'
            );"
        >

            @csrf

            @method('DELETE')

            <button
                type="submit"
                class="btn btn-outline-danger"
            >
                <i class="bi bi-trash me-1"></i>
                Delete Record
            </button>

        </form>

    </div>

</div>

@endsection