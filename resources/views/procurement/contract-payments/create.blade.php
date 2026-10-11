@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Create Payment</h4>

            <div class="text-muted">
                {{ $contract->contract_number ?? 'Contract #' . $contract->id }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.procurement.tenders.contracts.payments.index',
                [
                    'project' => $project,
                    'procurementTender' => $procurementTender,
                    'contract' => $contract,
                ]
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Back to Payments

            </a>

            <a href="{{ route(
                'admin.projects.procurement.tenders.contracts.show',
                [
                    'project' => $project,
                    'procurementTender' => $procurementTender,
                    'contract' => $contract,
                ]
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-file-earmark-text"></i>
                Contract

            </a>

        </div>

    </div>


    {{-- =========================================================
         FLASH MESSAGES
    ========================================================== --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route(
              'admin.projects.procurement.tenders.contracts.payments.store',
              [
                  'project' => $project,
                  'procurementTender' => $procurementTender,
                  'contract' => $contract,
              ]
          ) }}"
          id="paymentForm">

        @csrf


        <div class="row g-4">

            {{-- =================================================
                 LEFT COLUMN
            ================================================== --}}
            <div class="col-lg-8">


                {{-- =================================================
                     INVOICE
                ================================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">
                            <i class="bi bi-receipt me-1"></i>
                            Invoice
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="mb-3">

                            <label for="invoice_id"
                                   class="form-label fw-semibold">

                                Invoice
                                <span class="text-danger">*</span>

                            </label>

                            <select name="procurement_contract_invoice_id"
                                    id="invoice_id"
                                    class="form-select @error('procurement_contract_invoice_id') is-invalid @enderror"
                                    required>

                                <option value="">
                                    -- Select Invoice --
                                </option>

                                @foreach($invoices as $invoice)

                                    @php
                                        $balance = (float) (
                                            $invoice->calculated_balance ?? 0
                                        );

                                        $selected =
                                            old(
                                                'procurement_contract_invoice_id',
                                                optional($selectedInvoice)->id
                                            ) == $invoice->id;
                                    @endphp

                                    <option value="{{ $invoice->id }}"
                                            data-balance="{{ $balance }}"
                                            data-net-amount="{{ (float) $invoice->net_amount }}"
                                            data-status="{{ $invoice->status }}"
                                            @selected($selected)>

                                        {{ $invoice->invoice_number }}

                                        -
                                        {{ $invoice->currency ?? 'INR' }}
                                        {{ number_format($balance, 2) }}
                                        outstanding

                                    </option>

                                @endforeach

                            </select>


                            @error('procurement_contract_invoice_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Selected invoice information --}}
                        <div id="invoiceInfo"
                             class="{{ $selectedInvoice ? '' : 'd-none' }}">

                            <div class="row g-3">

                                <div class="col-md-4">

                                    <div class="border rounded p-3">

                                        <div class="small text-muted">
                                            Invoice Number
                                        </div>

                                        <div class="fw-semibold"
                                             id="invoiceNumber">

                                            {{ $selectedInvoice->invoice_number ?? '—' }}

                                        </div>

                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <div class="border rounded p-3">

                                        <div class="small text-muted">
                                            Invoice Amount
                                        </div>

                                        <div class="fw-semibold"
                                             id="invoiceAmount">

                                            @if($selectedInvoice)
                                                {{ $selectedInvoice->currency ?? 'INR' }}
                                                {{ number_format((float) $selectedInvoice->net_amount, 2) }}
                                            @else
                                                —
                                            @endif

                                        </div>

                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <div class="border rounded p-3">

                                        <div class="small text-muted">
                                            Outstanding
                                        </div>

                                        <div class="fw-semibold text-danger"
                                             id="invoiceBalance">

                                            @if($selectedInvoice)
                                                {{ $selectedInvoice->currency ?? 'INR' }}
                                                {{ number_format(
                                                    (float) ($selectedInvoice->calculated_balance ?? 0),
                                                    2
                                                ) }}
                                            @else
                                                —
                                            @endif

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     PAYMENT DETAILS
                ================================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">
                            <i class="bi bi-cash-stack me-1"></i>
                            Payment Details
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">


                            {{-- Payment Date --}}
                            <div class="col-md-6">

                                <label for="payment_date"
                                       class="form-label fw-semibold">

                                    Payment Date
                                    <span class="text-danger">*</span>

                                </label>

                                <input type="date"
                                       name="payment_date"
                                       id="payment_date"
                                       value="{{ old(
                                           'payment_date',
                                           now()->format('Y-m-d')
                                       ) }}"
                                       class="form-control @error('payment_date') is-invalid @enderror"
                                       required>

                                @error('payment_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Payment Type --}}
                            <div class="col-md-6">

                                <label for="payment_type"
                                       class="form-label fw-semibold">

                                    Payment Type
                                    <span class="text-danger">*</span>

                                </label>

                                <select name="payment_type"
                                        id="payment_type"
                                        class="form-select @error('payment_type') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        -- Select Payment Type --
                                    </option>

                                    @foreach([
                                        'Advance',
                                        'Progress Payment',
                                        'Interim Payment',
                                        'Final Payment',
                                        'Retention',
                                        'Other'
                                    ] as $type)

                                        <option value="{{ $type }}"
                                            @selected(old('payment_type') === $type)>
                                            {{ $type }}
                                        </option>

                                    @endforeach

                                </select>

                                @error('payment_type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Amount --}}
                            <div class="col-md-6">

                                <label for="amount"
                                       class="form-label fw-semibold">

                                    Payment Amount
                                    <span class="text-danger">*</span>

                                </label>

                                <input type="number"
                                       name="amount"
                                       id="amount"
                                       value="{{ old('amount') }}"
                                       class="form-control @error('amount') is-invalid @enderror"
                                       min="0.01"
                                       step="0.01"
                                       required>

                                <div class="form-text">
                                    Payment cannot exceed the outstanding invoice balance.
                                </div>

                                @error('amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Currency --}}
                            <div class="col-md-6">

                                <label for="currency"
                                       class="form-label fw-semibold">

                                    Currency

                                </label>

                                <input type="text"
                                       name="currency"
                                       id="currency"
                                       value="{{ old('currency', 'INR') }}"
                                       class="form-control @error('currency') is-invalid @enderror"
                                       maxlength="10">

                                @error('currency')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Payment Method --}}
                            <div class="col-md-6">

                                <label for="payment_method"
                                       class="form-label fw-semibold">

                                    Payment Method

                                </label>

                                <select name="payment_method"
                                        id="payment_method"
                                        class="form-select @error('payment_method') is-invalid @enderror">

                                    <option value="">
                                        -- Select Method --
                                    </option>

                                    @foreach([
                                        'Bank Transfer',
                                        'Cheque',
                                        'NEFT',
                                        'RTGS',
                                        'IMPS',
                                        'UPI',
                                        'Other'
                                    ] as $method)

                                        <option value="{{ $method }}"
                                            @selected(old('payment_method') === $method)>
                                            {{ $method }}
                                        </option>

                                    @endforeach

                                </select>

                                @error('payment_method')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Transaction Reference --}}
                            <div class="col-md-6">

                                <label for="transaction_reference"
                                       class="form-label fw-semibold">

                                    Transaction Reference

                                </label>

                                <input type="text"
                                       name="transaction_reference"
                                       id="transaction_reference"
                                       value="{{ old('transaction_reference') }}"
                                       class="form-control @error('transaction_reference') is-invalid @enderror"
                                       maxlength="255">

                                @error('transaction_reference')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Bank Name --}}
                            <div class="col-md-6">

                                <label for="bank_name"
                                       class="form-label fw-semibold">

                                    Bank Name

                                </label>

                                <input type="text"
                                       name="bank_name"
                                       id="bank_name"
                                       value="{{ old('bank_name') }}"
                                       class="form-control @error('bank_name') is-invalid @enderror"
                                       maxlength="255">

                                @error('bank_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Account Reference --}}
                            <div class="col-md-6">

                                <label for="account_reference"
                                       class="form-label fw-semibold">

                                    Account Reference

                                </label>

                                <input type="text"
                                       name="account_reference"
                                       id="account_reference"
                                       value="{{ old('account_reference') }}"
                                       class="form-control @error('account_reference') is-invalid @enderror"
                                       maxlength="255">

                                @error('account_reference')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Description --}}
                            <div class="col-12">

                                <label for="description"
                                       class="form-label fw-semibold">

                                    Description

                                </label>

                                <textarea name="description"
                                          id="description"
                                          rows="3"
                                          class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Remarks --}}
                            <div class="col-12">

                                <label for="remarks"
                                       class="form-label fw-semibold">

                                    Remarks

                                </label>

                                <textarea name="remarks"
                                          id="remarks"
                                          rows="3"
                                          class="form-control @error('remarks') is-invalid @enderror">{{ old('remarks') }}</textarea>

                                @error('remarks')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     MILESTONE
                ================================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">
                            <i class="bi bi-flag me-1"></i>
                            Milestone
                        </h5>

                    </div>


                    <div class="card-body">

                        <label for="milestone_id"
                               class="form-label fw-semibold">

                            Contract Milestone

                        </label>

                        <select name="procurement_contract_milestone_id"
                                id="milestone_id"
                                class="form-select @error('procurement_contract_milestone_id') is-invalid @enderror">

                            <option value="">
                                -- Select Milestone (Optional) --
                            </option>

                            @foreach($milestones as $milestone)

                                <option value="{{ $milestone->id }}"
                                    @selected(
                                        old(
                                            'procurement_contract_milestone_id'
                                        ) == $milestone->id
                                    )>

                                    {{ $milestone->milestone_number
                                        ? $milestone->milestone_number . ' - '
                                        : ''
                                    }}

                                    {{ $milestone->milestone_title
                                        ?? $milestone->title
                                        ?? 'Milestone #' . $milestone->id
                                    }}

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

            </div>


            {{-- =================================================
                 RIGHT COLUMN
            ================================================== --}}
            <div class="col-lg-4">

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">
                            Payment Summary
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Invoice Amount
                            </span>

                            <strong id="summaryInvoiceAmount">
                                —
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Outstanding
                            </span>

                            <strong id="summaryBalance"
                                    class="text-danger">

                                —

                            </strong>

                        </div>


                        <hr>


                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Payment Amount
                            </span>

                            <strong id="summaryPaymentAmount">
                                —
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between">

                            <span class="fw-semibold">
                                Remaining After Payment
                            </span>

                            <strong id="summaryRemaining">
                                —
                            </strong>

                        </div>


                        <div id="paymentWarning"
                             class="alert alert-danger mt-3 d-none mb-0">

                            Payment amount cannot exceed the outstanding
                            invoice balance.

                        </div>

                    </div>

                </div>


                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <div class="alert alert-info mb-0">

                            <div class="fw-semibold mb-1">
                                Payment Workflow
                            </div>

                            <small>
                                New payments are created as
                                <strong>Draft</strong>.
                                They must be submitted, approved and
                                processed before they are reflected as
                                paid against the invoice.
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
             ACTIONS
        ========================================================== --}}

        <div class="card border-0 shadow-sm mt-4">

            <div class="card-body">

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route(
                        'admin.projects.procurement.tenders.contracts.payments.index',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                            'contract' => $contract,
                        ]
                    ) }}"
                       class="btn btn-outline-secondary">

                        Cancel

                    </a>


                    <button type="submit"
                            class="btn btn-primary"
                            id="submitPaymentBtn">

                        <i class="bi bi-check-circle"></i>
                        Create Payment

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>
@endsection


@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const invoiceSelect = document.getElementById('invoice_id');
    const amountInput = document.getElementById('amount');
    const form = document.getElementById('paymentForm');
    const submitButton = document.getElementById('submitPaymentBtn');

    const invoiceInfo = document.getElementById('invoiceInfo');

    const invoiceNumber = document.getElementById('invoiceNumber');
    const invoiceAmount = document.getElementById('invoiceAmount');
    const invoiceBalance = document.getElementById('invoiceBalance');

    const summaryInvoiceAmount =
        document.getElementById('summaryInvoiceAmount');

    const summaryBalance =
        document.getElementById('summaryBalance');

    const summaryPaymentAmount =
        document.getElementById('summaryPaymentAmount');

    const summaryRemaining =
        document.getElementById('summaryRemaining');

    const paymentWarning =
        document.getElementById('paymentWarning');


    function money(value) {

        value = Number(value || 0);

        return value.toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }


    function updateInvoice() {

        const option =
            invoiceSelect.options[
                invoiceSelect.selectedIndex
            ];

        if (!option || !option.value) {

            invoiceInfo.classList.add('d-none');

            invoiceNumber.textContent = '—';
            invoiceAmount.textContent = '—';
            invoiceBalance.textContent = '—';

            summaryInvoiceAmount.textContent = '—';
            summaryBalance.textContent = '—';

            amountInput.removeAttribute('max');

            updateAmount();

            return;
        }


        const balance =
            Number(option.dataset.balance || 0);

        const netAmount =
            Number(option.dataset.netAmount || 0);


        invoiceInfo.classList.remove('d-none');

        invoiceNumber.textContent =
            option.textContent.trim().split(' - ')[0];

        invoiceAmount.textContent =
            money(netAmount);

        invoiceBalance.textContent =
            money(balance);


        summaryInvoiceAmount.textContent =
            money(netAmount);

        summaryBalance.textContent =
            money(balance);


        amountInput.max =
            balance.toFixed(2);


        updateAmount();
    }


    function updateAmount() {

        const option =
            invoiceSelect.options[
                invoiceSelect.selectedIndex
            ];

        const balance =
            option && option.value
                ? Number(option.dataset.balance || 0)
                : 0;

        const amount =
            Number(amountInput.value || 0);

        const remaining =
            balance - amount;


        if (amount > 0) {

            summaryPaymentAmount.textContent =
                money(amount);

        } else {

            summaryPaymentAmount.textContent =
                '—';

        }


        if (option && option.value) {

            summaryRemaining.textContent =
                money(Math.max(remaining, 0));

        } else {

            summaryRemaining.textContent =
                '—';

        }


        if (amount > balance && balance >= 0) {

            paymentWarning.classList.remove('d-none');

            amountInput.classList.add('is-invalid');

            submitButton.disabled = true;

        } else {

            paymentWarning.classList.add('d-none');

            amountInput.classList.remove('is-invalid');

            submitButton.disabled = false;

        }

    }


    invoiceSelect.addEventListener(
        'change',
        updateInvoice
    );

    amountInput.addEventListener(
        'input',
        updateAmount
    );


    form.addEventListener('submit', function (event) {

        const option =
            invoiceSelect.options[
                invoiceSelect.selectedIndex
            ];

        if (!option || !option.value) {

            event.preventDefault();

            alert('Please select an invoice.');

            invoiceSelect.focus();

            return;
        }


        const balance =
            Number(option.dataset.balance || 0);

        const amount =
            Number(amountInput.value || 0);


        if (amount <= 0) {

            event.preventDefault();

            alert('Payment amount must be greater than zero.');

            amountInput.focus();

            return;
        }


        if (amount > balance) {

            event.preventDefault();

            alert(
                'Payment amount cannot exceed the outstanding invoice balance.'
            );

            amountInput.focus();

            return;
        }


        submitButton.disabled = true;

        submitButton.innerHTML =
            '<span class="spinner-border spinner-border-sm me-1"></span> Creating...';

    });


    updateInvoice();

});
</script>

@endpush