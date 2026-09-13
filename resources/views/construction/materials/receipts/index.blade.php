@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Material Receipts
            </h4>

            <p class="text-muted mb-0">

                {{ $project->project_number }}

                <span class="mx-1">•</span>

                {{ $project->project_name }}

            </p>

        </div>

        <a href="{{ route(
            'admin.projects.construction.materials.index',
            $project
        ) }}"
           class="btn btn-light border">

            ← Back to Materials

        </a>

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
                               placeholder="Receipt, delivery or challan number">

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
                                'Received',
                                'Under Inspection',
                                'Accepted',
                                'Partially Accepted',
                                'Rejected',
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
                            'admin.projects.construction.materials.receipts.index',
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
                            <th>Receipt No.</th>
                            <th>Receipt Date</th>
                            <th>Delivery</th>
                            <th>Challan</th>
                            <th>Items</th>
                            <th>Received By</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($receipts as $receipt)

                        <tr>

                            <td>
                                {{ $receipts->firstItem() + $loop->index }}
                            </td>

                            <td>

                                <strong>
                                    {{ $receipt->receipt_number }}
                                </strong>

                            </td>

                            <td>
                                {{ $receipt->receipt_date?->format('d M Y') }}
                            </td>

                            <td>

                                {{ $receipt->delivery?->delivery_number ?? '—' }}

                            </td>

                            <td>

                                {{ $receipt->delivery?->challan_number ?? '—' }}

                            </td>

                            <td>

                                <span class="badge bg-light text-dark border">

                                    {{ $receipt->items->count() }}
                                    item(s)

                                </span>

                            </td>

                            <td>

                                {{ $receipt->receivedBy?->name ?? '—' }}

                            </td>

                            <td>

                                @php

                                    $statusClass = match(
                                        $receipt->status
                                    ) {
                                        'Draft' => 'secondary',
                                        'Received' => 'info',
                                        'Under Inspection' => 'warning',
                                        'Accepted' => 'success',
                                        'Partially Accepted' => 'primary',
                                        'Rejected' => 'danger',
                                        'Cancelled' => 'dark',
                                        default => 'secondary',
                                    };

                                @endphp

                                <span class="badge bg-{{ $statusClass }}">

                                    {{ $receipt->status }}

                                </span>

                            </td>

                            <td>

                                <a href="{{ route(
                                    'admin.projects.construction.materials.receipts.show',
                                    [
                                        'project' => $project->id,
                                        'materialReceipt' =>
                                            $receipt->id,
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

                                No material receipts found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">
                {{ $receipts->links() }}
            </div>

        </div>

    </div>

</div>

@endsection