@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Report HSE Incident
            </h3>

            <p class="text-muted mb-0">

                {{ $project->project_code ?? '—' }}

                @if($project->project_code && ($project->project_name ?? false))
                    -
                @endif

                {{ $project->project_name ?? $project->name ?? 'Project' }}

            </p>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.hse.incidents.index',
                ['project' => $project]
            ) }}"
            class="btn btn-outline-secondary"
        >

            <i class="bi bi-arrow-left me-1"></i>

            Back to Incidents

        </a>

    </div>


    {{-- VALIDATION ERRORS --}}
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


    {{-- FORM --}}
    @include(
        'construction.hse.incidents._form',
        [
            'action' => route(
                'admin.projects.construction.hse.incidents.store',
                ['project' => $project]
            ),

            'method' => null,

            'incident' => null,

            'incidentNumber' => $incidentNumber,

            'project' => $project,

            'contracts' => $contracts,

            'users' => $users,
        ]
    )

</div>

@endsection