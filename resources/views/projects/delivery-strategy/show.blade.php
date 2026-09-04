@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ================================================= --}}
    {{-- Header --}}
    {{-- ================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Delivery Strategy
            </div>

            <h3 class="mb-1">
                {{ $deliveryStrategy->title }}
            </h3>

            <div class="text-muted">

                {{ $project->project_name }}

                · {{ $deliveryStrategy->strategy_number }}

                · V{{ $deliveryStrategy->version_number }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.delivery-strategy.index',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Delivery Strategies
            </a>


            @if(
                $deliveryStrategy->status !== 'Approved'
            )

                <a
                    href="{{ route(
                        'admin.projects.delivery-strategy.edit',
                        [
                            'project' => $project->id,
                            'deliveryStrategy' =>
                                $deliveryStrategy->id,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    Edit
                </a>

            @endif


            @if(
                $deliveryStrategy->status === 'Draft'
            )

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.delivery-strategy.submit',
                        [
                            'project' => $project->id,
                            'deliveryStrategy' =>
                                $deliveryStrategy->id,
                        ]
                    ) }}"
                    class="d-inline"
                    onsubmit="return confirm(
                        'Submit this Delivery Strategy for review?'
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


            @if(
                $deliveryStrategy->status === 'Under Review'
            )

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.delivery-strategy.approve',
                        [
                            'project' => $project->id,
                            'deliveryStrategy' =>
                                $deliveryStrategy->id,
                        ]
                    ) }}"
                    class="d-inline"
                    onsubmit="return confirm(
                        'Approve this Delivery Strategy?'
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
                        'admin.projects.delivery-strategy.reject',
                        [
                            'project' => $project->id,
                            'deliveryStrategy' =>
                                $deliveryStrategy->id,
                        ]
                    ) }}"
                    class="d-inline"
                    onsubmit="return confirm(
                        'Reject this Delivery Strategy?'
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


            @if(
                $deliveryStrategy->status === 'Rejected'
            )

                <a
                    href="{{ route(
                        'admin.projects.delivery-strategy.edit',
                        [
                            'project' => $project->id,
                            'deliveryStrategy' =>
                                $deliveryStrategy->id,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    Edit & Resubmit
                </a>

            @endif


            @if(
                $deliveryStrategy->status === 'Approved'
            )

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.delivery-strategy.revision',
                        [
                            'project' => $project->id,
                            'deliveryStrategy' =>
                                $deliveryStrategy->id,
                        ]
                    ) }}"
                    class="d-inline"
                    onsubmit="return confirm(
                        'Create a new revision from this approved Delivery Strategy?'
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


    {{-- ================================================= --}}
    {{-- Messages --}}
    {{-- ================================================= --}}

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


    {{-- ================================================= --}}
    {{-- Summary --}}
    {{-- ================================================= --}}

    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Strategy Number
                    </div>

                    <div class="fw-semibold mt-1">
                        {{ $deliveryStrategy->strategy_number }}
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
                        V{{ $deliveryStrategy->version_number }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Delivery Model
                    </div>

                    <div class="fw-semibold mt-1">
                        {{ $deliveryStrategy->delivery_model }}
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

                        @php

                            $statusClass =
                                match(
                                    $deliveryStrategy->status
                                ) {

                                    'Approved'
                                        => 'bg-success',

                                    'Submitted',
                                    'Under Review'
                                        => 'bg-warning text-dark',

                                    'Rejected'
                                        => 'bg-danger',

                                    default
                                        => 'bg-secondary',

                                };

                        @endphp

                        <span
                            class="badge {{ $statusClass }}"
                        >
                            {{ $deliveryStrategy->status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- Delivery Approach --}}
    {{-- ================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Delivery Approach</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $deliveryStrategy->delivery_approach
                    ?? 'No delivery approach defined.'
                )
            ) !!}

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- Implementation Strategy --}}
    {{-- ================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Implementation Strategy</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $deliveryStrategy
                        ->implementation_strategy
                    ?? 'No implementation strategy defined.'
                )
            ) !!}

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- Project Packaging --}}
    {{-- ================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Project Packaging Strategy</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $deliveryStrategy
                        ->project_packaging_strategy
                    ?? 'No packaging strategy defined.'
                )
            ) !!}

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- Responsibility Matrix --}}
    {{-- ================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Responsibility Matrix</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $deliveryStrategy
                        ->responsibility_matrix
                    ?? 'No responsibility matrix defined.'
                )
            ) !!}

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- Key Milestones --}}
    {{-- ================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Key Milestones</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $deliveryStrategy
                        ->key_milestones
                    ?? 'No key milestones defined.'
                )
            ) !!}

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- Assumptions / Constraints --}}
    {{-- ================================================= --}}

    <div class="row">

        <div class="col-md-6">

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Assumptions</strong>
                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $deliveryStrategy
                                ->assumptions
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
                            $deliveryStrategy
                                ->constraints
                            ?? 'No constraints recorded.'
                        )
                    ) !!}

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- Approval Information --}}
    {{-- ================================================= --}}

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
                            $deliveryStrategy->effective_date
                                ? $deliveryStrategy
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
                            $deliveryStrategy->approved_date
                                ? $deliveryStrategy
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
                        {{ $deliveryStrategy->approved_by ?? '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- Revision History --}}
    {{-- ================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Revision History
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>

                            <th>
                                Version
                            </th>

                            <th>
                                Strategy Number
                            </th>

                            <th>
                                Delivery Model
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Effective Date
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse(
                            $revisions
                            as $revision
                        )

                            <tr
                                class="{{
                                    $revision->id ===
                                    $deliveryStrategy->id
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
                                        $deliveryStrategy->id
                                    )

                                        <span
                                            class="badge bg-primary ms-1"
                                        >
                                            Current
                                        </span>

                                    @endif

                                </td>


                                <td>
                                    {{ $revision->strategy_number }}
                                </td>


                                <td>
                                    {{ $revision->delivery_model }}
                                </td>


                                <td>
                                    {{ $revision->status }}
                                </td>


                                <td>

                                    {{
                                        $revision->effective_date
                                            ? $revision
                                                ->effective_date
                                                ->format('d M Y')
                                            : '—'
                                    }}

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.delivery-strategy.show',
                                            [
                                                'project' =>
                                                    $project->id,

                                                'deliveryStrategy' =>
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


    {{-- ================================================= --}}
    {{-- Remarks --}}
    {{-- ================================================= --}}

    @if($deliveryStrategy->remarks)

        <div class="card mb-4">

            <div class="card-header">
                <strong>Remarks</strong>
            </div>

            <div class="card-body">

                {!! nl2br(
                    e(
                        $deliveryStrategy->remarks
                    )
                ) !!}

            </div>

        </div>

    @endif


    {{-- ================================================= --}}
    {{-- Approved Notice --}}
    {{-- ================================================= --}}

    @if(
        $deliveryStrategy->status === 'Approved'
    )

        <div class="alert alert-info">

            <strong>
                Approved Delivery Strategy
            </strong>

            <div class="mt-1">
                This strategy is read-only.
                Create a new revision to make changes.
            </div>

        </div>

    @endif

</div>

@endsection