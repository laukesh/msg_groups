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
                Edit Agenda Item
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
    {{-- SUCCESS --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- UPDATE FORM --}}
    {{-- ========================================================= --}}

    <form
        method="POST"
        action="{{ route(
            'admin.projects.governance-meetings.agenda-items.update',
            [
                'project' => $project->id,
                'meeting' => $meeting->id,
                'agendaItem' => $agendaItem->id,
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
                    Agenda Item Information
                </strong>

                <div class="text-muted small mt-1">
                    Update the individual matter being discussed.
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
                                $agendaItem->item_no
                            ) }}"
                            min="1"
                            required
                        >

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
                                            $agendaItem->priority
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
                                            $agendaItem->status
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
                                        $agendaItem->decision_required
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
                        value="{{ old(
                            'subject',
                            $agendaItem->subject
                        ) }}"
                        maxlength="255"
                        required
                    >

                </div>


                {{-- Description --}}

                <div>

                    <label class="form-label">
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="7"
                        class="form-control"
                        placeholder="Describe the matter, background, proposal or issue..."
                    >{{ old(
                        'description',
                        $agendaItem->description
                    ) }}</textarea>

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
                                            'presenter_id',
                                            $agendaItem->presenter_id
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
                                'presenter_name',
                                $agendaItem->presenter_name
                            ) }}"
                            maxlength="255"
                            placeholder="External presenter name"
                        >

                        <div class="form-text">
                            Use this for an external presenter.
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- DISCUSSION --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Discussion
                </strong>

                <div class="text-muted small mt-1">
                    Record the key points discussed during the meeting.
                </div>

            </div>


            <div class="card-body">

                <textarea
                    name="discussion"
                    rows="10"
                    class="form-control"
                    placeholder="Record key points, issues raised, comments and discussion..."
                >{{ old(
                    'discussion',
                    $agendaItem->discussion
                ) }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- OUTCOME --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Outcome / Resolution
                </strong>

                <div class="text-muted small mt-1">
                    Record the agreed outcome, conclusion or resolution.
                </div>

            </div>


            <div class="card-body">

                <textarea
                    name="outcome"
                    rows="9"
                    class="form-control"
                    placeholder="Record the agreed outcome, conclusion or resolution..."
                >{{ old(
                    'outcome',
                    $agendaItem->outcome
                ) }}</textarea>

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
                >{{ old(
                    'remarks',
                    $agendaItem->remarks
                ) }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="alert alert-light border mb-4">

            <div class="row">

                <div class="col-md-6">

                    <strong>
                        Agenda Item Status
                    </strong>

                    <div class="mt-2">

                        <span class="badge bg-primary me-1">
                            Open
                        </span>

                        <span class="badge bg-info text-dark me-1">
                            Discussed
                        </span>

                        <span class="badge bg-warning text-dark me-1">
                            Deferred
                        </span>

                        <span class="badge bg-success">
                            Closed
                        </span>

                    </div>

                </div>


                <div class="col-md-6">

                    <strong>
                        Decision
                    </strong>

                    <div class="text-muted small mt-2">

                        Mark "Decision Required" when the item
                        requires formal approval or resolution.

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- UPDATE ACTIONS --}}
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
                Save Changes
            </button>

        </div>

    </form>


    {{-- ========================================================= --}}
    {{-- DELETE --}}
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
                        Delete Agenda Item
                    </div>

                    <div class="text-muted small">
                        This action cannot be undone.
                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.governance-meetings.agenda-items.destroy',
                        [
                            'project' => $project->id,
                            'meeting' => $meeting->id,
                            'agendaItem' => $agendaItem->id,
                        ]
                    ) }}"
                    onsubmit="return confirm(
                        'Are you sure you want to delete this agenda item?'
                    );"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-outline-danger"
                    >
                        Delete Agenda Item
                    </button>

                </form>

            </div>

        </div>

    </div>

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

                    presenterName.value =
                        selectedOption.dataset.name;

                }

            }
        );

    }
);

</script>

@endsection