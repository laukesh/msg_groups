@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Governance / Approval Matrix
            </div>

            <h3 class="mb-1">
                {{ $approvalMatrix->approval_type }}
            </h3>

            <div class="text-muted">

                {{ $approvalMatrix->approval_code }}

                ·

                {{ $project->project_name }}

                @if($project->project_number)
                    · {{ $project->project_number }}
                @endif

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.approval-matrix.index',
                    [
                        'project' => $project->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Approval Matrix
            </a>


            <a
                href="{{ route(
                    'admin.projects.approval-matrix.edit',
                    [
                        'project' => $project->id,
                        'approvalMatrix' => $approvalMatrix->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- STATUS --}}
    {{-- ========================================================= --}}

    @php

        $statusClass =
            match($approvalMatrix->status) {

                'Active'
                    => 'bg-success',

                'Draft'
                    => 'bg-warning text-dark',

                'Inactive'
                    => 'bg-secondary',

                default
                    => 'bg-secondary',

            };

    @endphp


    <div class="row g-3 mb-4">

        {{-- Approval Type --}}

        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Approval Type
                    </div>

                    <div class="fw-semibold fs-5 mt-1">
                        {{ $approvalMatrix->approval_type }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Authority --}}

        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Authority
                    </div>

                    <div class="fw-semibold fs-5 mt-1">
                        {{ $approvalMatrix->authority_role }}
                    </div>


                    @if($approvalMatrix->authorityUser)

                        <div class="text-muted small mt-1">

                            {{ $approvalMatrix->authorityUser->name }}

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- Status --}}

        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Status
                    </div>

                    <div class="mt-2">

                        <span
                            class="badge {{ $statusClass }} fs-6"
                        >
                            {{ $approvalMatrix->status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- BASIC INFORMATION --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Approval Rule
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Approval Code --}}

                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Approval Code
                    </div>

                    <div class="fw-semibold">
                        {{ $approvalMatrix->approval_code }}
                    </div>

                </div>


                {{-- Approval Type --}}

                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Approval Type
                    </div>

                    <div class="fw-semibold">
                        {{ $approvalMatrix->approval_type }}
                    </div>

                </div>


                {{-- Sequence --}}

                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Approval Sequence
                    </div>

                    <div class="fw-semibold">

                        <span
                            class="badge bg-light text-dark border"
                        >
                            {{ $approvalMatrix->approval_sequence }}
                        </span>

                    </div>

                </div>

            </div>


            @if($approvalMatrix->description)

                <div class="mt-2">

                    <div class="text-muted small">
                        Description
                    </div>

                    <div class="mt-1">

                        {!! nl2br(
                            e($approvalMatrix->description)
                        ) !!}

                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- GOVERNANCE --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Governance Framework
            </strong>

        </div>


        <div class="card-body">

            @if($approvalMatrix->governance)

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="text-muted small">
                            Governance Framework
                        </div>

                        <div class="fw-semibold">

                            {{ $approvalMatrix->governance->governance_number }}

                            ·

                            {{ $approvalMatrix->governance->title }}

                        </div>


                        <div class="text-muted small mt-1">

                            Status:
                            {{ $approvalMatrix->governance->status }}

                        </div>

                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.governance.show',
                            [
                                'project' =>
                                    $project->id,

                                'governance' =>
                                    $approvalMatrix
                                        ->governance
                                        ->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-outline-primary"
                    >
                        View Governance
                    </a>

                </div>

            @else

                <span class="text-muted">
                    This approval rule is not linked to a
                    specific governance framework.
                </span>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- APPROVAL AUTHORITY --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Approval Authority
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Role --}}

                <div class="col-md-6 mb-3">

                    <div class="text-muted small">
                        Authority Role
                    </div>

                    <div class="fw-semibold">
                        {{ $approvalMatrix->authority_role }}
                    </div>

                </div>


                {{-- User --}}

                <div class="col-md-6 mb-3">

                    <div class="text-muted small">
                        Designated Authority
                    </div>

                    <div class="fw-semibold">

                        @if($approvalMatrix->authorityUser)

                            {{ $approvalMatrix->authorityUser->name }}

                        @else

                            <span class="text-muted">
                                Role Based
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FINANCIAL AUTHORITY --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Financial Authority
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Currency --}}

                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Currency
                    </div>

                    <div class="fw-semibold">
                        {{ $approvalMatrix->currency }}
                    </div>

                </div>


                {{-- Minimum --}}

                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Minimum Amount
                    </div>

                    <div class="fw-semibold">

                        @if(
                            $approvalMatrix->minimum_amount !== null
                        )

                            {{ $approvalMatrix->currency }}
                            {{ number_format(
                                $approvalMatrix->minimum_amount,
                                2
                            ) }}

                        @else

                            No Minimum

                        @endif

                    </div>

                </div>


                {{-- Maximum --}}

                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Maximum Amount
                    </div>

                    <div class="fw-semibold">

                        @if(
                            $approvalMatrix->maximum_amount !== null
                        )

                            {{ $approvalMatrix->currency }}
                            {{ number_format(
                                $approvalMatrix->maximum_amount,
                                2
                            ) }}

                        @else

                            No Limit

                        @endif

                    </div>

                </div>

            </div>


            <div class="alert alert-light border mb-0">

                <strong>
                    Authority Range:
                </strong>

                @if(
                    $approvalMatrix->minimum_amount !== null
                )

                    {{ $approvalMatrix->currency }}
                    {{ number_format(
                        $approvalMatrix->minimum_amount,
                        2
                    ) }}

                @else

                    0.00

                @endif


                <span class="mx-1">
                    –
                </span>


                @if(
                    $approvalMatrix->maximum_amount !== null
                )

                    {{ $approvalMatrix->currency }}
                    {{ number_format(
                        $approvalMatrix->maximum_amount,
                        2
                    ) }}

                @else

                    No Limit

                @endif

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- APPROVAL REQUIREMENTS --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Approval Requirements
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Sequence --}}

                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Approval Sequence
                    </div>

                    <div class="mt-1">

                        <span
                            class="badge bg-light text-dark border fs-6"
                        >
                            Step
                            {{ $approvalMatrix->approval_sequence }}
                        </span>

                    </div>

                </div>


                {{-- Mandatory --}}

                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Mandatory
                    </div>

                    <div class="mt-1">

                        @if($approvalMatrix->is_mandatory)

                            <span class="badge bg-danger">
                                Mandatory
                            </span>

                        @else

                            <span
                                class="badge bg-light text-dark border"
                            >
                                Optional
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Multiple --}}

                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Multiple Approvals
                    </div>

                    <div class="mt-1">

                        @if(
                            $approvalMatrix
                                ->requires_multiple_approvals
                        )

                            <span class="badge bg-warning text-dark">
                                Required
                            </span>

                        @else

                            <span
                                class="badge bg-light text-dark border"
                            >
                                Not Required
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- VALIDITY --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Validity
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Effective Date --}}

                <div class="col-md-6 mb-3">

                    <div class="text-muted small">
                        Effective Date
                    </div>

                    <div class="fw-semibold">

                        @if($approvalMatrix->effective_date)

                            {{ $approvalMatrix->effective_date->format('d-m-Y') }}

                        @else

                            <span class="text-muted">
                                Not Defined
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Expiry Date --}}

                <div class="col-md-6 mb-3">

                    <div class="text-muted small">
                        Expiry Date
                    </div>

                    <div class="fw-semibold">

                        @if($approvalMatrix->expiry_date)

                            {{ $approvalMatrix->expiry_date->format('d-m-Y') }}

                        @else

                            <span class="text-muted">
                                No Expiry
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- REMARKS --}}
    {{-- ========================================================= --}}

    @if($approvalMatrix->remarks)

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Remarks
                </strong>

            </div>


            <div class="card-body">

                {!! nl2br(
                    e($approvalMatrix->remarks)
                ) !!}

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- STATUS ACTIONS --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Approval Rule Status
            </strong>

        </div>


        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <span class="text-muted">
                        Current Status:
                    </span>

                    <span class="badge {{ $statusClass }}">
                        {{ $approvalMatrix->status }}
                    </span>

                </div>


                <div class="d-flex gap-2">

                    @if(
                        $approvalMatrix->status !== 'Active'
                    )

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.approval-matrix.status',
                                [
                                    'project' =>
                                        $project->id,

                                    'approvalMatrix' =>
                                        $approvalMatrix->id,
                                ]
                            ) }}"
                        >

                            @csrf

                            <input
                                type="hidden"
                                name="status"
                                value="Active"
                            >

                            <button
                                type="submit"
                                class="btn btn-success"
                            >
                                Mark Active
                            </button>

                        </form>

                    @endif


                    @if(
                        $approvalMatrix->status === 'Active'
                    )

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.approval-matrix.status',
                                [
                                    'project' =>
                                        $project->id,

                                    'approvalMatrix' =>
                                        $approvalMatrix->id,
                                ]
                            ) }}"
                        >

                            @csrf

                            <input
                                type="hidden"
                                name="status"
                                value="Inactive"
                            >

                            <button
                                type="submit"
                                class="btn btn-outline-warning"
                            >
                                Deactivate
                            </button>

                        </form>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DELETE --}}
    {{-- ========================================================= --}}

    <div class="card border-danger mb-5">

        <div class="card-header text-danger">

            <strong>
                Danger Zone
            </strong>

        </div>


        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <div class="fw-semibold">
                        Delete Approval Rule
                    </div>

                    <div class="text-muted small">
                        This action cannot be undone.
                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.approval-matrix.destroy',
                        [
                            'project' =>
                                $project->id,

                            'approvalMatrix' =>
                                $approvalMatrix->id,
                        ]
                    ) }}"
                    onsubmit="return confirm(
                        'Are you sure you want to delete this approval rule?'
                    );"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-outline-danger"
                    >
                        Delete Approval Rule
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection