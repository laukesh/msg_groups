@extends('layouts.app')

@section('content')

<div class="container-fluid">
    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">

            {{-- TOP ROW --}}
            <div class="d-flex justify-content-between align-items-center gap-3">

                {{-- Title --}}
                <div>
                    <h4 class="mb-1 fw-bold">
                        Purchase Orders
                    </h4>

                    <div class="text-muted small">
                        Procurement Purchase Order Register
                    </div>
                </div>

                {{-- Create --}}
                <a
                    href="{{ route(
                        'admin.projects.procurement.tenders.purchase-orders.create',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                        ]
                    ) }}"
                    class="btn btn-primary px-3 text-nowrap"
                >
                    <i class="ri-add-line me-1"></i>
                    Create Purchase Order
                </a>

            </div>


            {{-- DIVIDER --}}
            <hr class="my-3">


            {{-- BOTTOM ROW --}}
            <div class="d-flex justify-content-between align-items-center gap-4">

                {{-- PROJECT --}}
                <div class="flex-grow-1">

                    <div class="text-muted small mb-1">
                        Project
                    </div>

                    <div class="fw-semibold">
                        {{ $project->project_name ?? 'N/A' }}
                    </div>

                </div>


                {{-- TENDER --}}
                <div style="min-width: 180px;">

                    <div class="text-muted small mb-1">
                        Tender
                    </div>

                    <div class="fw-semibold">
                        {{ $procurementTender->tender_number ?? 'N/A' }}
                    </div>

                </div>


                {{-- NAVIGATION --}}
                <div class="d-flex align-items-center gap-2 flex-shrink-0">

                    {{-- Back to Tender --}}
                    <a
                        href="{{ route(
                            'admin.projects.procurement.tenders.show',
                            [
                                'project' => $project,
                                'procurementTender' => $procurementTender,
                            ]
                        ) }}"
                        class="btn btn-sm btn-outline-secondary text-nowrap"
                    >
                        <i class="ri-arrow-left-line me-1"></i>
                        Tender
                    </a>


                    {{-- Back to Award --}}
                    @if($award)
                        <a
                            href="{{ route(
                                'admin.projects.procurement.tenders.awards.show',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                    'award' => $award,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-primary text-nowrap"
                        >
                            <i class="ri-award-line me-1"></i>
                            Award
                        </a>
                    @endif


                    {{-- Back to Contract --}}
                    @if($contract)
                        <a
                            href="{{ route(
                                'admin.projects.procurement.tenders.contracts.show',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                    'contract' => $contract,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-success text-nowrap"
                        >
                            <i class="ri-file-text-line me-1"></i>
                            Contract
                        </a>
                    @endif

                </div>

            </div>

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

                            @foreach(
                                $purchaseOrders
                                as $index => $purchaseOrder
                            )

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
                                                'admin.projects.procurement.tenders.purchase-orders.show',
                                                [
                                                    'project' =>
                                                        $project,

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

                                        {{
                                            $purchaseOrder->supplier_name
                                            ?: '—'
                                        }}

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
                                                    'admin.projects.procurement.tenders.purchase-orders.show',
                                                    [
                                                        'project' =>
                                                            $project,

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
                                                        'admin.projects.procurement.tenders.purchase-orders.deliveries.index',
                                                        [
                                                            'project' => $project,

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
                                                        'admin.projects.procurement.tenders.purchase-orders.destroy',
                                                        [
                                                            'project' =>
                                                                $project,

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
                        No Purchase Order has been created for this
                        tender yet.
                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.procurement.tenders.purchase-orders.create',
                            [
                                'project' =>
                                    $project,

                                'procurementTender' =>
                                    $procurementTender,
                            ]
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