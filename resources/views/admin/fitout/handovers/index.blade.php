@extends('layouts.app')

@section('title', 'Fit-Out Handovers')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Fit-Out Handovers
            </h4>

            <p class="text-muted mb-0">
                Manage fit-out handover records.
            </p>
        </div>

        <a
            href="{{ route('admin.fitout.handovers.create') }}"
            class="btn btn-primary"
        >
            <i class="bi bi-plus-circle me-1"></i>
            Create Handover
        </a>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- STATISTICS --}}
    {{-- ========================================================= --}}

    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Total Handovers
                            </small>

                            <h3 class="mb-0">
                                {{ $stats['total'] }}
                            </h3>

                        </div>

                        <div class="fs-2 text-primary">
                            <i class="bi bi-box-arrow-in-right"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Pending
                            </small>

                            <h3 class="mb-0">
                                {{ $stats['pending'] }}
                            </h3>

                        </div>

                        <div class="fs-2 text-warning">
                            <i class="bi bi-clock"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                In Progress
                            </small>

                            <h3 class="mb-0">
                                {{ $stats['in_progress'] }}
                            </h3>

                        </div>

                        <div class="fs-2 text-info">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Completed
                            </small>

                            <h3 class="mb-0">
                                {{ $stats['completed'] }}
                            </h3>

                        </div>

                        <div class="fs-2 text-success">
                            <i class="bi bi-check-circle"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FILTERS --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                Filters
            </h5>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.fitout.handovers.index') }}"
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
                            placeholder="Handover number..."
                            value="{{ request('search') }}"
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
                                'Pending',
                                'Scheduled',
                                'In Progress',
                                'Accepted',
                                'Rejected',
                                'Completed',
                                'Cancelled'
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


                    {{-- Handover Type --}}
                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Handover Type
                        </label>

                        <select
                            name="handover_type"
                            class="form-select"
                        >

                            <option value="">
                                All Types
                            </option>

                            @foreach([
                                'Fit-Out Handover',
                                'Final Handover',
                                'Partial Handover'
                            ] as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(
                                        request('handover_type') === $type
                                    )
                                >
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Unit --}}
                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Unit
                        </label>

                        <select
                            name="unit_id"
                            class="form-select"
                        >

                            <option value="">
                                All Units
                            </option>

                            @foreach($units as $unit)

                                <option
                                    value="{{ $unit->id }}"
                                    @selected(
                                        request('unit_id') == $unit->id
                                    )
                                >
                                    {{ $unit->unit_no }}
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


                    {{-- Buttons --}}
                    <div class="col-md-1 mb-3 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                            title="Filter"
                        >

                            <i class="fas fa-search"></i>

                        </button>

                    </div>

                </div>


                @if(
                    request()->filled('search') ||
                    request()->filled('status') ||
                    request()->filled('handover_type') ||
                    request()->filled('unit_id') ||
                    request()->filled('contractor_id')
                )

                    <div>

                        <a
                            href="{{ route(
                                'admin.fitout.handovers.index'
                            ) }}"
                            class="btn btn-sm btn-outline-secondary"
                        >

                            <i class="bi bi-x-circle me-1"></i>

                            Clear Filters

                        </a>

                    </div>

                @endif

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- TABLE --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                Handover List
            </h5>

            <span class="text-muted">
                {{ $handovers->total() }} records
            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Handover
                            </th>

                            <th>
                                Request
                            </th>

                            <th>
                                Unit
                            </th>

                            <th>
                                Tenant
                            </th>

                            <th>
                                Contractor
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($handovers as $handover)

                            <tr>

                                {{-- ID --}}
                                <td>

                                    {{ $handover->id }}

                                </td>


                                {{-- Handover Number --}}
                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.fitout.handovers.show',
                                            $handover->id
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >

                                        {{ $handover->handover_number }}

                                    </a>

                                </td>


                                {{-- Request --}}
                                <td>

                                    @if($handover->fitoutRequest)

                                        <a
                                            href="{{ route(
                                                'admin.fitout.requests.show',
                                                $handover->fitoutRequest->id
                                            ) }}"
                                            class="text-decoration-none"
                                        >

                                            {{
                                                $handover
                                                    ->fitoutRequest
                                                    ->request_no
                                            }}

                                        </a>

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- Unit --}}
                                <td>

                                    @if($handover->unit)

                                        {{ $handover->unit->unit_no }}

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- Tenant --}}
                                <td>

                                    @if($handover->tenant)

                                        {{
                                            $handover->tenant->company_name
                                            ??
                                            $handover->tenant->tenant_name
                                            ??
                                            $handover->tenant->name
                                            ??
                                            'Tenant #' .
                                            $handover->tenant->id
                                        }}

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- Contractor --}}
                                <td>

                                    @if($handover->contractor)

                                        {{
                                            $handover->contractor->company_name
                                            ??
                                            $handover->contractor->contractor_name
                                            ??
                                            $handover->contractor->name
                                            ??
                                            'Contractor #' .
                                            $handover->contractor->id
                                        }}

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- Type --}}
                                <td>

                                    <span class="badge bg-light text-dark">

                                        {{ $handover->handover_type }}

                                    </span>

                                </td>


                                {{-- Date --}}
                                <td>

                                    @if($handover->handover_date)

                                        {{
                                            $handover->handover_date
                                                ->format('d M Y')
                                        }}

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    @php

                                        $statusClass = match(
                                            $handover->status
                                        ) {

                                            'Pending' =>
                                                'bg-warning text-dark',

                                            'Scheduled' =>
                                                'bg-info text-dark',

                                            'In Progress' =>
                                                'bg-primary',

                                            'Accepted' =>
                                                'bg-success',

                                            'Completed' =>
                                                'bg-success',

                                            'Rejected' =>
                                                'bg-danger',

                                            'Cancelled' =>
                                                'bg-secondary',

                                            default =>
                                                'bg-secondary',

                                        };

                                    @endphp


                                    <span
                                        class="badge {{ $statusClass }}"
                                    >

                                        {{ $handover->status }}

                                    </span>

                                </td>


                                {{-- Actions --}}
                                <td class="text-end">

                                    <div class="dropdown">

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-light"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                        >

                                            <i class="bi bi-three-dots-vertical"></i>

                                        </button>


                                        <ul class="dropdown-menu dropdown-menu-end">

                                            {{-- View --}}
                                            <li>

                                                <a
                                                    class="dropdown-item"
                                                    href="{{ route(
                                                        'admin.fitout.handovers.show',
                                                        $handover->id
                                                    ) }}"
                                                >

                                                    <i class="bi bi-eye me-2"></i>

                                                    View

                                                </a>

                                            </li>


                                            {{-- Edit --}}
                                            <li>

                                                <a
                                                    class="dropdown-item"
                                                    href="{{ route(
                                                        'admin.fitout.handovers.edit',
                                                        $handover->id
                                                    ) }}"
                                                >

                                                    <i class="bi bi-pencil me-2"></i>

                                                    Edit

                                                </a>

                                            </li>


                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>


                                            {{-- Delete --}}
                                            <li>

                                                <form
                                                    action="{{ route(
                                                        'admin.fitout.handovers.destroy',
                                                        $handover->id
                                                    ) }}"
                                                    method="POST"
                                                    onsubmit="return confirm(
                                                        'Are you sure you want to delete this handover?'
                                                    );"
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

                                        </ul>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="10"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>

                                        <h5>
                                            No handovers found
                                        </h5>

                                        <p class="mb-3">
                                            There are no handover records matching your filters.
                                        </p>

                                        <a
                                            href="{{ route(
                                                'admin.fitout.handovers.create'
                                            ) }}"
                                            class="btn btn-primary"
                                        >

                                            <i class="bi bi-plus-circle me-1"></i>

                                            Create Handover

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($handovers->hasPages())

            <div class="card-footer">

                {{ $handovers->withQueryString()->links() }}

            </div>

        @endif

    </div>

</div>

@endsection