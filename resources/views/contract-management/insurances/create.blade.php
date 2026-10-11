@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted">
                Contract Management
            </div>

            <h4 class="mb-1">
                Add Insurance Policy
            </h4>

            <div class="text-muted">

                {{ $contract->contract_code }}

                <span class="mx-1">|</span>

                {{ $contract->contract_title }}

            </div>

        </div>


        <a href="{{ route(
            'admin.projects.contract-management.contracts.insurances.index',
            [$project, $contract]
        ) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>

            Back to Insurance

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- Validation --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">
                Please correct the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route(
              'admin.projects.contract-management.contracts.insurances.store',
              [$project, $contract]
          ) }}">

        @csrf


        {{-- ===================================================== --}}
        {{-- Policy Information --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Policy Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Insurance Type --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Insurance Type
                            <span class="text-danger">*</span>
                        </label>

                        <select name="insurance_type"
                                class="form-select @error('insurance_type') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Insurance Type
                            </option>

                            @foreach([
                                'Contractor All Risk (CAR)',
                                'Erection All Risk (EAR)',
                                'Workmen Compensation',
                                'Third Party Liability',
                                'Professional Indemnity',
                                'Marine / Transit Insurance',
                                'Plant & Equipment Insurance',
                                'Fire Insurance',
                                'Property Insurance',
                                'Motor / Vehicle Insurance',
                                'Other',
                            ] as $type)

                                <option value="{{ $type }}"
                                    @selected(
                                        old('insurance_type') === $type
                                    )>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                        @error('insurance_type')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Policy Number --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Policy Number
                        </label>

                        <input type="text"
                               name="policy_number"
                               value="{{ old('policy_number') }}"
                               class="form-control @error('policy_number') is-invalid @enderror"
                               placeholder="Enter policy number">

                        @error('policy_number')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Insurer --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Insurer Name
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="insurer_name"
                               value="{{ old('insurer_name') }}"
                               class="form-control @error('insurer_name') is-invalid @enderror"
                               placeholder="Insurance company"
                               required>

                        @error('insurer_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Insured Party --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Insured Party
                        </label>

                        <input type="text"
                               name="insured_party"
                               value="{{ old(
                                   'insured_party',
                                   $contract->party_name
                               ) }}"
                               class="form-control @error('insured_party') is-invalid @enderror">

                        @error('insured_party')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Beneficiary --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Beneficiary
                        </label>

                        <input type="text"
                               name="beneficiary"
                               value="{{ old('beneficiary') }}"
                               class="form-control @error('beneficiary') is-invalid @enderror"
                               placeholder="Employer / Project Owner">

                        @error('beneficiary')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Financial --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Coverage & Premium
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    <div class="col-md-4">

                        <label class="form-label">
                            Coverage Amount
                            <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               name="coverage_amount"
                               value="{{ old(
                                   'coverage_amount',
                                   0
                               ) }}"
                               min="0"
                               step="0.01"
                               class="form-control @error('coverage_amount') is-invalid @enderror"
                               required>

                        @error('coverage_amount')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Currency
                        </label>

                        <input type="text"
                               name="currency"
                               value="{{ old(
                                   'currency',
                                   $contract->currency ?? 'USD'
                               ) }}"
                               maxlength="10"
                               class="form-control @error('currency') is-invalid @enderror">

                        @error('currency')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Premium Amount
                        </label>

                        <input type="number"
                               name="premium_amount"
                               value="{{ old(
                                   'premium_amount',
                                   0
                               ) }}"
                               min="0"
                               step="0.01"
                               class="form-control @error('premium_amount') is-invalid @enderror">

                        @error('premium_amount')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Validity --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Policy Validity
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    <div class="col-md-4">

                        <label class="form-label">
                            Policy Start Date
                        </label>

                        <input type="date"
                               name="policy_start_date"
                               value="{{ old('policy_start_date') }}"
                               class="form-control @error('policy_start_date') is-invalid @enderror">

                        @error('policy_start_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Policy Expiry Date
                        </label>

                        <input type="date"
                               name="policy_expiry_date"
                               value="{{ old('policy_expiry_date') }}"
                               class="form-control @error('policy_expiry_date') is-invalid @enderror">

                        @error('policy_expiry_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Renewal Date
                        </label>

                        <input type="date"
                               name="renewal_date"
                               value="{{ old('renewal_date') }}"
                               class="form-control @error('renewal_date') is-invalid @enderror">

                        @error('renewal_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Compliance --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Compliance
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    <div class="col-md-4">

                        <label class="form-label">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            @foreach([
                                'Draft',
                                'Submitted',
                                'Under Verification',
                                'Active',
                                'Expired',
                                'Cancelled',
                                'Renewed',
                                'Closed',
                            ] as $status)

                                <option value="{{ $status }}"
                                    @selected(
                                        old(
                                            'status',
                                            'Submitted'
                                        ) === $status
                                    )>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                        @error('status')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Alert Before Expiry
                        </label>

                        <div class="input-group">

                            <input type="number"
                                   name="days_before_expiry_alert"
                                   value="{{ old(
                                       'days_before_expiry_alert',
                                       30
                                   ) }}"
                                   min="0"
                                   max="365"
                                   class="form-control @error('days_before_expiry_alert') is-invalid @enderror">

                            <span class="input-group-text">
                                Days
                            </span>

                        </div>

                        @error('days_before_expiry_alert')

                            <div class="text-danger small">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Submission Date
                        </label>

                        <input type="date"
                               name="submission_date"
                               value="{{ old(
                                   'submission_date',
                                   now()->format('Y-m-d')
                               ) }}"
                               class="form-control @error('submission_date') is-invalid @enderror">

                        @error('submission_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Verification Date
                        </label>

                        <input type="date"
                               name="verification_date"
                               value="{{ old('verification_date') }}"
                               class="form-control @error('verification_date') is-invalid @enderror">

                        @error('verification_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Remarks --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Remarks
                </h5>

            </div>

            <div class="card-body">

                <textarea name="remarks"
                          rows="5"
                          class="form-control @error('remarks') is-invalid @enderror"
                          placeholder="Enter remarks...">{{ old('remarks') }}</textarea>

                @error('remarks')

                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>

                @enderror

            </div>

        </div>


        {{-- Actions --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a href="{{ route(
                'admin.projects.contract-management.contracts.insurances.index',
                [$project, $contract]
            ) }}"
               class="btn btn-outline-secondary">

                Cancel

            </a>


            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-check-lg me-1"></i>

                Add Insurance

            </button>

        </div>

    </form>

</div>

@endsection