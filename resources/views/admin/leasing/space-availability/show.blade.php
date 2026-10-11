@extends('layouts.app')

@section('title', 'Space Availability - ' . $unitData->unit_no)

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">

                <i class="ri-layout-grid-line me-2"></i>

                {{ $unitData->unit_no }}

            </h4>

            <p class="text-muted mb-0">

                Space availability and commercial readiness

            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.assets.units.show', $unitData->id) }}"
                class="btn btn-outline-primary"
            >
                <i class="ri-building-line me-1"></i>
                Unit Details
            </a>


            <a
                href="{{ route('admin.leasing.space-availability.index') }}"
                class="btn btn-outline-secondary"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Back
            </a>

        </div>

    </div>


    {{-- STATUS --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h5 class="mb-1">
                        {{ $unitData->unit_no }}
                    </h5>

                    <div class="text-muted">

                        {{ $unitData->mall_name }}

                        @if($unitData->building_name)
                            / {{ $unitData->building_name }}
                        @endif

                        @if($unitData->floor_name)
                            / {{ $unitData->floor_name }}
                        @endif

                    </div>

                </div>


                <div class="col-md-4 text-md-end mt-3 mt-md-0">

                    @php

                        $statusClass = match (
                            strtolower(
                                $unitData->current_status
                            )
                        ) {

                            'vacant',
                            'available'
                                => 'bg-success',

                            'occupied'
                                => 'bg-primary',

                            'reserved'
                                => 'bg-warning text-dark',

                            'under renovation',
                            'under fit-out'
                                => 'bg-danger',

                            'blocked'
                                => 'bg-dark',

                            default
                                => 'bg-secondary',

                        };

                    @endphp


                    <span
                        class="badge {{ $statusClass }} fs-6"
                    >
                        {{ $unitData->current_status }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- COMMERCIAL INFORMATION --}}

    <div class="row g-4">

        <div class="col-xl-8">

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h6 class="mb-0">
                        Commercial Information
                    </h6>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-4">

                            <small class="text-muted">
                                Unit
                            </small>

                            <div class="fw-semibold">
                                {{ $unitData->unit_no }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">
                                Category
                            </small>

                            <div class="fw-semibold">
                                {{ $unitData->type_name ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">
                                Area
                            </small>

                            <div class="fw-semibold">

                                {{ number_format(
                                    (float) $unitData->carpet_area,
                                    2
                                ) }}

                                sq.ft.

                            </div>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">
                                Built-up Area
                            </small>

                            <div class="fw-semibold">

                                {{ number_format(
                                    (float) $unitData->builtup_area,
                                    2
                                ) }}

                                sq.ft.

                            </div>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">
                                Frontage
                            </small>

                            <div class="fw-semibold">

                                {{ number_format(
                                    (float) $unitData->frontage,
                                    2
                                ) }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">
                                Asking Rent
                            </small>

                            <div class="fw-semibold">

                                {{ number_format(
                                    (float) $unitData->monthly_rent,
                                    2
                                ) }}

                                / month

                            </div>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">
                                Security Deposit
                            </small>

                            <div class="fw-semibold">

                                {{ number_format(
                                    (float) $unitData->security_deposit,
                                    2
                                ) }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">
                                Service Charge
                            </small>

                            <div class="fw-semibold">

                                @if($rentSchedules->first())

                                    {{ number_format(
                                        (float)
                                        $rentSchedules->first()->cam_amount,
                                        2
                                    ) }}

                                @else

                                    —

                                @endif

                            </div>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">
                                Status
                            </small>

                            <div class="fw-semibold">
                                {{ $unitData->status_name
                                    ?? $unitData->current_status }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- FIT-OUT --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h6 class="mb-0">
                        Fit-Out / Commercial Readiness
                    </h6>

                </div>


                <div class="card-body">

                    @forelse($fitouts as $fitout)

                        <div class="border rounded p-3 mb-3">

                            <div class="row">

                                <div class="col-md-4">

                                    <small class="text-muted">
                                        Request
                                    </small>

                                    <div class="fw-semibold">
                                        {{ $fitout->request_no }}
                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <small class="text-muted">
                                        Type
                                    </small>

                                    <div>
                                        {{ $fitout->fitout_type }}
                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <small class="text-muted">
                                        Status
                                    </small>

                                    <div>

                                        <span class="badge bg-secondary">
                                            {{ $fitout->fitout_status }}
                                        </span>

                                    </div>

                                </div>

                            </div>


                            <div class="row mt-3">

                                <div class="col-md-6">

                                    <small class="text-muted">
                                        Proposed Start
                                    </small>

                                    <div>
                                        {{ $fitout->proposed_start_date ?? '—' }}
                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <small class="text-muted">
                                        Proposed End
                                    </small>

                                    <div>
                                        {{ $fitout->proposed_end_date ?? '—' }}
                                    </div>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="text-muted">

                            No fit-out activity recorded for this unit.

                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- RIGHT SIDE --}}

        <div class="col-xl-4">

            {{-- FLOOR PLANS --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h6 class="mb-0">

                        <i class="ri-map-2-line me-1"></i>

                        Floor Plan

                    </h6>

                </div>


                <div class="card-body">

                    @forelse($floorPlans as $document)

                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">

                            <div>

                                <div class="fw-semibold">
                                    {{ $document->document_name }}
                                </div>

                                <small class="text-muted">
                                    {{ $document->document_type }}
                                </small>

                            </div>


                            <a
                                href="{{ asset('storage/' . $document->document_path) }}"
                                target="_blank"
                                class="btn btn-sm btn-outline-primary"
                            >
                                <i class="ri-external-link-line"></i>
                            </a>

                        </div>

                    @empty

                        <div class="text-muted small">

                            No floor plan uploaded.

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- RENT SCHEDULE --}}

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h6 class="mb-0">

                        <i class="ri-money-dollar-circle-line me-1"></i>

                        Rent / Service Charge

                    </h6>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-sm mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>
                                        Period
                                    </th>

                                    <th class="text-end">
                                        Rent
                                    </th>

                                    <th class="text-end">
                                        CAM
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($rentSchedules as $schedule)

                                    <tr>

                                        <td>
                                            {{ $schedule->billing_period }}
                                        </td>

                                        <td class="text-end">

                                            {{ number_format(
                                                (float) $schedule->base_rent,
                                                2
                                            ) }}

                                        </td>

                                        <td class="text-end">

                                            {{ number_format(
                                                (float) $schedule->cam_amount,
                                                2
                                            ) }}

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="3"
                                            class="text-center text-muted py-3"
                                        >
                                            No rent schedule.
                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection