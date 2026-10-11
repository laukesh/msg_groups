@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Inspection:
                <strong>
                    {{ $inspection->inspection_number }}
                </strong>
            </div>

            <h3 class="mb-1">
                Inspection Findings
            </h3>

            <div class="text-muted">
                {{ $project->project_code ?? '—' }}
                -
                {{ $project->project_name ?? $project->name ?? 'Project' }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.hse.inspections.show',
                    [
                        'project' => $project,
                        'inspection' => $inspection,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Inspection
            </a>

            <a
                href="{{ route(
                    'admin.projects.construction.hse.inspections.findings.create',
                    [
                        'project' => $project,
                        'inspection' => $inspection,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Add Finding
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


    @php

        $total = $findings->count();

        $open = $findings
            ->whereIn('status', [
                'Open',
                'In Progress',
                'Action Required'
            ])
            ->count();

        $resolved = $findings
            ->where('status', 'Resolved')
            ->count();

        $critical = $findings
            ->where('severity', 'Critical')
            ->count();

        $high = $findings
            ->where('severity', 'High')
            ->count();

    @endphp


    <div class="row g-3 mb-4">

        <div class="col-md">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Total Findings
                    </div>
                    <h4 class="mb-0">
                        {{ $total }}
                    </h4>
                </div>
            </div>
        </div>


        <div class="col-md">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Open
                    </div>
                    <h4 class="mb-0 text-warning">
                        {{ $open }}
                    </h4>
                </div>
            </div>
        </div>


        <div class="col-md">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Resolved
                    </div>
                    <h4 class="mb-0 text-success">
                        {{ $resolved }}
                    </h4>
                </div>
            </div>
        </div>


        <div class="col-md">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        High
                    </div>
                    <h4 class="mb-0 text-warning">
                        {{ $high }}
                    </h4>
                </div>
            </div>
        </div>


        <div class="col-md">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Critical
                    </div>
                    <h4 class="mb-0 text-danger">
                        {{ $critical }}
                    </h4>
                </div>
            </div>
        </div>

    </div>


    <div class="card">

        <div class="card-header">

            <strong>
                Finding Register
            </strong>

            <span class="badge bg-primary ms-2">
                {{ $total }}
            </span>

        </div>


        <div class="card-body p-0">

            @if($findings->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Finding</th>

                            <th>Type</th>

                            <th>Severity</th>

                            <th>Responsible</th>

                            <th>Due Date</th>

                            <th>Status</th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($findings as $finding)

                            <tr>

                                <td>
                                    {{ $finding->finding_number }}
                                </td>


                                <td style="min-width:260px;">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.inspections.findings.show',
                                            [
                                                'project' => $project,
                                                'inspection' => $inspection,
                                                'finding' => $finding,
                                            ]
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >
                                        {{ $finding->finding_title }}
                                    </a>

                                </td>


                                <td>
                                    {{ $finding->finding_type ?? '—' }}
                                </td>


                                <td>

                                    @php

                                        $severityClass =
                                            match($finding->severity) {

                                                'Critical' =>
                                                    'bg-danger',

                                                'High' =>
                                                    'bg-warning text-dark',

                                                'Medium' =>
                                                    'bg-info text-dark',

                                                'Low' =>
                                                    'bg-secondary',

                                                default =>
                                                    'bg-secondary',

                                            };

                                    @endphp

                                    <span
                                        class="badge {{ $severityClass }}"
                                    >
                                        {{ $finding->severity }}
                                    </span>

                                </td>


                                <td>

                                    {{ $finding->responsible_name
                                        ?? $finding->responsibleUser?->name
                                        ?? '—'
                                    }}

                                </td>


                                <td>

                                    {{ $finding->due_date
                                        ? $finding->due_date->format('d-m-Y')
                                        : '—'
                                    }}

                                    @if($finding->isOverdue())

                                        <span class="badge bg-danger ms-1">
                                            Overdue
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @php

                                        $statusClass =
                                            match($finding->status) {

                                                'Open' =>
                                                    'bg-primary',

                                                'In Progress' =>
                                                    'bg-warning text-dark',

                                                'Action Required' =>
                                                    'bg-warning text-dark',

                                                'Resolved' =>
                                                    'bg-success',

                                                'Verified' =>
                                                    'bg-success',

                                                'Closed' =>
                                                    'bg-dark',

                                                default =>
                                                    'bg-secondary',

                                            };

                                    @endphp

                                    <span
                                        class="badge {{ $statusClass }}"
                                    >
                                        {{ $finding->status }}
                                    </span>

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.inspections.findings.show',
                                            [
                                                'project' => $project,
                                                'inspection' => $inspection,
                                                'finding' => $finding,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <i
                        class="bi bi-exclamation-diamond"
                        style="font-size:42px;"
                    ></i>

                    <h6 class="mt-3">
                        No Findings Found
                    </h6>

                    <p class="text-muted">
                        No findings have been recorded for this inspection.
                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.inspections.findings.create',
                            [
                                'project' => $project,
                                'inspection' => $inspection,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        Add First Finding
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection