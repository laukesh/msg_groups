@extends('layouts.app')

@section('content')

<div class="container-fluid">


    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">

                Plot
                {{ $plot->plot_number ?? 'Details' }}

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
                    'admin.land.lands.plots.edit',
                    [
                        $land,
                        $plot
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


    {{-- Plot Information --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Plot Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row">


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Plot Number
                    </small>

                    <div class="fw-semibold">

                        {{ $plot->plot_number ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Survey Number
                    </small>

                    <div class="fw-semibold">

                        {{ $plot->survey_number ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Parcel Number
                    </small>

                    <div class="fw-semibold">

                        {{ $plot->parcel_number ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Plot Type
                    </small>

                    <div>

                        {{ $plot->plot_type ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Plot Area
                    </small>

                    <div class="fw-semibold">

                        {{ $plot->plot_area ?? '-' }}

                        {{ $plot->area_unit ?? '' }}

                    </div>

                </div>


            </div>

        </div>

    </div>


    {{-- Boundaries --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Boundaries
            </strong>

        </div>


        <div class="card-body">

            {!! nl2br(
                e($plot->boundaries ?? '-')
            ) !!}

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
                e($plot->description ?? '-')
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
                e($plot->remarks ?? '-')
            ) !!}

        </div>

    </div>


    {{-- Delete --}}

    <div class="card border-danger">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong class="text-danger">
                        Delete Plot
                    </strong>

                    <div class="text-muted">
                        This action cannot be undone.
                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.land.lands.plots.destroy',
                        [
                            $land,
                            $plot
                        ]
                    ) }}"
                    onsubmit="return confirm('Are you sure you want to delete this plot?');">

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-danger">

                        Delete Plot

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection