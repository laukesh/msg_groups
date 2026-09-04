@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Edit Corrective Action
            </h3>

            <p class="text-muted mb-0">

                Action:
                <strong>
                    {{ $correctiveAction->action_number }}
                </strong>

                &nbsp; | &nbsp;

                Observation:
                <strong>
                    {{ $observation->observation_number }}
                </strong>

            </p>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.hse.observations.corrective-actions.show',
                [
                    'project' => $project,
                    'observation' => $observation,
                    'correctiveAction' => $correctiveAction,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Corrective Action
        </a>

    </div>


    {{-- ERRORS --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- STATUS WARNING --}}
    @if(
        in_array(
            $correctiveAction->status,
            ['Resolved', 'Verified', 'Closed'],
            true
        )
    )

        <div class="alert alert-warning">

            <i class="bi bi-exclamation-triangle me-1"></i>

            This corrective action has reached
            <strong>{{ $correctiveAction->status }}</strong>
            status.

            Please review the workflow before making changes.

        </div>

    @endif


    {{-- FORM --}}
    @include(
        'construction.hse.corrective-actions._form',
        [
            'action' => route(
                'admin.projects.construction.hse.observations.corrective-actions.update',
                [
                    'project' => $project,
                    'observation' => $observation,
                    'correctiveAction' => $correctiveAction,
                ]
            ),

            'method' => 'PUT',

            'correctiveAction' => $correctiveAction,

            'project' => $project,

            'observation' => $observation,

            'users' => $users,
        ]
    )

</div>

@endsection