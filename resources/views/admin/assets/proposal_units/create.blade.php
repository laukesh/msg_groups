@extends('layouts.app')

@section('title', 'Create Proposal Unit')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1">
                Create Proposal Unit
            </h1>

            <p class="text-muted mb-0">
                Create a new proposal unit.
            </p>
        </div>

        <a
            href="{{ route('admin.assets.proposal_units.index') }}"
            class="btn btn-secondary"
        >
            ← Back
        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Proposal Unit Form --}}
    <div class="card">

        <div class="card-header">

            <h5 class="mb-0">
                Proposal Unit Information
            </h5>

        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route('admin.assets.proposal_units.store') }}"
            >

                @csrf

                <div class="row">

                    {{-- Proposal --}}
                    <div class="col-md-6 mb-3">

                        <label
                            for="proposal_id"
                            class="form-label"
                        >
                            Proposal
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            id="proposal_id"
                            name="proposal_id"
                            class="form-select @error('proposal_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Proposal
                            </option>

                            @foreach($proposals ?? [] as $id => $title)

                                <option
                                    value="{{ $id }}"
                                    {{ old('proposal_id') == $id ? 'selected' : '' }}
                                >
                                    {{ $title }}
                                </option>

                            @endforeach

                        </select>

                        @error('proposal_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Unit --}}
                    <div class="col-md-6 mb-3">

                        <label
                            for="unit_id"
                            class="form-label"
                        >
                            Unit
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            id="unit_id"
                            name="unit_id"
                            class="form-select @error('unit_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Unit
                            </option>

                            @foreach($units ?? [] as $id => $no)

                                <option
                                    value="{{ $id }}"
                                    {{ old('unit_id') == $id ? 'selected' : '' }}
                                >
                                    {{ $no }}
                                </option>

                            @endforeach

                        </select>

                        @error('unit_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Proposed Rent --}}
                    <div class="col-md-4 mb-3">

                        <label
                            for="proposed_rent"
                            class="form-label"
                        >
                            Proposed Rent
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            id="proposed_rent"
                            name="proposed_rent"
                            class="form-control @error('proposed_rent') is-invalid @enderror"
                            value="{{ old('proposed_rent') }}"
                            placeholder="0.00"
                        >

                        @error('proposed_rent')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Proposed CAM Rate --}}
                    <div class="col-md-4 mb-3">

                        <label
                            for="proposed_cam_rate"
                            class="form-label"
                        >
                            Proposed CAM Rate
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            id="proposed_cam_rate"
                            name="proposed_cam_rate"
                            class="form-control @error('proposed_cam_rate') is-invalid @enderror"
                            value="{{ old('proposed_cam_rate') }}"
                            placeholder="0.00"
                        >

                        @error('proposed_cam_rate')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Security Deposit --}}
                    <div class="col-md-4 mb-3">

                        <label
                            for="proposed_security_deposit"
                            class="form-label"
                        >
                            Proposed Security Deposit
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            id="proposed_security_deposit"
                            name="proposed_security_deposit"
                            class="form-control @error('proposed_security_deposit') is-invalid @enderror"
                            value="{{ old('proposed_security_deposit') }}"
                            placeholder="0.00"
                        >

                        @error('proposed_security_deposit')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Rent Free Days --}}
                    <div class="col-md-3 mb-3">

                        <label
                            for="rent_free_days"
                            class="form-label"
                        >
                            Rent Free Days
                        </label>

                        <input
                            type="number"
                            min="0"
                            id="rent_free_days"
                            name="rent_free_days"
                            class="form-control @error('rent_free_days') is-invalid @enderror"
                            value="{{ old('rent_free_days', 0) }}"
                        >

                        @error('rent_free_days')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Fitout Period --}}
                    <div class="col-md-3 mb-3">

                        <label
                            for="fitout_period_days"
                            class="form-label"
                        >
                            Fitout Period (Days)
                        </label>

                        <input
                            type="number"
                            min="0"
                            id="fitout_period_days"
                            name="fitout_period_days"
                            class="form-control @error('fitout_period_days') is-invalid @enderror"
                            value="{{ old('fitout_period_days', 0) }}"
                        >

                        @error('fitout_period_days')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Remarks --}}
                    <div class="col-md-12 mb-3">

                        <label
                            for="remarks"
                            class="form-label"
                        >
                            Remarks
                        </label>

                        <textarea
                            id="remarks"
                            name="remarks"
                            rows="4"
                            class="form-control @error('remarks') is-invalid @enderror"
                            placeholder="Enter remarks..."
                        >{{ old('remarks') }}</textarea>

                        @error('remarks')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- Form Actions --}}
                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('admin.assets.proposal_units.index') }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Create Proposal Unit
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection