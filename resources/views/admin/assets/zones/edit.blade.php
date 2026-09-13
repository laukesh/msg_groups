@extends('layouts.app')

@section('title', 'Edit Zone')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-layer-group me-2"></i>
                Edit Zone
            </h4>

            <div class="text-muted">
                Update zone information and configuration.
            </div>
        </div>

        <div>
            <a href="{{ route('admin.assets.zones.index') }}"
               class="btn btn-outline-secondary">

                <i class="fas fa-arrow-left me-1"></i>
                Back to Zones

            </a>
        </div>

    </div>


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <div class="fw-semibold mb-2">

                <i class="fas fa-exclamation-triangle me-2"></i>
                Please correct the following errors:

            </div>

            <ul class="mb-0 ps-4">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        EDIT ZONE FORM
    ========================================================== --}}
    <form method="POST"
          action="{{ route('admin.assets.zones.update', $zone->id) }}">

        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm">

            {{-- =================================================
                CARD HEADER
            ================================================== --}}
            <div class="card-header bg-white">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h6 class="mb-0">
                            <i class="fas fa-layer-group me-2"></i>
                            Zone Information
                        </h6>

                        <small class="text-muted">
                            Update the details of this zone.
                        </small>

                    </div>

                    <span class="badge bg-primary">
                        ID: {{ $zone->id }}
                    </span>

                </div>

            </div>


            {{-- =================================================
                CARD BODY
            ================================================== --}}
            <div class="card-body">

                <div class="row g-3">

                    {{-- =================================================
                        FLOOR
                    ================================================== --}}
                    <div class="col-md-6">

                        <label for="floor_id"
                               class="form-label fw-semibold">

                            Floor
                            <span class="text-danger">*</span>

                        </label>

                        <select name="floor_id"
                                id="floor_id"
                                class="form-select @error('floor_id') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Floor
                            </option>

                            @foreach($floors as $floor)

                                <option value="{{ $floor->id }}"
                                    {{ old('floor_id', $zone->floor_id) == $floor->id ? 'selected' : '' }}>

                                    {{ $floor->building->building_name ?? 'Building' }}
                                    -
                                    {{ $floor->floor_name }}
                                    ({{ $floor->floor_code }})

                                </option>

                            @endforeach

                        </select>

                        @error('floor_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- =================================================
                        ZONE CODE
                    ================================================== --}}
                    <div class="col-md-6">

                        <label for="zone_code"
                               class="form-label fw-semibold">

                            Zone Code
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="zone_code"
                               id="zone_code"
                               value="{{ old('zone_code', $zone->zone_code) }}"
                               class="form-control @error('zone_code') is-invalid @enderror"
                               placeholder="e.g. ZN-01"
                               required>

                        @error('zone_code')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- =================================================
                        ZONE NAME
                    ================================================== --}}
                    <div class="col-md-6">

                        <label for="zone_name"
                               class="form-label fw-semibold">

                            Zone Name
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="zone_name"
                               id="zone_name"
                               value="{{ old('zone_name', $zone->zone_name) }}"
                               class="form-control @error('zone_name') is-invalid @enderror"
                               placeholder="e.g. Retail Zone A"
                               required>

                        @error('zone_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- =================================================
                        STATUS
                    ================================================== --}}
                    <div class="col-md-6">

                        <label for="status"
                               class="form-label fw-semibold">

                            Status
                            <span class="text-danger">*</span>

                        </label>

                        <select name="status"
                                id="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            <option value="1"
                                {{ (string) old('status', $zone->status) === '1' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0"
                                {{ (string) old('status', $zone->status) === '0' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- =================================================
                        DESCRIPTION
                    ================================================== --}}
                    <div class="col-12">

                        <label for="description"
                               class="form-label fw-semibold">

                            Description

                        </label>

                        <textarea name="description"
                                  id="description"
                                  rows="4"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Enter zone description...">{{ old('description', $zone->description) }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- =================================================
                FORM FOOTER
            ================================================== --}}
            <div class="card-footer bg-white">

                <div class="d-flex justify-content-between align-items-center">

                    <div class="text-muted small">
                        Last updated:
                        {{ optional($zone->updated_at)->format('d M Y, h:i A') ?? 'N/A' }}
                    </div>

                    <div class="d-flex gap-2">

                        <a href="{{ route('admin.assets.zones.show', $zone->id) }}"
                           class="btn btn-light">

                            <i class="fas fa-times me-1"></i>
                            Cancel

                        </a>

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="fas fa-save me-1"></i>
                            Update Zone

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection