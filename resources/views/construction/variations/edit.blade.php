@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4>
                Edit Variation
            </h4>

            <div class="text-muted">
                {{ $variation->variation_number }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.variations.show',
                    [
                        'project' => $project,
                        'variation' => $variation,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                View
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.variations.index',
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
            'admin.projects.construction.variations.update',
            [
                'project' => $project,
                'variation' => $variation,
            ]
        ) }}"
    >

        @csrf

        @method('PUT')


        <div class="card">

            <div class="card-header">

                <strong>
                    Variation Details
                </strong>

                <span class="float-end badge bg-secondary">
                    {{ $variation->variation_number }}
                </span>

            </div>


            <div class="card-body">

                @include(
                    'construction.variations._form'
                )

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2 mt-4">

            <a
                href="{{ route(
                    'admin.projects.construction.variations.show',
                    [
                        'project' => $project,
                        'variation' => $variation,
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
                Update Variation
            </button>

        </div>

    </form>

</div>

@endsection