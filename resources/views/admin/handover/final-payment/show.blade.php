@extends('layouts.app')

@section('title', 'Final Payment - ' . $payment->payment_no)

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                {{ $payment->payment_no }}
            </h4>

            <div class="text-muted">

                {{ $project->project_code ?? '' }}

                @if($project->project_name)
                    - {{ $project->project_name }}
                @endif

            </div>

            <div class="small text-muted mt-1">
                Final Payment / Financial Closeout
            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.handover.final-payment.index',
                $project
            ) }}"
               class="btn btn-light border">

                <i class="ri-arrow-left-line me-1"></i>

                Back to Final Payments

            </a>

        </div>

    </div>


    {{-- Alerts --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="ri-checkbox-circle-line me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="ri-error-warning-line me-1"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
         Contract / Final Account
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="card-title mb-1">
                        Contract & Final Account
                    </h5>

                    <div class="text-muted small">
                        Source of this Final Payment
                    </div>

                </div>


                <span class="badge
                    @if($finalAccount->status === 'Approved')
                        bg-success
                    @elseif($finalAccount->status === 'Rejected')
                        bg-danger
                    @else
                        bg-warning text-dark
                    @endif">

                    {{ $finalAccount->status }}

                </span>

            </div>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Procurement Contract
                    </div>

                    <div class="fw-semibold mt-1">

                            <div class="fw-semibold">
                                {{ $finalAccount->procurementContract?->contract_number
                                    ?? 'Contract #' . $finalAccount->procurement_contract_id }}
                            </div>

                            @if($finalAccount->procurementContract?->contract_title)
                                <small class="text-muted">
                                    {{ $finalAccount->procurementContract->contract_title }}
                                </small>
                            @endif

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Contractor
                    </div>

                    <div class="fw-semibold mt-1">

                        {{ $finalAccount->procurementContract?->bidder?->company_name
                                    ?? $finalAccount->procurementContract?->bidder_name
                                    ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Final Account No.
                    </div>

                    <div class="fw-semibold mt-1">

                        {{ $finalAccount->final_account_no }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Contract Value
                    </div>

                    <div class="fw-semibold mt-1">

                        ₹{{ number_format(
                            (float) $finalAccount->contract_value,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Gross Final Amount
                    </div>

                    <div class="fw-semibold mt-1">

                        ₹{{ number_format(
                            (float) $finalAccount->gross_final_amount,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Balance Payable
                    </div>

                    <div class="fw-bold text-primary fs-5 mt-1">

                        ₹{{ number_format(
                            $approvedAmount,
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         Payment Summary
    ========================================================== --}}

    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Approved Amount
                    </div>

                    <div class="fs-4 fw-bold mt-2">

                        ₹{{ number_format(
                            $approvedAmount,
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Amount Paid
                    </div>

                    <div class="fs-4 fw-bold text-success mt-2">

                        ₹{{ number_format(
                            $amountPaid,
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Balance
                    </div>

                    <div class="fs-4 fw-bold text-warning mt-2">

                        ₹{{ number_format(
                            $balanceAmount,
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         Payment Details
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="card-title mb-1">
                        Payment Details
                    </h5>

                    <div class="text-muted small">
                        {{ $payment->payment_no }}
                    </div>

                </div>


                @if($payment->status === 'Paid')

                    <span class="badge bg-success">
                        Paid
                    </span>

                @elseif($payment->status === 'Approved')

                    <span class="badge bg-primary">
                        Approved
                    </span>

                @elseif($payment->status === 'Rejected')

                    <span class="badge bg-danger">
                        Rejected
                    </span>

                @elseif($payment->status === 'Under Review')

                    <span class="badge bg-info">
                        Under Review
                    </span>

                @elseif($payment->status === 'Submitted')

                    <span class="badge bg-warning text-dark">
                        Submitted
                    </span>

                @else

                    <span class="badge bg-secondary">
                        {{ $payment->status }}
                    </span>

                @endif

            </div>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Approved Amount
                    </div>

                    <div class="fw-bold fs-5 mt-1">

                        ₹{{ number_format(
                            (float) $payment->approved_amount,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Amount Paid
                    </div>

                    <div class="fw-bold fs-5 text-success mt-1">

                        ₹{{ number_format(
                            (float) $payment->amount_paid,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Balance
                    </div>

                    <div class="fw-bold fs-5 text-warning mt-1">

                        ₹{{ number_format(
                            (float) $payment->balance_amount,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Payment Date
                    </div>

                    <div class="mt-1">

                        {{ $payment->payment_date
                            ? $payment->payment_date->format('d M Y')
                            : '-' }}

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Payment Method
                    </div>

                    <div class="mt-1">
                        {{ $payment->payment_method ?: '-' }}
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Transaction Reference
                    </div>

                    <div class="mt-1">

                        {{ $payment->transaction_reference ?: '-' }}

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Prepared By
                    </div>

                    <div class="mt-1">

                        {{ $payment->preparedBy?->name ?? '-' }}

                    </div>

                </div>


                @if($payment->payment_remarks)

                    <div class="col-12">

                        <div class="text-muted small">
                            Payment Remarks
                        </div>

                        <div class="mt-1">

                            {!! nl2br(
                                e($payment->payment_remarks)
                            ) !!}

                        </div>

                    </div>

                @endif


                @if($payment->finance_remarks)

                    <div class="col-12">

                        <div class="text-muted small">
                            Finance Remarks
                        </div>

                        <div class="mt-1">

                            {!! nl2br(
                                e($payment->finance_remarks)
                            ) !!}

                        </div>

                    </div>

                @endif


                @if($payment->remarks)

                    <div class="col-12">

                        <div class="text-muted small">
                            Remarks
                        </div>

                        <div class="mt-1">

                            {!! nl2br(
                                e($payment->remarks)
                            ) !!}

                        </div>

                    </div>

                @endif


                @if($payment->rejection_reason)

                    <div class="col-12">

                        <div class="alert alert-danger mb-0">

                            <strong>
                                Rejection Reason
                            </strong>

                            <div class="mt-1">

                                {!! nl2br(
                                    e($payment->rejection_reason)
                                ) !!}

                            </div>

                        </div>

                    </div>

                @endif


                @if($payment->payment_document_path)

                    <div class="col-12">

                        <a href="{{ asset(
                            'storage/' .
                            $payment->payment_document_path
                        ) }}"
                           target="_blank"
                           class="btn btn-outline-primary btn-sm">

                            <i class="ri-file-line me-1"></i>

                            View Payment Document

                        </a>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- =========================================================
         Payment Preparation
    ========================================================== --}}

    @if(in_array(
        $payment->status,
        [
            'Pending',
            'Prepared',
            'Rejected'
        ],
        true
    ))

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="card-title mb-0">
                    Payment Preparation
                </h5>

            </div>


            <div class="card-body">

                <form method="POST"
                      enctype="multipart/form-data"
                      action="{{ route(
                          'admin.projects.handover.final-payment.update',
                          [
                              $project,
                              $payment
                          ]
                      ) }}">

                    @csrf
                    @method('PUT')


                    <div class="row g-3">


                        <div class="col-md-6">

                            <label class="form-label">
                                Approved Amount
                            </label>

                            <input type="text"
                                   class="form-control"
                                   value="₹{{ number_format(
                                       $approvedAmount,
                                       2
                                   ) }}"
                                   readonly>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">

                                Amount Paid

                                <span class="text-danger">
                                    *
                                </span>

                            </label>

                            <input type="number"
                                   name="amount_paid"
                                   id="amount_paid"
                                   class="form-control"
                                   min="0"
                                   max="{{ $approvedAmount }}"
                                   step="0.01"
                                   value="{{ old(
                                       'amount_paid',
                                       $payment->amount_paid
                                   ) }}"
                                   required>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Balance Amount
                            </label>

                            <input type="text"
                                   id="balance_amount_display"
                                   class="form-control"
                                   value="₹{{ number_format(
                                       $balanceAmount,
                                       2
                                   ) }}"
                                   readonly>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Payment Date
                            </label>

                            <input type="date"
                                   name="payment_date"
                                   class="form-control"
                                   value="{{ old(
                                       'payment_date',
                                       $payment->payment_date?->format('Y-m-d')
                                   ) }}">

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Payment Method
                            </label>

                            <select name="payment_method"
                                    class="form-select">

                                <option value="">
                                    Select Method
                                </option>

                                @foreach([
                                    'Bank Transfer',
                                    'Cheque',
                                    'Online',
                                    'Other'
                                ] as $method)

                                    <option value="{{ $method }}"
                                        @selected(
                                            old(
                                                'payment_method',
                                                $payment->payment_method
                                            ) === $method
                                        )>

                                        {{ $method }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Transaction Reference
                            </label>

                            <input type="text"
                                   name="transaction_reference"
                                   class="form-control"
                                   value="{{ old(
                                       'transaction_reference',
                                       $payment->transaction_reference
                                   ) }}">

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Payment Document
                            </label>

                            <input type="file"
                                   name="payment_document"
                                   class="form-control">

                            <div class="form-text">
                                Maximum file size: 50 MB.
                            </div>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Payment Remarks
                            </label>

                            <textarea name="payment_remarks"
                                      rows="3"
                                      class="form-control">{{ old(
                                          'payment_remarks',
                                          $payment->payment_remarks
                                      ) }}</textarea>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Finance Remarks
                            </label>

                            <textarea name="finance_remarks"
                                      rows="3"
                                      class="form-control">{{ old(
                                          'finance_remarks',
                                          $payment->finance_remarks
                                      ) }}</textarea>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Remarks
                            </label>

                            <textarea name="remarks"
                                      rows="3"
                                      class="form-control">{{ old(
                                          'remarks',
                                          $payment->remarks
                                      ) }}</textarea>

                        </div>


                        <div class="col-12 text-end">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="ri-save-line me-1"></i>

                                Save Payment

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    @endif


    {{-- =========================================================
         Workflow
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="card-title mb-0">
                Workflow Actions
            </h5>

        </div>


        <div class="card-body">

            <div class="d-flex flex-wrap gap-2">


                {{-- Submit --}}

                @if(in_array(
                    $payment->status,
                    [
                        'Prepared',
                        'Rejected'
                    ],
                    true
                ))

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.handover.final-payment.submit',
                              [
                                  $project,
                                  $payment
                              ]
                          ) }}">

                        @csrf

                        <button class="btn btn-primary">

                            <i class="ri-send-plane-line me-1"></i>

                            Submit for Review

                        </button>

                    </form>

                @endif


                {{-- Review --}}

                @if($payment->status === 'Submitted')

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.handover.final-payment.review',
                              [
                                  $project,
                                  $payment
                              ]
                          ) }}">

                        @csrf

                        <button class="btn btn-info">

                            <i class="ri-search-eye-line me-1"></i>

                            Start Review

                        </button>

                    </form>

                @endif


                {{-- Approve --}}

                @if($payment->status === 'Under Review')

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.handover.final-payment.approve',
                              [
                                  $project,
                                  $payment
                              ]
                          ) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-success"
                                onclick="return confirm(
                                    'Approve this Final Payment?'
                                );">

                            <i class="ri-checkbox-circle-line me-1"></i>

                            Approve Payment

                        </button>

                    </form>


                    <button type="button"
                            class="btn btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#rejectPaymentModal">

                        <i class="ri-close-circle-line me-1"></i>

                        Reject

                    </button>

                @endif


                {{-- Paid --}}

                @if($payment->status === 'Approved')

                    <button type="button"
                            class="btn btn-success"
                            data-bs-toggle="modal"
                            data-bs-target="#paidPaymentModal">

                        <i class="ri-money-rupee-circle-line me-1"></i>

                        Mark as Paid

                    </button>

                @endif


                {{-- Cancel --}}

                @if(in_array(
                    $payment->status,
                    [
                        'Pending',
                        'Prepared',
                        'Rejected'
                    ],
                    true
                ))

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.handover.final-payment.cancel',
                              [
                                  $project,
                                  $payment
                              ]
                          ) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-outline-danger"
                                onclick="return confirm(
                                    'Cancel this Final Payment?'
                                );">

                            <i class="ri-forbid-line me-1"></i>

                            Cancel

                        </button>

                    </form>

                @endif


                @if($payment->status === 'Paid')

                    <span class="badge bg-success-subtle text-success p-2">

                        <i class="ri-checkbox-circle-line me-1"></i>

                        Payment Completed

                    </span>

                @endif

            </div>

        </div>

    </div>


    {{-- =========================================================
         Timeline
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="card-title mb-0">
                Payment Timeline
            </h5>

        </div>


        <div class="card-body">


            <div class="timeline">


                <div class="d-flex mb-4">

                    <div class="me-3">

                        <span class="badge bg-secondary rounded-circle p-2">

                            <i class="ri-add-line"></i>

                        </span>

                    </div>


                    <div>

                        <h6 class="mb-1">
                            Payment Created
                        </h6>

                        <small class="text-muted">

                            {{ $payment->created_at?->format(
                                'd M Y, h:i A'
                            ) ?? '-' }}

                        </small>

                    </div>

                </div>


                @if($payment->prepared_at)

                    <div class="d-flex mb-4">

                        <div class="me-3">

                            <span class="badge bg-primary rounded-circle p-2">

                                <i class="ri-file-edit-line"></i>

                            </span>

                        </div>


                        <div>

                            <h6 class="mb-1">
                                Payment Prepared
                            </h6>

                            <small class="text-muted">

                                {{ $payment->prepared_at->format(
                                    'd M Y, h:i A'
                                ) }}

                                —

                                {{ $payment->preparedBy?->name
                                    ?? 'User' }}

                            </small>

                        </div>

                    </div>

                @endif


                @if($payment->submitted_at)

                    <div class="d-flex mb-4">

                        <div class="me-3">

                            <span class="badge bg-info rounded-circle p-2">

                                <i class="ri-send-plane-line"></i>

                            </span>

                        </div>


                        <div>

                            <h6 class="mb-1">
                                Submitted
                            </h6>

                            <small class="text-muted">

                                {{ $payment->submitted_at->format(
                                    'd M Y, h:i A'
                                ) }}

                                —

                                {{ $payment->submittedBy?->name
                                    ?? 'User' }}

                            </small>

                        </div>

                    </div>

                @endif


                @if($payment->reviewed_at)

                    <div class="d-flex mb-4">

                        <div class="me-3">

                            <span class="badge bg-warning text-dark rounded-circle p-2">

                                <i class="ri-search-eye-line"></i>

                            </span>

                        </div>


                        <div>

                            <h6 class="mb-1">
                                Under Review
                            </h6>

                            <small class="text-muted">

                                {{ $payment->reviewed_at->format(
                                    'd M Y, h:i A'
                                ) }}

                                —

                                {{ $payment->reviewedBy?->name
                                    ?? 'User' }}

                            </small>

                        </div>

                    </div>

                @endif


                @if($payment->approved_at)

                    <div class="d-flex mb-4">

                        <div class="me-3">

                            <span class="badge bg-success rounded-circle p-2">

                                <i class="ri-checkbox-circle-line"></i>

                            </span>

                        </div>


                        <div>

                            <h6 class="mb-1">
                                Payment Approved
                            </h6>

                            <small class="text-muted">

                                {{ $payment->approved_at->format(
                                    'd M Y, h:i A'
                                ) }}

                                —

                                {{ $payment->approvedBy?->name
                                    ?? 'User' }}

                            </small>

                        </div>

                    </div>

                @endif


                @if($payment->paid_at)

                    <div class="d-flex">

                        <div class="me-3">

                            <span class="badge bg-success rounded-circle p-2">

                                <i class="ri-money-rupee-circle-line"></i>

                            </span>

                        </div>


                        <div>

                            <h6 class="mb-1">
                                Payment Paid
                            </h6>

                            <small class="text-muted">

                                {{ $payment->paid_at->format(
                                    'd M Y, h:i A'
                                ) }}

                                —

                                {{ $payment->paidBy?->name
                                    ?? 'User' }}

                            </small>

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>


{{-- =============================================================
     Reject Modal
============================================================== --}}

@if($payment->status === 'Under Review')

    <div class="modal fade"
         id="rejectPaymentModal"
         tabindex="-1">

        <div class="modal-dialog">

            <form method="POST"
                  action="{{ route(
                      'admin.projects.handover.final-payment.reject',
                      [
                          $project,
                          $payment
                      ]
                  ) }}">

                @csrf

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title">
                            Reject Final Payment
                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                        </button>

                    </div>


                    <div class="modal-body">

                        <div class="alert alert-warning">

                            Please provide a clear reason for
                            rejecting this payment.

                        </div>


                        <label class="form-label">

                            Rejection Reason

                            <span class="text-danger">
                                *
                            </span>

                        </label>

                        <textarea name="rejection_reason"
                                  class="form-control"
                                  rows="5"
                                  required></textarea>

                    </div>


                    <div class="modal-footer">

                        <button type="button"
                                class="btn btn-light"
                                data-bs-dismiss="modal">

                            Cancel

                        </button>

                        <button type="submit"
                                class="btn btn-danger">

                            Reject Payment

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

@endif


{{-- =============================================================
     Paid Modal
============================================================== --}}

@if($payment->status === 'Approved')

    <div class="modal fade"
         id="paidPaymentModal"
         tabindex="-1">

        <div class="modal-dialog modal-lg">

            <form method="POST"
                  action="{{ route(
                      'admin.projects.handover.final-payment.paid',
                      [
                          $project,
                          $payment
                      ]
                  ) }}">

                @csrf


                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title">
                            Mark Final Payment as Paid
                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                        </button>

                    </div>


                    <div class="modal-body">

                        <div class="alert alert-success">

                            <i class="ri-checkbox-circle-line me-1"></i>

                            This action confirms that the approved
                            Final Payment has actually been settled.

                        </div>


                        <div class="row g-3">


                            <div class="col-md-6">

                                <label class="form-label">
                                    Approved Amount
                                </label>

                                <input type="text"
                                       class="form-control"
                                       value="₹{{ number_format(
                                           (float) $payment->approved_amount,
                                           2
                                       ) }}"
                                       readonly>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">

                                    Amount Paid

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>

                                <input type="number"
                                       name="amount_paid"
                                       class="form-control"
                                       value="{{ number_format(
                                           (float) $payment->approved_amount,
                                           2,
                                           '.',
                                           ''
                                       ) }}"
                                       min="0"
                                       step="0.01"
                                       required>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">

                                    Payment Date

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>

                                <input type="date"
                                       name="payment_date"
                                       class="form-control"
                                       value="{{ now()->format(
                                           'Y-m-d'
                                       ) }}"
                                       required>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">

                                    Payment Method

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>

                                <select name="payment_method"
                                        class="form-select"
                                        required>

                                    <option value="">
                                        Select Method
                                    </option>

                                    <option value="Bank Transfer">
                                        Bank Transfer
                                    </option>

                                    <option value="Cheque">
                                        Cheque
                                    </option>

                                    <option value="Online">
                                        Online
                                    </option>

                                    <option value="Other">
                                        Other
                                    </option>

                                </select>

                            </div>


                            <div class="col-12">

                                <label class="form-label">

                                    Transaction Reference

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>

                                <input type="text"
                                       name="transaction_reference"
                                       class="form-control"
                                       placeholder="Enter bank / cheque / transaction reference"
                                       required>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Payment Remarks
                                </label>

                                <textarea name="payment_remarks"
                                          rows="3"
                                          class="form-control"></textarea>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Finance Remarks
                                </label>

                                <textarea name="finance_remarks"
                                          rows="3"
                                          class="form-control"></textarea>

                            </div>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button"
                                class="btn btn-light"
                                data-bs-dismiss="modal">

                            Cancel

                        </button>


                        <button type="submit"
                                class="btn btn-success"
                                onclick="return confirm(
                                    'Confirm that this Final Payment has been paid?'
                                );">

                            <i class="ri-money-rupee-circle-line me-1"></i>

                            Confirm Payment

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

@endif


@endsection


@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const approvedAmount =
            {{ (float) $approvedAmount }};

        const amountPaidInput =
            document.getElementById(
                'amount_paid'
            );

        const balanceDisplay =
            document.getElementById(
                'balance_amount_display'
            );


        if (
            amountPaidInput &&
            balanceDisplay
        ) {

            function calculateBalance() {

                let amountPaid =
                    parseFloat(
                        amountPaidInput.value
                    ) || 0;


                if (amountPaid < 0) {
                    amountPaid = 0;
                }


                if (
                    amountPaid >
                    approvedAmount
                ) {

                    amountPaid =
                        approvedAmount;

                    amountPaidInput.value =
                        approvedAmount.toFixed(2);
                }


                const balance =
                    Math.max(
                        0,
                        approvedAmount -
                        amountPaid
                    );


                balanceDisplay.value =
                    '₹' +
                    balance.toLocaleString(
                        'en-IN',
                        {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }
                    );
            }


            amountPaidInput.addEventListener(
                'input',
                calculateBalance
            );


            calculateBalance();

        }

    }
);

</script>

@endpush