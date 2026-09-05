@extends('layouts.app')

@section('title', 'Tenant Details')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                {{ $tenant->company_name }}
            </h4>

            <p class="text-muted mb-0">

                Tenant Code:
                <strong>{{ $tenant->tenant_code }}</strong>

            </p>

        </div>

        <!-- <div class="d-flex gap-2">

            <a href="{{ route('admin.tenants.index') }}"
               class="btn btn-outline-secondary">

                <i class="fas fa-arrow-left me-1"></i>

                Back

            </a>

            <a href="{{ route(
                'admin.tenants.edit',
                $tenant->id
            ) }}"
               class="btn btn-warning">

                <i class="fas fa-edit me-1"></i>

                Edit Tenant

            </a>

        </div> -->

        <div class="d-flex gap-2">

            <a href="{{ route('admin.tenants.index') }}"
               class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Back
            </a>

            <a href="{{ route('admin.tenants.edit', $tenant->id) }}"
               class="btn btn-warning">
                <i class="fas fa-edit me-1"></i>
                Edit
            </a>

            <a href="{{ route(
                'admin.tenants.contacts.index',
                $tenant->id
            ) }}"
               class="btn btn-primary">

                <i class="fas fa-user-plus me-1"></i>
                Add Contact

            </a>

            <div class="dropdown">

                <button
                    class="btn btn-outline-secondary dropdown-toggle"
                    type="button"
                    data-bs-toggle="dropdown"
                >
                    More
                </button>

                <ul class="dropdown-menu dropdown-menu-end" style="background: #f9f9f9;">

                    <li>
                        <a class="dropdown-item"
                           href="{{ route(
                               'admin.tenants.addresses.index',
                               $tenant->id
                           ) }}">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Manage Addresses
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item"
                           href="{{ route(
                               'admin.tenants.bank-accounts.index',
                               $tenant->id
                           ) }}">
                            <i class="fas fa-university me-2"></i>
                            Bank Accounts
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item"
                           href="{{ route(
                               'admin.tenants.documents.index',
                               $tenant->id
                           ) }}">
                            <i class="fas fa-file-alt me-2"></i>
                            Documents
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item"
                           href="{{ route(
                               'admin.tenants.emergency-contacts.index',
                               $tenant->id
                           ) }}">
                            <i class="fas fa-phone me-2"></i>
                            Emergency Contacts
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item"
                           href="{{ route(
                               'admin.tenants.notes.index',
                               $tenant->id
                           ) }}">
                            <i class="fas fa-sticky-note me-2"></i>
                            Notes
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item"
                           href="{{ route(
                               'admin.tenants.history.index',
                               $tenant->id
                           ) }}">
                            <i class="fas fa-history me-2"></i>
                            History
                        </a>
                    </li>

                </ul>

            </div>

        </div>

    </div>


    {{-- =========================================================
         BASIC INFORMATION
    ========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- COMPANY INFORMATION --}}

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        <i class="fas fa-building
                                  text-primary
                                  me-2"></i>

                        Tenant Information

                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Tenant Code
                            </div>

                            <div class="fw-semibold">
                                {{ $tenant->tenant_code }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Company Name
                            </div>

                            <div class="fw-semibold">
                                {{ $tenant->company_name }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Brand Name
                            </div>

                            <div>
                                {{ $tenant->brand_name ?: '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Company Registration No.
                            </div>

                            <div>
                                {{ $tenant->company_registration_no ?: '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                GST Number
                            </div>

                            <div>
                                {{ $tenant->gst_number ?: '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                PAN Number
                            </div>

                            <div>
                                {{ $tenant->pan_number ?: '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Website
                            </div>

                            <div>
                                {{ $tenant->website ?: '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Status
                            </div>

                            <div>

                                @if($tenant->status === 'Active')

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Inactive
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- LOGIN INFORMATION --}}

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        <i class="fas fa-user-lock
                                  text-success
                                  me-2"></i>

                        Login Account

                    </h5>

                </div>

                <div class="card-body">

                    @if($tenant->user)

                        <div class="mb-3">

                            <div class="text-muted small">
                                Name
                            </div>

                            <div class="fw-semibold">
                                {{ $tenant->user->name }}
                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="text-muted small">
                                Email
                            </div>

                            <div>
                                {{ $tenant->user->email }}
                            </div>

                        </div>


                        <div>

                            <div class="text-muted small mb-1">
                                Account Status
                            </div>

                            @if($tenant->user->is_active)

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    Inactive
                                </span>

                            @endif

                        </div>

                    @else

                        <div class="text-center text-muted py-4">

                            <i class="fas fa-user-slash
                                      fa-2x
                                      mb-2">
                            </i>

                            <div>
                                No login account linked.
                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

    {{-- Financial Overview --}}
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Financial Overview</h5>
        </div>

        <div class="card-body">

            @php
                $totalInvoiced = $tenant->invoices->sum('total_amount');
                $totalPaid = $tenant->invoices->sum('paid_amount');
                $totalOutstanding = $tenant->invoices->sum('balance_amount');
            @endphp

            <div class="row">

                {{-- Total Invoiced --}}
                <div class="col-md-4">
                    <div class="border rounded p-3">
                        <small class="text-muted">
                            Total Invoiced
                        </small>

                        <h4 class="mb-0">
                            ${{ number_format($totalInvoiced, 2) }}
                        </h4>
                    </div>
                </div>

                {{-- Total Paid --}}
                <div class="col-md-4">
                    <div class="border rounded p-3">
                        <small class="text-muted">
                            Total Paid
                        </small>

                        <h4 class="mb-0 text-success">
                            ${{ number_format($totalPaid, 2) }}
                        </h4>
                    </div>
                </div>

                {{-- Outstanding --}}
                <div class="col-md-4">
                    <div class="border rounded p-3">
                        <small class="text-muted">
                            Outstanding
                        </small>

                        <h4 class="mb-0 text-danger">
                            ${{ number_format($totalOutstanding, 2) }}
                        </h4>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Invoices</h5>
        </div>

        <div class="card-body">

            @if($tenant->invoices->count())

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">

                        <thead>
                            <tr>
                                <th>Invoice No</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Balance</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($tenant->invoices as $invoice)

                                <tr>

                                    <td>
                                        <a href="{{ route('admin.revenue.invoices.show', $invoice->id) }}"
                                           class="text-primary fw-semibold">
                                            {{ $invoice->invoice_no }}
                                        </a>
                                    </td>

                                    <td>
                                        {{ optional($invoice->invoice_date)->format('d M Y') }}
                                    </td>

                                    <td>
                                        ${{ number_format($invoice->total_amount, 2) }}
                                    </td>

                                    <td class="text-success">
                                        ${{ number_format($invoice->paid_amount, 2) }}
                                    </td>

                                    <td class="text-danger">
                                        ${{ number_format($invoice->balance_amount, 2) }}
                                    </td>

                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $invoice->invoice_status }}
                                        </span>
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>
                </div>

            @else

                <div class="text-muted">
                    No invoices found for this tenant.
                </div>

            @endif

        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Lease Agreements</h5>
        </div>

        <div class="card-body">

            @if($tenant->leaseAgreements->count())

                @foreach($tenant->leaseAgreements as $agreement)

                    <div class="border rounded p-3 mb-4">

                        <div class="row">

                            <div class="col-md-3">
                                <small class="text-muted">Agreement No</small>
                                <div>
                                    <strong>
                                        <a
                                            href="{{ route(
                                                'admin.leasing.proposals.show',
                                                $agreement->proposal_id
                                            ) }}"
                                            class="fw-semibold text-primary text-decoration-none"
                                        >
                                            {{ $agreement->agreement_no }}

                                            <i class="fas fa-external-link-alt ms-1 small"></i>
                                        </a>
                                    </strong>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <small class="text-muted">Status</small>
                                <div>
                                    <span class="badge bg-primary">
                                        {{ $agreement->agreement_status }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <small class="text-muted">Monthly Rent</small>
                                <div>
                                    ${{ number_format($agreement->monthly_rent, 2) }}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <small class="text-muted">CAM</small>
                                <div>
                                    ${{ number_format($agreement->cam_amount, 2) }}
                                </div>
                            </div>

                        </div>

                        <hr>

                        <div class="row">

                            <div class="col-md-3">
                                <small class="text-muted">Lease Start</small>
                                <div>
                                    {{ \Carbon\Carbon::parse($agreement->lease_start_date)->format('d M Y') }}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <small class="text-muted">Lease End</small>
                                <div>
                                    {{ \Carbon\Carbon::parse($agreement->lease_end_date)->format('d M Y') }}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <small class="text-muted">Billing</small>
                                <div>
                                    {{ $agreement->billing_frequency }}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <small class="text-muted">Due Day</small>
                                <div>
                                    Day {{ $agreement->payment_due_day }}
                                </div>
                            </div>

                        </div>

                    </div>
                    @if($agreement->rentSchedules->count())

                        <h6 class="mt-3 mb-3">
                            Rent Schedules
                        </h6>

                        <div class="table-responsive">

                            <table class="table table-sm table-bordered">

                                <thead>
                                    <tr>
                                        <th>Schedule</th>
                                        <th>Billing Period</th>
                                        <th>Period</th>
                                        <th>Total</th>
                                        <th>Invoice</th>
                                        <th>Payment</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach($agreement->rentSchedules as $schedule)

                                        <tr>

                                            <td>
                                                <a href="{{ route('admin.revenue.rent-schedules.show', $schedule->id) }}">
                                                    {{ $schedule->schedule_no }}
                                                </a>
                                            </td>

                                            <td>
                                                {{ $schedule->billing_period }}
                                            </td>

                                            <td>
                                                {{ \Carbon\Carbon::parse($schedule->period_start)->format('d M Y') }}
                                                -
                                                {{ \Carbon\Carbon::parse($schedule->period_end)->format('d M Y') }}
                                            </td>

                                            <td>
                                                ${{ number_format($schedule->total_amount, 2) }}
                                            </td>

                                            <td>

                                                @if($schedule->invoice_id)

                                                    <span class="badge bg-success">
                                                        Generated
                                                    </span>

                                                @else

                                                    <span class="badge bg-warning">
                                                        Pending
                                                    </span>

                                                @endif

                                            </td>

                                            <td>

                                                <span class="badge bg-secondary">
                                                    {{ $schedule->payment_status }}
                                                </span>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="text-muted">
                            No rent schedules generated.
                        </div>

                    @endif

                @endforeach

            @else

                <div class="text-muted">
                    No lease agreements found.
                </div>

            @endif

        </div>
    </div>


    {{-- =========================================================
         CONTACT SUMMARY
    ========================================================== --}}

    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex
                                justify-content-between
                                align-items-center">

                        <div>

                            <div class="text-muted small">
                                Contacts
                            </div>

                            <div class="fs-3 fw-bold">
                                {{ $tenant->contacts->count() }}
                            </div>

                        </div>

                        <i class="fas fa-address-book
                                  fa-2x
                                  text-primary">
                        </i>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex
                                justify-content-between
                                align-items-center">

                        <div>

                            <div class="text-muted small">
                                Addresses
                            </div>

                            <div class="fs-3 fw-bold">
                                {{ $tenant->addresses->count() }}
                            </div>

                        </div>

                        <i class="fas fa-map-marker-alt
                                  fa-2x
                                  text-success">
                        </i>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex
                                justify-content-between
                                align-items-center">

                        <div>

                            <div class="text-muted small">
                                Documents
                            </div>

                            <div class="fs-3 fw-bold">
                                {{ $tenant->documents->count() }}
                            </div>

                        </div>

                        <i class="fas fa-file-alt
                                  fa-2x
                                  text-warning">
                        </i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         CONTACTS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white
            d-flex justify-content-between
            align-items-center">

            <div>
                <h5 class="mb-0">
                    <i class="fas fa-address-book text-primary me-2"></i>
                    Contacts
                </h5>

                <small class="text-muted">
                    Tenant contacts
                </small>
            </div>

            <div class="d-flex gap-2">

                <a href="{{ route(
                    'admin.tenants.contacts.index',
                    $tenant->id
                ) }}"
                   class="btn btn-sm btn-outline-primary">

                    View All

                </a>

                <a href="{{ route(
                    'admin.tenants.contacts.index',
                    $tenant->id
                ) }}"
                   class="btn btn-sm btn-primary">

                    <i class="fas fa-plus me-1"></i>

                    Add Contact

                </a>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>Name</th>
                            <th>Designation</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Primary</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($tenant->contacts as $contact)

                        <tr>

                            <td>
                                {{ $contact->contact_name }}
                            </td>

                            <td>
                                {{ $contact->designation ?: '-' }}
                            </td>

                            <td>
                                {{ $contact->mobile ?: '-' }}
                            </td>

                            <td>
                                {{ $contact->email ?: '-' }}
                            </td>

                            <td>

                                @if($contact->is_primary)

                                    <span class="badge bg-success">
                                        Primary
                                    </span>

                                @else

                                    -

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="text-center
                                       text-muted
                                       py-4">

                                No contacts added.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =========================================================
         ADDRESSES
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white
            d-flex justify-content-between
            align-items-center">

            <div>

                <h5 class="mb-0">

                    <i class="fas fa-map-marker-alt
                              text-success me-2"></i>

                    Addresses

                </h5>

            </div>

            <div class="d-flex gap-2">

                <a href="{{ route(
                    'admin.tenants.addresses.index',
                    $tenant->id
                ) }}"
                   class="btn btn-sm btn-outline-primary">

                    View All

                </a>

                <a href="{{ route(
                    'admin.tenants.addresses.index',
                    $tenant->id
                ) }}"
                   class="btn btn-sm btn-success">

                    <i class="fas fa-plus me-1"></i>

                    Add Address

                </a>

            </div>

        </div>

        <div class="card-body">

            <div class="row g-3">

                @forelse($tenant->addresses as $address)

                    <div class="col-lg-6">

                        <div class="border rounded p-3 h-100">

                            <div class="d-flex
                                        justify-content-between
                                        mb-2">

                                <strong>
                                    {{ $address->address_type }}
                                </strong>

                                @if($address->is_default)

                                    <span class="badge bg-primary">
                                        Default
                                    </span>

                                @endif

                            </div>

                            <div>

                                {{ $address->address_line1 }}

                                @if($address->address_line2)
                                    <br>
                                    {{ $address->address_line2 }}
                                @endif

                                <br>

                                {{ $address->city }},
                                {{ $address->state }}

                                @if($address->pincode)
                                    - {{ $address->pincode }}
                                @endif

                                <br>

                                {{ $address->country }}

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="col-12 text-center text-muted py-4">

                        No addresses added.

                    </div>

                @endforelse

            </div>

        </div>

    </div>
    {{-- =========================================================
     BANK ACCOUNTS
========================================================= --}}

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white
                d-flex justify-content-between
                align-items-center">

        <div>

            <h5 class="mb-0">

                <i class="fas fa-university text-primary me-2"></i>

                Bank Accounts

            </h5>

            <small class="text-muted">

                Tenant banking and payment details

            </small>

        </div>


        <div class="d-flex gap-2">

            {{-- View All --}}

            <a
                href="{{ route(
                    'admin.tenants.bank-accounts.index',
                    $tenant->id
                ) }}"
                class="btn btn-sm btn-outline-primary"
            >

                <i class="fas fa-list me-1"></i>

                View All

            </a>


            {{-- Add Bank Account --}}

            <a
                href="{{ route(
                    'admin.tenants.bank-accounts.index',
                    $tenant->id
                ) }}"
                class="btn btn-sm btn-primary"
            >

                <i class="fas fa-plus me-1"></i>

                Add Bank Account

            </a>

        </div>

    </div>


    <div class="card-body">

        @if($tenant->bankAccounts->count())

            <div class="row g-3">

                @foreach(
                    $tenant->bankAccounts->take(3)
                    as $account
                )

                    <div class="col-md-6">

                        <div class="border rounded-3 p-3 h-100">

                            {{-- HEADER --}}

                            <div class="d-flex
                                        justify-content-between
                                        align-items-start
                                        mb-3">

                                <div class="d-flex
                                            align-items-center">

                                    <div
                                        class="rounded-circle
                                               bg-primary-subtle
                                               d-flex
                                               align-items-center
                                               justify-content-center
                                               me-3"
                                        style="
                                            width:44px;
                                            height:44px;
                                        "
                                    >

                                        <i class="
                                            fas fa-university
                                            text-primary
                                        "></i>

                                    </div>


                                    <div>

                                        <div class="fw-semibold">

                                            {{ $account->bank_name }}

                                        </div>

                                        @if($account->branch_name)

                                            <small class="text-muted">

                                                {{ $account->branch_name }}

                                            </small>

                                        @endif

                                    </div>

                                </div>


                                @if($account->is_default)

                                    <span
                                        class="badge bg-success"
                                    >

                                        <i class="fas fa-check me-1"></i>

                                        Default

                                    </span>

                                @endif

                            </div>


                            {{-- ACCOUNT HOLDER --}}

                            <div class="mb-2">

                                <small class="text-muted d-block">

                                    Account Holder

                                </small>

                                <span class="fw-semibold">

                                    {{ $account->account_holder }}

                                </span>

                            </div>


                            {{-- ACCOUNT NUMBER --}}

                            <div class="mb-2">

                                <small class="text-muted d-block">

                                    Account Number

                                </small>

                                <span class="fw-semibold">

                                    {{ $account->account_number }}

                                </span>

                            </div>


                            {{-- ACCOUNT TYPE --}}

                            <div class="row g-2 mb-2">

                                <div class="col-6">

                                    <small class="text-muted d-block">

                                        Account Type

                                    </small>

                                    <span>

                                        {{ $account->account_type ?: '—' }}

                                    </span>

                                </div>


                                <div class="col-6">

                                    <small class="text-muted d-block">

                                        IFSC Code

                                    </small>

                                    <span class="fw-semibold">

                                        {{ $account->ifsc_code ?: '—' }}

                                    </span>

                                </div>

                            </div>


                            {{-- SWIFT --}}

                            @if($account->swift_code)

                                <div class="mb-2">

                                    <small class="text-muted d-block">

                                        SWIFT Code

                                    </small>

                                    <span>

                                        {{ $account->swift_code }}

                                    </span>

                                </div>

                            @endif


                            {{-- ACTION --}}

                            <div class="border-top
                                        pt-2
                                        mt-3
                                        d-flex
                                        justify-content-end">

                                <a
                                    href="{{ route(
                                        'admin.tenants.bank-accounts.index',
                                        $tenant->id
                                    ) }}"
                                    class="btn btn-sm btn-outline-secondary"
                                >

                                    Manage Account

                                    <i class="fas fa-arrow-right ms-1"></i>

                                </a>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>


            {{-- VIEW ALL --}}

            @if($tenant->bankAccounts->count() > 3)

                <div class="text-center
                            border-top
                            pt-3
                            mt-3">

                    <a
                        href="{{ route(
                            'admin.tenants.bank-accounts.index',
                            $tenant->id
                        ) }}"
                        class="btn btn-sm btn-link"
                    >

                        View All
                        {{ $tenant->bankAccounts->count() }}
                        Bank Accounts

                        <i class="fas fa-arrow-right ms-1"></i>

                    </a>

                </div>

            @endif


        @else

            {{-- EMPTY STATE --}}

            <div class="text-center py-4">

                <div class="mb-3">

                    <i class="
                        fas fa-university
                        fa-3x
                        text-muted
                    "></i>

                </div>


                <h6 class="mb-1">

                    No bank accounts added

                </h6>


                <p class="text-muted small mb-3">

                    Add the tenant's bank account
                    information for payment and
                    financial records.

                </p>


                <a
                    href="{{ route(
                        'admin.tenants.bank-accounts.index',
                        $tenant->id
                    ) }}"
                    class="btn btn-sm btn-primary"
                >

                    <i class="fas fa-plus me-1"></i>

                    Add Bank Account

                </a>

            </div>

        @endif

    </div>

</div>


    {{-- =========================================================
         DOCUMENTS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white
            d-flex justify-content-between
            align-items-center">

            <div>

                <h5 class="mb-0">
                    <i class="fas fa-file-alt text-primary me-2"></i>
                    Documents
                </h5>

                <small class="text-muted">
                    Tenant registration and compliance documents
                </small>

            </div>

            <div class="d-flex gap-2">

                {{-- View All --}}
                <a href="{{ route(
                    'admin.tenants.documents.index',
                    $tenant->id
                ) }}"
                   class="btn btn-sm btn-outline-primary">

                    <i class="fas fa-list me-1"></i>
                    View All

                </a>


                {{-- Add Document --}}
                <a href="{{ route(
                    'admin.tenants.documents.index',
                    $tenant->id
                ) }}"
                   class="btn btn-sm btn-primary">

                    <i class="fas fa-plus me-1"></i>
                    Add Document

                </a>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>Document</th>
                            <th>Number</th>
                            <th>Issue Date</th>
                            <th>Expiry Date</th>
                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($tenant->documents as $document)

                        <tr>

                            <td>

                                {{ $document->documentType?->document_name
                                    ?? 'Document' }}

                            </td>

                            <td>
                                {{ $document->document_number ?: '-' }}
                            </td>

                            <td>

                                {{ $document->issue_date
                                    ? $document->issue_date->format('d M Y')
                                    : '-' }}

                            </td>

                            <td>

                                {{ $document->expiry_date
                                    ? $document->expiry_date->format('d M Y')
                                    : '-' }}

                            </td>

                            <td>

                                @if(
                                    $document->verification_status
                                    === 'Verified'
                                )

                                    <span class="badge bg-success">
                                        Verified
                                    </span>

                                @elseif(
                                    $document->verification_status
                                    === 'Rejected'
                                )

                                    <span class="badge bg-danger">
                                        Rejected
                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="text-center
                                       text-muted
                                       py-4">

                                No documents added.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =========================================================
         EMERGENCY CONTACTS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white
            d-flex justify-content-between
            align-items-center">

            <div>

                <h5 class="mb-0">
                    <i class="fas fa-phone-alt text-danger me-2"></i>
                    Emergency Contacts
                </h5>

                <small class="text-muted">
                    Important contacts for emergency situations
                </small>

            </div>

            <div class="d-flex gap-2">

                {{-- View All --}}
                <a href="{{ route(
                    'admin.tenants.emergency-contacts.index',
                    $tenant->id
                ) }}"
                   class="btn btn-sm btn-outline-primary">

                    <i class="fas fa-list me-1"></i>
                    View All

                </a>


                {{-- Add Emergency Contact --}}
                <a href="{{ route(
                    'admin.tenants.emergency-contacts.index',
                    $tenant->id
                ) }}"
                   class="btn btn-sm btn-danger">

                    <i class="fas fa-plus me-1"></i>
                    Add Emergency Contact

                </a>

            </div>

        </div>

        <div class="card-body">

            <div class="row g-3">

                @forelse(
                    $tenant->emergencyContacts
                    as $contact
                )

                    <div class="col-md-6">

                        <div class="border rounded p-3">

                            <div class="fw-semibold">

                                {{ $contact->person_name }}

                            </div>

                            <div class="text-muted small">

                                {{ $contact->relation ?: '-' }}

                            </div>

                            <div class="mt-2">

                                {{ $contact->mobile ?: '-' }}

                            </div>

                            @if($contact->email)

                                <div class="small text-muted">

                                    {{ $contact->email }}

                                </div>

                            @endif

                        </div>

                    </div>

                @empty

                    <div class="col-12 text-center text-muted py-4">

                        No emergency contacts added.

                    </div>

                @endforelse

            </div>

        </div>

    </div>


    {{-- =========================================================
         NOTES
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white
            d-flex justify-content-between
            align-items-center">

            <div>

                <h5 class="mb-0">

                    <i class="fas fa-sticky-note
                              text-warning me-2"></i>

                    Notes

                </h5>

                <small class="text-muted">
                    Internal tenant notes
                </small>

            </div>

            <div class="d-flex gap-2">

                <a href="{{ route(
                    'admin.tenants.notes.index',
                    $tenant->id
                ) }}"
                   class="btn btn-sm btn-outline-primary">

                    View All

                </a>

                <a href="{{ route(
                    'admin.tenants.notes.index',
                    $tenant->id
                ) }}"
                   class="btn btn-sm btn-warning">

                    <i class="fas fa-plus me-1"></i>

                    Add Note

                </a>

            </div>

        </div>

        <div class="card-body">

            @forelse($tenant->notes as $note)

                <div class="border-bottom pb-3 mb-3">

                    <div class="d-flex
                                justify-content-between">

                        <strong>

                            {{ $note->note_title ?: 'Note' }}

                        </strong>

                        <span class="badge bg-secondary">

                            {{ $note->visibility }}

                        </span>

                    </div>

                    <div class="mt-2">

                        {{ $note->note }}

                    </div>

                </div>

            @empty

                <div class="text-center text-muted py-4">

                    No notes available.

                </div>

            @endforelse

        </div>

    </div>


    {{-- =========================================================
         HISTORY
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Tenant History
            </h5>

        </div>

        <div class="card-body">

            @forelse($tenant->history as $history)

                <div class="d-flex
                            align-items-start
                            border-bottom
                            pb-3
                            mb-3">

                    <div class="me-3">

                        <div class="rounded-circle
                                    bg-primary
                                    bg-opacity-10
                                    text-primary
                                    d-flex
                                    align-items-center
                                    justify-content-center"
                             style="width:40px;height:40px;">

                            <i class="fas fa-history"></i>

                        </div>

                    </div>

                    <div>

                        <div class="fw-semibold">

                            {{ $history->activity_type }}

                        </div>

                        <div class="text-muted">

                            {{ $history->description ?: '-' }}

                        </div>

                        <small class="text-muted">

                            {{ $history->activity_date
                                ? $history->activity_date->format(
                                    'd M Y H:i'
                                )
                                : '-' }}

                            @if($history->performer)

                                &nbsp; | &nbsp;

                                By:
                                {{ $history->performer->name }}

                            @endif

                        </small>

                    </div>

                </div>

            @empty

                <div class="text-center text-muted py-4">

                    No history available.

                </div>

            @endforelse

        </div>

    </div>


</div>

@endsection