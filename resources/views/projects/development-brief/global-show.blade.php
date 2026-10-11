@extends('layouts.app')

@section('title', 'Development Brief Details')

@section('content')

@php
    $projectName = $developmentBrief->project->name
        ?? $developmentBrief->project->project_name
        ?? ('Project #' . $developmentBrief->project_id);

    $statusClass = match ($developmentBrief->approval_status) {
        'Approved' => 'success',
        'Rejected' => 'danger',
        'Under Review' => 'warning',
        'Revision Required' => 'warning',
        'Archived' => 'secondary',
        default => 'info',
    };
@endphp

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>
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

        <div>
            <a href="{{ route('admin.development-briefs.edit', $developmentBrief) }}"
               class="btn btn-warning">
                <i class="fas fa-edit"></i>
                Edit
            </a>

            <a href="{{ route('admin.development-briefs.index') }}"
               class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>
        </div>

    </div>


    {{-- Summary --}}
    <div class="card mb-4">
        <div class="card-body">

            <div class="row">

                <div class="col-md-2 mb-3">
                    <small class="text-muted d-block">Version</small>
                    <strong>{{ $developmentBrief->version ?: '—' }}</strong>
                </div>

                <div class="col-md-2 mb-3">
                    <small class="text-muted d-block">Revision</small>
                    <strong>{{ $developmentBrief->revision_number ?: '—' }}</strong>
                </div>

                <div class="col-md-3 mb-3">
                    <small class="text-muted d-block">Document Date</small>

                    <strong>
                        {{ $developmentBrief->document_date
                            ? \Carbon\Carbon::parse($developmentBrief->document_date)->format('d M Y')
                            : '—'
                        }}
                    </strong>
                </div>

                <div class="col-md-3 mb-3">
                    <small class="text-muted d-block">Quality Level</small>
                    <strong>{{ $developmentBrief->quality_level ?: '—' }}</strong>
                </div>

                <div class="col-md-2 mb-3">
                    <small class="text-muted d-block">Status</small>

                    <span class="badge bg-{{ $statusClass }}">
                        {{ $developmentBrief->approval_status ?: 'Draft' }}
                    </span>
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

                <div class="col-md-6 mb-4">
                    <strong class="d-block text-muted">
                        Brief Title
                    </strong>

                    {{ $developmentBrief->brief_title ?: '—' }}
                </div>

                <div class="col-md-6 mb-4">
                    <strong class="d-block text-muted">
                        Brief Reference
                    </strong>

                    {{ $developmentBrief->brief_reference ?: '—' }}
                </div>

                <div class="col-md-6 mb-4">
                    <strong class="d-block text-muted">
                        Target Use
                    </strong>

                    {!! nl2br(e($developmentBrief->target_use ?: '—')) !!}
                </div>

                <div class="col-md-6 mb-4">
                    <strong class="d-block text-muted">
                        Target Users
                    </strong>

                    {!! nl2br(e($developmentBrief->target_users ?: '—')) !!}
                </div>

                <div class="col-md-12">
                    <strong class="d-block text-muted">
                        Business Need
                    </strong>

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

                {!! nl2br(e($developmentBrief->area_schedule ?: '—')) !!}
            </div>

            <div>
                <strong class="d-block text-muted mb-2">
                    Room / Unit Mix
                </strong>

                {!! nl2br(e($developmentBrief->room_unit_mix ?: '—')) !!}
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

            <div class="mb-4">
                <strong class="d-block text-muted mb-2">
                    Quality Level
                </strong>

                {{ $developmentBrief->quality_level ?: '—' }}
            </div>

            <div class="mb-4">
                <strong class="d-block text-muted mb-2">
                    Quality Requirements
                </strong>

                {!! nl2br(e($developmentBrief->quality_requirements ?: '—')) !!}
            </div>

            <div class="mb-4">
                <strong class="d-block text-muted mb-2">
                    Design Criteria
                </strong>

                {!! nl2br(e($developmentBrief->design_criteria ?: '—')) !!}
            </div>

            <div class="mb-4">
                <strong class="d-block text-muted mb-2">
                    Design Intent
                </strong>

                {!! nl2br(e($developmentBrief->design_intent ?: '—')) !!}
            </div>

            <div>
                <strong class="d-block text-muted mb-2">
                    Technical Requirements
                </strong>

                {!! nl2br(e($developmentBrief->technical_requirements ?: '—')) !!}
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
                    Approval Status
                </strong>

                <span class="badge bg-{{ $statusClass }}">
                    {{ $developmentBrief->approval_status ?: 'Draft' }}
                </span>
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
                    <strong class="d-block text-muted">
                        Created By
                    </strong>

                    {{ $developmentBrief->creator->name
                        ?? $developmentBrief->creator->full_name
                        ?? '—' }}
                </div>

                <div class="col-md-4">
                    <strong class="d-block text-muted">
                        Prepared By
                    </strong>

                    {{ $developmentBrief->preparer->name
                        ?? $developmentBrief->preparer->full_name
                        ?? '—' }}
                </div>

                <div class="col-md-4">
                    <strong class="d-block text-muted">
                        Updated By
                    </strong>

                    {{ $developmentBrief->updater->name
                        ?? $developmentBrief->updater->full_name
                        ?? '—' }}
                </div>

            </div>

        </div>
    </div>


    {{-- Actions --}}
    <div class="d-flex justify-content-between mb-4">

        <a href="{{ route('admin.development-briefs.index') }}"
           class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to List
        </a>

        <div>

            <a href="{{ route('admin.development-briefs.edit', $developmentBrief) }}"
               class="btn btn-warning">
                <i class="fas fa-edit"></i>
                Edit
            </a>

            <form action="{{ route('admin.development-briefs.destroy', $developmentBrief) }}"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirm('Are you sure you want to delete this development brief?');">

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

@endsection

@push('styles')
<style>
    .card-header h5 {
        font-weight: 600;
    }

    .card {
        border-radius: 8px;
    }
</style>
@endpush