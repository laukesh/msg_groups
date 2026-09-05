@extends('layouts.app')

@section('title', 'Lease Agreement - ' . $agreement->agreement_no)

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                {{ $agreement->agreement_no }}
            </h4>

            <div class="text-muted">
                Lease Agreement Details
            </div>
        </div>

        <!-- <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.leasing.agreements.index'
            ) }}"
               class="btn btn-secondary">

                <i class="fas fa-arrow-left"></i>
                Back

            </a>

            <a href="{{ route(
                'admin.leasing.agreements.edit',
                $agreement->id
            ) }}"
               class="btn btn-primary">

                <i class="fas fa-edit"></i>
                Edit

            </a>

        </div> -->
        <div class="d-flex gap-2">

                {{-- Back --}}
                <a href="{{ route(
                    'admin.leasing.agreements.index'
                ) }}"
                   class="btn btn-secondary">

                    <i class="fas fa-arrow-left"></i>
                    Back

                </a>


                {{-- Edit --}}
                <a href="{{ route(
                    'admin.leasing.agreements.edit',
                    $agreement->id
                ) }}"
                   class="btn btn-primary">

                    <i class="fas fa-edit"></i>
                    Edit

                </a>


                {{-- Activate --}}
                @if($agreement->agreement_status === 'Draft')

                    <form method="POST"
                          action="{{ route(
                              'admin.leasing.agreements.activate',
                              $agreement->id
                          ) }}"
                          onsubmit="return confirm(
                              'Are you sure you want to activate this lease agreement?'
                          );">

                        @csrf

                        <button type="submit"
                                class="btn btn-success">

                            <i class="fas fa-check-circle me-1"></i>

                            Activate Agreement

                        </button>

                    </form>

                @endif

                @if($agreement->agreement_status === 'Active')

                    <form
                        action="{{ route('admin.revenue.rent-schedules.generate', $agreement->id) }}"
                        method="POST"
                        class="d-inline"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-calendar-plus"></i>
                            Generate Rent Schedule
                        </button>
                    </form>

                @endif

                @if($agreement->agreement_status === 'Active')

                    <a href="{{ route(
                        'admin.leasing.agreements.renew',
                        $agreement->id
                    ) }}"
                       class="btn btn-warning">

                        <i class="fas fa-sync-alt me-1"></i>
                        Renew Agreement

                    </a>

                @endif

                @if($agreement->agreement_status === 'Active')

                    <a href="{{ route(
                        'admin.leasing.renewals.create',
                        ['lease_agreement_id' => $agreement->id]
                    ) }}"
                       class="btn btn-warning">

                        <i class="fas fa-sync-alt me-1"></i>

                        Create Renewal

                    </a>

                @endif

            </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS --}}
    {{-- ========================================================= --}}

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


    {{-- ========================================================= --}}
    {{-- AGREEMENT SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">


        {{-- Agreement --}}
        <div class="col-lg-4">

            <div class="card h-100">

                <div class="card-header">

                    <h5 class="mb-0">
                        <i class="fas fa-file-contract me-1"></i>
                        Agreement
                    </h5>

                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <small class="text-muted">
                            Agreement No.
                        </small>

                        <div class="fw-bold fs-5">
                            {{ $agreement->agreement_no }}
                        </div>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted">
                            Agreement Date
                        </small>

                        <div>

                            {{ $agreement->agreement_date
                                ? $agreement->agreement_date->format('d M Y')
                                : '-'
                            }}

                        </div>

                    </div>


                    <div>

                        <small class="text-muted">
                            Status
                        </small>

                        <div class="mt-1">

                            @switch($agreement->agreement_status)

                                @case('Draft')

                                    <span class="badge bg-secondary">
                                        Draft
                                    </span>

                                    @break

                                @case('Active')

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                    @break

                                @case('Expired')

                                    <span class="badge bg-warning text-dark">
                                        Expired
                                    </span>

                                    @break

                                @case('Terminated')

                                    <span class="badge bg-danger">
                                        Terminated
                                    </span>

                                    @break

                                @case('Renewed')

                                    <span class="badge bg-primary">
                                        Renewed
                                    </span>

                                    @break

                                @case('Cancelled')

                                    <span class="badge bg-dark">
                                        Cancelled
                                    </span>

                                    @break

                                @default

                                    <span class="badge bg-secondary">
                                        {{ $agreement->agreement_status }}
                                    </span>

                            @endswitch

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Tenant --}}
        <div class="col-lg-4">

            <div class="card h-100">

                <div class="card-header">

                    <h5 class="mb-0">
                        <i class="fas fa-building me-1"></i>
                        Tenant
                    </h5>

                </div>

                <div class="card-body">

                    @if($agreement->tenant)

                        <div class="mb-3">

                            <small class="text-muted">
                                Company
                            </small>

                            <div class="fw-bold fs-5">

                                {{ $agreement->tenant->company_name }}

                            </div>

                        </div>


                        @if(!empty($agreement->tenant->brand_name))

                            <div class="mb-3">

                                <small class="text-muted">
                                    Brand Name
                                </small>

                                <div>
                                    {{ $agreement->tenant->brand_name }}
                                </div>

                            </div>

                        @endif


                        @if(!empty($agreement->tenant->contact_person))

                            <div class="mb-3">

                                <small class="text-muted">
                                    Contact Person
                                </small>

                                <div>
                                    {{ $agreement->tenant->contact_person }}
                                </div>

                            </div>

                        @endif


                        @if(!empty($agreement->tenant->mobile))

                            <div>

                                <small class="text-muted">
                                    Mobile
                                </small>

                                <div>
                                    {{ $agreement->tenant->mobile }}
                                </div>

                            </div>

                        @endif

                    @else

                        <span class="text-muted">
                            Tenant information not available.
                        </span>

                    @endif

                </div>

            </div>

        </div>


        {{-- Proposal --}}
        <div class="col-lg-4">

            <div class="card h-100">

                <div class="card-header">

                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-1"></i>
                        Source Proposal
                    </h5>

                </div>

                <div class="card-body">

                    @if($agreement->proposal)

                        <div class="mb-3">

                            <small class="text-muted">
                                Proposal No.
                            </small>

                            <div class="fw-bold fs-5">

                                <a href="{{ route(
                                    'admin.leasing.proposals.show',
                                    $agreement->proposal->id
                                ) }}"
                                   class="text-decoration-none">

                                    {{ $agreement->proposal->proposal_no }}

                                </a>

                            </div>

                        </div>


                        <div class="mb-3">

                            <small class="text-muted">
                                Proposal Date
                            </small>

                            <div>

                                {{ $agreement->proposal->proposal_date
                                    ? \Carbon\Carbon::parse(
                                        $agreement->proposal->proposal_date
                                    )->format('d M Y')
                                    : '-'
                                }}

                            </div>

                        </div>


                        <div>

                            <small class="text-muted">
                                Proposal Status
                            </small>

                            <div>

                                <span class="badge bg-success">
                                    {{ $agreement->proposal->proposal_status }}
                                </span>

                            </div>

                        </div>

                    @else

                        <span class="text-muted">
                            Source proposal not available.
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- LEASE PERIOD --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fas fa-calendar-alt me-1"></i>

                Lease Period

            </h5>

        </div>


        <div class="card-body">

            <div class="row">


                <div class="col-md-3">

                    <small class="text-muted">
                        Lease Start
                    </small>

                    <div class="fw-semibold">

                        {{ $agreement->lease_start_date
                            ? $agreement->lease_start_date->format('d M Y')
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Lease End
                    </small>

                    <div class="fw-semibold">

                        {{ $agreement->lease_end_date
                            ? $agreement->lease_end_date->format('d M Y')
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Lease Period
                    </small>

                    <div class="fw-semibold">

                        {{ $agreement->lease_period_months ?? 0 }}
                        Months

                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Rent Start
                    </small>

                    <div class="fw-semibold">

                        {{ $agreement->rent_start_date
                            ? $agreement->rent_start_date->format('d M Y')
                            : '-'
                        }}

                    </div>

                </div>

            </div>

            <hr>


            <div class="row">


                <div class="col-md-3">

                    <small class="text-muted">
                        Handover Date
                    </small>

                    <div>

                        {{ $agreement->handover_date
                            ? $agreement->handover_date->format('d M Y')
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Fit-out Start
                    </small>

                    <div>

                        {{ $agreement->fitout_start_date
                            ? $agreement->fitout_start_date->format('d M Y')
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Fit-out End
                    </small>

                    <div>

                        {{ $agreement->fitout_end_date
                            ? $agreement->fitout_end_date->format('d M Y')
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Rent Free Period
                    </small>

                    <div>

                        {{ $agreement->rent_free_days ?? 0 }}
                        Days

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- UNITS FROM PROPOSAL --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fas fa-store me-1"></i>

                Leased Units

            </h5>

        </div>


        <div class="card-body p-0">

            @if(
                $agreement->proposal &&
                $agreement->proposal->proposalUnits &&
                $agreement->proposal->proposalUnits->count()
            )

                <div class="table-responsive">

                    <table class="table table-bordered mb-0">

                        <thead class="table-light">

                            <tr>

                                <th width="70">
                                    #
                                </th>

                                <th>
                                    Unit No.
                                </th>

                                <th>
                                    Shop Name
                                </th>

                                <th>
                                    Carpet Area
                                </th>

                                <th>
                                    Built-up Area
                                </th>

                                <th>
                                    Proposed Rent
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $agreement->proposal->proposalUnits
                                as $index => $proposalUnit
                            )

                                <tr>

                                    <td>
                                        {{ $index + 1 }}
                                    </td>


                                    <td>

                                        @if($proposalUnit->unit)

                                            <strong>
                                                {{ $proposalUnit->unit->unit_no }}
                                            </strong>

                                        @else

                                            -

                                        @endif

                                    </td>


                                    <td>

                                        @if($proposalUnit->unit)

                                            {{ $proposalUnit->unit->shop_name ?? '-' }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    <td>

                                        @if($proposalUnit->unit)

                                            {{ number_format(
                                                $proposalUnit->unit->carpet_area ?? 0,
                                                2
                                            ) }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    <td>

                                        @if($proposalUnit->unit)

                                            {{ number_format(
                                                $proposalUnit->unit->builtup_area ?? 0,
                                                2
                                            ) }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    <td>

                                        ${{ number_format(
                                            $proposalUnit->proposed_rent ?? 0,
                                            2
                                        ) }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center text-muted py-4">

                    <i class="fas fa-store fa-2x mb-2"></i>

                    <div>
                        No units linked to the source proposal.
                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FINANCIAL DETAILS --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fas fa-rupee-sign me-1"></i>

                Financial Details

            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-3">

                    <small class="text-muted">
                        Monthly Rent
                    </small>

                    <div class="fs-5 fw-bold">

                        ${{ number_format(
                            $agreement->monthly_rent ?? 0,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        CAM Amount
                    </small>

                    <div class="fs-5 fw-bold">

                        ${{ number_format(
                            $agreement->cam_amount ?? 0,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Security Deposit
                    </small>

                    <div class="fs-5 fw-bold">

                        ${{ number_format(
                            $agreement->security_deposit ?? 0,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Utility Deposit
                    </small>

                    <div class="fs-5 fw-bold">

                        ${{ number_format(
                            $agreement->utility_deposit ?? 0,
                            2
                        ) }}

                    </div>

                </div>

            </div>


            <hr>


            <div class="row">


                <div class="col-md-4">

                    <small class="text-muted">
                        Monthly Billing
                    </small>

                    <div class="fw-semibold">

                        ${{ number_format(
                            (
                                $agreement->monthly_rent ?? 0
                            ) +
                            (
                                $agreement->cam_amount ?? 0
                            ),
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted">
                        Billing Frequency
                    </small>

                    <div class="fw-semibold">

                        {{ $agreement->billing_frequency ?? 'Monthly' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted">
                        Payment Due Day
                    </small>

                    <div class="fw-semibold">

                        Day
                        {{ $agreement->payment_due_day ?? 5 }}

                        of every
                        {{ $agreement->billing_frequency ?? 'Month' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- REMARKS --}}
    {{-- ========================================================= --}}

    @if($agreement->remarks)

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-comment-alt me-1"></i>

                    Remarks

                </h5>

            </div>

            <div class="card-body">

                {!! nl2br(
                    e($agreement->remarks)
                ) !!}

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- AUDIT --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fas fa-history me-1"></i>

                Record Information

            </h5>

        </div>


        <div class="card-body">

            <div class="row">


                <div class="col-md-4">

                    <small class="text-muted">
                        Created At
                    </small>

                    <div>

                        {{ $agreement->created_at
                            ? $agreement->created_at->format('d M Y H:i')
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted">
                        Last Updated
                    </small>

                    <div>

                        {{ $agreement->updated_at
                            ? $agreement->updated_at->format('d M Y H:i')
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted">
                        Agreement UUID
                    </small>

                    <div class="text-muted small">

                        {{ $agreement->uuid }}

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Documents --}}
    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                <i class="fas fa-folder-open me-1"></i>

                Lease Documents

            </h5>


            <a href="{{ route(
                'admin.leasing.documents.create',
                ['lease_agreement_id' => $agreement->id]
            ) }}"
               class="btn btn-primary btn-sm">

                <i class="fas fa-upload me-1"></i>

                Upload Document

            </a>

        </div>


        <div class="card-body p-0">

            @if($agreement->documents->count())

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>Document Type</th>

                                <th>Document Name</th>

                                <th>Version</th>

                                <th>Expiry</th>

                                <th>Status</th>

                                <th width="150">Action</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($agreement->documents as $document)

                                <tr>

                                    <td>

                                        {{ $loop->iteration }}

                                    </td>


                                    {{-- Document Type --}}

                                    <td>

                                        {{ $document->documentType?->document_name ?? '-' }}

                                    </td>


                                    {{-- Document Name --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $document->document_name }}

                                        </div>


                                        @if($document->document_number)

                                            <small class="text-muted">

                                                No:
                                                {{ $document->document_number }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- Version --}}

                                    <td>

                                        <span class="badge bg-secondary">

                                            v{{ $document->version_no ?? 1 }}

                                        </span>

                                    </td>


                                    {{-- Expiry --}}

                                    <td>

                                        @if($document->expiry_date)

                                            @if($document->expiry_date->isPast())

                                                <span class="text-danger fw-semibold">

                                                    {{ $document->expiry_date->format(
                                                        'd M Y'
                                                    ) }}

                                                    <small>
                                                        (Expired)
                                                    </small>

                                                </span>

                                            @else

                                                {{ $document->expiry_date->format(
                                                    'd M Y'
                                                ) }}

                                            @endif

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- Verification --}}

                                    <td>

                                        @if(
                                            $document->verification_status ===
                                            'Verified'
                                        )

                                            <span class="badge bg-success">

                                                <i class="fas fa-check me-1"></i>

                                                Verified

                                            </span>

                                        @elseif(
                                            $document->verification_status ===
                                            'Rejected'
                                        )

                                            <span class="badge bg-danger">

                                                <i class="fas fa-times me-1"></i>

                                                Rejected

                                            </span>

                                        @else

                                            <span class="badge bg-warning text-dark">

                                                <i class="fas fa-clock me-1"></i>

                                                Pending

                                            </span>

                                        @endif

                                    </td>


                                    {{-- Actions --}}

                                    <td>

                                        <div class="d-flex gap-1">


                                            {{-- View --}}

                                            <a href="{{ route(
                                                'admin.leasing.documents.show',
                                                $document->id
                                            ) }}"
                                               class="btn btn-sm btn-info"
                                               title="View">

                                                <i class="fas fa-eye"></i>

                                            </a>


                                            {{-- Edit --}}

                                            <a href="{{ route(
                                                'admin.leasing.documents.edit',
                                                $document->id
                                            ) }}"
                                               class="btn btn-sm btn-primary"
                                               title="Edit">

                                                <i class="fas fa-edit"></i>

                                            </a>


                                            {{-- Download --}}

                                            @if($document->file_path)

                                                <a href="{{ asset(
                                                    'storage/' .
                                                    $document->file_path
                                                ) }}"
                                                   download
                                                   class="btn btn-sm btn-success"
                                                   title="Download">

                                                    <i class="fas fa-download"></i>

                                                </a>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>

                    <h6>
                        No documents uploaded
                    </h6>

                    <p class="text-muted mb-3">

                        Upload the required lease documents
                        for this agreement.

                    </p>


                    <a href="{{ route(
                        'admin.leasing.documents.create',
                        ['lease_agreement_id' => $agreement->id]
                    ) }}"
                       class="btn btn-primary">

                        <i class="fas fa-upload me-1"></i>

                        Upload First Document

                    </a>

                </div>

            @endif

        </div>

    </div>

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                Lease Terms
            </h5>

        </div>

        <div class="card-body">

            @if($agreement->terms)

                <div class="row g-4">

                    <div class="col-md-3">
                        <small class="text-muted">
                            Lock-in Period
                        </small>

                        <div>
                            {{ $agreement->terms->lock_in_period_months ?? 0 }}
                            Months
                        </div>
                    </div>


                    <div class="col-md-3">
                        <small class="text-muted">
                            Notice Period
                        </small>

                        <div>
                            {{ $agreement->terms->notice_period_days ?? 0 }}
                            Days
                        </div>
                    </div>


                    <div class="col-md-3">
                        <small class="text-muted">
                            Escalation
                        </small>

                        <div>
                            {{ $agreement->terms->escalation_percentage ?? 0 }}%
                        </div>
                    </div>


                    <div class="col-md-3">
                        <small class="text-muted">
                            Escalation Frequency
                        </small>

                        <div>
                            {{ $agreement->terms->escalation_frequency ?? '-' }}
                        </div>
                    </div>


                    <div class="col-md-3">
                        <small class="text-muted">
                            Billing Cycle
                        </small>

                        <div>
                            {{ $agreement->terms->billing_cycle ?? '-' }}
                        </div>
                    </div>


                    <div class="col-md-3">
                        <small class="text-muted">
                            Payment Due
                        </small>

                        <div>
                            {{ $agreement->terms->payment_due_days ?? 0 }}
                            Days
                        </div>
                    </div>


                    <div class="col-md-3">
                        <small class="text-muted">
                            Maintenance
                        </small>

                        <div>
                            {{ $agreement->terms->maintenance_responsibility ?? '-' }}
                        </div>
                    </div>


                    <div class="col-md-3">
                        <small class="text-muted">
                            Insurance
                        </small>

                        <div>
                            {{ $agreement->terms->insurance_required ?? '-' }}
                        </div>
                    </div>

                </div>

            @else

                <div class="text-muted">
                    No lease terms configured.
                </div>

            @endif

        </div>

    </div>

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between">

            <h5 class="mb-0">
                Lease Renewals
            </h5>

            <a href="{{ route(
                'admin.leasing.renewals.create'
            ) }}"
               class="btn btn-sm btn-primary">

                New Renewal

            </a>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>
                            <th>Renewal No.</th>
                            <th>Request Date</th>
                            <th>Proposed Start</th>
                            <th>Proposed End</th>
                            <th>Proposed Rent</th>
                            <th>Status</th>
                        </tr>

                    </thead>

                    <tbody>

                    @forelse($agreement->renewals as $renewal)

                        <tr>

                            <td>
                                {{ $renewal->renewal_no }}
                            </td>

                            <td>
                                {{ $renewal->request_date
                                    ? $renewal->request_date->format('d M Y')
                                    : '-' }}
                            </td>

                            <td>
                                {{ $renewal->proposed_start_date
                                    ? $renewal->proposed_start_date->format('d M Y')
                                    : '-' }}
                            </td>

                            <td>
                                {{ $renewal->proposed_end_date
                                    ? $renewal->proposed_end_date->format('d M Y')
                                    : '-' }}
                            </td>

                            <td>
                                ${{ number_format(
                                    $renewal->proposed_rent ?? 0,
                                    2
                                ) }}
                            </td>

                            <td>

                                <span class="badge bg-secondary">
                                    {{ $renewal->approval_status }}
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6"
                                class="text-center text-muted py-4">
                                No renewals found.
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                Rent Escalations
            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>
                            <th>No.</th>
                            <th>Effective From</th>
                            <th>Previous Rent</th>
                            <th>Escalation</th>
                            <th>Revised Rent</th>
                            <th>Status</th>
                        </tr>

                    </thead>

                    <tbody>

                    @forelse($agreement->escalations as $escalation)

                        <tr>

                            <td>
                                #{{ $escalation->escalation_no }}
                            </td>

                            <td>
                                {{ $escalation->effective_from
                                    ? $escalation->effective_from->format('d M Y')
                                    : '-' }}
                            </td>

                            <td>
                                ${{ number_format(
                                    $escalation->previous_rent ?? 0,
                                    2
                                ) }}
                            </td>

                            <td>

                                @if(
                                    $escalation->escalation_type
                                    === 'Percentage'
                                )

                                    {{ $escalation->escalation_value }}%

                                @else

                                    ${{ number_format(
                                        $escalation->escalation_value ?? 0,
                                        2
                                    ) }}

                                @endif

                            </td>

                            <td class="fw-bold">

                                ${{ number_format(
                                    $escalation->revised_rent ?? 0,
                                    2
                                ) }}

                            </td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ $escalation->status }}
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center text-muted py-4">

                                No escalations found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                Lease Terminations
            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>
                            <th>Termination No.</th>
                            <th>Type</th>
                            <th>Request Date</th>
                            <th>Effective Date</th>
                            <th>Inspection</th>
                            <th>Handover</th>
                            <th>Status</th>
                        </tr>

                    </thead>

                    <tbody>

                    @forelse($agreement->terminations as $termination)

                        <tr>

                            <td>
                                {{ $termination->termination_no }}
                            </td>

                            <td>
                                {{ $termination->termination_type }}
                            </td>

                            <td>
                                {{ $termination->request_date
                                    ? $termination->request_date->format('d M Y')
                                    : '-' }}
                            </td>

                            <td>
                                {{ $termination->effective_date
                                    ? $termination->effective_date->format('d M Y')
                                    : '-' }}
                            </td>

                            <td>

                                <span class="badge
                                    {{ $termination->inspection_status === 'Completed'
                                        ? 'bg-success'
                                        : 'bg-warning text-dark' }}">

                                    {{ $termination->inspection_status }}

                                </span>

                            </td>

                            <td>

                                <span class="badge
                                    {{ $termination->handover_status === 'Completed'
                                        ? 'bg-success'
                                        : 'bg-warning text-dark' }}">

                                    {{ $termination->handover_status }}

                                </span>

                            </td>

                            <td>

                                <span class="badge bg-secondary">

                                    {{ $termination->termination_status }}

                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="text-center text-muted py-4">

                                No termination records found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                Lease History
            </h5>

        </div>


        <div class="card-body">

            @forelse($agreement->history as $history)

                <div class="border-bottom pb-3 mb-3">

                    <div class="d-flex justify-content-between">

                        <strong>
                            {{ $history->activity_title }}
                        </strong>

                        <small class="text-muted">

                            {{ $history->activity_date
                                ? $history->activity_date->format('d M Y H:i')
                                : '-' }}

                        </small>

                    </div>


                    <div class="mt-1">

                        <span class="badge bg-secondary">

                            {{ $history->activity_type }}

                        </span>

                    </div>


                    @if($history->activity_description)

                        <div class="text-muted mt-2">

                            {{ $history->activity_description }}

                        </div>

                    @endif


                    @if($history->performed_by)

                        <small class="text-muted">

                            By:
                            {{ $history->performer?->name ?? 'User' }}

                        </small>

                    @endif

                </div>

            @empty

                <div class="text-muted">

                    No history available.

                </div>

            @endforelse

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DELETE --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-end mb-4">

        <form method="POST"
              action="{{ route(
                  'admin.leasing.agreements.destroy',
                  $agreement->id
              ) }}"
              onsubmit="return confirm(
                  'Are you sure you want to delete this agreement?'
              );">

            @csrf

            @method('DELETE')

            <button type="submit"
                    class="btn btn-outline-danger">

                <i class="fas fa-trash me-1"></i>

                Delete Agreement

            </button>

        </form>

    </div>

</div>

@endsection