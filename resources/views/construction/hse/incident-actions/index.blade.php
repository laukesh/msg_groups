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
                Incident Actions
            </h3>

            <div class="text-muted">
                Incident:
                <strong>
                    {{ $incident->incident_number }}
                </strong>

                @if($incident->incident_type)
                    <span class="mx-1">•</span>
                    {{ $incident->incident_type }}
                @endif
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.hse.incidents.show',
                    [
                        'project' => $project,
                        'incident' => $incident,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Incident
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.incidents.actions.create',
                    [
                        'project' => $project,
                        'incident' => $incident,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Add Action
            </a>

        </div>

    </div>


    {{-- =========================================================
        FLASH MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- =========================================================
        INCIDENT SUMMARY
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Incident Number
                    </div>

                    <strong>
                        {{ $incident->incident_number }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Incident Type
                    </div>

                    <strong>
                        {{ $incident->incident_type ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Incident Date
                    </div>

                    <strong>
                        {{ $incident->incident_date?->format('d-m-Y') ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Incident Status
                    </div>

                    <span class="badge bg-secondary">
                        {{ $incident->status }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        ACTION REGISTER
    ========================================================== --}}

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Action Register
            </strong>

            <span class="badge bg-primary">
                {{ $incident->actions->count() }}
            </span>

        </div>


        <div class="card-body p-0">

            @if($incident->actions->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Action Number
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Description
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

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($incident->actions as $action)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.incidents.actions.show',
                                            [
                                                'project' => $project,
                                                'incident' => $incident,
                                                'action' => $action,
                                            ]
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >
                                        {{ $action->action_number }}
                                    </a>

                                </td>


                                <td>

                                    <span class="badge bg-info text-dark">
                                        {{ $action->action_type }}
                                    </span>

                                </td>


                                <td style="min-width: 250px;">

                                    <div>
                                        {{ \Illuminate\Support\Str::limit(
                                            $action->action_description,
                                            100
                                        ) }}
                                    </div>

                                </td>


                                <td>

                                    {{ $action->responsible_name
                                        ?? $action->responsibleUser?->name
                                        ?? '—'
                                    }}

                                </td>


                                <td>

                                    {{ $action->due_date
                                        ?->format('d-m-Y')
                                        ?? '—'
                                    }}

                                    @if($action->isOverdue())

                                        <div>
                                            <span class="badge bg-danger">
                                                Overdue
                                            </span>
                                        </div>

                                    @endif

                                </td>


                                <td>

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
                                        class="badge {{ $statusClass }}"
                                    >
                                        {{ $action->status }}
                                    </span>

                                </td>


                                <td>

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
                                        {{ $action->verification_status }}
                                    </span>

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.incidents.actions.show',
                                            [
                                                'project' => $project,
                                                'incident' => $incident,
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

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i
                            class="bi bi-check2-square"
                            style="font-size: 42px;"
                        ></i>

                    </div>

                    <h5>
                        No Incident Actions
                    </h5>

                    <p class="text-muted mb-3">
                        No corrective or preventive actions have
                        been assigned to this incident yet.
                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.incidents.actions.create',
                            [
                                'project' => $project,
                                'incident' => $incident,
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

</div>

@endsection