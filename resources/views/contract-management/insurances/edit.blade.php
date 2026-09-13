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
                Edit Insurance Policy
            </h4>

            <div class="text-muted">

                {{ $insurance->insurance_number }}

                <span class="mx-1">|</span>

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
              'admin.projects.contract-management.contracts.insurances.update',
              [$project, $contract, $insurance]
          ) }}">

        @csrf

        @method('PUT')


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


                    <div class="col-md-4">

                        <label class="form-label">
                            Insurance Number
                        </label>

                        <input type="text"
                               value="{{ $insurance->insurance_number }}"
                               class="form-control"
                               readonly>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Insurance Type
                            <span class="text-danger">*</span>
                        </label>

                        <select name="insurance_type"
                                class="form-select @error('insurance_type') is-invalid @enderror"
                                required>

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
                                        old(
                                            'insurance_type',
                                            $insurance->insurance_type
                                        ) === $type
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


                    <div class="col-md-4">

                        <label class="form-label">
                            Policy Number
                        </label>

                        <input type="text"
                               name="policy_number"
                               value="{{ old(
                                   'policy_number',
                                   $insurance->policy_number
                               ) }}"
                               class="form-control @error('policy_number') is-invalid @enderror">

                        @error('policy_number')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Insurer Name
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="insurer_name"
                               value="{{ old(
                                   'insurer_name',
                                   $insurance->insurer_name
                               ) }}"
                               class="form-control @error('insurer_name') is-invalid @enderror"
                               required>

                        @error('insurer_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Insured Party
                        </label>

                        <input type="text"
                               name="insured_party"
                               value="{{ old(
                                   'insured_party',
                                   $insurance->insured_party
                               ) }}"
                               class="form-control @error('insured_party') is-invalid @enderror">

                        @error('insured_party')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Beneficiary
                        </label>

                        <input type="text"
                               name="beneficiary"
                               value="{{ old(
                                   'beneficiary',
                                   $insurance->beneficiary
                               ) }}"
                               class="form-control @error('beneficiary') is-invalid @enderror">

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
                                   $insurance->coverage_amount
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
                                   $insurance->currency
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
                                   $insurance->premium_amount
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
                               value="{{ old(
                                   'policy_start_date',
                                   $insurance->policy_start_date?->format('Y-m-d')
                               ) }}"
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
                               value="{{ old(
                                   'policy_expiry_date',
                                   $insurance->policy_expiry_date?->format('Y-m-d')
                               ) }}"
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
                               value="{{ old(
                                   'renewal_date',
                                   $insurance->renewal_date?->format('Y-m-d')
                               ) }}"
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
                    Compliance & Verification
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
                                            $insurance->status
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
                                       $insurance->days_before_expiry_alert
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
                                   $insurance->submission_date?->format('Y-m-d')
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
                               value="{{ old(
                                   'verification_date',
                                   $insurance->verification_date?->format('Y-m-d')
                               ) }}"
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
                          class="form-control @error('remarks') is-invalid @enderror">{{ old(
                              'remarks',
                              $insurance->remarks
                          ) }}</textarea>

                @error('remarks')

                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>

                @enderror

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Audit --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Record Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <div class="text-muted small">
                            Insurance Number
                        </div>

                        <div class="fw-semibold">
                            {{ $insurance->insurance_number }}
                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Created
                        </div>

                        <div>
                            {{ $insurance->created_at
                                ? $insurance->created_at
                                    ->format('d M Y H:i')
                                : '—'
                            }}
                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Last Updated
                        </div>

                        <div>
                            {{ $insurance->updated_at
                                ? $insurance->updated_at
                                    ->format('d M Y H:i')
                                : '—'
                            }}
                        </div>

                    </div>

                </div>

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

                Update Insurance

            </button>

        </div>

    </form>

</div>

@endsection