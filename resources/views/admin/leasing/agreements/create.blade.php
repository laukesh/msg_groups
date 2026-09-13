@extends('layouts.app')

@section('title', 'Create Lease Agreement')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Create Lease Agreement
            </h4>

            <div class="text-muted">
                Create agreement from an approved lease proposal
            </div>
        </div>

        <a href="{{ route('admin.leasing.agreements.index') }}"
           class="btn btn-secondary">

            <i class="fas fa-arrow-left"></i>
            Back

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please fix the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- FORM --}}
    {{-- ========================================================= --}}

    <form method="POST"
          action="{{ route('admin.leasing.agreements.store') }}">

        @csrf


        {{-- ===================================================== --}}
        {{-- 1. PROPOSAL & TENANT --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-file-contract me-1"></i>

                    Agreement Information

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Proposal --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Approved Proposal

                            <span class="text-danger">*</span>

                        </label>

                        <select name="proposal_id"
                                id="proposal_id"
                                class="form-select @error('proposal_id') is-invalid @enderror"
                                required>

                            <option value="">
                                -- Select Approved Proposal --
                            </option>

                            @foreach($proposals as $proposal)

                                <option value="{{ $proposal->id }}"
                                    data-tenant="{{ $proposal->tenant_id }}"
                                    data-lease-start="{{ $proposal->lease_start_date }}"
                                    data-lease-end="{{ $proposal->lease_end_date }}"
                                    data-monthly-rent="{{ $proposal->monthly_rent ?? 0 }}"
                                    data-cam-amount="{{ $proposal->cam_amount ?? 0 }}"
                                    data-security-deposit="{{ $proposal->security_deposit ?? 0 }}"
                                    data-rent-free-days="{{ $proposal->rent_free_days ?? 0 }}"
                                    data-fitout-days="{{ $proposal->fitout_period_days ?? 0 }}"
                                    {{ old('proposal_id') == $proposal->id ? 'selected' : '' }}>

                                    {{ $proposal->proposal_no }}

                                    @if($proposal->tenant)
                                        - {{ $proposal->tenant->company_name }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('proposal_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                        <small class="text-muted">

                            Only approved proposals are shown.

                        </small>

                    </div>


                    {{-- Tenant --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Tenant

                            <span class="text-danger">*</span>

                        </label>

                        <select name="tenant_id"
                                id="tenant_id"
                                class="form-select @error('tenant_id') is-invalid @enderror"
                                required>

                            <option value="">
                                -- Select Tenant --
                            </option>

                            @foreach($tenants as $tenant)

                                <option value="{{ $tenant->id }}"
                                    {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>

                                    {{ $tenant->company_name }}

                                    @if(!empty($tenant->brand_name))

                                        -
                                        {{ $tenant->brand_name }}

                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('tenant_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Agreement Date --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            Agreement Date

                            <span class="text-danger">*</span>

                        </label>

                        <input type="date"
                               name="agreement_date"
                               class="form-control @error('agreement_date') is-invalid @enderror"
                               value="{{ old(
                                   'agreement_date',
                                   date('Y-m-d')
                               ) }}"
                               required>

                        @error('agreement_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            Agreement Status

                            <span class="text-danger">*</span>

                        </label>

                        <select name="agreement_status"
                                class="form-select @error('agreement_status') is-invalid @enderror"
                                required>

                            <option value="Draft"
                                {{ old('agreement_status', 'Draft') == 'Draft'
                                    ? 'selected'
                                    : '' }}>

                                Draft

                            </option>

                            <option value="Active"
                                {{ old('agreement_status') == 'Active'
                                    ? 'selected'
                                    : '' }}>

                                Active

                            </option>

                            <option value="Cancelled"
                                {{ old('agreement_status') == 'Cancelled'
                                    ? 'selected'
                                    : '' }}>

                                Cancelled

                            </option>

                        </select>

                        @error('agreement_status')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- 2. LEASE PERIOD --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-calendar-alt me-1"></i>

                    Lease Period

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Start --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            Lease Start Date

                            <span class="text-danger">*</span>

                        </label>

                        <input type="date"
                               name="lease_start_date"
                               id="lease_start_date"
                               class="form-control @error('lease_start_date') is-invalid @enderror"
                               value="{{ old('lease_start_date') }}"
                               required>

                        @error('lease_start_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- End --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            Lease End Date

                            <span class="text-danger">*</span>

                        </label>

                        <input type="date"
                               name="lease_end_date"
                               id="lease_end_date"
                               class="form-control @error('lease_end_date') is-invalid @enderror"
                               value="{{ old('lease_end_date') }}"
                               required>

                        @error('lease_end_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Period --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Lease Period (Months)
                        </label>

                        <input type="number"
                               name="lease_period_months"
                               id="lease_period_months"
                               class="form-control"
                               value="{{ old('lease_period_months') }}"
                               readonly>

                    </div>


                    {{-- Rent Start --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Rent Start Date
                        </label>

                        <input type="date"
                               name="rent_start_date"
                               class="form-control @error('rent_start_date') is-invalid @enderror"
                               value="{{ old('rent_start_date') }}">

                        @error('rent_start_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Handover --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Handover Date
                        </label>

                        <input type="date"
                               name="handover_date"
                               class="form-control @error('handover_date') is-invalid @enderror"
                               value="{{ old('handover_date') }}">

                        @error('handover_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Fitout Start --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Fit-out Start Date
                        </label>

                        <input type="date"
                               name="fitout_start_date"
                               id="fitout_start_date"
                               class="form-control"
                               value="{{ old('fitout_start_date') }}">

                    </div>


                    {{-- Fitout End --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Fit-out End Date
                        </label>

                        <input type="date"
                               name="fitout_end_date"
                               id="fitout_end_date"
                               class="form-control"
                               value="{{ old('fitout_end_date') }}">

                    </div>


                    {{-- Rent Free --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Rent Free Period (Days)
                        </label>

                        <input type="number"
                               name="rent_free_days"
                               min="0"
                               class="form-control @error('rent_free_days') is-invalid @enderror"
                               value="{{ old('rent_free_days', 0) }}">

                        @error('rent_free_days')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- 3. FINANCIAL DETAILS --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-rupee-sign me-1"></i>

                    Financial Details

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Monthly Rent --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Monthly Rent
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input type="number"
                                   name="monthly_rent"
                                   id="monthly_rent"
                                   step="0.01"
                                   min="0"
                                   class="form-control"
                                   value="{{ old('monthly_rent', 0) }}">

                        </div>

                    </div>


                    {{-- CAM --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            CAM Amount
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input type="number"
                                   name="cam_amount"
                                   id="cam_amount"
                                   step="0.01"
                                   min="0"
                                   class="form-control"
                                   value="{{ old('cam_amount', 0) }}">

                        </div>

                    </div>


                    {{-- Security --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Security Deposit
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input type="number"
                                   name="security_deposit"
                                   id="security_deposit"
                                   step="0.01"
                                   min="0"
                                   class="form-control"
                                   value="{{ old('security_deposit', 0) }}">

                        </div>

                    </div>


                    {{-- Utility --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Utility Deposit
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input type="number"
                                   name="utility_deposit"
                                   id="utility_deposit"
                                   step="0.01"
                                   min="0"
                                   class="form-control"
                                   value="{{ old('utility_deposit', 0) }}">

                        </div>

                    </div>


                    {{-- Billing Frequency --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Billing Frequency
                        </label>

                        <select name="billing_frequency"
                                class="form-select">

                            <option value="Monthly"
                                {{ old('billing_frequency', 'Monthly') == 'Monthly'
                                    ? 'selected'
                                    : '' }}>

                                Monthly

                            </option>

                            <option value="Quarterly"
                                {{ old('billing_frequency') == 'Quarterly'
                                    ? 'selected'
                                    : '' }}>

                                Quarterly

                            </option>

                            <option value="Half-Yearly"
                                {{ old('billing_frequency') == 'Half-Yearly'
                                    ? 'selected'
                                    : '' }}>

                                Half-Yearly

                            </option>

                            <option value="Yearly"
                                {{ old('billing_frequency') == 'Yearly'
                                    ? 'selected'
                                    : '' }}>

                                Yearly

                            </option>

                        </select>

                    </div>


                    {{-- Payment Due --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Payment Due Day
                        </label>

                        <select name="payment_due_day"
                                class="form-select">

                            @for($day = 1; $day <= 31; $day++)

                                <option value="{{ $day }}"
                                    {{ old(
                                        'payment_due_day',
                                        5
                                    ) == $day
                                        ? 'selected'
                                        : '' }}>

                                    {{ $day }}

                                </option>

                            @endfor

                        </select>

                    </div>

                </div>


                {{-- Monthly Summary --}}
                <div class="row mt-4">

                    <div class="col-md-5 ms-auto">

                        <div class="border rounded p-3 bg-light">

                            <div class="d-flex justify-content-between">

                                <span>
                                    Monthly Rent
                                </span>

                                <strong>
                                    $<span id="display_rent">0.00</span>
                                </strong>

                            </div>


                            <div class="d-flex justify-content-between mt-2">

                                <span>
                                    CAM
                                </span>

                                <strong>
                                    $<span id="display_cam">0.00</span>
                                </strong>

                            </div>


                            <hr>


                            <div class="d-flex justify-content-between">

                                <strong>
                                    Monthly Total
                                </strong>

                                <strong class="text-primary fs-5">

                                    $<span id="monthly_total">0.00</span>

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- 4. REMARKS --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-comment-alt me-1"></i>

                    Remarks

                </h5>

            </div>

            <div class="card-body">

                <textarea name="remarks"
                          rows="4"
                          class="form-control"
                          placeholder="Enter agreement remarks...">{{ old('remarks') }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- 5. SUBMIT --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div class="text-muted">

                        <i class="fas fa-info-circle me-1"></i>

                        The proposal will be marked as
                        <strong>Converted</strong>
                        after the agreement is created.

                    </div>


                    <div class="d-flex gap-2">

                        <a href="{{ route(
                            'admin.leasing.agreements.index'
                        ) }}"
                           class="btn btn-secondary">

                            Cancel

                        </a>


                        <button type="submit"
                                class="btn btn-primary">

                            <i class="fas fa-save me-1"></i>

                            Create Lease Agreement

                        </button>

                    </div>

                </div>

            </div>

        </div>


    </form>

</div>


{{-- ============================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Proposal → Tenant
    |--------------------------------------------------------------------------
    |
    | When an approved proposal is selected, automatically select
    | the tenant belonging to that proposal.
    |
    */

    const proposalSelect = document.getElementById('proposal_id');

    const tenantSelect = document.getElementById('tenant_id');


    function populateFromProposal() {

        const selectedOption = proposalSelect.options[proposalSelect.selectedIndex];

        if (!selectedOption || !selectedOption.value) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Tenant
        |--------------------------------------------------------------------------
        */

        tenantSelect.value = selectedOption.dataset.tenant || '';


        /*
        |--------------------------------------------------------------------------
        | Lease Dates
        |--------------------------------------------------------------------------
        */

        startDate.value = selectedOption.dataset.leaseStart || '';

        endDate.value = selectedOption.dataset.leaseEnd || '';


        /*
        |--------------------------------------------------------------------------
        | Financial Details
        |--------------------------------------------------------------------------
        */

        rent.value = selectedOption.dataset.monthlyRent || 0;

        cam.value = selectedOption.dataset.camAmount || 0;

        document.getElementById('security_deposit').value = selectedOption.dataset.securityDeposit || 0;


        /*
        |--------------------------------------------------------------------------
        | Rent Free Period
        |--------------------------------------------------------------------------
        */

        document.querySelector( '[name="rent_free_days"]' ).value = selectedOption.dataset.rentFreeDays || 0;


        /*
        |--------------------------------------------------------------------------
        | Recalculate
        |--------------------------------------------------------------------------
        */

        calculateLeasePeriod();

        calculateTotal();
    }


    /*
    |--------------------------------------------------------------------------
    | Lease Period
    |--------------------------------------------------------------------------
    */

    const startDate = document.getElementById('lease_start_date');

    const endDate = document.getElementById('lease_end_date');

    const period = document.getElementById('lease_period_months');


    function calculateLeasePeriod()
    {

        if (
            !startDate.value ||
            !endDate.value
        ) {

            period.value = '';

            return;

        }


        const start = new Date(startDate.value);

        const end = new Date(endDate.value);


        if (end < start) {

            period.value = '';

            return;

        }


        let months = (end.getFullYear() -  start.getFullYear()) * 12;

        months += end.getMonth() - start.getMonth();


        if (end.getDate() >=start.getDate() ) {

            months++;

        }


        period.value = months;

    }


    startDate.addEventListener(
        'change',
        calculateLeasePeriod
    );

    endDate.addEventListener(
        'change',
        calculateLeasePeriod
    );


    /*
    |--------------------------------------------------------------------------
    | Financial Total
    |--------------------------------------------------------------------------
    */

    const rent = document.getElementById('monthly_rent');

    const cam = document.getElementById('cam_amount');

    const displayRent = document.getElementById('display_rent');

    const displayCam = document.getElementById('display_cam');

    const monthlyTotal = document.getElementById('monthly_total');


    function formatCurrency(value)
    {

        return value.toLocaleString(
            'en-IN',
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );

    }


    function calculateTotal()
    {

        const rentValue = parseFloat(rent.value) || 0;

        const camValue = parseFloat(cam.value) || 0;

        const total = rentValue + camValue;


        displayRent.textContent = formatCurrency(rentValue);

        displayCam.textContent = formatCurrency(camValue);

        monthlyTotal.textContent = formatCurrency(total);

    }


    rent.addEventListener(
        'input',
        calculateTotal
    );

    cam.addEventListener(
        'input',
        calculateTotal
    );


    calculateTotal();


    /*
    |--------------------------------------------------------------------------
    | Fitout Date Validation
    |--------------------------------------------------------------------------
    */

    const fitoutStart =document.getElementById('fitout_start_date');

    const fitoutEnd = document.getElementById( 'fitout_end_date');


    fitoutStart.addEventListener(
        'change',
        function () {
            if (fitoutStart.value) {
                fitoutEnd.min = fitoutStart.value;
            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Auto tenant when proposal changes
    |--------------------------------------------------------------------------
    */

    proposalSelect.addEventListener(
        'change',
        populateFromProposal
    );
    if (proposalSelect.value) {
        populateFromProposal();
    }

});

</script>

@endsection