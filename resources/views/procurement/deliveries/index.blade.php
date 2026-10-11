@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center gap-3">

                <div>
                    <h4 class="mb-1 fw-bold">
                        Deliveries
                    </h4>

                    <div class="text-muted small">
                        Purchase Order:
                        <strong>
                            {{ $purchaseOrder->po_number }}
                        </strong>
                    </div>
                </div>

                @if($purchaseOrder->status === 'Issued')

                    <a
                        href="{{ route(
                            'admin.projects.procurement.tenders.purchase-orders.deliveries.create',
                            [
                                'project' => $project,
                                'procurementTender' => $procurementTender,
                                'purchaseOrder' => $purchaseOrder,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        <i class="ri-add-line me-1"></i>
                        Record Delivery
                    </a>

                @endif

            </div>

            <hr class="my-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                <div class="d-flex flex-wrap gap-4">

                    <div>
                        <div class="text-muted small">
                            Project
                        </div>

                        <div class="fw-semibold">
                            {{ $project->project_name ?? 'N/A' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-muted small">
                            Tender
                        </div>

                        <div class="fw-semibold">
                            {{ $procurementTender->tender_number ?? 'N/A' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-muted small">
                            Purchase Order
                        </div>

                        <div class="fw-semibold">
                            {{ $purchaseOrder->po_number }}
                        </div>
                    </div>

                </div>


                <a
                    href="{{ route(
                        'admin.projects.procurement.tenders.purchase-orders.show',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                            'purchaseOrder' => $purchaseOrder,
                        ]
                    ) }}"
                    class="btn btn-sm btn-outline-secondary text-nowrap"
                >
                    <i class="ri-arrow-left-line me-1"></i>
                    Back to PO
                </a>

            </div>

        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- ALERTS --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">
            <i class="ri-check-line me-1"></i>

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
            <i class="ri-error-warning-line me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- PO SUMMARY --}}
    {{-- ========================================================= --}}

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
                        {{ number_format((float) $purchaseOrder->total_amount, 2) }}
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
                        $statusClass = match($purchaseOrder->status) {

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


    {{-- ========================================================= --}}
    {{-- DELIVERY REGISTER --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">

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

                    <table class="table table-bordered table-hover mb-0 align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

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

                            @foreach($deliveries as $index => $delivery)

                                <tr>

                                    <td>
                                        {{ $deliveries->firstItem() + $index }}
                                    </td>


                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.projects.procurement.tenders.purchase-orders.deliveries.show',
                                                [
                                                    'project' => $project,
                                                    'procurementTender' => $procurementTender,
                                                    'purchaseOrder' => $purchaseOrder,
                                                    'delivery' => $delivery,
                                                ]
                                            ) }}"
                                            class="fw-semibold text-decoration-none"
                                        >
                                            {{ $delivery->delivery_number }}
                                        </a>

                                    </td>


                                    <td>

                                        @if($delivery->delivery_date)

                                            {{ $delivery->delivery_date->format('d-m-Y') }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        {{ $delivery->challan_number ?: '—' }}

                                        @if($delivery->challan_date)

                                            <div class="small text-muted">
                                                {{ $delivery->challan_date->format('d-m-Y') }}
                                            </div>

                                        @endif

                                    </td>


                                    <td>
                                        {{ $delivery->vehicle_number ?: '—' }}
                                    </td>


                                    <td>

                                        <span class="badge bg-light text-dark">
                                            {{ $delivery->items->count() }}
                                            item(s)
                                        </span>

                                    </td>


                                    <td>
                                        {{ $delivery->received_by ?: '—' }}
                                    </td>


                                    <td>

                                        @php

                                            $deliveryStatusClass =
                                                match($delivery->status) {

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

                                        <span class="badge {{ $deliveryStatusClass }}">
                                            {{ $delivery->status }}
                                        </span>

                                    </td>


                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.projects.procurement.tenders.purchase-orders.deliveries.show',
                                                [
                                                    'project' => $project,
                                                    'procurementTender' => $procurementTender,
                                                    'purchaseOrder' => $purchaseOrder,
                                                    'delivery' => $delivery,
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
                                'admin.projects.procurement.tenders.purchase-orders.deliveries.create',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                    'purchaseOrder' => $purchaseOrder,
                                ]
                            ) }}"
                            class="btn btn-primary"
                        >
                            <i class="ri-add-line me-1"></i>
                            Record First Delivery
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