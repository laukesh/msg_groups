@extends('layouts.app')

@section('title', 'Edit Material Request')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Edit Material Request
            </h4>

            <div class="text-muted">
                {{ $project->project_name }}
            </div>

        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.projects.construction.materials.requests.show', [
                'project' => $project->id,
                'materialRequest' => $materialRequest->id,
            ]) }}"
               class="btn btn-secondary">
                ← Back to Request
            </a>

            <a href="{{ route('admin.projects.construction.materials.requests.index', [
                'project' => $project->id
            ]) }}"
               class="btn btn-outline-secondary">
                Requests
            </a>

        </div>

    </div>


    <form method="POST"
          action="{{ route('admin.projects.construction.materials.requests.update', [
              'project' => $project->id,
              'materialRequest' => $materialRequest->id,
          ]) }}">

        @csrf
        @method('PUT')


        <div class="row">

            <div class="col-lg-9">

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">
                            Request Information
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label">
                                    Work Order
                                </label>

                                <select name="construction_work_order_id"
                                        class="form-select">

                                    <option value="">
                                        General Project
                                    </option>

                                    @foreach($workOrders as $workOrder)

                                        <option value="{{ $workOrder->id }}"
                                            @selected(
                                                old(
                                                    'construction_work_order_id',
                                                    $materialRequest->construction_work_order_id
                                                )
                                                == $workOrder->id
                                            )>

                                            {{ $workOrder->work_order_number }}
                                            -
                                            {{ $workOrder->work_order_title }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            <div class="col-md-3">

                                <label class="form-label">
                                    Request Date
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="date"
                                       name="request_date"
                                       value="{{ old(
                                           'request_date',
                                           optional($materialRequest->request_date)->format('Y-m-d')
                                       ) }}"
                                       class="form-control"
                                       required>

                            </div>


                            <div class="col-md-3">

                                <label class="form-label">
                                    Required Date
                                </label>

                                <input type="date"
                                       name="required_date"
                                       value="{{ old(
                                           'required_date',
                                           optional($materialRequest->required_date)->format('Y-m-d')
                                       ) }}"
                                       class="form-control">

                            </div>


                            <div class="col-12">

                                <label class="form-label">
                                    Remarks
                                </label>

                                <textarea name="remarks"
                                          rows="3"
                                          class="form-control">{{ old('remarks', $materialRequest->remarks) }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Items --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">
                            Material Items
                        </h5>

                        <button type="button"
                                class="btn btn-sm btn-primary"
                                id="addItem">
                            + Add Item
                        </button>

                    </div>


                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table align-middle"
                                   id="itemsTable">

                                <thead class="table-light">

                                    <tr>

                                        <th>
                                            Material Requirement
                                        </th>

                                        <th>
                                            Material
                                        </th>

                                        <th>
                                            Quantity
                                        </th>

                                        <th>
                                            Unit
                                        </th>

                                        <th>
                                            Remarks
                                        </th>

                                        <th>
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                @foreach($materialRequest->items as $index => $item)

                                    <tr class="item-row">

                                        <td>

                                            <select name="items[{{ $index }}][material_requirement_id]"
                                                    class="form-select requirement-select">

                                                <option value="">
                                                    No Requirement
                                                </option>

                                                @foreach($requirements as $requirement)

                                                    <option value="{{ $requirement->id }}"
                                                            data-material="{{ $requirement->material_id }}"
                                                            data-unit="{{ $requirement->unit }}"
                                                        @selected(
                                                            old(
                                                                "items.$index.material_requirement_id",
                                                                $item->material_requirement_id
                                                            )
                                                            == $requirement->id
                                                        )>

                                                        {{ $requirement->material?->material_code }}
                                                        -
                                                        {{ $requirement->material?->material_name }}

                                                        @if($requirement->workOrder)
                                                            | {{ $requirement->workOrder->work_order_number }}
                                                        @endif

                                                    </option>

                                                @endforeach

                                            </select>

                                        </td>


                                        <td>

                                            <select name="items[{{ $index }}][material_id]"
                                                    class="form-select material-select"
                                                    required>

                                                <option value="">
                                                    Select Material
                                                </option>

                                                @foreach($materials as $material)

                                                    <option value="{{ $material->id }}"
                                                            data-unit="{{ $material->unit }}"
                                                        @selected(
                                                            old(
                                                                "items.$index.material_id",
                                                                $item->material_id
                                                            )
                                                            == $material->id
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
                                                   class="form-control quantity-input"
                                                   step="0.0001"
                                                   min="0.0001"
                                                   required>

                                        </td>


                                        <td>

                                            <input type="text"
                                                   name="items[{{ $index }}][unit]"
                                                   value="{{ old(
                                                       "items.$index.unit",
                                                       $item->unit
                                                   ) }}"
                                                   class="form-control unit-input"
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


                                        <td>

                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger remove-item">
                                                ×
                                            </button>

                                        </td>

                                    </tr>

                                @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>


                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a href="{{ route('admin.projects.construction.materials.requests.show', [
                        'project' => $project->id,
                        'materialRequest' => $materialRequest->id,
                    ]) }}"
                       class="btn btn-outline-secondary">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn btn-primary">
                        Update Material Request
                    </button>

                </div>

            </div>


            <div class="col-lg-3">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white">

                        <h6 class="mb-0">
                            Current Status
                        </h6>

                    </div>

                    <div class="card-body">

                        <span class="badge bg-primary fs-6">
                            {{ $materialRequest->status }}
                        </span>

                        <p class="text-muted small mt-3 mb-0">

                            Requests can be edited while in
                            Draft or Changes Requested status.

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const tableBody =
        document.querySelector('#itemsTable tbody');

    const addButton =
        document.getElementById('addItem');

    let rowIndex =
        tableBody.querySelectorAll('.item-row').length;


    tableBody.addEventListener(
        'change',
        function (event) {

            if (
                event.target.classList.contains(
                    'requirement-select'
                )
            ) {

                const row =
                    event.target.closest(
                        '.item-row'
                    );

                const option =
                    event.target.options[
                        event.target.selectedIndex
                    ];

                if (
                    option &&
                    option.value
                ) {

                    row.querySelector(
                        '.material-select'
                    ).value =
                        option.dataset.material || '';

                    row.querySelector(
                        '.unit-input'
                    ).value =
                        option.dataset.unit || '';
                }
            }

            if (
                event.target.classList.contains(
                    'material-select'
                )
            ) {

                const row =
                    event.target.closest(
                        '.item-row'
                    );

                const option =
                    event.target.options[
                        event.target.selectedIndex
                    ];

                row.querySelector(
                    '.unit-input'
                ).value =
                    option?.dataset.unit || '';
            }

        }
    );


    tableBody.addEventListener(
        'click',
        function (event) {

            if (
                event.target.classList.contains(
                    'remove-item'
                )
            ) {

                const rows =
                    tableBody.querySelectorAll(
                        '.item-row'
                    );

                if (rows.length > 1) {

                    event.target
                        .closest('.item-row')
                        .remove();

                } else {

                    alert(
                        'At least one material item is required.'
                    );
                }
            }

        }
    );


    addButton.addEventListener(
        'click',
        function () {

            const firstRow =
                tableBody.querySelector(
                    '.item-row'
                );

            const newRow =
                firstRow.cloneNode(true);

            newRow
                .querySelectorAll('input')
                .forEach(function (input) {
                    input.value = '';
                });

            newRow
                .querySelectorAll('select')
                .forEach(function (select) {
                    select.selectedIndex = 0;
                });

            newRow
                .querySelectorAll(
                    'input, select'
                )
                .forEach(function (element) {

                    const name =
                        element.getAttribute(
                            'name'
                        );

                    if (name) {

                        element.setAttribute(
                            'name',
                            name.replace(
                                /\[\d+\]/,
                                '[' + rowIndex + ']'
                            )
                        );
                    }

                });

            tableBody.appendChild(
                newRow
            );

            rowIndex++;

        }
    );

});

</script>

@endsection