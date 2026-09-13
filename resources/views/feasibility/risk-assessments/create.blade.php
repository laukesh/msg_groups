@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3>Create Risk Assessment</h3>

            <p class="text-muted mb-0">
                {{ $feasibilityAssessment->assessment_number }}
                -
                {{ $feasibilityAssessment->title }}
            </p>
        </div>

        <a
            href="{{ route(
                'admin.land.lands.feasibility-assessments.risk-assessments.index',
                [
                    'land' => $land->id,
                    'feasibilityAssessment' => $feasibilityAssessment->id,
                ]
            ) }}"
            class="btn btn-secondary"
        >
            ← Back
        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'admin.land.lands.feasibility-assessments.risk-assessments.store',
            [
                'land' => $land->id,
                'feasibilityAssessment' => $feasibilityAssessment->id,
            ]
        ) }}"
    >

        @csrf


        {{-- ========================================================= --}}
        {{-- Basic Information --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Basic Information</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Assessment Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="Auto Generated"
                            readonly
                        >

                    </div>


                    <div class="col-md-5 mb-3">

                        <label class="form-label">
                            Title <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            value="{{ old('title') }}"
                            required
                        >

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Overall Risk Rating
                        </label>

                        <select
                            name="overall_risk_rating"
                            class="form-select"
                        >

                            <option value="">
                                Select
                            </option>

                            @foreach([
                                'Low',
                                'Medium',
                                'High',
                                'Critical'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        old('overall_risk_rating') === $value
                                    )
                                >
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Overall Risk Score
                        </label>

                        <input
                            type="number"
                            name="overall_risk_score"
                            class="form-control"
                            value="{{ old('overall_risk_score') }}"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Risk Summary
                        </label>

                        <textarea
                            name="risk_summary"
                            class="form-control"
                            rows="3"
                        >{{ old('risk_summary') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Risk Category Macro --}}
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


        {{-- ========================================================= --}}
        {{-- Risk Categories --}}
        {{-- ========================================================= --}}

        @foreach($riskCategories as $category)

            @php
                $key = $category['key'];
            @endphp

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        {{ $category['title'] }}
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Risk Rating
                            </label>

                            <select
                                name="{{ $key }}_risk_rating"
                                class="form-select"
                            >

                                <option value="">
                                    Select Rating
                                </option>

                                @foreach([
                                    'Low',
                                    'Medium',
                                    'High',
                                    'Critical'
                                ] as $rating)

                                    <option
                                        value="{{ $rating }}"
                                        @selected(
                                            old(
                                                $key . '_risk_rating'
                                            ) === $rating
                                        )
                                    >
                                        {{ $rating }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Risk Score
                            </label>

                            <input
                                type="number"
                                name="{{ $key }}_risk_score"
                                class="form-control"
                                value="{{ old(
                                    $key . '_risk_score'
                                ) }}"
                                min="0"
                                step="0.01"
                            >

                        </div>


                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Risk Level
                            </label>

                            <div class="form-control bg-light">

                                <span
                                    class="text-muted"
                                    id="{{ $key }}_risk_level"
                                >
                                    Based on assessment
                                </span>

                            </div>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Risk Details
                            </label>

                            <textarea
                                name="{{ $key }}_risk_details"
                                class="form-control"
                                rows="5"
                            >{{ old(
                                $key . '_risk_details'
                            ) }}</textarea>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Mitigation Measures
                            </label>

                            <textarea
                                name="{{ $key }}_risk_mitigation"
                                class="form-control"
                                rows="5"
                            >{{ old(
                                $key . '_risk_mitigation'
                            ) }}</textarea>

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

                        <label class="form-label">
                            Key Risks
                        </label>

                        <textarea
                            name="key_risks"
                            class="form-control"
                            rows="6"
                        >{{ old('key_risks') }}</textarea>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Critical Risks
                        </label>

                        <textarea
                            name="critical_risks"
                            class="form-control"
                            rows="6"
                        >{{ old('critical_risks') }}</textarea>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Risk Priorities
                        </label>

                        <textarea
                            name="risk_priorities"
                            class="form-control"
                            rows="6"
                        >{{ old('risk_priorities') }}</textarea>

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

                        <label class="form-label">
                            Mitigation Strategy
                        </label>

                        <textarea
                            name="mitigation_strategy"
                            class="form-control"
                            rows="6"
                        >{{ old('mitigation_strategy') }}</textarea>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Contingency Plan
                        </label>

                        <textarea
                            name="contingency_plan"
                            class="form-control"
                            rows="6"
                        >{{ old('contingency_plan') }}</textarea>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Risk Monitoring Plan
                        </label>

                        <textarea
                            name="risk_monitoring_plan"
                            class="form-control"
                            rows="6"
                        >{{ old('risk_monitoring_plan') }}</textarea>

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

                        <label class="form-label">
                            Key Risk Findings
                        </label>

                        <textarea
                            name="key_risk_findings"
                            class="form-control"
                            rows="7"
                        >{{ old('key_risk_findings') }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Recommendation
                        </label>

                        <textarea
                            name="recommendation"
                            class="form-control"
                            rows="7"
                        >{{ old('recommendation') }}</textarea>

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
                    'admin.land.lands.feasibility-assessments.risk-assessments.index',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                    ]
                ) }}"
                class="btn btn-secondary"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Create Risk Assessment
            </button>

        </div>

    </form>

</div>

@endsection