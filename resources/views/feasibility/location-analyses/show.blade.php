@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Location Analysis
            </h3>

            <p class="text-muted mb-0">
                {{ $locationAnalysis->analysis_number }}
                -
                {{ $locationAnalysis->title }}
            </p>

        </div>


        <div class="d-flex gap-2">

            {{-- Back --}}
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


            {{-- Edit --}}
            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.location-analyses.edit',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'locationAnalysis' =>
                            $locationAnalysis->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>

        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Basic Information --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Basic Information</strong>
        </div>


        <div class="card-body">

            <div class="row">

                {{-- Analysis Number --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Analysis Number
                    </label>

                    <div class="fw-semibold">
                        {{ $locationAnalysis->analysis_number }}
                    </div>

                </div>


                {{-- Title --}}
                <div class="col-md-5 mb-3">

                    <label class="form-label text-muted">
                        Title
                    </label>

                    <div class="fw-semibold">
                        {{ $locationAnalysis->title }}
                    </div>

                </div>


                {{-- Location Type --}}
                <div class="col-md-3 mb-3">

                    <label class="form-label text-muted">
                        Location Type
                    </label>

                    <div>
                        {{ $locationAnalysis->location_type ?? '-' }}
                    </div>

                </div>


                {{-- Status --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Status
                    </label>

                    <div>

                        @if($locationAnalysis->status === 'Draft')

                            <span class="badge bg-secondary">
                                Draft
                            </span>

                        @elseif($locationAnalysis->status === 'Submitted')

                            <span class="badge bg-warning text-dark">
                                Submitted
                            </span>

                        @elseif($locationAnalysis->status === 'Approved')

                            <span class="badge bg-success">
                                Approved
                            </span>

                        @elseif($locationAnalysis->status === 'Rejected')

                            <span class="badge bg-danger">
                                Rejected
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                {{ $locationAnalysis->status ?? 'N/A' }}
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Score --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Overall Location Score
                    </label>

                    <div class="fw-semibold">

                        @if(
                            $locationAnalysis->overall_location_score !== null
                        )

                            {{ number_format(
                                $locationAnalysis->overall_location_score,
                                2
                            ) }}

                            / 100

                        @else

                            -

                        @endif

                    </div>

                </div>


                {{-- Created Date --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Created At
                    </label>

                    <div>

                        @if($locationAnalysis->created_at)

                            {{ $locationAnalysis->created_at->format(
                                'd-m-Y H:i'
                            ) }}

                        @else

                            -

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Accessibility & Connectivity --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Accessibility & Connectivity</strong>
        </div>


        <div class="card-body">

            <div class="row">

                {{-- Accessibility --}}
                <div class="col-md-6 mb-4">

                    <label class="form-label text-muted">
                        Accessibility
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $locationAnalysis->accessibility
                                ?? '-'
                            )
                        ) !!}

                    </div>

                </div>


                {{-- Road Connectivity --}}
                <div class="col-md-6 mb-4">

                    <label class="form-label text-muted">
                        Road Connectivity
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $locationAnalysis->road_connectivity
                                ?? '-'
                            )
                        ) !!}

                    </div>

                </div>


                {{-- Public Transport --}}
                <div class="col-md-6 mb-4">

                    <label class="form-label text-muted">
                        Public Transport
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $locationAnalysis->public_transport
                                ?? '-'
                            )
                        ) !!}

                    </div>

                </div>


                {{-- Visibility --}}
                <div class="col-md-6 mb-4">

                    <label class="form-label text-muted">
                        Visibility
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $locationAnalysis->visibility
                                ?? '-'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Surrounding Area --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Surrounding Area</strong>
        </div>


        <div class="card-body">

            <div class="row">

                {{-- Surrounding Development --}}
                <div class="col-md-6 mb-4">

                    <label class="form-label text-muted">
                        Surrounding Development
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $locationAnalysis
                                    ->surrounding_development
                                ?? '-'
                            )
                        ) !!}

                    </div>

                </div>


                {{-- Nearby Landmarks --}}
                <div class="col-md-6 mb-4">

                    <label class="form-label text-muted">
                        Nearby Landmarks
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $locationAnalysis
                                    ->nearby_landmarks
                                ?? '-'
                            )
                        ) !!}

                    </div>

                </div>


                {{-- Competition --}}
                <div class="col-md-6 mb-4">

                    <label class="form-label text-muted">
                        Competition
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $locationAnalysis->competition
                                ?? '-'
                            )
                        ) !!}

                    </div>

                </div>


                {{-- Demographics --}}
                <div class="col-md-6 mb-4">

                    <label class="form-label text-muted">
                        Demographics
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $locationAnalysis->demographics
                                ?? '-'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Catchment Area --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Catchment Area</strong>
        </div>


        <div class="card-body">

            <label class="form-label text-muted">
                Catchment Area Analysis
            </label>

            <div class="border rounded p-3 bg-light">

                {!! nl2br(
                    e(
                        $locationAnalysis->catchment_area
                        ?? '-'
                    )
                ) !!}

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Advantages & Constraints --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Advantages & Constraints</strong>
        </div>


        <div class="card-body">

            <div class="row">

                {{-- Advantages --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label text-muted">
                        Location Advantages
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $locationAnalysis
                                    ->location_advantages
                                ?? '-'
                            )
                        ) !!}

                    </div>

                </div>


                {{-- Constraints --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label text-muted">
                        Location Constraints
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $locationAnalysis
                                    ->location_constraints
                                ?? '-'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Recommendation --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Assessment & Recommendation</strong>
        </div>


        <div class="card-body">

            <div class="row">

                {{-- Score --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Overall Location Score
                    </label>

                    <div class="display-6">

                        @if(
                            $locationAnalysis
                                ->overall_location_score !== null
                        )

                            {{ number_format(
                                $locationAnalysis
                                    ->overall_location_score,
                                2
                            ) }}

                            <small class="fs-6 text-muted">
                                / 100
                            </small>

                        @else

                            -

                        @endif

                    </div>

                </div>


                {{-- Recommendation --}}
                <div class="col-md-8 mb-3">

                    <label class="form-label text-muted">
                        Recommendation
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $locationAnalysis
                                    ->recommendation
                                ?? '-'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Audit Information --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Audit Information</strong>
        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <label class="form-label text-muted">
                        Created At
                    </label>

                    <div>

                        {{ $locationAnalysis->created_at
                            ? $locationAnalysis->created_at->format(
                                'd-m-Y H:i'
                            )
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="form-label text-muted">
                        Updated At
                    </label>

                    <div>

                        {{ $locationAnalysis->updated_at
                            ? $locationAnalysis->updated_at->format(
                                'd-m-Y H:i'
                            )
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="form-label text-muted">
                        Created By
                    </label>

                    <div>
                        {{ $locationAnalysis->created_by ?? '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Bottom Actions --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between mb-5">

        <div>

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.show',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Feasibility Assessment
            </a>

        </div>


        <div class="d-flex gap-2">

            {{-- Edit --}}
            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.location-analyses.edit',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'locationAnalysis' =>
                            $locationAnalysis->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>


            {{-- Delete --}}
            <form
                action="{{ route(
                    'admin.land.lands.feasibility-assessments.location-analyses.destroy',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'locationAnalysis' =>
                            $locationAnalysis->id,
                    ]
                ) }}"
                method="POST"
                class="d-inline"
                onsubmit="return confirm('Are you sure you want to delete this location analysis?');"
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