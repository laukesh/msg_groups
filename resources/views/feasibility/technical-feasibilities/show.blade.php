@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Technical Feasibility
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


            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.technical-feasibilities.edit',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'technicalFeasibility' =>
                            $technicalFeasibility->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Success --}}
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

        <div class="card-header">

            <strong>
                Basic Information
            </strong>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Analysis Number
                    </label>

                    <div class="fw-bold">
                        {{ $technicalFeasibility->analysis_number }}
                    </div>

                </div>


                <div class="col-md-5 mb-3">

                    <label class="text-muted small">
                        Title
                    </label>

                    <div class="fw-bold">
                        {{ $technicalFeasibility->title }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted small">
                        Status
                    </label>

                    <div>

                        @if(
                            $technicalFeasibility->status === 'Draft'
                        )

                            <span class="badge bg-secondary">
                                Draft
                            </span>

                        @elseif(
                            $technicalFeasibility->status === 'Submitted'
                        )

                            <span class="badge bg-warning text-dark">
                                Submitted
                            </span>

                        @elseif(
                            $technicalFeasibility->status === 'Approved'
                        )

                            <span class="badge bg-success">
                                Approved
                            </span>

                        @elseif(
                            $technicalFeasibility->status === 'Rejected'
                        )

                            <span class="badge bg-danger">
                                Rejected
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                {{ $technicalFeasibility->status }}
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Site Development --}}
    {{-- ========================================================= --}}

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

                    <label class="text-muted small">
                        Site Development Status
                    </label>

                    <div class="mb-2">
                        {{ $technicalFeasibility->site_development_status ?? '-' }}
                    </div>

                    <label class="text-muted small">
                        Details
                    </label>

                    <div>
                        {!! nl2br(e(
                            $technicalFeasibility
                                ->site_development_details
                                ?? '-'
                        )) !!}
                    </div>

                </div>


                {{-- Topography --}}
                <div class="col-md-4 mb-4">

                    <label class="text-muted small">
                        Site Topography
                    </label>

                    <div class="mb-2">
                        {{ $technicalFeasibility->site_topography ?? '-' }}
                    </div>

                    <label class="text-muted small">
                        Details
                    </label>

                    <div>
                        {!! nl2br(e(
                            $technicalFeasibility
                                ->site_topography_details
                                ?? '-'
                        )) !!}
                    </div>

                </div>


                {{-- Soil --}}
                <div class="col-md-4 mb-4">

                    <label class="text-muted small">
                        Soil Condition
                    </label>

                    <div class="mb-2">
                        {{ $technicalFeasibility->soil_condition ?? '-' }}
                    </div>

                    <label class="text-muted small">
                        Details
                    </label>

                    <div>
                        {!! nl2br(e(
                            $technicalFeasibility
                                ->soil_condition_details
                                ?? '-'
                        )) !!}
                    </div>

                </div>


                {{-- Geotechnical --}}
                <div class="col-md-12">

                    <label class="text-muted small">
                        Geotechnical Study Status
                    </label>

                    <div class="mb-2">

                        {{ $technicalFeasibility->geotechnical_status ?? '-' }}

                    </div>

                    <label class="text-muted small">
                        Geotechnical Details
                    </label>

                    <div>

                        {!! nl2br(e(
                            $technicalFeasibility
                                ->geotechnical_details
                                ?? '-'
                        )) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Construction --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Construction Feasibility
            </strong>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Construction Feasibility
                    </label>

                    <div>
                        {{ $technicalFeasibility
                            ->construction_feasibility_status
                            ?? '-'
                        }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Construction Method
                    </label>

                    <div>
                        {{ $technicalFeasibility
                            ->construction_method
                            ?? '-'
                        }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Construction Period
                    </label>

                    <div>

                        @if(
                            $technicalFeasibility
                                ->construction_period !== null
                        )

                            {{
                                number_format(
                                    $technicalFeasibility
                                        ->construction_period,
                                    2
                                )
                            }}
                            Months

                        @else

                            -

                        @endif

                    </div>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="text-muted small">
                        Construction Details
                    </label>

                    <div>
                        {!! nl2br(e(
                            $technicalFeasibility
                                ->construction_feasibility_details
                                ?? '-'
                        )) !!}
                    </div>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="text-muted small">
                        Construction Method Details
                    </label>

                    <div>
                        {!! nl2br(e(
                            $technicalFeasibility
                                ->construction_method_details
                                ?? '-'
                        )) !!}
                    </div>

                </div>


                <div class="col-md-12">

                    <label class="text-muted small">
                        Construction Constraints
                    </label>

                    <div>
                        {!! nl2br(e(
                            $technicalFeasibility
                                ->construction_constraints
                                ?? '-'
                        )) !!}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Infrastructure --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Infrastructure
            </strong>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">

                    <label class="text-muted small">
                        Infrastructure
                    </label>

                    <div>
                        {{ $technicalFeasibility
                            ->infrastructure_status
                            ?? '-'
                        }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted small">
                        Road Access
                    </label>

                    <div>
                        {{ $technicalFeasibility
                            ->road_access_status
                            ?? '-'
                        }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted small">
                        Drainage
                    </label>

                    <div>
                        {{ $technicalFeasibility
                            ->drainage_status
                            ?? '-'
                        }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted small">
                        Sewerage
                    </label>

                    <div>
                        {{ $technicalFeasibility
                            ->sewerage_status
                            ?? '-'
                        }}
                    </div>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="text-muted small">
                        Infrastructure Details
                    </label>

                    <div>
                        {!! nl2br(e(
                            $technicalFeasibility
                                ->infrastructure_details
                                ?? '-'
                        )) !!}
                    </div>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="text-muted small">
                        Road Access Details
                    </label>

                    <div>
                        {!! nl2br(e(
                            $technicalFeasibility
                                ->road_access_details
                                ?? '-'
                        )) !!}
                    </div>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="text-muted small">
                        Drainage Details
                    </label>

                    <div>
                        {!! nl2br(e(
                            $technicalFeasibility
                                ->drainage_details
                                ?? '-'
                        )) !!}
                    </div>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="text-muted small">
                        Sewerage Details
                    </label>

                    <div>
                        {!! nl2br(e(
                            $technicalFeasibility
                                ->sewerage_details
                                ?? '-'
                        )) !!}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Utilities --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Utilities
            </strong>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Utility
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Details
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr>

                            <td>
                                Electricity
                            </td>

                            <td>
                                {{ $technicalFeasibility
                                    ->electricity_status
                                    ?? '-'
                                }}
                            </td>

                            <td>
                                {!! nl2br(e(
                                    $technicalFeasibility
                                        ->electricity_details
                                        ?? '-'
                                )) !!}
                            </td>

                        </tr>


                        <tr>

                            <td>
                                Water Supply
                            </td>

                            <td>
                                {{ $technicalFeasibility
                                    ->water_supply_status
                                    ?? '-'
                                }}
                            </td>

                            <td>
                                {!! nl2br(e(
                                    $technicalFeasibility
                                        ->water_supply_details
                                        ?? '-'
                                )) !!}
                            </td>

                        </tr>


                        <tr>

                            <td>
                                Gas Supply
                            </td>

                            <td>
                                {{ $technicalFeasibility
                                    ->gas_supply_status
                                    ?? '-'
                                }}
                            </td>

                            <td>
                                {!! nl2br(e(
                                    $technicalFeasibility
                                        ->gas_supply_details
                                        ?? '-'
                                )) !!}
                            </td>

                        </tr>


                        <tr>

                            <td>
                                Telecommunications
                            </td>

                            <td>
                                {{ $technicalFeasibility
                                    ->telecommunications_status
                                    ?? '-'
                                }}
                            </td>

                            <td>
                                {!! nl2br(e(
                                    $technicalFeasibility
                                        ->telecommunications_details
                                        ?? '-'
                                )) !!}
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Access & Connectivity --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Access & Connectivity
            </strong>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="text-muted small">
                        Transportation Access
                    </label>

                    <div class="fw-bold mb-2">

                        {{
                            $technicalFeasibility
                                ->transportation_access_status
                            ?? '-'
                        }}

                    </div>

                    <div>

                        {!! nl2br(e(
                            $technicalFeasibility
                                ->transportation_access_details
                                ?? '-'
                        )) !!}

                    </div>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="text-muted small">
                        Public Transport
                    </label>

                    <div class="fw-bold mb-2">

                        {{
                            $technicalFeasibility
                                ->public_transport_status
                            ?? '-'
                        }}

                    </div>

                    <div>

                        {!! nl2br(e(
                            $technicalFeasibility
                                ->public_transport_details
                                ?? '-'
                        )) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Development Parameters --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Development Parameters
            </strong>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Permissible FSI
                    </label>

                    <div class="fw-bold">

                        {{
                            $technicalFeasibility->permissible_fsi !== null
                            ? number_format(
                                $technicalFeasibility
                                    ->permissible_fsi,
                                2
                            )
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Ground Coverage
                    </label>

                    <div class="fw-bold">

                        @if(
                            $technicalFeasibility
                                ->permissible_ground_coverage !== null
                        )

                            {{
                                number_format(
                                    $technicalFeasibility
                                        ->permissible_ground_coverage,
                                    2
                                )
                            }} %

                        @else

                            -

                        @endif

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Permissible Height
                    </label>

                    <div class="fw-bold">

                        @if(
                            $technicalFeasibility
                                ->permissible_height !== null
                        )

                            {{
                                number_format(
                                    $technicalFeasibility
                                        ->permissible_height,
                                    2
                                )
                            }} m

                        @else

                            -

                        @endif

                    </div>

                </div>


                <div class="col-md-12">

                    <label class="text-muted small">
                        Development Constraints
                    </label>

                    <div>

                        {!! nl2br(e(
                            $technicalFeasibility
                                ->development_constraints
                                ?? '-'
                        )) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Construction Technology --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Construction Technology
            </strong>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Technology Status
                    </label>

                    <div>
                        {{ $technicalFeasibility
                            ->technology_status
                            ?? '-'
                        }}
                    </div>

                </div>


                <div class="col-md-8 mb-3">

                    <label class="text-muted small">
                        Proposed Technology
                    </label>

                    <div>
                        {{ $technicalFeasibility
                            ->proposed_construction_technology
                            ?? '-'
                        }}
                    </div>

                </div>


                <div class="col-md-12">

                    <label class="text-muted small">
                        Technology Details
                    </label>

                    <div>

                        {!! nl2br(e(
                            $technicalFeasibility
                                ->technology_details
                                ?? '-'
                        )) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Implementation --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Project Implementation
            </strong>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Implementation Feasibility
                    </label>

                    <div>
                        {{ $technicalFeasibility
                            ->implementation_feasibility_status
                            ?? '-'
                        }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Estimated Implementation Period
                    </label>

                    <div>

                        @if(
                            $technicalFeasibility
                                ->estimated_implementation_period
                                !== null
                        )

                            {{
                                number_format(
                                    $technicalFeasibility
                                        ->estimated_implementation_period,
                                    2
                                )
                            }}
                            Months

                        @else

                            -

                        @endif

                    </div>

                </div>


                <div class="col-md-12">

                    <label class="text-muted small">
                        Implementation Details
                    </label>

                    <div>

                        {!! nl2br(e(
                            $technicalFeasibility
                                ->implementation_details
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
                Technical Risks & Mitigation
            </strong>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">

                    <label class="text-muted small">
                        Technical Risks
                    </label>

                    <div>

                        {!! nl2br(e(
                            $technicalFeasibility
                                ->technical_risks
                                ?? '-'
                        )) !!}

                    </div>

                </div>


                <div class="col-md-6">

                    <label class="text-muted small">
                        Mitigation Measures
                    </label>

                    <div>

                        {!! nl2br(e(
                            $technicalFeasibility
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

                <div class="col-md-6 mb-4">

                    <label class="text-muted small">
                        Key Technical Findings
                    </label>

                    <div>

                        {!! nl2br(e(
                            $technicalFeasibility
                                ->key_technical_findings
                                ?? '-'
                        )) !!}

                    </div>

                </div>


                <div class="col-md-6 mb-4">

                    <label class="text-muted small">
                        Recommendation
                    </label>

                    <div>

                        {!! nl2br(e(
                            $technicalFeasibility
                                ->recommendation
                                ?? '-'
                        )) !!}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted small">
                        Overall Technical Score
                    </label>

                    <div class="fs-4 fw-bold">

                        @if(
                            $technicalFeasibility
                                ->overall_technical_score !== null
                        )

                            {{
                                number_format(
                                    $technicalFeasibility
                                        ->overall_technical_score,
                                    2
                                )
                            }}

                            <span class="fs-6 text-muted">
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
    {{-- Bottom Actions --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between mb-5">

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
            ← Back to Technical Feasibilities
        </a>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.technical-feasibilities.edit',
                    [
                        'land' => $land->id,

                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,

                        'technicalFeasibility' =>
                            $technicalFeasibility->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit Technical Feasibility
            </a>


            <form
                action="{{ route(
                    'admin.land.lands.feasibility-assessments.technical-feasibilities.destroy',
                    [
                        'land' => $land->id,

                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,

                        'technicalFeasibility' =>
                            $technicalFeasibility->id,
                    ]
                ) }}"
                method="POST"
                onsubmit="return confirm('Are you sure you want to delete this Technical Feasibility?');"
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