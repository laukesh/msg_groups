@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4>
                Create Schedule Activity
            </h4>

            <div class="text-muted">
                {{ $project->project_name }}

                @if($project->project_code)
                    · {{ $project->project_code }}
                @endif
            </div>

        </div>


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


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    @php
        $activity = null;
    @endphp


    <form
        method="POST"
        action="{{ route(
            'admin.projects.construction.schedule.store',
            $project
        ) }}"
    >

        @csrf


        <div class="card">

            <div class="card-header">

                <strong>
                    Schedule Activity Details
                </strong>

            </div>


            <div class="card-body">

                <div class="alert alert-info">

                    <strong>
                        Activity Code
                    </strong>

                    <div class="small mt-1">
                        The Activity Code will be generated
                        automatically after saving.
                    </div>

                </div>


                @include(
                    'construction.schedule._form'
                )

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2 mt-4">

            <a
                href="{{ route(
                    'admin.projects.construction.schedule.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Activity
            </button>

        </div>

    </form>

</div>

@endsection