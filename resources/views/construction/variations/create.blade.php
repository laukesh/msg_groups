@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4>
                Create Variation
            </h4>

            <div class="text-muted">
                {{ $project->project_name }}
            </div>

        </div>


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
        $variation = null;
    @endphp


    <form
        method="POST"
        action="{{ route(
            'admin.projects.construction.variations.store',
            $project
        ) }}"
    >

        @csrf


        <div class="card">

            <div class="card-header">

                <strong>
                    Variation Details
                </strong>

            </div>


            <div class="card-body">

                <div class="alert alert-info">

                    <strong>
                        Variation Number
                    </strong>

                    <div class="small mt-1">
                        A unique Variation Number will be
                        generated automatically after saving.
                    </div>

                </div>


                @include(
                    'construction.variations._form'
                )

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2 mt-4">

            <a
                href="{{ route(
                    'admin.projects.construction.variations.index',
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
                Save Variation
            </button>

        </div>

    </form>

</div>

@endsection