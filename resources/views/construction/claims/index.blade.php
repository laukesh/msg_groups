@extends('layouts.app')

@section('title', 'Construction Claims')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Construction Claims
            </h4>

            <div class="text-muted">
                {{ $project->project_number }}
                -
                {{ $project->project_name }}
            </div>

        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.dashboard',
                $project
            ) }}"
               class="btn btn-outline-secondary">

                <i class="fa fa-speedometer2"></i>
                Construction Dashboard

            </a>

            <a href="{{ route(
                'admin.projects.construction.claims.create',
                $project
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-lg"></i>
                New Claim

            </a>

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


    {{-- Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-2">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Claims
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $summary['total'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-2">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Draft
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $summary['draft'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-2">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Under Review
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $summary['under_review'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-2">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Approved
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $summary['approved'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-2">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Claimed Amount
                    </div>

                    <div class="fw-bold">
                        $ {{ number_format(
                            $summary['claimed_amount'],
                            2
                        ) }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-2">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Approved Amount
                    </div>

                    <div class="fw-bold text-success">
                        $ {{ number_format(
                            $summary['approved_amount'],
                            2
                        ) }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Filters --}}
    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3">

                    <div class="col-md-3">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Claim / Subject / Contract"
                            value="{{ request('search') }}">

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Claim Type
                        </label>

                        <select
                            name="claim_type"
                            class="form-select">

                            <option value="">
                                All Types
                            </option>

                            @foreach([
                                'Variation',
                                'Delay',
                                'Extension of Time',
                                'Additional Cost',
                                'Price Escalation',
                                'Loss and Expense',
                                'Other'
                            ] as $type)

                                <option
                                    value="{{ $type }}"
                                    {{ request('claim_type') == $type ? 'selected' : '' }}>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select">

                            <option value="">
                                All Status
                            </option>

                            @foreach([
                                'Draft',
                                'Submitted',
                                'Under Review',
                                'Under Assessment',
                                'Approved',
                                'Partially Approved',
                                'Rejected',
                                'Withdrawn',
                                'Closed'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ request('status') == $status ? 'selected' : '' }}>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Priority
                        </label>

                        <select
                            name="priority"
                            class="form-select">

                            <option value="">
                                All Priority
                            </option>

                            @foreach([
                                'Low',
                                'Medium',
                                'High',
                                'Critical'
                            ] as $priority)

                                <option
                                    value="{{ $priority }}"
                                    {{ request('priority') == $priority ? 'selected' : '' }}>

                                    {{ $priority }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary">

                            <i class="bi bi-search"></i>
                            Filter

                        </button>

                        <a href="{{ route(
                            'admin.projects.construction.claims.index',
                            $project
                        ) }}"
                           class="btn btn-outline-secondary">

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Register --}}
    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <strong>
                Claims Register
            </strong>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>Claim</th>

                        <th>Type</th>

                        <th>Date</th>

                        <th>Contract</th>

                        <th>Claimed</th>

                        <th>Approved</th>

                        <th>Days</th>

                        <th>Status</th>

                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($claims as $claim)

                        <tr>

                            <td>

                                <strong>
                                    {{ $claim->claim_number }}
                                </strong>

                                <div class="small text-muted">
                                    {{ $claim->subject }}
                                </div>

                            </td>


                            <td>
                                {{ $claim->claim_type }}
                            </td>


                            <td>

                                {{ optional(
                                    $claim->claim_date
                                )->format('d M Y') }}

                            </td>


                            <td>

                                @if($claim->procurementContract)

                                    <strong>
                                        {{ $claim->procurementContract->contract_number }}
                                    </strong>

                                    @if($claim->procurementContract->bidder_name)

                                        <div class="small text-muted">
                                            {{ $claim->procurementContract->bidder_name }}
                                        </div>

                                    @endif

                                @else

                                    —

                                @endif

                            </td>


                            <td>

                                $ {{ number_format(
                                    $claim->claimed_amount,
                                    2
                                ) }}

                                @if($claim->claimed_days > 0)

                                    <div class="small text-muted">
                                        {{ $claim->claimed_days }} days
                                    </div>

                                @endif

                            </td>


                            <td>

                                $ {{ number_format(
                                    $claim->approved_amount,
                                    2
                                ) }}

                            </td>


                            <td>

                                {{ $claim->approved_days }}

                            </td>


                            <td>

                                @php

                                    $badge = match(
                                        $claim->status
                                    ) {

                                        'Draft' =>
                                            'secondary',

                                        'Submitted' =>
                                            'info',

                                        'Under Review' =>
                                            'warning',

                                        'Under Assessment' =>
                                            'warning',

                                        'Approved' =>
                                            'success',

                                        'Partially Approved' =>
                                            'primary',

                                        'Rejected' =>
                                            'danger',

                                        'Withdrawn' =>
                                            'dark',

                                        'Closed' =>
                                            'success',

                                        default =>
                                            'secondary',
                                    };

                                @endphp


                                <span class="badge bg-{{ $badge }}">

                                    {{ $claim->status }}

                                </span>

                            </td>


                            <td>

                                <!-- <a href="{{ route(
                                    'admin.projects.construction.claims.show',
                                    [
                                        'project' => $project,
                                        'claim' => $claim
                                    ]
                                ) }}"
                                   class="btn btn-sm btn-outline-primary">

                                    View

                                </a> -->

                                <div class="d-flex gap-1">

                                    {{-- View Claim --}}
                                    <a href="{{ route(
                                        'admin.projects.construction.claims.show',
                                        [
                                            'project' => $project,
                                            'claim' => $claim,
                                        ]
                                    ) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="View Claim">

                                        <i class="fa fa-eye"></i>

                                    </a>


                                    {{-- Edit Claim --}}
                                    <a href="{{ route(
                                        'admin.projects.construction.claims.edit',
                                        [
                                            'project' => $project,
                                            'claim' => $claim,
                                        ]
                                    ) }}"
                                       class="btn btn-sm btn-outline-warning"
                                       title="Edit Claim">

                                        <i class="fa fa-edit"></i>

                                    </a>


                                    {{-- Documents --}}
                                    <a href="{{ route(
                                        'admin.projects.construction.claims.documents.index',
                                        [
                                            'project' => $project,
                                            'claim' => $claim,
                                        ]
                                    ) }}"
                                       class="btn btn-sm btn-outline-secondary"
                                       title="Documents">

                                        <i class="fa fa-folder"></i>

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="9"
                                class="text-center text-muted py-5">

                                No construction claims found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($claims->hasPages())

            <div class="card-footer bg-white">

                {{ $claims->links() }}

            </div>

        @endif

    </div>

</div>

@endsection