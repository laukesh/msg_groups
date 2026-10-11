@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4 class="mb-1">
                Edit Progress Update
            </h4>

            <div class="text-muted">

                {{ $progress->progress_number }}

                ·

                {{ $project->project_name }}

                @if($project->project_code)
                    · {{ $project->project_code }}
                @endif

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.progress.show',
                    [
                        'project' => $project,
                        'progress' => $progress,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-eye me-1"></i>
                View
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.progress.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>


    {{-- ERRORS --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'admin.projects.construction.progress.update',
            [
                'project' => $project,
                'progress' => $progress,
            ]
        ) }}"
    >

        @csrf

        @method('PUT')


        <div class="card">

            <div class="card-header">

                <strong>
                    Progress Update
                </strong>

                <span class="float-end badge bg-secondary">

                    {{ $progress->progress_number }}

                </span>

            </div>


            <div class="card-body">

                @include(
                    'construction.progress._form',
                    [
                        'project' => $project,
                        'progress' => $progress,
                        'workOrders' => $workOrders,
                        'users' => $users,
                    ]
                )

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2 mt-4">

            <a
                href="{{ route(
                    'admin.projects.construction.progress.show',
                    [
                        'project' => $project,
                        'progress' => $progress,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Update Progress
            </button>

        </div>

    </form>

</div>

@endsection