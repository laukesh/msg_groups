@extends('layouts.app')

@section('title', 'New Material Requirement')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                New Material Requirement
            </h4>

            <div class="text-muted">
                {{ $project->project_name }}
            </div>

        </div>

        <a href="{{ route('admin.projects.construction.materials.requirements.index', ['project' => $project->id]) }}"
           class="btn btn-secondary">
            ← Back to Requirements
        </a>

    </div>


    <form method="POST"
          action="{{ route('admin.projects.construction.materials.requirements.store', ['project' => $project->id]) }}">

        @csrf


        <div class="row">

            <div class="col-lg-8">

                {{-- Requirement Details --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">
                            Requirement Details
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">


                            {{-- Work Order --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Work Order
                                </label>

                                <select name="construction_work_order_id"
                                        class="form-select @error('construction_work_order_id') is-invalid @enderror">

                                    <option value="">
                                        General Project Requirement
                                    </option>

                                    @foreach($workOrders as $workOrder)

                                        <option value="{{ $workOrder->id }}"
                                            @selected(old('construction_work_order_id') == $workOrder->id)>

                                            {{ $workOrder->work_order_number }}
                                            -
                                            {{ $workOrder->work_order_title }}

                                        </option>

                                    @endforeach

                                </select>

                                @error('construction_work_order_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Material --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Material <span class="text-danger">*</span>
                                </label>

                                <select name="material_id"
                                        id="material_id"
                                        class="form-select @error('material_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select Material
                                    </option>

                                    @foreach($materials as $material)

                                        <option value="{{ $material->id }}"
                                                data-unit="{{ $material->unit }}"
                                            @selected(old('material_id') == $material->id)>

                                            {{ $material->material_code }}
                                            -
                                            {{ $material->material_name }}

                                        </option>

                                    @endforeach

                                </select>

                                @error('material_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Quantity --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Required Quantity
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="number"
                                       step="0.0001"
                                       min="0.0001"
                                       name="required_quantity"
                                       value="{{ old('required_quantity') }}"
                                       class="form-control @error('required_quantity') is-invalid @enderror"
                                       required>

                                @error('required_quantity')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Unit --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Unit
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="unit"
                                       id="unit"
                                       value="{{ old('unit') }}"
                                       class="form-control @error('unit') is-invalid @enderror"
                                       placeholder="e.g. Bags, Kg, Nos"
                                       required>

                                @error('unit')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Required Date --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Required Date
                                </label>

                                <input type="date"
                                       name="required_date"
                                       value="{{ old('required_date') }}"
                                       class="form-control @error('required_date') is-invalid @enderror">

                                @error('required_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Purpose --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Purpose
                                </label>

                                <input type="text"
                                       name="purpose"
                                       value="{{ old('purpose') }}"
                                       class="form-control"
                                       placeholder="e.g. Foundation Work">

                            </div>


                            {{-- Remarks --}}
                            <div class="col-12">

                                <label class="form-label">
                                    Remarks
                                </label>

                                <textarea name="remarks"
                                          rows="4"
                                          class="form-control"
                                          placeholder="Additional information...">{{ old('remarks') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Actions --}}
                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('admin.projects.construction.materials.requirements.index', ['project' => $project->id]) }}"
                       class="btn btn-outline-secondary">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn btn-primary">
                        Save Requirement
                    </button>

                </div>

            </div>


            {{-- Information --}}
            <div class="col-lg-4">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white">

                        <h6 class="mb-0">
                            About Material Requirement
                        </h6>

                    </div>

                    <div class="card-body">

                        <p class="text-muted mb-3">

                            A material requirement defines the material
                            quantity needed for a project activity or
                            work order.

                        </p>

                        <div class="small text-muted">

                            <strong>Example</strong>

                            <div class="mt-2">

                                Cement<br>

                                Required Quantity:
                                <strong>500 Bags</strong><br>

                                Required Date:
                                <strong>15 Sep 2026</strong><br>

                                Purpose:
                                <strong>Foundation Work</strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const materialSelect =
        document.getElementById('material_id');

    const unitInput =
        document.getElementById('unit');

    materialSelect.addEventListener(
        'change',
        function () {

            const selected =
                this.options[this.selectedIndex];

            const unit =
                selected.getAttribute(
                    'data-unit'
                );

            if (unit) {
                unitInput.value = unit;
            }

        }
    );

});
</script>

@endsection