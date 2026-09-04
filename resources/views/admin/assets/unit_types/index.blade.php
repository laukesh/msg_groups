@extends('layouts.app')

@section('title', 'Unit Types')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1">
                <i class="fas fa-tags me-1"></i>
                Unit Types
            </h1>

            <p class="text-muted mb-0">
                Manage mall unit types.
            </p>
        </div>

        @can('unit_types.create')

            <a
                href="{{ route('admin.assets.unit-types.create') }}"
                class="btn btn-primary"
            >
                <i class="fas fa-plus me-1"></i>
                Add Unit Type
            </a>

        @endcan

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div
            class="alert alert-success alert-dismissible fade show"
            role="alert"
        >

            <i class="fas fa-check-circle me-1"></i>
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-1">
                Please fix the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- SEARCH & FILTER --}}
    {{-- ========================================================= --}}

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
                action="{{ route('admin.assets.unit-types.index') }}"
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
                            name="search"
                            id="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Search by unit type..."
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
                            name="status"
                            id="status"
                            class="form-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="1"
                                {{ request('status') === '1' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="0"
                                {{ request('status') === '0' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- Filter Buttons --}}
                    <div class="col-md-2 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Search
                        </button>

                        <a
                            href="{{ route('admin.assets.unit-types.index') }}"
                            class="btn btn-secondary"
                        >
                           
                            Clear
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- UNIT TYPE LIST --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="card-header d-flex">

            <h5 class="mb-0">
                <i class="fas fa-list me-1"></i>
                Unit Type List
            </h5>

            <span class="badge bg-primary ms-auto left-2">
                Total: {{ $unitTypes->total() }}
            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="70">
                                ID
                            </th>

                            <th>
                                Type Name
                            </th>

                            <th>
                                Description
                            </th>

                            <th width="110">
                                Status
                            </th>

                            <th>
                                Created By
                            </th>

                            <th>
                                Updated By
                            </th>

                            <th width="220">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($unitTypes as $unitType)

                        <tr>

                            {{-- ID --}}
                            <td>
                                {{ $unitType->id }}
                            </td>


                            {{-- Type Name --}}
                            <td>

                                <a
                                    href="{{ route(
                                        'admin.assets.unit-types.show',
                                        $unitType->id
                                    ) }}"
                                    class="text-decoration-none"
                                >

                                    <strong>
                                        {{ $unitType->type_name }}
                                    </strong>

                                </a>

                            </td>


                            {{-- Description --}}
                            <td>

                                @if($unitType->description)

                                    {{ \Illuminate\Support\Str::limit(
                                        $unitType->description,
                                        70
                                    ) }}

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                @if((string) $unitType->status === '1')

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


                            {{-- Created By --}}
                            <td>
                                {{ $unitType->creator?->name ?? '-' }}
                            </td>


                            {{-- Updated By --}}
                            <td>
                                {{ $unitType->updater?->name ?? '-' }}
                            </td>


                            {{-- Actions --}}
                            <td class="text-nowrap">

                                {{-- View --}}
                                <a
                                    href="{{ route(
                                        'admin.assets.unit-types.show',
                                        $unitType->id
                                    ) }}"
                                    class="btn btn-sm btn-info"
                                    title="View Unit Type"
                                >
                                    <i class="fas fa-eye me-1"></i>
                                    
                                </a>


                                {{-- Edit --}}
                                @can('unit_types.edit')

                                    <a
                                        href="{{ route(
                                            'admin.assets.unit-types.edit',
                                            $unitType->id
                                        ) }}"
                                        class="btn btn-sm btn-primary"
                                        title="Edit Unit Type"
                                    >
                                        <i class="fas fa-edit me-1"></i>
                                        
                                    </a>

                                @endcan


                                {{-- Delete --}}
                                @can('unit_types.delete')

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'admin.assets.unit-types.destroy',
                                            $unitType->id
                                        ) }}"
                                        class="d-inline"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-danger"
                                            title="Delete Unit Type"
                                            onclick="return confirm(
                                                'Are you sure you want to delete this unit type?'
                                            )"
                                        >
                                            <i class="fas fa-trash me-1"></i>
                                            
                                        </button>

                                    </form>

                                @endcan

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5"
                            >

                                <div class="text-muted">

                                    <i class="fas fa-tags fa-2x mb-2"></i>

                                    <div class="fw-semibold">
                                        No unit types found.
                                    </div>

                                    @if(request()->filled('search') || request()->filled('status'))

                                        <small>
                                            Try changing your search or filter criteria.
                                        </small>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- PAGINATION --}}
        {{-- ========================================================= --}}

        @if($unitTypes->hasPages())

            <div class="card-footer">

                {{ $unitTypes->withQueryString()->links() }}

            </div>

        @endif

    </div>

</div>

@endsection