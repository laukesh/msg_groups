@extends('layouts.app')

@section('title', 'Edit Deposit Receipt')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                Edit Deposit Receipt
            </h4>

            <p class="text-muted mb-0">
                Update an unconfirmed deposit receipt.
            </p>
        </div>

        <a href="{{ route(
            'admin.revenue.deposit-receipts.index'
        ) }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left me-1"></i>

            Back to Receipts

        </a>

    </div>


    {{-- =========================================================
         ERROR
    ========================================================== --}}

    @if(session('error'))

        <div class="alert alert-danger">

            <i class="fas fa-exclamation-circle me-1"></i>

            {{ session('error') }}

        </div>

    @endif


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
             RECEIPT FORM
        ====================================================== --}}

        <div class="col-xl-7">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-1">

                        <i class="fas fa-receipt
                                  text-primary
                                  me-2"></i>

                        Receipt Details

                    </h5>

                    <small class="text-muted">

                        {{ $receipt->receipt_no }}

                    </small>

                </div>


                <div class="card-body">

                    {{-- =============================================
                         RECEIPT STATUS
                    ============================================== --}}

                    @php

                        $statusClass = match(
                            $receipt->payment_status
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


                    <div class="alert
                                alert-{{ $statusClass }}">

                        <strong>
                            Current Status:
                        </strong>

                        {{ $receipt->payment_status }}

                    </div>


                    {{-- =============================================
                         RECEIPT NUMBER
                    ============================================== --}}

                    <div class="mb-3">

                        <label class="form-label">

                            Receipt Number

                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $receipt->receipt_no }}"
                               readonly>

                    </div>


                    {{-- =============================================
                         DEPOSIT
                    ============================================== --}}

                    <div class="mb-3">

                        <label class="form-label">

                            Deposit

                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ optional(
                                   optional(
                                       $receipt->deposit
                                   )->leaseAgreement
                               )->agreement_no
                               }} -
                               {{ optional(
                                   $receipt->deposit
                               )->deposit_type }}"
                               readonly>

                    </div>


                    {{-- =============================================
                         RECEIPT DATE
                    ============================================== --}}

                    <div class="mb-3">

                        <label class="form-label">

                            Receipt Date
                            <span class="text-danger">*</span>

                        </label>

                        <input type="date"
                               name="receipt_date"
                               form="receiptEditForm"
                               value="{{ old(
                                   'receipt_date',
                                   $receipt
                                       ->receipt_date
                                       ->format('Y-m-d')
                               ) }}"
                               class="form-control"
                               required>

                    </div>


                    {{-- =============================================
                         PAYMENT AMOUNT
                    ============================================== --}}

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
                                   form="receiptEditForm"
                                   value="{{ old(
                                       'payment_amount',
                                       $receipt->payment_amount
                                   ) }}"
                                   class="form-control"
                                   min="0.01"
                                   step="0.01"
                                   required>

                        </div>

                    </div>


                    {{-- =============================================
                         PAYMENT MODE
                    ============================================== --}}

                    <div class="mb-3">

                        <label class="form-label">

                            Payment Mode
                            <span class="text-danger">*</span>

                        </label>

                        <select name="payment_mode"
                                form="receiptEditForm"
                                class="form-select"
                                required>

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

                                <option value="{{ $mode }}"
                                    @selected(
                                        old(
                                            'payment_mode',
                                            $receipt->payment_mode
                                        ) === $mode
                                    )>

                                    {{ $mode }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- =============================================
                         BANK
                    ============================================== --}}

                    <div class="mb-3">

                        <label class="form-label">

                            Bank Name

                        </label>

                        <input type="text"
                               name="bank_name"
                               form="receiptEditForm"
                               value="{{ old(
                                   'bank_name',
                                   $receipt->bank_name
                               ) }}"
                               class="form-control"
                               maxlength="150">

                    </div>


                    {{-- =============================================
                         TRANSACTION REFERENCE
                    ============================================== --}}

                    <div class="mb-3">

                        <label class="form-label">

                            Transaction Reference

                        </label>

                        <input type="text"
                               name="transaction_reference"
                               form="receiptEditForm"
                               value="{{ old(
                                   'transaction_reference',
                                   $receipt
                                       ->transaction_reference
                               ) }}"
                               class="form-control"
                               maxlength="100">

                    </div>


                    {{-- =============================================
                         PAYMENT STATUS
                    ============================================== --}}

                    <!-- <div class="mb-3">

                        <label class="form-label">

                            Payment Status
                            <span class="text-danger">*</span>

                        </label>

                        <select name="payment_status"
                                form="receiptEditForm"
                                class="form-select"
                                required>

                            <option value="Pending"
                                @selected(
                                    old(
                                        'payment_status',
                                        $receipt->payment_status
                                    ) === 'Pending'
                                )>

                                Pending

                            </option>

                            <option value="Confirmed"
                                @selected(
                                    old(
                                        'payment_status',
                                        $receipt->payment_status
                                    ) === 'Confirmed'
                                )>

                                Confirmed

                            </option>

                            <option value="Cancelled"
                                @selected(
                                    old(
                                        'payment_status',
                                        $receipt->payment_status
                                    ) === 'Cancelled'
                                )>

                                Cancelled

                            </option>

                        </select>

                        <small class="text-muted">

                            Changing the receipt to Confirmed will
                            update the parent deposit.

                        </small>

                    </div> -->


                    {{-- =============================================
                         REMARKS
                    ============================================== --}}

                    <div class="mb-4">

                        <label class="form-label">

                            Remarks

                        </label>

                        <textarea name="remarks"
                                  form="receiptEditForm"
                                  rows="4"
                                  class="form-control">{{ old(
                                      'remarks',
                                      $receipt->remarks
                                  ) }}</textarea>

                    </div>


                    {{-- =============================================
                         ACTIONS
                    ============================================== --}}

                    <form id="receiptEditForm"
                          method="POST"
                          action="{{ route(
                              'admin.revenue.deposit-receipts.update',
                              $receipt->id
                          ) }}">

                        @csrf

                        @method('PUT')

                        <div class="d-flex
                                    justify-content-end
                                    gap-2">

                            <a href="{{ route(
                                'admin.revenue.deposit-receipts.index'
                            ) }}"
                               class="btn btn-secondary">

                                Cancel

                            </a>

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="fas fa-save me-1"></i>

                                Update Receipt

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- =====================================================
             SUMMARY
        ====================================================== --}}

        <div class="col-xl-5">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">

                        <i class="fas fa-money-check-alt
                                  text-primary
                                  me-2"></i>

                        Deposit Summary

                    </h5>

                </div>


                <div class="card-body">

                    @if($receipt->deposit)

                        <div class="mb-3">

                            <small class="text-muted">
                                Deposit Amount
                            </small>

                            <div class="fw-bold fs-5">

                                ${{
                                    number_format(
                                        $receipt
                                            ->deposit
                                            ->deposit_amount,
                                        2
                                    )
                                }}

                            </div>

                        </div>


                        <div class="mb-3">

                            <small class="text-muted">
                                Already Received
                            </small>

                            <div class="fw-bold text-success fs-5">

                                ${{
                                    number_format(
                                        $receipt
                                            ->deposit
                                            ->received_amount,
                                        2
                                    )
                                }}

                            </div>

                        </div>


                        <div class="mb-3">

                            <small class="text-muted">
                                Current Balance
                            </small>

                            <div class="fw-bold text-danger fs-5">

                                ${{
                                    number_format(
                                        $receipt
                                            ->deposit
                                            ->balance_amount,
                                        2
                                    )
                                }}

                            </div>

                        </div>


                        <hr>


                        <div class="small text-muted">

                            <strong>
                                Important:
                            </strong>

                            Confirming this receipt will add its
                            payment amount to the deposit.

                        </div>

                    @else

                        <div class="alert alert-danger mb-0">

                            Deposit record not found.

                        </div>

                    @endif

                </div>

            </div>


            {{-- =====================================================
                 FINANCIAL LOCK
            ====================================================== --}}

            <div class="card border-0 shadow-sm mt-4">

                <div class="card-body">

                    <h6 class="fw-bold">

                        <i class="fas fa-shield-alt
                                  text-success
                                  me-1"></i>

                        Financial Control

                    </h6>

                    <p class="small text-muted mb-0">

                        Confirmed receipts cannot be edited or deleted.
                        Any correction to a confirmed payment must go
                        through the reversal process.

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
