@extends('layouts.app')

@section('title', 'Add Equipment')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Add Equipment
            </h4>

            <p class="text-muted mb-0">

                {{ $project->project_number }}

                <span class="mx-1">•</span>

                {{ $project->project_name }}

            </p>

        </div>

        <div>

            <a
                href="{{ route(
                    'admin.projects.construction.equipment.index',
                    $project
                ) }}"
                class="btn btn-light border"
            >
                ← Back to Equipment
            </a>

        </div>

    </div>


    {{-- Validation Errors --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'admin.projects.construction.equipment.store',
            $project
        ) }}"
    >

        @csrf


        {{-- ========================================================= --}}
        {{-- BASIC INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h6 class="mb-0 fw-bold">
                    Equipment Information
                </h6>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Equipment Name --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Equipment Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="equipment_name"
                            value="{{ old('equipment_name') }}"
                            class="form-control @error('equipment_name') is-invalid @enderror"
                            placeholder="e.g. Excavator 20 Ton"
                            required
                        >

                        @error('equipment_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Category --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Category
                        </label>

                        <input
                            type="text"
                            name="category"
                            value="{{ old('category') }}"
                            class="form-control"
                            placeholder="e.g. Earth Moving"
                        >

                    </div>


                    {{-- Ownership --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Ownership Type
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="ownership_type"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Ownership
                            </option>

                            <option
                                value="Owned"
                                @selected(old('ownership_type') === 'Owned')
                            >
                                Owned
                            </option>

                            <option
                                value="Hired"
                                @selected(old('ownership_type') === 'Hired')
                            >
                                Hired
                            </option>

                            <option
                                value="Leased"
                                @selected(old('ownership_type') === 'Leased')
                            >
                                Leased
                            </option>

                        </select>

                    </div>


                    {{-- Status --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Available',
                                'Deployed',
                                'Under Maintenance',
                                'Breakdown',
                                'Retired'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        old(
                                            'status',
                                            'Available'
                                        ) === $status
                                    )
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Registration --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Registration Number
                        </label>

                        <input
                            type="text"
                            name="registration_number"
                            value="{{ old('registration_number') }}"
                            class="form-control"
                            placeholder="e.g. DL01AB1234"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- TECHNICAL INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h6 class="mb-0 fw-bold">
                    Technical Information
                </h6>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Make --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Make
                        </label>

                        <input
                            type="text"
                            name="make"
                            value="{{ old('make') }}"
                            class="form-control"
                            placeholder="e.g. JCB"
                        >

                    </div>


                    {{-- Model --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Model
                        </label>

                        <input
                            type="text"
                            name="model"
                            value="{{ old('model') }}"
                            class="form-control"
                            placeholder="e.g. 3DX"
                        >

                    </div>


                    {{-- Serial Number --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Serial Number
                        </label>

                        <input
                            type="text"
                            name="serial_number"
                            value="{{ old('serial_number') }}"
                            class="form-control"
                        >

                    </div>


                    {{-- Capacity --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Capacity
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                step="0.0001"
                                min="0"
                                name="capacity"
                                value="{{ old('capacity') }}"
                                class="form-control"
                                placeholder="e.g. 20"
                            >

                            <input
                                type="text"
                                name="capacity_unit"
                                value="{{ old('capacity_unit') }}"
                                class="form-control"
                                placeholder="Ton / m³ / L"
                            >

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- COMMERCIAL INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h6 class="mb-0 fw-bold">
                    Commercial Information
                </h6>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Purchase Date --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Purchase Date
                        </label>

                        <input
                            type="date"
                            name="purchase_date"
                            value="{{ old('purchase_date') }}"
                            class="form-control"
                        >

                    </div>


                    {{-- Purchase Value --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Purchase Value
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="purchase_value"
                            value="{{ old('purchase_value') }}"
                            class="form-control"
                            placeholder="0.00"
                        >

                    </div>


                    {{-- Hire Rate --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Hire Rate
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="hire_rate"
                                value="{{ old('hire_rate') }}"
                                class="form-control"
                                placeholder="0.00"
                            >

                            <input
                                type="text"
                                name="hire_rate_unit"
                                value="{{ old('hire_rate_unit') }}"
                                class="form-control"
                                placeholder="Day / Hour"
                            >

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- DESCRIPTION --}}
        {{-- ========================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h6 class="mb-0 fw-bold">
                    Additional Information
                </h6>

            </div>

            <div class="card-body">

                <div class="mb-3">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="4"
                        class="form-control"
                        placeholder="Equipment description..."
                    >{{ old('description') }}</textarea>

                </div>


                <div>

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea
                        name="remarks"
                        rows="3"
                        class="form-control"
                        placeholder="Additional remarks..."
                    >{{ old('remarks') }}</textarea>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- ACTIONS --}}
        {{-- ========================================================= --}}

        <div class="d-flex justify-content-end gap-2 mb-4">

            <a
                href="{{ route(
                    'admin.projects.construction.equipment.index',
                    $project
                ) }}"
                class="btn btn-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Equipment
            </button>

        </div>

    </form>

</div>

@endsection