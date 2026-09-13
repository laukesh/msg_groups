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
                Environmental Compliance
            </h3>

            <div class="text-muted">

                Compliance:
                <strong>
                    {{ $compliance->compliance_number }}
                </strong>

                <span class="mx-1">•</span>

                {{ $compliance->compliance_title }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.hse.environmental.compliances.edit',
                    [
                        'project' => $project,
                        'compliance' => $compliance,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-pencil me-1"></i>
                Edit
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.environmental.compliances.index',
                    [
                        'project' => $project,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Compliance Register
            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Main Details --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Compliance Details</strong>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Compliance Number
                    </div>

                    <strong>
                        {{ $compliance->compliance_number }}
                    </strong>

                </div>


                <div class="col-md-8">

                    <div class="text-muted small">
                        Title
                    </div>

                    <strong>
                        {{ $compliance->compliance_title }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Compliance Type
                    </div>

                    <strong>
                        {{ $compliance->compliance_type }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Regulatory Authority
                    </div>

                    <strong>
                        {{ $compliance->regulatory_authority ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Legislation / Reference
                    </div>

                    <strong>
                        {{ $compliance->legislation_reference ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Permit / License Number
                    </div>

                    <strong>
                        {{ $compliance->permit_license_number ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Applicable From
                    </div>

                    <strong>

                        {{ $compliance->applicable_from
                            ? $compliance->applicable_from->format('d-m-Y')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Due Date
                    </div>

                    <strong>

                        {{ $compliance->due_date
                            ? $compliance->due_date->format('d-m-Y')
                            : '—'
                        }}

                    </strong>

                    @if($compliance->isOverdue())

                        <div>
                            <span class="badge bg-danger mt-1">
                                Overdue
                            </span>
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- Status --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Compliance Status</strong>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Compliance
                    </div>

                    @switch($compliance->compliance_status)

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
                                {{ $compliance->compliance_status }}
                            </span>

                    @endswitch

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Risk Level
                    </div>

                    @switch($compliance->risk_level)

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

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Record Status
                    </div>

                    <span class="badge bg-secondary">
                        {{ $compliance->status }}
                    </span>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Review Date
                    </div>

                    <strong>

                        {{ $compliance->review_date
                            ? $compliance->review_date->format('d-m-Y')
                            : '—'
                        }}

                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Requirement --}}

    @if($compliance->requirement_description)

        <div class="card mb-4">

            <div class="card-header">
                <strong>Requirement Description</strong>
            </div>

            <div
                class="card-body"
                style="white-space:pre-line;"
            >
                {{ $compliance->requirement_description }}
            </div>

        </div>

    @endif


    {{-- Evidence --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Evidence</strong>
        </div>

        <div class="card-body">

            <div class="mb-3">

                @if($compliance->evidence_available)

                    <span class="badge bg-success">
                        Evidence Available
                    </span>

                @else

                    <span class="badge bg-secondary">
                        Evidence Not Available
                    </span>

                @endif

            </div>


            @if($compliance->evidence_description)

                <div
                    style="white-space:pre-line;"
                >
                    {{ $compliance->evidence_description }}
                </div>

            @else

                <span class="text-muted">
                    No evidence description provided.
                </span>

            @endif

        </div>

    </div>


    {{-- Non Compliance --}}

    @if(
        $compliance->non_compliance_details ||
        $compliance->compliance_status === 'Non-Compliant'
    )

        <div class="card mb-4 border-danger">

            <div class="card-header text-danger">

                <strong>
                    Non-Compliance Details
                </strong>

            </div>

            <div class="card-body">

                {{ $compliance->non_compliance_details ?? '—' }}

            </div>

        </div>

    @endif


    {{-- Corrective Action --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Corrective Action</strong>
        </div>

        <div class="card-body">

            <div class="mb-3">

                @if($compliance->corrective_action_required)

                    <span class="badge bg-danger">
                        Corrective Action Required
                    </span>

                @else

                    <span class="badge bg-success">
                        Not Required
                    </span>

                @endif

            </div>


            <div style="white-space:pre-line;">
                {{ $compliance->corrective_action ?? '—' }}
            </div>

        </div>

    </div>


    {{-- Responsible Person --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Responsibility</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">

                    <div class="text-muted small">
                        Responsible Person
                    </div>

                    <strong>
                        {{ $compliance->responsiblePerson?->name
                            ?? $compliance->responsible_person_name
                            ?? '—'
                        }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Remarks --}}

    @if($compliance->remarks)

        <div class="card mb-4">

            <div class="card-header">
                <strong>Remarks</strong>
            </div>

            <div
                class="card-body"
                style="white-space:pre-line;"
            >
                {{ $compliance->remarks }}
            </div>

        </div>

    @endif


    {{-- Audit --}}

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
                        {{ $compliance->creator?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Created At
                    </div>

                    <strong>

                        {{ $compliance->created_at
                            ? $compliance->created_at->format('d-m-Y H:i')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated By
                    </div>

                    <strong>
                        {{ $compliance->updater?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated At
                    </div>

                    <strong>

                        {{ $compliance->updated_at
                            ? $compliance->updated_at->format('d-m-Y H:i')
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
                        'source' => 'compliance',
                        'source_id' => $compliance->id,
                    ]
                ) }}"
                class="btn btn-sm btn-primary"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Add Action
            </a>

        </div>

        <div class="card-body">

            @if($compliance->actions->count())

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

                            @foreach($compliance->actions as $action)

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
                        No environmental actions have been created for this compliance.
                    </div>

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.environmental.actions.create',
                            [
                                'project' => $project,
                                'source' => 'compliance',
                                'source_id' => $compliance->id,
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
                'admin.projects.construction.hse.environmental.compliances.destroy',
                [
                    'project' => $project,
                    'compliance' => $compliance,
                ]
            ) }}"
            onsubmit="return confirm(
                'Are you sure you want to delete this compliance record?'
            );"
        >

            @csrf

            @method('DELETE')

            <button
                type="submit"
                class="btn btn-outline-danger"
            >
                <i class="bi bi-trash me-1"></i>
                Delete Compliance
            </button>

        </form>

    </div>

</div>

@endsection