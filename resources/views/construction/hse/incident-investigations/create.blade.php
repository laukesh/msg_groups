@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="text-muted small">

                Incident:

                <strong>
                    {{ $incident->incident_number }}
                </strong>

            </div>


            <h3 class="mb-1">
                Create Investigation
            </h3>


            <div class="text-muted">

                {{ $project->project_code ?? '—' }}

                -

                {{ $project->project_name ?? 'Project' }}

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
        ERRORS
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
        FORM
    ========================================================== --}}

    @include(
        'construction.hse.incident-investigations._form',
        [
            'action' => route(
                'admin.projects.construction.hse.incidents.investigations.store',
                [
                    'project' => $project,
                    'incident' => $incident,
                ]
            ),

            'method' => null,

            'investigation' => null,

            'investigationNumber' => $investigationNumber,

            'project' => $project,

            'incident' => $incident,

            'users' => $users,
        ]
    )

</div>

@endsection