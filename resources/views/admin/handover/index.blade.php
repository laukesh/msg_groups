@extends('layouts.app')

@section('title', 'Handover & Closeout')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
         ALERTS
    ============================================================ --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="ri-checkbox-circle-line me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('info'))

        <div class="alert alert-info alert-dismissible fade show">

            <i class="ri-information-line me-1"></i>

            {{ session('info') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="ri-error-warning-line me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ============================================================
         HEADER
    ============================================================ --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Handover &amp; Closeout
            </h4>

            <div class="text-muted">

                {{ $project->project_name ?? $project->name ?? 'Project' }}

                @if($handover->handover_no)

                    <span class="mx-2">•</span>

                    {{ $handover->handover_no }}

                @endif

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.handover.index') }}"
                class="btn btn-outline-secondary">

                <i class="ri-arrow-left-line me-1"></i>

                Back to Projects

            </a>

        </div>

    </div>


    {{-- ============================================================
         READINESS BANNER
    ============================================================ --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <div class="d-flex align-items-center gap-3">

                        <div class="handover-readiness-icon">

                            @if($readyForHandover)

                                <i class="ri-checkbox-circle-line"></i>

                            @else

                                <i class="ri-time-line"></i>

                            @endif

                        </div>


                        <div>

                            <div class="text-muted small">
                                HANDOVER READINESS
                            </div>

                            <h3 class="fw-bold mb-1">

                                {{ number_format($readinessPercentage, 0) }}%

                            </h3>

                            <p class="text-muted mb-0">

                                {{ $mandatoryCompleted }}
                                of
                                {{ $mandatoryTotal }}

                                mandatory requirements completed.

                            </p>

                        </div>

                    </div>

                </div>


                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">

                    @if($readyForHandover)

                        <span class="badge bg-success-subtle text-success px-3 py-2">

                            <i class="ri-checkbox-circle-line me-1"></i>

                            Ready for Handover

                        </span>

                    @else

                        <span class="badge bg-warning-subtle text-warning px-3 py-2">

                            <i class="ri-time-line me-1"></i>

                            Not Ready

                        </span>

                    @endif

                </div>

            </div>


            {{-- Progress --}}

            <div
                class="progress mt-4"
                style="height: 9px;">

                <div
                    class="progress-bar {{ $readyForHandover ? 'bg-success' : '' }}"
                    role="progressbar"
                    style="width: {{ $readinessPercentage }}%;"
                    aria-valuenow="{{ $readinessPercentage }}"
                    aria-valuemin="0"
                    aria-valuemax="100">

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         KPI CARDS
    ============================================================ --}}

    <div class="row g-3 mb-4">


        {{-- Total --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                TOTAL REQUIREMENTS
                            </div>

                            <h3 class="fw-bold mb-0 mt-2">
                                {{ $totalRequirements }}
                            </h3>

                        </div>


                        <div class="handover-kpi-icon blue">

                            <i class="ri-list-check-3"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Completed --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                COMPLETED
                            </div>

                            <h3 class="fw-bold text-success mb-0 mt-2">
                                {{ $completedRequirements }}
                            </h3>

                        </div>


                        <div class="handover-kpi-icon green">

                            <i class="ri-checkbox-circle-line"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- In Progress --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                IN PROGRESS
                            </div>

                            <h3 class="fw-bold text-primary mb-0 mt-2">
                                {{ $inProgressRequirements }}
                            </h3>

                        </div>


                        <div class="handover-kpi-icon purple">

                            <i class="ri-loader-4-line"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Pending --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                PENDING
                            </div>

                            <h3 class="fw-bold text-warning mb-0 mt-2">
                                {{ $pendingRequirements }}
                            </h3>

                        </div>


                        <div class="handover-kpi-icon orange">

                            <i class="ri-time-line"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ============================================================
     HANDOVER & CLOSEOUT MODULES
    ============================================================ --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div>
                <h5 class="mb-1">
                    Handover &amp; Closeout Modules
                </h5>

                <small class="text-muted">
                    Manage project handover, documentation, completion and closeout activities.
                </small>
            </div>

        </div>


        <div class="card-body">

            <div class="row g-3">

                {{-- ====================================================
                     READINESS
                ===================================================== --}}

                <div class="col-xl-3 col-md-6">

                    <div class="card border h-100 mb-0">

                        <div class="card-body">

                            <div class="d-flex align-items-start">

                                <div class="flex-shrink-0">

                                    <div
                                        class="rounded bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                                        style="width:42px;height:42px;">

                                        <i class="ri-dashboard-line fs-4"></i>

                                    </div>

                                </div>

                                <div class="ms-3">

                                    <h6 class="mb-1">
                                        Handover Readiness
                                    </h6>

                                    <p class="text-muted small mb-3">
                                        Monitor overall handover readiness and mandatory completion.
                                    </p>

                                    <a
                                        href="#handover-readiness"
                                        class="btn btn-sm btn-primary">

                                        Open Module

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ====================================================
                     REQUIREMENTS
                ===================================================== --}}

                <div class="col-xl-3 col-md-6">

                    <div class="card border h-100 mb-0">

                        <div class="card-body">

                            <div class="d-flex align-items-start">

                                <div class="flex-shrink-0">

                                    <div
                                        class="rounded bg-info-subtle text-info d-flex align-items-center justify-content-center"
                                        style="width:42px;height:42px;">

                                        <i class="ri-list-check-2 fs-4"></i>

                                    </div>

                                </div>

                                <div class="ms-3">

                                    <h6 class="mb-1">
                                        Handover Requirements
                                    </h6>

                                    <p class="text-muted small mb-3">
                                        Manage mandatory and project-specific handover requirements.
                                    </p>

                                    <a
                                        href="{{ route(
                                            'admin.projects.handover.requirements.index',
                                            $project
                                        ) }}"
                                        class="btn btn-sm btn-primary">

                                        Open Module

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ====================================================
                     SNAGGING
                ===================================================== --}}

                <div class="col-xl-3 col-md-6">

                    <div class="card border h-100 mb-0">

                        <div class="card-body">

                            <div class="d-flex align-items-start">

                                <div class="flex-shrink-0">

                                    <div
                                        class="rounded bg-warning-subtle text-warning d-flex align-items-center justify-content-center"
                                        style="width:42px;height:42px;">

                                        <i class="ri-error-warning-line fs-4"></i>

                                    </div>

                                </div>

                                <div class="ms-3">

                                    <h6 class="mb-1">
                                        Snagging / Punch List
                                    </h6>

                                    <p class="text-muted small mb-3">
                                        Track, rectify and close project snag and punch list items.
                                    </p>

                                    <a
                                        href="{{ route(
                                            'admin.projects.handover.snags.index',
                                            $project
                                        ) }}"
                                        class="btn btn-sm btn-primary">

                                        Open Module

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ====================================================
                     DOCUMENTS
                ===================================================== --}}

                <div class="col-xl-3 col-md-6">

                    <div class="card border h-100 mb-0">

                        <div class="card-body">

                            <div class="d-flex align-items-start">

                                <div class="flex-shrink-0">

                                    <div
                                        class="rounded bg-success-subtle text-success d-flex align-items-center justify-content-center"
                                        style="width:42px;height:42px;">

                                        <i class="ri-folder-upload-line fs-4"></i>

                                    </div>

                                </div>

                                <div class="ms-3">

                                    <h6 class="mb-1">
                                        Handover Documents
                                    </h6>

                                    <p class="text-muted small mb-3">
                                        Manage O&amp;M manuals, as-built drawings, warranties and approvals.
                                    </p>

                                    <a
                                        href="{{ route(
                                            'admin.projects.handover.documents.index',
                                            $project
                                        ) }}"
                                        class="btn btn-sm btn-primary">

                                        Open Module

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ====================================================
                     DEFECTS
                ===================================================== --}}

                <div class="col-xl-3 col-md-6">

                    <div class="card border h-100 mb-0">

                        <div class="card-body">

                            <div class="d-flex align-items-start">

                                <div class="flex-shrink-0">

                                    <div
                                        class="rounded bg-danger-subtle text-danger d-flex align-items-center justify-content-center"
                                        style="width:42px;height:42px;">

                                        <i class="ri-bug-line fs-4"></i>

                                    </div>

                                </div>

                                <div class="ms-3">

                                    <h6 class="mb-1">
                                        Defects
                                    </h6>

                                    <p class="text-muted small mb-3">
                                        Track defects, rectification, verification and closure.
                                    </p>

                                    <a
                                        href="{{ route(
                                            'admin.projects.handover.defects.index',
                                            $project
                                        ) }}"
                                        class="btn btn-sm btn-primary">

                                        Open Module

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ====================================================
                     PRACTICAL COMPLETION
                ===================================================== --}}

                <div class="col-xl-3 col-md-6">

                    <div class="card border h-100 mb-0">

                        <div class="card-body">

                            <div class="d-flex align-items-start">

                                <div class="flex-shrink-0">

                                    <div
                                        class="rounded bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                                        style="width:42px;height:42px;">

                                        <i class="ri-checkbox-circle-line fs-4"></i>

                                    </div>

                                </div>

                                <div class="ms-3">

                                    <h6 class="mb-1">
                                        Practical Completion
                                    </h6>

                                    <p class="text-muted small mb-3">
                                        Control practical completion and project readiness for handover.
                                    </p>

                                    <a href="{{ route(
                                        'admin.projects.handover.practical-completion.index',
                                        $project
                                    ) }}"
                                       class="btn btn-sm btn-primary">
                                        Open Module
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ====================================================
                     FINAL COMPLETION
                ===================================================== --}}

                <div class="col-xl-3 col-md-6">

                    <div class="card border h-100 mb-0">

                        <div class="card-body">

                            <div class="d-flex align-items-start">

                                <div class="flex-shrink-0">

                                    <div
                                        class="rounded bg-success-subtle text-success d-flex align-items-center justify-content-center"
                                        style="width:42px;height:42px;">

                                        <i class="ri-checkbox-multiple-line fs-4"></i>

                                    </div>

                                </div>

                                <div class="ms-3">

                                    <h6 class="mb-1">
                                        Final Completion
                                    </h6>

                                    <p class="text-muted small mb-3">
                                        Complete all outstanding project closeout obligations.
                                    </p>

                                    <a href="{{ route(
                                        'admin.projects.handover.final-completion.index',
                                        $project
                                    ) }}"
                                       class="btn btn-sm btn-primary">
                                        Open Module
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ====================================================
                     FINAL ACCOUNT
                ===================================================== --}}

                <div class="col-xl-3 col-md-6">

                    <div class="card border h-100 mb-0">

                        <div class="card-body">

                            <div class="d-flex align-items-start">

                                <div class="flex-shrink-0">

                                    <div
                                        class="rounded bg-warning-subtle text-warning d-flex align-items-center justify-content-center"
                                        style="width:42px;height:42px;">

                                        <i class="ri-money-rupee-circle-line fs-4"></i>

                                    </div>

                                </div>

                                <div class="ms-3">

                                    <h6 class="mb-1">
                                        Final Account
                                    </h6>

                                    <p class="text-muted small mb-3">
                                        Track final contract account and financial closeout.
                                    </p>

                                    <a href="{{ route(
                                        'admin.projects.handover.final-account.index',
                                        $project
                                    ) }}"
                                       class="btn btn-sm btn-primary">
                                        Open Module
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ====================================================
                     FINAL PAYMENT
                ===================================================== --}}

                <div class="col-xl-3 col-md-6">

                    <div class="card border h-100 mb-0">

                        <div class="card-body">

                            <div class="d-flex align-items-start">

                                <div class="flex-shrink-0">

                                    <div
                                        class="rounded bg-info-subtle text-info d-flex align-items-center justify-content-center"
                                        style="width:42px;height:42px;">

                                        <i class="ri-bank-card-line fs-4"></i>

                                    </div>

                                </div>

                                <div class="ms-3">

                                    <h6 class="mb-1">
                                        Final Payment
                                    </h6>

                                    <p class="text-muted small mb-3">
                                        Manage final payment and financial closure activities.
                                    </p>

                                    <a href="{{ route(
                                        'admin.projects.handover.final-payment.index',
                                        $project
                                    ) }}"
                                       class="btn btn-sm btn-primary">
                                        Open Module
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ====================================================
                     HANDOVER CERTIFICATE
                ===================================================== --}}

                <div class="col-xl-3 col-md-6">

                    <div class="card border h-100 mb-0">

                        <div class="card-body">

                            <div class="d-flex align-items-start">

                                <div class="flex-shrink-0">

                                    <div
                                        class="rounded bg-success-subtle text-success d-flex align-items-center justify-content-center"
                                        style="width:42px;height:42px;">

                                        <i class="ri-award-line fs-4"></i>

                                    </div>

                                </div>

                                <div class="ms-3">

                                    <h6 class="mb-1">
                                        Handover Certificates
                                    </h6>

                                    <p class="text-muted small mb-3">
                                        Prepare and issue formal project handover certificates.
                                    </p>

                                    <a href="{{ route(
                                        'admin.projects.handover.certificates.index',
                                        $project
                                    ) }}"
                                       class="btn btn-sm btn-primary">
                                        Open Module
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ====================================================
                     ASSET HANDOVER
                ===================================================== --}}

                <div class="col-xl-3 col-md-6">

                    <div class="card border h-100 mb-0">

                        <div class="card-body">

                            <div class="d-flex align-items-start">

                                <div class="flex-shrink-0">

                                    <div
                                        class="rounded bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                                        style="width:42px;height:42px;">

                                        <i class="ri-building-2-line fs-4"></i>

                                    </div>

                                </div>

                                <div class="ms-3">

                                    <h6 class="mb-1">
                                        Asset Handover
                                    </h6>

                                    <p class="text-muted small mb-3">
                                        Transfer completed project assets into operations.
                                    </p>

                                    <a href="{{ route(
                                        'admin.projects.handover.asset-handover.index',
                                        $project
                                    ) }}"
                                       class="btn btn-sm btn-primary">
                                        Open Module
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ====================================================
                     AUDIT LOG
                ===================================================== --}}

                <!-- <div class="col-xl-3 col-md-6">

                    <div class="card border h-100 mb-0">

                        <div class="card-body">

                            <div class="d-flex align-items-start">

                                <div class="flex-shrink-0">

                                    <div
                                        class="rounded bg-dark-subtle text-dark d-flex align-items-center justify-content-center"
                                        style="width:42px;height:42px;">

                                        <i class="ri-history-line fs-4"></i>

                                    </div>

                                </div>

                                <div class="ms-3">

                                    <h6 class="mb-1">
                                        Audit Log
                                    </h6>

                                    <p class="text-muted small mb-3">
                                        View handover and closeout activity history.
                                    </p>

                                    <a
                                        href="#audit-log"
                                        class="btn btn-sm btn-outline-secondary">

                                        Coming Soon

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div> -->

            </div>

        </div>

    </div>


    {{-- ============================================================
         HANDOVER STATUS
    ============================================================ --}}

    <div class="card border shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-3">


                <div class="col-md-3">

                    <div class="text-muted small">
                        HANDOVER NUMBER
                    </div>

                    <div class="fw-semibold mt-1">
                        {{ $handover->handover_no }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        STATUS
                    </div>

                    <div class="mt-1">

                        @php

                            $statusClass = match($handover->status) {

                                'Draft'
                                    => 'bg-secondary-subtle text-secondary',

                                'In Progress'
                                    => 'bg-warning-subtle text-warning',

                                'Ready for Handover'
                                    => 'bg-success-subtle text-success',

                                'Submitted'
                                    => 'bg-info-subtle text-info',

                                'Approved'
                                    => 'bg-primary-subtle text-primary',

                                'Rejected'
                                    => 'bg-danger-subtle text-danger',

                                'On Hold'
                                    => 'bg-dark-subtle text-dark',

                                'Completed'
                                    => 'bg-success text-white',

                                default
                                    => 'bg-light text-dark',

                            };

                        @endphp

                        <span class="badge {{ $statusClass }}">

                            {{ $handover->status }}

                        </span>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        PLANNED HANDOVER
                    </div>

                    <div class="fw-semibold mt-1">

                        @if($handover->planned_handover_date)

                            {{ $handover->planned_handover_date->format('d-m-Y') }}

                        @else

                            <span class="text-muted">
                                Not Set
                            </span>

                        @endif

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        MANDATORY ITEMS
                    </div>

                    <div class="fw-semibold mt-1">

                        {{ $mandatoryCompleted }}
                        /
                        {{ $mandatoryTotal }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         CLOSEOUT REQUIREMENTS
    ============================================================ --}}

    <div class="card border shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h6 class="mb-1 fw-semibold">
                        Handover Requirements
                    </h6>

                    <small class="text-muted">
                        Mandatory closeout items required before handover.
                    </small>

                </div>


                <div class="d-flex align-items-center gap-2">

                    <span class="badge bg-light text-dark">
                        {{ $totalRequirements }} Requirements
                    </span>

                    <a
                        href="{{ route(
                            'admin.projects.handover.requirements.index',
                            $project
                        ) }}"
                        class="btn btn-sm btn-primary">

                        <i class="ri-settings-3-line me-1"></i>

                        Manage Requirements

                    </a>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="60">
                                #
                            </th>

                            <th>
                                Requirement
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Priority
                            </th>

                            <th>
                                Mandatory
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($requirements as $requirement)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    <div class="fw-semibold">

                                        {{ $requirement->title }}

                                    </div>

                                    <small class="text-muted">

                                        {{ $requirement->requirement_code }}

                                    </small>

                                </td>


                                <td>

                                    <span class="badge bg-light text-dark">

                                        {{ $requirement->requirement_type }}

                                    </span>

                                </td>


                                <td>

                                    @if($requirement->priority === 'Critical')

                                        <span class="badge bg-danger">

                                            Critical

                                        </span>

                                    @elseif($requirement->priority === 'High')

                                        <span class="badge bg-warning text-dark">

                                            High

                                        </span>

                                    @elseif($requirement->priority === 'Medium')

                                        <span class="badge bg-primary-subtle text-primary">

                                            Medium

                                        </span>

                                    @else

                                        <span class="badge bg-light text-dark">

                                            {{ $requirement->priority }}

                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if($requirement->is_mandatory)

                                        <span class="text-danger">

                                            <i class="ri-star-fill"></i>

                                            Yes

                                        </span>

                                    @else

                                        <span class="text-muted">

                                            No

                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @switch($requirement->status)

                                        @case('Completed')

                                            <span class="badge bg-success-subtle text-success">

                                                <i class="ri-checkbox-circle-line me-1"></i>

                                                Completed

                                            </span>

                                            @break


                                        @case('In Progress')

                                            <span class="badge bg-primary-subtle text-primary">

                                                <i class="ri-loader-4-line me-1"></i>

                                                In Progress

                                            </span>

                                            @break


                                        @case('Submitted')

                                            <span class="badge bg-info-subtle text-info">

                                                <i class="ri-send-plane-line me-1"></i>

                                                Submitted

                                            </span>

                                            @break


                                        @case('Under Review')

                                            <span class="badge bg-info-subtle text-info">

                                                <i class="ri-search-eye-line me-1"></i>

                                                Under Review

                                            </span>

                                            @break


                                        @case('Rejected')

                                            <span class="badge bg-danger-subtle text-danger">

                                                <i class="ri-close-circle-line me-1"></i>

                                                Rejected

                                            </span>

                                            @break


                                        @case('Waived')

                                            <span class="badge bg-secondary-subtle text-secondary">

                                                <i class="ri-forbid-2-line me-1"></i>

                                                Waived

                                            </span>

                                            @break


                                        @default

                                            <span class="badge bg-warning-subtle text-warning">

                                                <i class="ri-time-line me-1"></i>

                                                Pending

                                            </span>

                                    @endswitch

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-5 text-muted">

                                    <i class="ri-folder-open-line fs-1 d-block mb-2"></i>

                                    No handover requirements found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection


{{-- ================================================================
     STYLES
================================================================ --}}

@push('styles')

<style>

    .handover-readiness-icon {

        width: 55px;
        height: 55px;

        border-radius: 12px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #e4f7ed;

        color: #198754;

        font-size: 28px;

    }


    .handover-kpi-icon {

        width: 45px;
        height: 45px;

        border-radius: 10px;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 22px;

    }


    .handover-kpi-icon.blue {

        background: #e7f0ff;
        color: #0d6efd;

    }


    .handover-kpi-icon.green {

        background: #e4f7ed;
        color: #198754;

    }


    .handover-kpi-icon.purple {

        background: #eee7ff;
        color: #6f42c1;

    }


    .handover-kpi-icon.orange {

        background: #fff3cd;
        color: #d39e00;

    }

</style>

@endpush