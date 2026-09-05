@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Create Procurement Plan
            </h4>

            <div class="text-muted">
                Create a new procurement plan.
            </div>

        </div>

        <a
            href="{{ route('admin.procurement.plans.index') }}"
            class="btn btn-outline-secondary"
        >
            Back
        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'admin.procurement.plans.store'
        ) }}"
    >

        @csrf


        <div class="card">

            <div class="card-header">
                <strong>
                    Procurement Plan Details
                </strong>
            </div>


            <div class="card-body">

                <div class="row g-3">

                    {{-- Project --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Project <span class="text-danger">*</span>
                        </label>

                        <select
                            name="project_id"
                            id="project_id"
                            class="form-select @error('project_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Project
                            </option>

                            @foreach($projects as $project)

                                <option
                                    value="{{ $project->id }}"
                                    @selected(
                                        old('project_id')
                                        == $project->id
                                    )
                                >
                                    {{ $project->project_name }}

                                    @if($project->project_code)
                                        ({{ $project->project_code }})
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('project_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Procurement Strategy --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Procurement Strategy
                        </label>

                        <select
                            name="procurement_strategy_id"
                            id="procurement_strategy_id"
                            class="form-select"
                            disabled
                        >

                            <option value="">
                                Select Project first
                            </option>

                        </select>

                        <div class="form-text">
                            Only Procurement Strategies belonging to the
                            selected Project will be displayed.
                        </div>

                        @error('procurement_strategy_id')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Plan Number --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Plan Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="Will be generated automatically"
                            readonly
                        >

                        <div class="form-text">
                            Plan number will be generated automatically after saving.
                        </div>

                    </div>


                    {{-- Year --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Procurement Year
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            name="procurement_year"
                            class="form-control @error('procurement_year') is-invalid @enderror"
                            value="{{ old(
                                'procurement_year',
                                now()->year
                            ) }}"
                            min="2000"
                            max="2100"
                            required
                        >

                        @error('procurement_year')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Currency --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Currency
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="currency"
                            class="form-control @error('currency') is-invalid @enderror"
                            value="{{ old(
                                'currency',
                                'USD'
                            ) }}"
                            maxlength="10"
                            required
                        >

                        @error('currency')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Plan Title --}}

                    <div class="col-12">

                        <label class="form-label">
                            Plan Title
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="plan_title"
                            class="form-control @error('plan_title') is-invalid @enderror"
                            value="{{ old('plan_title') }}"
                            maxlength="255"
                            required
                        >

                        @error('plan_title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Objective --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Procurement Objective
                        </label>

                        <textarea
                            name="procurement_objective"
                            rows="5"
                            class="form-control"
                        >{{ old('procurement_objective') }}</textarea>

                    </div>


                    {{-- Description --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="5"
                            class="form-control"
                        >{{ old('description') }}</textarea>

                    </div>


                    {{-- Start Date --}}

                    <div class="col-md-3">

                        <label class="form-label">
                            Planned Start Date
                        </label>

                        <input
                            type="date"
                            name="planned_start_date"
                            class="form-control"
                            value="{{ old(
                                'planned_start_date'
                            ) }}"
                        >

                    </div>


                    {{-- Completion Date --}}

                    <div class="col-md-3">

                        <label class="form-label">
                            Planned Completion Date
                        </label>

                        <input
                            type="date"
                            name="planned_completion_date"
                            class="form-control"
                            value="{{ old(
                                'planned_completion_date'
                            ) }}"
                        >

                    </div>


                    {{-- Estimated Value --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Total Estimated Value
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="total_estimated_value"
                            class="form-control"
                            value="{{ old(
                                'total_estimated_value'
                            ) }}"
                        >

                    </div>


                    {{-- Remarks --}}

                    <div class="col-12">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="4"
                            class="form-control"
                        >{{ old('remarks') }}</textarea>

                    </div>

                </div>

            </div>


            <div class="card-footer d-flex justify-content-end gap-2">

                <a
                    href="{{ route(
                        'admin.procurement.plans.index'
                    ) }}"
                    class="btn btn-outline-secondary"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Create Procurement Plan
                </button>

            </div>

        </div>

    </form>

</div>

@endsection

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const projectSelect =
            document.getElementById(
                'project_id'
            );

        const strategySelect =
            document.getElementById(
                'procurement_strategy_id'
            );


        if (
            !projectSelect ||
            !strategySelect
        ) {
            return;
        }


        projectSelect.addEventListener(
            'change',
            function () {

                const projectId =
                    this.value;


                strategySelect.innerHTML =
                    '<option value="">Loading...</option>';

                strategySelect.disabled =
                    true;


                if (!projectId) {

                    strategySelect.innerHTML =
                        '<option value="">Select Project first</option>';

                    return;
                }


                const url =
                    "{{ route(
                        'admin.procurement.projects.strategies',
                        ['project' => '__PROJECT__']
                    ) }}"
                    .replace(
                        '__PROJECT__',
                        projectId
                    );


                fetch(url, {
                    headers: {
                        'Accept':
                            'application/json'
                    }
                })
                .then(function (response) {

                    if (!response.ok) {
                        throw new Error(
                            'Unable to load Procurement Strategies.'
                        );
                    }

                    return response.json();

                })
                .then(function (strategies) {

                    strategySelect.innerHTML =
                        '<option value="">Select Procurement Strategy</option>';


                    if (
                        !strategies.length
                    ) {

                        strategySelect.innerHTML =
                            '<option value="">No Procurement Strategy found</option>';

                        return;
                    }


                    strategies.forEach(
                        function (strategy) {

                            const option =
                                document.createElement(
                                    'option'
                                );

                            option.value =
                                strategy.id;

                            option.textContent =
                                strategy.name;

                            strategySelect.appendChild(
                                option
                            );

                        }
                    );


                    strategySelect.disabled =
                        false;

                })
                .catch(function (error) {

                    console.error(error);

                    strategySelect.innerHTML =
                        '<option value="">Unable to load strategies</option>';

                });

            }
        );

    }
);

</script>