@extends('layouts.app')

@section('title', 'Fit-Out Inspections')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Fit-Out Inspections
            </h4>

            <p class="text-muted mb-0">
                Manage fit-out inspections and inspection schedules.
            </p>
        </div>

        <a
            href="{{ route('admin.fitout.inspections.create') }}"
            class="btn btn-primary"
        >
            <i class="bi bi-plus-lg me-1"></i>
            Schedule Inspection
        </a>

    </div>


    {{-- Success --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- Error --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- Inspection Table --}}
    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Inspection List
                </h5>

                <span class="text-muted">
                    {{ $inspections->total() }} inspections
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($inspections->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Inspection No.
                                </th>

                                <th>
                                    Fit-Out Request
                                </th>

                                <th>
                                    Inspection Type
                                </th>

                                <th>
                                    Stage
                                </th>

                                <th>
                                    Scheduled
                                </th>

                                <th>
                                    Inspector
                                </th>

                                <th>
                                    Result
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($inspections as $inspection)

                                <tr>

                                    {{-- ID --}}
                                    <td>

                                        {{ $inspection->id }}

                                    </td>


                                    {{-- Inspection Number --}}
                                    <td>

                                        <a
                                            href="{{ route('admin.fitout.inspections.show', $inspection->id) }}"
                                            class="fw-semibold text-decoration-none"
                                        >

                                            {{ $inspection->inspection_number }}

                                        </a>

                                    </td>


                                    {{-- Request --}}
                                    <td>

                                        @if($inspection->fitoutRequest)

                                            <a
                                                href="{{ route('admin.fitout.requests.show', $inspection->fitoutRequest->id) }}"
                                                class="text-decoration-none"
                                            >

                                                {{ $inspection->fitoutRequest->request_no }}

                                            </a>

                                            @if($inspection->fitoutRequest->tenant)

                                                <div class="small text-muted">

                                                    {{ $inspection->fitoutRequest->tenant->name
                                                        ?? $inspection->fitoutRequest->tenant->tenant_name
                                                        ?? '-' }}

                                                </div>

                                            @endif

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Inspection Type --}}
                                    <td>

                                        <span class="badge bg-light text-dark border">

                                            {{ $inspection->inspection_type }}

                                        </span>

                                    </td>


                                    {{-- Stage --}}
                                    <td>

                                        @if($inspection->fitoutStage)

                                            <div class="fw-semibold">

                                                {{ $inspection->fitoutStage->stage_name }}

                                            </div>

                                            <div class="small text-muted">

                                                Stage
                                                {{ $inspection->fitoutStage->stage_sequence }}

                                            </div>

                                        @else

                                            <span class="text-muted">
                                                Not assigned
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Scheduled --}}
                                    <td>

                                        <div class="fw-semibold">

                                            {{ $inspection->scheduled_date
                                                ? $inspection->scheduled_date->format('d M Y')
                                                : '-' }}

                                        </div>

                                        @if($inspection->scheduled_time)

                                            <div class="small text-muted">

                                                {{ \Carbon\Carbon::parse($inspection->scheduled_time)->format('h:i A') }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Inspector --}}
                                    <td>

                                        @if($inspection->inspector)

                                            {{ $inspection->inspector->name }}

                                        @else

                                            <span class="text-muted">
                                                Not assigned
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Result --}}
                                    <td>

                                        @php

                                            $resultClass = match(
                                                $inspection->result
                                            ) {

                                                'Passed'
                                                    => 'bg-success',

                                                'Failed'
                                                    => 'bg-danger',

                                                'Conditional Pass'
                                                    => 'bg-warning text-dark',

                                                default
                                                    => 'bg-secondary',

                                            };

                                        @endphp


                                        <span class="badge {{ $resultClass }}">

                                            {{ $inspection->result ?? 'Pending' }}

                                        </span>

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @php

                                            $statusClass = match(
                                                $inspection->status
                                            ) {

                                                'Scheduled'
                                                    => 'bg-primary',

                                                'In Progress'
                                                    => 'bg-warning text-dark',

                                                'Completed'
                                                    => 'bg-success',

                                                'Cancelled'
                                                    => 'bg-danger',

                                                'Rescheduled'
                                                    => 'bg-info text-dark',

                                                default
                                                    => 'bg-secondary',

                                            };

                                        @endphp


                                        <span class="badge {{ $statusClass }}">

                                            {{ $inspection->status }}

                                        </span>

                                    </td>
                                    <style type="text/css">
                                    .fitout-action-inspection-dropdown {
                                        position: static !important;
                                    }

                                </style>

                                    {{-- Actions --}}
                                    <td class="text-end">

                                        <div class="dropdown fitout-action-inspection-dropdown">

                                            <button
                                                class="btn btn-sm btn-light border dropdown-toggle"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false"
                                            >

                                                Action

                                            </button>


                                            <ul class="dropdown-menu dropdown-menu-end">


                                                {{-- View --}}
                                                <li>

                                                    <a
                                                        class="dropdown-item"
                                                        href="{{ route('admin.fitout.inspections.show', $inspection->id) }}"
                                                    >

                                                        <i class="bi bi-eye me-2"></i>

                                                        View

                                                    </a>

                                                </li>


                                                {{-- Edit --}}
                                                @if(
                                                    !in_array(
                                                        $inspection->status,
                                                        ['Completed', 'Cancelled']
                                                    )
                                                )

                                                    <li>

                                                        <a
                                                            class="dropdown-item"
                                                            href="{{ route('admin.fitout.inspections.edit', $inspection->id) }}"
                                                        >

                                                            <i class="bi bi-pencil me-2"></i>

                                                            Edit

                                                        </a>

                                                    </li>

                                                @endif


                                                {{-- Divider --}}
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>


                                                {{-- Start --}}
                                                @if(
                                                    $inspection->status === 'Scheduled'
                                                )

                                                    <li>

                                                        <form
                                                            action="{{ route('admin.fitout.inspections.start', $inspection->id) }}"
                                                            method="POST"
                                                        >

                                                            @csrf

                                                            <button
                                                                type="submit"
                                                                class="dropdown-item"
                                                            >

                                                                <i class="bi bi-play-circle me-2"></i>

                                                                Start Inspection

                                                            </button>

                                                        </form>

                                                    </li>

                                                @endif


                                                {{-- Complete --}}
                                                @if(
                                                    $inspection->status === 'In Progress'
                                                )

                                                    <li>

                                                        <a
                                                            class="dropdown-item"
                                                            href="{{ route('admin.fitout.inspections.show', $inspection->id) }}#completeInspection"
                                                        >

                                                            <i class="bi bi-check-circle me-2"></i>

                                                            Complete Inspection

                                                        </a>

                                                    </li>

                                                @endif


                                                {{-- Reschedule --}}
                                                @if(
                                                    in_array(
                                                        $inspection->status,
                                                        ['Scheduled', 'Rescheduled']
                                                    )
                                                )

                                                    <li>

                                                        <a
                                                            class="dropdown-item"
                                                            href="{{ route('admin.fitout.inspections.show', $inspection->id) }}#rescheduleInspection"
                                                        >

                                                            <i class="bi bi-calendar-event me-2"></i>

                                                            Reschedule

                                                        </a>

                                                    </li>

                                                @endif


                                                {{-- Re-inspection --}}
                                                @if(
                                                    $inspection->status === 'Completed' &&
                                                    $inspection->reinspection_required
                                                )

                                                    <li>

                                                        <a
                                                            class="dropdown-item"
                                                            href="{{ route('admin.fitout.inspections.reinspection.create', $inspection->id) }}"
                                                        >

                                                            <i class="bi bi-arrow-repeat me-2"></i>

                                                            Create Re-Inspection

                                                        </a>

                                                    </li>

                                                @endif


                                                {{-- Cancel --}}
                                                @if(
                                                    !in_array(
                                                        $inspection->status,
                                                        ['Completed', 'Cancelled']
                                                    )
                                                )

                                                    <li>

                                                        <form
                                                            action="{{ route('admin.fitout.inspections.cancel', $inspection->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Are you sure you want to cancel this inspection?');"
                                                        >

                                                            @csrf

                                                            <button
                                                                type="submit"
                                                                class="dropdown-item text-danger"
                                                            >

                                                                <i class="bi bi-x-circle me-2"></i>

                                                                Cancel

                                                            </button>

                                                        </form>

                                                    </li>

                                                @endif

                                            </ul>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if($inspections->hasPages())

                    <div class="p-3 border-top">

                        {{ $inspections->links() }}

                    </div>

                @endif


            @else

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i
                            class="bi bi-clipboard-x"
                            style="font-size: 3rem;"
                        ></i>

                    </div>

                    <h5>
                        No inspections found
                    </h5>

                    <p class="text-muted">
                        No fit-out inspections have been scheduled yet.
                    </p>

                    <a
                        href="{{ route('admin.fitout.inspections.create') }}"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-plus-lg me-1"></i>

                        Schedule First Inspection

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection