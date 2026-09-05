@extends('layouts.app')

@section('title', 'Edit Lease Renewal')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Edit Lease Renewal</h4>

            <div class="text-muted">
                {{ $renewal->renewal_no }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.leasing.renewals.show',
                $renewal->id
            ) }}"
               class="btn btn-secondary">

                <i class="fas fa-arrow-left me-1"></i>
                Back

            </a>

        </div>

    </div>


    {{-- Error Messages --}}
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


    {{-- Success --}}
    @if(session('success'))

        <div class="alert alert-success">

            <i class="fas fa-check-circle me-1"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- Error --}}
    @if(session('error'))

        <div class="alert alert-danger">

            <i class="fas fa-exclamation-circle me-1"></i>

            {{ session('error') }}

        </div>

    @endif


    <form method="POST"
          action="{{ route(
              'admin.leasing.renewals.update',
              $renewal->id
          ) }}">

        @csrf

        @method('PUT')


        {{-- ========================================================= --}}
        {{-- SECTION 1 : LEASE AGREEMENT --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-file-contract me-1"></i>

                    Lease Agreement

                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-8">

                        <label class="form-label">

                            Lease Agreement
                            <span class="text-danger">*</span>

                        </label>


                        <select name="lease_agreement_id"
                                id="lease_agreement_id"
                                class="form-select"
                                required>

                            <option value="">
                                -- Select Active Agreement --
                            </option>


                            @foreach($agreements as $agreement)

                                <option value="{{ $agreement->id }}"
                                    {{ old(
                                        'lease_agreement_id',
                                        $renewal->lease_agreement_id
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

                        <div class="form-text">

                            Only active lease agreements can be selected.

                        </div>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Renewal No.
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $renewal->renewal_no }}"
                               readonly>

                    </div>

                </div>


                {{-- Current Agreement Information --}}

                @if($renewal->agreement)

                    <div class="alert alert-info mt-3 mb-0">

                        <div class="row">

                            <div class="col-md-3">

                                <small class="text-muted">
                                    Agreement No.
                                </small>

                                <div class="fw-semibold">

                                    {{ $renewal->agreement->agreement_no }}

                                </div>

                            </div>


                            <div class="col-md-3">

                                <small class="text-muted">
                                    Tenant
                                </small>

                                <div class="fw-semibold">

                                    {{ $renewal->agreement
                                        ->tenant
                                        ?->company_name ?? '-' }}

                                </div>

                            </div>


                            <div class="col-md-3">

                                <small class="text-muted">
                                    Current Expiry
                                </small>

                                <div class="fw-semibold">

                                    {{ $renewal->current_expiry_date
                                        ? $renewal->current_expiry_date
                                            ->format('d M Y')
                                        : '-' }}

                                </div>

                            </div>


                            <div class="col-md-3">

                                <small class="text-muted">
                                    Current Rent
                                </small>

                                <div class="fw-semibold">

                                    ${{ number_format(
                                        $renewal->current_rent ?? 0,
                                        2
                                    ) }}

                                </div>

                            </div>

                        </div>

                    </div>

                @endif

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- SECTION 2 : RENEWAL PERIOD --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-calendar-alt me-1"></i>

                    Renewal Period

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Request Date --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Request Date
                            <span class="text-danger">*</span>

                        </label>

                        <input type="date"
                               name="request_date"
                               class="form-control"
                               value="{{ old(
                                   'request_date',
                                   $renewal->request_date
                                       ? $renewal->request_date
                                           ->format('Y-m-d')
                                       : ''
                               ) }}"
                               required>

                    </div>


                    {{-- Current Expiry --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Current Expiry Date

                        </label>

                        <input type="date"
                               class="form-control"
                               value="{{ $renewal->current_expiry_date
                                   ? $renewal->current_expiry_date
                                       ->format('Y-m-d')
                                   : ''
                               }}"
                               readonly>

                        <div class="form-text">

                            Automatically taken from the lease agreement.

                        </div>

                    </div>


                    {{-- Proposed Start --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Proposed Start Date
                            <span class="text-danger">*</span>

                        </label>

                        <input type="date"
                               name="proposed_start_date"
                               id="proposed_start_date"
                               class="form-control"
                               value="{{ old(
                                   'proposed_start_date',
                                   $renewal->proposed_start_date
                                       ? $renewal->proposed_start_date
                                           ->format('Y-m-d')
                                       : ''
                               ) }}"
                               required>

                    </div>


                    {{-- Proposed End --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Proposed End Date
                            <span class="text-danger">*</span>

                        </label>

                        <input type="date"
                               name="proposed_end_date"
                               id="proposed_end_date"
                               class="form-control"
                               value="{{ old(
                                   'proposed_end_date',
                                   $renewal->proposed_end_date
                                       ? $renewal->proposed_end_date
                                           ->format('Y-m-d')
                                       : ''
                               ) }}"
                               required>

                    </div>


                    {{-- Renewal Period --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Renewal Period (Months)

                        </label>

                        <input type="number"
                               name="renewal_period_months"
                               id="renewal_period_months"
                               class="form-control"
                               min="1"
                               value="{{ old(
                                   'renewal_period_months',
                                   $renewal->renewal_period_months
                               ) }}"
                               placeholder="e.g. 12">

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- SECTION 3 : FINANCIAL DETAILS --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-money-bill-wave me-1"></i>

                    Financial Details

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Current Rent --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Current Monthly Rent

                        </label>

                        <input type="text"
                               class="form-control"
                               value="${{ number_format(
                                   $renewal->current_rent ?? 0,
                                   2
                               ) }}"
                               readonly>

                    </div>


                    {{-- Proposed Rent --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Proposed Monthly Rent
                            <span class="text-danger">*</span>

                        </label>

                        <input type="number"
                               name="proposed_rent"
                               id="proposed_rent"
                               class="form-control"
                               min="0"
                               step="0.01"
                               value="{{ old(
                                   'proposed_rent',
                                   $renewal->proposed_rent
                               ) }}"
                               required>

                    </div>


                    {{-- Security Deposit --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Proposed Security Deposit

                        </label>

                        <input type="number"
                               name="proposed_security_deposit"
                               class="form-control"
                               min="0"
                               step="0.01"
                               value="{{ old(
                                   'proposed_security_deposit',
                                   $renewal->proposed_security_deposit
                               ) }}">

                    </div>


                    {{-- Escalation --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Escalation Percentage

                        </label>

                        <div class="input-group">

                            <input type="number"
                                   name="escalation_percentage"
                                   id="escalation_percentage"
                                   class="form-control"
                                   min="0"
                                   max="100"
                                   step="0.01"
                                   value="{{ old(
                                       'escalation_percentage',
                                       $renewal->escalation_percentage
                                   ) }}">

                            <span class="input-group-text">
                                %
                            </span>

                        </div>

                    </div>


                    {{-- Rent Difference --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Rent Increase

                        </label>

                        @php

                            $currentRent =
                                (float) ($renewal->current_rent ?? 0);

                            $proposedRent =
                                (float) ($renewal->proposed_rent ?? 0);

                            $difference =
                                $proposedRent - $currentRent;

                        @endphp

                        <input type="text"
                               id="rent_difference"
                               class="form-control"
                               value="${{ number_format(
                                   $difference,
                                   2
                               ) }}"
                               readonly>

                    </div>


                    {{-- Rent Increase % --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Rent Increase %

                        </label>

                        @php

                            $increasePercentage =
                                $currentRent > 0
                                ? (
                                    (
                                        $proposedRent -
                                        $currentRent
                                    ) / $currentRent
                                ) * 100
                                : 0;

                        @endphp

                        <div class="input-group">

                            <input type="text"
                                   id="rent_increase_percentage"
                                   class="form-control"
                                   value="{{ number_format(
                                       $increasePercentage,
                                       2
                                   ) }}"
                                   readonly>

                            <span class="input-group-text">
                                %
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- SECTION 4 : NEGOTIATION --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-comments me-1"></i>

                    Negotiation & Remarks

                </h5>

            </div>


            <div class="card-body">


                {{-- Negotiation Notes --}}

                <div class="mb-4">

                    <label class="form-label">

                        Negotiation Notes

                    </label>

                    <textarea name="negotiation_notes"
                              class="form-control"
                              rows="5"
                              placeholder="Enter negotiation details...">{{ old(
                                  'negotiation_notes',
                                  $renewal->negotiation_notes
                              ) }}</textarea>

                </div>


                {{-- Remarks --}}

                <div>

                    <label class="form-label">

                        Remarks

                    </label>

                    <textarea name="remarks"
                              class="form-control"
                              rows="4"
                              placeholder="Enter additional remarks...">{{ old(
                                  'remarks',
                                  $renewal->remarks
                              ) }}</textarea>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- STATUS INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="alert alert-warning">

            <div class="d-flex">

                <div class="me-2">

                    <i class="fas fa-info-circle"></i>

                </div>

                <div>

                    <strong>Draft Renewal</strong>

                    <div>

                        This renewal is currently in
                        <strong>Draft</strong> status.

                        You can modify the details until it is
                        submitted for approval.

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- ACTIONS --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-between">


                    <div>

                        <a href="{{ route(
                            'admin.leasing.renewals.show',
                            $renewal->id
                        ) }}"
                           class="btn btn-secondary">

                            <i class="fas fa-times me-1"></i>

                            Cancel

                        </a>

                    </div>


                    <div class="d-flex gap-2">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="fas fa-save me-1"></i>

                            Update Renewal

                        </button>

                    </div>

                </div>

            </div>

        </div>


    </form>

</div>

@endsection


{{-- ========================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ========================================================= --}}

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const proposedRent =
        document.getElementById('proposed_rent');

    const rentDifference =
        document.getElementById('rent_difference');

    const rentIncreasePercentage =
        document.getElementById(
            'rent_increase_percentage'
        );


    const currentRent =
        {{ (float) ($renewal->current_rent ?? 0) }};


    function calculateRentDifference()
    {

        const proposed =
            parseFloat(proposedRent.value) || 0;


        const difference =
            proposed - currentRent;


        const percentage =
            currentRent > 0
                ? (difference / currentRent) * 100
                : 0;


        rentDifference.value =
            '$' + difference.toLocaleString(
                'en-IN',
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );


        rentIncreasePercentage.value =
            percentage.toFixed(2);

    }


    proposedRent.addEventListener(
        'input',
        calculateRentDifference
    );


    calculateRentDifference();

});

</script>

@endpush