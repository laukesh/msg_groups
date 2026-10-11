@extends('layouts.app')

@section('title', 'Area Details')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Area Details</h1>
            <p class="text-muted mb-0">
                {{ $area->unit->code ?? $area->unit_id ?? 'Property Area' }}
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.assets.areas.edit', $area) }}"
               class="btn btn-primary">
                Edit
            </a>

            <a href="{{ route('admin.assets.revisions.index', $area) }}"
               class="btn btn-outline-secondary">
                Revisions
            </a>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-lg-8">

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Area Measurements</h5>
                </div>

                <div class="card-body">

                    <div class="row g-4">

                        @foreach([
                            'Gross Area' => $area->gross_area,
                            'Net Area' => $area->net_area,
                            'Usable Area' => $area->usable_area,
                            'Rentable Area' => $area->rentable_area,
                            'Chargeable Area' => $area->chargeable_area,
                            'Common Area' => $area->common_area,
                            'Service Area' => $area->service_area,
                        ] as $label => $value)

                            <div class="col-md-4">
                                <div class="text-muted small">
                                    {{ $label }}
                                </div>

                                <div class="fs-5 fw-semibold">
                                    {{ $value !== null
                                        ? number_format($value, 2)
                                        : '-' }}
                                    @if($value !== null)
                                        <small class="text-muted">
                                            {{ $area->measurement_unit }}
                                        </small>
                                    @endif
                                </div>
                            </div>

                        @endforeach

                    </div>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Measurement Information</h5>
                </div>

                <div class="card-body">

                    <dl class="row mb-0">

                        <dt class="col-sm-4">Area Type</dt>
                        <dd class="col-sm-8">
                            {{ $area->area_type_id }}
                        </dd>

                        <dt class="col-sm-4">Category</dt>
                        <dd class="col-sm-8">
                            {{ $area->area_category ?? '-' }}
                        </dd>

                        <dt class="col-sm-4">Measurement Date</dt>
                        <dd class="col-sm-8">
                            {{ $area->measurement_date?->format('d M Y') ?? '-' }}
                        </dd>

                        <dt class="col-sm-4">Measurement Method</dt>
                        <dd class="col-sm-8">
                            {{ $area->measurement_method ?? '-' }}
                        </dd>

                        <dt class="col-sm-4">Measurement Standard</dt>
                        <dd class="col-sm-8">
                            {{ $area->measurement_standard ?? '-' }}
                        </dd>

                        <dt class="col-sm-4">Effective From</dt>
                        <dd class="col-sm-8">
                            {{ $area->effective_from?->format('d M Y') ?? '-' }}
                        </dd>

                        <dt class="col-sm-4">Effective To</dt>
                        <dd class="col-sm-8">
                            {{ $area->effective_to?->format('d M Y') ?? '-' }}
                        </dd>

                    </dl>

                </div>
            </div>

        </div>

        <div class="col-lg-4">

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Property</h5>
                </div>

                <div class="card-body">

                    <dl class="row mb-0">

                        <dt class="col-5">Mall</dt>
                        <dd class="col-7">
                            {{ $area->mall->name ?? $area->mall_id }}
                        </dd>

                        <dt class="col-5">Building</dt>
                        <dd class="col-7">
                            {{ $area->building->name ?? $area->building_id ?? '-' }}
                        </dd>

                        <dt class="col-5">Floor</dt>
                        <dd class="col-7">
                            {{ $area->floor->name ?? $area->floor_id ?? '-' }}
                        </dd>

                        <dt class="col-5">Zone</dt>
                        <dd class="col-7">
                            {{ $area->zone->name ?? $area->zone_id ?? '-' }}
                        </dd>

                        <dt class="col-5">Unit</dt>
                        <dd class="col-7">
                            {{ $area->unit->code ?? $area->unit_id ?? '-' }}
                        </dd>

                        <dt class="col-5">Status</dt>
                        <dd class="col-7">
                            <span class="badge bg-success">
                                {{ $area->status }}
                            </span>
                        </dd>

                    </dl>

                </div>
            </div>

            @if($area->remarks)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Remarks</h5>
                    </div>

                    <div class="card-body">
                        {{ $area->remarks }}
                    </div>
                </div>
            @endif

        </div>

    </div>

</div>
@endsection