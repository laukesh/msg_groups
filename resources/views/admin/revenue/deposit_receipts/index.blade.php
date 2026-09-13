@extends('layouts.app')

@section('title', 'Deposit Receipts')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                Deposit Receipts
            </h4>

            <p class="text-muted mb-0">
                Record and manage payments received against deposits.
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
             ADD RECEIPT
        ====================================================== --}}

        <div class="col-xl-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-1">

                        <i class="fas fa-receipt
                                  text-primary
                                  me-2"></i>

                        Add Deposit Receipt

                    </h5>

                    <small class="text-muted">

                        Record money received against a deposit.

                    </small>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'admin.revenue.deposit-receipts.store'
                          ) }}">

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

                                @foreach(
                                    $deposits as $deposit
                                )

                                    <option
                                        value="{{ $deposit->id }}"
                                        data-balance="{{ $deposit->balance_amount }}"
                                        @selected(
                                            old('deposit_id')
                                            == $deposit->id
                                        )>

                                        {{ $deposit
                                            ->leaseAgreement
                                            ->agreement_no
                                        }}

                                        -

                                        {{ $deposit->deposit_type }}

                                        -

                                        ${{
                                            number_format(
                                                $deposit->balance_amount,
                                                2
                                            )
                                        }}
                                        balance

                                    </option>

                                @endforeach

                            </select>

                            <small class="text-muted">

                                Only deposits with an outstanding
                                balance are shown.

                            </small>

                        </div>


                        {{-- =================================================
                             BALANCE INFORMATION
                        ================================================== --}}

                        <div id="balanceInfo"
                             class="alert alert-light border d-none mb-3">

                            <div class="d-flex
                                        justify-content-between">

                                <span class="text-muted">

                                    Remaining Balance

                                </span>

                                <strong id="remainingBalance"
                                        class="text-danger">

                                    $0.00

                                </strong>

                            </div>

                        </div>


                        {{-- =================================================
                             RECEIPT DATE
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Receipt Date
                                <span class="text-danger">*</span>

                            </label>

                            <input type="date"
                                   name="receipt_date"
                                   value="{{ old(
                                       'receipt_date',
                                       date('Y-m-d')
                                   ) }}"
                                   class="form-control"
                                   required>

                        </div>


                        {{-- =================================================
                             PAYMENT AMOUNT
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Payment Amount
                                <span class="text-danger">*</span>

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    $
                                </span>

                                <input type="number"
                                       name="payment_amount"
                                       id="payment_amount"
                                       value="{{ old(
                                           'payment_amount'
                                       ) }}"
                                       class="form-control"
                                       min="0.01"
                                       step="0.01"
                                       required>

                            </div>

                            <small id="amountHelp"
                                   class="text-muted">

                                Select a deposit first.

                            </small>

                        </div>


                        {{-- =================================================
                             PAYMENT MODE
                        ================================================== --}}

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
                                    'UPI',
                                    'Credit Card',
                                    'Debit Card'
                                ] as $mode)

                                    <option
                                        value="{{ $mode }}"
                                        @selected(
                                            old('payment_mode')
                                            === $mode
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
                                   maxlength="150"
                                   placeholder="Enter bank name">

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
                                   maxlength="100"
                                   placeholder="UTR / Cheque No. / Transaction ID">

                        </div>


                        {{-- =================================================
                             PAYMENT STATUS
                        ================================================== --}}

                        <!-- <div class="mb-3">

                            <label class="form-label">

                                Payment Status
                                <span class="text-danger">*</span>

                            </label>

                            <select name="payment_status"
                                    class="form-select"
                                    required>

                                <option value="Confirmed"
                                    @selected(
                                        old(
                                            'payment_status',
                                            'Confirmed'
                                        ) === 'Confirmed'
                                    )>

                                    Confirmed

                                </option>

                                <option value="Pending"
                                    @selected(
                                        old('payment_status')
                                        === 'Pending'
                                    )>

                                    Pending

                                </option>

                                <option value="Cancelled"
                                    @selected(
                                        old('payment_status')
                                        === 'Cancelled'
                                    )>

                                    Cancelled

                                </option>

                                <option value="Reversed"
                                    @selected(
                                        old('payment_status')
                                        === 'Reversed'
                                    )>

                                    Reversed

                                </option>

                            </select>

                            <small class="text-muted">

                                Only a Confirmed receipt updates
                                the deposit balance.

                            </small>

                        </div> -->


                        {{-- =================================================
                             REMARKS
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Remarks

                            </label>

                            <textarea name="remarks"
                                      rows="3"
                                      class="form-control"
                                      placeholder="Optional remarks">{{ old(
                                          'remarks'
                                      ) }}</textarea>

                        </div>


                        {{-- =================================================
                             SUBMIT
                        ================================================== --}}

                        <button type="submit"
                                class="btn btn-primary w-100">

                            <i class="fas fa-save me-1"></i>

                            Save Receipt

                        </button>

                    </form>

                </div>

            </div>

        </div>


        {{-- =====================================================
             RECEIPT LIST
        ====================================================== --}}

        <div class="col-xl-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-1">
                        Receipt Records
                    </h5>

                    <small class="text-muted">

                        {{ $receipts->count() }}
                        receipt(s)

                    </small>

                </div>


                <div class="card-body p-0">

                    @if($receipts->count() > 0)

                        <div class="table-responsive">

                            <table class="table
                                          table-hover
                                          align-middle
                                          mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th>
                                            Receipt
                                        </th>

                                        <th>
                                            Agreement
                                        </th>

                                        <th>
                                            Date
                                        </th>

                                        <th>
                                            Amount
                                        </th>

                                        <th>
                                            Mode
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
                                        $receipts as $receipt
                                    )

                                        <tr>

                                            {{-- RECEIPT --}}

                                            <td>

                                                <div class="fw-semibold">

                                                    {{
                                                        $receipt->receipt_no
                                                    }}

                                                </div>

                                                @if(
                                                    $receipt
                                                        ->transaction_reference
                                                )

                                                    <small class="text-muted">

                                                        {{
                                                            $receipt
                                                                ->transaction_reference
                                                        }}

                                                    </small>

                                                @endif

                                            </td>


                                            {{-- AGREEMENT --}}

                                            <td>

                                                @if(
                                                    $receipt->deposit
                                                    && $receipt
                                                        ->deposit
                                                        ->leaseAgreement
                                                )

                                                    {{
                                                        $receipt
                                                            ->deposit
                                                            ->leaseAgreement
                                                            ->agreement_no
                                                    }}

                                                    <br>

                                                    <small class="text-muted">

                                                        {{
                                                            $receipt
                                                                ->deposit
                                                                ->deposit_type
                                                        }}

                                                    </small>

                                                @else

                                                    <span class="text-danger">
                                                        Not found
                                                    </span>

                                                @endif

                                            </td>


                                            {{-- DATE --}}

                                            <td>

                                                {{
                                                    $receipt
                                                        ->receipt_date
                                                        ->format('d M Y')
                                                }}

                                            </td>


                                            {{-- AMOUNT --}}

                                            <td>

                                                <strong class="text-success">

                                                    ${{
                                                        number_format(
                                                            $receipt
                                                                ->payment_amount,
                                                            2
                                                        )
                                                    }}

                                                </strong>

                                            </td>


                                            {{-- MODE --}}

                                            <td>

                                                <span class="badge
                                                             bg-light
                                                             text-dark
                                                             border">

                                                    {{
                                                        $receipt
                                                            ->payment_mode
                                                    }}

                                                </span>

                                            </td>


                                            {{-- STATUS --}}

                                            <td>

                                                @php

                                                    $statusClass = match(
                                                        $receipt
                                                            ->payment_status
                                                    ) {

                                                        'Pending'
                                                            => 'warning',

                                                        'Confirmed'
                                                            => 'success',

                                                        'Cancelled'
                                                            => 'secondary',

                                                        'Reversed'
                                                            => 'danger',

                                                        default
                                                            => 'dark',

                                                    };

                                                @endphp

                                                <span class="badge
                                                             bg-{{ $statusClass }}">

                                                    {{
                                                        $receipt
                                                            ->payment_status
                                                    }}

                                                </span>

                                            </td>


                                            {{-- ACTIONS --}}

                                            {{-- ACTIONS --}}

                                            <td class="text-end">

                                                @if(
                                                    $receipt->payment_status === 'Confirmed'
                                                )

                                                    <div class="d-inline-flex gap-1">

                                                        <button type="button"
                                                                class="btn btn-sm
                                                                       btn-outline-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#reverseReceiptModal"
                                                                data-receipt-id="{{ $receipt->id }}"
                                                                data-receipt-no="{{ $receipt->receipt_no }}"
                                                                data-receipt-amount="{{ number_format(
                                                                    $receipt->payment_amount,
                                                                    2
                                                                ) }}">

                                                            <i class="fas fa-undo me-1"></i>

                                                            Reverse

                                                        </button>

                                                    </div>

                                                @elseif(
                                                    $receipt->payment_status === 'Pending'
                                                    ||
                                                    $receipt->payment_status === 'Cancelled'
                                                )

                                                    <div class="d-inline-flex gap-1">

                                                        {{-- CONFIRM --}}
                                                        @if($receipt->payment_status === 'Pending')

                                                            <form method="POST"
                                                                  action="{{ route(
                                                                      'admin.revenue.deposit-receipts.confirm',
                                                                      $receipt->id
                                                                  ) }}"
                                                                  onsubmit="return confirm(
                                                                      'Are you sure you want to confirm this receipt?'
                                                                  );">

                                                                @csrf

                                                                <button type="submit"
                                                                        class="btn btn-sm btn-outline-success"
                                                                        title="Confirm Receipt">

                                                                    <i class="fas fa-check"></i>

                                                                </button>

                                                            </form>

                                                        @endif


                                                        {{-- EDIT --}}
                                                        <a href="{{ route(
                                                            'admin.revenue.deposit-receipts.edit',
                                                            $receipt->id
                                                        ) }}"
                                                           class="btn btn-sm btn-outline-warning"
                                                           title="Edit Receipt">

                                                            <i class="fas fa-edit"></i>

                                                        </a>


                                                        {{-- DELETE --}}
                                                        <form method="POST"
                                                              action="{{ route(
                                                                  'admin.revenue.deposit-receipts.destroy',
                                                                  $receipt->id
                                                              ) }}"
                                                              onsubmit="return confirm(
                                                                  'Are you sure you want to delete this receipt?'
                                                              );">

                                                            @csrf

                                                            @method('DELETE')

                                                            <button type="submit"
                                                                    class="btn btn-sm btn-outline-danger"
                                                                    title="Delete Receipt">

                                                                <i class="fas fa-trash"></i>

                                                            </button>

                                                        </form>

                                                    </div>

                                                @else

                                                    <span class="text-muted small">

                                                        Locked

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

                            <i class="fas fa-receipt
                                      fa-3x
                                      d-block
                                      mb-3">
                            </i>

                            <h6>
                                No deposit receipts found
                            </h6>

                            <p class="mb-0">

                                Record the first payment
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
     BALANCE JAVASCRIPT
<<<<<<< HEAD
============================================================== --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const depositSelect =
            document.getElementById('deposit_id');

        const paymentAmount =
            document.getElementById('payment_amount');

        const balanceInfo =
            document.getElementById('balanceInfo');

        const remainingBalance =
            document.getElementById('remainingBalance');

        const amountHelp =
            document.getElementById('amountHelp');


        function updateBalance()
        {
            const selectedOption =
                depositSelect.options[
                    depositSelect.selectedIndex
                ];


            if (
                !selectedOption ||
                !selectedOption.value
            ) {

                balanceInfo.classList.add('d-none');

                paymentAmount.removeAttribute('max');

                amountHelp.textContent =
                    'Select a deposit first.';

                return;
            }


            const balance =
                parseFloat(
                    selectedOption.dataset.balance
                ) || 0;


            balanceInfo.classList.remove('d-none');


            remainingBalance.textContent =
                '$' +
                balance.toLocaleString(
                    'en-IN',
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                );


            paymentAmount.setAttribute(
                'max',
                balance
            );


            amountHelp.textContent =
                'Maximum payment allowed: $' +
                balance.toLocaleString(
                    'en-IN',
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                );
        }


        depositSelect.addEventListener(
            'change',
            updateBalance
        );


        updateBalance();

    }
);

</script>

@endsection

{{-- =============================================================
     REVERSAL MODAL
============================================================== --}}

<div class="modal fade"
     id="reverseReceiptModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="fas fa-undo
                              text-danger
                              me-2"></i>

                    Reverse Receipt

                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>


            <form method="POST"
                  id="reverseReceiptForm">

                @csrf

                <div class="modal-body">

                    <div class="alert alert-warning">

                        <i class="fas fa-exclamation-triangle
                                  me-1"></i>

                        Reversing a confirmed receipt will reduce
                        the received amount of the associated deposit.

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Receipt
                        </label>

                        <input type="text"
                               id="reverseReceiptNo"
                               class="form-control"
                               readonly>

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Amount
                        </label>

                        <input type="text"
                               id="reverseReceiptAmount"
                               class="form-control"
                               readonly>

                    </div>


                    <div class="mb-3">

                        <label class="form-label">

                            Reversal Reason
                            <span class="text-danger">*</span>

                        </label>

                        <textarea name="reversal_remarks"
                                  class="form-control"
                                  rows="4"
                                  required
                                  placeholder="Enter reason for reversing this payment..."></textarea>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit"
                            class="btn btn-danger">

                        <i class="fas fa-undo me-1"></i>

                        Confirm Reversal

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const modal =
            document.getElementById(
                'reverseReceiptModal'
            );

        if (!modal) {
            return;
        }


        modal.addEventListener(
            'show.bs.modal',
            function (event) {

                const button =
                    event.relatedTarget;


                const receiptId =
                    button.getAttribute(
                        'data-receipt-id'
                    );

                const receiptNo =
                    button.getAttribute(
                        'data-receipt-no'
                    );

                const amount =
                    button.getAttribute(
                        'data-receipt-amount'
                    );


                document.getElementById(
                    'reverseReceiptNo'
                ).value = receiptNo;


                document.getElementById(
                    'reverseReceiptAmount'
                ).value = '$' + amount;


                document.getElementById(
                    'reverseReceiptForm'
                ).action =
                    "{{ url(
                        'admin/revenue/deposit-receipts'
                    ) }}"
                    + '/'
                    + receiptId
                    + '/reverse';

            }
        );

    }
);

</script>

