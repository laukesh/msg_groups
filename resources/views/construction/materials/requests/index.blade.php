@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                Material Requests
            </h4>

            <p class="text-muted mb-0">
                {{ $project->project_number }}
                <span class="mx-1">•</span>
                {{ $project->project_name }}
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.materials.index',
                $project
            ) }}"
               class="btn btn-light border">

                ← Back to Materials

            </a>

            <a href="{{ route(
                'admin.projects.construction.materials.requests.create',
                $project
            ) }}"
               class="btn btn-primary">

                + New Material Request

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
                               placeholder="Request number or work order">

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
                                'Draft',
                                'Submitted',
                                'Under Review',
                                'Approved',
                                'Rejected',
                                'Cancelled',
                                'Completed'
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
                            'admin.projects.construction.materials.requests.index',
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


    {{-- Table --}}

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Request No.</th>

                            <th>Request Date</th>

                            <th>Work Order</th>

                            <th>Items</th>

                            <th>Required Date</th>

                            <th>Requested By</th>

                            <th>Status</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($requests as $materialRequest)

                        <tr>

                            <td>
                                {{ $requests->firstItem() + $loop->index }}
                            </td>

                            <td>

                                <strong>
                                    {{ $materialRequest->request_number }}
                                </strong>

                            </td>

                            <td>
                                {{ $materialRequest->request_date?->format('d M Y') }}
                            </td>

                            <td>

                                @if($materialRequest->workOrder)

                                    {{ $materialRequest->workOrder->work_order_number }}

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>

                            <td>

                                <span class="badge bg-light text-dark border">

                                    {{ $materialRequest->items->count() }}
                                    item(s)

                                </span>

                            </td>

                            <td>

                                {{ $materialRequest->required_date?->format('d M Y') ?? '—' }}

                            </td>

                            <td>

                                {{ $materialRequest->requestedBy?->name ?? '—' }}

                            </td>

                            <td>

                                @php

                                    $statusClass = match(
                                        $materialRequest->status
                                    ) {
                                        'Draft' => 'secondary',
                                        'Submitted' => 'info',
                                        'Under Review' => 'warning',
                                        'Approved' => 'success',
                                        'Rejected' => 'danger',
                                        'Cancelled' => 'dark',
                                        'Completed' => 'primary',
                                        default => 'secondary',
                                    };

                                @endphp

                                <span class="badge bg-{{ $statusClass }}">

                                    {{ $materialRequest->status }}

                                </span>

                            </td>

                            <td>

                                <a href="{{ route(
                                    'admin.projects.construction.materials.requests.show',
                                    [
                                        'project' => $project->id,
                                        'materialRequest' =>
                                            $materialRequest->id,
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

                                No material requests found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">
                {{ $requests->links() }}
            </div>

        </div>

    </div>

</div>

@endsection