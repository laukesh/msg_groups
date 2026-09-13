@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ================================================================ --}}
    {{-- HEADER --}}
    {{-- ================================================================ --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Create Non-Conformance Report
            </h4>

            <div class="text-muted">
                Project:
                <strong>
                    {{ $project->project_name ?? $project->name }}
                </strong>
            </div>
        </div>


        <a
            href="{{ route(
                'admin.projects.construction.quality.ncrs.index',
                $project
            ) }}"
            class="btn btn-outline-secondary"
        >
            ← Back to NCRs
        </a>

    </div>


    {{-- ================================================================ --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ================================================================ --}}

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


    {{-- ================================================================ --}}
    {{-- FORM --}}
    {{-- ================================================================ --}}

    <form
        method="POST"
        action="{{ route(
            'admin.projects.construction.quality.ncrs.store',
            $project
        ) }}"
    >

        @csrf


        <div class="card shadow-sm">

            <div class="card-header">

                <h5 class="mb-0">
                    NCR Details
                </h5>

            </div>


            <div class="card-body">

                @include(
                    'construction.quality.ncrs._form'
                )

            </div>


            {{-- ======================================================== --}}
            {{-- FOOTER --}}
            {{-- ======================================================== --}}

            <div class="card-footer d-flex justify-content-between">

                <a
                    href="{{ route(
                        'admin.projects.construction.quality.ncrs.index',
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
                    Create NCR
                </button>

            </div>

        </div>

    </form>

</div>

@endsection