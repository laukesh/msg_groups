{{-- ============================================================
    PROPERTY AREA - EDIT
    File:
    resources/views/admin/assets/areas/edit.blade.php
============================================================ --}}

@extends('layouts.app')

@section('title', 'Edit Property Area')

@section('content')

<div class="container-fluid py-4">

    {{-- ========================================================
        PAGE HEADER
    ========================================================= --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <h1 class="h3 mb-1">
                <i class="ri-edit-2-line me-2"></i>
                Edit Property Area
            </h1>

            <p class="text-muted mb-0">
                Update property hierarchy, classification,
                measurements and area information.
            </p>

        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.assets.areas.show', $area->id) }}"
               class="btn btn-outline-primary">

                <i class="ri-eye-line me-1"></i>
                View

            </a>

            <a href="{{ route('admin.assets.areas.index') }}"
               class="btn btn-outline-secondary">

                <i class="ri-arrow-left-line me-1"></i>
                Back to Areas

            </a>

        </div>

    </div>


    {{-- ========================================================
        SUCCESS MESSAGE
    ========================================================= --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="ri-checkbox-circle-line me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ========================================================
        ERROR SUMMARY
    ========================================================= --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <div class="fw-semibold mb-2">

                <i class="ri-error-warning-line me-1"></i>
                Please correct the following errors:

            </div>

            <ul class="mb-0 ps-3">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ========================================================
        CURRENT AREA SUMMARY
    ========================================================= --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-1">
                <i class="ri-information-line me-2 text-primary"></i>
                Current Area Information
            </h5>

            <small class="text-muted">
                Existing information for this property area.
            </small>

        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- ID --}}
                <div class="col-md-3">

                    <div class="small text-muted">
                        Area ID
                    </div>

                    <div class="fw-semibold">
                        #{{ $area->id }}
                    </div>

                </div>


                {{-- MALL --}}
                <div class="col-md-3">

                    <div class="small text-muted">
                        Property
                    </div>

                    <div class="fw-semibold">

                        @if($area->mall)

                            {{ $area->mall->name
                                ?? $area->mall->mall_name
                                ?? $area->mall->code
                                ?? 'Mall #' . $area->mall_id }}

                        @else

                            {{ $area->mall_id ?? 'N/A' }}

                        @endif

                    </div>

                </div>


                {{-- BUILDING --}}
                <div class="col-md-3">

                    <div class="small text-muted">
                        Building
                    </div>

                    <div class="fw-semibold">

                        @if($area->building)

                            {{ $area->building->name
                                ?? $area->building->building_name
                                ?? $area->building->code
                                ?? 'Building #' . $area->building_id }}

                        @else

                            {{ $area->building_id ?? 'N/A' }}

                        @endif

                    </div>

                </div>


                {{-- UNIT --}}
                <div class="col-md-3">

                    <div class="small text-muted">
                        Unit
                    </div>

                    <div class="fw-semibold">

                        @if($area->unit)

                            {{ $area->unit->unit_no }}

                        @else

                            {{ $area->unit_id ?? 'N/A' }}

                        @endif

                    </div>

                </div>


                {{-- AREA TYPE --}}
                <div class="col-md-3">

                    <div class="small text-muted">
                        Area Type
                    </div>

                    <div class="fw-semibold">
                        {{ $area->area_type_id ?? 'N/A' }}
                    </div>

                </div>


                {{-- GROSS AREA --}}
                <div class="col-md-3">

                    <div class="small text-muted">
                        Gross Area
                    </div>

                    <div class="fw-semibold">

                        @if($area->gross_area !== null)

                            {{ number_format((float) $area->gross_area, 2) }}
                            {{ $area->measurement_unit ?? 'm²' }}

                        @else

                            N/A

                        @endif

                    </div>

                </div>


                {{-- NET AREA --}}
                <div class="col-md-3">

                    <div class="small text-muted">
                        Net Area
                    </div>

                    <div class="fw-semibold">

                        @if($area->net_area !== null)

                            {{ number_format((float) $area->net_area, 2) }}
                            {{ $area->measurement_unit ?? 'm²' }}

                        @else

                            N/A

                        @endif

                    </div>

                </div>


                {{-- STATUS --}}
                <div class="col-md-3">

                    <div class="small text-muted">
                        Status
                    </div>

                    @php
                        $currentStatus = strtoupper(
                            (string) ($area->status ?? 'ACTIVE')
                        );
                    @endphp

                    @if($currentStatus === 'ACTIVE')

                        <span class="badge bg-success">
                            Active
                        </span>

                    @elseif($currentStatus === 'INACTIVE')

                        <span class="badge bg-secondary">
                            Inactive
                        </span>

                    @elseif($currentStatus === 'DRAFT')

                        <span class="badge bg-warning text-dark">
                            Draft
                        </span>

                    @elseif($currentStatus === 'APPROVED')

                        <span class="badge bg-primary">
                            Approved
                        </span>

                    @else

                        <span class="badge bg-info">
                            {{ $area->status ?? 'N/A' }}
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================
        EDIT FORM
    ========================================================= --}}
    <form method="POST"
          action="{{ route('admin.assets.areas.update', $area->id) }}"
          id="propertyAreaForm">

        @csrf

        @method('PUT')


        {{-- ====================================================
            PROPERTY HIERARCHY
        ===================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-1">
                    <i class="ri-building-4-line me-2 text-primary"></i>
                    Property Hierarchy
                </h5>

                <small class="text-muted">
                    Select the property location.
                </small>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- ==================================================
                        MALL
                    =================================================== --}}
                    <div class="col-md-6 col-lg-4">

                        <label for="mall_id"
                               class="form-label">

                            Poperty
                            <span class="text-danger">*</span>

                        </label>

                        <select name="mall_id"
                                id="mall_id"
                                class="form-select @error('mall_id') is-invalid @enderror"
                                required>

                            <option value="">
                                -- Select Mall --
                            </option>

                            @foreach($malls as $mall)

                                <option value="{{ $mall->id }}"
                                    @if(old('mall_id', $area->mall_id) == $mall->id)
                                        selected
                                    @endif>

                                    {{ $mall->name
                                        ?? $mall->mall_name
                                        ?? $mall->code
                                        ?? 'Mall #' . $mall->id }}

                                </option>

                            @endforeach

                        </select>

                        @error('mall_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- ==================================================
                        BUILDING
                    =================================================== --}}
                    <div class="col-md-6 col-lg-4">

                        <label for="building_id"
                               class="form-label">

                            Building

                        </label>

                        <select name="building_id"
                                id="building_id"
                                class="form-select @error('building_id') is-invalid @enderror">

                            <option value="">
                                -- Select Building --
                            </option>

                            @foreach($buildings as $building)

                                <option value="{{ $building->id }}"
                                    @if(old('building_id', $area->building_id) == $building->id)
                                        selected
                                    @endif>

                                    {{ $building->name
                                        ?? $building->building_name
                                        ?? $building->code
                                        ?? 'Building #' . $building->id }}

                                </option>

                            @endforeach

                        </select>

                        @error('building_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- ==================================================
                        FLOOR
                    =================================================== --}}
                    <div class="col-md-6 col-lg-4">

                        <label for="floor_id"
                               class="form-label">

                            Floor

                        </label>

                        <select name="floor_id"
                                id="floor_id"
                                class="form-select @error('floor_id') is-invalid @enderror">

                            <option value="">
                                -- Select Floor --
                            </option>

                            @foreach($floors as $floor)

                                <option value="{{ $floor->id }}"
                                    @if(old('floor_id', $area->floor_id) == $floor->id)
                                        selected
                                    @endif>

                                    {{ $floor->name
                                        ?? $floor->floor_name
                                        ?? $floor->code
                                        ?? 'Floor #' . $floor->id }}

                                </option>

                            @endforeach

                        </select>

                        @error('floor_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- ==================================================
                        ZONE
                    =================================================== --}}
                    <div class="col-md-6 col-lg-4">

                        <label for="zone_id"
                               class="form-label">

                            Zone

                        </label>

                        <select name="zone_id"
                                id="zone_id"
                                class="form-select @error('zone_id') is-invalid @enderror">

                            <option value="">
                                -- Select Zone --
                            </option>

                            @foreach($zones as $zone)

                                <option value="{{ $zone->id }}"
                                    @if(old('zone_id', $area->zone_id) == $zone->id)
                                        selected
                                    @endif>

                                    {{ $zone->name
                                        ?? $zone->zone_name
                                        ?? $zone->code
                                        ?? 'Zone #' . $zone->id }}

                                </option>

                            @endforeach

                        </select>

                        @error('zone_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- ==================================================
                        UNIT
                    =================================================== --}}
                    <div class="col-md-6 col-lg-4">

                        <label for="unit_id"
                               class="form-label">

                            Unit

                        </label>

                        <select name="unit_id"
                                id="unit_id"
                                class="form-select @error('unit_id') is-invalid @enderror">

                            <option value="">
                                -- Select Unit --
                            </option>

                            @foreach($units as $unit)

                                <option value="{{ $unit->id }}"
                                    @if(old('unit_id', $area->unit_id) == $unit->id)
                                        selected
                                    @endif>

                                    {{ $unit->code
                                        ?? $unit->unit_no
                                        ?? $unit->name
                                        ?? 'Unit #' . $unit->id }}

                                </option>

                            @endforeach

                        </select>

                        @error('unit_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
            AREA CLASSIFICATION
        ========================================================= --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-1">
                    <i class="ri-layout-grid-line me-2 text-primary"></i>
                    Area Classification
                </h5>

                <small class="text-muted">
                    Define the area type and category.
                </small>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- AREA TYPE --}}
                    <div class="col-md-6">

                        <label for="area_type_id"
                               class="form-label">

                            Area Type
                            <span class="text-danger">*</span>

                        </label>

                        <select name="area_type_id"
                                id="area_type_id"
                                class="form-select @error('area_type_id') is-invalid @enderror"
                                required>

                            <option value="">
                                -- Select Area Type --
                            </option>

                            <option value="GROSS"
                                @if(old('area_type_id', $area->area_type_id) == 'GROSS')
                                    selected
                                @endif>
                                Gross Area
                            </option>

                            <option value="NET"
                                @if(old('area_type_id', $area->area_type_id) == 'NET')
                                    selected
                                @endif>
                                Net Area
                            </option>

                            <option value="USABLE"
                                @if(old('area_type_id', $area->area_type_id) == 'USABLE')
                                    selected
                                @endif>
                                Usable Area
                            </option>

                            <option value="RENTABLE"
                                @if(old('area_type_id', $area->area_type_id) == 'RENTABLE')
                                    selected
                                @endif>
                                Rentable Area
                            </option>

                            <option value="CHARGEABLE"
                                @if(old('area_type_id', $area->area_type_id) == 'CHARGEABLE')
                                    selected
                                @endif>
                                Chargeable Area
                            </option>

                            <option value="COMMON"
                                @if(old('area_type_id', $area->area_type_id) == 'COMMON')
                                    selected
                                @endif>
                                Common Area
                            </option>

                            <option value="SERVICE"
                                @if(old('area_type_id', $area->area_type_id) == 'SERVICE')
                                    selected
                                @endif>
                                Service Area
                            </option>

                            <option value="BUILT_UP"
                                @if(old('area_type_id', $area->area_type_id) == 'BUILT_UP')
                                    selected
                                @endif>
                                Built-up Area
                            </option>

                            <option value="CARPET"
                                @if(old('area_type_id', $area->area_type_id) == 'CARPET')
                                    selected
                                @endif>
                                Carpet Area
                            </option>

                            <option value="OTHER"
                                @if(old('area_type_id', $area->area_type_id) == 'OTHER')
                                    selected
                                @endif>
                                Other
                            </option>

                        </select>

                        @error('area_type_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- AREA CATEGORY --}}
                    <div class="col-md-6">

                        <label for="area_category"
                               class="form-label">

                            Area Category

                        </label>

                        <select name="area_category"
                                id="area_category"
                                class="form-select @error('area_category') is-invalid @enderror">

                            <option value="">
                                -- Select Category --
                            </option>

                            <option value="LEASABLE"
                                @if(old('area_category', $area->area_category) == 'LEASABLE')
                                    selected
                                @endif>
                                Leasable
                            </option>

                            <option value="NON_LEASABLE"
                                @if(old('area_category', $area->area_category) == 'NON_LEASABLE')
                                    selected
                                @endif>
                                Non-Leasable
                            </option>

                            <option value="COMMON"
                                @if(old('area_category', $area->area_category) == 'COMMON')
                                    selected
                                @endif>
                                Common
                            </option>

                            <option value="SERVICE"
                                @if(old('area_category', $area->area_category) == 'SERVICE')
                                    selected
                                @endif>
                                Service
                            </option>

                            <option value="BACK_OF_HOUSE"
                                @if(old('area_category', $area->area_category) == 'BACK_OF_HOUSE')
                                    selected
                                @endif>
                                Back of House
                            </option>

                            <option value="PUBLIC"
                                @if(old('area_category', $area->area_category) == 'PUBLIC')
                                    selected
                                @endif>
                                Public
                            </option>

                            <option value="RESTRICTED"
                                @if(old('area_category', $area->area_category) == 'RESTRICTED')
                                    selected
                                @endif>
                                Restricted
                            </option>

                            <option value="UTILITY"
                                @if(old('area_category', $area->area_category) == 'UTILITY')
                                    selected
                                @endif>
                                Utility
                            </option>

                            <option value="PARKING"
                                @if(old('area_category', $area->area_category) == 'PARKING')
                                    selected
                                @endif>
                                Parking
                            </option>

                            <option value="OTHER"
                                @if(old('area_category', $area->area_category) == 'OTHER')
                                    selected
                                @endif>
                                Other
                            </option>

                        </select>

                        @error('area_category')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
            AREA MEASUREMENTS
        ========================================================= --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-1">
                    <i class="ri-ruler-line me-2 text-primary"></i>
                    Area Measurements
                </h5>

                <small class="text-muted">
                    Enter the measured area values.
                </small>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- GROSS AREA --}}
                    <div class="col-md-6 col-lg-4">

                        <label for="gross_area"
                               class="form-label">

                            Gross Area

                        </label>

                        <div class="input-group">

                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="gross_area"
                                   id="gross_area"
                                   value="{{ old('gross_area', $area->gross_area) }}"
                                   class="form-control @error('gross_area') is-invalid @enderror"
                                   placeholder="0.00">

                            <span class="input-group-text">
                                m²
                            </span>

                        </div>

                        @error('gross_area')

                            <div class="text-danger small">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- NET AREA --}}
                    <div class="col-md-6 col-lg-4">

                        <label for="net_area"
                               class="form-label">

                            Net Area

                        </label>

                        <div class="input-group">

                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="net_area"
                                   id="net_area"
                                   value="{{ old('net_area', $area->net_area) }}"
                                   class="form-control @error('net_area') is-invalid @enderror"
                                   placeholder="0.00">

                            <span class="input-group-text">
                                m²
                            </span>

                        </div>

                        @error('net_area')

                            <div class="text-danger small">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- USABLE AREA --}}
                    <div class="col-md-6 col-lg-4">

                        <label for="usable_area"
                               class="form-label">

                            Usable Area

                        </label>

                        <div class="input-group">

                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="usable_area"
                                   id="usable_area"
                                   value="{{ old('usable_area', $area->usable_area) }}"
                                   class="form-control @error('usable_area') is-invalid @enderror"
                                   placeholder="0.00">

                            <span class="input-group-text">
                                m²
                            </span>

                        </div>

                        @error('usable_area')

                            <div class="text-danger small">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- RENTABLE AREA --}}
                    <div class="col-md-6 col-lg-4">

                        <label for="rentable_area"
                               class="form-label">

                            Rentable Area

                        </label>

                        <div class="input-group">

                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="rentable_area"
                                   id="rentable_area"
                                   value="{{ old('rentable_area', $area->rentable_area) }}"
                                   class="form-control @error('rentable_area') is-invalid @enderror"
                                   placeholder="0.00">

                            <span class="input-group-text">
                                m²
                            </span>

                        </div>

                        @error('rentable_area')

                            <div class="text-danger small">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- CHARGEABLE AREA --}}
                    <div class="col-md-6 col-lg-4">

                        <label for="chargeable_area"
                               class="form-label">

                            Chargeable Area

                        </label>

                        <div class="input-group">

                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="chargeable_area"
                                   id="chargeable_area"
                                   value="{{ old('chargeable_area', $area->chargeable_area) }}"
                                   class="form-control @error('chargeable_area') is-invalid @enderror"
                                   placeholder="0.00">

                            <span class="input-group-text">
                                m²
                            </span>

                        </div>

                        @error('chargeable_area')

                            <div class="text-danger small">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- COMMON AREA --}}
                    <div class="col-md-6 col-lg-4">

                        <label for="common_area"
                               class="form-label">

                            Common Area

                        </label>

                        <div class="input-group">

                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="common_area"
                                   id="common_area"
                                   value="{{ old('common_area', $area->common_area) }}"
                                   class="form-control @error('common_area') is-invalid @enderror"
                                   placeholder="0.00">

                            <span class="input-group-text">
                                m²
                            </span>

                        </div>

                        @error('common_area')

                            <div class="text-danger small">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- SERVICE AREA --}}
                    <div class="col-md-6 col-lg-4">

                        <label for="service_area"
                               class="form-label">

                            Service Area

                        </label>

                        <div class="input-group">

                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="service_area"
                                   id="service_area"
                                   value="{{ old('service_area', $area->service_area) }}"
                                   class="form-control @error('service_area') is-invalid @enderror"
                                   placeholder="0.00">

                            <span class="input-group-text">
                                m²
                            </span>

                        </div>

                        @error('service_area')

                            <div class="text-danger small">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- MEASUREMENT UNIT --}}
                    <div class="col-md-6 col-lg-4">

                        <label for="measurement_unit"
                               class="form-label">

                            Measurement Unit
                            <span class="text-danger">*</span>

                        </label>

                        <select name="measurement_unit"
                                id="measurement_unit"
                                class="form-select @error('measurement_unit') is-invalid @enderror"
                                required>

                            <option value="">
                                -- Select Measurement Unit --
                            </option>

                            <option value="SQM"
                                @if(old('measurement_unit', $area->measurement_unit) == 'SQM')
                                    selected
                                @endif>
                                Square Meter (m²)
                            </option>

                            <option value="SQFT"
                                @if(old('measurement_unit', $area->measurement_unit) == 'SQFT')
                                    selected
                                @endif>
                                Square Feet (ft²)
                            </option>

                            <option value="SQYD"
                                @if(old('measurement_unit', $area->measurement_unit) == 'SQYD')
                                    selected
                                @endif>
                                Square Yard (yd²)
                            </option>

                            <option value="ACRE"
                                @if(old('measurement_unit', $area->measurement_unit) == 'ACRE')
                                    selected
                                @endif>
                                Acre
                            </option>

                            <option value="HECTARE"
                                @if(old('measurement_unit', $area->measurement_unit) == 'HECTARE')
                                    selected
                                @endif>
                                Hectare
                            </option>

                        </select>

                        @error('measurement_unit')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
            MEASUREMENT INFORMATION
        ========================================================= --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-1">
                    <i class="ri-calendar-check-line me-2 text-primary"></i>
                    Measurement Information
                </h5>

                <small class="text-muted">
                    Record how and when the area was measured.
                </small>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- MEASUREMENT DATE --}}
                    <div class="col-md-6">

                        <label for="measurement_date"
                               class="form-label">

                            Measurement Date

                        </label>

                        <input type="date"
                               name="measurement_date"
                               id="measurement_date"
                               value="{{ old('measurement_date',  optional($area->measurement_date)->format('Y-m-d')) }}"
                               class="form-control @error('measurement_date') is-invalid @enderror">

                        @error('measurement_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- EFFECTIVE FROM --}}
                    <div class="col-md-6">

                        <label for="effective_from"
                               class="form-label">

                            Effective From

                        </label>

                        <input type="date"
                               name="effective_from"
                               id="effective_from"
                               value="{{ old('effective_from', optional($area->effective_from)->format('Y-m-d')) }}"
                               class="form-control @error('effective_from') is-invalid @enderror">

                        @error('effective_from')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- EFFECTIVE TO --}}
                    <div class="col-md-6">

                        <label for="effective_to"
                               class="form-label">

                            Effective To

                        </label>

                        <input type="date"
                               name="effective_to"
                               id="effective_to"
                               value="{{ old('effective_to', optional($area->effective_to)->format('Y-m-d')) }}"
                               class="form-control @error('effective_to') is-invalid @enderror">

                        @error('effective_to')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- MEASUREMENT METHOD --}}
                    <div class="col-md-6">

                        <label for="measurement_method"
                               class="form-label">

                            Measurement Method

                        </label>

                        <select name="measurement_method"
                                id="measurement_method"
                                class="form-select @error('measurement_method') is-invalid @enderror">

                            <option value="">
                                -- Select Method --
                            </option>

                            <option value="PHYSICAL"
                                @if(old('measurement_method', $area->measurement_method) == 'PHYSICAL')
                                    selected
                                @endif>
                                Physical Measurement
                            </option>

                            <option value="SURVEY"
                                @if(old('measurement_method', $area->measurement_method) == 'SURVEY')
                                    selected
                                @endif>
                                Professional Survey
                            </option>

                            <option value="ARCHITECTURAL"
                                @if(old('measurement_method', $area->measurement_method) == 'ARCHITECTURAL')
                                    selected
                                @endif>
                                Architectural Drawing
                            </option>

                            <option value="CAD"
                                @if(old('measurement_method', $area->measurement_method) == 'CAD')
                                    selected
                                @endif>
                                CAD / Digital Drawing
                            </option>

                            <option value="GIS"
                                @if(old('measurement_method', $area->measurement_method) == 'GIS')
                                    selected
                                @endif>
                                GIS Measurement
                            </option>

                            <option value="ESTIMATED"
                                @if(old('measurement_method', $area->measurement_method) == 'ESTIMATED')
                                    selected
                                @endif>
                                Estimated
                            </option>

                            <option value="OTHER"
                                @if(old('measurement_method', $area->measurement_method) == 'OTHER')
                                    selected
                                @endif>
                                Other
                            </option>

                        </select>

                        @error('measurement_method')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- MEASUREMENT STANDARD --}}
                    <div class="col-md-6">

                        <label for="measurement_standard"
                               class="form-label">

                            Measurement Standard

                        </label>

                        <select name="measurement_standard"
                                id="measurement_standard"
                                class="form-select @error('measurement_standard') is-invalid @enderror">

                            <option value="">
                                -- Select Standard --
                            </option>

                            <option value="BOMA"
                                @if(old('measurement_standard', $area->measurement_standard) == 'BOMA')
                                    selected
                                @endif>
                                BOMA
                            </option>

                            <option value="IPMS"
                                @if(old('measurement_standard', $area->measurement_standard) == 'IPMS')
                                    selected
                                @endif>
                                IPMS
                            </option>

                            <option value="RICS"
                                @if(old('measurement_standard', $area->measurement_standard) == 'RICS')
                                    selected
                                @endif>
                                RICS
                            </option>

                            <option value="LOCAL"
                                @if(old('measurement_standard', $area->measurement_standard) == 'LOCAL')
                                    selected
                                @endif>
                                Local Standard
                            </option>

                            <option value="INTERNAL"
                                @if(old('measurement_standard', $area->measurement_standard) == 'INTERNAL')
                                    selected
                                @endif>
                                Internal Standard
                            </option>

                            <option value="OTHER"
                                @if(old('measurement_standard', $area->measurement_standard) == 'OTHER')
                                    selected
                                @endif>
                                Other
                            </option>

                        </select>

                        @error('measurement_standard')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
            STATUS & REMARKS
        ========================================================= --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-1">
                    <i class="ri-information-line me-2 text-primary"></i>
                    Status & Remarks
                </h5>

                <small class="text-muted">
                    Set the current status and add notes.
                </small>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- STATUS --}}
                    <div class="col-md-6">

                        <label for="status"
                               class="form-label">

                            Status

                        </label>

                        <select name="status"
                                id="status"
                                class="form-select @error('status') is-invalid @enderror">

                            <option value="ACTIVE"
                                @if(old('status', $area->status ?? 'ACTIVE') == 'ACTIVE')
                                    selected
                                @endif>
                                Active
                            </option>

                            <option value="INACTIVE"
                                @if(old('status', $area->status) == 'INACTIVE')
                                    selected
                                @endif>
                                Inactive
                            </option>

                            <option value="DRAFT"
                                @if(old('status', $area->status) == 'DRAFT')
                                    selected
                                @endif>
                                Draft
                            </option>

                        </select>

                        @error('status')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- REMARKS --}}
                    <div class="col-12">

                        <label for="remarks"
                               class="form-label">

                            Remarks

                        </label>

                        <textarea name="remarks"
                                  id="remarks"
                                  rows="4"
                                  class="form-control @error('remarks') is-invalid @enderror"
                                  placeholder="Enter additional remarks...">{{ old('remarks', $area->remarks) }}</textarea>

                        @error('remarks')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
            FORM ACTIONS
        ========================================================= --}}
        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                    <div class="text-muted small">

                        <i class="ri-information-line me-1"></i>

                        Fields marked with
                        <span class="text-danger">*</span>
                        are required.

                    </div>


                    <div class="d-flex gap-2">

                        <a href="{{ route('admin.assets.areas.show', $area->id) }}"
                           class="btn btn-outline-primary">

                            <i class="ri-eye-line me-1"></i>
                            Cancel

                        </a>


                        <button type="reset"
                                class="btn btn-outline-warning">

                            <i class="ri-refresh-line me-1"></i>
                            Reset

                        </button>


                        <button type="submit"
                                class="btn btn-primary">

                            <i class="ri-save-3-line me-1"></i>
                            Update Property Area

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection


{{-- ============================================================
    JAVASCRIPT
============================================================ --}}
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const effectiveFrom =
        document.getElementById('effective_from');

    const effectiveTo =
        document.getElementById('effective_to');

    const form =
        document.getElementById('propertyAreaForm');


    /*
    |--------------------------------------------------------------------------
    | Effective Date Validation
    |--------------------------------------------------------------------------
    */

    if (effectiveFrom && effectiveTo) {

        effectiveFrom.addEventListener('change', function () {

            effectiveTo.min = this.value;

            if (
                effectiveTo.value &&
                this.value &&
                effectiveTo.value < this.value
            ) {

                effectiveTo.value = '';

            }

        });

        if (effectiveFrom.value) {

            effectiveTo.min = effectiveFrom.value;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Prevent Double Submission
    |--------------------------------------------------------------------------
    */

    if (form) {

        form.addEventListener('submit', function () {

            const submitButton =
                form.querySelector('button[type="submit"]');

            if (submitButton) {

                submitButton.disabled = true;

                submitButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-1"></span>' +
                    'Updating...';

            }

        });

    }

});

</script>

@endpush