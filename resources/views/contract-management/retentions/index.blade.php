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
                Retention Management
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
                'admin.projects.contract-management.contracts.retentions.create',
                [$project, $contract]
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-lg me-1"></i>

                Add Retention

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
    {{-- Contract Retention Requirement --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Contract Retention Requirement
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Retention Required
                    </div>

                    <div class="fs-5 fw-semibold">

                        @if($summary['retention_required'])

                            <span class="text-success">
                                Yes
                            </span>

                        @else

                            <span class="text-secondary">
                                No
                            </span>

                        @endif

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Retention Percentage
                    </div>

                    <div class="fs-5 fw-semibold text-primary">

                        {{ number_format(
                            $summary['retention_percentage'],
                            2
                        ) }}%

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Total Retained
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{ $contract->currency ?? 'USD' }}

                        {{ number_format(
                            $summary['total_retained'],
                            2
                        ) }}

                    </div>

                </div>

            </div>

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
                        Certified Amount
                    </div>

                    <div class="fs-4 fw-semibold">

                        {{ $contract->currency ?? 'USD' }}

                        {{ number_format(
                            $summary['total_certified'],
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Retained
                    </div>

                    <div class="fs-4 fw-semibold text-warning">

                        {{ $contract->currency ?? 'USD' }}

                        {{ number_format(
                            $summary['total_retained'],
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Released
                    </div>

                    <div class="fs-4 fw-semibold text-success">

                        {{ $contract->currency ?? 'USD' }}

                        {{ number_format(
                            $summary['total_released'],
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Retention Balance
                    </div>

                    <div class="fs-4 fw-semibold text-primary">

                        {{ $contract->currency ?? 'USD' }}

                        {{ number_format(
                            $summary['total_balance'],
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Status Summary --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 bg-light">

                <div class="card-body">

                    <div class="text-muted small">
                        Retained
                    </div>

                    <div class="fs-4 fw-semibold">
                        {{ $summary['retained_count'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 bg-light">

                <div class="card-body">

                    <div class="text-muted small">
                        Partially Released
                    </div>

                    <div class="fs-4 fw-semibold text-warning">
                        {{ $summary['partially_released_count'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 bg-light">

                <div class="card-body">

                    <div class="text-muted small">
                        Fully Released
                    </div>

                    <div class="fs-4 fw-semibold text-success">
                        {{ $summary['fully_released_count'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 bg-light">

                <div class="card-body">

                    <div class="text-muted small">
                        Upcoming Releases
                    </div>

                    <div class="fs-4 fw-semibold text-info">
                        {{ $summary['upcoming_releases'] }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Retention Register --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Retention Register
                </h5>

                <span class="text-muted small">

                    {{ $summary['total'] }}
                    record(s)

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($retentions->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-3">
                                    Retention No.
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Invoice / Payment
                                </th>

                                <th>
                                    Certified Amount
                                </th>

                                <th>
                                    Retention
                                </th>

                                <th>
                                    Released
                                </th>

                                <th>
                                    Balance
                                </th>

                                <th>
                                    Release Date
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($retentions as $retention)

                                @php

                                    $statusClass = match(
                                        $retention->status
                                    ) {

                                        'Retained'
                                            => 'warning',

                                        'Partially Released'
                                            => 'info',

                                        'Fully Released'
                                            => 'success',

                                        'Disputed'
                                            => 'danger',

                                        'Cancelled'
                                            => 'secondary',

                                        default
                                            => 'primary',

                                    };


                                    $releaseDays =
                                        $retention
                                            ->daysUntilRelease();

                                @endphp


                                <tr>

                                    <td class="px-3">

                                        <div class="fw-semibold">

                                            {{ $retention->retention_number }}

                                        </div>

                                    </td>


                                    <td>

                                        {{
                                            $retention
                                                ->retention_date
                                                ?->format('d M Y')
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        @if(
                                            $retention->invoice_number
                                        )

                                            <div>

                                                {{
                                                    $retention
                                                        ->invoice_number
                                                }}

                                            </div>

                                        @endif


                                        @if(
                                            $retention->payment_reference
                                        )

                                            <small class="text-muted">

                                                {{
                                                    $retention
                                                        ->payment_reference
                                                }}

                                            </small>

                                        @endif


                                        @if(
                                            !$retention->invoice_number &&
                                            !$retention->payment_reference
                                        )

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        <div class="fw-semibold">

                                            {{ $retention->currency }}

                                            {{ number_format(
                                                $retention->certified_amount,
                                                2
                                            ) }}

                                        </div>

                                    </td>


                                    <td>

                                        <div class="fw-semibold">

                                            {{ number_format(
                                                $retention->retention_percentage,
                                                2
                                            ) }}%

                                        </div>

                                        <small class="text-muted">

                                            {{ $retention->currency }}

                                            {{ number_format(
                                                $retention->retention_amount,
                                                2
                                            ) }}

                                        </small>

                                    </td>


                                    <td>

                                        {{ $retention->currency }}

                                        {{ number_format(
                                            $retention->released_amount,
                                            2
                                        ) }}

                                    </td>


                                    <td>

                                        <span class="fw-semibold
                                            {{ $retention->balance_amount > 0
                                                ? 'text-primary'
                                                : 'text-success'
                                            }}">

                                            {{ $retention->currency }}

                                            {{ number_format(
                                                $retention->balance_amount,
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    <td>

                                        @if(
                                            $retention->release_date
                                        )

                                            {{
                                                $retention
                                                    ->release_date
                                                    ->format('d M Y')
                                            }}

                                        @elseif(
                                            $retention->expected_release_date
                                        )

                                            <div class="text-muted">

                                                Expected:

                                                {{
                                                    $retention
                                                        ->expected_release_date
                                                        ->format('d M Y')
                                                }}

                                            </div>


                                            @if(
                                                $releaseDays !== null &&
                                                $retention->balance_amount > 0
                                            )

                                                @if($releaseDays < 0)

                                                    <small class="text-danger">

                                                        Overdue by
                                                        {{ abs($releaseDays) }}
                                                        days

                                                    </small>

                                                @elseif($releaseDays <= 30)

                                                    <small class="text-warning">

                                                        In
                                                        {{ $releaseDays }}
                                                        days

                                                    </small>

                                                @endif

                                            @endif

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        <span class="badge bg-{{ $statusClass }}">

                                            {{ $retention->status }}

                                        </span>

                                    </td>


                                    <td class="text-end">

                                        <a href="{{ route(
                                            'admin.projects.contract-management.contracts.retentions.edit',
                                            [$project, $contract, $retention]
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary">

                                            <i class="fa fa-edit"></i>

                                        </a>


                                        <form method="POST"
                                              action="{{ route(
                                                  'admin.projects.contract-management.contracts.retentions.destroy',
                                                  [$project, $contract, $retention]
                                              ) }}"
                                              class="d-inline">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Delete this retention entry?');">

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

                    <i class="bi bi-cash-stack fs-1 text-muted"></i>

                    <h5 class="mt-3">
                        No Retention Entries
                    </h5>

                    <p class="text-muted">
                        No retention amount has been recorded
                        against this contract.
                    </p>

                    <a href="{{ route(
                        'admin.projects.contract-management.contracts.retentions.create',
                        [$project, $contract]
                    ) }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-lg me-1"></i>

                        Add First Retention

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection