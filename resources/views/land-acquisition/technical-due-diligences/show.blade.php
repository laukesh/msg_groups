@extends('layouts.app')

@section('content')

<div class="container-fluid">


    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Technical Due Diligence Details
            </h3>

            <p class="text-muted mb-0">
                {{ $land->land_code }}
                -
                {{ $land->land_name }}
            </p>

        </div>


        <div>

            <a
                href="{{ route(
                    'admin.land.lands.technical-due-diligences.edit',
                    [
                        $land,
                        $dueDiligence
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>


            <a
                href="{{ route(
                    'admin.land.lands.show',
                    $land
                ) }}"
                class="btn btn-secondary"
            >
                Back to Land
            </a>

        </div>

    </div>


    {{-- Basic Information --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Basic Information</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-4">

                    <small class="text-muted">
                        Type
                    </small>

                    <div class="fw-semibold">
                        {{ $dueDiligence->type }}
                    </div>

                </div>


                <div class="col-md-3 mb-4">

                    <small class="text-muted">
                        Reference Number
                    </small>

                    <div>
                        {{ $dueDiligence->reference_no ?? '-' }}
                    </div>

                </div>


                <div class="col-md-3 mb-4">

                    <small class="text-muted">
                        Assessment Date
                    </small>

                    <div>

                        {{ $dueDiligence->assessment_date
                            ? $dueDiligence
                                ->assessment_date
                                ->format('d-m-Y')
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-3 mb-4">

                    <small class="text-muted">
                        Conducted By
                    </small>

                    <div>
                        {{ $dueDiligence->conducted_by ?? '-' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Status
                    </small>

                    <div>

                        <span class="badge bg-secondary">
                            {{ $dueDiligence->status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Summary --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Technical Assessment Summary</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $dueDiligence->summary ?? '-'
                )
            ) !!}

        </div>

    </div>


    {{-- Findings --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Technical Findings</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $dueDiligence->findings ?? '-'
                )
            ) !!}

        </div>

    </div>


    {{-- Recommendations --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Technical Recommendations</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $dueDiligence->recommendations ?? '-'
                )
            ) !!}

        </div>

    </div>


    {{-- Remarks --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Remarks</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $dueDiligence->remarks ?? '-'
                )
            ) !!}

        </div>

    </div>


    {{-- Delete --}}

    <div class="card border-danger">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong class="text-danger">
                        Delete Technical Due Diligence
                    </strong>

                    <div class="text-muted">
                        This action cannot be undone.
                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.land.lands.technical-due-diligences.destroy',
                        [
                            $land,
                            $dueDiligence
                        ]
                    ) }}"
                    onsubmit="return confirm('Are you sure you want to delete this technical due diligence record?');"
                >

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-danger"
                    >
                        Delete
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection