@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>Create Investment Decision</h3>

            <p class="text-muted mb-0">
                {{ $feasibilityAssessment->assessment_number }}
                -
                {{ $feasibilityAssessment->title }}
            </p>

        </div>

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

    </div>


    {{-- ========================================================= --}}
    {{-- Validation Errors --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>

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
            'admin.land.lands.feasibility-assessments.investment-decisions.store',
            [
                'land' => $land->id,
                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,
            ]
        ) }}"
    >

        @csrf


        {{-- ========================================================= --}}
        {{-- 1. Decision Information --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Decision Information</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Decision Number
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
                            Title
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            value="{{ old('title') }}"
                            placeholder="Investment decision title"
                            required
                        >

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Draft',
                                'Submitted',
                                'Approved',
                                'Rejected'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        old(
                                            'status',
                                            'Draft'
                                        ) === $value
                                    )
                                >
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Final Decision
                        </label>

                        <select
                            name="decision"
                            class="form-select"
                        >

                            <option value="">
                                Select Decision
                            </option>

                            <option
                                value="Go"
                                @selected(
                                    old('decision') === 'Go'
                                )
                            >
                                Go
                            </option>

                            <option
                                value="Conditional Go"
                                @selected(
                                    old('decision') ===
                                    'Conditional Go'
                                )
                            >
                                Conditional Go
                            </option>

                            <option
                                value="No-Go"
                                @selected(
                                    old('decision') === 'No-Go'
                                )
                            >
                                No-Go
                            </option>

                            <option
                                value="Defer"
                                @selected(
                                    old('decision') === 'Defer'
                                )
                            >
                                Defer
                            </option>

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Decision Date
                        </label>

                        <input
                            type="date"
                            name="decision_date"
                            class="form-control"
                            value="{{ old('decision_date') }}"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Investment Recommendation
                        </label>

                        <select
                            name="investment_recommendation"
                            class="form-select"
                        >

                            <option value="">
                                Select
                            </option>

                            @foreach([
                                'Invest',
                                'Invest with Conditions',
                                'Do Not Invest',
                                'Defer Investment',
                                'Further Evaluation Required'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        old(
                                            'investment_recommendation'
                                        ) === $value
                                    )
                                >
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Investment Priority
                        </label>

                        <select
                            name="investment_priority"
                            class="form-select"
                        >

                            <option value="">
                                Select
                            </option>

                            @foreach([
                                'High',
                                'Medium',
                                'Low'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        old(
                                            'investment_priority'
                                        ) === $value
                                    )
                                >
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- 2. Assessment Scores --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Feasibility Assessment Scores
                </strong>

            </div>


            <div class="card-body">

                <p class="text-muted small">
                    Enter the consolidated score for each feasibility
                    component. Scores must be between 0 and 100.
                </p>


                <div class="row">

                    @php

                        $scoreFields = [

                            'market_score' =>
                                'Market Score',

                            'location_score' =>
                                'Location Score',

                            'financial_score' =>
                                'Financial Score',

                            'technical_score' =>
                                'Technical Score',

                            'environmental_score' =>
                                'Environmental Score',

                            'legal_score' =>
                                'Legal Score',

                            'risk_score' =>
                                'Risk Score',

                            'investment_score' =>
                                'Investment Score',

                        ];

                    @endphp


                    @foreach(
                        $scoreFields as $field => $label
                    )

                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                {{ $label }}
                            </label>

                            <input
                                type="number"
                                name="{{ $field }}"
                                class="form-control"
                                value="{{ old($field) }}"
                                min="0"
                                max="100"
                                step="0.01"
                                placeholder="0 - 100"
                            >

                        </div>

                    @endforeach


                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Overall Score
                        </label>

                        <input
                            type="number"
                            name="overall_score"
                            class="form-control"
                            value="{{ old('overall_score') }}"
                            min="0"
                            max="100"
                            step="0.01"
                            placeholder="0 - 100"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- 3. Investment Recommendation --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Investment Recommendation
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Recommended Investment
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                ₹
                            </span>

                            <input
                                type="number"
                                name="recommended_investment"
                                class="form-control"
                                value="{{ old(
                                    'recommended_investment'
                                ) }}"
                                min="0"
                                step="0.01"
                            >

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Approved Investment
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                ₹
                            </span>

                            <input
                                type="number"
                                name="approved_investment"
                                class="form-control"
                                value="{{ old(
                                    'approved_investment'
                                ) }}"
                                min="0"
                                step="0.01"
                            >

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Expected ROI (%)
                        </label>

                        <input
                            type="number"
                            name="expected_roi"
                            class="form-control"
                            value="{{ old('expected_roi') }}"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Expected IRR (%)
                        </label>

                        <input
                            type="number"
                            name="expected_irr"
                            class="form-control"
                            value="{{ old('expected_irr') }}"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Expected NPV
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                ₹
                            </span>

                            <input
                                type="number"
                                name="expected_npv"
                                class="form-control"
                                value="{{ old('expected_npv') }}"
                                step="0.01"
                            >

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Expected Payback Period
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                name="expected_payback_period"
                                class="form-control"
                                value="{{ old(
                                    'expected_payback_period'
                                ) }}"
                                min="0"
                                step="0.01"
                            >

                            <span class="input-group-text">
                                Years
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- 4. Decision Conditions --}}
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

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                {{ $label }}
                            </label>

                            <textarea
                                name="{{ $field }}"
                                class="form-control"
                                rows="4"
                            >{{ old($field) }}</textarea>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- 5. Decision Rationale --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Decision Rationale
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Key Strengths
                        </label>

                        <textarea
                            name="key_strengths"
                            class="form-control"
                            rows="5"
                        >{{ old('key_strengths') }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Key Weaknesses
                        </label>

                        <textarea
                            name="key_weaknesses"
                            class="form-control"
                            rows="5"
                        >{{ old('key_weaknesses') }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Key Opportunities
                        </label>

                        <textarea
                            name="key_opportunities"
                            class="form-control"
                            rows="5"
                        >{{ old('key_opportunities') }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Key Risks
                        </label>

                        <textarea
                            name="key_risks"
                            class="form-control"
                            rows="5"
                        >{{ old('key_risks') }}</textarea>

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Decision Rationale
                        </label>

                        <textarea
                            name="decision_rationale"
                            class="form-control"
                            rows="6"
                            placeholder="Explain the basis for the investment decision..."
                        >{{ old('decision_rationale') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- 6. Investment Committee --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Investment Committee
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Committee Name
                        </label>

                        <input
                            type="text"
                            name="committee_name"
                            class="form-control"
                            value="{{ old(
                                'committee_name'
                            ) }}"
                            placeholder="e.g. Investment Committee"
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Committee Members
                        </label>

                        <input
                            type="text"
                            name="committee_members"
                            class="form-control"
                            value="{{ old(
                                'committee_members'
                            ) }}"
                            placeholder="Member names separated by comma"
                        >

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Committee Notes
                        </label>

                        <textarea
                            name="committee_notes"
                            class="form-control"
                            rows="5"
                        >{{ old('committee_notes') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- 7. Final Recommendation --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Final Recommendation
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Final Recommendation
                        </label>

                        <textarea
                            name="final_recommendation"
                            class="form-control"
                            rows="6"
                            placeholder="Provide the final investment recommendation..."
                        >{{ old(
                            'final_recommendation'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Management Comments
                        </label>

                        <textarea
                            name="management_comments"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'management_comments'
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Submit --}}
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
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Create Investment Decision
            </button>

        </div>

    </form>

</div>

@endsection