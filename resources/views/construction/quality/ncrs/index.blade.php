@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ================================================================ --}}
    {{-- HEADER --}}
    {{-- ================================================================ --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Non-Conformance Reports
            </h4>

            <div class="text-muted">
                Project:
                <strong>
                    {{ $project->project_name ?? $project->name }}
                </strong>
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.quality.ncrs.create',
                $project
            ) }}"
            class="btn btn-primary"
        >
            + Create NCR
        </a>

    </div>


    {{-- ================================================================ --}}
    {{-- SUMMARY CARDS --}}
    {{-- ================================================================ --}}

    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total NCRs
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $summary['total'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Open
                    </div>

                    <div class="fs-3 fw-bold text-warning">
                        {{ $summary['open'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Under Review
                    </div>

                    <div class="fs-3 fw-bold text-info">
                        {{ $summary['under_review'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Critical Open NCRs
                    </div>

                    <div class="fs-3 fw-bold text-danger">
                        {{ $summary['critical'] }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================ --}}
    {{-- SECOND SUMMARY ROW --}}
    {{-- ================================================================ --}}

    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Submitted
                    </div>

                    <div class="fs-4 fw-bold">
                        {{ $summary['submitted'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Corrective Action
                    </div>

                    <div class="fs-4 fw-bold">
                        {{ $summary['corrective_action'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Verification
                    </div>

                    <div class="fs-4 fw-bold">
                        {{ $summary['verification'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Closed
                    </div>

                    <div class="fs-4 fw-bold text-success">
                        {{ $summary['closed'] }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================ --}}
    {{-- NCR TABLE --}}
    {{-- ================================================================ --}}

    <div class="card shadow-sm">

        <div class="card-header">

            <h5 class="mb-0">
                NCR Register
            </h5>

        </div>


        <div class="card-body p-0">

            @if($ncrs->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    NCR No.
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Title
                                </th>

                                <th>
                                    Severity
                                </th>

                                <th>
                                    Contractor
                                </th>

                                <th>
                                    Work Order
                                </th>

                                <th>
                                    Raised By
                                </th>

                                <th>
                                    Due Date
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

                            @foreach($ncrs as $ncr)

                                <tr>

                                    {{-- NCR Number --}}
                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.projects.construction.quality.ncrs.show',
                                                [
                                                    'project' => $project,
                                                    'ncr' => $ncr
                                                ]
                                            ) }}"
                                            class="fw-semibold text-decoration-none"
                                        >
                                            {{ $ncr->ncr_number }}
                                        </a>

                                    </td>


                                    {{-- Date --}}
                                    <td>

                                        {{ $ncr->ncr_date?->format('d-m-Y') }}

                                    </td>


                                    {{-- Title --}}
                                    <td>

                                        <div class="fw-semibold">
                                            {{ $ncr->title }}
                                        </div>

                                        @if($ncr->location)

                                            <div class="text-muted small">
                                                {{ $ncr->location }}
                                            </div>

                                        @endif

                                    </td>


                                    {{-- Severity --}}
                                    <td>

                                        @php

                                            $severityClass = match(
                                                $ncr->severity
                                            ) {

                                                'Critical' =>
                                                    'bg-danger',

                                                'Major' =>
                                                    'bg-warning text-dark',

                                                default =>
                                                    'bg-secondary',

                                            };

                                        @endphp


                                        <span
                                            class="badge {{ $severityClass }}"
                                        >
                                            {{ $ncr->severity }}
                                        </span>

                                    </td>


                                    {{-- Contractor --}}
                                    <td>

                                        @if($ncr->contract?->bidder)

                                            {{ $ncr->contract->bidder->company_name }}

                                        @elseif($ncr->contract?->bidder_name)

                                            {{ $ncr->contract->bidder_name }}

                                        @elseif($ncr->workOrder?->contract?->bidder)

                                            {{ $ncr->workOrder->contract->bidder->company_name }}

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Work Order --}}
                                    <td>

                                        @if($ncr->workOrder)

                                            {{ $ncr->workOrder->work_order_number }}

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Raised By --}}
                                    <td>

                                        {{ $ncr->raisedBy?->name ?? '—' }}

                                    </td>


                                    {{-- Due Date --}}
                                    <td>

                                        @if($ncr->due_date)

                                            {{ $ncr->due_date->format('d-m-Y') }}

                                            @if(
                                                $ncr->due_date->isPast()
                                                &&
                                                $ncr->status !== 'Closed'
                                            )

                                                <span
                                                    class="badge bg-danger ms-1"
                                                >
                                                    Overdue
                                                </span>

                                            @endif

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @php

                                            $statusClass = match(
                                                $ncr->status
                                            ) {

                                                'Open' =>
                                                    'bg-secondary',

                                                'Submitted' =>
                                                    'bg-primary',

                                                'Under Review' =>
                                                    'bg-info text-dark',

                                                'Corrective Action Required' =>
                                                    'bg-warning text-dark',

                                                'Corrective Action Submitted' =>
                                                    'bg-primary',

                                                'Verification' =>
                                                    'bg-info text-dark',

                                                'Closed' =>
                                                    'bg-success',

                                                'Rejected' =>
                                                    'bg-danger',

                                                default =>
                                                    'bg-secondary',

                                            };

                                        @endphp


                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $ncr->status }}
                                        </span>

                                    </td>


                                    {{-- Action --}}
                                    <td class="text-end">

                                        <div class="btn-group">

                                            <a
                                                href="{{ route(
                                                    'admin.projects.construction.quality.ncrs.show',
                                                    [
                                                        'project' => $project,
                                                        'ncr' => $ncr
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>


                                            @if(
                                                in_array(
                                                    $ncr->status,
                                                    [
                                                        'Open',
                                                        'Rejected'
                                                    ],
                                                    true
                                                )
                                            )

                                                <a
                                                    href="{{ route(
                                                        'admin.projects.construction.quality.ncrs.edit',
                                                        [
                                                            'project' => $project,
                                                            'ncr' => $ncr
                                                        ]
                                                    ) }}"
                                                    class="btn btn-sm btn-outline-secondary"
                                                >
                                                    Edit
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

                    <div class="text-muted mb-3">
                        No Non-Conformance Reports found.
                    </div>

                    <a
                        href="{{ route(
                            'admin.projects.construction.quality.ncrs.create',
                            $project
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Create First NCR
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection