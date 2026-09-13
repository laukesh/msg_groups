@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h4 class="mb-1">
                Create Delivery
            </h4>

            <div class="text-muted">
                Purchase Order:
                <strong>
                    {{ $purchaseOrder->po_number }}
                </strong>
            </div>

        </div>

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

    </div>


    {{-- ========================================================= --}}
    {{-- ERROR MESSAGE --}}
    {{-- ========================================================= --}}

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
    {{-- VALIDATION ERRORS --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
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


    <form
        method="POST"
        action="{{ route(
            'admin.procurement.tenders.purchase-orders.deliveries.store',
            [
                'procurementTender' => $procurementTender,
                'purchaseOrder' => $purchaseOrder,
            ]
        ) }}"
    >

        @csrf


        {{-- ===================================================== --}}
        {{-- PO SUMMARY --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <strong>
                    Purchase Order Summary
                </strong>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            PO Number
                        </small>

                        <strong>
                            {{ $purchaseOrder->po_number }}
                        </strong>

                    </div>


                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            Supplier
                        </small>

                        <strong>
                            {{ $purchaseOrder->supplier_name ?: '—' }}
                        </strong>

                    </div>


                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            PO Date
                        </small>

                        <strong>

                            @if($purchaseOrder->po_date)

                                {{ $purchaseOrder->po_date->format('d-m-Y') }}

                            @else

                                —

                            @endif

                        </strong>

                    </div>


                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            Expected Delivery
                        </small>

                        <strong>

                            @if($purchaseOrder->expected_delivery_date)

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

                        <strong>

                            @if($purchaseOrder->award)

                                {{ $purchaseOrder->award->award_number }}

                            @else

                                —

                            @endif

                        </strong>

                    </div>


                    <div class="col-md-6">

                        <small class="text-muted d-block">
                            Contract
                        </small>

                        <strong>

                            @if($purchaseOrder->contract)

                                {{ $purchaseOrder->contract->contract_number }}

                            @else

                                —

                            @endif

                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- DELIVERY INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <strong>
                    Delivery Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    {{-- Delivery Number --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Delivery Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="System Generated"
                            readonly
                        >

                        <small class="text-muted">
                            Delivery number will be generated automatically.
                        </small>

                    </div>


                    {{-- Delivery Date --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Delivery Date
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="delivery_date"
                            class="form-control"
                            value="{{ old(
                                'delivery_date',
                                now()->format('Y-m-d')
                            ) }}"
                            required
                        >

                    </div>


                    {{-- Challan Number --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Challan Number
                        </label>

                        <input
                            type="text"
                            name="challan_number"
                            class="form-control"
                            value="{{ old('challan_number') }}"
                            placeholder="Supplier challan number"
                        >

                    </div>


                    {{-- Challan Date --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Challan Date
                        </label>

                        <input
                            type="date"
                            name="challan_date"
                            class="form-control"
                            value="{{ old('challan_date') }}"
                        >

                    </div>


                    {{-- Vehicle Number --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Vehicle Number
                        </label>

                        <input
                            type="text"
                            name="vehicle_number"
                            class="form-control"
                            value="{{ old('vehicle_number') }}"
                            placeholder="e.g. UP32AB1234"
                        >

                    </div>


                    {{-- Transporter --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Transporter Name
                        </label>

                        <input
                            type="text"
                            name="transporter_name"
                            class="form-control"
                            value="{{ old('transporter_name') }}"
                            placeholder="Transporter name"
                        >

                    </div>


                    {{-- Delivery Address --}}

                    <div class="col-md-12">

                        <label class="form-label">
                            Delivery Address
                        </label>

                        <textarea
                            name="delivery_address"
                            class="form-control"
                            rows="3"
                            placeholder="Delivery location"
                        >{{ old(
                            'delivery_address',
                            $purchaseOrder->delivery_address
                        ) }}</textarea>

                    </div>


                    {{-- Remarks --}}

                    <div class="col-md-12">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            class="form-control"
                            rows="3"
                            placeholder="Delivery remarks"
                        >{{ old('remarks') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- DELIVERY ITEMS --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header d-flex justify-content-between">

                <strong>
                    Delivery Items
                </strong>

                <span class="badge bg-info text-dark">
                    {{ $items->count() }} Pending Item(s)
                </span>

            </div>


            <div class="card-body p-0">

                @if($items->count())

                    <div class="table-responsive">

                        <table class="table table-bordered mb-0 align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th style="width:40px;">
                                        #
                                    </th>

                                    <th>
                                        Item
                                    </th>

                                    <th>
                                        Ordered
                                    </th>

                                    <th>
                                        Previously Delivered
                                    </th>

                                    <th>
                                        Pending
                                    </th>

                                    <th style="width:150px;">
                                        Deliver Now
                                    </th>

                                    <th style="width:140px;">
                                        Accepted
                                    </th>

                                    <th style="width:140px;">
                                        Rejected
                                    </th>

                                    <th>
                                        Remarks
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($items as $index => $item)

                                    <tr>

                                        {{-- Number --}}

                                        <td>
                                            {{ $index + 1 }}
                                        </td>


                                        {{-- Item --}}

                                        <td>

                                            <input
                                                type="hidden"
                                                name="items[{{ $index }}][purchase_order_item_id]"
                                                value="{{ $item->id }}"
                                            >

                                            <strong>
                                                {{ $item->item_name }}
                                            </strong>

                                            @if($item->item_code)

                                                <div class="small text-muted">
                                                    Code:
                                                    {{ $item->item_code }}
                                                </div>

                                            @endif

                                            @if($item->description)

                                                <div class="small text-muted">
                                                    {{ $item->description }}
                                                </div>

                                            @endif

                                        </td>


                                        {{-- Ordered --}}

                                        <td>

                                            <span class="fw-semibold">

                                                {{
                                                    number_format(
                                                        (float)
                                                        $item->quantity,
                                                        3
                                                    )
                                                }}

                                            </span>

                                            {{ $item->unit }}

                                        </td>


                                        {{-- Previously Delivered --}}

                                        <td>

                                            {{
                                                number_format(
                                                    (float)
                                                    $item
                                                        ->previously_delivered_quantity,
                                                    3
                                                )
                                            }}

                                            {{ $item->unit }}

                                        </td>


                                        {{-- Pending --}}

                                        <td>

                                            <span class="badge bg-warning text-dark">

                                                {{
                                                    number_format(
                                                        (float)
                                                        $item->pending_quantity,
                                                        3
                                                    )
                                                }}

                                                {{ $item->unit }}

                                            </span>

                                        </td>


                                        {{-- Deliver Now --}}

                                        <td>

                                            <input
                                                type="number"
                                                step="0.001"
                                                min="0"
                                                max="{{ $item->pending_quantity }}"
                                                name="items[{{ $index }}][delivered_quantity]"
                                                class="form-control delivered-qty"
                                                value="{{ old(
                                                    'items.' . $index . '.delivered_quantity',
                                                    0
                                                ) }}"
                                                data-index="{{ $index }}"
                                                data-pending="{{ $item->pending_quantity }}"
                                                required
                                            >

                                            <small class="text-muted">

                                                Max:
                                                {{
                                                    number_format(
                                                        (float)
                                                        $item->pending_quantity,
                                                        3
                                                    )
                                                }}

                                            </small>

                                        </td>


                                        {{-- Accepted --}}

                                        <td>

                                            <input
                                                type="number"
                                                step="0.001"
                                                min="0"
                                                name="items[{{ $index }}][accepted_quantity]"
                                                class="form-control accepted-qty"
                                                value="{{ old(
                                                    'items.' . $index . '.accepted_quantity',
                                                    0
                                                ) }}"
                                                data-index="{{ $index }}"
                                            >

                                        </td>


                                        {{-- Rejected --}}

                                        <td>

                                            <input
                                                type="number"
                                                step="0.001"
                                                min="0"
                                                name="items[{{ $index }}][rejected_quantity]"
                                                class="form-control rejected-qty"
                                                value="{{ old(
                                                    'items.' . $index . '.rejected_quantity',
                                                    0
                                                ) }}"
                                                data-index="{{ $index }}"
                                            >

                                        </td>


                                        {{-- Remarks --}}

                                        <td>

                                            <input
                                                type="text"
                                                name="items[{{ $index }}][remarks]"
                                                class="form-control"
                                                value="{{ old(
                                                    'items.' . $index . '.remarks'
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

                    <div class="text-center py-5">

                        <div class="fs-5 fw-semibold mb-2">
                            No Pending Items
                        </div>

                        <p class="text-muted mb-0">
                            All Purchase Order quantities have already
                            been delivered.
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- ACTIONS --}}
        {{-- ===================================================== --}}

        @if($items->count())

            <div class="card shadow-sm mb-5">

                <div class="card-body">

                    <div class="d-flex justify-content-end gap-2">

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
                            class="btn btn-outline-secondary"
                        >
                            Cancel
                        </a>


                        <button
                            type="submit"
                            class="btn btn-primary"
                            id="saveDeliveryBtn"
                        >
                            Save Delivery
                        </button>

                    </div>

                </div>

            </div>

        @endif

    </form>

</div>


{{-- ============================================================= --}}
{{-- JAVASCRIPT VALIDATION --}}
{{-- ============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const deliveredInputs =
        document.querySelectorAll('.delivered-qty');


    deliveredInputs.forEach(function (input) {

        input.addEventListener('input', function () {

            const index =
                this.dataset.index;

            const pending =
                parseFloat(
                    this.dataset.pending
                ) || 0;

            let delivered =
                parseFloat(
                    this.value
                ) || 0;


            /*
            |--------------------------------------------------------------------------
            | Prevent Over Delivery
            |--------------------------------------------------------------------------
            */

            if (delivered > pending) {

                this.value =
                    pending;

                delivered =
                    pending;

            }


            /*
            |--------------------------------------------------------------------------
            | Accepted + Rejected Cannot Exceed Delivered
            |--------------------------------------------------------------------------
            */

            const accepted =
                document.querySelector(
                    '.accepted-qty[data-index="' +
                    index +
                    '"]'
                );

            const rejected =
                document.querySelector(
                    '.rejected-qty[data-index="' +
                    index +
                    '"]'
                );


            if (accepted) {

                accepted.max =
                    delivered;

                if (
                    parseFloat(accepted.value || 0)
                    >
                    delivered
                ) {

                    accepted.value =
                        delivered;
                }
            }


            if (rejected) {

                rejected.max =
                    delivered;

                if (
                    parseFloat(rejected.value || 0)
                    >
                    delivered
                ) {

                    rejected.value =
                        delivered;
                }
            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Accepted Quantity Validation
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.accepted-qty')
        .forEach(function (input) {

            input.addEventListener(
                'input',
                function () {

                    const index =
                        this.dataset.index;

                    const deliveredInput =
                        document.querySelector(
                            '.delivered-qty[data-index="' +
                            index +
                            '"]'
                        );

                    const rejectedInput =
                        document.querySelector(
                            '.rejected-qty[data-index="' +
                            index +
                            '"]'
                        );


                    const delivered =
                        parseFloat(
                            deliveredInput?.value
                        ) || 0;

                    let accepted =
                        parseFloat(
                            this.value
                        ) || 0;


                    if (
                        accepted > delivered
                    ) {

                        accepted =
                            delivered;

                        this.value =
                            delivered;
                    }


                    if (rejectedInput) {

                        const rejected =
                            parseFloat(
                                rejectedInput.value
                            ) || 0;


                        if (
                            accepted + rejected
                            >
                            delivered
                        ) {

                            rejectedInput.value =
                                Math.max(
                                    0,
                                    delivered - accepted
                                );
                        }
                    }

                }
            );

        });


    /*
    |--------------------------------------------------------------------------
    | Rejected Quantity Validation
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.rejected-qty')
        .forEach(function (input) {

            input.addEventListener(
                'input',
                function () {

                    const index =
                        this.dataset.index;

                    const deliveredInput =
                        document.querySelector(
                            '.delivered-qty[data-index="' +
                            index +
                            '"]'
                        );

                    const acceptedInput =
                        document.querySelector(
                            '.accepted-qty[data-index="' +
                            index +
                            '"]'
                        );


                    const delivered =
                        parseFloat(
                            deliveredInput?.value
                        ) || 0;

                    const accepted =
                        parseFloat(
                            acceptedInput?.value
                        ) || 0;

                    let rejected =
                        parseFloat(
                            this.value
                        ) || 0;


                    const maxRejected =
                        Math.max(
                            0,
                            delivered - accepted
                        );


                    if (
                        rejected > maxRejected
                    ) {

                        this.value =
                            maxRejected;
                    }

                }
            );

        });


    /*
    |--------------------------------------------------------------------------
    | Form Submit Validation
    |--------------------------------------------------------------------------
    */

    const form =
        document.querySelector(
            'form'
        );


    if (form) {

        form.addEventListener(
            'submit',
            function (event) {

                let hasDelivery =
                    false;

                let valid =
                    true;


                document
                    .querySelectorAll('.delivered-qty')
                    .forEach(function (input) {

                        const delivered =
                            parseFloat(
                                input.value
                            ) || 0;

                        const pending =
                            parseFloat(
                                input.dataset.pending
                            ) || 0;


                        if (
                            delivered > 0
                        ) {

                            hasDelivery =
                                true;
                        }


                        if (
                            delivered > pending
                        ) {

                            valid =
                                false;

                            input.classList.add(
                                'is-invalid'
                            );

                        } else {

                            input.classList.remove(
                                'is-invalid'
                            );

                        }

                    });


                if (!hasDelivery) {

                    event.preventDefault();

                    alert(
                        'Please enter at least one delivery quantity.'
                    );

                    return;
                }


                if (!valid) {

                    event.preventDefault();

                    alert(
                        'Delivery quantity cannot exceed the pending quantity.'
                    );

                }

            }
        );

    }

});

</script>

@endsection