@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                New Location Analysis
            </h3>

            <p class="text-muted mb-0">

                {{ $feasibilityAssessment->assessment_number }}
                -
                {{ $feasibilityAssessment->title }}

            </p>

        </div>


        <a
            href="{{ route(
                'admin.land.lands.feasibility-assessments.location-analyses.index',
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


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Form --}}
    <form
        action="{{ route(
            'admin.land.lands.feasibility-assessments.location-analyses.store',
            [
                'land' => $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,
            ]
        ) }}"
        method="POST"
    >

        @csrf


        {{-- ========================================================= --}}
        {{-- Basic Information --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Basic Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Title --}}
                    <div class="col-md-8 mb-3">

                        <label class="form-label">

                            Analysis Title

                            <span class="text-danger">
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            value="{{ old('title') }}"
                            placeholder="Enter location analysis title"
                            required
                        >

                    </div>


                    {{-- Location Type --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Location Type

                        </label>

                        <select
                            name="location_type"
                            class="form-select"
                        >

                            <option value="">
                                Select Location Type
                            </option>

                            <option
                                value="Urban"
                                {{ old('location_type') === 'Urban' ? 'selected' : '' }}
                            >
                                Urban
                            </option>

                            <option
                                value="Suburban"
                                {{ old('location_type') === 'Suburban' ? 'selected' : '' }}
                            >
                                Suburban
                            </option>

                            <option
                                value="Rural"
                                {{ old('location_type') === 'Rural' ? 'selected' : '' }}
                            >
                                Rural
                            </option>

                            <option
                                value="Highway"
                                {{ old('location_type') === 'Highway' ? 'selected' : '' }}
                            >
                                Highway
                            </option>

                            <option
                                value="Commercial"
                                {{ old('location_type') === 'Commercial' ? 'selected' : '' }}
                            >
                                Commercial
                            </option>

                            <option
                                value="Mixed Use"
                                {{ old('location_type') === 'Mixed Use' ? 'selected' : '' }}
                            >
                                Mixed Use
                            </option>

                            <option
                                value="Other"
                                {{ old('location_type') === 'Other' ? 'selected' : '' }}
                            >
                                Other
                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Accessibility & Connectivity --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Accessibility & Connectivity
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Accessibility --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Accessibility
                        </label>

                        <textarea
                            name="accessibility"
                            class="form-control"
                            rows="5"
                            placeholder="Describe accessibility to the site..."
                        >{{ old('accessibility') }}</textarea>

                    </div>


                    {{-- Road Connectivity --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Road Connectivity
                        </label>

                        <textarea
                            name="road_connectivity"
                            class="form-control"
                            rows="5"
                            placeholder="Describe major roads, highways and connectivity..."
                        >{{ old('road_connectivity') }}</textarea>

                    </div>


                    {{-- Public Transport --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Public Transport
                        </label>

                        <textarea
                            name="public_transport"
                            class="form-control"
                            rows="5"
                            placeholder="Describe buses, railway, metro, taxi availability etc..."
                        >{{ old('public_transport') }}</textarea>

                    </div>


                    {{-- Visibility --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Visibility
                        </label>

                        <textarea
                            name="visibility"
                            class="form-control"
                            rows="5"
                            placeholder="Describe site visibility from roads and surrounding areas..."
                        >{{ old('visibility') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Surrounding Area --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Surrounding Area
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Surrounding Development --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Surrounding Development
                        </label>

                        <textarea
                            name="surrounding_development"
                            class="form-control"
                            rows="5"
                            placeholder="Describe existing and planned developments..."
                        >{{ old('surrounding_development') }}</textarea>

                    </div>


                    {{-- Nearby Landmarks --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Nearby Landmarks
                        </label>

                        <textarea
                            name="nearby_landmarks"
                            class="form-control"
                            rows="5"
                            placeholder="Shopping centres, schools, hospitals, offices, landmarks etc..."
                        >{{ old('nearby_landmarks') }}</textarea>

                    </div>


                    {{-- Competition --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Competition
                        </label>

                        <textarea
                            name="competition"
                            class="form-control"
                            rows="5"
                            placeholder="Describe existing and upcoming competitors..."
                        >{{ old('competition') }}</textarea>

                    </div>


                    {{-- Demographics --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Demographics
                        </label>

                        <textarea
                            name="demographics"
                            class="form-control"
                            rows="5"
                            placeholder="Population, income profile, age groups, employment etc..."
                        >{{ old('demographics') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Catchment Area --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Catchment Area
                </strong>

            </div>


            <div class="card-body">

                <label class="form-label">
                    Catchment Area Analysis
                </label>

                <textarea
                    name="catchment_area"
                    class="form-control"
                    rows="6"
                    placeholder="Describe primary, secondary and tertiary catchment areas..."
                >{{ old('catchment_area') }}</textarea>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Advantages & Constraints --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Advantages & Constraints
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Advantages --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Location Advantages
                        </label>

                        <textarea
                            name="location_advantages"
                            class="form-control"
                            rows="7"
                            placeholder="List major advantages of the location..."
                        >{{ old('location_advantages') }}</textarea>

                    </div>


                    {{-- Constraints --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Location Constraints
                        </label>

                        <textarea
                            name="location_constraints"
                            class="form-control"
                            rows="7"
                            placeholder="List major constraints or challenges..."
                        >{{ old('location_constraints') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Score & Recommendation --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Assessment & Recommendation
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Score --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Overall Location Score
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                name="overall_location_score"
                                class="form-control"
                                value="{{ old('overall_location_score') }}"
                                min="0"
                                max="100"
                                step="0.01"
                                placeholder="0 - 100"
                            >

                            <span class="input-group-text">
                                / 100
                            </span>

                        </div>

                        <div class="form-text">
                            Enter a score between 0 and 100.
                        </div>

                    </div>


                    {{-- Recommendation --}}
                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Recommendation
                        </label>

                        <textarea
                            name="recommendation"
                            class="form-control"
                            rows="5"
                            placeholder="Provide your overall location recommendation..."
                        >{{ old('recommendation') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Actions --}}
        {{-- ========================================================= --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.location-analyses.index',
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
                Save Location Analysis
            </button>

        </div>

    </form>

</div>

@endsection