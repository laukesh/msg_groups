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
                Edit Meeting Minutes
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


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.governance-meetings.minutes.index',
                    [
                        'project' => $project->id,
                        'meeting' => $meeting->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Minutes
            </a>


            <a
                href="{{ route(
                    'admin.projects.governance-meetings.show',
                    [
                        'project' => $project->id,
                        'meeting' => $meeting->id,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                Meeting
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
    {{-- SUCCESS / ERROR --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- APPROVED NOTICE --}}
    {{-- ========================================================= --}}

    @if($minutes->minutes_status === 'Approved')

        <div class="alert alert-success mb-4">

            <div class="fw-semibold">
                These minutes are Approved.
            </div>

            <div class="small mt-1">

                Approved minutes are treated as the official
                governance record.

                @if($minutes->approval_date)

                    Approved on
                    {{ $minutes->approval_date->format('d-m-Y') }}.

                @endif

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- FORM --}}
    {{-- ========================================================= --}}

    <form
        method="POST"
        action="{{ route(
            'admin.projects.governance-meetings.minutes.update',
            [
                'project' => $project->id,
                'meeting' => $meeting->id,
                'minutes' => $minutes->id,
            ]
        ) }}"
    >

        @csrf
        @method('PUT')


        {{-- ===================================================== --}}
        {{-- MINUTES INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Minutes Information
                </strong>

                <div class="text-muted small mt-1">
                    Update the meeting minutes record.
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
                                $minutes->minutes_number
                            ) }}"
                            maxlength="100"
                            required
                        >

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
                                            $minutes->prepared_by
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
                                $minutes->prepared_date
                                    ? $minutes->prepared_date->format('Y-m-d')
                                    : ''
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
                            @disabled(
                                $minutes->minutes_status === 'Approved'
                            )
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
                                            $minutes->minutes_status
                                        ) === $status
                                    )
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                        @if(
                            $minutes->minutes_status === 'Approved'
                        )

                            <input
                                type="hidden"
                                name="minutes_status"
                                value="Approved"
                            >

                        @endif

                        <div class="form-text">

                            @if(
                                $minutes->minutes_status === 'Approved'
                            )

                                Approved status cannot be changed
                                through this edit form.

                            @else

                                Draft, Submitted and Rejected minutes
                                can be updated according to the workflow.

                            @endif

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
                            @disabled(
                                $minutes->minutes_status === 'Approved'
                            )
                        >

                            <option value="">
                                — Select Approver —
                            </option>


                            @foreach($users as $user)

                                <option
                                    value="{{ $user->id }}"
                                    @selected(
                                        old(
                                            'approved_by',
                                            $minutes->approved_by
                                        ) == $user->id
                                    )
                                >
                                    {{ $user->name }}
                                </option>

                            @endforeach

                        </select>

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
                                'approval_date',
                                $minutes->approval_date
                                    ? $minutes->approval_date->format('Y-m-d')
                                    : ''
                            ) }}"
                            @disabled(
                                $minutes->minutes_status === 'Approved'
                            )
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

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Meeting Number
                        </div>

                        <div class="fw-semibold">
                            {{ $meeting->meeting_number }}
                        </div>

                    </div>


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


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Meeting Type
                        </div>

                        <div class="fw-semibold">
                            {{ $meeting->meeting_type ?? '—' }}
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Venue
                        </div>

                        <div class="fw-semibold">
                            {{ $meeting->venue ?? '—' }}
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Chairperson
                        </div>

                        <div class="fw-semibold">
                            {{ $meeting->chairperson->name ?? '—' }}
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Secretary
                        </div>

                        <div class="fw-semibold">
                            {{ $meeting->secretary->name ?? '—' }}
                        </div>

                    </div>


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

            </div>


            <div class="card-body">

                <textarea
                    name="opening_summary"
                    rows="5"
                    class="form-control"
                    placeholder="Record the opening, welcome remarks, confirmation of quorum, purpose of meeting, etc."
                >{{ old(
                    'opening_summary',
                    $minutes->opening_summary
                ) }}</textarea>

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
                    Detailed attendance remains available in the
                    Attendees register.
                </div>

            </div>


            <div class="card-body">

                <textarea
                    name="attendance_summary"
                    rows="5"
                    class="form-control"
                    placeholder="Summarize attendees, absentees, apologies and quorum..."
                >{{ old(
                    'attendance_summary',
                    $minutes->attendance_summary
                ) }}</textarea>

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

            </div>


            <div class="card-body">

                <textarea
                    name="discussion_summary"
                    rows="10"
                    class="form-control"
                    placeholder="Record the main discussions, concerns, observations, presentations and deliberations..."
                >{{ old(
                    'discussion_summary',
                    $minutes->discussion_summary
                ) }}</textarea>

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

            </div>


            <div class="card-body">

                <textarea
                    name="key_matters_discussed"
                    rows="8"
                    class="form-control"
                    placeholder="Record the major matters requiring governance attention or follow-up..."
                >{{ old(
                    'key_matters_discussed',
                    $minutes->key_matters_discussed
                ) }}</textarea>

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
                            Detailed decisions remain in the Decisions
                            register.
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
                    placeholder="Summarize formal decisions, resolutions, directions and recommendations..."
                >{{ old(
                    'decisions_summary',
                    $minutes->decisions_summary
                ) }}</textarea>

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
                            Detailed action items remain in the Action
                            Items register.
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
                    placeholder="Summarize actions, responsible parties, target dates and follow-up requirements..."
                >{{ old(
                    'action_summary',
                    $minutes->action_summary
                ) }}</textarea>

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

            </div>


            <div class="card-body">

                <textarea
                    name="closing_summary"
                    rows="5"
                    class="form-control"
                    placeholder="Record closing remarks, next meeting reference, adjournment time, etc."
                >{{ old(
                    'closing_summary',
                    $minutes->closing_summary
                ) }}</textarea>

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
                >{{ old(
                    'general_remarks',
                    $minutes->general_remarks
                ) }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- APPROVAL --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Approval
                </strong>

            </div>


            <div class="card-body">

                <div
                    id="approvalNotice"
                    class="alert alert-light border mb-4"
                >
                    Approval information is only applicable when
                    minutes are approved.
                </div>


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
                                            'approved_by',
                                            $minutes->approved_by
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
                                'approval_date',
                                $minutes->approval_date
                                    ? $minutes->approval_date->format('Y-m-d')
                                    : ''
                            ) }}"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- RECORD REMARKS --}}
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
                >{{ old(
                    'remarks',
                    $minutes->remarks
                ) }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- WORKFLOW INFORMATION --}}
        {{-- ===================================================== --}}

        @if($minutes->minutes_status === 'Draft')

            <div class="alert alert-warning border mb-4">

                <strong>
                    Draft Minutes
                </strong>

                <div class="small mt-1">
                    Review all information carefully before submitting
                    these minutes for approval.
                </div>

            </div>

        @elseif($minutes->minutes_status === 'Submitted')

            <div class="alert alert-primary border mb-4">

                <strong>
                    Submitted for Approval
                </strong>

                <div class="small mt-1">
                    These minutes are currently awaiting approval.
                </div>

            </div>

        @elseif($minutes->minutes_status === 'Rejected')

            <div class="alert alert-danger border mb-4">

                <strong>
                    Minutes Returned for Revision
                </strong>

                @if($minutes->remarks)

                    <div class="small mt-2">

                        <strong>
                            Remarks:
                        </strong>

                        <div class="mt-1">

                            {!! nl2br(
                                e($minutes->remarks)
                            ) !!}

                        </div>

                    </div>

                @endif

            </div>

        @elseif($minutes->minutes_status === 'Approved')

            <div class="alert alert-success border mb-4">

                <strong>
                    Approved Minutes
                </strong>

                <div class="small mt-1">
                    These minutes are the official approved
                    governance record.
                </div>

            </div>

        @endif


        {{-- ===================================================== --}}
        {{-- ACTIONS --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.governance-meetings.minutes.index',
                    [
                        'project' => $project->id,
                        'meeting' => $meeting->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>


            @if($minutes->minutes_status !== 'Approved')

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Save Changes
                </button>

            @else

                <button
                    type="submit"
                    class="btn btn-primary"
                    disabled
                >
                    Approved — Locked
                </button>

            @endif

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
                'approval_date'
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
                        'These minutes are marked as ' +
                        '<strong>Approved</strong>. ' +
                        'Approved By is required.';

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
                        'Approval information is only applicable ' +
                        'when the minutes are approved.';

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