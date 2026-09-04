@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                New Legal & Regulatory Feasibility
            </h3>

            <p class="text-muted mb-0">

                {{ $feasibilityAssessment->assessment_number }}
                -
                {{ $feasibilityAssessment->title }}

            </p>

        </div>


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

    </div>


    {{-- ========================================================= --}}
    {{-- Errors --}}
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
            'admin.land.lands.feasibility-assessments.legal-regulatory-feasibilities.store',
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
                            placeholder="Enter legal and regulatory assessment title"
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

                    {{-- Ownership --}}
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

                            <option
                                value="Clear"
                                {{ old(
                                    'ownership_status'
                                ) === 'Clear'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Clear
                            </option>

                            <option
                                value="Pending"
                                {{ old(
                                    'ownership_status'
                                ) === 'Pending'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Pending
                            </option>

                            <option
                                value="Disputed"
                                {{ old(
                                    'ownership_status'
                                ) === 'Disputed'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Disputed
                            </option>

                            <option
                                value="Not Verified"
                                {{ old(
                                    'ownership_status'
                                ) === 'Not Verified'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Not Verified
                            </option>

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

                            <option value="Verified">
                                Verified
                            </option>

                            <option value="Pending">
                                Pending
                            </option>

                            <option value="Issue Found">
                                Issue Found
                            </option>

                            <option value="Not Verified">
                                Not Verified
                            </option>

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

                            <option value="Clear">
                                Clear
                            </option>

                            <option value="Encumbered">
                                Encumbered
                            </option>

                            <option value="Pending">
                                Pending
                            </option>

                            <option value="Not Verified">
                                Not Verified
                            </option>

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Ownership Details
                        </label>

                        <textarea
                            name="ownership_details"
                            class="form-control"
                            rows="4"
                            placeholder="Enter ownership details..."
                        >{{ old('ownership_details') }}</textarea>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Title Verification Details
                        </label>

                        <textarea
                            name="title_verification_details"
                            class="form-control"
                            rows="4"
                            placeholder="Enter title verification details..."
                        >{{ old('title_verification_details') }}</textarea>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Encumbrance Details
                        </label>

                        <textarea
                            name="encumbrance_details"
                            class="form-control"
                            rows="4"
                            placeholder="Enter encumbrance details..."
                        >{{ old('encumbrance_details') }}</textarea>

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

                            <option value="Permitted">
                                Permitted
                            </option>

                            <option value="Conditionally Permitted">
                                Conditionally Permitted
                            </option>

                            <option value="Not Permitted">
                                Not Permitted
                            </option>

                            <option value="Pending Verification">
                                Pending Verification
                            </option>

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
                            value="{{ old('zoning_type') }}"
                            placeholder="e.g. Commercial / Residential / Mixed Use"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Zoning Details
                        </label>

                        <textarea
                            name="zoning_details"
                            class="form-control"
                            rows="3"
                            placeholder="Enter zoning details..."
                        >{{ old('zoning_details') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Development & Building Approvals --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Development & Building Approvals
                </strong>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Development --}}
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

                            <option value="Approved">
                                Approved
                            </option>

                            <option value="Pending">
                                Pending
                            </option>

                            <option value="Required">
                                Required
                            </option>

                            <option value="Not Required">
                                Not Required
                            </option>

                            <option value="Rejected">
                                Rejected
                            </option>

                        </select>


                        <textarea
                            name="development_permission_details"
                            class="form-control"
                            rows="4"
                            placeholder="Development permission details..."
                        >{{ old(
                            'development_permission_details'
                        ) }}</textarea>

                    </div>


                    {{-- Building --}}
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

                            <option value="Approved">
                                Approved
                            </option>

                            <option value="Pending">
                                Pending
                            </option>

                            <option value="Required">
                                Required
                            </option>

                            <option value="Not Required">
                                Not Required
                            </option>

                            <option value="Rejected">
                                Rejected
                            </option>

                        </select>


                        <textarea
                            name="building_approval_details"
                            class="form-control"
                            rows="4"
                            placeholder="Building approval details..."
                        >{{ old(
                            'building_approval_details'
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

                            <option value="Obtained">
                                Obtained
                            </option>

                            <option value="Pending">
                                Pending
                            </option>

                            <option value="Required">
                                Required
                            </option>

                            <option value="Not Required">
                                Not Required
                            </option>

                        </select>


                        <textarea
                            name="environmental_clearance_details"
                            class="form-control"
                            rows="5"
                            placeholder="Environmental clearance details..."
                        >{{ old(
                            'environmental_clearance_details'
                        ) }}</textarea>

                    </div>


                    {{-- Fire --}}
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

                            <option value="Obtained">
                                Obtained
                            </option>

                            <option value="Pending">
                                Pending
                            </option>

                            <option value="Required">
                                Required
                            </option>

                            <option value="Not Required">
                                Not Required
                            </option>

                        </select>


                        <textarea
                            name="fire_noc_details"
                            class="form-control"
                            rows="5"
                            placeholder="Fire NOC details..."
                        >{{ old('fire_noc_details') }}</textarea>

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

                            <option value="Obtained">
                                Obtained
                            </option>

                            <option value="Pending">
                                Pending
                            </option>

                            <option value="Required">
                                Required
                            </option>

                            <option value="Not Required">
                                Not Required
                            </option>

                        </select>


                        <textarea
                            name="pollution_clearance_details"
                            class="form-control"
                            rows="5"
                            placeholder="Pollution clearance details..."
                        >{{ old(
                            'pollution_clearance_details'
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

                            <option value="Applicable">
                                Applicable
                            </option>

                            <option value="Not Applicable">
                                Not Applicable
                            </option>

                            <option value="To Be Determined">
                                To Be Determined
                            </option>

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

                            <option value="Registered">
                                Registered
                            </option>

                            <option value="Pending">
                                Pending
                            </option>

                            <option value="Not Required">
                                Not Required
                            </option>

                            <option value="Not Verified">
                                Not Verified
                            </option>

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            RERA Details
                        </label>

                        <textarea
                            name="rera_details"
                            class="form-control"
                            rows="3"
                            placeholder="Enter RERA details..."
                        >{{ old('rera_details') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Other Approvals --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Other Statutory Approvals</strong>
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

                            <option value="Complete">
                                Complete
                            </option>

                            <option value="Pending">
                                Pending
                            </option>

                            <option value="Required">
                                Required
                            </option>

                            <option value="Not Required">
                                Not Required
                            </option>

                        </select>

                    </div>


                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Other Approval Details
                        </label>

                        <textarea
                            name="other_approval_details"
                            class="form-control"
                            rows="4"
                            placeholder="Enter other required approvals..."
                        >{{ old(
                            'other_approval_details'
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
                <strong>Legal Risks & Compliance</strong>
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

                            <option value="Compliant">
                                Compliant
                            </option>

                            <option value="Partially Compliant">
                                Partially Compliant
                            </option>

                            <option value="Non-Compliant">
                                Non-Compliant
                            </option>

                            <option value="Under Review">
                                Under Review
                            </option>

                            <option value="Not Verified">
                                Not Verified
                            </option>

                        </select>

                    </div>


                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Compliance Details
                        </label>

                        <textarea
                            name="compliance_details"
                            class="form-control"
                            rows="4"
                            placeholder="Describe compliance position..."
                        >{{ old(
                            'compliance_details'
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
                            placeholder="Identify title, approval, regulatory or litigation risks..."
                        >{{ old('legal_risks') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Findings & Recommendation --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Findings & Recommendation</strong>
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
                            placeholder="Summarize key legal and regulatory findings..."
                        >{{ old(
                            'key_legal_findings'
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
                            placeholder="Provide legal/regulatory recommendation..."
                        >{{ old('recommendation') }}</textarea>

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
                                    'overall_legal_score'
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
                    'admin.land.lands.feasibility-assessments.legal-regulatory-feasibilities.index',
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
                Save Legal & Regulatory Feasibility
            </button>

        </div>

    </form>

</div>

@endsection