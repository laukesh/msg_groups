@extends('layouts.app')

@section('content')

<div class="container-fluid py-3">

    {{-- ================================================================
        PAGE HEADER
    ================================================================= --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h4 class="mb-1">
                <i class="ri-building-4-line me-1"></i>
                Industry Operating Profiles
            </h4>

            <p class="text-muted mb-0">
                Manage operating standards for different asset industries.
            </p>
        </div>

        @can('industry_profiles.create')
            <a href="{{ route('admin.assets.operations.industry-profiles.create') }}"
               class="btn btn-primary">

                <i class="ri-add-line me-1"></i>
                Add Industry Profile

            </a>
        @endcan

    </div>


    {{-- ================================================================
        SUCCESS MESSAGE
    ================================================================= --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="ri-check-line me-1"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>

        </div>

    @endif


    {{-- ================================================================
        ERROR MESSAGE
    ================================================================= --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <i class="ri-error-warning-line me-1"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>

        </div>

    @endif


    {{-- ================================================================
        VALIDATION ERRORS
    ================================================================= --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <div class="fw-semibold mb-2">
                <i class="ri-error-warning-line me-1"></i>
                Please correct the following errors:
            </div>

            <ul class="mb-0 ps-3">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>

        </div>

    @endif


    {{-- ================================================================
        FILTERS
    ================================================================= --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex align-items-center">

                <i class="ri-filter-3-line me-2"></i>

                <span class="fw-semibold">
                    Filter Industry Profiles
                </span>

            </div>

        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.assets.operations.industry-profiles.index') }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-lg-5 col-md-6">

                        <label for="search"
                               class="form-label">

                            Search

                        </label>

                        <input type="text"
                               id="search"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Profile code, name or industry">

                    </div>


                    {{-- Industry --}}
                    <div class="col-lg-3 col-md-6">

                        <label for="industry_type"
                               class="form-label">

                            Industry

                        </label>

                        <select id="industry_type"
                                name="industry_type"
                                class="form-select">

                            <option value="">
                                All Industries
                            </option>

                            @foreach([
                                'Commercial / Retail',
                                'Hospitality',
                                'Residential / Housing',
                                'Industrial / Logistics',
                                'Community / Institutional'
                            ] as $type)

                                <option value="{{ $type }}"
                                    @selected(request('industry_type') === $type)>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="col-lg-2 col-md-6">

                        <label for="status"
                               class="form-label">

                            Status

                        </label>

                        <select id="status"
                                name="status"
                                class="form-select">

                            <option value="">
                                All
                            </option>

                            <option value="active"
                                @selected(request('status') === 'active')>

                                Active

                            </option>

                            <option value="inactive"
                                @selected(request('status') === 'inactive')>

                                Inactive

                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-lg-2 col-md-6 d-flex align-items-end">

                        <div class="d-flex gap-2 w-100">

                            <button type="submit"
                                    class="btn btn-primary flex-grow-1">

                                <i class="ri-search-line me-1"></i>
                                Search

                            </button>

                            <a href="{{ route('admin.assets.operations.industry-profiles.index') }}"
                               class="btn btn-light border"
                               title="Reset filters">

                                <i class="ri-refresh-line"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ================================================================
        PROFILE LIST
    ================================================================= --}}
    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div class="fw-semibold">

                    <i class="ri-list-check-2 me-1"></i>
                    Industry Profiles

                </div>

                <span class="text-muted small">

                    {{ $profiles->total() }}
                    {{ Str::plural('profile', $profiles->total()) }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-3">
                                #
                            </th>

                            <th>
                                Profile
                            </th>

                            <th>
                                Industry
                            </th>

                            <th>
                                Occupancy
                            </th>

                            <th>
                                Performance
                            </th>

                            <th>
                                Lifecycle
                            </th>

                            <th>
                                Assets
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end pe-3">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($profiles as $profile)

                            <tr>

                                {{-- Number --}}
                                <td class="ps-3">

                                    {{ $profiles->firstItem() + $loop->index }}

                                </td>


                                {{-- Profile --}}
                                <td>

                                    <div class="fw-semibold">

                                        {{ $profile->profile_name ?: '—' }}

                                    </div>

                                    @if($profile->profile_code)

                                        <small class="text-muted">

                                            {{ $profile->profile_code }}

                                        </small>

                                    @endif

                                </td>


                                {{-- Industry --}}
                                <td>

                                    @if($profile->industry_type)

                                        <span class="badge bg-light text-dark border">

                                            {{ $profile->industry_type }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Occupancy --}}
                                <td>

                                    @if($profile->occupancy_target !== null)

                                        <span class="fw-medium">

                                            {{ number_format(
                                                (float) $profile->occupancy_target,
                                                2
                                            ) }}%

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Performance --}}
                                <td>

                                    @if($profile->performance_target !== null)

                                        <span class="fw-medium">

                                            {{ number_format(
                                                (float) $profile->performance_target,
                                                2
                                            ) }}%

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Lifecycle --}}
                                <td>

                                    @if($profile->default_asset_life_years !== null)

                                        <span class="fw-medium">

                                            {{ number_format(
                                                (float) $profile->default_asset_life_years,
                                                2
                                            ) }}

                                            yrs

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Assets --}}
                                <td>

                                    <span class="badge bg-primary">

                                        {{ number_format(
                                            (int) ($profile->asset_count ?? 0)
                                        ) }}

                                    </span>

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if($profile->is_active)

                                        <span class="badge bg-success">

                                            <i class="ri-checkbox-circle-line me-1"></i>
                                            Active

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            <i class="ri-close-circle-line me-1"></i>
                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="pe-3">

                                    <div class="d-flex justify-content-end gap-1">

                                        {{-- View --}}
                                        @can('industry_profiles.view')

                                            <a href="{{ route(
                                                'admin.assets.operations.industry-profiles.show',
                                                $profile
                                            ) }}"
                                               class="btn btn-sm btn-outline-info"
                                               title="View profile">

                                                <i class="ri-eye-line"></i>

                                            </a>

                                        @endcan


                                        {{-- Edit --}}
                                        @can('industry_profiles.edit')

                                            <a href="{{ route(
                                                'admin.assets.operations.industry-profiles.edit',
                                                $profile
                                            ) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit profile">

                                                <i class="ri-edit-line"></i>

                                            </a>

                                        @endcan


                                        {{-- Delete --}}
                                        @can('industry_profiles.delete')

                                            @if((int) ($profile->asset_count ?? 0) === 0)

                                                <form method="POST"
                                                      action="{{ route(
                                                          'admin.assets.operations.industry-profiles.destroy',
                                                          $profile
                                                      ) }}"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this industry profile?');">

                                                    @csrf

                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Delete profile">

                                                        <i class="ri-delete-bin-line"></i>

                                                    </button>

                                                </form>

                                            @else

                                                <button type="button"
                                                        class="btn btn-sm btn-outline-secondary"
                                                        title="Cannot delete: profile is assigned to assets"
                                                        disabled>

                                                    <i class="ri-delete-bin-line"></i>

                                                </button>

                                            @endif

                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="ri-building-4-line fs-1"></i>

                                        <h6 class="mt-3 mb-1">
                                            No industry profiles found
                                        </h6>

                                        <p class="mb-0 small">
                                            Try changing your search or filter criteria.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ============================================================
            PAGINATION
        ============================================================= --}}
        @if($profiles->hasPages())

            <div class="card-footer bg-white">

                {{ $profiles->links() }}

            </div>

        @endif

    </div>

</div>

@endsection