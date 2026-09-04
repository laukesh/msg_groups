@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted">
                Contract Management
            </div>

            <h4 class="mb-1">
                Insurance Management
            </h4>

            <div class="text-muted">

                {{ $contract->contract_code }}

                <span class="mx-1">|</span>

                {{ $contract->contract_title }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.contract-management.contracts.show',
                [$project, $contract]
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>

                Contract

            </a>


            <a href="{{ route(
                'admin.projects.contract-management.contracts.insurances.create',
                [$project, $contract]
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-lg me-1"></i>

                Add Insurance

            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Success --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Summary Cards --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Policies
                    </div>

                    <h3 class="mb-0">
                        {{ $summary['total'] }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Active Policies
                    </div>

                    <h3 class="mb-0 text-success">
                        {{ $summary['active'] }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Expiring Soon
                    </div>

                    <h3 class="mb-0 text-warning">
                        {{ $summary['expiring'] }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Expired
                    </div>

                    <h3 class="mb-0 text-danger">
                        {{ $summary['expired'] }}
                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Compliance Summary --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card border-success shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Compliant
                    </div>

                    <div class="fs-4 fw-semibold text-success">
                        {{ $summary['compliant'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-danger shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Non-Compliant
                    </div>

                    <div class="fs-4 fw-semibold text-danger">
                        {{ $summary['non_compliant'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Coverage
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{ $contract->currency ?? 'INR' }}

                        {{ number_format(
                            $summary['total_coverage'],
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Premium
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{ $contract->currency ?? 'INR' }}

                        {{ number_format(
                            $summary['total_premium'],
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Insurance Register --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Insurance Register
            </h5>

        </div>


        <div class="card-body p-0">

            @if($insurances->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-3">
                                    Insurance No.
                                </th>

                                <th>
                                    Insurance Type
                                </th>

                                <th>
                                    Policy No.
                                </th>

                                <th>
                                    Insurer
                                </th>

                                <th>
                                    Coverage
                                </th>

                                <th>
                                    Validity
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Compliance
                                </th>

                                <th class="text-end">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($insurances as $insurance)

                                @php

                                    $statusClass = match(
                                        $insurance->status
                                    ) {

                                        'Active'
                                            => 'success',

                                        'Expired',
                                        'Cancelled'
                                            => 'danger',

                                        'Under Verification'
                                            => 'warning',

                                        'Renewed'
                                            => 'info',

                                        'Closed'
                                            => 'secondary',

                                        default
                                            => 'primary',

                                    };


                                    $complianceClass = match(
                                        $insurance->compliance_status
                                    ) {

                                        'Compliant'
                                            => 'success',

                                        'Expired',
                                        'Non-Compliant'
                                            => 'danger',

                                        'Partially Compliant'
                                            => 'warning',

                                        default
                                            => 'secondary',

                                    };


                                    $daysToExpiry =
                                        $insurance
                                            ->daysUntilExpiry();

                                @endphp


                                <tr>

                                    {{-- Insurance Number --}}

                                    <td class="px-3">

                                        <strong>
                                            {{ $insurance->insurance_number }}
                                        </strong>

                                    </td>


                                    {{-- Type --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $insurance->insurance_type }}

                                        </div>

                                        @if($insurance->insured_party)

                                            <small class="text-muted">

                                                {{ $insurance->insured_party }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- Policy Number --}}

                                    <td>

                                        {{ $insurance->policy_number
                                            ?: '—'
                                        }}

                                    </td>


                                    {{-- Insurer --}}

                                    <td>

                                        {{ $insurance->insurer_name }}

                                    </td>


                                    {{-- Coverage --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $insurance->currency }}

                                            {{ number_format(
                                                $insurance->coverage_amount,
                                                2
                                            ) }}

                                        </div>

                                    </td>


                                    {{-- Validity --}}

                                    <td>

                                        @if(
                                            $insurance->policy_start_date
                                        )

                                            <div>

                                                {{
                                                    $insurance
                                                        ->policy_start_date
                                                        ->format('d M Y')
                                                }}

                                            </div>

                                        @endif


                                        <div class="text-muted">

                                            to

                                            {{
                                                $insurance
                                                    ->policy_expiry_date
                                                    ?->format('d M Y')
                                                    ?? '—'
                                            }}

                                        </div>


                                        @if(
                                            $daysToExpiry !== null
                                        )

                                            @if($daysToExpiry < 0)

                                                <small class="text-danger">

                                                    Expired
                                                    {{ abs($daysToExpiry) }}
                                                    days ago

                                                </small>

                                            @elseif(
                                                $daysToExpiry <=
                                                $insurance
                                                    ->days_before_expiry_alert
                                            )

                                                <small class="text-warning">

                                                    Expires in
                                                    {{ $daysToExpiry }}
                                                    days

                                                </small>

                                            @else

                                                <small class="text-success">

                                                    {{ $daysToExpiry }}
                                                    days remaining

                                                </small>

                                            @endif

                                        @endif

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        <span class="badge bg-{{ $statusClass }}">

                                            {{ $insurance->status }}

                                        </span>

                                    </td>


                                    {{-- Compliance --}}

                                    <td>

                                        <span class="badge bg-{{ $complianceClass }}">

                                            {{ $insurance->compliance_status }}

                                        </span>

                                    </td>


                                    {{-- Actions --}}

                                    <td class="text-end">

                                        <a href="{{ route(
                                            'admin.projects.contract-management.contracts.insurances.edit',
                                            [$project, $contract, $insurance]
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary">

                                            <i class="fa fa-edit"></i>

                                        </a>


                                        <form method="POST"
                                              action="{{ route(
                                                  'admin.projects.contract-management.contracts.insurances.destroy',
                                                  [$project, $contract, $insurance]
                                              ) }}"
                                              class="d-inline">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Delete this insurance policy?');">

                                                <i class="fa fa-trash"></i>

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <h5>
                        No Insurance Policies
                    </h5>

                    <p class="text-muted mb-3">

                        No insurance policies have been
                        registered against this contract.

                    </p>


                    <a href="{{ route(
                        'admin.projects.contract-management.contracts.insurances.create',
                        [$project, $contract]
                    ) }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-lg me-1"></i>

                        Add First Insurance

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection