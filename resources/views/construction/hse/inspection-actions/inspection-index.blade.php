@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">

                Inspection:

                <strong>
                    {{ $inspection->inspection_number }}
                </strong>

            </div>


            <h3 class="mb-1">
                Corrective Actions
            </h3>


            <div class="text-muted">

                {{ $project->project_code ?? '—' }}

                -

                {{ $project->project_name
                    ?? $project->name
                    ?? 'Project'
                }}

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
                Back to Inspection
            </a>

        </div>

    </div>


    {{-- =========================================================
        FLASH MESSAGES
    ========================================================== --}}

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


    {{-- =========================================================
        SUMMARY
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Inspection Number
                    </div>

                    <strong>
                        {{ $inspection->inspection_number }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Inspection Date
                    </div>

                    <strong>

                        {{ $inspection->inspection_date
                            ? $inspection->inspection_date->format('d-m-Y')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Total Corrective Actions
                    </div>

                    <span class="badge bg-primary fs-6">

                        {{ $actions->count() }}

                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        ACTION REGISTER
    ========================================================== --}}

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <div>

                <strong>
                    Corrective Action Register
                </strong>

                <span class="badge bg-primary ms-2">
                    {{ $actions->count() }}
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($actions->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Action
                            </th>

                            <th>
                                Finding
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Responsible
                            </th>

                            <th>
                                Due Date
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Verification
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($actions as $action)

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


                                $verificationClass =
                                    match(
                                        $action->verification_status
                                    ) {

                                        'Verified' =>
                                            'bg-success',

                                        'Rejected' =>
                                            'bg-danger',

                                        default =>
                                            'bg-warning text-dark',

                                    };

                            @endphp


                            <tr>

                                {{-- # --}}

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                {{-- Action --}}

                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.inspections.findings.actions.show',
                                            [
                                                'project' => $project,
                                                'inspection' => $inspection,
                                                'finding' => $action->finding,
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
                                            90
                                        ) }}

                                    </div>

                                </td>


                                {{-- Finding --}}

                                <td>

                                    @if($action->finding)

                                        <a
                                            href="{{ route(
                                                'admin.projects.construction.hse.inspections.findings.show',
                                                [
                                                    'project' => $project,
                                                    'inspection' => $inspection,
                                                    'finding' => $action->finding,
                                                ]
                                            ) }}"
                                            class="fw-semibold text-decoration-none"
                                        >
                                            {{ $action->finding->finding_number }}
                                        </a>


                                        @if($action->finding->finding_title)

                                            <div class="small text-muted">

                                                {{ \Illuminate\Support\Str::limit(
                                                    $action->finding->finding_title,
                                                    70
                                                ) }}

                                            </div>

                                        @endif

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Type --}}

                                <td>

                                    {{ $action->action_type ?? '—' }}

                                </td>


                                {{-- Responsible --}}

                                <td>

                                    {{ $action->responsible_name
                                        ?? $action->responsibleUser?->name
                                        ?? '—'
                                    }}

                                </td>


                                {{-- Due Date --}}

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


                                {{-- Status --}}

                                <td>

                                    <span
                                        class="badge {{ $statusClass }}"
                                    >
                                        {{ $action->status }}
                                    </span>

                                </td>


                                {{-- Verification --}}

                                <td>

                                    <span
                                        class="badge {{ $verificationClass }}"
                                    >
                                        {{ $action->verification_status
                                            ?? 'Pending'
                                        }}
                                    </span>

                                </td>


                                {{-- View --}}

                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.inspections.findings.actions.show',
                                            [
                                                'project' => $project,
                                                'inspection' => $inspection,
                                                'finding' => $action->finding,
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


                    <p class="text-muted mb-3">

                        No corrective actions have been
                        raised from the findings of this
                        inspection yet.

                    </p>


                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.inspections.findings.index',
                            [
                                'project' => $project,
                                'inspection' => $inspection,
                            ]
                        ) }}"
                        class="btn btn-outline-primary"
                    >
                        <i class="bi bi-search me-1"></i>
                        View Findings
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection