@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Create Inspection
            </h4>

            <div class="text-muted">
                Project:
                {{ $project->name ?? $project->project_name ?? 'Project' }}
            </div>
        </div>

        <a
            href="{{ route(
                'admin.projects.construction.inspections.index',
                $project
            ) }}"
            class="btn btn-outline-secondary"
        >
            ← Back to Inspections
        </a>

    </div>


    {{-- Validation Errors --}}
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


    {{-- Form --}}
    <form
        method="POST"
        action="{{ route(
            'admin.projects.construction.inspections.store',
            $project
        ) }}"
    >

        @csrf


        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Inspection Details
                </h5>

            </div>


            <div class="card-body">

                @include(
                    'construction.inspections._form'
                )

            </div>


            <div class="card-footer bg-white">

                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route(
                            'admin.projects.construction.inspections.index',
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
                        Create Inspection
                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection