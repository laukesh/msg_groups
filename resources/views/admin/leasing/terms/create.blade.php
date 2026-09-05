@extends('layouts.app')

@section('title', 'Add Lease Terms')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Add Lease Terms
            </h4>

            <div class="text-muted">
                Configure commercial and operational terms for an active lease.
            </div>
        </div>

        <a href="{{ route('admin.leasing.terms.index') }}"
           class="btn btn-secondary">

            <i class="fas fa-arrow-left me-1"></i>
            Back

        </a>

    </div>


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
          action="{{ route('admin.leasing.terms.store') }}">

        @csrf


        {{-- ================================================= --}}
        {{-- AGREEMENT --}}
        {{-- ================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-file-contract me-1"></i>

                    Lease Agreement

                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-6">

                        <label class="form-label">

                            Lease Agreement
                            <span class="text-danger">*</span>

                        </label>


                        <select name="lease_agreement_id"
                                class="form-select @error('lease_agreement_id') is-invalid @enderror"
                                required>

                            <option value="">
                                -- Select Active Agreement --
                            </option>


                            @foreach($agreements as $agreement)

                                <option value="{{ $agreement->id }}"
                                    {{ old(
                                        'lease_agreement_id'
                                    ) == $agreement->id
                                        ? 'selected'
                                        : '' }}>

                                    {{ $agreement->agreement_no }}

                                    @if($agreement->tenant)

                                        -
                                        {{ $agreement->tenant->company_name }}

                                    @endif

                                </option>

                            @endforeach

                        </select>


                        @error('lease_agreement_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror


                        @if($agreements->isEmpty())

                            <div class="form-text text-danger">

                                No active agreements are available
                                without existing lease terms.

                            </div>

                        @else

                            <div class="form-text">

                                Only active agreements without existing
                                lease terms are shown.

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- LOCK-IN / NOTICE --}}
        {{-- ================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-clock me-1"></i>

                    Lock-in & Notice Period

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    <div class="col-md-4">

                        <label class="form-label">
                            Lock-in Period (Months)
                        </label>

                        <input type="number"
                               name="lock_in_period_months"
                               min="0"
                               class="form-control"
                               value="{{ old(
                                   'lock_in_period_months',
                                   0
                               ) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Notice Period (Days)
                        </label>

                        <input type="number"
                               name="notice_period_days"
                               min="0"
                               class="form-control"
                               value="{{ old(
                                   'notice_period_days',
                                   90
                               ) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Grace Period (Days)
                        </label>

                        <input type="number"
                               name="grace_period_days"
                               min="0"
                               class="form-control"
                               value="{{ old(
                                   'grace_period_days',
                                   0
                               ) }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- ESCALATION --}}
        {{-- ================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-chart-line me-1"></i>

                    Rent Escalation

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    <div class="col-md-6">

                        <label class="form-label">
                            Escalation Frequency
                        </label>

                        <select name="escalation_frequency"
                                class="form-select">

                            @foreach([
                                'Yearly',
                                'Every 3 Years',
                                'Custom'
                            ] as $frequency)

                                <option value="{{ $frequency }}"
                                    {{ old(
                                        'escalation_frequency',
                                        'Yearly'
                                    ) == $frequency
                                        ? 'selected'
                                        : '' }}>

                                    {{ $frequency }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Escalation Percentage
                        </label>

                        <div class="input-group">

                            <input type="number"
                                   name="escalation_percentage"
                                   step="0.01"
                                   min="0"
                                   max="100"
                                   class="form-control"
                                   value="{{ old(
                                       'escalation_percentage',
                                       0
                                   ) }}">

                            <span class="input-group-text">
                                %
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- BILLING --}}
        {{-- ================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-money-bill-wave me-1"></i>

                    Billing & Payment

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    <div class="col-md-4">

                        <label class="form-label">
                            Billing Cycle
                        </label>

                        <select name="billing_cycle"
                                class="form-select">

                            @foreach([
                                'Monthly',
                                'Quarterly',
                                'Half-Yearly',
                                'Yearly'
                            ] as $cycle)

                                <option value="{{ $cycle }}"
                                    {{ old(
                                        'billing_cycle',
                                        'Monthly'
                                    ) == $cycle
                                        ? 'selected'
                                        : '' }}>

                                    {{ $cycle }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Payment Due Days
                        </label>

                        <input type="number"
                               name="payment_due_days"
                               min="1"
                               max="31"
                               class="form-control"
                               value="{{ old(
                                   'payment_due_days',
                                   5
                               ) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Grace Period
                        </label>

                        <div class="input-group">

                            <input type="number"
                                   name="grace_period_days"
                                   min="0"
                                   class="form-control"
                                   value="{{ old(
                                       'grace_period_days',
                                       0
                                   ) }}">

                            <span class="input-group-text">
                                Days
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- LATE FEE --}}
        {{-- ================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-exclamation-triangle me-1"></i>

                    Late Fee

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    <div class="col-md-6">

                        <label class="form-label">
                            Late Fee Type
                        </label>

                        <select name="late_fee_type"
                                id="late_fee_type"
                                class="form-select">

                            <option value="Percentage"
                                {{ old(
                                    'late_fee_type',
                                    'Percentage'
                                ) === 'Percentage'
                                    ? 'selected'
                                    : '' }}>

                                Percentage

                            </option>

                            <option value="Fixed"
                                {{ old(
                                    'late_fee_type'
                                ) === 'Fixed'
                                    ? 'selected'
                                    : '' }}>

                                Fixed Amount

                            </option>

                        </select>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Late Fee Value
                        </label>

                        <div class="input-group">

                            <input type="number"
                                   name="late_fee_value"
                                   step="0.01"
                                   min="0"
                                   class="form-control"
                                   value="{{ old(
                                       'late_fee_value',
                                       0
                                   ) }}">

                            <span class="input-group-text"
                                  id="late_fee_suffix">

                                %

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- CAM / UTILITY --}}
        {{-- ================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-cogs me-1"></i>

                    CAM & Utility

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    <div class="col-md-6">

                        <label class="form-label">
                            CAM Calculation Method
                        </label>

                        <select name="cam_calculation_method"
                                class="form-select">

                            @foreach([
                                'Fixed',
                                'Per Sq Ft',
                                'Percentage'
                            ] as $method)

                                <option value="{{ $method }}"
                                    {{ old(
                                        'cam_calculation_method',
                                        'Fixed'
                                    ) == $method
                                        ? 'selected'
                                        : '' }}>

                                    {{ $method }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Utility Billing Method
                        </label>

                        <select name="utility_billing_method"
                                class="form-select">

                            @foreach([
                                'Meter Reading',
                                'Fixed',
                                'Actual'
                            ] as $method)

                                <option value="{{ $method }}"
                                    {{ old(
                                        'utility_billing_method',
                                        'Meter Reading'
                                    ) == $method
                                        ? 'selected'
                                        : '' }}>

                                    {{ $method }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- RESPONSIBILITIES --}}
        {{-- ================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-users-cog me-1"></i>

                    Responsibilities & Restrictions

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    <div class="col-md-4">

                        <label class="form-label">
                            Maintenance Responsibility
                        </label>

                        <select name="maintenance_responsibility"
                                class="form-select">

                            @foreach([
                                'Mall',
                                'Tenant',
                                'Shared'
                            ] as $responsibility)

                                <option value="{{ $responsibility }}"
                                    {{ old(
                                        'maintenance_responsibility',
                                        'Shared'
                                    ) == $responsibility
                                        ? 'selected'
                                        : '' }}>

                                    {{ $responsibility }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Insurance Required
                        </label>

                        <select name="insurance_required"
                                class="form-select">

                            <option value="Yes"
                                {{ old(
                                    'insurance_required',
                                    'Yes'
                                ) === 'Yes'
                                    ? 'selected'
                                    : '' }}>

                                Yes

                            </option>

                            <option value="No"
                                {{ old(
                                    'insurance_required'
                                ) === 'No'
                                    ? 'selected'
                                    : '' }}>

                                No

                            </option>

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Subletting Allowed
                        </label>

                        <select name="subletting_allowed"
                                class="form-select">

                            <option value="Yes"
                                {{ old(
                                    'subletting_allowed',
                                    'No'
                                ) === 'Yes'
                                    ? 'selected'
                                    : '' }}>

                                Yes

                            </option>

                            <option value="No"
                                {{ old(
                                    'subletting_allowed',
                                    'No'
                                ) === 'No'
                                    ? 'selected'
                                    : '' }}>

                                No

                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- SPECIAL TERMS --}}
        {{-- ================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-file-alt me-1"></i>

                    Additional Terms

                </h5>

            </div>


            <div class="card-body">


                <div class="mb-3">

                    <label class="form-label">
                        Termination Clause
                    </label>

                    <textarea name="termination_clause"
                              rows="4"
                              class="form-control"
                              placeholder="Enter termination conditions...">{{ old(
                                  'termination_clause'
                              ) }}</textarea>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Special Terms
                    </label>

                    <textarea name="special_terms"
                              rows="4"
                              class="form-control"
                              placeholder="Enter special commercial or operational terms...">{{ old(
                                  'special_terms'
                              ) }}</textarea>

                </div>


                <div>

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea name="remarks"
                              rows="3"
                              class="form-control"
                              placeholder="Enter remarks...">{{ old(
                                  'remarks'
                              ) }}</textarea>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- ACTION --}}
        {{-- ================================================= --}}

        <div class="card mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route(
                        'admin.leasing.terms.index'
                    ) }}"
                       class="btn btn-secondary">

                        Cancel

                    </a>


                    <button type="submit"
                            class="btn btn-primary">

                        <i class="fas fa-save me-1"></i>

                        Save Lease Terms

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const lateFeeType =
        document.getElementById('late_fee_type');

    const lateFeeSuffix =
        document.getElementById('late_fee_suffix');


    function updateLateFeeSuffix()
    {
        if (lateFeeType.value === 'Fixed') {

            lateFeeSuffix.textContent = '$';

        } else {

            lateFeeSuffix.textContent = '%';

        }
    }


    lateFeeType.addEventListener(
        'change',
        updateLateFeeSuffix
    );

    updateLateFeeSuffix();

});

</script>

@endsection