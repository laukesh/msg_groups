@extends('layouts.app')

@section('title', 'Tenant Dashboard')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                Tenant Dashboard
            </h4>

            <p class="text-muted mb-0">
                Overview of tenants, leases, documents and activities
            </p>
        </div>

        <div>
            <a href="{{ route('admin.tenants.create') }}"
               class="btn btn-primary">

                <i class="fas fa-plus me-1"></i>
                Add Tenant

            </a>
        </div>

    </div>


    {{-- =========================================================
         TENANT STATISTICS
    ========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- Total Tenants --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Total Tenants
                            </div>

                            <div class="fs-2 fw-bold">
                                {{ $tenantTotal }}
                            </div>

                        </div>

                        <div class="rounded-circle
                                    bg-primary
                                    bg-opacity-10
                                    text-primary
                                    d-flex
                                    align-items-center
                                    justify-content-center"
                             style="width:55px;height:55px;">

                            <i class="fas fa-users fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Active Tenants --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Active Tenants
                            </div>

                            <div class="fs-2 fw-bold text-success">
                                {{ $tenantActive }}
                            </div>

                        </div>

                        <div class="rounded-circle
                                    bg-success
                                    bg-opacity-10
                                    text-success
                                    d-flex
                                    align-items-center
                                    justify-content-center"
                             style="width:55px;height:55px;">

                            <i class="fas fa-user-check fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Inactive Tenants --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Inactive Tenants
                            </div>

                            <div class="fs-2 fw-bold text-secondary">
                                {{ $tenantInactive }}
                            </div>

                        </div>

                        <div class="rounded-circle
                                    bg-secondary
                                    bg-opacity-10
                                    text-secondary
                                    d-flex
                                    align-items-center
                                    justify-content-center"
                             style="width:55px;height:55px;">

                            <i class="fas fa-user-slash fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Active Leases --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Active Leases
                            </div>

                            <div class="fs-2 fw-bold text-primary">
                                {{ $agreementActive }}
                            </div>

                        </div>

                        <div class="rounded-circle
                                    bg-primary
                                    bg-opacity-10
                                    text-primary
                                    d-flex
                                    align-items-center
                                    justify-content-center"
                             style="width:55px;height:55px;">

                            <i class="fas fa-file-signature fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         LEASE / DOCUMENT STATISTICS
    ========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- Total Agreements --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Agreements
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $agreementTotal }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Expired Agreements --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Expired Agreements
                    </div>

                    <div class="fs-3 fw-bold text-danger">
                        {{ $agreementExpired }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Terminated Agreements --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Terminated Agreements
                    </div>

                    <div class="fs-3 fw-bold text-danger">
                        {{ $agreementTerminated }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Documents --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Tenant Documents
                    </div>

                    <div class="fs-3 fw-bold text-warning">
                        {{ $documentTotal }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         DOCUMENT VERIFICATION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Document Verification
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">

                    <div class="border rounded p-3">

                        <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                Pending
                            </span>

                            <span class="badge bg-warning text-dark">
                                {{ $documentPending }}
                            </span>

                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="border rounded p-3">

                        <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                Verified
                            </span>

                            <span class="badge bg-success">
                                {{ $documentVerified }}
                            </span>

                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="border rounded p-3">

                        <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                Rejected
                            </span>

                            <span class="badge bg-danger">
                                {{ $documentRejected }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         EXPIRING AGREEMENTS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-1">
                        Upcoming Lease Expiry
                    </h5>

                    <small class="text-muted">
                        Active agreements expiring within 90 days
                    </small>

                </div>

                <span class="badge bg-warning text-dark">

                    {{ $expiringAgreements->count() }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Agreement No.
                            </th>

                            <th>
                                Tenant
                            </th>

                            <th>
                                Start Date
                            </th>

                            <th>
                                End Date
                            </th>

                            <th>
                                Remaining
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse(
                        $expiringAgreements
                        as $agreement
                    )

                        @php

                            $daysRemaining = now()
                                ->startOfDay()
                                ->diffInDays(
                                    $agreement->lease_end_date,
                                    false
                                );

                        @endphp


                        <tr>

                            <td>

                                <a href="{{ route(
                                    'admin.leasing.agreements.show',
                                    $agreement->id
                                ) }}"
                                   class="fw-semibold text-decoration-none">

                                    {{ $agreement->agreement_no }}

                                </a>

                            </td>


                            <td>

                                {{ $agreement->tenant?->company_name ?? '-' }}

                            </td>


                            <td>

                                {{ $agreement->lease_start_date
                                    ? $agreement->lease_start_date->format('d M Y')
                                    : '-' }}

                            </td>


                            <td>

                                {{ $agreement->lease_end_date
                                    ? $agreement->lease_end_date->format('d M Y')
                                    : '-' }}

                            </td>


                            <td>

                                @if($daysRemaining <= 30)

                                    <span class="badge bg-danger">
                                        {{ $daysRemaining }} days
                                    </span>

                                @elseif($daysRemaining <= 60)

                                    <span class="badge bg-warning text-dark">
                                        {{ $daysRemaining }} days
                                    </span>

                                @else

                                    <span class="badge bg-info">
                                        {{ $daysRemaining }} days
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="text-center text-muted py-5">

                                <i class="fas fa-calendar-check
                                          fa-2x d-block mb-2"></i>

                                No active agreements are expiring
                                within 90 days.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =========================================================
         RECENT TENANT HISTORY
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-1">
                Recent Tenant Activity
            </h5>

            <small class="text-muted">
                Latest activities recorded against tenants
            </small>

        </div>


        <div class="card-body">

            @forelse(
                $recentHistory
                as $history
            )

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
                             style="width:42px;height:42px;">

                            <i class="fas fa-history"></i>

                        </div>

                    </div>


                    <div class="flex-grow-1">

                        <div class="fw-semibold">

                            {{ $history->activity_type }}

                        </div>


                        <div class="text-muted small">

                            {{ $history->description ?? '-' }}

                        </div>


                        <div class="small text-muted mt-1">

                            Tenant:

                            <strong>
                                {{ $history->tenant?->company_name ?? '-' }}
                            </strong>

                            &nbsp; | &nbsp;

                            {{ $history->activity_date
                                ? $history->activity_date->format('d M Y H:i')
                                : '-' }}

                        </div>

                    </div>

                </div>

            @empty

                <div class="text-center text-muted py-5">

                    <i class="fas fa-history
                              fa-2x d-block mb-2"></i>

                    No tenant activity found.

                </div>

            @endforelse

        </div>

    </div>


    {{-- =========================================================
         QUICK ACTIONS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Quick Actions
            </h5>

        </div>


        <div class="card-body">

            <div class="d-flex flex-wrap gap-2">

                <a href="{{ route('admin.tenants.create') }}"
                   class="btn btn-primary">

                    <i class="fas fa-user-plus me-1"></i>

                    Add Tenant

                </a>


                <a href="{{ route('admin.tenants.index') }}"
                   class="btn btn-outline-primary">

                    <i class="fas fa-users me-1"></i>

                    Manage Tenants

                </a>


                <a href="{{ route(
                    'admin.leasing.agreements.index'
                ) }}"
                   class="btn btn-outline-success">

                    <i class="fas fa-file-signature me-1"></i>

                    Lease Agreements

                </a>

            </div>

        </div>

    </div>

</div>

@endsection