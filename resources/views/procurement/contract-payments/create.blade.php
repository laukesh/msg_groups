@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ================================================================
         Header
    ================================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Contract:
                {{ $contract->contract_number }}
            </div>

            <h4 class="mb-1">
                Create Payment
            </h4>

            <div class="text-muted">
                {{ $contract->contract_title }}
            </div>

        </div>


        <div class="d-flex flex-wrap gap-2">

            {{-- Back to Tender --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.show',
                    $procurementTender
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Tender
            </a>


            {{-- Back to Contract --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.contracts.show',
                    [
                        'procurementTender' => $procurementTender,
                        'contract' => $contract,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-file-earmark-text me-1"></i>
                Back to Contract
            </a>


            {{-- Back to Invoice --}}
            @if($selectedInvoice)

                <a
                    href="{{ route(
                        'admin.procurement.tenders.contracts.invoices.show',
                        [
                            'procurementTender' => $procurementTender,
                            'contract' => $contract,
                            'invoice' => $selectedInvoice,
                        ]
                    ) }}"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-receipt me-1"></i>
                    Back to Invoice
                </a>

            @endif


            {{-- Back to Payments --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.contracts.payments.index',
                    [
                        'procurementTender' => $procurementTender,
                        'contract' => $contract,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>

    </div>


    {{-- ================================================================
         Errors
    ================================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ================================================================
         Determine Selected Invoice
    ================================================================= --}}

    @php

        $hasSelectedInvoice =
            isset($selectedInvoice)
            && $selectedInvoice;

        $hasInvoices =
            isset($invoices)
            && $invoices->count() > 0;

    @endphp


    {{-- ================================================================
         No Invoice Available
    ================================================================= --}}

    @if(!$hasInvoices && !$hasSelectedInvoice)

        <div class="alert alert-warning">

            <h6 class="mb-2">
                No Invoice Available for Payment
            </h6>

            <p class="mb-0">
                There are currently no approved invoices
                with an outstanding balance for this contract.
            </p>

        </div>

    @else


        {{-- ============================================================
             Payment Form
        ============================================================= --}}

        <form
            method="POST"
            action="{{ route(
                'admin.procurement.tenders.contracts.payments.store',
                [
                    'procurementTender' => $procurementTender,
                    'contract' => $contract,
                ]
            ) }}"
            id="paymentForm"
        >

            @csrf


            {{-- ========================================================
                 Invoice Details
            ========================================================= --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Invoice Details
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row g-3">


                        {{-- Invoice Selection --}}
                        <div class="col-md-8">

                            <label
                                for="procurement_contract_invoice_id"
                                class="form-label"
                            >
                                Invoice
                                <span class="text-danger">*</span>
                            </label>


                            <select
                                name="procurement_contract_invoice_id"
                                id="procurement_contract_invoice_id"
                                class="form-select @error('procurement_contract_invoice_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Select Invoice
                                </option>


                                @if($hasInvoices)

                                    @foreach($invoices as $invoice)

                                        <option
                                            value="{{ $invoice->id }}"

                                            data-amount="{{ number_format(
                                                (float) $invoice->net_amount,
                                                2,
                                                '.',
                                                ''
                                            ) }}"

                                            data-paid="{{ number_format(
                                                (float) $invoice->paid_amount,
                                                2,
                                                '.',
                                                ''
                                            ) }}"

                                            data-balance="{{ number_format(
                                                (float) $invoice->balance_amount,
                                                2,
                                                '.',
                                                ''
                                            ) }}"

                                            data-currency="{{ $invoice->currency }}"

                                            data-milestone="{{ $invoice->procurement_contract_milestone_id ?? '' }}"

                                            {{ old(
                                                'procurement_contract_invoice_id',
                                                $hasSelectedInvoice
                                                    ? $selectedInvoice->id
                                                    : ''
                                            ) == $invoice->id
                                                ? 'selected'
                                                : ''
                                            }}
                                        >

                                            {{ $invoice->invoice_number }}

                                            —

                                            {{ $invoice->invoice_date?->format('d-m-Y') }}

                                            —

                                            Outstanding:

                                            {{ number_format(
                                                (float) $invoice->balance_amount,
                                                2
                                            ) }}

                                            {{ $invoice->currency }}

                                        </option>

                                    @endforeach

                                @endif


                                {{-- Selected Invoice Fallback --}}
                                @if(
                                    $hasSelectedInvoice
                                    &&
                                    !$invoices->contains(
                                        'id',
                                        $selectedInvoice->id
                                    )
                                )

                                    <option
                                        value="{{ $selectedInvoice->id }}"

                                        data-amount="{{ number_format(
                                            (float) $selectedInvoice->net_amount,
                                            2,
                                            '.',
                                            ''
                                        ) }}"

                                        data-paid="{{ number_format(
                                            (float) $selectedInvoice->paid_amount,
                                            2,
                                            '.',
                                            ''
                                        ) }}"

                                        data-balance="{{ number_format(
                                            (float) $selectedInvoice->balance_amount,
                                            2,
                                            '.',
                                            ''
                                        ) }}"

                                        data-currency="{{ $selectedInvoice->currency }}"

                                        data-milestone="{{ $selectedInvoice->procurement_contract_milestone_id ?? '' }}"

                                        selected
                                    >

                                        {{ $selectedInvoice->invoice_number }}

                                        —

                                        {{ $selectedInvoice->invoice_date?->format('d-m-Y') }}

                                        —

                                        Outstanding:

                                        {{ number_format(
                                            (float) $selectedInvoice->balance_amount,
                                            2
                                        ) }}

                                        {{ $selectedInvoice->currency }}

                                    </option>

                                @endif

                            </select>


                            @error('procurement_contract_invoice_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Milestone --}}
                        <div class="col-md-4">

                            <label
                                for="procurement_contract_milestone_id"
                                class="form-label"
                            >
                                Milestone
                            </label>


                            <select
                                name="procurement_contract_milestone_id"
                                id="procurement_contract_milestone_id"
                                class="form-select @error('procurement_contract_milestone_id') is-invalid @enderror"
                            >

                                <option value="">
                                    Select Milestone
                                </option>


                                @foreach($milestones as $milestone)

                                    <option
                                        value="{{ $milestone->id }}"

                                        {{ old(
                                            'procurement_contract_milestone_id',
                                            $hasSelectedInvoice
                                                ? $selectedInvoice->procurement_contract_milestone_id
                                                : ''
                                        ) == $milestone->id
                                            ? 'selected'
                                            : ''
                                        }}
                                    >

                                        {{ $milestone->milestone_number }}

                                        —

                                        {{ $milestone->milestone_title }}

                                    </option>

                                @endforeach

                            </select>


                            @error('procurement_contract_milestone_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- Selected Invoice Information --}}
                    @if($hasSelectedInvoice)

                        <div class="alert alert-info mt-4 mb-0">

                            <div class="row g-3">

                                <div class="col-md-4">

                                    <div class="small text-muted">
                                        Selected Invoice
                                    </div>

                                    <div class="fw-semibold">
                                        {{ $selectedInvoice->invoice_number }}
                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <div class="small text-muted">
                                        Invoice Amount
                                    </div>

                                    <div class="fw-semibold">

                                        {{ $selectedInvoice->currency }}

                                        {{ number_format(
                                            (float) $selectedInvoice->net_amount,
                                            2
                                        ) }}

                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <div class="small text-muted">
                                        Current Outstanding
                                    </div>

                                    <div class="fw-semibold text-danger">

                                        {{ $selectedInvoice->currency }}

                                        {{ number_format(
                                            (float) $selectedInvoice->balance_amount,
                                            2
                                        ) }}

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- Invoice Summary --}}
                    <div
                        id="invoiceSummary"
                        class="row g-3 mt-3 d-none"
                    >

                        <div class="col-md-4">

                            <div class="border rounded p-3">

                                <div class="small text-muted">
                                    Invoice Amount
                                </div>

                                <div
                                    class="fs-5 fw-semibold"
                                    id="invoiceAmount"
                                >
                                    0.00
                                </div>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="border rounded p-3">

                                <div class="small text-muted">
                                    Already Paid
                                </div>

                                <div
                                    class="fs-5 fw-semibold text-success"
                                    id="invoicePaid"
                                >
                                    0.00
                                </div>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="border rounded p-3">

                                <div class="small text-muted">
                                    Outstanding
                                </div>

                                <div
                                    class="fs-5 fw-semibold text-danger"
                                    id="invoiceBalance"
                                >
                                    0.00
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ============================================================
                 Payment Details
            ============================================================= --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Payment Details
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row g-3">


                        {{-- ==================================================
                             Payment Number
                        =================================================== --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                Payment Number
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="bi bi-hash"></i>
                                </span>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="Auto-generated after saving"
                                    readonly
                                >

                            </div>

                            <div class="form-text">
                                Payment number will be generated automatically.
                            </div>

                        </div>


                        {{-- Payment Date --}}
                        <div class="col-md-4">

                            <label
                                for="payment_date"
                                class="form-label"
                            >
                                Payment Date
                                <span class="text-danger">*</span>
                            </label>


                            <input
                                type="date"
                                name="payment_date"
                                id="payment_date"
                                class="form-control @error('payment_date') is-invalid @enderror"
                                value="{{ old(
                                    'payment_date',
                                    now()->format('Y-m-d')
                                ) }}"
                                required
                            >


                            @error('payment_date')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Payment Type --}}
                        <div class="col-md-4">

                            <label
                                for="payment_type"
                                class="form-label"
                            >
                                Payment Type
                                <span class="text-danger">*</span>
                            </label>


                            @php

                                $paymentTypes = [
                                    'Milestone Payment',
                                    'Advance Payment',
                                    'Running Account Payment',
                                    'Final Payment',
                                    'Retention Payment',
                                    'Other',
                                ];

                            @endphp


                            <select
                                name="payment_type"
                                id="payment_type"
                                class="form-select"
                                required
                            >

                                @foreach($paymentTypes as $type)

                                    <option
                                        value="{{ $type }}"
                                        {{ old(
                                            'payment_type',
                                            'Milestone Payment'
                                        ) === $type
                                            ? 'selected'
                                            : ''
                                        }}
                                    >
                                        {{ $type }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Payment Amount --}}
                        <div class="col-md-4">

                            <label
                                for="amount"
                                class="form-label"
                            >
                                Payment Amount
                                <span class="text-danger">*</span>
                            </label>


                            <div class="input-group">

                                <input
                                    type="number"
                                    name="amount"
                                    id="amount"
                                    class="form-control @error('amount') is-invalid @enderror"
                                    value="{{ old('amount') }}"
                                    min="0.01"
                                    step="0.01"
                                    required
                                >

                                <span
                                    class="input-group-text"
                                    id="currencyLabel"
                                >
                                    USD
                                </span>

                            </div>


                            <div
                                class="form-text"
                                id="amountHelp"
                            >
                                Select an invoice to see the maximum
                                payable amount.
                            </div>


                            @error('amount')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Payment Method --}}
                        <div class="col-md-4">

                            <label
                                for="payment_method"
                                class="form-label"
                            >
                                Payment Method
                            </label>


                            <select
                                name="payment_method"
                                id="payment_method"
                                class="form-select"
                            >

                                <option value="">
                                    Select Method
                                </option>

                                @foreach([
                                    'Bank Transfer',
                                    'Cheque',
                                    'NEFT',
                                    'RTGS',
                                    'IMPS',
                                    'Other',
                                ] as $method)

                                    <option
                                        value="{{ $method }}"
                                        {{ old('payment_method') === $method
                                            ? 'selected'
                                            : ''
                                        }}
                                    >
                                        {{ $method }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Transaction Reference --}}
                        <div class="col-md-4">

                            <label
                                for="transaction_reference"
                                class="form-label"
                            >
                                Transaction Reference
                            </label>


                            <input
                                type="text"
                                name="transaction_reference"
                                id="transaction_reference"
                                class="form-control"
                                value="{{ old('transaction_reference') }}"
                                maxlength="150"
                            >

                        </div>


                        {{-- Bank Name --}}
                        <div class="col-md-6">

                            <label
                                for="bank_name"
                                class="form-label"
                            >
                                Bank Name
                            </label>


                            <input
                                type="text"
                                name="bank_name"
                                id="bank_name"
                                class="form-control"
                                value="{{ old('bank_name') }}"
                                maxlength="150"
                            >

                        </div>


                        {{-- Account Reference --}}
                        <div class="col-md-6">

                            <label
                                for="account_reference"
                                class="form-label"
                            >
                                Account Reference
                            </label>


                            <input
                                type="text"
                                name="account_reference"
                                id="account_reference"
                                class="form-control"
                                value="{{ old('account_reference') }}"
                                maxlength="150"
                            >

                        </div>


                        {{-- Description --}}
                        <div class="col-md-6">

                            <label
                                for="description"
                                class="form-label"
                            >
                                Description
                            </label>


                            <textarea
                                name="description"
                                id="description"
                                class="form-control"
                                rows="3"
                            >{{ old('description') }}</textarea>

                        </div>


                        {{-- Remarks --}}
                        <div class="col-md-6">

                            <label
                                for="remarks"
                                class="form-label"
                            >
                                Remarks
                            </label>


                            <textarea
                                name="remarks"
                                id="remarks"
                                class="form-control"
                                rows="3"
                            >{{ old('remarks') }}</textarea>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ============================================================
                 Payment Summary
            ============================================================= --}}

            <div
                id="paymentSummary"
                class="card mb-4 d-none"
            >

                <div class="card-header">

                    <strong>
                        Payment Summary
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-4">

                            <div class="small text-muted">
                                Outstanding Before Payment
                            </div>

                            <div
                                class="fs-5 fw-semibold text-danger"
                                id="summaryOutstanding"
                            >
                                0.00
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="small text-muted">
                                This Payment
                            </div>

                            <div
                                class="fs-5 fw-semibold"
                                id="summaryPayment"
                            >
                                0.00
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="small text-muted">
                                Remaining After Payment
                            </div>

                            <div
                                class="fs-5 fw-semibold text-success"
                                id="summaryRemaining"
                            >
                                0.00
                            </div>

                        </div>

                    </div>


                    <div
                        id="amountWarning"
                        class="alert alert-danger mt-3 d-none mb-0"
                    >
                        Payment amount cannot exceed the invoice
                        outstanding amount.
                    </div>


                    <div
                        id="zeroAmountWarning"
                        class="alert alert-warning mt-3 d-none mb-0"
                    >
                        Please enter a payment amount greater than zero.
                    </div>

                </div>

            </div>


            {{-- ============================================================
                 Buttons
            ============================================================= --}}

            <div class="d-flex justify-content-end gap-2">

                <a
                    href="{{ route(
                        'admin.procurement.tenders.contracts.payments.index',
                        [
                            'procurementTender' => $procurementTender,
                            'contract' => $contract,
                        ]
                    ) }}"
                    class="btn btn-outline-secondary"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="btn btn-primary"
                    id="submitPayment"
                    disabled
                >
                    Create Payment
                </button>

            </div>


        </form>

    @endif

</div>


{{-- ====================================================================
     JavaScript
===================================================================== --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const invoiceSelect =
            document.getElementById(
                'procurement_contract_invoice_id'
            );

        const milestoneSelect =
            document.getElementById(
                'procurement_contract_milestone_id'
            );

        const amountInput =
            document.getElementById(
                'amount'
            );

        const currencyLabel =
            document.getElementById(
                'currencyLabel'
            );

        const invoiceSummary =
            document.getElementById(
                'invoiceSummary'
            );

        const paymentSummary =
            document.getElementById(
                'paymentSummary'
            );

        const invoiceAmount =
            document.getElementById(
                'invoiceAmount'
            );

        const invoicePaid =
            document.getElementById(
                'invoicePaid'
            );

        const invoiceBalance =
            document.getElementById(
                'invoiceBalance'
            );

        const summaryOutstanding =
            document.getElementById(
                'summaryOutstanding'
            );

        const summaryPayment =
            document.getElementById(
                'summaryPayment'
            );

        const summaryRemaining =
            document.getElementById(
                'summaryRemaining'
            );

        const amountWarning =
            document.getElementById(
                'amountWarning'
            );

        const zeroAmountWarning =
            document.getElementById(
                'zeroAmountWarning'
            );

        const amountHelp =
            document.getElementById(
                'amountHelp'
            );

        const submitButton =
            document.getElementById(
                'submitPayment'
            );


        if (
            !invoiceSelect ||
            !amountInput ||
            !submitButton
        ) {
            return;
        }


        let currentBalance = 0;


        function formatAmount(value)
        {
            return Number(
                value || 0
            ).toLocaleString(
                'en-IN',
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );
        }


        function resetInvoiceSummary()
        {

            if (invoiceSummary) {

                invoiceSummary
                    .classList
                    .add('d-none');

            }


            if (paymentSummary) {

                paymentSummary
                    .classList
                    .add('d-none');

            }


            currentBalance = 0;


            amountInput
                .removeAttribute('max');


            amountInput.value = '';


            currencyLabel.textContent =
                'USD';


            amountHelp.textContent =
                'Select an invoice to see the maximum payable amount.';


            submitButton.disabled = true;

        }


        function updateInvoice()
        {

            const option =
                invoiceSelect.options[
                    invoiceSelect.selectedIndex
                ];


            if (
                !option ||
                !option.value
            ) {

                resetInvoiceSummary();

                return;

            }


            const amount =
                parseFloat(
                    option.dataset.amount || 0
                );


            const paid =
                parseFloat(
                    option.dataset.paid || 0
                );


            const balance =
                parseFloat(
                    option.dataset.balance || 0
                );


            const currency =
                option.dataset.currency ||
                'USD';


            const milestone =
                option.dataset.milestone ||
                '';


            currentBalance =
                Math.max(
                    0,
                    balance
                );


            invoiceAmount.textContent =
                formatAmount(amount)
                + ' '
                + currency;


            invoicePaid.textContent =
                formatAmount(paid)
                + ' '
                + currency;


            invoiceBalance.textContent =
                formatAmount(currentBalance)
                + ' '
                + currency;


            currencyLabel.textContent =
                currency;


            amountInput.setAttribute(
                'max',
                currentBalance.toFixed(2)
            );


            amountHelp.textContent =
                'Maximum payment: '
                +
                formatAmount(currentBalance)
                +
                ' '
                +
                currency;


            if (
                milestone &&
                milestoneSelect
            ) {

                const milestoneOption =
                    milestoneSelect.querySelector(
                        'option[value="' +
                        milestone +
                        '"]'
                    );


                if (milestoneOption) {

                    milestoneSelect.value =
                        milestone;

                }

            }


            if (invoiceSummary) {

                invoiceSummary
                    .classList
                    .remove('d-none');

            }


            if (paymentSummary) {

                paymentSummary
                    .classList
                    .remove('d-none');

            }


            updatePaymentSummary();

        }


        function updatePaymentSummary()
        {

            if (!invoiceSelect.value) {

                if (paymentSummary) {

                    paymentSummary
                        .classList
                        .add('d-none');

                }

                submitButton.disabled =
                    true;

                return;

            }


            const payment =
                parseFloat(
                    amountInput.value || 0
                );


            const remaining =
                currentBalance - payment;


            summaryOutstanding.textContent =
                formatAmount(
                    currentBalance
                );


            summaryPayment.textContent =
                formatAmount(
                    payment
                );


            summaryRemaining.textContent =
                formatAmount(
                    Math.max(
                        0,
                        remaining
                    )
                );


            amountWarning
                .classList
                .add('d-none');


            zeroAmountWarning
                .classList
                .add('d-none');


            let hasError = false;


            if (payment <= 0) {

                zeroAmountWarning
                    .classList
                    .remove('d-none');

                hasError = true;

            }


            if (payment > currentBalance) {

                amountWarning
                    .classList
                    .remove('d-none');

                hasError = true;

            }


            if (currentBalance <= 0) {

                hasError = true;

            }


            submitButton.disabled =
                hasError;

        }


        invoiceSelect.addEventListener(
            'change',
            updateInvoice
        );


        amountInput.addEventListener(
            'input',
            updatePaymentSummary
        );


        updateInvoice();

    }
);

</script>

@endsection