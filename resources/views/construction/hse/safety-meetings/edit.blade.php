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
                Edit Safety Meeting
            </h3>

            <div class="text-muted">
                {{ $meeting->title }}
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.hse.safety-meetings.show',
                [
                    'project' => $project,
                    'meeting' => $meeting,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Meeting
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
        'construction.hse.safety-meetings._form',
        [
            'formAction' => route(
                'admin.projects.construction.hse.safety-meetings.update',
                [
                    'project' => $project,
                    'meeting' => $meeting,
                ]
            ),

            'formMethod' => 'PUT',

            'meeting' => $meeting,

            'meetingNumber' =>
                $meeting->meeting_number,

            'users' => $users,

            'project' => $project,

            'cancelUrl' => route(
                'admin.projects.construction.hse.safety-meetings.show',
                [
                    'project' => $project,
                    'meeting' => $meeting,
                ]
            ),

            'submitLabel' => 'Update Safety Meeting',
        ]
    )

</div>

@endsection