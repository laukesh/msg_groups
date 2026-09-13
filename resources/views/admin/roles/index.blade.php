@extends('layouts.app')

@section('title', 'Roles')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Roles
            </h4>

            <div class="text-muted">
                Manage system roles and their permissions
            </div>
        </div>

        <a href="{{ route('admin.roles.create') }}"
           class="btn btn-primary">

            <i class="fas fa-plus"></i>
            Create Role

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
    {{-- ERROR MESSAGE --}}
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
    {{-- ROLES TABLE --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">

                    <i class="fas fa-user-shield me-1"></i>

                    Roles

                </h5>

                <span class="badge bg-primary">

                    {{ $roles->total() }} Total

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
                                Role Name
                            </th>

                            <th>
                                Permissions
                            </th>

                            <th width="180">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($roles as $role)

                            <tr>

                                {{-- ID --}}
                                <td>

                                    {{ $role->id }}

                                </td>


                                {{-- Role Name --}}
                                <td>

                                    <span class="fw-semibold">

                                        {{ $role->name }}

                                    </span>

                                </td>


                                {{-- Permissions --}}
                                <td>

                                    @if($role->permissions->count())

                                        <div class="d-flex flex-wrap gap-1">

                                            @foreach($role->permissions as $permission)

                                                <span class="badge bg-info text-dark">

                                                    {{ $permission->name }}

                                                </span>

                                            @endforeach

                                        </div>

                                    @else

                                        <span class="text-muted">

                                            No Permissions Assigned

                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td>

                                    <div class="d-flex gap-1">

                                        {{-- Edit --}}
                                        <a href="{{ route(
                                            'admin.roles.edit',
                                            $role->id
                                        ) }}"
                                           class="btn btn-sm btn-primary"
                                           title="Edit Role">

                                            <i class="fas fa-pen"></i>

                                        </a>


                                        {{-- Delete --}}
                                        <form method="POST"
                                              action="{{ route(
                                                  'admin.roles.destroy',
                                                  $role->id
                                              ) }}"
                                              onsubmit="return confirm(
                                                  'Are you sure you want to delete this role?'
                                              );">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    title="Delete Role">

                                                <i class="fas fa-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="fas fa-user-shield fa-3x mb-3"></i>

                                        <h5>
                                            No Roles Found
                                        </h5>

                                        <p class="mb-3">

                                            There are currently no roles
                                            available in the system.

                                        </p>

                                        <a href="{{ route('admin.roles.create') }}"
                                           class="btn btn-primary">

                                            <i class="fas fa-plus"></i>

                                            Create Role

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

        @if($roles->hasPages())

            <div class="card-footer">

                {{ $roles->links() }}

            </div>

        @endif

    </div>

</div>

@endsection