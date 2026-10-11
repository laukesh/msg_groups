@extends('layouts.app')

@section('title', 'Industry Profiles')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">

        <div>
            <h4 class="mb-1">
                <i class="ri-bar-chart-grouped-line me-2"></i>
                Industry Profiles
            </h4>

            <p class="text-muted mb-0">
                Manage industry profiles for asset classification.
            </p>
        </div>

        @can('industry_profiles.create')
            <a
                href="{{ route('admin.assets.industry-profiles.create') }}"
                class="btn btn-primary"
            >
                <i class="ri-add-line me-1"></i>
                Add Industry Profile
            </a>
        @endcan

    </div>


    {{-- Messages --}}
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

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show">
            <i class="ri-information-line me-1"></i>
            {{ session('info') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif


    {{-- Search --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.assets.industry-profiles.index') }}"
            >

                <div class="row g-3 align-items-end">

                    <div class="col-md-6">

                        <label class="form-label">
                            Search
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="ri-search-line"></i>
                            </span>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                value="{{ request('search') }}"
                                placeholder="Search name, code or description..."
                            >

                        </div>

                    </div>


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


                    <div class="col-md-3">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="ri-search-line me-1"></i>
                                Search
                            </button>

                            <a
                                href="{{ route('admin.assets.industry-profiles.index') }}"
                                class="btn btn-outline-secondary"
                            >
                                <i class="ri-refresh-line"></i>
                                Reset
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                Industry Profile List
            </h5>

            <span class="badge bg-secondary">
                {{ $industryProfiles->total() }} Records
            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th width="70">#</th>
                            <th>Industry Profile</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th width="100">Assets</th>
                            <th width="110">Status</th>
                            <th width="90">Order</th>
                            <th width="170" class="text-end">
                                Actions
                            </th>
                        </tr>

                    </thead>

                    <tbody>

                    @forelse($industryProfiles as $industryProfile)

                        <tr>

                            <td>
                                {{ $industryProfiles->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <span class="fw-semibold">
                                    {{ $industryProfile->name }}
                                </span>
                            </td>

                            <td>

                                @if($industryProfile->code)

                                    <span class="badge bg-light text-dark border">
                                        {{ $industryProfile->code }}
                                    </span>

                                @else

                                    <span class="text-muted">—</span>

                                @endif

                            </td>

                            <td>

                                <span
                                    class="d-inline-block text-truncate"
                                    style="max-width: 300px;"
                                    title="{{ $industryProfile->description }}"
                                >
                                    {{ $industryProfile->description ?: '—' }}
                                </span>

                            </td>

                            <td>
                                <span class="badge bg-info-subtle text-info">
                                    {{ $industryProfile->assets_count ?? $industryProfile->assets()->count() }}
                                </span>
                            </td>

                            <td>

                                @if($industryProfile->status === 'Active')

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Inactive
                                    </span>

                                @endif

                            </td>

                            <td>
                                {{ $industryProfile->sort_order ?? 0 }}
                            </td>

                            <td class="text-end">

                                <div class="d-flex justify-content-end gap-1">

                                    @can('industry_profiles.view')

                                        <a
                                            href="{{ route('admin.assets.industry-profiles.show', $industryProfile->id) }}"
                                            class="btn btn-sm btn-outline-info"
                                            title="View"
                                        >
                                            <i class="ri-eye-line"></i>
                                        </a>

                                    @endcan


                                    @can('industry_profiles.edit')

                                        <a
                                            href="{{ route('admin.assets.industry-profiles.edit', $industryProfile->id) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            title="Edit"
                                        >
                                            <i class="ri-edit-line"></i>
                                        </a>

                                    @endcan


                                    @can('industry_profiles.delete')

                                        <form
                                            method="POST"
                                            action="{{ route('admin.assets.industry-profiles.destroy', $industryProfile->id) }}"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this industry profile?');"
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

                            <td colspan="8" class="text-center py-5">

                                <i class="ri-inbox-line fs-1 text-muted"></i>

                                <h6 class="mt-2">
                                    No Industry Profiles Found
                                </h6>

                                <p class="text-muted mb-3">
                                    No industry profile records match your search.
                                </p>

                                @can('industry_profiles.create')

                                    <a
                                        href="{{ route('admin.assets.industry-profiles.create') }}"
                                        class="btn btn-primary btn-sm"
                                    >
                                        <i class="ri-add-line me-1"></i>
                                        Add Industry Profile
                                    </a>

                                @endcan

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($industryProfiles->hasPages())

            <div class="card-footer bg-white">
                {{ $industryProfiles->links() }}
            </div>

        @endif

    </div>

</div>

@endsection