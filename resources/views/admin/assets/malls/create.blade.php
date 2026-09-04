@extends('layouts.app')

@section('title', 'Create Mall')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        PAGE HEADER
    ============================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1">
                <i class="fas fa-building me-2"></i>
                Create Mall
            </h1>

            <p class="text-muted mb-0">
                Add a new mall to the system.
            </p>
        </div>

        <a
            href="{{ route('admin.assets.malls.index') }}"
            class="btn btn-secondary"
        >
            <i class="fas fa-arrow-left me-1"></i>
            Back to Malls
        </a>

    </div>


    {{-- ============================================================
        VALIDATION ERRORS
    ============================================================= --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <div class="d-flex align-items-start">

                <i class="fas fa-exclamation-circle me-2 mt-1"></i>

                <div>
                    <strong>
                        Please fix the following errors:
                    </strong>

                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>

        </div>

    @endif


    {{-- ============================================================
        CREATE MALL FORM
    ============================================================= --}}
    <form
        action="{{ route('admin.assets.malls.store') }}"
        method="POST"
    >

        @csrf

        <div class="row">

            {{-- ====================================================
                BASIC INFORMATION
            ===================================================== --}}
            <div class="col-md-12">

                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-building me-2"></i>
                            Basic Information
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            {{-- Mall Code --}}
                            <div class="col-md-6 mb-3">

                                <label
                                    for="mall_code"
                                    class="form-label"
                                >
                                    <i class="fas fa-barcode me-1"></i>
                                    Mall Code
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="mall_code"
                                    id="mall_code"
                                    class="form-control @error('mall_code') is-invalid @enderror"
                                    value="{{ old('mall_code') }}"
                                    placeholder="Enter mall code"
                                    required
                                >

                                @error('mall_code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Mall Name --}}
                            <div class="col-md-6 mb-3">

                                <label
                                    for="mall_name"
                                    class="form-label"
                                >
                                    <i class="fas fa-signature me-1"></i>
                                    Mall Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="mall_name"
                                    id="mall_name"
                                    class="form-control @error('mall_name') is-invalid @enderror"
                                    value="{{ old('mall_name') }}"
                                    placeholder="Enter mall name"
                                    required
                                >

                                @error('mall_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Mall Type --}}
                            <div class="col-md-6 mb-3">

                                <label
                                    for="mall_type"
                                    class="form-label"
                                >
                                    <i class="fas fa-layer-group me-1"></i>
                                    Mall Type
                                </label>

                                <!-- <input
                                    type="text"
                                    name="mall_type"
                                    id="mall_type"
                                    class="form-control @error('mall_type') is-invalid @enderror"
                                    value="{{ old('mall_type') }}"
                                    placeholder="e.g. Shopping Mall"
                                > -->
                                 <select
                                        name="mall_type"
                                        id="mall_type"
                                        class="form-select @error('mall_type') is-invalid @enderror"
                                        required
                                    >

                                        <option value="">
                                            Select Mall Type
                                        </option>

                                        <option value="Shopping Mall"
                                            {{ old('mall_type', $mall->mall_type ?? '') === 'Shopping Mall' ? 'selected' : '' }}>
                                            Shopping Mall
                                        </option>

                                        <option value="Retail Park"
                                            {{ old('mall_type', $mall->mall_type ?? '') === 'Retail Park' ? 'selected' : '' }}>
                                            Retail Park
                                        </option>

                                        <option value="Commercial Complex"
                                            {{ old('mall_type', $mall->mall_type ?? '') === 'Commercial Complex' ? 'selected' : '' }}>
                                            Commercial Complex
                                        </option>

                                        <option value="Mixed Use"
                                            {{ old('mall_type', $mall->mall_type ?? '') === 'Mixed Use' ? 'selected' : '' }}>
                                            Mixed Use
                                        </option>

                                    </select>

                                @error('mall_type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Opening Date --}}
                            <div class="col-md-6 mb-3">

                                <label
                                    for="opening_date"
                                    class="form-label"
                                >
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Opening Date
                                </label>

                                <input
                                    type="date"
                                    name="opening_date"
                                    id="opening_date"
                                    class="form-control @error('opening_date') is-invalid @enderror"
                                    value="{{ old('opening_date') }}"
                                >

                                @error('opening_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ====================================================
                ADDRESS
            ===================================================== --}}
            <div class="col-md-12">

                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Address
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            {{-- Address Line 1 --}}
                            <div class="col-md-6 mb-3">

                                <label
                                    for="address_line1"
                                    class="form-label"
                                >
                                    <i class="fas fa-road me-1"></i>
                                    Address Line 1
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="address_line1"
                                    id="address_line1"
                                    class="form-control @error('address_line1') is-invalid @enderror"
                                    value="{{ old('address_line1') }}"
                                    placeholder="Enter address"
                                    required
                                >

                                @error('address_line1')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Address Line 2 --}}
                            <div class="col-md-6 mb-3">

                                <label
                                    for="address_line2"
                                    class="form-label"
                                >
                                    <i class="fas fa-location-arrow me-1"></i>
                                    Address Line 2
                                </label>

                                <input
                                    type="text"
                                    name="address_line2"
                                    id="address_line2"
                                    class="form-control @error('address_line2') is-invalid @enderror"
                                    value="{{ old('address_line2') }}"
                                    placeholder="Apartment, building, street..."
                                >

                                @error('address_line2')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- City --}}
                            <div class="col-md-4 mb-3">

                                <label
                                    for="city"
                                    class="form-label"
                                >
                                    <i class="fas fa-city me-1"></i>
                                    City
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="city"
                                    id="city"
                                    class="form-control @error('city') is-invalid @enderror"
                                    value="{{ old('city') }}"
                                    placeholder="Enter city"
                                    required
                                >

                                @error('city')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- State --}}
                            <div class="col-md-4 mb-3">

                                <label
                                    for="state"
                                    class="form-label"
                                >
                                    <i class="fas fa-map me-1"></i>
                                    State
                                </label>

                                <input
                                    type="text"
                                    name="state"
                                    id="state"
                                    class="form-control @error('state') is-invalid @enderror"
                                    value="{{ old('state') }}"
                                    placeholder="Enter state"
                                >

                                @error('state')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Country --}}
                            <div class="col-md-4 mb-3">

                                <label
                                    for="country"
                                    class="form-label"
                                >
                                    <i class="fas fa-globe me-1"></i>
                                    Country
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="country"
                                    id="country"
                                    class="form-control @error('country') is-invalid @enderror"
                                    value="{{ old('country', 'Somalia') }}"
                                    placeholder="Enter country"
                                    required
                                >

                                @error('country')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Postal Code --}}
                            <div class="col-md-4 mb-3">

                                <label
                                    for="postal_code"
                                    class="form-label"
                                >
                                    <i class="fas fa-mail-bulk me-1"></i>
                                    Postal Code
                                </label>

                                <input
                                    type="text"
                                    name="postal_code"
                                    id="postal_code"
                                    class="form-control @error('postal_code') is-invalid @enderror"
                                    value="{{ old('postal_code') }}"
                                    placeholder="Enter postal code"
                                >

                                @error('postal_code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ====================================================
                LOCATION
            ===================================================== --}}
            <div class="col-md-12">

                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-location-dot me-2"></i>
                            Location
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            {{-- Latitude --}}
                            <div class="col-md-6 mb-3">

                                <label
                                    for="latitude"
                                    class="form-label"
                                >
                                    <i class="fas fa-arrows-up-down me-1"></i>
                                    Latitude
                                </label>

                                <input
                                    type="number"
                                    step="any"
                                    name="latitude"
                                    id="latitude"
                                    class="form-control @error('latitude') is-invalid @enderror"
                                    value="{{ old('latitude') }}"
                                    placeholder="e.g. 2.0469"
                                >

                                @error('latitude')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Longitude --}}
                            <div class="col-md-6 mb-3">

                                <label
                                    for="longitude"
                                    class="form-label"
                                >
                                    <i class="fas fa-arrows-left-right me-1"></i>
                                    Longitude
                                </label>

                                <input
                                    type="number"
                                    step="any"
                                    name="longitude"
                                    id="longitude"
                                    class="form-control @error('longitude') is-invalid @enderror"
                                    value="{{ old('longitude') }}"
                                    placeholder="e.g. 45.3182"
                                >

                                @error('longitude')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ====================================================
                AREA & CAPACITY
            ===================================================== --}}
            <div class="col-md-12">

                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-ruler-combined me-2"></i>
                            Area & Capacity
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            {{-- Total Area --}}
                            <div class="col-md-4 mb-3">

                                <label
                                    for="total_area"
                                    class="form-label"
                                >
                                    <i class="fas fa-vector-square me-1"></i>
                                    Total Area
                                </label>

                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    name="total_area"
                                    id="total_area"
                                    class="form-control @error('total_area') is-invalid @enderror"
                                    value="{{ old('total_area') }}"
                                    placeholder="Total area"
                                >

                                @error('total_area')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Leasable Area --}}
                            <div class="col-md-4 mb-3">

                                <label
                                    for="leasable_area"
                                    class="form-label"
                                >
                                    <i class="fas fa-expand-arrows-alt me-1"></i>
                                    Leasable Area
                                </label>

                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    name="leasable_area"
                                    id="leasable_area"
                                    class="form-control @error('leasable_area') is-invalid @enderror"
                                    value="{{ old('leasable_area') }}"
                                    placeholder="Leasable area"
                                >

                                @error('leasable_area')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Parking Capacity --}}
                            <div class="col-md-4 mb-3">

                                <label
                                    for="parking_capacity"
                                    class="form-label"
                                >
                                    <i class="fas fa-car me-1"></i>
                                    Parking Capacity
                                </label>

                                <input
                                    type="number"
                                    min="0"
                                    name="parking_capacity"
                                    id="parking_capacity"
                                    class="form-control @error('parking_capacity') is-invalid @enderror"
                                    value="{{ old('parking_capacity') }}"
                                    placeholder="Number of vehicles"
                                >

                                @error('parking_capacity')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ====================================================
                CONTACT INFORMATION
            ===================================================== --}}
            <div class="col-md-12">

                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-address-book me-2"></i>
                            Contact Information
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            {{-- Contact Person --}}
                            <div class="col-md-6 mb-3">

                                <label
                                    for="contact_person"
                                    class="form-label"
                                >
                                    <i class="fas fa-user me-1"></i>
                                    Contact Person
                                </label>

                                <input
                                    type="text"
                                    name="contact_person"
                                    id="contact_person"
                                    class="form-control @error('contact_person') is-invalid @enderror"
                                    value="{{ old('contact_person') }}"
                                    placeholder="Contact person name"
                                >

                                @error('contact_person')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Contact Number --}}
                            <div class="col-md-6 mb-3">

                                <label
                                    for="contact_number"
                                    class="form-label"
                                >
                                    <i class="fas fa-phone me-1"></i>
                                    Contact Number
                                </label>

                                <input
                                    type="text"
                                    name="contact_number"
                                    id="contact_number"
                                    class="form-control @error('contact_number') is-invalid @enderror"
                                    value="{{ old('contact_number') }}"
                                    placeholder="Contact number"
                                >

                                @error('contact_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Email --}}
                            <div class="col-md-6 mb-3">

                                <label
                                    for="email"
                                    class="form-label"
                                >
                                    <i class="fas fa-envelope me-1"></i>
                                    Email
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                    placeholder="email@example.com"
                                >

                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Website --}}
                            <div class="col-md-6 mb-3">

                                <label
                                    for="website"
                                    class="form-label"
                                >
                                    <i class="fas fa-globe me-1"></i>
                                    Website
                                </label>

                                <input
                                    type="url"
                                    name="website"
                                    id="website"
                                    class="form-control @error('website') is-invalid @enderror"
                                    value="{{ old('website') }}"
                                    placeholder="https://example.com"
                                >

                                @error('website')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ====================================================
                STATUS
            ===================================================== --}}
            <div class="col-md-12">

                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-toggle-on me-2"></i>
                            Status
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label
                                    for="status"
                                    class="form-label"
                                >
                                    <i class="fas fa-circle-check me-1"></i>
                                    Status
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="status"
                                    id="status"
                                    class="form-select @error('status') is-invalid @enderror"
                                    required
                                >

                                    <option value="">
                                        Select Status
                                    </option>

                                    <option
                                        value="1"
                                        {{ old('status', '1') === '1' ? 'selected' : '' }}
                                    >
                                        Active
                                    </option>

                                    <option
                                        value="0"
                                        {{ old('status') === '0' ? 'selected' : '' }}
                                    >
                                        Inactive
                                    </option>

                                </select>

                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ====================================================
                FORM ACTIONS
            ===================================================== --}}
            <div class="col-md-12 mb-4">

                <div class="d-flex justify-content-end gap-2">

                    {{-- Cancel --}}
                    <a
                        href="{{ route('admin.assets.malls.index') }}"
                        class="btn btn-secondary"
                    >
                        <i class="fas fa-times me-1"></i>
                        Cancel
                    </a>


                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="fas fa-save me-1"></i>
                        Create Mall
                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection