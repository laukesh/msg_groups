@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Governance
            </div>

            <h3 class="mb-1">
                Project Governance
            </h3>

            <div class="text-muted">

                {{ $project->project_name }}

                @if($project->project_number)
                    · {{ $project->project_number }}
                @endif

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.show',
                    [
                        'project' => $project->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Project
            </a>


            <a
                href="{{ route(
                    'admin.projects.governance.create',
                    [
                        'project' => $project->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                + Add Governance Framework
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- SUMMARY --}}
    {{-- ========================================================= --}}

    @php

        $totalGovernance =
            $governances->count();

        $activeGovernance =
            $governances
                ->where('status', 'Active')
                ->count();

        $draftGovernance =
            $governances
                ->where('status', 'Draft')
                ->count();

        $underReviewGovernance =
            $governances
                ->where('status', 'Under Review')
                ->count();

        $closedGovernance =
            $governances
                ->where('status', 'Closed')
                ->count();

    @endphp


    <div class="row g-3 mb-4">

        {{-- Total --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Frameworks
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $totalGovernance }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Active --}}

        <div class="col-md-3">

            <div class="card h-100 border-success">

                <div class="card-body">

                    <div class="text-muted small">
                        Active
                    </div>

                    <div class="fs-3 fw-semibold text-success">
                        {{ $activeGovernance }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Draft --}}

        <div class="col-md-3">

            <div class="card h-100 border-warning">

                <div class="card-body">

                    <div class="text-muted small">
                        Draft
                    </div>

                    <div class="fs-3 fw-semibold text-warning">
                        {{ $draftGovernance }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Under Review --}}

        <div class="col-md-3">

            <div class="card h-100 border-info">

                <div class="card-body">

                    <div class="text-muted small">
                        Under Review
                    </div>

                    <div class="fs-3 fw-semibold text-info">
                        {{ $underReviewGovernance }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- GOVERNANCE FRAMEWORK LIST --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Governance Frameworks
                    </strong>

                    <div class="text-muted small mt-1">
                        Governance structures and control frameworks
                        configured for this project.
                    </div>

                </div>


                <span class="text-muted small">

                    {{ $totalGovernance }}

                    {{ $totalGovernance === 1
                        ? 'framework'
                        : 'frameworks'
                    }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($governances->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead>

                            <tr>

                                <th>
                                    Governance No.
                                </th>

                                <th>
                                    Title
                                </th>

                                <th>
                                    Governance Model
                                </th>

                                <th>
                                    Project Sponsor
                                </th>

                                <th>
                                    Project Director
                                </th>

                                <th>
                                    Project Manager
                                </th>

                                <th>
                                    Effective Date
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($governances as $governance)

                                @php

                                    $statusClass =
                                        match($governance->status) {

                                            'Active'
                                                => 'bg-success',

                                            'Draft'
                                                => 'bg-warning text-dark',

                                            'Under Review'
                                                => 'bg-info text-dark',

                                            'Superseded'
                                                => 'bg-secondary',

                                            'Closed'
                                                => 'bg-dark',

                                            default
                                                => 'bg-secondary',

                                        };

                                @endphp


                                <tr>

                                    {{-- Number --}}

                                    <td>

                                        <strong>
                                            {{ $governance->governance_number }}
                                        </strong>

                                    </td>


                                    {{-- Title --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $governance->title }}

                                        </div>

                                    </td>


                                    {{-- Model --}}

                                    <td>

                                        {{ $governance->governance_model }}

                                    </td>


                                    {{-- Sponsor --}}

                                    <td>

                                        @if($governance->projectSponsor)

                                            {{ $governance->projectSponsor->name }}

                                        @else

                                            <span class="text-muted">
                                                Unassigned
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Director --}}

                                    <td>

                                        @if($governance->projectDirector)

                                            {{ $governance->projectDirector->name }}

                                        @else

                                            <span class="text-muted">
                                                Unassigned
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Manager --}}

                                    <td>

                                        @if($governance->projectManager)

                                            {{ $governance->projectManager->name }}

                                        @else

                                            <span class="text-muted">
                                                Unassigned
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Effective Date --}}

                                    <td>

                                        @if($governance->effective_date)

                                            {{ $governance->effective_date->format('d-m-Y') }}

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $governance->status }}
                                        </span>

                                    </td>


                                    {{-- Actions --}}

                                    <td class="text-end">

                                        <div class="d-flex justify-content-end gap-1">

                                            <a
                                                href="{{ route(
                                                    'admin.projects.governance.show',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'governance' =>
                                                            $governance->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>


                                            <a
                                                href="{{ route(
                                                    'admin.projects.governance.edit',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'governance' =>
                                                            $governance->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Edit
                                            </a>

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

                <div class="text-center py-5">

                    <div class="mb-3">

                        <h5>
                            No Governance Framework
                        </h5>

                        <div class="text-muted">

                            No project governance framework has
                            been configured for this project yet.

                        </div>

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
    {{-- GOVERNANCE EXPLANATION --}}
    {{-- ========================================================= --}}

    <div class="card mt-4 mb-5">

        <div class="card-header">

            <strong>
                Governance Framework
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">

                    <div class="fw-semibold mb-1">
                        Leadership
                    </div>

                    <div class="text-muted small">
                        Defines the Project Sponsor,
                        Project Director and Project Manager.
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="fw-semibold mb-1">
                        Decision Making
                    </div>

                    <div class="text-muted small">
                        Defines who can make project decisions
                        and within what authority.
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="fw-semibold mb-1">
                        Approvals
                    </div>

                    <div class="text-muted small">
                        Defines approval responsibilities,
                        limits and escalation requirements.
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="fw-semibold mb-1">
                        Reporting
                    </div>

                    <div class="text-muted small">
                        Defines project reporting,
                        meetings and governance reviews.
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection