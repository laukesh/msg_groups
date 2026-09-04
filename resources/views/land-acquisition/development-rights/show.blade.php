@extends('layouts.app')

@section('content')

<div class="container-fluid">


    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Development Right Details
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
                    'admin.land.lands.development-rights.edit',
                    [
                        $land,
                        $developmentRight
                    ]
                ) }}"
                class="btn btn-primary">

                Edit

            </a>


            <a
                href="{{ route(
                    'admin.land.lands.show',
                    $land
                ) }}"
                class="btn btn-secondary">

                Back to Land

            </a>

        </div>

    </div>


    {{-- Basic Information --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Development Right Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row">


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Right Type
                    </small>

                    <div class="fw-semibold">

                        {{ $developmentRight->right_type }}

                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Authority
                    </small>

                    <div>

                        {{ $developmentRight->authority ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Reference Number
                    </small>

                    <div>

                        {{ $developmentRight->reference_number ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted">
                        Effective Date
                    </small>

                    <div>

                        {{ $developmentRight->effective_date
                            ? $developmentRight
                                ->effective_date
                                ->format('d-m-Y')
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted">
                        Expiry Date
                    </small>

                    <div>

                        {{ $developmentRight->expiry_date
                            ? $developmentRight
                                ->expiry_date
                                ->format('d-m-Y')
                            : '-'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Description --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Description
            </strong>

        </div>


        <div class="card-body">

            {!! nl2br(
                e(
                    $developmentRight->description ?? '-'
                )
            ) !!}

        </div>

    </div>


    {{-- Permitted Use --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Permitted Use
            </strong>

        </div>


        <div class="card-body">

            {!! nl2br(
                e(
                    $developmentRight->permitted_use ?? '-'
                )
            ) !!}

        </div>

    </div>


    {{-- Restrictions --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Restrictions
            </strong>

        </div>


        <div class="card-body">

            {!! nl2br(
                e(
                    $developmentRight->restrictions ?? '-'
                )
            ) !!}

        </div>

    </div>


    {{-- Remarks --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Remarks
            </strong>

        </div>


        <div class="card-body">

            {!! nl2br(
                e(
                    $developmentRight->remarks ?? '-'
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
                        Delete Development Right
                    </strong>

                    <div class="text-muted">
                        This action cannot be undone.
                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.land.lands.development-rights.destroy',
                        [
                            $land,
                            $developmentRight
                        ]
                    ) }}"
                    onsubmit="return confirm('Are you sure you want to delete this development right?');">

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-danger">

                        Delete

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection