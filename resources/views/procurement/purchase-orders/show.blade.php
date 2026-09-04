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
                    {{ $purchaseOrder->po_number }}
                </h4>

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

                <span class="badge {{ $statusClass }}">
                    {{ $purchaseOrder->status }}
                </span>

            </div>

            <div class="text-muted mt-1">
                {{ $purchaseOrder->po_title }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.procurement.tenders.purchase-orders.index',
                    $procurementTender
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Back
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
    {{-- WORKFLOW --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <strong>
                Purchase Order Workflow
            </strong>

        </div>

        <div class="card-body">

            <div class="row text-center">

                {{-- Draft --}}

                <div class="col">

                    <div class="
                        rounded-circle
                        mx-auto
                        mb-2
                        d-flex
                        align-items-center
                        justify-content-center
                        {{ in_array(
                            $purchaseOrder->status,
                            [
                                'Draft',
                                'Submitted',
                                'Approved',
                                'Issued',
                                'Partially Delivered',
                                'Fully Delivered',
                                'Closed'
                            ]
                        )
                            ? 'bg-primary text-white'
                            : 'bg-light'
                        }}
                    "
                    style="width:45px;height:45px;">

                        1

                    </div>

                    <small class="fw-semibold">
                        Draft
                    </small>

                </div>


                {{-- Submitted --}}

                <div class="col">

                    <div class="
                        rounded-circle
                        mx-auto
                        mb-2
                        d-flex
                        align-items-center
                        justify-content-center
                        {{ in_array(
                            $purchaseOrder->status,
                            [
                                'Submitted',
                                'Approved',
                                'Issued',
                                'Partially Delivered',
                                'Fully Delivered',
                                'Closed'
                            ]
                        )
                            ? 'bg-primary text-white'
                            : 'bg-light'
                        }}
                    "
                    style="width:45px;height:45px;">

                        2

                    </div>

                    <small class="fw-semibold">
                        Submitted
                    </small>

                </div>


                {{-- Approved --}}

                <div class="col">

                    <div class="
                        rounded-circle
                        mx-auto
                        mb-2
                        d-flex
                        align-items-center
                        justify-content-center
                        {{ in_array(
                            $purchaseOrder->status,
                            [
                                'Approved',
                                'Issued',
                                'Partially Delivered',
                                'Fully Delivered',
                                'Closed'
                            ]
                        )
                            ? 'bg-primary text-white'
                            : 'bg-light'
                        }}
                    "
                    style="width:45px;height:45px;">

                        3

                    </div>

                    <small class="fw-semibold">
                        Approved
                    </small>

                </div>


                {{-- Issued --}}

                <div class="col">

                    <div class="
                        rounded-circle
                        mx-auto
                        mb-2
                        d-flex
                        align-items-center
                        justify-content-center
                        {{ in_array(
                            $purchaseOrder->status,
                            [
                                'Issued',
                                'Partially Delivered',
                                'Fully Delivered',
                                'Closed'
                            ]
                        )
                            ? 'bg-primary text-white'
                            : 'bg-light'
                        }}
                    "
                    style="width:45px;height:45px;">

                        4

                    </div>

                    <small class="fw-semibold">
                        Issued
                    </small>

                </div>


                {{-- Delivery --}}

                <div class="col">

                    <div class="
                        rounded-circle
                        mx-auto
                        mb-2
                        d-flex
                        align-items-center
                        justify-content-center
                        {{ in_array(
                            $purchaseOrder->status,
                            [
                                'Partially Delivered',
                                'Fully Delivered',
                                'Closed'
                            ]
                        )
                            ? 'bg-primary text-white'
                            : 'bg-light'
                        }}
                    "
                    style="width:45px;height:45px;">

                        5

                    </div>

                    <small class="fw-semibold">
                        Delivery
                    </small>

                </div>


                {{-- Closed --}}

                <div class="col">

                    <div class="
                        rounded-circle
                        mx-auto
                        mb-2
                        d-flex
                        align-items-center
                        justify-content-center
                        {{ $purchaseOrder->status === 'Closed'
                            ? 'bg-dark text-white'
                            : 'bg-light'
                        }}
                    "
                    style="width:45px;height:45px;">

                        6

                    </div>

                    <small class="fw-semibold">
                        Closed
                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ACTIONS --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex flex-wrap gap-2">

                {{-- Submit --}}

                @if($purchaseOrder->status === 'Draft')

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.procurement.tenders.purchase-orders.submit',
                            [
                                'procurementTender' =>
                                    $procurementTender,

                                'purchaseOrder' =>
                                    $purchaseOrder,
                            ]
                        ) }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-warning"
                            onclick="return confirm(
                                'Submit this Purchase Order for approval?'
                            );"
                        >
                            Submit for Approval
                        </button>

                    </form>

                @endif


                {{-- Approve --}}

                @if($purchaseOrder->status === 'Submitted')

                    <button
                        type="button"
                        class="btn btn-info"
                        data-bs-toggle="modal"
                        data-bs-target="#approveModal"
                    >
                        Approve PO
                    </button>

                @endif


                {{-- Issue --}}

                @if($purchaseOrder->status === 'Approved')

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.procurement.tenders.purchase-orders.issue',
                            [
                                'procurementTender' =>
                                    $procurementTender,

                                'purchaseOrder' =>
                                    $purchaseOrder,
                            ]
                        ) }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-success"
                            onclick="return confirm(
                                'Issue this Purchase Order to the supplier?'
                            );"
                        >
                            Issue Purchase Order
                        </button>

                    </form>

                @endif

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
                        class="btn btn-outline-primary"
                    >
                        Deliveries
                    </a>

                @endif

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
                        + Record Delivery
                    </a>

                @endif


                {{-- Delete Draft --}}

                @if($purchaseOrder->status === 'Draft')

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
                            class="btn btn-outline-danger"
                        >
                            Delete Draft
                        </button>

                    </form>

                @endif

                <a
                    href="{{ route(
                        'admin.procurement.tenders.purchase-orders.material-trackings.index',
                        [
                            'procurementTender' => $procurementTender,
                            'purchaseOrder' => $purchaseOrder,
                        ]
                    ) }}"
                    class="btn btn-outline-success"
                >
                    Material Tracking
                </a>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- PO INFORMATION --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">

        {{-- Basic Information --}}

        <div class="col-lg-8">

            <div class="card shadow-sm h-100">

                <div class="card-header">

                    <strong>
                        Purchase Order Information
                    </strong>

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                PO Number
                            </small>

                            <strong>
                                {{ $purchaseOrder->po_number }}
                            </strong>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                PO Date
                            </small>

                            <strong>

                                @if($purchaseOrder->po_date)

                                    {{
                                        $purchaseOrder
                                            ->po_date
                                            ->format('d-m-Y')
                                    }}

                                @else

                                    —

                                @endif

                            </strong>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Supplier
                            </small>

                            <strong>
                                {{ $purchaseOrder->supplier_name ?: '—' }}
                            </strong>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Expected Delivery
                            </small>

                            <strong>

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

                            </strong>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Award
                            </small>

                            @if($purchaseOrder->award)

                                <strong>
                                    {{
                                        $purchaseOrder
                                            ->award
                                            ->award_number
                                    }}
                                </strong>

                            @else

                                —

                            @endif

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Contract
                            </small>

                            @if($purchaseOrder->contract)

                                <strong>
                                    {{
                                        $purchaseOrder
                                            ->contract
                                            ->contract_number
                                    }}
                                </strong>

                            @else

                                <span class="text-muted">
                                    No Contract
                                </span>

                            @endif

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Currency
                            </small>

                            <strong>
                                {{ $purchaseOrder->currency }}
                            </strong>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Status
                            </small>

                            <span
                                class="badge {{ $statusClass }}"
                            >
                                {{ $purchaseOrder->status }}
                            </span>

                        </div>


                        <div class="col-md-12">

                            <small class="text-muted d-block">
                                Delivery Address
                            </small>

                            <div>
                                {{
                                    $purchaseOrder
                                        ->delivery_address
                                    ?: '—'
                                }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Amount Summary --}}

        <div class="col-lg-4">

            <div class="card shadow-sm h-100">

                <div class="card-header">

                    <strong>
                        Amount Summary
                    </strong>

                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">

                        <span>
                            Subtotal
                        </span>

                        <strong>
                            {{ $purchaseOrder->currency }}
                            {{ number_format(
                                (float) $purchaseOrder->subtotal_amount,
                                2
                            ) }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span>
                            Tax
                        </span>

                        <strong>
                            {{ number_format(
                                (float) $purchaseOrder->tax_amount,
                                2
                            ) }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span>
                            Discount
                        </span>

                        <strong>
                            {{ number_format(
                                (float) $purchaseOrder->discount_amount,
                                2
                            ) }}
                        </strong>

                    </div>


                    <hr>


                    <div class="d-flex justify-content-between">

                        <strong>
                            Grand Total
                        </strong>

                        <strong class="fs-5">

                            {{ $purchaseOrder->currency }}

                            {{ number_format(
                                (float) $purchaseOrder->total_amount,
                                2
                            ) }}

                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- PO ITEMS --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <strong>
                Purchase Order Items
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
                                Code
                            </th>

                            <th>
                                Item
                            </th>

                            <th>
                                Quantity
                            </th>

                            <th>
                                Unit
                            </th>

                            <th class="text-end">
                                Unit Price
                            </th>

                            <th class="text-end">
                                Tax %
                            </th>

                            <th class="text-end">
                                Discount
                            </th>

                            <th class="text-end">
                                Line Total
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse(
                            $purchaseOrder->items
                            as $index => $item
                        )

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>


                                <td>
                                    {{ $item->item_code ?: '—' }}
                                </td>


                                <td>

                                    <strong>
                                        {{ $item->item_name }}
                                    </strong>

                                    @if($item->description)

                                        <div class="small text-muted">
                                            {{ $item->description }}
                                        </div>

                                    @endif

                                </td>


                                <td>
                                    {{ number_format(
                                        (float) $item->quantity,
                                        3
                                    ) }}
                                </td>


                                <td>
                                    {{ $item->unit }}
                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        (float) $item->unit_price,
                                        2
                                    ) }}

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        (float) $item->tax_percentage,
                                        2
                                    ) }}%

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        (float) $item->discount_amount,
                                        2
                                    ) }}

                                </td>


                                <td class="text-end">

                                    <strong>

                                        {{ number_format(
                                            (float) $item->line_total,
                                            2
                                        ) }}

                                    </strong>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center text-muted py-4"
                                >
                                    No items found.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- TERMS --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-5">

        <div class="col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-header">

                    <strong>
                        Payment Terms
                    </strong>

                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $purchaseOrder->payment_terms
                            ?: '—'
                        )
                    ) !!}

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-header">

                    <strong>
                        Delivery Terms
                    </strong>

                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $purchaseOrder->delivery_terms
                            ?: '—'
                        )
                    ) !!}

                </div>

            </div>

        </div>


        <div class="col-md-12">

            <div class="card shadow-sm">

                <div class="card-header">

                    <strong>
                        Terms & Conditions
                    </strong>

                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $purchaseOrder->terms_and_conditions
                            ?: '—'
                        )
                    ) !!}

                </div>

            </div>

        </div>


        @if($purchaseOrder->remarks)

            <div class="col-md-12">

                <div class="card shadow-sm">

                    <div class="card-header">

                        <strong>
                            Remarks
                        </strong>

                    </div>

                    <div class="card-body">

                        {!! nl2br(
                            e($purchaseOrder->remarks)
                        ) !!}

                    </div>

                </div>

            </div>

        @endif

    </div>

</div>


{{-- ============================================================= --}}
{{-- APPROVE MODAL --}}
{{-- ============================================================= --}}

@if($purchaseOrder->status === 'Submitted')

<div
    class="modal fade"
    id="approveModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route(
                    'admin.procurement.tenders.purchase-orders.approve',
                    [
                        'procurementTender' =>
                            $procurementTender,

                        'purchaseOrder' =>
                            $purchaseOrder,
                    ]
                ) }}"
            >

                @csrf


                <div class="modal-header">

                    <h5 class="modal-title">
                        Approve Purchase Order
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    <p>
                        You are approving:
                        <strong>
                            {{ $purchaseOrder->po_number }}
                        </strong>
                    </p>


                    <div class="mb-3">

                        <label class="form-label">
                            Approval Remarks
                        </label>

                        <textarea
                            name="approval_remarks"
                            class="form-control"
                            rows="4"
                            placeholder="Enter approval remarks..."
                        ></textarea>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        Approve Purchase Order
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endif

@endsection