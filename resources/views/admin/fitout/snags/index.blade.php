@extends('layouts.app')

@section('title', 'Snag List')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Snag List</h4>

            <p class="text-muted mb-0">
                Manage fit-out defects and snag items.
            </p>
        </div>

        <a
            href="{{ route('admin.fitout.snags.create') }}"
            class="btn btn-primary"
        >
            <i class="bi bi-plus-circle me-1"></i>
            Create Snag
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


    {{-- Filters --}}
    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                Filters
            </h5>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.fitout.snags.index') }}"
            >

                <div class="row">


                    {{-- Search --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Snag no. / title"
                        >

                    </div>


                    {{-- Status --}}
                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            @foreach([
                                'Open',
                                'Assigned',
                                'In Progress',
                                'Resolved',
                                'Under Verification',
                                'Closed',
                                'Rejected',
                                'Reopened'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        request('status') === $status
                                    )
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Priority --}}
                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Priority
                        </label>

                        <select
                            name="priority"
                            class="form-select"
                        >

                            <option value="">
                                All Priority
                            </option>

                            @foreach([
                                'Low',
                                'Medium',
                                'High',
                                'Critical'
                            ] as $priority)

                                <option
                                    value="{{ $priority }}"
                                    @selected(
                                        request('priority') === $priority
                                    )
                                >
                                    {{ $priority }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Contractor --}}
                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Contractor
                        </label>

                        <select
                            name="contractor_id"
                            class="form-select"
                        >

                            <option value="">
                                All Contractors
                            </option>

                            @foreach($contractors as $contractor)

                                <option
                                    value="{{ $contractor->id }}"
                                    @selected(
                                        request('contractor_id')
                                        == $contractor->id
                                    )
                                >

                                    {{
                                        $contractor->company_name
                                        ??
                                        $contractor->contractor_name
                                        ??
                                        $contractor->name
                                        ??
                                        'Contractor #' . $contractor->id
                                    }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Date --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Due Date
                        </label>

                        <input
                            type="date"
                            name="due_date"
                            class="form-control"
                            value="{{ request('due_date') }}"
                        >

                    </div>


                    {{-- Buttons --}}
                    <div class="col-md-12">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="bi bi-search me-1"></i>

                            Filter

                        </button>


                        <a
                            href="{{ route(
                                'admin.fitout.snags.index'
                            ) }}"
                            class="btn btn-secondary"
                        >

                            <i class="bi bi-arrow-counterclockwise me-1"></i>

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Statistics --}}
    <div class="row mb-4">


        <div class="col-md-3 mb-3">

            <div class="card border-start border-4">

                <div class="card-body">

                    <small class="text-muted">
                        Total
                    </small>

                    <h4 class="mb-0">
                        {{ $stats['total'] ?? 0 }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3 mb-3">

            <div class="card border-start border-4 border-danger">

                <div class="card-body">

                    <small class="text-muted">
                        Critical
                    </small>

                    <h4 class="mb-0 text-danger">
                        {{ $stats['critical'] ?? 0 }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3 mb-3">

            <div class="card border-start border-4 border-warning">

                <div class="card-body">

                    <small class="text-muted">
                        Open
                    </small>

                    <h4 class="mb-0">
                        {{ $stats['open'] ?? 0 }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3 mb-3">

            <div class="card border-start border-4 border-success">

                <div class="card-body">

                    <small class="text-muted">
                        Closed
                    </small>

                    <h4 class="mb-0 text-success">
                        {{ $stats['closed'] ?? 0 }}
                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- Table --}}
    <div class="card">

        <div class="card-header d-flex justify-content-between">

            <h5 class="mb-0">
                Snags
            </h5>

            <span class="text-muted">
                {{ $snags->total() }} records
            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Snag No.
                            </th>

                            <th>
                                Title
                            </th>

                            <th>
                                Request
                            </th>

                            <th>
                                Inspection
                            </th>

                            <th>
                                Contractor
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

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($snags as $snag)

                            <tr>


                                {{-- Snag --}}
                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.fitout.snags.show',
                                            $snag->id
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >

                                        {{ $snag->snag_number }}

                                    </a>

                                    <small class="d-block text-muted">

                                        {{
                                            $snag->reported_date
                                                ? $snag->reported_date
                                                    ->format('d M Y')
                                                : '-'
                                        }}

                                    </small>

                                </td>


                                {{-- Title --}}
                                <td>

                                    <div class="fw-semibold">

                                        {{ $snag->title }}

                                    </div>

                                    @if($snag->category)

                                        <small class="text-muted">

                                            {{ $snag->category }}

                                        </small>

                                    @endif

                                </td>


                                {{-- Request --}}
                                <td>

                                    {{ $snag->fitoutRequest->request_no ?? '-' }}

                                </td>


                                {{-- Inspection --}}
                                <td>

                                    @if($snag->inspection)

                                        {{ $snag->inspection->inspection_number }}

                                        <small class="d-block text-muted">

                                            {{ $snag->inspection->inspection_type }}

                                        </small>

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- Contractor --}}
                                <td>

                                    @if($snag->contractor)

                                        {{
                                            $snag->contractor->company_name
                                            ??
                                            $snag->contractor->contractor_name
                                            ??
                                            $snag->contractor->name
                                            ??
                                            '-'
                                        }}

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- Priority --}}
                                <td>

                                    @php

                                        $priorityClass = match(
                                            $snag->priority
                                        ) {

                                            'Critical' =>
                                                'bg-danger',

                                            'High' =>
                                                'bg-warning text-dark',

                                            'Medium' =>
                                                'bg-info text-dark',

                                            default =>
                                                'bg-secondary',

                                        };

                                    @endphp

                                    <span
                                        class="badge {{ $priorityClass }}"
                                    >

                                        {{ $snag->priority }}

                                    </span>

                                </td>


                                {{-- Due Date --}}
                                <td>

                                    @if($snag->due_date)

                                        @php
                                            $isOverdue =
                                                $snag->due_date->isPast()
                                                &&
                                                !in_array(
                                                    $snag->status,
                                                    [
                                                        'Closed',
                                                        'Resolved'
                                                    ]
                                                );
                                        @endphp

                                        <span
                                            class="{{ $isOverdue
                                                ? 'text-danger fw-semibold'
                                                : ''
                                            }}"
                                        >

                                            {{
                                                $snag->due_date
                                                    ->format('d M Y')
                                            }}

                                        </span>

                                        @if($isOverdue)

                                            <small
                                                class="d-block text-danger"
                                            >
                                                Overdue
                                            </small>

                                        @endif

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    @php

                                        $statusClass = match(
                                            $snag->status
                                        ) {

                                            'Open' =>
                                                'bg-secondary',

                                            'Assigned' =>
                                                'bg-primary',

                                            'In Progress' =>
                                                'bg-info text-dark',

                                            'Resolved' =>
                                                'bg-success',

                                            'Under Verification' =>
                                                'bg-warning text-dark',

                                            'Closed' =>
                                                'bg-dark',

                                            'Rejected' =>
                                                'bg-danger',

                                            'Reopened' =>
                                                'bg-warning text-dark',

                                            default =>
                                                'bg-secondary',

                                        };

                                    @endphp

                                    <span
                                        class="badge {{ $statusClass }}"
                                    >

                                        {{ $snag->status }}

                                    </span>

                                </td>


                                {{-- Action --}}
                                <td class="text-end">

                                    <div class="dropdown" style="position: static;" >

                                        <button
                                            class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                            type="button"
                                            data-bs-toggle="dropdown"
                                        >

                                            Actions

                                        </button>


                                        <ul class="dropdown-menu dropdown-menu-end">


                                            <li>

                                                <a
                                                    class="dropdown-item"
                                                    href="{{ route(
                                                        'admin.fitout.snags.show',
                                                        $snag->id
                                                    ) }}"
                                                >

                                                    <i class="bi bi-eye me-2"></i>

                                                    View

                                                </a>

                                            </li>


                                            <li>

                                                <a
                                                    class="dropdown-item"
                                                    href="{{ route(
                                                        'admin.fitout.snags.edit',
                                                        $snag->id
                                                    ) }}"
                                                >

                                                    <i class="bi bi-pencil me-2"></i>

                                                    Edit

                                                </a>

                                            </li>


                                            @if($snag->status === 'Resolved')

                                                <li>

                                                    <form
                                                        action="{{ route(
                                                            'admin.fitout.snags.start-verification',
                                                            $snag->id
                                                        ) }}"
                                                        method="POST"
                                                    >

                                                        @csrf

                                                        <button
                                                            type="submit"
                                                            class="dropdown-item"
                                                        >

                                                            <i class="bi bi-shield-check me-2"></i>

                                                            Start Verification

                                                        </button>

                                                    </form>

                                                </li>

                                            @endif


                                            @if(
                                                in_array(
                                                    $snag->status,
                                                    [
                                                        'Open',
                                                        'Assigned',
                                                        'In Progress',
                                                        'Reopened'
                                                    ]
                                                )
                                            )

                                                <li>

                                                    <a
                                                        class="dropdown-item text-success"
                                                        href="{{ route(
                                                            'admin.fitout.snags.show',
                                                            $snag->id
                                                        ) }}#resolve"
                                                    >

                                                        <i class="bi bi-check-circle me-2"></i>

                                                        Resolve

                                                    </a>

                                                </li>

                                            @endif


                                            @if(
                                                in_array(
                                                    $snag->status,
                                                    [
                                                        'Open',
                                                        'Rejected'
                                                    ]
                                                )
                                            )

                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>

                                                <li>

                                                    <form
                                                        action="{{ route(
                                                            'admin.fitout.snags.destroy',
                                                            $snag->id
                                                        ) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this snag?');"
                                                    >

                                                        @csrf

                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            class="dropdown-item text-danger"
                                                        >

                                                            <i class="bi bi-trash me-2"></i>

                                                            Delete

                                                        </button>

                                                    </form>

                                                </li>

                                            @endif


                                        </ul>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center py-5"
                                >

                                    <i
                                        class="bi bi-clipboard-x fs-1 text-muted"
                                    ></i>

                                    <p class="text-muted mt-2 mb-0">
                                        No snags found.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($snags->hasPages())

            <div class="card-footer">

                {{ $snags->withQueryString()->links() }}

            </div>

        @endif

    </div>

</div>

@endsection