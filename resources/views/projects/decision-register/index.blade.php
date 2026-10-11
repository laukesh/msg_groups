@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Governance / Decision Register
            </div>

            <h3 class="mb-1">
                Decision Register
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
                    'admin.projects.governance.index',
                    [
                        'project' => $project->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Governance
            </a>


            <a
                href="{{ route(
                    'admin.projects.decision-register.create',
                    [
                        'project' => $project->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                + Add Decision
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

        $totalDecisions =
            $decisions->count();

        $draftDecisions =
            $decisions
                ->where('status', 'Draft')
                ->count();

        $approvedDecisions =
            $decisions
                ->where('status', 'Approved')
                ->count();

        $implementedDecisions =
            $decisions
                ->where('status', 'Implemented')
                ->count();

        $highPriorityDecisions =
            $decisions
                ->whereIn(
                    'priority',
                    [
                        'High',
                        'Critical',
                    ]
                )
                ->count();

        $implementationRequired =
            $decisions
                ->where(
                    'implementation_required',
                    true
                )
                ->count();

    @endphp


    <div class="row g-3 mb-4">

        {{-- Total --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Decisions
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $totalDecisions }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Approved --}}

        <div class="col-md-3">

            <div class="card h-100 border-primary">

                <div class="card-body">

                    <div class="text-muted small">
                        Approved
                    </div>

                    <div class="fs-3 fw-semibold text-primary">
                        {{ $approvedDecisions }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Implemented --}}

        <div class="col-md-3">

            <div class="card h-100 border-success">

                <div class="card-body">

                    <div class="text-muted small">
                        Implemented
                    </div>

                    <div class="fs-3 fw-semibold text-success">
                        {{ $implementedDecisions }}
                    </div>

                </div>

            </div>

        </div>


        {{-- High Priority --}}

        <div class="col-md-3">

            <div class="card h-100 border-danger">

                <div class="card-body">

                    <div class="text-muted small">
                        High / Critical
                    </div>

                    <div class="fs-3 fw-semibold text-danger">
                        {{ $highPriorityDecisions }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DECISION REGISTER --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Project Decisions
                    </strong>

                    <div class="text-muted small mt-1">
                        Record and track formal project governance decisions.
                    </div>

                </div>


                <span class="text-muted small">

                    {{ $totalDecisions }}

                    {{ $totalDecisions === 1
                        ? 'decision'
                        : 'decisions'
                    }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($decisions->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead>

                            <tr>

                                <th class="ps-3">
                                    Decision No.
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Subject
                                </th>

                                <th>
                                    Decision Type
                                </th>

                                <th>
                                    Decision Maker
                                </th>

                                <th>
                                    Priority
                                </th>

                                <th>
                                    Implementation
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end pe-3">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $decisions
                                as $decision
                            )

                                @php

                                    $priorityClass =
                                        match(
                                            $decision->priority
                                        ) {

                                            'Critical'
                                                => 'bg-danger',

                                            'High'
                                                => 'bg-warning text-dark',

                                            'Medium'
                                                => 'bg-primary',

                                            'Low'
                                                => 'bg-secondary',

                                            default
                                                => 'bg-secondary',

                                        };


                                    $statusClass =
                                        match(
                                            $decision->status
                                        ) {

                                            'Approved'
                                                => 'bg-primary',

                                            'Implemented'
                                                => 'bg-success',

                                            'Draft'
                                                => 'bg-warning text-dark',

                                            'Superseded'
                                                => 'bg-secondary',

                                            'Cancelled'
                                                => 'bg-danger',

                                            default
                                                => 'bg-secondary',

                                        };

                                @endphp


                                <tr>

                                    {{-- Decision Number --}}

                                    <td class="ps-3">

                                        <a
                                            href="{{ route(
                                                'admin.projects.decision-register.show',
                                                [
                                                    'project' =>
                                                        $project->id,

                                                    'decision' =>
                                                        $decision->id,
                                                ]
                                            ) }}"
                                            class="fw-semibold text-decoration-none"
                                        >

                                            {{ $decision->decision_number }}

                                        </a>

                                    </td>


                                    {{-- Date --}}

                                    <td>

                                        {{ $decision->decision_date
                                            ? $decision->decision_date
                                                ->format('d-m-Y')
                                            : '—'
                                        }}

                                    </td>


                                    {{-- Subject --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $decision->subject }}

                                        </div>


                                        @if($decision->reference_number)

                                            <div class="text-muted small">

                                                Ref:
                                                {{ $decision->reference_number }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Decision Type --}}

                                    <td>

                                        {{ $decision->decision_type }}

                                    </td>


                                    {{-- Decision Maker --}}

                                    <td>

                                        @if($decision->decisionMaker)

                                            <div class="fw-semibold">

                                                {{ $decision->decisionMaker->name }}

                                            </div>

                                        @endif


                                        @if($decision->decision_maker_role)

                                            <div class="text-muted small">

                                                {{ $decision->decision_maker_role }}

                                            </div>

                                        @elseif(!$decision->decisionMaker)

                                            <span class="text-muted">
                                                Not specified
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Priority --}}

                                    <td>

                                        <span
                                            class="badge {{ $priorityClass }}"
                                        >
                                            {{ $decision->priority }}
                                        </span>

                                    </td>


                                    {{-- Implementation --}}

                                    <td>

                                        @if(
                                            $decision->implementation_required
                                        )

                                            <span class="badge bg-info text-dark">
                                                Required
                                            </span>


                                            @if(
                                                $decision->implementation_due_date
                                            )

                                                <div class="text-muted small mt-1">

                                                    Due:
                                                    {{
                                                        $decision
                                                            ->implementation_due_date
                                                            ->format('d-m-Y')
                                                    }}

                                                </div>

                                            @endif

                                        @else

                                            <span
                                                class="badge bg-light text-dark border"
                                            >
                                                Not Required
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $decision->status }}
                                        </span>

                                    </td>


                                    {{-- Actions --}}

                                    <td class="text-end pe-3">

                                        <div
                                            class="d-flex justify-content-end gap-1"
                                        >

                                            <a
                                                href="{{ route(
                                                    'admin.projects.decision-register.show',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'decision' =>
                                                            $decision->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>


                                            <a
                                                href="{{ route(
                                                    'admin.projects.decision-register.edit',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'decision' =>
                                                            $decision->id,
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

                    <h5>
                        No Decisions Registered
                    </h5>

                    <div class="text-muted mb-3">

                        No project governance decisions have been
                        recorded yet.

                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.decision-register.create',
                            [
                                'project' => $project->id,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Register First Decision
                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DECISION REGISTER INFORMATION --}}
    {{-- ========================================================= --}}

    <div class="card mt-4 mb-5">

        <div class="card-header">

            <strong>
                Decision Register Purpose
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">

                    <div class="fw-semibold mb-1">
                        Decision
                    </div>

                    <div class="text-muted small">
                        Records the formal decision made by
                        project authority.
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="fw-semibold mb-1">
                        Rationale
                    </div>

                    <div class="text-muted small">
                        Captures why the decision was made and
                        the underlying reasoning.
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="fw-semibold mb-1">
                        Impact
                    </div>

                    <div class="text-muted small">
                        Records financial, schedule and other
                        project impacts.
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="fw-semibold mb-1">
                        Implementation
                    </div>

                    <div class="text-muted small">
                        Tracks whether the decision requires
                        implementation and who owns it.
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection