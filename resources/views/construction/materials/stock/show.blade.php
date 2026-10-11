@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Stock Details
            </h4>

            <p class="text-muted mb-0">

                {{ $project->project_number }}

                <span class="mx-1">•</span>

                {{ $project->project_name }}

            </p>

        </div>


        <a href="{{ route(
            'admin.projects.construction.materials.stock.index',
            $project
        ) }}"
           class="btn btn-light border">

            ← Back to Stock

        </a>

    </div>


    {{-- Material Card --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Material
                    </small>

                    <h5 class="fw-bold mb-0">

                        {{ $stock->material?->material_name }}

                    </h5>

                    <small class="text-muted">

                        {{ $stock->material?->material_code }}

                    </small>

                </div>


                <div class="col-md-2">

                    <small class="text-muted d-block">
                        Batch
                    </small>

                    <strong>
                        {{ $stock->batch_number ?: '—' }}
                    </strong>

                </div>


                <div class="col-md-2">

                    <small class="text-muted d-block">
                        Unit
                    </small>

                    <strong>
                        {{ $stock->unit }}
                    </strong>

                </div>


                <div class="col-md-2">

                    <small class="text-muted d-block">
                        Total Quantity
                    </small>

                    <h5 class="fw-bold mb-0">

                        {{ number_format(
                            $stock->quantity,
                            4
                        ) }}

                    </h5>

                </div>


                <div class="col-md-2">

                    <small class="text-muted d-block">
                        Available
                    </small>

                    <h5 class="fw-bold text-success mb-0">

                        {{ number_format(
                            $stock->available_quantity,
                            4
                        ) }}

                    </h5>

                </div>

            </div>

        </div>

    </div>


    {{-- Stock Information --}}

    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Current Quantity
                    </small>

                    <h4 class="fw-bold">
                        {{ number_format(
                            $stock->quantity,
                            4
                        ) }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Reserved Quantity
                    </small>

                    <h4 class="fw-bold">
                        {{ number_format(
                            $stock->reserved_quantity,
                            4
                        ) }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Reorder Level
                    </small>

                    <h4 class="fw-bold">
                        {{ number_format(
                            $stock->reorder_level,
                            4
                        ) }}
                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- Transactions --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <h6 class="mb-0 fw-bold">
                Stock Transaction History
            </h6>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th>Date</th>
                            <th>Transaction No.</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Reference</th>
                            <th>Work Order</th>
                            <th>Created By</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse(
                        $stock->transactions as $transaction
                    )

                        <tr>

                            <td>
                                {{ $transaction->transaction_date
                                    ? $transaction->transaction_date->format('d M Y H:i')
                                    : '—' }}
                            </td>

                            <td>

                                <a href="{{ route(
                                    'admin.projects.construction.materials.stock.transactions.show',
                                    [
                                        'project' => $project->id,
                                        'transaction' =>
                                            $transaction->id,
                                    ]
                                ) }}">

                                    {{ $transaction->transaction_number }}

                                </a>

                            </td>

                            <td>

                                @php

                                    $typeClass = match(
                                        $transaction->transaction_type
                                    ) {
                                        'Receipt',
                                        'Return',
                                        'Transfer In'
                                            => 'success',

                                        'Issue',
                                        'Consumption',
                                        'Wastage',
                                        'Transfer Out'
                                            => 'danger',

                                        'Adjustment'
                                            => 'warning',

                                        default
                                            => 'secondary',
                                    };

                                @endphp

                                <span class="badge bg-{{ $typeClass }}">

                                    {{ $transaction->transaction_type }}

                                </span>

                            </td>

                            <td class="fw-semibold">

                                {{ number_format(
                                    $transaction->quantity,
                                    4
                                ) }}

                                {{ $transaction->unit }}

                            </td>

                            {{-- Reference --}}

                            <td>

                                @if($transaction->reference_type === \App\Models\ConstructionMaterialReceipt::class)

                                    @php
                                        $reference = \App\Models\ConstructionMaterialReceipt::find(
                                            $transaction->reference_id
                                        );
                                    @endphp

                                    @if($reference)

                                        <div class="fw-semibold">
                                            {{ $reference->receipt_number }}
                                        </div>

                                        <small class="text-muted">
                                            Material Receipt
                                        </small>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                @elseif($transaction->reference_type === \App\Models\ConstructionMaterialRequest::class)

                                    @php
                                        $reference = \App\Models\ConstructionMaterialRequest::find(
                                            $transaction->reference_id
                                        );
                                    @endphp

                                    @if($reference)

                                        <div class="fw-semibold">
                                            {{ $reference->request_number }}
                                        </div>

                                        <small class="text-muted">
                                            Material Request
                                        </small>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                @elseif($transaction->reference_type === \App\Models\ConstructionMaterialDelivery::class)

                                    @php
                                        $reference = \App\Models\ConstructionMaterialDelivery::find(
                                            $transaction->reference_id
                                        );
                                    @endphp

                                    @if($reference)

                                        <div class="fw-semibold">
                                            {{ $reference->delivery_number }}
                                        </div>

                                        <small class="text-muted">
                                            Material Delivery
                                        </small>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                @elseif($transaction->reference_id)

                                    <div class="fw-semibold">
                                        #{{ $transaction->reference_id }}
                                    </div>

                                    <small class="text-muted">
                                        {{ class_basename($transaction->reference_type) }}
                                    </small>

                                @else

                                    —

                                @endif

                            </td>

                            <td>

                                {{ $transaction->workOrder?->work_order_number
                                    ?? '—' }}

                            </td>

                            <td>

                                {{ $transaction->creator?->name
                                    ?? '—' }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="text-center text-muted py-5">

                                No transactions found.

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