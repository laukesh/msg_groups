@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Add Corrective Action
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


        <a
            href="{{ route(
                'admin.projects.construction.hse.observations.corrective-actions.index',
                [
                    'project' => $project,
                    'observation' => $observation,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Corrective Actions
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


    {{-- FORM --}}
    @include(
        'construction.hse.corrective-actions._form',
        [
            'action' => route(
                'admin.projects.construction.hse.observations.corrective-actions.store',
                [
                    'project' => $project,
                    'observation' => $observation,
                ]
            ),

            'method' => null,

            'correctiveAction' => null,

            'project' => $project,

            'observation' => $observation,

            'users' => $users,
        ]
    )

</div>

@endsection