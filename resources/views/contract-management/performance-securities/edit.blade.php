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
                Edit Performance Security
            </h4>

            <div class="text-muted">

                {{ $security->security_number }}

                <span class="mx-1">|</span>

                {{ $contract->contract_code }}

                <span class="mx-1">|</span>

                {{ $contract->contract_title }}

            </div>

        </div>


        <a href="{{ route(
            'admin.projects.contract-management.contracts.performance-securities.index',
            [$project, $contract]
        ) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>

            Back to Performance Security

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


    {{-- ========================================================= --}}
    {{-- Current Requirement --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Contract Requirement
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Contract Value
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{ $contract->currency ?? 'USD' }}

                        {{ number_format(
                            $contract->contract_value,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Security Required
                    </div>

                    <div class="fs-5 fw-semibold">

                        @if($contract->performance_security_required)

                            <span class="text-success">
                                Yes
                            </span>

                        @else

                            <span class="text-secondary">
                                No
                            </span>

                        @endif

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Required Amount
                    </div>

                    <div class="fs-5 fw-semibold text-primary">

                        {{ $contract->currency ?? 'USD' }}

                        {{ number_format(
                            $contract->performance_security_amount,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Security Number
                    </div>

                    <div class="fs-5 fw-semibold">
                        {{ $security->security_number }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    <form method="POST"
          action="{{ route(
              'admin.projects.contract-management.contracts.performance-securities.update',
              [$project, $contract, $security]
          ) }}">

        @csrf

        @method('PUT')


        {{-- ===================================================== --}}
        {{-- Security Information --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Security Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Security Number --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Security Number
                        </label>

                        <input type="text"
                               value="{{ $security->security_number }}"
                               class="form-control"
                               readonly>

                    </div>


                    {{-- Security Type --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Security Type
                            <span class="text-danger">*</span>
                        </label>

                        <select name="security_type"
                                class="form-select @error('security_type') is-invalid @enderror"
                                required>

                            @foreach([
                                'Bank Guarantee',
                                'Performance Bond',
                                'Surety Bond',
                                'Demand Guarantee',
                                'Insurance Bond',
                                'Cash Security',
                                'Other',
                            ] as $type)

                                <option value="{{ $type }}"
                                    @selected(
                                        old(
                                            'security_type',
                                            $security->security_type
                                        ) === $type
                                    )>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                        @error('security_type')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Instrument Number --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Instrument Number
                        </label>

                        <input type="text"
                               name="instrument_number"
                               value="{{ old(
                                   'instrument_number',
                                   $security->instrument_number
                               ) }}"
                               class="form-control @error('instrument_number') is-invalid @enderror"
                               placeholder="BG / Bond / Guarantee No.">

                        @error('instrument_number')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Issuing Bank --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Issuing Bank / Institution
                        </label>

                        <input type="text"
                               name="issuing_bank"
                               value="{{ old(
                                   'issuing_bank',
                                   $security->issuing_bank
                               ) }}"
                               class="form-control @error('issuing_bank') is-invalid @enderror">

                        @error('issuing_bank')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Branch --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Issuing Branch
                        </label>

                        <input type="text"
                               name="issuing_branch"
                               value="{{ old(
                                   'issuing_branch',
                                   $security->issuing_branch
                               ) }}"
                               class="form-control @error('issuing_branch') is-invalid @enderror">

                        @error('issuing_branch')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Beneficiary --}}

                    <div class="col-md-12">

                        <label class="form-label">
                            Beneficiary
                        </label>

                        <input type="text"
                               name="beneficiary"
                               value="{{ old(
                                   'beneficiary',
                                   $security->beneficiary
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
        {{-- Financial Details --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Financial Details
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Security Amount --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Security Amount
                            <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               name="security_amount"
                               id="security_amount"
                               value="{{ old(
                                   'security_amount',
                                   $security->security_amount
                               ) }}"
                               min="0"
                               step="0.01"
                               class="form-control @error('security_amount') is-invalid @enderror"
                               required>

                        @error('security_amount')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Currency --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Currency
                        </label>

                        <input type="text"
                               name="currency"
                               value="{{ old(
                                   'currency',
                                   $security->currency
                               ) }}"
                               maxlength="10"
                               class="form-control @error('currency') is-invalid @enderror">

                        @error('currency')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Released Amount --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Released Amount
                        </label>

                        <input type="number"
                               name="released_amount"
                               id="released_amount"
                               value="{{ old(
                                   'released_amount',
                                   $security->released_amount
                               ) }}"
                               min="0"
                               step="0.01"
                               class="form-control @error('released_amount') is-invalid @enderror">

                        <div class="form-text">
                            Released amount cannot exceed security amount.
                        </div>

                        @error('released_amount')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Remaining Amount --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Remaining Security
                        </label>

                        <input type="text"
                               id="remaining_amount"
                               class="form-control"
                               readonly>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Validity & Dates --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Validity & Dates
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Issue Date --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Issue Date
                        </label>

                        <input type="date"
                               name="issue_date"
                               value="{{ old(
                                   'issue_date',
                                   $security->issue_date?->format('Y-m-d')
                               ) }}"
                               class="form-control @error('issue_date') is-invalid @enderror">

                        @error('issue_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Expiry Date --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Expiry Date
                        </label>

                        <input type="date"
                               name="expiry_date"
                               value="{{ old(
                                   'expiry_date',
                                   $security->expiry_date?->format('Y-m-d')
                               ) }}"
                               class="form-control @error('expiry_date') is-invalid @enderror">

                        @error('expiry_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Claim Expiry Date --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Claim Expiry Date
                        </label>

                        <input type="date"
                               name="claim_expiry_date"
                               value="{{ old(
                                   'claim_expiry_date',
                                   $security->claim_expiry_date?->format('Y-m-d')
                               ) }}"
                               class="form-control @error('claim_expiry_date') is-invalid @enderror">

                        @error('claim_expiry_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Submission Date --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Submission Date
                        </label>

                        <input type="date"
                               name="submission_date"
                               value="{{ old(
                                   'submission_date',
                                   $security->submission_date?->format('Y-m-d')
                               ) }}"
                               class="form-control @error('submission_date') is-invalid @enderror">

                        @error('submission_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Verification Date --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Verification Date
                        </label>

                        <input type="date"
                               name="verification_date"
                               value="{{ old(
                                   'verification_date',
                                   $security->verification_date?->format('Y-m-d')
                               ) }}"
                               class="form-control @error('verification_date') is-invalid @enderror">

                        @error('verification_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Release Date --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Release Date
                        </label>

                        <input type="date"
                               name="release_date"
                               value="{{ old(
                                   'release_date',
                                   $security->release_date?->format('Y-m-d')
                               ) }}"
                               class="form-control @error('release_date') is-invalid @enderror">

                        @error('release_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Extension --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Extension
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <div class="form-check mt-2">

                            <input type="checkbox"
                                   name="extension_required"
                                   value="1"
                                   id="extension_required"
                                   class="form-check-input"
                                   @checked(
                                       old(
                                           'extension_required',
                                           $security->extension_required
                                       )
                                   )>

                            <label for="extension_required"
                                   class="form-check-label">

                                Extension Required

                            </label>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Extended Expiry Date
                        </label>

                        <input type="date"
                               name="extended_expiry_date"
                               value="{{ old(
                                   'extended_expiry_date',
                                   $security->extended_expiry_date?->format('Y-m-d')
                               ) }}"
                               class="form-control @error('extended_expiry_date') is-invalid @enderror">

                        @error('extended_expiry_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Status & Verification --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Status & Verification
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Status --}}

                    <div class="col-md-6">

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
                                'Extended',
                                'Released',
                                'Cancelled',
                                'Closed',
                            ] as $status)

                                <option value="{{ $status }}"
                                    @selected(
                                        old(
                                            'status',
                                            $security->status
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


                    {{-- Verification Status --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Verification Status
                            <span class="text-danger">*</span>
                        </label>

                        <select name="verification_status"
                                class="form-select @error('verification_status') is-invalid @enderror"
                                required>

                            @foreach([
                                'Pending',
                                'Verified',
                                'Rejected',
                            ] as $verificationStatus)

                                <option value="{{ $verificationStatus }}"
                                    @selected(
                                        old(
                                            'verification_status',
                                            $security->verification_status
                                        ) === $verificationStatus
                                    )>

                                    {{ $verificationStatus }}

                                </option>

                            @endforeach

                        </select>

                        @error('verification_status')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Release Information --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Release Information
                </h5>

            </div>


            <div class="card-body">

                <label class="form-label">
                    Release Remarks
                </label>

                <textarea name="release_remarks"
                          rows="4"
                          class="form-control @error('release_remarks') is-invalid @enderror"
                          placeholder="Enter release details or remarks...">{{ old(
                              'release_remarks',
                              $security->release_remarks
                          ) }}</textarea>

                @error('release_remarks')

                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>

                @enderror

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- General Remarks --}}
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
                          placeholder="Enter remarks...">{{ old(
                              'remarks',
                              $security->remarks
                          ) }}</textarea>

                @error('remarks')

                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>

                @enderror

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Record Information --}}
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
                            Security Number
                        </div>

                        <div class="fw-semibold">
                            {{ $security->security_number }}
                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Created
                        </div>

                        <div>
                            {{ $security->created_at
                                ? $security->created_at
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
                            {{ $security->updated_at
                                ? $security->updated_at
                                    ->format('d M Y H:i')
                                : '—'
                            }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Actions --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a href="{{ route(
                'admin.projects.contract-management.contracts.performance-securities.index',
                [$project, $contract]
            ) }}"
               class="btn btn-outline-secondary">

                Cancel

            </a>


            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-check-lg me-1"></i>

                Update Performance Security

            </button>

        </div>

    </form>

</div>


{{-- ============================================================= --}}
{{-- Remaining Amount Calculation --}}
{{-- ============================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const securityAmount =
            document.getElementById(
                'security_amount'
            );

        const releasedAmount =
            document.getElementById(
                'released_amount'
            );

        const remainingAmount =
            document.getElementById(
                'remaining_amount'
            );


        function calculateRemaining()
        {
            const security =
                parseFloat(
                    securityAmount.value
                ) || 0;


            const released =
                parseFloat(
                    releasedAmount.value
                ) || 0;


            const remaining =
                Math.max(
                    0,
                    security - released
                );


            remainingAmount.value =
                remaining.toFixed(2);
        }


        securityAmount.addEventListener(
            'input',
            calculateRemaining
        );


        releasedAmount.addEventListener(
            'input',
            calculateRemaining
        );


        calculateRemaining();

    }
);

</script>

@endsection