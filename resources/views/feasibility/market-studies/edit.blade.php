@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Edit Market Study
            </h4>

            <div class="text-muted">
                {{ $marketStudy->study_number }}
            </div>
        </div>

        <a
            href="{{ route(
                'admin.land.lands.feasibility-assessments.market-studies.show',
                [
                    'land' => $land->id,
                    'feasibilityAssessment' => $feasibilityAssessment->id,
                    'marketStudy' => $marketStudy->id,
                ]
            ) }}"
            class="btn btn-secondary"
        >
            ← Back
        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        action="{{ route(
            'admin.land.lands.feasibility-assessments.market-studies.update',
            [
                'land' => $land->id,
                'feasibilityAssessment' => $feasibilityAssessment->id,
                'marketStudy' => $marketStudy->id,
            ]
        ) }}"
        method="POST"
    >

        @csrf

        @method('PUT')


        {{-- Basic Information --}}
        <div class="card mb-4">

            <div class="card-header">
                <strong>Basic Information</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Study Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $marketStudy->study_number }}"
                            readonly
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Title <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            value="{{ old('title', $marketStudy->title) }}"
                            required
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Study Date
                        </label>

                        <input
                            type="date"
                            name="study_date"
                            class="form-control"
                            value="{{ old('study_date', $marketStudy->study_date) }}"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Study Period
                        </label>

                        <input
                            type="text"
                            name="study_period"
                            class="form-control"
                            value="{{ old('study_period', $marketStudy->study_period) }}"
                            placeholder="e.g. 2026-2030"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            class="form-select"
                            required
                        >

                            <option value="Draft"
                                {{ old('status', $marketStudy->status) === 'Draft' ? 'selected' : '' }}>
                                Draft
                            </option>

                            <option value="Submitted"
                                {{ old('status', $marketStudy->status) === 'Submitted' ? 'selected' : '' }}>
                                Submitted
                            </option>

                            <option value="Approved"
                                {{ old('status', $marketStudy->status) === 'Approved' ? 'selected' : '' }}>
                                Approved
                            </option>

                            <option value="Rejected"
                                {{ old('status', $marketStudy->status) === 'Rejected' ? 'selected' : '' }}>
                                Rejected
                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- Market Information --}}
        <div class="card mb-4">

            <div class="card-header">
                <strong>Market Information</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Market Location
                        </label>

                        <input
                            type="text"
                            name="market_location"
                            class="form-control"
                            value="{{ old('market_location', $marketStudy->market_location) }}"
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Market Segment
                        </label>

                        <input
                            type="text"
                            name="market_segment"
                            class="form-control"
                            value="{{ old('market_segment', $marketStudy->market_segment) }}"
                        >

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Market Overview
                        </label>

                        <textarea
                            name="market_overview"
                            class="form-control"
                            rows="5"
                        >{{ old('market_overview', $marketStudy->market_overview) }}</textarea>

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Market Trends
                        </label>

                        <textarea
                            name="market_trends"
                            class="form-control"
                            rows="5"
                        >{{ old('market_trends', $marketStudy->market_trends) }}</textarea>

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Target Market
                        </label>

                        <textarea
                            name="target_market"
                            class="form-control"
                            rows="5"
                        >{{ old('target_market', $marketStudy->target_market) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- Market Size & Growth --}}
        <div class="card mb-4">

            <div class="card-header">
                <strong>Market Size & Growth</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Market Size
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="market_size"
                            class="form-control"
                            value="{{ old('market_size', $marketStudy->market_size) }}"
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Growth Rate (%)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="growth_rate"
                            class="form-control"
                            value="{{ old('growth_rate', $marketStudy->growth_rate) }}"
                        >

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Growth Outlook
                        </label>

                        <textarea
                            name="growth_outlook"
                            class="form-control"
                            rows="5"
                        >{{ old('growth_outlook', $marketStudy->growth_outlook) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- Market Analysis --}}
        <div class="card mb-4">

            <div class="card-header">
                <strong>Market Analysis</strong>
            </div>

            <div class="card-body">

                <div class="mb-3">

                    <label class="form-label">
                        Key Drivers
                    </label>

                    <textarea
                        name="key_drivers"
                        class="form-control"
                        rows="4"
                    >{{ old('key_drivers', $marketStudy->key_drivers) }}</textarea>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Key Constraints
                    </label>

                    <textarea
                        name="key_constraints"
                        class="form-control"
                        rows="4"
                    >{{ old('key_constraints', $marketStudy->key_constraints) }}</textarea>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Key Assumptions
                    </label>

                    <textarea
                        name="key_assumptions"
                        class="form-control"
                        rows="4"
                    >{{ old('key_assumptions', $marketStudy->key_assumptions) }}</textarea>

                </div>

            </div>

        </div>


        {{-- Findings --}}
        <div class="card mb-4">

            <div class="card-header">
                <strong>Findings & Recommendation</strong>
            </div>

            <div class="card-body">

                <div class="mb-3">

                    <label class="form-label">
                        Key Findings
                    </label>

                    <textarea
                        name="key_findings"
                        class="form-control"
                        rows="5"
                    >{{ old('key_findings', $marketStudy->key_findings) }}</textarea>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Recommendation
                    </label>

                    <textarea
                        name="recommendation"
                        class="form-control"
                        rows="5"
                    >{{ old('recommendation', $marketStudy->recommendation) }}</textarea>

                </div>

            </div>

        </div>


        {{-- Actions --}}
        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.market-studies.show',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' => $feasibilityAssessment->id,
                        'marketStudy' => $marketStudy->id,
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
                Update Market Study
            </button>

        </div>

    </form>

</div>

@endsection