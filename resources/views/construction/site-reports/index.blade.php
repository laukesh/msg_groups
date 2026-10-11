@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4 class="mb-1">
                Site Reports
            </h4>

            <div class="text-muted">
                {{ $project->project_name }}

                @if($project->project_code)
                    · {{ $project->project_code }}
                @endif
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.dashboard',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                Construction Dashboard
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.site-reports.create',
                    $project
                ) }}"
                class="btn btn-primary"
            >
                + Add Site Report
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


    {{-- Summary --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Reports
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $summary['total'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Draft
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $summary['draft'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Submitted
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $summary['submitted'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Approved
                    </div>

                    <div class="fs-3 fw-semibold text-success">
                        {{ $summary['approved'] }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Register --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Site Report Register
            </strong>

        </div>


        <div class="card-body p-0">

            @if($reports->isNotEmpty())

                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle mb-0"
                    >

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>
                                    Report
                                </th>

                                <th>
                                    Work Order
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Prepared By
                                </th>

                                <th>
                                    Progress
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

                            @foreach($reports as $report)

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


                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.projects.construction.site-reports.show',
                                                [
                                                    'project' => $project,
                                                    'report' => $report,
                                                ]
                                            ) }}"
                                            class="fw-semibold"
                                        >
                                            {{ $report->report_number }}
                                        </a>

                                        <div class="small text-muted">
                                            {{ $report->report_type }}
                                        </div>

                                    </td>


                                    <td>

                                        {{
                                            $report
                                                ->workOrder
                                                ?->work_order_number
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $report->report_date
                                                ?->format('d-m-Y')
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $report
                                                ->preparedBy
                                                ?->name
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        <div class="fw-semibold">

                                            {{
                                                number_format(
                                                    (float)
                                                    $report->overall_progress,
                                                    2
                                                )
                                            }}%

                                        </div>

                                    </td>


                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $report->status }}
                                        </span>

                                    </td>


                                    <td class="text-end">

                                        <div
                                            class="
                                                d-flex
                                                justify-content-end
                                                gap-1
                                            "
                                        >

                                            <a
                                                href="{{ route(
                                                    'admin.projects.construction.site-reports.show',
                                                    [
                                                        'project' => $project,
                                                        'report' => $report,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>


                                            @if($report->canEdit())

                                                <a
                                                    href="{{ route(
                                                        'admin.projects.construction.site-reports.edit',
                                                        [
                                                            'project' => $project,
                                                            'report' => $report,
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
                        No Site Reports found.
                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.construction.site-reports.create',
                            $project
                        ) }}"
                        class="btn btn-primary"
                    >
                        Add First Site Report
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection