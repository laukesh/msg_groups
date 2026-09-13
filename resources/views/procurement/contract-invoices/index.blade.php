@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Contract:
                {{ $contract->contract_number }}
            </div>

            <h4 class="mb-1">
                Contract Invoices
            </h4>

            <div class="text-muted">
                Invoice Register
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

            {{-- Payments --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.contracts.payments.index',
                    [
                        'procurementTender' => $procurementTender,
                        'contract' => $contract,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-credit-card me-1"></i>
                Payments
            </a>

        </div>

    </div>


    {{-- FLASH --}}
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


    {{-- SUMMARY --}}
    @php

        $totalInvoices =
            $invoices->count();

        $draftInvoices =
            $invoices
                ->where('status', 'Draft')
                ->count();

        $submittedInvoices =
            $invoices
                ->where('status', 'Submitted')
                ->count();

        $approvedInvoices =
            $invoices
                ->where('status', 'Approved')
                ->count();

        $rejectedInvoices =
            $invoices
                ->where('status', 'Rejected')
                ->count();

    @endphp


    <div class="row g-3 mb-4">

        <div class="col-md">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Total
                    </small>

                    <h4 class="mt-2 mb-0">
                        {{ $totalInvoices }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Draft
                    </small>

                    <h4 class="mt-2 mb-0">
                        {{ $draftInvoices }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Submitted
                    </small>

                    <h4 class="mt-2 mb-0 text-warning">
                        {{ $submittedInvoices }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Approved
                    </small>

                    <h4 class="mt-2 mb-0 text-success">
                        {{ $approvedInvoices }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Rejected
                    </small>

                    <h4 class="mt-2 mb-0 text-danger">
                        {{ $rejectedInvoices }}
                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- INVOICE TABLE --}}
    <div class="card">

        <div class="card-header">

            <strong>
                Invoice Register
            </strong>

        </div>


        <div class="card-body p-0">

            @if($invoices->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Invoice
                            </th>

                            <th>
                                Milestone
                            </th>

                            <th>
                                Invoice Date
                            </th>

                            <th>
                                Amount
                            </th>

                            <th>
                                Net Amount
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($invoices as $invoice)

                            @php

                                $statusClass = match(
                                    $invoice->status
                                ) {

                                    'Approved' =>
                                        'bg-success',

                                    'Rejected' =>
                                        'bg-danger',

                                    'Submitted' =>
                                        'bg-warning text-dark',

                                    'Paid' =>
                                        'bg-success',

                                    'Partially Paid' =>
                                        'bg-info',

                                    default =>
                                        'bg-secondary',

                                };


                                /*
                                |--------------------------------------------------------------------------
                                | Payment Eligibility
                                |--------------------------------------------------------------------------
                                */

                                $paidAmount = 0;

                                if (
                                    in_array(
                                        $invoice->status,
                                        [
                                            'Approved',
                                            'Partially Paid',
                                        ],
                                        true
                                    )
                                ) {

                                    $paidAmount =
                                        $invoice
                                            ->payments()
                                            ->where(
                                                'status',
                                                'Processed'
                                            )
                                            ->sum('amount');

                                }


                                $outstandingAmount = max(
                                    0,
                                    (float) $invoice->net_amount
                                    - (float) $paidAmount
                                );


                                $canMakePayment =
                                    in_array(
                                        $invoice->status,
                                        [
                                            'Approved',
                                            'Partially Paid',
                                        ],
                                        true
                                    )
                                    &&
                                    $outstandingAmount > 0;

                            @endphp


                            <tr>

                                {{-- Serial --}}
                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                {{-- Invoice --}}
                                <td>

                                    <div class="fw-semibold">

                                        {{ $invoice->invoice_number }}

                                    </div>

                                    <div class="small text-muted">

                                        {{ $invoice->invoice_type }}

                                    </div>

                                </td>


                                {{-- Milestone --}}
                                <td>

                                    @if($invoice->milestone)

                                        <div class="fw-semibold">

                                            {{
                                                $invoice->milestone
                                                    ->milestone_number
                                            }}

                                        </div>

                                        <div class="small text-muted">

                                            {{
                                                $invoice->milestone
                                                    ->milestone_title
                                            }}

                                        </div>

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Invoice Date --}}
                                <td>

                                    {{
                                        $invoice->invoice_date
                                            ?->format('d-m-Y')
                                        ?? '—'
                                    }}

                                </td>


                                {{-- Amount --}}
                                <td>

                                    {{
                                        number_format(
                                            (float)
                                            $invoice->amount,
                                            2
                                        )
                                    }}

                                    {{ $invoice->currency }}

                                </td>


                                {{-- Net Amount --}}
                                <td>

                                    <strong>

                                        {{
                                            number_format(
                                                (float)
                                                $invoice->net_amount,
                                                2
                                            )
                                        }}

                                    </strong>

                                    {{ $invoice->currency }}

                                </td>


                                {{-- Status --}}
                                <td>

                                    <span
                                        class="badge {{ $statusClass }}"
                                    >
                                        {{ $invoice->status }}
                                    </span>


                                    {{-- Outstanding --}}
                                    @if($canMakePayment)

                                        <div class="small text-muted mt-1">

                                            Outstanding:

                                            <strong>

                                                {{
                                                    number_format(
                                                        $outstandingAmount,
                                                        2
                                                    )
                                                }}

                                                {{ $invoice->currency }}

                                            </strong>

                                        </div>

                                    @endif

                                </td>


                                {{-- Action --}}
                                <td class="text-end">

                                    <div class="d-flex justify-content-end gap-1">

                                        {{-- View --}}
                                        <a
                                            href="{{ route(
                                                'admin.procurement.tenders.contracts.invoices.show',
                                                [
                                                    'procurementTender' =>
                                                        $procurementTender,

                                                    'contract' =>
                                                        $contract,

                                                    'invoice' =>
                                                        $invoice,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            View
                                        </a>


                                        {{-- Make Payment --}}
                                        @if($canMakePayment)

                                            <a
                                                href="{{ route(
                                                'admin.procurement.tenders.contracts.invoices.payment.create',
                                                [
                                                    'procurementTender' => $procurementTender,
                                                    'contract' => $contract,
                                                    'invoice' => $invoice->id,
                                                ]
                                            ) }}?invoice_id={{ $invoice->id }}"
                                                class="btn btn-success"
                                            >
                                                <i class="bi bi-credit-card me-1"></i>
                                                Make Payment
                                            </a>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="text-muted">

                        No invoices created for this contract.

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection