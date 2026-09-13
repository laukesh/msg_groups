@extends('layouts.app')

@section('title', 'Deposit Refunds')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                Deposit Refunds
            </h4>

            <p class="text-muted mb-0">
                Manage deposit settlements, deductions and refunds.
            </p>
        </div>

        <a href="{{ route(
            'admin.revenue.deposits.index'
        ) }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left me-1"></i>

            Back to Deposits

        </a>

    </div>


    {{-- =========================================================
         SUCCESS
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
         ERROR
    ========================================================== --}}

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-exclamation-circle me-1"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

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


    <div class="row g-4">


        {{-- =====================================================
             CREATE REFUND
        ====================================================== --}}

        <div class="col-xl-5">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-1">

                        <i class="fas fa-hand-holding-usd
                                  text-primary
                                  me-2"></i>

                        Create Deposit Refund

                    </h5>

                    <small class="text-muted">

                        Create a refund request for a fully received deposit.

                    </small>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'admin.revenue.deposit-refunds.store'
                          ) }}"
                          id="refundForm">

                        @csrf


                        {{-- =================================================
                             DEPOSIT
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Deposit
                                <span class="text-danger">*</span>

                            </label>

                            <select name="deposit_id"
                                    id="deposit_id"
                                    class="form-select"
                                    required>

                                <option value="">
                                    Select Deposit
                                </option>

                                @foreach($deposits as $deposit)

                                    <option
                                        value="{{ $deposit->id }}"
                                        data-deposit-amount="{{ $deposit->deposit_amount }}"
                                        data-refundable-amount="{{ $deposit->refundable_amount }}"
                                        @selected(
                                            old('deposit_id')
                                            == $deposit->id
                                        )>

                                        {{ optional(
                                            $deposit->leaseAgreement
                                        )->agreement_no }}

                                        -

                                        {{ $deposit->deposit_type }}

                                        -

                                        ${{
                                            number_format(
                                                $deposit->refundable_amount,
                                                2
                                            )
                                        }}
                                        refundable

                                    </option>

                                @endforeach

                            </select>

                            <small class="text-muted">

                                Only fully paid deposits with a
                                refundable balance are shown.

                            </small>

                        </div>


                        {{-- =================================================
                             DEPOSIT SUMMARY
                        ================================================== --}}

                        <div id="depositSummary"
                             class="alert alert-light border d-none mb-3">

                            <div class="d-flex
                                        justify-content-between
                                        mb-2">

                                <span class="text-muted">

                                    Original Deposit

                                </span>

                                <strong id="originalDeposit">

                                    $0.00

                                </strong>

                            </div>


                            <div class="d-flex
                                        justify-content-between">

                                <span class="text-muted">

                                    Available Refund

                                </span>

                                <strong id="availableRefund"
                                        class="text-success">

                                    $0.00

                                </strong>

                            </div>

                        </div>


                        {{-- =================================================
                             REFUND DATE
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Refund Date
                                <span class="text-danger">*</span>

                            </label>

                            <input type="date"
                                   name="refund_date"
                                   value="{{ old(
                                       'refund_date',
                                       date('Y-m-d')
                                   ) }}"
                                   class="form-control"
                                   required>

                        </div>


                        {{-- =================================================
                             DEDUCTIONS
                        ================================================== --}}

                        <div class="card bg-light border mb-3">

                            <div class="card-body">

                                <h6 class="fw-bold mb-3">

                                    <i class="fas fa-minus-circle
                                              text-danger
                                              me-1"></i>

                                    Deductions

                                </h6>


                                {{-- Outstanding Rent --}}

                                <div class="mb-3">

                                    <label class="form-label">

                                        Outstanding Rent

                                    </label>

                                    <input type="number"
                                           name="outstanding_rent"
                                           class="form-control deduction"
                                           value="{{ old(
                                               'outstanding_rent',
                                               0
                                           ) }}"
                                           min="0"
                                           step="0.01">

                                </div>


                                {{-- CAM --}}

                                <div class="mb-3">

                                    <label class="form-label">

                                        CAM Deduction

                                    </label>

                                    <input type="number"
                                           name="cam_deduction"
                                           class="form-control deduction"
                                           value="{{ old(
                                               'cam_deduction',
                                               0
                                           ) }}"
                                           min="0"
                                           step="0.01">

                                </div>


                                {{-- Utility --}}

                                <div class="mb-3">

                                    <label class="form-label">

                                        Utility Deduction

                                    </label>

                                    <input type="number"
                                           name="utility_deduction"
                                           class="form-control deduction"
                                           value="{{ old(
                                               'utility_deduction',
                                               0
                                           ) }}"
                                           min="0"
                                           step="0.01">

                                </div>


                                {{-- Damage --}}

                                <div class="mb-3">

                                    <label class="form-label">

                                        Damage Deduction

                                    </label>

                                    <input type="number"
                                           name="damage_deduction"
                                           class="form-control deduction"
                                           value="{{ old(
                                               'damage_deduction',
                                               0
                                           ) }}"
                                           min="0"
                                           step="0.01">

                                </div>


                                {{-- Penalty --}}

                                <div class="mb-3">

                                    <label class="form-label">

                                        Penalty Deduction

                                    </label>

                                    <input type="number"
                                           name="penalty_deduction"
                                           class="form-control deduction"
                                           value="{{ old(
                                               'penalty_deduction',
                                               0
                                           ) }}"
                                           min="0"
                                           step="0.01">

                                </div>


                                {{-- Other --}}

                                <div class="mb-0">

                                    <label class="form-label">

                                        Other Deduction

                                    </label>

                                    <input type="number"
                                           name="other_deduction"
                                           class="form-control deduction"
                                           value="{{ old(
                                               'other_deduction',
                                               0
                                           ) }}"
                                           min="0"
                                           step="0.01">

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             CALCULATION
                        ================================================== --}}

                        <div class="card border-primary mb-3">

                            <div class="card-body">

                                <div class="d-flex
                                            justify-content-between
                                            mb-2">

                                    <span>
                                        Total Deduction
                                    </span>

                                    <strong id="totalDeduction"
                                            class="text-danger">

                                        $0.00

                                    </strong>

                                </div>


                                <hr>


                                <div class="d-flex
                                            justify-content-between">

                                    <strong>
                                        Refund Amount
                                    </strong>

                                    <strong id="refundAmount"
                                            class="text-success fs-5">

                                        $0.00

                                    </strong>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             PAYMENT MODE
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Payment Mode

                            </label>

                            <select name="payment_mode"
                                    class="form-select">

                                <option value="">
                                    Select Payment Mode
                                </option>

                                @foreach([
                                    'Cash',
                                    'Cheque',
                                    'NEFT',
                                    'RTGS',
                                    'IMPS',
                                    'UPI'
                                ] as $mode)

                                    <option value="{{ $mode }}"
                                        @selected(
                                            old(
                                                'payment_mode'
                                            ) === $mode
                                        )>

                                        {{ $mode }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- =================================================
                             BANK
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Bank Name

                            </label>

                            <input type="text"
                                   name="bank_name"
                                   value="{{ old(
                                       'bank_name'
                                   ) }}"
                                   class="form-control"
                                   maxlength="150">

                        </div>


                        {{-- =================================================
                             TRANSACTION REFERENCE
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Transaction Reference

                            </label>

                            <input type="text"
                                   name="transaction_reference"
                                   value="{{ old(
                                       'transaction_reference'
                                   ) }}"
                                   class="form-control"
                                   maxlength="100">

                        </div>


                        {{-- =================================================
                             REMARKS
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Remarks

                            </label>

                            <textarea name="remarks"
                                      rows="3"
                                      class="form-control">{{ old(
                                          'remarks'
                                      ) }}</textarea>

                        </div>


                        {{-- =================================================
                             SUBMIT
                        ================================================== --}}

                        <button type="submit"
                                class="btn btn-primary w-100">

                            <i class="fas fa-save me-1"></i>

                            Create Refund Request

                        </button>

                    </form>

                </div>

            </div>

        </div>


        {{-- =====================================================
             REFUND RECORDS
        ====================================================== --}}

        <div class="col-xl-7">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-1">

                        Refund Records

                    </h5>

                    <small class="text-muted">

                        {{ $refunds->count() }}
                        refund(s)

                    </small>

                </div>


                <div class="card-body p-0">

                    @if($refunds->count())

                        <div class="table-responsive">

                            <table class="table
                                          table-hover
                                          align-middle
                                          mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th>
                                            Refund
                                        </th>

                                        <th>
                                            Agreement
                                        </th>

                                        <th>
                                            Deposit
                                        </th>

                                        <th>
                                            Deduction
                                        </th>

                                        <th>
                                            Refund
                                        </th>

                                        <th>
                                            Status
                                        </th>

                                        <th class="text-end">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach(
                                        $refunds as $refund
                                    )

                                        <tr>

                                            {{-- REFUND --}}

                                            <td>

                                                <strong>
                                                    {{ $refund->refund_no }}
                                                </strong>

                                                <br>

                                                <small class="text-muted">

                                                    {{
                                                        $refund
                                                            ->refund_date
                                                            ->format('d M Y')
                                                    }}

                                                </small>

                                            </td>


                                            {{-- AGREEMENT --}}

                                            <td>

                                                {{
                                                    optional(
                                                        optional(
                                                            $refund->deposit
                                                        )->leaseAgreement
                                                    )->agreement_no
                                                }}

                                            </td>


                                            {{-- DEPOSIT --}}

                                            <td>

                                                ${{
                                                    number_format(
                                                        $refund
                                                            ->original_deposit,
                                                        2
                                                    )
                                                }}

                                            </td>


                                            {{-- DEDUCTION --}}

                                            <td>

                                                <span class="text-danger">

                                                    ${{
                                                        number_format(
                                                            $refund
                                                                ->total_deduction,
                                                            2
                                                        )
                                                    }}

                                                </span>

                                            </td>


                                            {{-- REFUND --}}

                                            <td>

                                                <strong class="text-success">

                                                    ${{
                                                        number_format(
                                                            $refund
                                                                ->refund_amount,
                                                            2
                                                        )
                                                    }}

                                                </strong>

                                            </td>


                                            {{-- STATUS --}}

                                            <td>

                                                @php

                                                    $statusClass = match(
                                                        $refund->refund_status
                                                    ) {

                                                        'Pending'
                                                            => 'warning',

                                                        'Approved'
                                                            => 'primary',

                                                        'Processed'
                                                            => 'success',

                                                        'Cancelled'
                                                            => 'secondary',

                                                        default
                                                            => 'dark',

                                                    };

                                                @endphp

                                                <span class="badge
                                                             bg-{{ $statusClass }}">

                                                    {{
                                                        $refund
                                                            ->refund_status
                                                    }}

                                                </span>

                                            </td>


                                            {{-- ACTIONS --}}

                                            <td class="text-end">

                                                @if(
                                                    $refund
                                                        ->refund_status
                                                        === 'Pending'
                                                )

                                                    <div class="d-inline-flex
                                                                gap-1">

                                                        {{-- APPROVE --}}

                                                        <form method="POST"
                                                              action="{{ route(
                                                                  'admin.revenue.deposit-refunds.approve',
                                                                  $refund->id
                                                              ) }}">

                                                            @csrf

                                                            <button type="submit"
                                                                    class="btn btn-sm
                                                                           btn-outline-primary"
                                                                    onclick="return confirm(
                                                                        'Approve this refund?'
                                                                    );">

                                                                <i class="fas fa-check"></i>

                                                            </button>

                                                        </form>


                                                        {{-- CANCEL --}}

                                                        <button type="button"
                                                                class="btn btn-sm
                                                                       btn-outline-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#cancelRefundModal"
                                                                data-refund-id="{{ $refund->id }}">

                                                            <i class="fas fa-times"></i>

                                                        </button>

                                                    </div>


                                                @elseif(
                                                    $refund
                                                        ->refund_status
                                                        === 'Approved'
                                                )

                                                    <button type="button"
                                                            class="btn btn-sm
                                                                   btn-outline-success"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#processRefundModal"
                                                            data-refund-id="{{ $refund->id }}"
                                                            data-refund-no="{{ $refund->refund_no }}"
                                                            data-refund-amount="{{ number_format(
                                                                $refund->refund_amount,
                                                                2
                                                            ) }}">

                                                        <i class="fas fa-money-bill-wave me-1"></i>

                                                        Process

                                                    </button>


                                                @else

                                                    <span class="text-muted small">
                                                        —
                                                    </span>

                                                @endif

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="text-center
                                    text-muted
                                    py-5">

                            <i class="fas fa-hand-holding-usd
                                      fa-3x
                                      mb-3">
                            </i>

                            <h6>
                                No refund records found
                            </h6>

                            <p class="mb-0">

                                Create a refund request
                                using the form.

                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =============================================================
     PROCESS REFUND MODAL
============================================================== --}}

<div class="modal fade"
     id="processRefundModal"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="fas fa-money-bill-wave
                              text-success
                              me-2"></i>

                    Process Refund

                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>


            <form method="POST"
                  id="processRefundForm">

                @csrf

                <div class="modal-body">

                    <div class="alert alert-success">

                        This will mark the refund as
                        <strong>Processed</strong>.

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Refund
                        </label>

                        <input type="text"
                               id="processRefundNo"
                               class="form-control"
                               readonly>

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Refund Amount
                        </label>

                        <input type="text"
                               id="processRefundAmount"
                               class="form-control"
                               readonly>

                    </div>


                    <div class="mb-3">

                        <label class="form-label">

                            Payment Mode
                            <span class="text-danger">*</span>

                        </label>

                        <select name="payment_mode"
                                class="form-select"
                                required>

                            <option value="">
                                Select Payment Mode
                            </option>

                            @foreach([
                                'Cash',
                                'Cheque',
                                'NEFT',
                                'RTGS',
                                'IMPS',
                                'UPI'
                            ] as $mode)

                                <option value="{{ $mode }}">
                                    {{ $mode }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Bank Name
                        </label>

                        <input type="text"
                               name="bank_name"
                               class="form-control"
                               maxlength="150">

                    </div>


                    <div class="mb-3">

                        <label class="form-label">

                            Transaction Reference

                        </label>

                        <input type="text"
                               name="transaction_reference"
                               class="form-control"
                               maxlength="100">

                    </div>


                    <div class="mb-0">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea name="remarks"
                                  class="form-control"
                                  rows="3"></textarea>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit"
                            class="btn btn-success">

                        <i class="fas fa-check me-1"></i>

                        Process Refund

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =============================================================
     CANCEL REFUND MODAL
============================================================== --}}

