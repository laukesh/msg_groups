@extends('layouts.app')

@section('title', 'Lease Proposal Details')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h4 class="mb-1">
                Lease Proposal
                {{ $proposal->proposal_no }}
            </h4>

            <p class="text-muted mb-0">
                Proposal details and selected units
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.leasing.proposals.index') }}"
               class="btn btn-secondary">

                <i class="fas fa-arrow-left"></i>
                Back

            </a>

            @if($proposal->proposal_status === 'Draft')

                <a href="#"
                   class="btn btn-primary">

                    <i class="fas fa-edit"></i>
                    Edit

                </a>

            @endif

        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    <div class="row">

        {{-- Left Side --}}
        <div class="col-md-8">

            {{-- Proposal Information --}}
            <div class="card mb-3">

                <div class="card-header d-flex justify-content-between">

                    <h5 class="mb-0">
                        Proposal Information
                    </h5>

                    @php
                        $statusClass = match($proposal->proposal_status) {
                            'Draft' => 'secondary',
                            'Submitted' => 'primary',
                            'Under Review' => 'warning',
                            'Approved' => 'success',
                            'Rejected' => 'danger',
                            'Expired' => 'dark',
                            'Converted' => 'success',
                            default => 'secondary',
                        };
                    @endphp

                    <span class="badge bg-{{ $statusClass }}">
                        {{ $proposal->proposal_status }}
                    </span>

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="text-muted">
                                Proposal No.
                            </label>

                            <div class="fw-bold">
                                {{ $proposal->proposal_no }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted">
                                Proposal Date
                            </label>

                            <div>
                                {{ $proposal->proposal_date?->format('d M Y') }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted">
                                Valid Until
                            </label>

                            <div>
                                {{ $proposal->valid_until?->format('d M Y') ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted">
                                Lease Start Date
                            </label>

                            <div>
                                {{ $proposal->lease_start_date?->format('d M Y') ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted">
                                Lease End Date
                            </label>

                            <div>
                                {{ $proposal->lease_end_date?->format('d M Y') ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted">
                                Lease Period
                            </label>

                            <div>
                                {{ $proposal->lease_period_months
                                    ? $proposal->lease_period_months . ' Months'
                                    : '-' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Tenant --}}
            <div class="card mb-3">

                <div class="card-header">

                    <h5 class="mb-0">
                        Tenant Information
                    </h5>

                </div>

                <div class="card-body">

                    @if($proposal->tenant)

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted">
                                    Company Name
                                </label>

                                <div class="fw-bold">
                                    {{ $proposal->tenant->company_name }}
                                </div>

                            </div>


                            <div class="col-md-6">

                                <label class="text-muted">
                                    Brand Name
                                </label>

                                <div>
                                    {{ $proposal->tenant->brand_name ?? '-' }}
                                </div>

                            </div>


                            <div class="col-md-6">

                                <label class="text-muted">
                                    GST Number
                                </label>

                                <div>
                                    {{ $proposal->tenant->gst_number ?? '-' }}
                                </div>

                            </div>


                            <div class="col-md-6">

                                <label class="text-muted">
                                    Phone
                                </label>

                                <div>
                                    {{ $proposal->tenant->phone ?? '-' }}
                                </div>

                            </div>


                            <div class="col-md-6">

                                <label class="text-muted">
                                    Email
                                </label>

                                <div>
                                    {{ $proposal->tenant->email ?? '-' }}
                                </div>

                            </div>

                        </div>

                    @else

                        <div class="text-muted">
                            Tenant information not available.
                        </div>

                    @endif

                </div>

            </div>


            {{-- Units --}}
            <div class="card mb-3">

                <div class="card-header">

                    <h5 class="mb-0">
                        Proposed Units
                    </h5>

                </div>

                <div class="card-body p-0">

                    @if($proposal->proposalUnits->count())

                        <div class="table-responsive">

                            <table class="table table-bordered mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th>
                                            #
                                        </th>

                                        <th>
                                            Unit No.
                                        </th>

                                        <th>
                                            Shop Name
                                        </th>

                                        <th>
                                            Area
                                        </th>

                                        <th>
                                            Proposed Rent
                                        </th>

                                        <th>
                                            Security Deposit
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach(
                                        $proposal->proposalUnits
                                        as $index => $proposalUnit
                                    )

                                        <tr>

                                            <td>
                                                {{ $index + 1 }}
                                            </td>

                                            <td>

                                                <strong>
                                                    {{ $proposalUnit->unit->unit_no ?? '-' }}
                                                </strong>

                                            </td>

                                            <td>
                                                {{ $proposalUnit->unit->shop_name ?? '-' }}
                                            </td>

                                            <td>
                                                {{ number_format(
                                                    $proposalUnit->unit->carpet_area ?? 0,
                                                    2
                                                ) }}
                                            </td>

                                            <td>

                                                ${{ number_format(
                                                    $proposalUnit->proposed_rent ?? 0,
                                                    2
                                                ) }}

                                            </td>

                                            <td>

                                                ${{ number_format(
                                                    $proposalUnit->proposed_security_deposit ?? 0,
                                                    2
                                                ) }}

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="p-4 text-center text-muted">

                            No units have been added to this proposal.

                        </div>

                    @endif

                </div>

            </div>


            {{-- Remarks --}}
            <div class="card mb-3">

                <div class="card-header">

                    <h5 class="mb-0">
                        Remarks
                    </h5>

                </div>

                <div class="card-body">

                    {{ $proposal->remarks ?? 'No remarks added.' }}

                </div>

            </div>

        </div>


        {{-- Right Side --}}
        <div class="col-md-4">

            {{-- Financial Summary --}}
            <div class="card mb-3">

                <div class="card-header">

                    <h5 class="mb-0">
                        Financial Summary
                    </h5>

                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">

                        <span>
                            Monthly Rent
                        </span>

                        <strong>
                            ${{ number_format(
                                $proposal->monthly_rent ?? 0,
                                2
                            ) }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span>
                            CAM Amount
                        </span>

                        <strong>
                            ${{ number_format(
                                $proposal->cam_amount ?? 0,
                                2
                            ) }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span>
                            Security Deposit
                        </span>

                        <strong>
                            ${{ number_format(
                                $proposal->security_deposit ?? 0,
                                2
                            ) }}
                        </strong>

                    </div>


                    <hr>


                    <div class="d-flex justify-content-between">

                        <strong>
                            Monthly Total
                        </strong>

                        <strong class="text-primary">

                            ${{ number_format(
                                ($proposal->monthly_rent ?? 0) +
                                ($proposal->cam_amount ?? 0),
                                2
                            ) }}

                        </strong>

                    </div>

                </div>

            </div>


            {{-- Lease Terms --}}
            <div class="card mb-3">

                <div class="card-header">

                    <h5 class="mb-0">
                        Lease Terms
                    </h5>

                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <small class="text-muted">
                            Fit-out Period
                        </small>

                        <div>
                            {{ $proposal->fitout_period_days ?? 0 }}
                            Days
                        </div>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted">
                            Rent Free Period
                        </small>

                        <div>
                            {{ $proposal->rent_free_days ?? 0 }}
                            Days
                        </div>

                    </div>


                    <div>

                        <small class="text-muted">
                            Annual Escalation
                        </small>

                        <div>
                            {{ $proposal->escalation_percentage ?? 0 }}%
                        </div>

                    </div>

                </div>

            </div>


            {{-- Approval --}}
            <div class="card mb-3">

                <div class="card-header">

                    <h5 class="mb-0">
                        Approval
                    </h5>

                </div>

                <div class="card-body">

                    @if($proposal->approvedBy)

                        <div class="mb-2">

                            <small class="text-muted">
                                Approved By
                            </small>

                            <div>
                                {{ $proposal->approvedBy->name }}
                            </div>

                        </div>


                        <div>

                            <small class="text-muted">
                                Approved At
                            </small>

                            <div>
                                {{ $proposal->approved_at?->format('d M Y H:i') }}
                            </div>

                        </div>

                    @else

                        <span class="text-muted">
                            Not approved yet.
                        </span>

                    @endif

                </div>

            </div>


            {{-- Actions --}}
            @if($proposal->proposal_status === 'Draft')

                <div class="card">

                    <div class="card-body">

                        <form method="POST"
                              action="#">

                            @csrf

                            <button type="submit"
                                    class="btn btn-primary w-100">

                                <i class="fas fa-paper-plane"></i>

                                Submit for Review

                            </button>

                        </form>

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection