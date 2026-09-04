@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Material Request Details
            </h4>

            <p class="text-muted mb-2">

                {{ $project->project_number }}
                <span class="mx-1">•</span>
                {{ $project->project_name }}

            </p>

            <span class="badge bg-light text-dark border">

                {{ $materialRequest->request_number }}

            </span>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.materials.requests.index',
                $project
            ) }}"
               class="btn btn-light border">

                ← Back

            </a>


            @if(in_array(
                $materialRequest->status,
                ['Draft', 'Rejected']
            ))

                <a href="{{ route(
                    'admin.projects.construction.materials.requests.edit',
                    [
                        'project' => $project->id,
                        'materialRequest' =>
                            $materialRequest->id,
                    ]
                ) }}"
                   class="btn btn-warning">

                    Edit

                </a>

            @endif

        </div>

    </div>


    {{-- Request Summary --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0 fw-bold">
                Request Information
            </h6>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Request Number
                    </small>

                    <strong>
                        {{ $materialRequest->request_number }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Request Date
                    </small>

                    <strong>
                        {{ $materialRequest->request_date?->format('d M Y') }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Required Date
                    </small>

                    <strong>
                        {{ $materialRequest->required_date?->format('d M Y') ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Status
                    </small>

                    @php

                        $statusClass = match(
                            $materialRequest->status
                        ) {
                            'Draft' => 'secondary',
                            'Submitted' => 'info',
                            'Under Review' => 'warning',
                            'Approved' => 'success',
                            'Rejected' => 'danger',
                            'Cancelled' => 'dark',
                            'Completed' => 'primary',
                            default => 'secondary',
                        };

                    @endphp

                    <span class="badge bg-{{ $statusClass }}">
                        {{ $materialRequest->status }}
                    </span>

                </div>


                <div class="col-md-6">

                    <small class="text-muted d-block">
                        Work Order
                    </small>

                    <strong>

                        @if($materialRequest->workOrder)

                            {{ $materialRequest->workOrder->work_order_number }}
                            -
                            {{ $materialRequest->workOrder->work_order_title }}

                        @else

                            —

                        @endif

                    </strong>

                </div>


                <div class="col-md-6">

                    <small class="text-muted d-block">
                        Requested By
                    </small>

                    <strong>
                        {{ $materialRequest->requestedBy?->name ?? '—' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Materials --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0 fw-bold">
                Requested Materials
            </h6>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead>

                        <tr>

                            <th>#</th>
                            <th>Material Code</th>
                            <th>Material</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Remarks</th>

                        </tr>

                    </thead>

                    <tbody>

                    @foreach($materialRequest->items as $item)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>

                                {{ $item->material?->material_code ?? '—' }}

                            </td>

                            <td>

                                <strong>
                                    {{ $item->material?->material_name ?? '—' }}
                                </strong>

                            </td>

                            <td>
                                {{ $item->material?->category ?? '—' }}
                            </td>

                            <td>
                                {{ number_format(
                                    $item->requested_quantity,
                                    4
                                ) }}
                            </td>

                            <td>
                                {{ $item->unit }}
                            </td>

                            <td>
                                {{ $item->remarks ?? '—' }}
                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Remarks --}}

    @if($materialRequest->remarks)

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <h6 class="fw-bold">
                    Remarks
                </h6>

                <p class="mb-0 text-muted">
                    {{ $materialRequest->remarks }}
                </p>

            </div>

        </div>

    @endif


    {{-- Approval Information --}}

    @if($materialRequest->approved_at)

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <h6 class="fw-bold mb-3">
                    Approval Information
                </h6>

                <div class="row">

                    <div class="col-md-4">

                        <small class="text-muted d-block">
                            Approved By
                        </small>

                        <strong>
                            {{ $materialRequest->approvedBy?->name ?? '—' }}
                        </strong>

                    </div>

                    <div class="col-md-4">

                        <small class="text-muted d-block">
                            Approved At
                        </small>

                        <strong>
                            {{ $materialRequest->approved_at?->format('d M Y H:i') }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- Workflow Actions --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h6 class="mb-0 fw-bold">
                Workflow Actions
            </h6>

        </div>

        <div class="card-body">

            <div class="d-flex gap-2 flex-wrap">

                @if($materialRequest->status === 'Draft')

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.construction.materials.requests.submit',
                              [
                                  'project' => $project->id,
                                  'materialRequest' =>
                                      $materialRequest->id,
                              ]
                          ) }}">

                        @csrf

                        <button class="btn btn-primary">
                            Submit for Review
                        </button>

                    </form>

                @endif


                @if($materialRequest->status === 'Submitted')

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.construction.materials.requests.review',
                              [
                                  'project' => $project->id,
                                  'materialRequest' =>
                                      $materialRequest->id,
                              ]
                          ) }}">

                        @csrf

                        <button class="btn btn-warning">
                            Start Review
                        </button>

                    </form>

                @endif


                @if($materialRequest->status === 'Under Review')

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.construction.materials.requests.approve',
                              [
                                  'project' => $project->id,
                                  'materialRequest' =>
                                      $materialRequest->id,
                              ]
                          ) }}">

                        @csrf

                        <button class="btn btn-success">
                            Approve
                        </button>

                    </form>


                    <form method="POST"
                          action="{{ route(
                              'admin.projects.construction.materials.requests.reject',
                              [
                                  'project' => $project->id,
                                  'materialRequest' =>
                                      $materialRequest->id,
                              ]
                          ) }}">

                        @csrf

                        <button class="btn btn-danger">
                            Reject
                        </button>

                    </form>

                @endif


                @if(!in_array(
                    $materialRequest->status,
                    ['Approved', 'Completed', 'Cancelled']
                ))

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.construction.materials.requests.cancel',
                              [
                                  'project' => $project->id,
                                  'materialRequest' =>
                                      $materialRequest->id,
                              ]
                          ) }}">

                        @csrf

                        <button class="btn btn-outline-danger">
                            Cancel Request
                        </button>

                    </form>

                @endif

                @if($materialRequest->status === 'Approved')

                    <a href="{{ route(
                        'admin.projects.construction.materials.deliveries.create',
                        [
                            'project' => $project->id,
                            'materialRequest' =>
                                $materialRequest->id,
                        ]
                    ) }}"
                       class="btn btn-primary">

                        + Create Material Delivery

                    </a>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection