@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Edit Procurement Plan
            </h4>

            <div class="text-muted">
                {{ $procurementPlan->plan_number }}
            </div>

        </div>

        <a
            href="{{ route(
                'admin.procurement.plans.show',
                $procurementPlan
            ) }}"
            class="btn btn-outline-secondary"
        >
            Back
        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

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
            'admin.procurement.plans.update',
            $procurementPlan
        ) }}"
    >

        @csrf

        @method('PUT')


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
                            Project
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="project_id"
                            class="form-select"
                            required
                        >

                            @foreach($projects as $project)

                                <option
                                    value="{{ $project->id }}"
                                    @selected(
                                        old(
                                            'project_id',
                                            $procurementPlan->project_id
                                        ) == $project->id
                                    )
                                >

                                    {{ $project->project_name }}

                                    @if($project->project_code)
                                        ({{ $project->project_code }})
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Strategy --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Procurement Strategy
                        </label>

                        <select
                            name="procurement_strategy_id"
                            class="form-select"
                        >

                            <option value="">
                                Select Procurement Strategy
                            </option>

                            @foreach(
                                $procurementStrategies
                                as $strategy
                            )

                                <option
                                    value="{{ $strategy->id }}"
                                    @selected(
                                        old(
                                            'procurement_strategy_id',
                                            $procurementPlan->procurement_strategy_id
                                        ) == $strategy->id
                                    )
                                >

                                    {{
                                        $strategy->title
                                        ?? $strategy->strategy_title
                                        ?? 'Strategy #' . $strategy->id
                                    }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Plan Number --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Plan Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $procurementPlan->plan_number }}"
                            readonly
                        >

                        <div class="form-text">
                            Plan number is system generated and cannot be changed.
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
                            class="form-control"
                            value="{{ old(
                                'procurement_year',
                                $procurementPlan->procurement_year
                            ) }}"
                            min="2000"
                            max="2100"
                            required
                        >

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
                            class="form-control"
                            value="{{ old(
                                'currency',
                                $procurementPlan->currency
                            ) }}"
                            maxlength="10"
                            required
                        >

                    </div>


                    {{-- Title --}}

                    <div class="col-12">

                        <label class="form-label">
                            Plan Title
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="plan_title"
                            class="form-control"
                            value="{{ old(
                                'plan_title',
                                $procurementPlan->plan_title
                            ) }}"
                            required
                        >

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
                        >{{ old(
                            'procurement_objective',
                            $procurementPlan->procurement_objective
                        ) }}</textarea>

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
                        >{{ old(
                            'description',
                            $procurementPlan->description
                        ) }}</textarea>

                    </div>


                    {{-- Dates --}}

                    <div class="col-md-3">

                        <label class="form-label">
                            Planned Start Date
                        </label>

                        <input
                            type="date"
                            name="planned_start_date"
                            class="form-control"
                            value="{{ old(
                                'planned_start_date',
                                optional(
                                    $procurementPlan->planned_start_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Planned Completion Date
                        </label>

                        <input
                            type="date"
                            name="planned_completion_date"
                            class="form-control"
                            value="{{ old(
                                'planned_completion_date',
                                optional(
                                    $procurementPlan->planned_completion_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                    </div>


                    {{-- Value --}}

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
                                'total_estimated_value',
                                $procurementPlan->total_estimated_value
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
                        >{{ old(
                            'remarks',
                            $procurementPlan->remarks
                        ) }}</textarea>

                    </div>

                </div>

            </div>


            <div class="card-footer d-flex justify-content-end gap-2">

                <a
                    href="{{ route(
                        'admin.procurement.plans.show',
                        $procurementPlan
                    ) }}"
                    class="btn btn-outline-secondary"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Update Procurement Plan
                </button>

            </div>

        </div>

    </form>

</div>

@endsection