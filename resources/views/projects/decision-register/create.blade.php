@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Governance / Decision Register
            </div>

            <h3 class="mb-1">
                Register New Decision
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
                'admin.projects.decision-register.index',
                [
                    'project' => $project->id,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            ← Decision Register
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
            'admin.projects.decision-register.store',
            [
                'project' => $project->id,
            ]
        ) }}"
    >

        @csrf


        {{-- ===================================================== --}}
        {{-- DECISION INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Decision Information
                </strong>

                <div class="text-muted small mt-1">
                    Record the basic details of the project decision.
                </div>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Decision Number --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Decision Number

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="decision_number"
                            class="form-control"
                            value="{{ old(
                                'decision_number',
                                $decisionNumber
                            ) }}"
                            readonly
                        >

                    </div>


                    {{-- Decision Date --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Decision Date

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="date"
                            name="decision_date"
                            class="form-control"
                            value="{{ old(
                                'decision_date',
                                now()->format('Y-m-d')
                            ) }}"
                            required
                        >

                    </div>


                    {{-- Decision Type --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Decision Type

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="decision_type"
                            class="form-control"
                            value="{{ old('decision_type') }}"
                            placeholder="e.g. Schedule, Budget, Design, Commercial"
                            required
                        >

                    </div>

                </div>


                {{-- Subject --}}

                <div class="mb-3">

                    <label class="form-label">

                        Subject

                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="text"
                        name="subject"
                        class="form-control"
                        value="{{ old('subject') }}"
                        placeholder="Enter the subject of the decision"
                        required
                    >

                </div>


                {{-- Decision --}}

                <div class="mb-3">

                    <label class="form-label">

                        Decision

                        <span class="text-danger">*</span>

                    </label>

                    <textarea
                        name="decision"
                        rows="5"
                        class="form-control"
                        placeholder="Describe the decision that was made..."
                        required
                    >{{ old('decision') }}</textarea>

                </div>


                {{-- Rationale --}}

                <div class="mb-0">

                    <label class="form-label">
                        Rationale
                    </label>

                    <textarea
                        name="rationale"
                        rows="5"
                        class="form-control"
                        placeholder="Explain why this decision was made..."
                    >{{ old('rationale') }}</textarea>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- GOVERNANCE --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Governance & Decision Authority
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Governance --}}

                    <div class="col-md-6 mb-3">

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
                            Link this decision to the governance
                            framework under which it was made.
                        </div>

                    </div>


                    {{-- Decision Maker Role --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Decision Maker Role
                        </label>

                        <input
                            type="text"
                            name="decision_maker_role"
                            class="form-control"
                            value="{{ old(
                                'decision_maker_role'
                            ) }}"
                            placeholder="e.g. Project Sponsor, Steering Committee"
                        >

                    </div>

                </div>


                <div class="row">

                    {{-- Decision Maker --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Decision Maker
                        </label>

                        <select
                            name="decision_maker_id"
                            class="form-select"
                        >

                            <option value="">
                                Not Specified
                            </option>


                            @foreach($users as $user)

                                <option
                                    value="{{ $user->id }}"
                                    @selected(
                                        old(
                                            'decision_maker_id'
                                        ) == $user->id
                                    )
                                >

                                    {{ $user->name }}

                                </option>

                            @endforeach

                        </select>


                        <div class="form-text">
                            Optional. Use this when a specific
                            user made the decision.
                        </div>

                    </div>


                    {{-- Reference Number --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Reference Number
                        </label>

                        <input
                            type="text"
                            name="reference_number"
                            class="form-control"
                            value="{{ old(
                                'reference_number'
                            ) }}"
                            placeholder="Meeting number, resolution number, memo number, etc."
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- PRIORITY & IMPACT --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Priority & Impact
                </strong>

                <div class="text-muted small mt-1">
                    Capture the significance and project impact
                    of this decision.
                </div>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Priority --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Priority

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="priority"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Low',
                                'Medium',
                                'High',
                                'Critical',
                            ] as $priority)

                                <option
                                    value="{{ $priority }}"
                                    @selected(
                                        old(
                                            'priority',
                                            'Medium'
                                        ) === $priority
                                    )
                                >
                                    {{ $priority }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Financial Impact --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Financial Impact
                        </label>

                        <input
                            type="number"
                            name="financial_impact"
                            class="form-control"
                            value="{{ old(
                                'financial_impact'
                            ) }}"
                            step="0.01"
                            placeholder="0.00"
                        >

                        <div class="form-text">
                            Positive or negative financial impact.
                        </div>

                    </div>


                    {{-- Schedule Impact --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Schedule Impact (Days)
                        </label>

                        <input
                            type="number"
                            name="schedule_impact_days"
                            class="form-control"
                            value="{{ old(
                                'schedule_impact_days'
                            ) }}"
                            placeholder="e.g. 30"
                        >

                        <div class="form-text">
                            Positive = delay, negative = acceleration.
                        </div>

                    </div>

                </div>


                {{-- Impact Description --}}

                <div class="mb-0">

                    <label class="form-label">
                        Impact Description
                    </label>

                    <textarea
                        name="impact_description"
                        rows="5"
                        class="form-control"
                        placeholder="Describe the financial, schedule, scope, quality or other project impact..."
                    >{{ old(
                        'impact_description'
                    ) }}</textarea>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- IMPLEMENTATION --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Implementation
                </strong>

                <div class="text-muted small mt-1">
                    Define whether this decision requires follow-up
                    implementation.
                </div>

            </div>


            <div class="card-body">

                <div class="form-check mb-4">

                    <input
                        type="hidden"
                        name="implementation_required"
                        value="0"
                    >

                    <input
                        type="checkbox"
                        name="implementation_required"
                        value="1"
                        class="form-check-input"
                        id="implementation_required"
                        @checked(
                            old(
                                'implementation_required',
                                false
                            )
                        )
                    >

                    <label
                        class="form-check-label fw-semibold"
                        for="implementation_required"
                    >
                        This decision requires implementation
                    </label>

                </div>


                <div class="row">

                    {{-- Implementation Owner --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Implementation Owner
                        </label>

                        <select
                            name="implementation_owner_id"
                            class="form-select"
                        >

                            <option value="">
                                Not Assigned
                            </option>


                            @foreach($users as $user)

                                <option
                                    value="{{ $user->id }}"
                                    @selected(
                                        old(
                                            'implementation_owner_id'
                                        ) == $user->id
                                    )
                                >

                                    {{ $user->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Due Date --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Implementation Due Date
                        </label>

                        <input
                            type="date"
                            name="implementation_due_date"
                            class="form-control"
                            value="{{ old(
                                'implementation_due_date'
                            ) }}"
                        >

                    </div>


                    {{-- Implemented Date --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Implemented Date
                        </label>

                        <input
                            type="date"
                            name="implemented_date"
                            class="form-control"
                            value="{{ old(
                                'implemented_date'
                            ) }}"
                        >

                    </div>

                </div>


                <div class="alert alert-light border mb-0">

                    <strong>
                        Note:
                    </strong>

                    If implementation is not required, the
                    implementation owner and dates will be cleared
                    automatically when the record is saved.

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- STATUS --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Decision Status
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

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
                                'Approved',
                                'Implemented',
                                'Superseded',
                                'Cancelled',
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
                    placeholder="Additional notes about this decision..."
                >{{ old('remarks') }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- ACTIONS --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.decision-register.index',
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
                Register Decision
            </button>

        </div>

    </form>

</div>

@endsection