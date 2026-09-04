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
                Edit Toolbox Talk
            </h3>

            <div class="text-muted">
                {{ $toolboxTalk->title }}
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.hse.toolbox-talks.show',
                [
                    'project' => $project,
                    'toolboxTalk' => $toolboxTalk,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Toolbox Talk
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


    @if($toolboxTalk->status === 'Completed')

        <div class="alert alert-warning">

            <i class="bi bi-exclamation-triangle me-1"></i>

            This toolbox talk is marked as
            <strong>Completed</strong>.

            Editing it may change the recorded safety information.

        </div>

    @endif


    @include(
        'construction.hse.toolbox-talks._form',
        [
            'formAction' => route(
                'admin.projects.construction.hse.toolbox-talks.update',
                [
                    'project' => $project,
                    'toolboxTalk' => $toolboxTalk,
                ]
            ),

            'formMethod' => 'PUT',

            'toolboxTalk' => $toolboxTalk,

            'toolboxTalkNumber' =>
                $toolboxTalk->toolbox_talk_number,

            'users' => $users,

            'project' => $project,

            'cancelUrl' => route(
                'admin.projects.construction.hse.toolbox-talks.show',
                [
                    'project' => $project,
                    'toolboxTalk' => $toolboxTalk,
                ]
            ),

            'submitLabel' => 'Update Toolbox Talk',
        ]
    )

</div>

@endsection