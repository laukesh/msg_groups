@extends('layouts.app')

@section('content')

<div class="container-fluid">


    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Zoning Details
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
                    'admin.land.lands.zonings.edit',
                    [
                        $land,
                        $zoning
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


    {{-- Classification --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Zoning Classification
            </strong>

        </div>


        <div class="card-body">

            <div class="row">


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Zoning Code
                    </small>

                    <div class="fw-semibold">

                        {{ $zoning->zoning_code ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Zoning Type
                    </small>

                    <div class="fw-semibold">

                        {{ $zoning->zoning_type }}

                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Authority
                    </small>

                    <div>

                        {{ $zoning->authority ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted">
                        Effective Date
                    </small>

                    <div>

                        {{ $zoning->effective_date
                            ? $zoning
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

                        {{ $zoning->expiry_date
                            ? $zoning
                                ->expiry_date
                                ->format('d-m-Y')
                            : '-'
                        }}

                    </div>

                </div>

            </div>

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
                e($zoning->permitted_use ?? '-')
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
                e($zoning->restrictions ?? '-')
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
                e($zoning->remarks ?? '-')
            ) !!}

        </div>

    </div>


    {{-- Delete --}}

    <div class="card border-danger">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong class="text-danger">
                        Delete Zoning
                    </strong>

                    <div class="text-muted">
                        This action cannot be undone.
                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.land.lands.zonings.destroy',
                        [
                            $land,
                            $zoning
                        ]
                    ) }}"
                    onsubmit="return confirm('Are you sure you want to delete this zoning record?');">

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-danger">

                        Delete Zoning

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection