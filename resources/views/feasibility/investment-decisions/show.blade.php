@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Investment Decision
            </h3>

            <p class="text-muted mb-0">
                {{ $investmentDecision->decision_number }}
                -
                {{ $investmentDecision->title }}
            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.investment-decisions.index',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                    ]
                ) }}"
                class="btn btn-secondary"
            >
                ← Back
            </a>


            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.investment-decisions.edit',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'investmentDecision' =>
                            $investmentDecision->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Success Message --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Decision Summary --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Decision Summary
            </strong>


            @switch($investmentDecision->status)

                @case('Draft')

                    <span class="badge bg-secondary">
                        Draft
                    </span>

                    @break

                @case('Submitted')

                    <span class="badge bg-warning text-dark">
                        Submitted
                    </span>

                    @break

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

                @default

                    <span class="badge bg-secondary">
                        {{ $investmentDecision->status }}
                    </span>

            @endswitch

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Decision Number --}}

                <div class="col-md-3 mb-3">

                    <label class="text-muted small">
                        Decision Number
                    </label>

                    <div class="fw-bold">
                        {{ $investmentDecision->decision_number }}
                    </div>

                </div>


                {{-- Title --}}

                <div class="col-md-5 mb-3">

                    <label class="text-muted small">
                        Title
                    </label>

                    <div class="fw-bold">
                        {{ $investmentDecision->title }}
                    </div>

                </div>


                {{-- Decision --}}

                <div class="col-md-2 mb-3">

                    <label class="text-muted small">
                        Final Decision
                    </label>

                    <div>

                        @if(
                            $investmentDecision->decision === 'Go'
                        )

                            <span class="badge bg-success fs-6">
                                Go
                            </span>

                        @elseif(
                            $investmentDecision->decision ===
                            'Conditional Go'
                        )

                            <span class="badge bg-warning text-dark fs-6">
                                Conditional Go
                            </span>

                        @elseif(
                            $investmentDecision->decision ===
                            'No-Go'
                        )

                            <span class="badge bg-danger fs-6">
                                No-Go
                            </span>

                        @elseif(
                            $investmentDecision->decision ===
                            'Defer'
                        )

                            <span class="badge bg-secondary fs-6">
                                Defer
                            </span>

                        @elseif(
                            $investmentDecision->decision
                        )

                            <span class="badge bg-secondary fs-6">
                                {{ $investmentDecision->decision }}
                            </span>

                        @else

                            -

                        @endif

                    </div>

                </div>


                {{-- Decision Date --}}

                <div class="col-md-2 mb-3">

                    <label class="text-muted small">
                        Decision Date
                    </label>

                    <div class="fw-bold">

                        @if(
                            $investmentDecision->decision_date
                        )

                            {{
                                $investmentDecision
                                    ->decision_date
                                    ->format('d M Y')
                            }}

                        @else

                            -

                        @endif

                    </div>

                </div>


                {{-- Recommendation --}}

                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Investment Recommendation
                    </label>

                    <div class="fw-bold">
                        {{
                            $investmentDecision
                                ->investment_recommendation
                            ?? '-'
                        }}
                    </div>

                </div>


                {{-- Priority --}}

                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Investment Priority
                    </label>

                    <div>

                        @if(
                            $investmentDecision
                                ->investment_priority === 'High'
                        )

                            <span class="badge bg-danger">
                                High
                            </span>

                        @elseif(
                            $investmentDecision
                                ->investment_priority === 'Medium'
                        )

                            <span class="badge bg-warning text-dark">
                                Medium
                            </span>

                        @elseif(
                            $investmentDecision
                                ->investment_priority === 'Low'
                        )

                            <span class="badge bg-success">
                                Low
                            </span>

                        @elseif(
                            $investmentDecision
                                ->investment_priority
                        )

                            <span class="badge bg-secondary">
                                {{
                                    $investmentDecision
                                        ->investment_priority
                                }}
                            </span>

                        @else

                            -

                        @endif

                    </div>

                </div>


                {{-- Overall Score --}}

                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Overall Score
                    </label>

                    <div class="fs-4 fw-bold">

                        @if(
                            $investmentDecision->overall_score
                            !== null
                        )

                            {{
                                number_format(
                                    $investmentDecision
                                        ->overall_score,
                                    2
                                )
                            }}

                            <small class="text-muted">
                                / 100
                            </small>

                        @else

                            -

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Feasibility Scores --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Feasibility Assessment Scores
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                @php

                    $scoreFields = [

                        'market_score' =>
                            'Market',

                        'location_score' =>
                            'Location',

                        'financial_score' =>
                            'Financial',

                        'technical_score' =>
                            'Technical',

                        'environmental_score' =>
                            'Environmental',

                        'legal_score' =>
                            'Legal',

                        'risk_score' =>
                            'Risk',

                        'investment_score' =>
                            'Investment',

                    ];

                @endphp


                @foreach(
                    $scoreFields as $field => $label
                )

                    <div class="col-md-3 mb-4">

                        <div class="border rounded p-3">

                            <div class="text-muted small mb-1">
                                {{ $label }}
                            </div>

                            <div class="fs-5 fw-bold">

                                @if(
                                    $investmentDecision->{$field}
                                    !== null
                                )

                                    {{
                                        number_format(
                                            $investmentDecision
                                                ->{$field},
                                            2
                                        )
                                    }}

                                    <small class="text-muted">
                                        /100
                                    </small>

                                @else

                                    -

                                @endif

                            </div>

                        </div>

                    </div>

                @endforeach


                <div class="col-md-4 mb-3">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Overall Score
                        </div>

                        <div class="fs-3 fw-bold">

                            @if(
                                $investmentDecision
                                    ->overall_score !== null
                            )

                                {{
                                    number_format(
                                        $investmentDecision
                                            ->overall_score,
                                        2
                                    )
                                }}

                                <small class="text-muted">
                                    /100
                                </small>

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Investment Summary --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Investment Summary
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Recommended Investment --}}

                <div class="col-md-3 mb-4">

                    <label class="text-muted small">
                        Recommended Investment
                    </label>

                    <div class="fs-5 fw-bold">

                        @if(
                            $investmentDecision
                                ->recommended_investment
                            !== null
                        )

                            ₹{{
                                number_format(
                                    $investmentDecision
                                        ->recommended_investment,
                                    2
                                )
                            }}

                        @else

                            -

                        @endif

                    </div>

                </div>


                {{-- Approved Investment --}}

                <div class="col-md-3 mb-4">

                    <label class="text-muted small">
                        Approved Investment
                    </label>

                    <div class="fs-5 fw-bold text-success">

                        @if(
                            $investmentDecision
                                ->approved_investment
                            !== null
                        )

                            ₹{{
                                number_format(
                                    $investmentDecision
                                        ->approved_investment,
                                    2
                                )
                            }}

                        @else

                            -

                        @endif

                    </div>

                </div>


                {{-- ROI --}}

                <div class="col-md-3 mb-4">

                    <label class="text-muted small">
                        Expected ROI
                    </label>

                    <div class="fs-5 fw-bold">

                        @if(
                            $investmentDecision->expected_roi
                            !== null
                        )

                            {{
                                number_format(
                                    $investmentDecision
                                        ->expected_roi,
                                    2
                                )
                            }}%

                        @else

                            -

                        @endif

                    </div>

                </div>


                {{-- IRR --}}

                <div class="col-md-3 mb-4">

                    <label class="text-muted small">
                        Expected IRR
                    </label>

                    <div class="fs-5 fw-bold">

                        @if(
                            $investmentDecision->expected_irr
                            !== null
                        )

                            {{
                                number_format(
                                    $investmentDecision
                                        ->expected_irr,
                                    2
                                )
                            }}%

                        @else

                            -

                        @endif

                    </div>

                </div>


                {{-- NPV --}}

                <div class="col-md-3 mb-4">

                    <label class="text-muted small">
                        Expected NPV
                    </label>

                    <div class="fs-5 fw-bold">

                        @if(
                            $investmentDecision->expected_npv
                            !== null
                        )

                            ₹{{
                                number_format(
                                    $investmentDecision
                                        ->expected_npv,
                                    2
                                )
                            }}

                        @else

                            -

                        @endif

                    </div>

                </div>


                {{-- Payback --}}

                <div class="col-md-3 mb-4">

                    <label class="text-muted small">
                        Expected Payback Period
                    </label>

                    <div class="fs-5 fw-bold">

                        @if(
                            $investmentDecision
                                ->expected_payback_period
                            !== null
                        )

                            {{
                                number_format(
                                    $investmentDecision
                                        ->expected_payback_period,
                                    2
                                )
                            }}
                            Years

                        @else

                            -

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Decision Conditions --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Decision Conditions
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                @php

                    $conditionFields = [

                        'approval_conditions' =>
                            'Approval Conditions',

                        'pre_investment_conditions' =>
                            'Pre-Investment Conditions',

                        'risk_conditions' =>
                            'Risk Conditions',

                        'financial_conditions' =>
                            'Financial Conditions',

                        'legal_conditions' =>
                            'Legal Conditions',

                        'technical_conditions' =>
                            'Technical Conditions',

                    ];

                @endphp


                @foreach(
                    $conditionFields as $field => $label
                )

                    <div class="col-md-6 mb-4">

                        <label class="text-muted small">
                            {{ $label }}
                        </label>

                        <div class="border rounded p-3 bg-light">

                            {!! nl2br(e(
                                $investmentDecision->{$field}
                                ?? '-'
                            )) !!}

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Decision Rationale --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Decision Rationale
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                @php

                    $rationaleFields = [

                        'key_strengths' =>
                            'Key Strengths',

                        'key_weaknesses' =>
                            'Key Weaknesses',

                        'key_opportunities' =>
                            'Key Opportunities',

                        'key_risks' =>
                            'Key Risks',

                    ];

                @endphp


                @foreach(
                    $rationaleFields as $field => $label
                )

                    <div class="col-md-6 mb-4">

                        <label class="text-muted small">
                            {{ $label }}
                        </label>

                        <div class="border rounded p-3 bg-light">

                            {!! nl2br(e(
                                $investmentDecision->{$field}
                                ?? '-'
                            )) !!}

                        </div>

                    </div>

                @endforeach


                <div class="col-md-12 mb-3">

                    <label class="text-muted small">
                        Decision Rationale
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $investmentDecision
                                ->decision_rationale
                            ?? '-'
                        )) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Investment Committee --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Investment Committee
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Committee Name
                    </label>

                    <div class="fw-bold">
                        {{
                            $investmentDecision
                                ->committee_name
                            ?? '-'
                        }}
                    </div>

                </div>


                <div class="col-md-8 mb-3">

                    <label class="text-muted small">
                        Committee Members
                    </label>

                    <div class="fw-bold">
                        {{
                            $investmentDecision
                                ->committee_members
                            ?? '-'
                        }}
                    </div>

                </div>


                <div class="col-md-12">

                    <label class="text-muted small">
                        Committee Notes
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $investmentDecision
                                ->committee_notes
                            ?? '-'
                        )) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Final Recommendation --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Final Recommendation
            </strong>

        </div>


        <div class="card-body">

            <div class="mb-4">

                <label class="text-muted small">
                    Final Recommendation
                </label>

                <div class="border rounded p-3 bg-light">

                    {!! nl2br(e(
                        $investmentDecision
                            ->final_recommendation
                        ?? '-'
                    )) !!}

                </div>

            </div>


            <div>

                <label class="text-muted small">
                    Management Comments
                </label>

                <div class="border rounded p-3 bg-light">

                    {!! nl2br(e(
                        $investmentDecision
                            ->management_comments
                        ?? '-'
                    )) !!}

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Actions --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between mb-5">

        <a
            href="{{ route(
                'admin.land.lands.feasibility-assessments.investment-decisions.index',
                [
                    'land' => $land->id,
                    'feasibilityAssessment' =>
                        $feasibilityAssessment->id,
                ]
            ) }}"
            class="btn btn-secondary"
        >
            ← Back to Investment Decisions
        </a>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.investment-decisions.edit',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'investmentDecision' =>
                            $investmentDecision->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>


            <form
                action="{{ route(
                    'admin.land.lands.feasibility-assessments.investment-decisions.destroy',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'investmentDecision' =>
                            $investmentDecision->id,
                    ]
                ) }}"
                method="POST"
                onsubmit="return confirm('Are you sure you want to delete this Investment Decision?');"
            >

                @csrf

                @method('DELETE')

                <button
                    type="submit"
                    class="btn btn-danger"
                >
                    Delete
                </button>

            </form>

        </div>

    </div>

</div>

@endsection