@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Safety Observation
            </h3>

            <div class="text-muted">
                {{ $observation->observation_number }}
            </div>

        </div>


        <div class="d-flex gap-2">

            @if($observation->status !== 'Closed')

                <a
                    href="{{ route(
                        'admin.projects.construction.hse.observations.edit',
                        [
                            'project' => $project,
                            'observation' => $observation
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    <i class="bi bi-pencil me-1"></i>
                    Edit
                </a>

            @endif


            <a
                href="{{ route(
                    'admin.projects.construction.hse.observations.index',
                    [
                        'project' => $project
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
        BASIC INFORMATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Observation Information</strong>
        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Observation Number
                    </div>

                    <div class="fw-semibold">
                        {{ $observation->observation_number }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Observation Date
                    </div>

                    <div>

                        {{ $observation->observation_date
                            ? $observation->observation_date->format('d-m-Y')
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Observation Time
                    </div>

                    <div>
                        {{ $observation->observation_time ?: '-' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Severity
                    </div>


                    @if($observation->severity === 'Critical')

                        <span class="badge bg-danger">
                            Critical
                        </span>

                    @elseif($observation->severity === 'High')

                        <span class="badge bg-warning text-dark">
                            High
                        </span>

                    @elseif($observation->severity === 'Medium')

                        <span class="badge bg-info text-dark">
                            Medium
                        </span>

                    @else

                        <span class="badge bg-success">
                            Low
                        </span>

                    @endif

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Location
                    </div>

                    <div class="fw-semibold">
                        {{ $observation->location ?: '-' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Category
                    </div>

                    <div>
                        {{ $observation->category ?: '-' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Status
                    </div>


                    @if($observation->status === 'Closed')

                        <span class="badge bg-dark">
                            Closed
                        </span>

                    @elseif($observation->status === 'Resolved')

                        <span class="badge bg-primary">
                            Resolved
                        </span>

                    @elseif($observation->status === 'Verified')

                        <span class="badge bg-success">
                            Verified
                        </span>

                    @elseif($observation->status === 'In Progress')

                        <span class="badge bg-warning text-dark">
                            In Progress
                        </span>

                    @elseif($observation->status === 'Rejected')

                        <span class="badge bg-danger">
                            Rejected
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            {{ $observation->status }}
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        OBSERVATION WORKFLOW
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Observation Workflow</strong>
        </div>


        <div class="card-body">

            {{-- OPEN --}}

            @if($observation->status === 'Open')

                <div class="alert alert-secondary">

                    <strong>
                        Observation is Open.
                    </strong>

                    <br>

                    Start the observation to begin corrective action.

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.construction.hse.observations.start',
                        [
                            'project' => $project,
                            'observation' => $observation,
                        ]
                    ) }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-warning"
                    >
                        <i class="bi bi-play-fill me-1"></i>
                        Start Observation
                    </button>

                </form>

            @endif


            {{-- IN PROGRESS --}}

            @if($observation->status === 'In Progress')

                <div class="alert alert-warning">

                    <strong>
                        Observation is In Progress.
                    </strong>

                    <br>

                    Corrective actions must be completed and verified
                    before this observation can be verified.

                </div>

            @endif


            {{-- VERIFIED --}}

            @if($observation->status === 'Verified')

                <div class="alert alert-success">

                    <strong>
                        Observation Verified
                    </strong>

                    <br>

                    All corrective actions have been verified.

                    The observation can now be closed.

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.construction.hse.observations.close',
                        [
                            'project' => $project,
                            'observation' => $observation,
                        ]
                    ) }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-dark"
                        onclick="return confirm('Close this observation?');"
                    >

                        <i class="bi bi-lock-fill me-1"></i>

                        Close Observation

                    </button>

                </form>

            @endif


            {{-- CLOSED --}}

            @if($observation->status === 'Closed')

                <div class="alert alert-dark">

                    <i class="bi bi-lock-fill me-1"></i>

                    <strong>
                        Observation Closed
                    </strong>

                    <br>

                    This HSE observation has been completely closed.


                    @if($observation->closed_date)

                        <br>

                        Closed on:

                        <strong>
                            {{ $observation->closed_date->format('d-m-Y') }}
                        </strong>

                    @endif


                    @if($observation->closedBy)

                        <br>

                        Closed by:

                        <strong>
                            {{ $observation->closedBy->name }}
                        </strong>

                    @endif

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.construction.hse.observations.reopen',
                        [
                            'project' => $project,
                            'observation' => $observation,
                        ]
                    ) }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-warning"
                        onclick="return confirm('Reopen this observation?');"
                    >

                        <i class="bi bi-unlock me-1"></i>

                        Reopen Observation

                    </button>

                </form>

            @endif

        </div>

    </div>


    {{-- =========================================================
        CORRECTIVE ACTIONS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Corrective Actions
                </strong>


                @if($observation->status !== 'Closed')

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.observations.corrective-actions.create',
                            [
                                'project' => $project,
                                'observation' => $observation,
                            ]
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >

                        <i class="bi bi-plus-lg me-1"></i>

                        Add Corrective Action

                    </a>

                @else

                    <button
                        type="button"
                        class="btn btn-sm btn-secondary"
                        disabled
                    >

                        <i class="bi bi-lock me-1"></i>

                        Add Corrective Action

                    </button>

                @endif

            </div>

        </div>


        <div class="card-body">

            @php

                $correctiveActions =
                    $observation->correctiveActions;

                $totalActions =
                    $correctiveActions->count();

                $openActions =
                    $correctiveActions
                        ->where('status', 'Open')
                        ->count();

                $inProgressActions =
                    $correctiveActions
                        ->where('status', 'In Progress')
                        ->count();

                $resolvedActions =
                    $correctiveActions
                        ->where('status', 'Resolved')
                        ->count();

                $verifiedActions =
                    $correctiveActions
                        ->where('status', 'Verified')
                        ->count();

                $closedActions =
                    $correctiveActions
                        ->where('status', 'Closed')
                        ->count();

            @endphp


            {{-- SUMMARY --}}

            <div class="row mb-4">

                <div class="col-md-2">

                    <div class="border rounded p-3 text-center">

                        <small class="text-muted d-block">
                            Total
                        </small>

                        <h4 class="mb-0">
                            {{ $totalActions }}
                        </h4>

                    </div>

                </div>


                <div class="col-md-2">

                    <div class="border rounded p-3 text-center">

                        <small class="text-muted d-block">
                            Open
                        </small>

                        <h4 class="mb-0">
                            {{ $openActions }}
                        </h4>

                    </div>

                </div>


                <div class="col-md-2">

                    <div class="border rounded p-3 text-center">

                        <small class="text-muted d-block">
                            In Progress
                        </small>

                        <h4 class="mb-0">
                            {{ $inProgressActions }}
                        </h4>

                    </div>

                </div>


                <div class="col-md-2">

                    <div class="border rounded p-3 text-center">

                        <small class="text-muted d-block">
                            Resolved
                        </small>

                        <h4 class="mb-0">
                            {{ $resolvedActions }}
                        </h4>

                    </div>

                </div>


                <div class="col-md-2">

                    <div class="border rounded p-3 text-center">

                        <small class="text-muted d-block">
                            Verified
                        </small>

                        <h4 class="mb-0 text-success">
                            {{ $verifiedActions }}
                        </h4>

                    </div>

                </div>


                <div class="col-md-2">

                    <div class="border rounded p-3 text-center">

                        <small class="text-muted d-block">
                            Closed
                        </small>

                        <h4 class="mb-0">
                            {{ $closedActions }}
                        </h4>

                    </div>

                </div>

            </div>


            {{-- TABLE --}}

            @if($totalActions > 0)

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>

                            <tr>

                                <th>
                                    Action No.
                                </th>

                                <th>
                                    Action
                                </th>

                                <th>
                                    Responsible
                                </th>

                                <th>
                                    Due Date
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        @foreach(
                            $correctiveActions
                            as $correctiveAction
                        )

                            <tr>

                                <td>

                                    <strong>
                                        {{ $correctiveAction->action_number }}
                                    </strong>

                                </td>


                                <td>

                                    {{ \Illuminate\Support\Str::limit(
                                        $correctiveAction->action_description,
                                        100
                                    ) }}

                                </td>


                                <td>

                                    {{ $correctiveAction->responsibleUser?->name
                                        ?? $correctiveAction->responsible_name
                                        ?? '—'
                                    }}

                                </td>


                                <td>

                                    @if($correctiveAction->due_date)

                                        {{ $correctiveAction->due_date->format('d-m-Y') }}

                                        @if($correctiveAction->is_overdue)

                                            <br>

                                            <span class="badge bg-danger">
                                                Overdue
                                            </span>

                                        @endif

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    @switch($correctiveAction->status)

                                        @case('Open')

                                            <span class="badge bg-secondary">
                                                Open
                                            </span>

                                            @break

                                        @case('In Progress')

                                            <span class="badge bg-warning text-dark">
                                                In Progress
                                            </span>

                                            @break

                                        @case('Resolved')

                                            <span class="badge bg-primary">
                                                Resolved
                                            </span>

                                            @break

                                        @case('Verified')

                                            <span class="badge bg-success">
                                                Verified
                                            </span>

                                            @break

                                        @case('Closed')

                                            <span class="badge bg-dark">
                                                Closed
                                            </span>

                                            @break

                                        @default

                                            <span class="badge bg-secondary">
                                                {{ $correctiveAction->status }}
                                            </span>

                                    @endswitch

                                </td>


                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.observations.corrective-actions.show',
                                            [
                                                'project' => $project,
                                                'observation' => $observation,
                                                'correctiveAction' => $correctiveAction,
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

                        No corrective actions have been created
                        for this observation.

                    </div>


                    @if($observation->status !== 'Closed')

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.observations.corrective-actions.create',
                                [
                                    'project' => $project,
                                    'observation' => $observation,
                                ]
                            ) }}"
                            class="btn btn-primary"
                        >

                            <i class="bi bi-plus-lg me-1"></i>

                            Create Corrective Action

                        </a>

                    @else

                        <span class="text-muted">

                            Observation is closed.

                            Reopen it to add a corrective action.

                        </span>

                    @endif

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
        CONTRACTOR
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Contractor / Contract</strong>
        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Contract Number
                    </div>

                    <div class="fw-semibold">
                        {{ $observation->contract?->contract_number ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Contractor
                    </div>

                    <div class="fw-semibold">
                        {{ $observation->contract?->bidder_name ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Contractor Code
                    </div>

                    <div>
                        {{ $observation->contract?->contract_number ?? '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DESCRIPTION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Observation Description</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $observation->description
                    ?: '-'
                )
            ) !!}

        </div>

    </div>


    {{-- =========================================================
        ACTION & RESPONSIBILITY
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Action & Responsibility</strong>
        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Reported By
                    </div>

                    <div>
                        {{ $observation->reporter?->name
                            ?? $observation->reported_by_name
                            ?? '-'
                        }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Responsible User
                    </div>

                    <div>
                        {{ $observation->responsibleUser?->name ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Due Date
                    </div>

                    <div>

                        @if($observation->due_date)

                            {{ $observation->due_date->format('d-m-Y') }}

                            @if($observation->is_overdue)

                                <span class="badge bg-danger ms-1">
                                    Overdue
                                </span>

                            @endif

                        @else

                            -

                        @endif

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Immediate Action Taken
                    </div>

                    <div>

                        {!! nl2br(
                            e(
                                $observation->immediate_action
                                ?: '-'
                            )
                        ) !!}

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Corrective Action
                    </div>

                    <div>

                        {!! nl2br(
                            e(
                                $observation->corrective_action
                                ?: '-'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        CLOSURE
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Closure & Remarks</strong>
        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Closed Date
                    </div>

                    <div>

                        {{ $observation->closed_date
                            ? $observation->closed_date->format('d-m-Y')
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Closed By
                    </div>

                    <div>
                        {{ $observation->closedBy?->name ?? '-' }}
                    </div>

                </div>


                <div class="col-md-12">

                    <div class="text-muted small">
                        Closure Remarks
                    </div>

                    <div>

                        {!! nl2br(
                            e(
                                $observation->closure_remarks
                                ?: '-'
                            )
                        ) !!}

                    </div>

                </div>


                <div class="col-md-12">

                    <div class="text-muted small">
                        Remarks
                    </div>

                    <div>

                        {!! nl2br(
                            e(
                                $observation->remarks
                                ?: '-'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DELETE
    ========================================================== --}}

    <div class="card border-danger">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong class="text-danger">
                        Delete Safety Observation
                    </strong>

                    <div class="text-muted">
                        This action cannot be undone.
                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.construction.hse.observations.destroy',
                        [
                            'project' => $project,
                            'observation' => $observation
                        ]
                    ) }}"
                    onsubmit="return confirm('Are you sure you want to delete this safety observation?');"
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

</div>

@endsection