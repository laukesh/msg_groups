@extends('layouts.app')

@section('title', 'Departments')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">

                <i class="fas fa-sitemap me-2"></i>

                Departments

            </h4>

            <div class="text-muted">
                Manage departments.
            </div>

        </div>

        @can('departments.create')

            <a
                href="{{ route(
                    'admin.assets.departments.create'
                ) }}"
                class="btn btn-primary"
            >

                <i class="fas fa-plus me-1"></i>

                Add Department

            </a>

        @endcan

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div
            class="alert alert-success alert-dismissible fade show"
            role="alert"
        >

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

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


    {{-- Search & Filter --}}
    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fas fa-filter me-1"></i>

                Search & Filter

            </h5>

        </div>

        <div class="card-body">

            <form
                method="GET"
                action="{{ route(
                    'admin.assets.departments.index'
                ) }}"
            >

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-md-6 col-lg-7">

                        <label
                            for="search"
                            class="form-label"
                        >
                            Search
                        </label>

                        <input
                            type="text"
                            id="search"
                            name="search"
                            class="form-control"
                            placeholder="Search department..."
                            value="{{ request('search') }}"
                        >

                    </div>


                    {{-- Status --}}
                    <div class="col-md-4 col-lg-3">

                        <label
                            for="status"
                            class="form-label"
                        >
                            Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="active"
                                {{ request('status') === 'active'
                                    ? 'selected'
                                    : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="inactive"
                                {{ request('status') === 'inactive'
                                    ? 'selected'
                                    : '' }}
                            >
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div
                        class="col-md-2 d-flex align-items-end gap-2"
                    >

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="fas fa-search me-1"></i>

                            Search

                        </button>

                        <a
                            href="{{ route(
                                'admin.assets.departments.index'
                            ) }}"
                            class="btn btn-secondary"
                        >
                            Clear
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Department List --}}
    <div class="card border-0 shadow-sm">

        <div
            class="card-header d-flex "
        >

            <h5 class="mb-0">

                <i class="fas fa-list me-1"></i>

                Department List

            </h5>

            <span class="text-muted">

                Total: {{ $departments->total() }}

            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table
                    class="table table-bordered table-hover align-middle mb-0"
                >

                    <thead class="table-light">

                        <tr>

                            <th width="70">ID</th>

                            <th>Code</th>

                            <th>Department Name</th>

                            <th>Parent Department</th>

                            <th>Department Head</th>

                            <th>Status</th>

                            <th>Created By</th>

                            <th width="220">Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($departments as $department)

                        <tr>

                            {{-- ID --}}
                            <td>
                                {{ $department->id }}
                            </td>


                            {{-- Code --}}
                            <td>

                                <strong>
                                    {{ $department->department_code }}
                                </strong>

                            </td>


                            {{-- Name --}}
                            <td>

                                <a
                                    href="{{ route(
                                        'admin.assets.departments.show',
                                        $department->id
                                    ) }}"
                                    class="text-decoration-none"
                                >

                                    <strong>
                                        {{ $department->department_name }}
                                    </strong>

                                </a>

                            </td>


                            {{-- Parent --}}
                            <td>

                                {{ $department
                                    ->parentDepartment
                                    ?->department_name
                                    ?? '-' }}

                            </td>


                            {{-- Head --}}
                            <td>

                                {{ $department
                                    ->headUser
                                    ?->name
                                    ?? '-' }}

                            </td>


                            {{-- Status --}}
                            <td>

                                @if(
                                    strtolower(
                                        (string) $department->status
                                    ) === 'active'
                                )

                                    <span class="badge bg-success">

                                        <i
                                            class="fas fa-check-circle me-1"
                                        ></i>

                                        Active

                                    </span>

                                @else

                                    <span class="badge bg-secondary">

                                        <i
                                            class="fas fa-times-circle me-1"
                                        ></i>

                                        {{ ucfirst(
                                            $department->status
                                        ) }}

                                    </span>

                                @endif

                            </td>


                            {{-- Created By --}}
                            <td>

                                {{ $department
                                    ->creator
                                    ?->name
                                    ?? '-' }}

                            </td>


                            {{-- Actions --}}
                            <td>

                                <a
                                    href="{{ route(
                                        'admin.assets.departments.show',
                                        $department->id
                                    ) }}"
                                    class="btn btn-sm btn-info"
                                    title="View"
                                >

                                    <i class="fas fa-eye"></i>

                                    

                                </a>


                                @can('departments.edit')

                                    <a
                                        href="{{ route(
                                            'admin.assets.departments.edit',
                                            $department->id
                                        ) }}"
                                        class="btn btn-sm btn-primary"
                                        title="Edit"
                                    >

                                        <i class="fas fa-edit"></i>

                                        

                                    </a>

                                @endcan


                                @can('departments.delete')

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'admin.assets.departments.destroy',
                                            $department->id
                                        ) }}"
                                        class="d-inline"
                                        onsubmit="return confirm(
                                            'Are you sure you want to delete this department?'
                                        )"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-danger"
                                            title="Delete"
                                        >

                                            <i class="fas fa-trash"></i>

                                            

                                        </button>

                                    </form>

                                @endcan

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center py-5"
                            >

                                <div class="text-muted">

                                    <i
                                        class="fas fa-sitemap fa-2x mb-2"
                                    ></i>

                                    <div>
                                        No departments found.
                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($departments->hasPages())

            <div class="card-footer">

                {{ $departments
                    ->withQueryString()
                    ->links() }}

            </div>

        @endif

    </div>

</div>

@endsection