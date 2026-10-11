@extends('layouts.app')

@section('title', 'NLA Report')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h1 class="h3 mb-1">NLA Report</h1>
        <p class="text-muted">
            Net Leasable Area by property.
        </p>
    </div>

    <div class="card">
        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                <tr>
                    <th>Property</th>
                    <th>Building</th>
                    <th>Floor</th>
                    <th>Unit</th>
                    <th>Area Type</th>
                    <th class="text-end">Net Area</th>
                    <th>Unit</th>
                </tr>
                </thead>

                <tbody>
                @forelse($areas as $area)
                    <tr>

                        <td>
                            {{ $area->mall->mall_name ?? $area->mall_id }}
                        </td>

                        <td>
                            {{ $area->building->building_name ?? $area->building_id ?? '-' }}
                        </td>

                        <td>
                            {{ $area->floor->floor_name ?? $area->floor_id ?? '-' }}
                        </td>

                        <td>
                            {{ $area->unit->unit_no ?? $area->unit_id ?? '-' }}
                        </td>

                        <td>{{ $area->area_type_id }}</td>

                        <td class="text-end">
                            {{ number_format($area->net_area ?? 0, 2) }}
                        </td>

                        <td>
                            {{ $area->measurement_unit }}
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7"
                            class="text-center text-muted py-5">
                            No NLA data available.
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>

        </div>
    </div>

</div>
@endsection
