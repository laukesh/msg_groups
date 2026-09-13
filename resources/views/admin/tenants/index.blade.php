@extends('layouts.app')

@section('title', 'Tenants')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Tenants
            </h4>

            <p class="text-muted mb-0">
                Manage all tenant profiles
            </p>

        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.tenants.dashboard') }}"
               class="btn btn-outline-secondary">

                <i class="fas fa-chart-line me-1"></i>

                Dashboard

            </a>


            <a href="{{ route('admin.tenants.create') }}"
               class="btn btn-primary">

                <i class="fas fa-plus me-1"></i>

                Add Tenant

            </a>

        </div>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

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


    {{-- =========================================================
         ERROR MESSAGE
    ========================================================== --}}

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-exclamation-circle me-1"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif

    {{-- =========================================================
     TENANT FILTERS
    ========================================================= --}}

    <div class="card border-0 shadow-sm mb-3">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h6 class="mb-0 fw-semibold">

                        <i class="fas fa-filter text-primary me-2"></i>

                        Filters

                    </h6>

                    <small class="text-muted">

                        Search and filter tenant records

                    </small>

                </div>


                {{-- Reset --}}

                @if(
                    request('search') ||
                    request('status') ||
                    request('login_status') ||
                    request('business_category_id')
                )

                    <a
                        href="{{ route('admin.tenants.index') }}"
                        class="btn btn-sm btn-outline-secondary"
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
                action="{{ route('admin.tenants.index') }}"
            >

                <div class="row g-3">


                    {{-- SEARCH --}}

                    <div class="col-lg-5">

                        <label class="form-label small fw-semibold">

                            Search Tenant

                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-white" style="width: auto;">

                                <i class="fas fa-search text-muted"></i>

                            </span>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                value="{{ request('search') }}"
                                placeholder="Company, code, GST, PAN, phone or email"
                            >

                        </div>

                    </div>


                    {{-- TENANT STATUS --}}

                    <div class="col-lg-2">

                        <label class="form-label small fw-semibold">

                            Tenant Status

                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All
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
                                value="Inactive"
                                {{ request('status') === 'Inactive'
                                    ? 'selected'
                                    : '' }}
                            >
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- LOGIN STATUS --}}

                    <div class="col-lg-2">

                        <label class="form-label small fw-semibold">

                            Login Status

                        </label>

                        <select
                            name="login_status"
                            class="form-select"
                        >

                            <option value="">
                                All
                            </option>

                            <option
                                value="active"
                                {{ request('login_status') === 'active'
                                    ? 'selected'
                                    : '' }}
                            >
                                Login Active
                            </option>

                            <option
                                value="inactive"
                                {{ request('login_status') === 'inactive'
                                    ? 'selected'
                                    : '' }}
                            >
                                Login Inactive
                            </option>

                            <option
                                value="none"
                                {{ request('login_status') === 'none'
                                    ? 'selected'
                                    : '' }}
                            >
                                No Login
                            </option>

                        </select>

                    </div>


                    {{-- BUTTONS --}}

                    <div class="col-12">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="fas fa-search me-1"></i>

                                Apply Filters

                            </button>


                            <a
                                href="{{ route(
                                    'admin.tenants.index'
                                ) }}"
                                class="btn btn-outline-secondary"
                            >

                                <i class="fas fa-redo me-1"></i>

                                Reset

                            </a>

                        </div>

                    </div>

                </div>

            </form>

            @if(
                request('search') ||
                request('status') ||
                request('login_status')
            )

                <div class="mb-3">

                    <small class="text-muted me-2">
                        Active filters:
                    </small>


                    @if(request('search'))

                        <span class="badge bg-light text-dark border me-1">

                            Search: {{ request('search') }}

                        </span>

                    @endif


                    @if(request('status'))

                        <span class="badge bg-light text-dark border me-1">

                            Status: {{ request('status') }}

                        </span>

                    @endif


                    @if(request('login_status'))

                        <span class="badge bg-light text-dark border me-1">

                            Login:
                            {{ ucfirst(
                                str_replace(
                                    '_',
                                    ' ',
                                    request('login_status')
                                )
                            ) }}

                        </span>

                    @endif

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
         TENANT TABLE
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-1">
                        Tenant List
                    </h5>

                    <small class="text-muted">
                        {{ $tenants->total() }} total tenant(s)
                    </small>

                </div>

            </div>

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
                                Tenant Code
                            </th>

                            <th>
                                Company
                            </th>

                            <th>
                                Brand
                            </th>

                            <th>
                                Contact
                            </th>

                            <th>
                                Login
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

                    @forelse($tenants as $tenant)

                        <tr>

                            {{-- ID --}}

                            <td>

                                {{ $tenants->firstItem() + $loop->index }}

                            </td>


                            {{-- TENANT CODE --}}

                            <td>

                                <span class="fw-semibold">

                                    {{ $tenant->tenant_code }}

                                </span>

                            </td>


                            {{-- COMPANY --}}

                            <td>

                                <div class="fw-semibold">

                                    {{ $tenant->company_name }}

                                </div>

                                @if($tenant->gst_number)

                                    <small class="text-muted">

                                        GST:
                                        {{ $tenant->gst_number }}

                                    </small>

                                @endif

                            </td>


                            {{-- BRAND --}}

                            <td>

                                {{ $tenant->brand_name ?: '-' }}

                            </td>


                            {{-- CONTACT --}}

                            <td>

                                @if($tenant->phone)

                                    <div>

                                        <i class="fas fa-phone
                                                  text-muted
                                                  me-1">
                                        </i>

                                        {{ $tenant->phone }}

                                    </div>

                                @endif


                                @if($tenant->email)

                                    <div class="small text-muted">

                                        <i class="fas fa-envelope
                                                  me-1">
                                        </i>

                                        {{ $tenant->email }}

                                    </div>

                                @endif


                                @if(
                                    !$tenant->phone &&
                                    !$tenant->email
                                )

                                    -

                                @endif

                            </td>


                            {{-- LOGIN --}}

                            <td>

                                @if($tenant->user)

                                    @if($tenant->user->is_active)

                                        <span class="badge bg-success">

                                            <i class="fas fa-check me-1"></i>

                                            Active

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            Inactive

                                        </span>

                                    @endif

                                    <div class="small text-muted mt-1">

                                        {{ $tenant->user->email }}

                                    </div>

                                @else

                                    <span class="badge bg-warning text-dark">

                                        No Login

                                    </span>

                                @endif

                            </td>


                            {{-- TENANT STATUS --}}

                            <td>

                                @if($tenant->status === 'Active')

                                    <span class="badge bg-success">

                                        Active

                                    </span>

                                @else

                                    <span class="badge bg-secondary">

                                        Inactive

                                    </span>

                                @endif

                            </td>


                            {{-- ACTIONS --}}

                            <td class="text-end">

                                <div class="btn-group">

                                    {{-- View --}}

                                    <a href="{{ route(
                                        'admin.tenants.show',
                                        $tenant->id
                                    ) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="View">

                                        <i class="fas fa-eye"></i>

                                    </a>


                                    {{-- Edit --}}

                                    <a href="{{ route(
                                        'admin.tenants.edit',
                                        $tenant->id
                                    ) }}"
                                       class="btn btn-sm btn-outline-warning"
                                       title="Edit">

                                        <i class="fas fa-edit"></i>

                                    </a>


                                    {{-- Deactivate --}}

                                    @if($tenant->status === 'Active')

                                        <form method="POST"
                                              action="{{ route(
                                                  'admin.tenants.destroy',
                                                  $tenant->id
                                              ) }}"
                                              class="d-inline"
                                              onsubmit="return confirm(
                                                  'Are you sure you want to deactivate this tenant?'
                                              );">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Deactivate">

                                                <i class="fas fa-user-slash"></i>

                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8"
                                class="text-center
                                       text-muted
                                       py-5">

                                <i class="fas fa-users
                                          fa-3x
                                          mb-3
                                          d-block">
                                </i>

                                <h6>
                                    No tenants found
                                </h6>

                                <p class="mb-3">
                                    Start by creating your first tenant.
                                </p>

                                <a href="{{ route(
                                    'admin.tenants.create'
                                ) }}"
                                   class="btn btn-primary">

                                    <i class="fas fa-plus me-1"></i>

                                    Add Tenant

                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
             PAGINATION
        ====================================================== --}}

        @if($tenants->hasPages())

            <div class="card-footer bg-white">

                {{ $tenants->links() }}

            </div>

        @endif

    </div>

</div>

@endsection