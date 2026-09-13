@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Project / Development Strategy
            </div>

            <h3 class="mb-1">
                Development Strategy
            </h3>

            <div class="text-muted">
                {{ $project->project_name }}
                · {{ $project->project_number }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.show',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Project
            </a>

            @if(!$developmentStrategy)

                <a
                    href="{{ route(
                        'admin.projects.development-strategy.create',
                        ['project' => $project->id]
                    ) }}"
                    class="btn btn-primary"
                >
                    + Create Development Strategy
                </a>

            @endif

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


    @if(session('info'))

        <div class="alert alert-info">
            {{ session('info') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Project Context --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Project Context
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Project
                    </div>

                    <div class="fw-semibold">
                        {{ $project->project_name }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Project Number
                    </div>

                    <div class="fw-semibold">
                        {{ $project->project_number }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Project Status
                    </div>

                    <div class="mt-1">

                        @if($project->project_status === 'Active')

                            <span class="badge bg-success">
                                Active
                            </span>

                        @elseif($project->project_status === 'On Hold')

                            <span class="badge bg-warning text-dark">
                                On Hold
                            </span>

                        @elseif($project->project_status === 'Cancelled')

                            <span class="badge bg-danger">
                                Cancelled
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                {{ $project->project_status }}
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Strategy Status --}}
    {{-- ========================================================= --}}

    @if($developmentStrategy)

        <div class="card mb-4">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center">

                    <strong>
                        Development Strategy
                    </strong>

                    <div class="d-flex gap-2">

                        <a
                            href="{{ route(
                                'admin.projects.development-strategy.show',
                                [
                                    'project' =>
                                        $project->id,

                                    'developmentStrategy' =>
                                        $developmentStrategy->id,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            View
                        </a>


                        <a
                            href="{{ route(
                                'admin.projects.development-strategy.edit',
                                [
                                    'project' =>
                                        $project->id,

                                    'developmentStrategy' =>
                                        $developmentStrategy->id,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-secondary"
                        >
                            Edit
                        </a>


                        @if(
                            $developmentStrategy->status === 'Draft'
                        )

                            <form
                                method="POST"
                                action="{{ route(
                                    'admin.projects.development-strategy.destroy',
                                    [
                                        'project' =>
                                            $project->id,

                                        'developmentStrategy' =>
                                            $developmentStrategy->id,
                                    ]
                                ) }}"
                                onsubmit="return confirm('Are you sure you want to delete this Development Strategy?');"
                            >

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-sm btn-outline-danger"
                                >
                                    Delete
                                </button>

                            </form>

                        @endif

                    </div>

                </div>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    {{-- Strategy Number --}}

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Strategy Number
                        </div>

                        <div class="fw-semibold mt-1">
                            {{ $developmentStrategy->strategy_number }}
                        </div>

                    </div>


                    {{-- Title --}}

                    <div class="col-md-5">

                        <div class="text-muted small">
                            Title
                        </div>

                        <div class="fw-semibold mt-1">
                            {{ $developmentStrategy->title }}
                        </div>

                    </div>


                    {{-- Status --}}

                    <div class="col-md-2">

                        <div class="text-muted small">
                            Status
                        </div>

                        <div class="mt-1">

                            @switch($developmentStrategy->status)

                                @case('Approved')

                                    <span class="badge bg-success">
                                        Approved
                                    </span>

                                    @break

                                @case('Submitted')

                                    <span class="badge bg-info text-dark">
                                        Submitted
                                    </span>

                                    @break

                                @case('Rejected')

                                    <span class="badge bg-danger">
                                        Rejected
                                    </span>

                                    @break

                                @case('Under Review')

                                    <span class="badge bg-warning text-dark">
                                        Under Review
                                    </span>

                                    @break

                                @default

                                    <span class="badge bg-secondary">
                                        {{ $developmentStrategy->status }}
                                    </span>

                            @endswitch

                        </div>

                    </div>


                    {{-- Date --}}

                    <div class="col-md-2">

                        <div class="text-muted small">
                            Strategy Date
                        </div>

                        <div class="fw-semibold mt-1">

                            {{
                                $developmentStrategy->strategy_date
                                    ? $developmentStrategy
                                        ->strategy_date
                                        ->format('d M Y')
                                    : '-'
                            }}

                        </div>

                    </div>

                </div>


                <hr>


                {{-- Vision --}}

                <div class="mb-4">

                    <h6>
                        Development Vision
                    </h6>

                    <div class="text-muted">

                        @if($developmentStrategy->development_vision)

                            {!! nl2br(
                                e(
                                    $developmentStrategy
                                        ->development_vision
                                )
                            ) !!}

                        @else

                            <span class="text-muted">
                                Not defined.
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Concept --}}

                <div class="mb-4">

                    <h6>
                        Development Concept
                    </h6>

                    <div class="text-muted">

                        @if($developmentStrategy->development_concept)

                            {!! nl2br(
                                e(
                                    $developmentStrategy
                                        ->development_concept
                                )
                            ) !!}

                        @else

                            <span class="text-muted">
                                Not defined.
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Objectives --}}

                <div>

                    <h6>
                        Development Objectives
                    </h6>

                    <div class="text-muted">

                        @if($developmentStrategy->development_objectives)

                            {!! nl2br(
                                e(
                                    $developmentStrategy
                                        ->development_objectives
                                )
                            ) !!}

                        @else

                            <span class="text-muted">
                                Not defined.
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Strategy Details --}}
        {{-- ===================================================== --}}

        <div class="row g-4 mb-4">

            {{-- Development Approach --}}

            <div class="col-md-6">

                <div class="card h-100">

                    <div class="card-header">

                        <strong>
                            Development Approach
                        </strong>

                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <div class="text-muted small">
                                Development Type
                            </div>

                            <div class="fw-semibold">
                                {{
                                    $developmentStrategy
                                        ->development_type
                                    ?? '-'
                                }}
                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="text-muted small">
                                Development Model
                            </div>

                            <div class="fw-semibold">
                                {{
                                    $developmentStrategy
                                        ->development_model
                                    ?? '-'
                                }}
                            </div>

                        </div>


                        <div>

                            <div class="text-muted small">
                                Development Approach
                            </div>

                            <div class="text-muted mt-1">

                                {{
                                    $developmentStrategy
                                        ->development_approach
                                    ?? '-'
                                }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Market Positioning --}}

            <div class="col-md-6">

                <div class="card h-100">

                    <div class="card-header">

                        <strong>
                            Market Positioning
                        </strong>

                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <div class="text-muted small">
                                Target Market
                            </div>

                            <div class="text-muted mt-1">

                                {{
                                    $developmentStrategy
                                        ->target_market
                                    ?? '-'
                                }}

                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="text-muted small">
                                Market Positioning
                            </div>

                            <div class="text-muted mt-1">

                                {{
                                    $developmentStrategy
                                        ->market_positioning
                                    ?? '-'
                                }}

                            </div>

                        </div>


                        <div>

                            <div class="text-muted small">
                                Competitive Strategy
                            </div>

                            <div class="text-muted mt-1">

                                {{
                                    $developmentStrategy
                                        ->competitive_strategy
                                    ?? '-'
                                }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Development Mix --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Development Mix & Area
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Planned GLA
                        </div>

                        <div class="fs-5 fw-semibold">
                            {{
                                $developmentStrategy->planned_gla
                                ?? '-'
                            }}
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Planned NLA
                        </div>

                        <div class="fs-5 fw-semibold">
                            {{
                                $developmentStrategy->planned_nla
                                ?? '-'
                            }}
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Planned Leasable Area
                        </div>

                        <div class="fs-5 fw-semibold">
                            {{
                                $developmentStrategy
                                    ->planned_leasable_area
                                ?? '-'
                            }}
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Development Mix
                        </div>

                        <div class="text-muted mt-1">

                            {{
                                $developmentStrategy
                                    ->development_mix
                                ?? '-'
                            }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Strategic Considerations --}}
        {{-- ===================================================== --}}

        <div class="row g-4 mb-4">

            <div class="col-md-6">

                <div class="card h-100">

                    <div class="card-header">

                        <strong>
                            Strategic Assumptions
                        </strong>

                    </div>

                    <div class="card-body text-muted">

                        {{
                            $developmentStrategy
                                ->key_assumptions
                            ?? '-'
                        }}

                    </div>

                </div>

            </div>


            <div class="col-md-6">

                <div class="card h-100">

                    <div class="card-header">

                        <strong>
                            Strategic Constraints
                        </strong>

                    </div>

                    <div class="card-body text-muted">

                        {{
                            $developmentStrategy
                                ->strategic_constraints
                            ?? '-'
                        }}

                    </div>

                </div>

            </div>


            <div class="col-md-6">

                <div class="card h-100">

                    <div class="card-header">

                        <strong>
                            Key Opportunities
                        </strong>

                    </div>

                    <div class="card-body text-muted">

                        {{
                            $developmentStrategy
                                ->key_opportunities
                            ?? '-'
                        }}

                    </div>

                </div>

            </div>


            <div class="col-md-6">

                <div class="card h-100">

                    <div class="card-header">

                        <strong>
                            Key Challenges
                        </strong>

                    </div>

                    <div class="card-body text-muted">

                        {{
                            $developmentStrategy
                                ->key_challenges
                            ?? '-'
                        }}

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Recommendation --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Recommended Development Strategy
                </strong>

            </div>

            <div class="card-body">

                <div class="mb-4">

                    <h6>
                        Recommended Strategy
                    </h6>

                    <div class="text-muted">

                        {{
                            $developmentStrategy
                                ->recommended_strategy
                            ?? '-'
                        }}

                    </div>

                </div>


                <div>

                    <h6>
                        Strategic Rationale
                    </h6>

                    <div class="text-muted">

                        {{
                            $developmentStrategy
                                ->strategic_rationale
                            ?? '-'
                        }}

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Approval --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Strategy Approval
                </strong>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <div class="text-muted small">
                            Status
                        </div>

                        <div class="mt-1">

                            <span class="badge bg-secondary">
                                {{ $developmentStrategy->status }}
                            </span>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Strategy Date
                        </div>

                        <div class="fw-semibold">

                            {{
                                $developmentStrategy->strategy_date
                                    ? $developmentStrategy
                                        ->strategy_date
                                        ->format('d M Y')
                                    : '-'
                            }}

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Approval Date
                        </div>

                        <div class="fw-semibold">

                            {{
                                $developmentStrategy->approval_date
                                    ? $developmentStrategy
                                        ->approval_date
                                        ->format('d M Y')
                                    : '-'
                            }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Remarks --}}
        {{-- ===================================================== --}}

        @if($developmentStrategy->remarks)

            <div class="card mb-5">

                <div class="card-header">

                    <strong>
                        Remarks
                    </strong>

                </div>

                <div class="card-body text-muted">

                    {!! nl2br(
                        e(
                            $developmentStrategy->remarks
                        )
                    ) !!}

                </div>

            </div>

        @endif


    @else

        {{-- ===================================================== --}}
        {{-- No Strategy --}}
        {{-- ===================================================== --}}

        <div class="card">

            <div class="card-body text-center py-5">

                <h5>
                    Development Strategy Not Created
                </h5>

                <p class="text-muted mb-4">

                    This project does not have a Development Strategy
                    yet.

                </p>


                <a
                    href="{{ route(
                        'admin.projects.development-strategy.create',
                        ['project' => $project->id]
                    ) }}"
                    class="btn btn-primary"
                >
                    + Create Development Strategy
                </a>

            </div>

        </div>

    @endif

</div>

@endsection