@extends('layouts.app')

@section('title', 'Add Equipment Usage')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Add Equipment Usage
            </h4>

            <div class="text-muted">
                {{ $project->project_number }}
                -
                {{ $project->project_name }}
            </div>

        </div>

        <a
            href="{{ route(
                'admin.projects.construction.equipment.usage.index',
                $project
            ) }}"
            class="btn btn-outline-secondary">

            ← Back to Usage

        </a>

    </div>


    <form
        method="POST"
        action="{{ route(
            'admin.projects.construction.equipment.usage.store',
            $project
        ) }}">

        @csrf


        {{-- Equipment --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <strong>
                    Equipment & Deployment
                </strong>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Deployment <span class="text-danger">*</span>
                        </label>

                        <select
                            name="equipment_deployment_id"
                            id="deployment_id"
                            class="form-select @error('equipment_deployment_id') is-invalid @enderror"
                            required>

                            <option value="">
                                Select Deployment
                            </option>

                            @foreach($deployments as $deployment)

                                <option
                                    value="{{ $deployment->id }}"
                                    data-equipment="{{ $deployment->equipment_id }}"
                                    data-workorder="{{ $deployment->construction_work_order_id }}"
                                    data-operator="{{ $deployment->operator_id }}"
                                    data-meter="{{ $deployment->starting_meter }}"
                                    @selected(
                                        old('equipment_deployment_id')
                                        == $deployment->id
                                    )>

                                    {{ $deployment->deployment_number }}
                                    -
                                    {{ $deployment->equipment?->equipment_code }}
                                    -
                                    {{ $deployment->equipment?->equipment_name }}

                                </option>

                            @endforeach

                        </select>

                        @error('equipment_deployment_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Work Order
                        </label>

                        <select
                            name="construction_work_order_id"
                            id="work_order_id"
                            class="form-select">

                            <option value="">
                                Select Work Order
                            </option>

                            @foreach($workOrders as $workOrder)

                                <option
                                    value="{{ $workOrder->id }}"
                                    @selected(
                                        old('construction_work_order_id')
                                        == $workOrder->id
                                    )>

                                    {{ $workOrder->work_order_number }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Usage Date <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="usage_date"
                            class="form-control"
                            value="{{ old(
                                'usage_date',
                                now()->format('Y-m-d')
                            ) }}"
                            required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Operator
                        </label>

                        <select
                            name="operator_id"
                            id="operator_id"
                            class="form-select">

                            <option value="">
                                Select Operator
                            </option>

                            @foreach($users as $user)

                                <option
                                    value="{{ $user->id }}"
                                    @selected(
                                        old('operator_id')
                                        == $user->id
                                    )>

                                    {{ $user->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- Meter --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <strong>
                    Meter & Hours
                </strong>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Opening Meter
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="opening_meter"
                            id="opening_meter"
                            class="form-control"
                            value="{{ old('opening_meter') }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Closing Meter
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="closing_meter"
                            id="closing_meter"
                            class="form-control"
                            value="{{ old('closing_meter') }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Operating Hours
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="operating_hours"
                            id="operating_hours"
                            class="form-control"
                            value="{{ old('operating_hours', 0) }}">

                        <div class="form-text">
                            Automatically calculated from meters if left zero.
                        </div>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Idle Hours
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="idle_hours"
                            class="form-control"
                            value="{{ old('idle_hours', 0) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Breakdown Hours
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="breakdown_hours"
                            class="form-control"
                            value="{{ old('breakdown_hours', 0) }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- Fuel --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <strong>
                    Fuel Consumption
                </strong>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Fuel Consumed
                        </label>

                        <input
                            type="number"
                            step="0.0001"
                            min="0"
                            name="fuel_consumed"
                            class="form-control"
                            value="{{ old('fuel_consumed') }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Fuel Unit
                        </label>

                        <input
                            type="text"
                            name="fuel_unit"
                            class="form-control"
                            value="{{ old('fuel_unit', 'Litre') }}"
                            maxlength="30">

                    </div>

                </div>

            </div>

        </div>


        {{-- Work Details --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <strong>
                    Work Details
                </strong>

            </div>

            <div class="card-body">

                <div class="mb-3">

                    <label class="form-label">
                        Work Description
                    </label>

                    <textarea
                        name="work_description"
                        class="form-control"
                        rows="4">{{ old('work_description') }}</textarea>

                </div>


                <div>

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea
                        name="remarks"
                        class="form-control"
                        rows="3">{{ old('remarks') }}</textarea>

                </div>

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.equipment.usage.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary">

                Cancel

            </a>

            <button
                type="submit"
                class="btn btn-success">

                Save Usage Log

            </button>

        </div>

    </form>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const deployment =
            document.getElementById(
                'deployment_id'
            );

        const workOrder =
            document.getElementById(
                'work_order_id'
            );

        const operator =
            document.getElementById(
                'operator_id'
            );

        const openingMeter =
            document.getElementById(
                'opening_meter'
            );

        const operatingHours =
            document.getElementById(
                'operating_hours'
            );


        deployment.addEventListener(
            'change',
            function () {

                const option =
                    deployment.options[
                        deployment.selectedIndex
                    ];

                if (!option.value) {
                    return;
                }


                const selectedWorkOrder =
                    option.dataset.workorder;

                const selectedOperator =
                    option.dataset.operator;

                const selectedMeter =
                    option.dataset.meter;


                if (selectedWorkOrder) {

                    workOrder.value =
                        selectedWorkOrder;

                }


                if (selectedOperator) {

                    operator.value =
                        selectedOperator;

                }


                if (
                    selectedMeter !== ''
                    && openingMeter.value === ''
                ) {

                    openingMeter.value =
                        selectedMeter;

                }

            }
        );


        const calculateHours = function () {

            const opening =
                parseFloat(
                    openingMeter.value
                );

            const closing =
                parseFloat(
                    document.getElementById(
                        'closing_meter'
                    ).value
                );


            if (
                !isNaN(opening)
                && !isNaN(closing)
                && closing >= opening
            ) {

                operatingHours.value =
                    (
                        closing - opening
                    ).toFixed(2);

            }

        };


        openingMeter.addEventListener(
            'input',
            calculateHours
        );

        document.getElementById(
            'closing_meter'
        ).addEventListener(
            'input',
            calculateHours
        );

    }
);

</script>

@endsection