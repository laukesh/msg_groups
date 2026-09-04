@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Create Purchase Order
            </h4>

            <div class="text-muted">
                Tender:
                <strong>
                    {{ $procurementTender->tender_number ?? 'N/A' }}
                </strong>
            </div>
        </div>

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


    {{-- ========================================================= --}}
    {{-- Validation Errors --}}
    {{-- ========================================================= --}}

    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Success / Error --}}
    {{-- ========================================================= --}}

    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'admin.procurement.tenders.purchase-orders.store',
            $procurementTender
        ) }}"
        id="purchaseOrderForm"
    >

        @csrf


        {{-- ===================================================== --}}
        {{-- PO BASIC INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Purchase Order Information
                </strong>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Award --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Procurement Award
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="procurement_award_id"
                            id="procurement_award_id"
                            class="form-select @error('procurement_award_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                -- Select Award --
                            </option>

                            @foreach($awards as $award)

                                <option
                                    value="{{ $award->id }}"
                                    data-supplier="{{ $award->bidder_name }}"
                                    data-amount="{{ $award->awarded_amount }}"
                                    data-currency="{{ $award->currency }}"
                                    {{ old('procurement_award_id') == $award->id ? 'selected' : '' }}
                                >

                                    {{ $award->award_number }}
                                    -
                                    {{ $award->award_title }}

                                    @if($award->bidder_name)
                                        | {{ $award->bidder_name }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('procurement_award_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Contract --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Procurement Contract
                        </label>

                        <select
                            name="procurement_contract_id"
                            id="procurement_contract_id"
                            class="form-select @error('procurement_contract_id') is-invalid @enderror"
                        >

                            <option value="">
                                -- Select Contract --
                            </option>

                            @foreach($contracts as $contract)

                                <option
                                    value="{{ $contract->id }}"
                                    data-award="{{ $contract->procurement_award_id }}"
                                    {{ old('procurement_contract_id') == $contract->id ? 'selected' : '' }}
                                >

                                    {{ $contract->contract_number }}
                                    -
                                    {{ $contract->contract_title }}

                                </option>

                            @endforeach

                        </select>

                        <small class="text-muted">
                            Required when the selected award requires a contract.
                        </small>

                        @error('procurement_contract_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                    {{-- PO Number --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                PO Number
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="System Generated"
                                readonly
                            >

                            <small class="text-muted">
                                PO number will be generated automatically when the PO is saved.
                            </small>

                        </div>


                    {{-- PO Title --}}

                    <div class="col-md-8">

                        <label class="form-label">
                            PO Title
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="po_title"
                            class="form-control"
                            value="{{ old('po_title') }}"
                            placeholder="Purchase Order Title"
                            required
                        >

                    </div>


                    {{-- PO Date --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            PO Date
                        </label>

                        <input
                            type="date"
                            name="po_date"
                            class="form-control"
                            value="{{ old(
                                'po_date',
                                now()->format('Y-m-d')
                            ) }}"
                        >

                    </div>


                    {{-- Expected Delivery Date --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Expected Delivery Date
                        </label>

                        <input
                            type="date"
                            name="expected_delivery_date"
                            class="form-control"
                            value="{{ old('expected_delivery_date') }}"
                        >

                    </div>


                    {{-- Currency --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Currency
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="currency"
                            id="currency"
                            class="form-control"
                            value="{{ old('currency', 'INR') }}"
                            maxlength="10"
                            required
                        >

                    </div>


                    {{-- Supplier --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Supplier / Contractor
                        </label>

                        <input
                            type="text"
                            name="supplier_name"
                            id="supplier_name"
                            class="form-control"
                            value="{{ old('supplier_name') }}"
                        >

                    </div>


                    {{-- Award Amount Reference --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Award Amount
                        </label>

                        <div class="input-group">

                            <span
                                class="input-group-text"
                                id="award_currency"
                            >
                                INR
                            </span>

                            <input
                                type="text"
                                id="award_amount"
                                class="form-control"
                                readonly
                            >

                        </div>

                    </div>


                    {{-- Delivery Address --}}

                    <div class="col-md-12">

                        <label class="form-label">
                            Delivery Address
                        </label>

                        <textarea
                            name="delivery_address"
                            class="form-control"
                            rows="2"
                        >{{ old('delivery_address') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- PO ITEMS --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header d-flex justify-content-between align-items-center">

                <strong>
                    Purchase Order Items
                </strong>

                <button
                    type="button"
                    class="btn btn-sm btn-primary"
                    id="addItemBtn"
                >
                    + Add Item
                </button>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table
                        class="table table-bordered table-hover mb-0"
                        id="itemsTable"
                    >

                        <thead class="table-light">

                            <tr>

                                <th style="width: 100px;">
                                    Code
                                </th>

                                <th style="min-width: 180px;">
                                    Item
                                </th>

                                <th style="width: 110px;">
                                    Qty
                                </th>

                                <th style="width: 100px;">
                                    Unit
                                </th>

                                <th style="width: 130px;">
                                    Unit Price
                                </th>

                                <th style="width: 100px;">
                                    Tax %
                                </th>

                                <th style="width: 130px;">
                                    Discount
                                </th>

                                <th style="width: 140px;">
                                    Line Total
                                </th>

                                <th style="width: 60px;">
                                </th>

                            </tr>

                        </thead>


                        <tbody id="itemsBody">

                            @php

                                $oldItems = old('items', [
                                    [
                                        'item_code' => '',
                                        'item_name' => '',
                                        'description' => '',
                                        'quantity' => 1,
                                        'unit' => 'Nos',
                                        'unit_price' => 0,
                                        'tax_percentage' => 0,
                                        'discount_amount' => 0,
                                        'required_delivery_date' => '',
                                        'remarks' => '',
                                    ]
                                ]);

                            @endphp


                            @foreach($oldItems as $index => $item)

                                <tr class="item-row">

                                    <td>

                                        <input
                                            type="text"
                                            name="items[{{ $index }}][item_code]"
                                            class="form-control form-control-sm"
                                            value="{{ $item['item_code'] ?? '' }}"
                                        >

                                    </td>


                                    <td>

                                        <input
                                            type="text"
                                            name="items[{{ $index }}][item_name]"
                                            class="form-control form-control-sm"
                                            value="{{ $item['item_name'] ?? '' }}"
                                            required
                                        >

                                        <input
                                            type="hidden"
                                            name="items[{{ $index }}][description]"
                                            value="{{ $item['description'] ?? '' }}"
                                        >

                                    </td>


                                    <td>

                                        <input
                                            type="number"
                                            name="items[{{ $index }}][quantity]"
                                            class="form-control form-control-sm item-quantity"
                                            value="{{ $item['quantity'] ?? 1 }}"
                                            min="0.001"
                                            step="0.001"
                                            required
                                        >

                                    </td>


                                    <td>

                                        <input
                                            type="text"
                                            name="items[{{ $index }}][unit]"
                                            class="form-control form-control-sm"
                                            value="{{ $item['unit'] ?? 'Nos' }}"
                                            required
                                        >

                                    </td>


                                    <td>

                                        <input
                                            type="number"
                                            name="items[{{ $index }}][unit_price]"
                                            class="form-control form-control-sm item-unit-price"
                                            value="{{ $item['unit_price'] ?? 0 }}"
                                            min="0"
                                            step="0.01"
                                            required
                                        >

                                    </td>


                                    <td>

                                        <input
                                            type="number"
                                            name="items[{{ $index }}][tax_percentage]"
                                            class="form-control form-control-sm item-tax"
                                            value="{{ $item['tax_percentage'] ?? 0 }}"
                                            min="0"
                                            step="0.01"
                                        >

                                    </td>


                                    <td>

                                        <input
                                            type="number"
                                            name="items[{{ $index }}][discount_amount]"
                                            class="form-control form-control-sm item-discount"
                                            value="{{ $item['discount_amount'] ?? 0 }}"
                                            min="0"
                                            step="0.01"
                                        >

                                    </td>


                                    <td>

                                        <input
                                            type="text"
                                            class="form-control form-control-sm item-line-total"
                                            value="0.00"
                                            readonly
                                        >

                                    </td>


                                    <td class="text-center">

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger remove-item"
                                        >
                                            ×
                                        </button>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- TOTALS --}}
        {{-- ===================================================== --}}

        <div class="row justify-content-end mb-4">

            <div class="col-md-5">

                <div class="card">

                    <div class="card-header">
                        <strong>PO Summary</strong>
                    </div>

                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-2">

                            <span>
                                Subtotal
                            </span>

                            <strong>
                                <span id="summaryCurrency">
                                    INR
                                </span>

                                <span id="subtotalDisplay">
                                    0.00
                                </span>
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-2">

                            <span>
                                Tax
                            </span>

                            <strong>
                                <span id="taxDisplay">
                                    0.00
                                </span>
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-2">

                            <span>
                                Discount
                            </span>

                            <strong>
                                <span id="discountDisplay">
                                    0.00
                                </span>
                            </strong>

                        </div>


                        <hr>


                        <div class="d-flex justify-content-between">

                            <strong>
                                Grand Total
                            </strong>

                            <strong class="fs-5">

                                <span id="totalCurrency">
                                    INR
                                </span>

                                <span id="totalDisplay">
                                    0.00
                                </span>

                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- TERMS --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Terms & Remarks
                </strong>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Payment Terms
                        </label>

                        <textarea
                            name="payment_terms"
                            class="form-control"
                            rows="3"
                        >{{ old('payment_terms') }}</textarea>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Delivery Terms
                        </label>

                        <textarea
                            name="delivery_terms"
                            class="form-control"
                            rows="3"
                        >{{ old('delivery_terms') }}</textarea>

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            Terms & Conditions
                        </label>

                        <textarea
                            name="terms_and_conditions"
                            class="form-control"
                            rows="4"
                        >{{ old('terms_and_conditions') }}</textarea>

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            class="form-control"
                            rows="3"
                        >{{ old('remarks') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- ACTIONS --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.procurement.tenders.purchase-orders.index',
                    $procurementTender
                ) }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Purchase Order
            </button>

        </div>

    </form>

</div>


{{-- ============================================================= --}}
{{-- JavaScript --}}
{{-- ============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    let itemIndex =
        document.querySelectorAll('.item-row').length;


    /*
    |--------------------------------------------------------------------------
    | Award Selection
    |--------------------------------------------------------------------------
    */

    const awardSelect =
        document.getElementById('procurement_award_id');

    const supplierInput =
        document.getElementById('supplier_name');

    const currencyInput =
        document.getElementById('currency');

    const awardAmountInput =
        document.getElementById('award_amount');

    const awardCurrency =
        document.getElementById('award_currency');

    const summaryCurrency =
        document.getElementById('summaryCurrency');

    const totalCurrency =
        document.getElementById('totalCurrency');


    awardSelect.addEventListener(
        'change',
        function () {

            const option =
                this.options[this.selectedIndex];

            if (!option || !option.value) {

                return;
            }


            const supplier =
                option.dataset.supplier || '';

            const amount =
                option.dataset.amount || '0';

            const currency =
                option.dataset.currency || 'INR';


            if (!supplierInput.value) {

                supplierInput.value =
                    supplier;
            }


            currencyInput.value =
                currency;

            awardAmountInput.value =
                parseFloat(amount).toFixed(2);

            awardCurrency.textContent =
                currency;

            summaryCurrency.textContent =
                currency;

            totalCurrency.textContent =
                currency;
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Contract Filtering
    |--------------------------------------------------------------------------
    */

    const contractSelect =
        document.getElementById(
            'procurement_contract_id'
        );


    function filterContracts() {

        const awardId =
            awardSelect.value;


        Array.from(
            contractSelect.options
        ).forEach(function (option) {

            if (!option.value) {

                return;
            }


            const contractAward =
                option.dataset.award;


            if (
                !awardId ||
                contractAward === awardId
            ) {

                option.hidden = false;

            } else {

                option.hidden = true;

                if (
                    option.selected
                ) {

                    contractSelect.value = '';
                }
            }

        });
    }


    awardSelect.addEventListener(
        'change',
        filterContracts
    );


    filterContracts();


    /*
    |--------------------------------------------------------------------------
    | Calculate Row
    |--------------------------------------------------------------------------
    */

    function calculateRow(row) {

        const quantity =
            parseFloat(
                row.querySelector(
                    '.item-quantity'
                ).value
            ) || 0;


        const unitPrice =
            parseFloat(
                row.querySelector(
                    '.item-unit-price'
                ).value
            ) || 0;


        const taxPercentage =
            parseFloat(
                row.querySelector(
                    '.item-tax'
                ).value
            ) || 0;


        const discount =
            parseFloat(
                row.querySelector(
                    '.item-discount'
                ).value
            ) || 0;


        const baseAmount =
            quantity * unitPrice;


        const taxAmount =
            baseAmount *
            (taxPercentage / 100);


        const lineTotal =
            baseAmount +
            taxAmount -
            discount;


        row.querySelector(
            '.item-line-total'
        ).value =
            Math.max(
                0,
                lineTotal
            ).toFixed(2);


        return {

            subtotal: baseAmount,

            tax: taxAmount,

            discount: discount,

            total: Math.max(
                0,
                lineTotal
            )

        };
    }


    /*
    |--------------------------------------------------------------------------
    | Calculate PO Totals
    |--------------------------------------------------------------------------
    */

    function calculateTotals() {

        let subtotal = 0;
        let tax = 0;
        let discount = 0;
        let total = 0;


        document
            .querySelectorAll('.item-row')
            .forEach(function (row) {

                const result =
                    calculateRow(row);


                subtotal +=
                    result.subtotal;

                tax +=
                    result.tax;

                discount +=
                    result.discount;

                total +=
                    result.total;
            });


        document.getElementById(
            'subtotalDisplay'
        ).textContent =
            subtotal.toFixed(2);


        document.getElementById(
            'taxDisplay'
        ).textContent =
            tax.toFixed(2);


        document.getElementById(
            'discountDisplay'
        ).textContent =
            discount.toFixed(2);


        document.getElementById(
            'totalDisplay'
        ).textContent =
            total.toFixed(2);
    }


    /*
    |--------------------------------------------------------------------------
    | Add Item
    |--------------------------------------------------------------------------
    */

    document.getElementById(
        'addItemBtn'
    ).addEventListener(
        'click',
        function () {

            const tbody =
                document.getElementById(
                    'itemsBody'
                );


            const row =
                document.createElement('tr');


            row.className =
                'item-row';


            row.innerHTML = `

                <td>

                    <input
                        type="text"
                        name="items[${itemIndex}][item_code]"
                        class="form-control form-control-sm"
                    >

                </td>


                <td>

                    <input
                        type="text"
                        name="items[${itemIndex}][item_name]"
                        class="form-control form-control-sm"
                        required
                    >

                    <input
                        type="hidden"
                        name="items[${itemIndex}][description]"
                        value=""
                    >

                </td>


                <td>

                    <input
                        type="number"
                        name="items[${itemIndex}][quantity]"
                        class="form-control form-control-sm item-quantity"
                        value="1"
                        min="0.001"
                        step="0.001"
                        required
                    >

                </td>


                <td>

                    <input
                        type="text"
                        name="items[${itemIndex}][unit]"
                        class="form-control form-control-sm"
                        value="Nos"
                        required
                    >

                </td>


                <td>

                    <input
                        type="number"
                        name="items[${itemIndex}][unit_price]"
                        class="form-control form-control-sm item-unit-price"
                        value="0"
                        min="0"
                        step="0.01"
                        required
                    >

                </td>


                <td>

                    <input
                        type="number"
                        name="items[${itemIndex}][tax_percentage]"
                        class="form-control form-control-sm item-tax"
                        value="0"
                        min="0"
                        step="0.01"
                    >

                </td>


                <td>

                    <input
                        type="number"
                        name="items[${itemIndex}][discount_amount]"
                        class="form-control form-control-sm item-discount"
                        value="0"
                        min="0"
                        step="0.01"
                    >

                </td>


                <td>

                    <input
                        type="text"
                        class="form-control form-control-sm item-line-total"
                        value="0.00"
                        readonly
                    >

                </td>


                <td class="text-center">

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger remove-item"
                    >
                        ×
                    </button>

                </td>

            `;


            tbody.appendChild(row);


            itemIndex++;


            calculateTotals();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Remove Item
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'click',
        function (event) {

            if (
                !event.target.classList.contains(
                    'remove-item'
                )
            ) {

                return;
            }


            const rows =
                document.querySelectorAll(
                    '.item-row'
                );


            if (rows.length <= 1) {

                alert(
                    'At least one PO item is required.'
                );

                return;
            }


            event.target
                .closest('.item-row')
                .remove();


            calculateTotals();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Recalculate When Values Change
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'input',
        function (event) {

            if (
                event.target.closest(
                    '.item-row'
                )
            ) {

                calculateTotals();
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Initial Calculation
    |--------------------------------------------------------------------------
    */

    calculateTotals();

});

</script>

@endsection