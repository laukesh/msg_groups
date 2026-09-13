@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h4 class="mb-1">
                Material Tracking
            </h4>

            <div class="text-muted">
                Purchase Order:
                <strong>
                    {{ $purchaseOrder->po_number }}
                </strong>
            </div>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.procurement.tenders.purchase-orders.show',
                    [
                        'procurementTender' => $procurementTender,
                        'purchaseOrder' => $purchaseOrder,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back to PO
            </a>

            <a
                href="{{ route(
                    'admin.procurement.tenders.purchase-orders.material-trackings.create',
                    [
                        'procurementTender' => $procurementTender,
                        'purchaseOrder' => $purchaseOrder,
                    ]
                ) }}"
                class="btn btn-success"
            >
                + Create Material Tracking
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
         PO / PROJECT SUMMARY
    ========================================================== --}}

    <div class="card mb-3">

        <div class="card-header">
            <strong>Purchase Order Summary</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-2">

                    <small class="text-muted">
                        PO Number
                    </small>

                    <div class="fw-semibold">
                        {{ $purchaseOrder->po_number }}
                    </div>

                </div>


                <div class="col-md-3 mb-2">

                    <small class="text-muted">
                        Supplier
                    </small>

                    <div class="fw-semibold">
                        {{ $purchaseOrder->supplier_name ?? '-' }}
                    </div>

                </div>


                <div class="col-md-3 mb-2">

                    <small class="text-muted">
                        Project
                    </small>

                    <div class="fw-semibold">
                        {{ $purchaseOrder->project?->project_name ?? '-' }}
                    </div>

                    @if($purchaseOrder->project?->project_number)

                        <small class="text-muted">
                            {{ $purchaseOrder->project->project_number }}
                        </small>

                    @endif

                </div>


                <div class="col-md-3 mb-2">

                    <small class="text-muted">
                        PO Status
                    </small>

                    <div>

                        @php
                            $poStatusClass = match(
                                $purchaseOrder->status
                            ) {
                                'Issued' => 'bg-success',
                                'Approved' => 'bg-primary',
                                'Rejected' => 'bg-danger',
                                'Cancelled' => 'bg-danger',
                                default => 'bg-secondary',
                            };
                        @endphp

                        <span class="badge {{ $poStatusClass }}">
                            {{ $purchaseOrder->status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         TRACKING LIST
    ========================================================== --}}

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Material Tracking Records
            </strong>

            <span class="badge bg-secondary">
                {{ $trackings->total() }}
            </span>

        </div>


        <div class="card-body p-0">

            @if($trackings->count() > 0)

                <div class="table-responsive">

                    <table class="table table-bordered table-hover mb-0 align-middle">

                        <thead class="table-light">

                            <tr>

                                <th width="5%">
                                    #
                                </th>

                                <th>
                                    Tracking Number
                                </th>

                                <th>
                                    Tracking Date
                                </th>

                                <th>
                                    Project
                                </th>

                                <th class="text-center">
                                    Materials
                                </th>

                                <th class="text-end">
                                    Accepted
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
                                    Status
                                </th>

                                <th width="90">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($trackings as $tracking)

                                @php

                                    $accepted =
                                        $tracking->items
                                            ->sum(
                                                'accepted_quantity'
                                            );

                                    $issued =
                                        $tracking->items
                                            ->sum(
                                                'issued_quantity'
                                            );

                                    $consumed =
                                        $tracking->items
                                            ->sum(
                                                'consumed_quantity'
                                            );

                                    $balance =
                                        $tracking->items
                                            ->sum(
                                                'balance_quantity'
                                            );

                                    $statusClass = match(
                                        $tracking->status
                                    ) {
                                        'Available' => 'bg-success',
                                        'Partially Issued' => 'bg-warning text-dark',
                                        'Issued' => 'bg-primary',
                                        'Consumed' => 'bg-dark',
                                        default => 'bg-secondary',
                                    };

                                @endphp


                                <tr>

                                    <td>
                                        {{ $trackings->firstItem() + $loop->index }}
                                    </td>


                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.procurement.tenders.purchase-orders.material-trackings.show',
                                                [
                                                    'procurementTender' =>
                                                        $procurementTender,

                                                    'purchaseOrder' =>
                                                        $purchaseOrder,

                                                    'materialTracking' =>
                                                        $tracking,
                                                ]
                                            ) }}"
                                            class="fw-semibold text-decoration-none"
                                        >
                                            {{ $tracking->tracking_number }}
                                        </a>

                                    </td>


                                    <td>
                                        {{ $tracking->tracking_date?->format('d-m-Y') }}
                                    </td>


                                    <td>

                                        {{ $tracking->project?->project_name ?? '-' }}

                                        @if(
                                            $tracking->project?->project_number
                                        )

                                            <div class="small text-muted">
                                                {{ $tracking->project->project_number }}
                                            </div>

                                        @endif

                                    </td>


                                    <td class="text-center">

                                        <span class="badge bg-secondary">
                                            {{ $tracking->items->count() }}
                                        </span>

                                    </td>


                                    <td class="text-end">
                                        {{ number_format($accepted, 3) }}
                                    </td>


                                    <td class="text-end">
                                        {{ number_format($issued, 3) }}
                                    </td>


                                    <td class="text-end">
                                        {{ number_format($consumed, 3) }}
                                    </td>


                                    <td class="text-end fw-semibold">

                                        {{ number_format(
                                            $balance,
                                            3
                                        ) }}

                                    </td>


                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $tracking->status }}
                                        </span>

                                    </td>


                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.procurement.tenders.purchase-orders.material-trackings.show',
                                                [
                                                    'procurementTender' =>
                                                        $procurementTender,

                                                    'purchaseOrder' =>
                                                        $purchaseOrder,

                                                    'materialTracking' =>
                                                        $tracking,
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


                {{-- =================================================
                     PAGINATION
                ================================================== --}}

                @if($trackings->hasPages())

                    <div class="p-3">

                        {{ $trackings->links() }}

                    </div>

                @endif


            @else

                <div class="p-5 text-center">

                    <div class="mb-2 text-muted">
                        No Material Tracking records found.
                    </div>

                    <a
                        href="{{ route(
                            'admin.procurement.tenders.purchase-orders.material-trackings.create',
                            [
                                'procurementTender' =>
                                    $procurementTender,

                                'purchaseOrder' =>
                                    $purchaseOrder,
                            ]
                        ) }}"
                        class="btn btn-success"
                    >
                        + Create First Material Tracking
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection