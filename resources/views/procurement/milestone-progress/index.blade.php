@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        HEADER
    ============================================================= --}}

    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">

        <div>

            <div class="text-muted small mb-1">

                Project:
                <strong>
                    {{ $project->name ?? $project->project_name ?? '—' }}
                </strong>

            </div>


            <div class="text-muted small mb-1">

                Contract:
                <strong>
                    {{ $contract->contract_number }}
                </strong>

            </div>


            <h4 class="mb-1 fw-bold">
                Milestone Progress
            </h4>


            <div class="text-muted">

                {{ $milestone->milestone_number }}
                -
                {{ $milestone->milestone_title }}

            </div>

        </div>


        <div class="d-flex flex-wrap gap-2">

            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Tender
            </a>


            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.contracts.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                        'contract' => $contract,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-file-earmark-text me-1"></i>
                Contract
            </a>


            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.contracts.milestones.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                        'contract' => $contract,
                        'milestone' => $milestone,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-flag me-1"></i>
                Milestone
            </a>


            @if(
                $contract->status === 'Active' &&
                $milestone->status !== 'Completed'
            )

                <a
                    href="{{ route(
                        'admin.projects.procurement.tenders.contracts.milestones.progress.create',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                            'contract' => $contract,
                            'milestone' => $milestone,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    <i class="bi bi-plus-lg me-1"></i>
                    Add Progress
                </a>

            @endif

        </div>

    </div>


    {{-- ============================================================
        FLASH MESSAGES
    ============================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show shadow-sm">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show shadow-sm">

            <i class="bi bi-exclamation-triangle me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ============================================================
        SUMMARY
    ============================================================= --}}

    @php

        $currentProgress =
            (float) $milestone->progress_percentage;

        $totalUpdates =
            $progressUpdates->count();

        $submittedUpdates =
            $progressUpdates
                ->where('status', 'Submitted')
                ->count();

        $verifiedUpdates =
            $progressUpdates
                ->where('status', 'Verified')
                ->count();

        $rejectedUpdates =
            $progressUpdates
                ->where('status', 'Rejected')
                ->count();

    @endphp


    <div class="row g-3 mb-4">

        {{-- Current Progress --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small mb-1">
                                Current Progress
                            </div>

                            <div class="fs-3 fw-bold">
                                {{ number_format($currentProgress, 2) }}%
                            </div>

                        </div>


                        <div
                            class="rounded-circle bg-primary bg-opacity-10
                                   text-primary d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-graph-up-arrow fs-4"></i>
                        </div>

                    </div>


                    <div
                        class="progress mt-3"
                        style="height:7px;"
                    >

                        <div
                            class="progress-bar"
                            style="width: {{ min($currentProgress, 100) }}%;"
                        ></div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Updates --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small mb-1">
                                Progress Updates
                            </div>

                            <div class="fs-3 fw-bold">
                                {{ $totalUpdates }}
                            </div>

                        </div>


                        <div
                            class="rounded-circle bg-info bg-opacity-10
                                   text-info d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-list-check fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Submitted --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small mb-1">
                                Submitted
                            </div>

                            <div class="fs-3 fw-bold text-warning">
                                {{ $submittedUpdates }}
                            </div>

                        </div>


                        <div
                            class="rounded-circle bg-warning bg-opacity-10
                                   text-warning d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-hourglass-split fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Verified --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small mb-1">
                                Verified
                            </div>

                            <div class="fs-3 fw-bold text-success">
                                {{ $verifiedUpdates }}
                            </div>

                        </div>


                        <div
                            class="rounded-circle bg-success bg-opacity-10
                                   text-success d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        MILESTONE STATUS
    ============================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Milestone Status
                    </small>

                    @php

                        $milestoneStatusClass = match(
                            $milestone->status
                        ) {

                            'Completed' =>
                                'bg-success-subtle text-success',

                            'In Progress' =>
                                'bg-primary-subtle text-primary',

                            default =>
                                'bg-secondary-subtle text-secondary',

                        };

                    @endphp

                    <span
                        class="badge {{ $milestoneStatusClass }} px-3 py-2"
                    >
                        {{ $milestone->status }}
                    </span>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Planned End
                    </small>

                    <strong>

                        {{
                            $milestone->planned_end_date
                                ?->format('d-m-Y')
                            ?? '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Milestone Amount
                    </small>

                    <strong>

                        {{
                            number_format(
                                (float) $milestone->milestone_amount,
                                2
                            )
                        }}

                        {{ $milestone->currency }}

                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        PROGRESS HISTORY
    ============================================================= --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-bottom py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h6 class="fw-bold mb-1">

                        <i class="bi bi-clock-history me-2 text-primary"></i>

                        Progress History

                    </h6>

                    <div class="small text-muted">
                        Historical progress updates for this milestone
                    </div>

                </div>


                <span class="badge rounded-pill bg-primary px-3 py-2">

                    {{ $totalUpdates }}

                    {{ $totalUpdates === 1
                        ? 'Update'
                        : 'Updates'
                    }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($progressUpdates->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0 progress-register">

                        <thead>

                            <tr>

                                <th style="width:120px;">
                                    Date
                                </th>

                                <th
                                    class="text-center"
                                    style="width:110px;"
                                >
                                    Previous
                                </th>

                                <th
                                    class="text-center"
                                    style="width:110px;"
                                >
                                    Progress
                                </th>

                                <th style="min-width:250px;">
                                    Work Completed
                                </th>

                                <th style="min-width:250px;">
                                    Work Pending
                                </th>

                                <th style="width:120px;">
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        @foreach($progressUpdates as $progress)

                            @php

                                $progressStatusClass = match(
                                    $progress->status
                                ) {

                                    'Verified' =>
                                        'bg-success-subtle text-success',

                                    'Rejected' =>
                                        'bg-danger-subtle text-danger',

                                    'Submitted' =>
                                        'bg-warning-subtle text-warning-emphasis',

                                    default =>
                                        'bg-secondary-subtle text-secondary',

                                };

                            @endphp


                            <tr>

                                {{-- Date --}}
                                <td>

                                    <div class="fw-semibold">

                                        {{
                                            $progress->progress_date
                                                ?->format('d-m-Y')
                                        }}

                                    </div>

                                    @if($progress->submitted_at)

                                        <div class="small text-muted">

                                            {{
                                                $progress->submitted_at
                                                    ->format('H:i')
                                            }}

                                        </div>

                                    @endif

                                </td>


                                {{-- Previous --}}
                                <td class="text-center">

                                    <span class="text-muted">

                                        {{
                                            number_format(
                                                (float)
                                                $progress->previous_progress_percentage,
                                                2
                                            )
                                        }}%

                                    </span>

                                </td>


                                {{-- Progress --}}
                                <td class="text-center">

                                    <span class="progress-value">

                                        {{
                                            number_format(
                                                (float)
                                                $progress->progress_percentage,
                                                2
                                            )
                                        }}%

                                    </span>

                                </td>


                                {{-- Work Completed --}}
                                <td>

                                    @if($progress->work_completed)

                                        <div class="history-text">

                                            {{
                                                $progress->work_completed
                                            }}

                                        </div>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Work Pending --}}
                                <td>

                                    @if($progress->work_pending)

                                        <div class="history-text">

                                            {{
                                                $progress->work_pending
                                            }}

                                        </div>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    <span
                                        class="badge {{ $progressStatusClass }} px-2 py-1"
                                    >
                                        {{ $progress->status }}
                                    </span>

                                </td>

                            </tr>


                            {{-- Additional Details --}}
                            @if(
                                $progress->progress_description ||
                                $progress->issues ||
                                $progress->corrective_action
                            )

                                <tr class="detail-row">

                                    <td colspan="6">

                                        <div class="row g-3">

                                            @if($progress->progress_description)

                                                <div class="col-md-4">

                                                    <div class="small fw-semibold text-muted mb-1">
                                                        Progress Description
                                                    </div>

                                                    <div class="small">
                                                        {{ $progress->progress_description }}
                                                    </div>

                                                </div>

                                            @endif


                                            @if($progress->issues)

                                                <div class="col-md-4">

                                                    <div class="small fw-semibold text-muted mb-1">
                                                        Issues
                                                    </div>

                                                    <div class="small">
                                                        {{ $progress->issues }}
                                                    </div>

                                                </div>

                                            @endif


                                            @if($progress->corrective_action)

                                                <div class="col-md-4">

                                                    <div class="small fw-semibold text-muted mb-1">
                                                        Corrective Action
                                                    </div>

                                                    <div class="small">
                                                        {{ $progress->corrective_action }}
                                                    </div>

                                                </div>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @endif

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5 px-3">

                    <div
                        class="rounded-circle bg-primary bg-opacity-10
                               text-primary d-inline-flex align-items-center
                               justify-content-center mb-3"
                        style="width:64px;height:64px;"
                    >
                        <i class="bi bi-graph-up fs-2"></i>
                    </div>


                    <h6 class="fw-bold">
                        No Progress Updates
                    </h6>


                    <p class="text-muted mb-4">
                        No progress has been recorded for this milestone yet.
                    </p>


                    @if(
                        $contract->status === 'Active' &&
                        $milestone->status !== 'Completed'
                    )

                        <a
                            href="{{ route(
                                'admin.projects.procurement.tenders.contracts.milestones.progress.create',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                    'contract' => $contract,
                                    'milestone' => $milestone,
                                ]
                            ) }}"
                            class="btn btn-primary"
                        >

                            <i class="bi bi-plus-lg me-1"></i>

                            Add First Progress Update

                        </a>

                    @endif

                </div>

            @endif

        </div>

    </div>

</div>


<style>

    .progress-register {
        font-size: 0.875rem;
    }

    .progress-register thead th {
        background: #f8f9fa;
        color: #495057;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        white-space: nowrap;
        padding: 14px 12px;
        border-bottom: 1px solid #dee2e6;
    }

    .progress-register tbody td {
        padding: 14px 12px;
        border-bottom: 1px solid #edf0f2;
    }

    .progress-register tbody tr:last-child td {
        border-bottom: 0;
    }

    .progress-value {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 6px;
        background: #f0f4ff;
        color: #4056c6;
        font-size: 0.8rem;
        font-weight: 700;
        border: 1px solid #dce3ff;
    }

    .history-text {
        white-space: pre-line;
        line-height: 1.45;
        max-width: 360px;
    }

    .detail-row td {
        background: #fafbfc;
        padding-top: 10px !important;
        padding-bottom: 12px !important;
    }

</style>

@endsection