@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Corrective Action Details
            </h3>

            <p class="text-muted mb-0">

                {{ $correctiveAction->action_number }}

                &nbsp; | &nbsp;

                Observation:
                <strong>
                    {{ $observation->observation_number }}
                </strong>

            </p>

        </div>

        <div>

            <a
                href="{{ route(
                    'admin.projects.construction.hse.observations.corrective-actions.index',
                    [
                        'project' => $project,
                        'observation' => $observation,
                    ]
                ) }}"
                class="btn btn-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Corrective Actions
            </a>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        STATUS HEADER
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <div class="d-flex align-items-center gap-3">

                        <div>

                            <small class="text-muted d-block">
                                Action Number
                            </small>

                            <h4 class="mb-0">
                                {{ $correctiveAction->action_number }}
                            </h4>

                        </div>


                        <div>

                            <small class="text-muted d-block">
                                Status
                            </small>

                            @switch($correctiveAction->status)

                                @case('Open')

                                    <span class="badge bg-secondary fs-6">
                                        Open
                                    </span>

                                    @break

                                @case('In Progress')

                                    <span class="badge bg-warning text-dark fs-6">
                                        In Progress
                                    </span>

                                    @break

                                @case('Resolved')

                                    <span class="badge bg-primary fs-6">
                                        Resolved
                                    </span>

                                    @break

                                @case('Verified')

                                    <span class="badge bg-success fs-6">
                                        Verified
                                    </span>

                                    @break

                                @case('Closed')

                                    <span class="badge bg-dark fs-6">
                                        Closed
                                    </span>

                                    @break

                                @default

                                    <span class="badge bg-secondary fs-6">
                                        {{ $correctiveAction->status }}
                                    </span>

                            @endswitch

                        </div>


                        <div>

                            <small class="text-muted d-block">
                                Verification
                            </small>

                            @if(
                                $correctiveAction->verification_status
                                === 'Verified'
                            )

                                <span class="badge bg-success">
                                    Verified
                                </span>

                            @elseif(
                                $correctiveAction->verification_status
                                === 'Rejected'
                            )

                                <span class="badge bg-danger">
                                    Rejected
                                </span>

                            @else

                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                            @endif

                        </div>

                    </div>

                </div>


                <div class="col-md-4 text-md-end mt-3 mt-md-0">

                    @if(
                        $correctiveAction->status !== 'Closed'
                    )

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.observations.corrective-actions.edit',
                                [
                                    'project' => $project,
                                    'observation' => $observation,
                                    'correctiveAction' => $correctiveAction,
                                ]
                            ) }}"
                            class="btn btn-outline-secondary"
                        >
                            <i class="bi bi-pencil me-1"></i>
                            Edit
                        </a>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        ACTION INFORMATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Corrective Action Information</strong>
        </div>

        <div class="card-body">

            <div class="row">

                {{-- Description --}}

                <div class="col-md-12 mb-4">

                    <small class="text-muted d-block">
                        Action Description
                    </small>

                    <div class="mt-1">

                        {!! nl2br(
                            e(
                                $correctiveAction->action_description
                            )
                        ) !!}

                    </div>

                </div>


                {{-- Responsible User --}}

                <div class="col-md-4 mb-4">

                    <small class="text-muted d-block">
                        Responsible User
                    </small>

                    <div class="mt-1">

                        @if($correctiveAction->responsibleUser)

                            {{ $correctiveAction->responsibleUser->name }}

                        @else

                            <span class="text-muted">
                                —
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Responsible Name --}}

                <div class="col-md-4 mb-4">

                    <small class="text-muted d-block">
                        Responsible Person
                    </small>

                    <div class="mt-1">

                        {{ $correctiveAction->responsible_name ?? '—' }}

                    </div>

                </div>


                {{-- Due Date --}}

                <div class="col-md-4 mb-4">

                    <small class="text-muted d-block">
                        Due Date
                    </small>

                    <div class="mt-1">

                        @if($correctiveAction->due_date)

                            {{ $correctiveAction->due_date->format('d-m-Y') }}

                            @if($correctiveAction->is_overdue)

                                <span class="badge bg-danger ms-2">
                                    Overdue
                                </span>

                            @endif

                        @else

                            —

                        @endif

                    </div>

                </div>


                {{-- Completed Date --}}

                <div class="col-md-4 mb-4">

                    <small class="text-muted d-block">
                        Completed Date
                    </small>

                    <div class="mt-1">

                        {{ $correctiveAction->completed_date
                            ? $correctiveAction->completed_date->format('d-m-Y')
                            : '—'
                        }}

                    </div>

                </div>


                {{-- Completed By --}}

                <div class="col-md-4 mb-4">

                    <small class="text-muted d-block">
                        Completed By
                    </small>

                    <div class="mt-1">

                        {{ $correctiveAction->completedBy?->name ?? '—' }}

                    </div>

                </div>


                {{-- Created By --}}

                <div class="col-md-4 mb-4">

                    <small class="text-muted d-block">
                        Created By
                    </small>

                    <div class="mt-1">

                        {{ $correctiveAction->creator?->name ?? '—' }}

                    </div>

                </div>


                {{-- Remarks --}}

                <div class="col-md-12">

                    <small class="text-muted d-block">
                        Remarks
                    </small>

                    <div class="mt-1">

                        {!! nl2br(
                            e(
                                $correctiveAction->remarks ?? '—'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        VERIFICATION INFORMATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Verification</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <small class="text-muted d-block">
                        Verification Status
                    </small>

                    <div class="mt-1">

                        @if(
                            $correctiveAction->verification_status
                            === 'Verified'
                        )

                            <span class="badge bg-success">
                                Verified
                            </span>

                        @elseif(
                            $correctiveAction->verification_status
                            === 'Rejected'
                        )

                            <span class="badge bg-danger">
                                Rejected
                            </span>

                        @else

                            <span class="badge bg-warning text-dark">
                                Pending
                            </span>

                        @endif

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted d-block">
                        Verified Date
                    </small>

                    <div class="mt-1">

                        {{ $correctiveAction->verified_date
                            ? $correctiveAction->verified_date->format('d-m-Y')
                            : '—'
                        }}

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted d-block">
                        Verified By
                    </small>

                    <div class="mt-1">

                        {{ $correctiveAction->verifiedBy?->name ?? '—' }}

                    </div>

                </div>


                <div class="col-md-12">

                    <small class="text-muted d-block">
                        Verification Remarks
                    </small>

                    <div class="mt-1">

                        {!! nl2br(
                            e(
                                $correctiveAction->verification_remarks
                                ?? '—'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        WORKFLOW ACTIONS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Workflow</strong>
        </div>

        <div class="card-body">

            {{-- =================================================
                OPEN
            ================================================== --}}

            @if(
                $correctiveAction->status === 'Open'
            )

                <div class="alert alert-secondary">

                    <strong>
                        Action is currently Open.
                    </strong>

                    <br>

                    Start the corrective action when work begins.

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.construction.hse.observations.corrective-actions.start',
                        [
                            'project' => $project,
                            'observation' => $observation,
                            'correctiveAction' => $correctiveAction,
                        ]
                    ) }}"
                    class="d-inline"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-warning"
                    >

                        <i class="bi bi-play-fill me-1"></i>

                        Start Action

                    </button>

                </form>

            @endif


            {{-- =================================================
                IN PROGRESS
            ================================================== --}}

            @if(
                $correctiveAction->status === 'In Progress'
            )

                <div class="alert alert-warning">

                    <strong>
                        Action is In Progress.
                    </strong>

                    <br>

                    Once the corrective action has been completed,
                    mark it as resolved for HSE verification.

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.construction.hse.observations.corrective-actions.resolve',
                        [
                            'project' => $project,
                            'observation' => $observation,
                            'correctiveAction' => $correctiveAction,
                        ]
                    ) }}"
                    class="d-inline"
                    onsubmit="return confirm('Mark this corrective action as resolved?');"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-check-circle me-1"></i>

                        Mark as Resolved

                    </button>

                </form>

            @endif


            {{-- =================================================
                RESOLVED → VERIFICATION
            ================================================== --}}

            @if(
                $correctiveAction->status === 'Resolved'
                &&
                $correctiveAction->verification_status === 'Pending'
            )

                <div class="alert alert-primary">

                    <strong>
                        Action has been resolved.
                    </strong>

                    <br>

                    HSE must verify whether the corrective action
                    has been satisfactorily implemented.

                </div>


                {{-- Verify --}}

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.construction.hse.observations.corrective-actions.verify',
                        [
                            'project' => $project,
                            'observation' => $observation,
                            'correctiveAction' => $correctiveAction,
                        ]
                    ) }}"
                    class="mb-3"
                >

                    @csrf

                    <div class="mb-3">

                        <label class="form-label">
                            Verification Remarks
                        </label>

                        <textarea
                            name="verification_remarks"
                            rows="3"
                            class="form-control"
                            placeholder="Enter verification remarks..."
                        ></textarea>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-success"
                        onclick="return confirm('Verify this corrective action?');"
                    >

                        <i class="bi bi-check-circle me-1"></i>

                        Verify Corrective Action

                    </button>

                </form>


                {{-- Reject Verification --}}

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.construction.hse.observations.corrective-actions.reject-verification',
                        [
                            'project' => $project,
                            'observation' => $observation,
                            'correctiveAction' => $correctiveAction,
                        ]
                    ) }}"
                    onsubmit="return confirm('Reject this verification and return the action to In Progress?');"
                >

                    @csrf

                    <div class="mb-3">

                        <label class="form-label">
                            Rejection Reason *
                        </label>

                        <textarea
                            name="verification_remarks"
                            rows="3"
                            class="form-control"
                            required
                            placeholder="Explain why the corrective action is not satisfactory..."
                        ></textarea>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-outline-danger"
                    >

                        <i class="bi bi-x-circle me-1"></i>

                        Reject Verification

                    </button>

                </form>

            @endif


            {{-- =================================================
                VERIFIED → CLOSE
            ================================================== --}}

            @if(
                $correctiveAction->status === 'Verified'
                &&
                $correctiveAction->verification_status === 'Verified'
            )

                <div class="alert alert-success">

                    <strong>
                        Corrective action has been verified successfully.
                    </strong>

                    <br>

                    The action can now be formally closed.

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.construction.hse.observations.corrective-actions.close',
                        [
                            'project' => $project,
                            'observation' => $observation,
                            'correctiveAction' => $correctiveAction,
                        ]
                    ) }}"
                    onsubmit="return confirm('Close this corrective action?');"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-dark"
                    >

                        <i class="bi bi-lock-fill me-1"></i>

                        Close Corrective Action

                    </button>

                </form>

            @endif


            {{-- =================================================
                CLOSED
            ================================================== --}}

            @if(
                $correctiveAction->status === 'Closed'
            )

                <div class="alert alert-dark mb-0">

                    <i class="bi bi-lock-fill me-1"></i>

                    <strong>
                        Corrective action is closed.
                    </strong>

                    <br>

                    This action can no longer be modified.

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
        DELETE
    ========================================================== --}}

    @if(
        $correctiveAction->status !== 'Closed'
    )

        <div class="card border-danger">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <strong class="text-danger">
                            Delete Corrective Action
                        </strong>

                        <div class="text-muted">
                            This action cannot be undone.
                        </div>

                    </div>


                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.construction.hse.observations.corrective-actions.destroy',
                            [
                                'project' => $project,
                                'observation' => $observation,
                                'correctiveAction' => $correctiveAction,
                            ]
                        ) }}"
                        onsubmit="return confirm('Are you sure you want to delete this corrective action?');"
                    >

                        @csrf

                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn btn-danger"
                        >

                            <i class="bi bi-trash me-1"></i>

                            Delete

                        </button>

                    </form>

                </div>

            </div>

        </div>

    @endif

</div>

@endsection