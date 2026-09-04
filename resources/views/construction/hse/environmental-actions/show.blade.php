@extends('layouts.app')

@section('content')

<div class="container-fluid">


    {{-- ============================================================
         HEADER
    ============================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">

                Project:

                <strong>
                    {{ $project->project_code ?? $project->name ?? '—' }}
                </strong>

            </div>

            <h3 class="mb-1">
                Environmental Action
            </h3>

            <div class="text-muted">

                {{ $action->action_number }}

                <span class="mx-1">
                    •
                </span>

                {{ $action->action_title }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.hse.environmental.actions.edit',
                    [
                        'project' => $project,
                        'action' => $action,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-pencil me-1"></i>
                Edit
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.environmental.actions.index',
                    [
                        'project' => $project,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-list me-1"></i>
                Action Register
            </a>

        </div>

    </div>


    {{-- ============================================================
         SUCCESS
    ============================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif


    {{-- ============================================================
         SOURCE
    ============================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Action Source
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                {{-- Environmental Record --}}

                <div class="col-md-6">

                    <div class="text-muted small mb-1">
                        Environmental Record
                    </div>

                    @if($action->environmentalRecord)

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.environmental.records.show',
                                [
                                    'project' => $project,
                                    'record' => $action->environmentalRecord,
                                ]
                            ) }}"
                            class="fw-semibold text-decoration-none"
                        >
                            {{ $action->environmentalRecord->record_number }}
                        </a>

                        <div class="small text-muted mt-1">

                            {{ $action->environmentalRecord->record_title }}

                        </div>

                    @else

                        <span class="text-muted">
                            Not linked
                        </span>

                    @endif

                </div>


                {{-- Environmental Compliance --}}

                <div class="col-md-6">

                    <div class="text-muted small mb-1">
                        Environmental Compliance
                    </div>

                    @if($action->environmentalCompliance)

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.environmental.compliances.show',
                                [
                                    'project' => $project,
                                    'compliance' => $action->environmentalCompliance,
                                ]
                            ) }}"
                            class="fw-semibold text-decoration-none"
                        >
                            {{ $action->environmentalCompliance->compliance_number }}
                        </a>

                        <div class="small text-muted mt-1">

                            {{ $action->environmentalCompliance->compliance_title }}

                        </div>

                    @else

                        <span class="text-muted">
                            Not linked
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         ACTION DETAILS
    ============================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Action Details
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-4">

                    <div class="text-muted small">
                        Action Number
                    </div>

                    <strong>
                        {{ $action->action_number }}
                    </strong>

                </div>


                <div class="col-md-8">

                    <div class="text-muted small">
                        Action Title
                    </div>

                    <strong>
                        {{ $action->action_title }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Action Type
                    </div>

                    <strong>
                        {{ $action->action_type }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Priority
                    </div>

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

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Status
                    </div>

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

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         DESCRIPTION
    ============================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Action Description
            </strong>

        </div>

        <div
            class="card-body"
            style="white-space: pre-line;"
        >

            {{ $action->action_description ?? 'No description provided.' }}

        </div>

    </div>


    {{-- ============================================================
         ROOT CAUSE / PREVENTIVE ACTION
    ============================================================= --}}

    <div class="row g-4 mb-4">


        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header">

                    <strong>
                        Root Cause
                    </strong>

                </div>

                <div
                    class="card-body"
                    style="white-space: pre-line;"
                >

                    {{ $action->root_cause ?? '—' }}

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header">

                    <strong>
                        Preventive Action
                    </strong>

                </div>

                <div
                    class="card-body"
                    style="white-space: pre-line;"
                >

                    {{ $action->preventive_action ?? '—' }}

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         ASSIGNMENT & TIMELINE
    ============================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Assignment & Timeline
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-3">

                    <div class="text-muted small">
                        Assigned To
                    </div>

                    <strong>
                        {{ $action->assignee?->name
                            ?? $action->assigned_to_name
                            ?? '—'
                        }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Assigned Date
                    </div>

                    <strong>

                        {{ $action->assigned_date
                            ? $action->assigned_date->format('d-m-Y')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Due Date
                    </div>

                    <strong>

                        {{ $action->due_date
                            ? $action->due_date->format('d-m-Y')
                            : '—'
                        }}

                    </strong>

                    @if(
                        method_exists($action, 'isOverdue')
                        && $action->isOverdue()
                    )

                        <div class="mt-1">

                            <span class="badge bg-danger">
                                Overdue
                            </span>

                        </div>

                    @endif

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Completion Date
                    </div>

                    <strong>

                        {{ $action->completion_date
                            ? $action->completion_date->format('d-m-Y')
                            : '—'
                        }}

                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         COMPLETION
    ============================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Completion
            </strong>

        </div>

        <div
            class="card-body"
            style="white-space: pre-line;"
        >

            {{ $action->completion_remarks
                ?? 'No completion remarks.'
            }}

        </div>

    </div>


    {{-- ============================================================
         VERIFICATION
    ============================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Verification
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-3">

                    <div class="text-muted small">
                        Verification Required
                    </div>

                    @if($action->verification_required)

                        <span class="badge bg-danger">
                            Yes
                        </span>

                    @else

                        <span class="badge bg-success">
                            No
                        </span>

                    @endif

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Verification Status
                    </div>

                    @switch($action->verification_status)

                        @case('Verified')

                            <span class="badge bg-success">
                                Verified
                            </span>

                            @break

                        @case('Rejected')

                            <span class="badge bg-danger">
                                Rejected
                            </span>

                            @break

                        @case('Pending')

                            <span class="badge bg-warning text-dark">
                                Pending
                            </span>

                            @break

                        @default

                            <span class="badge bg-secondary">
                                Not Required
                            </span>

                    @endswitch

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Verified By
                    </div>

                    <strong>
                        {{ $action->verifier?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Verified At
                    </div>

                    <strong>

                        {{ $action->verified_at
                            ? $action->verified_at->format('d-m-Y H:i')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="col-12">

                    <div class="text-muted small mb-1">
                        Verification Remarks
                    </div>

                    <div style="white-space: pre-line;">

                        {{ $action->verification_remarks ?? '—' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         REMARKS
    ============================================================= --}}

    @if($action->remarks)

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Remarks
                </strong>

            </div>

            <div
                class="card-body"
                style="white-space: pre-line;"
            >

                {{ $action->remarks }}

            </div>

        </div>

    @endif


    {{-- ============================================================
         RECORD INFORMATION
    ============================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Record Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-3">

                    <div class="text-muted small">
                        Created By
                    </div>

                    <strong>
                        {{ $action->creator?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Created At
                    </div>

                    <strong>

                        {{ $action->created_at
                            ? $action->created_at->format('d-m-Y H:i')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated By
                    </div>

                    <strong>
                        {{ $action->updater?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated At
                    </div>

                    <strong>

                        {{ $action->updated_at
                            ? $action->updated_at->format('d-m-Y H:i')
                            : '—'
                        }}

                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         DELETE
    ============================================================= --}}

    @if($action->status !== 'Closed')

        <div class="d-flex justify-content-end">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.hse.environmental.actions.destroy',
                    [
                        'project' => $project,
                        'action' => $action,
                    ]
                ) }}"
                onsubmit="return confirm(
                    'Are you sure you want to delete this environmental action?'
                );"
            >

                @csrf

                @method('DELETE')

                <button
                    type="submit"
                    class="btn btn-outline-danger"
                >
                    <i class="bi bi-trash me-1"></i>
                    Delete Action
                </button>

            </form>

        </div>

    @endif

</div>

@endsection