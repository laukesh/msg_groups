@extends('layouts.app')

@section('title', 'Termination Details')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                {{ $termination->termination_no }}
            </h4>

            <div class="text-muted">
                Lease Termination
            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.leasing.terminations.index'
            ) }}"
               class="btn btn-secondary">

                <i class="fas fa-arrow-left me-1"></i>
                Back

            </a>


            @if(
                in_array(
                    $termination->termination_status,
                    ['Draft', 'Pending Approval']
                )
            )

                <a href="{{ route(
                    'admin.leasing.terminations.edit',
                    $termination->id
                ) }}"
                   class="btn btn-warning">

                    <i class="fas fa-edit me-1"></i>
                    Edit

                </a>

            @endif

        </div>

    </div>


    {{-- Messages --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    {{-- Status / Actions --}}

    <div class="card mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <span class="text-muted">
                        Termination Status
                    </span>

                    <div class="mt-1">

                        @php

                            $statusClass = match(
                                $termination->termination_status
                            ) {

                                'Draft'
                                    => 'bg-secondary',

                                'Pending Approval'
                                    => 'bg-warning text-dark',

                                'Approved'
                                    => 'bg-primary',

                                'Completed'
                                    => 'bg-success',

                                'Cancelled'
                                    => 'bg-danger',

                                default
                                    => 'bg-secondary',

                            };

                        @endphp

                        <span class="badge fs-6 {{ $statusClass }}">

                            {{ $termination->termination_status }}

                        </span>

                    </div>

                </div>


                <div class="d-flex gap-2">

                    {{-- Submit --}}

                    @if(
                        $termination->termination_status === 'Draft'
                    )

                        <form method="POST"
                              action="{{ route(
                                  'admin.leasing.terminations.submit',
                                  $termination->id
                              ) }}">

                            @csrf

                            <button type="submit"
                                    class="btn btn-primary"
                                    onclick="return confirm(
                                        'Submit this termination for approval?'
                                    )">

                                <i class="fas fa-paper-plane me-1"></i>

                                Submit for Approval

                            </button>

                        </form>

                    @endif


                    {{-- Approve --}}

                    @if(
                        $termination->termination_status ===
                        'Pending Approval'
                    )

                        <form method="POST"
                              action="{{ route(
                                  'admin.leasing.terminations.approve',
                                  $termination->id
                              ) }}">

                            @csrf

                            <button type="submit"
                                    class="btn btn-success"
                                    onclick="return confirm(
                                        'Are you sure you want to approve this termination? This will terminate the lease agreement.'
                                    )">

                                <i class="fas fa-check-circle me-1"></i>

                                Approve Termination

                            </button>

                        </form>

                    @endif


                    {{-- Cancel --}}

                    @if(
                        in_array(
                            $termination->termination_status,
                            ['Draft', 'Pending Approval']
                        )
                    )

                        <form method="POST"
                              action="{{ route(
                                  'admin.leasing.terminations.cancel',
                                  $termination->id
                              ) }}">

                            @csrf

                            <button type="submit"
                                    class="btn btn-danger"
                                    onclick="return confirm(
                                        'Are you sure you want to cancel this termination request?'
                                    )">

                                <i class="fas fa-times me-1"></i>

                                Cancel

                            </button>

                        </form>

                    @endif

                    @if(
                        $termination->termination_status === 'Approved' &&
                        $termination->inspection_status === 'Pending'
                    )

                        <form method="POST"
                              action="{{ route(
                                  'admin.leasing.terminations.completeInspection',
                                  $termination->id
                              ) }}">

                            @csrf

                            <button type="submit"
                                    class="btn btn-primary"
                                    onclick="return confirm(
                                        'Mark inspection as completed?'
                                    )">

                                <i class="fas fa-clipboard-check me-1"></i>

                                Complete Inspection

                            </button>

                        </form>

                    @endif

                    @if(
                        $termination->termination_status === 'Approved' &&
                        $termination->inspection_status === 'Completed' &&
                        $termination->handover_status === 'Pending'
                    )

                        <form method="POST"
                              action="{{ route(
                                  'admin.leasing.terminations.completeHandover',
                                  $termination->id
                              ) }}">

                            @csrf

                            <button type="submit"
                                    class="btn btn-info"
                                    onclick="return confirm(
                                        'Mark premises handover as completed?'
                                    )">

                                <i class="fas fa-key me-1"></i>

                                Complete Handover

                            </button>

                        </form>

                    @endif

                    @if(
                        $termination->termination_status === 'Approved' &&
                        $termination->inspection_status === 'Completed' &&
                        $termination->handover_status === 'Completed'
                    )

                        <form method="POST"
                              action="{{ route(
                                  'admin.leasing.terminations.complete',
                                  $termination->id
                              ) }}">

                            @csrf

                            <button type="submit"
                                    class="btn btn-success"
                                    onclick="return confirm(
                                        'Are you sure you want to complete this lease termination?'
                                    )">

                                <i class="fas fa-check-double me-1"></i>

                                Complete Termination

                            </button>

                        </form>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- Agreement Details --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                Lease Agreement
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <label class="text-muted small">
                        Agreement No.
                    </label>

                    <div class="fw-semibold">

                        {{ $termination->agreement?->agreement_no ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted small">
                        Tenant
                    </label>

                    <div class="fw-semibold">

                        {{ $termination->agreement?->tenant?->company_name
                            ?? $termination->agreement?->tenant?->name
                            ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted small">
                        Agreement Status
                    </label>

                    <div>

                        <span class="badge bg-secondary">

                            {{ $termination->agreement?->agreement_status ?? '-' }}

                        </span>

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted small">
                        Lease Start Date
                    </label>

                    <div>

                        {{ $termination->agreement?->lease_start_date
                            ? $termination->agreement->lease_start_date->format('d M Y')
                            : '-' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted small">
                        Lease End Date
                    </label>

                    <div>

                        {{ $termination->agreement?->lease_end_date
                            ? $termination->agreement->lease_end_date->format('d M Y')
                            : '-' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted small">
                        Monthly Rent
                    </label>

                    <div>

                        ${{ number_format(
                            $termination->agreement?->monthly_rent ?? 0,
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Termination Details --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                Termination Details
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <label class="text-muted small">
                        Termination No.
                    </label>

                    <div class="fw-semibold">

                        {{ $termination->termination_no }}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted small">
                        Termination Type
                    </label>

                    <div>

                        {{ $termination->termination_type }}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted small">
                        Request Date
                    </label>

                    <div>

                        {{ $termination->request_date
                            ? $termination->request_date->format('d M Y')
                            : '-' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted small">
                        Notice Date
                    </label>

                    <div>

                        {{ $termination->notice_date
                            ? $termination->notice_date->format('d M Y')
                            : '-' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted small">
                        Effective Date
                    </label>

                    <div class="fw-semibold">

                        {{ $termination->effective_date
                            ? $termination->effective_date->format('d M Y')
                            : '-' }}

                    </div>

                </div>


                <div class="col-md-12">

                    <label class="text-muted small">
                        Reason
                    </label>

                    <div>

                        {{ $termination->reason ?: '-' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Financial Settlement --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                Financial Settlement
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <label class="text-muted small">
                        Outstanding Amount
                    </label>

                    <div class="fs-5">

                        ${{ number_format(
                            $termination->outstanding_amount ?? 0,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted small">
                        Penalty Amount
                    </label>

                    <div class="fs-5">

                        ${{ number_format(
                            $termination->penalty_amount ?? 0,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted small">
                        Damage Charges
                    </label>

                    <div class="fs-5">

                        ${{ number_format(
                            $termination->damage_charges ?? 0,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted small">
                        Refundable Deposit
                    </label>

                    <div class="fs-5 text-success">

                        ${{ number_format(
                            $termination->refundable_deposit ?? 0,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted small">
                        Final Settlement
                    </label>

                    @php
                        $settlement =
                            $termination->final_settlement_amount ?? 0;
                    @endphp

                    <div class="fs-4 fw-bold
                        {{ $settlement > 0
                            ? 'text-danger'
                            : 'text-success' }}">

                        ${{ number_format(
                            abs($settlement),
                            2
                        ) }}

                    </div>

                    <small class="text-muted">

                        @if($settlement > 0)

                            Payable by tenant

                        @elseif($settlement < 0)

                            Refundable to tenant

                        @else

                            No balance

                        @endif

                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- Inspection / Handover --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                Exit Process
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="text-muted small">
                        Inspection Status
                    </label>

                    <div class="mt-1">

                        @if(
                            $termination->inspection_status === 'Completed'
                        )

                            <span class="badge bg-success">
                                Completed
                            </span>

                        @else

                            <span class="badge bg-warning text-dark">
                                Pending
                            </span>

                        @endif

                    </div>

                </div>


                <div class="col-md-6">

                    <label class="text-muted small">
                        Handover Status
                    </label>

                    <div class="mt-1">

                        @if(
                            $termination->handover_status === 'Completed'
                        )

                            <span class="badge bg-success">
                                Completed
                            </span>

                        @else

                            <span class="badge bg-warning text-dark">
                                Pending
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Approval --}}

    @if(
        $termination->approved_by ||
        $termination->approved_at
    )

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Approval Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-6">

                        <label class="text-muted small">
                            Approved By
                        </label>

                        <div>

                            {{ $termination->approver?->name ?? 'System' }}

                        </div>

                    </div>


                    <div class="col-md-6">

                        <label class="text-muted small">
                            Approved At
                        </label>

                        <div>

                            {{ $termination->approved_at
                                ? $termination->approved_at->format('d M Y H:i')
                                : '-' }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- Remarks --}}

    @if($termination->remarks)

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Remarks
                </h5>

            </div>

            <div class="card-body">

                {{ $termination->remarks }}

            </div>

        </div>

    @endif

</div>

@endsection