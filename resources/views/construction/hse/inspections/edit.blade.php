@extends('layouts.app')

@section('content')

<div class="container-fluid">


    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="text-muted small">

                Inspection:

                <strong>
                    {{ $inspection->inspection_number }}
                </strong>

            </div>


            <h3 class="mb-1">
                Edit HSE Inspection
            </h3>


            <div class="text-muted">

                {{ $project->project_code ?? '—' }}

                -

                {{ $project->project_name
                    ?? $project->name
                    ?? 'Project'
                }}

            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.hse.inspections.show',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >

            <i class="bi bi-arrow-left me-1"></i>

            Back

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
        CLOSED WARNING
    ========================================================== --}}

    @if($inspection->status === 'Closed')

        <div class="alert alert-warning">

            <i class="bi bi-exclamation-triangle me-1"></i>

            This inspection is already

            <strong>
                Closed
            </strong>.

            Editing a closed inspection may affect the
            inspection history.

        </div>

    @endif


    {{-- =========================================================
        FORM
    ========================================================== --}}

    @include(
        'construction.hse.inspections._form',
        [
            'action' => route(
                'admin.projects.construction.hse.inspections.update',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                ]
            ),

            'method' => 'PUT',

            'inspection' => $inspection,

            'inspectionNumber' =>
                $inspection->inspection_number,

            'project' => $project,

            'users' => $users,

            'contracts' => $contracts,

            'cancelUrl' => route(
                'admin.projects.construction.hse.inspections.show',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                ]
            ),

            'submitLabel' => 'Update Inspection',
        ]
    )

</div>

@endsection