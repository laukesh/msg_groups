@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4>
                Edit Schedule Activity
            </h4>

            <div class="text-muted">
                {{ $activity->activity_code }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.schedule.show',
                    [
                        'project' => $project,
                        'activity' => $activity,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                View
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.schedule.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'admin.projects.construction.schedule.update',
            [
                'project' => $project,
                'activity' => $activity,
            ]
        ) }}"
    >

        @csrf

        @method('PUT')


        <div class="card">

            <div class="card-header">

                <strong>
                    Schedule Activity Details
                </strong>

                <span class="float-end badge bg-secondary">
                    {{ $activity->activity_code }}
                </span>

            </div>


            <div class="card-body">

                @include(
                    'construction.schedule._form'
                )

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2 mt-4">

            <a
                href="{{ route(
                    'admin.projects.construction.schedule.show',
                    [
                        'project' => $project,
                        'activity' => $activity,
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
                Update Activity
            </button>

        </div>

    </form>

</div>

@endsection