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
                Create Toolbox Talk
            </h3>

            <div class="text-muted">
                {{ $project->project_name ?? 'Project' }}
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.hse.toolbox-talks.index',
                [
                    'project' => $project,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Toolbox Talks
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


    <div class="card mb-4">

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Project Code
                    </div>

                    <strong>
                        {{ $project->project_code ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Project Name
                    </div>

                    <strong>
                        {{ $project->project_name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Toolbox Talk Number
                    </div>

                    <strong>
                        {{ $toolboxTalkNumber }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    @include(
        'construction.hse.toolbox-talks._form',
        [
            'formAction' => route(
                'admin.projects.construction.hse.toolbox-talks.store',
                [
                    'project' => $project,
                ]
            ),

            'formMethod' => null,

            'toolboxTalk' => null,

            'toolboxTalkNumber' =>
                $toolboxTalkNumber,

            'users' => $users,

            'project' => $project,

            'cancelUrl' => route(
                'admin.projects.construction.hse.toolbox-talks.index',
                [
                    'project' => $project,
                ]
            ),

            'submitLabel' => 'Create Toolbox Talk',
        ]
    )

</div>

@endsection