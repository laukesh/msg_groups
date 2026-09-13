@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Edit Submittal
            </h4>

            <div class="text-muted">
                {{ $submittal->submittal_number }}
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.submittals.show',
                [
                    'project' => $project,
                    'submittal' => $submittal,
                ]
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
            'admin.projects.construction.submittals.update',
            [
                'project' => $project,
                'submittal' => $submittal,
            ]
        ) }}"
    >

        @csrf

        @method('PUT')


        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Submittal Details
                </h5>

            </div>

            <div class="card-body">

                @include(
                    'construction.submittals._form',
                    [
                        'submittal' => $submittal,
                    ]
                )

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.construction.submittals.show',
                    [
                        'project' => $project,
                        'submittal' => $submittal,
                    ]
                ) }}"
                class="btn btn-light"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Update Submittal
            </button>

        </div>

    </form>

</div>

@endsection