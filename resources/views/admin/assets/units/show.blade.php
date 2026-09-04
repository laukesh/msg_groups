@extends('layouts.app')

@section('title', 'Unit Details')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-store me-2"></i>
                Unit Details
            </h4>

            <div class="text-muted">
                View complete information about this unit.
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.assets.units.index') }}"
               class="btn btn-outline-secondary">

                <i class="fas fa-arrow-left me-1"></i>
                Back

            </a>

            @can('units.edit')

                <a href="{{ route('admin.assets.units.edit', $unit->id) }}"
                   class="btn btn-primary">

                    <i class="fas fa-edit me-1"></i>
                    Edit

                </a>

            @endcan

        </div>

    </div>


    {{-- =========================================================
        UNIT INFORMATION
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="fas fa-info-circle me-2"></i>
                Unit Information
            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                {{-- Unit No --}}
                <div class="col-lg-4 col-md-6 mb-4">

                    <div class="text-muted small">
                        Unit No
                    </div>

                    <div class="fw-semibold fs-6">
                        {{ $unit->unit_no ?: '—' }}
                    </div>

                </div>


                {{-- Shop Name --}}
                <div class="col-lg-4 col-md-6 mb-4">

                    <div class="text-muted small">
                        Shop Name
                    </div>

                    <div class="fw-semibold">
                        {{ $unit->shop_name ?: '—' }}
                    </div>

                </div>


                {{-- Unit Type --}}
                <div class="col-lg-4 col-md-6 mb-4">

                    <div class="text-muted small">
                        Unit Type
                    </div>

                    <div class="fw-semibold">

                        {{ $unit->unitType?->name
                            ?? $unit->unitType?->type_name
                            ?? '—' }}

                    </div>

                </div>


                {{-- Mall --}}
                <div class="col-lg-4 col-md-6 mb-4">

                    <div class="text-muted small">
                        Mall
                    </div>

                    <div class="fw-semibold">

                        {{ $unit->mall?->mall_name ?? '—' }}

                        @if($unit->mall?->mall_code)

                            <small class="text-muted d-block">
                                {{ $unit->mall->mall_code }}
                            </small>

                        @endif

                    </div>

                </div>


                {{-- Building --}}
                <div class="col-lg-4 col-md-6 mb-4">

                    <div class="text-muted small">
                        Building
                    </div>

                    <div class="fw-semibold">

                        {{ $unit->building?->building_name ?? '—' }}

                    </div>

                </div>


                {{-- Floor --}}
                <div class="col-lg-4 col-md-6 mb-4">

                    <div class="text-muted small">
                        Floor
                    </div>

                    <div class="fw-semibold">

                        {{ $unit->floor?->floor_name ?? '—' }}

                        @if($unit->floor?->floor_code)

                            <small class="text-muted d-block">
                                {{ $unit->floor->floor_code }}
                            </small>

                        @endif

                    </div>

                </div>


                {{-- Zone --}}
                <div class="col-lg-4 col-md-6 mb-4">

                    <div class="text-muted small">
                        Zone
                    </div>

                    <div class="fw-semibold">

                        {{ $unit->zone?->zone_name ?? '—' }}

                        @if($unit->zone?->zone_code)

                            <small class="text-muted d-block">
                                {{ $unit->zone->zone_code }}
                            </small>

                        @endif

                    </div>

                </div>


                {{-- Unit Status --}}
                <div class="col-lg-4 col-md-6 mb-4">

                    <div class="text-muted small">
                        Unit Status
                    </div>

                    <div>

                        @if($unit->unitStatus)

                            <span class="badge bg-info">

                                {{ $unit->unitStatus->status_name
                                    ?? $unit->unitStatus->name }}

                            </span>

                        @else

                            <span class="text-muted">
                                —
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Record Status --}}
                <div class="col-lg-4 col-md-6 mb-4">

                    <div class="text-muted small">
                        Record Status
                    </div>

                    <div>

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

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        AREA & RENT INFORMATION
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="fas fa-ruler-combined me-2"></i>
                Area & Rental Information
            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                {{-- Carpet Area --}}
                <div class="col-lg-3 col-md-6 mb-4">

                    <div class="text-muted small">
                        Carpet Area
                    </div>

                    <div class="fw-semibold">

                        {{ $unit->carpet_area !== null
                            ? number_format((float) $unit->carpet_area, 2)
                            : '—' }}

                    </div>

                </div>


                {{-- Built-up Area --}}
                <div class="col-lg-3 col-md-6 mb-4">

                    <div class="text-muted small">
                        Built-up Area
                    </div>

                    <div class="fw-semibold">

                        {{ $unit->builtup_area !== null
                            ? number_format((float) $unit->builtup_area, 2)
                            : '—' }}

                    </div>

                </div>


                {{-- Frontage --}}
                <div class="col-lg-3 col-md-6 mb-4">

                    <div class="text-muted small">
                        Frontage
                    </div>

                    <div class="fw-semibold">

                        {{ $unit->frontage !== null
                            ? number_format((float) $unit->frontage, 2)
                            : '—' }}

                    </div>

                </div>


                {{-- Monthly Rent --}}
                <div class="col-lg-3 col-md-6 mb-4">

                    <div class="text-muted small">
                        Monthly Rent
                    </div>

                    <div class="fw-semibold">

                        @if($unit->monthly_rent !== null)

                            ₹ {{ number_format((float) $unit->monthly_rent, 2) }}

                        @else

                            —

                        @endif

                    </div>

                </div>


                {{-- Security Deposit --}}
                <div class="col-lg-3 col-md-6 mb-4">

                    <div class="text-muted small">
                        Security Deposit
                    </div>

                    <div class="fw-semibold">

                        @if($unit->security_deposit !== null)

                            ₹ {{ number_format((float) $unit->security_deposit, 2) }}

                        @else

                            —

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        REMARKS
    ========================================================== --}}
    @if($unit->remarks)

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    <i class="fas fa-sticky-note me-2"></i>
                    Remarks
                </h5>

            </div>

            <div class="card-body">

                <p class="mb-0">
                    {{ $unit->remarks }}
                </p>

            </div>

        </div>

    @endif


    {{-- =========================================================
        AUDIT INFORMATION
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="fas fa-history me-2"></i>
                Audit Information
            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">

                    <div class="text-muted small">
                        Unit ID
                    </div>

                    <div class="fw-semibold">
                        #{{ $unit->id }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="text-muted small">
                        Created By
                    </div>

                    <div>
                        {{ $unit->creator?->name ?? $unit->created_by ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="text-muted small">
                        Updated By
                    </div>

                    <div>
                        {{ $unit->updater?->name ?? $unit->updated_by ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="text-muted small">
                        Created At
                    </div>

                    <div>

                        {{ $unit->created_at?->format('d M Y H:i') ?? '—' }}

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="text-muted small">
                        Updated At
                    </div>

                    <div>

                        {{ $unit->updated_at?->format('d M Y H:i') ?? '—' }}

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection