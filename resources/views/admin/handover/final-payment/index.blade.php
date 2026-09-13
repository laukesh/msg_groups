@extends('layouts.app')

@section('title', 'Final Payment')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         Header
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Final Payment
            </h4>

            <div class="text-muted">
                {{ $project->project_code ?? '' }}

                @if($project->project_name)
                    - {{ $project->project_name }}
                @endif
            </div>

            <div class="small text-muted mt-1">
                Contract-wise financial closeout
            </div>

        </div>


        <a href="{{ route(
            'admin.projects.handover.index',
            $project
        ) }}"
           class="btn btn-light border">

            <i class="ri-arrow-left-line me-1"></i>

            Back to Handover

        </a>

    </div>


    {{-- =========================================================
         Alerts
    ========================================================== --}}

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
         Financial Closeout Banner
    ========================================================== --}}

    @if($financialCloseoutComplete)

        <div class="alert alert-success border-success mb-4">

            <div class="d-flex align-items-center">

                <i class="ri-checkbox-circle-fill fs-3 me-3"></i>

                <div>

                    <h6 class="mb-1">
                        Financial Closeout Completed
                    </h6>

                    <div class="small">
                        All Final Accounts are approved and all
                        Final Payments have been fully paid.
                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
         KPI Cards
    ========================================================== --}}

    <div class="row g-3 mb-4">


        {{-- Final Accounts --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Final Accounts
                            </div>

                            <h4 class="mt-2 mb-0">
                                {{ $totalFinalAccounts }}
                            </h4>

                            <div class="small text-muted mt-1">
                                {{ $approvedFinalAccounts }}
                                approved
                            </div>

                        </div>

                        <div class="avatar-sm bg-primary-subtle rounded">

                            <div class="avatar-title text-primary fs-4">

                                <i class="ri-file-list-3-line"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Approved Amount --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Approved Amount
                            </div>

                            <h4 class="mt-2 mb-0">

                                ₹{{ number_format(
                                    $totalApprovedAmount,
                                    2
                                ) }}

                            </h4>

                        </div>

                        <div class="avatar-sm bg-info-subtle rounded">

                            <div class="avatar-title text-info fs-4">

                                <i class="ri-money-rupee-circle-line"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Paid --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Total Paid
                            </div>

                            <h4 class="mt-2 mb-0 text-success">

                                ₹{{ number_format(
                                    $totalPaymentAmount,
                                    2
                                ) }}

                            </h4>

                            <div class="small text-muted mt-1">

                                {{ $paymentsPaid }}
                                completed

                            </div>

                        </div>

                        <div class="avatar-sm bg-success-subtle rounded">

                            <div class="avatar-title text-success fs-4">

                                <i class="ri-checkbox-circle-line"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Balance --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Balance Payable
                            </div>

                            <h4 class="mt-2 mb-0 text-warning">

                                ₹{{ number_format(
                                    $totalBalanceAmount,
                                    2
                                ) }}

                            </h4>

                            <div class="small text-muted mt-1">

                                {{ $paymentsPending }}
                                pending

                            </div>

                        </div>

                        <div class="avatar-sm bg-warning-subtle rounded">

                            <div class="avatar-title text-warning fs-4">

                                <i class="ri-wallet-3-line"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         Contract-wise Final Payments
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div>

                <h5 class="card-title mb-1">
                    Contract-wise Final Payments
                </h5>

                <div class="text-muted small">
                    Each Procurement Contract has its own Final
                    Account and Final Payment.
                </div>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Contract
                            </th>

                            <th>
                                Contractor
                            </th>

                            <th>
                                Final Account
                            </th>

                            <th class="text-end">
                                Account Balance
                            </th>

                            <th>
                                Final Payment
                            </th>

                            <th class="text-end">
                                Paid
                            </th>

                            <th class="text-end">
                                Balance
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($finalAccounts as $account)

                        @php

                            $payment = $account->payment;

                            $accountBalance = max(
                                0,
                                (float) $account->balance_payable
                            );

                            $paid = $payment
                                ? max(
                                    0,
                                    (float) $payment->amount_paid
                                )
                                : 0;

                            $balance = $payment
                                ? max(
                                    0,
                                    (float) $payment->balance_amount
                                )
                                : $accountBalance;

                        @endphp


                        <tr>


                            {{-- Contract --}}

                            {{-- Contract --}}
                            <td>
                                <div class="fw-semibold">
                                    {{ $account->procurementContract?->contract_number
                                        ?? 'Contract #' . $account->procurement_contract_id }}
                                </div>

                                @if($account->procurementContract?->contract_title)
                                    <small class="text-muted">
                                        {{ $account->procurementContract->contract_title }}
                                    </small>
                                @endif
                            </td>

                            {{-- Contractor --}}
                            <td>
                                {{ $account->procurementContract?->bidder?->company_name
                                    ?? $account->procurementContract?->bidder_name
                                    ?? '-' }}
                            </td>


                            {{-- Final Account --}}

                            <td>

                                <div class="fw-semibold">

                                    {{ $account->final_account_no }}

                                </div>


                                @if($account->status === 'Approved')

                                    <span class="badge bg-success-subtle text-success">
                                        Approved
                                    </span>

                                @elseif($account->status === 'Rejected')

                                    <span class="badge bg-danger-subtle text-danger">
                                        Rejected
                                    </span>

                                @else

                                    <span class="badge bg-warning-subtle text-warning">
                                        {{ $account->status }}
                                    </span>

                                @endif

                            </td>


                            {{-- Account Balance --}}

                            <td class="text-end fw-semibold">

                                ₹{{ number_format(
                                    $accountBalance,
                                    2
                                ) }}

                            </td>


                            {{-- Payment --}}

                            <td>

                                @if($payment)

                                    <div class="fw-semibold">

                                        {{ $payment->payment_no }}

                                    </div>

                                @else

                                    <span class="text-muted">
                                        Not Created
                                    </span>

                                @endif

                            </td>


                            {{-- Paid --}}

                            <td class="text-end text-success fw-semibold">

                                ₹{{ number_format(
                                    $paid,
                                    2
                                ) }}

                            </td>


                            {{-- Balance --}}

                            <td class="text-end fw-semibold">

                                ₹{{ number_format(
                                    $balance,
                                    2
                                ) }}

                            </td>


                            {{-- Status --}}

                            <td>

                                @if(!$payment)

                                    @if($account->status === 'Approved')

                                        <span class="badge bg-warning text-dark">
                                            Payment Not Created
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Awaiting Account Approval
                                        </span>

                                    @endif


                                @elseif($payment->status === 'Paid')

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

                            </td>


                            {{-- Action --}}

                            <td class="text-end">


                                @if(!$payment)

                                    @if($account->status === 'Approved')

                                        <form method="POST"
                                              action="{{ route(
                                                  'admin.projects.handover.final-payment.store',
                                                  [
                                                      $project,
                                                      $account
                                                  ]
                                              ) }}">

                                            @csrf

                                            <button type="submit"
                                                    class="btn btn-sm btn-primary">

                                                <i class="ri-add-line me-1"></i>

                                                Create Payment

                                            </button>

                                        </form>

                                    @else

                                        <span class="text-muted small">
                                            Awaiting approval
                                        </span>

                                    @endif


                                @else

                                    <a href="{{ route(
                                        'admin.projects.handover.final-payment.show',
                                        [
                                            $project,
                                            $payment
                                        ]
                                    ) }}"
                                       class="btn btn-sm btn-outline-primary">

                                        <i class="ri-eye-line me-1"></i>

                                        Manage

                                    </a>

                                @endif

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="9"
                                class="text-center py-5">

                                <i class="ri-file-list-3-line fs-1 text-muted"></i>

                                <div class="fw-semibold mt-2">
                                    No Final Accounts found
                                </div>

                                <div class="text-muted small">
                                    Final Accounts must be prepared
                                    for the project contracts first.
                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =========================================================
         Financial Closeout Status
    ========================================================== --}}

    <div class="card border-0 shadow-sm mt-4">

        <div class="card-header bg-white">

            <h5 class="card-title mb-0">
                Financial Closeout Status
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Final Accounts
                        </div>

                        <div class="d-flex justify-content-between mt-2">

                            <strong>
                                {{ $approvedFinalAccounts }}
                                / {{ $totalFinalAccounts }}
                            </strong>

                            @if(
                                $totalFinalAccounts > 0
                                &&
                                $approvedFinalAccounts === $totalFinalAccounts
                            )

                                <i class="ri-checkbox-circle-fill text-success fs-4"></i>

                            @else

                                <i class="ri-time-line text-warning fs-4"></i>

                            @endif

                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Final Payments
                        </div>

                        <div class="d-flex justify-content-between mt-2">

                            <strong>
                                {{ $paymentsPaid }}
                                / {{ $totalFinalAccounts }}
                            </strong>

                            @if(
                                $totalFinalAccounts > 0
                                &&
                                $paymentsPaid === $totalFinalAccounts
                            )

                                <i class="ri-checkbox-circle-fill text-success fs-4"></i>

                            @else

                                <i class="ri-time-line text-warning fs-4"></i>

                            @endif

                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Overall Financial Closeout
                        </div>

                        <div class="mt-2">

                            @if($financialCloseoutComplete)

                                <span class="badge bg-success fs-6">
                                    <i class="ri-checkbox-circle-line me-1"></i>
                                    Complete
                                </span>

                            @else

                                <span class="badge bg-warning text-dark fs-6">
                                    <i class="ri-time-line me-1"></i>
                                    In Progress
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


</div>

@endsection