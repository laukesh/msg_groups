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
                Meeting Agenda Items
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
                    'admin.projects.governance-meetings.agenda-items.create',
                    [
                        'project' => $project->id,
                        'meeting' => $meeting->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                + Add Agenda Item
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
    {{-- ERRORS --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

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

        $totalItems =
            $agendaItems->count();

        $openItems =
            $agendaItems
                ->where('status', 'Open')
                ->count();

        $discussedItems =
            $agendaItems
                ->where('status', 'Discussed')
                ->count();

        $deferredItems =
            $agendaItems
                ->where('status', 'Deferred')
                ->count();

        $closedItems =
            $agendaItems
                ->where('status', 'Closed')
                ->count();

        $decisionRequired =
            $agendaItems
                ->where('decision_required', true)
                ->count();

        $criticalItems =
            $agendaItems
                ->where('priority', 'Critical')
                ->count();

        $highItems =
            $agendaItems
                ->where('priority', 'High')
                ->count();

    @endphp


    <div class="row g-3 mb-4">

        {{-- Total --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Agenda Items
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $totalItems }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Open --}}

        <div class="col-md-3">

            <div class="card h-100 border-primary">

                <div class="card-body">

                    <div class="text-muted small">
                        Open
                    </div>

                    <div class="fs-3 fw-semibold text-primary">
                        {{ $openItems }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Discussed --}}

        <div class="col-md-3">

            <div class="card h-100 border-info">

                <div class="card-body">

                    <div class="text-muted small">
                        Discussed
                    </div>

                    <div class="fs-3 fw-semibold text-info">
                        {{ $discussedItems }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Closed --}}

        <div class="col-md-3">

            <div class="card h-100 border-success">

                <div class="card-body">

                    <div class="text-muted small">
                        Closed
                    </div>

                    <div class="fs-3 fw-semibold text-success">
                        {{ $closedItems }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ADDITIONAL SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        {{-- Deferred --}}

        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Deferred
                    </div>

                    <div class="fs-4 fw-semibold text-warning">
                        {{ $deferredItems }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Decision Required --}}

        <div class="col-md-4">

            <div class="card h-100 border-warning">

                <div class="card-body">

                    <div class="text-muted small">
                        Decision Required
                    </div>

                    <div class="fs-4 fw-semibold text-warning">
                        {{ $decisionRequired }}
                    </div>

                </div>

            </div>

        </div>


        {{-- High / Critical --}}

        <div class="col-md-4">

            <div class="card h-100 border-danger">

                <div class="card-body">

                    <div class="text-muted small">
                        High / Critical Priority
                    </div>

                    <div class="fs-4 fw-semibold text-danger">
                        {{ $highItems + $criticalItems }}
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
                        Date
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
    {{-- AGENDA REGISTER --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Agenda Register
                    </strong>

                    <div class="text-muted small mt-1">
                        Individual matters for discussion and decision.
                    </div>

                </div>


                <span class="text-muted small">

                    {{ $totalItems }}

                    {{ $totalItems === 1
                        ? 'item'
                        : 'items'
                    }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($agendaItems->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead>

                            <tr>

                                <th
                                    class="ps-3"
                                    style="width: 70px;"
                                >
                                    No.
                                </th>

                                <th>
                                    Subject
                                </th>

                                <th>
                                    Presenter
                                </th>

                                <th>
                                    Priority
                                </th>

                                <th>
                                    Decision
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Outcome
                                </th>

                                <th
                                    class="text-end pe-3"
                                >
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($agendaItems as $item)

                                @php

                                    $priorityClass =
                                        match(
                                            $item->priority
                                        ) {

                                            'Low'
                                                => 'bg-light text-dark border',

                                            'Medium'
                                                => 'bg-info text-dark',

                                            'High'
                                                => 'bg-warning text-dark',

                                            'Critical'
                                                => 'bg-danger',

                                            default
                                                => 'bg-secondary',

                                        };


                                    $statusClass =
                                        match(
                                            $item->status
                                        ) {

                                            'Open'
                                                => 'bg-primary',

                                            'Discussed'
                                                => 'bg-info text-dark',

                                            'Deferred'
                                                => 'bg-warning text-dark',

                                            'Closed'
                                                => 'bg-success',

                                            default
                                                => 'bg-secondary',

                                        };

                                @endphp


                                <tr>

                                    {{-- Item Number --}}

                                    <td class="ps-3">

                                        <span
                                            class="fw-semibold"
                                        >
                                            {{ $item->item_no }}
                                        </span>

                                    </td>


                                    {{-- Subject --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $item->subject }}

                                        </div>


                                        @if($item->description)

                                            <div class="text-muted small mt-1">

                                                {{
                                                    \Illuminate\Support\Str::limit(
                                                        $item->description,
                                                        100
                                                    )
                                                }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Presenter --}}

                                    <td>

                                        @if($item->presenter)

                                            <div class="fw-semibold">

                                                {{ $item->presenter->name }}

                                            </div>

                                        @elseif($item->presenter_name)

                                            <div class="fw-semibold">

                                                {{ $item->presenter_name }}

                                            </div>

                                            <div class="text-muted small">
                                                External
                                            </div>

                                        @else

                                            <span class="text-muted">
                                                Not assigned
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Priority --}}

                                    <td>

                                        <span
                                            class="badge {{ $priorityClass }}"
                                        >
                                            {{ $item->priority }}
                                        </span>

                                    </td>


                                    {{-- Decision Required --}}

                                    <td>

                                        @if($item->decision_required)

                                            <span
                                                class="badge bg-danger"
                                            >
                                                Yes
                                            </span>

                                        @else

                                            <span
                                                class="badge bg-light text-dark border"
                                            >
                                                No
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $item->status }}
                                        </span>

                                    </td>


                                    {{-- Outcome --}}

                                    <td>

                                        @if($item->outcome)

                                            <span
                                                title="{{ $item->outcome }}"
                                            >

                                                {{
                                                    \Illuminate\Support\Str::limit(
                                                        $item->outcome,
                                                        70
                                                    )
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


                                                <ul class="dropdown-menu dropdown-menu-end">

                                                    @foreach([
                                                        'Open',
                                                        'Discussed',
                                                        'Deferred',
                                                        'Closed',
                                                    ] as $status)

                                                        <li>

                                                            <form
                                                                method="POST"
                                                                action="{{ route(
                                                                    'admin.projects.governance-meetings.agenda-items.status',
                                                                    [
                                                                        'project' =>
                                                                            $project->id,

                                                                        'meeting' =>
                                                                            $meeting->id,

                                                                        'agendaItem' =>
                                                                            $item->id,
                                                                    ]
                                                                ) }}"
                                                            >

                                                                @csrf

                                                                <input
                                                                    type="hidden"
                                                                    name="status"
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
                                                    'admin.projects.governance-meetings.agenda-items.edit',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'meeting' =>
                                                            $meeting->id,

                                                        'agendaItem' =>
                                                            $item->id,
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
                                                    'admin.projects.governance-meetings.agenda-items.destroy',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'meeting' =>
                                                            $meeting->id,

                                                        'agendaItem' =>
                                                            $item->id,
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
                                                    class="btn btn-sm btn-outline-danger"
                                                >
                                                    Delete
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>


                                {{-- ================================================= --}}
                                {{-- EXPANDED DETAILS --}}
                                {{-- ================================================= --}}

                                @if(
                                    $item->discussion ||
                                    $item->outcome ||
                                    $item->remarks
                                )

                                    <tr class="table-light">

                                        <td></td>

                                        <td
                                            colspan="7"
                                            class="py-3"
                                        >

                                            <div class="row">

                                                @if($item->discussion)

                                                    <div class="col-md-4">

                                                        <div class="text-muted small fw-semibold">
                                                            Discussion
                                                        </div>

                                                        <div class="small mt-1">

                                                            {!! nl2br(
                                                                e(
                                                                    $item->discussion
                                                                )
                                                            ) !!}

                                                        </div>

                                                    </div>

                                                @endif


                                                @if($item->outcome)

                                                    <div class="col-md-4">

                                                        <div class="text-muted small fw-semibold">
                                                            Outcome
                                                        </div>

                                                        <div class="small mt-1">

                                                            {!! nl2br(
                                                                e(
                                                                    $item->outcome
                                                                )
                                                            ) !!}

                                                        </div>

                                                    </div>

                                                @endif


                                                @if($item->remarks)

                                                    <div class="col-md-4">

                                                        <div class="text-muted small fw-semibold">
                                                            Remarks
                                                        </div>

                                                        <div class="small mt-1">

                                                            {!! nl2br(
                                                                e(
                                                                    $item->remarks
                                                                )
                                                            ) !!}

                                                        </div>

                                                    </div>

                                                @endif

                                            </div>

                                        </td>

                                    </tr>

                                @endif

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
                        No Agenda Items
                    </h5>

                    <div class="text-muted mb-3">

                        No individual agenda items have been
                        added to this governance meeting yet.

                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.governance-meetings.agenda-items.create',
                            [
                                'project' => $project->id,
                                'meeting' => $meeting->id,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Add First Agenda Item
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
                Agenda Item Status
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-2">

                    <span class="badge bg-primary">
                        Open
                    </span>

                    <span class="text-muted small ms-2">
                        Not yet discussed
                    </span>

                </div>


                <div class="col-md-3 mb-2">

                    <span class="badge bg-info text-dark">
                        Discussed
                    </span>

                    <span class="text-muted small ms-2">
                        Discussion completed
                    </span>

                </div>


                <div class="col-md-3 mb-2">

                    <span class="badge bg-warning text-dark">
                        Deferred
                    </span>

                    <span class="text-muted small ms-2">
                        Carried forward
                    </span>

                </div>


                <div class="col-md-3 mb-2">

                    <span class="badge bg-success">
                        Closed
                    </span>

                    <span class="text-muted small ms-2">
                        Matter completed
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection