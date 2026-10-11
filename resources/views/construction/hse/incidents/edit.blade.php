@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Edit HSE Incident
            </h3>

            <p class="text-muted mb-0">

                {{ $project->project_code ?? '—' }}

                @if($project->project_code && ($project->project_name ?? false))
                    -
                @endif

                {{ $project->project_name ?? $project->name ?? 'Project' }}

            </p>

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

                Back to Incident

            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.incidents.index',
                    ['project' => $project]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Incidents
            </a>

        </div>

    </div>


    {{-- INCIDENT STATUS --}}
    <div class="card mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <div class="text-muted small">
                        Incident Number
                    </div>

                    <h4 class="mb-1">
                        {{ $incident->incident_number }}
                    </h4>

                    <div class="text-muted">

                        {{ $incident->incident_type }}

                        @if($incident->incident_date)
                            -
                            {{ $incident->incident_date->format('d-m-Y') }}
                        @endif

                    </div>

                </div>


                <div class="col-md-4 text-md-end">

                    <div class="text-muted small mb-1">
                        Current Status
                    </div>


                    @switch($incident->status)

                        @case('Reported')
                            <span class="badge bg-primary fs-6">
                                Reported
                            </span>
                            @break

                        @case('Under Investigation')
                            <span class="badge bg-warning text-dark fs-6">
                                Under Investigation
                            </span>
                            @break

                        @case('Investigation Completed')
                            <span class="badge bg-info text-dark fs-6">
                                Investigation Completed
                            </span>
                            @break

                        @case('Actions Assigned')
                            <span class="badge bg-secondary fs-6">
                                Actions Assigned
                            </span>
                            @break

                        @case('Actions Completed')
                            <span class="badge bg-primary fs-6">
                                Actions Completed
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
                                {{ $incident->status }}
                            </span>

                    @endswitch

                </div>

            </div>

        </div>

    </div>


    {{-- ERRORS --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- CLOSED --}}
    @if($incident->status === 'Closed')

        <div class="alert alert-warning">

            <i class="bi bi-lock me-1"></i>

            This incident is closed and cannot be edited.

        </div>

    @else

        {{-- FORM --}}
        @include(
            'construction.hse.incidents._form',
            [
                'action' => route(
                    'admin.projects.construction.hse.incidents.update',
                    [
                        'project' => $project,
                        'incident' => $incident,
                    ]
                ),

                'method' => 'PUT',

                'incident' => $incident,

                'incidentNumber' => $incident->incident_number,

                'project' => $project,

                'contracts' => $contracts,

                'users' => $users,
            ]
        )

    @endif

</div>

@endsection