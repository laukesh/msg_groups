@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Material Receipt
            </h4>

            <p class="text-muted mb-0">

                {{ $project->project_number }}

                <span class="mx-1">•</span>

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
              'admin.projects.construction.materials.receipts.store',
              $project
          ) }}">

        @csrf


        <input type="hidden"
               name="material_delivery_id"
               value="{{ $materialDelivery->id }}">


        {{-- Delivery Reference --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h6 class="mb-0 fw-bold">
                    Delivery Reference
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
                            Challan Number
                        </small>

                        <strong>
                            {{ $materialDelivery->challan_number ?: '—' }}
                        </strong>

                    </div>


                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            Material Request
                        </small>

                        <strong>
                            {{ $materialDelivery->materialRequest?->request_number ?? '—' }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- Receipt Information --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h6 class="mb-0 fw-bold">
                    Receipt Information
                </h6>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <label class="form-label">
                            Receipt Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="receipt_date"
                               value="{{ old(
                                   'receipt_date',
                                   date('Y-m-d')
                               ) }}"
                               class="form-control"
                               required>

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


        {{-- Receipt Items --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h6 class="mb-0 fw-bold">
                    Material Receipt & Inspection
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
                                    Delivered
                                </th>

                                <th>
                                    Accepted
                                </th>

                                <th>
                                    Rejected
                                </th>

                                <th>
                                    Unit
                                </th>

                                <th>
                                    Batch
                                </th>

                                <th>
                                    Inspection
                                </th>

                                <th>
                                    Remarks
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                        @foreach(
                            $materialDelivery->items
                            as $index => $deliveryItem
                        )

                            <tr>

                                <td>

                                    <strong>
                                        {{ $deliveryItem->material?->material_code }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ $deliveryItem->material?->material_name }}
                                    </small>

                                    <input type="hidden"
                                           name="items[{{ $index }}][material_id]"
                                           value="{{ $deliveryItem->material_id }}">

                                </td>


                                <td>

                                    <strong>
                                        {{ number_format(
                                            $deliveryItem->delivered_quantity,
                                            4
                                        ) }}
                                    </strong>

                                    <input type="hidden"
                                           name="items[{{ $index }}][delivered_quantity]"
                                           value="{{ $deliveryItem->delivered_quantity }}">

                                </td>


                                <td>

                                    <input type="number"
                                           name="items[{{ $index }}][accepted_quantity]"
                                           value="{{ old(
                                               "items.$index.accepted_quantity",
                                               $deliveryItem->delivered_quantity
                                           ) }}"
                                           class="form-control accepted-qty"
                                           step="0.0001"
                                           min="0"
                                           max="{{ $deliveryItem->delivered_quantity }}"
                                           required>

                                </td>


                                <td>

                                    <input type="number"
                                           name="items[{{ $index }}][rejected_quantity]"
                                           value="{{ old(
                                               "items.$index.rejected_quantity",
                                               0
                                           ) }}"
                                           class="form-control rejected-qty"
                                           step="0.0001"
                                           min="0"
                                           max="{{ $deliveryItem->delivered_quantity }}"
                                           required>

                                </td>


                                <td>

                                    {{ $deliveryItem->unit }}

                                    <input type="hidden"
                                           name="items[{{ $index }}][unit]"
                                           value="{{ $deliveryItem->unit }}">

                                </td>


                                <td>

                                    <input type="text"
                                           name="items[{{ $index }}][batch_number]"
                                           value="{{ old(
                                               "items.$index.batch_number",
                                               $deliveryItem->batch_number
                                           ) }}"
                                           class="form-control">

                                </td>


                                <td class="text-center">

                                    <input type="hidden"
                                           name="items[{{ $index }}][inspection_required]"
                                           value="0">

                                    <input type="checkbox"
                                           name="items[{{ $index }}][inspection_required]"
                                           value="1"
                                           checked>

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

                <div class="alert alert-info mt-3 mb-0">

                    <strong>Important:</strong>

                    Accepted Quantity + Rejected Quantity must equal
                    Delivered Quantity.

                    Accepted quantity will become eligible for stock
                    after the receipt/inspection process.

                </div>

            </div>

        </div>


        {{-- Buttons --}}

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

                Save Material Receipt

            </button>

        </div>

    </form>

</div>

@endsection