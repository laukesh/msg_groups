@extends('layouts.app')

@section('content')

<div class="container-fluid">


    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">

                Project:

                <strong>
                    {{ $project->project_code ?? '—' }}
                </strong>

            </div>


            <h3 class="mb-1">
                Edit Incident Action
            </h3>


            <div class="text-muted">

                Action:

                <strong>
                    {{ $action->action_number }}
                </strong>


                <span class="mx-1">
                    •
                </span>


                Incident:

                <strong>
                    {{ $incident->incident_number }}
                </strong>

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.hse.incidents.actions.show',
                    [
                        'project' => $project,
                        'incident' => $incident,
                        'action' => $action,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >

                <i class="bi bi-arrow-left me-1"></i>

                Back to Action

            </a>

        </div>

    </div>



    {{-- =========================================================
        VALIDATION ERRORS
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
        CURRENT WORKFLOW STATUS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-body">

            <div class="row align-items-center">


                <div class="col-md-6">

                    <div class="text-muted small">
                        Current Action Status
                    </div>

                    @php

                        $statusClass = match($action->status) {

                            'Open' =>
                                'bg-secondary',

                            'In Progress' =>
                                'bg-warning text-dark',

                            'Completed' =>
                                'bg-primary',

                            'Closed' =>
                                'bg-dark',

                            default =>
                                'bg-secondary',

                        };

                    @endphp


                    <span class="badge {{ $statusClass }}">
                        {{ $action->status }}
                    </span>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Verification Status
                    </div>


                    @php

                        $verificationClass =
                            match($action->verification_status) {

                                'Pending' =>
                                    'bg-warning text-dark',

                                'Verified' =>
                                    'bg-success',

                                'Rejected' =>
                                    'bg-danger',

                                default =>
                                    'bg-secondary',

                            };

                    @endphp


                    <span class="badge {{ $verificationClass }}">
                        {{ $action->verification_status ?? '—' }}
                    </span>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        WARNING
    ========================================================== --}}

    @if($action->status === 'Closed')

        <div class="alert alert-danger">

            <i class="bi bi-lock-fill me-1"></i>

            This incident action is

            <strong>
                Closed
            </strong>

            and cannot be edited.

        </div>

    @elseif($action->status === 'Completed')

        <div class="alert alert-warning">

            <i class="bi bi-exclamation-triangle me-1"></i>

            This action has been completed and is currently
            part of the verification workflow.

            Editing the action may affect its verification.

        </div>

    @elseif($action->verification_status === 'Rejected')

        <div class="alert alert-warning">

            <i class="bi bi-arrow-repeat me-1"></i>

            Verification was rejected.

            Correct the action details and then complete the
            action again for verification.

        </div>

    @endif



    {{-- =========================================================
        FORM
    ========================================================== --}}

    @include(
        'construction.hse.incident-actions._form',
        [
            'formAction' => route(
                'admin.projects.construction.hse.incidents.actions.update',
                [
                    'project' => $project,
                    'incident' => $incident,
                    'action' => $action,
                ]
            ),

            'formMethod' => 'PUT',

            'actionModel' => $action,

            'actionNumber' => $action->action_number,

            'users' => $users,

            'cancelUrl' => route(
                'admin.projects.construction.hse.incidents.actions.show',
                [
                    'project' => $project,
                    'incident' => $incident,
                    'action' => $action,
                ]
            ),

            'submitLabel' => 'Update Incident Action',
        ]
    )

</div>

@endsection