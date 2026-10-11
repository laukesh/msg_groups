@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4 class="mb-1">
                Schedule Activity
            </h4>

            <div class="text-muted">
                {{ $activity->activity_code }}
            </div>

        </div>


        <div class="d-flex gap-2">

            @if($activity->canEdit())

                <a
                    href="{{ route(
                        'admin.projects.construction.schedule.edit',
                        [
                            'project' => $project,
                            'activity' => $activity,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    Edit
                </a>

            @endif


            <a
                href="{{ route(
                    'admin.projects.construction.schedule.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>

    </div>


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


    @php

        $statusClass =
            match($activity->status) {

                'Completed' =>
                    'bg-success',

                'In Progress' =>
                    'bg-primary',

                'Delayed' =>
                    'bg-danger',

                'On Hold' =>
                    'bg-warning text-dark',

                'Cancelled' =>
                    'bg-dark',

                default =>
                    'bg-secondary',

            };


        $priorityClass =
            match($activity->priority) {

                'Critical' =>
                    'bg-danger',

                'High' =>
                    'bg-warning text-dark',

                'Low' =>
                    'bg-info text-dark',

                default =>
                    'bg-secondary',

            };

    @endphp


    <div class="row g-4">

        <div class="col-lg-8">

            {{-- Basic Information --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Activity Information
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Activity Code
                            </div>

                            <div class="fw-semibold">
                                {{ $activity->activity_code }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Activity Name
                            </div>

                            <div class="fw-semibold">
                                {{ $activity->activity_name }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                WBS Code
                            </div>

                            <div class="fw-semibold">
                                {{ $activity->wbs_code ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Phase
                            </div>

                            <div class="fw-semibold">
                                {{ $activity->phase ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Work Order
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $activity
                                        ->workOrder
                                        ?->work_order_number
                                    ?? '—'
                                }}

                            </div>

                        </div>


                        <div class="col-md-12">

                            <div class="text-muted small">
                                Description
                            </div>

                            <div>

                                {!! nl2br(
                                    e(
                                        $activity->description
                                        ?? '—'
                                    )
                                ) !!}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Schedule --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Schedule
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-3">

                            <div class="text-muted small">
                                Planned Start
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $activity
                                        ->planned_start_date
                                        ?->format('d-m-Y')
                                    ?? '—'
                                }}

                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="text-muted small">
                                Planned Finish
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $activity
                                        ->planned_finish_date
                                        ?->format('d-m-Y')
                                    ?? '—'
                                }}

                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="text-muted small">
                                Actual Start
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $activity
                                        ->actual_start_date
                                        ?->format('d-m-Y')
                                    ?? '—'
                                }}

                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="text-muted small">
                                Actual Finish
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $activity
                                        ->actual_finish_date
                                        ?->format('d-m-Y')
                                    ?? '—'
                                }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Planned Duration
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $activity->duration_days
                                    ?? '—'
                                }}

                                @if($activity->duration_days)
                                    days
                                @endif

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Progress
                            </div>

                            <div class="fw-semibold">

                                {{
                                    number_format(
                                        (float)
                                        $activity->progress_percentage,
                                        2
                                    )
                                }}%

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Delay
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $activity->delay_days ?? 0
                                }}
                                days

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Dependencies --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Dependencies
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Predecessor
                            </div>

                            @if($activity->predecessor)

                                <a
                                    href="{{ route(
                                        'admin.projects.construction.schedule.show',
                                        [
                                            'project' => $project,
                                            'activity' =>
                                                $activity->predecessor,
                                        ]
                                    ) }}"
                                >

                                    {{
                                        $activity
                                            ->predecessor
                                            ->activity_code
                                    }}

                                </a>

                                <div class="small text-muted">

                                    {{
                                        $activity
                                            ->predecessor
                                            ->activity_name
                                    }}

                                </div>

                            @else

                                —

                            @endif

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Successor Activities
                            </div>

                            @if($activity->successors->isNotEmpty())

                                @foreach(
                                    $activity->successors
                                    as $successor
                                )

                                    <div class="mb-2">

                                        <a
                                            href="{{ route(
                                                'admin.projects.construction.schedule.show',
                                                [
                                                    'project' => $project,
                                                    'activity' =>
                                                        $successor,
                                                ]
                                            ) }}"
                                        >
                                            {{
                                                $successor
                                                    ->activity_code
                                            }}
                                        </a>

                                        <div class="small text-muted">
                                            {{
                                                $successor
                                                    ->activity_name
                                            }}
                                        </div>

                                    </div>

                                @endforeach

                            @else

                                —

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- Remarks --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Remarks
                    </strong>

                </div>


                <div class="card-body">

                    {!! nl2br(
                        e(
                            $activity->remarks
                            ?? 'No remarks.'
                        )
                    ) !!}

                </div>

            </div>

        </div>


        <div class="col-lg-4">

            {{-- Status --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Status
                    </strong>

                </div>


                <div class="card-body">

                    <span
                        class="badge {{ $statusClass }}"
                    >
                        {{ $activity->status }}
                    </span>

                </div>

            </div>


            {{-- Priority --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Priority
                    </strong>

                </div>


                <div class="card-body">

                    <span
                        class="badge {{ $priorityClass }}"
                    >
                        {{ $activity->priority }}
                    </span>

                </div>

            </div>


            {{-- Responsible --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Responsibility
                    </strong>

                </div>


                <div class="card-body">

                    <div class="text-muted small">
                        Responsible Person
                    </div>

                    <div class="fw-semibold mb-3">

                        {{
                            $activity
                                ->responsibleUser
                                ?->name
                            ?? '—'
                        }}

                    </div>


                    <div class="text-muted small">
                        Created By
                    </div>

                    <div class="fw-semibold">

                        {{
                            $activity
                                ->creator
                                ?->name
                            ?? '—'
                        }}

                    </div>

                </div>

            </div>


            {{-- Delete --}}

            @if(
                !$activity
                    ->successors()
                    ->exists()
            )

                <div class="card border-danger">

                    <div class="card-header text-danger">

                        <strong>
                            Danger Zone
                        </strong>

                    </div>


                    <div class="card-body">

                        <p class="small text-muted">
                            Deleting this activity cannot be
                            undone.
                        </p>


                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.construction.schedule.destroy',
                                [
                                    'project' => $project,
                                    'activity' => $activity,
                                ]
                            ) }}"
                            onsubmit="
                                return confirm(
                                    'Are you sure you want to delete this activity?'
                                );
                            "
                        >

                            @csrf

                            @method('DELETE')


                            <button
                                type="submit"
                                class="btn btn-outline-danger w-100"
                            >
                                Delete Activity
                            </button>

                        </form>

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection