@extends('layouts.app')

@section('title', 'New Equipment Deployment')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                New Equipment Deployment
            </h4>

            <p class="text-muted mb-0">

                {{ $project->project_number }}

                <span class="mx-1">•</span>

                {{ $project->project_name }}

            </p>

        </div>

        <a
            href="{{ route(
                'admin.projects.construction.equipment.deployments.index',
                $project
            ) }}"
            class="btn btn-light border"
        >
            ← Back to Deployments
        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'admin.projects.construction.equipment.deployments.store',
            $project
        ) }}"
    >

        @csrf


        {{-- Equipment --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h6 class="mb-0 fw-bold">
                    Deployment Information
                </h6>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Equipment
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="equipment_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Equipment
                            </option>

                            @foreach($equipment as $item)

                                <option
                                    value="{{ $item->id }}"
                                    @selected(
                                        old('equipment_id') == $item->id
                                    )
                                >

                                    {{ $item->equipment_code }}
                                    —
                                    {{ $item->equipment_name }}

                                </option>

                            @endforeach

                        </select>

                        <small class="text-muted">
                            Only currently available equipment is shown.
                        </small>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Work Order
                        </label>

                        <select
                            name="construction_work_order_id"
                            class="form-select"
                        >

                            <option value="">
                                No specific Work Order
                            </option>

                            @foreach($workOrders as $workOrder)

                                <option
                                    value="{{ $workOrder->id }}"
                                    @selected(
                                        old(
                                            'construction_work_order_id'
                                        ) == $workOrder->id
                                    )
                                >

                                    {{
                                        $workOrder->work_order_number
                                        ?? $workOrder->work_order_no
                                        ?? $workOrder->order_number
                                        ?? '#'.$workOrder->id
                                    }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Deployment Date
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="deployment_date"
                            value="{{ old(
                                'deployment_date',
                                now()->format('Y-m-d')
                            ) }}"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Operator
                        </label>

                        <select
                            name="operator_id"
                            class="form-select"
                        >

                            <option value="">
                                Select Operator
                            </option>

                            @foreach($users as $user)

                                <option
                                    value="{{ $user->id }}"
                                    @selected(
                                        old('operator_id') == $user->id
                                    )
                                >
                                    {{ $user->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Starting Meter
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="starting_meter"
                            value="{{ old('starting_meter') }}"
                            class="form-control"
                            placeholder="e.g. 1250.50"
                        >

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            Deployment Location
                        </label>

                        <input
                            type="text"
                            name="location"
                            value="{{ old('location') }}"
                            class="form-control"
                            placeholder="e.g. Block A, Basement, Tower 1"
                        >

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="4"
                            class="form-control"
                            placeholder="Additional deployment remarks..."
                        >{{ old('remarks') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2 mb-4">

            <a
                href="{{ route(
                    'admin.projects.construction.equipment.deployments.index',
                    $project
                ) }}"
                class="btn btn-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Deployment
            </button>

        </div>

    </form>

</div>

@endsection