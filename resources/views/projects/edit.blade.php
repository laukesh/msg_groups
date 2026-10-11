@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Development Project
            </div>

            <h3 class="mb-1">
                Edit Project
            </h3>

            <div class="text-muted">
                {{ $project->project_number }}
                · {{ $project->project_name }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.show',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-outline-primary"
            >
                View Project
            </a>

            <a
                href="{{ route('admin.projects.index') }}"
                class="btn btn-outline-secondary"
            >
                ← Projects
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


    <form
        method="POST"
        action="{{ route(
            'admin.projects.update',
            ['project' => $project->id]
        ) }}"
    >

        @csrf

        @method('PUT')


        {{-- ===================================================== --}}
        {{-- Lifecycle Source --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Investment & Lifecycle Source
                </strong>

            </div>


            <div class="card-body">

                <div class="alert alert-info mb-4">

                    <strong>
                        Lifecycle references are locked.
                    </strong>

                    <div class="small mt-1">
                        Land, Feasibility Assessment and Investment
                        Decision cannot be changed after project creation.
                    </div>

                </div>


                <div class="row">

                    {{-- Land --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Land
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{
                                $project->land->land_name
                                ?? $project->land->name
                                ?? 'Land #' . $project->land_id
                            }}"
                            readonly
                        >

                    </div>


                    {{-- Feasibility --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Feasibility Assessment
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{
                                $project
                                    ->feasibilityAssessment
                                    ->assessment_number
                                ?? 'Assessment #' .
                                    $project->feasibility_assessment_id
                            }}"
                            readonly
                        >

                    </div>


                    {{-- Investment Decision --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Investment Decision
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{
                                $project
                                    ->investmentDecision
                                    ->decision_number
                                ?? 'Decision #' .
                                    $project->investment_decision_id
                            }}"
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
                            value="{{ old(
                                'project_name',
                                $project->project_name
                            ) }}"
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
                            value="{{ old(
                                'project_code',
                                $project->project_code
                            ) }}"
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
                            class="form-select"
                        >

                            <option value="">
                                -- Select --
                            </option>

                            @foreach([
                                'Commercial',
                                'Residential',
                                'Mixed Use',
                                'Retail',
                                'Hospitality',
                                'Other'
                            ] as $type)

                                <option
                                    value="{{ $type }}"
                                    {{ old(
                                        'project_type',
                                        $project->project_type
                                    ) === $type
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

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
                        class="form-control"
                    >{{ old(
                        'project_description',
                        $project->project_description
                    ) }}</textarea>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Lifecycle --}}
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

                            @foreach([
                                'Planning',
                                'Design',
                                'Procurement',
                                'Construction',
                                'Commissioning',
                                'Handover',
                                'Completed'
                            ] as $stage)

                                <option
                                    value="{{ $stage }}"
                                    {{ old(
                                        'project_stage',
                                        $project->project_stage
                                    ) === $stage
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $stage }}
                                </option>

                            @endforeach

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

                            @foreach([
                                'Draft',
                                'Active',
                                'On Hold',
                                'Delayed',
                                'Completed',
                                'Cancelled',
                                'Closed'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ old(
                                        'project_status',
                                        $project->project_status
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

                            @foreach([
                                'High',
                                'Medium',
                                'Low'
                            ] as $priority)

                                <option
                                    value="{{ $priority }}"
                                    {{ old(
                                        'project_priority',
                                        $project->project_priority
                                    ) === $priority
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $priority }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Responsibility --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Project Responsibility
                </strong>

            </div>


            <div class="card-body">

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
                            value="{{ old(
                                'project_sponsor_id',
                                $project->project_sponsor_id
                            ) }}"
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
                            value="{{ old(
                                'project_director_id',
                                $project->project_director_id
                            ) }}"
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
                            value="{{ old(
                                'project_manager_id',
                                $project->project_manager_id
                            ) }}"
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
                            value="{{ old(
                                'development_manager_id',
                                $project->development_manager_id
                            ) }}"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Dates --}}
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
                            value="{{ old(
                                'approval_date',
                                optional(
                                    $project->approval_date
                                )->format('Y-m-d')
                            ) }}"
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
                            value="{{ old(
                                'project_initiation_date',
                                optional(
                                    $project->project_initiation_date
                                )->format('Y-m-d')
                            ) }}"
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
                            value="{{ old(
                                'project_start_date',
                                optional(
                                    $project->project_start_date
                                )->format('Y-m-d')
                            ) }}"
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
                            value="{{ old(
                                'planned_completion_date',
                                optional(
                                    $project->planned_completion_date
                                )->format('Y-m-d')
                            ) }}"
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
                            value="{{ old(
                                'actual_completion_date',
                                optional(
                                    $project->actual_completion_date
                                )->format('Y-m-d')
                            ) }}"
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
                    >{{ old(
                        'development_objective',
                        $project->development_objective
                    ) }}</textarea>

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
                    >{{ old(
                        'scope_summary',
                        $project->scope_summary
                    ) }}</textarea>

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
                    >{{ old(
                        'development_scope',
                        $project->development_scope
                    ) }}</textarea>

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
                            value="{{ old(
                                'development_area',
                                $project->development_area
                            ) }}"
                        >

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
                            value="{{ old(
                                'planned_gla',
                                $project->planned_gla
                            ) }}"
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
                            value="{{ old(
                                'planned_nla',
                                $project->planned_nla
                            ) }}"
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
                            value="{{ old(
                                'planned_leasable_area',
                                $project->planned_leasable_area
                            ) }}"
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
                >{{ old(
                    'remarks',
                    $project->remarks
                ) }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Actions --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.show',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Update Project
            </button>

        </div>

    </form>

</div>

@endsection