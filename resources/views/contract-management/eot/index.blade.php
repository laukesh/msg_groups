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
                Extensions of Time
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
                'admin.projects.contract-management.contracts.eot.create',
                [$project, $contract]
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-lg me-1"></i>

                Add EOT Request

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
    {{-- Summary --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total EOT Requests
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
                        Pending Review
                    </div>

                    <h3 class="mb-0">
                        {{ $summary['pending'] }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Requested Days
                    </div>

                    <h3 class="mb-0">
                        {{ number_format(
                            $summary['requested_days']
                        ) }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Approved Days
                    </div>

                    <h3 class="mb-0">
                        {{ number_format(
                            $summary['approved_days']
                        ) }}
                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Current Completion --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Original Contract Completion
                    </div>

                    <div class="fw-semibold fs-5">

                        {{ $contract->completion_date
                            ? $contract->completion_date
                                ->format('d M Y')
                            : '—'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Total Approved Extension
                    </div>

                    <div class="fw-semibold fs-5">

                        {{ number_format(
                            $summary['approved_days']
                        ) }}

                        days

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Current Revised Completion
                    </div>

                    <div class="fw-semibold fs-5 text-success">

                        {{ $currentCompletionDate
                            ? $currentCompletionDate
                                ->format('d M Y')
                            : '—'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- EOT Register --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                EOT Register
            </h5>

        </div>


        <div class="card-body p-0">

            @if($eots->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-3">
                                    EOT No.
                                </th>

                                <th>
                                    Request Date
                                </th>

                                <th>
                                    Reason
                                </th>

                                <th>
                                    Title
                                </th>

                                <th class="text-center">
                                    Requested
                                </th>

                                <th class="text-center">
                                    Approved
                                </th>

                                <th>
                                    Revised Completion
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

                            @foreach($eots as $eot)

                                @php

                                    $statusClass = match(
                                        $eot->status
                                    ) {

                                        'Approved',
                                        'Partially Approved'
                                            => 'success',

                                        'Rejected',
                                        'Withdrawn'
                                            => 'danger',

                                        'Under Review',
                                        'Under Negotiation'
                                            => 'warning',

                                        'Closed'
                                            => 'secondary',

                                        default
                                            => 'primary',

                                    };

                                @endphp


                                <tr>

                                    <td class="px-3">

                                        <strong>
                                            {{ $eot->eot_number }}
                                        </strong>

                                    </td>


                                    <td>

                                        {{ $eot->request_date
                                            ? $eot->request_date
                                                ->format('d M Y')
                                            : '—'
                                        }}

                                    </td>


                                    <td>

                                        {{ $eot->reason_type }}

                                    </td>


                                    <td>

                                        <div class="fw-semibold">

                                            {{ $eot->title }}

                                        </div>

                                        @if($eot->submitted_by_party)

                                            <small class="text-muted">

                                                {{ $eot->submitted_by_party }}

                                            </small>

                                        @endif

                                    </td>


                                    <td class="text-center">

                                        <span class="fw-semibold">

                                            {{ number_format(
                                                $eot->requested_days
                                            ) }}

                                        </span>

                                        <small class="text-muted">
                                            days
                                        </small>

                                    </td>


                                    <td class="text-center">

                                        <span class="fw-semibold">

                                            {{ number_format(
                                                $eot->approved_days
                                            ) }}

                                        </span>

                                        <small class="text-muted">
                                            days
                                        </small>

                                    </td>


                                    <td>

                                        @if($eot->revised_completion_date)

                                            {{ $eot->revised_completion_date
                                                ->format('d M Y')
                                            }}

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        <span class="badge bg-{{ $statusClass }}">

                                            {{ $eot->status }}

                                        </span>

                                    </td>


                                    <td class="text-end">

                                        <a href="{{ route(
                                            'admin.projects.contract-management.contracts.eot.edit',
                                            [$project, $contract, $eot]
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary">

                                            <i class="fa fa-edit"></i>

                                        </a>


                                        <form method="POST"
                                              action="{{ route(
                                                  'admin.projects.contract-management.contracts.eot.destroy',
                                                  [$project, $contract, $eot]
                                              ) }}"
                                              class="d-inline">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Delete this EOT request?');">

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
                        No EOT Requests Found
                    </h5>

                    <p class="text-muted mb-3">

                        No Extension of Time requests
                        have been registered against
                        this contract.

                    </p>


                    <a href="{{ route(
                        'admin.projects.contract-management.contracts.eot.create',
                        [$project, $contract]
                    ) }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-lg me-1"></i>

                        Add First EOT

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection