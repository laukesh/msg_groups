@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project:
                <strong>
                    {{ $project->project_code ?? '—' }}
                </strong>
            </div>

            <h3 class="mb-1">
                Create Safety Meeting
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
                'admin.projects.construction.hse.safety-meetings.index',
                [
                    'project' => $project,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Meetings
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
                'admin.projects.construction.hse.safety-meetings.store',
                [
                    'project' => $project,
                ]
            ),

            'formMethod' => null,

            'meeting' => null,

            'meetingNumber' => $meetingNumber,

            'users' => $users,

            'project' => $project,

            'cancelUrl' => route(
                'admin.projects.construction.hse.safety-meetings.index',
                [
                    'project' => $project,
                ]
            ),

            'submitLabel' => 'Create Safety Meeting',
        ]
    )

</div>

@endsection