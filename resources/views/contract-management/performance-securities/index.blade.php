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
                Performance Security
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
                'admin.projects.contract-management.contracts.performance-securities.create',
                [$project, $contract]
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-lg me-1"></i>

                Add Security

            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Success Message --}}
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
    {{-- Requirement / Coverage --}}
    {{-- ========================================================= --}}

    @php

        $coveragePercentage =
            min(
                100,
                max(
                    0,
                    (float) $summary['coverage_percentage']
                )
            );

        $coverageClass =
            $coveragePercentage >= 100
                ? 'success'
                : (
                    $coveragePercentage > 0
                        ? 'warning'
                        : 'danger'
                );

    @endphp


    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Performance Security Coverage
                </h5>

                @if($contract->performance_security_required)

                    <span class="badge bg-primary">
                        Required
                    </span>

                @else

                    <span class="badge bg-secondary">
                        Not Required
                    </span>

                @endif

            </div>

        </div>


        <div class="card-body">

            <div class="row g-4">


                {{-- Required --}}

                <div class="col-xl-3 col-md-6">

                    <div class="text-muted small">
                        Required Security
                    </div>

                    <div class="fs-4 fw-semibold">

                        {{ $contract->currency ?? 'USD' }}

                        {{ number_format(
                            $summary['required_amount'],
                            2
                        ) }}

                    </div>

                </div>


                {{-- Active --}}

                <div class="col-xl-3 col-md-6">

                    <div class="text-muted small">
                        Active Security
                    </div>

                    <div class="fs-4 fw-semibold text-success">

                        {{ $contract->currency ?? 'USD' }}

                        {{ number_format(
                            $summary['active_amount'],
                            2
                        ) }}

                    </div>

                </div>


                {{-- Shortfall --}}

                <div class="col-xl-3 col-md-6">

                    <div class="text-muted small">
                        Security Shortfall
                    </div>

                    <div class="fs-4 fw-semibold
                        {{ $summary['shortfall'] > 0
                            ? 'text-danger'
                            : 'text-success'
                        }}">

                        {{ $contract->currency ?? 'USD' }}

                        {{ number_format(
                            $summary['shortfall'],
                            2
                        ) }}

                    </div>

                </div>


                {{-- Coverage --}}

                <div class="col-xl-3 col-md-6">

                    <div class="text-muted small">
                        Coverage
                    </div>

                    <div class="fs-4 fw-semibold">

                        {{ number_format(
                            $coveragePercentage,
                            2
                        ) }}%

                    </div>

                </div>

            </div>


            {{-- Progress --}}

            @if($summary['required_amount'] > 0)

                <div class="mt-4">

                    <div class="d-flex justify-content-between mb-1">

                        <small class="text-muted">
                            Security Coverage
                        </small>

                        <small class="fw-semibold">
                            {{ number_format(
                                $coveragePercentage,
                                2
                            ) }}%
                        </small>

                    </div>


                    <div class="progress"
                         style="height: 10px;">

                        <div class="progress-bar bg-{{ $coverageClass }}"
                             role="progressbar"
                             style="width: {{ $coveragePercentage }}%;"
                             aria-valuenow="{{ $coveragePercentage }}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                        </div>

                    </div>


                    @if($summary['shortfall'] > 0)

                        <div class="small text-danger mt-2">

                            <i class="bi bi-exclamation-triangle me-1"></i>

                            Performance security shortfall exists.

                        </div>

                    @else

                        <div class="small text-success mt-2">

                            <i class="bi bi-check-circle me-1"></i>

                            Required performance security is fully covered.

                        </div>

                    @endif

                </div>

            @else

                <div class="alert alert-secondary mt-4 mb-0">

                    Performance security is not required for this contract.

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Summary Cards --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Securities
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $summary['total'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Verified
                    </div>

                    <div class="fs-3 fw-semibold text-success">
                        {{ $summary['verified'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Pending Verification
                    </div>

                    <div class="fs-3 fw-semibold text-warning">
                        {{ $summary['pending_verification'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Expiring / Expired
                    </div>

                    <div class="fs-3 fw-semibold">

                        <span class="text-warning">
                            {{ $summary['expiring_soon'] }}
                        </span>

                        <span class="text-muted">
                            /
                        </span>

                        <span class="text-danger">
                            {{ $summary['expired'] }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Security Register --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Performance Security Register
                </h5>

                <span class="text-muted small">

                    {{ $summary['total'] }}
                    record(s)

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($securities->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-3">
                                    Security No.
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Instrument No.
                                </th>

                                <th>
                                    Issuing Bank
                                </th>

                                <th>
                                    Amount
                                </th>

                                <th>
                                    Validity
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Verification
                                </th>

                                <th class="text-end">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($securities as $security)

                                @php

                                    $statusClass = match(
                                        $security->status
                                    ) {

                                        'Active'
                                            => 'success',

                                        'Extended'
                                            => 'info',

                                        'Expired',
                                        'Cancelled'
                                            => 'danger',

                                        'Under Verification'
                                            => 'warning',

                                        'Released'
                                            => 'secondary',

                                        'Closed'
                                            => 'dark',

                                        default
                                            => 'primary',

                                    };


                                    $verificationClass = match(
                                        $security->verification_status
                                    ) {

                                        'Verified'
                                            => 'success',

                                        'Rejected'
                                            => 'danger',

                                        default
                                            => 'warning',

                                    };


                                    $days =
                                        $security
                                            ->daysUntilExpiry();

                                @endphp


                                <tr>

                                    {{-- Security No --}}

                                    <td class="px-3">

                                        <div class="fw-semibold">

                                            {{ $security->security_number }}

                                        </div>

                                        @if($security->beneficiary)

                                            <small class="text-muted">

                                                {{ $security->beneficiary }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- Type --}}

                                    <td>

                                        {{ $security->security_type }}

                                    </td>


                                    {{-- Instrument --}}

                                    <td>

                                        {{ $security->instrument_number
                                            ?: '—'
                                        }}

                                    </td>


                                    {{-- Bank --}}

                                    <td>

                                        {{ $security->issuing_bank
                                            ?: '—'
                                        }}

                                        @if(
                                            $security->issuing_branch
                                        )

                                            <div class="small text-muted">

                                                {{ $security->issuing_branch }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Amount --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $security->currency }}

                                            {{ number_format(
                                                $security->security_amount,
                                                2
                                            ) }}

                                        </div>

                                        @if(
                                            $security->released_amount > 0
                                        )

                                            <small class="text-muted">

                                                Released:

                                                {{ number_format(
                                                    $security->released_amount,
                                                    2
                                                ) }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- Validity --}}

                                    <td>

                                        @if($security->issue_date)

                                            <div>

                                                {{
                                                    $security
                                                        ->issue_date
                                                        ->format('d M Y')
                                                }}

                                            </div>

                                        @endif


                                        <div class="text-muted">

                                            to

                                            {{
                                                $security
                                                    ->effectiveExpiryDate()
                                                    ?->format('d M Y')
                                                    ?? '—'
                                            }}

                                        </div>


                                        @if($days !== null)

                                            @if($days < 0)

                                                <small class="text-danger">

                                                    <i class="bi bi-x-circle me-1"></i>

                                                    Expired
                                                    {{ abs($days) }}
                                                    days ago

                                                </small>

                                            @elseif($days <= 30)

                                                <small class="text-warning">

                                                    <i class="bi bi-exclamation-triangle me-1"></i>

                                                    Expires in
                                                    {{ $days }}
                                                    days

                                                </small>

                                            @else

                                                <small class="text-success">

                                                    {{ $days }}
                                                    days remaining

                                                </small>

                                            @endif

                                        @endif

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        <span class="badge bg-{{ $statusClass }}">

                                            {{ $security->status }}

                                        </span>

                                    </td>


                                    {{-- Verification --}}

                                    <td>

                                        <span class="badge bg-{{ $verificationClass }}">

                                            {{ $security->verification_status }}

                                        </span>

                                    </td>


                                    {{-- Action --}}

                                    <td class="text-end">

                                        <a href="{{ route(
                                            'admin.projects.contract-management.contracts.performance-securities.edit',
                                            [$project, $contract, $security]
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary">

                                            <i class="fa fa-edit"></i>

                                        </a>


                                        <form method="POST"
                                              action="{{ route(
                                                  'admin.projects.contract-management.contracts.performance-securities.destroy',
                                                  [$project, $contract, $security]
                                              ) }}"
                                              class="d-inline">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Delete this performance security?');">

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

                    <div class="mb-3">

                        <i class="bi bi-shield-check fs-1 text-muted"></i>

                    </div>

                    <h5>
                        No Performance Securities
                    </h5>

                    <p class="text-muted mb-3">

                        No performance security has been
                        registered against this contract.

                    </p>


                    <a href="{{ route(
                        'admin.projects.contract-management.contracts.performance-securities.create',
                        [$project, $contract]
                    ) }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-lg me-1"></i>

                        Add First Security

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection