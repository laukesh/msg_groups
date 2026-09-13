@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Material Deliveries
            </h4>

            <p class="text-muted mb-0">
                {{ $project->project_number }}
                <span class="mx-1">•</span>
                {{ $project->project_name }}
            </p>

        </div>

        <div>

            <a href="{{ route(
                'admin.projects.construction.materials.index',
                $project
            ) }}"
               class="btn btn-light border">

                ← Back to Materials

            </a>

        </div>

    </div>


    {{-- Filters --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3">

                    <div class="col-md-5">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Delivery, challan, vehicle or request number">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            @foreach([
                                'Expected',
                                'Partially Delivered',
                                'Delivered',
                                'Received',
                                'Cancelled'
                            ] as $status)

                                <option value="{{ $status }}"
                                    @selected(request('status') === $status)>
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4 d-flex align-items-end">

                        <button class="btn btn-primary me-2">
                            Search
                        </button>

                        <a href="{{ route(
                            'admin.projects.construction.materials.deliveries.index',
                            $project
                        ) }}"
                           class="btn btn-secondary">

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Deliveries --}}

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th>#</th>
                            <th>Delivery No.</th>
                            <th>Delivery Date</th>
                            <th>Material Request</th>
                            <th>Challan</th>
                            <th>Vehicle</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($deliveries as $delivery)

                        <tr>

                            <td>
                                {{ $deliveries->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <strong>
                                    {{ $delivery->delivery_number }}
                                </strong>
                            </td>

                            <td>
                                {{ $delivery->delivery_date?->format('d M Y') }}
                            </td>

                            <td>

                                @if($delivery->materialRequest)

                                    {{ $delivery->materialRequest->request_number }}

                                @else

                                    —

                                @endif

                            </td>

                            <td>

                                {{ $delivery->challan_number ?: '—' }}

                            </td>

                            <td>

                                {{ $delivery->vehicle_number ?: '—' }}

                            </td>

                            <td>

                                <span class="badge bg-light text-dark border">

                                    {{ $delivery->items->count() }}
                                    item(s)

                                </span>

                            </td>

                            <td>

                                @php

                                    $statusClass = match(
                                        $delivery->status
                                    ) {
                                        'Expected' => 'secondary',
                                        'Partially Delivered' => 'warning',
                                        'Delivered' => 'info',
                                        'Received' => 'success',
                                        'Cancelled' => 'danger',
                                        default => 'secondary',
                                    };

                                @endphp

                                <span class="badge bg-{{ $statusClass }}">
                                    {{ $delivery->status }}
                                </span>

                            </td>

                            <td>

                                <a href="{{ route(
                                    'admin.projects.construction.materials.deliveries.show',
                                    [
                                        'project' => $project->id,
                                        'materialDelivery' =>
                                            $delivery->id,
                                    ]
                                ) }}"
                                   class="btn btn-sm btn-info">

                                    View

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9"
                                class="text-center text-muted py-5">

                                No material deliveries found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>


            <div class="mt-3">

                {{ $deliveries->links() }}

            </div>

        </div>

    </div>

</div>

@endsection