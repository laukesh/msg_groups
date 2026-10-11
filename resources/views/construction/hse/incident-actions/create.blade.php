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
                Add Incident Action
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

    </div>



    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
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
        INCIDENT SUMMARY
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Incident Summary
            </strong>

        </div>


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
                        Current Status
                    </div>

                    <span class="badge bg-info text-dark">
                        {{ $incident->status }}
                    </span>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        FORM
    ========================================================== --}}

    @include(
        'construction.hse.incident-actions._form',
        [
            'formAction' => route(
                'admin.projects.construction.hse.incidents.actions.store',
                [
                    'project' => $project,
                    'incident' => $incident,
                ]
            ),

            'formMethod' => null,

            'actionModel' => null,

            'actionNumber' => $actionNumber,

            'users' => $users,

            'cancelUrl' => route(
                'admin.projects.construction.hse.incidents.show',
                [
                    'project' => $project,
                    'incident' => $incident,
                ]
            ),

            'submitLabel' => 'Create Incident Action',
        ]
    )

</div>

@endsection