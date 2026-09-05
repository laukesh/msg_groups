@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Receive Payment
            </h3>

            <p class="text-muted mb-0">
                Record payment against invoice.
            </p>

        </div>


        <a href="{{ route(
            'admin.revenue.invoices.show',
            $invoice->id
        ) }}"
           class="btn btn-secondary">

            ← Back to Invoice

        </a>

    </div>


    {{-- Invoice Summary --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <strong>
                Invoice Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-3">

                    <strong>Invoice No</strong>

                    <div class="mt-1">

                        {{ $invoice->invoice_no }}

                    </div>

                </div>


                <div class="col-md-3">

                    <strong>Tenant</strong>

                    <div class="mt-1">

                        {{ $invoice->tenant->company_name ?? '-' }}

                        @if($invoice->tenant?->brand_name)

                            <br>

                            <small class="text-muted">
                                {{ $invoice->tenant->brand_name }}
                            </small>

                        @endif

                    </div>

                </div>


                <div class="col-md-3">

                    <strong>Total Amount</strong>

                    <div class="mt-1">

                        ${{ number_format(
                            $invoice->total_amount,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-3">

                    <strong>Outstanding Balance</strong>

                    <div class="mt-1 text-danger fw-bold">

                        ${{ number_format(
                            $invoice->balance_amount,
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Payment Form --}}

    <div class="card shadow-sm">

        <div class="card-header">

            <strong>
                Payment Details
            </strong>

        </div>


        <div class="card-body">


            <form method="POST"
                  action="{{ route(
                      'admin.revenue.payments.store',
                      $invoice->id
                  ) }}">

                @csrf


                <div class="row g-3">


                    {{-- Payment Date --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Payment Date
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="date"
                            name="payment_date"
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


                    {{-- Payment Amount --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Payment Amount
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="number"
                            name="payment_amount"
                            class="form-control @error('payment_amount') is-invalid @enderror"
                            value="{{ old('payment_amount') }}"
                            min="0.01"
                            max="{{ $invoice->balance_amount }}"
                            step="0.01"
                            required
                        >

                        <small class="text-muted">

                            Maximum:
                            ${{ number_format(
                                $invoice->balance_amount,
                                2
                            ) }}

                        </small>

                        @error('payment_amount')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Payment Mode --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Payment Mode
                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="payment_mode"
                            class="form-select @error('payment_mode') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Payment Mode
                            </option>

                            @foreach([
                                'Cash',
                                'Cheque',
                                'NEFT',
                                'RTGS',
                                'IMPS',
                                'UPI',
                                'Credit Card',
                                'Debit Card'
                            ] as $mode)

                                <option
                                    value="{{ $mode }}"
                                    @selected(
                                        old('payment_mode') === $mode
                                    )
                                >
                                    {{ $mode }}
                                </option>

                            @endforeach

                        </select>

                        @error('payment_mode')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Bank --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Bank Name
                        </label>

                        <input
                            type="text"
                            name="bank_name"
                            class="form-control"
                            value="{{ old('bank_name') }}"
                            maxlength="150"
                        >

                    </div>


                    {{-- Cheque --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Cheque No
                        </label>

                        <input
                            type="text"
                            name="cheque_no"
                            class="form-control"
                            value="{{ old('cheque_no') }}"
                            maxlength="50"
                        >

                    </div>


                    {{-- Transaction Reference --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Transaction Reference
                        </label>

                        <input
                            type="text"
                            name="transaction_reference"
                            class="form-control"
                            value="{{ old(
                                'transaction_reference'
                            ) }}"
                            maxlength="100"
                        >

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
                        >{{ old('remarks') }}</textarea>

                    </div>


                </div>


                <hr class="my-4">


                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route(
                            'admin.revenue.invoices.show',
                            $invoice->id
                        ) }}"
                        class="btn btn-secondary"
                    >

                        Cancel

                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        Save Payment

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>

@endsection