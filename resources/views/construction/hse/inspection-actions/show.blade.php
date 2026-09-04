@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Finding:
                <strong>
                    {{ $finding->finding_number }}
                </strong>
            </div>

            <h3 class="mb-1">
                {{ $action->action_number }}
            </h3>

            <div class="text-muted">
                Inspection:
                {{ $inspection->inspection_number }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.hse.inspections.findings.actions.edit',
                    [
                        'project' => $project,
                        'inspection' => $inspection,
                        'finding' => $finding,
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
                    'admin.projects.construction.hse.inspections.findings.actions.index',
                    [
                        'project' => $project,
                        'inspection' => $inspection,
                        'finding' => $finding,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Actions
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.inspections.findings.show',
                    [
                        'project' => $project,
                        'inspection' => $inspection,
                        'finding' => $finding,
                    ]
                ) }}"
                class="btn btn-secondary"
            >
                Finding
            </a>

        </div>

    </div>


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


    @php

        $statusClass =
            match($action->status) {

                'Open' =>
                    'bg-primary',

                'In Progress' =>
                    'bg-warning text-dark',

                'Completed' =>
                    'bg-success',

                'Closed' =>
                    'bg-dark',

                default =>
                    'bg-secondary',

            };


        $verificationClass =
            match($action->verification_status) {

                'Verified' =>
                    'bg-success',

                'Rejected' =>
                    'bg-danger',

                default =>
                    'bg-warning text-dark',

            };

    @endphp


    {{-- Summary --}}

    <div class="card mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <div class="text-muted small">
                        Corrective Action
                    </div>

                    <h4 class="mb-2">
                        {{ $action->action_number }}
                    </h4>

                    @if($action->action_type)

                        <span class="text-muted">
                            {{ $action->action_type }}
                        </span>

                    @endif

                </div>


                <div class="col-md-4 text-md-end">

                    <span
                        class="badge {{ $statusClass }} fs-6"
                    >
                        {{ $action->status }}
                    </span>

                    <span
                        class="badge {{ $verificationClass }} fs-6"
                    >
                        {{ $action->verification_status ?? 'Pending' }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- Action Details --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Action Details</strong>
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


                <div class="col-md-4">

                    <div class="text-muted small">
                        Action Type
                    </div>

                    <strong>
                        {{ $action->action_type ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Due Date
                    </div>

                    <strong>

                        {{ $action->due_date
                            ? $action->due_date->format('d-m-Y')
                            : '—'
                        }}

                        @if($action->isOverdue())

                            <span class="badge bg-danger ms-1">
                                Overdue
                            </span>

                        @endif

                    </strong>

                </div>


                <div class="col-12">

                    <div class="text-muted small mb-1">
                        Action Description
                    </div>

                    <div style="white-space:pre-line;">
                        {{ $action->action_description }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Responsibility --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Responsibility</strong>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-6">

                    <div class="text-muted small">
                        Responsible Person
                    </div>

                    <strong>
                        {{ $action->responsible_name
                            ?? $action->responsibleUser?->name
                            ?? '—'
                        }}
                    </strong>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Finding
                    </div>

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.inspections.findings.show',
                            [
                                'project' => $project,
                                'inspection' => $inspection,
                                'finding' => $finding,
                            ]
                        ) }}"
                        class="fw-semibold"
                    >
                        {{ $finding->finding_number }}
                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- Completion --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Completion</strong>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Status
                    </div>

                    <span class="badge {{ $statusClass }}">
                        {{ $action->status }}
                    </span>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Completed Date
                    </div>

                    <strong>
                        {{ $action->completed_date
                            ? $action->completed_date->format('d-m-Y')
                            : '—'
                        }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Completed By
                    </div>

                    <strong>
                        {{ $action->completedBy?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-12">

                    <div class="text-muted small">
                        Completion Remarks
                    </div>

                    <div style="white-space:pre-line;">
                        {{ $action->completion_remarks ?? '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Verification --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Verification</strong>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Verification Status
                    </div>

                    <span class="badge {{ $verificationClass }}">
                        {{ $action->verification_status ?? 'Pending' }}
                    </span>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Verified Date
                    </div>

                    <strong>
                        {{ $action->verified_date
                            ? $action->verified_date->format('d-m-Y')
                            : '—'
                        }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Verified By
                    </div>

                    <strong>
                        {{ $action->verifiedBy?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-12">

                    <div class="text-muted small">
                        Verification Remarks
                    </div>

                    <div style="white-space:pre-line;">
                        {{ $action->verification_remarks ?? '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Remarks --}}

    @if($action->remarks)

        <div class="card mb-4">

            <div class="card-header">
                <strong>Remarks</strong>
            </div>

            <div class="card-body" style="white-space:pre-line;">
                {{ $action->remarks }}
            </div>

        </div>

    @endif


    {{-- Record Information --}}

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
                        {{ $action->creator?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Created At
                    </div>

                    <strong>
                        {{ $action->created_at?->format('d-m-Y H:i') ?? '—' }}
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
                        {{ $action->updated_at?->format('d-m-Y H:i') ?? '—' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Delete --}}

    @if($action->status !== 'Closed')

        <div class="d-flex justify-content-end">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.hse.inspections.findings.actions.destroy',
                    [
                        'project' => $project,
                        'inspection' => $inspection,
                        'finding' => $finding,
                        'action' => $action,
                    ]
                ) }}"
                onsubmit="return confirm(
                    'Are you sure you want to delete this corrective action?'
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