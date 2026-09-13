@extends('layouts.app')

@section('title', 'Edit Mall')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="h3 mb-1"> <i class="fas fa-edit me-1"></i> Edit Mall</h4>
            <p class="text-muted mb-0">
                Update mall information and details.
            </p>
        </div>

        <div>
            <a
                href="{{ route('admin.assets.malls.show', $mall->id) }}"
                class="btn btn-info"
            >
                <i class="fas fa-eye me-1"></i> View Mall
            </a>

            <a
                href="{{ route('admin.assets.malls.index') }}"
                class="btn btn-secondary"
            >
                <i class="fas fa-arrow-left me-1"></i> Back to Malls
            </a>
        </div>
    </div>


    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Edit Mall Form --}}
    <form
        action="{{ route('admin.assets.malls.update', $mall->id) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

        <div class="row">

            {{-- Basic Information --}}
            <div class="col-md-12">
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-1"></i> Basic Information</h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            {{-- Mall Code --}}
                            <div class="col-md-6 mb-3">
                                <label for="mall_code" class="form-label">
                                    <i class="fas fa-barcode me-1"></i> Mall Code <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="mall_code"
                                    id="mall_code"
                                    class="form-control @error('mall_code') is-invalid @enderror"
                                    value="{{ old('mall_code', $mall->mall_code) }}"
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
                                <label for="mall_name" class="form-label">
                                    <i class="fas fa-store me-1"></i> Mall Name <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="mall_name"
                                    id="mall_name"
                                    class="form-control @error('mall_name') is-invalid @enderror"
                                    value="{{ old('mall_name', $mall->mall_name) }}"
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
                                <label for="mall_type" class="form-label">
                                    <i class="fas fa-store me-1"></i>    Mall Type
                                </label>

                                <input
                                    type="text"
                                    name="mall_type"
                                    id="mall_type"
                                    class="form-control @error('mall_type') is-invalid @enderror"
                                    value="{{ old('mall_type', $mall->mall_type) }}"
                                    placeholder="e.g. Shopping Mall"
                                >

                                @error('mall_type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>


                            {{-- Opening Date --}}
                            <div class="col-md-6 mb-3">
                                <label for="opening_date" class="form-label">
                                    <i class="fas fa-calendar-alt me-1"></i>    Opening Date
                                </label>

                                <input
                                    type="date"
                                    name="opening_date"
                                    id="opening_date"
                                    class="form-control @error('opening_date') is-invalid @enderror"
                                    value="{{ old('opening_date', optional($mall->opening_date)->format('Y-m-d')) }}"
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


            {{-- Address --}}
            <div class="col-md-12">
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-home me-1"></i> Address</h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            {{-- Address Line 1 --}}
                            <div class="col-md-6 mb-3">
                                <label for="address_line1" class="form-label">
                                    <i class="fas fa-home me-1"></i>    Address Line 1 <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="address_line1"
                                    id="address_line1"
                                    class="form-control @error('address_line1') is-invalid @enderror"
                                    value="{{ old('address_line1', $mall->address_line1) }}"
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
                                <label for="address_line2" class="form-label">
                                    <i class="fas fa-home me-1"></i>    Address Line 2
                                </label>

                                <input
                                    type="text"
                                    name="address_line2"
                                    id="address_line2"
                                    class="form-control @error('address_line2') is-invalid @enderror"
                                    value="{{ old('address_line2', $mall->address_line2) }}"
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
                                <label for="city" class="form-label">
                                    <i class="fas fa-city me-1"></i>    City <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="city"
                                    id="city"
                                    class="form-control @error('city') is-invalid @enderror"
                                    value="{{ old('city', $mall->city) }}"
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
                                <label for="state" class="form-label">
                                    <i class="fas fa-city me-1"></i>    State
                                </label>

                                <input
                                    type="text"
                                    name="state"
                                    id="state"
                                    class="form-control @error('state') is-invalid @enderror"
                                    value="{{ old('state', $mall->state) }}"
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
                                <label for="country" class="form-label">
                                    <i class="fas fa-globe me-1"></i>    Country <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="country"
                                    id="country"
                                    class="form-control @error('country') is-invalid @enderror"
                                    value="{{ old('country', $mall->country) }}"
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
                                <label for="postal_code" class="form-label">
                                 <i class="fas fa-mail-bulk me-1"></i>   Postal Code
                                </label>

                                <input
                                    type="text"
                                    name="postal_code"
                                    id="postal_code"
                                    class="form-control @error('postal_code') is-invalid @enderror"
                                    value="{{ old('postal_code', $mall->postal_code) }}"
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


            {{-- Location --}}
            <div class="col-md-12">
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-map-marker-alt me-1"></i> Location</h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            {{-- Latitude --}}
                            <div class="col-md-6 mb-3">
                                <label for="latitude" class="form-label">
                                    <i class="fas fa-map-marker-alt me-1"></i>    Latitude
                                </label>

                                <input
                                    type="number"
                                    step="any"
                                    name="latitude"
                                    id="latitude"
                                    class="form-control @error('latitude') is-invalid @enderror"
                                    value="{{ old('latitude', $mall->latitude) }}"
                                    placeholder="Enter latitude"
                                >

                                @error('latitude')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>


                            {{-- Longitude --}}
                            <div class="col-md-6 mb-3">
                                <label for="longitude" class="form-label">
                                    <i class="fas fa-map-marker-alt me-1"></i>    Longitude
                                </label>

                                <input
                                    type="number"
                                    step="any"
                                    name="longitude"
                                    id="longitude"
                                    class="form-control @error('longitude') is-invalid @enderror"
                                    value="{{ old('longitude', $mall->longitude) }}"
                                    placeholder="Enter longitude"
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


            {{-- Area & Capacity --}}
            <div class="col-md-12">
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-ruler-combined me-1"></i> Area & Capacity</h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            {{-- Total Area --}}
                            <div class="col-md-4 mb-3">
                                <label for="total_area" class="form-label">
                                    <i class="fas fa-ruler-combined me-1"></i>    Total Area
                                </label>

                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    name="total_area"
                                    id="total_area"
                                    class="form-control @error('total_area') is-invalid @enderror"
                                    value="{{ old('total_area', $mall->total_area) }}"
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
                                <label for="leasable_area" class="form-label">
                                    <i class="fas fa-ruler-combined me-1"></i>    Leasable Area
                                </label>

                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    name="leasable_area"
                                    id="leasable_area"
                                    class="form-control @error('leasable_area') is-invalid @enderror"
                                    value="{{ old('leasable_area', $mall->leasable_area) }}"
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
                                <label for="parking_capacity" class="form-label">
                                    <i class="fas fa-parking me-1"></i>    Parking Capacity
                                </label>

                                <input
                                    type="number"
                                    min="0"
                                    name="parking_capacity"
                                    id="parking_capacity"
                                    class="form-control @error('parking_capacity') is-invalid @enderror"
                                    value="{{ old('parking_capacity', $mall->parking_capacity) }}"
                                    placeholder="Parking capacity"
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


            {{-- Contact Information --}}
            <div class="col-md-12">
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-address-book me-1"></i> Contact Information</h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            {{-- Contact Person --}}
                            <div class="col-md-6 mb-3">
                                <label for="contact_person" class="form-label">
                                    <i class="fas fa-user me-1"></i>    Contact Person
                                </label>

                                <input
                                    type="text"
                                    name="contact_person"
                                    id="contact_person"
                                    class="form-control @error('contact_person') is-invalid @enderror"
                                    value="{{ old('contact_person', $mall->contact_person) }}"
                                    placeholder="Contact person"
                                >

                                @error('contact_person')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>


                            {{-- Contact Number --}}
                            <div class="col-md-6 mb-3">
                                <label for="contact_number" class="form-label">
                                    <i class="fas fa-phone me-1"></i>    Contact Number
                                </label>

                                <input
                                    type="text"
                                    name="contact_number"
                                    id="contact_number"
                                    class="form-control @error('contact_number') is-invalid @enderror"
                                    value="{{ old('contact_number', $mall->contact_number) }}"
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
                                <label for="email" class="form-label">
                                  <i class="fas fa-envelope me-1"></i>   Email
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $mall->email) }}"
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
                                <label for="website" class="form-label">
                                  <i class="fas fa-globe me-1"></i>    Website
                                </label>

                                <input
                                    type="url"
                                    name="website"
                                    id="website"
                                    class="form-control @error('website') is-invalid @enderror"
                                    value="{{ old('website', $mall->website) }}"
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


            {{-- Status --}}
            <div class="col-md-12">
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-toggle-on me-1"></i> Status</h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6">

                                <label for="status" class="form-label">
                                 <i class="fas fa-circle-check me-1"></i>    Status <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="status"
                                    id="status"
                                    class="form-select @error('status') is-invalid @enderror"
                                    required
                                >
                                    <option value="">Select Status</option>

                                    <option
                                        value="1"
                                        {{ old('status', $mall->status) === 1 ? 'selected' : '' }}
                                        
                                    >
                                        Active
                                    </option>

                                    <option
                                        value="0"
                                        {{ old('status', $mall->status) === '0' ? 'selected' : '' }}
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


            {{-- Audit Information --}}
            <div class="col-md-12">
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-file-alt me-1"></i> Audit Information</h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label class="form-label"><i class="fas fa-id-card me-1"></i> Mall ID</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $mall->id }}"
                                    readonly
                                >
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label"><i class="fas fa-user me-1"></i> Created By</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $mall->created_by }}"
                                    readonly
                                >
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label"><i class="fas fa-user-edit me-1"></i> Updated By</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $mall->updated_by }}"
                                    readonly
                                >
                            </div>

                        </div>

                    </div>
                </div>
            </div>


            {{-- Form Actions --}}
            <div class="col-md-12 mb-4">

                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('admin.assets.malls.show', $mall->id) }}"
                        class="btn btn-secondary"
                    >    <i class="fas fa-times me-1"></i>
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    > <i class="fas fa-save me-1"></i>
                        Update Mall
                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection