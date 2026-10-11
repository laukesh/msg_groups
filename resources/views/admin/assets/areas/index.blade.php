@extends('layouts.app')

@section('title', 'Property Areas')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Property Areas</h1>
            <p class="text-muted mb-0">Manage property area measurements.</p>
        </div>

        <a href="{{ route('admin.assets.areas.create') }}"
           class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Area
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET"
                  action="{{ route('admin.assets.areas.index') }}">

                <div class="row g-3">

                    <div class="col-md-3">
                        <label class="form-label">Property</label>
                        <select name="mall_id" class="form-select">
                            <option value="">All Properties</option>
                            @foreach($malls ?? [] as $mall)
                                <option value="{{ $mall->id }}"
                                    @selected(request('mall_id') == $mall->id)>
                                    {{ $mall->mall_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Building</label>
                        <select name="building_id" class="form-select">
                            <option value="">All</option>
                            @foreach($buildings ?? [] as $building)
                                <option value="{{ $building->id }}"
                                    @selected(request('building_id') == $building->id)>
                                    {{ $building->building_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Floor</label>
                        <select name="floor_id" class="form-select">
                            <option value="">All</option>
                            @foreach($floors ?? [] as $floor)
                                <option value="{{ $floor->id }}"
                                    @selected(request('floor_id') == $floor->id)>
                                    {{ $floor->floor_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <option value="ACTIVE" @selected(request('status') === 'ACTIVE')>
                                Active
                            </option>
                            <option value="INACTIVE" @selected(request('status') === 'INACTIVE')>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button class="btn btn-primary">
                            Filter
                        </button>

                        <a href="{{ route('admin.assets.areas.index') }}"
                           class="btn btn-outline-secondary">
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                    <tr>
                        <th>Unit</th>
                        <th>Area Type</th>
                        <th>Category</th>
                        <th class="text-end">Gross</th>
                        <th class="text-end">Net</th>
                        <th class="text-end">Rentable</th>
                        <th>Unit</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($areas as $area)
                        <tr>
                            <td>
                                {{ $area->unit->unit_no ?? $area->unit_no ?? '-' }}
                            </td>

                            <td>{{ $area->area_type_id }}</td>

                            <td>{{ $area->area_category ?? '-' }}</td>

                            <td class="text-end">
                                {{ number_format($area->gross_area, 2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($area->net_area, 2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($area->rentable_area, 2) }}
                            </td>

                            <td>{{ $area->measurement_unit }}</td>

                            <td>
                                <span class="badge
                                    {{ $area->status === 'ACTIVE'
                                        ? 'bg-success'
                                        : 'bg-secondary' }}">
                                    {{ $area->status }}
                                </span>
                            </td>

                            <td class="text-end">
                                <div class="btn-group btn-group-sm">

                                    <a href="{{ route('admin.assets.areas.show', $area) }}"
                                       class="btn btn-sm  btn-primary">
                                       <i class="bi bi-eye"></i> 
                                    </a>

                                    <a href="{{ route('admin.assets.areas.edit', $area) }}"
                                       class="btn btn-sm btn-secondary">
                                          <i class="bi bi-pencil"></i>
                                    </a>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9"
                                class="text-center text-muted py-5">
                                No property areas found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>
            </div>

        </div>

        @if($areas->hasPages())
            <div class="card-footer">
                {{ $areas->withQueryString()->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
