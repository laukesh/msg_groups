@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Fit-Out Requests
            </h4>

            <p class="text-muted mb-0">
                Manage tenant fit-out requests and their progress.
            </p>

        </div>


        <a href="{{ route('admin.fitout.requests.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-lg"></i>

            New Fit-Out Request

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS / ERROR --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-2 col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Total Requests
                    </small>

                    <h4 class="mb-0 mt-1">
                        {{ $totalRequests }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Draft
                    </small>

                    <h4 class="mb-0 mt-1">
                        {{ $draftRequests }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Submitted
                    </small>

                    <h4 class="mb-0 mt-1">
                        {{ $submittedRequests }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Under Review
                    </small>

                    <h4 class="mb-0 mt-1">
                        {{ $underReviewRequests }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Approved
                    </small>

                    <h4 class="mb-0 mt-1">
                        {{ $approvedRequests }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        In Progress
                    </small>

                    <h4 class="mb-0 mt-1">
                        {{ $inProgressRequests }}
                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FILTER --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Filters
            </strong>

        </div>


        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.fitout.requests.index') }}">

                <div class="row g-3">

                    {{-- Search --}}

                    <div class="col-md-3">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Request / Tenant / Contractor">

                    </div>


                    {{-- Type --}}

                    <div class="col-md-2">

                        <label class="form-label">
                            Fit-Out Type
                        </label>

                        <select name="fitout_type"
                                class="form-select">

                            <option value="">
                                All
                            </option>

                            <option value="New"
                                @selected(request('fitout_type') === 'New')>
                                New
                            </option>

                            <option value="Renovation"
                                @selected(request('fitout_type') === 'Renovation')>
                                Renovation
                            </option>

                            <option value="Expansion"
                                @selected(request('fitout_type') === 'Expansion')>
                                Expansion
                            </option>

                            <option value="Modification"
                                @selected(request('fitout_type') === 'Modification')>
                                Modification
                            </option>

                        </select>

                    </div>


                    {{-- Status --}}

                    <div class="col-md-2">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All
                            </option>

                            @foreach([
                                'Draft',
                                'Submitted',
                                'Under Review',
                                'Approved',
                                'Rejected',
                                'In Progress',
                                'Completed',
                                'Closed'
                            ] as $status)

                                <option value="{{ $status }}"
                                    @selected(request('status') === $status)>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Contractor --}}

                    <div class="col-md-2">

                        <label class="form-label">
                            Contractor
                        </label>

                        <select name="contractor_id"
                                class="form-select">

                            <option value="">
                                All
                            </option>

                            @foreach($contractors as $contractor)

                                <option value="{{ $contractor->id }}"
                                    @selected(
                                        (string) request('contractor_id')
                                        ===
                                        (string) $contractor->id
                                    )>

                                    {{ $contractor->contractor_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- From --}}

                    <div class="col-md-1">

                        <label class="form-label">
                            From
                        </label>

                        <input type="date"
                               name="from_date"
                               class="form-control"
                               value="{{ request('from_date') }}">

                    </div>


                    {{-- To --}}

                    <div class="col-md-1">

                        <label class="form-label">
                            To
                        </label>

                        <input type="date"
                               name="to_date"
                               class="form-control"
                               value="{{ request('to_date') }}">

                    </div>


                    {{-- Buttons --}}

                    <div class="col-md-1 d-flex align-items-end">

                        <button type="submit"
                                class="btn btn-primary w-100">

                            Filter

                        </button>

                    </div>


                    <div class="col-md-12">

                        <a href="{{ route('admin.fitout.requests.index') }}"
                           class="btn btn-sm btn-outline-secondary">

                            Clear Filters

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- TABLE --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Request No.
                            </th>

                            <th>
                                Tenant
                            </th>

                            <th>
                                Unit
                            </th>

                            <th>
                                Contractor
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Proposed Start
                            </th>

                            <th>
                                Proposed End
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

                        @forelse($requests as $fitoutRequest)

                            <tr>

                                {{-- Request --}}

                                <td>

                                    <strong>
                                        {{ $fitoutRequest->request_no }}
                                    </strong>

                                </td>


                                {{-- Tenant --}}

                                <td>

                                    @if($fitoutRequest->tenant)

                                        <div>
                                            {{ $fitoutRequest->tenant->company_name }}
                                        </div>

                                        <small class="text-muted">

                                            {{ $fitoutRequest->tenant->tenant_code }}

                                        </small>

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- Unit --}}

                                <td>

                                    @if($fitoutRequest->unit)

                                        {{ $fitoutRequest->unit->unit_no }}

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- Contractor --}}

                                <td>

                                    @if($fitoutRequest->contractor)

                                        <div>
                                            {{ $fitoutRequest->contractor->contractor_name }}
                                        </div>

                                        <small class="text-muted">

                                            {{ $fitoutRequest->contractor->contractor_code }}

                                        </small>

                                    @else

                                        <span class="text-muted">
                                            Not Assigned
                                        </span>

                                    @endif

                                </td>


                                {{-- Type --}}

                                <td>

                                    {{ $fitoutRequest->fitout_type }}

                                </td>


                                {{-- Start --}}

                                <td>

                                    {{ $fitoutRequest->proposed_start_date
                                        ? $fitoutRequest->proposed_start_date->format('d M Y')
                                        : '-'
                                    }}

                                </td>


                                {{-- End --}}

                                <td>

                                    {{ $fitoutRequest->proposed_end_date
                                        ? $fitoutRequest->proposed_end_date->format('d M Y')
                                        : '-'
                                    }}

                                </td>


                                {{-- Status --}}

                                <td>

                                    @php
                                        $statusClass = match(
                                            $fitoutRequest->fitout_status
                                        ) {
                                            'Draft' => 'bg-secondary',
                                            'Submitted' => 'bg-info text-dark',
                                            'Under Review' => 'bg-warning text-dark',
                                            'Approved' => 'bg-success',
                                            'Rejected' => 'bg-danger',
                                            'In Progress' => 'bg-primary',
                                            'Completed' => 'bg-success',
                                            'Closed' => 'bg-dark',
                                            default => 'bg-secondary',
                                        };
                                    @endphp

                                    <span class="badge {{ $statusClass }}">

                                        {{ $fitoutRequest->fitout_status }}

                                    </span>

                                </td>


                                {{-- Action --}}

                                <td class="text-end">

                                    <a href="{{ route('admin.fitout.requests.show', $fitoutRequest->id) }}"
                                       class="dropdown-item">

                                        <i class="fas fa-eye me-2"></i>
                                        View Details

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        No fit-out requests found.

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}

        @if($requests->hasPages())

            <div class="card-footer">

                {{ $requests->links() }}

            </div>

        @endif

    </div>

</div>

@endsection