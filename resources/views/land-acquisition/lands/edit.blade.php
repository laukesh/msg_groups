@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                Edit Land
            </h3>

            <p class="text-muted mb-0">
                Update land acquisition record
            </p>
        </div>

        <div>

            <a href="{{ route('admin.land.lands.show', $land) }}"
               class="btn btn-outline-primary">
                View Land
            </a>

            <a href="{{ route('admin.land.lands.index') }}"
               class="btn btn-secondary">
                Back
            </a>

        </div>

    </div>


    {{-- Validation Errors --}}

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


    <form method="POST"
          action="{{ route('admin.land.lands.update', $land) }}">

        @csrf

        @method('PUT')


        {{-- =========================================================
             BASIC INFORMATION
        ========================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Basic Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row">


                    {{-- Land Code --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Land Code *
                        </label>

                        <input
                            type="text"
                            name="land_code"
                            class="form-control @error('land_code') is-invalid @enderror"
                            value="{{ old('land_code', $land->land_code) }}"
                            required
                        >

                        @error('land_code')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Land Name --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Land Name *
                        </label>

                        <input
                            type="text"
                            name="land_name"
                            class="form-control @error('land_name') is-invalid @enderror"
                            value="{{ old('land_name', $land->land_name) }}"
                            required
                        >

                        @error('land_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Opportunity --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Land Opportunity
                        </label>

                        <select
                            name="opportunity_id"
                            class="form-select @error('opportunity_id') is-invalid @enderror">

                            <option value="">
                                Select Opportunity
                            </option>

                            @foreach($opportunities as $opportunity)

                                <option
                                    value="{{ $opportunity->id }}"
                                    @selected(
                                        old(
                                            'opportunity_id',
                                            $land->opportunity_id
                                        ) == $opportunity->id
                                    )>

                                    {{ $opportunity->opportunity_no }}
                                    -
                                    {{ $opportunity->opportunity_name }}

                                </option>

                            @endforeach

                        </select>

                        @error('opportunity_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Acquisition Status --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Acquisition Status *
                        </label>

                        <select
                            name="acquisition_status"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Opportunity',
                                'Under Evaluation',
                                'Due Diligence',
                                'Negotiation',
                                'Approval Pending',
                                'Approved',
                                'Acquisition in Progress',
                                'Acquired',
                                'Rejected',
                                'Withdrawn'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        old(
                                            'acquisition_status',
                                            $land->acquisition_status
                                        ) === $status
                                    )
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Land Type --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Land Type
                        </label>

                        <input
                            type="text"
                            name="land_type"
                            class="form-control @error('land_type') is-invalid @enderror"
                            value="{{ old('land_type', $land->land_type) }}"
                        >

                        @error('land_type')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Acquisition Date --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Acquisition Date
                        </label>

                        <input
                            type="date"
                            name="acquisition_date"
                            class="form-control @error('acquisition_date') is-invalid @enderror"
                            value="{{ old(
                                'acquisition_date',
                                $land->acquisition_date
                                    ? $land->acquisition_date->format('Y-m-d')
                                    : ''
                            ) }}"
                        >

                        @error('acquisition_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Description --}}

                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="form-control @error('description') is-invalid @enderror"
                        >{{ old('description', $land->description) }}</textarea>

                        @error('description')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
             LOCATION
        ========================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Location
                </strong>

            </div>


            <div class="card-body">

                <div class="row">


                    {{-- Address 1 --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Address Line 1
                        </label>

                        <input
                            type="text"
                            name="address_line1"
                            class="form-control"
                            value="{{ old(
                                'address_line1',
                                $land->address_line1
                            ) }}"
                        >

                    </div>


                    {{-- Address 2 --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Address Line 2
                        </label>

                        <input
                            type="text"
                            name="address_line2"
                            class="form-control"
                            value="{{ old(
                                'address_line2',
                                $land->address_line2
                            ) }}"
                        >

                    </div>


                    {{-- Locality --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Locality
                        </label>

                        <input
                            type="text"
                            name="locality"
                            class="form-control"
                            value="{{ old(
                                'locality',
                                $land->locality
                            ) }}"
                        >

                    </div>


                    {{-- City --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            City
                        </label>

                        <input
                            type="text"
                            name="city"
                            class="form-control"
                            value="{{ old(
                                'city',
                                $land->city
                            ) }}"
                        >

                    </div>


                    {{-- State --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            State
                        </label>

                        <input
                            type="text"
                            name="state"
                            class="form-control"
                            value="{{ old(
                                'state',
                                $land->state
                            ) }}"
                        >

                    </div>


                    {{-- Country --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Country
                        </label>

                        <input
                            type="text"
                            name="country"
                            class="form-control"
                            value="{{ old(
                                'country',
                                $land->country ?? 'India'
                            ) }}"
                        >

                    </div>


                    {{-- Postal Code --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Postal Code
                        </label>

                        <input
                            type="text"
                            name="postal_code"
                            class="form-control"
                            value="{{ old(
                                'postal_code',
                                $land->postal_code
                            ) }}"
                        >

                    </div>


                    {{-- Latitude --}}

                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Latitude
                        </label>

                        <input
                            type="number"
                            step="any"
                            name="latitude"
                            class="form-control"
                            value="{{ old(
                                'latitude',
                                $land->latitude
                            ) }}"
                        >

                    </div>


                    {{-- Longitude --}}

                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Longitude
                        </label>

                        <input
                            type="number"
                            step="any"
                            name="longitude"
                            class="form-control"
                            value="{{ old(
                                'longitude',
                                $land->longitude
                            ) }}"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
             AREA INFORMATION
        ========================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Area Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row">


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Total Area
                        </label>

                        <input
                            type="number"
                            step="0.0001"
                            min="0"
                            name="total_area"
                            class="form-control @error('total_area') is-invalid @enderror"
                            value="{{ old(
                                'total_area',
                                $land->total_area
                            ) }}"
                        >

                        @error('total_area')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

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
                                    old(
                                        'area_unit',
                                        $land->area_unit
                                    ) === 'sqft'
                                )>
                                Square Feet
                            </option>

                            <option
                                value="sqm"
                                @selected(
                                    old(
                                        'area_unit',
                                        $land->area_unit
                                    ) === 'sqm'
                                )>
                                Square Meter
                            </option>

                            <option
                                value="acre"
                                @selected(
                                    old(
                                        'area_unit',
                                        $land->area_unit
                                    ) === 'acre'
                                )>
                                Acre
                            </option>

                            <option
                                value="hectare"
                                @selected(
                                    old(
                                        'area_unit',
                                        $land->area_unit
                                    ) === 'hectare'
                                )>
                                Hectare
                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
             REMARKS
        ========================================================== --}}

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
                >{{ old('remarks', $land->remarks) }}</textarea>

            </div>

        </div>


        {{-- =========================================================
             ACTIONS
        ========================================================== --}}

        <div class="d-flex justify-content-end mb-4">

            <a
                href="{{ route('admin.land.lands.show', $land) }}"
                class="btn btn-secondary me-2">

                Cancel

            </a>

            <button
                type="submit"
                class="btn btn-primary">

                Update Land

            </button>

        </div>

    </form>

</div>

@endsection