@extends('layouts.app')

@section('title', 'Asset Categories')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-tags me-2"></i>
                Asset Categories
            </h4>

            <div class="text-muted">
                Manage asset categories.
            </div>
        </div>

        @can('asset_categories.create')

            <a
                href="{{ route('admin.assets.asset-categories.create') }}"
                class="btn btn-primary"
            >
                <i class="fas fa-plus me-1"></i>
                Add Asset Category
            </a>

        @endcan

    </div>


    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


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
                action="{{ route('admin.assets.asset-categories.index') }}"
            >

                <div class="row g-3">

                    <div class="col-md-7">

                        <label class="form-label" for="search">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            id="search"
                            class="form-control"
                            placeholder="Search category..."
                            value="{{ request('search') }}"
                        >

                    </div>

                    <div class="col-md-3">

                        <label class="form-label" for="is_active">
                            Status
                        </label>

                        <select
                            name="is_active"
                            id="is_active"
                            class="form-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="1"
                                {{ request('is_active') === '1' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="0"
                                {{ request('is_active') === '0' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>

                        </select>

                    </div>

                    <div class="col-md-2 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="fas fa-search me-1"></i>
                            Search
                        </button>

                        <a
                            href="{{ route('admin.assets.asset-categories.index') }}"
                            class="btn btn-secondary"
                        >
                            Clear
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <div class="card border-0 shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                <i class="fas fa-list me-1"></i>
                Asset Category List
            </h5>

            <span class="text-muted">
                Total: {{ $categories->total() }}
            </span>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th width="70">ID</th>
                            <th>Category Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Updated By</th>
                            <th width="220">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                    @forelse($categories as $category)

                        <tr>

                            <td>{{ $category->id }}</td>

                            <td>
                                <a
                                    href="{{ route(
                                        'admin.assets.asset-categories.show',
                                        $category->id
                                    ) }}"
                                    class="text-decoration-none"
                                >
                                    <strong>
                                        {{ $category->category_name }}
                                    </strong>
                                </a>
                            </td>

                            <td>
                                {{ \Illuminate\Support\Str::limit(
                                    $category->description,
                                    70
                                ) ?: '-' }}
                            </td>

                            <td>

                                @if($category->is_active)

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

                            <td>
                                {{ $category->creator?->name ?? '-' }}
                            </td>

                            <td>
                                {{ $category->updater?->name ?? '-' }}
                            </td>

                            <td>

                                <a
                                    href="{{ route(
                                        'admin.assets.asset-categories.show',
                                        $category->id
                                    ) }}"
                                    class="btn btn-sm btn-info"
                                >
                                    <i class="fas fa-eye"></i>
                                    
                                </a>

                                @can('asset_categories.edit')

                                    <a
                                        href="{{ route(
                                            'admin.assets.asset-categories.edit',
                                            $category->id
                                        ) }}"
                                        class="btn btn-sm btn-primary"
                                    >
                                        <i class="fas fa-edit"></i>
                                        
                                    </a>

                                @endcan

                                @can('asset_categories.delete')

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'admin.assets.asset-categories.destroy',
                                            $category->id
                                        ) }}"
                                        class="d-inline"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this asset category?')"
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
                                colspan="7"
                                class="text-center py-5"
                            >

                                <div class="text-muted">

                                    <i class="fas fa-tags fa-2x mb-2"></i>

                                    <div>
                                        No asset categories found.
                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if($categories->hasPages())

            <div class="card-footer">

                {{ $categories->withQueryString()->links() }}

            </div>

        @endif

    </div>

</div>

@endsection