@extends('layouts.app')

@section('title', 'New Material Request')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                New Material Request
            </h4>

            <div class="text-muted">
                {{ $project->project_name }}
            </div>
        </div>

        <a href="{{ route('admin.projects.construction.materials.requests.index', [
            'project' => $project->id
        ]) }}"
           class="btn btn-secondary">
            ← Back to Requests
        </a>

    </div>


    <form method="POST"
          action="{{ route('admin.projects.construction.materials.requests.store', [
              'project' => $project->id
          ]) }}">

        @csrf

        <div class="row">

            <div class="col-lg-9">

                {{-- Request Information --}}
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
                                                old('construction_work_order_id')
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
                                       value="{{ old('request_date', now()->format('Y-m-d')) }}"
                                       class="form-control"
                                       required>

                            </div>


                            <div class="col-md-3">

                                <label class="form-label">
                                    Required Date
                                </label>

                                <input type="date"
                                       name="required_date"
                                       value="{{ old('required_date') }}"
                                       class="form-control">

                            </div>


                            <div class="col-12">

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

                                        <th style="width: 28%;">
                                            Material Requirement
                                        </th>

                                        <th style="width: 22%;">
                                            Material
                                        </th>

                                        <th style="width: 15%;">
                                            Quantity
                                        </th>

                                        <th style="width: 12%;">
                                            Unit
                                        </th>

                                        <th>
                                            Remarks
                                        </th>

                                        <th style="width: 50px;">
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <tr class="item-row">

                                        <td>

                                            <select name="items[0][material_requirement_id]"
                                                    class="form-select requirement-select">

                                                <option value="">
                                                    No Requirement
                                                </option>

                                                @foreach($requirements as $requirement)

                                                    <option value="{{ $requirement->id }}"
                                                            data-material="{{ $requirement->material_id }}"
                                                            data-unit="{{ $requirement->unit }}"
                                                            data-required="{{ $requirement->required_quantity }}">

                                                        {{ $requirement->material?->material_code }}
                                                        -
                                                        {{ $requirement->material?->material_name }}

                                                        @if($requirement->workOrder)
                                                            | {{ $requirement->workOrder->work_order_number }}
                                                        @endif

                                                        | Required:
                                                        {{ number_format((float) $requirement->required_quantity, 2) }}
                                                        {{ $requirement->unit }}

                                                    </option>

                                                @endforeach

                                            </select>

                                        </td>


                                        <td>

                                            <select name="items[0][material_id]"
                                                    class="form-select material-select"
                                                    required>

                                                <option value="">
                                                    Select Material
                                                </option>

                                                @foreach($materials as $material)

                                                    <option value="{{ $material->id }}"
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
                                                   class="form-control quantity-input"
                                                   step="0.0001"
                                                   min="0.0001"
                                                   required>

                                        </td>


                                        <td>

                                            <input type="text"
                                                   name="items[0][unit]"
                                                   class="form-control unit-input"
                                                   required>

                                        </td>


                                        <td>

                                            <input type="text"
                                                   name="items[0][remarks]"
                                                   class="form-control">

                                        </td>


                                        <td>

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


                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a href="{{ route('admin.projects.construction.materials.requests.index', [
                        'project' => $project->id
                    ]) }}"
                       class="btn btn-outline-secondary">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn btn-primary">
                        Save Material Request
                    </button>

                </div>

            </div>


            {{-- Information --}}
            <div class="col-lg-3">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white">
                        <h6 class="mb-0">
                            Requirement Integration
                        </h6>
                    </div>

                    <div class="card-body">

                        <p class="text-muted small">

                            Select a Material Requirement when
                            the request is being raised against
                            an existing material requirement.

                        </p>

                        <div class="small">

                            <strong>Example</strong>

                            <div class="mt-2 text-muted">

                                Requirement:
                                <strong>500 Bags</strong><br>

                                Already Requested:
                                <strong>300 Bags</strong><br>

                                Remaining:
                                <strong>200 Bags</strong>

                            </div>

                        </div>

                        <hr>

                        <div class="small text-muted">

                            A single requirement can be fulfilled
                            through multiple material requests.

                        </div>

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

    let rowIndex = 1;


    function updateRow(row) {

        const requirementSelect =
            row.querySelector('.requirement-select');

        const materialSelect =
            row.querySelector('.material-select');

        const unitInput =
            row.querySelector('.unit-input');

        const selectedRequirement =
            requirementSelect.options[
                requirementSelect.selectedIndex
            ];

        if (
            selectedRequirement &&
            selectedRequirement.value
        ) {

            const materialId =
                selectedRequirement.dataset.material;

            const unit =
                selectedRequirement.dataset.unit;

            materialSelect.value =
                materialId;

            unitInput.value =
                unit;
        }
    }


    tableBody.addEventListener(
        'change',
        function (event) {

            if (
                event.target.classList.contains(
                    'requirement-select'
                )
            ) {

                updateRow(
                    event.target.closest(
                        '.item-row'
                    )
                );
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

                const selected =
                    event.target.options[
                        event.target.selectedIndex
                    ];

                if (selected) {

                    row.querySelector(
                        '.unit-input'
                    ).value =
                        selected.dataset.unit || '';
                }
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