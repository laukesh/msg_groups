@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Governance / Meetings / Agenda
            </div>

            <h3 class="mb-1">
                Add Agenda Item
            </h3>

            <div class="text-muted">

                {{ $meeting->meeting_number }}

                ·

                {{ $meeting->committee_name }}

                ·

                {{ $project->project_name }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.governance-meetings.agenda-items.index',
                    [
                        'project' => $project->id,
                        'meeting' => $meeting->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Agenda Register
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
    {{-- FORM --}}
    {{-- ========================================================= --}}

    <form
        method="POST"
        action="{{ route(
            'admin.projects.governance-meetings.agenda-items.store',
            [
                'project' => $project->id,
                'meeting' => $meeting->id,
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
                    Agenda Item Information
                </strong>

                <div class="text-muted small mt-1">
                    Define the individual matter to be discussed.
                </div>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Item Number --}}

                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            Item No.

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="number"
                            name="item_no"
                            class="form-control"
                            value="{{ old(
                                'item_no',
                                $nextItemNo
                            ) }}"
                            min="1"
                            required
                        >

                        <div class="form-text">
                            Next available number is
                            {{ $nextItemNo }}.
                        </div>

                    </div>


                    {{-- Priority --}}

                    <div class="col-md-3 mb-3">

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


                    {{-- Status --}}

                    <div class="col-md-3 mb-3">

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
                                'Open',
                                'Discussed',
                                'Deferred',
                                'Closed',
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        old(
                                            'status',
                                            'Open'
                                        ) === $status
                                    )
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Decision Required --}}

                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Decision Required
                        </label>

                        <div class="form-check mt-2">

                            <input
                                type="checkbox"
                                name="decision_required"
                                value="1"
                                id="decision_required"
                                class="form-check-input"
                                @checked(
                                    old(
                                        'decision_required',
                                        false
                                    )
                                )
                            >

                            <label
                                for="decision_required"
                                class="form-check-label"
                            >
                                Yes, decision is required
                            </label>

                        </div>

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
                        placeholder="Enter agenda item subject"
                        maxlength="255"
                        required
                    >

                </div>


                {{-- Description --}}

                <div class="mb-0">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="6"
                        class="form-control"
                        placeholder="Describe the matter, background, proposal or issue to be discussed..."
                    >{{ old('description') }}</textarea>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- PRESENTER --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Presenter
                </strong>

                <div class="text-muted small mt-1">
                    Identify the person responsible for presenting this item.
                </div>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- System User --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            System User
                        </label>

                        <select
                            name="presenter_id"
                            id="presenter_id"
                            class="form-select"
                        >

                            <option value="">
                                External / No System Account
                            </option>


                            @foreach($users as $user)

                                <option
                                    value="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                    @selected(
                                        old(
                                            'presenter_id'
                                        ) == $user->id
                                    )
                                >

                                    {{ $user->name }}

                                </option>

                            @endforeach

                        </select>

                        <div class="form-text">
                            Select an internal system user where applicable.
                        </div>

                    </div>


                    {{-- Presenter Name --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Presenter Name
                        </label>

                        <input
                            type="text"
                            name="presenter_name"
                            id="presenter_name"
                            class="form-control"
                            value="{{ old(
                                'presenter_name'
                            ) }}"
                            placeholder="External presenter name"
                            maxlength="255"
                        >

                        <div class="form-text">
                            Use this for an external participant.
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- DISCUSSION & OUTCOME --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Discussion & Outcome
                </strong>

                <div class="text-muted small mt-1">
                    These fields can be completed during or after the meeting.
                </div>

            </div>


            <div class="card-body">

                {{-- Discussion --}}

                <div class="mb-4">

                    <label class="form-label">
                        Discussion
                    </label>

                    <textarea
                        name="discussion"
                        rows="8"
                        class="form-control"
                        placeholder="Record key points, issues raised, comments and discussion..."
                    >{{ old('discussion') }}</textarea>

                </div>


                {{-- Outcome --}}

                <div class="mb-0">

                    <label class="form-label">
                        Outcome
                    </label>

                    <textarea
                        name="outcome"
                        rows="7"
                        class="form-control"
                        placeholder="Record the agreed outcome, conclusion or resolution..."
                    >{{ old('outcome') }}</textarea>

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
                    placeholder="Additional notes about this agenda item..."
                >{{ old('remarks') }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="alert alert-light border mb-4">

            <strong>
                Agenda Item Lifecycle
            </strong>

            <div class="mt-2">

                <span class="badge bg-primary me-2">
                    Open
                </span>

                <span class="text-muted small me-3">
                    Matter is pending discussion
                </span>


                <span class="badge bg-info text-dark me-2">
                    Discussed
                </span>

                <span class="text-muted small me-3">
                    Discussion completed
                </span>


                <span class="badge bg-warning text-dark me-2">
                    Deferred
                </span>

                <span class="text-muted small me-3">
                    Carried forward
                </span>


                <span class="badge bg-success me-2">
                    Closed
                </span>

                <span class="text-muted small">
                    Matter completed
                </span>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- ACTIONS --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.governance-meetings.agenda-items.index',
                    [
                        'project' => $project->id,
                        'meeting' => $meeting->id,
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
                Add Agenda Item
            </button>

        </div>

    </form>

</div>


{{-- ============================================================= --}}
{{-- AUTO POPULATE PRESENTER NAME --}}
{{-- ============================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const presenterSelect =
            document.getElementById('presenter_id');

        const presenterName =
            document.getElementById('presenter_name');


        if (
            !presenterSelect ||
            !presenterName
        ) {
            return;
        }


        presenterSelect.addEventListener(
            'change',
            function () {

                const selectedOption =
                    this.options[
                        this.selectedIndex
                    ];


                if (
                    this.value &&
                    selectedOption.dataset.name
                ) {

                    /*
                     * Populate the name from the
                     * selected internal user.
                     */

                    presenterName.value =
                        selectedOption.dataset.name;

                }

            }
        );

    }
);

</script>

@endsection