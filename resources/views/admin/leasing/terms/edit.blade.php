@extends('layouts.app')

@section('title', 'Edit Lease Terms')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Edit Lease Terms
            </h4>

            <div class="text-muted">
                {{ $term->agreement?->agreement_no ?? '-' }}
            </div>

        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.leasing.terms.show',
                $term->id
            ) }}"
               class="btn btn-info">

                <i class="fas fa-eye me-1"></i>
                View

            </a>

            <a href="{{ route(
                'admin.leasing.terms.index'
            ) }}"
               class="btn btn-secondary">

                <i class="fas fa-arrow-left me-1"></i>
                Back

            </a>

        </div>

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
          action="{{ route(
              'admin.leasing.terms.update',
              $term->id
          ) }}">

        @csrf
        @method('PUT')


        {{-- Agreement --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    <i class="fas fa-file-contract me-1"></i>
                    Lease Agreement
                </h5>

            </div>

            <div class="card-body">

                <label class="form-label">

                    Lease Agreement
                    <span class="text-danger">*</span>

                </label>

                <select name="lease_agreement_id"
                        class="form-select"
                        required>

                    @foreach($agreements as $agreement)

                        <option value="{{ $agreement->id }}"
                            {{ old(
                                'lease_agreement_id',
                                $term->lease_agreement_id
                            ) == $agreement->id
                                ? 'selected'
                                : '' }}>

                            {{ $agreement->agreement_no }}

                            @if($agreement->tenant)
                                - {{ $agreement->tenant->company_name }}
                            @endif

                        </option>

                    @endforeach

                </select>

            </div>

        </div>


        {{-- Lock-in --}}
        <div class="card mb-4">

            <div class="card-header">
                <h5 class="mb-0">
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
                                   $term->lock_in_period_months
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
                                   $term->notice_period_days
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
                                   $term->grace_period_days
                               ) }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- Escalation --}}
        <div class="card mb-4">

            <div class="card-header">
                <h5 class="mb-0">
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
                                        $term->escalation_frequency
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
                                       $term->escalation_percentage
                                   ) }}">

                            <span class="input-group-text">
                                %
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Billing --}}
        <div class="card mb-4">

            <div class="card-header">
                <h5 class="mb-0">
                    Billing & Payment
                </h5>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

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
                                        $term->billing_cycle
                                    ) == $cycle
                                        ? 'selected'
                                        : '' }}>

                                    {{ $cycle }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-6">

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
                                   $term->payment_due_days
                               ) }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- Late Fee --}}
        <div class="card mb-4">

            <div class="card-header">
                <h5 class="mb-0">
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
                                    $term->late_fee_type
                                ) === 'Percentage'
                                    ? 'selected'
                                    : '' }}>

                                Percentage

                            </option>

                            <option value="Fixed"
                                {{ old(
                                    'late_fee_type',
                                    $term->late_fee_type
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
                                       $term->late_fee_value
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


        {{-- CAM / Utility --}}
        <div class="card mb-4">

            <div class="card-header">
                <h5 class="mb-0">
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
                                        $term->cam_calculation_method
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
                                        $term->utility_billing_method
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


        {{-- Responsibilities --}}
        <div class="card mb-4">

            <div class="card-header">
                <h5 class="mb-0">
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
                            ] as $value)

                                <option value="{{ $value }}"
                                    {{ old(
                                        'maintenance_responsibility',
                                        $term->maintenance_responsibility
                                    ) == $value
                                        ? 'selected'
                                        : '' }}>

                                    {{ $value }}

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

                            @foreach(['Yes', 'No'] as $value)

                                <option value="{{ $value }}"
                                    {{ old(
                                        'insurance_required',
                                        $term->insurance_required
                                    ) == $value
                                        ? 'selected'
                                        : '' }}>

                                    {{ $value }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Subletting Allowed
                        </label>

                        <select name="subletting_allowed"
                                class="form-select">

                            @foreach(['Yes', 'No'] as $value)

                                <option value="{{ $value }}"
                                    {{ old(
                                        'subletting_allowed',
                                        $term->subletting_allowed
                                    ) == $value
                                        ? 'selected'
                                        : '' }}>

                                    {{ $value }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- Additional Terms --}}
        <div class="card mb-4">

            <div class="card-header">
                <h5 class="mb-0">
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
                              class="form-control">{{ old(
                                  'termination_clause',
                                  $term->termination_clause
                              ) }}</textarea>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Special Terms
                    </label>

                    <textarea name="special_terms"
                              rows="4"
                              class="form-control">{{ old(
                                  'special_terms',
                                  $term->special_terms
                              ) }}</textarea>

                </div>


                <div>

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea name="remarks"
                              rows="3"
                              class="form-control">{{ old(
                                  'remarks',
                                  $term->remarks
                              ) }}</textarea>

                </div>

            </div>

        </div>


        {{-- Save --}}
        <div class="card mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route(
                        'admin.leasing.terms.show',
                        $term->id
                    ) }}"
                       class="btn btn-secondary">

                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="fas fa-save me-1"></i>

                        Update Lease Terms

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const type =
        document.getElementById('late_fee_type');

    const suffix =
        document.getElementById('late_fee_suffix');


    function updateSuffix()
    {
        suffix.textContent =
            type.value === 'Fixed'
                ? '$'
                : '%';
    }


    type.addEventListener(
        'change',
        updateSuffix
    );

    updateSuffix();

});

</script>

@endsection