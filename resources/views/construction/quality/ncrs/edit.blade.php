@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ================================================================ --}}
    {{-- HEADER --}}
    {{-- ================================================================ --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Edit Non-Conformance Report
            </h4>

            <div class="text-muted">

                Project:
                <strong>
                    {{ $project->project_name ?? $project->name }}
                </strong>

                <span class="mx-2">|</span>

                NCR:
                <strong>
                    {{ $ncr->ncr_number }}
                </strong>

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.quality.ncrs.show',
                    [
                        'project' => $project,
                        'ncr' => $ncr
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                View NCR
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.quality.ncrs.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Back
            </a>

        </div>

    </div>


    {{-- ================================================================ --}}
    {{-- CURRENT STATUS --}}
    {{-- ================================================================ --}}

    <div class="alert alert-warning d-flex align-items-center">

        <div>

            <strong>
                Current Status:
            </strong>

            {{ $ncr->status }}

            <div class="small mt-1">
                NCR can only be edited while it is
                <strong>Open</strong> or
                <strong>Rejected</strong>.
            </div>

        </div>

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
            'admin.projects.construction.quality.ncrs.update',
            [
                'project' => $project,
                'ncr' => $ncr
            ]
        ) }}"
    >

        @csrf

        @method('PUT')


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
                        'admin.projects.construction.quality.ncrs.show',
                        [
                            'project' => $project,
                            'ncr' => $ncr
                        ]
                    ) }}"
                    class="btn btn-light border"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Update NCR
                </button>

            </div>

        </div>

    </form>

</div>

@endsection