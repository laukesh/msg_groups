@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Development Project
            </div>

            <h3 class="mb-1">
                {{ $project->project_name }}
            </h3>

            <div class="text-muted">

                {{ $project->project_number }}

                @if($project->project_code)
                    · {{ $project->project_code }}
                @endif

            </div>

        </div>


        <div class="d-flex gap-2">

            


            <a
                href="{{ route('admin.projects.index') }}"
                class="btn btn-outline-secondary"
            >
                ← Projects
            </a>
            <a
                href="{{ route(
                    'admin.projects.edit',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-outline-primary"
            >
                Edit Project
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Messages --}}
    {{-- ========================================================= --}}

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


    {{-- ========================================================= --}}
    {{-- Project Status --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Project Stage
                    </div>

                    <div class="mt-2">

                        <span class="badge bg-info text-dark fs-6">
                            {{ $project->project_stage }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Project Status
                    </div>

                    <div class="mt-2">

                        @php
                            $statusClass = match($project->project_status) {
                                'Draft' => 'bg-secondary',
                                'Pending Approval' => 'bg-warning text-dark',
                                'Approved' => 'bg-info text-dark',
                                'Active' => 'bg-success',
                                'On Hold' => 'bg-warning text-dark',
                                'Delayed' => 'bg-danger',
                                'Completed' => 'bg-success',
                                'Cancelled' => 'bg-danger',
                                'Closed' => 'bg-dark',
                                default => 'bg-secondary',
                            };
                        @endphp

                        <span class="badge {{ $statusClass }} fs-6">
                            {{ $project->project_status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Project Type
                    </div>

                    <div class="fw-semibold mt-2">
                        {{ $project->project_type ?? '-' }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Priority
                    </div>

                    <div class="fw-semibold mt-2">
                        {{ $project->project_priority ?? '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Project Status Workflow --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Project Status Workflow</strong>
            <span class="text-muted small">Status changes are controlled by workflow actions</span>
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2 mb-3">
                @if($project->project_status === 'Draft')
                    <form method="POST" action="{{ route('admin.projects.submit', $project) }}">@csrf<button class="btn btn-primary">Submit for Approval</button></form>
                @elseif($project->project_status === 'Pending Approval')
                    <form method="POST" action="{{ route('admin.projects.approve', $project) }}">@csrf<button class="btn btn-success">Approve</button></form>
                    <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#projectRejectModal">Reject</button>
                @elseif($project->project_status === 'Approved')
                    <form method="POST" action="{{ route('admin.projects.start', $project) }}">@csrf<button class="btn btn-success">Start Project</button></form>
                    <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#projectCancelModal">Cancel</button>
                @elseif($project->project_status === 'Active')
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#projectHoldModal">Put On Hold</button>
                    <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#projectDelayModal">Mark Delayed</button>
                    <form method="POST" action="{{ route('admin.projects.complete', $project) }}">@csrf<button class="btn btn-success">Mark Completed</button></form>
                    <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#projectCancelModal">Cancel</button>
                @elseif($project->project_status === 'On Hold')
                    <form method="POST" action="{{ route('admin.projects.resume', $project) }}">@csrf<button class="btn btn-success">Resume</button></form>
                    <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#projectCancelModal">Cancel</button>
                @elseif($project->project_status === 'Delayed')
                    <form method="POST" action="{{ route('admin.projects.resolve_delay', $project) }}">@csrf<button class="btn btn-success">Resolve Delay</button></form>
                    <form method="POST" action="{{ route('admin.projects.complete', $project) }}">@csrf<button class="btn btn-success">Mark Completed</button></form>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#projectHoldModal">Put On Hold</button>
                @elseif($project->project_status === 'Completed')
                    <form method="POST" action="{{ route('admin.projects.close', $project) }}">@csrf<button class="btn btn-dark">Close Project</button></form>
                @endif
            </div>

            <div class="small text-muted mb-3">Lifecycle: Draft → Pending Approval → Approved → Active → Completed → Closed. On Hold / Delayed are controlled exception states.</div>

            @if($project->statusHistories->count())
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle mb-0">
                        <thead class="table-light"><tr><th>Date</th><th>From</th><th>To</th><th>Action</th><th>Remarks</th><th>By</th></tr></thead>
                        <tbody>
                        @foreach($project->statusHistories as $history)
                            <tr>
                                <td>{{ optional($history->performed_at)->format('d M Y H:i') }}</td>
                                <td>{{ $history->from_status ?? '-' }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $history->to_status }}</span></td>
                                <td>{{ $history->action }}</td>
                                <td>{{ $history->remarks ?? '-' }}</td>
                                <td>{{ optional($history->performedBy)->name ?? 'System' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-muted">No status workflow history available.</div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="projectRejectModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('admin.projects.reject', $project) }}">@csrf
            <div class="modal-header"><h5 class="modal-title">Reject Project</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body"><label class="form-label">Reason <span class="text-danger">*</span></label><textarea name="remarks" class="form-control" rows="4" required></textarea></div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-danger">Reject</button></div>
        </form>
    </div></div></div>

    <div class="modal fade" id="projectHoldModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('admin.projects.hold', $project) }}">@csrf
            <div class="modal-header"><h5 class="modal-title">Put Project On Hold</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body"><label class="form-label">Remarks</label><textarea name="remarks" class="form-control" rows="4"></textarea></div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-warning">Put On Hold</button></div>
        </form>
    </div></div></div>

    <div class="modal fade" id="projectDelayModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('admin.projects.delay', $project) }}">@csrf
            <div class="modal-header"><h5 class="modal-title">Mark Project Delayed</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body"><label class="form-label">Reason <span class="text-danger">*</span></label><textarea name="remarks" class="form-control" rows="4" required></textarea></div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-danger">Mark Delayed</button></div>
        </form>
    </div></div></div>

    <div class="modal fade" id="projectCancelModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('admin.projects.cancel', $project) }}">@csrf
            <div class="modal-header"><h5 class="modal-title">Cancel Project</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body"><label class="form-label">Reason <span class="text-danger">*</span></label><textarea name="remarks" class="form-control" rows="4" required></textarea></div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back</button><button class="btn btn-danger">Cancel Project</button></div>
        </form>
    </div></div></div>

    {{-- ========================================================= --}}
    {{-- Lifecycle Traceability --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Investment & Lifecycle Traceability
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Land --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small">
                            Land
                        </div>

                        <div class="fw-semibold mt-1">

                            @if($project->land)

                                {{
                                    $project->land->land_name
                                    ?? $project->land->name
                                    ?? 'Land #' . $project->land_id
                                }}

                            @else

                                Land #{{ $project->land_id }}

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Feasibility --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small">
                            Feasibility Assessment
                        </div>

                        <div class="fw-semibold mt-1">

                            @if($project->feasibilityAssessment)

                                {{
                                    $project
                                        ->feasibilityAssessment
                                        ->assessment_number
                                    ?? 'Assessment #' .
                                        $project->feasibility_assessment_id
                                }}

                            @else

                                Assessment #{{ $project->feasibility_assessment_id }}

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Investment Decision --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small">
                            Investment Decision
                        </div>

                        <div class="fw-semibold mt-1">

                            @if($project->investmentDecision)

                                {{
                                    $project
                                        ->investmentDecision
                                        ->decision_number
                                    ?? 'Decision #' .
                                        $project->investment_decision_id
                                }}

                            @else

                                Decision #{{ $project->investment_decision_id }}

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Development Planning --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Development Planning
                </strong>

                <span class="text-muted small">
                    Project Planning Modules
                </span>

            </div>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- Development Strategy --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="fw-semibold">
                            Development Strategy
                        </div>

                        <div class="text-muted small mt-1">
                            Define the overall development approach,
                            objectives and strategic direction.
                        </div>

                        <div class="mt-3">

                            <a
                                href="{{ route(
                                    'admin.projects.development-strategy.index',
                                    ['project' => $project->id]
                                ) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Open Module
                            </a>

                        </div>

                    </div>

                </div>


                {{-- Master Schedule --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="fw-semibold">
                            Master Schedule
                        </div>

                        <div class="text-muted small mt-1">
                            Plan project phases, milestones,
                            activities and timelines.
                        </div>

                        <div class="mt-3">

                            <a
                                href="{{ route(
                                    'admin.projects.master-schedule.index',
                                    ['project' => $project->id]
                                ) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Open Module
                            </a>

                        </div>

                    </div>

                </div>


                {{-- Budget --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="fw-semibold">
                            Budget
                        </div>

                        <div class="text-muted small mt-1">
                            Establish the approved development
                            budget and cost structure.
                        </div>

                        <div class="mt-3">

                            <a
                                href="{{ route(
                                    'admin.projects.budget.index',
                                    ['project' => $project->id]
                                ) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Open Module
                            </a>

                        </div>

                    </div>

                </div>


                {{-- Funding Plan --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="fw-semibold">
                            Funding Plan
                        </div>

                        <div class="text-muted small mt-1">
                            Define equity, debt and other project
                            funding sources.
                        </div>

                        <div class="mt-3">

                            <a
                                href="{{ route(
                                    'admin.projects.funding-plan.index',
                                    ['project' => $project->id]
                                ) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Open Module
                            </a>

                        </div>

                    </div>

                </div>


                {{-- Delivery Strategy --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="fw-semibold">
                            Delivery Strategy
                        </div>

                        <div class="text-muted small mt-1">
                            Define how the development will
                            be delivered.
                        </div>

                        <div class="mt-3">

                            <a
                                href="{{ route(
                                    'admin.projects.delivery-strategy.index',
                                    ['project' => $project->id]
                                ) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Open Module
                            </a>

                        </div>

                    </div>

                </div>


                {{-- Procurement Strategy --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="fw-semibold">
                            Procurement Strategy
                        </div>

                        <div class="text-muted small mt-1">
                            Define procurement approach,
                            packages and sourcing strategy.
                        </div>

                        <div class="mt-3">

                            <a
                                href="{{ route(
                                    'admin.projects.procurement-strategy.index',
                                    ['project' => $project->id]
                                ) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Open Module
                            </a>

                        </div>

                    </div>

                </div>


                {{-- Contract Strategy --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="fw-semibold">
                            Contract Strategy
                        </div>

                        <div class="text-muted small mt-1">
                            Define contract models, commercial
                            approach and contracting strategy.
                        </div>

                        <div class="mt-3">

                            <a
                                href="{{ route(
                                    'admin.projects.contract-strategy.index',
                                    ['project' => $project->id]
                                ) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Open Module
                            </a>

                        </div>

                    </div>

                </div>


                {{-- Risk Register --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="fw-semibold">
                            Risk Register
                        </div>

                        <div class="text-muted small mt-1">
                            Identify, assess and manage
                            project execution risks.
                        </div>

                        <div class="mt-3">

                            <a
                                href="{{ route(
                                    'admin.projects.risks.index',
                                    ['project' => $project->id]
                                ) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Open Module
                            </a>

                        </div>

                    </div>

                </div>


                {{-- Stakeholder Register --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="fw-semibold">
                            Stakeholder Register
                        </div>

                        <div class="text-muted small mt-1">
                            Identify project stakeholders,
                            responsibilities and engagement.
                        </div>

                        <div class="mt-3">

                            <a
                                href="{{ route(
                                    'admin.projects.stakeholders.index',
                                    ['project' => $project->id]
                                ) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Open Module
                            </a>

                        </div>

                    </div>

                </div>


                {{-- Project Governance --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="fw-semibold">
                            Project Governance
                        </div>

                        <div class="text-muted small mt-1">
                            Define project authority,
                            approvals, reporting and escalation.
                        </div>

                        <div class="mt-3">

                            <a
                                href="{{ route(
                                    'admin.projects.governance.index',
                                    ['project' => $project->id]
                                ) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Open Module
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Funding Plan Summary --}}
    {{-- ========================================================= --}}

    @php

        $latestFundingPlan = null;

        if (
            method_exists($project, 'fundingPlans') &&
            $project->relationLoaded('fundingPlans')
        ) {
            $latestFundingPlan = $project->fundingPlans
                ->sortByDesc('version_number')
                ->first();
        }

    @endphp


    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Funding Plan Summary
                    </strong>

                    <div class="text-muted small mt-1">
                        Project funding position and latest revision
                    </div>

                </div>


                <a
                    href="{{ route(
                        'admin.projects.funding-plan.index',
                        ['project' => $project->id]
                    ) }}"
                    class="btn btn-sm btn-outline-primary"
                >
                    Open Funding Plans
                </a>

            </div>

        </div>


        <div class="card-body">

            @if($latestFundingPlan)

                @php

                    $requirement =
                        (float) $latestFundingPlan
                            ->total_funding_requirement;

                    $planned =
                        (float) $latestFundingPlan
                            ->total_planned_funding;

                    $committed =
                        (float) $latestFundingPlan
                            ->total_committed_funding;

                    $gap =
                        max(
                            $requirement - $committed,
                            0
                        );

                    $coverage =
                        $requirement > 0
                            ? min(
                                ($committed / $requirement) * 100,
                                100
                            )
                            : 0;

                    $fundingStatusClass =
                        match(
                            $latestFundingPlan->status
                        ) {

                            'Approved'
                                => 'bg-success',

                            'Submitted',
                            'Under Review'
                                => 'bg-warning text-dark',

                            'Rejected'
                                => 'bg-danger',

                            default
                                => 'bg-secondary',

                        };

                @endphp


                {{-- Plan Header --}}

                <div class="d-flex justify-content-between align-items-start mb-4">

                    <div>

                        <div class="text-muted small">
                            Latest Funding Plan
                        </div>

                        <div class="fw-semibold fs-5">

                            {{ $latestFundingPlan->funding_plan_number }}

                            <span class="text-muted">
                                · V{{ $latestFundingPlan->version_number }}
                            </span>

                        </div>

                        <div class="text-muted small mt-1">

                            {{ $latestFundingPlan->title }}

                        </div>

                    </div>


                    <span class="badge {{ $fundingStatusClass }} fs-6">

                        {{ $latestFundingPlan->status }}

                    </span>

                </div>


                {{-- Financial Summary --}}

                <div class="row g-3">


                    {{-- Requirement --}}

                    <div class="col-md-3">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small">
                                Funding Requirement
                            </div>

                            <div class="fs-5 fw-semibold mt-1">

                                {{ $latestFundingPlan->currency }}

                                {{
                                    number_format(
                                        $requirement,
                                        2
                                    )
                                }}

                            </div>

                        </div>

                    </div>


                    {{-- Planned --}}

                    <div class="col-md-3">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small">
                                Planned Funding
                            </div>

                            <div class="fs-5 fw-semibold mt-1">

                                {{ $latestFundingPlan->currency }}

                                {{
                                    number_format(
                                        $planned,
                                        2
                                    )
                                }}

                            </div>

                        </div>

                    </div>


                    {{-- Committed --}}

                    <div class="col-md-3">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small">
                                Committed Funding
                            </div>

                            <div class="fs-5 fw-semibold mt-1">

                                {{ $latestFundingPlan->currency }}

                                {{
                                    number_format(
                                        $committed,
                                        2
                                    )
                                }}

                            </div>

                        </div>

                    </div>


                    {{-- Gap --}}

                    <div class="col-md-3">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small">
                                Funding Gap
                            </div>

                            @if($gap > 0)

                                <div class="fs-5 fw-semibold text-danger mt-1">

                                    {{ $latestFundingPlan->currency }}

                                    {{
                                        number_format(
                                            $gap,
                                            2
                                        )
                                    }}

                                </div>

                            @else

                                <div class="fs-5 fw-semibold text-success mt-1">

                                    Fully Covered

                                </div>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Coverage --}}

                <div class="mt-4">

                    <div class="d-flex justify-content-between mb-1">

                        <span class="small text-muted">
                            Committed Funding Coverage
                        </span>

                        <strong class="small">

                            {{ number_format(
                                $coverage,
                                2
                            ) }}%

                        </strong>

                    </div>


                    <div
                        class="progress"
                        style="height: 18px;"
                    >

                        <div
                            class="progress-bar
                                {{
                                    $coverage >= 100
                                        ? 'bg-success'
                                        : 'bg-primary'
                                }}"
                            role="progressbar"
                            style="width: {{ $coverage }}%;"
                            aria-valuenow="{{ $coverage }}"
                            aria-valuemin="0"
                            aria-valuemax="100"
                        >
                            {{ number_format(
                                $coverage,
                                1
                            ) }}%
                        </div>

                    </div>

                </div>


                {{-- Actions --}}

                <div class="mt-4">

                    <a
                        href="{{ route(
                            'admin.projects.funding-plan.show',
                            [
                                'project' => $project->id,
                                'fundingPlan' =>
                                    $latestFundingPlan->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        View Latest Funding Plan
                    </a>


                    <a
                        href="{{ route(
                            'admin.projects.funding-plan.index',
                            [
                                'project' => $project->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-outline-secondary"
                    >
                        View All Revisions
                    </a>

                </div>


            @else

                {{-- No Funding Plan --}}

                <div class="text-center py-4">

                    <div class="text-muted mb-2">

                        No Funding Plan has been created
                        for this project yet.

                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.funding-plan.create',
                            [
                                'project' => $project->id,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Create Funding Plan
                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Project Information --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">

        {{-- Scope --}}

        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header">

                    <strong>
                        Development Scope
                    </strong>

                </div>

                <div class="card-body">

                    <h6>
                        Development Objective
                    </h6>

                    <p class="text-muted">

                        {!! nl2br(
                            e(
                                $project->development_objective
                                ?? '-'
                            )
                        ) !!}

                    </p>


                    <h6 class="mt-4">
                        Scope Summary
                    </h6>

                    <p class="text-muted">

                        {!! nl2br(
                            e(
                                $project->scope_summary
                                ?? '-'
                            )
                        ) !!}

                    </p>


                    <h6 class="mt-4">
                        Development Scope
                    </h6>

                    <p class="text-muted mb-0">

                        {!! nl2br(
                            e(
                                $project->development_scope
                                ?? '-'
                            )
                        ) !!}

                    </p>

                </div>

            </div>

        </div>


        {{-- Dates --}}

        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header">

                    <strong>
                        Project Dates
                    </strong>

                </div>


                <div class="card-body">

                    <table class="table table-sm mb-0">

                        <tr>

                            <th style="width:45%;">
                                Approval Date
                            </th>

                            <td>

                                {{
                                    $project->approval_date
                                        ? $project->approval_date
                                            ->format('d M Y')
                                        : '-'
                                }}

                            </td>

                        </tr>


                        <tr>

                            <th>
                                Project Initiation
                            </th>

                            <td>

                                {{
                                    $project
                                        ->project_initiation_date
                                    ? $project
                                        ->project_initiation_date
                                        ->format('d M Y')
                                    : '-'
                                }}

                            </td>

                        </tr>


                        <tr>

                            <th>
                                Planned Start
                            </th>

                            <td>

                                {{
                                    $project->project_start_date
                                        ? $project
                                            ->project_start_date
                                            ->format('d M Y')
                                        : '-'
                                }}

                            </td>

                        </tr>


                        <tr>

                            <th>
                                Planned Completion
                            </th>

                            <td>

                                {{
                                    $project
                                        ->planned_completion_date
                                    ? $project
                                        ->planned_completion_date
                                        ->format('d M Y')
                                    : '-'
                                }}

                            </td>

                        </tr>


                        <tr>

                            <th>
                                Actual Completion
                            </th>

                            <td>

                                {{
                                    $project
                                        ->actual_completion_date
                                    ? $project
                                        ->actual_completion_date
                                        ->format('d M Y')
                                    : '-'
                                }}

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- Delivery Strategy Summary --}}
    {{-- ========================================================= --}}

    @php

        $latestDeliveryStrategy = null;

        if (
            method_exists($project, 'deliveryStrategies') &&
            $project->relationLoaded('deliveryStrategies')
        ) {
            $latestDeliveryStrategy = $project->deliveryStrategies
                ->sortByDesc('version_number')
                ->first();
        }

    @endphp


    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Delivery Strategy Summary
                    </strong>

                    <div class="text-muted small mt-1">
                        Project delivery approach and latest revision
                    </div>

                </div>


                <a
                    href="{{ route(
                        'admin.projects.delivery-strategy.index',
                        ['project' => $project->id]
                    ) }}"
                    class="btn btn-sm btn-outline-primary"
                >
                    Open Delivery Strategies
                </a>

            </div>

        </div>


        <div class="card-body">

            @if($latestDeliveryStrategy)

                @php

                    $statusClass =
                        match(
                            $latestDeliveryStrategy->status
                        ) {

                            'Approved'
                                => 'bg-success',

                            'Submitted',
                            'Under Review'
                                => 'bg-warning text-dark',

                            'Rejected'
                                => 'bg-danger',

                            default
                                => 'bg-secondary',

                        };

                @endphp


                {{-- Header --}}

                <div class="d-flex justify-content-between align-items-start mb-4">

                    <div>

                        <div class="text-muted small">
                            Latest Delivery Strategy
                        </div>

                        <div class="fw-semibold fs-5">

                            {{ $latestDeliveryStrategy->strategy_number }}

                            <span class="text-muted">
                                · V{{ $latestDeliveryStrategy->version_number }}
                            </span>

                        </div>


                        <div class="text-muted small mt-1">

                            {{ $latestDeliveryStrategy->title }}

                        </div>

                    </div>


                    <span class="badge {{ $statusClass }} fs-6">

                        {{ $latestDeliveryStrategy->status }}

                    </span>

                </div>


                {{-- Summary --}}

                <div class="row g-3">


                    {{-- Delivery Model --}}

                    <div class="col-md-4">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small">
                                Delivery Model
                            </div>

                            <div class="fw-semibold mt-1">

                                {{ $latestDeliveryStrategy->delivery_model }}

                            </div>

                        </div>

                    </div>


                    {{-- Effective Date --}}

                    <div class="col-md-4">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small">
                                Effective Date
                            </div>

                            <div class="fw-semibold mt-1">

                                {{
                                    $latestDeliveryStrategy->effective_date
                                        ? $latestDeliveryStrategy
                                            ->effective_date
                                            ->format('d M Y')
                                        : 'Not defined'
                                }}

                            </div>

                        </div>

                    </div>


                    {{-- Approval --}}

                    <div class="col-md-4">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small">
                                Approval Date
                            </div>

                            <div class="fw-semibold mt-1">

                                {{
                                    $latestDeliveryStrategy->approved_date
                                        ? $latestDeliveryStrategy
                                            ->approved_date
                                            ->format('d M Y')
                                        : 'Not approved'
                                }}

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Delivery Approach --}}

                @if($latestDeliveryStrategy->delivery_approach)

                    <div class="mt-4">

                        <div class="text-muted small mb-1">
                            Delivery Approach
                        </div>

                        <div class="border rounded p-3">

                            {!! nl2br(
                                e(
                                    $latestDeliveryStrategy
                                        ->delivery_approach
                                )
                            ) !!}

                        </div>

                    </div>

                @endif


                {{-- Actions --}}

                <div class="mt-4">

                    <a
                        href="{{ route(
                            'admin.projects.delivery-strategy.show',
                            [
                                'project' =>
                                    $project->id,

                                'deliveryStrategy' =>
                                    $latestDeliveryStrategy->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        View Latest Strategy
                    </a>


                    <a
                        href="{{ route(
                            'admin.projects.delivery-strategy.index',
                            [
                                'project' =>
                                    $project->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-outline-secondary"
                    >
                        View All Revisions
                    </a>

                </div>


            @else

                <div class="text-center py-4">

                    <div class="text-muted mb-3">

                        No Delivery Strategy has been created
                        for this project yet.

                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.delivery-strategy.create',
                            [
                                'project' =>
                                    $project->id,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Create Delivery Strategy
                    </a>

                </div>

            @endif

        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- Procurement Strategy Summary --}}
    {{-- ========================================================= --}}

    @php

        $latestProcurementStrategy = null;

        if (
            method_exists($project, 'procurementStrategies') &&
            $project->relationLoaded('procurementStrategies')
        ) {
            $latestProcurementStrategy =
                $project->procurementStrategies
                    ->sortByDesc('version_number')
                    ->first();
        }

    @endphp


    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Procurement Strategy Summary
                    </strong>

                    <div class="text-muted small mt-1">
                        Strategic approach for project procurement
                    </div>

                </div>


                <a
                    href="{{ route(
                        'admin.projects.procurement-strategy.index',
                        ['project' => $project->id]
                    ) }}"
                    class="btn btn-sm btn-outline-primary"
                >
                    Open Procurement Strategies
                </a>

            </div>

        </div>


        <div class="card-body">

            @if($latestProcurementStrategy)

                @php

                    $statusClass =
                        match(
                            $latestProcurementStrategy->status
                        ) {

                            'Approved'
                                => 'bg-success',

                            'Submitted',
                            'Under Review'
                                => 'bg-warning text-dark',

                            'Rejected'
                                => 'bg-danger',

                            default
                                => 'bg-secondary',

                        };

                @endphp


                {{-- Header --}}

                <div class="d-flex justify-content-between align-items-start mb-4">

                    <div>

                        <div class="text-muted small">
                            Latest Procurement Strategy
                        </div>

                        <div class="fw-semibold fs-5">

                            {{ $latestProcurementStrategy->strategy_number }}

                            <span class="text-muted">
                                · V{{ $latestProcurementStrategy->version_number }}
                            </span>

                        </div>


                        <div class="text-muted small mt-1">

                            {{ $latestProcurementStrategy->title }}

                        </div>

                    </div>


                    <span class="badge {{ $statusClass }} fs-6">

                        {{ $latestProcurementStrategy->status }}

                    </span>

                </div>


                {{-- Summary Cards --}}

                <div class="row g-3">


                    {{-- Procurement Model --}}

                    <div class="col-md-4">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small">
                                Procurement Model
                            </div>

                            <div class="fw-semibold mt-1">

                                {{ $latestProcurementStrategy->procurement_model }}

                            </div>

                        </div>

                    </div>


                    {{-- Effective Date --}}

                    <div class="col-md-4">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small">
                                Effective Date
                            </div>

                            <div class="fw-semibold mt-1">

                                {{
                                    $latestProcurementStrategy->effective_date
                                        ? $latestProcurementStrategy
                                            ->effective_date
                                            ->format('d M Y')
                                        : 'Not defined'
                                }}

                            </div>

                        </div>

                    </div>


                    {{-- Approval Date --}}

                    <div class="col-md-4">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small">
                                Approval Date
                            </div>

                            <div class="fw-semibold mt-1">

                                {{
                                    $latestProcurementStrategy->approved_date
                                        ? $latestProcurementStrategy
                                            ->approved_date
                                            ->format('d M Y')
                                        : 'Not approved'
                                }}

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Procurement Approach --}}

                @if(
                    $latestProcurementStrategy->procurement_approach
                )

                    <div class="mt-4">

                        <div class="text-muted small mb-1">
                            Procurement Approach
                        </div>

                        <div class="border rounded p-3">

                            {!! nl2br(
                                e(
                                    $latestProcurementStrategy
                                        ->procurement_approach
                                )
                            ) !!}

                        </div>

                    </div>

                @endif


                {{-- Procurement Packages --}}

                @if(
                    $latestProcurementStrategy->procurement_packages
                )

                    <div class="mt-4">

                        <div class="text-muted small mb-1">
                            Strategic Procurement Packages
                        </div>

                        <div class="border rounded p-3">

                            {!! nl2br(
                                e(
                                    $latestProcurementStrategy
                                        ->procurement_packages
                                )
                            ) !!}

                        </div>

                    </div>

                @endif


                {{-- Actions --}}

                <div class="mt-4">

                    <a
                        href="{{ route(
                            'admin.projects.procurement-strategy.show',
                            [
                                'project' =>
                                    $project->id,

                                'procurementStrategy' =>
                                    $latestProcurementStrategy->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        View Latest Strategy
                    </a>


                    <a
                        href="{{ route(
                            'admin.projects.procurement-strategy.index',
                            [
                                'project' =>
                                    $project->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-outline-secondary"
                    >
                        View All Revisions
                    </a>

                </div>


            @else

                <div class="text-center py-4">

                    <div class="text-muted mb-3">

                        No Procurement Strategy has been
                        created for this project yet.

                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.procurement-strategy.create',
                            [
                                'project' =>
                                    $project->id,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Create Procurement Strategy
                    </a>

                </div>

            @endif

        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- Contract Strategy Summary --}}
    {{-- ========================================================= --}}

    @php

        $latestContractStrategy = null;

        if (
            $project->relationLoaded('contractStrategies')
        ) {
            $latestContractStrategy =
                $project->contractStrategies
                    ->sortByDesc('version_number')
                    ->first();
        }

    @endphp


    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Contract Strategy Summary
                    </strong>

                    <div class="text-muted small mt-1">
                        Strategic approach for project contracting
                    </div>

                </div>


                <a
                    href="{{ route(
                        'admin.projects.contract-strategy.index',
                        ['project' => $project->id]
                    ) }}"
                    class="btn btn-sm btn-outline-primary"
                >
                    Open Contract Strategies
                </a>

            </div>

        </div>


        <div class="card-body">

            @if($latestContractStrategy)

                @php

                    $statusClass =
                        match($latestContractStrategy->status) {

                            'Approved'
                                => 'bg-success',

                            'Submitted',
                            'Under Review'
                                => 'bg-warning text-dark',

                            'Rejected'
                                => 'bg-danger',

                            default
                                => 'bg-secondary',

                        };

                @endphp


                {{-- Header --}}

                <div class="d-flex justify-content-between align-items-start mb-4">

                    <div>

                        <div class="text-muted small">
                            Latest Contract Strategy
                        </div>

                        <div class="fw-semibold fs-5">

                            {{ $latestContractStrategy->strategy_number }}

                            <span class="text-muted">
                                · V{{ $latestContractStrategy->version_number }}
                            </span>

                        </div>


                        <div class="text-muted small mt-1">

                            {{ $latestContractStrategy->title }}

                        </div>

                    </div>


                    <span class="badge {{ $statusClass }} fs-6">

                        {{ $latestContractStrategy->status }}

                    </span>

                </div>


                {{-- Summary Cards --}}

                <div class="row g-3">


                    {{-- Contracting Model --}}

                    <div class="col-md-4">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small">
                                Contracting Model
                            </div>

                            <div class="fw-semibold mt-1">

                                {{ $latestContractStrategy->contracting_model }}

                            </div>

                        </div>

                    </div>


                    {{-- Contract Type --}}

                    <div class="col-md-4">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small">
                                Contract Type
                            </div>

                            <div class="fw-semibold mt-1">

                                {{ $latestContractStrategy->contract_type }}

                            </div>

                        </div>

                    </div>


                    {{-- Effective Date --}}

                    <div class="col-md-4">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small">
                                Effective Date
                            </div>

                            <div class="fw-semibold mt-1">

                                {{
                                    $latestContractStrategy->effective_date
                                        ? $latestContractStrategy
                                            ->effective_date
                                            ->format('d M Y')
                                        : 'Not defined'
                                }}

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Commercial Model --}}

                @if(
                    $latestContractStrategy->commercial_model
                )

                    <div class="mt-4">

                        <div class="text-muted small mb-1">
                            Commercial Model
                        </div>

                        <div class="border rounded p-3">

                            {!! nl2br(
                                e(
                                    $latestContractStrategy
                                        ->commercial_model
                                )
                            ) !!}

                        </div>

                    </div>

                @endif


                {{-- Contract Packaging --}}

                @if(
                    $latestContractStrategy->contract_packaging
                )

                    <div class="mt-4">

                        <div class="text-muted small mb-1">
                            Contract Packaging
                        </div>

                        <div class="border rounded p-3">

                            {!! nl2br(
                                e(
                                    $latestContractStrategy
                                        ->contract_packaging
                                )
                            ) !!}

                        </div>

                    </div>

                @endif


                {{-- Risk Allocation --}}

                @if(
                    $latestContractStrategy
                        ->risk_allocation_strategy
                )

                    <div class="mt-4">

                        <div class="text-muted small mb-1">
                            Risk Allocation Strategy
                        </div>

                        <div class="border rounded p-3">

                            {!! nl2br(
                                e(
                                    $latestContractStrategy
                                        ->risk_allocation_strategy
                                )
                            ) !!}

                        </div>

                    </div>

                @endif


                {{-- Actions --}}

                <div class="mt-4">

                    <a
                        href="{{ route(
                            'admin.projects.contract-strategy.show',
                            [
                                'project' =>
                                    $project->id,

                                'contractStrategy' =>
                                    $latestContractStrategy->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        View Latest Strategy
                    </a>


                    <a
                        href="{{ route(
                            'admin.projects.contract-strategy.index',
                            [
                                'project' =>
                                    $project->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-outline-secondary"
                    >
                        View All Revisions
                    </a>

                </div>


            @else

                <div class="text-center py-4">

                    <div class="text-muted mb-3">

                        No Contract Strategy has been
                        created for this project yet.

                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.contract-strategy.create',
                            [
                                'project' =>
                                    $project->id,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Create Contract Strategy
                    </a>

                </div>

            @endif

        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- Risk Register --}}
    {{-- ========================================================= --}}

    @php

        $projectRisks = $project->relationLoaded('risks')
            ? $project->risks
            : collect();

        $criticalRisks = $projectRisks
            ->where('risk_level', 'Critical')
            ->count();

        $highRisks = $projectRisks
            ->where('risk_level', 'High')
            ->count();

        $openRisks = $projectRisks
            ->whereIn('status', [
                'Open',
                'Monitoring'
            ])
            ->count();

    @endphp


    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Risk Register
                    </strong>

                    <div class="text-muted small mt-1">
                        Project risks, assessment and mitigation
                    </div>

                </div>


                <div class="d-flex gap-2">

                    <a
                        href="{{ route(
                            'admin.projects.risks.index',
                            [
                                'project' => $project->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-outline-primary"
                    >
                        Open Risk Register
                    </a>


                    <a
                        href="{{ route(
                            'admin.projects.risks.create',
                            [
                                'project' => $project->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        + Add Risk
                    </a>

                </div>

            </div>

        </div>


        <div class="card-body">

            @if($projectRisks->count())

                {{-- Summary --}}

                <div class="row g-3 mb-4">

                    <div class="col-md-3">

                        <div class="border rounded p-3">

                            <div class="text-muted small">
                                Total Risks
                            </div>

                            <div class="fs-4 fw-semibold">
                                {{ $projectRisks->count() }}
                            </div>

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="border rounded p-3">

                            <div class="text-muted small">
                                Critical
                            </div>

                            <div class="fs-4 fw-semibold text-danger">
                                {{ $criticalRisks }}
                            </div>

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="border rounded p-3">

                            <div class="text-muted small">
                                High
                            </div>

                            <div class="fs-4 fw-semibold text-warning">
                                {{ $highRisks }}
                            </div>

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="border rounded p-3">

                            <div class="text-muted small">
                                Open / Monitoring
                            </div>

                            <div class="fs-4 fw-semibold">
                                {{ $openRisks }}
                            </div>

                        </div>

                    </div>

                </div>


                {{-- Top Risks --}}

                @php

                    $topRisks = $projectRisks
                        ->sortByDesc('risk_score')
                        ->take(5);

                @endphp


                <div class="table-responsive">

                    <table class="table table-sm table-hover align-middle mb-0">

                        <thead>

                            <tr>

                                <th>
                                    Risk No.
                                </th>

                                <th>
                                    Risk
                                </th>

                                <th>
                                    Category
                                </th>

                                <th>
                                    Score
                                </th>

                                <th>
                                    Level
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

                            @foreach($topRisks as $risk)

                                @php

                                    $levelClass =
                                        match($risk->risk_level) {

                                            'Critical'
                                                => 'bg-danger',

                                            'High'
                                                => 'bg-warning text-dark',

                                            'Medium'
                                                => 'bg-info text-dark',

                                            default
                                                => 'bg-success',

                                        };


                                    $statusClass =
                                        match($risk->status) {

                                            'Closed'
                                                => 'bg-success',

                                            'Mitigated'
                                                => 'bg-info text-dark',

                                            'Occurred'
                                                => 'bg-danger',

                                            'Monitoring'
                                                => 'bg-warning text-dark',

                                            default
                                                => 'bg-secondary',

                                        };

                                @endphp


                                <tr>

                                    <td>

                                        <strong>
                                            {{ $risk->risk_number }}
                                        </strong>

                                    </td>


                                    <td>

                                        <div class="fw-semibold">
                                            {{ $risk->risk_title }}
                                        </div>

                                        <div class="text-muted small">

                                            {{
                                                \Illuminate\Support\Str::limit(
                                                    $risk->risk_description,
                                                    60
                                                )
                                            }}

                                        </div>

                                    </td>


                                    <td>
                                        {{ $risk->risk_category }}
                                    </td>


                                    <td>

                                        <strong>
                                            {{ $risk->risk_score }}
                                        </strong>

                                    </td>


                                    <td>

                                        <span
                                            class="badge {{ $levelClass }}"
                                        >
                                            {{ $risk->risk_level }}
                                        </span>

                                    </td>


                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $risk->status }}
                                        </span>

                                    </td>


                                    <td class="text-end">

                                        <a
                                            href="{{ route(
                                                'admin.projects.risks.show',
                                                [
                                                    'project' =>
                                                        $project->id,
                                                    'risk' =>
                                                        $risk->id,
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


                @if($projectRisks->count() > 5)

                    <div class="text-end mt-3">

                        <a
                            href="{{ route(
                                'admin.projects.risks.index',
                                [
                                    'project' => $project->id,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-secondary"
                        >
                            View All
                            {{ $projectRisks->count() }}
                            Risks
                        </a>

                    </div>

                @endif


            @else

                <div class="text-center py-4">

                    <div class="text-muted mb-3">

                        No risks have been registered
                        for this project yet.

                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.risks.create',
                            [
                                'project' => $project->id,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Create First Risk
                    </a>

                </div>

            @endif

        </div>

    </div>
    {{-- ========================================================= --}}
    {{-- Stakeholder Register --}}
    {{-- ========================================================= --}}

    @php

        $projectStakeholders = $project->relationLoaded('stakeholders')
            ? $project->stakeholders
            : collect();

        $criticalStakeholders = $projectStakeholders
            ->where('priority', 'Critical')
            ->count();

        $highStakeholders = $projectStakeholders
            ->where('priority', 'High')
            ->count();

        $activeStakeholders = $projectStakeholders
            ->where('status', 'Active')
            ->count();

    @endphp


    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Stakeholder Register
                    </strong>

                    <div class="text-muted small mt-1">
                        Stakeholder identification, influence and engagement
                    </div>

                </div>


                <div class="d-flex gap-2">

                    <a
                        href="{{ route(
                            'admin.projects.stakeholders.index',
                            [
                                'project' => $project->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-outline-primary"
                    >
                        Open Register
                    </a>


                    <a
                        href="{{ route(
                            'admin.projects.stakeholders.create',
                            [
                                'project' => $project->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        + Add Stakeholder
                    </a>

                </div>

            </div>

        </div>


        <div class="card-body">

            {{-- Summary --}}

            <div class="row g-3 mb-4">

                <div class="col-md-3">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Total Stakeholders
                        </div>

                        <div class="fs-4 fw-semibold">
                            {{ $projectStakeholders->count() }}
                        </div>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Active
                        </div>

                        <div class="fs-4 fw-semibold">
                            {{ $activeStakeholders }}
                        </div>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            High Priority
                        </div>

                        <div class="fs-4 fw-semibold text-warning">
                            {{ $highStakeholders }}
                        </div>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Critical
                        </div>

                        <div class="fs-4 fw-semibold text-danger">
                            {{ $criticalStakeholders }}
                        </div>

                    </div>

                </div>

            </div>


            @if($projectStakeholders->count())

                {{-- Top stakeholders --}}

                @php

                    $topStakeholders = $projectStakeholders
                        ->sortByDesc(function ($stakeholder) {

                            return match ($stakeholder->priority) {
                                'Critical' => 4,
                                'High' => 3,
                                'Medium' => 2,
                                'Low' => 1,
                                default => 0,
                            };

                        })
                        ->take(5);

                @endphp


                <div class="table-responsive">

                    <table class="table table-sm table-hover align-middle mb-0">

                        <thead>

                            <tr>

                                <th>
                                    Stakeholder
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Influence
                                </th>

                                <th>
                                    Interest
                                </th>

                                <th>
                                    Engagement
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

                            @foreach($topStakeholders as $stakeholder)

                                @php

                                    $priorityClass =
                                        match($stakeholder->priority) {

                                            'Critical'
                                                => 'bg-danger',

                                            'High'
                                                => 'bg-warning text-dark',

                                            'Medium'
                                                => 'bg-info text-dark',

                                            default
                                                => 'bg-success',

                                        };


                                    $statusClass =
                                        $stakeholder->status === 'Active'
                                            ? 'bg-success'
                                            : 'bg-secondary';

                                @endphp


                                <tr>

                                    <td>

                                        <div class="fw-semibold">
                                            {{ $stakeholder->stakeholder_name }}
                                        </div>

                                        <div class="text-muted small">
                                            {{ $stakeholder->stakeholder_number }}
                                        </div>

                                    </td>


                                    <td>
                                        {{ $stakeholder->stakeholder_type }}
                                    </td>


                                    <td>
                                        {{ $stakeholder->influence_level }}
                                    </td>


                                    <td>
                                        {{ $stakeholder->interest_level }}
                                    </td>


                                    <td>
                                        {{ $stakeholder->engagement_level }}
                                    </td>


                                    <td>

                                        <span
                                            class="badge {{ $priorityClass }}"
                                        >
                                            {{ $stakeholder->priority }}
                                        </span>

                                    </td>


                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $stakeholder->status }}
                                        </span>

                                    </td>


                                    <td class="text-end">

                                        <a
                                            href="{{ route(
                                                'admin.projects.stakeholders.show',
                                                [
                                                    'project' => $project->id,
                                                    'stakeholder' => $stakeholder->id,
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


                @if($projectStakeholders->count() > 5)

                    <div class="text-end mt-3">

                        <a
                            href="{{ route(
                                'admin.projects.stakeholders.index',
                                [
                                    'project' => $project->id,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-secondary"
                        >
                            View All
                            {{ $projectStakeholders->count() }}
                            Stakeholders
                        </a>

                    </div>

                @endif


            @else

                <div class="text-center py-4">

                    <div class="text-muted mb-3">

                        No stakeholders have been registered
                        for this project yet.

                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.stakeholders.create',
                            [
                                'project' => $project->id,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Create First Stakeholder
                    </a>

                </div>

            @endif

        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- Project Governance --}}
    {{-- ========================================================= --}}

    @php

        $projectGovernances = $project->relationLoaded('governance')
            ? $project->governance
            : collect();

        $activeGovernance = $projectGovernances
            ->where('status', 'Active')
            ->sortByDesc('id')
            ->first();

        $draftGovernance = $projectGovernances
            ->where('status', 'Draft')
            ->count();

        $reviewGovernance = $projectGovernances
            ->where('status', 'Under Review')
            ->count();

    @endphp


    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Project Governance
                    </strong>

                    <div class="text-muted small mt-1">
                        Governance structure, authority, decisions,
                        approvals and reporting framework.
                    </div>

                </div>


                <div class="d-flex gap-2">

                    <a
                        href="{{ route(
                            'admin.projects.governance.index',
                            [
                                'project' => $project->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-outline-primary"
                    >
                        Open Governance
                    </a>


                    <a
                        href="{{ route(
                            'admin.projects.governance.create',
                            [
                                'project' => $project->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        + Add Governance
                    </a>

                </div>

            </div>

        </div>


        <div class="card-body">

            {{-- ===================================================== --}}
            {{-- SUMMARY --}}
            {{-- ===================================================== --}}

            <div class="row g-3 mb-4">

                {{-- Total --}}

                <div class="col-md-3">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small">
                            Governance Frameworks
                        </div>

                        <div class="fs-4 fw-semibold">
                            {{ $projectGovernances->count() }}
                        </div>

                    </div>

                </div>


                {{-- Active --}}

                <div class="col-md-3">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small">
                            Active Framework
                        </div>

                        <div class="fs-4 fw-semibold text-success">

                            {{ $activeGovernance ? 'Yes' : 'No' }}

                        </div>

                    </div>

                </div>


                {{-- Draft --}}

                <div class="col-md-3">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small">
                            Draft
                        </div>

                        <div class="fs-4 fw-semibold text-warning">
                            {{ $draftGovernance }}
                        </div>

                    </div>

                </div>


                {{-- Under Review --}}

                <div class="col-md-3">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small">
                            Under Review
                        </div>

                        <div class="fs-4 fw-semibold text-info">
                            {{ $reviewGovernance }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- ACTIVE GOVERNANCE --}}
            {{-- ===================================================== --}}

            @if($activeGovernance)

                <div class="border rounded">

                    <div class="p-3 border-bottom">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>

                                <div class="text-muted small">
                                    Active Governance Framework
                                </div>

                                <h5 class="mb-1">

                                    {{ $activeGovernance->title }}

                                </h5>

                                <div class="text-muted small">

                                    {{ $activeGovernance->governance_number }}

                                    ·

                                    {{ $activeGovernance->governance_model }}

                                </div>

                            </div>


                            <span class="badge bg-success">
                                Active
                            </span>

                        </div>

                    </div>


                    <div class="p-3">

                        <div class="row">

                            {{-- Sponsor --}}

                            <div class="col-md-4 mb-3">

                                <div class="text-muted small">
                                    Project Sponsor
                                </div>

                                <div class="fw-semibold">

                                    @if($activeGovernance->projectSponsor)

                                        {{ $activeGovernance->projectSponsor->name }}

                                    @else

                                        <span class="text-muted">
                                            Not Assigned
                                        </span>

                                    @endif

                                </div>

                            </div>


                            {{-- Director --}}

                            <div class="col-md-4 mb-3">

                                <div class="text-muted small">
                                    Project Director
                                </div>

                                <div class="fw-semibold">

                                    @if($activeGovernance->projectDirector)

                                        {{ $activeGovernance->projectDirector->name }}

                                    @else

                                        <span class="text-muted">
                                            Not Assigned
                                        </span>

                                    @endif

                                </div>

                            </div>


                            {{-- Manager --}}

                            <div class="col-md-4 mb-3">

                                <div class="text-muted small">
                                    Project Manager
                                </div>

                                <div class="fw-semibold">

                                    @if($activeGovernance->projectManager)

                                        {{ $activeGovernance->projectManager->name }}

                                    @else

                                        <span class="text-muted">
                                            Not Assigned
                                        </span>

                                    @endif

                                </div>

                            </div>


                            {{-- Effective Date --}}

                            <div class="col-md-4 mb-3">

                                <div class="text-muted small">
                                    Effective Date
                                </div>

                                <div class="fw-semibold">

                                    @if($activeGovernance->effective_date)

                                        {{ $activeGovernance->effective_date->format('d-m-Y') }}

                                    @else

                                        —

                                    @endif

                                </div>

                            </div>


                            {{-- Review Date --}}

                            <div class="col-md-4 mb-3">

                                <div class="text-muted small">
                                    Review Date
                                </div>

                                <div class="fw-semibold">

                                    @if($activeGovernance->review_date)

                                        {{ $activeGovernance->review_date->format('d-m-Y') }}

                                    @else

                                        —

                                    @endif

                                </div>

                            </div>


                            {{-- Decision Framework --}}

                            <div class="col-md-4 mb-3">

                                <div class="text-muted small">
                                    Decision Framework
                                </div>

                                <div class="fw-semibold">

                                    @if($activeGovernance->decision_making_framework)

                                        Defined

                                    @else

                                        <span class="text-muted">
                                            Not Defined
                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>


                        <div class="d-flex justify-content-end">

                            <a
                                href="{{ route(
                                    'admin.projects.governance.show',
                                    [
                                        'project' =>
                                            $project->id,

                                        'governance' =>
                                            $activeGovernance->id,
                                    ]
                                ) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                View Governance Framework
                            </a>

                        </div>

                    </div>

                </div>


            @else

                {{-- ================================================= --}}
                {{-- NO ACTIVE GOVERNANCE --}}
                {{-- ================================================= --}}

                <div class="text-center py-4">

                    <div class="text-muted mb-3">

                        No active governance framework has been
                        configured for this project.

                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.governance.create',
                            [
                                'project' => $project->id,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Create Governance Framework
                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Governance Meetings --}}
    {{-- ========================================================= --}}

    @php

        $governanceMeetings = $project->relationLoaded('governanceMeetings')
            ? $project->governanceMeetings
            : collect();

        $totalMeetings = $governanceMeetings->count();

        $today = now()->toDateString();

        $upcomingMeetings = $governanceMeetings
            ->filter(function ($meeting) use ($today) {
                return $meeting->meeting_date
                    && \Carbon\Carbon::parse($meeting->meeting_date)
                        ->toDateString() >= $today;
            })
            ->count();

        $completedMeetings = $governanceMeetings
            ->filter(function ($meeting) {
                return isset($meeting->meeting_status)
                    && $meeting->meeting_status === 'Completed';
            })
            ->count();

        $latestMeeting = $governanceMeetings
            ->filter(fn ($meeting) => $meeting->meeting_date)
            ->sortByDesc('meeting_date')
            ->first();

    @endphp


    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Governance Meetings
                    </strong>

                    <div class="text-muted small mt-1">
                        Steering committee meetings, agendas,
                        decisions and action items.
                    </div>

                </div>


                <div class="d-flex gap-2">

                    <a
                        href="{{ route(
                            'admin.projects.governance-meetings.index',
                            [
                                'project' => $project->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-outline-primary"
                    >
                        View Meetings
                    </a>


                    <a
                        href="{{ route(
                            'admin.projects.governance-meetings.create',
                            [
                                'project' => $project->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        + New Meeting
                    </a>

                    <a
                        href="{{ route(
                            'admin.projects.governance.follow-up.index',
                            [
                                'project' => $project->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        Governance Follow-up
                    </a>

                </div>

            </div>

        </div>


        <div class="card-body">

            {{-- ===================================================== --}}
            {{-- SUMMARY --}}
            {{-- ===================================================== --}}

            <div class="row g-3 mb-4">

                {{-- Total Meetings --}}

                <div class="col-md-3">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small">
                            Total Meetings
                        </div>

                        <div class="fs-4 fw-semibold mt-1">
                            {{ $totalMeetings }}
                        </div>

                    </div>

                </div>


                {{-- Upcoming Meetings --}}

                <div class="col-md-3">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small">
                            Upcoming
                        </div>

                        <div class="fs-4 fw-semibold text-primary mt-1">
                            {{ $upcomingMeetings }}
                        </div>

                    </div>

                </div>


                {{-- Completed Meetings --}}

                <div class="col-md-3">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small">
                            Completed
                        </div>

                        <div class="fs-4 fw-semibold text-success mt-1">
                            {{ $completedMeetings }}
                        </div>

                    </div>

                </div>


                {{-- Latest Meeting --}}

                <div class="col-md-3">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small">
                            Latest Meeting
                        </div>

                        @if($latestMeeting)

                            <div class="fw-semibold mt-1">

                                {{ $latestMeeting->meeting_number ?? 'Meeting #' . $latestMeeting->id }}

                            </div>

                            <div class="text-muted small">

                                {{
                                    \Carbon\Carbon::parse(
                                        $latestMeeting->meeting_date
                                    )->format('d M Y')
                                }}

                            </div>

                        @else

                            <div class="text-muted mt-1">
                                No meetings yet
                            </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- LATEST MEETING --}}
            {{-- ===================================================== --}}

            @if($latestMeeting)

                <div class="border rounded p-3">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <div class="text-muted small">
                                Latest Governance Meeting
                            </div>

                            <h5 class="mb-1">

                                {{ $latestMeeting->meeting_number ?? 'Meeting #' . $latestMeeting->id }}

                            </h5>

                            <div class="text-muted small">

                                @if($latestMeeting->meeting_date)
                                    {{
                                        \Carbon\Carbon::parse(
                                            $latestMeeting->meeting_date
                                        )->format('d M Y')
                                    }}
                                @else
                                    —
                                @endif

                                @if(!empty($latestMeeting->meeting_title))
                                    · {{ $latestMeeting->meeting_title }}
                                @endif

                            </div>

                        </div>


                        @if(!empty($latestMeeting->meeting_status))

                            @php
                                $meetingStatusClass = match(
                                    $latestMeeting->meeting_status
                                ) {
                                    'Completed' => 'bg-success',
                                    'Scheduled', 'Confirmed' => 'bg-primary',
                                    'Cancelled' => 'bg-danger',
                                    'Postponed' => 'bg-warning text-dark',
                                    default => 'bg-secondary',
                                };
                            @endphp

                            <span class="badge {{ $meetingStatusClass }}">
                                {{ $latestMeeting->meeting_status }}
                            </span>

                        @endif

                    </div>


                    <div class="mt-3 d-flex flex-wrap gap-2">

                        <a
                            href="{{ route(
                                'admin.projects.governance-meetings.show',
                                [
                                    'project' => $project->id,
                                    'meeting' => $latestMeeting->id,
                                ]
                            ) }}"
                            class="btn btn-sm btn-primary"
                        >
                            View Latest Meeting
                        </a>


                        <a
                            href="{{ route(
                                'admin.projects.governance-meetings.index',
                                [
                                    'project' => $project->id,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-secondary"
                        >
                            View All Meetings
                        </a>

                    </div>

                </div>

            @else

                <div class="text-center py-4">

                    <div class="text-muted mb-3">
                        No governance meetings have been created
                        for this project yet.
                    </div>

                    <a
                        href="{{ route(
                            'admin.projects.governance-meetings.create',
                            [
                                'project' => $project->id,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Create First Meeting
                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Area --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Development Area
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Development Area
                    </div>

                    <div class="fs-5 fw-semibold">
                        {{ $project->development_area ?? '-' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Planned GLA
                    </div>

                    <div class="fs-5 fw-semibold">
                        {{ $project->planned_gla ?? '-' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Planned NLA
                    </div>

                    <div class="fs-5 fw-semibold">
                        {{ $project->planned_nla ?? '-' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Planned Leasable Area
                    </div>

                    <div class="fs-5 fw-semibold">
                        {{ $project->planned_leasable_area ?? '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Responsibility --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Project Responsibility
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Project Sponsor
                    </div>

                    <div class="fw-semibold">
                        {{ $project->project_sponsor_id ?? '-' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Project Director
                    </div>

                    <div class="fw-semibold">
                        {{ $project->project_director_id ?? '-' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Project Manager
                    </div>

                    <div class="fw-semibold">
                        {{ $project->project_manager_id ?? '-' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Development Manager
                    </div>

                    <div class="fw-semibold">
                        {{ $project->development_manager_id ?? '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Remarks --}}
    {{-- ========================================================= --}}

    @if($project->remarks)

        <div class="card mb-5">

            <div class="card-header">

                <strong>
                    Remarks
                </strong>

            </div>

            <div class="card-body">

                {!! nl2br(
                    e($project->remarks)
                ) !!}

            </div>

        </div>

    @endif

</div>

@endsection