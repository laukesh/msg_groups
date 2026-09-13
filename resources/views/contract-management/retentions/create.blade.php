@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted">
                Contract Management
            </div>

            <h4 class="mb-1">
                Add Retention Entry
            </h4>

            <div class="text-muted">

                {{ $contract->contract_code }}

                <span class="mx-1">|</span>

                {{ $contract->contract_title }}

            </div>

        </div>


        <a href="{{ route(
            'admin.projects.contract-management.contracts.retentions.index',
            [$project, $contract]
        ) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>

            Back

        </a>

    </div>


    {{-- Errors --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
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


    {{-- Contract Requirement --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Contract Retention Terms
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Retention Required
                    </div>

                    <div class="fs-5 fw-semibold">

                        @if($contract->retention_required)

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


                <div class="col-md-4">

                    <div class="text-muted small">
                        Contract Retention %
                    </div>

                    <div class="fs-5 fw-semibold text-primary">

                        {{ number_format(
                            $contract->retention_percentage,
                            2
                        ) }}%

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Contract Currency
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{ $contract->currency ?? 'USD' }}

                    </div>

                </div>

            </div>


            @if(!$contract->retention_required)

                <div class="alert alert-warning mt-4 mb-0">

                    <i class="bi bi-exclamation-triangle me-1"></i>

                    Retention is currently marked as
                    <strong>not required</strong> for this contract.

                </div>

            @endif

        </div>

    </div>


    <form method="POST"
          action="{{ route(
              'admin.projects.contract-management.contracts.retentions.store',
              [$project, $contract]
          ) }}">

        @csrf


        {{-- ===================================================== --}}
        {{-- Retention Details --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Retention Details
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Date --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Retention Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="retention_date"
                               value="{{ old(
                                   'retention_date',
                                   now()->format('Y-m-d')
                               ) }}"
                               class="form-control"
                               required>

                    </div>


                    {{-- Invoice Number --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Invoice Number
                        </label>

                        <input type="text"
                               name="invoice_number"
                               value="{{ old('invoice_number') }}"
                               class="form-control"
                               placeholder="Invoice number">

                    </div>


                    {{-- Payment Reference --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Payment Reference
                        </label>

                        <input type="text"
                               name="payment_reference"
                               value="{{ old('payment_reference') }}"
                               class="form-control"
                               placeholder="Payment / transaction reference">

                    </div>


                    {{-- Certified Amount --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Certified Amount
                            <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               name="certified_amount"
                               id="certified_amount"
                               value="{{ old('certified_amount') }}"
                               min="0"
                               step="0.01"
                               class="form-control"
                               required>

                        <div class="form-text">
                            Amount certified for the related invoice/payment.
                        </div>

                    </div>


                    {{-- Retention Percentage --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Retention Percentage
                            <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               name="retention_percentage"
                               id="retention_percentage"
                               value="{{ old(
                                   'retention_percentage',
                                   $contract->retention_percentage
                               ) }}"
                               min="0"
                               max="100"
                               step="0.01"
                               class="form-control"
                               required>

                    </div>


                    {{-- Calculated Retention --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Retention Amount
                        </label>

                        <input type="text"
                               id="retention_amount_display"
                               class="form-control"
                               readonly>

                        <div class="form-text">
                            Automatically calculated.
                        </div>

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
                                   0
                               ) }}"
                               min="0"
                               step="0.01"
                               class="form-control">

                    </div>


                    {{-- Balance --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Balance Amount
                        </label>

                        <input type="text"
                               id="balance_amount_display"
                               class="form-control"
                               readonly>

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
                                   $contract->currency ?? 'USD'
                               ) }}"
                               maxlength="10"
                               class="form-control">

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Release Details --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Release Details
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Expected Release Date
                        </label>

                        <input type="date"
                               name="expected_release_date"
                               value="{{ old('expected_release_date') }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Release Date
                        </label>

                        <input type="date"
                               name="release_date"
                               value="{{ old('release_date') }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="Retained"
                                @selected(
                                    old('status', 'Retained')
                                    === 'Retained'
                                )>
                                Retained
                            </option>

                            <option value="Disputed"
                                @selected(
                                    old('status')
                                    === 'Disputed'
                                )>
                                Disputed
                            </option>

                            <option value="Cancelled"
                                @selected(
                                    old('status')
                                    === 'Cancelled'
                                )>
                                Cancelled
                            </option>

                        </select>

                        <div class="form-text">
                            Release status is automatically updated
                            when released amount is entered.
                        </div>

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            Release Remarks
                        </label>

                        <textarea name="release_remarks"
                                  rows="4"
                                  class="form-control"
                                  placeholder="Enter release details...">{{ old('release_remarks') }}</textarea>

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
                          class="form-control"
                          placeholder="Enter remarks...">{{ old('remarks') }}</textarea>

            </div>

        </div>


        {{-- Actions --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a href="{{ route(
                'admin.projects.contract-management.contracts.retentions.index',
                [$project, $contract]
            ) }}"
               class="btn btn-outline-secondary">

                Cancel

            </a>


            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-check-lg me-1"></i>

                Add Retention

            </button>

        </div>

    </form>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const certifiedAmount =
            document.getElementById(
                'certified_amount'
            );

        const percentage =
            document.getElementById(
                'retention_percentage'
            );

        const released =
            document.getElementById(
                'released_amount'
            );

        const retentionDisplay =
            document.getElementById(
                'retention_amount_display'
            );

        const balanceDisplay =
            document.getElementById(
                'balance_amount_display'
            );


        function calculate()
        {
            const certified =
                parseFloat(
                    certifiedAmount.value
                ) || 0;


            const rate =
                parseFloat(
                    percentage.value
                ) || 0;


            const releasedAmount =
                parseFloat(
                    released.value
                ) || 0;


            const retention =
                certified * rate / 100;


            const balance =
                Math.max(
                    0,
                    retention - releasedAmount
                );


            retentionDisplay.value =
                retention.toFixed(2);


            balanceDisplay.value =
                balance.toFixed(2);
        }


        certifiedAmount.addEventListener(
            'input',
            calculate
        );


        percentage.addEventListener(
            'input',
            calculate
        );


        released.addEventListener(
            'input',
            calculate
        );


        calculate();

    }
);

</script>

@endsection