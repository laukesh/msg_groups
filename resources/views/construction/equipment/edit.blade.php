@extends('layouts.app')

@section('title', 'Edit Equipment')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Edit Equipment
            </h4>

            <p class="text-muted mb-0">

                {{ $equipment->equipment_code }}

                <span class="mx-1">•</span>

                {{ $equipment->equipment_name }}

            </p>

        </div>

        <div>

            <a
                href="{{ route(
                    'admin.projects.construction.equipment.show',
                    [
                        'project' => $project,
                        'equipment' => $equipment,
                    ]
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
            'admin.projects.construction.equipment.update',
            [
                'project' => $project,
                'equipment' => $equipment,
            ]
        ) }}"
    >

        @csrf

        @method('PUT')


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

                    {{-- Equipment Code --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Equipment Code
                        </label>

                        <input
                            type="text"
                            value="{{ $equipment->equipment_code }}"
                            class="form-control"
                            readonly
                        >

                    </div>


                    {{-- Equipment Name --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Equipment Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="equipment_name"
                            value="{{ old(
                                'equipment_name',
                                $equipment->equipment_name
                            ) }}"
                            class="form-control"
                            required
                        >

                    </div>


                    {{-- Category --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Category
                        </label>

                        <input
                            type="text"
                            name="category"
                            value="{{ old(
                                'category',
                                $equipment->category
                            ) }}"
                            class="form-control"
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

                            @foreach([
                                'Owned',
                                'Hired',
                                'Leased'
                            ] as $ownership)

                                <option
                                    value="{{ $ownership }}"
                                    @selected(
                                        old(
                                            'ownership_type',
                                            $equipment->ownership_type
                                        ) === $ownership
                                    )
                                >
                                    {{ $ownership }}
                                </option>

                            @endforeach

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
                                            $equipment->status
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
                            value="{{ old(
                                'registration_number',
                                $equipment->registration_number
                            ) }}"
                            class="form-control"
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

                    <div class="col-md-4">

                        <label class="form-label">
                            Make
                        </label>

                        <input
                            type="text"
                            name="make"
                            value="{{ old(
                                'make',
                                $equipment->make
                            ) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Model
                        </label>

                        <input
                            type="text"
                            name="model"
                            value="{{ old(
                                'model',
                                $equipment->model
                            ) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Serial Number
                        </label>

                        <input
                            type="text"
                            name="serial_number"
                            value="{{ old(
                                'serial_number',
                                $equipment->serial_number
                            ) }}"
                            class="form-control"
                        >

                    </div>


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
                                value="{{ old(
                                    'capacity',
                                    $equipment->capacity
                                ) }}"
                                class="form-control"
                            >

                            <input
                                type="text"
                                name="capacity_unit"
                                value="{{ old(
                                    'capacity_unit',
                                    $equipment->capacity_unit
                                ) }}"
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

                    <div class="col-md-4">

                        <label class="form-label">
                            Purchase Date
                        </label>

                        <input
                            type="date"
                            name="purchase_date"
                            value="{{ old(
                                'purchase_date',
                                optional(
                                    $equipment->purchase_date
                                )->format('Y-m-d')
                            ) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Purchase Value
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="purchase_value"
                            value="{{ old(
                                'purchase_value',
                                $equipment->purchase_value
                            ) }}"
                            class="form-control"
                        >

                    </div>


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
                                value="{{ old(
                                    'hire_rate',
                                    $equipment->hire_rate
                                ) }}"
                                class="form-control"
                            >

                            <input
                                type="text"
                                name="hire_rate_unit"
                                value="{{ old(
                                    'hire_rate_unit',
                                    $equipment->hire_rate_unit
                                ) }}"
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
                    >{{ old(
                        'description',
                        $equipment->description
                    ) }}</textarea>

                </div>


                <div>

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea
                        name="remarks"
                        rows="3"
                        class="form-control"
                    >{{ old(
                        'remarks',
                        $equipment->remarks
                    ) }}</textarea>

                </div>

            </div>

        </div>


        {{-- Actions --}}

        <div class="d-flex justify-content-between mb-4">

            <div>

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.construction.equipment.destroy',
                        [
                            'project' => $project,
                            'equipment' => $equipment,
                        ]
                    ) }}"
                    onsubmit="return confirm(
                        'Are you sure you want to delete this equipment?'
                    );"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-outline-danger"
                    >
                        Delete Equipment
                    </button>

                </form>

            </div>


            <div class="d-flex gap-2">

                <a
                    href="{{ route(
                        'admin.projects.construction.equipment.show',
                        [
                            'project' => $project,
                            'equipment' => $equipment,
                        ]
                    ) }}"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Update Equipment
                </button>

            </div>

        </div>

    </form>

</div>

@endsection