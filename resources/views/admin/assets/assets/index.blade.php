@extends('layouts.app')

@section('title', 'Assets')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="fas fa-boxes me-2"></i>Assets
            </h4>
            <div class="text-muted">Manage mall assets.</div>
        </div>

        @can('assets.create')
            <a href="{{ route('admin.assets.assets.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                Add Asset
            </a>
        @endcan
    </div>


    {{-- Success Message --}}
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


    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Search & Filter --}}
    <div class="card mb-4 border-0 shadow-sm">

        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-filter me-1"></i>
                Search & Filter
            </h5>
        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.assets.assets.index') }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-lg-5 col-md-6">
                        <label for="search" class="form-label">
                            Search
                        </label>

                        <input type="text"
                               id="search"
                               name="search"
                               class="form-control"
                               placeholder="Asset code, name, serial number..."
                               value="{{ request('search') }}">
                    </div>


                    {{-- Status --}}
                    <div class="col-lg-3 col-md-6">

                        <label for="status" class="form-label">
                            Status
                        </label>

                        <select id="status"
                                name="status"
                                class="form-select">

                            <option value="">
                                All Statuses
                            </option>

                            <option value="1"
                                {{ request('status') === '1' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0"
                                {{ request('status') === '0' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- Category --}}
                    <div class="col-lg-2 col-md-6">

                        <label for="asset_category" class="form-label">
                            Category
                        </label>

                        <select id="asset_category"
                                name="asset_category"
                                class="form-select">

                            <option value="">
                                All Categories
                            </option>

                            @foreach($assetCategories ?? [] as $id => $name)

                                <option value="{{ $id }}"
                                    {{ (string) request('asset_category') === (string) $id ? 'selected' : '' }}>

                                    {{ $name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-lg-2 col-md-6 d-flex align-items-end gap-2">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="fas fa-search me-1"></i>
                            Search

                        </button>

                        <a href="{{ route('admin.assets.assets.index') }}"
                           class="btn btn-secondary">

                            <i class="fas fa-sync-alt me-1"></i>
                            Clear

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Asset List --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                <i class="fas fa-list me-1"></i>
                Asset List
            </h5>

            <span class="text-muted">
                Total: {{ $items->total() }}
            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="60">ID</th>
                            <th>Asset Code</th>
                            <th>Asset Name</th>
                            <th>Category</th>
                            <th>Asset Type</th>
                            <th>Serial Number</th>
                            <th>Unit</th>
                            <th>Status</th>
                            <th width="180">Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($items as $item)

                        <tr>

                            {{-- ID --}}
                            <td>
                                {{ $item->id }}
                            </td>


                            {{-- Asset Code --}}
                            <td>

                                <a href="{{ route('admin.assets.assets.show', $item->id) }}"
                                   class="text-decoration-none fw-semibold">

                                    {{ $item->asset_code ?: '-' }}

                                </a>

                            </td>


                            {{-- Asset Name --}}
                            <td>

                                {{ \Illuminate\Support\Str::limit(
                                    (string) $item->asset_name,
                                    50
                                ) ?: '-' }}

                            </td>


                            {{-- Category Name --}}
                            <td>

                                @if($item->assetCategory)

                                    {{ $item->assetCategory->category_name }}

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- Asset Type --}}
                            <td>
                                {{ $item->asset_type ?: '-' }}
                            </td>


                            {{-- Serial Number --}}
                            <td>
                                {{ $item->serial_number ?: '-' }}
                            </td>


                            {{-- Unit --}}
                            <td>

                                {{ optional($item->unit)->unit_no
                                    ?? optional($item->unit)->name
                                    ?? '-' }}

                            </td>


                            {{-- Status --}}
                            <td> 

                                @if((int) $item->status === 1)

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


                            {{-- Actions --}}
                            <td>

                                {{-- View --}}
                                <a href="{{ route('admin.assets.assets.show', $item->id) }}"
                                   class="btn btn-sm btn-info"
                                   title="View">

                                    <i class="fas fa-eye"></i>

                                </a>


                                {{-- Edit --}}
                                @can('assets.edit')

                                    <a href="{{ route('admin.assets.assets.edit', $item->id) }}"
                                       class="btn btn-sm btn-primary"
                                       title="Edit">

                                        <i class="fas fa-edit"></i>

                                    </a>

                                @endcan


                                {{-- Delete --}}
                                @can('assets.delete')

                                    <form method="POST"
                                          action="{{ route('admin.assets.assets.destroy', $item->id) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this asset?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Delete">

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </form>

                                @endcan

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9"
                                class="text-center py-5">

                                <div class="text-muted">

                                    <i class="fas fa-box-open fa-2x mb-2"></i>

                                    <div>
                                        No assets found.
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
        @if($items->hasPages())

            <div class="card-footer bg-white">

                {{ $items->withQueryString()->links() }}

            </div>

        @endif

    </div>

</div>
@endsection