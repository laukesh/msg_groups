@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Edit Asset Performance
            </h4>

            <p class="text-muted mb-0">
                Update asset operational performance.
            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.assets.operations.performance.show',
                    $performance
                ) }}"
                class="btn btn-info"
            >

                <i class="ri-eye-line"></i>

                View

            </a>


            <a
                href="{{ route(
                    'admin.assets.operations.performance.index'
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


    <form
        method="POST"
        action="{{ route(
            'admin.assets.operations.performance.update',
            $performance
        ) }}"
    >

        @csrf

        @method('PUT')


        {{-- Basic --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Performance Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    <div class="col-md-6">

                        <label class="form-label">

                            Asset

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="asset_id"
                            class="form-select"
                            required
                        >

                            @foreach($assets as $asset)

                                <option
                                    value="{{ $asset->id }}"
                                    {{ old(
                                        'asset_id',
                                        $performance->asset_id
                                    ) == $asset->id
                                        ? 'selected'
                                        : '' }}
                                >

                                    {{ $asset->asset_name }}

                                    @if($asset->asset_code)

                                        ({{ $asset->asset_code }})

                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">

                            Performance Date

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="date"
                            name="performance_date"
                            value="{{ old(
                                'performance_date',
                                optional(
                                    $performance->performance_date
                                )->format('Y-m-d')
                            ) }}"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">

                            Status

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="performance_status"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Excellent',
                                'Good',
                                'Average',
                                'Poor',
                                'Critical'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ old(
                                        'performance_status',
                                        $performance->performance_status
                                    ) == $status
                                        ? 'selected'
                                        : '' }}
                                >

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Performance Score
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                name="performance_score"
                                value="{{ old(
                                    'performance_score',
                                    $performance->performance_score
                                ) }}"
                                class="form-control"
                                min="0"
                                max="100"
                                step="0.01"
                            >

                            <span class="input-group-text">
                                %
                            </span>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Inspector
                        </label>

                        <select
                            name="inspector_id"
                            class="form-select"
                        >

                            <option value="">
                                Select Inspector
                            </option>

                            @foreach($inspectors as $inspector)

                                <option
                                    value="{{ $inspector->id }}"
                                    {{ old(
                                        'inspector_id',
                                        $performance->inspector_id
                                    ) == $inspector->id
                                        ? 'selected'
                                        : '' }}
                                >

                                    {{ $inspector->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- KPI --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Operational KPIs
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    @foreach([
                        'availability_percentage' => 'Availability',
                        'utilization_percentage' => 'Utilization',
                        'efficiency_percentage' => 'Efficiency'
                    ] as $field => $label)

                        <div class="col-md-4">

                            <label class="form-label">
                                {{ $label }}
                            </label>

                            <div class="input-group">

                                <input
                                    type="number"
                                    name="{{ $field }}"
                                    value="{{ old(
                                        $field,
                                        $performance->{$field}
                                    ) }}"
                                    class="form-control"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                >

                                <span class="input-group-text">
                                    %
                                </span>

                            </div>

                        </div>

                    @endforeach


                    <div class="col-md-6">

                        <label class="form-label">
                            Operating Hours
                        </label>

                        <input
                            type="number"
                            name="operating_hours"
                            value="{{ old(
                                'operating_hours',
                                $performance->operating_hours
                            ) }}"
                            class="form-control"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Downtime Hours
                        </label>

                        <input
                            type="number"
                            name="downtime_hours"
                            value="{{ old(
                                'downtime_hours',
                                $performance->downtime_hours
                            ) }}"
                            class="form-control"
                            min="0"
                            step="0.01"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- Financial --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Financial Performance
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    @foreach([
                        'maintenance_cost' => 'Maintenance Cost',
                        'operating_cost' => 'Operating Cost',
                        'revenue_generated' => 'Revenue Generated',
                        'target_value' => 'Target Value',
                        'actual_value' => 'Actual Value'
                    ] as $field => $label)

                        <div class="col-md-4">

                            <label class="form-label">
                                {{ $label }}
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    $
                                </span>

                                <input
                                    type="number"
                                    name="{{ $field }}"
                                    value="{{ old(
                                        $field,
                                        $performance->{$field}
                                    ) }}"
                                    class="form-control"
                                    min="0"
                                    step="0.01"
                                >

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>


        {{-- Remarks --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Remarks
                </h5>

            </div>


            <div class="card-body">

                <textarea
                    name="remarks"
                    rows="5"
                    class="form-control"
                >{{ old(
                    'remarks',
                    $performance->remarks
                ) }}</textarea>

            </div>

        </div>


        {{-- Buttons --}}
        <div class="card">

            <div class="card-body">

                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route(
                            'admin.assets.operations.performance.index'
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

                        Update Performance

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection