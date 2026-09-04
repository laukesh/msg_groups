@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Edit Material Request
            </h4>

            <p class="text-muted mb-0">

                {{ $materialRequest->request_number }}

                <span class="mx-1">•</span>

                {{ $project->project_number }}
                -
                {{ $project->project_name }}

            </p>

        </div>

        <a href="{{ route(
            'admin.projects.construction.materials.requests.show',
            [
                'project' => $project->id,
                'materialRequest' => $materialRequest->id,
            ]
        ) }}"
           class="btn btn-secondary">

            ← Back to Request

        </a>

    </div>


    <form method="POST"
          action="{{ route(
              'admin.projects.construction.materials.requests.update',
              [
                  'project' => $project->id,
                  'materialRequest' => $materialRequest->id,
              ]
          ) }}">

        @csrf
        @method('PUT')


        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h6 class="mb-0 fw-bold">
                    Request Information
                </h6>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Request Number
                        </label>

                        <input type="text"
                               value="{{ $materialRequest->request_number }}"
                               class="form-control"
                               readonly>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Request Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="request_date"
                               value="{{ old(
                                   'request_date',
                                   $materialRequest->request_date?->format('Y-m-d')
                               ) }}"
                               class="form-control"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Required Date
                        </label>

                        <input type="date"
                               name="required_date"
                               value="{{ old(
                                   'required_date',
                                   $materialRequest->required_date?->format('Y-m-d')
                               ) }}"
                               class="form-control">

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            Work Order
                        </label>

                        <select name="construction_work_order_id"
                                class="form-select">

                            <option value="">
                                Select Work Order
                            </option>

                            @foreach($workOrders as $workOrder)

                                <option value="{{ $workOrder->id }}"
                                    @selected(
                                        old(
                                            'construction_work_order_id',
                                            $materialRequest->construction_work_order_id
                                        ) == $workOrder->id
                                    )>

                                    {{ $workOrder->work_order_number }}
                                    -
                                    {{ $workOrder->work_order_title }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea name="remarks"
                                  rows="3"
                                  class="form-control">{{ old(
                                      'remarks',
                                      $materialRequest->remarks
                                  ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- Items --}}

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

                                <th>Material</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Remarks</th>

                            </tr>

                        </thead>

                        <tbody>

                        @foreach($materialRequest->items as $index => $item)

                            <tr>

                                <td>

                                    <select
                                        name="items[{{ $index }}][material_id]"
                                        class="form-select material-select"
                                        required>

                                        @foreach($materials as $material)

                                            <option
                                                value="{{ $material->id }}"
                                                data-unit="{{ $material->unit }}"
                                                @selected(
                                                    $item->material_id ==
                                                    $material->id
                                                )>

                                                {{ $material->material_code }}
                                                -
                                                {{ $material->material_name }}

                                            </option>

                                        @endforeach

                                    </select>

                                </td>


                                <td>

                                    <input type="number"
                                           name="items[{{ $index }}][requested_quantity]"
                                           value="{{ old(
                                               "items.$index.requested_quantity",
                                               $item->requested_quantity
                                           ) }}"
                                           class="form-control"
                                           step="0.0001"
                                           min="0.0001"
                                           required>

                                </td>


                                <td>

                                    <input type="text"
                                           name="items[{{ $index }}][unit]"
                                           value="{{ $item->unit }}"
                                           class="form-control unit-field"
                                           readonly
                                           required>

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
                'admin.projects.construction.materials.requests.show',
                [
                    'project' => $project->id,
                    'materialRequest' => $materialRequest->id,
                ]
            ) }}"
               class="btn btn-secondary">

                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary">

                Update Material Request

            </button>

        </div>

    </form>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    document.addEventListener(
        'change',
        function (event) {

            if (
                event.target.classList.contains(
                    'material-select'
                )
            ) {

                const option =
                    event.target.options[
                        event.target.selectedIndex
                    ];

                const row =
                    event.target.closest('tr');

                const unit =
                    option.dataset.unit || '';

                row.querySelector(
                    '.unit-field'
                ).value = unit;
            }

        }
    );

});

</script>

@endsection