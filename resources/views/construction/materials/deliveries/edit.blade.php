@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Edit Material Delivery
            </h4>

            <p class="text-muted mb-0">

                {{ $materialDelivery->delivery_number }}
                <span class="mx-1">•</span>
                {{ $project->project_number }}
                -
                {{ $project->project_name }}

            </p>

        </div>


        <a href="{{ route(
            'admin.projects.construction.materials.deliveries.show',
            [
                'project' => $project->id,
                'materialDelivery' =>
                    $materialDelivery->id,
            ]
        ) }}"
           class="btn btn-secondary">

            ← Back to Delivery

        </a>

    </div>


    <form method="POST"
          action="{{ route(
              'admin.projects.construction.materials.deliveries.update',
              [
                  'project' => $project->id,
                  'materialDelivery' =>
                      $materialDelivery->id,
              ]
          ) }}">

        @csrf
        @method('PUT')


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
                            Delivery Number
                        </label>

                        <input type="text"
                               value="{{ $materialDelivery->delivery_number }}"
                               class="form-control"
                               readonly>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Delivery Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="delivery_date"
                               value="{{ old(
                                   'delivery_date',
                                   $materialDelivery->delivery_date?->format('Y-m-d')
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
                               value="{{ old(
                                   'vehicle_number',
                                   $materialDelivery->vehicle_number
                               ) }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Challan Number
                        </label>

                        <input type="text"
                               name="challan_number"
                               value="{{ old(
                                   'challan_number',
                                   $materialDelivery->challan_number
                               ) }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Challan Date
                        </label>

                        <input type="date"
                               name="challan_date"
                               value="{{ old(
                                   'challan_date',
                                   $materialDelivery->challan_date?->format('Y-m-d')
                               ) }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Remarks
                        </label>

                        <input type="text"
                               name="remarks"
                               value="{{ old(
                                   'remarks',
                                   $materialDelivery->remarks
                               ) }}"
                               class="form-control">

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

                                <th>Material</th>
                                <th>Ordered Qty.</th>
                                <th>Delivered Qty.</th>
                                <th>Unit</th>
                                <th>Batch Number</th>
                                <th>Remarks</th>

                            </tr>

                        </thead>

                        <tbody>

                        @foreach(
                            $materialDelivery->items
                            as $index => $item
                        )

                            <tr>

                                <td>

                                    <strong>
                                        {{ $item->material?->material_code }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ $item->material?->material_name }}
                                    </small>

                                    <input type="hidden"
                                           name="items[{{ $index }}][material_id]"
                                           value="{{ $item->material_id }}">

                                </td>


                                <td>

                                    {{ number_format(
                                        $item->ordered_quantity ?? 0,
                                        4
                                    ) }}

                                    <input type="hidden"
                                           name="items[{{ $index }}][ordered_quantity]"
                                           value="{{ $item->ordered_quantity }}">

                                </td>


                                <td>

                                    <input type="number"
                                           name="items[{{ $index }}][delivered_quantity]"
                                           value="{{ old(
                                               "items.$index.delivered_quantity",
                                               $item->delivered_quantity
                                           ) }}"
                                           class="form-control"
                                           step="0.0001"
                                           min="0.0001"
                                           required>

                                </td>


                                <td>

                                    {{ $item->unit }}

                                    <input type="hidden"
                                           name="items[{{ $index }}][unit]"
                                           value="{{ $item->unit }}">

                                </td>


                                <td>

                                    <input type="text"
                                           name="items[{{ $index }}][batch_number]"
                                           value="{{ old(
                                               "items.$index.batch_number",
                                               $item->batch_number
                                           ) }}"
                                           class="form-control">

                                </td>


                                <td>

                                    <input type="text"
                                           name="items[{{ $index }}][remarks]"
                                           value="{{ old(
                                               "items.$index.remarks",
                                               $item->remarks
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
                'admin.projects.construction.materials.deliveries.show',
                [
                    'project' => $project->id,
                    'materialDelivery' =>
                        $materialDelivery->id,
                ]
            ) }}"
               class="btn btn-secondary">

                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary">

                Update Delivery

            </button>

        </div>

    </form>

</div>

@endsection