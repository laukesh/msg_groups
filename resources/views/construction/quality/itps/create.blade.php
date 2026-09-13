@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ================================================================ --}}
    {{-- HEADER --}}
    {{-- ================================================================ --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Create Inspection & Test Plan
            </h4>

            <div class="text-muted">
                Project:
                {{ $project->name ?? $project->project_name ?? 'Project' }}
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.quality.itps.index',
                $project
            ) }}"
            class="btn btn-outline-secondary"
        >
            ← Back to ITPs
        </a>

    </div>


    {{-- ================================================================ --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ================================================================ --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">
                Please correct the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ================================================================ --}}
    {{-- FORM --}}
    {{-- ================================================================ --}}

    <form
        method="POST"
        action="{{ route(
            'admin.projects.construction.quality.itps.store',
            $project
        ) }}"
    >

        @csrf


        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    ITP Details
                </h5>

            </div>


            <div class="card-body">

                @include(
                    'construction.quality.itps._form'
                )

            </div>


            <div class="card-footer bg-white">

                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route(
                            'admin.projects.construction.quality.itps.index',
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
                        Create ITP
                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection