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
                {{ $developmentStrategy->title }}
            </h3>

            <div class="text-muted">

                {{ $developmentStrategy->strategy_number }}

                ·

                {{ $project->project_name }}

                ·

                {{ $project->project_number }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.development-strategy.edit',
                    [
                        'project' => $project->id,
                        'developmentStrategy' =>
                            $developmentStrategy->id,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                Edit Strategy
            </a>


            <a
                href="{{ route(
                    'admin.projects.development-strategy.index',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Strategy
            </a>


            <a
                href="{{ route(
                    'admin.projects.show',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Project
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
    {{-- Strategy Summary --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Strategy Number
                    </div>

                    <div class="fw-semibold mt-2">
                        {{ $developmentStrategy->strategy_number }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Development Type
                    </div>

                    <div class="fw-semibold mt-2">
                        {{ $developmentStrategy->development_type ?? '-' }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Development Model
                    </div>

                    <div class="fw-semibold mt-2">
                        {{ $developmentStrategy->development_model ?? '-' }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Status
                    </div>

                    <div class="mt-2">

                        @switch($developmentStrategy->status)

                            @case('Approved')

                                <span class="badge bg-success fs-6">
                                    Approved
                                </span>

                                @break

                            @case('Submitted')

                                <span class="badge bg-info text-dark fs-6">
                                    Submitted
                                </span>

                                @break

                            @case('Under Review')

                                <span class="badge bg-warning text-dark fs-6">
                                    Under Review
                                </span>

                                @break

                            @case('Rejected')

                                <span class="badge bg-danger fs-6">
                                    Rejected
                                </span>

                                @break

                            @default

                                <span class="badge bg-secondary fs-6">
                                    {{ $developmentStrategy->status }}
                                </span>

                        @endswitch

                    </div>

                </div>

            </div>

        </div>

    </div>


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
                        Project Stage
                    </div>

                    <div class="fw-semibold">
                        {{ $project->project_stage }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Vision & Concept --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Development Vision & Concept
            </strong>

        </div>


        <div class="card-body">

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

                        -

                    @endif

                </div>

            </div>


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

                        -

                    @endif

                </div>

            </div>


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

                        -

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Development Approach --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Development Approach
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Development Type
                    </div>

                    <div class="fw-semibold mt-1">
                        {{ $developmentStrategy->development_type ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Development Model
                    </div>

                    <div class="fw-semibold mt-1">
                        {{ $developmentStrategy->development_model ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4">

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


            <h6>
                Development Approach
            </h6>

            <div class="text-muted">

                @if($developmentStrategy->development_approach)

                    {!! nl2br(
                        e(
                            $developmentStrategy
                                ->development_approach
                        )
                    ) !!}

                @else

                    -

                @endif

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Market Positioning --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Market Positioning
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <h6>
                        Target Market
                    </h6>

                    <div class="text-muted">

                        @if($developmentStrategy->target_market)

                            {!! nl2br(
                                e(
                                    $developmentStrategy
                                        ->target_market
                                )
                            ) !!}

                        @else

                            -

                        @endif

                    </div>

                </div>


                <div class="col-md-4">

                    <h6>
                        Market Positioning
                    </h6>

                    <div class="text-muted">

                        @if($developmentStrategy->market_positioning)

                            {!! nl2br(
                                e(
                                    $developmentStrategy
                                        ->market_positioning
                                )
                            ) !!}

                        @else

                            -

                        @endif

                    </div>

                </div>


                <div class="col-md-4">

                    <h6>
                        Competitive Strategy
                    </h6>

                    <div class="text-muted">

                        @if($developmentStrategy->competitive_strategy)

                            {!! nl2br(
                                e(
                                    $developmentStrategy
                                        ->competitive_strategy
                                )
                            ) !!}

                        @else

                            -

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Development Mix --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Development Mix & Area
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4 mb-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Planned GLA
                    </div>

                    <div class="fs-5 fw-semibold">
                        {{ $developmentStrategy->planned_gla ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Planned NLA
                    </div>

                    <div class="fs-5 fw-semibold">
                        {{ $developmentStrategy->planned_nla ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4">

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

            </div>


            <h6>
                Development Mix
            </h6>

            <div class="text-muted">

                @if($developmentStrategy->development_mix)

                    {!! nl2br(
                        e(
                            $developmentStrategy
                                ->development_mix
                        )
                    ) !!}

                @else

                    -

                @endif

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Strategic Considerations --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">

        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header">

                    <strong>
                        Key Assumptions
                    </strong>

                </div>

                <div class="card-body text-muted">

                    @if($developmentStrategy->key_assumptions)

                        {!! nl2br(
                            e(
                                $developmentStrategy
                                    ->key_assumptions
                            )
                        ) !!}

                    @else

                        -

                    @endif

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

                    @if($developmentStrategy->strategic_constraints)

                        {!! nl2br(
                            e(
                                $developmentStrategy
                                    ->strategic_constraints
                            )
                        ) !!}

                    @else

                        -

                    @endif

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

                    @if($developmentStrategy->key_opportunities)

                        {!! nl2br(
                            e(
                                $developmentStrategy
                                    ->key_opportunities
                            )
                        ) !!}

                    @else

                        -

                    @endif

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

                    @if($developmentStrategy->key_challenges)

                        {!! nl2br(
                            e(
                                $developmentStrategy
                                    ->key_challenges
                            )
                        ) !!}

                    @else

                        -

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Recommendation --}}
    {{-- ========================================================= --}}

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

                    @if($developmentStrategy->recommended_strategy)

                        {!! nl2br(
                            e(
                                $developmentStrategy
                                    ->recommended_strategy
                            )
                        ) !!}

                    @else

                        -

                    @endif

                </div>

            </div>


            <div>

                <h6>
                    Strategic Rationale
                </h6>

                <div class="text-muted">

                    @if($developmentStrategy->strategic_rationale)

                        {!! nl2br(
                            e(
                                $developmentStrategy
                                    ->strategic_rationale
                            )
                        ) !!}

                    @else

                        -

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Approval --}}
    {{-- ========================================================= --}}

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

                        @switch($developmentStrategy->status)

                            @case('Approved')

                                <span class="badge bg-success">
                                    Approved
                                </span>

                                @break

                            @case('Rejected')

                                <span class="badge bg-danger">
                                    Rejected
                                </span>

                                @break

                            @case('Submitted')

                                <span class="badge bg-info text-dark">
                                    Submitted
                                </span>

                                @break

                            @default

                                <span class="badge bg-secondary">
                                    {{ $developmentStrategy->status }}
                                </span>

                        @endswitch

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


    {{-- ========================================================= --}}
    {{-- Remarks --}}
    {{-- ========================================================= --}}

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

</div>

@endsection