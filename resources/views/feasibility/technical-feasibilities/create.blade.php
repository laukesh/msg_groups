@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                New Technical Feasibility
            </h3>

            <p class="text-muted mb-0">

                {{ $feasibilityAssessment->assessment_number }}
                -
                {{ $feasibilityAssessment->title }}

            </p>

        </div>


        <a
            href="{{ route(
                'admin.land.lands.feasibility-assessments.technical-feasibilities.index',
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


    {{-- ========================================================= --}}
    {{-- Form --}}
    {{-- ========================================================= --}}

    <form
        action="{{ route(
            'admin.land.lands.feasibility-assessments.technical-feasibilities.store',
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
                            placeholder="Enter technical feasibility title"
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
        {{-- Site Development --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Site Development & Ground Conditions
                </strong>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Site Development --}}
                    <div class="col-md-4 mb-4">

                        <label class="form-label">
                            Site Development Status
                        </label>

                        <select
                            name="site_development_status"
                            class="form-select mb-2"
                        >

                            <option value="">
                                Select Status
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

                            <option value="Pending Study">
                                Pending Study
                            </option>

                        </select>


                        <textarea
                            name="site_development_details"
                            class="form-control"
                            rows="5"
                            placeholder="Enter site development details..."
                        >{{ old(
                            'site_development_details'
                        ) }}</textarea>

                    </div>


                    {{-- Topography --}}
                    <div class="col-md-4 mb-4">

                        <label class="form-label">
                            Site Topography
                        </label>

                        <select
                            name="site_topography"
                            class="form-select mb-2"
                        >

                            <option value="">
                                Select
                            </option>

                            <option value="Flat">
                                Flat
                            </option>

                            <option value="Sloping">
                                Sloping
                            </option>

                            <option value="Undulating">
                                Undulating
                            </option>

                            <option value="Steep">
                                Steep
                            </option>

                        </select>


                        <textarea
                            name="site_topography_details"
                            class="form-control"
                            rows="5"
                            placeholder="Topography details..."
                        >{{ old(
                            'site_topography_details'
                        ) }}</textarea>

                    </div>


                    {{-- Soil --}}
                    <div class="col-md-4 mb-4">

                        <label class="form-label">
                            Soil Condition
                        </label>

                        <select
                            name="soil_condition"
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

                            <option value="Unknown">
                                Unknown
                            </option>

                        </select>


                        <textarea
                            name="soil_condition_details"
                            class="form-control"
                            rows="5"
                            placeholder="Soil condition details..."
                        >{{ old(
                            'soil_condition_details'
                        ) }}</textarea>

                    </div>


                    {{-- Geotechnical --}}
                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Geotechnical Study Status
                        </label>

                        <select
                            name="geotechnical_status"
                            class="form-select mb-2"
                        >

                            <option value="">
                                Select Status
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


                        <textarea
                            name="geotechnical_details"
                            class="form-control"
                            rows="4"
                            placeholder="Enter geotechnical findings..."
                        >{{ old(
                            'geotechnical_details'
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Construction --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Construction Feasibility</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Construction Feasibility
                        </label>

                        <select
                            name="construction_feasibility_status"
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
                            Construction Method
                        </label>

                        <input
                            type="text"
                            name="construction_method"
                            class="form-control"
                            value="{{ old(
                                'construction_method'
                            ) }}"
                            placeholder="e.g. RCC, Steel, Precast"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Construction Period
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                name="construction_period"
                                class="form-control"
                                value="{{ old(
                                    'construction_period'
                                ) }}"
                                min="0"
                                step="0.01"
                            >

                            <span class="input-group-text">
                                Months
                            </span>

                        </div>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Construction Details
                        </label>

                        <textarea
                            name="construction_feasibility_details"
                            class="form-control"
                            rows="5"
                            placeholder="Construction feasibility details..."
                        >{{ old(
                            'construction_feasibility_details'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Construction Method Details
                        </label>

                        <textarea
                            name="construction_method_details"
                            class="form-control"
                            rows="5"
                            placeholder="Construction method details..."
                        >{{ old(
                            'construction_method_details'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Construction Constraints
                        </label>

                        <textarea
                            name="construction_constraints"
                            class="form-control"
                            rows="4"
                            placeholder="Enter construction constraints..."
                        >{{ old(
                            'construction_constraints'
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Infrastructure --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Infrastructure</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Infrastructure --}}
                    <div class="col-md-4 mb-4">

                        <label class="form-label">
                            Infrastructure Status
                        </label>

                        <select
                            name="infrastructure_status"
                            class="form-select mb-2"
                        >

                            <option value="">
                                Select
                            </option>

                            <option value="Adequate">
                                Adequate
                            </option>

                            <option value="Partially Adequate">
                                Partially Adequate
                            </option>

                            <option value="Inadequate">
                                Inadequate
                            </option>

                            <option value="Requires Development">
                                Requires Development
                            </option>

                        </select>


                        <textarea
                            name="infrastructure_details"
                            class="form-control"
                            rows="5"
                            placeholder="Infrastructure details..."
                        >{{ old(
                            'infrastructure_details'
                        ) }}</textarea>

                    </div>


                    {{-- Road --}}
                    <div class="col-md-4 mb-4">

                        <label class="form-label">
                            Road Access
                        </label>

                        <select
                            name="road_access_status"
                            class="form-select mb-2"
                        >

                            <option value="">
                                Select
                            </option>

                            <option value="Good">
                                Good
                            </option>

                            <option value="Adequate">
                                Adequate
                            </option>

                            <option value="Poor">
                                Poor
                            </option>

                            <option value="Requires Improvement">
                                Requires Improvement
                            </option>

                        </select>


                        <textarea
                            name="road_access_details"
                            class="form-control"
                            rows="5"
                            placeholder="Road access details..."
                        >{{ old(
                            'road_access_details'
                        ) }}</textarea>

                    </div>


                    {{-- Drainage --}}
                    <div class="col-md-4 mb-4">

                        <label class="form-label">
                            Drainage
                        </label>

                        <select
                            name="drainage_status"
                            class="form-select mb-2"
                        >

                            <option value="">
                                Select
                            </option>

                            <option value="Available">
                                Available
                            </option>

                            <option value="Adequate">
                                Adequate
                            </option>

                            <option value="Inadequate">
                                Inadequate
                            </option>

                            <option value="Requires Development">
                                Requires Development
                            </option>

                        </select>


                        <textarea
                            name="drainage_details"
                            class="form-control"
                            rows="5"
                            placeholder="Drainage details..."
                        >{{ old(
                            'drainage_details'
                        ) }}</textarea>

                    </div>


                    {{-- Sewerage --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Sewerage
                        </label>

                        <select
                            name="sewerage_status"
                            class="form-select mb-2"
                        >

                            <option value="">
                                Select
                            </option>

                            <option value="Available">
                                Available
                            </option>

                            <option value="Adequate">
                                Adequate
                            </option>

                            <option value="Inadequate">
                                Inadequate
                            </option>

                            <option value="Requires Development">
                                Requires Development
                            </option>

                        </select>


                        <textarea
                            name="sewerage_details"
                            class="form-control"
                            rows="4"
                        >{{ old(
                            'sewerage_details'
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Utilities --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Utilities</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    @php
                        $utilities = [
                            'electricity' => 'Electricity',
                            'water_supply' => 'Water Supply',
                            'gas_supply' => 'Gas Supply',
                            'telecommunications' => 'Telecommunications',
                        ];
                    @endphp


                    @foreach($utilities as $field => $label)

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

                                <option value="Available">
                                    Available
                                </option>

                                <option value="Adequate">
                                    Adequate
                                </option>

                                <option value="Inadequate">
                                    Inadequate
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
        {{-- Access & Connectivity --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Access & Connectivity
                </strong>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-4">

                        <label class="form-label">
                            Transportation Access
                        </label>

                        <select
                            name="transportation_access_status"
                            class="form-select mb-2"
                        >

                            <option value="">
                                Select
                            </option>

                            <option value="Excellent">
                                Excellent
                            </option>

                            <option value="Good">
                                Good
                            </option>

                            <option value="Adequate">
                                Adequate
                            </option>

                            <option value="Poor">
                                Poor
                            </option>

                        </select>


                        <textarea
                            name="transportation_access_details"
                            class="form-control"
                            rows="4"
                            placeholder="Transportation access details..."
                        >{{ old(
                            'transportation_access_details'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-6 mb-4">

                        <label class="form-label">
                            Public Transport
                        </label>

                        <select
                            name="public_transport_status"
                            class="form-select mb-2"
                        >

                            <option value="">
                                Select
                            </option>

                            <option value="Excellent">
                                Excellent
                            </option>

                            <option value="Good">
                                Good
                            </option>

                            <option value="Adequate">
                                Adequate
                            </option>

                            <option value="Poor">
                                Poor
                            </option>

                            <option value="Not Available">
                                Not Available
                            </option>

                        </select>


                        <textarea
                            name="public_transport_details"
                            class="form-control"
                            rows="4"
                            placeholder="Public transport details..."
                        >{{ old(
                            'public_transport_details'
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Development Parameters --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Development Parameters
                </strong>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Permissible FSI
                        </label>

                        <input
                            type="number"
                            name="permissible_fsi"
                            class="form-control"
                            value="{{ old(
                                'permissible_fsi'
                            ) }}"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Ground Coverage
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                name="permissible_ground_coverage"
                                class="form-control"
                                value="{{ old(
                                    'permissible_ground_coverage'
                                ) }}"
                                min="0"
                                step="0.01"
                            >

                            <span class="input-group-text">
                                %
                            </span>

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Permissible Height
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                name="permissible_height"
                                class="form-control"
                                value="{{ old(
                                    'permissible_height'
                                ) }}"
                                min="0"
                                step="0.01"
                            >

                            <span class="input-group-text">
                                m
                            </span>

                        </div>

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Development Constraints
                        </label>

                        <textarea
                            name="development_constraints"
                            class="form-control"
                            rows="5"
                            placeholder="Enter development restrictions and constraints..."
                        >{{ old(
                            'development_constraints'
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Construction Technology --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Construction Technology
                </strong>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Technology Status
                        </label>

                        <select
                            name="technology_status"
                            class="form-select"
                        >

                            <option value="">
                                Select
                            </option>

                            <option value="Suitable">
                                Suitable
                            </option>

                            <option value="Conditionally Suitable">
                                Conditionally Suitable
                            </option>

                            <option value="Not Suitable">
                                Not Suitable
                            </option>

                            <option value="Under Evaluation">
                                Under Evaluation
                            </option>

                        </select>

                    </div>


                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Proposed Construction Technology
                        </label>

                        <input
                            type="text"
                            name="proposed_construction_technology"
                            class="form-control"
                            value="{{ old(
                                'proposed_construction_technology'
                            ) }}"
                            placeholder="e.g. RCC framed structure / Precast / Steel"
                        >

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Technology Details
                        </label>

                        <textarea
                            name="technology_details"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'technology_details'
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Implementation --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Project Implementation
                </strong>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Implementation Feasibility
                        </label>

                        <select
                            name="implementation_feasibility_status"
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
                            Implementation Period
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                name="estimated_implementation_period"
                                class="form-control"
                                value="{{ old(
                                    'estimated_implementation_period'
                                ) }}"
                                min="0"
                                step="0.01"
                            >

                            <span class="input-group-text">
                                Months
                            </span>

                        </div>

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Implementation Details
                        </label>

                        <textarea
                            name="implementation_details"
                            class="form-control"
                            rows="5"
                            placeholder="Describe implementation approach, sequencing and requirements..."
                        >{{ old(
                            'implementation_details'
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
                    Technical Risks & Mitigation
                </strong>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Technical Risks
                        </label>

                        <textarea
                            name="technical_risks"
                            class="form-control"
                            rows="7"
                            placeholder="Identify technical risks..."
                        >{{ old(
                            'technical_risks'
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

                <strong>
                    Findings & Recommendation
                </strong>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Key Technical Findings
                        </label>

                        <textarea
                            name="key_technical_findings"
                            class="form-control"
                            rows="7"
                            placeholder="Summarize key technical findings..."
                        >{{ old(
                            'key_technical_findings'
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
                            placeholder="Provide technical recommendation..."
                        >{{ old(
                            'recommendation'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Overall Technical Score
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                name="overall_technical_score"
                                class="form-control"
                                value="{{ old(
                                    'overall_technical_score'
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

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Actions --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.technical-feasibilities.index',
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
                Save Technical Feasibility
            </button>

        </div>

    </form>

</div>

@endsection