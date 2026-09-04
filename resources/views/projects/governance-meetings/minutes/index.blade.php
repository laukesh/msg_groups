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
                Meeting Minutes
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
                    'admin.projects.governance-meetings.show',
                    [
                        'project' => $project->id,
                        'meeting' => $meeting->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Meeting
            </a>


            @if(!$minutes)

                <a
                    href="{{ route(
                        'admin.projects.governance-meetings.minutes.create',
                        [
                            'project' => $project->id,
                            'meeting' => $meeting->id,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    + Create Minutes
                </a>

            @else

                <a
                    href="{{ route(
                        'admin.projects.governance-meetings.minutes.edit',
                        [
                            'project' => $project->id,
                            'meeting' => $meeting->id,
                            'minutes' => $minutes->id,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    Edit Minutes
                </a>

            @endif

        </div>

    </div>


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
    {{-- MEETING INFORMATION --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Meeting Information
            </strong>

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


                {{-- Meeting Date --}}

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


                {{-- Meeting Type --}}

                <div class="col-md-3">

                    <div class="text-muted small">
                        Meeting Type
                    </div>

                    <div class="fw-semibold">

                        {{ $meeting->meeting_type ?? '—' }}

                    </div>

                </div>


                {{-- Status --}}

                <div class="col-md-3">

                    <div class="text-muted small">
                        Meeting Status
                    </div>

                    <div class="fw-semibold">

                        {{ $meeting->status ?? '—' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- NO MINUTES --}}
    {{-- ========================================================= --}}

    @if(!$minutes)

        <div class="card mb-5">

            <div class="card-body text-center py-5">

                <div class="mb-3">

                    <span
                        class="d-inline-flex
                               align-items-center
                               justify-content-center
                               rounded-circle
                               border"
                        style="
                            width:64px;
                            height:64px;
                            font-size:28px;
                        "
                    >
                        📝
                    </span>

                </div>


                <h5>
                    No Meeting Minutes
                </h5>


                <p class="text-muted mb-4">

                    Official minutes have not yet been prepared
                    for this meeting.

                </p>


                <a
                    href="{{ route(
                        'admin.projects.governance-meetings.minutes.create',
                        [
                            'project' => $project->id,
                            'meeting' => $meeting->id,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    + Create Meeting Minutes
                </a>

            </div>

        </div>

    @else

        {{-- ===================================================== --}}
        {{-- MINUTES SUMMARY --}}
        {{-- ===================================================== --}}

        @php

            $statusClass = match(
                $minutes->minutes_status
            ) {

                'Draft'
                    => 'bg-warning text-dark',

                'Submitted'
                    => 'bg-primary',

                'Approved'
                    => 'bg-success',

                'Rejected'
                    => 'bg-danger',

                default
                    => 'bg-secondary',

            };

        @endphp


        <div class="row g-3 mb-4">

            {{-- Minutes Number --}}

            <div class="col-md-3">

                <div class="card h-100">

                    <div class="card-body">

                        <div class="text-muted small">
                            Minutes Number
                        </div>

                        <div class="fs-5 fw-semibold mt-1">

                            {{ $minutes->minutes_number }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- Status --}}

            <div class="col-md-3">

                <div class="card h-100">

                    <div class="card-body">

                        <div class="text-muted small">
                            Status
                        </div>

                        <div class="mt-2">

                            <span
                                class="badge {{ $statusClass }}"
                            >
                                {{ $minutes->minutes_status }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Prepared By --}}

            <div class="col-md-3">

                <div class="card h-100">

                    <div class="card-body">

                        <div class="text-muted small">
                            Prepared By
                        </div>

                        <div class="fw-semibold mt-1">

                            {{ $minutes->preparer->name ?? '—' }}

                        </div>


                        @if($minutes->prepared_date)

                            <div class="text-muted small">

                                {{
                                    $minutes->prepared_date
                                        ->format('d-m-Y')
                                }}

                            </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Approved By --}}

            <div class="col-md-3">

                <div class="card h-100">

                    <div class="card-body">

                        <div class="text-muted small">
                            Approved By
                        </div>

                        @if($minutes->approver)

                            <div class="fw-semibold mt-1">

                                {{ $minutes->approver->name }}

                            </div>


                            @if($minutes->approval_date)

                                <div class="text-success small">

                                    {{
                                        $minutes->approval_date
                                            ->format('d-m-Y')
                                    }}

                                </div>

                            @endif

                        @else

                            <div class="text-muted mt-1">
                                Not approved
                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- MINUTES CONTENT --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <strong>
                            Official Meeting Minutes
                        </strong>

                        <div class="text-muted small mt-1">
                            {{ $minutes->minutes_number }}
                        </div>

                    </div>


                    <span
                        class="badge {{ $statusClass }}"
                    >
                        {{ $minutes->minutes_status }}
                    </span>

                </div>

            </div>


            <div class="card-body">


                {{-- Opening --}}

                @if($minutes->opening_summary)

                    <div class="mb-4">

                        <h6 class="fw-semibold">
                            1. Opening / Welcome
                        </h6>

                        <div class="text-muted">

                            {!! nl2br(
                                e(
                                    $minutes->opening_summary
                                )
                            ) !!}

                        </div>

                    </div>

                @endif


                {{-- Attendance --}}

                @if($minutes->attendance_summary)

                    <div class="mb-4">

                        <h6 class="fw-semibold">
                            2. Attendance Summary
                        </h6>

                        <div class="text-muted">

                            {!! nl2br(
                                e(
                                    $minutes->attendance_summary
                                )
                            ) !!}

                        </div>

                    </div>

                @endif


                {{-- Discussion --}}

                @if($minutes->discussion_summary)

                    <div class="mb-4">

                        <h6 class="fw-semibold">
                            3. Discussion Summary
                        </h6>

                        <div class="text-muted">

                            {!! nl2br(
                                e(
                                    $minutes->discussion_summary
                                )
                            ) !!}

                        </div>

                    </div>

                @endif


                {{-- Key Matters --}}

                @if($minutes->key_matters_discussed)

                    <div class="mb-4">

                        <h6 class="fw-semibold">
                            4. Key Matters Discussed
                        </h6>

                        <div class="text-muted">

                            {!! nl2br(
                                e(
                                    $minutes->key_matters_discussed
                                )
                            ) !!}

                        </div>

                    </div>

                @endif


                {{-- Decisions --}}

                @if($minutes->decisions_summary)

                    <div class="mb-4">

                        <h6 class="fw-semibold">
                            5. Decisions Summary
                        </h6>

                        <div class="text-muted">

                            {!! nl2br(
                                e(
                                    $minutes->decisions_summary
                                )
                            ) !!}

                        </div>

                    </div>

                @endif


                {{-- Actions --}}

                @if($minutes->action_summary)

                    <div class="mb-4">

                        <h6 class="fw-semibold">
                            6. Action Summary
                        </h6>

                        <div class="text-muted">

                            {!! nl2br(
                                e(
                                    $minutes->action_summary
                                )
                            ) !!}

                        </div>

                    </div>

                @endif


                {{-- Closing --}}

                @if($minutes->closing_summary)

                    <div class="mb-4">

                        <h6 class="fw-semibold">
                            7. Closing / Adjournment
                        </h6>

                        <div class="text-muted">

                            {!! nl2br(
                                e(
                                    $minutes->closing_summary
                                )
                            ) !!}

                        </div>

                    </div>

                @endif


                {{-- General Remarks --}}

                @if($minutes->general_remarks)

                    <div class="mb-4">

                        <h6 class="fw-semibold">
                            General Remarks
                        </h6>

                        <div class="text-muted">

                            {!! nl2br(
                                e(
                                    $minutes->general_remarks
                                )
                            ) !!}

                        </div>

                    </div>

                @endif


                {{-- Nothing entered --}}

                @if(
                    !$minutes->opening_summary &&
                    !$minutes->attendance_summary &&
                    !$minutes->discussion_summary &&
                    !$minutes->key_matters_discussed &&
                    !$minutes->decisions_summary &&
                    !$minutes->action_summary &&
                    !$minutes->closing_summary &&
                    !$minutes->general_remarks
                )

                    <div class="text-center py-4 text-muted">

                        No minutes content has been entered yet.

                    </div>

                @endif

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- APPROVAL INFORMATION --}}
        {{-- ===================================================== --}}

        @if(
            $minutes->minutes_status === 'Approved'
        )

            <div class="card border-success mb-4">

                <div class="card-header text-success">

                    <strong>
                        Approval
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row">

                        <div class="col-md-4">

                            <div class="text-muted small">
                                Approved By
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $minutes->approver->name
                                    ?? '—'
                                }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Approval Date
                            </div>

                            <div class="fw-semibold">

                                @if($minutes->approval_date)

                                    {{
                                        $minutes->approval_date
                                            ->format('d-m-Y')
                                    }}

                                @else

                                    —

                                @endif

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Status
                            </div>

                            <span class="badge bg-success">
                                Approved
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        @endif


        {{-- ===================================================== --}}
        {{-- REJECTION REMARKS --}}
        {{-- ===================================================== --}}

        @if(
            $minutes->minutes_status === 'Rejected' &&
            $minutes->remarks
        )

            <div class="alert alert-danger mb-4">

                <strong>
                    Rejection / Revision Remarks
                </strong>

                <div class="mt-2">

                    {!! nl2br(
                        e(
                            $minutes->remarks
                        )
                    ) !!}

                </div>

            </div>

        @endif


        {{-- ===================================================== --}}
        {{-- WORKFLOW ACTIONS --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Minutes Workflow
                </strong>

            </div>


            <div class="card-body">

                <div class="d-flex flex-wrap gap-2">

                    {{-- Edit --}}

                    @if(
                        in_array(
                            $minutes->minutes_status,
                            [
                                'Draft',
                                'Rejected'
                            ]
                        )
                    )

                        <a
                            href="{{ route(
                                'admin.projects.governance-meetings.minutes.edit',
                                [
                                    'project' =>
                                        $project->id,

                                    'meeting' =>
                                        $meeting->id,

                                    'minutes' =>
                                        $minutes->id,
                                ]
                            ) }}"
                            class="btn btn-outline-primary"
                        >
                            Edit Minutes
                        </a>

                    @endif


                    {{-- Submit --}}

                    @if(
                        in_array(
                            $minutes->minutes_status,
                            [
                                'Draft',
                                'Rejected'
                            ]
                        )
                    )

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.governance-meetings.minutes.submit',
                                [
                                    'project' =>
                                        $project->id,

                                    'meeting' =>
                                        $meeting->id,

                                    'minutes' =>
                                        $minutes->id,
                                ]
                            ) }}"
                            onsubmit="return confirm(
                                'Submit these minutes for approval?'
                            );"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Submit for Approval
                            </button>

                        </form>

                    @endif


                    {{-- Approve --}}

                    @if(
                        $minutes->minutes_status === 'Submitted'
                    )

                        <button
                            type="button"
                            class="btn btn-success"
                            data-bs-toggle="modal"
                            data-bs-target="#approveMinutesModal"
                        >
                            Approve Minutes
                        </button>

                    @endif


                    {{-- Reject --}}

                    @if(
                        $minutes->minutes_status === 'Submitted'
                    )

                        <button
                            type="button"
                            class="btn btn-outline-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#rejectMinutesModal"
                        >
                            Reject / Return
                        </button>

                    @endif


                    {{-- Delete --}}

                    @if(
                        $minutes->minutes_status !== 'Approved'
                    )

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.governance-meetings.minutes.destroy',
                                [
                                    'project' =>
                                        $project->id,

                                    'meeting' =>
                                        $meeting->id,

                                    'minutes' =>
                                        $minutes->id,
                                ]
                            ) }}"
                            onsubmit="return confirm(
                                'Are you sure you want to delete these minutes?'
                            );"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-outline-danger"
                            >
                                Delete
                            </button>

                        </form>

                    @endif

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- RECORD INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="card mb-5">

            <div class="card-header">

                <strong>
                    Record Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Minutes ID
                        </div>

                        <div class="fw-semibold">
                            #{{ $minutes->id }}
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Created
                        </div>

                        <div class="fw-semibold">

                            @if($minutes->created_at)

                                {{
                                    $minutes->created_at
                                        ->format('d-m-Y H:i')
                                }}

                            @else

                                —

                            @endif

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Last Updated
                        </div>

                        <div class="fw-semibold">

                            @if($minutes->updated_at)

                                {{
                                    $minutes->updated_at
                                        ->format('d-m-Y H:i')
                                }}

                            @else

                                —

                            @endif

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Current Status
                        </div>

                        <span
                            class="badge {{ $statusClass }}"
                        >
                            {{ $minutes->minutes_status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- APPROVE MODAL --}}
        {{-- ===================================================== --}}

        @if(
            $minutes->minutes_status === 'Submitted'
        )

            <div
                class="modal fade"
                id="approveMinutesModal"
                tabindex="-1"
                aria-hidden="true"
            >

                <div class="modal-dialog">

                    <div class="modal-content">

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.governance-meetings.minutes.approve',
                                [
                                    'project' =>
                                        $project->id,

                                    'meeting' =>
                                        $meeting->id,

                                    'minutes' =>
                                        $minutes->id,
                                ]
                            ) }}"
                        >

                            @csrf

                            <div class="modal-header">

                                <h5 class="modal-title">
                                    Approve Meeting Minutes
                                </h5>

                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                ></button>

                            </div>


                            <div class="modal-body">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Approved By

                                        <span class="text-danger">
                                            *
                                        </span>

                                    </label>

                                    <select
                                        name="approved_by"
                                        class="form-select"
                                        required
                                    >

                                        <option value="">
                                            — Select Approver —
                                        </option>


                                        @foreach(
                                            \App\Models\User::query()
                                                ->orderBy('name')
                                                ->get()
                                            as $user
                                        )

                                            <option
                                                value="{{ $user->id }}"
                                            >
                                                {{ $user->name }}
                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                <div class="mb-3">

                                    <label class="form-label">
                                        Approval Date
                                    </label>

                                    <input
                                        type="date"
                                        name="approval_date"
                                        class="form-control"
                                        value="{{ now()->format('Y-m-d') }}"
                                    >

                                </div>


                                <div class="alert alert-light border mb-0">

                                    Approving these minutes will make
                                    them an official governance record.

                                </div>

                            </div>


                            <div class="modal-footer">

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal"
                                >
                                    Cancel
                                </button>

                                <button
                                    type="submit"
                                    class="btn btn-success"
                                >
                                    Approve Minutes
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- REJECT MODAL --}}
            {{-- ================================================= --}}

            <div
                class="modal fade"
                id="rejectMinutesModal"
                tabindex="-1"
                aria-hidden="true"
            >

                <div class="modal-dialog">

                    <div class="modal-content">

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.governance-meetings.minutes.reject',
                                [
                                    'project' =>
                                        $project->id,

                                    'meeting' =>
                                        $meeting->id,

                                    'minutes' =>
                                        $minutes->id,
                                ]
                            ) }}"
                        >

                            @csrf

                            <div class="modal-header">

                                <h5 class="modal-title">
                                    Return Minutes for Revision
                                </h5>

                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                ></button>

                            </div>


                            <div class="modal-body">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Reason / Remarks

                                        <span class="text-danger">
                                            *
                                        </span>

                                    </label>

                                    <textarea
                                        name="remarks"
                                        rows="5"
                                        class="form-control"
                                        placeholder="Explain what needs to be revised..."
                                        required
                                    ></textarea>

                                </div>

                            </div>


                            <div class="modal-footer">

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal"
                                >
                                    Cancel
                                </button>

                                <button
                                    type="submit"
                                    class="btn btn-danger"
                                >
                                    Return for Revision
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        @endif

    @endif

</div>

@endsection