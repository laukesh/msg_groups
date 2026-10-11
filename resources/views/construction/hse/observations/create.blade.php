@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                Add Safety Observation
            </h3>

            <div class="text-muted">
                {{ $project->project_code ?? '' }}

                @if($project->project_code && ($project->project_name ?? $project->name ?? null))
                    -
                @endif

                {{ $project->project_name ?? $project->name ?? 'Project' }}
            </div>
        </div>

        <a
            href="{{ route(
                'admin.projects.construction.hse.observations.index',
                ['project' => $project]
            ) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Observations
        </a>

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


    {{-- FORM --}}
    @include(
        'construction.hse.observations._form',
        [
            'action' => route(
                'admin.projects.construction.hse.observations.store',
                ['project' => $project]
            ),

            'method' => null,

            'observation' => null,

            'project' => $project,

            'users' => $users,

            'contractors' => $contractors,

            'contracts' => $contracts,
        ]
    )

</div>

@endsection