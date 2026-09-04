@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>
            <h4 class="mb-1">
                Deliveries
            </h4>

            <div class="text-muted">
                Purchase Order:
                <strong>
                    {{ $purchaseOrder->po_number }}
                </strong>
            </div>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.procurement.tenders.purchase-orders.show',
                    [
                        'procurementTender' => $procurementTender,
                        'purchaseOrder' => $purchaseOrder,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Back to PO
            </a>

            @if($purchaseOrder->status === 'Issued')

                <a
                    href="{{ route(
                        'admin.procurement.tenders.purchase-orders.deliveries.create',
                        [
                            'procurementTender' => $procurementTender,
                            'purchaseOrder' => $purchaseOrder,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    + Record Delivery
                </a>

            @endif

        </div>

    </div>


    {{-- ALERTS --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- PO SUMMARY --}}

    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        PO Number
                    </div>

                    <div class="fw-bold fs-5">
                        {{ $purchaseOrder->po_number }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Supplier
                    </div>

                    <div class="fw-bold">
                        {{ $purchaseOrder->supplier_name ?: '—' }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        PO Amount
                    </div>

                    <div class="fw-bold fs-5">

                        {{ $purchaseOrder->currency }}

                        {{ number_format(
                            (float) $purchaseOrder->total_amount,
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        PO Status
                    </div>

                    @php

                        $statusClass = match(
                            $purchaseOrder->status
                        ) {
                            'Issued' =>
                                'bg-success',

                            'Partially Delivered' =>
                                'bg-primary',

                            'Fully Delivered' =>
                                'bg-success',

                            'Closed' =>
                                'bg-dark',

                            default =>
                                'bg-secondary',
                        };

                    @endphp

                    <div class="mt-1">

                        <span class="badge {{ $statusClass }}">
                            {{ $purchaseOrder->status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- DELIVERY REGISTER --}}

    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between">

            <strong>
                Delivery Register
            </strong>

            <span class="text-muted">
                {{ $deliveries->total() }} Delivery(s)
            </span>

        </div>


        <div class="card-body p-0">

            @if($deliveries->count())

                <div class="table-responsive">

                    <table class="table table-bordered table-hover mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Delivery No.
                                </th>

                                <th>
                                    Delivery Date
                                </th>

                                <th>
                                    Challan No.
                                </th>

                                <th>
                                    Vehicle
                                </th>

                                <th>
                                    Items
                                </th>

                                <th>
                                    Received By
                                </th>

                                <th>
                                    Status
                                </th>

                                <th style="width:100px;">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $deliveries
                                as $index => $delivery
                            )

                                <tr>

                                    {{-- # --}}

                                    <td>

                                        {{
                                            $deliveries->firstItem()
                                            + $index
                                        }}

                                    </td>


                                    {{-- Delivery Number --}}

                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.procurement.tenders.purchase-orders.deliveries.show',
                                                [
                                                    'procurementTender' =>
                                                        $procurementTender,

                                                    'purchaseOrder' =>
                                                        $purchaseOrder,

                                                    'delivery' =>
                                                        $delivery,
                                                ]
                                            ) }}"
                                            class="fw-semibold text-decoration-none"
                                        >

                                            {{ $delivery->delivery_number }}

                                        </a>

                                    </td>


                                    {{-- Delivery Date --}}

                                    <td>

                                        @if($delivery->delivery_date)

                                            {{
                                                $delivery
                                                    ->delivery_date
                                                    ->format('d-m-Y')
                                            }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Challan --}}

                                    <td>

                                        {{ $delivery->challan_number ?: '—' }}

                                        @if($delivery->challan_date)

                                            <div class="small text-muted">

                                                {{
                                                    $delivery
                                                        ->challan_date
                                                        ->format('d-m-Y')
                                                }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Vehicle --}}

                                    <td>

                                        {{ $delivery->vehicle_number ?: '—' }}

                                    </td>


                                    {{-- Items --}}

                                    <td>

                                        <span class="badge bg-light text-dark">

                                            {{ $delivery->items->count() }}

                                            item(s)

                                        </span>

                                    </td>


                                    {{-- Received By --}}

                                    <td>

                                        {{ $delivery->received_by ?: '—' }}

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        @php

                                            $deliveryStatusClass =
                                                match(
                                                    $delivery->status
                                                ) {

                                                    'Received' =>
                                                        'bg-success',

                                                    'Under Inspection' =>
                                                        'bg-warning text-dark',

                                                    'Accepted' =>
                                                        'bg-primary',

                                                    'Partially Accepted' =>
                                                        'bg-info text-dark',

                                                    'Rejected' =>
                                                        'bg-danger',

                                                    default =>
                                                        'bg-secondary',

                                                };

                                        @endphp

                                        <span
                                            class="badge {{ $deliveryStatusClass }}"
                                        >
                                            {{ $delivery->status }}
                                        </span>

                                    </td>


                                    {{-- Action --}}

                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.procurement.tenders.purchase-orders.deliveries.show',
                                                [
                                                    'procurementTender' =>
                                                        $procurementTender,

                                                    'purchaseOrder' =>
                                                        $purchaseOrder,

                                                    'delivery' =>
                                                        $delivery,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            View
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="fs-5 fw-semibold mb-2">
                        No Deliveries Found
                    </div>

                    <p class="text-muted mb-3">
                        No delivery has been recorded against this
                        Purchase Order yet.
                    </p>

                    @if($purchaseOrder->status === 'Issued')

                        <a
                            href="{{ route(
                                'admin.procurement.tenders.purchase-orders.deliveries.create',
                                [
                                    'procurementTender' =>
                                        $procurementTender,

                                    'purchaseOrder' =>
                                        $purchaseOrder,
                                ]
                            ) }}"
                            class="btn btn-primary"
                        >
                            + Record First Delivery
                        </a>

                    @endif

                </div>

            @endif

        </div>


        @if($deliveries->hasPages())

            <div class="card-footer">

                {{ $deliveries->links() }}

            </div>

        @endif

    </div>

</div>

@endsection