@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Legal & Regulatory Feasibility
            </h3>

            <p class="text-muted mb-0">

                {{ $legalRegulatoryFeasibility->analysis_number }}
                -
                {{ $legalRegulatoryFeasibility->title }}

            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.legal-regulatory-feasibilities.index',
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
                    'admin.land.lands.feasibility-assessments.legal-regulatory-feasibilities.edit',
                    [
                        'land' => $land->id,

                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,

                        'legalRegulatoryFeasibility' =>
                            $legalRegulatoryFeasibility->id,
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

        <div class="card-header d-flex justify-content-between">

            <strong>
                Basic Information
            </strong>


            @if(
                $legalRegulatoryFeasibility->status === 'Draft'
            )

                <span class="badge bg-secondary">
                    Draft
                </span>

            @elseif(
                $legalRegulatoryFeasibility->status === 'Submitted'
            )

                <span class="badge bg-warning text-dark">
                    Submitted
                </span>

            @elseif(
                $legalRegulatoryFeasibility->status === 'Approved'
            )

                <span class="badge bg-success">
                    Approved
                </span>

            @elseif(
                $legalRegulatoryFeasibility->status === 'Rejected'
            )

                <span class="badge bg-danger">
                    Rejected
                </span>

            @else

                <span class="badge bg-secondary">
                    {{ $legalRegulatoryFeasibility->status }}
                </span>

            @endif

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <small class="text-muted">
                        Analysis Number
                    </small>

                    <div class="fw-semibold">

                        {{
                            $legalRegulatoryFeasibility
                                ->analysis_number
                        }}

                    </div>

                </div>


                <div class="col-md-5 mb-3">

                    <small class="text-muted">
                        Title
                    </small>

                    <div class="fw-semibold">

                        {{
                            $legalRegulatoryFeasibility
                                ->title
                        }}

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Legal Score
                    </small>

                    <div class="fw-bold fs-5">

                        @if(
                            $legalRegulatoryFeasibility
                                ->overall_legal_score !== null
                        )

                            {{
                                number_format(
                                    $legalRegulatoryFeasibility
                                        ->overall_legal_score,
                                    2
                                )
                            }}

                            <span class="text-muted fs-6">
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
    {{-- Ownership & Title --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Land Ownership & Title Verification
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Ownership --}}
                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Ownership Status
                    </small>

                    <div class="mt-1">

                        @if(
                            $legalRegulatoryFeasibility
                                ->ownership_status
                        )

                            <span class="badge bg-secondary">

                                {{
                                    $legalRegulatoryFeasibility
                                        ->ownership_status
                                }}

                            </span>

                        @else

                            -

                        @endif

                    </div>


                    <div class="mt-3">

                        <small class="text-muted">
                            Details
                        </small>

                        <div class="mt-1">

                            {{
                                $legalRegulatoryFeasibility
                                    ->ownership_details
                                    ?? '-'
                            }}

                        </div>

                    </div>

                </div>


                {{-- Title --}}
                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Title Verification
                    </small>

                    <div class="mt-1">

                        @if(
                            $legalRegulatoryFeasibility
                                ->title_verification_status
                        )

                            <span class="badge bg-secondary">

                                {{
                                    $legalRegulatoryFeasibility
                                        ->title_verification_status
                                }}

                            </span>

                        @else

                            -

                        @endif

                    </div>


                    <div class="mt-3">

                        <small class="text-muted">
                            Details
                        </small>

                        <div class="mt-1">

                            {{
                                $legalRegulatoryFeasibility
                                    ->title_verification_details
                                    ?? '-'
                            }}

                        </div>

                    </div>

                </div>


                {{-- Encumbrance --}}
                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Encumbrance
                    </small>

                    <div class="mt-1">

                        @if(
                            $legalRegulatoryFeasibility
                                ->encumbrance_status
                        )

                            <span class="badge bg-secondary">

                                {{
                                    $legalRegulatoryFeasibility
                                        ->encumbrance_status
                                }}

                            </span>

                        @else

                            -

                        @endif

                    </div>


                    <div class="mt-3">

                        <small class="text-muted">
                            Details
                        </small>

                        <div class="mt-1">

                            {{
                                $legalRegulatoryFeasibility
                                    ->encumbrance_details
                                    ?? '-'
                            }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Zoning --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Land Use & Zoning</strong>
        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <small class="text-muted">
                        Zoning Status
                    </small>

                    <div class="mt-1">

                        {{
                            $legalRegulatoryFeasibility
                                ->zoning_status
                                ?? '-'
                        }}

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted">
                        Zoning Type
                    </small>

                    <div class="mt-1 fw-semibold">

                        {{
                            $legalRegulatoryFeasibility
                                ->zoning_type
                                ?? '-'
                        }}

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted">
                        Zoning Details
                    </small>

                    <div class="mt-1">

                        {{
                            $legalRegulatoryFeasibility
                                ->zoning_details
                                ?? '-'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Development & Building --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Development & Building Approvals
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-4">

                    <small class="text-muted">
                        Development Permission
                    </small>

                    <div class="mt-1">

                        {{
                            $legalRegulatoryFeasibility
                                ->development_permission_status
                                ?? '-'
                        }}

                    </div>


                    <div class="mt-3">

                        <small class="text-muted">
                            Details
                        </small>

                        <div class="mt-1">

                            {{
                                $legalRegulatoryFeasibility
                                    ->development_permission_details
                                    ?? '-'
                            }}

                        </div>

                    </div>

                </div>


                <div class="col-md-6 mb-4">

                    <small class="text-muted">
                        Building Approval
                    </small>

                    <div class="mt-1">

                        {{
                            $legalRegulatoryFeasibility
                                ->building_approval_status
                                ?? '-'
                        }}

                    </div>


                    <div class="mt-3">

                        <small class="text-muted">
                            Details
                        </small>

                        <div class="mt-1">

                            {{
                                $legalRegulatoryFeasibility
                                    ->building_approval_details
                                    ?? '-'
                            }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Environmental / Fire / Pollution --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Environmental & Safety Clearances
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Environmental --}}
                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Environmental Clearance
                    </small>

                    <div class="mt-1">

                        {{
                            $legalRegulatoryFeasibility
                                ->environmental_clearance_status
                                ?? '-'
                        }}

                    </div>


                    <div class="mt-3">

                        <small class="text-muted">
                            Details
                        </small>

                        <div class="mt-1">

                            {{
                                $legalRegulatoryFeasibility
                                    ->environmental_clearance_details
                                    ?? '-'
                            }}

                        </div>

                    </div>

                </div>


                {{-- Fire --}}
                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Fire NOC
                    </small>

                    <div class="mt-1">

                        {{
                            $legalRegulatoryFeasibility
                                ->fire_noc_status
                                ?? '-'
                        }}

                    </div>


                    <div class="mt-3">

                        <small class="text-muted">
                            Details
                        </small>

                        <div class="mt-1">

                            {{
                                $legalRegulatoryFeasibility
                                    ->fire_noc_details
                                    ?? '-'
                            }}

                        </div>

                    </div>

                </div>


                {{-- Pollution --}}
                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Pollution Clearance
                    </small>

                    <div class="mt-1">

                        {{
                            $legalRegulatoryFeasibility
                                ->pollution_clearance_status
                                ?? '-'
                        }}

                    </div>


                    <div class="mt-3">

                        <small class="text-muted">
                            Details
                        </small>

                        <div class="mt-1">

                            {{
                                $legalRegulatoryFeasibility
                                    ->pollution_clearance_details
                                    ?? '-'
                            }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- RERA --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>RERA</strong>
        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <small class="text-muted">
                        Applicability
                    </small>

                    <div class="mt-1">

                        {{
                            $legalRegulatoryFeasibility
                                ->rera_applicability
                                ?? '-'
                        }}

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted">
                        RERA Status
                    </small>

                    <div class="mt-1">

                        {{
                            $legalRegulatoryFeasibility
                                ->rera_status
                                ?? '-'
                        }}

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted">
                        Details
                    </small>

                    <div class="mt-1">

                        {{
                            $legalRegulatoryFeasibility
                                ->rera_details
                                ?? '-'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Other Approvals --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Other Statutory Approvals</strong>
        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <small class="text-muted">
                        Status
                    </small>

                    <div class="mt-1">

                        {{
                            $legalRegulatoryFeasibility
                                ->other_approval_status
                                ?? '-'
                        }}

                    </div>

                </div>


                <div class="col-md-8 mb-3">

                    <small class="text-muted">
                        Details
                    </small>

                    <div class="mt-1">

                        {{
                            $legalRegulatoryFeasibility
                                ->other_approval_details
                                ?? '-'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Compliance & Risks --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Compliance & Legal Risks</strong>
        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Compliance Status
                    </small>

                    <div class="mt-1">

                        {{
                            $legalRegulatoryFeasibility
                                ->compliance_status
                                ?? '-'
                        }}

                    </div>

                </div>


                <div class="col-md-8 mb-4">

                    <small class="text-muted">
                        Compliance Details
                    </small>

                    <div class="mt-1">

                        {{
                            $legalRegulatoryFeasibility
                                ->compliance_details
                                ?? '-'
                        }}

                    </div>

                </div>


                <div class="col-md-12">

                    <small class="text-muted">
                        Legal Risks
                    </small>

                    <div class="mt-2 p-3 bg-light rounded">

                        {{
                            $legalRegulatoryFeasibility
                                ->legal_risks
                                ?? '-'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Findings & Recommendation --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Findings & Recommendation</strong>
        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-4">

                    <small class="text-muted">
                        Key Legal Findings
                    </small>

                    <div class="mt-2 p-3 bg-light rounded">

                        {{
                            $legalRegulatoryFeasibility
                                ->key_legal_findings
                                ?? '-'
                        }}

                    </div>

                </div>


                <div class="col-md-6 mb-4">

                    <small class="text-muted">
                        Recommendation
                    </small>

                    <div class="mt-2 p-3 bg-light rounded">

                        {{
                            $legalRegulatoryFeasibility
                                ->recommendation
                                ?? '-'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Delete --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between mb-5">

        <a
            href="{{ route(
                'admin.land.lands.feasibility-assessments.show',
                [
                    'land' => $land->id,

                    'feasibilityAssessment' =>
                        $feasibilityAssessment->id,
                ]
            ) }}"
            class="btn btn-secondary"
        >
            Back to Assessment
        </a>


        <form
            action="{{ route(
                'admin.land.lands.feasibility-assessments.legal-regulatory-feasibilities.destroy',
                [
                    'land' => $land->id,

                    'feasibilityAssessment' =>
                        $feasibilityAssessment->id,

                    'legalRegulatoryFeasibility' =>
                        $legalRegulatoryFeasibility->id,
                ]
            ) }}"
            method="POST"
            onsubmit="return confirm('Are you sure you want to delete this Legal & Regulatory Feasibility?');"
        >

            @csrf

            @method('DELETE')

            <button
                type="submit"
                class="btn btn-outline-danger"
            >
                Delete
            </button>

        </form>

    </div>

</div>

@endsection