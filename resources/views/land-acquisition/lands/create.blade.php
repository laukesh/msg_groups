@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h3>
            Add Land
        </h3>

        <p class="text-muted">
            Add a new land acquisition record
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


    <form method="POST"
          action="{{ route('admin.land.lands.store') }}">

        @csrf


        {{-- Basic Information --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Basic Information
                </strong>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Land Code
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="Auto Generated"
                            disabled
                        >

                        <div class="form-text">
                            Land code will be generated automatically.
                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Land Name *
                        </label>

                        <input
                            type="text"
                            name="land_name"
                            class="form-control"
                            value="{{ old('land_name') }}"
                            required
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Land Opportunity
                        </label>

                        <select
                            name="opportunity_id"
                            class="form-select">

                            <option value="">
                                Select Opportunity
                            </option>

                            @foreach($opportunities as $opportunity)

                                <option
                                    value="{{ $opportunity->id }}"
                                    @selected(
                                        old('opportunity_id')
                                        == $opportunity->id
                                    )>

                                    {{ $opportunity->opportunity_no }}
                                    -
                                    {{ $opportunity->opportunity_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Acquisition Status *
                        </label>

                        <select
                            name="acquisition_status"
                            class="form-select"
                            required>

                            <option value="Under Evaluation">
                                Under Evaluation
                            </option>

                            <option value="Approved">
                                Approved
                            </option>

                            <option value="Acquired">
                                Acquired
                            </option>

                            <option value="Rejected">
                                Rejected
                            </option>

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Land Type
                        </label>

                        <input
                            type="text"
                            name="land_type"
                            class="form-control"
                            value="{{ old('land_type') }}"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Acquisition Date
                        </label>

                        <input
                            type="date"
                            name="acquisition_date"
                            class="form-control"
                            value="{{ old('acquisition_date') }}"
                        >

                    </div>

                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="form-control @error('description') is-invalid @enderror"
                        ></textarea>

                        @error('description')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- Location --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Location
                </strong>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Address Line 1
                        </label>

                        <input
                            type="text"
                            name="address_line1"
                            class="form-control"
                            value="{{ old('address_line1') }}"
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Address Line 2
                        </label>

                        <input
                            type="text"
                            name="address_line2"
                            class="form-control"
                            value="{{ old('address_line2') }}"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Locality
                        </label>

                        <input
                            type="text"
                            name="locality"
                            class="form-control"
                            value="{{ old('locality') }}"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            City
                        </label>

                        <input
                            type="text"
                            name="city"
                            class="form-control"
                            value="{{ old('city') }}"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            State
                        </label>

                        <input
                            type="text"
                            name="state"
                            class="form-control"
                            value="{{ old('state') }}"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Country
                        </label>

                        <input
                            type="text"
                            name="country"
                            class="form-control"
                            value="{{ old('country', 'India') }}"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Postal Code
                        </label>

                        <input
                            type="text"
                            name="postal_code"
                            class="form-control"
                            value="{{ old('postal_code') }}"
                        >

                    </div>


                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Latitude
                        </label>

                        <input
                            type="number"
                            step="any"
                            name="latitude"
                            class="form-control"
                            value="{{ old('latitude') }}"
                        >

                    </div>


                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Longitude
                        </label>

                        <input
                            type="number"
                            step="any"
                            name="longitude"
                            class="form-control"
                            value="{{ old('longitude') }}"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- Area --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Area Information
                </strong>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <label class="form-label">
                            Total Area
                        </label>

                        <input
                            type="number"
                            step="0.0001"
                            name="total_area"
                            class="form-control"
                            value="{{ old('total_area') }}"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Area Unit
                        </label>

                        <select
                            name="area_unit"
                            class="form-select">

                            <option value="">
                                Select Unit
                            </option>

                            <option value="sqft">
                                Square Feet
                            </option>

                            <option value="sqm">
                                Square Meter
                            </option>

                            <option value="acre">
                                Acre
                            </option>

                            <option value="hectare">
                                Hectare
                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- Remarks --}}

        <div class="card mb-4">

            <div class="card-body">

                <label class="form-label">
                    Remarks
                </label>

                <textarea
                    name="remarks"
                    rows="4"
                    class="form-control"
                >{{ old('remarks') }}</textarea>

            </div>

        </div>


        <div class="d-flex justify-content-end">

            <a
                href="{{ route('admin.land.lands.index') }}"
                class="btn btn-secondary me-2">

                Cancel

            </a>

            <button
                type="submit"
                class="btn btn-primary">

                Save Land

            </button>

        </div>

    </form>

</div>

@endsection