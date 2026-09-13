@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Site Instruction
            </div>

            <h4 class="mb-1">
                {{ $siteInstruction->subject }}
            </h4>

            <div class="text-muted">

                {{ $siteInstruction->instruction_number }}

                <span class="mx-1">•</span>

                {{
                    $siteInstruction->instruction_date
                        ?->format('d-m-Y')
                    ?? '—'
                }}

            </div>

        </div>


        <div class="d-flex gap-2 flex-wrap">

            <a
                href="{{ route(
                    'admin.projects.construction.site-instructions.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>


            @if(
                $siteInstruction->status === 'Draft'
            )

                <a
                    href="{{ route(
                        'admin.projects.construction.site-instructions.edit',
                        [
                            'project' =>
                                $project,

                            'siteInstruction' =>
                                $siteInstruction,
                        ]
                    ) }}"
                    class="btn btn-outline-primary"
                >
                    Edit
                </a>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.construction.site-instructions.issue',
                        [
                            'project' =>
                                $project,

                            'siteInstruction' =>
                                $siteInstruction,
                        ]
                    ) }}"
                    class="d-inline"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-primary"
                        onclick="
                            return confirm(
                                'Issue this Site Instruction?'
                            );
                        "
                    >
                        Issue
                    </button>

                </form>

            @endif


            @if(
                $siteInstruction->status === 'Issued'
            )

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.construction.site-instructions.acknowledge',
                        [
                            'project' =>
                                $project,

                            'siteInstruction' =>
                                $siteInstruction,
                        ]
                    ) }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-info"
                    >
                        Acknowledge
                    </button>

                </form>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.construction.site-instructions.start',
                        [
                            'project' =>
                                $project,

                            'siteInstruction' =>
                                $siteInstruction,
                        ]
                    ) }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Start
                    </button>

                </form>

            @endif


            @if(
                in_array(
                    $siteInstruction->status,
                    [
                        'Acknowledged',
                        'In Progress',
                    ],
                    true
                )
            )

                <button
                    type="button"
                    class="btn btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#complyModal"
                >
                    Mark Complied
                </button>

            @endif


            @if(
                $siteInstruction->status === 'Complied'
            )

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.construction.site-instructions.close',
                        [
                            'project' =>
                                $project,

                            'siteInstruction' =>
                                $siteInstruction,
                        ]
                    ) }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        Close
                    </button>

                </form>

            @endif


            @if(
                in_array(
                    $siteInstruction->status,
                    [
                        'Draft',
                        'Issued',
                    ],
                    true
                )
            )

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.construction.site-instructions.cancel',
                        [
                            'project' =>
                                $project,

                            'siteInstruction' =>
                                $siteInstruction,
                        ]
                    ) }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-outline-danger"
                        onclick="
                            return confirm(
                                'Cancel this Site Instruction?'
                            );
                        "
                    >
                        Cancel
                    </button>

                </form>

            @endif


            @if(
                in_array(
                    $siteInstruction->status,
                    [
                        'Draft',
                        'Cancelled',
                    ],
                    true
                )
            )

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.construction.site-instructions.destroy',
                        [
                            'project' =>
                                $project,

                            'siteInstruction' =>
                                $siteInstruction,
                        ]
                    ) }}"
                >

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-outline-danger"
                        onclick="
                            return confirm(
                                'Delete this Site Instruction?'
                            );
                        "
                    >
                        Delete
                    </button>

                </form>

            @endif

        </div>

    </div>


    {{-- Status --}}

    @php

        $statusClass =
            match(
                $siteInstruction->status
            ) {

                'Closed' =>
                    'bg-success',

                'Complied' =>
                    'bg-success',

                'In Progress' =>
                    'bg-primary',

                'Acknowledged' =>
                    'bg-info text-dark',

                'Issued' =>
                    'bg-primary',

                'Cancelled' =>
                    'bg-secondary',

                default =>
                    'bg-warning text-dark',

            };

    @endphp


    <div class="mb-4">

        <span
            class="badge {{ $statusClass }} fs-6"
        >
            {{ $siteInstruction->status }}
        </span>

    </div>


    {{-- Instruction Details --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Instruction Details
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Instruction Number
                    </div>

                    <div class="fw-semibold">
                        {{ $siteInstruction->instruction_number }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Instruction Date
                    </div>

                    <div class="fw-semibold">

                        {{
                            $siteInstruction->instruction_date
                                ?->format('d-m-Y')
                            ?? '—'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Type
                    </div>

                    <div class="fw-semibold">
                        {{ $siteInstruction->instruction_type ?? '—' }}
                    </div>

                </div>


                <div class="col-12">

                    <div class="text-muted small">
                        Subject
                    </div>

                    <div class="fw-semibold">
                        {{ $siteInstruction->subject }}
                    </div>

                </div>


                <div class="col-12">

                    <div class="text-muted small mb-1">
                        Description / Instruction
                    </div>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $siteInstruction->description
                                ?? '—'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Assignment --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Assignment & Reference
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-6">

                    <div class="text-muted small">
                        Contractor
                    </div>

                    <div class="fw-semibold">

                        {{
                            $siteInstruction->contract?->bidder?->company_name
                            ?? $siteInstruction->contract?->bidder_name
                            ?? $siteInstruction->workOrder?->contract?->bidder?->company_name
                            ?? $siteInstruction->workOrder?->contract?->bidder_name
                            ?? '—'
                        }}

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Consultant
                    </div>

                    @if($siteInstruction->consultant)

                        <div class="fw-semibold">
                            {{ $siteInstruction->consultant->company_name }}
                        </div>

                        @if($siteInstruction->consultant->consultant_name)

                            <div class="small text-muted">
                                {{ $siteInstruction->consultant->consultant_name }}
                            </div>

                        @endif

                    @else

                        <div class="text-muted">
                            —
                        </div>

                    @endif

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Work Order
                    </div>

                    <div class="fw-semibold">

                        {{
                            $siteInstruction->workOrder
                                ?->work_order_number
                            ?? '—'
                        }}

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Schedule Activity
                    </div>

                    <div class="fw-semibold">

                        {{
                            $siteInstruction->schedule_activity_id
                            ?? '—'
                        }}

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Location
                    </div>

                    <div class="fw-semibold">

                        {{
                            $siteInstruction->location
                            ?? '—'
                        }}

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Priority
                    </div>

                    <div class="fw-semibold">

                        {{ $siteInstruction->priority }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Action --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Required Action
            </strong>

        </div>


        <div class="card-body">

            <div class="mb-4">

                {!! nl2br(
                    e(
                        $siteInstruction->required_action
                        ?? 'No specific action recorded.'
                    )
                ) !!}

            </div>


            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Due Date
                    </div>

                    <div class="fw-semibold">

                        {{
                            $siteInstruction->due_date
                                ?->format('d-m-Y')
                            ?? '—'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Acknowledged
                    </div>

                    <div class="fw-semibold">

                        {{
                            $siteInstruction->acknowledgement_date
                                ?->format('d-m-Y H:i')
                            ?? '—'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Compliance Date
                    </div>

                    <div class="fw-semibold">

                        {{
                            $siteInstruction->compliance_date
                                ?->format('d-m-Y')
                            ?? '—'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Response --}}

    @if(
        $siteInstruction->response
        ||
        $siteInstruction->status === 'Complied'
        ||
        $siteInstruction->status === 'Closed'
    )

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Response / Compliance
                </strong>

            </div>


            <div class="card-body">

                {!! nl2br(
                    e(
                        $siteInstruction->response
                        ?? '—'
                    )
                ) !!}

            </div>

        </div>

    @endif


    {{-- Closure --}}

    @if(
        $siteInstruction->closed_date
    )

        <div class="card mb-4">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <div class="text-muted small">
                            Closed Date
                        </div>

                        <div class="fw-semibold">

                            {{
                                $siteInstruction->closed_date
                                    ?->format('d-m-Y')
                            }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- Remarks --}}

    @if($siteInstruction->remarks)

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Remarks
                </strong>

            </div>

            <div class="card-body">

                {!! nl2br(
                    e(
                        $siteInstruction->remarks
                    )
                ) !!}

            </div>

        </div>

    @endif

</div>


{{-- Compliance Modal --}}

@if(
    in_array(
        $siteInstruction->status,
        [
            'Acknowledged',
            'In Progress',
        ],
        true
    )
)

<div
    class="modal fade"
    id="complyModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.site-instructions.comply',
                    [
                        'project' =>
                            $project,

                        'siteInstruction' =>
                            $siteInstruction,
                    ]
                ) }}"
            >

                @csrf


                <div class="modal-header">

                    <h5 class="modal-title">
                        Mark Instruction as Complied
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    <label class="form-label">
                        Response / Compliance Details
                        <span class="text-danger">*</span>
                    </label>

                    <textarea
                        name="response"
                        rows="5"
                        class="form-control"
                        required
                    ></textarea>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        Mark Complied
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endif

@endsection