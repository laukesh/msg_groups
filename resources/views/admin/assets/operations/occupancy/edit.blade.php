@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Edit Occupancy
            </h4>

            <p class="text-muted mb-0">
                Update asset occupancy information.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.assets.operations.occupancy.show',
                    $occupancy
                ) }}"
                class="btn btn-info"
            >
                <i class="ri-eye-line"></i>
                View
            </a>

            <a
                href="{{ route(
                    'admin.assets.operations.occupancy.index'
                ) }}"
                class="btn btn-secondary"
            >
                <i class="ri-arrow-left-line"></i>
                Back
            </a>

        </div>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="card">

        <div class="card-body">

            <form
                method="POST"
                action="{{ route(
                    'admin.assets.operations.occupancy.update',
                    $occupancy
                ) }}"
            >

                @csrf

                @method('PUT')


                <div class="row g-3">


                    {{-- Asset --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Asset <span class="text-danger">*</span>
                        </label>

                        <select
                            name="asset_id"
                            class="form-select @error('asset_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Asset
                            </option>

                            @foreach($assets as $asset)

                                <option
                                    value="{{ $asset->id }}"
                                    {{ old(
                                        'asset_id',
                                        $occupancy->asset_id
                                    ) == $asset->id ? 'selected' : '' }}
                                >

                                    {{ $asset->asset_name }}

                                    @if($asset->asset_code)
                                        ({{ $asset->asset_code }})
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('asset_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Occupancy Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="occupancy_status"
                            class="form-select @error('occupancy_status') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Status
                            </option>

                            <option value="occupied"
                                {{ old(
                                    'occupancy_status',
                                    $occupancy->occupancy_status
                                ) == 'occupied' ? 'selected' : '' }}>
                                Occupied
                            </option>

                            <option value="vacant"
                                {{ old(
                                    'occupancy_status',
                                    $occupancy->occupancy_status
                                ) == 'vacant' ? 'selected' : '' }}>
                                Vacant
                            </option>

                            <option value="partially_occupied"
                                {{ old(
                                    'occupancy_status',
                                    $occupancy->occupancy_status
                                ) == 'partially_occupied' ? 'selected' : '' }}>
                                Partially Occupied
                            </option>

                            <option value="under_maintenance"
                                {{ old(
                                    'occupancy_status',
                                    $occupancy->occupancy_status
                                ) == 'under_maintenance' ? 'selected' : '' }}>
                                Under Maintenance
                            </option>

                            <option value="reserved"
                                {{ old(
                                    'occupancy_status',
                                    $occupancy->occupancy_status
                                ) == 'reserved' ? 'selected' : '' }}>
                                Reserved
                            </option>

                        </select>

                        @error('occupancy_status')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Occupancy --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Occupancy Percentage
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                name="occupancy_percentage"
                                value="{{ old(
                                    'occupancy_percentage',
                                    $occupancy->occupancy_percentage
                                ) }}"
                                class="form-control @error('occupancy_percentage') is-invalid @enderror"
                                min="0"
                                max="100"
                                step="0.01"
                                required
                            >

                            <span class="input-group-text">
                                %
                            </span>

                        </div>

                        @error('occupancy_percentage')

                            <div class="text-danger small">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Tenant --}}
                    <div class="col-md-8">

                        <label class="form-label">
                            Tenant Name
                        </label>

                        <input
                            type="text"
                            name="tenant_name"
                            value="{{ old(
                                'tenant_name',
                                $occupancy->tenant_name
                            ) }}"
                            class="form-control @error('tenant_name') is-invalid @enderror"
                            placeholder="Enter tenant name"
                        >

                        @error('tenant_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Lease Start --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Lease Start Date
                        </label>

                        <input
                            type="date"
                            name="lease_start_date"
                            value="{{ old(
                                'lease_start_date',
                                optional(
                                    $occupancy->lease_start_date
                                )->format('Y-m-d')
                            ) }}"
                            class="form-control @error('lease_start_date') is-invalid @enderror"
                        >

                        @error('lease_start_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Lease End --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Lease End Date
                        </label>

                        <input
                            type="date"
                            name="lease_end_date"
                            value="{{ old(
                                'lease_end_date',
                                optional(
                                    $occupancy->lease_end_date
                                )->format('Y-m-d')
                            ) }}"
                            class="form-control @error('lease_end_date') is-invalid @enderror"
                        >

                        @error('lease_end_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Rent --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Monthly Rent
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input
                                type="number"
                                name="monthly_rent"
                                value="{{ old(
                                    'monthly_rent',
                                    $occupancy->monthly_rent
                                ) }}"
                                class="form-control @error('monthly_rent') is-invalid @enderror"
                                min="0"
                                step="0.01"
                            >

                        </div>

                        @error('monthly_rent')

                            <div class="text-danger small">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Remarks --}}
                    <div class="col-12">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="4"
                            class="form-control @error('remarks') is-invalid @enderror"
                            placeholder="Enter remarks"
                        >{{ old(
                            'remarks',
                            $occupancy->remarks
                        ) }}</textarea>

                        @error('remarks')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Buttons --}}
                    <div class="col-12">

                        <hr>

                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="{{ route(
                                    'admin.assets.operations.occupancy.index'
                                ) }}"
                                class="btn btn-secondary"
                            >
                                Cancel
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="ri-save-line"></i>
                                Update Occupancy
                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection