@extends('layouts.app')

@section('title', 'Manage User')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-user-cog me-2"></i>
                Manage User
            </h4>

            <div class="text-muted">
                Manage account, roles and access for
                <strong>{{ $user->name }}</strong>
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.users.audits', $user->id) }}"
               class="btn btn-outline-secondary">

                <i class="fas fa-history me-1"></i>
                Audit Logs

            </a>

            <a href="{{ route('admin.users.index') }}"
               class="btn btn-outline-primary">

                <i class="fas fa-arrow-left me-1"></i>
                Back to Users

            </a>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show" role="alert">

            <i class="fas fa-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show" role="alert">

            <div class="fw-semibold mb-2">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Please correct the following errors:
            </div>

            <ul class="mb-0 ps-4">

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


    <div class="row g-4">

        {{-- =====================================================
            USER INFORMATION
        ====================================================== --}}
        <div class="col-lg-5">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <h6 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        User Information
                    </h6>

                </div>

                <div class="card-body">

                    {{-- User --}}
                    <div class="d-flex align-items-center mb-4">

                        <div class="rounded-circle bg-primary text-white
                                    d-flex align-items-center justify-content-center
                                    me-3"
                             style="width:55px;height:55px;">

                            {{ strtoupper(substr($user->name, 0, 1)) }}

                        </div>

                        <div>

                            <h5 class="mb-1">
                                {{ $user->name }}
                            </h5>

                            <div class="text-muted">
                                {{ $user->email }}
                            </div>

                        </div>

                    </div>


                    {{-- Email --}}
                    <div class="mb-3">

                        <label class="form-label text-muted small mb-1">
                            Email Address
                        </label>

                        <div class="fw-semibold">
                            <i class="fas fa-envelope me-2 text-muted"></i>
                            {{ $user->email }}
                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="mb-3">

                        <label class="form-label text-muted small mb-1">
                            Account Status
                        </label>

                        <div>

                            @if($user->is_active)

                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Active
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle me-1"></i>
                                    Inactive
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Roles --}}
                    <div class="mb-0">

                        <label class="form-label text-muted small mb-2">
                            Assigned Roles
                        </label>

                        <div>

                            @forelse($user->roles as $role)

                                <span class="badge bg-primary me-1 mb-1">
                                    {{ $role->name }}
                                </span>

                            @empty

                                <span class="text-muted">
                                    No roles assigned
                                </span>

                            @endforelse

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            ROLE MANAGEMENT
        ====================================================== --}}
        <div class="col-lg-7">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h6 class="mb-0">
                        <i class="fas fa-user-shield me-2"></i>
                        Role Management
                    </h6>

                </div>

                <div class="card-body">

                    {{-- Assign Role --}}
                    <div class="mb-4">

                        <h6 class="mb-1">
                            <i class="fas fa-plus-circle text-success me-2"></i>
                            Assign Role
                        </h6>

                        <p class="text-muted small mb-3">
                            Assign an additional role to this user.
                        </p>

                        <form method="POST"
                              action="{{ route('admin.users.assign-role', $user->id) }}">

                            @csrf

                            <div class="row g-2">

                                <div class="col-md-8">

                                    <select name="role"
                                            class="form-select"
                                            required>

                                        <option value="">
                                            Select Role
                                        </option>

                                        @foreach($roles as $role)

                                            <option value="{{ $role->name }}">
                                                {{ $role->name }}
                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                                <div class="col-md-4">

                                    <button type="submit"
                                            class="btn btn-success w-100">

                                        <i class="fas fa-plus me-1"></i>
                                        Assign Role

                                    </button>

                                </div>

                            </div>

                        </form>

                    </div>


                    <hr>


                    {{-- Revoke Role --}}
                    <div class="mb-0">

                        <h6 class="mb-1">
                            <i class="fas fa-minus-circle text-danger me-2"></i>
                            Revoke Role
                        </h6>

                        <p class="text-muted small mb-3">
                            Remove an existing role from this user.
                        </p>

                        <form method="POST"
                              action="{{ route('admin.users.revoke-role', $user->id) }}">

                            @csrf

                            <div class="row g-2">

                                <div class="col-md-8">

                                    <select name="role"
                                            class="form-select"
                                            required>

                                        <option value="">
                                            Select Role
                                        </option>

                                        @foreach($user->roles as $role)

                                            <option value="{{ $role->name }}">
                                                {{ $role->name }}
                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                                <div class="col-md-4">

                                    <button type="submit"
                                            class="btn btn-danger w-100">

                                        <i class="fas fa-minus me-1"></i>
                                        Revoke Role

                                    </button>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        ACCOUNT STATUS
    ========================================================== --}}
    <div class="card border-0 shadow-sm mt-4">

        <div class="card-header bg-white">

            <h6 class="mb-0">
                <i class="fas fa-toggle-on me-2"></i>
                Account Status
            </h6>

        </div>

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <div class="fw-semibold">
                        {{ $user->is_active ? 'Account is Active' : 'Account is Inactive' }}
                    </div>

                    <div class="text-muted small">
                        {{ $user->is_active
                            ? 'The user can currently access the application.'
                            : 'The user cannot currently access the application.'
                        }}
                    </div>

                </div>


                <div>

                    @if(! $user->is_active)

                        <form method="POST"
                              action="{{ route('admin.users.activate', $user->id) }}"
                              class="d-inline">

                            @csrf

                            <button type="submit"
                                    class="btn btn-success">

                                <i class="fas fa-check-circle me-1"></i>
                                Activate User

                            </button>

                        </form>

                    @else

                        <form method="POST"
                              action="{{ route('admin.users.deactivate', $user->id) }}"
                              class="d-inline"
                              onsubmit="return confirm('Are you sure you want to deactivate this user?');">

                            @csrf

                            <button type="submit"
                                    class="btn btn-danger">

                                <i class="fas fa-ban me-1"></i>
                                Deactivate User

                            </button>

                        </form>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        AUDIT LOGS
    ========================================================== --}}
    <div class="card border-0 shadow-sm mt-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h6 class="mb-1">
                        <i class="fas fa-history me-2"></i>
                        Audit Logs
                    </h6>

                    <p class="text-muted small mb-0">
                        View role, status and account changes for this user.
                    </p>

                </div>

                <a href="{{ route('admin.users.audits', $user->id) }}"
                   class="btn btn-outline-secondary">

                    <i class="fas fa-history me-1"></i>
                    View Audit Logs

                </a>

            </div>

        </div>

    </div>

</div>

@endsection