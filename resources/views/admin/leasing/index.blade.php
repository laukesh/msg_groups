@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">


    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="d-flex justify-content-between
                align-items-center mb-4">

        <div>

            <h3 class="mb-1 fw-bold">

                Leasing

            </h3>

            <p class="text-muted mb-0">

                Manage lease proposals and agreements

            </p>

        </div>

    </div>



    {{-- =====================================================
         FILTERS
    ====================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex
                        justify-content-between
                        align-items-center">

                <div>

                    <h6 class="mb-0 fw-semibold">

                        <i class="fas fa-filter
                                  text-primary me-2"></i>

                        Filters

                    </h6>

                    <small class="text-muted">

                        Search proposals, agreements or tenants

                    </small>

                </div>


                @if(
                    request('search') ||
                    request('status')
                )

                    <a
                        href="{{ route(
                            'admin.leasing.index'
                        ) }}"
                        class="btn btn-sm
                               btn-outline-secondary"
                    >

                        <i class="fas fa-times me-1"></i>

                        Clear Filters

                    </a>

                @endif

            </div>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route(
                    'admin.leasing.index'
                ) }}"
            >

                <div class="row g-3">


                    {{-- SEARCH --}}

                    <div class="col-lg-7">

                        <label class="form-label
                                      small fw-semibold">

                            Search

                        </label>

                        <div class="input-group">

                            <span class="input-group-text
                                         bg-white" style="width: auto;">

                                <i class="fas fa-search
                                          text-muted"></i>

                            </span>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control"
                                placeholder="Proposal No, Agreement No, Tenant or Brand"
                            >

                        </div>

                    </div>


                    {{-- STATUS --}}

                    <div class="col-lg-3">

                        <label class="form-label
                                      small fw-semibold">

                            Status

                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="Draft"
                                {{ request('status') === 'Draft'
                                    ? 'selected'
                                    : '' }}
                            >
                                Draft
                            </option>

                            <option
                                value="Pending Approval"
                                {{ request('status') === 'Pending Approval'
                                    ? 'selected'
                                    : '' }}
                            >
                                Pending Approval
                            </option>

                            <option
                                value="Approved"
                                {{ request('status') === 'Approved'
                                    ? 'selected'
                                    : '' }}
                            >
                                Approved
                            </option>

                            <option
                                value="Active"
                                {{ request('status') === 'Active'
                                    ? 'selected'
                                    : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="Expired"
                                {{ request('status') === 'Expired'
                                    ? 'selected'
                                    : '' }}
                            >
                                Expired
                            </option>

                            <option
                                value="Terminated"
                                {{ request('status') === 'Terminated'
                                    ? 'selected'
                                    : '' }}
                            >
                                Terminated
                            </option>

                            <option
                                value="Renewed"
                                {{ request('status') === 'Renewed'
                                    ? 'selected'
                                    : '' }}
                            >
                                Renewed
                            </option>

                            <option
                                value="Cancelled"
                                {{ request('status') === 'Cancelled'
                                    ? 'selected'
                                    : '' }}
                            >
                                Cancelled
                            </option>

                        </select>

                    </div>


                    {{-- BUTTONS --}}

                    <div class="col-lg-2
                                d-flex
                                align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >

                            <i class="fas fa-search me-1"></i>

                            Search

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>



    {{-- =====================================================
         ACTIVE FILTER
    ====================================================== --}}

    @if(
        request('search') ||
        request('status')
    )

        <div class="mb-3">

            <span class="text-muted small me-2">

                Active filters:

            </span>


            @if(request('search'))

                <span class="badge
                             bg-light
                             text-dark
                             border me-1">

                    Search:
                    {{ request('search') }}

                </span>

            @endif


            @if(request('status'))

                <span class="badge
                             bg-light
                             text-dark
                             border me-1">

                    Status:
                    {{ request('status') }}

                </span>

            @endif

        </div>

    @endif



    {{-- =====================================================
         LEASING TABLE
    ====================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white
                    d-flex
                    justify-content-between
                    align-items-center">

            <div>

                <h5 class="mb-0">

                    Leasing List

                </h5>

                <small class="text-muted">

                    {{ $leasing->total() }}
                    record(s)

                </small>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table
                              table-hover
                              align-middle
                              mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-4">
                                Proposal
                            </th>

                            <th>
                                Agreement
                            </th>

                            <th>
                                Tenant
                            </th>

                            <th>
                                Lease Period
                            </th>

                            <th>
                                Monthly Rent
                            </th>

                            <th>
                                CAM
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end pe-4">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($leasing as $lease)

                            <tr>


                                {{-- PROPOSAL --}}

                                <td class="ps-4">

                                    <div class="fw-semibold">

                                        {{ $lease->proposal_no }}

                                    </div>

                                    @if($lease->proposal_title)

                                        <small class="text-muted">

                                            {{ $lease->proposal_title }}

                                        </small>

                                    @endif

                                </td>


                                {{-- AGREEMENT --}}

                                <td>

                                    @if($lease->agreement_no)

                                        <div class="fw-semibold">

                                            {{ $lease->agreement_no }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $lease->agreement_status }}

                                        </small>

                                    @else

                                        <span class="text-muted">

                                            Not Created

                                        </span>

                                    @endif

                                </td>


                                {{-- TENANT --}}

                                <td>

                                    <div class="fw-semibold">

                                        {{ $lease->tenant_name
                                            ?: '—' }}

                                    </div>

                                    @if($lease->brand_name)

                                        <small class="text-muted">

                                            {{ $lease->brand_name }}

                                        </small>

                                    @endif

                                </td>


                                {{-- LEASE PERIOD --}}

                                <td>

                                    @if($lease->lease_start_date)

                                        <div>

                                            {{ \Carbon\Carbon::parse(
                                                $lease->lease_start_date
                                            )->format('d M Y') }}

                                        </div>

                                        <small class="text-muted">

                                            to

                                            {{ $lease->lease_end_date
                                                ? \Carbon\Carbon::parse(
                                                    $lease->lease_end_date
                                                )->format('d M Y')
                                                : '—' }}

                                        </small>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- RENT --}}

                                <td>

                                    ${{ number_format(
                                        $lease->monthly_rent ?? 0,
                                        2
                                    ) }}

                                </td>


                                {{-- CAM --}}

                                <td>

                                    ${{ number_format(
                                        $lease->cam_amount ?? 0,
                                        2
                                    ) }}

                                </td>


                                {{-- STATUS --}}

                                <td>

                                    @php

                                        $status =
                                            $lease->agreement_status
                                            ?: $lease->proposal_status;

                                        $badge = match($status) {

                                            'Active',
                                            'Approved'
                                                => 'bg-success',

                                            'Pending Approval',
                                            'Draft'
                                                => 'bg-warning text-dark',

                                            'Expired',
                                            'Terminated',
                                            'Rejected',
                                            'Cancelled'
                                                => 'bg-danger',

                                            'Renewed'
                                                => 'bg-primary',

                                            default
                                                => 'bg-secondary',

                                        };

                                    @endphp


                                    <span class="badge {{ $badge }}">

                                        {{ $status ?: '—' }}

                                    </span>

                                </td>


                                {{-- ACTION --}}

                                <td class="text-end pe-4">

                                    @if($lease->agreement_id)

                                        <a
                                            href="{{ route(
                                                'admin.leasing.show',
                                                $lease->agreement_id
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            <i class="fas fa-eye me-1"></i>
                                            View
                                        </a>

                                    @else

                                        <a
                                            href="{{ route('admin.leasing.show',$lease->agreement_id) }}"
                                            class="btn btn-sm
                                                   btn-outline-secondary"
                                        >

                                            <i class="fas fa-file-alt me-1"></i>

                                            View Proposal

                                        </a>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5"
                                >

                                    <i class="
                                        fas fa-file-contract
                                        fa-2x
                                        text-muted
                                        mb-3
                                    "></i>

                                    <div class="fw-semibold">

                                        No leasing records found

                                    </div>

                                    <small class="text-muted">

                                        Try changing your search
                                        or filter.

                                    </small>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- PAGINATION --}}

        @if($leasing->hasPages())

            <div class="card-footer bg-white">

                {{ $leasing->links() }}

            </div>

        @endif

    </div>

</div>

@endsection