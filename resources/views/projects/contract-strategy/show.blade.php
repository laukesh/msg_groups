@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Contract Strategy
            </div>

            <h3 class="mb-1">
                {{ $contractStrategy->title }}
            </h3>

            <div class="text-muted">

                {{ $project->project_name }}

                · {{ $contractStrategy->strategy_number }}

                · V{{ $contractStrategy->version_number }}

            </div>

        </div>


        <div class="d-flex gap-2 flex-wrap">

            <a
                href="{{ route(
                    'admin.projects.contract-strategy.index',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Contract Strategies
            </a>


            @if($contractStrategy->status !== 'Approved')

                <a
                    href="{{ route(
                        'admin.projects.contract-strategy.edit',
                        [
                            'project' => $project->id,
                            'contractStrategy' =>
                                $contractStrategy->id,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    Edit
                </a>

            @endif


            @if($contractStrategy->status === 'Draft')

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.contract-strategy.submit',
                        [
                            'project' => $project->id,
                            'contractStrategy' =>
                                $contractStrategy->id,
                        ]
                    ) }}"
                    onsubmit="return confirm(
                        'Submit this Contract Strategy for review?'
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


            @if($contractStrategy->status === 'Under Review')

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.contract-strategy.approve',
                        [
                            'project' => $project->id,
                            'contractStrategy' =>
                                $contractStrategy->id,
                        ]
                    ) }}"
                    onsubmit="return confirm(
                        'Approve this Contract Strategy?'
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
                        'admin.projects.contract-strategy.reject',
                        [
                            'project' => $project->id,
                            'contractStrategy' =>
                                $contractStrategy->id,
                        ]
                    ) }}"
                    onsubmit="return confirm(
                        'Reject this Contract Strategy?'
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


            @if($contractStrategy->status === 'Rejected')

                <a
                    href="{{ route(
                        'admin.projects.contract-strategy.edit',
                        [
                            'project' => $project->id,
                            'contractStrategy' =>
                                $contractStrategy->id,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    Edit & Resubmit
                </a>

            @endif


            @if($contractStrategy->status === 'Approved')

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.contract-strategy.revision',
                        [
                            'project' => $project->id,
                            'contractStrategy' =>
                                $contractStrategy->id,
                        ]
                    ) }}"
                    onsubmit="return confirm(
                        'Create a new revision from this approved Contract Strategy?'
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


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Summary --}}

    @php

        $statusClass =
            match($contractStrategy->status) {

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
                        {{ $contractStrategy->strategy_number }}
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
                        V{{ $contractStrategy->version_number }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Contracting Model
                    </div>

                    <div class="fw-semibold mt-1">
                        {{ $contractStrategy->contracting_model }}
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
                            {{ $contractStrategy->status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Contract Type --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Contract Type</strong>
        </div>

        <div class="card-body">

            {{ $contractStrategy->contract_type }}

        </div>

    </div>


    {{-- Commercial Model --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Commercial Model</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $contractStrategy->commercial_model
                    ?? 'No commercial model defined.'
                )
            ) !!}

        </div>

    </div>


    {{-- Contract Packaging --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Contract Packaging</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $contractStrategy->contract_packaging
                    ?? 'No contract packaging strategy defined.'
                )
            ) !!}

        </div>

    </div>


    {{-- Payment & Risk --}}

    <div class="row">

        <div class="col-md-6">

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Payment Strategy</strong>
                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $contractStrategy->payment_strategy
                            ?? 'Not defined.'
                        )
                    ) !!}

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Risk Allocation Strategy</strong>
                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $contractStrategy->risk_allocation_strategy
                            ?? 'Not defined.'
                        )
                    ) !!}

                </div>

            </div>

        </div>

    </div>


    {{-- Contract Protections --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Contract Protections</strong>
        </div>

        <div class="card-body">

            <div class="row">

                @foreach([
                    [
                        'title' => 'Performance Security',
                        'value' =>
                            $contractStrategy->performance_security_strategy,
                    ],
                    [
                        'title' => 'Retention',
                        'value' =>
                            $contractStrategy->retention_strategy,
                    ],
                    [
                        'title' => 'Liquidated Damages',
                        'value' =>
                            $contractStrategy->liquidated_damages_strategy,
                    ],
                    [
                        'title' => 'Insurance',
                        'value' =>
                            $contractStrategy->insurance_strategy,
                    ],
                    [
                        'title' => 'Defect Liability',
                        'value' =>
                            $contractStrategy->defect_liability_strategy,
                    ],
                ] as $item)

                    <div class="col-md-4 mb-4">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small mb-2">
                                {{ $item['title'] }}
                            </div>

                            <div>

                                {!! nl2br(
                                    e(
                                        $item['value']
                                        ?? 'Not defined.'
                                    )
                                ) !!}

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>


    {{-- Change / Claims / Disputes --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Change, Claims & Dispute Strategy</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <div class="text-muted small mb-2">
                        Variation / Change
                    </div>

                    {!! nl2br(
                        e(
                            $contractStrategy->variation_change_strategy
                            ?? 'Not defined.'
                        )
                    ) !!}

                </div>


                <div class="col-md-4">

                    <div class="text-muted small mb-2">
                        Claims
                    </div>

                    {!! nl2br(
                        e(
                            $contractStrategy->claims_strategy
                            ?? 'Not defined.'
                        )
                    ) !!}

                </div>


                <div class="col-md-4">

                    <div class="text-muted small mb-2">
                        Dispute Resolution
                    </div>

                    {!! nl2br(
                        e(
                            $contractStrategy->dispute_resolution_strategy
                            ?? 'Not defined.'
                        )
                    ) !!}

                </div>

            </div>

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
                            $contractStrategy->assumptions
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
                            $contractStrategy->constraints
                            ?? 'No constraints recorded.'
                        )
                    ) !!}

                </div>

            </div>

        </div>

    </div>


    {{-- Approval Information --}}

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
                            $contractStrategy->effective_date
                                ? $contractStrategy->effective_date
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
                            $contractStrategy->approved_date
                                ? $contractStrategy->approved_date
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
                        {{ $contractStrategy->approved_by ?? '—' }}
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
                            <th>Contracting Model</th>
                            <th>Contract Type</th>
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
                                    $contractStrategy->id
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
                                        $contractStrategy->id
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
                                    {{ $revision->contracting_model }}
                                </td>


                                <td>
                                    {{ $revision->contract_type }}
                                </td>


                                <td>
                                    {{ $revision->status }}
                                </td>


                                <td>

                                    {{
                                        $revision->effective_date
                                            ? $revision->effective_date
                                                ->format('d M Y')
                                            : '—'
                                    }}

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.contract-strategy.show',
                                            [
                                                'project' => $project->id,
                                                'contractStrategy' =>
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
                                    colspan="7"
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

    @if($contractStrategy->remarks)

        <div class="card mb-4">

            <div class="card-header">
                <strong>Remarks</strong>
            </div>

            <div class="card-body">

                {!! nl2br(
                    e($contractStrategy->remarks)
                ) !!}

            </div>

        </div>

    @endif


    @if($contractStrategy->status === 'Approved')

        <div class="alert alert-info">

            <strong>Approved Contract Strategy</strong>

            <div class="mt-1">
                This strategy is read-only.
                Create a new revision to make changes.
            </div>

        </div>

    @endif

</div>

@endsection