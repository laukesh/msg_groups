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
                Edit Incident Witness
            </h3>


            <div class="text-muted">

                {{ $witness->witness_name }}

                @if($witness->witness_type)

                    <span class="mx-1">
                        •
                    </span>

                    {{ $witness->witness_type }}

                @endif

            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.hse.incidents.witnesses.show',
                [
                    'project' => $project,
                    'incident' => $incident,
                    'witness' => $witness,
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
        'construction.hse.incident-witnesses._form',
        [
            'action' => route(
                'admin.projects.construction.hse.incidents.witnesses.update',
                [
                    'project' => $project,
                    'incident' => $incident,
                    'witness' => $witness,
                ]
            ),

            'method' => 'PUT',

            'witness' => $witness,

            'cancelUrl' => route(
                'admin.projects.construction.hse.incidents.witnesses.show',
                [
                    'project' => $project,
                    'incident' => $incident,
                    'witness' => $witness,
                ]
            ),

            'submitLabel' => 'Update Witness',
        ]
    )

</div>

@endsection