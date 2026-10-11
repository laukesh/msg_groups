@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="text-muted small">

                Investigation:

                <strong>
                    {{ $investigation->investigation_number }}
                </strong>

            </div>


            <h3 class="mb-1">
                Edit Investigation
            </h3>


            <div class="text-muted">

                Incident:

                {{ $incident->incident_number }}

                &nbsp; | &nbsp;

                {{ $project->project_code ?? '—' }}

                -

                {{ $project->project_name ?? 'Project' }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.hse.incidents.investigations.show',
                    [
                        'project' => $project,
                        'incident' => $incident,
                        'investigation' => $investigation,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >

                <i class="bi bi-arrow-left me-1"></i>

                Back

            </a>

        </div>

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
        STATUS INFORMATION
    ========================================================== --}}

    @if($investigation->status === 'Draft')

        <div class="alert alert-secondary">

            <i class="bi bi-pencil me-1"></i>

            This investigation is currently in
            <strong>Draft</strong> status.

            After saving the changes, it can be submitted
            for review.

        </div>

    @elseif($investigation->status === 'Rejected')

        <div class="alert alert-warning">

            <i class="bi bi-exclamation-triangle me-1"></i>

            This investigation was
            <strong>Rejected</strong>.

            Please review the investigation and the review
            remarks, make the required corrections, and
            resubmit it for approval.

        </div>

    @endif



    {{-- =========================================================
        FORM
    ========================================================== --}}

    @include(
        'construction.hse.incident-investigations._form',
        [
            'action' => route(
                'admin.projects.construction.hse.incidents.investigations.update',
                [
                    'project' => $project,
                    'incident' => $incident,
                    'investigation' => $investigation,
                ]
            ),

            'method' => 'PUT',

            'investigation' => $investigation,

            'investigationNumber' =>
                $investigation->investigation_number,

            'project' => $project,

            'incident' => $incident,

            'users' => $users,
        ]
    )

</div>

@endsection