@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Corrective Actions
            </h3>

            <p class="text-muted mb-0">

                Project:
                <strong>
                    {{ $project->project_name ?? $project->name ?? '—' }}
                </strong>

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
                    'admin.projects.construction.hse.observations.corrective-actions.create',
                    [
                        'project' => $project,
                        'observation' => $observation,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Add Corrective Action
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.observations.show',
                    [
                        'project' => $project,
                        'observation' => $observation,
                    ]
                ) }}"
                class="btn btn-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Observation
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
        OBSERVATION SUMMARY
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Observation Summary</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">

                    <small class="text-muted d-block">
                        Observation Number
                    </small>

                    <strong>
                        {{ $observation->observation_number }}
                    </strong>

                </div>


                <div class="col-md-3 mb-3">

                    <small class="text-muted d-block">
                        Date
                    </small>

                    {{ $observation->observation_date
                        ? $observation->observation_date->format('d-m-Y')
                        : '—'
                    }}

                </div>


                <div class="col-md-3 mb-3">

                    <small class="text-muted d-block">
                        Category
                    </small>

                    {{ $observation->category ?? '—' }}

                </div>


                <div class="col-md-3 mb-3">

                    <small class="text-muted d-block">
                        Severity
                    </small>

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
                            {{ $observation->severity ?? 'Low' }}
                        </span>

                    @endif

                </div>


                <div class="col-md-12">

                    <small class="text-muted d-block">
                        Description
                    </small>

                    <div class="mt-1">
                        {{ $observation->description }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        CORRECTIVE ACTIONS TABLE
    ========================================================== --}}

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Corrective Action Records
                </strong>

                <span class="badge bg-secondary">
                    {{ $correctiveActions->total() }} Total
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th>
                                Action No.
                            </th>

                            <th>
                                Action Description
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
                                Verification
                            </th>

                            <th width="260">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse(
                        $correctiveActions
                        as $correctiveAction
                    )

                        <tr>

                            {{-- Action Number --}}

                            <td>

                                <strong>
                                    {{ $correctiveAction->action_number }}
                                </strong>

                            </td>


                            {{-- Description --}}

                            <td>

                                <div style="max-width: 350px;">

                                    {{ \Illuminate\Support\Str::limit(
                                        $correctiveAction->action_description,
                                        120
                                    ) }}

                                </div>

                            </td>


                            {{-- Responsible --}}

                            <td>

                                @if($correctiveAction->responsibleUser)

                                    <strong>
                                        {{ $correctiveAction->responsibleUser->name }}
                                    </strong>

                                @elseif($correctiveAction->responsible_name)

                                    {{ $correctiveAction->responsible_name }}

                                @else

                                    <span class="text-muted">
                                        Not Assigned
                                    </span>

                                @endif

                            </td>


                            {{-- Due Date --}}

                            <td>

                                @if($correctiveAction->due_date)

                                    {{ $correctiveAction->due_date->format('d-m-Y') }}

                                    @if($correctiveAction->is_overdue)

                                        <br>

                                        <span class="badge bg-danger mt-1">
                                            Overdue
                                        </span>

                                    @elseif(
                                        $correctiveAction->days_remaining !== null
                                        &&
                                        $correctiveAction->days_remaining >= 0
                                        &&
                                        $correctiveAction->days_remaining <= 3
                                    )

                                        <br>

                                        <span class="badge bg-warning text-dark mt-1">
                                            {{ $correctiveAction->days_remaining }}
                                            day(s) left
                                        </span>

                                    @endif

                                @else

                                    <span class="text-muted">
                                        No Due Date
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}

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


                            {{-- Verification --}}

                            <td>

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

                            </td>


                            {{-- Actions --}}

                            <td>

                                <div class="d-flex flex-wrap gap-1">

                                    {{-- View --}}

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


                                    {{-- Edit --}}

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
                                            class="btn btn-sm btn-outline-secondary"
                                        >
                                            Edit
                                        </a>

                                    @endif


                                    {{-- Start --}}

                                    @if(
                                        $correctiveAction->status === 'Open'
                                    )

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
                                                class="btn btn-sm btn-warning"
                                            >
                                                Start
                                            </button>

                                        </form>

                                    @endif


                                    {{-- Resolve --}}

                                    @if(
                                        in_array(
                                            $correctiveAction->status,
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
                                                class="btn btn-sm btn-primary"
                                            >
                                                Resolve
                                            </button>

                                        </form>

                                    @endif


                                    {{-- Verify --}}

                                    @if(
                                        $correctiveAction->status === 'Resolved'
                                        &&
                                        $correctiveAction->verification_status === 'Pending'
                                    )

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
                                            class="d-inline"
                                            onsubmit="return confirm('Verify this corrective action?');"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-success"
                                            >
                                                Verify
                                            </button>

                                        </form>

                                    @endif


                                    {{-- Close --}}

                                    @if(
                                        $correctiveAction->status === 'Verified'
                                        &&
                                        $correctiveAction->verification_status === 'Verified'
                                    )

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
                                            class="d-inline"
                                            onsubmit="return confirm('Close this corrective action?');"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-dark"
                                            >
                                                Close
                                            </button>

                                        </form>

                                    @endif


                                    {{-- Delete --}}

                                    @if(
                                        $correctiveAction->status !== 'Closed'
                                    )

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
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this corrective action?');"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                            >
                                                Delete
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5"
                            >

                                <div class="text-muted mb-2">

                                    No corrective actions have been
                                    created for this observation.

                                </div>


                                <a
                                    href="{{ route(
                                        'admin.projects.construction.hse.observations.corrective-actions.create',
                                        [
                                            'project' => $project,
                                            'observation' => $observation,
                                        ]
                                    ) }}"
                                    class="btn btn-primary btn-sm"
                                >

                                    <i class="bi bi-plus-lg me-1"></i>

                                    Add First Corrective Action

                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}

        @if($correctiveActions->hasPages())

            <div class="card-footer">

                {{ $correctiveActions->links() }}

            </div>

        @endif

    </div>

</div>

@endsection