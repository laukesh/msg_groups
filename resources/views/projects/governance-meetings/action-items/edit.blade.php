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
                Edit Action Item
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
            'admin.projects.governance-meetings.action-items.update',
            [
                'project' => $project->id,
                'meeting' => $meeting->id,
                'actionItem' => $actionItem->id,
            ]
        ) }}"
    >

        @csrf
        @method('PUT')


        {{-- ===================================================== --}}
        {{-- ACTION INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Action Information
                </strong>

                <div class="text-muted small mt-1">
                    Update the action, source agenda item and priority.
                </div>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Action No. --}}

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
                                $actionItem->action_no
                            ) }}"
                            min="1"
                            required
                        >

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
                                            'project_governance_meeting_agenda_item_id',
                                            $actionItem->project_governance_meeting_agenda_item_id
                                        ) == $agendaItem->id
                                    )
                                >

                                    Item {{ $agendaItem->item_no }}
                                    —
                                    {{ $agendaItem->subject }}

                                </option>

                            @endforeach

                        </select>

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
                                            $actionItem->priority
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
                            id="status"
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
                                            $actionItem->status
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
                        rows="7"
                        class="form-control"
                        required
                    >{{ old(
                        'action_description',
                        $actionItem->action_description
                    ) }}</textarea>

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
                    Update the person or organization responsible
                    for completing the action.
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
                                            'responsible_user_id',
                                            $actionItem->responsible_user_id
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
                                'responsible_name',
                                $actionItem->responsible_name
                            ) }}"
                            maxlength="255"
                            placeholder="External responsible person"
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
                                'responsible_organization',
                                $actionItem->responsible_organization
                            ) }}"
                            maxlength="255"
                            placeholder="Company / Department / Organization"
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
                    Manage the target completion date and completion record.
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
                            value="{{ old(
                                'due_date',
                                optional(
                                    $actionItem->due_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                        <div class="form-text">
                            Pending actions past this date are shown as overdue.
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
                                'completion_date',
                                optional(
                                    $actionItem->completion_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                        <div class="form-text">
                            Automatically populated when status is Completed
                            if left blank.
                        </div>

                    </div>


                    {{-- Current Status --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Current Status
                        </label>

                        <div class="border rounded p-3 bg-light">

                            <div class="d-flex align-items-center gap-2">

                                @php

                                    $currentStatus =
                                        old(
                                            'status',
                                            $actionItem->status
                                        );

                                    $statusClass =
                                        match($currentStatus) {

                                            'Open'
                                                => 'bg-primary',

                                            'In Progress'
                                                => 'bg-info text-dark',

                                            'Completed'
                                                => 'bg-success',

                                            'Overdue'
                                                => 'bg-danger',

                                            'Cancelled'
                                                => 'bg-secondary',

                                            default
                                                => 'bg-secondary',

                                        };

                                @endphp

                                <span
                                    class="badge {{ $statusClass }}"
                                >
                                    {{ $currentStatus }}
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
                        rows="6"
                        class="form-control"
                        placeholder="Describe how the action was completed, evidence submitted, or result achieved..."
                    >{{ old(
                        'completion_remarks',
                        $actionItem->completion_remarks
                    ) }}</textarea>

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
                >{{ old(
                    'remarks',
                    $actionItem->remarks
                ) }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- AUDIT INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Record Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <div class="text-muted small">
                            Action ID
                        </div>

                        <div class="fw-semibold">
                            #{{ $actionItem->id }}
                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Created
                        </div>

                        <div class="fw-semibold">

                            @if($actionItem->created_at)

                                {{ $actionItem->created_at->format(
                                    'd-m-Y H:i'
                                ) }}

                            @else

                                —

                            @endif

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Last Updated
                        </div>

                        <div class="fw-semibold">

                            @if($actionItem->updated_at)

                                {{ $actionItem->updated_at->format(
                                    'd-m-Y H:i'
                                ) }}

                            @else

                                —

                            @endif

                        </div>

                    </div>

                </div>

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
                        Delete Action Item
                    </div>

                    <div class="text-muted small">
                        This action cannot be undone.
                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.governance-meetings.action-items.destroy',
                        [
                            'project' => $project->id,
                            'meeting' => $meeting->id,
                            'actionItem' => $actionItem->id,
                        ]
                    ) }}"
                    onsubmit="return confirm(
                        'Are you sure you want to delete this action item?'
                    );"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-outline-danger"
                    >
                        Delete Action Item
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


{{-- ============================================================= --}}
{{-- RESPONSIBLE USER -> NAME --}}
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

        const statusSelect =
            document.getElementById(
                'status'
            );

        const completionDate =
            document.getElementById(
                'completion_date'
            );


        /*
        |--------------------------------------------------------------------------
        | Populate responsible name
        |--------------------------------------------------------------------------
        */

        if (
            responsibleSelect &&
            responsibleName
        ) {

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


        /*
        |--------------------------------------------------------------------------
        | Completion Date
        |--------------------------------------------------------------------------
        */

        if (
            statusSelect &&
            completionDate
        ) {

            statusSelect.addEventListener(
                'change',
                function () {

                    if (
                        this.value === 'Completed' &&
                        !completionDate.value
                    ) {

                        const today =
                            new Date();

                        const year =
                            today.getFullYear();

                        const month =
                            String(
                                today.getMonth() + 1
                            ).padStart(
                                2,
                                '0'
                            );

                        const day =
                            String(
                                today.getDate()
                            ).padStart(
                                2,
                                '0'
                            );

                        completionDate.value =
                            `${year}-${month}-${day}`;

                    }


                    if (
                        this.value !== 'Completed'
                    ) {

                        /*
                         * Do not automatically clear an
                         * existing date while editing.
                         *
                         * The controller will clear it
                         * during update when status is not
                         * Completed.
                         */

                    }

                }
            );

        }

    }
);

</script>

@endsection