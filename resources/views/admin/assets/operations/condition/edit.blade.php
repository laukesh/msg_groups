@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Edit Asset Condition
            </h4>

            <p class="text-muted mb-0">
                Update the asset inspection record.
            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.assets.operations.conditions.show',
                    $condition
                ) }}"
                class="btn btn-info"
            >

                <i class="ri-eye-line"></i>

                View

            </a>


            <a
                href="{{ route(
                    'admin.assets.operations.conditions.index'
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
            'admin.assets.operations.conditions.update',
            $condition
        ) }}"
    >

        @csrf

        @method('PUT')


        {{-- Inspection --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Inspection Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Asset --}}
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
                                        $condition->asset_id
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


                    {{-- Date --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Inspection Date
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="inspection_date"
                            value="{{ old(
                                'inspection_date',
                                optional(
                                    $condition->inspection_date
                                )->format('Y-m-d')
                            ) }}"
                            class="form-control"
                            required
                        >

                    </div>


                    {{-- Inspector --}}
                    <div class="col-md-3">

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
                                        $condition->inspector_id
                                    ) == $inspector->id
                                        ? 'selected'
                                        : '' }}
                                >

                                    {{ $inspector->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Condition --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Overall Condition
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="condition"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Excellent',
                                'Good',
                                'Fair',
                                'Poor',
                                'Critical'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    {{ old(
                                        'condition',
                                        $condition->condition
                                    ) == $value
                                        ? 'selected'
                                        : '' }}
                                >

                                    {{ $value }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Score --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Condition Score
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                name="condition_score"
                                value="{{ old(
                                    'condition_score',
                                    $condition->condition_score
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

                </div>

            </div>

        </div>


        {{-- Component Condition --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Component Condition
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    @foreach([
                        'structural_condition' => 'Structural',
                        'electrical_condition' => 'Electrical',
                        'mechanical_condition' => 'Mechanical',
                        'safety_condition' => 'Safety'
                    ] as $field => $label)

                        <div class="col-md-3">

                            <label class="form-label">
                                {{ $label }}
                            </label>

                            <select
                                name="{{ $field }}"
                                class="form-select"
                            >

                                <option value="">
                                    Select
                                </option>

                                @foreach([
                                    'Excellent',
                                    'Good',
                                    'Fair',
                                    'Poor',
                                    'Critical'
                                ] as $value)

                                    <option
                                        value="{{ $value }}"
                                        {{ old(
                                            $field,
                                            $condition->{$field}
                                        ) == $value
                                            ? 'selected'
                                            : '' }}
                                    >

                                        {{ $value }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>


        {{-- Recommendation --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Repair & Recommendation
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    <div class="col-md-4">

                        <label class="form-label">
                            Estimated Repair Cost
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input
                                type="number"
                                name="estimated_repair_cost"
                                value="{{ old(
                                    'estimated_repair_cost',
                                    $condition->estimated_repair_cost
                                ) }}"
                                class="form-control"
                                min="0"
                                step="0.01"
                            >

                        </div>

                    </div>


                    <div class="col-md-8">

                        <label class="form-label">
                            Recommended Action
                        </label>

                        <textarea
                            name="recommended_action"
                            rows="3"
                            class="form-control"
                        >{{ old(
                            'recommended_action',
                            $condition->recommended_action
                        ) }}</textarea>

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="4"
                            class="form-control"
                        >{{ old(
                            'remarks',
                            $condition->remarks
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- Buttons --}}
        <div class="card">

            <div class="card-body">

                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route(
                            'admin.assets.operations.conditions.index'
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

                        Update Condition

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection