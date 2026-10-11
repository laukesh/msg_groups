@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Governance / Meetings / Attendees
            </div>

            <h3 class="mb-1">
                Add Meeting Attendee
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
                    'admin.projects.governance-meetings.attendees.index',
                    [
                        'project' => $project->id,
                        'meeting' => $meeting->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Attendee Register
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
            'admin.projects.governance-meetings.attendees.store',
            [
                'project' => $project->id,
                'meeting' => $meeting->id,
            ]
        ) }}"
    >

        @csrf


        {{-- ===================================================== --}}
        {{-- ATTENDEE INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Attendee Information
                </strong>

                <div class="text-muted small mt-1">
                    Add an internal system user or an external participant.
                </div>

            </div>


            <div class="card-body">

                {{-- ================================================= --}}
                {{-- SYSTEM USER --}}
                {{-- ================================================= --}}

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            System User
                        </label>

                        <select
                            name="user_id"
                            id="user_id"
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
                                        old('user_id') == $user->id
                                    )
                                >

                                    {{ $user->name }}

                                </option>

                            @endforeach

                        </select>


                        <div class="form-text">
                            Select a user if this participant has
                            an account in the system.
                        </div>

                    </div>


                    {{-- Attendee Name --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Attendee Name

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="attendee_name"
                            id="attendee_name"
                            class="form-control"
                            value="{{ old('attendee_name') }}"
                            placeholder="Enter attendee name"
                            required
                        >


                        <div class="form-text">
                            For system users this can be automatically
                            populated. External participants must be
                            entered manually.
                        </div>

                    </div>

                </div>


                <div class="row">

                    {{-- Role --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Attendee Role
                        </label>

                        <input
                            type="text"
                            name="attendee_role"
                            class="form-control"
                            value="{{ old('attendee_role') }}"
                            placeholder="e.g. Project Manager, Consultant, Sponsor"
                        >

                    </div>


                    {{-- Organization --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Organization
                        </label>

                        <input
                            type="text"
                            name="organization"
                            class="form-control"
                            value="{{ old('organization') }}"
                            placeholder="Company / Department / Organization"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- ATTENDANCE --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Attendance
                </strong>

                <div class="text-muted small mt-1">
                    Record the participant's attendance status.
                </div>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Attendance Status --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Attendance Status

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="attendance_status"
                            id="attendance_status"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Invited',
                                'Present',
                                'Absent',
                                'Apologies',
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        old(
                                            'attendance_status',
                                            'Invited'
                                        ) === $status
                                    )
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Joined At --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Joined At
                        </label>

                        <input
                            type="time"
                            name="joined_at"
                            class="form-control"
                            value="{{ old('joined_at') }}"
                        >

                    </div>


                    {{-- Left At --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Left At
                        </label>

                        <input
                            type="time"
                            name="left_at"
                            class="form-control"
                            value="{{ old('left_at') }}"
                        >

                    </div>

                </div>


                <div class="alert alert-light border mb-0">

                    <strong>
                        Attendance status:
                    </strong>

                    <div class="mt-2">

                        <span class="badge bg-primary me-2">
                            Invited
                        </span>

                        <span class="text-muted small me-3">
                            Expected participant
                        </span>


                        <span class="badge bg-success me-2">
                            Present
                        </span>

                        <span class="text-muted small me-3">
                            Attended
                        </span>


                        <span class="badge bg-danger me-2">
                            Absent
                        </span>

                        <span class="text-muted small me-3">
                            Did not attend
                        </span>


                        <span class="badge bg-warning text-dark me-2">
                            Apologies
                        </span>

                        <span class="text-muted small">
                            Unable to attend
                        </span>

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
                    placeholder="Additional notes about this attendee..."
                >{{ old('remarks') }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- ACTIONS --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.governance-meetings.attendees.index',
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
                Add Attendee
            </button>

        </div>

    </form>

</div>


{{-- ============================================================= --}}
{{-- AUTO POPULATE SYSTEM USER NAME --}}
{{-- ============================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const userSelect =
            document.getElementById('user_id');

        const attendeeName =
            document.getElementById('attendee_name');


        if (!userSelect || !attendeeName) {
            return;
        }


        userSelect.addEventListener(
            'change',
            function () {

                const selectedOption =
                    this.options[this.selectedIndex];


                if (
                    this.value &&
                    selectedOption.dataset.name
                ) {

                    /*
                     * Only populate automatically when the
                     * attendee name is currently empty.
                     */

                    if (
                        attendeeName.value.trim() === ''
                    ) {

                        attendeeName.value =
                            selectedOption.dataset.name;
                    }

                }

            }
        );

    }
);

</script>

@endsection