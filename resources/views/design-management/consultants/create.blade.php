@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Add Consultant
            </h4>

            <div class="text-muted">
                Design Management
                @if($project->project_name)
                    — {{ $project->project_name }}
                @endif
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.design-management.consultants.index',
                $project
            ) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>

    </div>


    {{-- Validation Errors --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    @include('design-management.partials.alerts')


    <form
        method="POST"
        action="{{ route(
            'admin.projects.design-management.consultants.store',
            $project
        ) }}"
    >

        @csrf

        @include(
            'design-management.consultants._form'
        )


        {{-- ========================================================= --}}
        {{-- Actions --}}
        {{-- ========================================================= --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.design-management.consultants.index',
                    $project
                ) }}"
                class="btn btn-light border"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >

                <i class="bi bi-check-lg me-1"></i>

                Save Consultant

            </button>

        </div>

    </form>

</div>

@endsection