@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Risk Assessment
            </h3>

            <p class="text-muted mb-0">

                {{ $riskAssessment->analysis_number }}
                -
                {{ $riskAssessment->title }}

            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.risk-assessments.index',
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
                    'admin.land.lands.feasibility-assessments.risk-assessments.edit',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'riskAssessment' =>
                            $riskAssessment->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Success --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Basic Information --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Risk Assessment Summary
            </strong>


            @switch($riskAssessment->status)

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
                        {{ $riskAssessment->status }}
                    </span>

            @endswitch

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Analysis Number
                    </label>

                    <div class="fw-bold">
                        {{ $riskAssessment->analysis_number }}
                    </div>

                </div>


                <div class="col-md-5 mb-3">

                    <label class="text-muted small">
                        Title
                    </label>

                    <div class="fw-bold">
                        {{ $riskAssessment->title }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted small">
                        Overall Risk
                    </label>

                    <div>

                        @php
                            $rating =
                                $riskAssessment
                                    ->overall_risk_rating;
                        @endphp


                        @if($rating === 'Low')

                            <span class="badge bg-success">
                                Low
                            </span>

                        @elseif($rating === 'Medium')

                            <span class="badge bg-warning text-dark">
                                Medium
                            </span>

                        @elseif($rating === 'High')

                            <span class="badge bg-danger">
                                High
                            </span>

                        @elseif($rating === 'Critical')

                            <span class="badge bg-dark">
                                Critical
                            </span>

                        @elseif($rating)

                            <span class="badge bg-secondary">
                                {{ $rating }}
                            </span>

                        @else

                            -

                        @endif

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Overall Risk Score
                    </label>

                    <div class="fs-5 fw-bold">

                        @if(
                            $riskAssessment->overall_risk_score
                            !== null
                        )

                            {{
                                number_format(
                                    $riskAssessment
                                        ->overall_risk_score,
                                    2
                                )
                            }}

                        @else

                            -

                        @endif

                    </div>

                </div>


                <div class="col-md-8 mb-3">

                    <label class="text-muted small">
                        Risk Summary
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $riskAssessment->risk_summary
                            ?? '-'
                        )) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Risk Categories --}}
    {{-- ========================================================= --}}

    @php

        $riskCategories = [

            [
                'key' => 'market',
                'title' => 'Market Risk',
            ],

            [
                'key' => 'land',
                'title' => 'Land / Property Risk',
            ],

            [
                'key' => 'technical',
                'title' => 'Technical Risk',
            ],

            [
                'key' => 'construction',
                'title' => 'Construction Risk',
            ],

            [
                'key' => 'financial',
                'title' => 'Financial Risk',
            ],

            [
                'key' => 'legal',
                'title' => 'Legal Risk',
            ],

            [
                'key' => 'regulatory',
                'title' => 'Regulatory Risk',
            ],

            [
                'key' => 'environmental',
                'title' => 'Environmental Risk',
            ],

            [
                'key' => 'operational',
                'title' => 'Operational Risk',
            ],

            [
                'key' => 'funding',
                'title' => 'Funding / Financing Risk',
            ],

            [
                'key' => 'execution',
                'title' => 'Execution Risk',
            ],

            [
                'key' => 'schedule',
                'title' => 'Schedule / Timeline Risk',
            ],

            [
                'key' => 'economic',
                'title' => 'Economic Risk',
            ],

            [
                'key' => 'political',
                'title' => 'Political Risk',
            ],

            [
                'key' => 'force_majeure',
                'title' => 'Force Majeure Risk',
            ],

        ];

    @endphp


    @foreach($riskCategories as $category)

        @php

            $key = $category['key'];

            $rating =
                $riskAssessment
                    ->{$key . '_risk_rating'};

            $score =
                $riskAssessment
                    ->{$key . '_risk_score'};

            $details =
                $riskAssessment
                    ->{$key . '_risk_details'};

            $mitigation =
                $riskAssessment
                    ->{$key . '_risk_mitigation'};

        @endphp


        <div class="card mb-4">

            <div class="card-header d-flex justify-content-between align-items-center">

                <strong>
                    {{ $category['title'] }}
                </strong>


                <div>

                    @if($rating === 'Low')

                        <span class="badge bg-success">
                            Low
                        </span>

                    @elseif($rating === 'Medium')

                        <span class="badge bg-warning text-dark">
                            Medium
                        </span>

                    @elseif($rating === 'High')

                        <span class="badge bg-danger">
                            High
                        </span>

                    @elseif($rating === 'Critical')

                        <span class="badge bg-dark">
                            Critical
                        </span>

                    @elseif($rating)

                        <span class="badge bg-secondary">
                            {{ $rating }}
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            Not Rated
                        </span>

                    @endif

                </div>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-3 mb-3">

                        <label class="text-muted small">
                            Risk Rating
                        </label>

                        <div class="fw-bold">

                            {{ $rating ?? '-' }}

                        </div>

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="text-muted small">
                            Risk Score
                        </label>

                        <div class="fw-bold">

                            @if($score !== null)

                                {{ number_format($score, 2) }}

                            @else

                                -

                            @endif

                        </div>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="text-muted small">
                            Risk Details
                        </label>

                        <div class="border rounded p-3 bg-light">

                            {!! nl2br(e(
                                $details ?? '-'
                            )) !!}

                        </div>

                    </div>


                    <div class="col-md-12">

                        <label class="text-muted small">
                            Mitigation Measures
                        </label>

                        <div class="border rounded p-3 bg-light">

                            {!! nl2br(e(
                                $mitigation ?? '-'
                            )) !!}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endforeach


    {{-- ========================================================= --}}
    {{-- Key Risks --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Key Risks & Priorities</strong>
        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Key Risks
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $riskAssessment->key_risks
                            ?? '-'
                        )) !!}

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Critical Risks
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $riskAssessment->critical_risks
                            ?? '-'
                        )) !!}

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Risk Priorities
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $riskAssessment->risk_priorities
                            ?? '-'
                        )) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Mitigation & Contingency --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>
                Mitigation & Contingency Planning
            </strong>
        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Mitigation Strategy
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $riskAssessment
                                ->mitigation_strategy
                            ?? '-'
                        )) !!}

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Contingency Plan
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $riskAssessment
                                ->contingency_plan
                            ?? '-'
                        )) !!}

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Risk Monitoring Plan
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $riskAssessment
                                ->risk_monitoring_plan
                            ?? '-'
                        )) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Findings --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>
                Findings & Recommendation
            </strong>
        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="text-muted small">
                        Key Risk Findings
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $riskAssessment
                                ->key_risk_findings
                            ?? '-'
                        )) !!}

                    </div>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="text-muted small">
                        Recommendation
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $riskAssessment
                                ->recommendation
                            ?? '-'
                        )) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Bottom Actions --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between mb-5">

        <a
            href="{{ route(
                'admin.land.lands.feasibility-assessments.risk-assessments.index',
                [
                    'land' => $land->id,
                    'feasibilityAssessment' =>
                        $feasibilityAssessment->id,
                ]
            ) }}"
            class="btn btn-secondary"
        >
            ← Back to Risk Assessments
        </a>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.risk-assessments.edit',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'riskAssessment' =>
                            $riskAssessment->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>


            <form
                action="{{ route(
                    'admin.land.lands.feasibility-assessments.risk-assessments.destroy',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'riskAssessment' =>
                            $riskAssessment->id,
                    ]
                ) }}"
                method="POST"
                onsubmit="return confirm('Are you sure you want to delete this Risk Assessment?');"
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