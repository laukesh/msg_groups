@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Environmental Feasibility
            </h3>

            <p class="text-muted mb-0">

                {{ $environmentalFeasibility->analysis_number }}
                -
                {{ $environmentalFeasibility->title }}

            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.environmental-feasibilities.index',
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
                    'admin.land.lands.feasibility-assessments.environmental-feasibilities.edit',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'environmentalFeasibility' =>
                            $environmentalFeasibility->id,
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
    {{-- Basic Information --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between">

            <strong>
                Basic Information
            </strong>


            @if(
                $environmentalFeasibility->status === 'Draft'
            )

                <span class="badge bg-secondary">
                    Draft
                </span>

            @elseif(
                $environmentalFeasibility->status === 'Submitted'
            )

                <span class="badge bg-warning text-dark">
                    Submitted
                </span>

            @elseif(
                $environmentalFeasibility->status === 'Approved'
            )

                <span class="badge bg-success">
                    Approved
                </span>

            @elseif(
                $environmentalFeasibility->status === 'Rejected'
            )

                <span class="badge bg-danger">
                    Rejected
                </span>

            @else

                <span class="badge bg-secondary">
                    {{ $environmentalFeasibility->status }}
                </span>

            @endif

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Analysis Number
                    </label>

                    <div class="fw-bold">
                        {{ $environmentalFeasibility->analysis_number }}
                    </div>

                </div>


                <div class="col-md-5 mb-3">

                    <label class="text-muted small">
                        Title
                    </label>

                    <div class="fw-bold">
                        {{ $environmentalFeasibility->title }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted small">
                        Environmental Score
                    </label>

                    <div>

                        @if(
                            $environmentalFeasibility
                                ->overall_environmental_score !== null
                        )

                            <span class="fs-5 fw-bold">

                                {{
                                    number_format(
                                        $environmentalFeasibility
                                            ->overall_environmental_score,
                                        2
                                    )
                                }}

                            </span>

                            <span class="text-muted">
                                / 100
                            </span>

                        @else

                            -

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Environmental Assessment --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Environmental Assessment</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Environmental Status
                    </label>

                    <div>

                        {{ $environmentalFeasibility
                            ->environmental_status ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Environmental Impact Assessment
                    </label>

                    <div>

                        {{ $environmentalFeasibility
                            ->environmental_impact_assessment_status
                            ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Environmental Clearance
                    </label>

                    <div>

                        {{ $environmentalFeasibility
                            ->environmental_clearance_status
                            ?? '-' }}

                    </div>

                </div>


                <div class="col-md-12">

                    <label class="text-muted small">
                        Environmental Overview
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $environmentalFeasibility
                                ->environmental_overview
                                ?? '-'
                        )) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Environmental Quality --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Environmental Quality</strong>
        </div>

        <div class="card-body">

            <div class="row">

                @php

                    $qualityFields = [

                        'air_quality' => 'Air Quality',

                        'water_environment' =>
                            'Water Environment',

                        'soil_environment' =>
                            'Soil Environment',

                        'noise_pollution' =>
                            'Noise Pollution',

                    ];

                @endphp


                @foreach(
                    $qualityFields
                    as $field => $label
                )

                    <div class="col-md-6 mb-4">

                        <label class="text-muted small">
                            {{ $label }} Status
                        </label>

                        <div class="fw-semibold mb-2">

                            {{ $environmentalFeasibility
                                ->{$field . '_status'}
                                ?? '-' }}

                        </div>


                        <div class="border rounded p-3 bg-light">

                            {!! nl2br(e(
                                $environmentalFeasibility
                                    ->{$field . '_details'}
                                    ?? '-'
                            )) !!}

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Ecology & Biodiversity --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Ecology & Biodiversity</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-4">

                    <label class="text-muted small">
                        Ecological Status
                    </label>

                    <div class="fw-semibold mb-2">

                        {{ $environmentalFeasibility
                            ->ecological_status ?? '-' }}

                    </div>


                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $environmentalFeasibility
                                ->ecological_details
                                ?? '-'
                        )) !!}

                    </div>

                </div>


                <div class="col-md-6 mb-4">

                    <label class="text-muted small">
                        Biodiversity Status
                    </label>

                    <div class="fw-semibold mb-2">

                        {{ $environmentalFeasibility
                            ->biodiversity_status ?? '-' }}

                    </div>


                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $environmentalFeasibility
                                ->biodiversity_details
                                ?? '-'
                        )) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Trees & Green Cover --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Trees & Green Cover</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-4">

                    <label class="text-muted small">
                        Tree Cutting Required
                    </label>

                    <div class="fw-semibold mb-2">

                        {{ $environmentalFeasibility
                            ->tree_cutting_required ?? '-' }}

                    </div>


                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $environmentalFeasibility
                                ->tree_cutting_details
                                ?? '-'
                        )) !!}

                    </div>

                </div>


                <div class="col-md-6 mb-4">

                    <label class="text-muted small">
                        Green Cover Status
                    </label>

                    <div class="fw-semibold mb-2">

                        {{ $environmentalFeasibility
                            ->green_cover_status ?? '-' }}

                    </div>


                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $environmentalFeasibility
                                ->green_cover_details
                                ?? '-'
                        )) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Waste Management --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Waste Management</strong>
        </div>

        <div class="card-body">

            <div class="row">

                @php

                    $wasteFields = [

                        'solid_waste_management' =>
                            'Solid Waste Management',

                        'hazardous_waste' =>
                            'Hazardous Waste',

                        'construction_waste' =>
                            'Construction Waste',

                    ];

                @endphp


                @foreach(
                    $wasteFields
                    as $field => $label
                )

                    <div class="col-md-4 mb-4">

                        <label class="text-muted small">
                            {{ $label }}
                        </label>

                        <div class="fw-semibold mb-2">

                            {{ $environmentalFeasibility
                                ->{$field . '_status'}
                                ?? '-' }}

                        </div>


                        <div class="border rounded p-3 bg-light">

                            {!! nl2br(e(
                                $environmentalFeasibility
                                    ->{$field . '_details'}
                                    ?? '-'
                            )) !!}

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Pollution & Climate --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Pollution & Climate</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-4">

                    <label class="text-muted small">
                        Pollution Control Status
                    </label>

                    <div class="fw-semibold mb-2">

                        {{ $environmentalFeasibility
                            ->pollution_control_status
                            ?? '-' }}

                    </div>


                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $environmentalFeasibility
                                ->pollution_control_details
                                ?? '-'
                        )) !!}

                    </div>

                </div>


                <div class="col-md-6 mb-4">

                    <label class="text-muted small">
                        Climate Impact
                    </label>

                    <div class="fw-semibold mb-2">

                        {{ $environmentalFeasibility
                            ->climate_impact_status
                            ?? '-' }}

                    </div>


                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $environmentalFeasibility
                                ->climate_impact_details
                                ?? '-'
                        )) !!}

                    </div>

                </div>


                <div class="col-md-12">

                    <label class="text-muted small">
                        Climate Resilience Measures
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $environmentalFeasibility
                                ->climate_resilience_measures
                                ?? '-'
                        )) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Sustainability --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>
                Sustainability & Green Development
            </strong>
        </div>

        <div class="card-body">

            <div class="row">

                @php

                    $sustainabilityFields = [

                        'sustainability' =>
                            'Sustainability',

                        'green_building_potential' =>
                            'Green Building Potential',

                        'renewable_energy_potential' =>
                            'Renewable Energy Potential',

                        'water_conservation_potential' =>
                            'Water Conservation Potential',

                    ];

                @endphp


                @foreach(
                    $sustainabilityFields
                    as $field => $label
                )

                    <div class="col-md-6 mb-4">

                        <label class="text-muted small">
                            {{ $label }}
                        </label>

                        <div class="fw-semibold mb-2">

                            {{ $environmentalFeasibility
                                ->{$field . '_status'}
                                ?? '-' }}

                        </div>


                        <div class="border rounded p-3 bg-light">

                            {!! nl2br(e(
                                $environmentalFeasibility
                                    ->{$field . '_details'}
                                    ?? '-'
                            )) !!}

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Regulatory --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>
                Environmental Regulatory Requirements
            </strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Environmental Clearance
                    </label>

                    <div class="fw-semibold">

                        {{ $environmentalFeasibility
                            ->environmental_clearance_status
                            ?? '-' }}

                    </div>

                </div>


                <div class="col-md-8 mb-3">

                    <label class="text-muted small">
                        Clearance Details
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $environmentalFeasibility
                                ->environmental_clearance_details
                                ?? '-'
                        )) !!}

                    </div>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="text-muted small">
                        Applicable Environmental Laws
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $environmentalFeasibility
                                ->applicable_environmental_laws
                                ?? '-'
                        )) !!}

                    </div>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="text-muted small">
                        Required Environmental Approvals
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $environmentalFeasibility
                                ->required_environmental_approvals
                                ?? '-'
                        )) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Risks --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>
                Environmental Risks & Mitigation
            </strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="text-muted small">
                        Environmental Risks
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $environmentalFeasibility
                                ->environmental_risks
                                ?? '-'
                        )) !!}

                    </div>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="text-muted small">
                        Mitigation Measures
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $environmentalFeasibility
                                ->mitigation_measures
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
                        Key Environmental Findings
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $environmentalFeasibility
                                ->key_environmental_findings
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
                            $environmentalFeasibility
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
                'admin.land.lands.feasibility-assessments.environmental-feasibilities.index',
                [
                    'land' => $land->id,
                    'feasibilityAssessment' =>
                        $feasibilityAssessment->id,
                ]
            ) }}"
            class="btn btn-secondary"
        >
            ← Back to Environmental Feasibilities
        </a>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.environmental-feasibilities.edit',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'environmentalFeasibility' =>
                            $environmentalFeasibility->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit Environmental Feasibility
            </a>


            <form
                action="{{ route(
                    'admin.land.lands.feasibility-assessments.environmental-feasibilities.destroy',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'environmentalFeasibility' =>
                            $environmentalFeasibility->id,
                    ]
                ) }}"
                method="POST"
                onsubmit="return confirm('Are you sure you want to delete this Environmental Feasibility?');"
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