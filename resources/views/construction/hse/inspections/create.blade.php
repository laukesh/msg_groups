@extends('layouts.app')

@section('content')

<div class="container-fluid">


    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="text-muted small">

                Project:

                <strong>
                    {{ $project->project_code ?? '—' }}
                </strong>

            </div>


            <h3 class="mb-1">
                Add HSE Inspection
            </h3>


            <div class="text-muted">

                {{ $project->project_name
                    ?? $project->name
                    ?? 'Project'
                }}

            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.hse.inspections.index',
                [
                    'project' => $project,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >

            <i class="bi bi-arrow-left me-1"></i>

            Back to Inspections

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
        'construction.hse.inspections._form',
        [
            'action' => route(
                'admin.projects.construction.hse.inspections.store',
                [
                    'project' => $project,
                ]
            ),

            'method' => null,

            'inspection' => null,

            'inspectionNumber' => $inspectionNumber,

            'project' => $project,

            'users' => $users,

            'contracts' => $contracts,

            'cancelUrl' => route(
                'admin.projects.construction.hse.inspections.index',
                [
                    'project' => $project,
                ]
            ),

            'submitLabel' => 'Create Inspection',
        ]
    )

</div>

@endsection