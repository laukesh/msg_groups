@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Rent Schedule
            </h4>

            <div class="text-muted">
                {{ $schedule->schedule_no }}
            </div>
        </div>

        <a href="{{ route('admin.revenue.rent-schedules.index') }}"
           class="btn btn-secondary">
            Back
        </a>

    </div>


    {{-- Schedule Summary --}}
    <div class="card mb-4">

        <div class="card-header">
            <h5 class="mb-0">
                Schedule Details
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <small class="text-muted">
                        Schedule No
                    </small>

                    <div class="fw-semibold">
                        {{ $schedule->schedule_no }}
                    </div>
                </div>


                <div class="col-md-3 mb-3">
                    <small class="text-muted">
                        Billing Period
                    </small>

                    <div>
                        {{ $schedule->billing_period }}
                    </div>
                </div>


                <div class="col-md-3 mb-3">
                    <small class="text-muted">
                        Period Start
                    </small>

                    <div>
                        {{ \Carbon\Carbon::parse($schedule->period_start)->format('d M Y') }}
                    </div>
                </div>


                <div class="col-md-3 mb-3">
                    <small class="text-muted">
                        Period End
                    </small>

                    <div>
                        {{ \Carbon\Carbon::parse($schedule->period_end)->format('d M Y') }}
                    </div>
                </div>


                <div class="col-md-3 mb-3">
                    <small class="text-muted">
                        Due Date
                    </small>

                    <div>
                        {{ \Carbon\Carbon::parse($schedule->due_date)->format('d M Y') }}
                    </div>
                </div>


                <div class="col-md-3 mb-3">
                    <small class="text-muted">
                        Invoice
                    </small>

                    <div>

                        @if($schedule->invoice)

                            <a href="{{ route(
                                'admin.revenue.invoices.show',
                                $schedule->invoice->id
                            ) }}"
                            class="fw-semibold text-primary">

                                {{ $schedule->invoice->invoice_no }}

                            </a>

                        @else

                            <span class="badge bg-warning">
                                Not Generated
                            </span>

                        @endif

                    </div>
                </div>


                <div class="col-md-3 mb-3">
                    <small class="text-muted">
                        Invoice Status
                    </small>

                    <div>

                        @if($schedule->invoice_generated === 'Yes')

                            <span class="badge bg-success">
                                Generated
                            </span>

                        @else

                            <span class="badge bg-warning">
                                Pending
                            </span>

                        @endif

                    </div>
                </div>


                <div class="col-md-3 mb-3">
                    <small class="text-muted">
                        Payment Status
                    </small>

                    <div>
                        <span class="badge bg-secondary">
                            {{ $schedule->payment_status }}
                        </span>
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- Agreement --}}
    @if($schedule->leaseAgreement)

        <div class="card mb-4">

            <div class="card-header">
                <h5 class="mb-0">
                    Lease Agreement
                </h5>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">
                        <small class="text-muted">
                            Agreement No
                        </small>

                        <div class="fw-semibold">
                            {{ $schedule->leaseAgreement->agreement_no }}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted">
                            Monthly Rent
                        </small>

                        <div>
                            ${{ number_format(
                                $schedule->leaseAgreement->monthly_rent,
                                2
                            ) }}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted">
                            CAM
                        </small>

                        <div>
                            ${{ number_format(
                                $schedule->leaseAgreement->cam_amount,
                                2
                            ) }}
                        </div>
                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- Charges --}}
    <div class="card mb-4">

        <div class="card-header">
            <h5 class="mb-0">
                Charges
            </h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>Charge</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td>Base Rent</td>
                            <td class="text-end">
                                ${{ number_format(
                                    $schedule->base_rent,
                                    2
                                ) }}
                            </td>
                        </tr>

                        <tr>
                            <td>Escalation</td>
                            <td class="text-end">
                                ${{ number_format(
                                    $schedule->escalation_amount,
                                    2
                                ) }}
                            </td>
                        </tr>

                        <tr>
                            <td>CAM</td>
                            <td class="text-end">
                                ${{ number_format(
                                    $schedule->cam_amount,
                                    2
                                ) }}
                            </td>
                        </tr>

                        <tr>
                            <td>Utility</td>
                            <td class="text-end">
                                ${{ number_format(
                                    $schedule->utility_estimate,
                                    2
                                ) }}
                            </td>
                        </tr>

                        <tr>
                            <td>Discount</td>
                            <td class="text-end">
                                ${{ number_format(
                                    $schedule->discount_amount,
                                    2
                                ) }}
                            </td>
                        </tr>

                        <tr class="fw-semibold">
                            <td>Taxable Amount</td>
                            <td class="text-end">
                                ${{ number_format(
                                    $schedule->taxable_amount,
                                    2
                                ) }}
                            </td>
                        </tr>

                        <tr>
                            <td>Tax</td>
                            <td class="text-end">
                                ${{ number_format(
                                    $schedule->tax_amount,
                                    2
                                ) }}
                            </td>
                        </tr>

                        <tr class="table-primary fw-bold">
                            <td>Total Amount</td>
                            <td class="text-end">
                                ${{ number_format(
                                    $schedule->total_amount,
                                    2
                                ) }}
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Actions --}}
    <div class="card">

        <div class="card-body">

            @if(!$schedule->invoice_id)

                <form
                    action="{{ route(
                        'admin.revenue.rent-schedules.generate-invoice',
                        $schedule->id
                    ) }}"
                    method="POST"
                    class="d-inline"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Generate Invoice
                    </button>

                </form>

            @else

                <a
                    href="{{ route(
                        'admin.revenue.invoices.show',
                        $schedule->invoice_id
                    ) }}"
                    class="btn btn-primary"
                >
                    View Invoice
                </a>

            @endif

        </div>

    </div>

</div>

@endsection