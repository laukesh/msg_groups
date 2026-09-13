@extends('layouts.app')

@section('title', 'Lease Proposals')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h4 class="mb-1">Lease Proposals</h4>
            <p class="text-muted mb-0">
                Manage lease proposals and approvals.
            </p>
        </div>

        <div>
            <a href="{{ route('admin.leasing.proposals.create') }}"
               class="btn btn-primary">
                <i class="fas fa-plus"></i>
                New Proposal
            </a>
        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>
    @endif


    {{-- Filters --}}
    <div class="card mb-3">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.leasing.proposals.index') }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Proposal number / title"
                               value="{{ request('search') }}">

                    </div>


                    {{-- Tenant --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Tenant
                        </label>

                        <select name="tenant_id"
                                class="form-select">

                            <option value="">
                                All Tenants
                            </option>

                            @foreach($tenants as $tenant)

                                <option value="{{ $tenant->id }}"
                                    {{ request('tenant_id') == $tenant->id ? 'selected' : '' }}>

                                    {{ $tenant->company_name }}

                                    @if($tenant->brand_name)
                                        - {{ $tenant->brand_name }}
                                    @endif

                                </option>

                            @endforeach

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
                                All Status
                            </option>

                            <option value="Draft"
                                {{ request('status') == 'Draft' ? 'selected' : '' }}>
                                Draft
                            </option>

                            <option value="Pending Approval"
                                {{ request('status') == 'Pending Approval' ? 'selected' : '' }}>
                                Pending Approval
                            </option>

                            <option value="Approved"
                                {{ request('status') == 'Approved' ? 'selected' : '' }}>
                                Approved
                            </option>

                            <option value="Rejected"
                                {{ request('status') == 'Rejected' ? 'selected' : '' }}>
                                Rejected
                            </option>

                        </select>

                    </div>


                    {{-- From Date --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            From Date
                        </label>

                        <input type="date"
                               name="from_date"
                               class="form-control"
                               value="{{ request('from_date') }}">

                    </div>


                    {{-- To Date --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            To Date
                        </label>

                        <input type="date"
                               name="to_date"
                               class="form-control"
                               value="{{ request('to_date') }}">

                    </div>


                    {{-- Buttons --}}
                    <div class="col-12">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="fas fa-search"></i>
                            Search

                        </button>

                        <a href="{{ route('admin.leasing.proposals.index') }}"
                           class="btn btn-secondary">

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Proposal Table --}}
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                Proposal List
            </h5>

            <span class="text-muted">
                Total: {{ $proposals->total() }}
            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover table-bordered mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="60">
                                #
                            </th>

                            <th>
                                Proposal No.
                            </th>

                            <th>
                                Tenant
                            </th>

                            <th>
                                Units
                            </th>

                            <th>
                                Proposal Date
                            </th>

                            <th>
                                Lease Period
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="180">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($proposals as $proposal)

                            <tr>

                                {{-- Serial --}}
                                <td>
                                    {{ $proposals->firstItem() + $loop->index }}
                                </td>


                                {{-- Proposal Number --}}
                                <td>

                                    <a href="{{ route(
                                        'admin.leasing.proposals.show',
                                        $proposal->id
                                    ) }}"
                                       class="fw-semibold">

                                        {{ $proposal->proposal_number }}

                                    </a>

                                    <div class="small text-muted">

                                        {{ $proposal->proposal_title }}

                                    </div>

                                </td>


                                {{-- Tenant --}}
                                <td>

                                    @if($proposal->tenant)

                                        <div class="fw-semibold">

                                            {{ $proposal->tenant->company_name }}

                                        </div>

                                        @if($proposal->tenant->brand_name)

                                            <div class="small text-muted">

                                                {{ $proposal->tenant->brand_name }}

                                            </div>

                                        @endif

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- Units --}}
                                <td>

                                    @forelse($proposal->units as $proposalUnit)

                                        @if($proposalUnit->unit)

                                            <span class="badge bg-light text-dark border me-1">

                                                {{ $proposalUnit->unit->unit_no }}

                                            </span>

                                        @endif

                                    @empty

                                        <span class="text-muted">
                                            No Unit
                                        </span>

                                    @endforelse

                                </td>


                                {{-- Proposal Date --}}
                                <td>

                                    {{ $proposal->proposal_date
                                        ? $proposal->proposal_date->format('d-m-Y')
                                        : '-' }}

                                </td>


                                {{-- Lease Period --}}
                                <td>

                                    <div>
                                        {{ $proposal->lease_start_date
                                            ? $proposal->lease_start_date->format('d-m-Y')
                                            : '-' }}
                                    </div>

                                    <div class="small text-muted">
                                        to
                                        {{ $proposal->lease_end_date
                                            ? $proposal->lease_end_date->format('d-m-Y')
                                            : '-' }}
                                    </div>

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if($proposal->proposal_status === 'Draft')

                                        <span class="badge bg-secondary">
                                            Draft
                                        </span>

                                    @elseif($proposal->proposal_status === 'Pending Approval')

                                        <span class="badge bg-warning text-dark">
                                            Pending Approval
                                        </span>

                                    @elseif($proposal->proposal_status === 'Approved')

                                        <span class="badge bg-success">
                                            Approved
                                        </span>

                                    @elseif($proposal->proposal_status === 'Rejected')

                                        <span class="badge bg-danger">
                                            Rejected
                                        </span>

                                    @else

                                        <span class="badge bg-dark">
                                            {{ $proposal->proposal_status }}
                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td>

                                    <div class="d-flex gap-1">

                                        {{-- View --}}
                                        <a href="{{ route(
                                            'admin.leasing.proposals.show',
                                            $proposal->id
                                        ) }}"
                                           class="btn btn-sm btn-info"
                                           title="View">

                                            <i class="fas fa-eye"></i>

                                        </a>


                                        {{-- Edit --}}
                                        @if($proposal->proposal_status === 'Draft')

                                            <a href="{{ route(
                                                'admin.leasing.proposals.edit',
                                                $proposal->id
                                            ) }}"
                                               class="btn btn-sm btn-primary"
                                               title="Edit">

                                                <i class="fas fa-edit"></i>

                                            </a>

                                        @endif


                                        {{-- Submit --}}
                                        @if($proposal->proposal_status === 'Draft')

                                            <form method="POST"
                                                  action="{{ route(
                                                      'admin.leasing.proposals.submit',
                                                      $proposal->id
                                                  ) }}">

                                                @csrf

                                                <button type="submit"
                                                        class="btn btn-sm btn-warning"
                                                        title="Submit"
                                                        onclick="return confirm('Submit this proposal for approval?')">

                                                    <i class="fas fa-paper-plane"></i>

                                                </button>

                                            </form>

                                        @endif


                                        {{-- Approve --}}
                                        @if($proposal->proposal_status === 'Pending Approval')

                                            <form method="POST"
                                                  action="{{ route(
                                                      'admin.leasing.proposals.approve',
                                                      $proposal->id
                                                  ) }}">

                                                @csrf

                                                <button type="submit"
                                                        class="btn btn-sm btn-success"
                                                        title="Approve"
                                                        onclick="return confirm('Approve this lease proposal?')">

                                                    <i class="fas fa-check"></i>

                                                </button>

                                            </form>

                                        @endif


                                        {{-- Delete --}}
                                        @if($proposal->proposal_status === 'Draft')

                                            <form method="POST"
                                                  action="{{ route(
                                                      'admin.leasing.proposals.destroy',
                                                      $proposal->id
                                                  ) }}">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this proposal?')">

                                                    <i class="fas fa-trash"></i>

                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8"
                                    class="text-center py-4">

                                    <div class="text-muted">

                                        No lease proposals found.

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($proposals->hasPages())

            <div class="card-footer">

                {{ $proposals->links() }}

            </div>

        @endif

    </div>

</div>

@endsection