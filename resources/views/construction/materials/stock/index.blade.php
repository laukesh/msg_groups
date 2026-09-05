@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Material Stock
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
                'admin.projects.construction.materials.stock.transactions',
                $project
            ) }}"
               class="btn btn-outline-primary">

                Transaction History

            </a>

        </div>

    </div>


    {{-- Summary --}}

    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Materials in Stock
                    </small>

                    <h3 class="fw-bold mb-0 mt-2">
                        {{ number_format($totalMaterials) }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Total Quantity
                    </small>

                    <h3 class="fw-bold mb-0 mt-2">
                        {{ number_format($totalQuantity, 4) }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Available Quantity
                    </small>

                    <h3 class="fw-bold mb-0 mt-2">
                        {{ number_format($totalAvailable, 4) }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Low Stock Items
                    </small>

                    <h3 class="fw-bold mb-0 mt-2 text-warning">
                        {{ number_format($lowStock) }}
                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- Search --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3">

                    <div class="col-md-8">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Material code, material name or batch number">

                    </div>


                    <div class="col-md-4 d-flex align-items-end">

                        <button class="btn btn-primary me-2">
                            Search
                        </button>

                        <a href="{{ route(
                            'admin.projects.construction.materials.stock.index',
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


    {{-- Stock Table --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <h6 class="mb-0 fw-bold">
                Current Project Stock
            </h6>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th>#</th>
                            <th>Material</th>
                            <th>Batch</th>
                            <th>Unit</th>
                            <th>Quantity</th>
                            <th>Reserved</th>
                            <th>Available</th>
                            <th>Reorder Level</th>
                            <th>Last Transaction</th>
                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($stocks as $stock)

                        <tr>

                            <td>
                                {{ $stocks->firstItem() + $loop->index }}
                            </td>

                            <td>

                                <strong>
                                    {{ $stock->material?->material_code }}
                                </strong>

                                <br>

                                <small class="text-muted">
                                    {{ $stock->material?->material_name }}
                                </small>

                            </td>

                            <td>
                                {{ $stock->batch_number ?: '—' }}
                            </td>

                            <td>
                                {{ $stock->unit }}
                            </td>

                            <td class="fw-semibold">
                                {{ number_format(
                                    $stock->quantity,
                                    4
                                ) }}
                            </td>

                            <td>
                                {{ number_format(
                                    $stock->reserved_quantity,
                                    4
                                ) }}
                            </td>

                            <td>

                                <span class="fw-bold
                                    {{ $stock->available_quantity <= $stock->reorder_level
                                        && $stock->reorder_level > 0
                                        ? 'text-warning'
                                        : 'text-success' }}">

                                    {{ number_format(
                                        $stock->available_quantity,
                                        4
                                    ) }}

                                </span>

                            </td>

                            <td>
                                {{ number_format(
                                    $stock->reorder_level,
                                    4
                                ) }}
                            </td>

                            <td>
                                {{ $stock->last_transaction_at
                                    ? $stock->last_transaction_at->format('d M Y H:i')
                                    : '—' }}
                            </td>

                            <td>

                                <a href="{{ route(
                                    'admin.projects.construction.materials.stock.show',
                                    [
                                        'project' => $project->id,
                                        'stock' => $stock->id,
                                    ]
                                ) }}"
                                   class="btn btn-sm btn-info">

                                    View

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="10"
                                class="text-center text-muted py-5">

                                No stock available for this project yet.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>


            <div class="mt-3">

                {{ $stocks->links() }}

            </div>

        </div>

    </div>

</div>

@endsection