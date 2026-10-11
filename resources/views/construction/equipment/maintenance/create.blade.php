@extends('layouts.app')

@section('title', 'Add Equipment Maintenance')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Add Equipment Maintenance
            </h4>

            <div class="text-muted">
                {{ $project->project_number }}
                -
                {{ $project->project_name }}
            </div>

        </div>

        <a
            href="{{ route(
                'admin.projects.construction.equipment.maintenance.index',
                $project
            ) }}"
            class="btn btn-outline-secondary">

            ← Back to Maintenance

        </a>

    </div>


    <form
        method="POST"
        action="{{ route(
            'admin.projects.construction.equipment.maintenance.store',
            $project
        ) }}">

        @csrf


        {{-- Basic Information --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <strong>
                    Maintenance Information
                </strong>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Equipment <span class="text-danger">*</span>
                        </label>

                        <select
                            name="equipment_id"
                            class="form-select @error('equipment_id') is-invalid @enderror"
                            required>

                            <option value="">
                                Select Equipment
                            </option>

                            @foreach($equipment as $item)

                                <option
                                    value="{{ $item->id }}"
                                    @selected(
                                        old('equipment_id')
                                        == $item->id
                                    )>

                                    {{ $item->equipment_code }}
                                    -
                                    {{ $item->equipment_name }}

                                </option>

                            @endforeach

                        </select>

                        @error('equipment_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Maintenance Type
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="maintenance_type"
                            class="form-select"
                            required>

                            @foreach([
                                'Preventive',
                                'Corrective',
                                'Breakdown',
                                'Inspection',
                                'Servicing'
                            ] as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(
                                        old(
                                            'maintenance_type',
                                            'Preventive'
                                        ) === $type
                                    )>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Scheduled Date
                        </label>

                        <input
                            type="date"
                            name="scheduled_date"
                            class="form-control"
                            value="{{ old('scheduled_date') }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Maintenance Date
                        </label>

                        <input
                            type="date"
                            name="maintenance_date"
                            class="form-control"
                            value="{{ old('maintenance_date') }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Meter Reading
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="meter_reading"
                            class="form-control"
                            value="{{ old('meter_reading') }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Maintenance Vendor
                        </label>

                        <input
                            type="text"
                            name="maintenance_vendor"
                            class="form-control"
                            maxlength="150"
                            value="{{ old('maintenance_vendor') }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- Issue / Work --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <strong>
                    Maintenance Work
                </strong>

            </div>

            <div class="card-body">

                <div class="mb-3">

                    <label class="form-label">
                        Issue Description
                    </label>

                    <textarea
                        name="issue_description"
                        class="form-control"
                        rows="4">{{ old('issue_description') }}</textarea>

                </div>


                <div>

                    <label class="form-label">
                        Work Performed
                    </label>

                    <textarea
                        name="work_performed"
                        class="form-control"
                        rows="4">{{ old('work_performed') }}</textarea>

                </div>

            </div>

        </div>


        {{-- Cost --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <strong>
                    Cost & Next Service
                </strong>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Maintenance Cost
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="cost"
                            class="form-control"
                            value="{{ old('cost', 0) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Next Service Date
                        </label>

                        <input
                            type="date"
                            name="next_service_date"
                            class="form-control"
                            value="{{ old('next_service_date') }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Next Service Meter
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="next_service_meter"
                            class="form-control"
                            value="{{ old('next_service_meter') }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- Remarks --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <strong>
                    Remarks
                </strong>

            </div>

            <div class="card-body">

                <textarea
                    name="remarks"
                    class="form-control"
                    rows="4">{{ old('remarks') }}</textarea>

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.equipment.maintenance.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary">

                Cancel

            </a>

            <button
                type="submit"
                class="btn btn-success">

                Save Maintenance

            </button>

        </div>

    </form>

</div>

@endsection