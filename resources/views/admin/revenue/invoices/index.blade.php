@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">Invoices</h3>
            <p class="text-muted mb-0">
                Manage generated invoices and payments.
            </p>
        </div>

    </div>


    <div class="card shadow-sm">

        <div class="card-header">
            <h5 class="mb-0">
                Invoice List
            </h5>

            <small class="text-muted">
                Total: {{ $invoices->count() }}
            </small>
        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Invoice No</th>

                            <th>Agreement</th>

                            <th>Tenant</th>

                            <th>Billing Period</th>

                            <th>Due Date</th>

                            <th>Total</th>

                            <th>Paid</th>

                            <th>Balance</th>

                            <th>Status</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($invoices as $invoice)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    <strong>
                                        {{ $invoice->invoice_no }}
                                    </strong>

                                </td>


                                <td>

                                    {{ $invoice->leaseAgreement->agreement_no ?? '-' }}

                                </td>


                                <td>

                                    {{ $invoice->tenant->company_name
                                        ?? $invoice->tenant->company_name
                                        ?? '-' }}

                                </td>


                                <td>

                                    @if($invoice->billing_period_from && $invoice->billing_period_to)

                                        {{ $invoice->billing_period_from->format('d M Y') }}

                                        <br>

                                        <small class="text-muted">
                                            to
                                            {{ $invoice->billing_period_to->format('d M Y') }}
                                        </small>

                                    @else

                                        -

                                    @endif

                                </td>


                                <td>

                                    {{ $invoice->due_date
                                        ? $invoice->due_date->format('d M Y')
                                        : '-' }}

                                </td>


                                <td>

                                    <strong>
                                        ${{ number_format($invoice->total_amount, 2) }}
                                    </strong>

                                </td>


                                <td>

                                    ${{ number_format($invoice->paid_amount, 2) }}

                                </td>


                                <td>

                                    <strong>
                                        ${{ number_format($invoice->balance_amount, 2) }}
                                    </strong>

                                </td>


                                <td>

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

                                </td>


                                <td>

                                    <a href="{{ route(
                                        'admin.revenue.invoices.show',
                                        $invoice->id
                                    ) }}"
                                       class="btn btn-sm btn-primary">

                                        View

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="11"
                                    class="text-center py-4">

                                    No invoices found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
