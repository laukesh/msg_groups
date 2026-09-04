@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Create Submittal
            </h4>

            <div class="text-muted">
                Project:
                {{ $project->name ?? $project->project_name ?? 'Project' }}
            </div>
        </div>

        <a
            href="{{ route(
                'admin.projects.construction.submittals.index',
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


    <form
        method="POST"
        action="{{ route(
            'admin.projects.construction.submittals.store',
            $project
        ) }}"
    >

        @csrf


        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Submittal Details
                </h5>

            </div>

            <div class="card-body">

                @include(
                    'construction.submittals._form'
                )

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.construction.submittals.index',
                    $project
                ) }}"
                class="btn btn-light"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Create Submittal
            </button>

        </div>

    </form>

</div>

@endsection