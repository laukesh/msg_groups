@extends('layouts.app')

@section('title', 'Space Availability')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="ri-layout-grid-line me-2"></i>
                Space Availability
            </h4>

            <p class="text-muted mb-0">
                Real-time inventory of leasable spaces
                and commercial readiness.
            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.leasing.space-availability.export', request()->query()) }}"
                class="btn btn-outline-success"
            >
                <i class="ri-file-excel-2-line me-1"></i>
                Export
            </a>

        </div>

    </div>


    {{-- =========================================================
         KPI CARDS
    ========================================================== --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Spaces
                    </div>

                    <h3 class="mb-0">
                        {{ number_format($totalSpaces) }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Available
                    </div>

                    <h3 class="mb-0 text-success">
                        {{ number_format($availableSpaces) }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Occupied
                    </div>

                    <h3 class="mb-0 text-primary">
                        {{ number_format($occupiedSpaces) }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Reserved
                    </div>

                    <h3 class="mb-0 text-warning">
                        {{ number_format($reservedSpaces) }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Under Fit-Out
                    </div>

                    <h3 class="mb-0 text-danger">
                        {{ number_format($fitoutSpaces) }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Available Area
                    </div>

                    <h3 class="mb-0">
                        {{ number_format($availableArea, 2) }}
                    </h3>

                    <small class="text-muted">
                        / {{ number_format($totalArea, 2) }}
                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         FILTERS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0">
                <i class="ri-filter-3-line me-2"></i>
                Space Filters
            </h6>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.leasing.space-availability.index') }}"
            >

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-lg-3">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Unit / shop name"
                        >

                    </div>


                    {{-- Mall --}}
                    <div class="col-lg-2">

                        <label class="form-label">
                            Mall
                        </label>

                        <select
                            name="mall_id"
                            class="form-select"
                        >

                            <option value="">
                                All Malls
                            </option>

                            @foreach($malls as $mall)

                                <option
                                    value="{{ $mall->id }}"
                                    @if(request('mall_id') == $mall->id)
                                        selected
                                    @endif
                                >
                                    {{ $mall->mall_name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Building --}}
                    <div class="col-lg-2">

                        <label class="form-label">
                            Building
                        </label>

                        <select
                            name="building_id"
                            class="form-select"
                        >

                            <option value="">
                                All Buildings
                            </option>

                            @foreach($buildings as $building)

                                <option
                                    value="{{ $building->id }}"
                                    @if(request('building_id') == $building->id)
                                        selected
                                    @endif
                                >
                                    {{ $building->building_name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Floor --}}
                    <div class="col-lg-2">

                        <label class="form-label">
                            Floor
                        </label>

                        <select
                            name="floor_id"
                            class="form-select"
                        >

                            <option value="">
                                All Floors
                            </option>

                            @foreach($floors as $floor)

                                <option
                                    value="{{ $floor->id }}"
                                    @if(request('floor_id') == $floor->id)
                                        selected
                                    @endif
                                >
                                    {{ $floor->floor_name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Category --}}
                    <div class="col-lg-3">

                        <label class="form-label">
                            Category
                        </label>

                        <select
                            name="unit_type_id"
                            class="form-select"
                        >

                            <option value="">
                                All Categories
                            </option>

                            @foreach($unitTypes as $type)

                                <option
                                    value="{{ $type->id }}"
                                    @if(request('unit_type_id') == $type->id)
                                        selected
                                    @endif
                                >
                                    {{ $type->type_name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="col-lg-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Statuses
                            </option>

                            @foreach($statuses as $status)

                                <option
                                    value="{{ $status->status_name }}"
                                    @if(request('status') == $status->status_name)
                                        selected
                                    @endif
                                >
                                    {{ $status->status_name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Area Min --}}
                    <div class="col-lg-2">

                        <label class="form-label">
                            Min Area
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="area_min"
                            value="{{ request('area_min') }}"
                            class="form-control"
                        >

                    </div>


                    {{-- Area Max --}}
                    <div class="col-lg-2">

                        <label class="form-label">
                            Max Area
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="area_max"
                            value="{{ request('area_max') }}"
                            class="form-control"
                        >

                    </div>


                    {{-- Rent Min --}}
                    <div class="col-lg-2">

                        <label class="form-label">
                            Min Rent
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="rent_min"
                            value="{{ request('rent_min') }}"
                            class="form-control"
                        >

                    </div>


                    {{-- Rent Max --}}
                    <div class="col-lg-2">

                        <label class="form-label">
                            Max Rent
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="rent_max"
                            value="{{ request('rent_max') }}"
                            class="form-control"
                        >

                    </div>


                    {{-- Buttons --}}
                    <div class="col-lg-3 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="ri-search-line me-1"></i>
                            Apply
                        </button>


                        <a
                            href="{{ route('admin.leasing.space-availability.index') }}"
                            class="btn btn-outline-secondary"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         INVENTORY TABLE
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h6 class="mb-1">
                        Available Space Inventory
                    </h6>

                    <small class="text-muted">
                        Units and their commercial readiness
                    </small>

                </div>

                <span class="badge bg-light text-dark">
                    {{ $areas->total() }} records
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Unit
                            </th>

                            <th>
                                Floor
                            </th>

                            <th>
                                Area
                            </th>

                            <th>
                                Category
                            </th>

                            <th class="text-end">
                                Asking Rent
                            </th>

                            <th class="text-end">
                                Service Charge
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Available Date
                            </th>

                            <th>
                                Fit-Out Condition
                            </th>

                            <th>
                                Floor Plan
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($areas as $area)

                            <tr>

                                {{-- Unit --}}
                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.leasing.space-availability.show',
                                            $area->id
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >
                                        {{ $area->unit_no }}
                                    </a>

                                    @if($area->shop_name)

                                        <div class="small text-muted">
                                            {{ $area->shop_name }}
                                        </div>

                                    @endif

                                </td>


                                {{-- Floor --}}
                                <td>

                                    <div>
                                        {{ $area->floor_name ?? '—' }}
                                    </div>

                                    @if($area->building_name)

                                        <small class="text-muted">
                                            {{ $area->building_name }}
                                        </small>

                                    @endif

                                </td>


                                {{-- Area --}}
                                <td>

                                    {{ number_format(
                                        (float) $area->carpet_area,
                                        2
                                    ) }}

                                    <small class="text-muted">
                                        sq.ft.
                                    </small>

                                </td>


                                {{-- Category --}}
                                <td>
                                    {{ $area->type_name ?? '—' }}
                                </td>


                                {{-- Asking Rent --}}
                                <td class="text-end">

                                    {{ number_format(
                                        (float) $area->monthly_rent,
                                        2
                                    ) }}

                                </td>


                                {{-- Service Charge --}}
                                <td class="text-end">

                                    <span class="text-muted">
                                        —
                                    </span>

                                </td>


                                {{-- Status --}}
                                <td>

                                    @php

                                        $statusClass = match (
                                            strtolower(
                                                $area->current_status
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
                                        class="badge {{ $statusClass }}"
                                    >
                                        {{ $area->current_status }}
                                    </span>

                                </td>


                                {{-- Available Date --}}
                                <td>

                                    <span class="text-muted">
                                        —
                                    </span>

                                </td>


                                {{-- Fit-Out --}}
                                <td>

                                    @if(
                                        in_array(
                                            strtolower(
                                                $area->current_status
                                            ),
                                            [
                                                'under renovation',
                                                'under fit-out'
                                            ]
                                        )
                                    )

                                        <span class="badge bg-danger">
                                            Under Fit-Out
                                        </span>

                                    @elseif(
                                        strtolower(
                                            $area->current_status
                                        ) === 'vacant'
                                    )

                                        <span class="badge bg-success">
                                            Ready / Vacant
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Floor Plan --}}
                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.leasing.space-availability.show',
                                            $area->id
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        <i class="ri-map-2-line"></i>
                                    </a>

                                </td>


                                {{-- Action --}}
                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.leasing.space-availability.show',
                                            $area->id
                                        ) }}"
                                        class="btn btn-sm btn-outline-secondary"
                                        title="View"
                                    >
                                        <i class="ri-eye-line"></i>
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="11"
                                    class="text-center py-5"
                                >

                                    <i
                                        class="ri-layout-grid-line fs-1 text-muted"
                                    ></i>

                                    <div class="mt-2 text-muted">
                                        No spaces found.
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($areas->hasPages())

            <div class="card-footer bg-white">

                {{ $areas->links() }}

            </div>

        @endif

    </div>

</div>

@endsection