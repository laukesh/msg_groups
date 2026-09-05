@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="d-flex justify-content-between
                align-items-start mb-4">

        <div>

            <div class="d-flex align-items-center gap-2">

                <h3 class="mb-1 fw-bold">

                    Lease Agreement

                </h3>

                @php

                    $statusClass = match(
                        $agreement->agreement_status
                    ) {

                        'Active' =>
                            'bg-success',

                        'Expired',
                        'Terminated',
                        'Cancelled' =>
                            'bg-danger',

                        'Renewed' =>
                            'bg-primary',

                        'Draft' =>
                            'bg-warning text-dark',

                        default =>
                            'bg-secondary',

                    };

                @endphp

                <span class="badge {{ $statusClass }}">

                    {{ $agreement->agreement_status }}

                </span>

            </div>


            <div class="text-muted">

                {{ $agreement->agreement_no }}

                @if($agreement->tenant_name)

                    <span class="mx-1">•</span>

                    {{ $agreement->tenant_name }}

                @endif

            </div>

        </div>


        <div class="d-flex gap-2">

            {{-- View Proposal --}}

            @if($agreement->proposal_id)

                <a
                    href="{{ route(
                        'admin.leasing.proposals.show',
                        $agreement->proposal_id
                    ) }}"
                    class="btn btn-outline-primary"
                >

                    <i class="fas fa-file-contract me-1"></i>

                    View Proposal

                </a>

            @endif


            {{-- Edit --}}

            <a
                href="#"
                class="btn btn-outline-secondary"
            >

                <i class="fas fa-edit me-1"></i>

                Edit Agreement

            </a>


            {{-- Print --}}

            <button
                type="button"
                onclick="window.print()"
                class="btn btn-outline-secondary"
            >

                <i class="fas fa-print me-1"></i>

                Print

            </button>


            {{-- Back --}}

            <a
                href="{{ route(
                    'admin.leasing.index'
                ) }}"
                class="btn btn-secondary"
            >

                <i class="fas fa-arrow-left me-1"></i>

                Back

            </a>

        </div>

    </div>



    {{-- =====================================================
         SUMMARY
    ====================================================== --}}

    <div class="card border-0 bg-light mb-4">

    <div class="card-body">

        <div class="d-flex align-items-center
                    justify-content-between">

            <div class="text-center">

                <div class="rounded-circle
                            bg-success
                            text-white
                            mx-auto mb-2"
                     style="width:40px;height:40px;
                            line-height:40px;">

                    ✓

                </div>

                <small class="fw-semibold">
                    Proposal
                </small>

            </div>


            <div class="flex-grow-1
                        border-top mx-3"></div>


            <div class="text-center">

                <div class="rounded-circle
                            bg-success
                            text-white
                            mx-auto mb-2"
                     style="width:40px;height:40px;
                            line-height:40px;">

                    ✓

                </div>

                <small class="fw-semibold">
                    Agreement
                </small>

            </div>


            <div class="flex-grow-1
                        border-top mx-3"></div>


            <div class="text-center">

                <div class="rounded-circle
                            bg-success
                            text-white
                            mx-auto mb-2"
                     style="width:40px;height:40px;
                            line-height:40px;">

                    ✓

                </div>

                <small class="fw-semibold">
                    Active
                </small>

            </div>


            <div class="flex-grow-1
                        border-top mx-3"></div>


            <div class="text-center">

                <div class="rounded-circle
                            bg-secondary
                            text-white
                            mx-auto mb-2"
                     style="width:40px;height:40px;
                            line-height:40px;">

                    4

                </div>

                <small class="fw-semibold">
                    Renewal
                </small>

            </div>

        </div>

    </div>

