@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Asset CAPEX
            </h4>

            <p class="text-muted mb-0">
                Capital expenditure planning and tracking
            </p>
        </div>

        @can('capex.create')
            <a href="{{ route('admin.assets.capex.create') }}"
               class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Add CAPEX
            </a>
        @endcan

    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}

    <div class="card mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.assets.capex.index') }}">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="CAPEX code, title, category">

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

                            @foreach([
                                'planned',
                                'approved',
                                'in_progress',
                                'completed',
                                'cancelled'
                            ] as $status)

                                <option value="{{ $status }}"
                                    @selected(request('status') === $status)>
                                    {{ ucwords(str_replace('_', ' ', $status)) }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">
                            Priority
                        </label>

                        <select name="priority"
                                class="form-select">

                            <option value="">
                                All Priority
                            </option>

                            @foreach([
                                'low',
                                'medium',
                                'high',
                                'critical'
                            ] as $priority)

                                <option value="{{ $priority }}"
                                    @selected(request('priority') === $priority)>
                                    {{ ucfirst($priority) }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-2 d-flex align-items-end">

                        <button type="submit"
                                class="btn btn-secondary me-2">
                            <i class="fas fa-search"></i>
                            Filter
                        </button>

                        <a href="{{ route('admin.assets.capex.index') }}"
                           class="btn btn-light">
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- Table --}}

    <div class="card">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>CAPEX Code</th>
                            <th>Asset</th>
                            <th>Title</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Estimated Cost</th>
                            <th>Approved Budget</th>
                            <th>Actual Cost</th>
                            <th width="150">Action</th>
                        </tr>

                    </thead>

                    <tbody>

                    @forelse($capex as $item)

                        <tr>

                            <td>
                                {{ $capex->firstItem() + $loop->index }}
                            </td>

                            <td>
                                {{ $item->capex_code ?? '-' }}
                            </td>

                            <td>

                                @if($item->asset)

                                    <strong>
                                        {{ $item->asset->asset_name }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ $item->asset->asset_code }}
                                    </small>

                                @else
                                    -
                                @endif

                            </td>

                            <td>
                                {{ $item->title }}
                            </td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ ucfirst($item->priority) }}
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-info">
                                    {{ ucwords(str_replace('_', ' ', $item->status)) }}
                                </span>
                            </td>

                            <td>
                                ${{ number_format($item->estimated_cost, 2) }}
                            </td>

                            <td>
                                ${{ number_format($item->approved_budget, 2) }}
                            </td>

                            <td>
                                ${{ number_format($item->actual_cost, 2) }}
                            </td>

                            <td>

                                <div class="d-flex gap-1">

                                    <a href="{{ route(
                                        'admin.assets.capex.show',
                                        $item->id
                                    ) }}"
                                    class="btn btn-sm btn-info"
                                    title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @can('capex.edit')

                                        <a href="{{ route(
                                            'admin.assets.capex.edit',
                                            $item->id
                                        ) }}"
                                        class="btn btn-sm btn-warning"
                                        title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                    @endcan

                                    @can('capex.delete')

                                        <form method="POST"
                                              action="{{ route(
                                                  'admin.assets.capex.destroy',
                                                  $item->id
                                              ) }}"
                                              onsubmit="return confirm(
                                                  'Are you sure you want to delete this CAPEX?'
                                              );">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                        </form>

                                    @endcan

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="10"
                                class="text-center py-4">

                                No CAPEX records found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if($capex->hasPages())

            <div class="card-footer">

                {{ $capex->links() }}

            </div>

        @endif

    </div>

</div>

@endsection