@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                New Material Request
            </h4>

            <p class="text-muted mb-0">
                {{ $project->project_number }}
                <span class="mx-1">•</span>
                {{ $project->project_name }}
            </p>

        </div>

        <a href="{{ route(
            'admin.projects.construction.materials.requests.index',
            $project
        ) }}"
           class="btn btn-secondary">

            ← Back to Requests

        </a>

    </div>


    <form method="POST"
          action="{{ route(
              'admin.projects.construction.materials.requests.store',
              $project
          ) }}">

        @csrf


        {{-- Request Information --}}

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
                            Request Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="request_date"
                               value="{{ old(
                                   'request_date',
                                   date('Y-m-d')
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
                               value="{{ old('required_date') }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

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
                                            'construction_work_order_id'
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
                                  class="form-control">{{ old('remarks') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- Items --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white d-flex justify-content-between align-items-center">

                <h6 class="mb-0 fw-bold">
                    Requested Materials
                </h6>

                <button type="button"
                        class="btn btn-sm btn-primary"
                        id="addItem">

                    + Add Material

                </button>

            </div>


            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered align-middle"
                           id="itemsTable">

                        <thead>

                            <tr>

                                <th width="35%">
                                    Material
                                </th>

                                <th width="20%">
                                    Quantity
                                </th>

                                <th width="15%">
                                    Unit
                                </th>

                                <th>
                                    Remarks
                                </th>

                                <th width="70">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody id="itemsBody">

                            <tr class="item-row">

                                <td>

                                    <select name="items[0][material_id]"
                                            class="form-select material-select"
                                            required>

                                        <option value="">
                                            Select Material
                                        </option>

                                        @foreach($materials as $material)

                                            <option
                                                value="{{ $material->id }}"
                                                data-unit="{{ $material->unit }}">

                                                {{ $material->material_code }}
                                                -
                                                {{ $material->material_name }}

                                            </option>

                                        @endforeach

                                    </select>

                                </td>


                                <td>

                                    <input type="number"
                                           name="items[0][requested_quantity]"
                                           class="form-control"
                                           step="0.0001"
                                           min="0.0001"
                                           required>

                                </td>


                                <td>

                                    <input type="text"
                                           name="items[0][unit]"
                                           class="form-control unit-field"
                                           readonly
                                           required>

                                </td>


                                <td>

                                    <input type="text"
                                           name="items[0][remarks]"
                                           class="form-control">

                                </td>


                                <td class="text-center">

                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger remove-item">

                                        ×

                                    </button>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- Buttons --}}

        <div class="d-flex justify-content-end gap-2">

            <a href="{{ route(
                'admin.projects.construction.materials.requests.index',
                $project
            ) }}"
               class="btn btn-secondary">

                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary">

                Save Material Request

            </button>

        </div>

    </form>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    let itemIndex = 1;

    const itemsBody =
        document.getElementById('itemsBody');

    const addItemButton =
        document.getElementById('addItem');


    function materialOptions() {

        return `
            <option value="">
                Select Material
            </option>

            @foreach($materials as $material)

                <option
                    value="{{ $material->id }}"
                    data-unit="{{ $material->unit }}">

                    {{ $material->material_code }}
                    -
                    {{ $material->material_name }}

                </option>

            @endforeach
        `;
    }


    addItemButton.addEventListener(
        'click',
        function () {

            const row =
                document.createElement('tr');

            row.classList.add('item-row');

            row.innerHTML = `

                <td>

                    <select
                        name="items[${itemIndex}][material_id]"
                        class="form-select material-select"
                        required>

                        ${materialOptions()}

                    </select>

                </td>

                <td>

                    <input
                        type="number"
                        name="items[${itemIndex}][requested_quantity]"
                        class="form-control"
                        step="0.0001"
                        min="0.0001"
                        required>

                </td>

                <td>

                    <input
                        type="text"
                        name="items[${itemIndex}][unit]"
                        class="form-control unit-field"
                        readonly
                        required>

                </td>

                <td>

                    <input
                        type="text"
                        name="items[${itemIndex}][remarks]"
                        class="form-control">

                </td>

                <td class="text-center">

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger remove-item">

                        ×

                    </button>

                </td>

            `;

            itemsBody.appendChild(row);

            itemIndex++;

        }
    );


    document.addEventListener(
        'change',
        function (event) {

            if (
                event.target.classList.contains(
                    'material-select'
                )
            ) {

                const selected =
                    event.target.options[
                        event.target.selectedIndex
                    ];

                const unit =
                    selected.dataset.unit || '';

                const row =
                    event.target.closest('.item-row');

                row.querySelector(
                    '.unit-field'
                ).value = unit;

            }

        }
    );


    document.addEventListener(
        'click',
        function (event) {

            if (
                event.target.classList.contains(
                    'remove-item'
                )
            ) {

                const rows =
                    itemsBody.querySelectorAll(
                        '.item-row'
                    );

                if (rows.length > 1) {

                    event.target
                        .closest('.item-row')
                        .remove();

                }

            }

        }
    );

});

</script>

@endsection