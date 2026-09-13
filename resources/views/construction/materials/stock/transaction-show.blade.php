@extends('layouts.app')

@section('title', 'Stock Transaction')

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | Reference Display
    |--------------------------------------------------------------------------
    */

    $referenceType = '—';
    $referenceRecord = null;
    $referenceNumber = null;

    if (!empty($transaction->reference_type)) {

        $referenceType = match ($transaction->reference_type) {

            \App\Models\ConstructionMaterialReceipt::class
                => 'Material Receipt',

            \App\Models\ConstructionMaterialRequest::class
                => 'Material Request',

            \App\Models\ConstructionMaterialDelivery::class
                => 'Material Delivery',

            default
                => class_basename($transaction->reference_type),
        };

        /*
        |--------------------------------------------------------------------------
        | Load Reference Record
        |--------------------------------------------------------------------------
        |
        | This is intentionally handled safely so old transactions do not
        | generate an error if their reference record no longer exists.
        |
        */

        if (
            $transaction->reference_id &&
            class_exists($transaction->reference_type)
        ) {
            try {

                $referenceRecord =
                    $transaction->reference_type::find(
                        $transaction->reference_id
                    );

            } catch (\Throwable $e) {

                $referenceRecord = null;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Friendly Reference Number
        |--------------------------------------------------------------------------
        */

        if ($referenceRecord) {

            if (
                $referenceRecord instanceof
                \App\Models\ConstructionMaterialReceipt
            ) {

                $referenceNumber =
                    $referenceRecord->receipt_number;

            } elseif (
                $referenceRecord instanceof
                \App\Models\ConstructionMaterialRequest
            ) {

                $referenceNumber =
                    $referenceRecord->request_number;

            } elseif (
                $referenceRecord instanceof
                \App\Models\ConstructionMaterialDelivery
            ) {

                $referenceNumber =
                    $referenceRecord->delivery_number;
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Work Order
    |--------------------------------------------------------------------------
    */

    $workOrder = null;

    /*
     * Preferred:
     * ConstructionMaterialTransaction -> workOrder
     */
    if (
        isset($transaction->workOrder) &&
        $transaction->workOrder
    ) {
        $workOrder = $transaction->workOrder;
    }

    /*
     * Work order number can have different naming depending on the
     * existing construction work order table.
     */
    $workOrderNumber = null;

    if ($workOrder) {

        $workOrderNumber =
            $workOrder->work_order_number
            ?? $workOrder->work_order_no
            ?? $workOrder->order_number
            ?? $workOrder->code
            ?? null;
    }


    /*
    |--------------------------------------------------------------------------
    | Transaction Type Badge
    |--------------------------------------------------------------------------
    */

    $transactionBadgeClass = match (
        $transaction->transaction_type
    ) {

        'Receipt',
        'Transfer In',
        'Return'
            => 'bg-success',

        'Issue',
        'Consumption',
        'Transfer Out'
            => 'bg-primary',

        'Wastage'
            => 'bg-danger',

        'Adjustment'
            => 'bg-warning text-dark',

        default
            => 'bg-secondary',
    };

@endphp


<div class="container-fluid">

    {{-- ============================================================= --}}
    {{-- HEADER --}}
    {{-- ============================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Stock Transaction
            </h4>

            <div class="text-muted">

                {{ $project->project_number ?? $project->project_code }}

                <span class="mx-1">•</span>

                {{ $project->project_name }}

            </div>

        </div>


        <div>

            <a
                href="{{ route(
                    'admin.projects.construction.materials.stock.transactions',
                    $project
                ) }}"
                class="btn btn-light border"
            >
                ← Back
            </a>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- MAIN TRANSACTION CARD --}}
    {{-- ============================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="row g-4">

                {{-- Transaction Number --}}

                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Transaction Number
                    </div>

                    <div class="fs-4 fw-bold">
                        {{ $transaction->transaction_number }}
                    </div>

                </div>


                {{-- Transaction Type --}}

                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Transaction Type
                    </div>

                    <div>

                        <span
                            class="badge {{ $transactionBadgeClass }} px-3 py-2"
                        >
                            {{ $transaction->transaction_type }}
                        </span>

                    </div>

                </div>


                {{-- Transaction Date --}}

                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Transaction Date
                    </div>

                    <div class="fw-semibold">

                        {{ optional($transaction->transaction_date)->format('d M Y H:i') }}

                    </div>

                </div>


                {{-- Material --}}

                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Material
                    </div>

                    <div class="fw-bold">

                        {{ $transaction->material->material_code ?? '—' }}

                    </div>

                    @if($transaction->material)

                        <div class="text-muted small mt-1">

                            {{ $transaction->material->material_name }}

                        </div>

                    @endif

                </div>


                {{-- Quantity --}}

                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Quantity
                    </div>

                    <div class="fs-4 fw-bold">

                        {{ number_format(
                            (float) $transaction->quantity,
                            4
                        ) }}

                        <span class="fs-6 fw-normal text-muted">

                            {{ $transaction->unit }}

                        </span>

                    </div>

                </div>


                {{-- Batch Number --}}

                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Batch Number
                    </div>

                    <div class="fw-semibold">

                        {{ $transaction->batch_number ?: '—' }}

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- REFERENCE TYPE --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Reference Type
                    </div>

                    <div class="fw-semibold">

                        {{ $referenceType }}

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- REFERENCE --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Reference
                    </div>

                    <div class="fw-semibold">

                        @if($referenceNumber)

                            {{ $referenceNumber }}

                        @elseif($transaction->reference_id)

                            #{{ $transaction->reference_id }}

                        @else

                            —

                        @endif

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- WORK ORDER --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Work Order
                    </div>

                    <div class="fw-semibold">

                        @if($workOrderNumber)

                            {{ $workOrderNumber }}

                        @elseif($transaction->construction_work_order_id)

                            #{{ $transaction->construction_work_order_id }}

                        @else

                            —

                        @endif

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- CREATED BY --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Created By
                    </div>

                    <div class="fw-semibold">

                        {{ $transaction->creator->name ?? '—' }}

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- CREATED AT --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Created At
                    </div>

                    <div class="fw-semibold">

                        {{ optional($transaction->created_at)->format('d M Y H:i') }}

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- STOCK --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Stock ID
                    </div>

                    <div class="fw-semibold">

                        {{ $transaction->stock_id ?? '—' }}

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- REMARKS --}}
                {{-- ================================================= --}}

                <div class="col-12">

                    <div class="text-muted small mb-1">
                        Remarks
                    </div>

                    <div class="fw-semibold">

                        {{ $transaction->remarks ?: '—' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- REFERENCE DETAILS --}}
    {{-- ============================================================= --}}

    @if($referenceRecord)

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h6 class="mb-0 fw-bold">
                    Reference Details
                </h6>

            </div>

            <div class="card-body">

                <div class="row g-4">

                    @if(
                        $referenceRecord instanceof
                        \App\Models\ConstructionMaterialReceipt
                    )

                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Receipt Number
                            </div>

                            <div class="fw-bold">

                                {{ $referenceRecord->receipt_number }}

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Receipt Date
                            </div>

                            <div class="fw-semibold">

                                {{ optional(
                                    $referenceRecord->receipt_date
                                )->format('d M Y') }}

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Receipt Status
                            </div>

                            <div>

                                <span class="badge bg-success">

                                    {{ $referenceRecord->status }}

                                </span>

                            </div>

                        </div>


                    @elseif(
                        $referenceRecord instanceof
                        \App\Models\ConstructionMaterialRequest
                    )

                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Request Number
                            </div>

                            <div class="fw-bold">

                                {{ $referenceRecord->request_number }}

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Request Date
                            </div>

                            <div class="fw-semibold">

                                {{ optional(
                                    $referenceRecord->request_date
                                )->format('d M Y') }}

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Request Status
                            </div>

                            <div>

                                <span class="badge bg-primary">

                                    {{ $referenceRecord->status }}

                                </span>

                            </div>

                        </div>


                    @elseif(
                        $referenceRecord instanceof
                        \App\Models\ConstructionMaterialDelivery
                    )

                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Delivery Number
                            </div>

                            <div class="fw-bold">

                                {{ $referenceRecord->delivery_number }}

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Delivery Date
                            </div>

                            <div class="fw-semibold">

                                {{ optional(
                                    $referenceRecord->delivery_date
                                )->format('d M Y') }}

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Delivery Status
                            </div>

                            <div>

                                <span class="badge bg-info">

                                    {{ $referenceRecord->status }}

                                </span>

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    @endif


    {{-- ============================================================= --}}
    {{-- NAVIGATION --}}
    {{-- ============================================================= --}}

    <div class="d-flex justify-content-between mb-4">

        <a
            href="{{ route(
                'admin.projects.construction.materials.stock.index',
                $project
            ) }}"
            class="btn btn-secondary"
        >
            ← Back to Stock
        </a>


        <a
            href="{{ route(
                'admin.projects.construction.materials.stock.transactions',
                $project
            ) }}"
            class="btn btn-primary"
        >
            Transaction History
        </a>

    </div>

</div>

@endsection