@extends('layouts.app')

@section('title', 'Lease Renewal Details')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Lease Renewal
            </h4>

            <div class="text-muted">

                {{ $renewal->renewal_no }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.leasing.renewals.index'
            ) }}"
               class="btn btn-secondary">

                <i class="fas fa-arrow-left me-1"></i>

                Back

            </a>

        </div>

    </div>


    {{-- Success --}}

    @if(session('success'))

        <div class="alert alert-success">

            <i class="fas fa-check-circle me-1"></i>

            {{ session('success') }}

        </div>

    @endif

    @if(session('error'))

        <div class="alert alert-danger">

            <i class="fas fa-exclamation-circle me-1"></i>

            {{ session('error') }}

        </div>

    @endif


    {{-- Basic Information --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fas fa-info-circle me-1"></i>

                Renewal Information

            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-3">

                    <div class="text-muted small">
                        Renewal No.
                    </div>

                    <div class="fw-semibold">
                        {{ $renewal->renewal_no }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Request Date
                    </div>

                    <div class="fw-semibold">

                        {{ $renewal->request_date
                            ? $renewal->request_date->format(
                                'd M Y'
                            )
                            : '-' }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Agreement
                    </div>

                    <div class="fw-semibold">

                        @if($renewal->agreement)

                            <a href="{{ route(
                                'admin.leasing.agreements.show',
                                $renewal->agreement->id
                            ) }}">

                                {{ $renewal->agreement->agreement_no }}

                            </a>

                        @else

                            -

                        @endif

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Status
                    </div>

                    <div>

                        @if($renewal->approval_status === 'Draft')

                            <span class="badge bg-secondary">
                                Draft
                            </span>

                        @elseif($renewal->approval_status === 'Pending')

                            <span class="badge bg-warning text-dark">
                                Pending
                            </span>

                        @elseif($renewal->approval_status === 'Approved')

                            <span class="badge bg-success">
                                Approved
                            </span>

                        @elseif($renewal->approval_status === 'Rejected')

                            <span class="badge bg-danger">
                                Rejected
                            </span>

                        @else

                            <span class="badge bg-dark">
                                Cancelled
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Tenant --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fas fa-user me-1"></i>

                Tenant & Agreement

            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-4">

                    <div class="text-muted small">
                        Tenant
                    </div>

                    <div class="fw-semibold">

                        {{ $renewal->agreement?->tenant?->company_name
                            ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Current Lease Start
                    </div>

                    <div class="fw-semibold">

                        {{ $renewal->agreement?->lease_start_date
                            ? $renewal->agreement->lease_start_date
                                ->format('d M Y')
                            : '-' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Current Lease Expiry
                    </div>

                    <div class="fw-semibold text-danger">

                        {{ $renewal->current_expiry_date
                            ? $renewal->current_expiry_date->format(
                                'd M Y'
                            )
                            : '-' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Renewal Comparison --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fas fa-exchange-alt me-1"></i>

                Renewal Comparison

            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="30%">
                                Particular
                            </th>

                            <th>
                                Current
                            </th>

                            <th>
                                Proposed Renewal
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr>

                            <td>
                                Lease Start Date
                            </td>

                            <td>

                                {{ $renewal->agreement?->lease_start_date
                                    ? $renewal->agreement
                                        ->lease_start_date
                                        ->format('d M Y')
                                    : '-' }}

                            </td>

                            <td>

                                {{ $renewal->proposed_start_date
                                    ? $renewal->proposed_start_date
                                        ->format('d M Y')
                                    : '-' }}

                            </td>

                        </tr>


                        <tr>

                            <td>
                                Lease End Date
                            </td>

                            <td>

                                {{ $renewal->current_expiry_date
                                    ? $renewal->current_expiry_date
                                        ->format('d M Y')
                                    : '-' }}

                            </td>

                            <td class="fw-semibold">

                                {{ $renewal->proposed_end_date
                                    ? $renewal->proposed_end_date
                                        ->format('d M Y')
                                    : '-' }}

                            </td>

                        </tr>


                        <tr>

                            <td>
                                Lease Period
                            </td>

                            <td>
                                -
                            </td>

                            <td>

                                {{ $renewal->renewal_period_months
                                    ?? '-' }}

                                @if($renewal->renewal_period_months)
                                    Months
                                @endif

                            </td>

                        </tr>


                        <tr>

                            <td>
                                Monthly Rent
                            </td>

                            <td>

                                ${{ number_format(
                                    $renewal->current_rent ?? 0,
                                    2
                                ) }}

                            </td>

                            <td class="fw-semibold">

                                ${{ number_format(
                                    $renewal->proposed_rent ?? 0,
                                    2
                                ) }}

                            </td>

                        </tr>


                        <tr>

                            <td>
                                Security Deposit
                            </td>

                            <td>

                                ${{ number_format(
                                    $renewal->agreement
                                        ?->security_deposit ?? 0,
                                    2
                                ) }}

                            </td>

                            <td>

                                ${{ number_format(
                                    $renewal
                                        ->proposed_security_deposit ?? 0,
                                    2
                                ) }}

                            </td>

                        </tr>


                        <tr>

                            <td>
                                Escalation
                            </td>

                            <td>
                                -
                            </td>

                            <td>

                                {{ number_format(
                                    $renewal
                                        ->escalation_percentage ?? 0,
                                    2
                                ) }}%

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Negotiation --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fas fa-comments me-1"></i>

                Negotiation & Remarks

            </h5>

        </div>


        <div class="card-body">


            <div class="mb-4">

                <div class="text-muted small mb-1">
                    Negotiation Notes
                </div>

                <div>

                    {!! nl2br(
                        e(
                            $renewal->negotiation_notes
                            ?? '-'
                        )
                    ) !!}

                </div>

            </div>


            <div>

                <div class="text-muted small mb-1">
                    Remarks
                </div>

                <div>

                    {!! nl2br(
                        e(
                            $renewal->remarks
                            ?? '-'
                        )
                    ) !!}

                </div>

            </div>

        </div>

    </div>


    {{-- Approval Information --}}

    @if($renewal->approved_at || $renewal->approvedBy)

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-check-circle me-1"></i>

                    Approval Information

                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <div class="text-muted small">
                            Approved By
                        </div>

                        <div class="fw-semibold">

                            {{ $renewal->approvedBy?->name ?? '-' }}

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Approved At
                        </div>

                        <div class="fw-semibold">

                            {{ $renewal->approved_at
                                ? $renewal->approved_at->format(
                                    'd M Y H:i'
                                )
                                : '-' }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- Actions --}}

    <div class="card mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-end gap-2">


                @if($renewal->approval_status === 'Draft')

                    <a href="{{ route(
                        'admin.leasing.renewals.edit',
                        $renewal->id
                    ) }}"
                       class="btn btn-primary">

                        <i class="fas fa-edit me-1"></i>

                        Edit

                    </a>


                    <form method="POST"
                          action="{{ route(
                              'admin.leasing.renewals.submit',
                              $renewal->id
                          ) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-warning"
                                onclick="return confirm(
                                    'Submit this renewal for approval?'
                                );">

                            <i class="fas fa-paper-plane me-1"></i>

                            Submit for Approval

                        </button>

                    </form>

                @endif


                @if($renewal->approval_status === 'Pending')

                    <form method="POST"
                          action="{{ route(
                              'admin.leasing.renewals.approve',
                              $renewal->id
                          ) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-success"
                                onclick="return confirm(
                                    'Approve this lease renewal?'
                                );">

                            <i class="fas fa-check me-1"></i>

                            Approve

                        </button>

                    </form>


                    <form method="POST"
                          action="{{ route(
                              'admin.leasing.renewals.reject',
                              $renewal->id
                          ) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-danger"
                                onclick="return confirm(
                                    'Reject this lease renewal?'
                                );">

                            <i class="fas fa-times me-1"></i>

                            Reject

                        </button>

                    </form>

                @endif

                @if($renewal->approval_status === 'Approved')

                    <a href="{{ route(
                        'admin.leasing.renewals.convert',
                        $renewal->id
                    ) }}"
                       class="btn btn-success">

                        <i class="fas fa-file-contract me-1"></i>

                        Create Renewed Agreement

                    </a>

                @endif


                @if(
                    in_array(
                        $renewal->approval_status,
                        ['Draft', 'Pending']
                    )
                )

                    <form method="POST"
                          action="{{ route(
                              'admin.leasing.renewals.cancel',
                              $renewal->id
                          ) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-outline-danger"
                                onclick="return confirm(
                                    'Cancel this renewal request?'
                                );">

                            <i class="fas fa-ban me-1"></i>

                            Cancel

                        </button>

                    </form>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection