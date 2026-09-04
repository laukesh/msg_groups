@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Meeting:
                <strong>
                    {{ $meeting->meeting_number }}
                </strong>
            </div>

            <h3 class="mb-1">
                Edit Participant
            </h3>

            <div class="text-muted">

                {{ $participant->participant_name }}

                <span class="mx-1">•</span>

                {{ $meeting->title }}

            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.hse.safety-meetings.participants.show',
                [
                    'project' => $project,
                    'meeting' => $meeting,
                    'participant' => $participant,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>

    </div>


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


    @include(
        'construction.hse.safety-meeting-participants._form',
        [
            'formAction' => route(
                'admin.projects.construction.hse.safety-meetings.participants.update',
                [
                    'project' => $project,
                    'meeting' => $meeting,
                    'participant' => $participant,
                ]
            ),

            'formMethod' => 'PUT',

            'participant' => $participant,

            'project' => $project,

            'meeting' => $meeting,

            'cancelUrl' => route(
                'admin.projects.construction.hse.safety-meetings.participants.show',
                [
                    'project' => $project,
                    'meeting' => $meeting,
                    'participant' => $participant,
                ]
            ),

            'submitLabel' => 'Update Participant',
        ]
    )

</div>

@endsection