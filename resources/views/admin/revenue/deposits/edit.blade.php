@extends('layouts.app')

@section('title', 'Edit Deposit')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                Edit Deposit
            </h4>

            <p class="text-muted mb-0">
                Update deposit details and review payment status.
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
         SUCCESS MESSAGE
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
         ERROR MESSAGE
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
             EDIT FORM
        ====================================================== --}}

        <div class="col-xl-7">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-1">

                        <i class="fas fa-edit
                                  text-primary
                                  me-2"></i>

                        Deposit Details

                    </h5>

                    <small class="text-muted">

                        Update the deposit information.

                    </small>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'admin.revenue.deposits.update',
                              $deposit->id
                          ) }}">

                        @csrf

                        @method('PUT')


                        {{-- =================================================
                             LEASE AGREEMENT
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Lease Agreement
                                <span class="text-danger">*</span>

                            </label>

                            <select name="lease_agreement_id"
                                    class="form-select"
                                    required>

                                <option value="">
                                    Select Lease Agreement
                                </option>

                                @foreach(
                                    $leaseAgreements as $agreement
                                )

                                    <option
                                        value="{{ $agreement->id }}"
                                        @selected(
                                            old(
                                                'lease_agreement_id',
                                                $deposit
                                                    ->lease_agreement_id
                                            ) == $agreement->id
                                        )>

                                        {{ $agreement->agreement_no }}
                                        -
                                        {{ $agreement->agreement_status }}

                                    </option>

                                @endforeach

                            </select>

                            <small class="text-muted">

                                Only Active and Renewed agreements
                                are available.

                            </small>

                        </div>


                        {{-- =================================================
                             DEPOSIT TYPE
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Deposit Type
                                <span class="text-danger">*</span>

                            </label>

                            <select name="deposit_type"
                                    class="form-select"
                                    required>

                                <option value="Security Deposit"
                                    @selected(
                                        old(
                                            'deposit_type',
                                            $deposit->deposit_type
                                        ) === 'Security Deposit'
                                    )>

                                    Security Deposit

                                </option>

                                <option value="Additional Deposit"
                                    @selected(
                                        old(
                                            'deposit_type',
                                            $deposit->deposit_type
                                        ) === 'Additional Deposit'
                                    )>

                                    Additional Deposit

                                </option>

                                <option value="Utility Deposit"
                                    @selected(
                                        old(
                                            'deposit_type',
                                            $deposit->deposit_type
                                        ) === 'Utility Deposit'
                                    )>

                                    Utility Deposit

                                </option>

                            </select>

                        </div>


                        {{-- =================================================
                             DEPOSIT AMOUNT
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Deposit Amount
                                <span class="text-danger">*</span>

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    $
                                </span>

                                <input type="number"
                                       name="deposit_amount"
                                       value="{{ old(
                                           'deposit_amount',
                                           $deposit->deposit_amount
                                       ) }}"
                                       class="form-control"
                                       min="0.01"
                                       step="0.01"
                                       required>

                            </div>

                            @if(
                                $deposit->received_amount > 0
                            )

                                <small class="text-warning">

                                    <i class="fas fa-lock me-1"></i>

                                    Deposit amount cannot be changed
                                    after a payment has been received.

                                </small>

                            @endif

                        </div>


                        {{-- =================================================
                             DUE DATE
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Due Date

                            </label>

                            <input type="date"
                                   name="due_date"
                                   value="{{ old(
                                       'due_date',
                                       optional(
                                           $deposit->due_date
                                       )->format('Y-m-d')
                                   ) }}"
                                   class="form-control">

                        </div>


                        {{-- =================================================
                             REMARKS
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Remarks

                            </label>

                            <textarea name="remarks"
                                      rows="4"
                                      class="form-control"
                                      placeholder="Optional remarks">{{ old(
                                          'remarks',
                                          $deposit->remarks
                                      ) }}</textarea>

                        </div>


                        <div class="d-flex
                                    justify-content-end
                                    gap-2">

                            <a href="{{ route(
                                'admin.revenue.deposits.index'
                            ) }}"
                               class="btn btn-secondary">

                                Cancel

                            </a>

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="fas fa-save me-1"></i>

                                Update Deposit

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- =====================================================
             DEPOSIT SUMMARY
        ====================================================== --}}

        <div class="col-xl-5">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">

                        <i class="fas fa-chart-pie
                                  text-primary
                                  me-2"></i>

                        Deposit Summary

                    </h5>

                </div>


                <div class="card-body">

                    {{-- TOTAL --}}

                    <div class="d-flex
                                justify-content-between
                                border-bottom
                                pb-3
                                mb-3">

                        <span class="text-muted">
                            Deposit Amount
                        </span>

                        <strong>

                            ${{
                                number_format(
                                    $deposit->deposit_amount,
                                    2
                                )
                            }}

                        </strong>

                    </div>


                    {{-- RECEIVED --}}

                    <div class="d-flex
                                justify-content-between
                                border-bottom
                                pb-3
                                mb-3">

                        <span class="text-muted">
                            Received
                        </span>

                        <strong class="text-success">

                            ${{
                                number_format(
                                    $deposit->received_amount,
                                    2
                                )
                            }}

                        </strong>

                    </div>


                    {{-- BALANCE --}}

                    <div class="d-flex
                                justify-content-between
                                border-bottom
                                pb-3
                                mb-3">

                        <span class="text-muted">
                            Balance
                        </span>

                        <strong class="text-danger">

                            ${{
                                number_format(
                                    $deposit->balance_amount,
                                    2
                                )
                            }}

                        </strong>

                    </div>


                    {{-- REFUNDABLE --}}

                    <div class="d-flex
                                justify-content-between
                                border-bottom
                                pb-3
                                mb-3">

                        <span class="text-muted">
                            Refundable
                        </span>

                        <strong class="text-info">

                            ${{
                                number_format(
                                    $deposit->refundable_amount,
                                    2
                                )
                            }}

                        </strong>

                    </div>


                    {{-- STATUS --}}

                    <div class="d-flex
                                justify-content-between">

                        <span class="text-muted">
                            Payment Status
                        </span>

                        @php

                            $statusClass = match(
                                $deposit->payment_status
                            ) {

                                'Pending'
                                    => 'secondary',

                                'Partial'
                                    => 'warning',

                                'Paid'
                                    => 'success',

                                'Refunded'
                                    => 'info',

                                default
                                    => 'dark',

                            };

                        @endphp

                        <span class="badge
                                     bg-{{ $statusClass }}">

                            {{ $deposit->payment_status }}

                        </span>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 PAYMENT INFORMATION
            ====================================================== --}}

            <div class="card border-0 shadow-sm mt-4">

                <div class="card-body">

                    <h6 class="fw-bold mb-3">

                        <i class="fas fa-info-circle
                                  text-primary
                                  me-1"></i>

                        Deposit Information

                    </h6>

                    <div class="small text-muted">

                        <div class="mb-2">

                            <strong>
                                UUID:
                            </strong>

                            <br>

                            {{ $deposit->uuid }}

                        </div>


                        <div class="mb-2">

                            <strong>
                                Due Date:
                            </strong>

                            <br>

                            {{ $deposit->due_date
                                ? $deposit->due_date->format('d M Y')
                                : 'Not specified'
                            }}

                        </div>


                        <div>

                            <strong>
                                Created:
                            </strong>

                            <br>

                            {{ $deposit->created_at
                                ? $deposit->created_at->format(
                                    'd M Y h:i A'
                                )
                                : '-'
                            }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
