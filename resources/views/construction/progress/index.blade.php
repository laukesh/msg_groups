@extends('layouts.app')

@section('content')

@php

    $progressUpdates = $progressUpdates ?? collect();

    $summary = $summary ?? [
        'total' => 0,
        'latest_progress' => 0,
        'average_progress' => 0,
        'completed' => 0,
    ];

    $latestProgress = (float) (
        $summary['latest_progress'] ?? 0
    );

    $latestProgress = min(
        100,
        max(
            0,
            $latestProgress
        )
    );

    $averageProgress = (float) (
        $summary['average_progress'] ?? 0
    );

    $averageProgress = min(
        100,
        max(
            0,
            $averageProgress
        )
    );

@endphp


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
                Construction Progress
            </h4>

            <div class="text-muted">

                {{ $project->project_name ?? 'Project' }}

                @if(!empty($project->project_code))

                    · {{ $project->project_code }}

                @endif

            </div>

        </div>


        <div class="d-flex gap-2">
            <a
                href="{{ route(
                    'admin.projects.construction.dashboard',
                    [
                        'project' => $project,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Construction Dashboard
            </a>
            <a
                href="{{ route(
                    'admin.projects.construction.progress.create',
                    [
                        'project' => $project,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Add Progress
            </a>

        </div>

    </div>


    {{-- ================================================================
         FLASH MESSAGE
    ================================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-1"></i>

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

            <i class="bi bi-exclamation-triangle me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ================================================================
         SUMMARY
    ================================================================= --}}

    <div class="row g-3 mb-4">


        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Progress Updates
                    </div>

                    <div class="fs-3 fw-semibold">

                        {{ $summary['total'] ?? 0 }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Latest Progress
                    </div>

                    <div class="fs-3 fw-semibold">

                        {{ number_format(
                            $latestProgress,
                            2
                        ) }}%

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Average Progress
                    </div>

                    <div class="fs-3 fw-semibold">

                        {{ number_format(
                            $averageProgress,
                            2
                        ) }}%

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Completed Updates
                    </div>

                    <div class="fs-3 fw-semibold">

                        {{ $summary['completed'] ?? 0 }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
         CURRENT PROGRESS
    ================================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Current Project Progress
            </strong>

        </div>


        <div class="card-body">

            <div class="d-flex justify-content-between mb-2">

                <span class="text-muted">
                    Latest Reported Progress
                </span>

                <strong>

                    {{ number_format(
                        $latestProgress,
                        2
                    ) }}%

                </strong>

            </div>


            <div
                class="progress"
                style="height: 12px;"
            >

                <div
                    class="progress-bar"
                    role="progressbar"
                    style="width: {{ $latestProgress }}%;"
                    aria-valuenow="{{ $latestProgress }}"
                    aria-valuemin="0"
                    aria-valuemax="100"
                ></div>

            </div>

        </div>

    </div>


    {{-- ================================================================
         REGISTER
    ================================================================= --}}

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Progress Register
                </strong>

                <span class="text-muted small">

                    {{ $progressUpdates->count() }}

                    {{
                        $progressUpdates->count() === 1
                        ? 'record'
                        : 'records'
                    }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($progressUpdates->isEmpty())

                <div class="text-center py-5 px-3">

                    <div class="mb-3">

                        <i
                            class="bi bi-clipboard-data fs-1 text-muted"
                        ></i>

                    </div>

                    <h6>
                        No progress updates found
                    </h6>

                    <p class="text-muted mb-3">

                        No construction progress has been
                        recorded for this project yet.

                    </p>


                    <a
                        href="{{ route(
                            'admin.projects.construction.progress.create',
                            [
                                'project' => $project,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        Add First Progress Update
                    </a>

                </div>

            @else

                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle mb-0"
                    >

                        <thead class="table-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Progress Date
                                </th>

                                <th>
                                    Progress Number
                                </th>

                                <th>
                                    Work Order
                                </th>

                                <th>
                                    Contractor
                                </th>

                                <th>
                                    Progress
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Reported By
                                </th>

                                <th class="text-end">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $progressUpdates
                                as $progress
                            )

                                @php

                                    $percentage = (float) (
                                        $progress
                                            ->progress_percentage
                                        ?? 0
                                    );

                                    $percentage = min(
                                        100,
                                        max(
                                            0,
                                            $percentage
                                        )
                                    );

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


                                <tr>


                                    {{-- NUMBER --}}

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    {{-- DATE --}}

                                    <td>

                                        {{
                                            $progress
                                                ->progress_date
                                                ?->format('d-m-Y')
                                            ?? '—'
                                        }}

                                    </td>


                                    {{-- PROGRESS NUMBER --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{
                                                $progress
                                                    ->progress_number
                                            }}

                                        </div>

                                    </td>


                                    {{-- WORK ORDER --}}

                                    <td>

                                        @if(
                                            $progress->workOrder
                                        )

                                            <div class="fw-semibold">

                                                {{
                                                    $progress
                                                        ->workOrder
                                                        ->work_order_number
                                                }}

                                            </div>

                                            <div class="small text-muted">

                                                {{
                                                    $progress
                                                        ->workOrder
                                                        ->work_order_title
                                                    ?? '—'
                                                }}

                                            </div>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- CONTRACTOR --}}

                                    <td>

                                        @if(
                                            $progress
                                                ->workOrder
                                                ?->contract
                                                ?->bidder
                                        )

                                            {{
                                                $progress
                                                    ->workOrder
                                                    ->contract
                                                    ->bidder
                                                    ->company_name
                                                ??
                                                $progress
                                                    ->workOrder
                                                    ->contract
                                                    ->bidder
                                                    ->bidder_name
                                                ??
                                                '—'
                                            }}

                                        @elseif(
                                            $progress
                                                ->workOrder
                                                ?->contract
                                                ?->bidder_name
                                        )

                                            {{
                                                $progress
                                                    ->workOrder
                                                    ->contract
                                                    ->bidder_name
                                            }}

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- PROGRESS --}}

                                    <td style="min-width: 170px;">

                                        <div
                                            class="d-flex justify-content-between mb-1"
                                        >

                                            <span class="small text-muted">
                                                Overall
                                            </span>

                                            <strong class="small">

                                                {{
                                                    number_format(
                                                        $percentage,
                                                        2
                                                    )
                                                }}%

                                            </strong>

                                        </div>


                                        <div
                                            class="progress"
                                            style="height: 7px;"
                                        >

                                            <div
                                                class="progress-bar"
                                                role="progressbar"
                                                style="width: {{ $percentage }}%;"
                                                aria-valuenow="{{ $percentage }}"
                                                aria-valuemin="0"
                                                aria-valuemax="100"
                                            ></div>

                                        </div>

                                    </td>


                                    {{-- STATUS --}}

                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $progress->status }}
                                        </span>

                                    </td>


                                    {{-- REPORTED BY --}}

                                    <td>

                                        {{
                                            $progress
                                                ->reportedBy
                                                ?->name
                                            ?? '—'
                                        }}

                                    </td>


                                    {{-- ACTION --}}

                                    <td class="text-end">

                                        <div
                                            class="d-flex justify-content-end gap-1"
                                        >

                                            <a
                                                href="{{ route(
                                                    'admin.projects.construction.progress.show',
                                                    [
                                                        'project' => $project,
                                                        'progress' => $progress,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="View"
                                            >

                                                <i class="fa fa-eye"></i>

                                            </a>


                                            <a
                                                href="{{ route(
                                                    'admin.projects.construction.progress.edit',
                                                    [
                                                        'project' => $project,
                                                        'progress' => $progress,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                                title="Edit"
                                            >

                                                <i class="fa fa-edit"></i>

                                            </a>


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
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete"
                                                >

                                                    <i class="fa fa-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection