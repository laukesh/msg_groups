@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Cost Control
            </div>

            <h4>
                Processed Payments
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
            <strong>Payment Register</strong>
        </div>


        <div class="card-body p-0">

            @if($payments->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>
                                    Payment Number
                                </th>

                                <th>
                                    Payment Date
                                </th>

                                <th>
                                    Contract
                                </th>

                                <th>
                                    Invoice
                                </th>

                                <th>
                                    Method
                                </th>

                                <th>
                                    Transaction Ref.
                                </th>

                                <th class="text-end">
                                    Amount
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($payments as $payment)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        <strong>
                                            {{ $payment->payment_number }}
                                        </strong>

                                    </td>


                                    <td>

                                        {{
                                            $payment->payment_date
                                                ?->format('d-m-Y')
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $payment->contract
                                                ?->contract_number
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $payment->invoice
                                                ?->invoice_number
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $payment->payment_method
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $payment
                                                ->transaction_reference
                                            ?? '—'
                                        }}

                                    </td>


                                    <td class="text-end fw-semibold">

                                        {{
                                            number_format(
                                                (float)
                                                $payment->amount,
                                                2
                                            )
                                        }}

                                        {{ $payment->currency }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>


                        <tfoot class="table-light">

                            <tr>

                                <th colspan="7">
                                    Total Processed Payments
                                </th>

                                <th class="text-end">

                                    {{
                                        number_format(
                                            $payments->sum('amount'),
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
                    No processed payments found.
                </div>

            @endif

        </div>

    </div>

</div>

@endsection