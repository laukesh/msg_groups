@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Create Development Project
            </h3>

            <p class="text-muted mb-0">
                Project Setup
            </p>

        </div>

        <a
            href="{{ route('admin.projects.index') }}"
            class="btn btn-outline-secondary"
        >
            ← Back to Projects
        </a>

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


    <form
        method="POST"
        action="{{ route('admin.projects.store') }}"
    >

        @csrf


        {{-- ===================================================== --}}
        {{-- Investment Approval Source --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Investment Approval
                </strong>

            </div>


            <div class="card-body">

                <div class="alert alert-info">

                    <strong>
                        Project Creation Gate
                    </strong>

                    <div class="small mt-1">

                        Only an approved Investment Decision can be
                        converted into a Development Project.

                    </div>

                </div>


                <div class="row">

                    <div class="col-md-8">

                        <label
                            for="investment_decision_id"
                            class="form-label"
                        >
                            Investment Decision
                            <span class="text-danger">*</span>
                        </label>


                        <select
                            name="investment_decision_id"
                            id="investment_decision_id"
                            class="form-select @error('investment_decision_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                -- Select Approved Investment Decision --
                            </option>


                            @foreach(
                                $investmentDecisions
                                as $investmentDecision
                            )

                                <option
                                    value="{{ $investmentDecision->id }}"
                                    {{ old(
                                        'investment_decision_id'
                                    ) == $investmentDecision->id
                                        ? 'selected'
                                        : ''
                                    }}
                                >

                                    {{
                                        $investmentDecision
                                            ->decision_number
                                        ?? 'Investment Decision #' .
                                            $investmentDecision->id
                                    }}

                                    @if(
                                        $investmentDecision
                                            ->decision
                                    )

                                        -
                                        {{
                                            $investmentDecision
                                                ->decision
                                        }}

                                    @endif

                                </option>

                            @endforeach

                        </select>


                        @error('investment_decision_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Approval Status
                        </label>

                        <div>

                            <span class="badge bg-success fs-6">
                                Approved
                            </span>

                        </div>

                    </div>

                </div>


                {{-- Parent information --}}

                <div
                    id="investment-source-info"
                    class="row mt-4 d-none"
                >

                    <div class="col-md-4">

                        <label class="form-label">
                            Feasibility Assessment
                        </label>

                        <input
                            type="text"
                            id="feasibility_display"
                            class="form-control"
                            readonly
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Land
                        </label>

                        <input
                            type="text"
                            id="land_display"
                            class="form-control"
                            readonly
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Investment Decision
                        </label>

                        <input
                            type="text"
                            id="decision_display"
                            class="form-control"
                            readonly
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Project Identity --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Project Identity
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label
                            for="project_name"
                            class="form-label"
                        >
                            Project Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="project_name"
                            id="project_name"
                            class="form-control @error('project_name') is-invalid @enderror"
                            value="{{ old('project_name') }}"
                            placeholder="Enter project name"
                            required
                        >

                        @error('project_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-3 mb-3">

                        <label
                            for="project_code"
                            class="form-label"
                        >
                            Project Code
                        </label>

                        <input
                            type="text"
                            name="project_code"
                            id="project_code"
                            class="form-control @error('project_code') is-invalid @enderror"
                            value="{{ old('project_code') }}"
                            placeholder="e.g. MSG-MALL-001"
                        >

                        @error('project_code')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-3 mb-3">

                        <label
                            for="project_type"
                            class="form-label"
                        >
                            Project Type
                        </label>

                        <select
                            name="project_type"
                            id="project_type"
                            class="form-select @error('project_type') is-invalid @enderror"
                        >

                            <option value="">
                                -- Select --
                            </option>

                            <option
                                value="Commercial"
                                {{ old('project_type') === 'Commercial'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Commercial
                            </option>

                            <option
                                value="Residential"
                                {{ old('project_type') === 'Residential'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Residential
                            </option>

                            <option
                                value="Mixed Use"
                                {{ old('project_type') === 'Mixed Use'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Mixed Use
                            </option>

                            <option
                                value="Retail"
                                {{ old('project_type') === 'Retail'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Retail
                            </option>

                            <option
                                value="Hospitality"
                                {{ old('project_type') === 'Hospitality'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Hospitality
                            </option>

                            <option
                                value="Other"
                                {{ old('project_type') === 'Other'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Other
                            </option>

                        </select>

                        @error('project_type')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                <div class="mb-3">

                    <label
                        for="project_description"
                        class="form-label"
                    >
                        Project Description
                    </label>

                    <textarea
                        name="project_description"
                        id="project_description"
                        rows="4"
                        class="form-control @error('project_description') is-invalid @enderror"
                        placeholder="Describe the development project"
                    >{{ old('project_description') }}</textarea>

                    @error('project_description')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Project Lifecycle --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Project Lifecycle
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label
                            for="project_stage"
                            class="form-label"
                        >
                            Project Stage
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="project_stage"
                            id="project_stage"
                            class="form-select"
                            required
                        >

                            <option
                                value="Planning"
                                {{ old(
                                    'project_stage',
                                    'Planning'
                                ) === 'Planning'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Planning
                            </option>

                            <option value="Design">
                                Design
                            </option>

                            <option value="Procurement">
                                Procurement
                            </option>

                            <option value="Construction">
                                Construction
                            </option>

                            <option value="Commissioning">
                                Commissioning
                            </option>

                            <option value="Handover">
                                Handover
                            </option>

                            <option value="Completed">
                                Completed
                            </option>

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label
                            for="project_status"
                            class="form-label"
                        >
                            Project Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="project_status"
                            id="project_status"
                            class="form-select"
                            required
                        >

                            <option
                                value="Draft"
                                {{ old(
                                    'project_status',
                                    'Draft'
                                ) === 'Draft'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Draft
                            </option>

                            <option value="Active">
                                Active
                            </option>

                            <option value="On Hold">
                                On Hold
                            </option>

                            <option value="Delayed">
                                Delayed
                            </option>

                            <option value="Completed">
                                Completed
                            </option>

                            <option value="Cancelled">
                                Cancelled
                            </option>

                            <option value="Closed">
                                Closed
                            </option>

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label
                            for="project_priority"
                            class="form-label"
                        >
                            Project Priority
                        </label>

                        <select
                            name="project_priority"
                            id="project_priority"
                            class="form-select"
                        >

                            <option value="">
                                -- Select --
                            </option>

                            <option
                                value="High"
                                {{ old('project_priority') === 'High'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                High
                            </option>

                            <option
                                value="Medium"
                                {{ old('project_priority') === 'Medium'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Medium
                            </option>

                            <option
                                value="Low"
                                {{ old('project_priority') === 'Low'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Low
                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Project Responsibility --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Project Responsibility
                </strong>

            </div>


            <div class="card-body">

                <div class="alert alert-secondary small">

                    User / employee assignments can be connected to
                    your existing user or employee master later.
                    For now these fields remain optional.

                </div>


                <div class="row">

                    <div class="col-md-3 mb-3">

                        <label
                            for="project_sponsor_id"
                            class="form-label"
                        >
                            Project Sponsor ID
                        </label>

                        <input
                            type="number"
                            name="project_sponsor_id"
                            id="project_sponsor_id"
                            class="form-control"
                            value="{{ old('project_sponsor_id') }}"
                        >

                    </div>


                    <div class="col-md-3 mb-3">

                        <label
                            for="project_director_id"
                            class="form-label"
                        >
                            Project Director ID
                        </label>

                        <input
                            type="number"
                            name="project_director_id"
                            id="project_director_id"
                            class="form-control"
                            value="{{ old('project_director_id') }}"
                        >

                    </div>


                    <div class="col-md-3 mb-3">

                        <label
                            for="project_manager_id"
                            class="form-label"
                        >
                            Project Manager ID
                        </label>

                        <input
                            type="number"
                            name="project_manager_id"
                            id="project_manager_id"
                            class="form-control"
                            value="{{ old('project_manager_id') }}"
                        >

                    </div>


                    <div class="col-md-3 mb-3">

                        <label
                            for="development_manager_id"
                            class="form-label"
                        >
                            Development Manager ID
                        </label>

                        <input
                            type="number"
                            name="development_manager_id"
                            id="development_manager_id"
                            class="form-control"
                            value="{{ old('development_manager_id') }}"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Project Dates --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Project Dates
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label
                            for="approval_date"
                            class="form-label"
                        >
                            Approval Date
                        </label>

                        <input
                            type="date"
                            name="approval_date"
                            id="approval_date"
                            class="form-control"
                            value="{{ old('approval_date') }}"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label
                            for="project_initiation_date"
                            class="form-label"
                        >
                            Project Initiation Date
                        </label>

                        <input
                            type="date"
                            name="project_initiation_date"
                            id="project_initiation_date"
                            class="form-control"
                            value="{{ old('project_initiation_date') }}"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label
                            for="project_start_date"
                            class="form-label"
                        >
                            Planned Start Date
                        </label>

                        <input
                            type="date"
                            name="project_start_date"
                            id="project_start_date"
                            class="form-control"
                            value="{{ old('project_start_date') }}"
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label
                            for="planned_completion_date"
                            class="form-label"
                        >
                            Planned Completion Date
                        </label>

                        <input
                            type="date"
                            name="planned_completion_date"
                            id="planned_completion_date"
                            class="form-control"
                            value="{{ old('planned_completion_date') }}"
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label
                            for="actual_completion_date"
                            class="form-label"
                        >
                            Actual Completion Date
                        </label>

                        <input
                            type="date"
                            name="actual_completion_date"
                            id="actual_completion_date"
                            class="form-control"
                            value="{{ old('actual_completion_date') }}"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Development Scope --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Development Scope
                </strong>

            </div>


            <div class="card-body">

                <div class="mb-3">

                    <label
                        for="development_objective"
                        class="form-label"
                    >
                        Development Objective
                    </label>

                    <textarea
                        name="development_objective"
                        id="development_objective"
                        rows="3"
                        class="form-control"
                        placeholder="What is the primary objective of this development?"
                    >{{ old('development_objective') }}</textarea>

                </div>


                <div class="mb-3">

                    <label
                        for="scope_summary"
                        class="form-label"
                    >
                        Scope Summary
                    </label>

                    <textarea
                        name="scope_summary"
                        id="scope_summary"
                        rows="3"
                        class="form-control"
                        placeholder="Brief summary of the project scope"
                    >{{ old('scope_summary') }}</textarea>

                </div>


                <div class="mb-3">

                    <label
                        for="development_scope"
                        class="form-label"
                    >
                        Development Scope
                    </label>

                    <textarea
                        name="development_scope"
                        id="development_scope"
                        rows="5"
                        class="form-control"
                        placeholder="Detailed development scope"
                    >{{ old('development_scope') }}</textarea>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Development Area --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Development Area
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-3 mb-3">

                        <label
                            for="development_area"
                            class="form-label"
                        >
                            Development Area
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="development_area"
                            id="development_area"
                            class="form-control"
                            value="{{ old('development_area') }}"
                        >

                        <div class="form-text">
                            Area in sq. ft. / project-defined unit.
                        </div>

                    </div>


                    <div class="col-md-3 mb-3">

                        <label
                            for="planned_gla"
                            class="form-label"
                        >
                            Planned GLA
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="planned_gla"
                            id="planned_gla"
                            class="form-control"
                            value="{{ old('planned_gla') }}"
                        >

                    </div>


                    <div class="col-md-3 mb-3">

                        <label
                            for="planned_nla"
                            class="form-label"
                        >
                            Planned NLA
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="planned_nla"
                            id="planned_nla"
                            class="form-control"
                            value="{{ old('planned_nla') }}"
                        >

                    </div>


                    <div class="col-md-3 mb-3">

                        <label
                            for="planned_leasable_area"
                            class="form-label"
                        >
                            Planned Leasable Area
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="planned_leasable_area"
                            id="planned_leasable_area"
                            class="form-control"
                            value="{{ old('planned_leasable_area') }}"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Remarks --}}
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
                    id="remarks"
                    rows="4"
                    class="form-control"
                    placeholder="Additional project remarks"
                >{{ old('remarks') }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Actions --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route('admin.projects.index') }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Create Development Project
            </button>

        </div>

    </form>

</div>

@endsection