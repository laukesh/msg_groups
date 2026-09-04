@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Governance / Meetings / Action Items
            </div>

            <h3 class="mb-1">
                Add Action Item
            </h3>

            <div class="text-muted">

                {{ $meeting->meeting_number }}

                ·

                {{ $meeting->committee_name }}

                ·

                {{ $project->project_name }}

            </div>

        </div>


        <div>

            <a
                href="{{ route(
                    'admin.projects.governance-meetings.action-items.index',
                    [
                        'project' => $project->id,
                        'meeting' => $meeting->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Action Register
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
            'admin.projects.governance-meetings.action-items.store',
            [
                'project' => $project->id,
                'meeting' => $meeting->id,
            ]
        ) }}"
    >

        @csrf


        {{-- ===================================================== --}}
        {{-- ACTION INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Action Information
                </strong>

                <div class="text-muted small mt-1">
                    Define the action that must be completed.
                </div>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Action Number --}}

                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            Action No.

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="number"
                            name="action_no"
                            class="form-control"
                            value="{{ old(
                                'action_no',
                                $nextActionNo
                            ) }}"
                            min="1"
                            required
                        >

                        <div class="form-text">
                            Next available number:
                            {{ $nextActionNo }}
                        </div>

                    </div>


                    {{-- Source Agenda --}}

                    <div class="col-md-5 mb-3">

                        <label class="form-label">
                            Source Agenda Item
                        </label>

                        <select
                            name="project_governance_meeting_agenda_item_id"
                            class="form-select"
                        >

                            <option value="">
                                — Not linked to an agenda item —
                            </option>


                            @foreach($agendaItems as $agendaItem)

                                <option
                                    value="{{ $agendaItem->id }}"
                                    @selected(
                                        old(
                                            'project_governance_meeting_agenda_item_id'
                                        ) == $agendaItem->id
                                    )
                                >

                                    Item {{ $agendaItem->item_no }}
                                    —
                                    {{ $agendaItem->subject }}

                                </option>

                            @endforeach

                        </select>

                        <div class="form-text">
                            Link this action to the agenda item
                            that generated it.
                        </div>

                    </div>


                    {{-- Priority --}}

                    <div class="col-md-2 mb-3">

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

                    <div class="col-md-2 mb-3">

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
                                'In Progress',
                                'Completed',
                                'Overdue',
                                'Cancelled',
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

                </div>


                {{-- Action Description --}}

                <div class="mb-0">

                    <label class="form-label">

                        Action Description

                        <span class="text-danger">*</span>

                    </label>

                    <textarea
                        name="action_description"
                        rows="6"
                        class="form-control"
                        placeholder="Describe exactly what needs to be done..."
                        required
                    >{{ old('action_description') }}</textarea>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- RESPONSIBLE PERSON --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Responsible Person / Organization
                </strong>

                <div class="text-muted small mt-1">
                    Assign responsibility for completing this action.
                </div>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- System User --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            System User
                        </label>

                        <select
                            name="responsible_user_id"
                            id="responsible_user_id"
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
                                            'responsible_user_id'
                                        ) == $user->id
                                    )
                                >

                                    {{ $user->name }}

                                </option>

                            @endforeach

                        </select>

                        <div class="form-text">
                            Select an internal user if applicable.
                        </div>

                    </div>


                    {{-- Responsible Name --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Responsible Name
                        </label>

                        <input
                            type="text"
                            name="responsible_name"
                            id="responsible_name"
                            class="form-control"
                            value="{{ old(
                                'responsible_name'
                            ) }}"
                            placeholder="External responsible person"
                            maxlength="255"
                        >

                    </div>


                    {{-- Organization --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Organization
                        </label>

                        <input
                            type="text"
                            name="responsible_organization"
                            class="form-control"
                            value="{{ old(
                                'responsible_organization'
                            ) }}"
                            placeholder="Company / Department / Organization"
                            maxlength="255"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- DEADLINE --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Deadline & Completion
                </strong>

                <div class="text-muted small mt-1">
                    Define the target completion date and, if applicable,
                    record completion information.
                </div>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Due Date --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Due Date
                        </label>

                        <input
                            type="date"
                            name="due_date"
                            class="form-control"
                            value="{{ old('due_date') }}"
                        >

                        <div class="form-text">
                            The system will identify pending actions
                            past this date as overdue.
                        </div>

                    </div>


                    {{-- Completion Date --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Completion Date
                        </label>

                        <input
                            type="date"
                            name="completion_date"
                            id="completion_date"
                            class="form-control"
                            value="{{ old(
                                'completion_date'
                            ) }}"
                        >

                        <div class="form-text">
                            Required when the action is completed.
                        </div>

                    </div>


                    {{-- Status Information --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Lifecycle
                        </label>

                        <div class="border rounded p-3 bg-light">

                            <div class="small mb-1">

                                <span class="badge bg-primary">
                                    Open
                                </span>

                                <span class="text-muted ms-1">
                                    Not started
                                </span>

                            </div>


                            <div class="small mb-1">

                                <span class="badge bg-info text-dark">
                                    In Progress
                                </span>

                                <span class="text-muted ms-1">
                                    Work underway
                                </span>

                            </div>


                            <div class="small">

                                <span class="badge bg-success">
                                    Completed
                                </span>

                                <span class="text-muted ms-1">
                                    Finished
                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Completion Remarks --}}

                <div class="mb-0">

                    <label class="form-label">
                        Completion Remarks
                    </label>

                    <textarea
                        name="completion_remarks"
                        rows="5"
                        class="form-control"
                        placeholder="Describe how the action was completed, evidence submitted, or result achieved..."
                    >{{ old('completion_remarks') }}</textarea>

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
                    placeholder="Additional notes about this action..."
                >{{ old('remarks') }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="alert alert-light border mb-4">

            <strong>
                Action Management
            </strong>

            <div class="text-muted small mt-2">

                Once an action is created, its status, responsible person,
                due date and completion information can be updated from
                the Action Register.

            </div>

            <div class="mt-3">

                <span class="badge bg-primary me-1">
                    Open
                </span>

                <span class="badge bg-info text-dark me-1">
                    In Progress
                </span>

                <span class="badge bg-success me-1">
                    Completed
                </span>

                <span class="badge bg-danger me-1">
                    Overdue
                </span>

                <span class="badge bg-secondary">
                    Cancelled
                </span>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- ACTIONS --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.governance-meetings.action-items.index',
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
                Add Action Item
            </button>

        </div>

    </form>

</div>


{{-- ============================================================= --}}
{{-- AUTO POPULATE RESPONSIBLE NAME --}}
{{-- ============================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const responsibleSelect =
            document.getElementById(
                'responsible_user_id'
            );

        const responsibleName =
            document.getElementById(
                'responsible_name'
            );


        if (
            !responsibleSelect ||
            !responsibleName
        ) {
            return;
        }


        responsibleSelect.addEventListener(
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

                    responsibleName.value =
                        selectedOption.dataset.name;

                }

            }
        );

    }
);

</script>

@endsection