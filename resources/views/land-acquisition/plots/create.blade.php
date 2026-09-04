@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h3>
            Add Plot
        </h3>

        <p class="text-muted">

            {{ $land->land_code }}
            -
            {{ $land->land_name }}

        </p>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'admin.land.lands.plots.store',
            $land
        ) }}">

        @csrf


        {{-- Plot Identification --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Plot Identification
                </strong>

            </div>


            <div class="card-body">

                <div class="row">


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Plot Number
                        </label>

                        <input
                            type="text"
                            name="plot_number"
                            class="form-control"
                            value="{{ old(
                                'plot_number'
                            ) }}"
                            placeholder="Enter plot number"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Survey Number
                        </label>

                        <input
                            type="text"
                            name="survey_number"
                            class="form-control"
                            value="{{ old(
                                'survey_number'
                            ) }}"
                            placeholder="Enter survey number"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Parcel Number
                        </label>

                        <input
                            type="text"
                            name="parcel_number"
                            class="form-control"
                            value="{{ old(
                                'parcel_number'
                            ) }}"
                            placeholder="Enter parcel number"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Plot Type
                        </label>

                        <input
                            type="text"
                            name="plot_type"
                            class="form-control"
                            value="{{ old(
                                'plot_type'
                            ) }}"
                            placeholder="Residential, Commercial..."
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- Area --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Plot Area
                </strong>

            </div>


            <div class="card-body">

                <div class="row">


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Plot Area
                        </label>

                        <input
                            type="number"
                            step="0.0001"
                            min="0"
                            name="plot_area"
                            class="form-control"
                            value="{{ old(
                                'plot_area'
                            ) }}"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Area Unit
                        </label>

                        <select
                            name="area_unit"
                            class="form-select">

                            <option value="">
                                Select Unit
                            </option>

                            <option
                                value="sqft"
                                @selected(
                                    old('area_unit')
                                    === 'sqft'
                                )>
                                Square Feet
                            </option>

                            <option
                                value="sqm"
                                @selected(
                                    old('area_unit')
                                    === 'sqm'
                                )>
                                Square Meter
                            </option>

                            <option
                                value="acre"
                                @selected(
                                    old('area_unit')
                                    === 'acre'
                                )>
                                Acre
                            </option>

                            <option
                                value="hectare"
                                @selected(
                                    old('area_unit')
                                    === 'hectare'
                                )>
                                Hectare
                            </option>

                        </select>

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

                <div class="row">


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Boundaries
                        </label>

                        <textarea
                            name="boundaries"
                            rows="5"
                            class="form-control"
                            placeholder="Enter plot boundary details"
                        >{{ old('boundaries') }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="5"
                            class="form-control"
                            placeholder="Enter plot description"
                        >{{ old('description') }}</textarea>

                    </div>

                </div>

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

                <textarea
                    name="remarks"
                    rows="4"
                    class="form-control"
                    placeholder="Enter remarks..."
                >{{ old('remarks') }}</textarea>

            </div>

        </div>


        {{-- Actions --}}

        <div class="d-flex justify-content-end">

            <a
                href="{{ route(
                    'admin.land.lands.show',
                    $land
                ) }}"
                class="btn btn-secondary me-2">

                Cancel

            </a>


            <button
                type="submit"
                class="btn btn-primary">

                Save Plot

            </button>

        </div>

    </form>

</div>

@endsection