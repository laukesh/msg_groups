@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4 class="mb-1">
                Site Issues / RFI
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
                    'admin.projects.construction.site-issues.create',
                    $project
                ) }}"
                class="btn btn-primary"
            >
                + Add Issue / RFI
            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Summary --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Issues
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
                        Open Issues
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $summary['open'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        High / Critical
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $summary['high_priority'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Overdue
                    </div>

                    <div class="fs-3 fw-semibold text-danger">
                        {{ $summary['overdue'] }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Register --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Site Issue Register
            </strong>

        </div>


        <div class="card-body p-0">

            @if($issues->isNotEmpty())

                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle mb-0"
                    >

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>
                                    Issue
                                </th>

                                <th>
                                    Work Order
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Priority
                                </th>

                                <th>
                                    Assigned To
                                </th>

                                <th>
                                    Due Date
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

                            @foreach($issues as $issue)

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


                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.projects.construction.site-issues.show',
                                                [
                                                    'project' => $project,
                                                    'issue' => $issue,
                                                ]
                                            ) }}"
                                            class="fw-semibold"
                                        >
                                            {{ $issue->issue_number }}
                                        </a>

                                        <div class="small text-muted">

                                            {{ $issue->title }}

                                        </div>

                                        <div class="small text-muted">

                                            {{ $issue->issue_type }}

                                        </div>

                                    </td>


                                    <td>

                                        {{
                                            $issue
                                                ->workOrder
                                                ?->work_order_number
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $issue->issue_date
                                                ?->format('d-m-Y')
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        <span
                                            class="badge {{ $priorityClass }}"
                                        >
                                            {{ $issue->priority }}
                                        </span>

                                    </td>


                                    <td>

                                        {{
                                            $issue
                                                ->assignedTo
                                                ?->name
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        @if($issue->due_date)

                                            <span
                                                class="
                                                    {{
                                                        $issue->isOverdue()
                                                            ? 'text-danger fw-semibold'
                                                            : ''
                                                    }}
                                                "
                                            >

                                                {{
                                                    $issue->due_date
                                                        ->format('d-m-Y')
                                                }}

                                            </span>

                                            @if($issue->isOverdue())

                                                <div class="small text-danger">
                                                    Overdue
                                                </div>

                                            @endif

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $issue->status }}
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
                                                    'admin.projects.construction.site-issues.show',
                                                    [
                                                        'project' => $project,
                                                        'issue' => $issue,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>


                                            <a
                                                href="{{ route(
                                                    'admin.projects.construction.site-issues.edit',
                                                    [
                                                        'project' => $project,
                                                        'issue' => $issue,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Edit
                                            </a>

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
                        No Site Issues / RFI found.
                    </div>

                    <a
                        href="{{ route(
                            'admin.projects.construction.site-issues.create',
                            $project
                        ) }}"
                        class="btn btn-primary"
                    >
                        Add First Issue / RFI
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection