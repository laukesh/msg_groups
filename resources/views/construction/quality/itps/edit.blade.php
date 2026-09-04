@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ================================================================ --}}
    {{-- HEADER --}}
    {{-- ================================================================ --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Edit Inspection & Test Plan
            </h4>

            <div class="text-muted">

                {{ $itp->itp_number }}

                <span class="mx-1">
                    •
                </span>

                {{ $itp->title }}

            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.quality.itps.show',
                [
                    'project' =>
                        $project,

                    'itp' =>
                        $itp,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            ← Back to ITP
        </a>

    </div>


    {{-- ================================================================ --}}
    {{-- STATUS --}}
    {{-- ================================================================ --}}

    <div class="mb-3">

        @if($itp->status === 'Draft')

            <span class="badge bg-secondary">
                Draft
            </span>

        @elseif($itp->status === 'Rejected')

            <span class="badge bg-danger">
                Rejected
            </span>

        @endif

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
            'admin.projects.construction.quality.itps.update',
            [
                'project' =>
                    $project,

                'itp' =>
                    $itp,
            ]
        ) }}"
    >

        @csrf

        @method('PUT')


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

                <div class="d-flex justify-content-between">

                    <a
                        href="{{ route(
                            'admin.projects.construction.quality.itps.show',
                            [
                                'project' =>
                                    $project,

                                'itp' =>
                                    $itp,
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
                        Update ITP
                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection