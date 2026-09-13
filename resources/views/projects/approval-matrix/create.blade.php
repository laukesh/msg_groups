@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Governance / Approval Matrix
            </div>

            <h3 class="mb-1">
                Create Approval Rule
            </h3>

            <div class="text-muted">

                {{ $project->project_name }}

                @if($project->project_number)
                    · {{ $project->project_number }}
                @endif

            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.approval-matrix.index',
                [
                    'project' => $project->id,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            ← Approval Matrix
        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
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
    {{-- FORM --}}
    {{-- ========================================================= --}}

    <form
        method="POST"
        action="{{ route(
            'admin.projects.approval-matrix.store',
            [
                'project' => $project->id,
            ]
        ) }}"
    >

        @csrf


        {{-- ===================================================== --}}
        {{-- BASIC INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Approval Rule
                </strong>

                <div class="text-muted small mt-1">
                    Define what requires approval and its authority.
                </div>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Approval Code --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Approval Code

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="approval_code"
                            class="form-control"
                            value="{{ old(
                                'approval_code',
                                $approvalCode
                            ) }}"
                            readonly
                        >

                    </div>


                    {{-- Approval Type --}}

                    <div class="col-md-8 mb-3">

                        <label class="form-label">

                            Approval Type

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="approval_type"
                            class="form-control"
                            value="{{ old('approval_type') }}"
                            placeholder="e.g. Budget Approval, Contract Approval, Change Request"
                            required
                        >

                    </div>

                </div>


                {{-- Description --}}

                <div class="mb-3">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="4"
                        class="form-control"
                        placeholder="Describe what this approval rule applies to..."
                    >{{ old('description') }}</textarea>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- GOVERNANCE LINK --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Governance Framework
                </strong>

            </div>


            <div class="card-body">

                <label class="form-label">
                    Governance Framework
                </label>

                <select
                    name="project_governance_id"
                    class="form-select"
                >

                    <option value="">
                        Not Linked
                    </option>


                    @foreach($governances as $governance)

                        <option
                            value="{{ $governance->id }}"
                            @selected(
                                old(
                                    'project_governance_id'
                                ) == $governance->id
                            )
                        >

                            {{ $governance->governance_number }}
                            -
                            {{ $governance->title }}

                            ({{ $governance->status }})

                        </option>

                    @endforeach

                </select>


                <div class="form-text">
                    Link this approval rule to the governance framework
                    under which it operates.
                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- APPROVAL AUTHORITY --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Approval Authority
                </strong>

                <div class="text-muted small mt-1">
                    Define the role and optional individual responsible
                    for this approval.
                </div>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Authority Role --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Authority Role

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="authority_role"
                            class="form-control"
                            value="{{ old('authority_role') }}"
                            placeholder="e.g. Project Manager"
                            required
                        >

                    </div>


                    {{-- Authority User --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Designated Authority
                        </label>

                        <select
                            name="authority_user_id"
                            class="form-select"
                        >

                            <option value="">
                                No Specific User
                            </option>


                            @foreach($users as $user)

                                <option
                                    value="{{ $user->id }}"
                                    @selected(
                                        old(
                                            'authority_user_id'
                                        ) == $user->id
                                    )
                                >
                                    {{ $user->name }}
                                </option>

                            @endforeach

                        </select>


                        <div class="form-text">
                            Optional. Leave blank when approval is based
                            on the role rather than a specific person.
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- FINANCIAL AUTHORITY --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Financial Authority
                </strong>

                <div class="text-muted small mt-1">
                    Define the monetary range for this approval authority.
                </div>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Currency --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Currency

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="currency"
                            class="form-control"
                            value="{{ old(
                                'currency',
                                'USD'
                            ) }}"
                            maxlength="10"
                            required
                        >

                    </div>


                    {{-- Minimum --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Minimum Amount
                        </label>

                        <input
                            type="number"
                            name="minimum_amount"
                            class="form-control"
                            value="{{ old(
                                'minimum_amount'
                            ) }}"
                            min="0"
                            step="0.01"
                            placeholder="0.00"
                        >

                    </div>


                    {{-- Maximum --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Maximum Amount
                        </label>

                        <input
                            type="number"
                            name="maximum_amount"
                            class="form-control"
                            value="{{ old(
                                'maximum_amount'
                            ) }}"
                            min="0"
                            step="0.01"
                            placeholder="Leave blank for no limit"
                        >

                    </div>

                </div>


                <div class="alert alert-light border mb-0">

                    <strong>
                        Example:
                    </strong>

                    Minimum $10,00,000 and Maximum $1,00,00,000
                    means this authority applies to transactions
                    within that range.

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- APPROVAL SEQUENCE --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Approval Sequence
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Sequence --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Approval Sequence

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="number"
                            name="approval_sequence"
                            class="form-control"
                            value="{{ old(
                                'approval_sequence',
                                1
                            ) }}"
                            min="1"
                            required
                        >

                        <div class="form-text">
                            1 = first approval, 2 = second approval,
                            etc.
                        </div>

                    </div>


                    {{-- Mandatory --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label d-block">
                            Mandatory Approval
                        </label>

                        <div class="form-check mt-2">

                            <input
                                type="hidden"
                                name="is_mandatory"
                                value="0"
                            >

                            <input
                                type="checkbox"
                                name="is_mandatory"
                                value="1"
                                class="form-check-input"
                                id="is_mandatory"
                                @checked(
                                    old(
                                        'is_mandatory',
                                        true
                                    )
                                )
                            >

                            <label
                                class="form-check-label"
                                for="is_mandatory"
                            >
                                Approval is mandatory
                            </label>

                        </div>

                    </div>


                    {{-- Multiple --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label d-block">
                            Multiple Approvals
                        </label>

                        <div class="form-check mt-2">

                            <input
                                type="hidden"
                                name="requires_multiple_approvals"
                                value="0"
                            >

                            <input
                                type="checkbox"
                                name="requires_multiple_approvals"
                                value="1"
                                class="form-check-input"
                                id="requires_multiple_approvals"
                                @checked(
                                    old(
                                        'requires_multiple_approvals',
                                        false
                                    )
                                )
                            >

                            <label
                                class="form-check-label"
                                for="requires_multiple_approvals"
                            >
                                Requires multiple approvals
                            </label>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- STATUS & DATES --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Status & Validity
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Status --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Status

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="status"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Draft',
                                'Active',
                                'Inactive',
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        old(
                                            'status',
                                            'Draft'
                                        ) === $status
                                    )
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Effective Date --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Effective Date
                        </label>

                        <input
                            type="date"
                            name="effective_date"
                            class="form-control"
                            value="{{ old(
                                'effective_date'
                            ) }}"
                        >

                    </div>


                    {{-- Expiry Date --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Expiry Date
                        </label>

                        <input
                            type="date"
                            name="expiry_date"
                            class="form-control"
                            value="{{ old(
                                'expiry_date'
                            ) }}"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- REMARKS --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Remarks
                </strong>

            </div>


            <div class="card-body">

                <textarea
                    name="remarks"
                    rows="5"
                    class="form-control"
                    placeholder="Additional notes about this approval rule..."
                >{{ old('remarks') }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- ACTIONS --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.approval-matrix.index',
                    [
                        'project' => $project->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Approval Rule
            </button>

        </div>

    </form>

</div>

@endsection