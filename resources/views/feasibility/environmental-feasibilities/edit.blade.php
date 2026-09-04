@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Edit Environmental Feasibility
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
                    'admin.land.lands.feasibility-assessments.environmental-feasibilities.show',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' => $feasibilityAssessment->id,
                        'environmentalFeasibility' => $environmentalFeasibility->id,
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

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Form --}}
    {{-- ========================================================= --}}

    <form
        action="{{ route(
            'admin.land.lands.feasibility-assessments.environmental-feasibilities.update',
            [
                'land' => $land->id,
                'feasibilityAssessment' => $feasibilityAssessment->id,
                'environmentalFeasibility' => $environmentalFeasibility->id,
            ]
        ) }}"
        method="POST"
    >

        @csrf

        @method('PUT')


        {{-- ===================================================== --}}
        {{-- Basic Information --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Basic Information</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Analysis Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $environmentalFeasibility->analysis_number }}"
                            readonly
                        >

                    </div>


                    <div class="col-md-5 mb-3">

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
                                $environmentalFeasibility->title
                            ) }}"
                            required
                        >

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Status
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
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ old(
                                        'status',
                                        $environmentalFeasibility->status
                                    ) === $status
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

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

                            @foreach([
                                'Feasible',
                                'Conditionally Feasible',
                                'Not Feasible',
                                'Under Study'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    {{ old(
                                        'environmental_status',
                                        $environmentalFeasibility
                                            ->environmental_status
                                    ) === $value
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $value }}
                                </option>

                            @endforeach

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

                            @foreach([
                                'Completed',
                                'In Progress',
                                'Required',
                                'Not Required'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    {{ old(
                                        'environmental_impact_assessment_status',
                                        $environmentalFeasibility
                                            ->environmental_impact_assessment_status
                                    ) === $value
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $value }}
                                </option>

                            @endforeach

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
                                    'overall_environmental_score',
                                    $environmentalFeasibility
                                        ->overall_environmental_score
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
                        >{{ old(
                            'environmental_overview',
                            $environmentalFeasibility
                                ->environmental_overview
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
                        >{{ old(
                            'environmental_impact_assessment_details',
                            $environmentalFeasibility
                                ->environmental_impact_assessment_details
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

                        $qualityFields = [

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
                        $qualityFields
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

                                @foreach([
                                    'Good',
                                    'Moderate',
                                    'Poor',
                                    'Critical',
                                    'Requires Mitigation'
                                ] as $value)

                                    <option
                                        value="{{ $value }}"
                                        {{ old(
                                            $field . '_status',
                                            $environmentalFeasibility
                                                ->{$field . '_status'}
                                        ) === $value
                                            ? 'selected'
                                            : ''
                                        }}
                                    >
                                        {{ $value }}
                                    </option>

                                @endforeach

                            </select>


                            <textarea
                                name="{{ $field }}_details"
                                class="form-control"
                                rows="4"
                            >{{ old(
                                $field . '_details',
                                $environmentalFeasibility
                                    ->{$field . '_details'}
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

                            @foreach([
                                'Low Impact',
                                'Moderate Impact',
                                'High Impact',
                                'Critical'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    {{ old(
                                        'ecological_status',
                                        $environmentalFeasibility
                                            ->ecological_status
                                    ) === $value
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>


                        <textarea
                            name="ecological_details"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'ecological_details',
                            $environmentalFeasibility
                                ->ecological_details
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

                            @foreach([
                                'Low Risk',
                                'Moderate Risk',
                                'High Risk',
                                'Critical'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    {{ old(
                                        'biodiversity_status',
                                        $environmentalFeasibility
                                            ->biodiversity_status
                                    ) === $value
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>


                        <textarea
                            name="biodiversity_details"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'biodiversity_details',
                            $environmentalFeasibility
                                ->biodiversity_details
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

                            @foreach([
                                'No',
                                'Yes',
                                'Under Assessment'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    {{ old(
                                        'tree_cutting_required',
                                        $environmentalFeasibility
                                            ->tree_cutting_required
                                    ) === $value
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>


                        <textarea
                            name="tree_cutting_details"
                            class="form-control"
                            rows="4"
                        >{{ old(
                            'tree_cutting_details',
                            $environmentalFeasibility
                                ->tree_cutting_details
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

                            @foreach([
                                'Adequate',
                                'Moderate',
                                'Low',
                                'Requires Improvement'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    {{ old(
                                        'green_cover_status',
                                        $environmentalFeasibility
                                            ->green_cover_status
                                    ) === $value
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>


                        <textarea
                            name="green_cover_details"
                            class="form-control"
                            rows="4"
                        >{{ old(
                            'green_cover_details',
                            $environmentalFeasibility
                                ->green_cover_details
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

                                @foreach([
                                    'Adequate',
                                    'Inadequate',
                                    'Available',
                                    'Not Available',
                                    'Requires Development'
                                ] as $value)

                                    <option
                                        value="{{ $value }}"
                                        {{ old(
                                            $field . '_status',
                                            $environmentalFeasibility
                                                ->{$field . '_status'}
                                        ) === $value
                                            ? 'selected'
                                            : ''
                                        }}
                                    >
                                        {{ $value }}
                                    </option>

                                @endforeach

                            </select>


                            <textarea
                                name="{{ $field }}_details"
                                class="form-control"
                                rows="4"
                            >{{ old(
                                $field . '_details',
                                $environmentalFeasibility
                                    ->{$field . '_details'}
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

                            @foreach([
                                'Adequate',
                                'Requires Improvement',
                                'Inadequate'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    {{ old(
                                        'pollution_control_status',
                                        $environmentalFeasibility
                                            ->pollution_control_status
                                    ) === $value
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>


                        <textarea
                            name="pollution_control_details"
                            class="form-control"
                            rows="4"
                        >{{ old(
                            'pollution_control_details',
                            $environmentalFeasibility
                                ->pollution_control_details
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

                            @foreach([
                                'Low',
                                'Moderate',
                                'High'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    {{ old(
                                        'climate_impact_status',
                                        $environmentalFeasibility
                                            ->climate_impact_status
                                    ) === $value
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>


                        <textarea
                            name="climate_impact_details"
                            class="form-control"
                            rows="4"
                        >{{ old(
                            'climate_impact_details',
                            $environmentalFeasibility
                                ->climate_impact_details
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
                        >{{ old(
                            'climate_resilience_measures',
                            $environmentalFeasibility
                                ->climate_resilience_measures
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

                                @foreach([
                                    'High',
                                    'Moderate',
                                    'Low',
                                    'Not Applicable'
                                ] as $value)

                                    <option
                                        value="{{ $value }}"
                                        {{ old(
                                            $field . '_status',
                                            $environmentalFeasibility
                                                ->{$field . '_status'}
                                        ) === $value
                                            ? 'selected'
                                            : ''
                                        }}
                                    >
                                        {{ $value }}
                                    </option>

                                @endforeach

                            </select>


                            <textarea
                                name="{{ $field }}_details"
                                class="form-control"
                                rows="4"
                            >{{ old(
                                $field . '_details',
                                $environmentalFeasibility
                                    ->{$field . '_details'}
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
                <strong>
                    Environmental Regulatory Requirements
                </strong>
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

                            @foreach([
                                'Obtained',
                                'Required',
                                'Not Required',
                                'Under Process'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    {{ old(
                                        'environmental_clearance_status',
                                        $environmentalFeasibility
                                            ->environmental_clearance_status
                                    ) === $value
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $value }}
                                </option>

                            @endforeach

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
                            'environmental_clearance_details',
                            $environmentalFeasibility
                                ->environmental_clearance_details
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
                        >{{ old(
                            'applicable_environmental_laws',
                            $environmentalFeasibility
                                ->applicable_environmental_laws
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
                        >{{ old(
                            'required_environmental_approvals',
                            $environmentalFeasibility
                                ->required_environmental_approvals
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
                <strong>
                    Environmental Risks & Mitigation
                </strong>
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
                        >{{ old(
                            'environmental_risks',
                            $environmentalFeasibility
                                ->environmental_risks
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
                        >{{ old(
                            'mitigation_measures',
                            $environmentalFeasibility
                                ->mitigation_measures
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
                <strong>
                    Findings & Recommendation
                </strong>
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
                        >{{ old(
                            'key_environmental_findings',
                            $environmentalFeasibility
                                ->key_environmental_findings
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
                        >{{ old(
                            'recommendation',
                            $environmentalFeasibility
                                ->recommendation
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
                    'admin.land.lands.feasibility-assessments.environmental-feasibilities.show',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'environmentalFeasibility' =>
                            $environmentalFeasibility->id,
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
                Update Environmental Feasibility
            </button>

        </div>

    </form>

</div>

@endsection