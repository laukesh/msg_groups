@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Material Delivery Details
            </h4>

            <p class="text-muted mb-2">

                {{ $project->project_number }}
                <span class="mx-1">•</span>
                {{ $project->project_name }}

            </p>

            <span class="badge bg-light text-dark border">

                {{ $materialDelivery->delivery_number }}

            </span>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.materials.deliveries.index',
                $project
            ) }}"
               class="btn btn-light border">

                ← Back

            </a>


            @if(!in_array(
                $materialDelivery->status,
                ['Received', 'Cancelled']
            ))

                <a href="{{ route(
                    'admin.projects.construction.materials.deliveries.edit',
                    [
                        'project' => $project->id,
                        'materialDelivery' =>
                            $materialDelivery->id,
                    ]
                ) }}"
                   class="btn btn-warning">

                    Edit

                </a>

            @endif

        </div>

    </div>


    {{-- Delivery Information --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0 fw-bold">
                Delivery Information
            </h6>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Delivery Number
                    </small>

                    <strong>
                        {{ $materialDelivery->delivery_number }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Delivery Date
                    </small>

                    <strong>
                        {{ $materialDelivery->delivery_date?->format('d M Y') }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Material Request
                    </small>

                    @if($materialDelivery->materialRequest)

                        <a href="{{ route(
                            'admin.projects.construction.materials.requests.show',
                            [
                                'project' => $project->id,
                                'materialRequest' =>
                                    $materialDelivery->materialRequest->id,
                            ]
                        ) }}">

                            {{ $materialDelivery->materialRequest->request_number }}

                        </a>

                    @else

                        —

                    @endif

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Status
                    </small>

                    @php

                        $statusClass = match(
                            $materialDelivery->status
                        ) {
                            'Expected' => 'secondary',
                            'Partially Delivered' => 'warning',
                            'Delivered' => 'info',
                            'Received' => 'success',
                            'Cancelled' => 'danger',
                            default => 'secondary',
                        };

                    @endphp

                    <span class="badge bg-{{ $statusClass }}">
                        {{ $materialDelivery->status }}
                    </span>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Vehicle Number
                    </small>

                    <strong>
                        {{ $materialDelivery->vehicle_number ?: '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Challan Number
                    </small>

                    <strong>
                        {{ $materialDelivery->challan_number ?: '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Challan Date
                    </small>

                    <strong>
                        {{ $materialDelivery->challan_date?->format('d M Y') ?? '—' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Items --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0 fw-bold">
                Delivered Materials
            </h6>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead>

                        <tr>

                            <th>#</th>
                            <th>Material</th>
                            <th>Requested / Ordered</th>
                            <th>Delivered</th>
                            <th>Unit</th>
                            <th>Batch Number</th>
                            <th>Remarks</th>

                        </tr>

                    </thead>

                    <tbody>

                    @foreach(
                        $materialDelivery->items
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
                                    $item->ordered_quantity ?? 0,
                                    4
                                ) }}

                            </td>

                            <td>

                                <strong>
                                    {{ number_format(
                                        $item->delivered_quantity,
                                        4
                                    ) }}
                                </strong>

                            </td>

                            <td>
                                {{ $item->unit }}
                            </td>

                            <td>
                                {{ $item->batch_number ?: '—' }}
                            </td>

                            <td>
                                {{ $item->remarks ?: '—' }}
                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Remarks --}}

    @if($materialDelivery->remarks)

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <h6 class="fw-bold mb-2">
                    Remarks
                </h6>

                <p class="mb-0 text-muted">
                    {{ $materialDelivery->remarks }}
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
                        $materialDelivery->status,
                        ['Delivered', 'Partially Delivered']
                    )
                )

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.construction.materials.deliveries.receive',
                              [
                                  'project' => $project->id,
                                  'materialDelivery' =>
                                      $materialDelivery->id,
                              ]
                          ) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-success">

                            Mark as Received

                        </button>

                    </form>

                @endif


                @if(!in_array(
                    $materialDelivery->status,
                    ['Received', 'Cancelled']
                ))

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.construction.materials.deliveries.cancel',
                              [
                                  'project' => $project->id,
                                  'materialDelivery' =>
                                      $materialDelivery->id,
                              ]
                          ) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-outline-danger">

                            Cancel Delivery

                        </button>

                    </form>

                @endif


                @if($materialDelivery->status === 'Received')

                    <span class="text-success align-self-center">

                        ✓ Delivery received.
                        Receipt & inspection will be recorded separately.

                    </span>

                @endif

                @if(
                    in_array(
                        $materialDelivery->status,
                        ['Delivered', 'Received']
                    )
                    &&
                    !$materialDelivery
                        ->receipts()
                        ->whereNotIn('status', ['Cancelled', 'Rejected'])
                        ->exists()
                )

                    <a href="{{ route(
                        'admin.projects.construction.materials.receipts.create',
                        [
                            'project' => $project->id,
                            'materialDelivery' =>
                                $materialDelivery->id,
                        ]
                    ) }}"
                       class="btn btn-primary">

                        + Create Material Receipt

                    </a>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection