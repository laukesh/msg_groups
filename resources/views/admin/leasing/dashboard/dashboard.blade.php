@extends('layouts.app')

@section('title', 'Leasing Dashboard')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Leasing Dashboard
            </h4>

            <p class="text-muted mb-0">
                Overview of leasing operations and activities
            </p>

        </div>

    </div>


    {{-- Main Statistics --}}

    <div class="row g-3 mb-4">

        {{-- Total Proposals --}}

        <div class="col-xl-3 col-md-6">

            <a href="{{ route(
                'admin.leasing.proposals.index'
            ) }}"
               class="text-decoration-none">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small">
                                    Total Proposals
                                </div>

                                <div class="fs-2 fw-bold text-dark">
                                    {{ $totalProposals }}
                                </div>

                            </div>

                            <div class="text-primary fs-2">
                                <i class="fas fa-file-contract"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </a>

        </div>


        {{-- Active Agreements --}}

        <div class="col-xl-3 col-md-6">

            <a href="{{ route(
                'admin.leasing.agreements.index'
            ) }}"
               class="text-decoration-none">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small">
                                    Active Agreements
                                </div>

                                <div class="fs-2 fw-bold text-success">
                                    {{ $activeAgreements }}
                                </div>

                            </div>

                            <div class="text-success fs-2">
                                <i class="fas fa-file-signature"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </a>

        </div>


        {{-- Expiring --}}

        <div class="col-xl-3 col-md-6">

            <a href="{{ route(
                'admin.leasing.agreements.index'
            ) }}"
               class="text-decoration-none">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small">
                                    Expiring Within 90 Days
                                </div>

                                <div class="fs-2 fw-bold text-warning">
                                    {{ $expiringCount }}
                                </div>

                            </div>

                            <div class="text-warning fs-2">
                                <i class="fas fa-calendar-alt"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </a>

        </div>


        {{-- Terminated --}}

        <div class="col-xl-3 col-md-6">

            <a href="{{ route(
                'admin.leasing.terminations.index'
            ) }}"
               class="text-decoration-none">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small">
                                    Terminated Agreements
                                </div>

                                <div class="fs-2 fw-bold text-danger">
                                    {{ $terminatedAgreements }}
                                </div>

                            </div>

                            <div class="text-danger fs-2">
                                <i class="fas fa-ban"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </a>

        </div>

    </div>


    {{-- Pending Actions --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                Pending Actions
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- Proposal --}}

                <div class="col-lg-3 col-md-6">

                    <a href="{{ route(
                        'admin.leasing.proposals.index'
                    ) }}"
                       class="text-decoration-none">

                        <div class="border rounded p-3">

                            <div class="text-muted">
                                Pending Proposals
                            </div>

                            <div class="fs-3 fw-bold text-warning">
                                {{ $pendingProposals }}
                            </div>

                            <small>
                                Review proposals →
                            </small>

                        </div>

                    </a>

                </div>


                {{-- Renewal --}}

                <div class="col-lg-3 col-md-6">

                    <a href="{{ route(
                        'admin.leasing.renewals.index'
                    ) }}"
                       class="text-decoration-none">

                        <div class="border rounded p-3">

                            <div class="text-muted">
                                Pending Renewals
                            </div>

                            <div class="fs-3 fw-bold text-warning">
                                {{ $pendingRenewals }}
                            </div>

                            <small>
                                Review renewals →
                            </small>

                        </div>

                    </a>

                </div>


                {{-- Escalation --}}

                <div class="col-lg-3 col-md-6">

                    <a href="{{ route(
                        'admin.leasing.escalations.index'
                    ) }}"
                       class="text-decoration-none">

                        <div class="border rounded p-3">

                            <div class="text-muted">
                                Pending Escalations
                            </div>

                            <div class="fs-3 fw-bold text-warning">
                                {{ $pendingEscalations }}
                            </div>

                            <small>
                                Review escalations →
                            </small>

                        </div>

                    </a>

                </div>


                {{-- Termination --}}

                <div class="col-lg-3 col-md-6">

                    <a href="{{ route(
                        'admin.leasing.terminations.index'
                    ) }}"
                       class="text-decoration-none">

                        <div class="border rounded p-3">

                            <div class="text-muted">
                                Pending Terminations
                            </div>

                            <div class="fs-3 fw-bold text-danger">
                                {{ $pendingTerminations }}
                            </div>

                            <small>
                                Review terminations →
                            </small>

                        </div>

                    </a>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">


        {{-- Expiring Agreements --}}

        <div class="col-lg-7">

            <div class="card">

                <div class="card-header">

                    <div class="d-flex justify-content-between">

                        <h5 class="mb-0">
                            Upcoming Lease Expiry
                        </h5>

                        <span class="badge bg-warning text-dark">

                            {{ $expiringCount }}

                        </span>

                    </div>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th>
                                        Agreement
                                    </th>

                                    <th>
                                        Tenant
                                    </th>

                                    <th>
                                        Expiry Date
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

                                    $daysRemaining =
                                        now()
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
                                        ) }}">

                                            {{ $agreement->agreement_no }}

                                        </a>

                                    </td>


                                    <td>

                                        {{ $agreement->tenant?->company_name
                                            ?? $agreement->tenant?->name
                                            ?? '-' }}

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

                                    <td colspan="4"
                                        class="text-center text-muted py-4">

                                        No upcoming lease expiries.

                                    </td>

                                </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- Recent Activities --}}

        <div class="col-lg-5">

            <div class="card">

                <div class="card-header">

                    <h5 class="mb-0">
                        Recent Activities
                    </h5>

                </div>


                <div class="card-body">

                    @forelse(
                        $recentActivities
                        as $activity
                    )

                        <div class="border-bottom pb-3 mb-3">

                            <div class="fw-semibold">

                                {{ $activity->activity_title }}

                            </div>


                            <div class="mt-1">

                                <span class="badge bg-secondary">

                                    {{ $activity->activity_type }}

                                </span>

                                <span class="text-muted small ms-2">

                                    {{ $activity->agreement?->agreement_no
                                        ?? '-' }}

                                </span>

                            </div>


                            <div class="text-muted small mt-1">

                                {{ $activity->activity_date
                                    ? $activity->activity_date->format(
                                        'd M Y H:i'
                                    )
                                    : '-' }}

                            </div>

                        </div>

                    @empty

                        <div class="text-muted text-center">

                            No recent activities.

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>


    {{-- Quick Actions --}}

    <div class="card mt-4 mb-5">

        <div class="card-header">

            <h5 class="mb-0">
                Quick Actions
            </h5>

        </div>


        <div class="card-body">

            <div class="d-flex flex-wrap gap-2">

                <a href="{{ route(
                    'admin.leasing.proposals.create'
                ) }}"
                   class="btn btn-primary">

                    <i class="fas fa-plus me-1"></i>

                    New Proposal

                </a>


                <a href="{{ route(
                    'admin.leasing.agreements.create'
                ) }}"
                   class="btn btn-success">

                    <i class="fas fa-plus me-1"></i>

                    New Agreement

                </a>


                <a href="{{ route(
                    'admin.leasing.renewals.create'
                ) }}"
                   class="btn btn-info">

                    <i class="fas fa-sync me-1"></i>

                    New Renewal

                </a>


                <a href="{{ route(
                    'admin.leasing.terminations.create'
                ) }}"
                   class="btn btn-danger">

                    <i class="fas fa-times me-1"></i>

                    New Termination

                </a>

            </div>

        </div>

    </div>

</div>

@endsection