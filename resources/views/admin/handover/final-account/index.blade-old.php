@extends('layouts.app')

@section('content')

@php
    $currencySymbol = function ($currency) {
        return match (strtoupper((string) $currency)) {
            'INR' => '₹',
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'AED' => 'د.إ',
            default => strtoupper((string) $currency),
        };
    };

    $statusBadge = function ($status) {
        return match ($status) {
            'Draft' => 'bg-secondary',
            'Prepared' => 'bg-info',
            'Submitted' => 'bg-primary',
            'Under Review' => 'bg-warning text-dark',
            'Approved' => 'bg-success',
            'Rejected' => 'bg-danger',
            default => 'bg-secondary',
        };
    };
@endphp

<div class="container-fluid">

    {{-- ============================================================
        PAGE HEADER
    ============================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <div class="d-flex align-items-center gap-2 mb-1">

                <a href="{{ route('admin.projects.handover.index', $project) }}"
                   class="btn btn-sm btn-light">
                    <i class="ri-arrow-left-line"></i>
                </a>

                <h4 class="mb-0">
                    Final Account
                </h4>

            </div>

            <div class="text-muted">
                {{ $project->project_name ?? 'Project' }}

                @if(!empty($project->project_code))
                    <span class="mx-1">•</span>
                    {{ $project->project_code }}
                @endif

                <span class="mx-1">•</span>

                Handover:
                <strong>
                    {{ $handover->handover_no }}
                </strong>
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.projects.handover.documents.index', $project) }}"
               class="btn btn-outline-secondary">
                <i class="ri-file-list-3-line me-1"></i>
                Documents
            </a>

            @if($availableContracts->count() > 0)
                <button type="button"
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#createFinalAccountModal">
                    <i class="ri-add-line me-1"></i>
                    Create Final Account
                </button>
            @endif

        </div>

    </div>


    {{-- ============================================================
        ALERTS
    ============================================================= --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="ri-checkbox-circle-line me-1"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="ri-error-warning-line me-1"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>

        </div>
    @endif


    {{-- ============================================================
        INFORMATION ALERT
    ============================================================= --}}
    <div class="alert alert-light border mb-4">

        <div class="d-flex align-items-start">

            <i class="ri-information-line fs-4 text-primary me-3"></i>

            <div>

                <strong>
                    Final Account is system consolidated
                </strong>

                <div class="text-muted small mt-1">

                    Contract Value is taken from Procurement Contract.
                    Approved Variations and Claims are taken from
                    Construction Management. Actual Amount Paid is taken
                    from approved/processed Procurement Contract Payments.

                    These system-derived values cannot be manually edited.

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        KPI CARDS
    ============================================================= --}}
    <div class="row g-3 mb-4">

        {{-- Total Accounts --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <div class="text-muted small">
                                Final Accounts
                            </div>

                            <h3 class="mb-0 mt-1">
                                {{ $totalAccounts }}
                            </h3>
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


        {{-- Contract Value --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Contract Value
                            </div>

                            <h4 class="mb-0 mt-1">
                                {{ number_format($totalContractValue, 2) }}
                            </h4>

                        </div>

                        <div class="avatar-sm bg-info-subtle rounded">

                            <div class="avatar-title text-info fs-4">
                                <i class="ri-contract-line"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Gross Final --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Gross Final Amount
                            </div>

                            <h4 class="mb-0 mt-1">
                                {{ number_format($totalGrossFinalAmount, 2) }}
                            </h4>

                        </div>

                        <div class="avatar-sm bg-success-subtle rounded">

                            <div class="avatar-title text-success fs-4">
                                <i class="ri-calculator-line"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Amount Paid --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Amount Paid
                            </div>

                            <h4 class="mb-0 mt-1">
                                {{ number_format($totalAmountPaid, 2) }}
                            </h4>

                        </div>

                        <div class="avatar-sm bg-warning-subtle rounded">

                            <div class="avatar-title text-warning fs-4">
                                <i class="ri-bank-card-line"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        STATUS SUMMARY
    ============================================================= --}}
    <div class="row g-3 mb-4">

        <div class="col-lg-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <small class="text-muted">Draft</small>
                    <h5 class="mb-0 mt-1">
                        {{ $draftAccounts }}
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <small class="text-muted">Prepared</small>
                    <h5 class="mb-0 mt-1">
                        {{ $preparedAccounts }}
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <small class="text-muted">Submitted</small>
                    <h5 class="mb-0 mt-1">
                        {{ $submittedAccounts }}
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <small class="text-muted">Under Review</small>
                    <h5 class="mb-0 mt-1">
                        {{ $underReviewAccounts }}
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <small class="text-muted">Approved</small>
                    <h5 class="mb-0 mt-1 text-success">
                        {{ $approvedAccounts }}
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <small class="text-muted">Rejected</small>
                    <h5 class="mb-0 mt-1 text-danger">
                        {{ $rejectedAccounts }}
                    </h5>
                </div>
            </div>
        </div>

    </div>


    {{-- ============================================================
        FINANCIAL OVERVIEW
    ============================================================= --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-1">
                        Financial Overview
                    </h5>

                    <small class="text-muted">
                        Consolidated values from Procurement and
                        Construction Management
                    </small>

                </div>

                <div class="text-end">

                    <small class="text-muted d-block">
                        Total Balance Payable
                    </small>

                    <strong class="text-danger fs-5">
                        {{ number_format($totalBalancePayable, 2) }}
                    </strong>

                </div>

            </div>

        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-3">

                    <div class="border rounded p-3">

                        <small class="text-muted d-block">
                            Approved Variations
                        </small>

                        <h5 class="mb-1 mt-1">
                            {{ number_format($totalVariations, 2) }}
                        </h5>

                        <small class="text-success">
                            Construction Management
                        </small>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="border rounded p-3">

                        <small class="text-muted d-block">
                            Approved Claims
                        </small>

                        <h5 class="mb-1 mt-1">
                            {{ number_format($totalClaims, 2) }}
                        </h5>

                        <small class="text-success">
                            Construction Management
                        </small>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="border rounded p-3">

                        <small class="text-muted d-block">
                            Certified Amount
                        </small>

                        <h5 class="mb-1 mt-1">
                            {{ number_format($totalCertifiedAmount, 2) }}
                        </h5>

                        <small class="text-muted">
                            Final Account / Finance
                        </small>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="border rounded p-3">

                        <small class="text-muted d-block">
                            Balance Payable
                        </small>

                        <h5 class="mb-1 mt-1 text-danger">
                            {{ number_format($totalBalancePayable, 2) }}
                        </h5>

                        <small class="text-muted">
                            Gross Final − Paid
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        FINAL ACCOUNT REGISTER
    ============================================================= --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-1">
                        Final Account Register
                    </h5>

                    <small class="text-muted">
                        One Final Account per Procurement Contract
                    </small>

                </div>

                <span class="badge bg-light text-dark">
                    {{ $accounts->count() }} Account(s)
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($accounts->count() > 0)

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="ps-4">
                                    Final Account
                                </th>

                                <th>
                                    Procurement Contract
                                </th>

                                <th>
                                    Contract Value
                                </th>

                                <th>
                                    Variations
                                </th>

                                <th>
                                    Claims
                                </th>

                                <th>
                                    Gross Final
                                </th>

                                <th>
                                    Paid
                                </th>

                                <th>
                                    Balance
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end pe-4">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($accounts as $account)

                                @php

                                    $contract =
                                        $account->procurementContract;

                                    $contractCurrency =
                                        $contract->currency
                                        ?? $contract->contract_currency
                                        ?? '';

                                    $symbol =
                                        $currencySymbol(
                                            $contractCurrency
                                        );

                                    $payments =
                                        $contract?->payments
                                        ?? collect();

                                    $completedPayments =
                                        $payments->whereIn(
                                            'status',
                                            [
                                                'Approved',
                                                'Processed'
                                            ]
                                        );

                                    $paymentCount =
                                        $completedPayments->count();

                                @endphp


                                <tr>

                                    {{-- Final Account --}}
                                    <td class="ps-4">

                                        <div class="fw-semibold">
                                            {{ $account->final_account_no }}
                                        </div>

                                        <small class="text-muted">
                                            @if($account->prepared_date)
                                                Prepared:
                                                {{ $account->prepared_date->format('d M Y') }}
                                            @else
                                                Not prepared
                                            @endif
                                        </small>

                                    </td>


                                    {{-- Contract --}}
                                    <td>

                                        @if($contract)

                                            <div class="fw-semibold">

                                                {{ $contract->contract_no
                                                    ?? $contract->contract_number
                                                    ?? ('Contract #' . $contract->id)
                                                }}

                                            </div>

                                            @if($contract->title)
                                                <small class="text-muted">
                                                    {{ Str::limit(
                                                        $contract->title,
                                                        45
                                                    ) }}
                                                </small>
                                            @elseif($contract->description)
                                                <small class="text-muted">
                                                    {{ Str::limit(
                                                        $contract->description,
                                                        45
                                                    ) }}
                                                </small>
                                            @endif

                                            @if($contract->bidder)
                                                <div class="small text-muted mt-1">
                                                    <i class="ri-building-line"></i>
                                                    {{ $contract->bidder->name
                                                        ?? $contract->bidder->company_name
                                                        ?? 'Bidder'
                                                    }}
                                                </div>
                                            @endif

                                        @else

                                            <span class="text-danger">
                                                Contract not found
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Contract Value --}}
                                    <td>

                                        <div class="fw-semibold">
                                            {{ $symbol }}
                                            {{ number_format(
                                                $account->contract_value,
                                                2
                                            ) }}
                                        </div>

                                        <small class="text-muted">
                                            System sourced
                                        </small>

                                    </td>


                                    {{-- Variations --}}
                                    <td>

                                        <div class="fw-semibold text-success">

                                            {{ $symbol }}
                                            {{ number_format(
                                                $account->approved_variations,
                                                2
                                            ) }}

                                        </div>

                                        <small class="text-muted">
                                            Approved only
                                        </small>

                                    </td>


                                    {{-- Claims --}}
                                    <td>

                                        <div class="fw-semibold text-success">

                                            {{ $symbol }}
                                            {{ number_format(
                                                $account->approved_claims,
                                                2
                                            ) }}

                                        </div>

                                        <small class="text-muted">
                                            Approved amount
                                        </small>

                                    </td>


                                    {{-- Gross Final --}}
                                    <td>

                                        <div class="fw-semibold">

                                            {{ $symbol }}
                                            {{ number_format(
                                                $account->gross_final_amount,
                                                2
                                            ) }}

                                        </div>

                                        <small class="text-muted">
                                            Calculated
                                        </small>

                                    </td>


                                    {{-- Paid --}}
                                    <td>

                                        <div class="fw-semibold text-primary">

                                            {{ $symbol }}
                                            {{ number_format(
                                                $account->amount_paid,
                                                2
                                            ) }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $paymentCount }}
                                            {{ Str::plural(
                                                'payment',
                                                $paymentCount
                                            ) }}

                                        </small>

                                    </td>


                                    {{-- Balance --}}
                                    <td>

                                        <div class="fw-semibold
                                            {{ $account->balance_payable > 0
                                                ? 'text-danger'
                                                : 'text-success'
                                            }}">

                                            {{ $symbol }}
                                            {{ number_format(
                                                $account->balance_payable,
                                                2
                                            ) }}

                                        </div>

                                        @if($account->balance_payable <= 0)

                                            <span class="badge bg-success-subtle text-success">
                                                Fully Paid
                                            </span>

                                        @else

                                            <span class="badge bg-warning-subtle text-warning">
                                                Outstanding
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        <span class="badge {{ $statusBadge($account->status) }}">

                                            {{ $account->status }}

                                        </span>

                                        @if($account->status === 'Rejected'
                                            && $account->rejection_reason)

                                            <div class="small text-danger mt-1"
                                                 title="{{ $account->rejection_reason }}">

                                                <i class="ri-information-line"></i>

                                                {{ Str::limit(
                                                    $account->rejection_reason,
                                                    30
                                                ) }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-end pe-4">

                                        <div class="dropdown">

                                            <button
                                                class="btn btn-sm btn-light"
                                                type="button"
                                                data-bs-toggle="dropdown">

                                                <i class="ri-more-2-fill"></i>

                                            </button>


                                            <ul class="dropdown-menu dropdown-menu-end">

                                                {{-- View --}}
                                                <li>

                                                    <button
                                                        type="button"
                                                        class="dropdown-item"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewAccountModal{{ $account->id }}">

                                                        <i class="ri-eye-line me-2"></i>
                                                        View Financial Details

                                                    </button>

                                                </li>


                                                {{-- Payment History --}}
                                                <li>

                                                    <button
                                                        type="button"
                                                        class="dropdown-item"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#paymentHistoryModal{{ $account->id }}">

                                                        <i class="ri-bank-card-line me-2"></i>
                                                        Payment History

                                                    </button>

                                                </li>


                                                @if(in_array(
                                                    $account->status,
                                                    [
                                                        'Draft',
                                                        'Prepared',
                                                        'Rejected'
                                                    ]
                                                ))

                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>

                                                    {{-- Edit --}}
                                                    <li>

                                                        <button
                                                            type="button"
                                                            class="dropdown-item"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editAccountModal{{ $account->id }}">

                                                            <i class="ri-edit-line me-2"></i>
                                                            Edit Finance Details

                                                        </button>

                                                    </li>

                                                @endif


                                                @if(in_array(
                                                    $account->status,
                                                    [
                                                        'Prepared',
                                                        'Rejected'
                                                    ]
                                                ))

                                                    <li>

                                                        <form
                                                            method="POST"
                                                            action="{{ route(
                                                                'admin.projects.handover.final-account.submit',
                                                                [
                                                                    $project,
                                                                    $account
                                                                ]
                                                            ) }}"
                                                            onsubmit="return confirm('Submit this Final Account for review?');">

                                                            @csrf

                                                            <button
                                                                type="submit"
                                                                class="dropdown-item">

                                                                <i class="ri-send-plane-line me-2"></i>
                                                                Submit for Review

                                                            </button>

                                                        </form>

                                                    </li>

                                                @endif


                                                @if($account->status === 'Submitted')

                                                    <li>

                                                        <form
                                                            method="POST"
                                                            action="{{ route(
                                                                'admin.projects.handover.final-account.review',
                                                                [
                                                                    $project,
                                                                    $account
                                                                ]
                                                            ) }}">

                                                            @csrf

                                                            <button
                                                                type="submit"
                                                                class="dropdown-item">

                                                                <i class="ri-search-eye-line me-2"></i>
                                                                Start Review

                                                            </button>

                                                        </form>

                                                    </li>

                                                @endif


                                                @if($account->status === 'Under Review')

                                                    <li>

                                                        <form
                                                            method="POST"
                                                            action="{{ route(
                                                                'admin.projects.handover.final-account.approve',
                                                                [
                                                                    $project,
                                                                    $account
                                                                ]
                                                            ) }}"
                                                            onsubmit="return confirm('Approve this Final Account?');">

                                                            @csrf

                                                            <button
                                                                type="submit"
                                                                class="dropdown-item text-success">

                                                                <i class="ri-checkbox-circle-line me-2"></i>
                                                                Approve

                                                            </button>

                                                        </form>

                                                    </li>


                                                    <li>

                                                        <button
                                                            type="button"
                                                            class="dropdown-item text-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#rejectAccountModal{{ $account->id }}">

                                                            <i class="ri-close-circle-line me-2"></i>
                                                            Reject

                                                        </button>

                                                    </li>

                                                @endif

                                            </ul>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                {{-- Empty State --}}
                <div class="text-center py-5">

                    <div class="mb-3">

                        <i class="ri-file-list-3-line display-4 text-muted"></i>

                    </div>

                    <h5>
                        No Final Accounts Created
                    </h5>

                    <p class="text-muted mb-4">

                        Create a Final Account for each Procurement Contract
                        that requires financial closeout.

                    </p>

                    @if($availableContracts->count() > 0)

                        <button
                            type="button"
                            class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#createFinalAccountModal">

                            <i class="ri-add-line me-1"></i>
                            Create Final Account

                        </button>

                    @endif

                </div>

            @endif

        </div>

    </div>


    {{-- ============================================================
        ACCOUNT DETAILS MODALS
    ============================================================= --}}
    @foreach($accounts as $account)

        @php

            $contract =
                $account->procurementContract;

            $contractCurrency =
                $contract->currency
                ?? $contract->contract_currency
                ?? '';

            $symbol =
                $currencySymbol(
                    $contractCurrency
                );

            $payments =
                $contract?->payments
                ?? collect();

            $completedPayments =
                $payments->whereIn(
                    'status',
                    [
                        'Approved',
                        'Processed'
                    ]
                );

        @endphp


        {{-- ========================================================
            VIEW FINANCIAL DETAILS
        ========================================================= --}}
        <div
            class="modal fade"
            id="viewAccountModal{{ $account->id }}"
            tabindex="-1">

            <div class="modal-dialog modal-xl modal-dialog-scrollable">

                <div class="modal-content">

                    <div class="modal-header">

                        <div>

                            <h5 class="modal-title mb-1">

                                {{ $account->final_account_no }}

                            </h5>

                            <small class="text-muted">

                                {{ $contract?->contract_no
                                    ?? $contract?->contract_number
                                    ?? 'Procurement Contract'
                                }}

                            </small>

                        </div>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                        </button>

                    </div>


                    <div class="modal-body">

                        {{-- Financial Summary --}}
                        <div class="row g-3 mb-4">

                            <div class="col-md-3">

                                <div class="border rounded p-3">

                                    <small class="text-muted">
                                        Contract Value
                                    </small>

                                    <h5 class="mb-0 mt-1">

                                        {{ $symbol }}
                                        {{ number_format(
                                            $account->contract_value,
                                            2
                                        ) }}

                                    </h5>

                                </div>

                            </div>


                            <div class="col-md-3">

                                <div class="border rounded p-3">

                                    <small class="text-muted">
                                        Approved Variations
                                    </small>

                                    <h5 class="mb-0 mt-1 text-success">

                                        {{ $symbol }}
                                        {{ number_format(
                                            $account->approved_variations,
                                            2
                                        ) }}

                                    </h5>

                                </div>

                            </div>


                            <div class="col-md-3">

                                <div class="border rounded p-3">

                                    <small class="text-muted">
                                        Approved Claims
                                    </small>

                                    <h5 class="mb-0 mt-1 text-success">

                                        {{ $symbol }}
                                        {{ number_format(
                                            $account->approved_claims,
                                            2
                                        ) }}

                                    </h5>

                                </div>

                            </div>


                            <div class="col-md-3">

                                <div class="border rounded p-3">

                                    <small class="text-muted">
                                        Gross Final Amount
                                    </small>

                                    <h5 class="mb-0 mt-1">

                                        {{ $symbol }}
                                        {{ number_format(
                                            $account->gross_final_amount,
                                            2
                                        ) }}

                                    </h5>

                                </div>

                            </div>


                            <div class="col-md-4">

                                <div class="border rounded p-3">

                                    <small class="text-muted">
                                        Amount Paid
                                    </small>

                                    <h5 class="mb-0 mt-1 text-primary">

                                        {{ $symbol }}
                                        {{ number_format(
                                            $account->amount_paid,
                                            2
                                        ) }}

                                    </h5>

                                    <small class="text-muted">

                                        {{ $completedPayments->count() }}
                                        completed payment(s)

                                    </small>

                                </div>

                            </div>


                            <div class="col-md-4">

                                <div class="border rounded p-3">

                                    <small class="text-muted">
                                        Certified Amount
                                    </small>

                                    <h5 class="mb-0 mt-1">

                                        {{ $symbol }}
                                        {{ number_format(
                                            $account->certified_amount,
                                            2
                                        ) }}

                                    </h5>

                                </div>

                            </div>


                            <div class="col-md-4">

                                <div class="border rounded p-3">

                                    <small class="text-muted">
                                        Balance Payable
                                    </small>

                                    <h5 class="mb-0 mt-1
                                        {{ $account->balance_payable > 0
                                            ? 'text-danger'
                                            : 'text-success'
                                        }}">

                                        {{ $symbol }}
                                        {{ number_format(
                                            $account->balance_payable,
                                            2
                                        ) }}

                                    </h5>

                                </div>

                            </div>

                        </div>


                        {{-- Adjustments --}}
                        <h6 class="mb-3">
                            Finance Adjustments
                        </h6>

                        <div class="table-responsive">

                            <table class="table table-sm table-bordered">

                                <tbody>

                                    <tr>
                                        <td>
                                            Advance Payment
                                        </td>
                                        <td class="text-end">
                                            {{ $symbol }}
                                            {{ number_format(
                                                $account->advance_payment,
                                                2
                                            ) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            Advance Recovery
                                        </td>
                                        <td class="text-end">
                                            {{ $symbol }}
                                            {{ number_format(
                                                $account->advance_recovery,
                                                2
                                            ) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            Retention
                                        </td>
                                        <td class="text-end">
                                            {{ $symbol }}
                                            {{ number_format(
                                                $account->retention_amount,
                                                2
                                            ) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            Deductions
                                        </td>
                                        <td class="text-end">
                                            {{ $symbol }}
                                            {{ number_format(
                                                $account->deductions,
                                                2
                                            ) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            Other Adjustments
                                        </td>
                                        <td class="text-end">
                                            {{ $symbol }}
                                            {{ number_format(
                                                $account->other_adjustments,
                                                2
                                            ) }}
                                        </td>
                                    </tr>

                                </tbody>

                            </table>

                        </div>


                        @if($account->contractor_statement)

                            <div class="mt-4">

                                <h6>
                                    Contractor Statement
                                </h6>

                                <div class="border rounded p-3 bg-light">

                                    {!! nl2br(
                                        e($account->contractor_statement)
                                    ) !!}

                                </div>

                            </div>

                        @endif


                        @if($account->finance_remarks)

                            <div class="mt-4">

                                <h6>
                                    Finance Remarks
                                </h6>

                                <div class="border rounded p-3 bg-light">

                                    {!! nl2br(
                                        e($account->finance_remarks)
                                    ) !!}

                                </div>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
            PAYMENT HISTORY MODAL
        ========================================================= --}}
        <div
            class="modal fade"
            id="paymentHistoryModal{{ $account->id }}"
            tabindex="-1">

            <div class="modal-dialog modal-xl modal-dialog-scrollable">

                <div class="modal-content">

                    <div class="modal-header">

                        <div>

                            <h5 class="modal-title">
                                Payment History
                            </h5>

                            <small class="text-muted">

                                {{ $account->final_account_no }}

                                @if($contract)
                                    •
                                    {{ $contract->contract_no
                                        ?? $contract->contract_number
                                        ?? ('Contract #' . $contract->id)
                                    }}
                                @endif

                            </small>

                        </div>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                        </button>

                    </div>


                    <div class="modal-body p-0">

                        <div class="alert alert-light border rounded-0 mb-0">

                            <div class="d-flex justify-content-between">

                                <div>

                                    <small class="text-muted">
                                        Included in Amount Paid
                                    </small>

                                    <h5 class="mb-0">

                                        {{ $symbol }}
                                        {{ number_format(
                                            $account->amount_paid,
                                            2
                                        ) }}

                                    </h5>

                                </div>

                                <div class="text-end">

                                    <small class="text-muted">
                                        Completed Payments
                                    </small>

                                    <h5 class="mb-0">

                                        {{ $completedPayments->count() }}

                                    </h5>

                                </div>

                            </div>

                        </div>


                        <div class="table-responsive">

                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th class="ps-4">
                                            Payment No.
                                        </th>

                                        <th>
                                            Date
                                        </th>

                                        <th>
                                            Type
                                        </th>

                                        <th>
                                            Method
                                        </th>

                                        <th>
                                            Transaction
                                        </th>

                                        <th>
                                            Bank
                                        </th>

                                        <th class="text-end">
                                            Amount
                                        </th>

                                        <th>
                                            Status
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @forelse(
                                        $completedPayments
                                        as $payment
                                    )

                                        <tr>

                                            <td class="ps-4">

                                                <strong>
                                                    {{ $payment->payment_number }}
                                                </strong>

                                            </td>

                                            <td>

                                                @if($payment->payment_date)

                                                    {{ $payment->payment_date->format(
                                                        'd M Y'
                                                    ) }}

                                                @else

                                                    -

                                                @endif

                                            </td>

                                            <td>
                                                {{ $payment->payment_type ?: '-' }}
                                            </td>

                                            <td>
                                                {{ $payment->payment_method ?: '-' }}
                                            </td>

                                            <td>

                                                {{ $payment->transaction_reference
                                                    ?: '-'
                                                }}

                                            </td>

                                            <td>

                                                {{ $payment->bank_name ?: '-' }}

                                            </td>

                                            <td class="text-end">

                                                <strong>

                                                    {{ $payment->currency }}

                                                    {{ number_format(
                                                        $payment->amount,
                                                        2
                                                    ) }}

                                                </strong>

                                            </td>

                                            <td>

                                                @if($payment->status === 'Processed')

                                                    <span class="badge bg-success">
                                                        Processed
                                                    </span>

                                                @elseif($payment->status === 'Approved')

                                                    <span class="badge bg-primary">
                                                        Approved
                                                    </span>

                                                @endif

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td
                                                colspan="8"
                                                class="text-center py-5 text-muted">

                                                <i class="ri-bank-card-line fs-3 d-block mb-2"></i>

                                                No approved or processed
                                                payments found for this contract.

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
            EDIT FINANCE MODAL
        ========================================================= --}}
        @if(in_array(
            $account->status,
            [
                'Draft',
                'Prepared',
                'Rejected'
            ]
        ))

            <div
                class="modal fade final-account-edit-modal"
                id="editAccountModal{{ $account->id }}"
                tabindex="-1">

                <div class="modal-dialog modal-lg modal-dialog-scrollable">

                    <div class="modal-content">

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.handover.final-account.update',
                                [
                                    $project,
                                    $account
                                ]
                            ) }}">

                            @csrf
                            @method('PUT')


                            <div class="modal-header">

                                <div>

                                    <h5 class="modal-title">
                                        Edit Finance Details
                                    </h5>

                                    <small class="text-muted">
                                        {{ $account->final_account_no }}
                                    </small>

                                </div>

                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal">
                                </button>

                            </div>


                            <div class="modal-body">

                                {{-- System values --}}
                                <div class="alert alert-info">

                                    <i class="ri-information-line me-1"></i>

                                    Contract Value, Approved Variations,
                                    Approved Claims and Amount Paid are
                                    automatically sourced and cannot be edited here.

                                </div>


                                <div class="row g-3 mb-4">

                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Contract Value
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text">
                                                {{ $symbol }}
                                            </span>

                                            <input
                                                type="text"
                                                class="form-control"
                                                value="{{ number_format(
                                                    $account->contract_value,
                                                    2,
                                                    '.',
                                                    ''
                                                ) }}"
                                                readonly>

                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Amount Paid
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text">
                                                {{ $symbol }}
                                            </span>

                                            <input
                                                type="text"
                                                class="form-control"
                                                value="{{ number_format(
                                                    $account->amount_paid,
                                                    2,
                                                    '.',
                                                    ''
                                                ) }}"
                                                readonly>

                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Approved Variations
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text">
                                                {{ $symbol }}
                                            </span>

                                            <input
                                                type="text"
                                                class="form-control"
                                                value="{{ number_format(
                                                    $account->approved_variations,
                                                    2,
                                                    '.',
                                                    ''
                                                ) }}"
                                                readonly>

                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Approved Claims
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text">
                                                {{ $symbol }}
                                            </span>

                                            <input
                                                type="text"
                                                class="form-control"
                                                value="{{ number_format(
                                                    $account->approved_claims,
                                                    2,
                                                    '.',
                                                    ''
                                                ) }}"
                                                readonly>

                                        </div>

                                    </div>

                                </div>


                                <hr>


                                <h6 class="mb-3">
                                    Finance Adjustments
                                </h6>


                                <div class="row g-3">

                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Advance Payment
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text">
                                                {{ $symbol }}
                                            </span>

                                            <input
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                name="advance_payment"
                                                class="form-control"
                                                value="{{ old(
                                                    'advance_payment',
                                                    $account->advance_payment
                                                ) }}">

                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Advance Recovery
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text">
                                                {{ $symbol }}
                                            </span>

                                            <input
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                name="advance_recovery"
                                                class="form-control"
                                                value="{{ old(
                                                    'advance_recovery',
                                                    $account->advance_recovery
                                                ) }}">

                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Retention Amount
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text">
                                                {{ $symbol }}
                                            </span>

                                            <input
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                name="retention_amount"
                                                class="form-control"
                                                value="{{ old(
                                                    'retention_amount',
                                                    $account->retention_amount
                                                ) }}">

                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Deductions
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text">
                                                {{ $symbol }}
                                            </span>

                                            <input
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                name="deductions"
                                                class="form-control"
                                                value="{{ old(
                                                    'deductions',
                                                    $account->deductions
                                                ) }}">

                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Other Adjustments
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text">
                                                {{ $symbol }}
                                            </span>

                                            <input
                                                type="number"
                                                step="0.01"
                                                name="other_adjustments"
                                                class="form-control"
                                                value="{{ old(
                                                    'other_adjustments',
                                                    $account->other_adjustments
                                                ) }}">

                                        </div>

                                        <small class="text-muted">
                                            Positive or negative adjustment.
                                        </small>

                                    </div>


                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Certified Amount
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text">
                                                {{ $symbol }}
                                            </span>

                                            <input
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                name="certified_amount"
                                                class="form-control"
                                                value="{{ old(
                                                    'certified_amount',
                                                    $account->certified_amount
                                                ) }}">

                                        </div>

                                    </div>


                                    <div class="col-12">

                                        <label class="form-label">
                                            Contractor Statement
                                        </label>

                                        <textarea
                                            name="contractor_statement"
                                            class="form-control"
                                            rows="3"
                                            placeholder="Enter contractor final account statement...">{{ old(
                                                'contractor_statement',
                                                $account->contractor_statement
                                            ) }}</textarea>

                                    </div>


                                    <div class="col-12">

                                        <label class="form-label">
                                            Finance Remarks
                                        </label>

                                        <textarea
                                            name="finance_remarks"
                                            class="form-control"
                                            rows="3"
                                            placeholder="Enter finance remarks...">{{ old(
                                                'finance_remarks',
                                                $account->finance_remarks
                                            ) }}</textarea>

                                    </div>


                                    <div class="col-12">

                                        <label class="form-label">
                                            Remarks
                                        </label>

                                        <textarea
                                            name="remarks"
                                            class="form-control"
                                            rows="3"
                                            placeholder="Additional remarks...">{{ old(
                                                'remarks',
                                                $account->remarks
                                            ) }}</textarea>

                                    </div>

                                </div>

                            </div>


                            <div class="modal-footer">

                                <button
                                    type="button"
                                    class="btn btn-light"
                                    data-bs-dismiss="modal">

                                    Cancel

                                </button>

                                <button
                                    type="submit"
                                    class="btn btn-primary">

                                    <i class="ri-save-line me-1"></i>
                                    Save Finance Details

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        @endif


        {{-- ========================================================
            REJECT MODAL
        ========================================================= --}}
        @if($account->status === 'Under Review')

            <div
                class="modal fade"
                id="rejectAccountModal{{ $account->id }}"
                tabindex="-1">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.handover.final-account.reject',
                                [
                                    $project,
                                    $account
                                ]
                            ) }}">

                            @csrf


                            <div class="modal-header">

                                <h5 class="modal-title text-danger">

                                    Reject Final Account

                                </h5>

                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal">
                                </button>

                            </div>


                            <div class="modal-body">

                                <div class="alert alert-warning">

                                    The Final Account will return to
                                    <strong>Rejected</strong> status and can
                                    then be corrected and resubmitted.

                                </div>


                                <div class="mb-3">

                                    <label class="form-label">
                                        Rejection Reason
                                        <span class="text-danger">*</span>
                                    </label>

                                    <textarea
                                        name="rejection_reason"
                                        class="form-control"
                                        rows="4"
                                        required
                                        placeholder="Enter reason for rejection..."></textarea>

                                </div>

                            </div>


                            <div class="modal-footer">

                                <button
                                    type="button"
                                    class="btn btn-light"
                                    data-bs-dismiss="modal">

                                    Cancel

                                </button>

                                <button
                                    type="submit"
                                    class="btn btn-danger">

                                    <i class="ri-close-circle-line me-1"></i>
                                    Reject Final Account

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        @endif

    @endforeach


    {{-- ============================================================
        CREATE FINAL ACCOUNT MODAL
    ============================================================= --}}
    <div
        class="modal fade"
        id="createFinalAccountModal"
        tabindex="-1">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.handover.final-account.store',
                        $project
                    ) }}">

                    @csrf


                    <div class="modal-header">

                        <div>

                            <h5 class="modal-title">
                                Create Final Account
                            </h5>

                            <small class="text-muted">
                                Select a Procurement Contract
                            </small>

                        </div>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                        </button>

                    </div>


                    <div class="modal-body">

                        <div class="alert alert-info">

                            <i class="ri-information-line me-1"></i>

                            A separate Final Account will be created for
                            each Procurement Contract.

                        </div>


                        @if($availableContracts->count() > 0)

                            <div class="mb-3">

                                <label class="form-label">
                                    Procurement Contract
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="procurement_contract_id"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        Select Procurement Contract
                                    </option>

                                    @foreach(
                                        $availableContracts
                                        as $contract
                                    )

                                        @php

                                            $contractCurrency =
                                                $contract->currency
                                                ?? $contract->contract_currency
                                                ?? '';

                                            $contractValue =
                                                $contract->contract_value
                                                ?? $contract->contract_amount
                                                ?? $contract->total_amount
                                                ?? $contract->amount
                                                ?? 0;

                                        @endphp

                                        <option
                                            value="{{ $contract->id }}">

                                            {{ $contract->contract_no
                                                ?? $contract->contract_number
                                                ?? ('Contract #' . $contract->id)
                                            }}

                                            @if($contract->title)
                                                -
                                                {{ Str::limit(
                                                    $contract->title,
                                                    60
                                                ) }}
                                            @endif

                                            @if($contractValue)
                                                |
                                                {{ $contractCurrency }}
                                                {{ number_format(
                                                    $contractValue,
                                                    2
                                                ) }}
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            <div class="border rounded p-3 bg-light">

                                <div class="row g-3">

                                    <div class="col-md-4">

                                        <small class="text-muted d-block">
                                            Contract Value
                                        </small>

                                        <strong>
                                            Auto sourced
                                        </strong>

                                    </div>

                                    <div class="col-md-4">

                                        <small class="text-muted d-block">
                                            Variations
                                        </small>

                                        <strong>
                                            Auto sourced
                                        </strong>

                                    </div>

                                    <div class="col-md-4">

                                        <small class="text-muted d-block">
                                            Claims & Payments
                                        </small>

                                        <strong>
                                            Auto sourced
                                        </strong>

                                    </div>

                                </div>

                            </div>

                        @else

                            <div class="text-center py-4">

                                <i class="ri-checkbox-circle-line fs-1 text-success"></i>

                                <h5 class="mt-3">
                                    All Contracts Have Final Accounts
                                </h5>

                                <p class="text-muted mb-0">

                                    A Final Account already exists for every
                                    Procurement Contract associated with
                                    this project.

                                </p>

                            </div>

                        @endif

                    </div>


                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">

                            Cancel

                        </button>

                        @if($availableContracts->count() > 0)

                            <button
                                type="submit"
                                class="btn btn-primary">

                                <i class="ri-add-line me-1"></i>
                                Create Final Account

                            </button>

                        @endif

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>


<style>
    /* ================================================================
       FINAL ACCOUNT EDIT MODAL
       Keep header/footer visible; scroll only the modal body.
    ================================================================= */
    .final-account-edit-modal .modal-dialog {
        height: calc(100vh - 30px);
        max-height: calc(100vh - 30px);
        margin-top: 15px;
        margin-bottom: 15px;
    }

    .final-account-edit-modal .modal-content {
        height: 100%;
        max-height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .final-account-edit-modal .modal-header {
        flex: 0 0 auto;
    }

    .final-account-edit-modal .modal-body {
        flex: 1 1 auto;
        min-height: 0;
        overflow-y: auto !important;
        overflow-x: hidden;
    }

    .final-account-edit-modal .modal-footer {
        flex: 0 0 auto;
        background: #fff;
        border-top: 1px solid #dee2e6;
        position: relative;
        z-index: 10;
    }

    @media (max-width: 767.98px) {
        .final-account-edit-modal .modal-dialog {
            height: calc(100vh - 10px);
            max-height: calc(100vh - 10px);
            margin: 5px;
        }
    }
</style>


@endsection