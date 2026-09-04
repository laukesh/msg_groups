@extends('layouts.app')

@section('title', 'Users')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Users
            </h4>

            <div class="text-muted">
                Manage system users and their roles
            </div>
        </div>

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
    {{-- VALIDATION ERRORS --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-exclamation-circle me-1"></i>

            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

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
                  action="{{ route('admin.users.index') }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Search Users
                        </label>

                        <input type="text"
                               name="q"
                               class="form-control"
                               value="{{ $q ?? request('q') }}"
                               placeholder="Search by name or email...">

                    </div>


                    {{-- Buttons --}}
                    <div class="col-md-6 d-flex align-items-end gap-2">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="fas fa-search"></i>
                            Search

                        </button>


                        <a href="{{ route('admin.users.index') }}"
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
    {{-- USERS TABLE --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">

                    <i class="fas fa-users me-1"></i>

                    Users

                </h5>

                <span class="badge bg-primary">

                    {{ $users->total() }} Total

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="70">
                                #
                            </th>

                            <th>
                                Name
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Roles
                            </th>

                            <th width="130">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($users as $user)

                            <tr>

                                {{-- ID --}}
                                <td>

                                    {{ $user->id }}

                                </td>


                                {{-- Name --}}
                                <td>

                                    <a href="{{ route(
                                        'admin.users.show',
                                        $user->id
                                    ) }}"
                                       class="fw-semibold text-decoration-none">

                                        {{ $user->name }}

                                    </a>

                                </td>


                                {{-- Email --}}
                                <td>

                                    <a href="mailto:{{ $user->email }}"
                                       class="text-decoration-none">

                                        {{ $user->email }}

                                    </a>

                                </td>


                                {{-- Active --}}
                                <td>

                                    @if($user->is_active)

                                        <span class="badge bg-success">

                                            <i class="fas fa-check-circle me-1"></i>
                                            Active

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            <i class="fas fa-times-circle me-1"></i>
                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                {{-- Roles --}}
                                <td>

                                    @if($user->roles->count())

                                        <div class="d-flex flex-wrap gap-1">

                                            @foreach($user->roles as $role)

                                                <span class="badge bg-info text-dark">

                                                    {{ $role->name }}

                                                </span>

                                            @endforeach

                                        </div>

                                    @else

                                        <span class="text-muted">
                                            No Role Assigned
                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td>

                                    <div class="d-flex gap-1">

                                        {{-- View / Manage --}}
                                        <a href="{{ route(
                                            'admin.users.show',
                                            $user->id
                                        ) }}"
                                           class="btn btn-sm btn-info"
                                           title="Manage User">

                                            <i class="fas fa-eye"></i>

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="fas fa-users fa-3x mb-3"></i>

                                        <h5>
                                            No Users Found
                                        </h5>

                                        <p class="mb-0">

                                            There are no users
                                            matching your search.

                                        </p>

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

        @if($users->hasPages())

            <div class="card-footer">

                {{ $users->links() }}

            </div>

        @endif

    </div>

</div>

@endsection