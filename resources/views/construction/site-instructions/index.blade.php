@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction
            </div>

            <h4 class="mb-1">
                Site Instructions
            </h4>

            <div class="text-muted">
                {{ $project->name ?? $project->project_name ?? 'Project' }}
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
                    'admin.projects.construction.site-instructions.create',
                    $project
                ) }}"
                class="btn btn-primary"
            >
                + New Site Instruction
            </a>

        </div>

    </div>


    {{-- Flash Messages --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- Summary --}}

    <div class="row g-3 mb-4">

        <div class="col-md-2">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total
                    </div>

                    <div class="fs-4 fw-semibold">
                        {{ $counts['total'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-2">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Draft
                    </div>

                    <div class="fs-4 fw-semibold">
                        {{ $counts['draft'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-2">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Issued
                    </div>

                    <div class="fs-4 fw-semibold">
                        {{ $counts['issued'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        In Progress
                    </div>

                    <div class="fs-4 fw-semibold">
                        {{ $counts['in_progress'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-2">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Closed
                    </div>

                    <div class="fs-4 fw-semibold">
                        {{ $counts['closed'] }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Table --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Site Instruction Register
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th>
                                Instruction
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Subject
                            </th>

                            <th>
                                Contractor
                            </th>

                            <th>
                                Due Date
                            </th>

                            <th>
                                Priority
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

                        @forelse(
                            $instructions as $instruction
                        )

                            <tr>

                                <td>

                                    <div class="fw-semibold">

                                        {{ $instruction->instruction_number }}

                                    </div>

                                    @if($instruction->instruction_type)

                                        <div class="small text-muted">

                                            {{ $instruction->instruction_type }}

                                        </div>

                                    @endif

                                </td>


                                <td>

                                    {{
                                        $instruction->instruction_date
                                            ?->format('d-m-Y')
                                        ?? '—'
                                    }}

                                </td>


                                <td>

                                    <div class="fw-semibold">

                                        {{ $instruction->subject }}

                                    </div>

                                    @if($instruction->location)

                                        <div class="small text-muted">

                                            {{ $instruction->location }}

                                        </div>

                                    @endif

                                </td>


                                <td>

                                    {{
                                        $instruction->contract?->bidder?->company_name
                                        ?? $instruction->contract?->bidder_name
                                        ?? $instruction->workOrder?->contract?->bidder?->company_name
                                        ?? $instruction->workOrder?->contract?->bidder_name
                                        ?? '—'
                                    }}

                                </td>


                                <td>

                                    {{
                                        $instruction->due_date
                                            ?->format('d-m-Y')
                                        ?? '—'
                                    }}

                                </td>


                                <td>

                                    @php

                                        $priorityClass =
                                            match(
                                                $instruction->priority
                                            ) {

                                                'Critical' =>
                                                    'bg-danger',

                                                'High' =>
                                                    'bg-warning text-dark',

                                                'Low' =>
                                                    'bg-secondary',

                                                default =>
                                                    'bg-info text-dark',

                                            };

                                    @endphp


                                    <span
                                        class="badge {{ $priorityClass }}"
                                    >
                                        {{ $instruction->priority }}
                                    </span>

                                </td>


                                <td>

                                    @php

                                        $statusClass =
                                            match(
                                                $instruction->status
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

                                                'Rejected' =>
                                                    'bg-danger',

                                                default =>
                                                    'bg-warning text-dark',

                                            };

                                    @endphp


                                    <span
                                        class="badge {{ $statusClass }}"
                                    >
                                        {{ $instruction->status }}
                                    </span>

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.site-instructions.show',
                                            [
                                                'project' =>
                                                    $project,

                                                'siteInstruction' =>
                                                    $instruction,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted mb-3">

                                        No Site Instructions found.

                                    </div>


                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.site-instructions.create',
                                            $project
                                        ) }}"
                                        class="btn btn-primary"
                                    >
                                        Create First Instruction
                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if(
            $instructions->hasPages()
        )

            <div class="card-footer">

                {{ $instructions->links() }}

            </div>

        @endif

    </div>

</div>

@endsection