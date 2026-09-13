@extends('layouts.app')

@section('title', 'Assets')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-boxes me-2"></i>
                Assets
            </h4>

            <div class="text-muted">
                Manage assets and monitor their economic performance
            </div>
        </div>

        <a href="{{ route('admin.assets.assets.create') }}"
           class="btn btn-primary">

            <i class="fas fa-plus"></i>
            New Asset

        </a>

    </div>


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


    {{-- Search --}}

    <div class="card mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.assets.assets.index') }}">

                <div class="row g-3">

                    <div class="col-md-5">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Asset code, name, type...">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="Active"
                                @selected(request('status') === 'Active')>
                                Active
                            </option>

                            <option value="Inactive"
                                @selected(request('status') === 'Inactive')>
                                Inactive
                            </option>

                            <option value="Disposed"
                                @selected(request('status') === 'Disposed')>
                                Disposed
                            </option>

                        </select>

                    </div>


                    <div class="col-md-4 d-flex align-items-end gap-2">

                        <button class="btn btn-primary">

                            <i class="fas fa-search"></i>
                            Search

                        </button>

                        <a href="{{ route('admin.assets.assets.index') }}"
                           class="btn btn-secondary">

                            <i class="fas fa-redo"></i>
                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Table --}}

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">

                    <i class="fas fa-boxes me-1"></i>

                    Asset List

                </h5>

                <span class="badge bg-primary">

                     Total: {{ $items->total() }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="60">#</th>
                            <th>Asset Code</th>
                            <th>Asset Name</th>
                            <th>Type</th>
                            <th>Purchase Cost</th>
                            <th>Current Value</th>
                            <th>Status</th>
                            <th width="160">Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($items as $asset)

                        <tr>

                            <td>
                                {{ $asset->id }}
                            </td>

                            <td>

                                <a href="{{ route(
                                    'admin.assets.assets.show',
                                    $asset->id
                                ) }}"
                                   class="fw-semibold text-decoration-none">

                                    {{ $asset->asset_code }}

                                </a>

                            </td>

                            <td>
                                {{ $asset->asset_name }}
                            </td>

                            <td>
                                {{ $asset->asset_type ?? '-' }}
                            </td>

                            <td>

                                ₹{{ number_format(
                                    $asset->purchase_cost,
                                    2
                                ) }}

                            </td>

                            <td>

                                ₹{{ number_format(
                                    $asset->current_value,
                                    2
                                ) }}

                            </td>

                            <td>

                                @if($asset->status === 'Active')

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @elseif($asset->status === 'Inactive')

                                    <span class="badge bg-secondary">
                                        Inactive
                                    </span>

                                @elseif($asset->status === 'Disposed')

                                    <span class="badge bg-danger">
                                        Disposed
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ $asset->status }}
                                    </span>

                                @endif

                            </td>

                            <td>

                                <div class="d-flex gap-1">

                                    <a href="{{ route(
                                        'admin.assets.assets.show',
                                        $asset->id
                                    ) }}"
                                       class="btn btn-sm btn-info"
                                       title="View">

                                        <i class="fas fa-eye"></i>

                                    </a>

                                    <a href="{{ route(
                                        'admin.assets.assets.edit',
                                        $asset->id
                                    ) }}"
                                       class="btn btn-sm btn-primary"
                                       title="Edit">

                                        <i class="fas fa-pen"></i>

                                    </a>

                                    <form method="POST"
                                          action="{{ route(
                                              'admin.assets.assets.destroy',
                                              $asset->id
                                          ) }}"
                                          onsubmit="return confirm(
                                              'Are you sure you want to delete this asset?'
                                          );">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Delete">

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8"
                                class="text-center py-5">

                                <i class="fas fa-boxes fa-3x text-muted mb-3"></i>

                                <h5>
                                    No Assets Found
                                </h5>

                                <p class="text-muted">
                                    No assets match your search criteria.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($items->hasPages())

            <div class="card-footer">

                {{ $items->links() }}

            </div>

        @endif

    </div>

</div>

@endsection