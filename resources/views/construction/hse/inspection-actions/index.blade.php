@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Finding:
                <strong>
                    {{ $finding->finding_number }}
                </strong>
            </div>

            <h3 class="mb-1">
                Corrective Actions
            </h3>

            <div class="text-muted">
                Inspection:
                {{ $inspection->inspection_number }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.hse.inspections.findings.show',
                    [
                        'project' => $project,
                        'inspection' => $inspection,
                        'finding' => $finding,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Finding
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.inspections.findings.actions.create',
                    [
                        'project' => $project,
                        'inspection' => $inspection,
                        'finding' => $finding,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Add Action
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


    <div class="card mb-4">

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Finding
                    </div>

                    <strong>
                        {{ $finding->finding_title }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Severity
                    </div>

                    <strong>
                        {{ $finding->severity }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Finding Status
                    </div>

                    <span class="badge bg-warning text-dark">
                        {{ $finding->status }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    <div class="card">

        <div class="card-header">

            <strong>
                Action Register
            </strong>

            <span class="badge bg-primary ms-2">
                {{ $actions->count() }}
            </span>

        </div>


        <div class="card-body p-0">

            @if($actions->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Action</th>

                            <th>Type</th>

                            <th>Responsible</th>

                            <th>Due Date</th>

                            <th>Status</th>

                            <th>Verification</th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($actions as $action)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.inspections.findings.actions.show',
                                            [
                                                'project' => $project,
                                                'inspection' => $inspection,
                                                'finding' => $finding,
                                                'action' => $action,
                                            ]
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >
                                        {{ $action->action_number }}
                                    </a>

                                    <div class="small text-muted">

                                        {{ \Illuminate\Support\Str::limit(
                                            $action->action_description,
                                            80
                                        ) }}

                                    </div>

                                </td>


                                <td>
                                    {{ $action->action_type ?? '—' }}
                                </td>


                                <td>

                                    {{ $action->responsible_name
                                        ?? $action->responsibleUser?->name
                                        ?? '—'
                                    }}

                                </td>


                                <td>

                                    {{ $action->due_date
                                        ? $action->due_date->format('d-m-Y')
                                        : '—'
                                    }}

                                    @if($action->isOverdue())

                                        <span class="badge bg-danger ms-1">
                                            Overdue
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @php

                                        $statusClass =
                                            match($action->status) {

                                                'Open' =>
                                                    'bg-primary',

                                                'In Progress' =>
                                                    'bg-warning text-dark',

                                                'Completed' =>
                                                    'bg-success',

                                                'Closed' =>
                                                    'bg-dark',

                                                default =>
                                                    'bg-secondary',

                                            };

                                    @endphp

                                    <span class="badge {{ $statusClass }}">
                                        {{ $action->status }}
                                    </span>

                                </td>


                                <td>

                                    @php

                                        $verificationClass =
                                            match($action->verification_status) {

                                                'Verified' =>
                                                    'bg-success',

                                                'Rejected' =>
                                                    'bg-danger',

                                                default =>
                                                    'bg-warning text-dark',

                                            };

                                    @endphp

                                    <span
                                        class="badge {{ $verificationClass }}"
                                    >
                                        {{ $action->verification_status ?? 'Pending' }}
                                    </span>

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.inspections.findings.actions.show',
                                            [
                                                'project' => $project,
                                                'inspection' => $inspection,
                                                'finding' => $finding,
                                                'action' => $action,
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
                        class="bi bi-check2-square"
                        style="font-size:42px;"
                    ></i>

                    <h6 class="mt-3">
                        No Corrective Actions Found
                    </h6>

                    <p class="text-muted">
                        No corrective action has been created
                        for this finding yet.
                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.inspections.findings.actions.create',
                            [
                                'project' => $project,
                                'inspection' => $inspection,
                                'finding' => $finding,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        Add First Action
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection