@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Edit Location Analysis
            </h3>

            <p class="text-muted mb-0">

                {{ $locationAnalysis->analysis_number }}
                -
                {{ $locationAnalysis->title }}

            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.location-analyses.show',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'locationAnalysis' =>
                            $locationAnalysis->id,
                    ]
                ) }}"
                class="btn btn-secondary"
            >
                ← Back
            </a>

        </div>

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


    {{-- Update Form --}}
    <form
        action="{{ route(
            'admin.land.lands.feasibility-assessments.location-analyses.update',
            [
                'land' => $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,

                'locationAnalysis' =>
                    $locationAnalysis->id,
            ]
        ) }}"
        method="POST"
    >

        @csrf

        @method('PUT')


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

                    {{-- Analysis Number --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Analysis Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $locationAnalysis->analysis_number }}"
                            readonly
                        >

                    </div>


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
                            value="{{ old(
                                'title',
                                $locationAnalysis->title
                            ) }}"
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
                                {{ old(
                                    'location_type',
                                    $locationAnalysis->location_type
                                ) === 'Urban'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Urban
                            </option>

                            <option
                                value="Suburban"
                                {{ old(
                                    'location_type',
                                    $locationAnalysis->location_type
                                ) === 'Suburban'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Suburban
                            </option>

                            <option
                                value="Rural"
                                {{ old(
                                    'location_type',
                                    $locationAnalysis->location_type
                                ) === 'Rural'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Rural
                            </option>

                            <option
                                value="Highway"
                                {{ old(
                                    'location_type',
                                    $locationAnalysis->location_type
                                ) === 'Highway'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Highway
                            </option>

                            <option
                                value="Commercial"
                                {{ old(
                                    'location_type',
                                    $locationAnalysis->location_type
                                ) === 'Commercial'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Commercial
                            </option>

                            <option
                                value="Mixed Use"
                                {{ old(
                                    'location_type',
                                    $locationAnalysis->location_type
                                ) === 'Mixed Use'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Mixed Use
                            </option>

                            <option
                                value="Other"
                                {{ old(
                                    'location_type',
                                    $locationAnalysis->location_type
                                ) === 'Other'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Other
                            </option>

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            class="form-select"
                            required
                        >

                            <option
                                value="Draft"
                                {{ old(
                                    'status',
                                    $locationAnalysis->status
                                ) === 'Draft'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Draft
                            </option>

                            <option
                                value="Submitted"
                                {{ old(
                                    'status',
                                    $locationAnalysis->status
                                ) === 'Submitted'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Submitted
                            </option>

                            <option
                                value="Approved"
                                {{ old(
                                    'status',
                                    $locationAnalysis->status
                                ) === 'Approved'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Approved
                            </option>

                            <option
                                value="Rejected"
                                {{ old(
                                    'status',
                                    $locationAnalysis->status
                                ) === 'Rejected'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Rejected
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
                        >{{ old(
                            'accessibility',
                            $locationAnalysis->accessibility
                        ) }}</textarea>

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
                        >{{ old(
                            'road_connectivity',
                            $locationAnalysis->road_connectivity
                        ) }}</textarea>

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
                        >{{ old(
                            'public_transport',
                            $locationAnalysis->public_transport
                        ) }}</textarea>

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
                        >{{ old(
                            'visibility',
                            $locationAnalysis->visibility
                        ) }}</textarea>

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
                        >{{ old(
                            'surrounding_development',
                            $locationAnalysis->surrounding_development
                        ) }}</textarea>

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
                        >{{ old(
                            'nearby_landmarks',
                            $locationAnalysis->nearby_landmarks
                        ) }}</textarea>

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
                        >{{ old(
                            'competition',
                            $locationAnalysis->competition
                        ) }}</textarea>

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
                        >{{ old(
                            'demographics',
                            $locationAnalysis->demographics
                        ) }}</textarea>

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
                >{{ old(
                    'catchment_area',
                    $locationAnalysis->catchment_area
                ) }}</textarea>

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
                        >{{ old(
                            'location_advantages',
                            $locationAnalysis->location_advantages
                        ) }}</textarea>

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
                        >{{ old(
                            'location_constraints',
                            $locationAnalysis->location_constraints
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Assessment & Recommendation --}}
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
                                value="{{ old(
                                    'overall_location_score',
                                    $locationAnalysis
                                        ->overall_location_score
                                ) }}"
                                min="0"
                                max="100"
                                step="0.01"
                            >

                            <span class="input-group-text">
                                / 100
                            </span>

                        </div>

                        <div class="form-text">
                            Enter a value between 0 and 100.
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
                        >{{ old(
                            'recommendation',
                            $locationAnalysis->recommendation
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Actions --}}
        {{-- ========================================================= --}}

        <div class="d-flex justify-content-between mb-5">

            <div>

                <a
                    href="{{ route(
                        'admin.land.lands.feasibility-assessments.location-analyses.show',
                        [
                            'land' => $land->id,

                            'feasibilityAssessment' =>
                                $feasibilityAssessment->id,

                            'locationAnalysis' =>
                                $locationAnalysis->id,
                        ]
                    ) }}"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

            </div>


            <div>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Update Location Analysis
                </button>

            </div>

        </div>

    </form>

</div>

@endsection