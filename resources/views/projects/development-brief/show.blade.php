@extends('layouts.app')

@section('title', 'Development Brief Details')

@section('content')

@php
    $projectName = $project->name
        ?? $project->project_name
        ?? ('Project #' . $project->id);

    $statusClass = match ($developmentBrief->approval_status) {
        'Approved' => 'success',
        'Rejected' => 'danger',
        'Under Review' => 'warning',
        'Superseded' => 'secondary',
        default => 'info',
    };
@endphp

<div class="container-fluid">
    <style>
    .development-brief-header h1 {
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .brief-header-actions {
        width: 100%;
    }

    .brief-header-actions .btn {
        white-space: normal;
        min-height: 42px;
    }

    @media (max-width: 991.98px) {
        .brief-header-actions {
            justify-content: flex-start !important;
        }
    }

    @media (max-width: 575.98px) {
        .brief-header-actions .btn {
            flex: 1 1 100%;
        }
    }
</style>

    {{-- Header --}}
    <div class="row align-items-start g-3 mb-4 development-brief-header">

        {{-- Title Section --}}
        <div class="col-12 col-lg-7">
            <h1 class="h3 mb-1">
                {{ $developmentBrief->brief_title }}
            </h1>

            <p class="text-muted mb-1">
                Project:
                <strong>{{ $projectName }}</strong>
            </p>

            @if($developmentBrief->brief_reference)
                <span class="text-muted">
                    Reference:
                    {{ $developmentBrief->brief_reference }}
                </span>
            @endif
        </div>

        {{-- Action Buttons --}}
        <div class="col-12 col-lg-5">
            <div class="d-flex flex-wrap justify-content-lg-end gap-2 brief-header-actions">

                <a
                    href="{{ route('admin.projects.development-brief.edit', [$project, $developmentBrief]) }}"
                    class="btn btn-warning"
                >
                    <i class="fas fa-edit"></i>
                    Edit
                </a>

                <a
                    href="{{ route(
                        'admin.projects.development-brief.documents',
                        [
                            'project' => $project->id,
                            'developmentBrief' => $developmentBrief->id,
                        ]
                    ) }}"
                    class="btn btn-outline-primary"
                >
                    <i class="fa fa-folder-open"></i>
                    Supporting Documents
                </a>

                <a
                    href="{{ route(
                        'admin.projects.development-brief.comments',
                        [
                            'project' => $project->id,
                            'developmentBrief' => $developmentBrief->id,
                        ]
                    ) }}"
                    class="btn btn-outline-primary"
                >
                    <i class="fa fa-comments"></i>
                    Comments
                </a>
                <a
                    href="{{ route(
                        'admin.projects.development-brief.versions',
                        [
                            'project' => $project->id,
                            'developmentBrief' => $developmentBrief->id,
                        ]
                    ) }}"
                    class="btn btn-outline-dark"
                >
                    <i class="fas fa-history"></i>
                    Versions
                </a>
                <a
                    href="{{ route(
                        'admin.projects.development-brief.workflow-history',
                        [
                            'project' => $project->id,
                            'developmentBrief' => $developmentBrief->id,
                        ]
                    ) }}"
                    class="btn btn-outline-dark"
                >
                    <i class="fas fa-history"></i>
                    Workflow History
                </a>

                <a
                    href="{{ route('admin.projects.development-brief.index', $project) }}"
                    class="btn btn-secondary"
                >
                    <i class="fas fa-arrow-left"></i>
                    Back
                </a>



            </div>
        </div>

    </div>



    {{-- Workflow Actions --}}
    @if(
        in_array($developmentBrief->approval_status ?? 'Draft', [
            'Draft',
            'Under Review',
            'Rejected',
            'Approved',
        ])
    )
        <div class="card border-primary shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-route me-2"></i>
                    Workflow Actions
                </h5>
            </div>

            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center gap-2">

                    {{-- Draft: Submit for Review --}}
                    @if(($developmentBrief->approval_status ?? 'Draft') === 'Draft')
                        <form
                            action="{{ route(
                                'admin.projects.development-brief.workflow.action',
                                [
                                    'project' => $project->id,
                                    'developmentBrief' => $developmentBrief->id,
                                    'action' => 'submit',
                                ]
                            ) }}"
                            method="POST"
                            class="d-inline"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="btn btn-primary"
                                onclick="return confirm('Submit this Development Brief for review?')"
                            >
                                <i class="fas fa-paper-plane me-1"></i>
                                Submit for Review
                            </button>
                        </form>
                    @endif

                    {{-- Under Review Actions --}}
                    @if(($developmentBrief->approval_status ?? 'Draft') === 'Under Review')
                        <button
                            type="button"
                            class="btn btn-success"
                            data-bs-toggle="modal"
                            data-bs-target="#approveBriefModal"
                        >
                            <i class="fas fa-check me-1"></i>
                            Approve
                        </button>

                        <button
                            type="button"
                            class="btn btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#rejectBriefModal"
                        >
                            <i class="fas fa-times me-1"></i>
                            Reject
                        </button>

                        <button
                            type="button"
                            class="btn btn-warning"
                            data-bs-toggle="modal"
                            data-bs-target="#returnBriefModal"
                        >
                            <i class="fas fa-undo me-1"></i>
                            Return for Revision
                        </button>
                    @endif

                    {{-- Rejected: Resubmit --}}
                    @if(($developmentBrief->approval_status ?? 'Draft') === 'Rejected')
                        <form
                            action="{{ route(
                                'admin.projects.development-brief.workflow.action',
                                [
                                    'project' => $project->id,
                                    'developmentBrief' => $developmentBrief->id,
                                    'action' => 'resubmit',
                                ]
                            ) }}"
                            method="POST"
                            class="d-inline"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="btn btn-primary"
                                onclick="return confirm('Resubmit this Development Brief for review?')"
                            >
                                <i class="fas fa-paper-plane me-1"></i>
                                Resubmit
                            </button>
                        </form>
                    @endif

                    {{-- Approved: Supersede --}}
                    @if(($developmentBrief->approval_status ?? 'Draft') === 'Approved')
                        <form
                            action="{{ route(
                                'admin.projects.development-brief.workflow.action',
                                [
                                    'project' => $project->id,
                                    'developmentBrief' => $developmentBrief->id,
                                    'action' => 'supersede',
                                ]
                            ) }}"
                            method="POST"
                            class="d-inline"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="btn btn-secondary"
                                onclick="return confirm('Mark this approved brief as superseded?')"
                            >
                                <i class="fas fa-exchange-alt me-1"></i>
                                Mark Superseded
                            </button>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    @endif

    {{-- Status --}}
    <div class="card mb-4">
        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3 mb-md-0">
                    <small class="text-muted d-block">Version</small>
                    <strong>{{ $developmentBrief->version ?: '—' }}</strong>
                </div>

                <div class="col-md-3 mb-3 mb-md-0">
                    <small class="text-muted d-block">Revision Number</small>
                    <strong>{{ $developmentBrief->revision_number ?? '—' }}</strong>
                </div>

                <div class="col-md-3 mb-3 mb-md-0">
                    <small class="text-muted d-block">Document Date</small>
                    <strong>
                        {{ $developmentBrief->document_date
                            ? \Carbon\Carbon::parse($developmentBrief->document_date)->format('d M Y')
                            : '—'
                        }}
                    </strong>
                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block">Approval Status</small>

                    <span class="badge bg-{{ $statusClass }}">
                        {{ $developmentBrief->approval_status ?: 'Draft' }}
                    </span>
                </div>

            </div>

        </div>
    </div>

    {{-- Related Information Summary --}}
    <div class="row g-3 mb-4">

        {{-- Comments --}}
        <div class="col-md-6 col-lg-3">
            <div class="card border-primary h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">
                                Total Comments
                            </small>

                            <h3 class="mb-0 text-primary">
                                {{ $developmentBrief->comments_count ?? 0 }}
                            </h3>
                        </div>

                        <i class="fas fa-comments fa-2x text-primary"></i>
                    </div>

                    <a
                        href="{{ route(
                            'admin.projects.development-brief.comments',
                            [
                                'project' => $project->id,
                                'developmentBrief' => $developmentBrief->id,
                            ]
                        ) }}"
                        class="small text-decoration-none"
                    >
                        View Comments
                        <i class="fas fa-arrow-right"></i>
                    </a>

                </div>
            </div>
        </div>

        {{-- Supporting Documents --}}
        <div class="col-md-6 col-lg-3">
            <div class="card border-success h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">
                                Supporting Documents
                            </small>

                            <h3 class="mb-0 text-success">
                                {{ $developmentBrief->documents_count ?? 0 }}
                            </h3>
                        </div>

                        <i class="fas fa-folder-open fa-2x text-success"></i>
                    </div>

                    <a
                        href="{{ route(
                            'admin.projects.development-brief.documents',
                            [
                                'project' => $project->id,
                                'developmentBrief' => $developmentBrief->id,
                            ]
                        ) }}"
                        class="small text-decoration-none"
                    >
                        View Documents
                        <i class="fas fa-arrow-right"></i>
                    </a>

                </div>
            </div>
        </div>
        {{-- Versions --}}
        <div class="col-md-6 col-lg-3">
            <div class="card border-dark h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <small class="text-muted d-block">
                                Version History
                            </small>

                            <h3 class="mb-0 text-dark">
                                {{ $developmentBrief->versions_count ?? 0 }}
                            </h3>
                        </div>

                        <i class="fas fa-history fa-2x text-dark"></i>

                    </div>

                    <a
                        href="{{ route(
                            'admin.projects.development-brief.versions',
                            [
                                'project' => $project->id,
                                'developmentBrief' => $developmentBrief->id,
                            ]
                        ) }}"
                        class="small text-decoration-none"
                    >
                        View Versions
                        <i class="fas fa-arrow-right"></i>
                    </a>

                </div>
            </div>
        </div>

        {{-- Workflow History --}}
        <div class="col-md-6 col-lg-3">
            <div class="card border-info h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <small class="text-muted d-block">
                                Workflow Actions
                            </small>

                            <h3 class="mb-0 text-info">
                                {{ $developmentBrief->workflow_history_count ?? 0 }}
                            </h3>
                        </div>

                        <i class="fas fa-route fa-2x text-info"></i>

                    </div>

                    <a
                        href="{{ route(
                            'admin.projects.development-brief.workflow-history',
                            [
                                'project' => $project->id,
                                'developmentBrief' => $developmentBrief->id,
                            ]
                        ) }}"
                        class="small text-decoration-none"
                    >
                        View Workflow History
                        <i class="fas fa-arrow-right"></i>
                    </a>

                </div>
            </div>
        </div>

    </div>


    {{-- Basic Information --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-file-alt"></i>
                Basic Information
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <strong class="d-block text-muted">Brief Title</strong>
                    {{ $developmentBrief->brief_title ?: '—' }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong class="d-block text-muted">Brief Reference</strong>
                    {{ $developmentBrief->brief_reference ?: '—' }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong class="d-block text-muted">Target Users</strong>
                    {!! nl2br(e($developmentBrief->target_users ?: '—')) !!}
                </div>

                <div class="col-md-6 mb-3">
                    <strong class="d-block text-muted">Target Use</strong>
                    {!! nl2br(e($developmentBrief->target_use ?: '—')) !!}
                </div>

                <div class="col-md-12 mb-3">
                    <strong class="d-block text-muted">Business Need</strong>
                    {!! nl2br(e($developmentBrief->business_need ?: '—')) !!}
                </div>

            </div>

        </div>
    </div>


    {{-- Area and Space Requirements --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-ruler-combined"></i>
                Area and Space Requirements
            </h5>
        </div>

        <div class="card-body">

            <div class="mb-4">
                <strong class="d-block text-muted mb-2">
                    Area Schedule
                </strong>

                <div class="brief-content">
                    {!! nl2br(e($developmentBrief->area_schedule ?: '—')) !!}
                </div>
            </div>

            <div>
                <strong class="d-block text-muted mb-2">
                    Room / Unit Mix
                </strong>

                <div class="brief-content">
                    {!! nl2br(e($developmentBrief->room_unit_mix ?: '—')) !!}
                </div>
            </div>

        </div>
    </div>


    {{-- Business and Functional Requirements --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-tasks"></i>
                Business and Functional Requirements
            </h5>
        </div>

        <div class="card-body">

            <div class="mb-4">
                <strong class="d-block text-muted mb-2">
                    Business Requirements
                </strong>

                {!! nl2br(e($developmentBrief->business_requirements ?: '—')) !!}
            </div>

            <div class="mb-4">
                <strong class="d-block text-muted mb-2">
                    Functional Requirements
                </strong>

                {!! nl2br(e($developmentBrief->functional_requirements ?: '—')) !!}
            </div>

            <div>
                <strong class="d-block text-muted mb-2">
                    Operational Requirements
                </strong>

                {!! nl2br(e($developmentBrief->operational_requirements ?: '—')) !!}
            </div>

        </div>
    </div>


    {{-- Design and Technical Requirements --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-drafting-compass"></i>
                Design and Technical Requirements
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-4">
                    <strong class="d-block text-muted mb-2">
                        Quality Level
                    </strong>

                    {{ $developmentBrief->quality_level ?: '—' }}
                </div>

                <div class="col-md-6 mb-4">
                    <strong class="d-block text-muted mb-2">
                        Quality Requirements
                    </strong>

                    {!! nl2br(e($developmentBrief->quality_requirements ?: '—')) !!}
                </div>

                <div class="col-md-12 mb-4">
                    <strong class="d-block text-muted mb-2">
                        Design Criteria
                    </strong>

                    {!! nl2br(e($developmentBrief->design_criteria ?: '—')) !!}
                </div>

                <div class="col-md-12 mb-4">
                    <strong class="d-block text-muted mb-2">
                        Design Intent
                    </strong>

                    {!! nl2br(e($developmentBrief->design_intent ?: '—')) !!}
                </div>

                <div class="col-md-12">
                    <strong class="d-block text-muted mb-2">
                        Technical Requirements
                    </strong>

                    {!! nl2br(e($developmentBrief->technical_requirements ?: '—')) !!}
                </div>

            </div>

        </div>
    </div>


    {{-- Sustainability and Commercial Requirements --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-leaf"></i>
                Sustainability and Commercial Requirements
            </h5>
        </div>

        <div class="card-body">

            <div class="mb-4">
                <strong class="d-block text-muted mb-2">
                    Sustainability Requirements
                </strong>

                {!! nl2br(e($developmentBrief->sustainability_requirements ?: '—')) !!}
            </div>

            <div class="row">

                <div class="col-md-6">
                    <strong class="d-block text-muted mb-2">
                        Commercial Intent
                    </strong>

                    {!! nl2br(e($developmentBrief->commercial_intent ?: '—')) !!}
                </div>

                <div class="col-md-6">
                    <strong class="d-block text-muted mb-2">
                        Success Criteria
                    </strong>

                    {!! nl2br(e($developmentBrief->success_criteria ?: '—')) !!}
                </div>

            </div>

        </div>
    </div>


    {{-- Constraints and Dependencies --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-exclamation-triangle"></i>
                Constraints, Assumptions and Dependencies
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-4">
                    <strong class="d-block text-muted mb-2">
                        Key Constraints
                    </strong>

                    {!! nl2br(e($developmentBrief->key_constraints ?: '—')) !!}
                </div>

                <div class="col-md-4 mb-4">
                    <strong class="d-block text-muted mb-2">
                        Key Assumptions
                    </strong>

                    {!! nl2br(e($developmentBrief->key_assumptions ?: '—')) !!}
                </div>

                <div class="col-md-4 mb-4">
                    <strong class="d-block text-muted mb-2">
                        Key Dependencies
                    </strong>

                    {!! nl2br(e($developmentBrief->key_dependencies ?: '—')) !!}
                </div>

                <div class="col-md-12">
                    <strong class="d-block text-muted mb-2">
                        Required Studies
                    </strong>

                    {!! nl2br(e($developmentBrief->required_studies ?: '—')) !!}
                </div>

            </div>

        </div>
    </div>


    {{-- Approval and Revision Information --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-check-circle"></i>
                Approval and Revision Information
            </h5>
        </div>

        <div class="card-body">

            <div class="mb-4">
                <strong class="d-block text-muted mb-2">
                    Required Approvals
                </strong>

                {!! nl2br(e($developmentBrief->approvals_required ?: '—')) !!}
            </div>

            <div class="mb-4">
                <strong class="d-block text-muted mb-2">
                    Approval Remarks
                </strong>

                {!! nl2br(e($developmentBrief->approval_remarks ?: '—')) !!}
            </div>

            <div>
                <strong class="d-block text-muted mb-2">
                    Revision History
                </strong>

                {!! nl2br(e($developmentBrief->revision_history ?: '—')) !!}
            </div>

        </div>
    </div>


    {{-- Audit Information --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-history"></i>
                Audit Information
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">
                    <strong class="d-block text-muted">Created By</strong>

                    {{ $developmentBrief->creator->name
                        ?? $developmentBrief->creator->full_name
                        ?? '—'
                    }}
                </div>

                <div class="col-md-4">
                    <strong class="d-block text-muted">Prepared By</strong>

                    {{ $developmentBrief->preparer->name
                        ?? $developmentBrief->preparer->full_name
                        ?? '—'
                    }}
                </div>

                <div class="col-md-4">
                    <strong class="d-block text-muted">Last Updated By</strong>

                    {{ $developmentBrief->updater->name
                        ?? $developmentBrief->updater->full_name
                        ?? '—'
                    }}
                </div>

            </div>

        </div>
    </div>


    {{-- Bottom Actions --}}
    <div class="d-flex justify-content-between mb-4">

        <a
            href="{{ route('admin.projects.development-brief.index', $project) }}"
            class="btn btn-secondary"
        >
            <i class="fas fa-arrow-left"></i>
            Back to List
        </a>

        <div>

            <a
                href="{{ route('admin.projects.development-brief.edit', [$project, $developmentBrief]) }}"
                class="btn btn-warning"
            >
                <i class="fas fa-edit"></i>
                Edit Development Brief
            </a>

            <form
                action="{{ route('admin.projects.development-brief.destroy', [$project, $developmentBrief]) }}"
                method="POST"
                class="d-inline"
                onsubmit="return confirm('Are you sure you want to delete this development brief?');"
            >
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i>
                    Delete
                </button>
            </form>

        </div>

    </div>

</div>

{{-- Approve Modal --}}
<div
    class="modal fade"
    id="approveBriefModal"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Approve Development Brief
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>
            </div>

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.development-brief.workflow.action',
                    [
                        'project' => $project->id,
                        'developmentBrief' => $developmentBrief->id,
                        'action' => 'approve',
                    ]
                ) }}"
            >
                @csrf

                <div class="modal-body">
                    Are you sure you want to approve this Development Brief?
                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i>
                        Confirm Approval
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div
    class="modal fade"
    id="rejectBriefModal"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Reject Development Brief
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>
            </div>

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.development-brief.workflow.action',
                    [
                        'project' => $project->id,
                        'developmentBrief' => $developmentBrief->id,
                        'action' => 'reject',
                    ]
                ) }}"
            >
                @csrf

                <div class="modal-body">

                    <label class="form-label">
                        Rejection Remarks
                        <span class="text-danger">*</span>
                    </label>

                    <textarea
                        name="approval_remarks"
                        class="form-control"
                        rows="4"
                        required
                        placeholder="Enter reason for rejection..."
                    ></textarea>

                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i>
                        Confirm Rejection
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- Return for Revision Modal --}}
<div
    class="modal fade"
    id="returnBriefModal"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Return for Revision
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>
            </div>

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.development-brief.workflow.action',
                    [
                        'project' => $project->id,
                        'developmentBrief' => $developmentBrief->id,
                        'action' => 'return',
                    ]
                ) }}"
            >
                @csrf

                <div class="modal-body">

                    <label class="form-label">
                        Revision Remarks
                        <span class="text-danger">*</span>
                    </label>

                    <textarea
                        name="approval_remarks"
                        class="form-control"
                        rows="4"
                        required
                        placeholder="Explain what needs to be revised..."
                    ></textarea>

                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-undo"></i>
                        Return for Revision
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
<style>
    .brief-content {
        white-space: normal;
        line-height: 1.7;
    }

    .card-header h5 {
        font-weight: 600;
    }

    .text-end {
        text-align: right;
    }
</style>
@endsection

