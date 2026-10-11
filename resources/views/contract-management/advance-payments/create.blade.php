@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted">
                Contract Management
            </div>

            <h4 class="mb-1">
                Add Advance Payment Transaction
            </h4>

            <div class="text-muted">

                {{ $contract->contract_code }}

                <span class="mx-1">|</span>

                {{ $contract->contract_title }}

            </div>

        </div>


        <a href="{{ route(
            'admin.projects.contract-management.contracts.advance-payments.index',
            [$project, $contract]
        ) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>

            Back

        </a>

    </div>


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


    {{-- Contract Term --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Contract Advance Payment Term
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Advance Required
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{ $contract->advance_payment_required ? 'Yes' : 'No' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Contract Advance Amount
                    </div>

                    <div class="fs-5 fw-semibold text-primary">

                        {{ $contract->currency ?? 'USD' }}

                        {{ number_format(
                            $contract->advance_payment_amount,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Currency
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{ $contract->currency ?? 'USD' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    <form method="POST"
          action="{{ route(
              'admin.projects.contract-management.contracts.advance-payments.store',
              [$project, $contract]
          ) }}">

        @csrf


        {{-- Transaction --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Transaction Details
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    <div class="col-md-4">

                        <label class="form-label">
                            Transaction Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="transaction_date"
                               value="{{ old(
                                   'transaction_date',
                                   now()->format('Y-m-d')
                               ) }}"
                               class="form-control"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Transaction Type
                            <span class="text-danger">*</span>
                        </label>

                        <select name="transaction_type"
                                id="transaction_type"
                                class="form-select"
                                required>

                            <option value="Advance Released"
                                @selected(
                                    old(
                                        'transaction_type',
                                        'Advance Released'
                                    )
                                    ===
                                    'Advance Released'
                                )>
                                Advance Released
                            </option>

                            <option value="Advance Recovery"
                                @selected(
                                    old('transaction_type')
                                    ===
                                    'Advance Recovery'
                                )>
                                Advance Recovery
                            </option>

                            <option value="Adjustment"
                                @selected(
                                    old('transaction_type')
                                    ===
                                    'Adjustment'
                                )>
                                Adjustment
                            </option>

                            <option value="Refund"
                                @selected(
                                    old('transaction_type')
                                    ===
                                    'Refund'
                                )>
                                Refund
                            </option>

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Reference Number
                        </label>

                        <input type="text"
                               name="reference_number"
                               value="{{ old('reference_number') }}"
                               class="form-control"
                               placeholder="Payment / transaction reference">

                    </div>


                    {{-- Advance Amount --}}

                    <div class="col-md-4"
                         id="advance_amount_group">

                        <label class="form-label">
                            Advance Amount
                        </label>

                        <input type="number"
                               name="advance_amount"
                               id="advance_amount"
                               value="{{ old(
                                   'advance_amount',
                                   0
                               ) }}"
                               min="0"
                               step="0.01"
                               class="form-control">

                        <div class="form-text">
                            Used when releasing advance,
                            adjustment or refund.
                        </div>

                    </div>


                    {{-- Recovery Amount --}}

                    <div class="col-md-4"
                         id="recovered_amount_group">

                        <label class="form-label">
                            Recovery Amount
                        </label>

                        <input type="number"
                               name="recovered_amount"
                               id="recovered_amount"
                               value="{{ old(
                                   'recovered_amount',
                                   0
                               ) }}"
                               min="0"
                               step="0.01"
                               class="form-control">

                        <div class="form-text">
                            Used when recovering advance from contractor.
                        </div>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Certified Amount
                        </label>

                        <input type="number"
                               name="certified_amount"
                               value="{{ old(
                                   'certified_amount',
                                   0
                               ) }}"
                               min="0"
                               step="0.01"
                               class="form-control">

                        <div class="form-text">
                            Optional related certified amount.
                        </div>

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
                               class="form-control"
                               maxlength="10">

                    </div>

                </div>

            </div>

        </div>


        {{-- Recovery --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Recovery Details
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Expected Recovery Date
                        </label>

                        <input type="date"
                               name="expected_recovery_date"
                               value="{{ old(
                                   'expected_recovery_date'
                               ) }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Recovery Date
                        </label>

                        <input type="date"
                               name="recovery_date"
                               value="{{ old(
                                   'recovery_date'
                               ) }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="Released">
                                Released
                            </option>

                            <option value="Partially Recovered">
                                Partially Recovered
                            </option>

                            <option value="Fully Recovered">
                                Fully Recovered
                            </option>

                            <option value="Not Released">
                                Not Released
                            </option>

                        </select>

                        <div class="form-text">
                            System recalculates the overall ledger status.
                        </div>

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea name="remarks"
                                  rows="5"
                                  class="form-control"
                                  placeholder="Enter transaction remarks...">{{ old('remarks') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2 mb-5">

            <a href="{{ route(
                'admin.projects.contract-management.contracts.advance-payments.index',
                [$project, $contract]
            ) }}"
               class="btn btn-outline-secondary">

                Cancel

            </a>


            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-check-lg me-1"></i>

                Save Transaction

            </button>

        </div>

    </form>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const type =
            document.getElementById(
                'transaction_type'
            );

        const advanceGroup =
            document.getElementById(
                'advance_amount_group'
            );

        const recoveryGroup =
            document.getElementById(
                'recovered_amount_group'
            );


        function updateFields()
        {
            const value =
                type.value;


            if (
                value ===
                'Advance Recovery'
            ) {

                advanceGroup.style.display =
                    'none';

                recoveryGroup.style.display =
                    'block';

            } else {

                advanceGroup.style.display =
                    'block';

                recoveryGroup.style.display =
                    'none';
            }
        }


        type.addEventListener(
            'change',
            updateFields
        );


        updateFields();

    }
);

</script>

@endsection