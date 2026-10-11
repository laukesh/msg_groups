@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Toolbox Talk:
                <strong>
                    {{ $toolboxTalk->toolbox_talk_number }}
                </strong>
            </div>

            <h3 class="mb-1">
                Add Participant
            </h3>

            <div class="text-muted">
                {{ $toolboxTalk->title }}
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.hse.toolbox-talks.participants.index',
                [
                    'project' => $project,
                    'toolboxTalk' => $toolboxTalk,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Attendance
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


    {{-- Toolbox Talk Summary --}}

    <div class="card mb-4">

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Toolbox Talk Number
                    </div>

                    <strong>
                        {{ $toolboxTalk->toolbox_talk_number }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Title
                    </div>

                    <strong>
                        {{ $toolboxTalk->title }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Talk Date
                    </div>

                    <strong>

                        {{ $toolboxTalk->talk_date
                            ? $toolboxTalk->talk_date->format('d-m-Y')
                            : '—'
                        }}

                    </strong>

                </div>

            </div>

        </div>

    </div>


    @include(
        'construction.hse.toolbox-talk-participants._form',
        [
            'formAction' => route(
                'admin.projects.construction.hse.toolbox-talks.participants.store',
                [
                    'project' => $project,
                    'toolboxTalk' => $toolboxTalk,
                ]
            ),

            'formMethod' => null,

            'participant' => null,

            'project' => $project,

            'toolboxTalk' => $toolboxTalk,

            'cancelUrl' => route(
                'admin.projects.construction.hse.toolbox-talks.participants.index',
                [
                    'project' => $project,
                    'toolboxTalk' => $toolboxTalk,
                ]
            ),

            'submitLabel' => 'Add Participant',
        ]
    )

</div>

@endsection