<div class="modal fade"
     id="cancelRefundModal"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="fas fa-times-circle
                              text-danger
                              me-2"></i>

                    Cancel Refund

                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>


            <form method="POST"
                  id="cancelRefundForm">

                @csrf

                <div class="modal-body">

                    <label class="form-label">

                        Cancellation Reason
                        <span class="text-danger">*</span>

                    </label>

                    <textarea name="remarks"
                              class="form-control"
                              rows="4"
                              required
                              placeholder="Enter reason for cancellation..."></textarea>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit"
                            class="btn btn-danger">

                        Confirm Cancellation

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =============================================================
     CALCULATION SCRIPT
============================================================== --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const depositSelect =
            document.getElementById(
                'deposit_id'
            );

        const depositSummary =
            document.getElementById(
                'depositSummary'
            );

        const originalDeposit =
            document.getElementById(
                'originalDeposit'
            );

        const availableRefund =
            document.getElementById(
                'availableRefund'
            );

        const totalDeduction =
            document.getElementById(
                'totalDeduction'
            );

        const refundAmount =
            document.getElementById(
                'refundAmount'
            );


        function formatCurrency(amount)
        {
            return '$' +
                Number(amount || 0).toLocaleString(
                    'en-IN',
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                );
        }


        function calculateRefund()
        {
            let original = 0;

            let available = 0;


            const option =
                depositSelect.options[
                    depositSelect.selectedIndex
                ];


            if (
                option &&
                option.value
            ) {

                original =
                    parseFloat(
                        option.dataset.depositAmount
                    ) || 0;

                available =
                    parseFloat(
                        option.dataset.refundableAmount
                    ) || 0;


                depositSummary.classList.remove(
                    'd-none'
                );

            } else {

                depositSummary.classList.add(
                    'd-none'
                );
            }


            let deduction = 0;


            document
                .querySelectorAll('.deduction')
                .forEach(function (input) {

                    deduction +=
                        parseFloat(
                            input.value
                        ) || 0;

                });


            const refund =
                Math.max(
                    0,
                    original - deduction
                );


            totalDeduction.textContent =
                formatCurrency(
                    deduction
                );


            refundAmount.textContent =
                formatCurrency(
                    refund
                );


            originalDeposit.textContent =
                formatCurrency(
                    original
                );


            availableRefund.textContent =
                formatCurrency(
                    available
                );


            if (
                refund > available
                && option
                && option.value
            ) {

                refundAmount.classList
                    .remove('text-success');

                refundAmount.classList
                    .add('text-danger');

            } else {

                refundAmount.classList
                    .remove('text-danger');

                refundAmount.classList
                    .add('text-success');

            }
        }


        depositSelect.addEventListener(
            'change',
            calculateRefund
        );


        document
            .querySelectorAll('.deduction')
            .forEach(function (input) {

                input.addEventListener(
                    'input',
                    calculateRefund
                );

            });


        calculateRefund();


        /*
        |--------------------------------------------------------------------------
        | Process Modal
        |--------------------------------------------------------------------------
        */

        const processModal =
            document.getElementById(
                'processRefundModal'
            );


        if (processModal) {

            processModal.addEventListener(
                'show.bs.modal',
                function (event) {

                    const button =
                        event.relatedTarget;

                    const id =
                        button.getAttribute(
                            'data-refund-id'
                        );

                    const refundNo =
                        button.getAttribute(
                            'data-refund-no'
                        );

                    const amount =
                        button.getAttribute(
                            'data-refund-amount'
                        );


                    document.getElementById(
                        'processRefundNo'
                    ).value =
                        refundNo;


                    document.getElementById(
                        'processRefundAmount'
                    ).value =
                        '$' + amount;


                    document.getElementById(
                        'processRefundForm'
                    ).action =
                        "{{ url(
                            'admin/revenue/deposit-refunds'
                        ) }}"
                        + '/'
                        + id
                        + '/process';

                });

        }


        /*
        |--------------------------------------------------------------------------
        | Cancel Modal
        |--------------------------------------------------------------------------
        */

        const cancelModal =
            document.getElementById(
                'cancelRefundModal'
            );


        if (cancelModal) {

            cancelModal.addEventListener(
                'show.bs.modal',
                function (event) {

                    const button =
                        event.relatedTarget;

                    const id =
                        button.getAttribute(
                            'data-refund-id'
                        );


                    document.getElementById(
                        'cancelRefundForm'
                    ).action =
                        "{{ url(
                            'admin/revenue/deposit-refunds'
                        ) }}"
                        + '/'
                        + id
                        + '/cancel';

                });

        }

    }
);

</script>

@endsection
