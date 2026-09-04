@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted">
                Contract Management
            </div>

            <h4 class="mb-1">
                Edit Advance Payment Transaction
            </h4>

            <div class="text-muted">

                {{ $advancePayment->advance_number }}

                <span class="mx-1">|</span>

                {{ $contract->contract_code }}

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


    <form method="POST"
          action="{{ route(
              'admin.projects.contract-management.contracts.advance-payments.update',
              [$project, $contract, $advancePayment]
          ) }}">

        @csrf

        @method('PUT')


        {{-- Transaction Details --}}

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
                            Transaction Number
                        </label>

                        <input type="text"
                               value="{{ $advancePayment->advance_number }}"
                               class="form-control"
                               readonly>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Transaction Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="transaction_date"
                               value="{{ old(
                                   'transaction_date',
                                   $advancePayment
                                       ->transaction_date
                                       ?->format('Y-m-d')
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

                            @foreach([
                                'Advance Released',
                                'Advance Recovery',
                                'Adjustment',
                                'Refund'
                            ] as $type)

                                <option value="{{ $type }}"
                                    @selected(
                                        old(
                                            'transaction_type',
                                            $advancePayment->transaction_type
                                        )
                                        ===
                                        $type
                                    )>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Reference Number
                        </label>

                        <input type="text"
                               name="reference_number"
                               value="{{ old(
                                   'reference_number',
                                   $advancePayment->reference_number
                               ) }}"
                               class="form-control">

                    </div>


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
                                   $advancePayment->advance_amount
                               ) }}"
                               min="0"
                               step="0.01"
                               class="form-control">

                    </div>


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
                                   $advancePayment->recovered_amount
                               ) }}"
                               min="0"
                               step="0.01"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Certified Amount
                        </label>

                        <input type="number"
                               name="certified_amount"
                               value="{{ old(
                                   'certified_amount',
                                   $advancePayment->certified_amount
                               ) }}"
                               min="0"
                               step="0.01"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Currency
                        </label>

                        <input type="text"
                               name="currency"
                               value="{{ old(
                                   'currency',
                                   $advancePayment->currency
                               ) }}"
                               class="form-control"
                               maxlength="10">

                    </div>

                </div>

            </div>

        </div>


        {{-- Recovery Details --}}

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
                                   'expected_recovery_date',
                                   $advancePayment
                                       ->expected_recovery_date
                                       ?->format('Y-m-d')
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
                                   'recovery_date',
                                   $advancePayment
                                       ->recovery_date
                                       ?->format('Y-m-d')
                               ) }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Current Balance
                        </label>

                        <input type="text"
                               value="{{ $advancePayment->currency }} {{ number_format(
                                   $advancePayment->balance_amount,
                                   2
                               ) }}"
                               class="form-control"
                               readonly>

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            @foreach([
                                'Released',
                                'Partially Recovered',
                                'Fully Recovered',
                                'Not Released'
                            ] as $status)

                                <option value="{{ $status }}"
                                    @selected(
                                        old(
                                            'status',
                                            $advancePayment->status
                                        )
                                        ===
                                        $status
                                    )>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea name="remarks"
                                  rows="5"
                                  class="form-control">{{ old(
                                      'remarks',
                                      $advancePayment->remarks
                                  ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- Audit --}}

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
                            Created
                        </div>

                        {{
                            $advancePayment->created_at
                            ?->format('d M Y H:i')
                            ?? '—'
                        }}

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Last Updated
                        </div>

                        {{
                            $advancePayment->updated_at
                            ?->format('d M Y H:i')
                            ?? '—'
                        }}

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

                Update Transaction

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