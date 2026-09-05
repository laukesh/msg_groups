@extends('layouts.app')

@section('title', 'Edit Lease Agreement')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Edit Lease Agreement
            </h4>

            <div class="text-muted">
                {{ $agreement->agreement_no }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.leasing.agreements.show',
                $agreement->id
            ) }}"
               class="btn btn-info">

                <i class="fas fa-eye"></i>
                View

            </a>

            <a href="{{ route(
                'admin.leasing.agreements.index'
            ) }}"
               class="btn btn-secondary">

                <i class="fas fa-arrow-left"></i>
                Back

            </a>

        </div>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please fix the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route(
              'admin.leasing.agreements.update',
              $agreement->id
          ) }}">

        @csrf
        @method('PUT')


        {{-- ===================================================== --}}
        {{-- AGREEMENT INFORMATION --}}
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


                    {{-- Agreement Number --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Agreement No.
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $agreement->agreement_no }}"
                               readonly>

                    </div>


                    {{-- Proposal --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Source Proposal
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $agreement->proposal?->proposal_no ?? '-' }}"
                               readonly>

                    </div>


                    {{-- Tenant --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Tenant
                            <span class="text-danger">*</span>
                        </label>

                        <select name="tenant_id"
                                class="form-select @error('tenant_id') is-invalid @enderror"
                                required>

                            <option value="">
                                -- Select Tenant --
                            </option>

                            @foreach($tenants as $tenant)

                                <option value="{{ $tenant->id }}"
                                    {{ old(
                                        'tenant_id',
                                        $agreement->tenant_id
                                    ) == $tenant->id
                                        ? 'selected'
                                        : '' }}>

                                    {{ $tenant->company_name }}

                                    @if(!empty($tenant->brand_name))
                                        - {{ $tenant->brand_name }}
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
                                   $agreement->agreement_date?->format('Y-m-d')
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

                            @foreach([
                                'Draft',
                                'Active',
                                'Expired',
                                'Terminated',
                                'Renewed',
                                'Cancelled'
                            ] as $status)

                                <option value="{{ $status }}"
                                    {{ old(
                                        'agreement_status',
                                        $agreement->agreement_status
                                    ) == $status
                                        ? 'selected'
                                        : '' }}>

                                    {{ $status }}

                                </option>

                            @endforeach

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
        {{-- LEASE PERIOD --}}
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


                    {{-- Start Date --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Lease Start Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="lease_start_date"
                               id="lease_start_date"
                               class="form-control @error('lease_start_date') is-invalid @enderror"
                               value="{{ old(
                                   'lease_start_date',
                                   $agreement->lease_start_date?->format('Y-m-d')
                               ) }}"
                               required>

                        @error('lease_start_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- End Date --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Lease End Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="lease_end_date"
                               id="lease_end_date"
                               class="form-control @error('lease_end_date') is-invalid @enderror"
                               value="{{ old(
                                   'lease_end_date',
                                   $agreement->lease_end_date?->format('Y-m-d')
                               ) }}"
                               required>

                        @error('lease_end_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Lease Period --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Lease Period (Months)
                        </label>

                        <input type="number"
                               id="lease_period_months"
                               class="form-control"
                               value="{{ old(
                                   'lease_period_months',
                                   $agreement->lease_period_months
                               ) }}"
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
                               value="{{ old(
                                   'rent_start_date',
                                   $agreement->rent_start_date?->format('Y-m-d')
                               ) }}">

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
                               value="{{ old(
                                   'handover_date',
                                   $agreement->handover_date?->format('Y-m-d')
                               ) }}">

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
                               value="{{ old(
                                   'fitout_start_date',
                                   $agreement->fitout_start_date?->format('Y-m-d')
                               ) }}">

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
                               value="{{ old(
                                   'fitout_end_date',
                                   $agreement->fitout_end_date?->format('Y-m-d')
                               ) }}">

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
                               value="{{ old(
                                   'rent_free_days',
                                   $agreement->rent_free_days ?? 0
                               ) }}">

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
        {{-- FINANCIAL DETAILS --}}
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
                                   class="form-control @error('monthly_rent') is-invalid @enderror"
                                   value="{{ old(
                                       'monthly_rent',
                                       $agreement->monthly_rent ?? 0
                                   ) }}">

                        </div>

                        @error('monthly_rent')
                            <div class="text-danger small">
                                {{ $message }}
                            </div>
                        @enderror

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
                                   class="form-control @error('cam_amount') is-invalid @enderror"
                                   value="{{ old(
                                       'cam_amount',
                                       $agreement->cam_amount ?? 0
                                   ) }}">

                        </div>

                        @error('cam_amount')
                            <div class="text-danger small">
                                {{ $message }}
                            </div>
                        @enderror

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
                                   step="0.01"
                                   min="0"
                                   class="form-control"
                                   value="{{ old(
                                       'security_deposit',
                                       $agreement->security_deposit ?? 0
                                   ) }}">

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
                                   step="0.01"
                                   min="0"
                                   class="form-control"
                                   value="{{ old(
                                       'utility_deposit',
                                       $agreement->utility_deposit ?? 0
                                   ) }}">

                        </div>

                    </div>


                    {{-- Billing --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Billing Frequency
                        </label>

                        <select name="billing_frequency"
                                class="form-select">

                            @foreach([
                                'Monthly',
                                'Quarterly',
                                'Half-Yearly',
                                'Yearly'
                            ] as $frequency)

                                <option value="{{ $frequency }}"
                                    {{ old(
                                        'billing_frequency',
                                        $agreement->billing_frequency ?? 'Monthly'
                                    ) == $frequency
                                        ? 'selected'
                                        : '' }}>

                                    {{ $frequency }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Payment Due Day --}}
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
                                        $agreement->payment_due_day ?? 5
                                    ) == $day
                                        ? 'selected'
                                        : '' }}>

                                    {{ $day }}

                                </option>

                            @endfor

                        </select>

                    </div>

                </div>


                {{-- Financial Summary --}}
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
        {{-- REMARKS --}}
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
                          placeholder="Enter agreement remarks...">{{ old(
                              'remarks',
                              $agreement->remarks
                          ) }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- SUBMIT --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div class="text-muted">

                        <i class="fas fa-info-circle me-1"></i>

                        Agreement:
                        <strong>
                            {{ $agreement->agreement_no }}
                        </strong>

                    </div>


                    <div class="d-flex gap-2">

                        <a href="{{ route(
                            'admin.leasing.agreements.show',
                            $agreement->id
                        ) }}"
                           class="btn btn-secondary">

                            Cancel

                        </a>


                        <button type="submit"
                                class="btn btn-primary">

                            <i class="fas fa-save me-1"></i>

                            Update Lease Agreement

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
    | Lease Period
    |--------------------------------------------------------------------------
    */

    const startDate =
        document.getElementById('lease_start_date');

    const endDate =
        document.getElementById('lease_end_date');

    const period =
        document.getElementById('lease_period_months');


    function calculateLeasePeriod()
    {
        if (
            !startDate.value ||
            !endDate.value
        ) {
            period.value = '';
            return;
        }


        const start =
            new Date(startDate.value);

        const end =
            new Date(endDate.value);


        if (end < start) {
            period.value = '';
            return;
        }


        let months =
            (end.getFullYear() -
             start.getFullYear()) * 12;

        months +=
            end.getMonth() -
            start.getMonth();


        if (
            end.getDate() >=
            start.getDate()
        ) {
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


    calculateLeasePeriod();


    /*
    |--------------------------------------------------------------------------
    | Financial Summary
    |--------------------------------------------------------------------------
    */

    const rent =
        document.getElementById('monthly_rent');

    const cam =
        document.getElementById('cam_amount');

    const displayRent =
        document.getElementById('display_rent');

    const displayCam =
        document.getElementById('display_cam');

    const monthlyTotal =
        document.getElementById('monthly_total');


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
        const rentValue =
            parseFloat(rent.value) || 0;

        const camValue =
            parseFloat(cam.value) || 0;

        const total =
            rentValue + camValue;


        displayRent.textContent =
            formatCurrency(rentValue);

        displayCam.textContent =
            formatCurrency(camValue);

        monthlyTotal.textContent =
            formatCurrency(total);
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
    | Fitout Date
    |--------------------------------------------------------------------------
    */

    const fitoutStart =
        document.getElementById(
            'fitout_start_date'
        );

    const fitoutEnd =
        document.getElementById(
            'fitout_end_date'
        );


    function updateFitoutMinDate()
    {
        if (fitoutStart.value) {

            fitoutEnd.min =
                fitoutStart.value;

        }
    }


    fitoutStart.addEventListener(
        'change',
        updateFitoutMinDate
    );

    updateFitoutMinDate();

});

</script>

@endsection