@extends('layouts.app')

@section('title', 'Zones')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-layer-group me-2"></i>
                Zones
            </h4>

            <div class="text-muted">
                Manage floor zones and their configuration.
            </div>
        </div>

        @can('zones.create')

            <a href="{{ route('admin.assets.zones.create') }}"
               class="btn btn-primary">

                <i class="fas fa-plus me-1"></i>
                Add Zone

            </a>

        @endcan

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        SEARCH / FILTER
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0">
                <i class="fas fa-search me-2"></i>
                Search Zones
            </h6>

        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.assets.zones.index') }}">

                <div class="row g-3 align-items-end">

                    <div class="col-md-8">

                        <label for="search"
                               class="form-label fw-semibold">

                            Search

                        </label>

                        <input type="text"
                               name="search"
                               id="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Search by zone code or zone name...">

                    </div>


                    <div class="col-md-4">

                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="fas fa-search me-1"></i>
                                Search

                            </button>

                            @if(request('search'))

                                <a href="{{ route('admin.assets.zones.index') }}"
                                   class="btn btn-outline-secondary">

                                    <i class="fas fa-times me-1"></i>
                                    Clear

                                </a>

                            @endif

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        ZONE LIST
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        {{-- Card Header --}}
        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h6 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Zone List
                    </h6>

                    <small class="text-muted">
                        View and manage all floor zones.
                    </small>

                </div>

                @if(method_exists($zones, 'total'))

                    <span class="badge bg-secondary">
                        {{ $zones->total() }} Zones
                    </span>

                @endif

            </div>

        </div>


        {{-- Table --}}
        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="70">
                                ID
                            </th>

                            <th>
                                Floor
                            </th>

                            <th>
                                Zone Code
                            </th>

                            <th>
                                Zone Name
                            </th>

                            <th>
                                Description
                            </th>

                            <th width="110">
                                Status
                            </th>

                            <th width="180" class="text-center">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($zones as $zone)

                        <tr>

                            {{-- =================================================
                                ID
                            ================================================== --}}
                            <td>

                                <span class="badge bg-light text-dark border">
                                    #{{ $zone->id }}
                                </span>

                            </td>


                            {{-- =================================================
                                FLOOR
                            ================================================== --}}
                            <td>

                                @if($zone->floor)

                                    <div class="fw-semibold">
                                        {{ $zone->floor->floor_name }}
                                    </div>

                                    <small class="text-muted">

                                        {{ $zone->floor->floor_code ?? '' }}

                                        @if($zone->floor->building)
                                            · {{ $zone->floor->building->building_name }}
                                        @endif

                                    </small>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                ZONE CODE
                            ================================================== --}}
                            <td>

                                <span class="badge bg-light text-dark border">
                                    {{ $zone->zone_code }}
                                </span>

                            </td>


                            {{-- =================================================
                                ZONE NAME
                            ================================================== --}}
                            <td>

                                <a href="{{ route('admin.assets.zones.show', $zone->id) }}"
                                   class="text-decoration-none fw-semibold">

                                    {{ $zone->zone_name }}

                                </a>

                            </td>


                            {{-- =================================================
                                DESCRIPTION
                            ================================================== --}}
                            <td>

                                @if($zone->description)

                                    <span title="{{ $zone->description }}">
                                        {{ \Illuminate\Support\Str::limit($zone->description, 50) }}
                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                STATUS
                            ================================================== --}}
                            <td>

                                @if($zone->status === 'active' || $zone->status === 1 || $zone->status === '1')

                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        <i class="fas fa-times-circle me-1"></i>
                                        {{ ucfirst($zone->status ?? 'Inactive') }}
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                ACTIONS
                            ================================================== --}}
                            <td class="text-center">

                                <div class="d-inline-flex gap-1">

                                    {{-- View --}}
                                    <a href="{{ route('admin.assets.zones.show', $zone->id) }}"
                                       class="btn btn-sm btn-outline-info"
                                       title="View Zone">

                                        <i class="fas fa-eye"></i>

                                    </a>


                                    {{-- Edit --}}
                                    @can('zones.edit')

                                        <a href="{{ route('admin.assets.zones.edit', $zone->id) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit Zone">

                                            <i class="fas fa-edit"></i>

                                        </a>

                                    @endcan


                                    {{-- Delete --}}
                                    @can('zones.delete')

                                        <form method="POST"
                                              action="{{ route('admin.assets.zones.destroy', $zone->id) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this zone?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete Zone">

                                                <i class="fas fa-trash"></i>

                                            </button>

                                        </form>

                                    @endcan

                                </div>

                            </td>

                        </tr>

                    @empty

                        {{-- =================================================
                            EMPTY STATE
                        ================================================== --}}
                        <tr>

                            <td colspan="7"
                                class="text-center py-5">

                                <div class="text-muted">

                                    <i class="fas fa-layer-group fa-3x mb-3"></i>

                                    <h6>
                                        No Zones Found
                                    </h6>

                                    @if(request('search'))

                                        <p class="mb-3">
                                            No zones match your search criteria.
                                        </p>

                                        <a href="{{ route('admin.assets.zones.index') }}"
                                           class="btn btn-outline-secondary btn-sm">

                                            <i class="fas fa-times me-1"></i>
                                            Clear Search

                                        </a>

                                    @else

                                        <p class="mb-3">
                                            No zones have been created yet.
                                        </p>

                                        @can('zones.create')

                                            <a href="{{ route('admin.assets.zones.create') }}"
                                               class="btn btn-primary btn-sm">

                                                <i class="fas fa-plus me-1"></i>
                                                Create First Zone

                                            </a>

                                        @endcan

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =========================================================
            PAGINATION
        ========================================================== --}}
        @if(method_exists($zones, 'hasPages') && $zones->hasPages())

            <div class="card-footer bg-white">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div class="text-muted small">

                        Showing
                        <strong>{{ $zones->firstItem() }}</strong>
                        to
                        <strong>{{ $zones->lastItem() }}</strong>
                        of
                        <strong>{{ $zones->total() }}</strong>
                        zones

                    </div>

                    <div>
                        {{ $zones->withQueryString()->links() }}
                    </div>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection