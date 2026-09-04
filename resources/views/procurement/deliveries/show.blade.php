@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="d-flex align-items-center gap-2">

                <h4 class="mb-0">
                    {{ $delivery->delivery_number }}
                </h4>

                @php

                    $statusClass = match(
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

                <span class="badge {{ $statusClass }}">
                    {{ $delivery->status }}
                </span>

            </div>

            <div class="text-muted mt-1">

                Purchase Order:

                <strong>
                    {{ $purchaseOrder->po_number }}
                </strong>

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.procurement.tenders.purchase-orders.deliveries.index',
                    [
                        'procurementTender' =>
                            $procurementTender,

                        'purchaseOrder' =>
                            $purchaseOrder,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Back to Deliveries
            </a>


            <a
                href="{{ route(
                    'admin.procurement.tenders.purchase-orders.show',
                    [
                        'procurementTender' =>
                            $procurementTender,

                        'purchaseOrder' =>
                            $purchaseOrder,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                View PO
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ALERTS --}}
    {{-- ========================================================= --}}

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


    {{-- ========================================================= --}}
    {{-- DELIVERY SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">


        {{-- Delivery Information --}}

        <div class="col-lg-8">

            <div class="card shadow-sm h-100">

                <div class="card-header">

                    <strong>
                        Delivery Information
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row g-3">


                        {{-- Delivery Number --}}

                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Delivery Number
                            </small>

                            <strong>
                                {{ $delivery->delivery_number }}
                            </strong>

                        </div>


                        {{-- Delivery Date --}}

                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Delivery Date
                            </small>

                            <strong>

                                @if($delivery->delivery_date)

                                    {{
                                        $delivery
                                            ->delivery_date
                                            ->format('d-m-Y')
                                    }}

                                @else

                                    —

                                @endif

                            </strong>

                        </div>


                        {{-- Supplier --}}

                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Supplier
                            </small>

                            <strong>
                                {{ $delivery->supplier_name ?: '—' }}
                            </strong>

                        </div>


                        {{-- Challan --}}

                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Challan Number
                            </small>

                            <strong>

                                {{ $delivery->challan_number ?: '—' }}

                            </strong>

                            @if($delivery->challan_date)

                                <div class="small text-muted">

                                    Date:
                                    {{
                                        $delivery
                                            ->challan_date
                                            ->format('d-m-Y')
                                    }}

                                </div>

                            @endif

                        </div>


                        {{-- Vehicle --}}

                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Vehicle Number
                            </small>

                            <strong>
                                {{ $delivery->vehicle_number ?: '—' }}
                            </strong>

                        </div>


                        {{-- Transporter --}}

                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Transporter
                            </small>

                            <strong>
                                {{ $delivery->transporter_name ?: '—' }}
                            </strong>

                        </div>


                        {{-- Received By --}}

                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Received By
                            </small>

                            <strong>
                                {{ $delivery->received_by ?: '—' }}
                            </strong>

                        </div>


                        {{-- Received At --}}

                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Received At
                            </small>

                            <strong>

                                @if($delivery->received_at)

                                    {{
                                        $delivery
                                            ->received_at
                                            ->format('d-m-Y H:i')
                                    }}

                                @else

                                    —

                                @endif

                            </strong>

                        </div>


                        {{-- Address --}}

                        <div class="col-md-12">

                            <small class="text-muted d-block">
                                Delivery Address
                            </small>

                            <div>
                                {{
                                    $delivery->delivery_address
                                    ?: '—'
                                }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- PO Reference --}}

        <div class="col-lg-4">

            <div class="card shadow-sm h-100">

                <div class="card-header">

                    <strong>
                        Purchase Order
                    </strong>

                </div>


                <div class="card-body">

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            PO Number
                        </small>

                        <a
                            href="{{ route(
                                'admin.procurement.tenders.purchase-orders.show',
                                [
                                    'procurementTender' =>
                                        $procurementTender,

                                    'purchaseOrder' =>
                                        $purchaseOrder,
                                ]
                            ) }}"
                            class="fw-semibold text-decoration-none"
                        >
                            {{ $purchaseOrder->po_number }}
                        </a>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            PO Title
                        </small>

                        <strong>
                            {{ $purchaseOrder->po_title }}
                        </strong>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Supplier
                        </small>

                        <strong>
                            {{ $purchaseOrder->supplier_name ?: '—' }}
                        </strong>

                    </div>


                    <div>

                        <small class="text-muted d-block">
                            PO Status
                        </small>

                        @php

                            $poStatusClass = match(
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

                        <span
                            class="badge {{ $poStatusClass }}"
                        >
                            {{ $purchaseOrder->status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DELIVERY ITEMS --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <strong>
                Delivered Items
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Item
                            </th>

                            <th class="text-end">
                                Ordered
                            </th>

                            <th class="text-end">
                                Previously Delivered
                            </th>

                            <th class="text-end">
                                Delivered Now
                            </th>

                            <th class="text-end">
                                Remaining
                            </th>

                            <th class="text-end">
                                Accepted
                            </th>

                            <th class="text-end">
                                Rejected
                            </th>

                            <th>
                                Unit
                            </th>

                            <th>
                                Remarks
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse(
                            $delivery->items
                            as $index => $deliveryItem
                        )

                            @php

                                $ordered =
                                    (float)
                                    $deliveryItem
                                        ->ordered_quantity;

                                $previous =
                                    (float)
                                    $deliveryItem
                                        ->previously_delivered_quantity;

                                $delivered =
                                    (float)
                                    $deliveryItem
                                        ->delivered_quantity;

                                $accepted =
                                    (float)
                                    $deliveryItem
                                        ->accepted_quantity;

                                $rejected =
                                    (float)
                                    $deliveryItem
                                        ->rejected_quantity;

                                $remaining =
                                    max(
                                        0,
                                        $ordered
                                        -
                                        $previous
                                        -
                                        $delivered
                                    );

                            @endphp


                            <tr>

                                {{-- # --}}

                                <td>
                                    {{ $index + 1 }}
                                </td>


                                {{-- Item --}}

                                <td>

                                    @if(
                                        $deliveryItem
                                            ->purchaseOrderItem
                                    )

                                        <strong>

                                            {{
                                                $deliveryItem
                                                    ->purchaseOrderItem
                                                    ->item_name
                                            }}

                                        </strong>

                                        @if(
                                            $deliveryItem
                                                ->purchaseOrderItem
                                                ->item_code
                                        )

                                            <div class="small text-muted">

                                                Code:
                                                {{
                                                    $deliveryItem
                                                        ->purchaseOrderItem
                                                        ->item_code
                                                }}

                                            </div>

                                        @endif

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Ordered --}}

                                <td class="text-end">

                                    {{
                                        number_format(
                                            $ordered,
                                            3
                                        )
                                    }}

                                </td>


                                {{-- Previously Delivered --}}

                                <td class="text-end">

                                    {{
                                        number_format(
                                            $previous,
                                            3
                                        )
                                    }}

                                </td>


                                {{-- Delivered --}}

                                <td class="text-end">

                                    <strong>

                                        {{
                                            number_format(
                                                $delivered,
                                                3
                                            )
                                        }}

                                    </strong>

                                </td>


                                {{-- Remaining --}}

                                <td class="text-end">

                                    @if($remaining > 0)

                                        <span
                                            class="badge bg-warning text-dark"
                                        >

                                            {{
                                                number_format(
                                                    $remaining,
                                                    3
                                                )
                                            }}

                                        </span>

                                    @else

                                        <span
                                            class="badge bg-success"
                                        >
                                            0.000
                                        </span>

                                    @endif

                                </td>


                                {{-- Accepted --}}

                                <td class="text-end">

                                    <span
                                        class="badge bg-success"
                                    >

                                        {{
                                            number_format(
                                                $accepted,
                                                3
                                            )
                                        }}

                                    </span>

                                </td>


                                {{-- Rejected --}}

                                <td class="text-end">

                                    @if($rejected > 0)

                                        <span
                                            class="badge bg-danger"
                                        >

                                            {{
                                                number_format(
                                                    $rejected,
                                                    3
                                                )
                                            }}

                                        </span>

                                    @else

                                        <span
                                            class="badge bg-light text-dark"
                                        >
                                            0.000
                                        </span>

                                    @endif

                                </td>


                                {{-- Unit --}}

                                <td>
                                    {{ $deliveryItem->unit }}
                                </td>


                                {{-- Remarks --}}

                                <td>

                                    {{
                                        $deliveryItem->remarks
                                        ?: '—'
                                    }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="10"
                                    class="text-center text-muted py-4"
                                >
                                    No delivery items found.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DELIVERY SUMMARY --}}
    {{-- ========================================================= --}}

    @php

        $totalOrdered =
            $delivery->items->sum(
                fn ($item) =>
                    (float)
                    $item->ordered_quantity
            );

        $totalPreviouslyDelivered =
            $delivery->items->sum(
                fn ($item) =>
                    (float)
                    $item->previously_delivered_quantity
            );

        $totalDelivered =
            $delivery->items->sum(
                fn ($item) =>
                    (float)
                    $item->delivered_quantity
            );

        $totalAccepted =
            $delivery->items->sum(
                fn ($item) =>
                    (float)
                    $item->accepted_quantity
            );

        $totalRejected =
            $delivery->items->sum(
                fn ($item) =>
                    (float)
                    $item->rejected_quantity
            );

    @endphp


    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Ordered Quantity
                    </small>

                    <div class="fs-5 fw-bold">
                        {{ number_format($totalOrdered, 3) }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Previously Delivered
                    </small>

                    <div class="fs-5 fw-bold">
                        {{
                            number_format(
                                $totalPreviouslyDelivered,
                                3
                            )
                        }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        This Delivery
                    </small>

                    <div class="fs-5 fw-bold text-primary">
                        {{ number_format($totalDelivered, 3) }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Accepted / Rejected
                    </small>

                    <div class="fs-5 fw-bold">

                        <span class="text-success">
                            {{ number_format($totalAccepted, 3) }}
                        </span>

                        /

                        <span class="text-danger">
                            {{ number_format($totalRejected, 3) }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- REMARKS --}}
    {{-- ========================================================= --}}

    @if($delivery->remarks)

        <div class="card shadow-sm mb-5">

            <div class="card-header">

                <strong>
                    Delivery Remarks
                </strong>

            </div>

            <div class="card-body">

                {!! nl2br(
                    e($delivery->remarks)
                ) !!}

            </div>

        </div>

    @endif

</div>

@endsection