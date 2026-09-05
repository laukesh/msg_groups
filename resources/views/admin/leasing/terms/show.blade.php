@extends('layouts.app')

@section('title', 'Lease Terms Details')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Lease Terms Details</h4>

            <div class="text-muted">
                {{ $term->agreement?->agreement_no ?? '-' }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.leasing.terms.index') }}"
               class="btn btn-secondary">

                <i class="fas fa-arrow-left me-1"></i>
                Back

            </a>

            <a href="{{ route(
                'admin.leasing.terms.edit',
                $term->id
            ) }}"
               class="btn btn-primary">

                <i class="fas fa-edit me-1"></i>
                Edit

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


    {{-- Agreement --}}
    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                <i class="fas fa-file-contract me-1"></i>
                Lease Agreement
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Agreement No.
                    </div>

                    <div class="fw-semibold">
                        {{ $term->agreement?->agreement_no ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Tenant
                    </div>

                    <div class="fw-semibold">
                        {{ $term->agreement?->tenant?->company_name ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Agreement Status
                    </div>

                    @php
                        $status =
                            $term->agreement?->agreement_status;
                    @endphp

                    <span class="badge
                        @if($status === 'Active')
                            bg-success
                        @elseif($status === 'Draft')
                            bg-warning text-dark
                        @elseif($status === 'Expired')
                            bg-secondary
                        @elseif($status === 'Terminated')
                            bg-danger
                        @else
                            bg-info
                        @endif">

                        {{ $status ?? '-' }}

                    </span>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Lease Start
                    </div>

                    <div>
                        {{ $term->agreement?->lease_start_date?->format('d M Y') ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Lease End
                    </div>

                    <div>
                        {{ $term->agreement?->lease_end_date?->format('d M Y') ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Monthly Rent
                    </div>

                    <div class="fw-semibold">
                        ${{ number_format(
                            $term->agreement?->monthly_rent ?? 0,
                            2
                        ) }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Lock-in --}}
    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                <i class="fas fa-clock me-1"></i>
                Lock-in & Notice
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Lock-in Period
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{ $term->lock_in_period_months ?? 0 }}
                        Months

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Notice Period
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{ $term->notice_period_days ?? 0 }}
                        Days

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Grace Period
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{ $term->grace_period_days ?? 0 }}
                        Days

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Escalation --}}
    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                <i class="fas fa-chart-line me-1"></i>
                Rent Escalation
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-6">

                    <div class="text-muted small">
                        Escalation Frequency
                    </div>

                    <div class="fw-semibold">
                        {{ $term->escalation_frequency ?? '-' }}
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Escalation Percentage
                    </div>

                    <div class="fw-semibold">

                        {{ number_format(
                            $term->escalation_percentage ?? 0,
                            2
                        ) }}%

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Billing --}}
    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                <i class="fas fa-money-bill-wave me-1"></i>
                Billing & Late Fee
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Billing Cycle
                    </div>

                    <div class="fw-semibold">
                        {{ $term->billing_cycle ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Payment Due
                    </div>

                    <div class="fw-semibold">

                        {{ $term->payment_due_days ?? 0 }}
                        Days

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Grace Period
                    </div>

                    <div class="fw-semibold">

                        {{ $term->grace_period_days ?? 0 }}
                        Days

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Late Fee Type
                    </div>

                    <div class="fw-semibold">
                        {{ $term->late_fee_type ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Late Fee Value
                    </div>

                    <div class="fw-semibold">

                        @if($term->late_fee_type === 'Fixed')

                            ${{ number_format(
                                $term->late_fee_value ?? 0,
                                2
                            ) }}

                        @else

                            {{ number_format(
                                $term->late_fee_value ?? 0,
                                2
                            ) }}%

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- CAM / Utility --}}
    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                <i class="fas fa-cogs me-1"></i>
                CAM & Utility
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-6">

                    <div class="text-muted small">
                        CAM Calculation Method
                    </div>

                    <div class="fw-semibold">
                        {{ $term->cam_calculation_method ?? '-' }}
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Utility Billing Method
                    </div>

                    <div class="fw-semibold">
                        {{ $term->utility_billing_method ?? '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Responsibilities --}}
    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                <i class="fas fa-users-cog me-1"></i>
                Responsibilities & Restrictions
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Maintenance Responsibility
                    </div>

                    <span class="badge bg-secondary">

                        {{ $term->maintenance_responsibility ?? '-' }}

                    </span>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Insurance Required
                    </div>

                    <span class="badge
                        {{ $term->insurance_required === 'Yes'
                            ? 'bg-success'
                            : 'bg-secondary' }}">

                        {{ $term->insurance_required ?? '-' }}

                    </span>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Subletting Allowed
                    </div>

                    <span class="badge
                        {{ $term->subletting_allowed === 'Yes'
                            ? 'bg-warning text-dark'
                            : 'bg-secondary' }}">

                        {{ $term->subletting_allowed ?? '-' }}

                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- Additional Terms --}}
    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                <i class="fas fa-file-alt me-1"></i>
                Additional Terms
            </h5>

        </div>

        <div class="card-body">

            <div class="mb-4">

                <div class="text-muted small mb-1">
                    Termination Clause
                </div>

                <div class="border rounded p-3 bg-light">

                    {!! nl2br(e(
                        $term->termination_clause ?? '-'
                    )) !!}

                </div>

            </div>


            <div class="mb-4">

                <div class="text-muted small mb-1">
                    Special Terms
                </div>

                <div class="border rounded p-3 bg-light">

                    {!! nl2br(e(
                        $term->special_terms ?? '-'
                    )) !!}

                </div>

            </div>


            <div>

                <div class="text-muted small mb-1">
                    Remarks
                </div>

                <div class="border rounded p-3 bg-light">

                    {!! nl2br(e(
                        $term->remarks ?? '-'
                    )) !!}

                </div>

            </div>

        </div>

    </div>


    {{-- Delete --}}
    <div class="card mb-4 border-danger">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Delete Lease Terms
                    </strong>

                    <div class="text-muted small">
                        This will soft-delete the lease terms record.
                    </div>

                </div>


                <form method="POST"
                      action="{{ route(
                          'admin.leasing.terms.destroy',
                          $term->id
                      ) }}"
                      onsubmit="return confirm(
                          'Are you sure you want to delete these lease terms?'
                      );">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="btn btn-danger">

                        <i class="fas fa-trash me-1"></i>
                        Delete

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection