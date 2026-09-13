@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Contract:
                {{ $contract->contract_number }}
            </div>

            <h4 class="mb-1">
                Contract Payments
            </h4>

            <div class="text-muted">
                Payment Register
            </div>

        </div>

        {{-- Header Navigation --}}
        <div class="d-flex gap-2">

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
            <a
                href="{{ route(
                    'admin.procurement.tenders.contracts.invoices.index',
                    [
                        'procurementTender' => $procurementTender,
                        'contract' => $contract,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-receipt me-1"></i>
                Back to Invoice
            </a>

        </div>

    </div>


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


    <div class="card">

        <div class="card-header">

            <strong>
                Payment Register
            </strong>

        </div>


        <div class="card-body p-0">

            @if($payments->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Payment</th>

                            <th>Invoice</th>

                            <th>Milestone</th>

                            <th>Date</th>

                            <th>Amount</th>

                            <th>Status</th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($payments as $payment)

                            @php

                                $statusClass = match(
                                    $payment->status
                                ) {

                                    'Processed',
                                    'Approved' =>
                                        'bg-success',

                                    'Rejected' =>
                                        'bg-danger',

                                    'Submitted' =>
                                        'bg-warning text-dark',

                                    default =>
                                        'bg-secondary',

                                };

                            @endphp


                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    <div class="fw-semibold">
                                        {{ $payment->payment_number }}
                                    </div>

                                    <div class="small text-muted">
                                        {{ $payment->payment_type }}
                                    </div>

                                </td>


                                <td>

                                    @if($payment->invoice)

                                        {{ $payment->invoice->invoice_number }}

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    @if($payment->milestone)

                                        <div class="fw-semibold">

                                            {{
                                                $payment->milestone
                                                    ->milestone_number
                                            }}

                                        </div>

                                        <div class="small text-muted">

                                            {{
                                                $payment->milestone
                                                    ->milestone_title
                                            }}

                                        </div>

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    {{
                                        $payment->payment_date
                                            ?->format('d-m-Y')
                                        ?? '—'
                                    }}

                                </td>


                                <td>

                                    <strong>

                                        {{
                                            number_format(
                                                (float) $payment->amount,
                                                2
                                            )
                                        }}

                                    </strong>

                                    {{ $payment->currency }}

                                </td>


                                <td>

                                    <span
                                        class="badge {{ $statusClass }}"
                                    >
                                        {{ $payment->status }}
                                    </span>

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.procurement.tenders.contracts.payments.show',
                                            [
                                                'procurementTender' =>
                                                    $procurementTender,

                                                'contract' =>
                                                    $contract,

                                                'payment' =>
                                                    $payment,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5 text-muted">

                    No payments found for this contract.

                </div>

            @endif

        </div>

    </div>

</div>

@endsection