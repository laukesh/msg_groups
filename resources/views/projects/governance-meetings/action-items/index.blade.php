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
                Meeting Action Items
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
                    'admin.projects.governance-meetings.action-items.create',
                    [
                        'project' => $project->id,
                        'meeting' => $meeting->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                + Add Action Item
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
    {{-- CALCULATE SUMMARY --}}
    {{-- ========================================================= --}}

    @php

        $today = now()->startOfDay();

        $totalActions =
            $actionItems->count();

        $openActions =
            $actionItems
                ->where('status', 'Open')
                ->count();

        $inProgressActions =
            $actionItems
                ->where('status', 'In Progress')
                ->count();

        $completedActions =
            $actionItems
                ->where('status', 'Completed')
                ->count();

        $cancelledActions =
            $actionItems
                ->where('status', 'Cancelled')
                ->count();

        $overdueActions = $actionItems
            ->filter(function ($item) use ($today) {

                if ($item->status === 'Completed') {
                    return false;
                }

                if ($item->status === 'Cancelled') {
                    return false;
                }

                if (!$item->due_date) {
                    return false;
                }

                return $item->due_date->lt($today);

            })
            ->count();


        $criticalActions =
            $actionItems
                ->where('priority', 'Critical')
                ->count();

        $highActions =
            $actionItems
                ->where('priority', 'High')
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
                        Total Actions
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $totalActions }}
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
                        {{ $openActions }}
                    </div>

                </div>

            </div>

        </div>


        {{-- In Progress --}}

        <div class="col-md-3">

            <div class="card h-100 border-info">

                <div class="card-body">

                    <div class="text-muted small">
                        In Progress
                    </div>

                    <div class="fs-3 fw-semibold text-info">
                        {{ $inProgressActions }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Completed --}}

        <div class="col-md-3">

            <div class="card h-100 border-success">

                <div class="card-body">

                    <div class="text-muted small">
                        Completed
                    </div>

                    <div class="fs-3 fw-semibold text-success">
                        {{ $completedActions }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SECONDARY SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        {{-- Overdue --}}

        <div class="col-md-3">

            <div class="card h-100 border-danger">

                <div class="card-body">

                    <div class="text-muted small">
                        Overdue
                    </div>

                    <div class="fs-4 fw-semibold text-danger">
                        {{ $overdueActions }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Critical --}}

        <div class="col-md-3">

            <div class="card h-100 border-danger">

                <div class="card-body">

                    <div class="text-muted small">
                        Critical Priority
                    </div>

                    <div class="fs-4 fw-semibold text-danger">
                        {{ $criticalActions }}
                    </div>

                </div>

            </div>

        </div>


        {{-- High --}}

        <div class="col-md-3">

            <div class="card h-100 border-warning">

                <div class="card-body">

                    <div class="text-muted small">
                        High Priority
                    </div>

                    <div class="fs-4 fw-semibold text-warning">
                        {{ $highActions }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Cancelled --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Cancelled
                    </div>

                    <div class="fs-4 fw-semibold">
                        {{ $cancelledActions }}
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
    {{-- ACTION REGISTER --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Action Register
                    </strong>

                    <div class="text-muted small mt-1">
                        Track responsibilities, deadlines and completion.
                    </div>

                </div>


                <span class="text-muted small">

                    {{ $totalActions }}

                    {{ $totalActions === 1
                        ? 'action'
                        : 'actions'
                    }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($actionItems->count())

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
                                    Action
                                </th>

                                <th>
                                    Source Agenda
                                </th>

                                <th>
                                    Responsible
                                </th>

                                <th>
                                    Priority
                                </th>

                                <th>
                                    Due Date
                                </th>

                                <th>
                                    Status
                                </th>

                                <th
                                    class="text-end pe-3"
                                >
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($actionItems as $item)

                                @php

                                    $isOverdue =
                                        $item->status !== 'Completed' &&
                                        $item->status !== 'Cancelled' &&
                                        $item->due_date &&
                                        $item->due_date->lt($today);


                                    $displayStatus =
                                        $isOverdue
                                            ? 'Overdue'
                                            : $item->status;


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
                                            $displayStatus
                                        ) {

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


                                <tr>

                                    {{-- Action No. --}}

                                    <td class="ps-3">

                                        <span class="fw-semibold">
                                            {{ $item->action_no }}
                                        </span>

                                    </td>


                                    {{-- Action Description --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{
                                                \Illuminate\Support\Str::limit(
                                                    $item->action_description,
                                                    120
                                                )
                                            }}

                                        </div>


                                        @if($item->remarks)

                                            <div class="text-muted small mt-1">

                                                {{
                                                    \Illuminate\Support\Str::limit(
                                                        $item->remarks,
                                                        80
                                                    )
                                                }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Source Agenda --}}

                                    <td>

                                        @if($item->agendaItem)

                                            <div class="fw-semibold">

                                                Item
                                                {{ $item->agendaItem->item_no }}

                                            </div>

                                            <div class="text-muted small">

                                                {{
                                                    \Illuminate\Support\Str::limit(
                                                        $item->agendaItem->subject,
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


                                    {{-- Responsible --}}

                                    <td>

                                        @if($item->responsibleUser)

                                            <div class="fw-semibold">

                                                {{
                                                    $item->responsibleUser->name
                                                }}

                                            </div>

                                            @if(
                                                $item->responsible_organization
                                            )

                                                <div class="text-muted small">

                                                    {{
                                                        $item->responsible_organization
                                                    }}

                                                </div>

                                            @endif

                                        @elseif($item->responsible_name)

                                            <div class="fw-semibold">

                                                {{
                                                    $item->responsible_name
                                                }}

                                            </div>


                                            @if(
                                                $item->responsible_organization
                                            )

                                                <div class="text-muted small">

                                                    {{
                                                        $item->responsible_organization
                                                    }}

                                                </div>

                                            @endif

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


                                    {{-- Due Date --}}

                                    <td>

                                        @if($item->due_date)

                                            <div
                                                class="
                                                    fw-semibold
                                                    {{ $isOverdue
                                                        ? 'text-danger'
                                                        : ''
                                                    }}
                                                "
                                            >

                                                {{
                                                    $item->due_date
                                                        ->format('d-m-Y')
                                                }}

                                            </div>


                                            @if($isOverdue)

                                                <div class="text-danger small">
                                                    Overdue
                                                </div>

                                            @elseif(
                                                $item->status === 'Completed' &&
                                                $item->completion_date
                                            )

                                                <div class="text-success small">

                                                    Completed:
                                                    {{
                                                        $item->completion_date
                                                            ->format('d-m-Y')
                                                    }}

                                                </div>

                                            @endif

                                        @else

                                            <span class="text-muted">
                                                No due date
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $displayStatus }}
                                        </span>

                                    </td>


                                    {{-- Actions --}}

                                    <td class="text-end pe-3">

                                        <div
                                            class="d-flex justify-content-end gap-1"
                                        >

                                            {{-- Status --}}

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
                                                        'Open',
                                                        'In Progress',
                                                        'Completed',
                                                        'Overdue',
                                                        'Cancelled',
                                                    ] as $status)

                                                        <li>

                                                            <form
                                                                method="POST"
                                                                action="{{ route(
                                                                    'admin.projects.governance-meetings.action-items.status',
                                                                    [
                                                                        'project' =>
                                                                            $project->id,

                                                                        'meeting' =>
                                                                            $meeting->id,

                                                                        'actionItem' =>
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
                                                    'admin.projects.governance-meetings.action-items.edit',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'meeting' =>
                                                            $meeting->id,

                                                        'actionItem' =>
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
                                                    'admin.projects.governance-meetings.action-items.destroy',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'meeting' =>
                                                            $meeting->id,

                                                        'actionItem' =>
                                                            $item->id,
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
                                                    class="btn btn-sm btn-outline-danger"
                                                >
                                                    Delete
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>


                                {{-- ================================================= --}}
                                {{-- COMPLETION DETAILS --}}
                                {{-- ================================================= --}}

                                @if(
                                    $item->completion_remarks ||
                                    $item->remarks
                                )

                                    <tr class="table-light">

                                        <td></td>

                                        <td
                                            colspan="7"
                                            class="py-3"
                                        >

                                            <div class="row">

                                                @if(
                                                    $item->completion_remarks
                                                )

                                                    <div class="col-md-6">

                                                        <div
                                                            class="
                                                                text-muted
                                                                small
                                                                fw-semibold
                                                            "
                                                        >
                                                            Completion Remarks
                                                        </div>

                                                        <div class="small mt-1">

                                                            {!! nl2br(
                                                                e(
                                                                    $item->completion_remarks
                                                                )
                                                            ) !!}

                                                        </div>

                                                    </div>

                                                @endif


                                                @if($item->remarks)

                                                    <div class="col-md-6">

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
                        No Action Items
                    </h5>

                    <div class="text-muted mb-3">

                        No actions have been assigned from
                        this governance meeting yet.

                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.governance-meetings.action-items.create',
                            [
                                'project' => $project->id,
                                'meeting' => $meeting->id,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Add First Action Item
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
                Action Status
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 col-lg-2 mb-2">

                    <span class="badge bg-primary">
                        Open
                    </span>

                    <span class="text-muted small ms-2">
                        Not started
                    </span>

                </div>


                <div class="col-md-4 col-lg-2 mb-2">

                    <span class="badge bg-info text-dark">
                        In Progress
                    </span>

                    <span class="text-muted small ms-2">
                        Work underway
                    </span>

                </div>


                <div class="col-md-4 col-lg-2 mb-2">

                    <span class="badge bg-success">
                        Completed
                    </span>

                    <span class="text-muted small ms-2">
                        Finished
                    </span>

                </div>


                <div class="col-md-4 col-lg-2 mb-2">

                    <span class="badge bg-danger">
                        Overdue
                    </span>

                    <span class="text-muted small ms-2">
                        Past due
                    </span>

                </div>


                <div class="col-md-4 col-lg-2 mb-2">

                    <span class="badge bg-secondary">
                        Cancelled
                    </span>

                    <span class="text-muted small ms-2">
                        No longer required
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection