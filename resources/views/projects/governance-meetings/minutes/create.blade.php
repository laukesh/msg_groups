@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Governance / Meetings / Minutes
            </div>

            <h3 class="mb-1">
                Prepare Meeting Minutes
            </h3>

            <div class="text-muted">

                {{ $meeting->meeting_number }}

                @if($meeting->meeting_title)
                    · {{ $meeting->meeting_title }}
                @endif

                ·

                {{ $project->project_name }}

            </div>

        </div>


        <div>

            <a
                href="{{ route(
                    'admin.projects.governance-meetings.show',
                    [
                        'project' => $project->id,
                        'meeting' => $meeting->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Back to Meeting
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
            'admin.projects.governance-meetings.minutes.store',
            [
                'project' => $project->id,
                'meeting' => $meeting->id,
            ]
        ) }}"
    >

        @csrf


        {{-- ===================================================== --}}
        {{-- MINUTES INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Minutes Information
                </strong>

                <div class="text-muted small mt-1">
                    Basic information for the official meeting minutes.
                </div>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Minutes Number --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Minutes Number

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="minutes_number"
                            class="form-control"
                            value="{{ old(
                                'minutes_number',
                                $nextMinutesNumber
                            ) }}"
                            maxlength="100"
                            required
                        >

                        <div class="form-text">
                            Suggested number:
                            {{ $nextMinutesNumber }}
                        </div>

                    </div>


                    {{-- Prepared By --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Prepared By
                        </label>

                        <select
                            name="prepared_by"
                            class="form-select"
                        >

                            <option value="">
                                — Select Preparer —
                            </option>


                            @foreach($users as $user)

                                <option
                                    value="{{ $user->id }}"
                                    @selected(
                                        old(
                                            'prepared_by',
                                            auth()->id()
                                        ) == $user->id
                                    )
                                >
                                    {{ $user->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Prepared Date --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Prepared Date
                        </label>

                        <input
                            type="date"
                            name="prepared_date"
                            class="form-control"
                            value="{{ old(
                                'prepared_date',
                                now()->format('Y-m-d')
                            ) }}"
                        >

                    </div>


                    {{-- Status --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Minutes Status

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="minutes_status"
                            id="minutes_status"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Draft',
                                'Submitted',
                                'Approved',
                                'Rejected',
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        old(
                                            'minutes_status',
                                            'Draft'
                                        ) === $status
                                    )
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                        <div class="form-text">
                            Normally prepare the minutes as Draft,
                            then submit them for approval.
                        </div>

                    </div>


                    {{-- Approved By --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Approved By
                        </label>

                        <select
                            name="approved_by"
                            id="approved_by"
                            class="form-select"
                        >

                            <option value="">
                                — Select Approver —
                            </option>


                            @foreach($users as $user)

                                <option
                                    value="{{ $user->id }}"
                                    @selected(
                                        old(
                                            'approved_by'
                                        ) == $user->id
                                    )
                                >
                                    {{ $user->name }}
                                </option>

                            @endforeach

                        </select>

                        <div class="form-text">
                            Required only when status is Approved.
                        </div>

                    </div>


                    {{-- Approval Date --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Approval Date
                        </label>

                        <input
                            type="date"
                            name="approval_date"
                            id="approval_date"
                            class="form-control"
                            value="{{ old(
                                'approval_date'
                            ) }}"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- MEETING SNAPSHOT --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Meeting Snapshot
                </strong>

                <div class="text-muted small mt-1">
                    Reference information from the meeting.
                </div>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    {{-- Meeting Number --}}

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Meeting Number
                        </div>

                        <div class="fw-semibold">

                            {{ $meeting->meeting_number }}

                        </div>

                    </div>


                    {{-- Date --}}

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Meeting Date
                        </div>

                        <div class="fw-semibold">

                            @if($meeting->meeting_date)

                                {{ $meeting->meeting_date->format('d-m-Y') }}

                            @else

                                —

                            @endif

                        </div>

                    </div>


                    {{-- Type --}}

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Meeting Type
                        </div>

                        <div class="fw-semibold">

                            {{ $meeting->meeting_type ?? '—' }}

                        </div>

                    </div>


                    {{-- Venue --}}

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Venue
                        </div>

                        <div class="fw-semibold">

                            {{ $meeting->venue ?? '—' }}

                        </div>

                    </div>


                    {{-- Chairperson --}}

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Chairperson
                        </div>

                        <div class="fw-semibold">

                            {{ $meeting->chairperson->name ?? '—' }}

                        </div>

                    </div>


                    {{-- Secretary --}}

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Secretary
                        </div>

                        <div class="fw-semibold">

                            {{ $meeting->secretary->name ?? '—' }}

                        </div>

                    </div>


                    {{-- Project --}}

                    <div class="col-md-6">

                        <div class="text-muted small">
                            Project
                        </div>

                        <div class="fw-semibold">

                            {{ $project->project_name }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- OPENING --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    1. Opening / Welcome
                </strong>

                <div class="text-muted small mt-1">
                    Record how the meeting commenced.
                </div>

            </div>


            <div class="card-body">

                <textarea
                    name="opening_summary"
                    rows="5"
                    class="form-control"
                    placeholder="Record the opening, welcome remarks, confirmation of quorum, purpose of meeting, etc."
                >{{ old('opening_summary') }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- ATTENDANCE --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    2. Attendance Summary
                </strong>

                <div class="text-muted small mt-1">
                    Summarize attendance and apologies. Detailed
                    attendance remains available in the Attendees register.
                </div>

            </div>


            <div class="card-body">

                <textarea
                    name="attendance_summary"
                    rows="5"
                    class="form-control"
                    placeholder="Summarize attendees, absentees, apologies, quorum confirmation, etc."
                >{{ old('attendance_summary') }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- DISCUSSION --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    3. Discussion Summary
                </strong>

                <div class="text-muted small mt-1">
                    Record the substantive discussions held during the
                    meeting.
                </div>

            </div>


            <div class="card-body">

                <textarea
                    name="discussion_summary"
                    rows="10"
                    class="form-control"
                    placeholder="Record the main discussions, concerns, observations, presentations and deliberations..."
                >{{ old('discussion_summary') }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- KEY MATTERS --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    4. Key Matters Discussed
                </strong>

                <div class="text-muted small mt-1">
                    Capture the major matters requiring governance
                    attention or follow-up.
                </div>

            </div>


            <div class="card-body">

                <textarea
                    name="key_matters_discussed"
                    rows="8"
                    class="form-control"
                    placeholder="List the key matters discussed during the meeting..."
                >{{ old('key_matters_discussed') }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- DECISIONS --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <strong>
                            5. Decisions / Resolutions Summary
                        </strong>

                        <div class="text-muted small mt-1">
                            Summarize decisions made during the meeting.
                            Detailed decisions are maintained in the
                            Decisions register.
                        </div>

                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.governance-meetings.decisions.index',
                            [
                                'project' => $project->id,
                                'meeting' => $meeting->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-outline-primary"
                    >
                        View Decisions
                    </a>

                </div>

            </div>


            <div class="card-body">

                <textarea
                    name="decisions_summary"
                    rows="8"
                    class="form-control"
                    placeholder="Summarize the formal decisions, resolutions, directions and recommendations..."
                >{{ old('decisions_summary') }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- ACTIONS --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <strong>
                            6. Action Summary
                        </strong>

                        <div class="text-muted small mt-1">
                            Summarize actions agreed during the meeting.
                            Detailed actions are maintained in the
                            Action Items register.
                        </div>

                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.governance-meetings.action-items.index',
                            [
                                'project' => $project->id,
                                'meeting' => $meeting->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-outline-primary"
                    >
                        View Actions
                    </a>

                </div>

            </div>


            <div class="card-body">

                <textarea
                    name="action_summary"
                    rows="8"
                    class="form-control"
                    placeholder="Summarize the actions, responsible parties, target dates and follow-up requirements..."
                >{{ old('action_summary') }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- CLOSING --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    7. Closing / Adjournment
                </strong>

                <div class="text-muted small mt-1">
                    Record the closing remarks and adjournment.
                </div>

            </div>


            <div class="card-body">

                <textarea
                    name="closing_summary"
                    rows="5"
                    class="form-control"
                    placeholder="Record closing remarks, next meeting reference, adjournment time, etc."
                >{{ old('closing_summary') }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- GENERAL REMARKS --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    General Remarks
                </strong>

            </div>


            <div class="card-body">

                <textarea
                    name="general_remarks"
                    rows="6"
                    class="form-control"
                    placeholder="Additional governance notes, references or observations..."
                >{{ old('general_remarks') }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- APPROVAL INFORMATION --}}
        {{-- ===================================================== --}}

        <div
            class="card mb-4"
            id="approvalSection"
        >

            <div class="card-header">

                <strong>
                    Approval
                </strong>

                <div class="text-muted small mt-1">
                    Approval information is required only when the
                    minutes are formally approved.
                </div>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Approved By
                        </label>

                        <select
                            name="approved_by"
                            id="approvalApprover"
                            class="form-select"
                        >

                            <option value="">
                                — Select Approver —
                            </option>


                            @foreach($users as $user)

                                <option
                                    value="{{ $user->id }}"
                                    @selected(
                                        old(
                                            'approved_by'
                                        ) == $user->id
                                    )
                                >
                                    {{ $user->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Approval Date
                        </label>

                        <input
                            type="date"
                            name="approval_date"
                            id="approvalDate"
                            class="form-control"
                            value="{{ old(
                                'approval_date'
                            ) }}"
                        >

                    </div>

                </div>


                <div
                    id="approvalNotice"
                    class="alert alert-light border mb-0"
                >
                    Minutes will normally be created as
                    <strong>Draft</strong> and submitted later for approval.
                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- REMARKS --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Record Remarks
                </strong>

            </div>


            <div class="card-body">

                <textarea
                    name="remarks"
                    rows="5"
                    class="form-control"
                    placeholder="Additional record-level remarks..."
                >{{ old('remarks') }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="alert alert-light border mb-4">

            <strong>
                Minutes Workflow
            </strong>

            <div class="text-muted small mt-2">

                Prepare the minutes as Draft, review the contents,
                and then submit them for approval. Once approved,
                the minutes become the official governance record
                for this meeting.

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- ACTIONS --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.governance-meetings.show',
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
                Create Minutes
            </button>

        </div>

    </form>

</div>


{{-- ============================================================= --}}
{{-- APPROVAL STATUS SCRIPT --}}
{{-- ============================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const statusSelect =
            document.getElementById(
                'minutes_status'
            );

        const approver =
            document.getElementById(
                'approvalApprover'
            );

        const approvalDate =
            document.getElementById(
                'approvalDate'
            );

        const notice =
            document.getElementById(
                'approvalNotice'
            );


        if (!statusSelect) {
            return;
        }


        function updateApprovalState()
        {
            const status =
                statusSelect.value;


            if (status === 'Approved') {

                if (approver) {

                    approver.required =
                        true;

                }


                if (notice) {

                    notice.classList.remove(
                        'alert-light'
                    );

                    notice.classList.add(
                        'alert-success'
                    );

                    notice.innerHTML =
                        'The minutes are being created as ' +
                        '<strong>Approved</strong>. ' +
                        'Approved By is required. ' +
                        'If the approval date is blank, ' +
                        'the system will use today\'s date.';

                }

            } else {

                if (approver) {

                    approver.required =
                        false;

                }


                if (notice) {

                    notice.classList.remove(
                        'alert-success'
                    );

                    notice.classList.add(
                        'alert-light'
                    );

                    notice.innerHTML =
                        'Minutes will normally be created as ' +
                        '<strong>Draft</strong> and submitted later ' +
                        'for approval.';

                }

            }
        }


        statusSelect.addEventListener(
            'change',
            updateApprovalState
        );


        updateApprovalState();

    }
);

</script>

@endsection