</div>

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-4">

                {{-- =================================================
                     UNITS
                ================================================== --}}

                <div class="mt-4">

                    <div class="d-flex
                                justify-content-between
                                align-items-center mb-3">

                        <div>

                            <h5 class="fw-bold mb-1">

                                Leased Units

                            </h5>

                            <small class="text-muted">

                                Units associated with this lease proposal

                            </small>

                        </div>

                        <span class="badge bg-light text-dark border">

                            {{ $units->count() }} Unit(s)

                        </span>

                    </div>


                    @if($units->count())

                        <div class="row g-3">

                            @foreach($units as $unit)

                                <div class="col-md-6 col-xl-4">

                                    <div class="card
                                                border
                                                h-100">

                                        <div class="card-body">

                                            <div class="d-flex
                                                        justify-content-between
                                                        align-items-start">

                                                <div>

                                                    <div class="fw-bold fs-5">

                                                        {{ $unit->unit_no }}

                                                    </div>

                                                    @if($unit->shop_name)

                                                        <small class="text-muted">

                                                            {{ $unit->shop_name }}

                                                        </small>

                                                    @endif

                                                </div>


                                                <span class="badge
                                                             bg-primary">

                                                    Unit

                                                </span>

                                            </div>


                                            <hr>


                                            <div class="row g-3">

                                                <div class="col-6">

                                                    <small class="text-muted d-block">

                                                        Carpet Area

                                                    </small>

                                                    <strong>

                                                        {{ number_format(
                                                            $unit->carpet_area ?? 0,
                                                            2
                                                        ) }}

                                                    </strong>

                                                </div>


                                                <div class="col-6">

                                                    <small class="text-muted d-block">

                                                        Built-up Area

                                                    </small>

                                                    <strong>

                                                        {{ number_format(
                                                            $unit->builtup_area ?? 0,
                                                            2
                                                        ) }}

                                                    </strong>

                                                </div>


                                                <div class="col-6">

                                                    <small class="text-muted d-block">

                                                        Proposed Rent

                                                    </small>

                                                    <strong>

                                                        ${{ number_format(
                                                            $unit->proposed_rent ?? 0,
                                                            2
                                                        ) }}

                                                    </strong>

                                                </div>


                                                <div class="col-6">

                                                    <small class="text-muted d-block">

                                                        CAM

                                                    </small>

                                                    <strong>

                                                        ${{ number_format(
                                                            $unit->proposed_cam_rate ?? 0,
                                                            2
                                                        ) }}

                                                    </strong>

                                                </div>

                                            </div>


                                            <div class="mt-3">

                                                <a
                                                    href=""
                                                    class="btn
                                                           btn-sm
                                                           btn-outline-primary
                                                           w-100"
                                                >

                                                    View Unit

                                                    <i class="
                                                        fas
                                                        fa-arrow-right
                                                        ms-1
                                                    "></i>

                                                </a>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="alert alert-light border">

                            <i class="
                                fas
                                fa-info-circle
                                me-2
                                text-muted
                            "></i>

                            No units are associated with this lease.

                        </div>

                    @endif

                </div>


                {{-- AGREEMENT --}}

                <div class="col-md-3">

                    <small class="text-muted d-block mb-1">

                        Agreement No.

                    </small>

                    <div class="fw-semibold">

                        {{ $agreement->agreement_no }}

                    </div>

                </div>


                {{-- TENANT --}}

                <div class="col-md-3">

                <small class="text-muted d-block mb-1">
                    Tenant
                </small>

                <div class="d-flex align-items-center gap-2">

                    <a
                        href="{{ route(
                            'admin.tenants.show',
                            $agreement->tenant_id
                        ) }}"
                        class="fw-semibold text-primary text-decoration-none"
                    >
                        {{ $agreement->tenant_name ?: '—' }}

                        <i class="fas fa-external-link-alt ms-1 small"></i>
                    </a>

                </div>

                @if($agreement->brand_name)

                    <small class="text-muted">
                        {{ $agreement->brand_name }}
                    </small>

                @endif

            </div>


                {{-- AGREEMENT DATE --}}

                <div class="col-md-3">

                    <small class="text-muted d-block mb-1">

                        Agreement Date

                    </small>

                    <div>

                        {{ $agreement->agreement_date
                            ? \Carbon\Carbon::parse(
                                $agreement->agreement_date
                            )->format('d M Y')
                            : '—' }}

                    </div>

                </div>


                {{-- STATUS --}}

                <div class="col-md-3">

                    <small class="text-muted d-block mb-1">

                        Status

                    </small>

                    <span class="badge {{ $statusClass }}">

                        {{ $agreement->agreement_status }}

                    </span>

                </div>


                <div class="col-12">

                    <hr class="my-0">

                </div>


                {{-- START --}}

                <div class="col-md-3">

                    <small class="text-muted d-block mb-1">

                        Lease Start

                    </small>

                    <strong>

                        {{ $agreement->lease_start_date
                            ? \Carbon\Carbon::parse(
                                $agreement->lease_start_date
                            )->format('d M Y')
                            : '—' }}

                    </strong>

                </div>


                {{-- END --}}

                <div class="col-md-3">

                    <small class="text-muted d-block mb-1">

                        Lease End

                    </small>

                    <strong>

                        {{ $agreement->lease_end_date
                            ? \Carbon\Carbon::parse(
                                $agreement->lease_end_date
                            )->format('d M Y')
                            : '—' }}

                    </strong>

                </div>


                {{-- RENT --}}

                <div class="col-md-3">

                    <small class="text-muted d-block mb-1">

                        Monthly Rent

                    </small>

                    <strong>

                        ${{ number_format(
                            $agreement->monthly_rent ?? 0,
                            2
                        ) }}

                    </strong>

                </div>


                {{-- CAM --}}

                <div class="col-md-3">

                    <small class="text-muted d-block mb-1">

                        CAM

                    </small>

                    <strong>

                        ${{ number_format(
                            $agreement->cam_amount ?? 0,
                            2
                        ) }}

                    </strong>

                </div>


                <div class="col-12">

                    <hr class="my-0">

                </div>


                {{-- BILLING --}}

                <div class="col-md-3">

                    <small class="text-muted d-block mb-1">

                        Billing Frequency

                    </small>

                    <div>

                        {{ $agreement->billing_frequency ?: '—' }}

                    </div>

                </div>


                {{-- DUE DAY --}}

                <div class="col-md-3">

                    <small class="text-muted d-block mb-1">

                        Payment Due Day

                    </small>

                    <div>

                        Day {{ $agreement->payment_due_day ?: '—' }}

                    </div>

                </div>


                {{-- SECURITY --}}

                <div class="col-md-3">

                    <small class="text-muted d-block mb-1">

                        Security Deposit

                    </small>

                    <div>

                        ${{ number_format(
                            $agreement->security_deposit ?? 0,
                            2
                        ) }}

                    </div>

                </div>


                {{-- UTILITY --}}

                <div class="col-md-3">

                    <small class="text-muted d-block mb-1">

                        Utility Deposit

                    </small>

                    <div>

                        ${{ number_format(
                            $agreement->utility_deposit ?? 0,
                            2
                        ) }}

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="d-flex
                                    justify-content-between
                                    align-items-start">

                            <div>

                                <small class="text-muted">
                                    Revenue
                                </small>

                                <div class="fw-bold fs-5 mt-1">

                                    ${{ number_format(
                                        $agreement->monthly_rent ?? 0,
                                        2
                                    ) }}

                                </div>

                                <small class="text-muted">
                                    Monthly Rent
                                </small>

                            </div>

                            <i class="
                                fas fa-indian-rupee-sign
                                text-success
                            "></i>

                        </div>

                        <a
                            href="{{ url(
                                '/admin/revenue/rent-schedules'
                            ) }}?agreement_id={{ $agreement->id }}"
                            class="btn btn-sm
                                   btn-outline-success
                                   mt-3"
                        >

                            View Rent Schedule

                            <i class="fas fa-arrow-right ms-1"></i>

                        </a>

                    </div>

                </div>


            </div>

        </div>

    </div>



    {{-- =====================================================
         TABS
    ====================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white p-0">

            <ul
                class="nav nav-tabs px-3 pt-2"
                id="leaseTabs"
                role="tablist"
            >

                <li class="nav-item">

                    <button
                        class="nav-link active"
                        data-bs-toggle="tab"
                        data-bs-target="#overview"
                        type="button"
                    >

                        Overview

                    </button>

                </li>


                <li class="nav-item">

                    <button
                        class="nav-link"
                        data-bs-toggle="tab"
                        data-bs-target="#proposal"
                        type="button"
                    >

                        Proposal

                    </button>

                </li>


                <li class="nav-item">

                    <button
                        class="nav-link"
                        data-bs-toggle="tab"
                        data-bs-target="#agreement"
                        type="button"
                    >

                        Agreement

                    </button>

                </li>


                <li class="nav-item">

                    <button
                        class="nav-link"
                        data-bs-toggle="tab"
                        data-bs-target="#terms"
                        type="button"
                    >

                        Terms

                    </button>

                </li>


                <li class="nav-item">

                    <button
                        class="nav-link"
                        data-bs-toggle="tab"
                        data-bs-target="#documents"
                        type="button"
                    >

                        Documents

                        <span class="badge bg-secondary ms-1">

                            {{ $documents->count() }}

                        </span>

                    </button>

                </li>


                <li class="nav-item">

                    <button
                        class="nav-link"
                        data-bs-toggle="tab"
                        data-bs-target="#escalations"
                        type="button"
                    >

                        Escalations

                        <span class="badge bg-secondary ms-1">

                            {{ $escalations->count() }}

                        </span>

                    </button>

                </li>


                <li class="nav-item">

                    <button
                        class="nav-link"
                        data-bs-toggle="tab"
                        data-bs-target="#renewals"
                        type="button"
                    >

                        Renewals

                        <span class="badge bg-secondary ms-1">

                            {{ $renewals->count() }}

                        </span>

                    </button>

                </li>


                <li class="nav-item">

                    <button
                        class="nav-link"
                        data-bs-toggle="tab"
                        data-bs-target="#extensions"
                        type="button"
                    >

                        Extensions

                        <span class="badge bg-secondary ms-1">

                            {{ $extensions->count() }}

                        </span>

                    </button>

                </li>


                <li class="nav-item">

                    <button
                        class="nav-link"
                        data-bs-toggle="tab"
                        data-bs-target="#termination"
                        type="button"
                    >

                        Termination

                        <span class="badge bg-secondary ms-1">

                            {{ $terminations->count() }}

                        </span>

                    </button>

                </li>

                <li class="nav-item">

                    <button
                        class="nav-link"
                        data-bs-toggle="tab"
                        data-bs-target="#history"
                        type="button"
                    >
                        <i class="fas fa-history me-1"></i>
                        History
                    </button>

                </li>

            </ul>

        </div>



        <div class="card-body">

            <div class="tab-content">


                {{-- =================================================
                     OVERVIEW
                ================================================== --}}

                <div
                    class="tab-pane fade show active"
                    id="overview"
                >

                    <div class="row g-4">

                        <div class="col-lg-8">

                            <h5 class="fw-bold mb-3">

                                Lease Overview

                            </h5>

                            <p class="text-muted">

                                This lease agreement connects the
                                tenant, original proposal and all
                                subsequent lease activities.

                            </p>

                        </div>


                        <div class="col-lg-4">

                            <div class="bg-light rounded p-3">

                                <small class="text-muted">

                                    Lease Period

                                </small>

                                <div class="fw-semibold mt-1">

                                    {{ $agreement->lease_period_months
                                        ?: '—' }}

                                    months

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     PROPOSAL
                ================================================== --}}

                <div
                    class="tab-pane fade"
                    id="proposal"
                >

                    <div class="d-flex
                                justify-content-between
                                align-items-center mb-3">

                        <h5 class="fw-bold mb-0">

                            Lease Proposal

                        </h5>


                        @if($agreement->proposal_id)

                            <a
                                href="{{ route(
                                    'admin.leasing.proposals.show',
                                    $agreement->proposal_id
                                ) }}"
                                class="btn btn-sm
                                       btn-outline-primary"
                            >

                                View Full Proposal

                                <i class="
                                    fas fa-arrow-right
                                    ms-1
                                "></i>

                            </a>

                        @endif

                    </div>


                    <div class="row g-4">

                        <div class="col-md-4">

                            <small class="text-muted">

                                Proposal No.

                            </small>

                            <div class="fw-semibold">

                                {{ $agreement->proposal_no ?: '—' }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">

                                Proposal Title

                            </small>

                            <div class="fw-semibold">

                                {{ $agreement->proposal_title
                                    ?: '—' }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">

                                Proposal Status

                            </small>

                            <div>

                                {{ $agreement->proposal_status
                                    ?: '—' }}

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     AGREEMENT
                ================================================== --}}

                <div
                    class="tab-pane fade"
                    id="agreement"
                >

                    <h5 class="fw-bold mb-4">

                        Agreement Information

                    </h5>


                    <div class="row g-4">

                        <div class="col-md-4">

                            <small class="text-muted">

                                Agreement No.

                            </small>

                            <div class="fw-semibold">

                                {{ $agreement->agreement_no }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">

                                Agreement Date

                            </small>

                            <div>

                                {{ $agreement->agreement_date
                                    ? \Carbon\Carbon::parse(
                                        $agreement->agreement_date
                                    )->format('d M Y')
                                    : '—' }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">

                                Billing Frequency

                            </small>

                            <div>

                                {{ $agreement->billing_frequency
                                    ?: '—' }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">

                                Rent Start Date

                            </small>

                            <div>

                                {{ $agreement->rent_start_date
                                    ? \Carbon\Carbon::parse(
                                        $agreement->rent_start_date
                                    )->format('d M Y')
                                    : '—' }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">

                                Handover Date

                            </small>

                            <div>

                                {{ $agreement->handover_date
                                    ? \Carbon\Carbon::parse(
                                        $agreement->handover_date
                                    )->format('d M Y')
                                    : '—' }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">

                                Fit-Out Period

                            </small>

                            <div>

                                {{ $agreement->fitout_start_date
                                    ? \Carbon\Carbon::parse(
                                        $agreement->fitout_start_date
                                    )->format('d M Y')
                                    : '—' }}

                                →

                                {{ $agreement->fitout_end_date
                                    ? \Carbon\Carbon::parse(
                                        $agreement->fitout_end_date
                                    )->format('d M Y')
                                    : '—' }}

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     TERMS
                ================================================== --}}

                <div
                    class="tab-pane fade"
                    id="terms"
                >

                    <h5 class="fw-bold mb-4">

                        Lease Terms

                    </h5>


                    @if($terms)

                        <div class="row g-4">

                            <div class="col-md-3">

                                <small class="text-muted">

                                    Lock-in Period

                                </small>

                                <div class="fw-semibold">

                                    {{ $terms->lock_in_period_months }}
                                    months

                                </div>

                            </div>


                            <div class="col-md-3">

                                <small class="text-muted">

                                    Notice Period

                                </small>

                                <div class="fw-semibold">

                                    {{ $terms->notice_period_days }}
                                    days

                                </div>

                            </div>


                            <div class="col-md-3">

                                <small class="text-muted">

                                    Escalation

                                </small>

                                <div class="fw-semibold">

                                    {{ $terms->escalation_percentage }}%

                                </div>

                            </div>


                            <div class="col-md-3">

                                <small class="text-muted">

                                    Escalation Frequency

                                </small>

                                <div class="fw-semibold">

                                    {{ $terms->escalation_frequency }}

                                </div>

                            </div>


                            <div class="col-md-3">

                                <small class="text-muted">

                                    Billing Cycle

                                </small>

                                <div>

                                    {{ $terms->billing_cycle }}

                                </div>

                            </div>


                            <div class="col-md-3">

                                <small class="text-muted">

                                    Grace Period

                                </small>

                                <div>

                                    {{ $terms->grace_period_days }}
                                    days

                                </div>

                            </div>


                            <div class="col-md-3">

                                <small class="text-muted">

                                    Late Fee

                                </small>

                                <div>

                                    {{ $terms->late_fee_type }}

                                    —

                                    {{ $terms->late_fee_value }}

                                </div>

                            </div>


                            <div class="col-md-3">

                                <small class="text-muted">

                                    Insurance Required

                                </small>

                                <div>

                                    {{ $terms->insurance_required }}

                                </div>

                            </div>


                            <div class="col-12">

                                <hr>

                            </div>


                            <div class="col-md-6">

                                <small class="text-muted">

                                    Termination Clause

                                </small>

                                <p class="mb-0 mt-1">

                                    {{ $terms->termination_clause
                                        ?: '—' }}

                                </p>

                            </div>


                            <div class="col-md-6">

                                <small class="text-muted">

                                    Special Terms

                                </small>

                                <p class="mb-0 mt-1">

                                    {{ $terms->special_terms
                                        ?: '—' }}

                                </p>

                            </div>

                        </div>

                    @else

                        <div class="text-center
                                    text-muted
                                    py-5">

                            No lease terms have been added.

                        </div>

                    @endif

                </div>



                {{-- =================================================
                     DOCUMENTS
                ================================================== --}}

                <div
                    class="tab-pane fade"
                    id="documents"
                >

                    <div class="d-flex
                                justify-content-between
                                align-items-center mb-3">

                        <h5 class="fw-bold mb-0">

                            Lease Documents

                        </h5>


                        <a
                            href="{{ route(
                                'admin.leasing.documents.create',
                                ['agreement_id' => $agreement->id]
                            ) }}"
                            class="btn btn-sm btn-primary"
                        >
                            <i class="fas fa-plus me-1"></i>
                            Add Document
                        </a>

                    </div>


                    @if($documents->count())

                        <div class="table-responsive">

                            <table class="table
                                          table-hover
                                          align-middle">

                                <thead>

                                    <tr>

                                        <th>Document</th>

                                        <th>Document No.</th>

                                        <th>Version</th>

                                        <th>Expiry</th>

                                        <th>Status</th>

                                        <th></th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach($documents as $document)

                                        <tr>

                                            <td>

                                                <div class="fw-semibold">

                                                    {{ $document->document_name }}

                                                </div>

                                                <small class="text-muted">

                                                    {{ $document->file_name
                                                        ?: 'No file' }}

                                                </small>

                                            </td>

                                            <td>

                                                {{ $document->document_number
                                                    ?: '—' }}

                                            </td>

                                            <td>

                                                v{{ $document->version_no }}

                                            </td>

                                            <td>

                                                {{ $document->expiry_date
                                                    ? \Carbon\Carbon::parse(
                                                        $document->expiry_date
                                                    )->format('d M Y')
                                                    : '—' }}

                                            </td>

                                            <td>

                                                <span class="badge
                                                    bg-secondary">

                                                    {{ $document->verification_status }}

                                                </span>

                                            </td>

                                            <td class="text-end">

                                                @if($document->file_path)

                                                    <a
                                                        href="{{ asset(
                                                            'storage/' .
                                                            $document->file_path
                                                        ) }}"
                                                        target="_blank"
                                                        class="btn btn-sm
                                                               btn-outline-primary"
                                                    >

                                                        View

                                                    </a>

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

                            No documents found.

                        </div>

                    @endif

                </div>



                {{-- =================================================
                     ESCALATIONS
                ================================================== --}}

                <div
                    class="tab-pane fade"
                    id="escalations"
                >

                    <div class="d-flex
                                justify-content-between
                                align-items-center mb-3">

                        <h5 class="fw-bold mb-0">

                            Rent Escalations

                        </h5>


                        <a
                            href="{{ route(
                                'admin.leasing.escalations.create',
                                ['agreement_id' => $agreement->id]
                            ) }}"
                            class="btn btn-sm btn-primary"
                        >
                            <i class="fas fa-plus me-1"></i>
                            Add Escalation
                        </a>

                    </div>


                    @if($escalations->count())

                        <div class="table-responsive">

                            <table class="table
                                          table-hover">

                                <thead>

                                    <tr>

                                        <th>No.</th>

                                        <th>Effective From</th>

                                        <th>Previous Rent</th>

                                        <th>Type</th>

                                        <th>Value</th>

                                        <th>Revised Rent</th>

                                        <th>Status</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach(
                                        $escalations as $escalation
                                    )

                                        <tr>

                                            <td>

                                                {{ $escalation->escalation_no }}

                                            </td>

                                            <td>

                                                {{ \Carbon\Carbon::parse(
                                                    $escalation->effective_from
                                                )->format('d M Y') }}

                                            </td>

                                            <td>

                                                ${{ number_format(
                                                    $escalation->previous_rent,
                                                    2
                                                ) }}

                                            </td>

                                            <td>

                                                {{ $escalation->escalation_type }}

                                            </td>

                                            <td>

                                                {{ $escalation->escalation_value }}

                                                @if(
                                                    $escalation->escalation_type
                                                    === 'Percentage'
                                                )
                                                    %
                                                @endif

                                            </td>

                                            <td>

                                                ${{ number_format(
                                                    $escalation->revised_rent,
                                                    2
                                                ) }}

                                            </td>

                                            <td>

                                                {{ $escalation->status }}

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

                            No escalations found.

                        </div>

                    @endif

                </div>



                {{-- =================================================
                     RENEWALS
                ================================================== --}}

                <div
                    class="tab-pane fade"
                    id="renewals"
                >

                    <h5 class="fw-bold mb-3">

                        Lease Renewals

                    </h5>

                    <a
                        href="{{ route(
                            'admin.leasing.renewals.create',
                            ['agreement_id' => $agreement->id]
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        <i class="fas fa-plus me-1"></i>
                        Add Renewal
                    </a>


                    @if($renewals->count())

                        <div class="table-responsive">

                            <table class="table
                                          table-hover">

                                <thead>

                                    <tr>

                                        <th>Renewal No.</th>

                                        <th>Request Date</th>

                                        <th>Current Expiry</th>

                                        <th>Proposed End</th>

                                        <th>Current Rent</th>

                                        <th>Proposed Rent</th>

                                        <th>Status</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($renewals as $renewal)

                                        <tr>

                                            <td>

                                                {{ $renewal->renewal_no }}

                                            </td>

                                            <td>

                                                {{ \Carbon\Carbon::parse(
                                                    $renewal->request_date
                                                )->format('d M Y') }}

                                            </td>

                                            <td>

                                                {{ \Carbon\Carbon::parse(
                                                    $renewal->current_expiry_date
                                                )->format('d M Y') }}

                                            </td>

                                            <td>

                                                {{ \Carbon\Carbon::parse(
                                                    $renewal->proposed_end_date
                                                )->format('d M Y') }}

                                            </td>

                                            <td>

                                                ${{ number_format(
                                                    $renewal->current_rent,
                                                    2
                                                ) }}

                                            </td>

                                            <td>

                                                ${{ number_format(
                                                    $renewal->proposed_rent,
                                                    2
                                                ) }}

                                            </td>

                                            <td>

                                                {{ $renewal->approval_status }}

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

                            No renewal records found.

                        </div>

                    @endif

                </div>



                {{-- =================================================
                     EXTENSIONS
                ================================================== --}}

                <div
                    class="tab-pane fade"
                    id="extensions"
                >

                    <h5 class="fw-bold mb-3">

                        Lease Extensions

                    </h5>


                    @if($extensions->count())

                        <div class="table-responsive">

                            <table class="table
                                          table-hover">

                                <thead>

                                    <tr>

                                        <th>Extension No.</th>

                                        <th>Request Date</th>

                                        <th>Current End</th>

                                        <th>Extended End</th>

                                        <th>Months</th>

                                        <th>Revised Rent</th>

                                        <th>Status</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach(
                                        $extensions as $extension
                                    )

                                        <tr>

                                            <td>

                                                {{ $extension->extension_no }}

                                            </td>

                                            <td>

                                                {{ \Carbon\Carbon::parse(
                                                    $extension->request_date
                                                )->format('d M Y') }}

                                            </td>

                                            <td>

                                                {{ \Carbon\Carbon::parse(
                                                    $extension->current_end_date
                                                )->format('d M Y') }}

                                            </td>

                                            <td>

                                                {{ \Carbon\Carbon::parse(
                                                    $extension->extended_end_date
                                                )->format('d M Y') }}

                                            </td>

                                            <td>

                                                {{ $extension->extension_months }}

                                            </td>

                                            <td>

                                                ${{ number_format(
                                                    $extension->revised_rent,
                                                    2
                                                ) }}

                                            </td>

                                            <td>

                                                {{ $extension->approval_status }}

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

                            No extension records found.

                        </div>

                    @endif

                </div>



                {{-- =================================================
                     TERMINATION
                ================================================== --}}

                <div
                    class="tab-pane fade"
                    id="termination"
                >

                    <h5 class="fw-bold mb-3">

                        Lease Termination

                    </h5>

                    <button
                        type="button"
                        class="btn btn-outline-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#terminationModal"
                    >
                        <i class="fas fa-ban me-1"></i>
                        Initiate Termination
                    </button>


                    @if($terminations->count())

                        <div class="table-responsive">

                            <table class="table
                                          table-hover">

                                <thead>

                                    <tr>

                                        <th>Termination No.</th>

                                        <th>Type</th>

                                        <th>Request Date</th>

                                        <th>Effective Date</th>

                                        <th>Outstanding</th>

                                        <th>Settlement</th>

                                        <th>Status</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach(
                                        $terminations as $termination
                                    )

                                        <tr>

                                            <td>

                                                {{ $termination->termination_no }}

                                            </td>

                                            <td>

                                                {{ $termination->termination_type }}

                                            </td>

                                            <td>

                                                {{ \Carbon\Carbon::parse(
                                                    $termination->request_date
                                                )->format('d M Y') }}

                                            </td>

                                            <td>

                                                {{ \Carbon\Carbon::parse(
                                                    $termination->effective_date
                                                )->format('d M Y') }}

                                            </td>

                                            <td>

                                                ${{ number_format(
                                                    $termination->outstanding_amount,
                                                    2
                                                ) }}

                                            </td>

                                            <td>

                                                ${{ number_format(
                                                    $termination->final_settlement_amount,
                                                    2
                                                ) }}

                                            </td>

                                            <td>

                                                {{ $termination->termination_status }}

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

                            <i class="
                                fas fa-check-circle
                                fa-2x
                                text-success
                                mb-3
                            "></i>

                            <div class="fw-semibold">

                                No termination record

                            </div>

                            <small class="text-muted">

                                This lease has no termination
                                request.

                            </small>

                        </div>

                    @endif

                </div>

                {{-- =================================================
                     HISTORY
                ================================================== --}}

                <div
                    class="tab-pane fade"
                    id="history"
                >

                    <div class="d-flex
                                justify-content-between
                                align-items-center mb-4">

                        <div>

                            <h5 class="fw-bold mb-1">

                                Lease History

                            </h5>

                            <small class="text-muted">

                                Complete activity and audit trail
                                for this lease.

                            </small>

                        </div>

                    </div>


                    @if($history->count())

                        <div class="lease-history">

                            @foreach($history as $item)

                                <div class="history-item">

                                    {{-- Timeline marker --}}

                                    <div class="history-marker">

                                        @php

                                            $icon = match(
                                                $item->activity_type
                                            ) {

                                                'Proposal'
                                                    => 'fa-file-contract',

                                                'Agreement'
                                                    => 'fa-file-signature',

                                                'Approval'
                                                    => 'fa-check-circle',

                                                'Rent Update'
                                                    => 'fa-money-bill-wave',

                                                'Escalation'
                                                    => 'fa-chart-line',

                                                'Renewal'
                                                    => 'fa-sync',

                                                'Extension'
                                                    => 'fa-calendar-plus',

                                                'Document'
                                                    => 'fa-file',

                                                'Invoice'
                                                    => 'fa-file-invoice',

                                                'Payment'
                                                    => 'fa-credit-card',

                                                'Inspection'
                                                    => 'fa-search',

                                                'Handover'
                                                    => 'fa-key',

                                                'Termination'
                                                    => 'fa-ban',

                                                default
                                                    => 'fa-history',
                                            };

                                        @endphp

                                        <i class="
                                            fas
                                            {{ $icon }}
                                        "></i>

                                    </div>


                                    {{-- Content --}}

                                    <div class="history-content">

                                        <div class="d-flex
                                                    justify-content-between
                                                    align-items-start">

                                            <div>

                                                <div class="fw-semibold">

                                                    {{ $item->activity_title }}

                                                </div>

                                                <span class="badge
                                                             bg-light
                                                             text-dark
                                                             border
                                                             mt-1">

                                                    {{ $item->activity_type }}

                                                </span>

                                            </div>


                                            <small class="text-muted">

                                                {{ $item->activity_date
                                                    ? \Carbon\Carbon::parse(
                                                        $item->activity_date
                                                    )->format(
                                                        'd M Y, h:i A'
                                                    )
                                                    : '—' }}

                                            </small>

                                        </div>


                                        @if($item->description)

                                            <div class="text-muted mt-2">

                                                {{ $item->description }}

                                            </div>

                                        @endif


                                        {{-- OLD / NEW VALUES --}}

                                        @if(
                                            $item->old_value ||
                                            $item->new_value
                                        )

                                            <div class="row g-3 mt-2">


                                                @if($item->old_value)

                                                    <div class="col-md-6">

                                                        <div class="history-value
                                                                    bg-light
                                                                    rounded
                                                                    p-3">

                                                            <small class="text-muted
                                                                          d-block
                                                                          mb-1">

                                                                Previous Value

                                                            </small>

                                                            <pre class="mb-0 small">{{ $item->old_value }}</pre>

                                                        </div>

                                                    </div>

                                                @endif


                                                @if($item->new_value)

                                                    <div class="col-md-6">

                                                        <div class="history-value
                                                                    bg-light
                                                                    rounded
                                                                    p-3">

                                                            <small class="text-muted
                                                                          d-block
                                                                          mb-1">

                                                                New Value

                                                            </small>

                                                            <pre class="mb-0 small">{{ $item->new_value }}</pre>

                                                        </div>

                                                    </div>

                                                @endif

                                            </div>

                                        @endif


                                        {{-- PERFORMED BY --}}

                                        <div class="mt-3
                                                    small
                                                    text-muted">

                                            <i class="fas fa-user me-1"></i>

                                            {{ $item->performer?->name
                                                ?? 'System' }}

                                            @if($item->reference_module)

                                                <span class="mx-1">•</span>

                                                {{ $item->reference_module }}

                                                @if($item->reference_id)

                                                    #{{ $item->reference_id }}

                                                @endif

                                            @endif

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="text-center
                                    text-muted
                                    py-5">

                            <i class="
                                fas fa-history
                                fa-2x
                                mb-3
                            "></i>

                            <div class="fw-semibold">

                                No history available

                            </div>

                            <small>

                                Lease activity will appear here
                                as actions are performed.

                            </small>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection