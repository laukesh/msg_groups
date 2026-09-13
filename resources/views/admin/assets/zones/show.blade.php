@extends('layouts.app')

@section('title', $zone->zone_name)

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-layer-group me-2"></i>
                {{ $zone->zone_name }}
            </h4>

            <div class="text-muted">
                Zone Code:
                <span class="fw-semibold">
                    {{ $zone->zone_code }}
                </span>
            </div>
        </div>

        <div class="d-flex gap-2">

            @can('zones.edit')

                <a href="{{ route('admin.assets.zones.edit', $zone->id) }}"
                   class="btn btn-primary">

                    <i class="fas fa-edit me-1"></i>
                    Edit

                </a>

            @endcan

            <a href="{{ route('admin.assets.zones.index') }}"
               class="btn btn-outline-secondary">

                <i class="fas fa-arrow-left me-1"></i>
                Back to Zones

            </a>

        </div>

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


    <div class="row g-4">

        {{-- =====================================================
            ZONE INFORMATION
        ====================================================== --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Zone Information
                    </h6>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        {{-- Zone ID --}}
                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Zone ID
                            </div>

                            <div class="fw-semibold">
                                #{{ $zone->id }}
                            </div>

                        </div>


                        {{-- Zone Code --}}
                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Zone Code
                            </div>

                            <div>
                                <span class="badge bg-light text-dark border">
                                    {{ $zone->zone_code }}
                                </span>
                            </div>

                        </div>


                        {{-- Status --}}
                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Status
                            </div>

                            <div>

                                @if(
                                    $zone->status === 'active' ||
                                    $zone->status === 1 ||
                                    $zone->status === '1'
                                )

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

                            </div>

                        </div>


                        {{-- Zone Name --}}
                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Zone Name
                            </div>

                            <div class="fw-semibold">
                                {{ $zone->zone_name }}
                            </div>

                        </div>


                        {{-- Floor --}}
                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Floor
                            </div>

                            @if($zone->floor)

                                <div class="fw-semibold">
                                    {{ $zone->floor->floor_name }}
                                </div>

                                <small class="text-muted">
                                    {{ $zone->floor->floor_code ?? '' }}
                                </small>

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </div>


                        {{-- Building --}}
                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Building
                            </div>

                            <div class="fw-semibold">

                                {{ $zone->floor?->building?->building_name ?? '—' }}

                            </div>

                        </div>


                        {{-- Description --}}
                        <div class="col-12">

                            <div class="text-muted small mb-1">
                                Description
                            </div>

                            <div class="border rounded p-3 bg-light">

                                @if($zone->description)

                                    {!! nl2br(e($zone->description)) !!}

                                @else

                                    <span class="text-muted">
                                        No description available.
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            QUICK ACTIONS
        ====================================================== --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Quick Actions
                    </h6>

                </div>

                <div class="card-body">

                    @can('zones.edit')

                        <a href="{{ route('admin.assets.zones.edit', $zone->id) }}"
                           class="btn btn-primary w-100 mb-2">

                            <i class="fas fa-edit me-2"></i>
                            Edit Zone

                        </a>

                    @endcan


                    @can('zones.delete')

                        <form method="POST"
                              action="{{ route('admin.assets.zones.destroy', $zone->id) }}"
                              onsubmit="return confirm('Are you sure you want to delete this zone?');">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-outline-danger w-100 mb-2">

                                <i class="fas fa-trash me-2"></i>
                                Delete Zone

                            </button>

                        </form>

                    @endcan


                    <a href="{{ route('admin.assets.zones.index') }}"
                       class="btn btn-outline-secondary w-100">

                        <i class="fas fa-list me-2"></i>
                        All Zones

                    </a>

                </div>

            </div>

        </div>


        {{-- =====================================================
            AUDIT INFORMATION
        ====================================================== --}}
        <div class="col-12">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Audit Information
                    </h6>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        {{-- Created By --}}
                        <div class="col-md-3">

                            <div class="text-muted small mb-1">
                                Created By
                            </div>

                            <div class="fw-semibold">

                                {{ $zone->creator->name ?? $zone->created_by ?? '—' }}

                            </div>

                        </div>


                        {{-- Updated By --}}
                        <div class="col-md-3">

                            <div class="text-muted small mb-1">
                                Updated By
                            </div>

                            <div class="fw-semibold">

                                {{ $zone->updater->name ?? $zone->updated_by ?? '—' }}

                            </div>

                        </div>


                        {{-- Created At --}}
                        <div class="col-md-3">

                            <div class="text-muted small mb-1">
                                Created At
                            </div>

                            <div>

                                {{ $zone->created_at?->format('d M Y, h:i A') ?? '—' }}

                            </div>

                        </div>


                        {{-- Updated At --}}
                        <div class="col-md-3">

                            <div class="text-muted small mb-1">
                                Updated At
                            </div>

                            <div>

                                {{ $zone->updated_at?->format('d M Y, h:i A') ?? '—' }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection