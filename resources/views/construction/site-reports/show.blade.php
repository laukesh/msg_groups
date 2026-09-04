@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4 class="mb-1">
                Daily Site Report
            </h4>

            <div class="text-muted">
                {{ $report->report_number }}
            </div>

        </div>


        <div class="d-flex gap-2">

            @if($report->canEdit())

                <a
                    href="{{ route(
                        'admin.projects.construction.site-reports.edit',
                        [
                            'project' => $project,
                            'report' => $report,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    Edit
                </a>

            @endif


            @if(
                in_array(
                    $report->status,
                    [
                        'Draft',
                        'Revision Required',
                    ],
                    true
                )
            )

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.construction.site-reports.submit',
                        [
                            'project' => $project,
                            'report' => $report,
                        ]
                    ) }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-success"
                        onclick="
                            return confirm(
                                'Submit this Site Report?'
                            );
                        "
                    >
                        Submit
                    </button>

                </form>

            @endif


            <a
                href="{{ route(
                    'admin.projects.construction.site-reports.index',
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


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    <div class="row g-4">

        <div class="col-lg-8">

            {{-- Basic Information --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Report Information</strong>
                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-4">

                            <div class="text-muted small">
                                Report Number
                            </div>

                            <div class="fw-semibold">
                                {{ $report->report_number }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Report Date
                            </div>

                            <div class="fw-semibold">
                                {{
                                    $report->report_date
                                        ?->format('d-m-Y')
                                    ?? '—'
                                }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Report Type
                            </div>

                            <div class="fw-semibold">
                                {{ $report->report_type }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Work Order
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $report
                                        ->workOrder
                                        ?->work_order_number
                                    ?? '—'
                                }}

                            </div>

                            <div class="small text-muted">

                                {{
                                    $report
                                        ->workOrder
                                        ?->work_order_title
                                    ?? ''
                                }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Prepared By
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $report
                                        ->preparedBy
                                        ?->name
                                    ?? '—'
                                }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Site Conditions --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Site Conditions</strong>
                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-4">

                            <div class="text-muted small">
                                Weather
                            </div>

                            <div class="fw-semibold">
                                {{ $report->weather_condition ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Temperature
                            </div>

                            <div class="fw-semibold">
                                {{ $report->temperature ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Overall Progress
                            </div>

                            <div class="fw-semibold">
                                {{ number_format(
                                    (float) $report->overall_progress,
                                    2
                                ) }}%
                            </div>

                        </div>


                        <div class="col-md-12">

                            <div class="text-muted small">
                                Site Condition
                            </div>

                            <div class="fw-semibold">
                                {{ $report->site_condition ?? '—' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Work Summary --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Work Summary</strong>
                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $report->work_summary
                            ?? 'No work summary recorded.'
                        )
                    ) !!}

                </div>

            </div>


            {{-- Activities --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Activities</strong>
                </div>


                <div class="card-body">

                    <h6>
                        Completed
                    </h6>

                    <div class="mb-4">

                        {!! nl2br(
                            e(
                                $report->activities_completed
                                ?? '—'
                            )
                        ) !!}

                    </div>


                    <h6>
                        Planned
                    </h6>

                    <div>

                        {!! nl2br(
                            e(
                                $report->activities_planned
                                ?? '—'
                            )
                        ) !!}

                    </div>

                </div>

            </div>


            {{-- Resources --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Resources</strong>
                </div>


                <div class="card-body">

                    <h6>
                        Manpower
                    </h6>

                    <div class="mb-4">

                        {!! nl2br(
                            e(
                                $report->manpower_summary
                                ?? '—'
                            )
                        ) !!}

                    </div>


                    <h6>
                        Equipment
                    </h6>

                    <div class="mb-4">

                        {!! nl2br(
                            e(
                                $report->equipment_summary
                                ?? '—'
                            )
                        ) !!}

                    </div>


                    <h6>
                        Materials
                    </h6>

                    <div>

                        {!! nl2br(
                            e(
                                $report->material_summary
                                ?? '—'
                            )
                        ) !!}

                    </div>

                </div>

            </div>


            {{-- Safety & Quality --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Safety & Quality</strong>
                </div>


                <div class="card-body">

                    <h6>
                        Safety Observations
                    </h6>

                    <div class="mb-4">

                        {!! nl2br(
                            e(
                                $report->safety_observations
                                ?? '—'
                            )
                        ) !!}

                    </div>


                    <h6>
                        Quality Observations
                    </h6>

                    <div>

                        {!! nl2br(
                            e(
                                $report->quality_observations
                                ?? '—'
                            )
                        ) !!}

                    </div>

                </div>

            </div>


            {{-- Issues --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Issues & Delays</strong>
                </div>


                <div class="card-body">

                    <h6>
                        Delays
                    </h6>

                    <div class="mb-4">

                        {!! nl2br(
                            e(
                                $report->delays
                                ?? '—'
                            )
                        ) !!}

                    </div>


                    <h6>
                        Issues
                    </h6>

                    <div class="mb-4">

                        {!! nl2br(
                            e(
                                $report->issues
                                ?? '—'
                            )
                        ) !!}

                    </div>


                    <h6>
                        Corrective Actions
                    </h6>

                    <div>

                        {!! nl2br(
                            e(
                                $report->corrective_actions
                                ?? '—'
                            )
                        ) !!}

                    </div>

                </div>

            </div>


            {{-- Instructions / Remarks --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Instructions & Remarks</strong>
                </div>


                <div class="card-body">

                    <h6>
                        Instructions
                    </h6>

                    <div class="mb-4">

                        {!! nl2br(
                            e(
                                $report->instructions
                                ?? '—'
                            )
                        ) !!}

                    </div>


                    <h6>
                        Visitors
                    </h6>

                    <div class="mb-4">

                        {!! nl2br(
                            e(
                                $report->visitors
                                ?? '—'
                            )
                        ) !!}

                    </div>


                    <h6>
                        Remarks
                    </h6>

                    <div>

                        {!! nl2br(
                            e(
                                $report->remarks
                                ?? '—'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-lg-4">

            {{-- Status --}}

            @php

                $statusClass =
                    match(
                        $report->status
                    ) {

                        'Approved' =>
                            'bg-success',

                        'Submitted' =>
                            'bg-primary',

                        'Revision Required' =>
                            'bg-warning text-dark',

                        default =>
                            'bg-secondary',
                    };

            @endphp


            <div class="card mb-4">

                <div class="card-header">
                    <strong>Status</strong>
                </div>


                <div class="card-body">

                    <span
                        class="badge {{ $statusClass }}"
                    >
                        {{ $report->status }}
                    </span>

                </div>

            </div>


            {{-- Approval --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Approval</strong>
                </div>


                <div class="card-body">

                    <div class="mb-3">

                        <div class="text-muted small">
                            Submitted By
                        </div>

                        {{
                            $report
                                ->submittedBy
                                ?->name
                            ?? '—'
                        }}

                    </div>


                    <div class="mb-3">

                        <div class="text-muted small">
                            Submitted At
                        </div>

                        {{
                            $report->submitted_at
                                ?->format('d-m-Y H:i')
                            ?? '—'
                        }}

                    </div>


                    <div class="mb-3">

                        <div class="text-muted small">
                            Approved By
                        </div>

                        {{
                            $report
                                ->approvedBy
                                ?->name
                            ?? '—'
                        }}

                    </div>


                    <div>

                        <div class="text-muted small">
                            Approved At
                        </div>

                        {{
                            $report->approved_at
                                ?->format('d-m-Y H:i')
                            ?? '—'
                        }}

                    </div>

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
                            $report
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
                            $report->created_at
                                ?->format('d-m-Y H:i')
                            ?? '—'
                        }}

                    </div>


                    <div>

                        <div class="text-muted small">
                            Updated By
                        </div>

                        {{
                            $report
                                ->updater
                                ?->name
                            ?? '—'
                        }}

                    </div>

                </div>

            </div>


            {{-- Delete --}}

            @if($report->isDraft())

                <div class="card border-danger">

                    <div class="card-header text-danger">
                        <strong>Danger Zone</strong>
                    </div>


                    <div class="card-body">

                        <p class="small text-muted">
                            Only Draft reports can be deleted.
                        </p>


                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.construction.site-reports.destroy',
                                [
                                    'project' => $project,
                                    'report' => $report,
                                ]
                            ) }}"
                            onsubmit="
                                return confirm(
                                    'Are you sure you want to delete this Site Report?'
                                );
                            "
                        >

                            @csrf

                            @method('DELETE')


                            <button
                                type="submit"
                                class="btn btn-outline-danger w-100"
                            >
                                Delete Site Report
                            </button>

                        </form>

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection