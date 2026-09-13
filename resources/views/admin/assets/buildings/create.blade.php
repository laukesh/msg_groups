@extends('layouts.app')

@section('title', 'Create Building')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

       
 <div>
            <h4 class="h3 mb-1">
                <i class="fas fa-building me-2"></i>
                Create Building
            </h4>

            <p class="text-muted mb-0">
                Add a new building to the system.
            </p>
        </div>

        <a
            href="{{ route('admin.assets.buildings.index') }}"
            class="btn btn-secondary"
        >
            <i class="fas fa-arrow-left me-1"></i>
            Back to buildings
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

        <div class="card-header">
            <h5 class="mb-0">
              <i class="fas fa-info-circle me-1"></i>  Building Information
            </h5>
        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route('admin.assets.buildings.store') }}"
            >

                @csrf

                <div class="row">

                    {{-- Mall --}}

                    <div class="col-md-6 mb-3">

                        <label
                            for="mall_id"
                            class="form-label"
                        >
                            <i class="fas fa-building me-1"></i> Mall
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="mall_id"
                            id="mall_id"
                            class="form-select @error('mall_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                <i class="fas fa-building me-1"></i> Select Mall
                            </option>

                            @foreach($malls as $id => $name)

                                <option
                                    value="{{ $id }}"
                                    {{ old('mall_id') == $id
                                        ? 'selected'
                                        : '' }}
                                >
                                    {{ $name }}
                                </option>

                            @endforeach

                        </select>

                        @error('mall_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Building Code --}}

                    <div class="col-md-6 mb-3">

                        <label
                            for="building_code"
                            class="form-label"
                        >
                            <i class="fas fa-barcode me-1"></i> Building Code
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="building_code"
                            id="building_code"
                            class="form-control @error('building_code') is-invalid @enderror"
                            value="{{ old('building_code') }}"
                            placeholder="e.g. BLD-001"
                            required
                        >

                        @error('building_code')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Building Name --}}

                    <div class="col-md-6 mb-3">

                        <label
                            for="building_name"
                            class="form-label"
                        >
                            <i class="fas fa-building me-1"></i> Building Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="building_name"
                            id="building_name"
                            class="form-control @error('building_name') is-invalid @enderror"
                            value="{{ old('building_name') }}"
                            placeholder="Enter building name"
                            required
                        >

                        @error('building_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Status --}}

                    <div class="col-md-6 mb-3">

                        <label
                            for="status"
                            class="form-label"
                        >
                            <i class="fas fa-tasks me-1"></i> Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required
                        >

                            <option
                                value="1"
                                {{ old('status', '1') == '1'
                                    ? 'selected'
                                    : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="0"
                                {{ old('status') == '0'
                                    ? 'selected'
                                    : '' }}
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


                    {{-- Total Floors --}}

                    <div class="col-md-6 mb-3">

                        <label
                            for="total_floors"
                            class="form-label"
                        >
                            <i class="fas fa-layer-group me-1"></i> Total Floors
                        </label>

                        <input
                            type="number"
                            name="total_floors"
                            id="total_floors"
                            min="0"
                            class="form-control"
                            value="{{ old('total_floors', 0) }}"
                        >

                    </div>


                    {{-- Total Units --}}

                    <div class="col-md-6 mb-3">

                        <label
                            for="total_units"
                            class="form-label"
                        >
                            <i class="fas fa-home me-1"></i> Total Units
                        </label>

                        <input
                            type="number"
                            name="total_units"
                            id="total_units"
                            min="0"
                            class="form-control"
                            value="{{ old('total_units', 0) }}"
                        >

                    </div>


                    {{-- Description --}}

                    <div class="col-md-12 mb-3">

                        <label
                            for="description"
                            class="form-label"
                        >
                            <i class="fas fa-align-left me-1"></i> Description
                        </label>

                        <textarea
                            name="description"
                            id="description"
                            rows="4"
                            class="form-control"
                            placeholder="Enter building description..."
                        >{{ old('description') }}</textarea>

                    </div>

                </div>


                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('admin.assets.buildings.index') }}"
                        class="btn btn-secondary"
                    >
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="fas fa-save me-1"></i> Create Building
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection