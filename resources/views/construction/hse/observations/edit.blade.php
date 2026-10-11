@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Edit Safety Observation
            </h3>

            <div class="text-muted">

                <strong>
                    {{ $observation->observation_number }}
                </strong>

                @if($project->project_code ?? false)
                    -
                    {{ $project->project_code }}
                @endif

                -

                {{ $project->project_name ?? $project->name ?? 'Project' }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.hse.observations.show',
                    [
                        'project' => $project,
                        'observation' => $observation,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Observation
            </a>

        </div>

    </div>


    {{-- VALIDATION ERRORS --}}
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


    {{-- CLOSED WARNING --}}
    @if($observation->status === 'Closed')

        <div class="alert alert-warning">

            <i class="bi bi-lock me-1"></i>

            This observation is closed and cannot be edited.

        </div>

    @else

        @include(
            'construction.hse.observations._form',
            [
                'action' => route(
                    'admin.projects.construction.hse.observations.update',
                    [
                        'project' => $project,
                        'observation' => $observation,
                    ]
                ),

                'method' => 'PUT',

                'observation' => $observation,

                'project' => $project,

                'users' => $users,

                'contractors' => $contractors,

                'contracts' => $contracts,
            ]
        )

    @endif

</div>

@endsection