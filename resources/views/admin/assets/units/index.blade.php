@extends('layouts.app')

@section('title', 'Units')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-store me-2"></i>
                Units
            </h4>

            <div class="text-muted">
                Manage mall units, shops, locations and rental information.
            </div>
        </div>

        @can('units.create')

            <a href="{{ route('admin.assets.units.create') }}"
               class="btn btn-primary">

                <i class="fas fa-plus me-1"></i>
                Create Unit

            </a>

        @endcan

    </div>


    {{-- =========================================================
        FLASH MESSAGES
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
        SEARCH
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0">
                <i class="fas fa-search me-2"></i>
                Search Units
            </h6>

        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.assets.units.index') }}">

                <div class="row g-3 align-items-end">

                    <div class="col-lg-8 col-md-7">

                        <label for="search"
                               class="form-label fw-semibold">

                            Search

                        </label>

                        <input type="text"
                               id="search"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Search unit no, shop name or code...">

                    </div>

                    <div class="col-lg-4 col-md-5">

                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="fas fa-search me-1"></i>
                                Search

                            </button>

                            @if(request('search'))

                                <a href="{{ route('admin.assets.units.index') }}"
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
        UNITS TABLE
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h6 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Unit List
                    </h6>

                    <small class="text-muted">
                        View and manage all registered units.
                    </small>

                </div>

                @if(method_exists($units, 'total'))

                    <span class="badge bg-secondary">
                        {{ $units->total() }} Units
                    </span>

                @endif

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="70">
                                #
                            </th>

                            <th>
                                Unit No
                            </th>

                            <th>
                                Shop Name
                            </th>

                            <th>
                                Mall
                            </th>

                            <th>
                                Building
                            </th>

                            <th>
                                Floor
                            </th>

                            <th>
                                Zone
                            </th>

                            <th>
                                Unit Status
                            </th>

                            <th>
                                Record Status
                            </th>

                            <th width="180"
                                class="text-center">

                                Actions

                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($units as $unit)

                        <tr>

                            {{-- ID --}}
                            <td>

                                <span class="badge bg-light text-dark border">
                                    #{{ $unit->id }}
                                </span>

                            </td>


                            {{-- Unit No --}}
                            <td>

                                <a href="{{ route('admin.assets.units.show', $unit->id) }}"
                                   class="fw-semibold text-decoration-none">

                                    {{ $unit->unit_no }}

                                </a>

                            </td>


                            {{-- Shop Name --}}
                            <td>

                                {{ $unit->shop_name ?: '—' }}

                            </td>


                            {{-- Mall --}}
                            <td>

                                @if($unit->mall)

                                    <span class="fw-semibold">
                                        {{ $unit->mall->mall_name }}
                                    </span>

                                    @if($unit->mall->mall_code ?? false)

                                        <small class="text-muted d-block">
                                            {{ $unit->mall->mall_code }}
                                        </small>

                                    @endif

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Building --}}
                            <td>

                                {{ $unit->building?->building_name ?? '—' }}

                            </td>


                            {{-- Floor --}}
                            <td>

                                @if($unit->floor)

                                    {{ $unit->floor->floor_name }}

                                    @if($unit->floor->floor_code ?? false)

                                        <small class="text-muted d-block">
                                            {{ $unit->floor->floor_code }}
                                        </small>

                                    @endif

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Zone --}}
                            <td>

                                {{ $unit->zone?->zone_name ?? '—' }}

                            </td>


                            {{-- Unit Status --}}
                            <td>

                                @if($unit->unitStatus)

                                    <span class="badge bg-info">
                                        {{ $unit->unitStatus->status_name }}
                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Record Status --}}
                            <td>

                                @if(
                                    $unit->status === 'active' ||
                                    $unit->status === 1 ||
                                    $unit->status === '1'
                                )

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
                            <td class="text-center">

                                <div class="d-inline-flex gap-1">

                                    {{-- View --}}
                                    <a href="{{ route('admin.assets.units.show', $unit->id) }}"
                                       class="btn btn-sm btn-outline-info"
                                       title="View Unit">

                                        <i class="fas fa-eye"></i>

                                    </a>


                                    {{-- Edit --}}
                                    @can('units.edit')

                                        <a href="{{ route('admin.assets.units.edit', $unit->id) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit Unit">

                                            <i class="fas fa-edit"></i>

                                        </a>

                                    @endcan


                                    {{-- Delete --}}
                                    @can('units.delete')

                                        <form method="POST"
                                              action="{{ route('admin.assets.units.destroy', $unit->id) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this unit?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete Unit">

                                                <i class="fas fa-trash"></i>

                                            </button>

                                        </form>

                                    @endcan

                                </div>

                            </td>

                        </tr>

                    @empty

                        {{-- Empty State --}}
                        <tr>

                            <td colspan="10"
                                class="text-center py-5">

                                <div class="text-muted">

                                    <i class="fas fa-store fa-3x mb-3"></i>

                                    <h6>
                                        No Units Found
                                    </h6>

                                    @if(request('search'))

                                        <p class="mb-3">
                                            No units match your search criteria.
                                        </p>

                                        <a href="{{ route('admin.assets.units.index') }}"
                                           class="btn btn-outline-secondary btn-sm">

                                            <i class="fas fa-times me-1"></i>
                                            Clear Search

                                        </a>

                                    @else

                                        <p class="mb-3">
                                            No units have been created yet.
                                        </p>

                                        @can('units.create')

                                            <a href="{{ route('admin.assets.units.create') }}"
                                               class="btn btn-primary btn-sm">

                                                <i class="fas fa-plus me-1"></i>
                                                Create First Unit

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


        {{-- =====================================================
            PAGINATION
        ====================================================== --}}
        @if(method_exists($units, 'hasPages') && $units->hasPages())

            <div class="card-footer bg-white">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div class="text-muted small">

                        Showing
                        <strong>{{ $units->firstItem() }}</strong>
                        to
                        <strong>{{ $units->lastItem() }}</strong>
                        of
                        <strong>{{ $units->total() }}</strong>
                        units

                    </div>

                    <div>

                        {{ $units->withQueryString()->links() }}

                    </div>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection