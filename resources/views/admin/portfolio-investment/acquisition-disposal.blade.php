@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Acquisition & Disposal</h4>
            <p class="text-muted mb-0">
                Monitor land acquisition status, acquisition costs and disposal review.
            </p>
        </div>
    </div>

    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Total Land Records</small>
                    <h3 class="mb-0">{{ $stats['total'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Acquired</small>
                    <h3 class="mb-0 text-success">
                        {{ $stats['acquired'] }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">In Progress</small>
                    <h3 class="mb-0 text-warning">
                        {{ $stats['in_progress'] }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Available / Identified</small>
                    <h3 class="mb-0 text-info">
                        {{ $stats['available'] }}
                    </h3>
                </div>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Acquisition Register</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Land</th>
                            <th>Land Type</th>
                            <th>Location</th>
                            <th>Total Area</th>
                            <th>Acquisition Date</th>
                            <th>Acquisition Cost</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($lands as $index => $land)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <strong>{{ $land->land_code }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $land->land_name }}
                                    </small>
                                </td>

                                <td>
                                    {{ $land->land_type ?? '—' }}
                                </td>

                                <td>
                                    @php
                                        $location = collect([
                                            $land->address_line1,
                                            $land->address_line2,
                                            $land->locality,
                                            $land->city,
                                            $land->state,
                                            $land->country,
                                            $land->postal_code,
                                        ])->filter()->implode(', ');
                                    @endphp

                                    {{ $location ?: '—' }}
                                </td>

                                <td>
                                    {{ $land->total_area ?? '—' }}
                                    {{ $land->area_unit ?? '' }}
                                </td>

                                <td>
                                    {{ $land->acquisition_date
                                        ? \Carbon\Carbon::parse($land->acquisition_date)->format('d-m-Y')
                                        : '—'
                                    }}
                                </td>

                                <td>
                                    ₹{{ number_format((float) $land->total_acquisition_cost, 2) }}
                                </td>

                                <td>
                                    @php
                                        $status = strtolower((string) $land->acquisition_status);
                                    @endphp

                                    @if(in_array($status, ['acquired', 'completed', 'registered']))
                                        <span class="badge bg-success">
                                            {{ ucfirst($land->acquisition_status) }}
                                        </span>
                                    @elseif(in_array($status, ['in progress', 'under acquisition', 'pending']))
                                        <span class="badge bg-warning text-dark">
                                            {{ ucfirst($land->acquisition_status) }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($land->acquisition_status ?? 'N/A') }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex gap-1">

                                        {{-- Land Details --}}
                                        <a href="{{ url('admin/land/lands/' . $land->id) }}"
                                           class="btn btn-sm btn-outline-secondary">
                                            <i class="ri-landscape-line"></i>
                                            Land
                                        </a>

                                        {{-- Acquisition Review --}}
                                        <a href="{{ route(
                                            'admin.land.lands.acquisition-review',
                                            ['land' => $land->id]
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="ri-eye-line"></i>
                                            Review
                                        </a>

                                        {{-- Acquisition Costs --}}
                                        <a href="{{ route(
                                            'admin.land.lands.acquisition-costs.index',
                                            ['land' => $land->id]
                                        ) }}"
                                           class="btn btn-sm btn-outline-success">
                                            <i class="ri-money-rupee-circle-line"></i>
                                            Costs
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">
                                    No acquisition records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>
@endsection