@extends('layouts.app')

@section('content')

<div class="container-fluid">


    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">

                Project:

                <strong>
                    {{ $project->project_code ?? '—' }}
                </strong>

            </div>


            <h3 class="mb-1">
                {{ $action->action_number }}
            </h3>


            <div class="text-muted">

                Incident:

                <strong>
                    {{ $incident->incident_number }}
                </strong>


                @if($incident->incident_type)

                    <span class="mx-1">
                        •
                    </span>

                    {{ $incident->incident_type }}

                @endif

            </div>

        </div>


        <div class="d-flex gap-2">


            {{-- =================================================
                EDIT
            ================================================== --}}

            @if($action->status !== 'Closed')

                <a
                    href="{{ route(
                        'admin.projects.construction.hse.incidents.actions.edit',
                        [
                            'project' => $project,
                            'incident' => $incident,
                            'action' => $action,
                        ]
                    ) }}"
                    class="btn btn-outline-primary"
                >

                    <i class="bi bi-pencil me-1"></i>

                    Edit

                </a>

            @endif


            {{-- =================================================
                ACTION REGISTER
            ================================================== --}}

            <a
                href="{{ route(
                    'admin.projects.construction.hse.incidents.actions.index',
                    [
                        'project' => $project,
                        'incident' => $incident,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >

                <i class="bi bi-list me-1"></i>

                Actions

            </a>


            {{-- =================================================
                INCIDENT
            ================================================== --}}

            <a
                href="{{ route(
                    'admin.projects.construction.hse.incidents.show',
                    [
                        'project' => $project,
                        'incident' => $incident,
                    ]
                ) }}"
                class="btn btn-secondary"
            >

                Incident

            </a>

        </div>

    </div>



    {{-- =========================================================
        FLASH MESSAGES
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}


            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle me-1"></i>

            {{ session('error') }}


            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif



    {{-- =========================================================
        ACTION STATUS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-body">

            <div class="row align-items-center">


                {{-- =================================================
                    ACTION INFORMATION
                ================================================== --}}

                <div class="col-md-7">

                    <div class="text-muted small">
                        Action Type
                    </div>


                    <h5 class="mb-1">

                        {{ $action->action_type ?? '—' }}

                    </h5>


                    <div class="text-muted">

                        Assigned to:

                        <strong>

                            {{ $action->responsible_name
                                ?? $action->responsibleUser?->name
                                ?? 'Not Assigned'
                            }}

                        </strong>

                    </div>

                </div>


                {{-- =================================================
                    STATUS
                ================================================== --}}

                <div class="col-md-5 text-md-end">


                    <div class="mb-2">

                        <div class="text-muted small mb-1">
                            Action Status
                        </div>


                        @php

                            $statusClass = match(
                                $action->status
                            ) {

                                'Open' =>
                                    'bg-secondary',

                                'In Progress' =>
                                    'bg-warning text-dark',

                                'Completed' =>
                                    'bg-primary',

                                'Closed' =>
                                    'bg-dark',

                                default =>
                                    'bg-secondary',

                            };

                        @endphp


                        <span
                            class="badge {{ $statusClass }} fs-6"
                        >

                            {{ $action->status }}

                        </span>

                    </div>


                    <div>

                        <div class="text-muted small mb-1">
                            Verification Status
                        </div>


                        @php

                            $verificationClass =
                                match(
                                    $action->verification_status
                                ) {

                                    'Pending' =>
                                        'bg-warning text-dark',

                                    'Verified' =>
                                        'bg-success',

                                    'Rejected' =>
                                        'bg-danger',

                                    default =>
                                        'bg-secondary',

                                };

                        @endphp


                        <span
                            class="badge {{ $verificationClass }}"
                        >

                            {{ $action->verification_status ?? '—' }}

                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        WORKFLOW INFORMATION
    ========================================================== --}}

    @if($action->status === 'Open')

        <div class="alert alert-info mb-4">

            <i class="bi bi-info-circle me-1"></i>

            This action has been assigned and is waiting to be started.

        </div>

    @elseif($action->status === 'In Progress')

        <div class="alert alert-warning mb-4">

            <i class="bi bi-hourglass-split me-1"></i>

            This action is currently in progress.

        </div>

    @elseif(
        $action->status === 'Completed' &&
        $action->verification_status === 'Pending'
    )

        <div class="alert alert-primary mb-4">

            <i class="bi bi-shield-exclamation me-1"></i>

            This action has been completed and is waiting for
            verification.

        </div>

    @elseif(
        $action->status === 'In Progress' &&
        $action->verification_status === 'Rejected'
    )

        <div class="alert alert-danger mb-4">

            <i class="bi bi-arrow-repeat me-1"></i>

            Verification was rejected.

            The action must be corrected and completed again.

        </div>

    @elseif(
        $action->status === 'Closed' &&
        $action->verification_status === 'Verified'
    )

        <div class="alert alert-success mb-4">

            <i class="bi bi-shield-check me-1"></i>

            This action has been successfully verified and closed.

        </div>

    @endif



    {{-- =========================================================
        ACTION DETAILS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Action Details
            </strong>

        </div>


        <div class="card-body">


            {{-- =================================================
                DESCRIPTION
            ================================================== --}}

            <div class="mb-4">

                <div class="text-muted small mb-1">
                    Action Description
                </div>


                <div style="white-space: pre-line;">

                    {{ $action->action_description ?? '—' }}

                </div>

            </div>


            {{-- =================================================
                BASIC INFORMATION
            ================================================== --}}

            <div class="row g-4">


                <div class="col-md-3">

                    <div class="text-muted small">
                        Action Number
                    </div>

                    <strong>
                        {{ $action->action_number }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Action Type
                    </div>

                    <strong>
                        {{ $action->action_type ?? '—' }}
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


                    @if($action->isOverdue())

                        <div>

                            <span class="badge bg-danger mt-1">

                                Overdue

                            </span>

                        </div>

                    @endif

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Responsible User
                    </div>


                    <strong>

                        {{ $action->responsible_name
                            ?? $action->responsibleUser?->name
                            ?? '—'
                        }}

                    </strong>

                </div>


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
                        Created Date
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
                        Updated Date
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



    {{-- =========================================================
        COMPLETION DETAILS
    ========================================================== --}}

    @if(
        $action->completed_date ||
        $action->completed_by ||
        $action->completion_remarks
    )

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Completion Details
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-4">


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Completed By
                        </div>


                        <strong>

                            {{ $action->completedBy?->name ?? '—' }}

                        </strong>

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
                            Completion Status
                        </div>


                        <span class="badge bg-primary">
                            Completed
                        </span>

                    </div>


                    <div class="col-12">

                        <div class="text-muted small mb-1">
                            Completion Remarks
                        </div>


                        <div style="white-space: pre-line;">

                            {{ $action->completion_remarks ?? '—' }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif



    {{-- =========================================================
        VERIFICATION DETAILS
    ========================================================== --}}

    @if(
        $action->verified_date ||
        $action->verified_by ||
        $action->verification_remarks ||
        $action->verification_status === 'Rejected'
    )

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Verification Details
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-4">


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Verification Status
                        </div>


                        @php

                            $verificationClass =
                                match(
                                    $action->verification_status
                                ) {

                                    'Pending' =>
                                        'bg-warning text-dark',

                                    'Verified' =>
                                        'bg-success',

                                    'Rejected' =>
                                        'bg-danger',

                                    default =>
                                        'bg-secondary',

                                };

                        @endphp


                        <span
                            class="badge {{ $verificationClass }}"
                        >

                            {{ $action->verification_status ?? '—' }}

                        </span>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Verified By
                        </div>


                        <strong>

                            {{ $action->verifiedBy?->name ?? '—' }}

                        </strong>

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

    @endif



    {{-- =========================================================
        REMARKS
    ========================================================== --}}

    @if($action->remarks)

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Remarks
                </strong>

            </div>


            <div class="card-body">

                <div style="white-space: pre-line;">

                    {{ $action->remarks }}

                </div>

            </div>

        </div>

    @endif



    {{-- =========================================================
        PARENT INCIDENT
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Parent Incident
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-4">

                    <div class="text-muted small">
                        Incident Number
                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.incidents.show',
                            [
                                'project' => $project,
                                'incident' => $incident,
                            ]
                        ) }}"
                        class="fw-semibold text-decoration-none"
                    >

                        {{ $incident->incident_number }}

                    </a>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Incident Status
                    </div>


                    <span class="badge bg-info text-dark">

                        {{ $incident->status }}

                    </span>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Incident Date
                    </div>


                    <strong>

                        {{ $incident->incident_date
                            ? $incident->incident_date->format('d-m-Y')
                            : '—'
                        }}

                    </strong>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        WORKFLOW
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Action Workflow
            </strong>

        </div>


        <div class="card-body">


            {{-- =================================================
                WORKFLOW VISUAL
            ================================================== --}}

            <div class="mb-4">

                <div class="d-flex flex-wrap align-items-center gap-2">

                    <span
                        class="badge
                        {{ $action->status === 'Open'
                            ? 'bg-secondary'
                            : 'bg-light text-dark border'
                        }}"
                    >
                        Open
                    </span>


                    <span class="text-muted">
                        →
                    </span>


                    <span
                        class="badge
                        {{ $action->status === 'In Progress'
                            ? 'bg-warning text-dark'
                            : 'bg-light text-dark border'
                        }}"
                    >
                        In Progress
                    </span>


                    <span class="text-muted">
                        →
                    </span>


                    <span
                        class="badge
                        {{ $action->status === 'Completed'
                            ? 'bg-primary'
                            : 'bg-light text-dark border'
                        }}"
                    >
                        Completed
                    </span>


                    <span class="text-muted">
                        →
                    </span>


                    <span
                        class="badge
                        {{
                            $action->verification_status === 'Verified'
                                ? 'bg-success'
                                : 'bg-light text-dark border'
                        }}"
                    >
                        Verified
                    </span>


                    <span class="text-muted">
                        →
                    </span>


                    <span
                        class="badge
                        {{ $action->status === 'Closed'
                            ? 'bg-dark'
                            : 'bg-light text-dark border'
                        }}"
                    >
                        Closed
                    </span>

                </div>

            </div>



            {{-- =================================================
                BUTTONS
            ================================================== --}}

            <div class="d-flex flex-wrap gap-2">


                {{-- =============================================
                    OPEN → IN PROGRESS
                ============================================== --}}

                @if($action->status === 'Open')

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.construction.hse.incidents.actions.start',
                            [
                                'project' => $project,
                                'incident' => $incident,
                                'action' => $action,
                            ]
                        ) }}"
                    >

                        @csrf


                        <button
                            type="submit"
                            class="btn btn-warning"
                            onclick="return confirm(
                                'Start this incident action?'
                            )"
                        >

                            <i class="bi bi-play-fill me-1"></i>

                            Start Action

                        </button>

                    </form>

                @endif



                {{-- =============================================
                    OPEN / IN PROGRESS → COMPLETED
                ============================================== --}}

                @if(
                    in_array(
                        $action->status,
                        [
                            'Open',
                            'In Progress',
                        ],
                        true
                    )
                )

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.construction.hse.incidents.actions.complete',
                            [
                                'project' => $project,
                                'incident' => $incident,
                                'action' => $action,
                            ]
                        ) }}"
                    >

                        @csrf


                        <button
                            type="submit"
                            class="btn btn-primary"
                            onclick="return confirm(
                                'Mark this incident action as completed?'
                            )"
                        >

                            <i class="bi bi-check-lg me-1"></i>

                            Mark Completed

                        </button>

                    </form>

                @endif



                {{-- =============================================
                    COMPLETED + PENDING → VERIFY
                ============================================== --}}

                @if(
                    $action->status === 'Completed' &&
                    $action->verification_status === 'Pending'
                )

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.construction.hse.incidents.actions.verify',
                            [
                                'project' => $project,
                                'incident' => $incident,
                                'action' => $action,
                            ]
                        ) }}"
                    >

                        @csrf


                        <button
                            type="submit"
                            class="btn btn-success"
                            onclick="return confirm(
                                'Verify this completed action?'
                            )"
                        >

                            <i class="bi bi-shield-check me-1"></i>

                            Verify Action

                        </button>

                    </form>


                    {{-- =========================================
                        COMPLETED + PENDING → REJECT
                    ========================================== --}}

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.construction.hse.incidents.actions.reject-verification',
                            [
                                'project' => $project,
                                'incident' => $incident,
                                'action' => $action,
                            ]
                        ) }}"
                    >

                        @csrf


                        <input
                            type="hidden"
                            name="verification_remarks"
                            value="Verification rejected. Please review and correct the action."
                        >


                        <button
                            type="submit"
                            class="btn btn-outline-danger"
                            onclick="return confirm(
                                'Reject verification and return this action for correction?'
                            )"
                        >

                            <i class="bi bi-x-circle me-1"></i>

                            Reject Verification

                        </button>

                    </form>

                @endif



                {{-- =============================================
                    DELETE OPEN ACTION
                ============================================== --}}

                @if($action->status === 'Open')

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.construction.hse.incidents.actions.destroy',
                            [
                                'project' => $project,
                                'incident' => $incident,
                                'action' => $action,
                            ]
                        ) }}"
                        onsubmit="return confirm(
                            'Are you sure you want to delete this action?'
                        );"
                    >

                        @csrf

                        @method('DELETE')


                        <button
                            type="submit"
                            class="btn btn-outline-danger"
                        >

                            <i class="bi bi-trash me-1"></i>

                            Delete

                        </button>

                    </form>

                @endif


            </div>

        </div>

    </div>



    {{-- =========================================================
        FINAL STATUS
    ========================================================== --}}

    @if(
        $action->status === 'Closed' &&
        $action->verification_status === 'Verified'
    )

        <div class="alert alert-success">

            <i class="bi bi-check-circle-fill me-1"></i>

            <strong>
                Action Completed Successfully
            </strong>

            <div class="mt-1">

                This incident action has been completed,
                verified and closed.

                The parent incident is now governed by the
                overall incident workflow.

            </div>

        </div>

    @endif


</div>

@endsection