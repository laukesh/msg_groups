@extends('layouts.app')

@section('title', 'Revenue Statuses')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">

        <div>

            <h4 class="mb-1">
                <i class="ri-money-dollar-circle-line me-1"></i>
                Revenue Statuses
            </h4>

            <p class="text-muted mb-0">
                Manage revenue and non-revenue asset classifications.
            </p>

        </div>

        @can('revenue_statuses.create')

            <a
                href="{{ route('admin.assets.revenue-statuses.create') }}"
                class="btn btn-primary"
            >
                <i class="ri-add-line me-1"></i>
                Add Revenue Status
            </a>

        @endcan

    </div>

    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="ri-checkbox-circle-line me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif

    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="ri-error-warning-line me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif

    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.assets.revenue-statuses.index') }}"
            >

                <div class="row g-3 align-items-end">

                    {{-- Search --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Search by name, code or description..."
                        >

                    </div>

                    {{-- Status --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="Active"
                                {{ request('status') === 'Active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="Inactive"
                                {{ request('status') === 'Inactive' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>

                        </select>

                    </div>

                    {{-- Buttons --}}
                    <div class="col-md-3 d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary flex-fill"
                        >
                            <i class="ri-search-line me-1"></i>
                            Search
                        </button>

                        <a
                            href="{{ route('admin.assets.revenue-statuses.index') }}"
                            class="btn btn-light border"
                            title="Reset"
                        >
                            <i class="ri-refresh-line"></i>
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                Revenue Status List
            </h5>

            <span class="badge bg-secondary">
                {{ $revenueStatuses->total() }} Records
            </span>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="60">
                                #
                            </th>

                            <th>
                                Name
                            </th>

                            <th>
                                Code
                            </th>

                            <th>
                                Description
                            </th>

                            <th class="text-center">
                                Assets
                            </th>

                            <th class="text-center">
                                Status
                            </th>

                            <th class="text-center">
                                Sort
                            </th>

                            <th class="text-end">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($revenueStatuses as $revenueStatus)

                            <tr>

                                {{-- Number --}}
                                <td>
                                    {{ $revenueStatuses->firstItem() + $loop->index }}
                                </td>

                                {{-- Name --}}
                                <td>

                                    <strong>
                                        {{ $revenueStatus->name }}
                                    </strong>

                                </td>

                                {{-- Code --}}
                                <td>

                                    @if($revenueStatus->code)

                                        <span class="badge bg-light text-dark border">
                                            {{ $revenueStatus->code }}
                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>

                                {{-- Description --}}
                                <td>

                                    <span
                                        class="text-muted d-inline-block text-truncate"
                                        style="max-width:260px;"
                                    >
                                        {{ $revenueStatus->description ?: '—' }}
                                    </span>

                                </td>

                                {{-- Assets --}}
                                <td class="text-center">

                                    <span class="badge bg-info">
                                        {{ $revenueStatus->assets_count }}
                                    </span>

                                </td>

                                {{-- Status --}}
                                <td class="text-center">

                                    @if($revenueStatus->status === 'Active')

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Inactive
                                        </span>

                                    @endif

                                </td>

                                {{-- Sort --}}
                                <td class="text-center">
                                    {{ $revenueStatus->sort_order }}
                                </td>

                                {{-- Actions --}}
                                <td>

                                    <div class="d-flex justify-content-end gap-1">

                                        @can('revenue_statuses.view')

                                            <a
                                                href="{{ route('admin.assets.revenue-statuses.show', $revenueStatus->id) }}"
                                                class="btn btn-sm btn-outline-info"
                                                title="View"
                                            >
                                                <i class="ri-eye-line"></i>
                                            </a>

                                        @endcan

                                        @can('revenue_statuses.edit')

                                            <a
                                                href="{{ route('admin.assets.revenue-statuses.edit', $revenueStatus->id) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="Edit"
                                            >
                                                <i class="ri-edit-line"></i>
                                            </a>

                                        @endcan

                                        @can('revenue_statuses.delete')

                                            <form
                                                method="POST"
                                                action="{{ route('admin.assets.revenue-statuses.destroy', $revenueStatus->id) }}"
                                                onsubmit="return confirm('Are you sure you want to delete this revenue status?');"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete"
                                                >
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>

                                            </form>

                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5 text-muted"
                                >

                                    <i class="ri-inbox-line fs-2 d-block mb-2"></i>

                                    No revenue status records found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if($revenueStatuses->hasPages())

            <div class="card-footer bg-white">

                {{ $revenueStatuses->links() }}

            </div>

        @endif

    </div>

</div>

@endsection