@extends('layouts.app')

@section('content')

<div class="container-fluid">


    {{-- ============================================================
         HEADER
    ============================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">

                Project:

                <strong>
                    {{ $project->project_code ?? $project->name ?? '—' }}
                </strong>

            </div>

            <h3 class="mb-1">
                Environmental Actions
            </h3>

            <div class="text-muted">
                Track corrective, preventive and compliance actions.
            </div>

        </div>
        <div class="d-flex gap-2">
            <a
                href="{{ route(
                    'admin.projects.construction.hse.environmental.compliances.index',
                    ['project' => $project]
                ) }}"
                class="btn btn-outline-primary"
            >
                Compliance 
            </a>
            <a
                href="{{ route(
                    'admin.projects.construction.hse.environmental.records.index',
                    ['project' => $project]
                ) }}"
                class="btn btn-outline-primary"
            >
                Environmental 
            </a>
            <a
                href="{{ route(
                    'admin.projects.construction.hse.environmental.actions.create',
                    [
                        'project' => $project,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Add Environmental Action
            </a>
            
        </div>
        

    </div>


    {{-- ============================================================
         SUCCESS
    ============================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif


    {{-- ============================================================
         ERROR
    ============================================================= --}}

    @if(session('error'))

        <div class="alert alert-danger">

            {{ session('error') }}

        </div>

    @endif


    {{-- ============================================================
         SUMMARY
    ============================================================= --}}

    @php

        $totalActions = $actions->count();

        $openActions = $actions
            ->where('status', 'Open')
            ->count();

        $inProgressActions = $actions
            ->where('status', 'In Progress')
            ->count();

        $completedActions = $actions
            ->where('status', 'Completed')
            ->count();

        $overdueActions = $actions
            ->filter(
                fn ($item) =>
                    method_exists($item, 'isOverdue')
                        ? $item->isOverdue()
                        : false
            )
            ->count();

    @endphp


    <div class="row g-3 mb-4">


        {{-- Total --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Actions
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $totalActions }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Open --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Open
                    </div>

                    <div class="fs-3 fw-semibold text-primary">
                        {{ $openActions }}
                    </div>

                </div>

            </div>

        </div>


        {{-- In Progress --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        In Progress
                    </div>

                    <div class="fs-3 fw-semibold text-warning">
                        {{ $inProgressActions }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Overdue --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Overdue
                    </div>

                    <div class="fs-3 fw-semibold text-danger">
                        {{ $overdueActions }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         ACTION REGISTER
    ============================================================= --}}

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Environmental Action Register
            </strong>

            <span class="text-muted small">
                {{ $totalActions }} action(s)
            </span>

        </div>


        <div class="card-body p-0">

            @if($actions->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Action No.
                                </th>

                                <th>
                                    Action
                                </th>

                                <th>
                                    Source
                                </th>

                                <th>
                                    Type
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

                                <tr>


                                    {{-- Action Number --}}

                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.projects.construction.hse.environmental.actions.show',
                                                [
                                                    'project' => $project,
                                                    'action' => $action,
                                                ]
                                            ) }}"
                                            class="fw-semibold text-decoration-none"
                                        >
                                            {{ $action->action_number }}
                                        </a>

                                    </td>


                                    {{-- Action --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $action->action_title }}

                                        </div>

                                        @if($action->action_description)

                                            <div class="small text-muted">

                                                {{ \Illuminate\Support\Str::limit(
                                                    $action->action_description,
                                                    80
                                                ) }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Source --}}

                                    <td>

                                        @if($action->environmentalRecord)

                                            <span class="badge bg-info text-dark">
                                                Record
                                            </span>

                                            <div class="small mt-1">

                                                {{ $action->environmentalRecord->record_number }}

                                            </div>

                                        @elseif($action->environmentalCompliance)

                                            <span class="badge bg-secondary">
                                                Compliance
                                            </span>

                                            <div class="small mt-1">

                                                {{ $action->environmentalCompliance->compliance_number }}

                                            </div>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Type --}}

                                    <td>

                                        {{ $action->action_type }}

                                    </td>


                                    {{-- Priority --}}

                                    <td>

                                        @switch($action->priority)

                                            @case('Critical')

                                                <span class="badge bg-danger">
                                                    Critical
                                                </span>

                                                @break

                                            @case('High')

                                                <span class="badge bg-warning text-dark">
                                                    High
                                                </span>

                                                @break

                                            @case('Medium')

                                                <span class="badge bg-info text-dark">
                                                    Medium
                                                </span>

                                                @break

                                            @default

                                                <span class="badge bg-success">
                                                    Low
                                                </span>

                                        @endswitch

                                    </td>


                                    {{-- Assigned To --}}

                                    <td>

                                        {{ $action->assignee?->name
                                            ?? $action->assigned_to_name
                                            ?? '—'
                                        }}

                                    </td>


                                    {{-- Due Date --}}

                                    <td>

                                        @if($action->due_date)

                                            {{ $action->due_date->format('d-m-Y') }}

                                            @if(
                                                method_exists($action, 'isOverdue')
                                                && $action->isOverdue()
                                            )

                                                <div>

                                                    <span class="badge bg-danger">
                                                        Overdue
                                                    </span>

                                                </div>

                                            @endif

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        @switch($action->status)

                                            @case('Open')

                                                <span class="badge bg-primary">
                                                    Open
                                                </span>

                                                @break

                                            @case('In Progress')

                                                <span class="badge bg-warning text-dark">
                                                    In Progress
                                                </span>

                                                @break

                                            @case('Completed')

                                                <span class="badge bg-success">
                                                    Completed
                                                </span>

                                                @break

                                            @case('Closed')

                                                <span class="badge bg-secondary">
                                                    Closed
                                                </span>

                                                @break

                                            @default

                                                <span class="badge bg-light text-dark">
                                                    {{ $action->status }}
                                                </span>

                                        @endswitch

                                    </td>


                                    {{-- Verification --}}

                                    <td>

                                        @switch($action->verification_status)

                                            @case('Verified')

                                                <span class="badge bg-success">
                                                    Verified
                                                </span>

                                                @break

                                            @case('Rejected')

                                                <span class="badge bg-danger">
                                                    Rejected
                                                </span>

                                                @break

                                            @case('Pending')

                                                <span class="badge bg-warning text-dark">
                                                    Pending
                                                </span>

                                                @break

                                            @default

                                                <span class="badge bg-secondary">
                                                    Not Required
                                                </span>

                                        @endswitch

                                    </td>


                                    {{-- Actions --}}

                                    <td class="text-end">

                                        <div class="d-flex justify-content-end gap-1">

                                            <a
                                                href="{{ route(
                                                    'admin.projects.construction.hse.environmental.actions.show',
                                                    [
                                                        'project' => $project,
                                                        'action' => $action,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="View"
                                            >
                                                <i class="fa fa-eye"></i>
                                            </a>


                                            @if($action->status !== 'Closed')

                                                <a
                                                    href="{{ route(
                                                        'admin.projects.construction.hse.environmental.actions.edit',
                                                        [
                                                            'project' => $project,
                                                            'action' => $action,
                                                        ]
                                                    ) }}"
                                                    class="btn btn-sm btn-outline-secondary"
                                                    title="Edit"
                                                >
                                                    <i class="fa fa-edit"></i>
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

                    <div class="mb-3">

                        <i
                            class="bi bi-check2-square"
                            style="font-size: 42px;"
                        ></i>

                    </div>

                    <h5>
                        No Environmental Actions
                    </h5>

                    <p class="text-muted mb-4">

                        No corrective, preventive or compliance
                        actions have been created for this project.

                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.environmental.actions.create',
                            [
                                'project' => $project,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        Add Environmental Action
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection