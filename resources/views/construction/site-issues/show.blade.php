@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4 class="mb-1">
                Site Issue / RFI
            </h4>

            <div class="text-muted">
                {{ $issue->issue_number }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.site-issues.edit',
                    [
                        'project' => $project,
                        'issue' => $issue,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.site-issues.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    <div class="row g-4">

        <div class="col-lg-8">

            {{-- Main Details --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Issue Details</strong>
                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Issue Number
                            </div>

                            <div class="fw-semibold">
                                {{ $issue->issue_number }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Issue Date
                            </div>

                            <div class="fw-semibold">
                                {{
                                    $issue->issue_date
                                        ?->format('d-m-Y')
                                    ?? '—'
                                }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Type
                            </div>

                            <div class="fw-semibold">
                                {{ $issue->issue_type }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Category
                            </div>

                            <div class="fw-semibold">
                                {{ $issue->category ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-12">

                            <div class="text-muted small">
                                Title
                            </div>

                            <div class="fs-5 fw-semibold">
                                {{ $issue->title }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Work Order
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $issue
                                        ->workOrder
                                        ?->work_order_number
                                    ?? '—'
                                }}

                            </div>

                            <div class="small text-muted">

                                {{
                                    $issue
                                        ->workOrder
                                        ?->work_order_title
                                    ?? ''
                                }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Related Progress
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $issue
                                        ->progress
                                        ?->progress_number
                                    ?? '—'
                                }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Description --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Description</strong>
                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $issue->description
                            ??
                            'No description provided.'
                        )
                    ) !!}

                </div>

            </div>


            {{-- Corrective Action --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Corrective Action</strong>
                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $issue->corrective_action
                            ??
                            'No corrective action recorded.'
                        )
                    ) !!}

                </div>

            </div>


            {{-- Resolution --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Resolution</strong>
                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $issue->resolution
                            ??
                            'Not resolved yet.'
                        )
                    ) !!}

                </div>

            </div>

        </div>


        <div class="col-lg-4">

            {{-- Status --}}

            @php

                $statusClass =
                    match(
                        $issue->status
                    ) {

                        'Resolved',
                        'Closed' =>
                            'bg-success',

                        'In Progress' =>
                            'bg-primary',

                        'Reopened' =>
                            'bg-warning text-dark',

                        default =>
                            'bg-danger',
                    };


                $priorityClass =
                    match(
                        $issue->priority
                    ) {

                        'Critical' =>
                            'bg-danger',

                        'High' =>
                            'bg-warning text-dark',

                        'Medium' =>
                            'bg-primary',

                        default =>
                            'bg-secondary',
                    };

            @endphp


            <div class="card mb-4">

                <div class="card-header">
                    <strong>Issue Status</strong>
                </div>


                <div class="card-body">

                    <div class="mb-3">

                        <div class="text-muted small">
                            Status
                        </div>

                        <span
                            class="badge {{ $statusClass }}"
                        >
                            {{ $issue->status }}
                        </span>

                    </div>


                    <div class="mb-3">

                        <div class="text-muted small">
                            Priority
                        </div>

                        <span
                            class="badge {{ $priorityClass }}"
                        >
                            {{ $issue->priority }}
                        </span>

                    </div>


                    <div class="mb-3">

                        <div class="text-muted small">
                            Due Date
                        </div>

                        <div class="fw-semibold">

                            {{
                                $issue->due_date
                                    ?->format('d-m-Y')
                                ?? '—'
                            }}

                        </div>

                    </div>


                    @if($issue->isOverdue())

                        <div class="alert alert-danger py-2">

                            <strong>
                                Overdue
                            </strong>

                            <div class="small">
                                This issue has passed its due date.
                            </div>

                        </div>

                    @endif


                    <div>

                        <div class="text-muted small">
                            Resolution Date
                        </div>

                        <div class="fw-semibold">

                            {{
                                $issue->resolution_date
                                    ?->format('d-m-Y')
                                ?? '—'
                            }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- People --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Responsibility</strong>
                </div>


                <div class="card-body">

                    <div class="mb-3">

                        <div class="text-muted small">
                            Raised By
                        </div>

                        <div class="fw-semibold">

                            {{
                                $issue
                                    ->raisedBy
                                    ?->name
                                ?? '—'
                            }}

                        </div>

                    </div>


                    <div>

                        <div class="text-muted small">
                            Assigned To
                        </div>

                        <div class="fw-semibold">

                            {{
                                $issue
                                    ->assignedTo
                                    ?->name
                                ?? '—'
                            }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- Remarks --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Remarks</strong>
                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $issue->remarks
                            ??
                            '—'
                        )
                    ) !!}

                </div>

            </div>


            {{-- Audit --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Audit Information</strong>
                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <div class="text-muted small">
                            Created By
                        </div>

                        {{
                            $issue
                                ->creator
                                ?->name
                            ?? '—'
                        }}

                    </div>


                    <div class="mb-3">

                        <div class="text-muted small">
                            Created At
                        </div>

                        {{
                            $issue->created_at
                                ?->format('d-m-Y H:i')
                            ?? '—'
                        }}

                    </div>


                    <div>

                        <div class="text-muted small">
                            Last Updated By
                        </div>

                        {{
                            $issue
                                ->updater
                                ?->name
                            ?? '—'
                        }}

                    </div>

                </div>

            </div>


            {{-- Delete --}}

            <div class="card border-danger">

                <div class="card-header text-danger">
                    <strong>Danger Zone</strong>
                </div>

                <div class="card-body">

                    <p class="small text-muted">
                        Deleting this issue cannot be undone.
                    </p>

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.construction.site-issues.destroy',
                            [
                                'project' => $project,
                                'issue' => $issue,
                            ]
                        ) }}"
                        onsubmit="
                            return confirm(
                                'Are you sure you want to delete this issue?'
                            );
                        "
                    >

                        @csrf

                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn btn-outline-danger w-100"
                        >
                            Delete Issue / RFI
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection