@extends('layouts.app')

@section('title', 'Edit Building')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="h3 mb-1">
                <i class="fas fa-edit me-1"></i> Edit Building
            </h4>

            <p class="text-muted">
                Update building information.
            </p>

        </div>

        <a
            href="{{ route('admin.assets.buildings.index') }}"
            class="btn btn-secondary"
        >
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>

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
                action="{{ route(
                    'admin.assets.buildings.update',
                    $building->id
                ) }}"
            >

                @csrf
                @method('PUT')

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
                            class="form-select"
                            required
                        >

                            @foreach($malls as $id => $name)

                                <option
                                    value="{{ $id }}"
                                    {{ old(
                                        'mall_id',
                                        $building->mall_id
                                    ) == $id
                                        ? 'selected'
                                        : '' }}
                                >
                                    {{ $name }}
                                </option>

                            @endforeach

                        </select>

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
                            class="form-control"
                            value="{{ old(
                                'building_code',
                                $building->building_code
                            ) }}"
                            required
                        >

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
                            class="form-control"
                            value="{{ old(
                                'building_name',
                                $building->building_name
                            ) }}"
                            required
                        >

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
                            class="form-select"
                            required
                        >

                            <option
                                value="1"
                                {{ old(
                                    'status',
                                    $building->status
                                ) == 1
                                    ? 'selected'
                                    : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="0"
                                {{ old(
                                    'status',
                                    $building->status
                                ) == 0
                                    ? 'selected'
                                    : '' }}
                            >
                                Inactive
                            </option>

                        </select>

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
                            value="{{ old(
                                'total_floors',
                                $building->total_floors
                            ) }}"
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
                            value="{{ old(
                                'total_units',
                                $building->total_units
                            ) }}"
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
                        >{{ old(
                            'description',
                            $building->description
                        ) }}</textarea>

                    </div>

                </div>


                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route(
                            'admin.assets.buildings.show',
                            $building->id
                        ) }}"
                        class="btn btn-secondary"
                    >
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="fas fa-save me-1"></i> Update Building
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection