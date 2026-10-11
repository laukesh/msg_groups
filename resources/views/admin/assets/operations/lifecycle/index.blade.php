@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="ri-history-line me-1"></i>
                Asset Lifecycle Planning
            </h4>

            <p class="text-muted mb-0">
                Monitor asset lifecycle, renewal and replacement planning.
            </p>
        </div>

        @can('lifecycle.create')
            <a href="{{ route('admin.assets.operations.lifecycle.create') }}"
               class="btn btn-primary">

                <i class="ri-add-line me-1"></i>
                Add Lifecycle Plan

            </a>
        @endcan

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="ri-check-line me-1"></i>

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

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Filters --}}
    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.assets.operations.lifecycle.index') }}">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Search Asset
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Asset code or asset name">

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Lifecycle Stage
                        </label>

                        <select name="lifecycle_stage"
                                class="form-select">

                            <option value="">
                                All Stages
                            </option>

                            @foreach([
                                'Planning',
                                'Acquisition',
                                'Active',
                                'Maintenance',
                                'Renewal',
                                'Replacement',
                                'Disposal',
                                'Disposed'
                            ] as $stage)

                                <option value="{{ $stage }}"
                                    @selected(request('lifecycle_stage') === $stage)>
                                    {{ $stage }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="lifecycle_status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            @foreach([
                                'Planned',
                                'In Progress',
                                'Active',
                                'Due',
                                'Completed',
                                'Cancelled'
                            ] as $status)

                                <option value="{{ $status }}"
                                    @selected(request('lifecycle_status') === $status)>
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Priority
                        </label>

                        <select name="priority"
                                class="form-select">

                            <option value="">
                                All Priority
                            </option>

                            @foreach([
                                'Low',
                                'Medium',
                                'High',
                                'Critical'
                            ] as $priority)

                                <option value="{{ $priority }}"
                                    @selected(request('priority') === $priority)>
                                    {{ $priority }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-2 d-flex align-items-end">

                        <div class="d-flex gap-2 w-100">

                            <button type="submit"
                                    class="btn btn-primary flex-fill">

                                <i class="ri-search-line me-1"></i>
                                Search

                            </button>

                            <a href="{{ route('admin.assets.operations.lifecycle.index') }}"
                               class="btn btn-light">

                                <i class="ri-refresh-line"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Lifecycle Table --}}
    <div class="card shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Asset</th>

                            <th>Stage</th>

                            <th>Status</th>

                            <th>Priority</th>

                            <th>Lifecycle Score</th>

                            <th>Remaining Life</th>

                            <th>Replacement Date</th>

                            <th>Recommended Action</th>

                            <th class="text-end">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($lifecycles as $lifecycle)

                            <tr>

                                <td>
                                    {{ $lifecycles->firstItem() + $loop->index }}
                                </td>


                                <td>

                                    @if($lifecycle->asset)

                                        <div class="fw-semibold">
                                            {{ $lifecycle->asset->asset_name }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $lifecycle->asset->asset_code }}
                                        </small>

                                    @else

                                        <span class="text-muted">
                                            Asset not found
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @php
                                        $stageClass = match($lifecycle->lifecycle_stage) {
                                            'Planning' => 'bg-info',
                                            'Acquisition' => 'bg-primary',
                                            'Active' => 'bg-success',
                                            'Maintenance' => 'bg-warning text-dark',
                                            'Renewal' => 'bg-secondary',
                                            'Replacement' => 'bg-danger',
                                            'Disposal' => 'bg-dark',
                                            'Disposed' => 'bg-dark',
                                            default => 'bg-secondary',
                                        };
                                    @endphp

                                    <span class="badge {{ $stageClass }}">
                                        {{ $lifecycle->lifecycle_stage }}
                                    </span>

                                </td>


                                <td>

                                    @php
                                        $statusClass = match($lifecycle->lifecycle_status) {
                                            'Planned' => 'bg-info',
                                            'In Progress' => 'bg-warning text-dark',
                                            'Active' => 'bg-success',
                                            'Due' => 'bg-danger',
                                            'Completed' => 'bg-success',
                                            'Cancelled' => 'bg-secondary',
                                            default => 'bg-secondary',
                                        };
                                    @endphp

                                    <span class="badge {{ $statusClass }}">
                                        {{ $lifecycle->lifecycle_status }}
                                    </span>

                                </td>


                                <td>

                                    @php
                                        $priorityClass = match($lifecycle->priority) {
                                            'Low' => 'bg-success',
                                            'Medium' => 'bg-info',
                                            'High' => 'bg-warning text-dark',
                                            'Critical' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp

                                    <span class="badge {{ $priorityClass }}">
                                        {{ $lifecycle->priority }}
                                    </span>

                                </td>


                                <td>

                                    @if($lifecycle->lifecycle_score !== null)

                                        <span class="fw-semibold">
                                            {{ number_format($lifecycle->lifecycle_score, 2) }}
                                        </span>
                                        / 100

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if($lifecycle->remaining_life_years !== null)

                                        {{ number_format($lifecycle->remaining_life_years, 2) }}
                                        years

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if($lifecycle->planned_replacement_date)

                                        {{ $lifecycle->planned_replacement_date->format('d M Y') }}

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if($lifecycle->recommended_action)

                                        <span class="badge bg-light text-dark border">
                                            {{ $lifecycle->recommended_action }}
                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                <td class="text-end">

                                    <div class="d-flex justify-content-end gap-1">

                                        @can('lifecycle.view')

                                            <a href="{{ route('admin.assets.operations.lifecycle.show', $lifecycle) }}"
                                               class="btn btn-sm btn-outline-info"
                                               title="View">

                                                <i class="ri-eye-line"></i>

                                            </a>

                                        @endcan


                                        @can('lifecycle.edit')

                                            <a href="{{ route('admin.assets.operations.lifecycle.edit', $lifecycle) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit">

                                                <i class="ri-edit-line"></i>

                                            </a>

                                        @endcan


                                        @can('lifecycle.delete')

                                            <form method="POST"
                                                  action="{{ route('admin.assets.operations.lifecycle.destroy', $lifecycle) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this lifecycle record?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Delete">

                                                    <i class="ri-delete-bin-line"></i>

                                                </button>

                                            </form>

                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="10"
                                    class="text-center py-5">

                                    <i class="ri-history-line fs-1 text-muted"></i>

                                    <h6 class="mt-3">
                                        No lifecycle records found
                                    </h6>

                                    <p class="text-muted mb-0">
                                        Create a lifecycle plan for an asset.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($lifecycles->hasPages())

            <div class="card-footer">

                {{ $lifecycles->links() }}

            </div>

        @endif

    </div>

</div>

@endsection