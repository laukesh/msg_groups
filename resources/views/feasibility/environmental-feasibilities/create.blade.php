@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                New Environmental Feasibility
            </h3>

            <p class="text-muted mb-0">

                {{ $feasibilityAssessment->assessment_number }}
                -
                {{ $feasibilityAssessment->title }}

            </p>

        </div>


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

    </div>


    {{-- ========================================================= --}}
    {{-- Validation --}}
    {{-- ========================================================= --}}

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


    <form
        action="{{ route(
            'admin.land.lands.feasibility-assessments.environmental-feasibilities.store',
            [
                'land' => $land->id,
                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,
            ]
        ) }}"
        method="POST"
    >

        @csrf


        {{-- ===================================================== --}}
        {{-- Basic Information --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Basic Information</strong>
            </div>

            <div class="card-body">

                <div class="row">

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
                            placeholder="Enter environmental feasibility title"
                            required
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="Draft"
                            readonly
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Environmental Assessment --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Environmental Assessment</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Environmental Status
                        </label>

                        <select
                            name="environmental_status"
                            class="form-select"
                        >

                            <option value="">
                                Select
                            </option>

                            <option value="Feasible">
                                Feasible
                            </option>

                            <option value="Conditionally Feasible">
                                Conditionally Feasible
                            </option>

                            <option value="Not Feasible">
                                Not Feasible
                            </option>

                            <option value="Under Study">
                                Under Study
                            </option>

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Environmental Impact Assessment
                        </label>

                        <select
                            name="environmental_impact_assessment_status"
                            class="form-select"
                        >

                            <option value="">
                                Select
                            </option>

                            <option value="Completed">
                                Completed
                            </option>

                            <option value="In Progress">
                                In Progress
                            </option>

                            <option value="Required">
                                Required
                            </option>

                            <option value="Not Required">
                                Not Required
                            </option>

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Overall Environmental Score
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                name="overall_environmental_score"
                                class="form-control"
                                value="{{ old(
                                    'overall_environmental_score'
                                ) }}"
                                min="0"
                                max="100"
                                step="0.01"
                            >

                            <span class="input-group-text">
                                / 100
                            </span>

                        </div>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Environmental Overview
                        </label>

                        <textarea
                            name="environmental_overview"
                            class="form-control"
                            rows="5"
                            placeholder="Provide an overall environmental assessment..."
                        >{{ old(
                            'environmental_overview'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            EIA Details
                        </label>

                        <textarea
                            name="environmental_impact_assessment_details"
                            class="form-control"
                            rows="5"
                            placeholder="Enter EIA details..."
                        >{{ old(
                            'environmental_impact_assessment_details'
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Environmental Quality --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Environmental Quality</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    @php

                        $environmentalQuality = [

                            'air_quality' =>
                                'Air Quality',

                            'water_environment' =>
                                'Water Environment',

                            'soil_environment' =>
                                'Soil Environment',

                            'noise_pollution' =>
                                'Noise Pollution',

                        ];

                    @endphp


                    @foreach(
                        $environmentalQuality
                        as $field => $label
                    )

                        <div class="col-md-6 mb-4">

                            <label class="form-label">
                                {{ $label }}
                            </label>

                            <select
                                name="{{ $field }}_status"
                                class="form-select mb-2"
                            >

                                <option value="">
                                    Select
                                </option>

                                <option value="Good">
                                    Good
                                </option>

                                <option value="Moderate">
                                    Moderate
                                </option>

                                <option value="Poor">
                                    Poor
                                </option>

                                <option value="Critical">
                                    Critical
                                </option>

                                <option value="Requires Mitigation">
                                    Requires Mitigation
                                </option>

                            </select>


                            <textarea
                                name="{{ $field }}_details"
                                class="form-control"
                                rows="4"
                                placeholder="{{ $label }} details..."
                            >{{ old(
                                $field . '_details'
                            ) }}</textarea>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Ecology & Biodiversity --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Ecology & Biodiversity</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-4">

                        <label class="form-label">
                            Ecological Status
                        </label>

                        <select
                            name="ecological_status"
                            class="form-select mb-2"
                        >

                            <option value="">
                                Select
                            </option>

                            <option value="Low Impact">
                                Low Impact
                            </option>

                            <option value="Moderate Impact">
                                Moderate Impact
                            </option>

                            <option value="High Impact">
                                High Impact
                            </option>

                            <option value="Critical">
                                Critical
                            </option>

                        </select>


                        <textarea
                            name="ecological_details"
                            class="form-control"
                            rows="5"
                            placeholder="Ecological details..."
                        >{{ old(
                            'ecological_details'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-6 mb-4">

                        <label class="form-label">
                            Biodiversity Status
                        </label>

                        <select
                            name="biodiversity_status"
                            class="form-select mb-2"
                        >

                            <option value="">
                                Select
                            </option>

                            <option value="Low Risk">
                                Low Risk
                            </option>

                            <option value="Moderate Risk">
                                Moderate Risk
                            </option>

                            <option value="High Risk">
                                High Risk
                            </option>

                            <option value="Critical">
                                Critical
                            </option>

                        </select>


                        <textarea
                            name="biodiversity_details"
                            class="form-control"
                            rows="5"
                            placeholder="Biodiversity details..."
                        >{{ old(
                            'biodiversity_details'
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Trees & Green Cover --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Trees & Green Cover</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-4">

                        <label class="form-label">
                            Tree Cutting Required
                        </label>

                        <select
                            name="tree_cutting_required"
                            class="form-select mb-2"
                        >

                            <option value="">
                                Select
                            </option>

                            <option value="No">
                                No
                            </option>

                            <option value="Yes">
                                Yes
                            </option>

                            <option value="Under Assessment">
                                Under Assessment
                            </option>

                        </select>


                        <textarea
                            name="tree_cutting_details"
                            class="form-control"
                            rows="4"
                            placeholder="Tree cutting details..."
                        >{{ old(
                            'tree_cutting_details'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-6 mb-4">

                        <label class="form-label">
                            Green Cover Status
                        </label>

                        <select
                            name="green_cover_status"
                            class="form-select mb-2"
                        >

                            <option value="">
                                Select
                            </option>

                            <option value="Adequate">
                                Adequate
                            </option>

                            <option value="Moderate">
                                Moderate
                            </option>

                            <option value="Low">
                                Low
                            </option>

                            <option value="Requires Improvement">
                                Requires Improvement
                            </option>

                        </select>


                        <textarea
                            name="green_cover_details"
                            class="form-control"
                            rows="4"
                            placeholder="Green cover details..."
                        >{{ old(
                            'green_cover_details'
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Waste Management --}}
        {{-- ===================================================== --}}

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

                            <label class="form-label">
                                {{ $label }}
                            </label>

                            <select
                                name="{{ $field }}_status"
                                class="form-select mb-2"
                            >

                                <option value="">
                                    Select
                                </option>

                                <option value="Adequate">
                                    Adequate
                                </option>

                                <option value="Inadequate">
                                    Inadequate
                                </option>

                                <option value="Available">
                                    Available
                                </option>

                                <option value="Not Available">
                                    Not Available
                                </option>

                                <option value="Requires Development">
                                    Requires Development
                                </option>

                            </select>


                            <textarea
                                name="{{ $field }}_details"
                                class="form-control"
                                rows="4"
                            >{{ old(
                                $field . '_details'
                            ) }}</textarea>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Pollution & Climate --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Pollution & Climate</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-4">

                        <label class="form-label">
                            Pollution Control Status
                        </label>

                        <select
                            name="pollution_control_status"
                            class="form-select mb-2"
                        >

                            <option value="">
                                Select
                            </option>

                            <option value="Adequate">
                                Adequate
                            </option>

                            <option value="Requires Improvement">
                                Requires Improvement
                            </option>

                            <option value="Inadequate">
                                Inadequate
                            </option>

                        </select>


                        <textarea
                            name="pollution_control_details"
                            class="form-control"
                            rows="4"
                        >{{ old(
                            'pollution_control_details'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-6 mb-4">

                        <label class="form-label">
                            Climate Impact
                        </label>

                        <select
                            name="climate_impact_status"
                            class="form-select mb-2"
                        >

                            <option value="">
                                Select
                            </option>

                            <option value="Low">
                                Low
                            </option>

                            <option value="Moderate">
                                Moderate
                            </option>

                            <option value="High">
                                High
                            </option>

                        </select>


                        <textarea
                            name="climate_impact_details"
                            class="form-control"
                            rows="4"
                        >{{ old(
                            'climate_impact_details'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Climate Resilience Measures
                        </label>

                        <textarea
                            name="climate_resilience_measures"
                            class="form-control"
                            rows="5"
                            placeholder="Describe climate resilience measures..."
                        >{{ old(
                            'climate_resilience_measures'
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Sustainability --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Sustainability & Green Development</strong>
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

                            <label class="form-label">
                                {{ $label }}
                            </label>

                            <select
                                name="{{ $field }}_status"
                                class="form-select mb-2"
                            >

                                <option value="">
                                    Select
                                </option>

                                <option value="High">
                                    High
                                </option>

                                <option value="Moderate">
                                    Moderate
                                </option>

                                <option value="Low">
                                    Low
                                </option>

                                <option value="Not Applicable">
                                    Not Applicable
                                </option>

                            </select>


                            <textarea
                                name="{{ $field }}_details"
                                class="form-control"
                                rows="4"
                                placeholder="{{ $label }} details..."
                            >{{ old(
                                $field . '_details'
                            ) }}</textarea>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Regulatory --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Environmental Regulatory Requirements</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Environmental Clearance
                        </label>

                        <select
                            name="environmental_clearance_status"
                            class="form-select"
                        >

                            <option value="">
                                Select
                            </option>

                            <option value="Obtained">
                                Obtained
                            </option>

                            <option value="Required">
                                Required
                            </option>

                            <option value="Not Required">
                                Not Required
                            </option>

                            <option value="Under Process">
                                Under Process
                            </option>

                        </select>

                    </div>


                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Clearance Details
                        </label>

                        <textarea
                            name="environmental_clearance_details"
                            class="form-control"
                            rows="3"
                        >{{ old(
                            'environmental_clearance_details'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Applicable Environmental Laws
                        </label>

                        <textarea
                            name="applicable_environmental_laws"
                            class="form-control"
                            rows="6"
                            placeholder="Enter applicable environmental laws, rules and regulations..."
                        >{{ old(
                            'applicable_environmental_laws'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Required Environmental Approvals
                        </label>

                        <textarea
                            name="required_environmental_approvals"
                            class="form-control"
                            rows="6"
                            placeholder="List required environmental approvals..."
                        >{{ old(
                            'required_environmental_approvals'
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Risks --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Environmental Risks & Mitigation</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Environmental Risks
                        </label>

                        <textarea
                            name="environmental_risks"
                            class="form-control"
                            rows="7"
                            placeholder="Identify environmental risks..."
                        >{{ old(
                            'environmental_risks'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Mitigation Measures
                        </label>

                        <textarea
                            name="mitigation_measures"
                            class="form-control"
                            rows="7"
                            placeholder="Enter mitigation measures..."
                        >{{ old(
                            'mitigation_measures'
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Findings --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Findings & Recommendation</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Key Environmental Findings
                        </label>

                        <textarea
                            name="key_environmental_findings"
                            class="form-control"
                            rows="7"
                            placeholder="Summarize key environmental findings..."
                        >{{ old(
                            'key_environmental_findings'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Recommendation
                        </label>

                        <textarea
                            name="recommendation"
                            class="form-control"
                            rows="7"
                            placeholder="Provide environmental recommendation..."
                        >{{ old(
                            'recommendation'
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Actions --}}
        {{-- ===================================================== --}}

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
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Environmental Feasibility
            </button>

        </div>

    </form>

</div>

@endsection