@extends('layouts.app')

@section('content')

<div class="container-fluid">


    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Acquisition Costs
            </h3>

            <p class="text-muted mb-0">

                {{ $land->land_code }}
                -
                {{ $land->land_name }}

            </p>

        </div>


        <div>

            <a
                href="{{ route(
                    'admin.land.lands.acquisition-costs.create',
                    $land
                ) }}"
                class="btn btn-primary">

                + Add Cost

            </a>


            <a
                href="{{ route(
                    'admin.land.lands.show',
                    $land
                ) }}"
                class="btn btn-secondary">

                Back to Land

            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif


    {{-- Summary --}}

    <div class="row mb-4">


        {{-- Total --}}

        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <small class="text-muted">
                        Total Acquisition Cost
                    </small>

                    <h3 class="mb-0">

                        ₹ {{ number_format(
                            $totalAmount,
                            2
                        ) }}

                    </h3>

                </div>

            </div>

        </div>


        {{-- Paid --}}

        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <small class="text-muted">
                        Paid
                    </small>

                    <h3 class="mb-0">

                        ₹ {{ number_format(
                            $paidAmount,
                            2
                        ) }}

                    </h3>

                </div>

            </div>

        </div>


        {{-- Pending --}}

        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <small class="text-muted">
                        Pending
                    </small>

                    <h3 class="mb-0">

                        ₹ {{ number_format(
                            $pendingAmount,
                            2
                        ) }}

                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- Cost Table --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Acquisition Cost Details
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>

                            <th>
                                Category
                            </th>

                            <th>
                                Description
                            </th>

                            <th>
                                Amount
                            </th>

                            <th>
                                Tax
                            </th>

                            <th>
                                Total
                            </th>

                            <th>
                                Payment
                            </th>

                            <th width="180">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse(
                        $acquisitionCosts
                        as $cost
                    )

                        <tr>

                            <td>

                                <strong>
                                    {{ $cost->cost_category }}
                                </strong>

                            </td>


                            <td>

                                {{ $cost->cost_description ?? '-' }}

                            </td>


                            <td>

                                {{ $cost->currency }}

                                {{ number_format(
                                    $cost->amount,
                                    2
                                ) }}

                            </td>


                            <td>

                                {{ $cost->currency }}

                                {{ number_format(
                                    $cost->tax_amount,
                                    2
                                ) }}

                            </td>


                            <td>

                                <strong>

                                    {{ $cost->currency }}

                                    {{ number_format(
                                        $cost->total_amount,
                                        2
                                    ) }}

                                </strong>

                            </td>


                            <td>

                                @if(
                                    $cost->payment_status
                                    === 'Paid'
                                )

                                    <span class="badge bg-success">
                                        Paid
                                    </span>

                                @elseif(
                                    $cost->payment_status
                                    === 'Partially Paid'
                                )

                                    <span class="badge bg-warning text-dark">
                                        Partially Paid
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Pending
                                    </span>

                                @endif

                            </td>


                            <td>

                                <a
                                    href="{{ route(
                                        'admin.land.lands.acquisition-costs.show',
                                        [
                                            $land,
                                            $cost
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary">

                                    View

                                </a>


                                <a
                                    href="{{ route(
                                        'admin.land.lands.acquisition-costs.edit',
                                        [
                                            $land,
                                            $cost
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-secondary">

                                    Edit

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5">

                                No acquisition costs found.

                                <br>

                                <a
                                    href="{{ route(
                                        'admin.land.lands.acquisition-costs.create',
                                        $land
                                    ) }}"
                                    class="btn btn-sm btn-primary mt-2">

                                    Add First Cost

                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <div class="card-footer">

            {{ $acquisitionCosts->links() }}

        </div>

    </div>

</div>

@endsection