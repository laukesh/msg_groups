@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Procurement Strategy
            </div>

            <h3 class="mb-1">
                {{ $procurementStrategy->title }}
            </h3>

            <div class="text-muted">

                {{ $project->project_name }}

                · {{ $procurementStrategy->strategy_number }}

                · V{{ $procurementStrategy->version_number }}

            </div>

        </div>


        <div class="d-flex gap-2 flex-wrap">

            <a
                href="{{ route(
                    'admin.projects.procurement-strategy.index',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Procurement Strategies
            </a>


            @if($procurementStrategy->status !== 'Approved')

                <a
                    href="{{ route(
                        'admin.projects.procurement-strategy.edit',
                        [
                            'project' => $project->id,
                            'procurementStrategy' =>
                                $procurementStrategy->id,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    Edit
                </a>

            @endif


            @if($procurementStrategy->status === 'Draft')

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.procurement-strategy.submit',
                        [
                            'project' => $project->id,
                            'procurementStrategy' =>
                                $procurementStrategy->id,
                        ]
                    ) }}"
                    class="d-inline"
                    onsubmit="return confirm(
                        'Submit this Procurement Strategy for review?'
                    );"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-warning"
                    >
                        Submit for Review
                    </button>

                </form>

            @endif


            @if($procurementStrategy->status === 'Under Review')

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.procurement-strategy.approve',
                        [
                            'project' => $project->id,
                            'procurementStrategy' =>
                                $procurementStrategy->id,
                        ]
                    ) }}"
                    class="d-inline"
                    onsubmit="return confirm(
                        'Approve this Procurement Strategy?'
                    );"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        Approve
                    </button>

                </form>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.procurement-strategy.reject',
                        [
                            'project' => $project->id,
                            'procurementStrategy' =>
                                $procurementStrategy->id,
                        ]
                    ) }}"
                    class="d-inline"
                    onsubmit="return confirm(
                        'Reject this Procurement Strategy?'
                    );"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-danger"
                    >
                        Reject
                    </button>

                </form>

            @endif


            @if($procurementStrategy->status === 'Rejected')

                <a
                    href="{{ route(
                        'admin.projects.procurement-strategy.edit',
                        [
                            'project' => $project->id,
                            'procurementStrategy' =>
                                $procurementStrategy->id,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    Edit & Resubmit
                </a>

            @endif


            @if($procurementStrategy->status === 'Approved')

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.procurement-strategy.revision',
                        [
                            'project' => $project->id,
                            'procurementStrategy' =>
                                $procurementStrategy->id,
                        ]
                    ) }}"
                    class="d-inline"
                    onsubmit="return confirm(
                        'Create a new revision from this approved Procurement Strategy?'
                    );"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-warning"
                    >
                        Create Revision
                    </button>

                </form>

            @endif

        </div>

    </div>


    {{-- Messages --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Summary --}}

    @php

        $statusClass =
            match($procurementStrategy->status) {

                'Approved' => 'bg-success',

                'Submitted',
                'Under Review' => 'bg-warning text-dark',

                'Rejected' => 'bg-danger',

                default => 'bg-secondary',

            };

    @endphp


    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Strategy Number
                    </div>

                    <div class="fw-semibold mt-1">
                        {{ $procurementStrategy->strategy_number }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Version
                    </div>

                    <div class="fs-5 fw-semibold">
                        V{{ $procurementStrategy->version_number }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Procurement Model
                    </div>

                    <div class="fw-semibold mt-1">
                        {{ $procurementStrategy->procurement_model }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Status
                    </div>

                    <div class="mt-1">

                        <span class="badge {{ $statusClass }}">
                            {{ $procurementStrategy->status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Procurement Approach --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Procurement Approach</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $procurementStrategy->procurement_approach
                    ?? 'No procurement approach defined.'
                )
            ) !!}

        </div>

    </div>


    {{-- Procurement Packages --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Procurement Packages</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $procurementStrategy->procurement_packages
                    ?? 'No procurement packages defined.'
                )
            ) !!}

            <div class="alert alert-light border mt-3 mb-0">

                <strong>Note:</strong>
                These are strategic procurement packages.
                Actual procurement execution will be handled
                in the Procurement domain.

            </div>

        </div>

    </div>


    {{-- Sourcing Strategy --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Sourcing Strategy</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $procurementStrategy->sourcing_strategy
                    ?? 'No sourcing strategy defined.'
                )
            ) !!}

        </div>

    </div>


    {{-- Tendering Strategy --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Tendering Strategy</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $procurementStrategy->tendering_strategy
                    ?? 'No tendering strategy defined.'
                )
            ) !!}

        </div>

    </div>


    {{-- Vendor Selection Criteria --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Vendor Selection Criteria</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $procurementStrategy->vendor_selection_criteria
                    ?? 'No vendor selection criteria defined.'
                )
            ) !!}

            <div class="alert alert-light border mt-3 mb-0">

                <strong>Note:</strong>
                Actual vendor qualification and bid evaluation
                will be handled in the Procurement domain.

            </div>

        </div>

    </div>


    {{-- Procurement Schedule --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Procurement Schedule</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $procurementStrategy->procurement_schedule
                    ?? 'No procurement schedule defined.'
                )
            ) !!}

        </div>

    </div>


    {{-- Assumptions / Constraints --}}

    <div class="row">

        <div class="col-md-6">

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Assumptions</strong>
                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $procurementStrategy->assumptions
                            ?? 'No assumptions recorded.'
                        )
                    ) !!}

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Constraints</strong>
                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $procurementStrategy->constraints
                            ?? 'No constraints recorded.'
                        )
                    ) !!}

                </div>

            </div>

        </div>

    </div>


    {{-- Approval --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Approval Information</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Effective Date
                    </div>

                    <div class="fw-semibold">

                        {{
                            $procurementStrategy->effective_date
                                ? $procurementStrategy
                                    ->effective_date
                                    ->format('d M Y')
                                : '—'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Approved Date
                    </div>

                    <div class="fw-semibold">

                        {{
                            $procurementStrategy->approved_date
                                ? $procurementStrategy
                                    ->approved_date
                                    ->format('d M Y')
                                : '—'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Approved By
                    </div>

                    <div class="fw-semibold">
                        {{ $procurementStrategy->approved_by ?? '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Revision History --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Revision History</strong>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>

                            <th>Version</th>
                            <th>Strategy Number</th>
                            <th>Procurement Model</th>
                            <th>Status</th>
                            <th>Effective Date</th>
                            <th class="text-end">Action</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($revisions as $revision)

                            <tr
                                class="{{
                                    $revision->id ===
                                    $procurementStrategy->id
                                        ? 'table-primary'
                                        : ''
                                }}"
                            >

                                <td>

                                    <strong>
                                        V{{ $revision->version_number }}
                                    </strong>

                                    @if(
                                        $revision->id ===
                                        $procurementStrategy->id
                                    )

                                        <span class="badge bg-primary ms-1">
                                            Current
                                        </span>

                                    @endif

                                </td>


                                <td>
                                    {{ $revision->strategy_number }}
                                </td>


                                <td>
                                    {{ $revision->procurement_model }}
                                </td>


                                <td>
                                    {{ $revision->status }}
                                </td>


                                <td>

                                    {{
                                        $revision->effective_date
                                            ? $revision->effective_date->format('d M Y')
                                            : '—'
                                    }}

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.procurement-strategy.show',
                                            [
                                                'project' => $project->id,
                                                'procurementStrategy' =>
                                                    $revision->id,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center text-muted p-4"
                                >
                                    No revisions found.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Remarks --}}

    @if($procurementStrategy->remarks)

        <div class="card mb-4">

            <div class="card-header">
                <strong>Remarks</strong>
            </div>

            <div class="card-body">

                {!! nl2br(
                    e($procurementStrategy->remarks)
                ) !!}

            </div>

        </div>

    @endif


    @if($procurementStrategy->status === 'Approved')

        <div class="alert alert-info">

            <strong>Approved Procurement Strategy</strong>

            <div class="mt-1">
                This strategy is read-only.
                Create a new revision to make changes.
            </div>

        </div>

    @endif

</div>

@endsection