@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>
            <div class="text-muted small">
                Cost Control
            </div>

            <h4>
                Project Invoices
            </h4>

            <div class="text-muted">
                {{ $project->project_name }}
            </div>
        </div>

        <a
            href="{{ route(
                'admin.projects.construction.cost-control.index',
                $project
            ) }}"
            class="btn btn-outline-secondary"
        >
            Back to Cost Control
        </a>

    </div>


    <div class="card">

        <div class="card-header">
            <strong>Invoice Register</strong>
        </div>

        <div class="card-body p-0">

            @if($invoices->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th>#</th>
                                <th>Invoice</th>
                                <th>Date</th>
                                <th>Contract</th>
                                <th>Milestone</th>
                                <th>Status</th>
                                <th class="text-end">Net Amount</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($invoices as $invoice)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $invoice->invoice_number }}
                                        </strong>

                                        @if($invoice->description)

                                            <div class="small text-muted">
                                                {{ $invoice->description }}
                                            </div>

                                        @endif
                                    </td>

                                    <td>
                                        {{ $invoice->invoice_date?->format('d-m-Y') ?? '—' }}
                                    </td>

                                    <td>
                                        {{ $invoice->contract?->contract_number ?? '—' }}
                                    </td>

                                    <td>
                                        {{ $invoice->milestone?->milestone_name ?? '—' }}
                                    </td>

                                    <td>

                                        @php
                                            $statusClass = match($invoice->status) {
                                                'Paid' => 'bg-success',
                                                'Partially Paid' => 'bg-warning text-dark',
                                                'Approved' => 'bg-primary',
                                                default => 'bg-secondary',
                                            };
                                        @endphp

                                        <span class="badge {{ $statusClass }}">
                                            {{ $invoice->status }}
                                        </span>

                                    </td>

                                    <td class="text-end fw-semibold">

                                        {{ number_format(
                                            (float) $invoice->net_amount,
                                            2
                                        ) }}

                                        {{ $invoice->currency }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                        <tfoot class="table-light">

                            <tr>

                                <th colspan="6">
                                    Total Invoiced
                                </th>

                                <th class="text-end">

                                    {{
                                        number_format(
                                            $invoices->sum('net_amount'),
                                            2
                                        )
                                    }}

                                </th>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            @else

                <div class="text-center py-5 text-muted">
                    No invoices found.
                </div>

            @endif

        </div>

    </div>

</div>

@endsection