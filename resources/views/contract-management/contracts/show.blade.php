@extends('layouts.app')

@section('content')

<style type="text/css">
    .bg-white{
        color: #000;
    }
</style>

<div class="container-fluid">


    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted mb-1">
                Contract Management
            </div>

            <h4 class="mb-1">

                {{ $contract->contract_title }}

            </h4>

            <div class="text-muted">

                {{ $contract->contract_code }}

                @if($contract->contract_number)

                    <span class="ms-2">
                        | {{ $contract->contract_number }}
                    </span>

                @endif

            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.contract-management.contracts.index',
                $project
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>

                Back to Register

            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Contract Header Card --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-4">


                {{-- Party --}}
                <div class="col-xl-3 col-md-6">

                    <div class="text-muted small mb-1">
                        Contract Party
                    </div>

                    <div class="fw-semibold">

                        {{ $contract->party_name }}

                    </div>

                    <div class="small text-muted">

                        {{ $contract->party_type }}

                    </div>

                </div>


                {{-- Contract Type --}}
                <div class="col-xl-2 col-md-6">

                    <div class="text-muted small mb-1">
                        Contract Type
                    </div>

                    <div class="fw-semibold">

                        {{ $contract->contract_type ?? '—' }}

                    </div>

                </div>


                {{-- Value --}}
                <div class="col-xl-3 col-md-6">

                    <div class="text-muted small mb-1">
                        Contract Value
                    </div>

                    <div class="fw-semibold fs-5">

                        {{ $contract->currency ?? 'INR' }}

                        {{ number_format(
                            $contractValue,
                            2
                        ) }}

                    </div>

                </div>


                {{-- Status --}}
                <div class="col-xl-2 col-md-6">

                    <div class="text-muted small mb-1">
                        Status
                    </div>

                    <span class="badge bg-{{ $statusClass }}">

                        {{ $contract->status }}

                    </span>

                </div>


                {{-- Source --}}
                <div class="col-xl-2 col-md-6">

                    <div class="text-muted small mb-1">
                        Source
                    </div>

                    <span class="badge bg-light text-dark">

                        {{ $contract->contract_source }}

                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Navigation Tabs --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-body py-2">

            <ul class="nav nav-pills gap-1">

                <li class="nav-item">

                    <a href="#overview"
                       class="nav-link active">

                        <i class="bi bi-file-earmark-text me-1"></i>
                        Overview

                    </a>

                </li>


                <li class="nav-item">

                    <a href="#financial"
                       class="nav-link">

                        <i class="bi bi-currency-rupee me-1"></i>
                        Financial

                    </a>

                </li>


                <li class="nav-item">

                    <a href="#milestones"
                       class="nav-link">

                        <i class="bi bi-flag me-1"></i>
                        Milestones

                    </a>

                </li>


                <li class="nav-item">

                    <a href="#payments"
                       class="nav-link">

                        <i class="bi bi-cash-stack me-1"></i>
                        Payments

                    </a>

                </li>


                <li class="nav-item">

                    <a href="#variations"
                       class="nav-link">

                        <i class="bi bi-shuffle me-1"></i>
                        Variations

                    </a>

                </li>


                <li class="nav-item">

                    <a href="{{ route(
                        'admin.projects.contract-management.contracts.claims.index',
                        [$project, $contract]
                    ) }}"
                       class="nav-link">

                        <i class="bi bi-exclamation-triangle me-1"></i>

                        Claims

                    </a>

                </li>


                <li class="nav-item">

                    <a href="{{ route(
                        'admin.projects.contract-management.contracts.eot.index',
                        [$project, $contract]
                    ) }}"
                       class="nav-link">

                        <i class="bi bi-calendar-plus me-1"></i>

                        EOT

                    </a>

                </li>


                <li class="nav-item">

                    <a href="{{ route(
                        'admin.projects.contract-management.contracts.insurances.index',
                        [$project, $contract]
                    ) }}"
                       class="nav-link">

                        <i class="bi bi-shield-check me-1"></i>

                        Insurance

                    </a>

                </li>
                <li class="nav-item">

                    <a href="{{ route(
                        'admin.projects.contract-management.contracts.performance-securities.index',
                        [$project, $contract]
                    ) }}"
                       class="nav-link">

                        <i class="bi bi-shield-lock me-1"></i>

                        Performance Security

                    </a>

                </li>
                <li class="nav-item">

                    <a href="{{ route(
                        'admin.projects.contract-management.contracts.retentions.index',
                        [$project, $contract]
                    ) }}"
                       class="nav-link">

                        <i class="bi bi-cash-stack me-1"></i>

                        Retention

                    </a>

                </li>
                <li class="nav-item">

                    <a href="{{ route(
                        'admin.projects.contract-management.contracts.advance-payments.index',
                        [$project, $contract]
                    ) }}"
                       class="nav-link">

                        <i class="bi bi-cash-stack me-1"></i>

                        Advance Payment

                    </a>

                </li>


                <li class="nav-item">

                    <a href="{{ route(
                            'admin.projects.contract-management.contracts.documents.index',
                            [$project, $contract]
                        ) }}"
                       class="nav-link">

                        <i class="bi bi-folder me-1"></i>
                        Documents

                    </a>

                </li>

                <li class="nav-item">

                    <a href="{{ route(
                            'admin.projects.contract-management.contracts.correspondence.index',
                            [$project, $contract]
                        ) }}"
                       class="nav-link">

                        <i class="bi bi-folder me-1"></i>
                        Correspondence

                    </a>

                </li>


                <li class="nav-item">

                    <a href="#closeout"
                       class="nav-link">

                        Closeout

                    </a>

                </li>

            </ul>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Overview --}}
    {{-- ========================================================= --}}

    <div id="overview"
         class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Contract Overview
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-4">

                    <div class="text-muted small">
                        Contract Code
                    </div>

                    <div class="fw-semibold">

                        {{ $contract->contract_code }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Contract Number
                    </div>

                    <div class="fw-semibold">

                        {{ $contract->contract_number ?? '—' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Contract Type
                    </div>

                    <div>

                        {{ $contract->contract_type ?? '—' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Party Type
                    </div>

                    <div>

                        {{ $contract->party_type }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Party
                    </div>

                    <div class="fw-semibold">

                        {{ $contract->party_name }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Contract Source
                    </div>

                    <div>

                        {{ $contract->contract_source }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Signing Date
                    </div>

                    <div>

                        {{ $contract->signing_date
                            ? $contract->signing_date
                                ->format('d M Y')
                            : '—'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Start Date
                    </div>

                    <div>

                        {{ $contract->start_date
                            ? $contract->start_date
                                ->format('d M Y')
                            : '—'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Completion Date
                    </div>

                    <div>

                        {{ $contract->completion_date
                            ? $contract->completion_date
                                ->format('d M Y')
                            : '—'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Financial Summary --}}
    {{-- ========================================================= --}}

    <div id="financial"
         class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Financial Summary
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-3">


                <div class="col-xl-3 col-md-6">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small">
                            Original Contract Value
                        </div>

                        <div class="fs-5 fw-semibold">

                            {{ $contract->currency ?? 'INR' }}

                            {{ number_format(
                                $contractValue,
                                2
                            ) }}

                        </div>

                    </div>

                </div>


                <div class="col-xl-3 col-md-6">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small">
                            Approved Variations
                        </div>

                        <div class="fs-5 fw-semibold">

                            {{ $contract->currency ?? 'INR' }}

                            {{ number_format(
                                $variationAmount,
                                2
                            ) }}

                        </div>

                    </div>

                </div>


                <div class="col-xl-3 col-md-6">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small">
                            Revised Contract Value
                        </div>

                        <div class="fs-5 fw-semibold">

                            {{ $contract->currency ?? 'INR' }}

                            {{ number_format(
                                $revisedContractValue,
                                2
                            ) }}

                        </div>

                    </div>

                </div>


                <div class="col-xl-3 col-md-6">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small">
                            Paid Amount
                        </div>

                        <div class="fs-5 fw-semibold">

                            {{ $contract->currency ?? 'INR' }}

                            {{ number_format(
                                $paidAmount,
                                2
                            ) }}

                        </div>

                    </div>

                </div>


            </div>


            <hr>


            <div class="row g-4">


                <div class="col-md-3">

                    <div class="text-muted small">
                        Invoice / Certified Amount
                    </div>

                    <div class="fw-semibold">

                        {{ $contract->currency ?? 'INR' }}

                        {{ number_format(
                            $invoiceAmount,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Outstanding
                    </div>

                    <div class="fw-semibold">

                        {{ $contract->currency ?? 'INR' }}

                        {{ number_format(
                            $outstandingAmount,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Retention
                    </div>

                    <div class="fw-semibold">

                        @if($contract->retention_required)

                            {{ number_format(
                                $contract->retention_percentage,
                                2
                            ) }}%

                            <span class="text-muted">

                                ({{ number_format(
                                    $retentionAmount,
                                    2
                                ) }})

                            </span>

                        @else

                            Not Required

                        @endif

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Performance Security
                    </div>

                    <div class="fw-semibold">

                        @if($contract->performance_security_required)

                            {{ $contract->currency ?? 'INR' }}

                            {{ number_format(
                                $contract->performance_security_amount,
                                2
                            ) }}

                        @else

                            Not Required

                        @endif

                    </div>

                </div>

            </div>


            <hr>


            <div>

                <div class="d-flex justify-content-between mb-1">

                    <span class="small text-muted">
                        Payment Progress
                    </span>

                    <span class="small fw-semibold">

                        {{ number_format(
                            $paymentPercentage,
                            1
                        ) }}%

                    </span>

                </div>


                <div class="progress"
                     style="height: 8px;">

                    <div class="progress-bar"
                         role="progressbar"
                         style="width: {{ $paymentPercentage }}%;">

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Timeline --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Contract Timeline
            </h5>

        </div>


        <div class="card-body">

            <div class="row text-center">


                <div class="col-md-3">

                    <div class="text-muted small">
                        Start Date
                    </div>

                    <div class="fw-semibold">

                        {{ $contract->start_date
                            ? $contract->start_date
                                ->format('d M Y')
                            : '—'
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Total Days
                    </div>

                    <div class="fw-semibold">

                        {{ $daysTotal ?? '—' }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Days Elapsed
                    </div>

                    <div class="fw-semibold">

                        {{ $daysElapsed ?? '—' }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Days Remaining
                    </div>

                    <div class="fw-semibold">

                        {{ $daysRemaining ?? '—' }}

                    </div>

                </div>

            </div>


            @if($timeProgress !== null)

                <hr>

                <div class="d-flex justify-content-between mb-1">

                    <span class="small text-muted">
                        Time Elapsed
                    </span>

                    <span class="small fw-semibold">

                        {{ number_format(
                            $timeProgress,
                            1
                        ) }}%

                    </span>

                </div>


                <div class="progress"
                     style="height: 8px;">

                    <div class="progress-bar"
                         role="progressbar"
                         style="width: {{ $timeProgress }}%;">

                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Scope --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Scope of Work
            </h5>

        </div>


        <div class="card-body">

            @if($contract->scope_of_work)

                {!! nl2br(
                    e($contract->scope_of_work)
                ) !!}

            @else

                <span class="text-muted">
                    No scope of work recorded.
                </span>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Procurement Source --}}
    {{-- ========================================================= --}}

    @if($procurementContract)

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Procurement Source
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-4">


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Procurement Contract
                        </div>

                        <div class="fw-semibold">

                            {{ $procurementContract->contract_number }}

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Bidder
                        </div>

                        <div>

                            {{ $procurementContract->bidder?->company_name
                                ??
                                $procurementContract->bidder_name
                                ??
                                '—'
                            }}

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Tender
                        </div>

                        <div>

                            {{ $procurementContract->tender?->tender_number
                                ??
                                '—'
                            }}

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Procurement Package
                        </div>

                        <div>

                            {{ $procurementContract
                                ->tender
                                ?->package
                                ?->package_number
                                ??
                                '—'
                            }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Consultant Source --}}
    {{-- ========================================================= --}}

    @if($consultant)

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Consultant Source
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-4">


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Consultant Code
                        </div>

                        <div class="fw-semibold">

                            {{ $consultant->consultant_code }}

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Company
                        </div>

                        <div class="fw-semibold">

                            {{ $consultant->company_name }}

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Type
                        </div>

                        <div>

                            {{ $consultant->consultant_type ?? '—' }}

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Contact
                        </div>

                        <div>

                            {{ $consultant->contact_person ?? '—' }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Future Modules --}}
    {{-- ========================================================= --}}

    {{-- ============================================================
     MILESTONES
============================================================ --}}

<div class="card shadow-sm mb-4">

    <div class="card-header bg-white">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h5 class="mb-0">
                    Milestones
                </h5>

                <small class="text-muted">
                    Contract milestones and delivery progress
                </small>

            </div>

            @if($procurementContract && $procurementContract->tender)

                <a href="{{ route(
                    'admin.procurement.tenders.contracts.milestones.index',
                    [
                        'procurementTender' =>
                            $procurementContract->tender,

                        'contract' =>
                            $procurementContract,
                    ]
                ) }}"
                   class="btn btn-sm btn-outline-primary">

                    <i class="bi bi-list-check me-1"></i>

                    Manage Milestones

                </a>

            @endif

        </div>

    </div>


    <div class="card-body">


        @if($procurementContract)


            {{-- ====================================================
                 MILESTONE SUMMARY
            ==================================================== --}}

            <div class="row g-3 mb-4">


                <div class="col-xl-2 col-md-4 col-6">

                    <div class="border rounded p-3 h-100">

                        <div class="small text-muted">
                            Total
                        </div>

                        <div class="fs-4 fw-semibold">

                            {{
                                $milestoneSummary['total']
                            }}

                        </div>

                    </div>

                </div>


                <div class="col-xl-2 col-md-4 col-6">

                    <div class="border rounded p-3 h-100">

                        <div class="small text-muted">
                            Pending
                        </div>

                        <div class="fs-4 fw-semibold text-secondary">

                            {{
                                $milestoneSummary['pending']
                            }}

                        </div>

                    </div>

                </div>


                <div class="col-xl-2 col-md-4 col-6">

                    <div class="border rounded p-3 h-100">

                        <div class="small text-muted">
                            In Progress
                        </div>

                        <div class="fs-4 fw-semibold text-primary">

                            {{
                                $milestoneSummary['in_progress']
                            }}

                        </div>

                    </div>

                </div>


                <div class="col-xl-2 col-md-4 col-6">

                    <div class="border rounded p-3 h-100">

                        <div class="small text-muted">
                            Completed
                        </div>

                        <div class="fs-4 fw-semibold text-success">

                            {{
                                $milestoneSummary['completed']
                            }}

                        </div>

                    </div>

                </div>


                <div class="col-xl-2 col-md-4 col-6">

                    <div class="border rounded p-3 h-100">

                        <div class="small text-muted">
                            Delayed
                        </div>

                        <div class="fs-4 fw-semibold text-danger">

                            {{
                                $milestoneSummary['delayed']
                            }}

                        </div>

                    </div>

                </div>


                <div class="col-xl-2 col-md-4 col-6">

                    <div class="border rounded p-3 h-100">

                        <div class="small text-muted">
                            Avg. Progress
                        </div>

                        <div class="fs-4 fw-semibold">

                            {{
                                number_format(
                                    $milestoneSummary[
                                        'average_progress'
                                    ],
                                    2
                                )
                            }}%

                        </div>

                    </div>

                </div>

            </div>


            {{-- ====================================================
                 MILESTONE TABLE
            ==================================================== --}}

            @if($milestones->count())


                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Milestone
                                </th>

                                <th>
                                    Planned End
                                </th>

                                <th>
                                    Actual End
                                </th>

                                <th>
                                    Amount
                                </th>

                                <th style="min-width:160px;">
                                    Progress
                                </th>

                                <th>
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $milestones->take(10)
                                as $milestone
                            )

                                <tr>


                                    {{-- Number --}}

                                    <td>

                                        <span class="fw-semibold">

                                            {{
                                                $milestone
                                                    ->milestone_number
                                            }}

                                        </span>

                                    </td>


                                    {{-- Milestone --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{
                                                $milestone
                                                    ->milestone_title
                                            }}

                                        </div>


                                        @if(
                                            $milestone->description
                                        )

                                            <div class="small text-muted">

                                                {{
                                                    \Illuminate\Support\Str::limit(
                                                        $milestone->description,
                                                        80
                                                    )
                                                }}

                                            </div>

                                        @endif


                                        @if(
                                            $milestone
                                                ->deliverable_required
                                        )

                                            <div class="small text-info mt-1">

                                                <i class="bi bi-file-earmark-check me-1"></i>

                                                Deliverable required

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Planned End --}}

                                    <td>

                                        {{
                                            $milestone
                                                ->planned_end_date
                                                ?->format('d M Y')
                                            ?? '—'
                                        }}

                                    </td>


                                    {{-- Actual End --}}

                                    <td>

                                        {{
                                            $milestone
                                                ->actual_end_date
                                                ?->format('d M Y')
                                            ?? '—'
                                        }}

                                    </td>


                                    {{-- Amount --}}

                                    <td>

                                        <span class="text-nowrap">

                                            {{
                                                $milestone
                                                    ->currency
                                            }}

                                            {{
                                                number_format(
                                                    $milestone
                                                        ->milestone_amount,
                                                    2
                                                )
                                            }}

                                        </span>

                                    </td>


                                    {{-- Progress --}}

                                    <td>

                                        @php

                                            $progress =
                                                min(
                                                    100,
                                                    max(
                                                        0,
                                                        (float)
                                                        $milestone
                                                            ->progress_percentage
                                                    )
                                                );

                                        @endphp


                                        <div class="d-flex justify-content-between mb-1">

                                            <small>
                                                Progress
                                            </small>

                                            <small class="fw-semibold">

                                                {{
                                                    number_format(
                                                        $progress,
                                                        2
                                                    )
                                                }}%

                                            </small>

                                        </div>


                                        <div
                                            class="progress"
                                            style="height:7px;"
                                        >

                                            <div
                                                class="progress-bar"
                                                role="progressbar"
                                                style="width: {{ $progress }}%;"
                                                aria-valuenow="{{ $progress }}"
                                                aria-valuemin="0"
                                                aria-valuemax="100"
                                            ></div>

                                        </div>

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        @switch(
                                            $milestone->status
                                        )

                                            @case('Completed')

                                                <span class="badge bg-success">
                                                    Completed
                                                </span>

                                                @break


                                            @case('In Progress')

                                                <span class="badge bg-primary">
                                                    In Progress
                                                </span>

                                                @break


                                            @case('Delayed')

                                                <span class="badge bg-danger">
                                                    Delayed
                                                </span>

                                                @break


                                            @case('On Hold')

                                                <span class="badge bg-warning text-dark">
                                                    On Hold
                                                </span>

                                                @break


                                            @case('Cancelled')

                                                <span class="badge bg-secondary">
                                                    Cancelled
                                                </span>

                                                @break


                                            @default

                                                <span class="badge bg-light text-dark border">

                                                    {{
                                                        $milestone->status
                                                    }}

                                                </span>

                                        @endswitch

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- More than 10 --}}

                @if($milestones->count() > 10)

                    <div class="text-center mt-3">

                        <a href="{{ route(
                            'admin.projects.construction.contracts.milestones.index',
                            [
                                $project,
                                $procurementContract
                            ]
                        ) }}"
                           class="btn btn-sm btn-outline-primary">

                            View All
                            {{ $milestones->count() }}
                            Milestones

                        </a>

                    </div>

                @endif


            @else


                {{-- No Milestones --}}

                <div class="text-center py-5">

                    <i class="bi bi-list-check fs-1 text-muted"></i>

                    <h6 class="mt-3">
                        No Milestones Found
                    </h6>

                    <p class="text-muted mb-3">

                        No milestones have been defined
                        for this procurement contract.

                    </p>


                    <a href="{{ route(
                        'admin.projects.construction.contracts.milestones.index',
                        [
                            $project,
                            $procurementContract
                        ]
                    ) }}"
                       class="btn btn-sm btn-outline-primary">

                        Manage Milestones

                    </a>

                </div>

            @endif


        @else


            <div class="alert alert-warning mb-0">

                <i class="bi bi-exclamation-triangle me-1"></i>

                This Contract Management contract is not
                linked to a Procurement Contract.

            </div>


        @endif

    </div>

</div>


    <div id="payments"
         class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Payments
            </h5>

        </div>

        <div class="card-body">

            @if(
                $procurementContract &&
                $procurementContract->payments->count()
            )

                <div class="table-responsive">

                    <table class="table table-sm align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Payment No.
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Type
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

                            @foreach(
                                $procurementContract->payments
                                as $payment
                            )

                                <tr>

                                    <td>

                                        {{ $payment->payment_number }}

                                    </td>

                                    <td>

                                        {{ $payment->payment_date
                                            ? $payment->payment_date
                                                ->format('d M Y')
                                            : '—'
                                        }}

                                    </td>

                                    <td>

                                        {{ $payment->payment_type ?? '—' }}

                                    </td>

                                    <td class="text-end">

                                        {{ number_format(
                                            (float)
                                            $payment->amount,
                                            2
                                        ) }}

                                    </td>

                                    <td>

                                        {{ $payment->status ?? '—' }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <span class="text-muted">
                    No payments recorded.
                </span>

            @endif

        </div>

    </div>


    <div id="variations"
         class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Variations
            </h5>

        </div>

        <div class="card-body">

            @if(
                $procurementContract &&
                $procurementContract->variations->count()
            )

                <div class="table-responsive">

                    <table class="table table-sm align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Variation No.
                                </th>

                                <th>
                                    Title
                                </th>

                                <th>
                                    Date
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

                            @foreach(
                                $procurementContract->variations
                                as $variation
                            )

                                <tr>

                                    <td>

                                        {{ $variation->variation_number }}

                                    </td>

                                    <td>

                                        {{ $variation->title }}

                                    </td>

                                    <td>

                                        {{ $variation->variation_date
                                            ? $variation->variation_date
                                                ->format('d M Y')
                                            : '—'
                                        }}

                                    </td>

                                    <td class="text-end">

                                        {{ $variation->currency ?? 'INR' }}

                                        {{ number_format(
                                            (float)
                                            $variation->amount,
                                            2
                                        ) }}

                                    </td>

                                    <td>

                                        {{ $variation->status }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <span class="text-muted">
                    No variations recorded.
                </span>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Future Contract Management Sections --}}
    {{-- ========================================================= --}}

    @foreach([
        'claims' => 'Claims',
        'eot' => 'Extensions of Time',
        'insurance' => 'Insurance',
        'documents' => 'Documents',
        'closeout' => 'Contract Closeout'
    ] as $sectionId => $sectionTitle)

        <div id="{{ $sectionId }}"
             class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    {{ $sectionTitle }}
                </h5>

            </div>

            <div class="card-body">

                <div class="text-muted">

                    This section will be available
                    in the next Contract Management phase.

                </div>

            </div>

        </div>

    @endforeach


</div>

@endsection