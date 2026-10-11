@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ================================================================
         HEADER
    ================================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4 class="mb-1">
                Progress Details
            </h4>

            <div class="text-muted">

                {{ $progress->progress_number }}

                ·

                {{ $project->project_name }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.progress.edit',
                    [
                        'project' => $project,
                        'progress' => $progress,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-pencil me-1"></i>
                Edit
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.progress.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Progress Register
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.dashboard',
                    [
                        'project' => $project,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Construction Dashboard
            </a>

        </div>

    </div>


    {{-- SUCCESS --}}

    @if(session('success'))

        <div class="alert alert-success">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- ================================================================
         MAIN
    ================================================================= --}}

    <div class="row g-4">


        {{-- ============================================================
             LEFT
        ============================================================= --}}

        <div class="col-lg-8">


            {{-- Progress Information --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Progress Information
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Progress Number
                            </div>

                            <div class="fw-semibold">

                                {{ $progress->progress_number }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Progress Date
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $progress
                                        ->progress_date
                                        ?->format('d-m-Y')
                                    ?? '—'
                                }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Work Order
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $progress
                                        ->workOrder
                                        ?->work_order_number
                                    ?? '—'
                                }}

                            </div>

                            <div class="small text-muted">

                                {{
                                    $progress
                                        ->workOrder
                                        ?->work_order_title
                                    ?? '—'
                                }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Contract
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $progress
                                        ->workOrder
                                        ?->contract
                                        ?->contract_number
                                    ?? '—'
                                }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Contractor / Supplier
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $progress
                                        ->workOrder
                                        ?->contract
                                        ?->bidder
                                        ?->company_name
                                    ??
                                    $progress
                                        ->workOrder
                                        ?->contract
                                        ?->bidder
                                        ?->bidder_name
                                    ??
                                    $progress
                                        ->workOrder
                                        ?->contract
                                        ?->bidder_name
                                    ??
                                    '—'
                                }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Status
                            </div>

                            @php

                                $statusClass = match(
                                    $progress->status
                                ) {

                                    'Completed' =>
                                        'bg-success',

                                    'Delayed' =>
                                        'bg-danger',

                                    'On Hold' =>
                                        'bg-warning text-dark',

                                    'In Progress' =>
                                        'bg-primary',

                                    default =>
                                        'bg-secondary',
                                };

                            @endphp


                            <span
                                class="badge {{ $statusClass }}"
                            >
                                {{ $progress->status }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Progress Metrics --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Progress Metrics
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        <div class="col-md-3">

                            <div class="text-muted small">
                                Planned
                            </div>

                            <div class="fs-4 fw-semibold">

                                @if(
                                    $progress->planned_percentage
                                    !== null
                                )

                                    {{
                                        number_format(
                                            (float)
                                            $progress->planned_percentage,
                                            2
                                        )
                                    }}%

                                @else

                                    —

                                @endif

                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="text-muted small">
                                Overall
                            </div>

                            <div class="fs-4 fw-semibold">

                                {{
                                    number_format(
                                        (float)
                                        $progress->progress_percentage,
                                        2
                                    )
                                }}%

                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="text-muted small">
                                Physical
                            </div>

                            <div class="fs-4 fw-semibold">

                                @if(
                                    $progress->physical_progress
                                    !== null
                                )

                                    {{
                                        number_format(
                                            (float)
                                            $progress->physical_progress,
                                            2
                                        )
                                    }}%

                                @else

                                    —

                                @endif

                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="text-muted small">
                                Financial
                            </div>

                            <div class="fs-4 fw-semibold">

                                @if(
                                    $progress->financial_progress
                                    !== null
                                )

                                    {{
                                        number_format(
                                            (float)
                                            $progress->financial_progress,
                                            2
                                        )
                                    }}%

                                @else

                                    —

                                @endif

                            </div>

                        </div>

                    </div>


                    @if(
                        $progress->planned_percentage !== null
                    )

                        <hr>

                        @php

                            $variance =
                                (float)
                                $progress->progress_percentage
                                -
                                (float)
                                $progress->planned_percentage;

                        @endphp


                        <div
                            class="d-flex justify-content-between"
                        >

                            <span class="text-muted">
                                Schedule Variance
                            </span>

                            <strong>

                                {{ $variance >= 0 ? '+' : '' }}

                                {{
                                    number_format(
                                        $variance,
                                        2
                                    )
                                }}%

                            </strong>

                        </div>

                    @endif

                </div>

            </div>


            {{-- Work Description --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Work Description
                    </strong>

                </div>


                <div class="card-body">

                    {!! nl2br(
                        e(
                            $progress->work_description
                            ??
                            'No description provided.'
                        )
                    ) !!}

                </div>

            </div>


            {{-- Issues & Actions --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Issues & Actions
                    </strong>

                </div>


                <div class="card-body">


                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            Issues
                        </div>

                        <div>

                            {!! nl2br(
                                e(
                                    $progress->issues
                                    ??
                                    'No issues reported.'
                                )
                            ) !!}

                        </div>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            Corrective Action
                        </div>

                        <div>

                            {!! nl2br(
                                e(
                                    $progress->corrective_action
                                    ??
                                    'No corrective action recorded.'
                                )
                            ) !!}

                        </div>

                    </div>


                    <div>

                        <div class="text-muted small mb-1">
                            Next Action
                        </div>

                        <div>

                            {!! nl2br(
                                e(
                                    $progress->next_action
                                    ??
                                    'No next action recorded.'
                                )
                            ) !!}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================
             RIGHT
        ============================================================= --}}

        <div class="col-lg-4">


            {{-- Site Information --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Site Information
                    </strong>

                </div>


                <div class="card-body">


                    <div class="mb-3">

                        <div class="text-muted small">
                            Weather Condition
                        </div>

                        <div class="fw-semibold">

                            {{
                                $progress
                                    ->weather_condition
                                ??
                                '—'
                            }}

                        </div>

                    </div>


                    <div class="mb-3">

                        <div class="text-muted small">
                            Reported By
                        </div>

                        <div class="fw-semibold">

                            {{
                                $progress
                                    ->reportedBy
                                    ?->name
                                ??
                                '—'
                            }}

                        </div>

                    </div>


                    <div>

                        <div class="text-muted small">
                            Remarks
                        </div>

                        <div>

                            {!! nl2br(
                                e(
                                    $progress->remarks
                                    ??
                                    '—'
                                )
                            ) !!}

                        </div>

                    </div>

                </div>

            </div>


            {{-- Audit Information --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Audit Information
                    </strong>

                </div>


                <div class="card-body">


                    <div class="mb-3">

                        <div class="text-muted small">
                            Created By
                        </div>

                        <div>

                            {{
                                $progress
                                    ->creator
                                    ?->name
                                ??
                                '—'
                            }}

                        </div>

                    </div>


                    <div class="mb-3">

                        <div class="text-muted small">
                            Created At
                        </div>

                        <div>

                            {{
                                $progress->created_at
                                    ?->format('d-m-Y H:i')
                                ??
                                '—'
                            }}

                        </div>

                    </div>


                    <div>

                        <div class="text-muted small">
                            Last Updated By
                        </div>

                        <div>

                            {{
                                $progress
                                    ->updater
                                    ?->name
                                ??
                                '—'
                            }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- Danger Zone --}}

            <div class="card border-danger">

                <div class="card-header text-danger">

                    <strong>
                        Danger Zone
                    </strong>

                </div>


                <div class="card-body">

                    <p class="small text-muted">
                        Deleting this progress update cannot be undone.
                    </p>


                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.construction.progress.destroy',
                            [
                                'project' => $project,
                                'progress' => $progress,
                            ]
                        ) }}"
                        onsubmit="return confirm('Are you sure you want to delete this progress update?');"
                    >

                        @csrf

                        @method('DELETE')


                        <button
                            type="submit"
                            class="btn btn-outline-danger w-100"
                        >
                            <i class="bi bi-trash me-1"></i>
                            Delete Progress Update
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection