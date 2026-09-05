@extends('layouts.app')

@section('title', 'Lease Agreements')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Lease Agreements
            </h4>

            <div class="text-muted">
                Manage all lease agreements
            </div>
        </div>

        <a href="{{ route('admin.leasing.agreements.create') }}"
           class="btn btn-primary">

            <i class="fas fa-plus"></i>
            New Lease Agreement

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- SEARCH / FILTER --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.leasing.agreements.index') }}">

                <div class="row g-3">


                    {{-- Search --}}
                    <div class="col-md-5">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Agreement No, Proposal No, Tenant...">

                    </div>


                    {{-- Status --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Agreement Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="Draft"
                                {{ request('status') == 'Draft' ? 'selected' : '' }}>
                                Draft
                            </option>

                            <option value="Active"
                                {{ request('status') == 'Active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="Expired"
                                {{ request('status') == 'Expired' ? 'selected' : '' }}>
                                Expired
                            </option>

                            <option value="Terminated"
                                {{ request('status') == 'Terminated' ? 'selected' : '' }}>
                                Terminated
                            </option>

                            <option value="Renewed"
                                {{ request('status') == 'Renewed' ? 'selected' : '' }}>
                                Renewed
                            </option>

                            <option value="Cancelled"
                                {{ request('status') == 'Cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-md-4 d-flex align-items-end gap-2">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="fas fa-search"></i>
                            Search

                        </button>


                        <a href="{{ route(
                            'admin.leasing.agreements.index'
                        ) }}"
                           class="btn btn-secondary">

                            <i class="fas fa-redo"></i>
                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- AGREEMENTS TABLE --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">

                    <i class="fas fa-file-contract me-1"></i>

                    Lease Agreements

                </h5>


                <span class="badge bg-primary">

                    {{ $agreements->total() }} Total

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="60">
                                #
                            </th>

                            <th>
                                Agreement No.
                            </th>

                            <th>
                                Proposal No.
                            </th>

                            <th>
                                Tenant
                            </th>

                            <th>
                                Agreement Date
                            </th>

                            <th>
                                Lease Period
                            </th>

                            <th>
                                Monthly Rent
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="150">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($agreements as $agreement)

                            <tr>


                                {{-- ID --}}
                                <td>

                                    {{ $agreement->id }}

                                </td>


                                {{-- Agreement Number --}}
                                <td>

                                    <a href="{{ route(
                                        'admin.leasing.agreements.show',
                                        $agreement->id
                                    ) }}"
                                       class="fw-semibold text-decoration-none">

                                        {{ $agreement->agreement_no }}

                                    </a>

                                </td>


                                {{-- Proposal --}}
                                <td>

                                    @if($agreement->proposal)

                                        <a href="{{ route(
                                            'admin.leasing.proposals.show',
                                            $agreement->proposal->id
                                        ) }}"
                                           class="text-decoration-none">

                                            {{ $agreement->proposal->proposal_no }}

                                        </a>

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- Tenant --}}
                                <td>

                                    @if($agreement->tenant)

                                        <div class="fw-semibold">

                                            {{ $agreement->tenant->company_name }}

                                        </div>

                                        @if(!empty(
                                            $agreement->tenant->brand_name
                                        ))

                                            <small class="text-muted">

                                                {{ $agreement->tenant->brand_name }}

                                            </small>

                                        @endif

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- Agreement Date --}}
                                <td>

                                    {{ $agreement->agreement_date
                                        ? $agreement->agreement_date->format('d M Y')
                                        : '-'
                                    }}

                                </td>


                                {{-- Lease Period --}}
                                <td>

                                    <div>

                                        {{ $agreement->lease_start_date
                                            ? $agreement->lease_start_date->format('d M Y')
                                            : '-'
                                        }}

                                    </div>

                                    <small class="text-muted">

                                        to

                                        {{ $agreement->lease_end_date
                                            ? $agreement->lease_end_date->format('d M Y')
                                            : '-'
                                        }}

                                    </small>

                                    @if($agreement->lease_period_months)

                                        <div class="mt-1">

                                            <span class="badge bg-light text-dark">

                                                {{ $agreement->lease_period_months }}
                                                Months

                                            </span>

                                        </div>

                                    @endif

                                </td>


                                {{-- Rent --}}
                                <td>

                                    ${{ number_format(
                                        $agreement->monthly_rent ?? 0,
                                        2
                                    ) }}

                                    @if(
                                        $agreement->cam_amount > 0
                                    )

                                        <div>

                                            <small class="text-muted">

                                                CAM:
                                                ${{ number_format(
                                                    $agreement->cam_amount,
                                                    2
                                                ) }}

                                            </small>

                                        </div>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    @switch($agreement->agreement_status)

                                        @case('Draft')

                                            <span class="badge bg-secondary">
                                                Draft
                                            </span>

                                            @break


                                        @case('Active')

                                            <span class="badge bg-success">
                                                Active
                                            </span>

                                            @break


                                        @case('Expired')

                                            <span class="badge bg-warning text-dark">
                                                Expired
                                            </span>

                                            @break


                                        @case('Terminated')

                                            <span class="badge bg-danger">
                                                Terminated
                                            </span>

                                            @break


                                        @case('Renewed')

                                            <span class="badge bg-primary">
                                                Renewed
                                            </span>

                                            @break


                                        @case('Cancelled')

                                            <span class="badge bg-dark">
                                                Cancelled
                                            </span>

                                            @break


                                        @default

                                            <span class="badge bg-secondary">

                                                {{ $agreement->agreement_status }}

                                            </span>

                                    @endswitch

                                </td>


                                {{-- Actions --}}
                                <td>

                                    <div class="d-flex gap-1">


                                        {{-- View --}}
                                        <a href="{{ route(
                                            'admin.leasing.agreements.show',
                                            $agreement->id
                                        ) }}"
                                           class="btn btn-sm btn-info"
                                           title="View">

                                            <i class="fas fa-eye"></i>

                                        </a>


                                        {{-- Edit --}}
                                        <a href="{{ route(
                                            'admin.leasing.agreements.edit',
                                            $agreement->id
                                        ) }}"
                                           class="btn btn-sm btn-primary"
                                           title="Edit">

                                            <i class="fas fa-pen"></i>

                                        </a>


                                        {{-- Delete --}}
                                        <form method="POST"
                                              action="{{ route(
                                                  'admin.leasing.agreements.destroy',
                                                  $agreement->id
                                              ) }}"
                                              onsubmit="return confirm(
                                                  'Are you sure you want to delete this lease agreement?'
                                              );">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    title="Delete">

                                                <i class="fas fa-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="fas fa-file-contract fa-3x mb-3"></i>

                                        <h5>
                                            No Lease Agreements Found
                                        </h5>

                                        <p class="mb-3">

                                            There are no lease agreements
                                            matching your search.

                                        </p>

                                        <a href="{{ route(
                                            'admin.leasing.agreements.create'
                                        ) }}"
                                           class="btn btn-primary">

                                            <i class="fas fa-plus"></i>

                                            Create Lease Agreement

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- PAGINATION --}}
        {{-- ===================================================== --}}

        @if($agreements->hasPages())

            <div class="card-footer">

                {{ $agreements->links() }}

            </div>

        @endif

    </div>

</div>

@endsection
