@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>Edit Investment Decision</h3>

            <p class="text-muted mb-0">
                {{ $investmentDecision->decision_number }}
                -
                {{ $investmentDecision->title }}
            </p>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.investment-decisions.show',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'investmentDecision' =>
                            $investmentDecision->id,
                    ]
                ) }}"
                class="btn btn-secondary"
            >
                ← Back
            </a>

        </div>

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
            'admin.land.lands.feasibility-assessments.investment-decisions.update',
            [
                'land' => $land->id,
                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,
                'investmentDecision' =>
                    $investmentDecision->id,
            ]
        ) }}"
    >

        @csrf

        @method('PUT')


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
                            value="{{ $investmentDecision->decision_number }}"
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
                            value="{{ old(
                                'title',
                                $investmentDecision->title
                            ) }}"
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
                                            $investmentDecision->status
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

                            @foreach([
                                'Go',
                                'Conditional Go',
                                'No-Go',
                                'Defer'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        old(
                                            'decision',
                                            $investmentDecision->decision
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
                            Decision Date
                        </label>

                        <input
                            type="date"
                            name="decision_date"
                            class="form-control"
                            value="{{ old(
                                'decision_date',
                                optional(
                                    $investmentDecision
                                        ->decision_date
                                )->format('Y-m-d')
                            ) }}"
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
                                            'investment_recommendation',
                                            $investmentDecision
                                                ->investment_recommendation
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
                                            'investment_priority',
                                            $investmentDecision
                                                ->investment_priority
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
        {{-- 2. Feasibility Scores --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Feasibility Assessment Scores</strong>
            </div>

            <div class="card-body">

                <p class="text-muted small">
                    Scores should be between 0 and 100.
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
                                value="{{ old(
                                    $field,
                                    $investmentDecision->{$field}
                                ) }}"
                                min="0"
                                max="100"
                                step="0.01"
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
                            value="{{ old(
                                'overall_score',
                                $investmentDecision->overall_score
                            ) }}"
                            min="0"
                            max="100"
                            step="0.01"
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
                <strong>Investment Recommendation</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Recommended Investment
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input
                                type="number"
                                name="recommended_investment"
                                class="form-control"
                                value="{{ old(
                                    'recommended_investment',
                                    $investmentDecision
                                        ->recommended_investment
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
                                $
                            </span>

                            <input
                                type="number"
                                name="approved_investment"
                                class="form-control"
                                value="{{ old(
                                    'approved_investment',
                                    $investmentDecision
                                        ->approved_investment
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
                            value="{{ old(
                                'expected_roi',
                                $investmentDecision->expected_roi
                            ) }}"
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
                            value="{{ old(
                                'expected_irr',
                                $investmentDecision->expected_irr
                            ) }}"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Expected NPV
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input
                                type="number"
                                name="expected_npv"
                                class="form-control"
                                value="{{ old(
                                    'expected_npv',
                                    $investmentDecision->expected_npv
                                ) }}"
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
                                    'expected_payback_period',
                                    $investmentDecision
                                        ->expected_payback_period
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
                <strong>Decision Conditions</strong>
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
                            >{{ old(
                                $field,
                                $investmentDecision->{$field}
                            ) }}</textarea>

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
                <strong>Decision Rationale</strong>
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

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                {{ $label }}
                            </label>

                            <textarea
                                name="{{ $field }}"
                                class="form-control"
                                rows="5"
                            >{{ old(
                                $field,
                                $investmentDecision->{$field}
                            ) }}</textarea>

                        </div>

                    @endforeach


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Decision Rationale
                        </label>

                        <textarea
                            name="decision_rationale"
                            class="form-control"
                            rows="6"
                        >{{ old(
                            'decision_rationale',
                            $investmentDecision
                                ->decision_rationale
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- 6. Investment Committee --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Investment Committee</strong>
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
                                'committee_name',
                                $investmentDecision
                                    ->committee_name
                            ) }}"
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
                                'committee_members',
                                $investmentDecision
                                    ->committee_members
                            ) }}"
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
                        >{{ old(
                            'committee_notes',
                            $investmentDecision
                                ->committee_notes
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- 7. Final Recommendation --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Final Recommendation</strong>
            </div>

            <div class="card-body">

                <div class="mb-3">

                    <label class="form-label">
                        Final Recommendation
                    </label>

                    <textarea
                        name="final_recommendation"
                        class="form-control"
                        rows="6"
                    >{{ old(
                        'final_recommendation',
                        $investmentDecision
                            ->final_recommendation
                    ) }}</textarea>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Management Comments
                    </label>

                    <textarea
                        name="management_comments"
                        class="form-control"
                        rows="5"
                    >{{ old(
                        'management_comments',
                        $investmentDecision
                            ->management_comments
                    ) }}</textarea>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Actions --}}
        {{-- ========================================================= --}}

        <div class="d-flex justify-content-between mb-5">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.investment-decisions.show',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'investmentDecision' =>
                            $investmentDecision->id,
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
                Update Investment Decision
            </button>

        </div>

    </form>

</div>

@endsection