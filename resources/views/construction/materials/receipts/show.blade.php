@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Material Receipt Details
            </h4>

            <p class="text-muted mb-2">

                {{ $project->project_number }}

                <span class="mx-1">•</span>

                {{ $project->project_name }}

            </p>

            <span class="badge bg-light text-dark border">

                {{ $materialReceipt->receipt_number }}

            </span>

        </div>


        <a href="{{ route(
            'admin.projects.construction.materials.receipts.index',
            $project
        ) }}"
           class="btn btn-light border">

            ← Back

        </a>

    </div>


    {{-- Receipt Information --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0 fw-bold">
                Receipt Information
            </h6>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Receipt Number
                    </small>

                    <strong>
                        {{ $materialReceipt->receipt_number }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Receipt Date
                    </small>

                    <strong>
                        {{ $materialReceipt->receipt_date?->format('d M Y') }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Delivery
                    </small>

                    <strong>
                        {{ $materialReceipt->delivery?->delivery_number ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Status
                    </small>

                    @php

                        $statusClass = match(
                            $materialReceipt->status
                        ) {
                            'Draft' => 'secondary',
                            'Received' => 'info',
                            'Under Inspection' => 'warning',
                            'Accepted' => 'success',
                            'Partially Accepted' => 'primary',
                            'Rejected' => 'danger',
                            'Cancelled' => 'dark',
                            default => 'secondary',
                        };

                    @endphp

                    <span class="badge bg-{{ $statusClass }}">
                        {{ $materialReceipt->status }}
                    </span>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Received By
                    </small>

                    <strong>
                        {{ $materialReceipt->receivedBy?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Challan
                    </small>

                    <strong>
                        {{ $materialReceipt->delivery?->challan_number ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Vehicle
                    </small>

                    <strong>
                        {{ $materialReceipt->delivery?->vehicle_number ?? '—' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Materials --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0 fw-bold">
                Received Materials
            </h6>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead>

                        <tr>

                            <th>#</th>
                            <th>Material</th>
                            <th>Delivered</th>
                            <th>Accepted</th>
                            <th>Rejected</th>
                            <th>Unit</th>
                            <th>Batch</th>
                            <th>Inspection</th>

                        </tr>

                    </thead>

                    <tbody>

                    @foreach(
                        $materialReceipt->items
                        as $item
                    )

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>

                                <strong>
                                    {{ $item->material?->material_code }}
                                </strong>

                                <br>

                                <small class="text-muted">
                                    {{ $item->material?->material_name }}
                                </small>

                            </td>

                            <td>
                                {{ number_format(
                                    $item->delivered_quantity,
                                    4
                                ) }}
                            </td>

                            <td>

                                <span class="text-success fw-semibold">

                                    {{ number_format(
                                        $item->accepted_quantity,
                                        4
                                    ) }}

                                </span>

                            </td>

                            <td>

                                @if($item->rejected_quantity > 0)

                                    <span class="text-danger fw-semibold">

                                        {{ number_format(
                                            $item->rejected_quantity,
                                            4
                                        ) }}

                                    </span>

                                @else

                                    0.0000

                                @endif

                            </td>

                            <td>
                                {{ $item->unit }}
                            </td>

                            <td>
                                {{ $item->batch_number ?: '—' }}
                            </td>

                            <td>

                                @if($item->inspection_required)

                                    <span class="badge bg-warning text-dark">
                                        Required
                                    </span>

                                @else

                                    <span class="badge bg-light text-dark border">
                                        Not Required
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Remarks --}}

    @if($materialReceipt->remarks)

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <h6 class="fw-bold mb-2">
                    Remarks
                </h6>

                <p class="text-muted mb-0">
                    {{ $materialReceipt->remarks }}
                </p>

            </div>

        </div>

    @endif


    {{-- Workflow --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h6 class="mb-0 fw-bold">
                Workflow Actions
            </h6>

        </div>

        <div class="card-body">

            <div class="d-flex gap-2 flex-wrap">


                @if(
                    in_array(
                        $materialReceipt->status,
                        ['Received', 'Draft']
                    )
                )

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.construction.materials.receipts.inspect',
                              [
                                  'project' => $project->id,
                                  'materialReceipt' =>
                                      $materialReceipt->id,
                              ]
                          ) }}">

                        @csrf

                        <button class="btn btn-warning">

                            Send for Inspection

                        </button>

                    </form>

                @endif


                @if(
                    $materialReceipt->status === 'Under Inspection'
                )

                    <span class="badge bg-warning text-dark p-2">

                        Inspection Pending

                    </span>

                    <span class="text-muted align-self-center">

                        Final acceptance/rejection should be
                        completed through the applicable inspection/
                        quality workflow.

                    </span>

                @endif


                @if(
                    !in_array(
                        $materialReceipt->status,
                        [
                            'Accepted',
                            'Partially Accepted',
                            'Cancelled'
                        ]
                    )
                )

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.construction.materials.receipts.cancel',
                              [
                                  'project' => $project->id,
                                  'materialReceipt' =>
                                      $materialReceipt->id,
                              ]
                          ) }}">

                        @csrf

                        <button class="btn btn-outline-danger">

                            Cancel Receipt

                        </button>

                    </form>

                @endif


                @if(
                    in_array(
                        $materialReceipt->status,
                        [
                            'Accepted',
                            'Partially Accepted'
                        ]
                    )
                )

                    <span class="text-success align-self-center">

                        ✓ Accepted quantity is eligible for stock.

                    </span>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection