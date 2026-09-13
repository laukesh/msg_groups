@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h4 class="mb-1">
                Material Tracking Details
            </h4>

            <div class="text-muted">
                Tracking Number:
                <strong>
                    {{ $materialTracking->tracking_number }}
                </strong>
            </div>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.procurement.tenders.purchase-orders.material-trackings.index',
                    [
                        'procurementTender' => $procurementTender,
                        'purchaseOrder' => $purchaseOrder,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>

    </div>


    {{-- =========================================================
         ALERTS
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    {{-- =========================================================
         TRACKING INFORMATION
    ========================================================== --}}

    <div class="card mb-3">

        <div class="card-header">
            <strong>Tracking Information</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Tracking Number
                    </small>

                    <div class="fw-semibold">
                        {{ $materialTracking->tracking_number }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Tracking Date
                    </small>

                    <div class="fw-semibold">

                        {{ $materialTracking->tracking_date?->format('d-m-Y') }}

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Status
                    </small>

                    <div>

                        @php

                            $statusClass = match(
                                $materialTracking->status
                            ) {
                                'Available' =>
                                    'bg-success',

                                'Partially Issued' =>
                                    'bg-warning text-dark',

                                'Issued' =>
                                    'bg-primary',

                                'Consumed' =>
                                    'bg-dark',

                                default =>
                                    'bg-secondary',
                            };

                        @endphp

                        <span
                            class="badge {{ $statusClass }}"
                        >
                            {{ $materialTracking->status }}
                        </span>

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Project
                    </small>

                    <div class="fw-semibold">

                        {{ $materialTracking->project?->project_name ?? '-' }}

                    </div>

                    @if(
                        $materialTracking->project?->project_number
                    )

                        <small class="text-muted">

                            {{ $materialTracking->project->project_number }}

                        </small>

                    @endif

                </div>

            </div>


            <hr>


            <div class="row">

                <div class="col-md-4">

                    <small class="text-muted">
                        Purchase Order
                    </small>

                    <div class="fw-semibold">

                        {{ $purchaseOrder->po_number }}

                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted">
                        Supplier
                    </small>

                    <div class="fw-semibold">

                        {{ $purchaseOrder->supplier_name ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted">
                        Remarks
                    </small>

                    <div>

                        {{ $materialTracking->remarks ?? '-' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         SUMMARY CARDS
    ========================================================== --}}

    @php

        $totalOrdered =
            $materialTracking->items
                ->sum('ordered_quantity');

        $totalReceived =
            $materialTracking->items
                ->sum('received_quantity');

        $totalAccepted =
            $materialTracking->items
                ->sum('accepted_quantity');

        $totalRejected =
            $materialTracking->items
                ->sum('rejected_quantity');

        $totalIssued =
            $materialTracking->items
                ->sum('issued_quantity');

        $totalConsumed =
            $materialTracking->items
                ->sum('consumed_quantity');

        $totalBalance =
            $materialTracking->items
                ->sum('balance_quantity');

    @endphp


    <div class="row mb-3">

        <div class="col-md-3 mb-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Ordered
                    </small>

                    <h4 class="mb-0">
                        {{ number_format($totalOrdered, 3) }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3 mb-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Received
                    </small>

                    <h4 class="mb-0">
                        {{ number_format($totalReceived, 3) }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3 mb-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Accepted
                    </small>

                    <h4 class="mb-0 text-success">
                        {{ number_format($totalAccepted, 3) }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3 mb-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Rejected
                    </small>

                    <h4 class="mb-0 text-danger">
                        {{ number_format($totalRejected, 3) }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-4 mb-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Issued
                    </small>

                    <h4 class="mb-0">
                        {{ number_format($totalIssued, 3) }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-4 mb-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Consumed
                    </small>

                    <h4 class="mb-0">
                        {{ number_format($totalConsumed, 3) }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-4 mb-3">

            <div class="card h-100 border-success">

                <div class="card-body">

                    <small class="text-muted">
                        Available Balance
                    </small>

                    <h4 class="mb-0 text-success">
                        {{ number_format($totalBalance, 3) }}
                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         MATERIAL DETAILS
    ========================================================== --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Material Details
            </strong>

        </div>


        <div class="card-body p-0">

            @if($materialTracking->items->count() > 0)

                <div class="table-responsive">

                    <table
                        class="table table-bordered table-hover mb-0 align-middle"
                    >

                        <thead class="table-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Material
                                </th>

                                <th>
                                    Item Code
                                </th>

                                <th>
                                    Unit
                                </th>

                                <th class="text-end">
                                    Ordered
                                </th>

                                <th class="text-end">
                                    Received
                                </th>

                                <th class="text-end">
                                    Accepted
                                </th>

                                <th class="text-end">
                                    Rejected
                                </th>

                                <th class="text-end">
                                    Issued
                                </th>

                                <th class="text-end">
                                    Consumed
                                </th>

                                <th class="text-end">
                                    Balance
                                </th>

                                <th>
                                    Delivery
                                </th>

                                <th>
                                    Remarks
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $materialTracking->items
                                as $item
                            )

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        <strong>
                                            {{ $item->material_name }}
                                        </strong>

                                    </td>


                                    <td>
                                        {{ $item->item_code ?? '-' }}
                                    </td>


                                    <td>
                                        {{ $item->unit }}
                                    </td>


                                    <td class="text-end">

                                        {{ number_format(
                                            (float)
                                            $item->ordered_quantity,
                                            3
                                        ) }}

                                    </td>


                                    <td class="text-end">

                                        {{ number_format(
                                            (float)
                                            $item->received_quantity,
                                            3
                                        ) }}

                                    </td>


                                    <td class="text-end">

                                        <span class="badge bg-success">

                                            {{ number_format(
                                                (float)
                                                $item->accepted_quantity,
                                                3
                                            ) }}

                                        </span>

                                    </td>


                                    <td class="text-end">

                                        {{ number_format(
                                            (float)
                                            $item->rejected_quantity,
                                            3
                                        ) }}

                                    </td>


                                    <td class="text-end">

                                        {{ number_format(
                                            (float)
                                            $item->issued_quantity,
                                            3
                                        ) }}

                                    </td>


                                    <td class="text-end">

                                        {{ number_format(
                                            (float)
                                            $item->consumed_quantity,
                                            3
                                        ) }}

                                    </td>


                                    <td class="text-end fw-semibold">

                                        {{ number_format(
                                            (float)
                                            $item->balance_quantity,
                                            3
                                        ) }}

                                    </td>


                                    <td>

                                        @if($item->deliveryItem?->delivery)

                                            <a
                                                href="#"
                                                class="text-decoration-none"
                                            >
                                                {{
                                                    $item
                                                        ->deliveryItem
                                                        ->delivery
                                                        ->delivery_number
                                                }}
                                            </a>

                                        @else

                                            -

                                        @endif

                                    </td>


                                    <td>

                                        {{ $item->remarks ?? '-' }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>


                        <tfoot class="table-light">

                            <tr>

                                <th colspan="4" class="text-end">
                                    Total
                                </th>

                                <th class="text-end">
                                    {{ number_format($totalOrdered, 3) }}
                                </th>

                                <th class="text-end">
                                    {{ number_format($totalReceived, 3) }}
                                </th>

                                <th class="text-end">
                                    {{ number_format($totalAccepted, 3) }}
                                </th>

                                <th class="text-end">
                                    {{ number_format($totalRejected, 3) }}
                                </th>

                                <th class="text-end">
                                    {{ number_format($totalIssued, 3) }}
                                </th>

                                <th class="text-end">
                                    {{ number_format($totalConsumed, 3) }}
                                </th>

                                <th class="text-end">
                                    {{ number_format($totalBalance, 3) }}
                                </th>

                                <th colspan="2">
                                </th>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            @else

                <div class="p-5 text-center text-muted">

                    No material tracking items found.

                </div>

            @endif

        </div>

    </div>

</div>

@endsection