@extends('layouts.app')

@section('title', 'Occupancy Report')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h1 class="h3 mb-1">Occupancy Report</h1>
        <p class="text-muted">
            Property occupancy based on rentable area.
        </p>
    </div>

    <div class="row g-4 mb-4">

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">
                        Total Rentable Area
                    </div>

                    <div class="fs-4 fw-semibold">
                        {{ number_format(
                            $totalRentableArea ?? 0,
                            2
                        ) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">
                        Occupied Area
                    </div>

                    <div class="fs-4 fw-semibold text-success">
                        {{ number_format(
                            $occupiedArea ?? 0,
                            2
                        ) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">
                        Occupancy Rate
                    </div>

                    <div class="fs-4 fw-semibold">
                        {{ number_format(
                            $occupancyRate ?? 0,
                            2
                        ) }}%
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Occupancy by Unit</h5>
        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                <tr>
                    <th>Unit</th>
                    <th>Area Type</th>
                    <th class="text-end">Rentable Area</th>
                    <th>Occupancy</th>
                </tr>
                </thead>

                <tbody>
                @forelse($areas as $area)
                    <tr>

                        <td>
                            {{ $area->unit->unit_no ?? $area->unit_id ?? '-' }}
                        </td>

                        <td>{{ $area->area_type_id }}</td>

                        <td class="text-end">
                            {{ number_format(
                                $area->rentable_area ?? 0,
                                2
                            ) }}
                            {{ $area->measurement_unit }}
                        </td>

                        <td>
                            @if(isset($area->occupied))
                                <span class="badge
                                    {{ $area->occupied
                                        ? 'bg-success'
                                        : 'bg-secondary' }}">
                                    {{ $area->occupied
                                        ? 'Occupied'
                                        : 'Vacant' }}
                                </span>
                            @else
                                -
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="4"
                            class="text-center text-muted py-5">
                            No occupancy data available.
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>

        </div>
    </div>

</div>
@endsection
