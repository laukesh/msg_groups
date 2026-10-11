@extends('layouts.app')

@section('title', 'Rent Schedules')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Rent Schedules
            </h4>

            <p class="text-muted mb-0">
                Manage rent schedules and generate invoices.
            </p>
        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Validation Errors --}}
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


    {{-- Rent Schedule Table --}}
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                Rent Schedule List
            </h5>

            <span class="text-muted">
                Total: {{ $rentSchedules->count() }}
            </span>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Schedule No
                            </th>

                            <th>
                                Agreement
                            </th>

                            <th>
                                Billing Period
                            </th>

                            <th>
                                Period
                            </th>

                            <th>
                                Due Date
                            </th>

                            <th class="text-end">
                                Base Rent
                            </th>

                            <th class="text-end">
                                CAM
                            </th>

                            <th class="text-end">
                                Tax
                            </th>

                            <th class="text-end">
                                Total
                            </th>

                            <th>
                                Invoice
                            </th>

                            <th>
                                Payment
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($rentSchedules as $schedule)

                            <tr>

                                {{-- ID --}}
                                <td>
                                    {{ $schedule->id }}
                                </td>


                                {{-- Schedule Number --}}
                                <td>

                                    <strong>
                                        {{ $schedule->schedule_no }}
                                    </strong>

                                </td>


                                {{-- Agreement --}}
                                <td>

                                    @if($schedule->leaseAgreement)

                                        <strong>
                                            {{ $schedule->leaseAgreement->agreement_no }}
                                        </strong>

                                    @else

                                        <span class="text-danger">
                                            Agreement not found
                                        </span>

                                    @endif

                                </td>


                                {{-- Billing Period --}}
                                <td>
                                    {{ $schedule->billing_period }}
                                </td>


                                {{-- Period --}}
                                <td>

                                    @if($schedule->period_start)

                                        {{ \Carbon\Carbon::parse(
                                            $schedule->period_start
                                        )->format('d M Y') }}

                                    @endif

                                    <br>

                                    <small class="text-muted">
                                        to
                                    </small>

                                    <br>

                                    @if($schedule->period_end)

                                        {{ \Carbon\Carbon::parse(
                                            $schedule->period_end
                                        )->format('d M Y') }}

                                    @endif

                                </td>


                                {{-- Due Date --}}
                                <td>

                                    @if($schedule->due_date)

                                        {{ \Carbon\Carbon::parse(
                                            $schedule->due_date
                                        )->format('d M Y') }}

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- Base Rent --}}
                                <td class="text-end">

                                    ${{ number_format(
                                        $schedule->base_rent ?? 0,
                                        2
                                    ) }}

                                </td>


                                {{-- CAM --}}
                                <td class="text-end">

                                    ${{ number_format(
                                        $schedule->cam_amount ?? 0,
                                        2
                                    ) }}

                                </td>


                                {{-- Tax --}}
                                <td class="text-end">

                                    ${{ number_format(
                                        $schedule->tax_amount ?? 0,
                                        2
                                    ) }}

                                </td>


                                {{-- Total --}}
                                <td class="text-end">

                                    <strong>

                                        ${{ number_format(
                                            $schedule->total_amount ?? 0,
                                            2
                                        ) }}

                                    </strong>

                                </td>


                                {{-- Invoice Status --}}
                                <td>

                                    @if($schedule->invoice_generated === 'Yes')

                                        <span class="badge bg-success">
                                            Generated
                                        </span>

                                        @if($schedule->invoice)

                                            <br>

                                            <small class="text-muted">

                                                {{ $schedule->invoice->invoice_no }}

                                            </small>

                                        @endif

                                    @else

                                        <span class="badge bg-warning text-dark">
                                            Not Generated
                                        </span>

                                    @endif

                                </td>


                                {{-- Payment Status --}}
                                <td>

                                    @if($schedule->payment_status === 'Paid')

                                        <span class="badge bg-success">
                                            Paid
                                        </span>

                                    @elseif($schedule->payment_status === 'Partial')

                                        <span class="badge bg-info">
                                            Partial
                                        </span>

                                    @elseif($schedule->payment_status === 'Overdue')

                                        <span class="badge bg-danger">
                                            Overdue
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Pending
                                        </span>

                                    @endif

                                </td>


                                {{-- Action --}}
                                <td>

                                    @if($schedule->invoice_generated === 'No')

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.revenue.rent-schedules.generate-invoice',
                                                $schedule->id
                                            ) }}"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="btn btn-primary btn-sm"
                                                onclick="return confirm(
                                                    'Are you sure you want to generate the invoice for this rent schedule?'
                                                )"
                                            >

                                                Generate Invoice

                                            </button>

                                        </form>

                                    @else

                                        <button
                                            type="button"
                                            class="btn btn-secondary btn-sm"
                                            disabled
                                        >

                                            Invoice Generated

                                        </button>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="13"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        No rent schedules found.

                                    </div>

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