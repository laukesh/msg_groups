@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="text-muted small">
                Incident:
                <strong>
                    {{ $incident->incident_number }}
                </strong>
            </div>

            <h3 class="mb-1">
                Edit Incident Person
            </h3>

            <div class="text-muted">

                {{ $person->person_name }}

                @if($person->person_type)

                    <span class="mx-1">
                        •
                    </span>

                    {{ $person->person_type }}

                @endif

            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.hse.incidents.persons.show',
                [
                    'project' => $project,
                    'incident' => $incident,
                    'person' => $person,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >

            <i class="bi bi-arrow-left me-1"></i>

            Back

        </a>

    </div>


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


    @include(
        'construction.hse.incident-persons._form',
        [
            'action' => route(
                'admin.projects.construction.hse.incidents.persons.update',
                [
                    'project' => $project,
                    'incident' => $incident,
                    'person' => $person,
                ]
            ),

            'method' => 'PUT',

            'person' => $person,

            'cancelUrl' => route(
                'admin.projects.construction.hse.incidents.persons.show',
                [
                    'project' => $project,
                    'incident' => $incident,
                    'person' => $person,
                ]
            ),

            'submitLabel' => 'Update Incident Person',
        ]
    )

</div>

@endsection