@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Invoice Details
            </h3>

            <p class="text-muted mb-0">
                {{ $invoice->invoice_no }}
            </p>

        </div>


        <div class="d-flex gap-2">

            @if(
                !in_array($invoice->invoice_status, ['Paid', 'Cancelled'])
                && $invoice->balance_amount > 0
            )

                <a
                    href="{{ route(
                        'admin.revenue.payments.create',
                        $invoice->id
                    ) }}"
                    class="btn btn-success"
                >

                    + Receive Payment

                </a>

            @endif

            {{-- View Payments --}}
            <a
                href="{{ route(
                    'admin.revenue.payments.index',
                    ['invoice_id' => $invoice->id]
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="fas fa-money-bill-wave me-1"></i>
                View Payments
            </a>

            <a
                href="{{ route(
                    'admin.revenue.invoices.print',
                    $invoice->id
                ) }}"
                target="_blank"
                class="btn btn-outline-secondary"
            >
                Print Invoice
            </a>


            <a
                href="{{ route(
                    'admin.revenue.invoices.index'
                ) }}"
                class="btn btn-secondary"
            >

                ← Back

            </a>

        </div>

    </div>


    {{-- Invoice Header --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header d-flex justify-content-between">

            <strong>
                {{ $invoice->invoice_no }}
            </strong>


            @php

                $statusClass = match($invoice->invoice_status) {

                    'Draft' => 'bg-secondary',

                    'Generated' => 'bg-primary',

                    'Sent' => 'bg-info',

                    'Partially Paid' => 'bg-warning text-dark',

                    'Paid' => 'bg-success',

                    'Overdue' => 'bg-danger',

                    'Cancelled' => 'bg-dark',

                    default => 'bg-secondary',

                };

            @endphp


            <span class="badge {{ $statusClass }}">

                {{ $invoice->invoice_status }}

            </span>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-4">

                    <strong>Agreement</strong>

                    <div class="mt-1">

                        {{ $invoice->leaseAgreement->agreement_no ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <strong>Tenant</strong>

                    <div class="mt-1">

                        {{ $invoice->tenant->company_name
                            ?? $invoice->tenant->company_name
                            ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <strong>Invoice Type</strong>

                    <div class="mt-1">

                        {{ $invoice->invoice_type }}

                    </div>

                </div>


                <div class="col-md-4">

                    <strong>Invoice Date</strong>

                    <div class="mt-1">

                        {{ $invoice->invoice_date
                            ? $invoice->invoice_date->format('d M Y')
                            : '-' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <strong>Billing Period</strong>

                    <div class="mt-1">

                        {{ $invoice->billing_period_from->format('d M Y') }}

                        -

                        {{ $invoice->billing_period_to->format('d M Y') }}

                    </div>

                </div>


                <div class="col-md-4">

                    <strong>Due Date</strong>

                    <div class="mt-1">

                        {{ $invoice->due_date
                            ? $invoice->due_date->format('d M Y')
                            : '-' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Invoice Items --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <strong>
                Invoice Items
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Description</th>

                            <th>Quantity</th>

                            <th>Rate</th>

                            <th>Taxable Amount</th>

                            <th>Tax</th>

                            <th>Total</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($invoice->items as $item)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    {{ $item->item_description }}

                                </td>


                                <td>

                                    {{ number_format($item->quantity, 2) }}

                                </td>


                                <td>

                                    ${{ number_format($item->rate, 2) }}

                                </td>


                                <td>

                                    ${{ number_format(
                                        $item->taxable_amount,
                                        2
                                    ) }}

                                </td>


                                <td>

                                    {{ number_format(
                                        $item->tax_percentage,
                                        2
                                    ) }}%

                                    <br>

                                    <small>

                                        ${{ number_format(
                                            $item->tax_amount,
                                            2
                                        ) }}

                                    </small>

                                </td>


                                <td>

                                    <strong>

                                        ${{ number_format(
                                            $item->total_amount,
                                            2
                                        ) }}

                                    </strong>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7"
                                    class="text-center py-4">

                                    No invoice items found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Invoice Summary --}}

    <div class="row justify-content-end">

        <div class="col-md-5">

            <div class="card shadow-sm">

                <div class="card-body">


                    <div class="d-flex justify-content-between mb-2">

                        <span>
                            Subtotal
                        </span>

                        <strong>
                            ${{ number_format(
                                $invoice->subtotal,
                                2
                            ) }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-2">

                        <span>
                            Discount
                        </span>

                        <strong>
                            ${{ number_format(
                                $invoice->discount_amount,
                                2
                            ) }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-2">

                        <span>
                            Taxable Amount
                        </span>

                        <strong>
                            ${{ number_format(
                                $invoice->taxable_amount,
                                2
                            ) }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-2">

                        <span>
                            Tax
                        </span>

                        <strong>
                            ${{ number_format(
                                $invoice->tax_amount,
                                2
                            ) }}
                        </strong>

                    </div>


                    <hr>


                    <div class="d-flex justify-content-between mb-2">

                        <strong>
                            Total Amount
                        </strong>

                        <strong class="fs-5">

                            ${{ number_format(
                                $invoice->total_amount,
                                2
                            ) }}

                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-2">

                        <span>
                            Paid Amount
                        </span>

                        <strong class="text-success">

                            ${{ number_format(
                                $invoice->paid_amount,
                                2
                            ) }}

                        </strong>

                    </div>


                    <div class="d-flex justify-content-between">

                        <strong>
                            Balance Amount
                        </strong>

                        <strong class="text-danger">

                            ${{ number_format(
                                $invoice->balance_amount,
                                2
                            ) }}

                        </strong>

                    </div>


                </div>

            </div>

        </div>

    </div>

</div>

@endsection
