@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Governance / Meetings
            </div>

            <h3 class="mb-1">
                Schedule Governance Meeting
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
                'admin.projects.governance-meetings.index',
                [
                    'project' => $project->id,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            ← Meeting Register
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
            'admin.projects.governance-meetings.store',
            [
                'project' => $project->id,
            ]
        ) }}"
    >

        @csrf


        {{-- ===================================================== --}}
        {{-- MEETING INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Meeting Information
                </strong>

                <div class="text-muted small mt-1">
                    Define the basic meeting details and schedule.
                </div>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Meeting Number --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Meeting Number

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="meeting_number"
                            class="form-control"
                            value="{{ old(
                                'meeting_number',
                                $meetingNumber
                            ) }}"
                            readonly
                        >

                    </div>


                    {{-- Meeting Date --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Meeting Date

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="date"
                            name="meeting_date"
                            class="form-control"
                            value="{{ old(
                                'meeting_date',
                                now()->format('Y-m-d')
                            ) }}"
                            required
                        >

                    </div>


                    {{-- Meeting Type --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Meeting Type

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="meeting_type"
                            class="form-control"
                            value="{{ old('meeting_type') }}"
                            placeholder="e.g. Steering Committee, Project Review"
                            required
                        >

                    </div>

                </div>


                <div class="row">

                    {{-- Start Time --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Start Time
                        </label>

                        <input
                            type="time"
                            name="start_time"
                            class="form-control"
                            value="{{ old('start_time') }}"
                        >

                    </div>


                    {{-- End Time --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            End Time
                        </label>

                        <input
                            type="time"
                            name="end_time"
                            class="form-control"
                            value="{{ old('end_time') }}"
                        >

                    </div>


                    {{-- Meeting Mode --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Meeting Mode

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="meeting_mode"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Physical',
                                'Virtual',
                                'Hybrid',
                            ] as $mode)

                                <option
                                    value="{{ $mode }}"
                                    @selected(
                                        old(
                                            'meeting_mode',
                                            'Physical'
                                        ) === $mode
                                    )
                                >
                                    {{ $mode }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                <div class="row">

                    {{-- Committee --}}

                    <div class="col-md-8 mb-3">

                        <label class="form-label">

                            Committee Name

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="committee_name"
                            class="form-control"
                            value="{{ old(
                                'committee_name'
                            ) }}"
                            placeholder="e.g. Project Steering Committee"
                            required
                        >

                    </div>


                    {{-- Location --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Location
                        </label>

                        <input
                            type="text"
                            name="location"
                            class="form-control"
                            value="{{ old('location') }}"
                            placeholder="Meeting room / online platform"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- GOVERNANCE --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Governance & Participants
                </strong>

                <div class="text-muted small mt-1">
                    Link the meeting to its governance framework
                    and identify key meeting officials.
                </div>

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
                            Only governance frameworks belonging
                            to this project are shown.
                        </div>

                    </div>


                    {{-- Reference --}}

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
                            placeholder="Meeting notice / resolution / memo number"
                        >

                    </div>

                </div>


                <div class="row">

                    {{-- Chairperson --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Chairperson
                        </label>

                        <select
                            name="chairperson_id"
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
                                            'chairperson_id'
                                        ) == $user->id
                                    )
                                >

                                    {{ $user->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Secretary --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Secretary
                        </label>

                        <select
                            name="secretary_id"
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
                                            'secretary_id'
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
        {{-- AGENDA --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Meeting Agenda
                </strong>

                <div class="text-muted small mt-1">
                    Record the proposed topics to be discussed.
                </div>

            </div>


            <div class="card-body">

                <textarea
                    name="agenda"
                    rows="8"
                    class="form-control"
                    placeholder="Enter meeting agenda, key discussion points, matters requiring approval, etc."
                >{{ old('agenda') }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- MINUTES --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Meeting Minutes
                </strong>

                <div class="text-muted small mt-1">
                    For a scheduled meeting this may be left blank.
                    Minutes become required when the meeting is marked Held.
                </div>

            </div>


            <div class="card-body">

                <textarea
                    name="minutes"
                    rows="10"
                    class="form-control"
                    placeholder="Enter meeting minutes, discussions, resolutions and conclusions..."
                >{{ old('minutes') }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- STATUS --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Meeting Status
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
                            id="meeting_status"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Scheduled',
                                'Held',
                                'Postponed',
                                'Cancelled',
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        old(
                                            'status',
                                            'Scheduled'
                                        ) === $status
                                    )
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                <div class="alert alert-light border mb-0">

                    <strong>
                        Meeting lifecycle:
                    </strong>

                    Scheduled → Held

                    <span class="mx-1">|</span>

                    Scheduled → Postponed

                    <span class="mx-1">|</span>

                    Scheduled → Cancelled

                    <br>

                    <span class="text-muted small">
                        Minutes are required when status is Held.
                    </span>

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
                    placeholder="Additional notes about the meeting..."
                >{{ old('remarks') }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- ACTIONS --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.governance-meetings.index',
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
                Schedule Meeting
            </button>

        </div>

    </form>

</div>

@endsection