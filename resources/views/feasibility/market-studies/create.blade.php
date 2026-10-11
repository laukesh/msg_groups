@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Create Market Study
            </h3>

            <p class="text-muted mb-0">

                Feasibility:
                {{ $feasibilityAssessment->assessment_number }}

                |

                {{ $feasibilityAssessment->title }}

            </p>

        </div>


        <div class="d-flex gap-2">

            {{-- Back to Feasibility --}}

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.show',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' => $feasibilityAssessment->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Back to Feasibility
            </a>


            {{-- Back to Market Studies --}}

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.market-studies.index',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' => $feasibilityAssessment->id,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                ← Back to Market Studies
            </a>

        </div>

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
        'admin.land.lands.feasibility-assessments.market-studies.store',
        [
            'land' => $land->id,
            'feasibilityAssessment' => $feasibilityAssessment->id,
        ]
    ) }}"
    method="POST"
>

        @csrf


        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Market Study Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row">


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Study Title *
                        </label>

                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            value="{{ old('title') }}"
                            placeholder="Example: Regional Retail Market Study"
                            required
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
                            value="{{ old('market_segment') }}"
                            placeholder="Retail / Office / Residential / Mixed Use"
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Study Date
                        </label>

                        <input
                            type="date"
                            name="study_date"
                            class="form-control"
                            value="{{ old('study_date') }}"
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Study Period
                        </label>

                        <input
                            type="text"
                            name="study_period"
                            class="form-control"
                            value="{{ old('study_period') }}"
                            placeholder="Example: 2026 - 2035"
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Market Location
                        </label>

                        <input
                            type="text"
                            name="market_location"
                            class="form-control"
                            value="{{ old('market_location') }}"
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Market Size
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="market_size"
                            class="form-control"
                            value="{{ old('market_size') }}"
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Expected Growth Rate (%)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="growth_rate"
                            class="form-control"
                            value="{{ old('growth_rate') }}"
                        >

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Market Overview
                        </label>

                        <textarea
                            name="market_overview"
                            rows="5"
                            class="form-control"
                        >{{ old('market_overview') }}</textarea>

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Market Trends
                        </label>

                        <textarea
                            name="market_trends"
                            rows="5"
                            class="form-control"
                        >{{ old('market_trends') }}</textarea>

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Target Market
                        </label>

                        <textarea
                            name="target_market"
                            rows="5"
                            class="form-control"
                        >{{ old('target_market') }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Growth Outlook
                        </label>

                        <textarea
                            name="growth_outlook"
                            rows="5"
                            class="form-control"
                        >{{ old('growth_outlook') }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Key Drivers
                        </label>

                        <textarea
                            name="key_drivers"
                            rows="5"
                            class="form-control"
                        >{{ old('key_drivers') }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Key Constraints
                        </label>

                        <textarea
                            name="key_constraints"
                            rows="5"
                            class="form-control"
                        >{{ old('key_constraints') }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Key Assumptions
                        </label>

                        <textarea
                            name="key_assumptions"
                            rows="5"
                            class="form-control"
                        >{{ old('key_assumptions') }}</textarea>

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Key Findings
                        </label>

                        <textarea
                            name="key_findings"
                            rows="5"
                            class="form-control"
                        >{{ old('key_findings') }}</textarea>

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Recommendation
                        </label>

                        <textarea
                            name="recommendation"
                            rows="5"
                            class="form-control"
                        >{{ old('recommendation') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        <div class="d-flex justify-content-end">

            <a
                href="{{ route(
                        'admin.land.lands.feasibility-assessments.show',
                        [
                            'land' => $land->id,
                            'feasibilityAssessment' => $feasibilityAssessment->id,
                        ]
                    ) }}"
                class="btn btn-secondary me-2"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Market Study
            </button>

        </div>

    </form>

</div>

@endsection