@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Edit Legal & Regulatory Feasibility
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
                    'admin.land.lands.feasibility-assessments.legal-regulatory-feasibilities.show',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' => $feasibilityAssessment->id,
                        'legalRegulatoryFeasibility' => $legalRegulatoryFeasibility->id,
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

            <strong>Please correct the following errors:</strong>

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
            'admin.land.lands.feasibility-assessments.legal-regulatory-feasibilities.update',
            [
                'land' => $land->id,
                'feasibilityAssessment' => $feasibilityAssessment->id,
                'legalRegulatoryFeasibility' => $legalRegulatoryFeasibility->id,
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

                <strong>
                    Basic Information
                </strong>

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
                            value="{{ $legalRegulatoryFeasibility->analysis_number }}"
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
                                $legalRegulatoryFeasibility->title
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
                                        $legalRegulatoryFeasibility->status
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
        {{-- Ownership & Title --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Land Ownership & Title Verification
                </strong>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Ownership Status --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Ownership Status
                        </label>

                        <select
                            name="ownership_status"
                            class="form-select"
                        >

                            <option value="">
                                Select Status
                            </option>

                            @foreach([
                                'Clear',
                                'Pending',
                                'Disputed',
                                'Not Verified'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ old(
                                        'ownership_status',
                                        $legalRegulatoryFeasibility
                                            ->ownership_status
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


                    {{-- Title Verification --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Title Verification
                        </label>

                        <select
                            name="title_verification_status"
                            class="form-select"
                        >

                            <option value="">
                                Select Status
                            </option>

                            @foreach([
                                'Verified',
                                'Pending',
                                'Issue Found',
                                'Not Verified'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ old(
                                        'title_verification_status',
                                        $legalRegulatoryFeasibility
                                            ->title_verification_status
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


                    {{-- Encumbrance --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Encumbrance Status
                        </label>

                        <select
                            name="encumbrance_status"
                            class="form-select"
                        >

                            <option value="">
                                Select Status
                            </option>

                            @foreach([
                                'Clear',
                                'Encumbered',
                                'Pending',
                                'Not Verified'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ old(
                                        'encumbrance_status',
                                        $legalRegulatoryFeasibility
                                            ->encumbrance_status
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


                    {{-- Ownership Details --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Ownership Details
                        </label>

                        <textarea
                            name="ownership_details"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'ownership_details',
                            $legalRegulatoryFeasibility
                                ->ownership_details
                        ) }}</textarea>

                    </div>


                    {{-- Title Details --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Title Verification Details
                        </label>

                        <textarea
                            name="title_verification_details"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'title_verification_details',
                            $legalRegulatoryFeasibility
                                ->title_verification_details
                        ) }}</textarea>

                    </div>


                    {{-- Encumbrance Details --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Encumbrance Details
                        </label>

                        <textarea
                            name="encumbrance_details"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'encumbrance_details',
                            $legalRegulatoryFeasibility
                                ->encumbrance_details
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Land Use / Zoning --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Land Use & Zoning</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Zoning Status
                        </label>

                        <select
                            name="zoning_status"
                            class="form-select"
                        >

                            <option value="">
                                Select Status
                            </option>

                            @foreach([
                                'Permitted',
                                'Conditionally Permitted',
                                'Not Permitted',
                                'Pending Verification'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ old(
                                        'zoning_status',
                                        $legalRegulatoryFeasibility
                                            ->zoning_status
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


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Zoning Type
                        </label>

                        <input
                            type="text"
                            name="zoning_type"
                            class="form-control"
                            value="{{ old(
                                'zoning_type',
                                $legalRegulatoryFeasibility->zoning_type
                            ) }}"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Zoning Details
                        </label>

                        <textarea
                            name="zoning_details"
                            class="form-control"
                            rows="4"
                        >{{ old(
                            'zoning_details',
                            $legalRegulatoryFeasibility
                                ->zoning_details
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Development & Building --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Development & Building Approvals
                </strong>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Development Permission --}}
                    <div class="col-md-6 mb-4">

                        <label class="form-label">
                            Development Permission Status
                        </label>

                        <select
                            name="development_permission_status"
                            class="form-select mb-2"
                        >

                            <option value="">
                                Select Status
                            </option>

                            @foreach([
                                'Approved',
                                'Pending',
                                'Required',
                                'Not Required',
                                'Rejected'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ old(
                                        'development_permission_status',
                                        $legalRegulatoryFeasibility
                                            ->development_permission_status
                                    ) === $status
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>


                        <textarea
                            name="development_permission_details"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'development_permission_details',
                            $legalRegulatoryFeasibility
                                ->development_permission_details
                        ) }}</textarea>

                    </div>


                    {{-- Building Approval --}}
                    <div class="col-md-6 mb-4">

                        <label class="form-label">
                            Building Approval Status
                        </label>

                        <select
                            name="building_approval_status"
                            class="form-select mb-2"
                        >

                            <option value="">
                                Select Status
                            </option>

                            @foreach([
                                'Approved',
                                'Pending',
                                'Required',
                                'Not Required',
                                'Rejected'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ old(
                                        'building_approval_status',
                                        $legalRegulatoryFeasibility
                                            ->building_approval_status
                                    ) === $status
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>


                        <textarea
                            name="building_approval_details"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'building_approval_details',
                            $legalRegulatoryFeasibility
                                ->building_approval_details
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Environmental / Fire / Pollution --}}
        {{-- ===================================================== --}}

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

                        <label class="form-label">
                            Environmental Clearance
                        </label>

                        <select
                            name="environmental_clearance_status"
                            class="form-select mb-2"
                        >

                            <option value="">
                                Select Status
                            </option>

                            @foreach([
                                'Obtained',
                                'Pending',
                                'Required',
                                'Not Required'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ old(
                                        'environmental_clearance_status',
                                        $legalRegulatoryFeasibility
                                            ->environmental_clearance_status
                                    ) === $status
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>


                        <textarea
                            name="environmental_clearance_details"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'environmental_clearance_details',
                            $legalRegulatoryFeasibility
                                ->environmental_clearance_details
                        ) }}</textarea>

                    </div>


                    {{-- Fire NOC --}}
                    <div class="col-md-4 mb-4">

                        <label class="form-label">
                            Fire NOC
                        </label>

                        <select
                            name="fire_noc_status"
                            class="form-select mb-2"
                        >

                            <option value="">
                                Select Status
                            </option>

                            @foreach([
                                'Obtained',
                                'Pending',
                                'Required',
                                'Not Required'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ old(
                                        'fire_noc_status',
                                        $legalRegulatoryFeasibility
                                            ->fire_noc_status
                                    ) === $status
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>


                        <textarea
                            name="fire_noc_details"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'fire_noc_details',
                            $legalRegulatoryFeasibility
                                ->fire_noc_details
                        ) }}</textarea>

                    </div>


                    {{-- Pollution --}}
                    <div class="col-md-4 mb-4">

                        <label class="form-label">
                            Pollution Clearance
                        </label>

                        <select
                            name="pollution_clearance_status"
                            class="form-select mb-2"
                        >

                            <option value="">
                                Select Status
                            </option>

                            @foreach([
                                'Obtained',
                                'Pending',
                                'Required',
                                'Not Required'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ old(
                                        'pollution_clearance_status',
                                        $legalRegulatoryFeasibility
                                            ->pollution_clearance_status
                                    ) === $status
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>


                        <textarea
                            name="pollution_clearance_details"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'pollution_clearance_details',
                            $legalRegulatoryFeasibility
                                ->pollution_clearance_details
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- RERA --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>RERA Applicability & Compliance</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            RERA Applicability
                        </label>

                        <select
                            name="rera_applicability"
                            class="form-select"
                        >

                            <option value="">
                                Select
                            </option>

                            @foreach([
                                'Applicable',
                                'Not Applicable',
                                'To Be Determined'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    {{ old(
                                        'rera_applicability',
                                        $legalRegulatoryFeasibility
                                            ->rera_applicability
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
                            RERA Status
                        </label>

                        <select
                            name="rera_status"
                            class="form-select"
                        >

                            <option value="">
                                Select Status
                            </option>

                            @foreach([
                                'Registered',
                                'Pending',
                                'Not Required',
                                'Not Verified'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ old(
                                        'rera_status',
                                        $legalRegulatoryFeasibility
                                            ->rera_status
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


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            RERA Details
                        </label>

                        <textarea
                            name="rera_details"
                            class="form-control"
                            rows="4"
                        >{{ old(
                            'rera_details',
                            $legalRegulatoryFeasibility
                                ->rera_details
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Other Approvals --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Other Statutory Approvals
                </strong>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Other Approval Status
                        </label>

                        <select
                            name="other_approval_status"
                            class="form-select"
                        >

                            <option value="">
                                Select Status
                            </option>

                            @foreach([
                                'Complete',
                                'Pending',
                                'Required',
                                'Not Required'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ old(
                                        'other_approval_status',
                                        $legalRegulatoryFeasibility
                                            ->other_approval_status
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


                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Other Approval Details
                        </label>

                        <textarea
                            name="other_approval_details"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'other_approval_details',
                            $legalRegulatoryFeasibility
                                ->other_approval_details
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Compliance & Risks --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Compliance & Legal Risks</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Compliance Status
                        </label>

                        <select
                            name="compliance_status"
                            class="form-select"
                        >

                            <option value="">
                                Select Status
                            </option>

                            @foreach([
                                'Compliant',
                                'Partially Compliant',
                                'Non-Compliant',
                                'Under Review',
                                'Not Verified'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ old(
                                        'compliance_status',
                                        $legalRegulatoryFeasibility
                                            ->compliance_status
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


                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Compliance Details
                        </label>

                        <textarea
                            name="compliance_details"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'compliance_details',
                            $legalRegulatoryFeasibility
                                ->compliance_details
                        ) }}</textarea>

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Legal Risks
                        </label>

                        <textarea
                            name="legal_risks"
                            class="form-control"
                            rows="6"
                            placeholder="Identify title, ownership, approval, regulatory or litigation risks..."
                        >{{ old(
                            'legal_risks',
                            $legalRegulatoryFeasibility
                                ->legal_risks
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Findings & Recommendation --}}
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
                            Key Legal Findings
                        </label>

                        <textarea
                            name="key_legal_findings"
                            class="form-control"
                            rows="7"
                        >{{ old(
                            'key_legal_findings',
                            $legalRegulatoryFeasibility
                                ->key_legal_findings
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
                            $legalRegulatoryFeasibility
                                ->recommendation
                        ) }}</textarea>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Overall Legal Score
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                name="overall_legal_score"
                                class="form-control"
                                value="{{ old(
                                    'overall_legal_score',
                                    $legalRegulatoryFeasibility
                                        ->overall_legal_score
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
                    'admin.land.lands.feasibility-assessments.legal-regulatory-feasibilities.show',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' => $feasibilityAssessment->id,
                        'legalRegulatoryFeasibility' => $legalRegulatoryFeasibility->id,
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
                Update Legal & Regulatory Feasibility
            </button>

        </div>

    </form>

</div>

@endsection