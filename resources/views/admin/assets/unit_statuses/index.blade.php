@extends('layouts.app')

@section('title', 'Unit Statuses')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="ri-checkbox-circle-line"></i>
                Unit Statuses
            </h1>
            <p class="text-muted mb-0">Manage unit statuses used for mall units.</p>
        </div>

        @can('unit_statuses.create')
            <a href="{{ route('admin.assets.unit-statuses.create') }}" class="btn btn-primary">
                <i class="ri-add-line"></i>
                Add Unit Status
            </a>
        @endcan
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="ri-checkbox-circle-line"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="ri-error-warning-line"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <i class="ri-alert-line"></i>
            <ul class="mb-0 d-inline-block">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Search / Filter --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.assets.unit-statuses.index') }}">
                <div class="row g-2">

                    {{-- Search --}}
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-search-line"></i>
                            </span>
                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Search status name..."
                                value="{{ request('search') }}"
                            >
                        </div>
                    </div>

                    {{-- Active Filter --}}
                    <div class="col-md-3">
                        <select name="is_active" class="form-select">
                            <option value="">All Status</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-search-line"></i>
                            Search
                        </button>
                        <a href="{{ route('admin.assets.unit-statuses.index') }}" class="btn btn-secondary">
                            <i class="ri-refresh-line"></i>
                            Clear
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- Unit Status Table --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="ri-list-check-2"></i>
                Unit Status List
            </h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Status Name</th>
                            <th>Color</th>
                            <th>Description</th>
                            <th>Sort Order</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Updated By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($statuses as $status)
                        <tr>
                            {{-- ID --}}
                            <td>{{ $status->id }}</td>

                            {{-- Status Name --}}
                            <td>
                                <a href="{{ route('admin.assets.unit-statuses.show', $status->id) }}">
                                    <strong>{{ $status->status_name }}</strong>
                                </a>
                            </td>

                            {{-- Color --}}
                            <td>
                                @if($status->color_code)
                                    <span
                                        style="display:inline-block; width:22px; height:22px;
                                               background-color: {{ $status->color_code }};
                                               border:1px solid #ccc; border-radius:4px;
                                               vertical-align:middle; margin-right:6px;"
                                        title="{{ $status->color_code }}"
                                    ></span>
                                    <code>{{ $status->color_code }}</code>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- Description --}}
                            <td>{{ \Illuminate\Support\Str::limit($status->description, 60) ?: '-' }}</td>

                            {{-- Sort Order --}}
                            <td>
                                <span class="badge bg-light text-dark">{{ $status->sort_order }}</span>
                            </td>

                            {{-- Active Status --}}
                            <td>
                                @if($status->is_active)
                                    <span class="badge bg-success">
                                        <i class="ri-checkbox-circle-line"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="ri-close-circle-line"></i>
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            {{-- Created By --}}
                            <td>{{ $status->creator->name ?? '-' }}</td>

                            {{-- Updated By --}}
                            <td>{{ $status->updater->name ?? '-' }}</td>

                            {{-- Actions --}}
                            <td class="text-nowrap">
                                {{-- View --}}
                                <a href="{{ route('admin.assets.unit-statuses.show', $status->id) }}" class="btn btn-sm btn-info">
                                    <i class="ri-eye-line"></i>
                                    
                                </a>

                                {{-- Edit --}}
                                @can('unit_statuses.edit')
                                    <a href="{{ route('admin.assets.unit-statuses.edit', $status->id) }}" class="btn btn-sm btn-primary">
                                        <i class="ri-edit-line"></i>
                                        
                                    </a>
                                @endcan

                                {{-- Delete --}}
                                @can('unit_statuses.delete')
                                    <form
                                        method="POST"
                                        action="{{ route('admin.assets.unit-statuses.destroy', $status->id) }}"
                                        class="d-inline"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this unit status?')"
                                        >
                                            <i class="ri-delete-bin-line"></i>
                                            
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="ri-inbox-line" style="font-size:1.5rem;"></i>
                                    <div>No unit statuses found.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($statuses->hasPages())
                <div class="p-3">
                    {{ $statuses->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection