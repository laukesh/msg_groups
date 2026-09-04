@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Governance
            </div>

            <h3 class="mb-1">
                Edit Governance Framework
            </h3>

            <div class="text-muted">

                {{ $project->project_name }}

                @if($project->project_number)
                    · {{ $project->project_number }}
                @endif

                · {{ $governance->governance_number }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.governance.show',
                    [
                        'project' => $project->id,
                        'governance' => $governance->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Back
            </a>

        </div>

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
    {{-- UPDATE FORM --}}
    {{-- ========================================================= --}}

    <form
        method="POST"
        action="{{ route(
            'admin.projects.governance.update',
            [
                'project' => $project->id,
                'governance' => $governance->id,
            ]
        ) }}"
    >

        @csrf
        @method('PUT')


        {{-- ===================================================== --}}
        {{-- BASIC INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Governance Framework
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Governance Number --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Governance Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $governance->governance_number }}"
                            readonly
                        >

                    </div>


                    {{-- Title --}}

                    <div class="col-md-8 mb-3">

                        <label class="form-label">

                            Title

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            value="{{ old(
                                'title',
                                $governance->title
                            ) }}"
                            required
                        >

                    </div>

                </div>


                <div class="row">

                    {{-- Governance Model --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Governance Model

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="governance_model"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Corporate',
                                'Project-Based',
                                'Program-Based',
                                'Joint Venture',
                                'Public Private Partnership',
                                'Other',
                            ] as $model)

                                <option
                                    value="{{ $model }}"
                                    @selected(
                                        old(
                                            'governance_model',
                                            $governance->governance_model
                                        ) === $model
                                    )
                                >
                                    {{ $model }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}

                    <div class="col-md-6 mb-3">

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
                                'Under Review',
                                'Superseded',
                                'Closed',
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        old(
                                            'status',
                                            $governance->status
                                        ) === $status
                                    )
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
        {{-- PROJECT LEADERSHIP --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Project Leadership
                </strong>

                <div class="text-muted small mt-1">
                    Define the primary governance leadership roles.
                </div>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Sponsor --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Project Sponsor
                        </label>

                        <select
                            name="project_sponsor_id"
                            class="form-select"
                        >

                            <option value="">
                                Select Sponsor
                            </option>


                            @foreach($users as $user)

                                <option
                                    value="{{ $user->id }}"
                                    @selected(
                                        old(
                                            'project_sponsor_id',
                                            $governance->project_sponsor_id
                                        ) == $user->id
                                    )
                                >
                                    {{ $user->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Director --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Project Director
                        </label>

                        <select
                            name="project_director_id"
                            class="form-select"
                        >

                            <option value="">
                                Select Director
                            </option>


                            @foreach($users as $user)

                                <option
                                    value="{{ $user->id }}"
                                    @selected(
                                        old(
                                            'project_director_id',
                                            $governance->project_director_id
                                        ) == $user->id
                                    )
                                >
                                    {{ $user->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Manager --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Project Manager
                        </label>

                        <select
                            name="project_manager_id"
                            class="form-select"
                        >

                            <option value="">
                                Select Manager
                            </option>


                            @foreach($users as $user)

                                <option
                                    value="{{ $user->id }}"
                                    @selected(
                                        old(
                                            'project_manager_id',
                                            $governance->project_manager_id
                                        ) == $user->id
                                    )
                                >
                                    {{ $user->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- GOVERNANCE OBJECTIVE --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Governance Objective
                </strong>

            </div>


            <div class="card-body">

                <textarea
                    name="governance_objective"
                    rows="6"
                    class="form-control"
                    placeholder="Describe the purpose and objectives of the project governance framework..."
                >{{ old(
                    'governance_objective',
                    $governance->governance_objective
                ) }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- DECISION MAKING --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Decision-Making Framework
                </strong>

                <div class="text-muted small mt-1">
                    Define how project decisions are made and escalated.
                </div>

            </div>


            <div class="card-body">

                <textarea
                    name="decision_making_framework"
                    rows="7"
                    class="form-control"
                    placeholder="Define decision-making authority, decision levels, delegated authority and decision escalation..."
                >{{ old(
                    'decision_making_framework',
                    $governance->decision_making_framework
                ) }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- APPROVAL FRAMEWORK --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Approval Framework
                </strong>

                <div class="text-muted small mt-1">
                    Define approval responsibilities and authority.
                </div>

            </div>


            <div class="card-body">

                <textarea
                    name="approval_framework"
                    rows="7"
                    class="form-control"
                    placeholder="Define who approves budgets, contracts, changes, payments, schedules and other project matters..."
                >{{ old(
                    'approval_framework',
                    $governance->approval_framework
                ) }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- ESCALATION FRAMEWORK --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Escalation Framework
                </strong>

                <div class="text-muted small mt-1">
                    Define when and how issues are escalated.
                </div>

            </div>


            <div class="card-body">

                <textarea
                    name="escalation_framework"
                    rows="7"
                    class="form-control"
                    placeholder="Define escalation levels, thresholds, responsible authorities and escalation timelines..."
                >{{ old(
                    'escalation_framework',
                    $governance->escalation_framework
                ) }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- REPORTING FRAMEWORK --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Reporting Framework
                </strong>

                <div class="text-muted small mt-1">
                    Define governance reporting requirements.
                </div>

            </div>


            <div class="card-body">

                <textarea
                    name="reporting_framework"
                    rows="7"
                    class="form-control"
                    placeholder="Define project reports, reporting frequency, recipients, dashboards and governance reporting requirements..."
                >{{ old(
                    'reporting_framework',
                    $governance->reporting_framework
                ) }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- MEETING FRAMEWORK --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Meeting & Committee Framework
                </strong>

                <div class="text-muted small mt-1">
                    Define governance meetings and review forums.
                </div>

            </div>


            <div class="card-body">

                <textarea
                    name="meeting_framework"
                    rows="7"
                    class="form-control"
                    placeholder="Define steering committee meetings, project review meetings, participants, frequency and decision recording..."
                >{{ old(
                    'meeting_framework',
                    $governance->meeting_framework
                ) }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- GOVERNANCE PERIOD --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Governance Period
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Effective Date --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Effective Date
                        </label>

                        <input
                            type="date"
                            name="effective_date"
                            class="form-control"
                            value="{{ old(
                                'effective_date',
                                $governance->effective_date
                                    ? $governance->effective_date->format('Y-m-d')
                                    : ''
                            ) }}"
                        >

                    </div>


                    {{-- Review Date --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Review Date
                        </label>

                        <input
                            type="date"
                            name="review_date"
                            class="form-control"
                            value="{{ old(
                                'review_date',
                                $governance->review_date
                                    ? $governance->review_date->format('Y-m-d')
                                    : ''
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
                    placeholder="Additional governance notes..."
                >{{ old(
                    'remarks',
                    $governance->remarks
                ) }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- UPDATE ACTIONS --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.governance.show',
                    [
                        'project' => $project->id,
                        'governance' => $governance->id,
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
                Save Changes
            </button>

        </div>

    </form>


    {{-- ========================================================= --}}
    {{-- DELETE FORM --}}
    {{-- OUTSIDE UPDATE FORM --}}
    {{-- ========================================================= --}}

    <div class="card border-danger mb-5">

        <div class="card-header text-danger">

            <strong>
                Danger Zone
            </strong>

        </div>


        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <div class="fw-semibold">
                        Delete Governance Framework
                    </div>

                    <div class="text-muted small">
                        This action cannot be undone.
                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.governance.destroy',
                        [
                            'project' => $project->id,
                            'governance' => $governance->id,
                        ]
                    ) }}"
                    onsubmit="return confirm(
                        'Are you sure you want to delete this governance framework?'
                    );"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-outline-danger"
                    >
                        Delete Governance
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection