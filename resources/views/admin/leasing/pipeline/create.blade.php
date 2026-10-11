@extends('layouts.app')

@section('title', 'Create Leasing Opportunity')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-semibold">
                <i class="ri-add-circle-line me-2"></i>
                Create Leasing Opportunity
            </h4>

            <p class="text-muted mb-0">
                Add a new opportunity to the leasing pipeline.
            </p>
        </div>

        <a href="{{ route('admin.leasing.pipeline.index') }}"
           class="btn btn-light border">
            <i class="ri-arrow-left-line me-1"></i>
            Back to Pipeline
        </a>

    </div>


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">
                <i class="ri-error-warning-line me-1"></i>
                Please correct the following errors:
            </div>

            <ul class="mb-0 ps-3">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route('admin.leasing.pipeline.store') }}">

        @csrf

        <div class="row g-4">

            {{-- =================================================
                MAIN FORM
            ================================================== --}}
            <div class="col-xl-8">

                {{-- Opportunity --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h6 class="mb-0 fw-semibold">
                            <i class="ri-flow-chart me-2"></i>
                            Opportunity Information
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Prospect --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Prospect
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="prospect_name"
                                       value="{{ old('prospect_name') }}"
                                       class="form-control @error('prospect_name') is-invalid @enderror"
                                       placeholder="Enter prospect name"
                                       required>

                                @error('prospect_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Company --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Company
                                </label>

                                <input type="text"
                                       name="company_name"
                                       value="{{ old('company_name') }}"
                                       class="form-control"
                                       placeholder="Company / organization">

                            </div>


                            {{-- Stage --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Pipeline Stage
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="stage"
                                        class="form-select @error('stage') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select Stage
                                    </option>

                                    <option value="lead"
                                        @selected(old('stage') === 'lead')}>
                                        Lead
                                    </option>

                                    <option value="prospect"
                                        @selected(old('stage') === 'prospect')}>
                                        Prospect
                                    </option>

                                    <option value="negotiation"
                                        @selected(old('stage') === 'negotiation')}>
                                        Negotiation
                                    </option>

                                    <option value="agreement"
                                        @selected(old('stage') === 'agreement')}>
                                        Agreement
                                    </option>

                                    <option value="opening"
                                        @selected(old('stage') === 'opening')}>
                                        Opening
                                    </option>

                                    <option value="won"
                                        @selected(old('stage') === 'won')}>
                                        Won
                                    </option>

                                    <option value="lost"
                                        @selected(old('stage') === 'lost')}>
                                        Lost
                                    </option>

                                </select>

                                @error('stage')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Unit --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Unit
                                </label>

                                <select name="unit_id"
                                        class="form-select @error('unit_id') is-invalid @enderror">

                                    <option value="">
                                        Select Unit
                                    </option>

                                    @foreach(($units ?? []) as $unit)

                                        <option value="{{ $unit->id }}"
                                            @selected(old('unit_id') == $unit->id)>

                                            {{ $unit->unit_no}}

                                        </option>

                                    @endforeach

                                </select>

                                @error('unit_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Area --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Area
                                </label>

                                <div class="input-group">

                                    <input type="number"
                                           step="0.01"
                                           min="0"
                                           name="area"
                                           value="{{ old('area') }}"
                                           class="form-control @error('area') is-invalid @enderror"
                                           placeholder="0.00">

                                    <span class="input-group-text">
                                        sq.ft
                                    </span>

                                </div>

                                @error('area')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Expected Rent --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Expected Rent
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </span>

                                    <input type="number"
                                           step="0.01"
                                           min="0"
                                           name="expected_rent"
                                           value="{{ old('expected_rent') }}"
                                           class="form-control @error('expected_rent') is-invalid @enderror"
                                           placeholder="0.00">

                                </div>

                                @error('expected_rent')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Rent Period --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Rent Period
                                </label>

                                <select name="rent_period"
                                        class="form-select">

                                    <option value="">
                                        Select Period
                                    </option>

                                    <option value="monthly"
                                        @selected(old('rent_period') === 'monthly')}>
                                        Monthly
                                    </option>

                                    <option value="quarterly"
                                        @selected(old('rent_period') === 'quarterly')}>
                                        Quarterly
                                    </option>

                                    <option value="yearly"
                                        @selected(old('rent_period') === 'yearly')}>
                                        Yearly
                                    </option>

                                </select>

                            </div>


                            {{-- Probability --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Probability
                                </label>

                                <div class="input-group">

                                    <input type="number"
                                           name="probability"
                                           min="0"
                                           max="100"
                                           step="1"
                                           value="{{ old('probability', 0) }}"
                                           class="form-control @error('probability') is-invalid @enderror"
                                           placeholder="0">

                                    <span class="input-group-text">
                                        %
                                    </span>

                                </div>

                                @error('probability')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    ACTION & FORECAST
                ================================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h6 class="mb-0 fw-semibold">
                            <i class="ri-calendar-check-line me-2"></i>
                            Action & Forecast
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Next Action --}}
                            <div class="col-md-8">

                                <label class="form-label fw-semibold">
                                    Next Action
                                </label>

                                <input type="text"
                                       name="next_action"
                                       value="{{ old('next_action') }}"
                                       class="form-control"
                                       placeholder="e.g. Schedule commercial negotiation">

                            </div>


                            {{-- Owner --}}
                            <div class="col-md-4">

                                <label class="form-label fw-semibold">
                                    Pipeline Owner
                                </label>

                                <select name="owner_id"
                                        class="form-select">

                                    <option value="">
                                        Select Owner
                                    </option>

                                    @foreach(($owners ?? []) as $owner)

                                        <option value="{{ $owner->id }}"
                                            @selected(old('owner_id') == $owner->id)>

                                            {{ $owner->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Forecast Opening --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Forecast Opening Date
                                </label>

                                <input type="date"
                                       name="forecast_opening"
                                       value="{{ old('forecast_opening') }}"
                                       class="form-control">

                            </div>


                            {{-- Expected Opening --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Expected Opening Date
                                </label>

                                <input type="date"
                                       name="expected_opening_date"
                                       value="{{ old('expected_opening_date') }}"
                                       class="form-control">

                            </div>


                            {{-- Notes --}}
                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Notes
                                </label>

                                <textarea name="notes"
                                          rows="5"
                                          class="form-control"
                                          placeholder="Additional information about this opportunity...">{{ old('notes') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                SIDE PANEL
            ================================================== --}}
            <div class="col-xl-4">

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h6 class="mb-0 fw-semibold">
                            <i class="ri-information-line me-2"></i>
                            Pipeline Guidance
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <div class="fw-semibold mb-1">
                                Lead
                            </div>

                            <small class="text-muted">
                                Initial leasing inquiry or potential tenant.
                            </small>

                        </div>

                        <div class="mb-3">

                            <div class="fw-semibold mb-1">
                                Prospect
                            </div>

                            <small class="text-muted">
                                Qualified prospect with genuine leasing interest.
                            </small>

                        </div>

                        <div class="mb-3">

                            <div class="fw-semibold mb-1">
                                Negotiation
                            </div>

                            <small class="text-muted">
                                Commercial terms and rental conditions are being negotiated.
                            </small>

                        </div>

                        <div class="mb-3">

                            <div class="fw-semibold mb-1">
                                Agreement
                            </div>

                            <small class="text-muted">
                                Lease documentation and final approvals are in progress.
                            </small>

                        </div>

                        <div>

                            <div class="fw-semibold mb-1">
                                Opening
                            </div>

                            <small class="text-muted">
                                Tenant is preparing the unit for business opening.
                            </small>

                        </div>

                    </div>

                </div>


                {{-- Buttons --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        @can('pipeline.create')

                            <button type="submit"
                                    class="btn btn-primary w-100 mb-2">

                                <i class="ri-save-line me-1"></i>
                                Save Opportunity

                            </button>

                        @endcan

                        <a href="{{ route('admin.leasing.pipeline.index') }}"
                           class="btn btn-light border w-100">

                            Cancel

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection