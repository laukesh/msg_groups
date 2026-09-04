@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                New Material Delivery
            </h4>

            <p class="text-muted mb-0">

                {{ $project->project_number }}
                <span class="mx-1">•</span>
                {{ $project->project_name }}

            </p>

        </div>

        <a href="{{ route(
            'admin.projects.construction.materials.requests.show',
            [
                'project' => $project->id,
                'materialRequest' =>
                    $materialRequest->id,
            ]
        ) }}"
           class="btn btn-secondary">

            ← Back to Material Request

        </a>

    </div>


    <form method="POST"
          action="{{ route(
              'admin.projects.construction.materials.deliveries.store',
              $project
          ) }}">

        @csrf


        <input type="hidden"
               name="material_request_id"
               value="{{ $materialRequest->id }}">


        {{-- Request Reference --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h6 class="mb-0 fw-bold">
                    Material Request
                </h6>

            </div>

            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-4">

                        <small class="text-muted d-block">
                            Request Number
                        </small>

                        <strong>
                            {{ $materialRequest->request_number }}
                        </strong>

                    </div>


                    <div class="col-md-4">

                        <small class="text-muted d-block">
                            Request Date
                        </small>

                        <strong>
                            {{ $materialRequest->request_date?->format('d M Y') }}
                        </strong>

                    </div>


                    <div class="col-md-4">

                        <small class="text-muted d-block">
                            Work Order
                        </small>

                        <strong>

                            {{ $materialRequest->workOrder?->work_order_number ?? '—' }}

                        </strong>

                    </div>

                </div>

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

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Delivery Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="delivery_date"
                               value="{{ old(
                                   'delivery_date',
                                   date('Y-m-d')
                               ) }}"
                               class="form-control"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Vehicle Number
                        </label>

                        <input type="text"
                               name="vehicle_number"
                               value="{{ old('vehicle_number') }}"
                               class="form-control"
                               placeholder="e.g. DL01AB1234">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Challan Number
                        </label>

                        <input type="text"
                               name="challan_number"
                               value="{{ old('challan_number') }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Challan Date
                        </label>

                        <input type="date"
                               name="challan_date"
                               value="{{ old('challan_date') }}"
                               class="form-control">

                    </div>


                    <div class="col-md-8">

                        <label class="form-label">
                            Remarks
                        </label>

                        <input type="text"
                               name="remarks"
                               value="{{ old('remarks') }}"
                               class="form-control">

                    </div>

                </div>

            </div>

        </div>


        {{-- Delivery Items --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h6 class="mb-0 fw-bold">
                    Delivery Items
                </h6>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered align-middle">

                        <thead>

                            <tr>

                                <th>
                                    Material
                                </th>

                                <th>
                                    Requested Qty.
                                </th>

                                <th>
                                    Delivery Qty.
                                </th>

                                <th>
                                    Unit
                                </th>

                                <th>
                                    Batch Number
                                </th>

                                <th>
                                    Remarks
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                        @foreach(
                            $materialRequest->items
                            as $index => $requestItem
                        )

                            <tr>

                                <td>

                                    <strong>
                                        {{ $requestItem->material?->material_code }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ $requestItem->material?->material_name }}
                                    </small>

                                    <input type="hidden"
                                           name="items[{{ $index }}][material_id]"
                                           value="{{ $requestItem->material_id }}">

                                </td>


                                <td>

                                    {{ number_format(
                                        $requestItem->requested_quantity,
                                        4
                                    ) }}

                                    <input type="hidden"
                                           name="items[{{ $index }}][ordered_quantity]"
                                           value="{{ $requestItem->requested_quantity }}">

                                </td>


                                <td>

                                    <input type="number"
                                           name="items[{{ $index }}][delivered_quantity]"
                                           value="{{ old(
                                               "items.$index.delivered_quantity",
                                               $requestItem->requested_quantity
                                           ) }}"
                                           class="form-control"
                                           step="0.0001"
                                           min="0.0001"
                                           max="{{ $requestItem->requested_quantity }}"
                                           required>

                                </td>


                                <td>

                                    {{ $requestItem->unit }}

                                    <input type="hidden"
                                           name="items[{{ $index }}][unit]"
                                           value="{{ $requestItem->unit }}">

                                </td>


                                <td>

                                    <input type="text"
                                           name="items[{{ $index }}][batch_number]"
                                           value="{{ old(
                                               "items.$index.batch_number"
                                           ) }}"
                                           class="form-control">

                                </td>


                                <td>

                                    <input type="text"
                                           name="items[{{ $index }}][remarks]"
                                           value="{{ old(
                                               "items.$index.remarks"
                                           ) }}"
                                           class="form-control">

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2">

            <a href="{{ route(
                'admin.projects.construction.materials.requests.show',
                [
                    'project' => $project->id,
                    'materialRequest' =>
                        $materialRequest->id,
                ]
            ) }}"
               class="btn btn-secondary">

                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary">

                Save Delivery

            </button>

        </div>

    </form>

</div>

@endsection