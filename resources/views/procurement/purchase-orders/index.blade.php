@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Purchase Orders</h4>

            <div class="text-muted">
                Tender:
                <strong>
                    {{ $procurementTender->tender_number ?? 'N/A' }}
                </strong>
            </div>
        </div>

        <a
            href="{{ route(
                'admin.procurement.tenders.purchase-orders.create',
                $procurementTender
            ) }}"
            class="btn btn-primary"
        >
            + Create Purchase Order
        </a>

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
    {{-- SUMMARY --}}
    {{-- ========================================================= --}}

    @php

        $totalPOs = $purchaseOrders->total();

    @endphp


    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Purchase Orders
                    </div>

                    <div class="fs-4 fw-bold">
                        {{ $totalPOs }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- PURCHASE ORDER TABLE --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm">

        <div class="card-header">

            <strong>
                Purchase Order Register
            </strong>

        </div>


        <div class="card-body p-0">

            @if($purchaseOrders->count())

                <div class="table-responsive">

                    <table class="table table-hover table-bordered mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    PO Number
                                </th>

                                <th>
                                    PO Title
                                </th>

                                <th>
                                    Award
                                </th>

                                <th>
                                    Contract
                                </th>

                                <th>
                                    Supplier
                                </th>

                                <th>
                                    PO Date
                                </th>

                                <th>
                                    Delivery Date
                                </th>

                                <th class="text-end">
                                    Amount
                                </th>

                                <th>
                                    Status
                                </th>

                                <th style="width: 170px;">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($purchaseOrders as $index => $purchaseOrder)

                                <tr>

                                    {{-- Number --}}

                                    <td>

                                        {{
                                            $purchaseOrders->firstItem()
                                            + $index
                                        }}

                                    </td>


                                    {{-- PO Number --}}

                                    <td>

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

                                    </td>


                                    {{-- Title --}}

                                    <td>

                                        {{ $purchaseOrder->po_title }}

                                    </td>


                                    {{-- Award --}}

                                    <td>

                                        @if($purchaseOrder->award)

                                            <div>
                                                {{
                                                    $purchaseOrder
                                                        ->award
                                                        ->award_number
                                                }}
                                            </div>

                                            <small class="text-muted">

                                                {{
                                                    $purchaseOrder
                                                        ->award
                                                        ->status
                                                }}

                                            </small>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Contract --}}

                                    <td>

                                        @if($purchaseOrder->contract)

                                            {{
                                                $purchaseOrder
                                                    ->contract
                                                    ->contract_number
                                            }}

                                        @else

                                            <span class="text-muted">
                                                No contract
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Supplier --}}

                                    <td>

                                        {{ $purchaseOrder->supplier_name ?: '—' }}

                                    </td>


                                    {{-- PO Date --}}

                                    <td>

                                        @if($purchaseOrder->po_date)

                                            {{
                                                $purchaseOrder
                                                    ->po_date
                                                    ->format('d-m-Y')
                                            }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Expected Delivery --}}

                                    <td>

                                        @if(
                                            $purchaseOrder
                                                ->expected_delivery_date
                                        )

                                            {{
                                                $purchaseOrder
                                                    ->expected_delivery_date
                                                    ->format('d-m-Y')
                                            }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Amount --}}

                                    <td class="text-end">

                                        <strong>

                                            {{
                                                $purchaseOrder->currency
                                            }}

                                            {{

                                                number_format(
                                                    (float)
                                                    $purchaseOrder
                                                        ->total_amount,
                                                    2
                                                )

                                            }}

                                        </strong>

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        @php

                                            $statusClass = match(
                                                $purchaseOrder->status
                                            ) {

                                                'Draft' =>
                                                    'bg-secondary',

                                                'Submitted' =>
                                                    'bg-warning text-dark',

                                                'Approved' =>
                                                    'bg-info text-dark',

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
                                            class="badge {{ $statusClass }}"
                                        >

                                            {{ $purchaseOrder->status }}

                                        </span>

                                    </td>


                                    {{-- Actions --}}

                                    <td>

                                        <div class="d-flex gap-1">

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
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>

                                            @if(
                                                in_array(
                                                    $purchaseOrder->status,
                                                    [
                                                        'Issued',
                                                        'Partially Delivered',
                                                        'Fully Delivered'
                                                    ]
                                                )
                                            )

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
                                                    class="btn btn-sm btn-outline-info"
                                                    title="View Deliveries"
                                                >
                                                    Deliveries
                                                </a>

                                            @endif


                                            @if(
                                                $purchaseOrder->status === 'Draft'
                                            )

                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.procurement.tenders.purchase-orders.destroy',
                                                        [
                                                            'procurementTender' =>
                                                                $procurementTender,

                                                            'purchaseOrder' =>
                                                                $purchaseOrder,
                                                        ]
                                                    ) }}"
                                                    onsubmit="return confirm(
                                                        'Delete this Draft Purchase Order?'
                                                    );"
                                                >

                                                    @csrf

                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                    >
                                                        Delete
                                                    </button>

                                                </form>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


            @else

                {{-- ================================================= --}}
                {{-- EMPTY STATE --}}
                {{-- ================================================= --}}

                <div class="text-center py-5">

                    <div class="fs-5 fw-semibold mb-2">
                        No Purchase Orders Found
                    </div>

                    <p class="text-muted mb-3">
                        No Purchase Order has been created for this tender yet.
                    </p>

                    <a
                        href="{{ route(
                            'admin.procurement.tenders.purchase-orders.create',
                            $procurementTender
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Create Purchase Order
                    </a>

                </div>

            @endif

        </div>


        {{-- ========================================================= --}}
        {{-- PAGINATION --}}
        {{-- ========================================================= --}}

        @if($purchaseOrders->hasPages())

            <div class="card-footer">

                {{ $purchaseOrders->links() }}

            </div>

        @endif

    </div>

</div>

@endsection