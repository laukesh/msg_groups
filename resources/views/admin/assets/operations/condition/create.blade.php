@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Add Asset Condition
            </h4>

            <p class="text-muted mb-0">
                Record a new asset condition inspection.
            </p>

        </div>


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
            'admin.assets.operations.conditions.store'
        ) }}"
    >

        @csrf


        {{-- Basic Information --}}
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
                            class="form-select @error('asset_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Asset
                            </option>

                            @foreach($assets as $asset)

                                <option
                                    value="{{ $asset->id }}"
                                    {{ old('asset_id') == $asset->id
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

                        @error('asset_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Inspection Date --}}
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
                                now()->format('Y-m-d')
                            ) }}"
                            class="form-control @error('inspection_date') is-invalid @enderror"
                            required
                        >

                        @error('inspection_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Inspector --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Inspector
                        </label>

                        <select
                            name="inspector_id"
                            class="form-select @error('inspector_id') is-invalid @enderror"
                        >

                            <option value="">
                                Select Inspector
                            </option>

                            @foreach($inspectors as $inspector)

                                <option
                                    value="{{ $inspector->id }}"
                                    {{ old('inspector_id') == $inspector->id
                                        ? 'selected'
                                        : '' }}
                                >

                                    {{ $inspector->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('inspector_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Condition --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Overall Condition
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="condition"
                            class="form-select @error('condition') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Condition
                            </option>

                            <option value="Excellent"
                                {{ old('condition') == 'Excellent'
                                    ? 'selected'
                                    : '' }}>
                                Excellent
                            </option>

                            <option value="Good"
                                {{ old('condition') == 'Good'
                                    ? 'selected'
                                    : '' }}>
                                Good
                            </option>

                            <option value="Fair"
                                {{ old('condition') == 'Fair'
                                    ? 'selected'
                                    : '' }}>
                                Fair
                            </option>

                            <option value="Poor"
                                {{ old('condition') == 'Poor'
                                    ? 'selected'
                                    : '' }}>
                                Poor
                            </option>

                            <option value="Critical"
                                {{ old('condition') == 'Critical'
                                    ? 'selected'
                                    : '' }}>
                                Critical
                            </option>

                        </select>

                        @error('condition')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

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
                                value="{{ old('condition_score') }}"
                                class="form-control @error('condition_score') is-invalid @enderror"
                                min="0"
                                max="100"
                                step="0.01"
                                placeholder="0 - 100"
                            >

                            <span class="input-group-text">
                                %
                            </span>

                        </div>

                        @error('condition_score')

                            <div class="text-danger small">
                                {{ $message }}
                            </div>

                        @enderror

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


                    <div class="col-md-3">

                        <label class="form-label">
                            Structural
                        </label>

                        <select
                            name="structural_condition"
                            class="form-select"
                        >

                            <option value="">
                                Select
                            </option>

                            <option value="Excellent"
                                {{ old('structural_condition') == 'Excellent'
                                    ? 'selected' : '' }}>
                                Excellent
                            </option>

                            <option value="Good"
                                {{ old('structural_condition') == 'Good'
                                    ? 'selected' : '' }}>
                                Good
                            </option>

                            <option value="Fair"
                                {{ old('structural_condition') == 'Fair'
                                    ? 'selected' : '' }}>
                                Fair
                            </option>

                            <option value="Poor"
                                {{ old('structural_condition') == 'Poor'
                                    ? 'selected' : '' }}>
                                Poor
                            </option>

                            <option value="Critical"
                                {{ old('structural_condition') == 'Critical'
                                    ? 'selected' : '' }}>
                                Critical
                            </option>

                        </select>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Electrical
                        </label>

                        <select
                            name="electrical_condition"
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

                                <option value="{{ $value }}"
                                    {{ old('electrical_condition') == $value
                                        ? 'selected' : '' }}>

                                    {{ $value }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Mechanical
                        </label>

                        <select
                            name="mechanical_condition"
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

                                <option value="{{ $value }}"
                                    {{ old('mechanical_condition') == $value
                                        ? 'selected' : '' }}>

                                    {{ $value }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Safety
                        </label>

                        <select
                            name="safety_condition"
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

                                <option value="{{ $value }}"
                                    {{ old('safety_condition') == $value
                                        ? 'selected' : '' }}>

                                    {{ $value }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- Cost and Action --}}
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
                                    0
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
                            placeholder="Enter recommended maintenance, repair or replacement action"
                        >{{ old('recommended_action') }}</textarea>

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="4"
                            class="form-control"
                            placeholder="Enter inspection remarks"
                        >{{ old('remarks') }}</textarea>

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

                        Save Condition

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection