@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Governance / Meetings / Decisions
            </div>

            <h3 class="mb-1">
                Meeting Decisions & Resolutions
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


            <a
                href="{{ route(
                    'admin.projects.governance-meetings.decisions.create',
                    [
                        'project' => $project->id,
                        'meeting' => $meeting->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                + Add Decision
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


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
    {{-- SUMMARY --}}
    {{-- ========================================================= --}}

    @php

        $totalDecisions =
            $decisions->count();

        $draftDecisions =
            $decisions
                ->where('decision_status', 'Draft')
                ->count();

        $approvedDecisions =
            $decisions
                ->where('decision_status', 'Approved')
                ->count();

        $rejectedDecisions =
            $decisions
                ->where('decision_status', 'Rejected')
                ->count();

        $deferredDecisions =
            $decisions
                ->where('decision_status', 'Deferred')
                ->count();

        $supersededDecisions =
            $decisions
                ->where('decision_status', 'Superseded')
                ->count();

        $approvalDecisions =
            $decisions
                ->where('decision_type', 'Approval')
                ->count();

        $resolutionDecisions =
            $decisions
                ->where('decision_type', 'Resolution')
                ->count();

    @endphp


    {{-- ========================================================= --}}
    {{-- SUMMARY CARDS --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        {{-- Total --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Decisions
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $totalDecisions }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Draft --}}

        <div class="col-md-3">

            <div class="card h-100 border-warning">

                <div class="card-body">

                    <div class="text-muted small">
                        Draft
                    </div>

                    <div class="fs-3 fw-semibold text-warning">
                        {{ $draftDecisions }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Approved --}}

        <div class="col-md-3">

            <div class="card h-100 border-success">

                <div class="card-body">

                    <div class="text-muted small">
                        Approved
                    </div>

                    <div class="fs-3 fw-semibold text-success">
                        {{ $approvedDecisions }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Rejected --}}

        <div class="col-md-3">

            <div class="card h-100 border-danger">

                <div class="card-body">

                    <div class="text-muted small">
                        Rejected
                    </div>

                    <div class="fs-3 fw-semibold text-danger">
                        {{ $rejectedDecisions }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SECONDARY SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Deferred
                    </div>

                    <div class="fs-4 fw-semibold">
                        {{ $deferredDecisions }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Superseded
                    </div>

                    <div class="fs-4 fw-semibold">
                        {{ $supersededDecisions }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100 border-primary">

                <div class="card-body">

                    <div class="text-muted small">
                        Approvals
                    </div>

                    <div class="fs-4 fw-semibold text-primary">
                        {{ $approvalDecisions }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100 border-info">

                <div class="card-body">

                    <div class="text-muted small">
                        Resolutions
                    </div>

                    <div class="fs-4 fw-semibold text-info">
                        {{ $resolutionDecisions }}
                    </div>

                </div>

            </div>

        </div>

    </div>


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

            <div class="row">

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
                        Committee
                    </div>

                    <div class="fw-semibold">
                        {{ $meeting->committee_name }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Meeting Status
                    </div>

                    <div class="fw-semibold">
                        {{ $meeting->status }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DECISION REGISTER --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Decision Register
                    </strong>

                    <div class="text-muted small mt-1">
                        Formal decisions, directions and resolutions
                        recorded during this meeting.
                    </div>

                </div>


                <span class="text-muted small">

                    {{ $totalDecisions }}

                    {{ $totalDecisions === 1
                        ? 'decision'
                        : 'decisions'
                    }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($decisions->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead>

                            <tr>

                                <th
                                    class="ps-3"
                                    style="width: 80px;"
                                >
                                    No.
                                </th>

                                <th>
                                    Decision
                                </th>

                                <th>
                                    Source Agenda
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Approved By
                                </th>

                                <th>
                                    Effective Date
                                </th>

                                <th
                                    class="text-end pe-3"
                                >
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($decisions as $decision)

                                @php

                                    $typeClass =
                                        match(
                                            $decision->decision_type
                                        ) {

                                            'Approval'
                                                => 'bg-primary',

                                            'Direction'
                                                => 'bg-warning text-dark',

                                            'Resolution'
                                                => 'bg-info text-dark',

                                            'Recommendation'
                                                => 'bg-secondary',

                                            'Information'
                                                => 'bg-light text-dark border',

                                            default
                                                => 'bg-secondary',

                                        };


                                    $statusClass =
                                        match(
                                            $decision->decision_status
                                        ) {

                                            'Draft'
                                                => 'bg-warning text-dark',

                                            'Approved'
                                                => 'bg-success',

                                            'Rejected'
                                                => 'bg-danger',

                                            'Deferred'
                                                => 'bg-secondary',

                                            'Superseded'
                                                => 'bg-dark',

                                            default
                                                => 'bg-secondary',

                                        };

                                @endphp


                                {{-- ================================================= --}}
                                {{-- MAIN ROW --}}
                                {{-- ================================================= --}}

                                <tr>

                                    {{-- Decision Number --}}

                                    <td class="ps-3">

                                        <span class="fw-semibold">

                                            D-{{ str_pad(
                                                $decision->decision_no,
                                                3,
                                                '0',
                                                STR_PAD_LEFT
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- Decision --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{
                                                $decision->decision_title
                                            }}

                                        </div>


                                        <div class="text-muted small mt-1">

                                            {{
                                                \Illuminate\Support\Str::limit(
                                                    $decision->decision_text,
                                                    140
                                                )
                                            }}

                                        </div>

                                    </td>


                                    {{-- Source Agenda --}}

                                    <td>

                                        @if($decision->agendaItem)

                                            <div class="fw-semibold">

                                                Item
                                                {{ $decision->agendaItem->item_no }}

                                            </div>

                                            <div class="text-muted small">

                                                {{
                                                    \Illuminate\Support\Str::limit(
                                                        $decision->agendaItem->subject,
                                                        60
                                                    )
                                                }}

                                            </div>

                                        @else

                                            <span class="text-muted">
                                                Not linked
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Type --}}

                                    <td>

                                        <span
                                            class="badge {{ $typeClass }}"
                                        >
                                            {{ $decision->decision_type }}
                                        </span>

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $decision->decision_status }}
                                        </span>

                                    </td>


                                    {{-- Approved By --}}

                                    <td>

                                        @if($decision->approver)

                                            <div class="fw-semibold">

                                                {{
                                                    $decision->approver->name
                                                }}

                                            </div>


                                            @if(
                                                $decision->approval_date
                                            )

                                                <div class="text-success small">

                                                    Approved:
                                                    {{
                                                        $decision
                                                            ->approval_date
                                                            ->format('d-m-Y')
                                                    }}

                                                </div>

                                            @endif

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Effective Date --}}

                                    <td>

                                        @if($decision->effective_date)

                                            <span class="fw-semibold">

                                                {{
                                                    $decision
                                                        ->effective_date
                                                        ->format('d-m-Y')
                                                }}

                                            </span>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Actions --}}

                                    <td class="text-end pe-3">

                                        <div
                                            class="d-flex justify-content-end gap-1"
                                        >

                                            {{-- Status Dropdown --}}

                                            <div class="dropdown">

                                                <button
                                                    class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                    type="button"
                                                    data-bs-toggle="dropdown"
                                                >
                                                    Status
                                                </button>


                                                <ul
                                                    class="
                                                        dropdown-menu
                                                        dropdown-menu-end
                                                    "
                                                >

                                                    @foreach([
                                                        'Draft',
                                                        'Approved',
                                                        'Rejected',
                                                        'Deferred',
                                                        'Superseded',
                                                    ] as $status)

                                                        <li>

                                                            <form
                                                                method="POST"
                                                                action="{{ route(
                                                                    'admin.projects.governance-meetings.decisions.status',
                                                                    [
                                                                        'project' =>
                                                                            $project->id,

                                                                        'meeting' =>
                                                                            $meeting->id,

                                                                        'decision' =>
                                                                            $decision->id,
                                                                    ]
                                                                ) }}"
                                                            >

                                                                @csrf

                                                                <input
                                                                    type="hidden"
                                                                    name="decision_status"
                                                                    value="{{ $status }}"
                                                                >

                                                                <button
                                                                    type="submit"
                                                                    class="dropdown-item"
                                                                >
                                                                    {{ $status }}
                                                                </button>

                                                            </form>

                                                        </li>

                                                    @endforeach

                                                </ul>

                                            </div>


                                            {{-- Edit --}}

                                            <a
                                                href="{{ route(
                                                    'admin.projects.governance-meetings.decisions.edit',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'meeting' =>
                                                            $meeting->id,

                                                        'decision' =>
                                                            $decision->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Edit
                                            </a>


                                            {{-- Delete --}}

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.governance-meetings.decisions.destroy',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'meeting' =>
                                                            $meeting->id,

                                                        'decision' =>
                                                            $decision->id,
                                                    ]
                                                ) }}"
                                                onsubmit="return confirm(
                                                    'Are you sure you want to delete this decision?'
                                                );"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                >
                                                    Delete
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>


                                {{-- ================================================= --}}
                                {{-- DECISION DETAILS --}}
                                {{-- ================================================= --}}

                                <tr class="table-light">

                                    <td></td>

                                    <td
                                        colspan="7"
                                        class="py-3"
                                    >

                                        <div class="row">

                                            <div class="col-md-8">

                                                <div
                                                    class="
                                                        text-muted
                                                        small
                                                        fw-semibold
                                                    "
                                                >
                                                    Decision Text
                                                </div>

                                                <div class="small mt-1">

                                                    {!! nl2br(
                                                        e(
                                                            $decision->decision_text
                                                        )
                                                    ) !!}

                                                </div>

                                            </div>


                                            <div class="col-md-4">

                                                @if($decision->remarks)

                                                    <div
                                                        class="
                                                            text-muted
                                                            small
                                                            fw-semibold
                                                        "
                                                    >
                                                        Remarks
                                                    </div>

                                                    <div class="small mt-1">

                                                        {!! nl2br(
                                                            e(
                                                                $decision->remarks
                                                            )
                                                        ) !!}

                                                    </div>

                                                @endif

                                            </div>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


            @else

                {{-- ================================================= --}}
                {{-- EMPTY STATE --}}
                {{-- ================================================= --}}

                <div class="text-center py-5">

                    <h5>
                        No Decisions or Resolutions
                    </h5>

                    <div class="text-muted mb-3">

                        No formal decisions or resolutions have
                        been recorded for this meeting yet.

                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.governance-meetings.decisions.create',
                            [
                                'project' => $project->id,
                                'meeting' => $meeting->id,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Add First Decision
                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- STATUS LEGEND --}}
    {{-- ========================================================= --}}

    <div class="card mb-5">

        <div class="card-header">

            <strong>
                Decision Status
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 col-lg-2 mb-2">

                    <span class="badge bg-warning text-dark">
                        Draft
                    </span>

                    <span class="text-muted small ms-2">
                        Under preparation
                    </span>

                </div>


                <div class="col-md-4 col-lg-2 mb-2">

                    <span class="badge bg-success">
                        Approved
                    </span>

                    <span class="text-muted small ms-2">
                        Formally approved
                    </span>

                </div>


                <div class="col-md-4 col-lg-2 mb-2">

                    <span class="badge bg-danger">
                        Rejected
                    </span>

                    <span class="text-muted small ms-2">
                        Not approved
                    </span>

                </div>


                <div class="col-md-4 col-lg-2 mb-2">

                    <span class="badge bg-secondary">
                        Deferred
                    </span>

                    <span class="text-muted small ms-2">
                        Decision postponed
                    </span>

                </div>


                <div class="col-md-4 col-lg-2 mb-2">

                    <span class="badge bg-dark">
                        Superseded
                    </span>

                    <span class="text-muted small ms-2">
                        Replaced by later decision
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection