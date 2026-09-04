@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h4 class="mb-1">
                Create Material Tracking
            </h4>

            <div class="text-muted">
                Purchase Order:
                <strong>
                    {{ $purchaseOrder->po_number }}
                </strong>
            </div>
        </div>

        <div>
            <a
                href="{{ route(
                    'admin.procurement.tenders.purchase-orders.material-trackings.index',
                    [
                        'procurementTender' => $procurementTender,
                        'purchaseOrder' => $purchaseOrder,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>
        </div>

    </div>


    {{-- =========================================================
         ALERTS
    ========================================================== --}}

    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
         PO INFORMATION
    ========================================================== --}}

    <div class="card mb-3">

        <div class="card-header">
            <strong>Purchase Order Information</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">

                    <label class="form-label text-muted">
                        PO Number
                    </label>

                    <div class="fw-semibold">
                        {{ $purchaseOrder->po_number }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="form-label text-muted">
                        PO Title
                    </label>

                    <div class="fw-semibold">
                        {{ $purchaseOrder->po_title }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="form-label text-muted">
                        Supplier
                    </label>

                    <div class="fw-semibold">
                        {{ $purchaseOrder->supplier_name ?? '-' }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="form-label text-muted">
                        Project
                    </label>

                    <div class="fw-semibold">

                        {{ $purchaseOrder->project?->project_name ?? '-' }}

                        @if($purchaseOrder->project?->project_number)

                            <div class="small text-muted">
                                {{ $purchaseOrder->project->project_number }}
                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         FORM
    ========================================================== --}}

    <form
        method="POST"
        action="{{ route(
            'admin.procurement.tenders.purchase-orders.material-trackings.store',
            [
                'procurementTender' => $procurementTender,
                'purchaseOrder' => $purchaseOrder,
            ]
        ) }}"
    >

        @csrf


        {{-- =====================================================
             TRACKING INFORMATION
        ====================================================== --}}

        <div class="card mb-3">

            <div class="card-header">
                <strong>Tracking Information</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <label
                            for="tracking_date"
                            class="form-label"
                        >
                            Tracking Date
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="tracking_date"
                            id="tracking_date"
                            class="form-control"
                            value="{{ old(
                                'tracking_date',
                                now()->format('Y-m-d')
                            ) }}"
                            required
                        >

                    </div>


                    <div class="col-md-8">

                        <label
                            for="remarks"
                            class="form-label"
                        >
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            id="remarks"
                            rows="2"
                            class="form-control"
                            placeholder="Enter remarks"
                        >{{ old('remarks') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             MATERIALS
        ====================================================== --}}

        <div class="card mb-3">

            <div class="card-header d-flex justify-content-between align-items-center">

                <strong>
                    Available Materials
                </strong>

                <span class="text-muted small">
                    Only accepted quantities with remaining balance are shown.
                </span>

            </div>


            <div class="card-body p-0">

                @if($deliveryItems->count() > 0)

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover mb-0 align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th width="4%">
                                        #
                                    </th>

                                    <th>
                                        Material
                                    </th>

                                    <th>
                                        Item Code
                                    </th>

                                    <th>
                                        Unit
                                    </th>

                                    <th class="text-end">
                                        Ordered
                                    </th>

                                    <th class="text-end">
                                        Delivered
                                    </th>

                                    <th class="text-end">
                                        Accepted
                                    </th>

                                    <th class="text-end">
                                        Already Tracked
                                    </th>

                                    <th class="text-end">
                                        Remaining
                                    </th>

                                    <th class="text-end">
                                        Rejected
                                    </th>

                                    <th width="130">
                                        Issue Qty
                                    </th>

                                    <th width="130">
                                        Consume Qty
                                    </th>

                                    <th width="120">
                                        Balance
                                    </th>

                                    <th>
                                        Remarks
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach(
                                    $deliveryItems
                                    as $index => $deliveryItem
                                )

                                    @php

                                        $purchaseOrderItem =
                                            $deliveryItem->purchaseOrderItem;

                                        /*
                                        |--------------------------------------------------------------------------
                                        | Quantities calculated by Controller
                                        |--------------------------------------------------------------------------
                                        */

                                        $acceptedQuantity =
                                            (float)
                                            (
                                                $deliveryItem
                                                    ->tracking_accepted_quantity
                                                ??
                                                $deliveryItem->accepted_quantity
                                            );

                                        $alreadyTrackedQuantity =
                                            (float)
                                            (
                                                $deliveryItem
                                                    ->already_tracked_quantity
                                                ?? 0
                                            );

                                        $remainingQuantity =
                                            (float)
                                            (
                                                $deliveryItem
                                                    ->remaining_tracking_quantity
                                                ??
                                                max(
                                                    0,
                                                    $acceptedQuantity
                                                    -
                                                    $alreadyTrackedQuantity
                                                )
                                            );


                                        /*
                                        |--------------------------------------------------------------------------
                                        | Old Input
                                        |--------------------------------------------------------------------------
                                        */

                                        $oldIssued =
                                            (float)
                                            old(
                                                "items.$index.issued_quantity",
                                                0
                                            );

                                        $oldConsumed =
                                            (float)
                                            old(
                                                "items.$index.consumed_quantity",
                                                0
                                            );


                                        /*
                                        |--------------------------------------------------------------------------
                                        | Current Balance
                                        |--------------------------------------------------------------------------
                                        */

                                        $balance =
                                            max(
                                                0,
                                                $remainingQuantity
                                                -
                                                $oldIssued
                                            );

                                    @endphp


                                    <tr>

                                        {{-- =================================
                                             SERIAL
                                        ================================== --}}

                                        <td>
                                            {{ $index + 1 }}
                                        </td>


                                        {{-- =================================
                                             MATERIAL
                                        ================================== --}}

                                        <td>

                                            <strong>
                                                {{ $purchaseOrderItem?->item_name ?? '-' }}
                                            </strong>

                                            @if(
                                                $purchaseOrderItem?->description
                                            )

                                                <div class="small text-muted">
                                                    {{ $purchaseOrderItem->description }}
                                                </div>

                                            @endif

                                        </td>


                                        {{-- =================================
                                             ITEM CODE
                                        ================================== --}}

                                        <td>
                                            {{ $purchaseOrderItem?->item_code ?? '-' }}
                                        </td>


                                        {{-- =================================
                                             UNIT
                                        ================================== --}}

                                        <td>
                                            {{ $deliveryItem->unit }}
                                        </td>


                                        {{-- =================================
                                             ORDERED
                                        ================================== --}}

                                        <td class="text-end">

                                            {{ number_format(
                                                (float)
                                                $deliveryItem->ordered_quantity,
                                                3
                                            ) }}

                                        </td>


                                        {{-- =================================
                                             DELIVERED
                                        ================================== --}}

                                        <td class="text-end">

                                            {{ number_format(
                                                (float)
                                                $deliveryItem->delivered_quantity,
                                                3
                                            ) }}

                                        </td>


                                        {{-- =================================
                                             ACCEPTED
                                        ================================== --}}

                                        <td class="text-end">

                                            <span class="badge bg-success">

                                                {{ number_format(
                                                    $acceptedQuantity,
                                                    3
                                                ) }}

                                            </span>

                                        </td>


                                        {{-- =================================
                                             ALREADY TRACKED
                                        ================================== --}}

                                        <td class="text-end">

                                            <span
                                                class="badge bg-secondary"
                                            >

                                                {{ number_format(
                                                    $alreadyTrackedQuantity,
                                                    3
                                                ) }}

                                            </span>

                                        </td>


                                        {{-- =================================
                                             REMAINING
                                        ================================== --}}

                                        <td class="text-end">

                                            <span
                                                class="badge bg-primary"
                                            >

                                                {{ number_format(
                                                    $remainingQuantity,
                                                    3
                                                ) }}

                                            </span>

                                        </td>


                                        {{-- =================================
                                             REJECTED
                                        ================================== --}}

                                        <td class="text-end">

                                            {{ number_format(
                                                (float)
                                                $deliveryItem->rejected_quantity,
                                                3
                                            ) }}

                                        </td>


                                        {{-- =================================
                                             ISSUE QUANTITY
                                        ================================== --}}

                                        <td>

                                            <input
                                                type="hidden"
                                                name="items[{{ $index }}][delivery_item_id]"
                                                value="{{ $deliveryItem->id }}"
                                            >

                                            <input
                                                type="number"
                                                name="items[{{ $index }}][issued_quantity]"
                                                class="form-control form-control-sm issue-quantity"
                                                data-index="{{ $index }}"
                                                data-remaining="{{ $remainingQuantity }}"
                                                value="{{ $oldIssued }}"
                                                min="0"
                                                max="{{ $remainingQuantity }}"
                                                step="0.001"
                                            >

                                            <div
                                                class="small text-muted mt-1"
                                            >
                                                Max:
                                                {{ number_format(
                                                    $remainingQuantity,
                                                    3
                                                ) }}
                                            </div>

                                        </td>


                                        {{-- =================================
                                             CONSUMED QUANTITY
                                        ================================== --}}

                                        <td>

                                            <input
                                                type="number"
                                                name="items[{{ $index }}][consumed_quantity]"
                                                class="form-control form-control-sm consumed-quantity"
                                                data-index="{{ $index }}"
                                                value="{{ $oldConsumed }}"
                                                min="0"
                                                step="0.001"
                                            >

                                            <div
                                                class="small text-muted mt-1"
                                            >
                                                Max = Issue Qty
                                            </div>

                                        </td>


                                        {{-- =================================
                                             BALANCE
                                        ================================== --}}

                                        <td class="text-end">

                                            <span
                                                id="balance-{{ $index }}"
                                                class="fw-semibold"
                                            >
                                                {{ number_format(
                                                    $balance,
                                                    3
                                                ) }}
                                            </span>

                                        </td>


                                        {{-- =================================
                                             REMARKS
                                        ================================== --}}

                                        <td>

                                            <input
                                                type="text"
                                                name="items[{{ $index }}][remarks]"
                                                class="form-control form-control-sm"
                                                value="{{ old(
                                                    "items.$index.remarks"
                                                ) }}"
                                                placeholder="Remarks"
                                            >

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="p-4 text-center">

                        <div class="text-muted mb-2">
                            No material is available for tracking.
                        </div>

                        <div class="small text-muted">
                            Material Tracking can be created only after
                            material is received and accepted through Delivery,
                            and any previously tracked quantity has been
                            excluded.
                        </div>

                    </div>

                @endif

            </div>

        </div>


        {{-- =====================================================
             SUBMIT
        ====================================================== --}}

        @if($deliveryItems->count() > 0)

            <div class="d-flex justify-content-end gap-2">

                <a
                    href="{{ route(
                        'admin.procurement.tenders.purchase-orders.material-trackings.index',
                        [
                            'procurementTender' =>
                                $procurementTender,

                            'purchaseOrder' =>
                                $purchaseOrder,
                        ]
                    ) }}"
                    class="btn btn-outline-secondary"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="btn btn-success"
                >
                    Create Material Tracking
                </button>

            </div>

        @endif

    </form>

</div>


{{-- =============================================================
     JAVASCRIPT
============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Issue Quantity
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.issue-quantity')
        .forEach(function (input) {

            input.addEventListener('input', function () {

                const index =
                    this.dataset.index;

                const remaining =
                    parseFloat(
                        this.dataset.remaining
                    ) || 0;

                let issued =
                    parseFloat(
                        this.value
                    ) || 0;


                /*
                |--------------------------------------------------------------------------
                | Do not allow issued > remaining
                |--------------------------------------------------------------------------
                */

                if (issued > remaining) {

                    issued = remaining;

                    this.value =
                        remaining.toFixed(3);
                }


                /*
                |--------------------------------------------------------------------------
                | Do not allow negative quantity
                |--------------------------------------------------------------------------
                */

                if (issued < 0) {

                    issued = 0;

                    this.value =
                        '0.000';
                }


                /*
                |--------------------------------------------------------------------------
                | Update Balance
                |--------------------------------------------------------------------------
                */

                const balance =
                    Math.max(
                        0,
                        remaining - issued
                    );


                const balanceElement =
                    document.getElementById(
                        'balance-' + index
                    );


                if (balanceElement) {

                    balanceElement.textContent =
                        balance.toFixed(3);

                }


                /*
                |--------------------------------------------------------------------------
                | Validate Consumed Quantity
                |--------------------------------------------------------------------------
                */

                const consumedInput =
                    document.querySelector(
                        '.consumed-quantity[data-index="' +
                        index +
                        '"]'
                    );


                if (consumedInput) {

                    const consumed =
                        parseFloat(
                            consumedInput.value
                        ) || 0;


                    if (consumed > issued) {

                        consumedInput.value =
                            issued.toFixed(3);

                    }

                    consumedInput.max =
                        issued;

                }

            });

        });


    /*
    |--------------------------------------------------------------------------
    | Consumed Quantity
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.consumed-quantity')
        .forEach(function (input) {

            input.addEventListener('input', function () {

                const index =
                    this.dataset.index;

                const issueInput =
                    document.querySelector(
                        '.issue-quantity[data-index="' +
                        index +
                        '"]'
                    );


                if (!issueInput) {
                    return;
                }


                const issued =
                    parseFloat(
                        issueInput.value
                    ) || 0;

                let consumed =
                    parseFloat(
                        this.value
                    ) || 0;


                /*
                |--------------------------------------------------------------------------
                | Consumed cannot exceed issued
                |--------------------------------------------------------------------------
                */

                if (consumed > issued) {

                    consumed = issued;

                    this.value =
                        issued.toFixed(3);
                }


                /*
                |--------------------------------------------------------------------------
                | Do not allow negative quantity
                |--------------------------------------------------------------------------
                */

                if (consumed < 0) {

                    this.value =
                        '0.000';
                }


                this.max =
                    issued;

            });

        });


    /*
    |--------------------------------------------------------------------------
    | Initialize Existing Values
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.issue-quantity')
        .forEach(function (input) {

            input.dispatchEvent(
                new Event('input')
            );

        });

});

</script>

@endsection