@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Edit Technical Feasibility
            </h3>

            <p class="text-muted mb-0">

                {{ $technicalFeasibility->analysis_number }}
                -
                {{ $technicalFeasibility->title }}

            </p>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.technical-feasibilities.show',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' => $feasibilityAssessment->id,
                        'technicalFeasibility' => $technicalFeasibility->id,
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
            'admin.land.lands.feasibility-assessments.technical-feasibilities.update',
            [
                'land' => $land->id,
                'feasibilityAssessment' => $feasibilityAssessment->id,
                'technicalFeasibility' => $technicalFeasibility->id,
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
                            value="{{ $technicalFeasibility->analysis_number }}"
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
                                $technicalFeasibility->title
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
                                        $technicalFeasibility->status
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

                            @foreach([
                                'Feasible',
                                'Conditionally Feasible',
                                'Not Feasible',
                                'Pending Study'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    {{ old(
                                        'site_development_status',
                                        $technicalFeasibility
                                            ->site_development_status
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
                            name="site_development_details"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'site_development_details',
                            $technicalFeasibility
                                ->site_development_details
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

                            @foreach([
                                'Flat',
                                'Sloping',
                                'Undulating',
                                'Steep'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    {{ old(
                                        'site_topography',
                                        $technicalFeasibility
                                            ->site_topography
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
                            name="site_topography_details"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'site_topography_details',
                            $technicalFeasibility
                                ->site_topography_details
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

                            @foreach([
                                'Good',
                                'Moderate',
                                'Poor',
                                'Unknown'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    {{ old(
                                        'soil_condition',
                                        $technicalFeasibility
                                            ->soil_condition
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
                            name="soil_condition_details"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'soil_condition_details',
                            $technicalFeasibility
                                ->soil_condition_details
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

                            @foreach([
                                'Completed',
                                'In Progress',
                                'Required',
                                'Not Required'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    {{ old(
                                        'geotechnical_status',
                                        $technicalFeasibility
                                            ->geotechnical_status
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
                            name="geotechnical_details"
                            class="form-control"
                            rows="4"
                        >{{ old(
                            'geotechnical_details',
                            $technicalFeasibility
                                ->geotechnical_details
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

                            @foreach([
                                'Feasible',
                                'Conditionally Feasible',
                                'Not Feasible',
                                'Under Study'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    {{ old(
                                        'construction_feasibility_status',
                                        $technicalFeasibility
                                            ->construction_feasibility_status
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
                            Construction Method
                        </label>

                        <input
                            type="text"
                            name="construction_method"
                            class="form-control"
                            value="{{ old(
                                'construction_method',
                                $technicalFeasibility
                                    ->construction_method
                            ) }}"
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
                                    'construction_period',
                                    $technicalFeasibility
                                        ->construction_period
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
                        >{{ old(
                            'construction_feasibility_details',
                            $technicalFeasibility
                                ->construction_feasibility_details
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
                        >{{ old(
                            'construction_method_details',
                            $technicalFeasibility
                                ->construction_method_details
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
                        >{{ old(
                            'construction_constraints',
                            $technicalFeasibility
                                ->construction_constraints
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

                    @php
                        $infrastructure = [
                            'infrastructure' => 'Infrastructure',
                            'road_access' => 'Road Access',
                            'drainage' => 'Drainage',
                            'sewerage' => 'Sewerage',
                        ];
                    @endphp


                    @foreach(
                        $infrastructure as $field => $label
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
                                    'Available',
                                    'Adequate',
                                    'Partially Adequate',
                                    'Inadequate',
                                    'Good',
                                    'Poor',
                                    'Requires Development',
                                    'Requires Improvement'
                                ] as $value)

                                    <option
                                        value="{{ $value }}"
                                        {{ old(
                                            $field . '_status',
                                            $technicalFeasibility
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
                                $technicalFeasibility
                                    ->{$field . '_details'}
                            ) }}</textarea>

                        </div>

                    @endforeach

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

                                @foreach([
                                    'Available',
                                    'Adequate',
                                    'Inadequate',
                                    'Not Available',
                                    'Requires Development'
                                ] as $value)

                                    <option
                                        value="{{ $value }}"
                                        {{ old(
                                            $field . '_status',
                                            $technicalFeasibility
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
                                $technicalFeasibility
                                    ->{$field . '_details'}
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
                <strong>Access & Connectivity</strong>
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

                            @foreach([
                                'Excellent',
                                'Good',
                                'Adequate',
                                'Poor'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    {{ old(
                                        'transportation_access_status',
                                        $technicalFeasibility
                                            ->transportation_access_status
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
                            name="transportation_access_details"
                            class="form-control"
                            rows="4"
                        >{{ old(
                            'transportation_access_details',
                            $technicalFeasibility
                                ->transportation_access_details
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

                            @foreach([
                                'Excellent',
                                'Good',
                                'Adequate',
                                'Poor',
                                'Not Available'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    {{ old(
                                        'public_transport_status',
                                        $technicalFeasibility
                                            ->public_transport_status
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
                            name="public_transport_details"
                            class="form-control"
                            rows="4"
                        >{{ old(
                            'public_transport_details',
                            $technicalFeasibility
                                ->public_transport_details
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
                <strong>Development Parameters</strong>
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
                                'permissible_fsi',
                                $technicalFeasibility
                                    ->permissible_fsi
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
                                    'permissible_ground_coverage',
                                    $technicalFeasibility
                                        ->permissible_ground_coverage
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
                                    'permissible_height',
                                    $technicalFeasibility
                                        ->permissible_height
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
                        >{{ old(
                            'development_constraints',
                            $technicalFeasibility
                                ->development_constraints
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
                <strong>Construction Technology</strong>
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

                            @foreach([
                                'Suitable',
                                'Conditionally Suitable',
                                'Not Suitable',
                                'Under Evaluation'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    {{ old(
                                        'technology_status',
                                        $technicalFeasibility
                                            ->technology_status
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
                            Proposed Construction Technology
                        </label>

                        <input
                            type="text"
                            name="proposed_construction_technology"
                            class="form-control"
                            value="{{ old(
                                'proposed_construction_technology',
                                $technicalFeasibility
                                    ->proposed_construction_technology
                            ) }}"
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
                            'technology_details',
                            $technicalFeasibility
                                ->technology_details
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
                <strong>Project Implementation</strong>
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

                            @foreach([
                                'Feasible',
                                'Conditionally Feasible',
                                'Not Feasible',
                                'Under Study'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    {{ old(
                                        'implementation_feasibility_status',
                                        $technicalFeasibility
                                            ->implementation_feasibility_status
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
                            Estimated Implementation Period
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                name="estimated_implementation_period"
                                class="form-control"
                                value="{{ old(
                                    'estimated_implementation_period',
                                    $technicalFeasibility
                                        ->estimated_implementation_period
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
                        >{{ old(
                            'implementation_details',
                            $technicalFeasibility
                                ->implementation_details
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
                <strong>Technical Risks & Mitigation</strong>
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
                        >{{ old(
                            'technical_risks',
                            $technicalFeasibility
                                ->technical_risks
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
                            $technicalFeasibility
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
                <strong>Findings & Recommendation</strong>
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
                        >{{ old(
                            'key_technical_findings',
                            $technicalFeasibility
                                ->key_technical_findings
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
                            $technicalFeasibility
                                ->recommendation
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
                                    'overall_technical_score',
                                    $technicalFeasibility
                                        ->overall_technical_score
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

        <div class="d-flex justify-content-between mb-5">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.technical-feasibilities.show',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'technicalFeasibility' =>
                            $technicalFeasibility->id,
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
                Update Technical Feasibility
            </button>

        </div>

    </form>

</div>

@endsection