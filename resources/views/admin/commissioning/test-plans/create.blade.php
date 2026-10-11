@extends('layouts.app')

@section('title', 'Create Commissioning Test Plan')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="bi bi-plus-circle me-2"></i>
                Create Commissioning Test Plan
            </h4>

            <div class="text-muted">

                {{ $project->project_code ?? '' }}

                @if(
                    !empty($project->project_code) &&
                    !empty($project->project_name)
                )
                    -
                @endif

                {{ $project->project_name ?? '' }}

            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.commissioning.test-plans.index',
                $project
            ) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>

    </div>


    {{-- Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Please correct the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Form --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Test Plan Details
                    </strong>

                    <div class="text-muted small">
                        New plans are created as Draft.
                    </div>

                </div>

                <span class="badge bg-secondary">
                    Draft
                </span>

            </div>

        </div>


        <div class="card-body">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.commissioning.test-plans.store',
                    $project
                ) }}"
            >

                @include(
                    'admin.commissioning.test-plans._form'
                )


                {{-- Buttons --}}
                <div class="border-top mt-4 pt-4">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-save me-1"></i>
                        Save as Draft
                    </button>


                    <a
                        href="{{ route(
                            'admin.projects.commissioning.test-plans.index',
                            $project
                        ) }}"
                        class="btn btn-outline-secondary ms-2"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection