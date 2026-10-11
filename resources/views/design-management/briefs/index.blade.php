@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex flex-wrap justify-content-between align-items-start mb-4">

        <div>

            <div class="text-uppercase text-muted small fw-semibold mb-1">
                Design Management
            </div>

            <h4 class="fw-bold mb-1">
                Project Briefs
            </h4>

            <div class="text-muted">

                {{ $project->project_name }}

                @if($project->project_code)
                    <span class="mx-1">•</span>
                    {{ $project->project_code }}
                @endif

            </div>

        </div>

        <div class="d-flex gap-2 mt-3 mt-md-0">

            @include(
                'design-management.partials.dashboard-link'
            )

            <a
                href="{{ route(
                    'admin.projects.design-management.briefs.create',
                    $project
                ) }}"
                class="btn btn-primary"
            >
                <i class="ri-add-line me-1"></i>
                Add Brief
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ALERTS --}}
    {{-- ========================================================= --}}

    @include(
        'design-management.partials.alerts'
    )


    {{-- ========================================================= --}}
    {{-- INTRODUCTION --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <div class="d-flex align-items-start">

                        <div
                            class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3"
                            style="width:52px;height:52px;"
                        >
                            <i class="ri-file-list-3-line fs-3"></i>
                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                Design Project Brief
                            </h5>

                            <p class="text-muted mb-0">

                                Define the project's design requirements,
                                objectives, functional needs, technical
                                standards and authority requirements before
                                detailed design activities begin.

                            </p>

                        </div>

                    </div>

                </div>

                <div class="col-lg-4 mt-3 mt-lg-0">

                    <div class="border rounded-3 p-3 bg-light">

                        <div class="small text-muted mb-1">
                            Current Version
                        </div>

                        @if($latestBrief)

                            <div class="d-flex align-items-center justify-content-between">

                                <div>

                                    <span class="fw-bold fs-5">
                                        v{{ $latestBrief->version }}
                                    </span>

                                    <div class="small text-muted">
                                        {{ $latestBrief->title }}
                                    </div>

                                </div>

                                @include(
                                    'design-management.partials.status-badge',
                                    ['status' => $latestBrief->status]
                                )

                            </div>

                        @else

                            <span class="text-muted">
                                No brief created yet.
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- STATISTICS --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-xl col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Total Briefs
                            </div>

                            <div class="fs-3 fw-bold mt-1">
                                {{ $totalBriefs }}
                            </div>

                        </div>

                        <div
                            class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                            style="width:44px;height:44px;"
                        >
                            <i class="ri-file-text-line fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Draft --}}
        <div class="col-xl col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Draft
                            </div>

                            <div class="fs-3 fw-bold mt-1">
                                {{ $draftBriefs }}
                            </div>

                        </div>

                        <div
                            class="rounded-3 bg-secondary bg-opacity-10 text-secondary d-flex align-items-center justify-content-center"
                            style="width:44px;height:44px;"
                        >
                            <i class="ri-draft-line fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Submitted --}}
        <div class="col-xl col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Submitted
                            </div>

                            <div class="fs-3 fw-bold mt-1">
                                {{ $submittedBriefs }}
                            </div>

                        </div>

                        <div
                            class="rounded-3 bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center"
                            style="width:44px;height:44px;"
                        >
                            <i class="ri-send-plane-line fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Approved --}}
        <div class="col-xl col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Approved
                            </div>

                            <div class="fs-3 fw-bold mt-1">
                                {{ $approvedBriefs }}
                            </div>

                        </div>

                        <div
                            class="rounded-3 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center"
                            style="width:44px;height:44px;"
                        >
                            <i class="ri-checkbox-circle-line fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Rejected --}}
        <div class="col-xl col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Rejected
                            </div>

                            <div class="fs-3 fw-bold mt-1">
                                {{ $rejectedBriefs }}
                            </div>

                        </div>

                        <div
                            class="rounded-3 bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center"
                            style="width:44px;height:44px;"
                        >
                            <i class="ri-close-circle-line fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- STATUS SUMMARY --}}
    {{-- ========================================================= --}}

    @if($totalBriefs > 0)

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>

                        <h6 class="fw-bold mb-1">
                            Brief Status Overview
                        </h6>

                        <small class="text-muted">
                            Current status of project design briefs
                        </small>

                    </div>

                    <span class="text-muted small">
                        {{ $totalBriefs }} total
                    </span>

                </div>


                @php

                    $statusSegments = [
                        [
                            'label' => 'Draft',
                            'count' => $draftBriefs,
                            'class' => 'bg-secondary',
                        ],
                        [
                            'label' => 'Submitted',
                            'count' => $submittedBriefs,
                            'class' => 'bg-warning',
                        ],
                        [
                            'label' => 'Approved',
                            'count' => $approvedBriefs,
                            'class' => 'bg-success',
                        ],
                        [
                            'label' => 'Rejected',
                            'count' => $rejectedBriefs,
                            'class' => 'bg-danger',
                        ],
                    ];

                @endphp


                <div
                    class="progress mb-3"
                    style="height:10px;"
                >

                    @foreach($statusSegments as $segment)

                        @if($segment['count'] > 0)

                            @php
                                $percentage =
                                    ($segment['count'] / $totalBriefs) * 100;
                            @endphp

                            <div
                                class="progress-bar {{ $segment['class'] }}"
                                style="width: {{ $percentage }}%;"
                                title="{{ $segment['label'] }}: {{ $segment['count'] }}"
                            ></div>

                        @endif

                    @endforeach

                </div>


                <div class="d-flex flex-wrap gap-4">

                    @foreach($statusSegments as $segment)

                        <div class="d-flex align-items-center">

                            <span
                                class="rounded-circle {{ $segment['class'] }} me-2"
                                style="width:9px;height:9px;"
                            ></span>

                            <span class="small text-muted">
                                {{ $segment['label'] }}
                            </span>

                            <span class="small fw-semibold ms-1">
                                {{ $segment['count'] }}
                            </span>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- BRIEF REGISTER --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm">

        {{-- Card Header --}}
        <div class="card-header bg-white border-bottom p-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center">

                <div>

                    <h6 class="fw-bold mb-1">
                        Design Brief Register
                    </h6>

                    <small class="text-muted">
                        Project design brief versions and workflow status
                    </small>

                </div>

                <div class="mt-2 mt-md-0">

                    <span class="badge bg-light text-dark border">
                        {{ $totalBriefs }}
                        {{ $totalBriefs === 1 ? 'Brief' : 'Briefs' }}
                    </span>

                </div>

            </div>

        </div>


        {{-- Table --}}
        <div class="card-body p-0">

            @if($briefs->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="ps-4">
                                    Brief
                                </th>

                                <th>
                                    Title
                                </th>

                                <th>
                                    Version
                                </th>

                                <th>
                                    Prepared By
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Updated
                                </th>

                                <th
                                    class="text-end pe-4"
                                    style="width:170px;"
                                >
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($briefs as $brief)

                                <tr>

                                    {{-- Brief --}}
                                    <td class="ps-4">

                                        <div class="d-flex align-items-center">

                                            <div
                                                class="rounded-2 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3"
                                                style="width:38px;height:38px;"
                                            >
                                                <i class="ri-file-text-line"></i>
                                            </div>

                                            <div>

                                                <a
                                                    href="{{ route(
                                                        'admin.projects.design-management.briefs.show',
                                                        [$project, $brief]
                                                    ) }}"
                                                    class="fw-semibold text-decoration-none"
                                                >
                                                    {{ $brief->brief_code ?: 'BRIEF-' . str_pad($brief->id, 5, '0', STR_PAD_LEFT) }}
                                                </a>

                                                <div class="small text-muted">
                                                    ID #{{ $brief->id }}
                                                </div>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Title --}}
                                    <td>

                                        <div class="fw-semibold">
                                            {{ $brief->title }}
                                        </div>

                                        @if($brief->remarks)

                                            <div
                                                class="small text-muted text-truncate"
                                                style="max-width:280px;"
                                            >
                                                {{ $brief->remarks }}
                                            </div>

                                        @endif

                                    </td>


                                    {{-- Version --}}
                                    <td>

                                        <span class="badge bg-light text-dark border">

                                            <i class="ri-git-branch-line me-1"></i>

                                            v{{ $brief->version }}

                                        </span>

                                        @if($brief->version_number)

                                            <div class="small text-muted mt-1">
                                                Revision {{ $brief->version_number }}
                                            </div>

                                        @endif

                                    </td>


                                    {{-- Prepared By --}}
                                    <td>

                                        @if($brief->preparer)

                                            <div class="fw-medium">
                                                {{ $brief->preparer->name }}
                                            </div>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @include(
                                            'design-management.partials.status-badge',
                                            [
                                                'status' => $brief->status
                                            ]
                                        )

                                    </td>


                                    {{-- Updated --}}
                                    <td>

                                        @if($brief->updated_at)

                                            <div class="small fw-medium">
                                                {{ $brief->updated_at->format('d M Y') }}
                                            </div>

                                            <div class="small text-muted">
                                                {{ $brief->updated_at->format('h:i A') }}
                                            </div>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-end pe-4">

                                        <div class="d-inline-flex gap-1">

                                            {{-- View --}}
                                            <a
                                                href="{{ route(
                                                    'admin.projects.design-management.briefs.show',
                                                    [$project, $brief]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="View Brief"
                                            >
                                                <i class="ri-eye-line"></i>
                                            </a>


                                            {{-- Edit --}}
                                            @if($brief->isEditable())

                                                <a
                                                    href="{{ route(
                                                        'admin.projects.design-management.briefs.edit',
                                                        [$project, $brief]
                                                    ) }}"
                                                    class="btn btn-sm btn-outline-secondary"
                                                    title="Edit Brief"
                                                >
                                                    <i class="ri-edit-line"></i>
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

                {{-- ================================================= --}}
                {{-- EMPTY STATE --}}
                {{-- ================================================= --}}

                <div class="text-center py-5 px-3">

                    <div
                        class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-3"
                        style="width:70px;height:70px;"
                    >
                        <i class="ri-file-list-3-line fs-2"></i>
                    </div>

                    <h5 class="fw-bold mb-2">
                        No Design Briefs Yet
                    </h5>

                    <p
                        class="text-muted mx-auto mb-4"
                        style="max-width:520px;"
                    >
                        Create the first project design brief to define
                        the project's design objectives, requirements,
                        standards and authority considerations.
                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.design-management.briefs.create',
                            $project
                        ) }}"
                        class="btn btn-primary"
                    >
                        <i class="ri-add-line me-1"></i>
                        Create First Brief
                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- INFORMATION FOOTER --}}
    {{-- ========================================================= --}}

    <div class="row mt-4">

        <div class="col-lg-8">

            <div class="d-flex align-items-start">

                <i class="ri-information-line text-primary fs-5 me-2"></i>

                <div>

                    <div class="fw-semibold small">
                        About Project Briefs
                    </div>

                    <div class="small text-muted">
                        Project Briefs establish the design basis for the
                        project and provide a controlled reference for
                        subsequent design development and approvals.
                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">

            <span class="small text-muted">
                Design Management
                <span class="mx-1">•</span>
                Project Level
            </span>

        </div>

    </div>

</div>

@endsection