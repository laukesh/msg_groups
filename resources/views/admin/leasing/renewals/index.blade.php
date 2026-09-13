@extends('layouts.app')

@section('title', 'Lease Renewals')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Lease Renewals</h4>

            <div class="text-muted">
                Manage lease renewal requests and approvals.
            </div>
        </div>

        <a href="{{ route('admin.leasing.renewals.create') }}"
           class="btn btn-primary">

            <i class="fas fa-plus me-1"></i>
            Create Renewal

        </a>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success">

            <i class="fas fa-check-circle me-1"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- Table --}}
    <div class="card">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fas fa-sync-alt me-1"></i>

                Renewal Requests

            </h5>

        </div>


        <div class="card-body p-0">

            @if($renewals->count())

                <div class="table-responsive">

                    <table class="table table-hover mb-0 align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>Renewal No.</th>

                                <th>Agreement</th>

                                <th>Tenant</th>

                                <th>Current Expiry</th>

                                <th>Proposed Period</th>

                                <th>Current Rent</th>

                                <th>Proposed Rent</th>

                                <th>Status</th>

                                <th width="100">Action</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($renewals as $renewal)

                                <tr>

                                    <td>

                                        {{ $renewals->firstItem() + $loop->index }}

                                    </td>


                                    {{-- Renewal No --}}

                                    <td>

                                        <a href="{{ route(
                                            'admin.leasing.renewals.show',
                                            $renewal->id
                                        ) }}"
                                           class="fw-semibold text-decoration-none">

                                            {{ $renewal->renewal_no }}

                                        </a>

                                    </td>


                                    {{-- Agreement --}}

                                    <td>

                                        @if($renewal->agreement)

                                            <a href="{{ route(
                                                'admin.leasing.agreements.show',
                                                $renewal->agreement->id
                                            ) }}">

                                                {{ $renewal->agreement->agreement_no }}

                                            </a>

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- Tenant --}}

                                    <td>

                                        {{ $renewal->agreement?->tenant?->company_name
                                            ?? '-' }}

                                    </td>


                                    {{-- Current Expiry --}}

                                    <td>

                                        {{ $renewal->current_expiry_date
                                            ? $renewal->current_expiry_date->format(
                                                'd M Y'
                                            )
                                            : '-' }}

                                    </td>


                                    {{-- Proposed Period --}}

                                    <td>

                                        <div>

                                            {{ $renewal->proposed_start_date
                                                ? $renewal->proposed_start_date->format(
                                                    'd M Y'
                                                )
                                                : '-' }}

                                        </div>

                                        <small class="text-muted">

                                            to

                                            {{ $renewal->proposed_end_date
                                                ? $renewal->proposed_end_date->format(
                                                    'd M Y'
                                                )
                                                : '-' }}

                                        </small>

                                    </td>


                                    {{-- Current Rent --}}

                                    <td>

                                        ${{ number_format(
                                            $renewal->current_rent ?? 0,
                                            2
                                        ) }}

                                    </td>


                                    {{-- Proposed Rent --}}

                                    <td>

                                        ${{ number_format(
                                            $renewal->proposed_rent ?? 0,
                                            2
                                        ) }}

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        @if(
                                            $renewal->approval_status === 'Draft'
                                        )

                                            <span class="badge bg-secondary">
                                                Draft
                                            </span>

                                        @elseif(
                                            $renewal->approval_status === 'Pending'
                                        )

                                            <span class="badge bg-warning text-dark">
                                                Pending
                                            </span>

                                        @elseif(
                                            $renewal->approval_status === 'Approved'
                                        )

                                            <span class="badge bg-success">
                                                Approved
                                            </span>

                                        @elseif(
                                            $renewal->approval_status === 'Rejected'
                                        )

                                            <span class="badge bg-danger">
                                                Rejected
                                            </span>

                                        @elseif(
                                            $renewal->approval_status === 'Cancelled'
                                        )

                                            <span class="badge bg-dark">
                                                Cancelled
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Action --}}

                                    <td>

                                        <a href="{{ route(
                                            'admin.leasing.renewals.show',
                                            $renewal->id
                                        ) }}"
                                           class="btn btn-sm btn-info">

                                            <i class="fas fa-eye"></i>

                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}

                <div class="p-3">

                    {{ $renewals->links() }}

                </div>

            @else

                <div class="text-center py-5">

                    <i class="fas fa-sync-alt fa-3x text-muted mb-3"></i>

                    <h5>No renewal requests found</h5>

                    <p class="text-muted">

                        No lease renewal requests have been created yet.

                    </p>


                    <a href="{{ route(
                        'admin.leasing.renewals.create'
                    ) }}"
                       class="btn btn-primary">

                        <i class="fas fa-plus me-1"></i>

                        Create Renewal

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection