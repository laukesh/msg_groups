@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Add Land Opportunity
            </h3>

            <p class="text-muted mb-0">
                Register a potential land acquisition opportunity.
            </p>

        </div>

        <div>

            <a
                href="{{ route('admin.land.opportunities.index') }}"
                class="btn btn-outline-primary">

                <i class="bi bi-arrow-left me-1"></i>
                Back to Opportunities

            </a>

        </div>

    </div>


    {{-- Validation Errors --}}

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
        action="{{ route('admin.land.opportunities.store') }}">

        @csrf


        {{-- =========================================================
             OPPORTUNITY INFORMATION
        ========================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Opportunity Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row">


                    {{-- Opportunity Number --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Opportunity No.
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="Auto Generated"
                            disabled
                        >

                        <div class="form-text">
                            Opportunity number will be generated automatically.
                        </div>

                    </div>


                    {{-- Opportunity Name --}}

                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Opportunity Name *
                        </label>

                        <input
                            type="text"
                            name="opportunity_name"
                            value="{{ old('opportunity_name') }}"
                            class="form-control @error('opportunity_name') is-invalid @enderror"
                            placeholder="Enter opportunity name"
                            required
                        >

                        @error('opportunity_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Source --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Source
                        </label>

                        <input
                            type="text"
                            name="source"
                            value="{{ old('source') }}"
                            class="form-control @error('source') is-invalid @enderror"
                            placeholder="Broker, Direct, Referral..."
                        >

                        @error('source')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Identified Date --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Identified Date
                        </label>

                        <input
                            type="date"
                            name="identified_date"
                            value="{{ old('identified_date') }}"
                            class="form-control @error('identified_date') is-invalid @enderror"
                        >

                        @error('identified_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Status --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Status *
                        </label>

                        <select
                            name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required>

                            <option value="New"
                                @selected(old('status', 'New') === 'New')>
                                New
                            </option>

                            <option value="Under Evaluation"
                                @selected(old('status') === 'Under Evaluation')>
                                Under Evaluation
                            </option>

                            <option value="Approved"
                                @selected(old('status') === 'Approved')>
                                Approved
                            </option>

                            <option value="Rejected"
                                @selected(old('status') === 'Rejected')>
                                Rejected
                            </option>

                            <option value="On Hold"
                                @selected(old('status') === 'On Hold')>
                                On Hold
                            </option>

                        </select>

                        @error('status')

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
                            placeholder="Describe the land opportunity..."
                        >{{ old('description') }}</textarea>

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

                <div class="mb-3">

                    <label class="form-label">
                        Location
                    </label>

                    <textarea
                        name="location_text"
                        rows="3"
                        class="form-control @error('location_text') is-invalid @enderror"
                        placeholder="Enter location details"
                    >{{ old('location_text') }}</textarea>

                    @error('location_text')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>

        </div>


        {{-- =========================================================
             AREA & COST
        ========================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Estimated Area & Cost
                </strong>

            </div>


            <div class="card-body">

                <div class="row">


                    {{-- Estimated Area --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Estimated Area
                        </label>

                        <input
                            type="number"
                            step="0.0001"
                            min="0"
                            name="estimated_area"
                            value="{{ old('estimated_area') }}"
                            class="form-control @error('estimated_area') is-invalid @enderror"
                            placeholder="Enter estimated area"
                        >

                        @error('estimated_area')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Area Unit --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Area Unit
                        </label>

                        <select
                            name="area_unit"
                            class="form-select @error('area_unit') is-invalid @enderror">

                            <option value="">
                                Select Unit
                            </option>

                            <option value="sqft"
                                @selected(old('area_unit') === 'sqft')>
                                Square Feet
                            </option>

                            <option value="sqm"
                                @selected(old('area_unit') === 'sqm')>
                                Square Meter
                            </option>

                            <option value="acre"
                                @selected(old('area_unit') === 'acre')>
                                Acre
                            </option>

                            <option value="hectare"
                                @selected(old('area_unit') === 'hectare')>
                                Hectare
                            </option>

                        </select>

                        @error('area_unit')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Estimated Acquisition Cost --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Estimated Acquisition Cost
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="estimated_acquisition_cost"
                            value="{{ old('estimated_acquisition_cost') }}"
                            class="form-control @error('estimated_acquisition_cost') is-invalid @enderror"
                            placeholder="Enter estimated cost"
                        >

                        @error('estimated_acquisition_cost')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Currency --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Currency
                        </label>

                        <input
                            type="text"
                            name="currency"
                            value="{{ old('currency', 'INR') }}"
                            class="form-control @error('currency') is-invalid @enderror"
                            maxlength="10"
                        >

                        @error('currency')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

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
                    class="form-control @error('remarks') is-invalid @enderror"
                    placeholder="Enter remarks..."
                >{{ old('remarks') }}</textarea>

                @error('remarks')

                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>

                @enderror

            </div>

        </div>


        {{-- =========================================================
             BUTTONS
        ========================================================== --}}

        <div class="d-flex justify-content-end mb-4">

            <a
                href="{{ route('admin.land.opportunities.index') }}"
                class="btn btn-secondary me-2">

                Cancel

            </a>


            <button
                type="submit"
                class="btn btn-primary">

                Save Opportunity

            </button>

        </div>


    </form>

</div>

@endsection