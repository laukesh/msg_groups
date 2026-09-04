@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Project / Funding Plan / Sources
            </div>

            <h3 class="mb-1">
                Edit Funding Source
            </h3>

            <div class="text-muted">
                {{ $project->project_name }}
                ·
                {{ $fundingPlan->funding_plan_number }}
                ·
                {{ $fundingSource->source_code }}
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.funding-plan.show',
                [
                    'project' => $project->id,
                    'fundingPlan' => $fundingPlan->id,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            ← Back to Funding Plan
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


    <form
        method="POST"
        action="{{ route(
            'admin.projects.funding-plan.sources.update',
            [
                'project' => $project->id,
                'fundingPlan' => $fundingPlan->id,
                'fundingSource' => $fundingSource->id,
            ]
        ) }}"
    >

        @csrf
        @method('PUT')


        {{-- Source Information --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Funding Source Information</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3 mb-3">

                        <label
                            for="source_code"
                            class="form-label"
                        >
                            Source Code
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="source_code"
                            id="source_code"
                            class="form-control @error('source_code') is-invalid @enderror"
                            value="{{ old(
                                'source_code',
                                $fundingSource->source_code
                            ) }}"
                            required
                        >

                        @error('source_code')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-5 mb-3">

                        <label
                            for="source_name"
                            class="form-label"
                        >
                            Source Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="source_name"
                            id="source_name"
                            class="form-control @error('source_name') is-invalid @enderror"
                            value="{{ old(
                                'source_name',
                                $fundingSource->source_name
                            ) }}"
                            required
                        >

                        @error('source_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-4 mb-3">

                        <label
                            for="source_type"
                            class="form-label"
                        >
                            Source Type
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="source_type"
                            id="source_type"
                            class="form-select @error('source_type') is-invalid @enderror"
                            required
                        >

                            @foreach([
                                'Equity',
                                'Debt',
                                'Promoter Contribution',
                                'Investor',
                                'Internal Accrual',
                                'JV Partner',
                                'Government Grant',
                                'Other'
                            ] as $type)

                                <option
                                    value="{{ $type }}"
                                    {{ old(
                                        'source_type',
                                        $fundingSource->source_type
                                    ) === $type
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                        @error('source_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label
                            for="provider_name"
                            class="form-label"
                        >
                            Provider / Institution
                        </label>

                        <input
                            type="text"
                            name="provider_name"
                            id="provider_name"
                            class="form-control"
                            value="{{ old(
                                'provider_name',
                                $fundingSource->provider_name
                            ) }}"
                        >

                    </div>


                    <div class="col-md-3 mb-3">

                        <label
                            for="sequence"
                            class="form-label"
                        >
                            Display Sequence
                        </label>

                        <input
                            type="number"
                            name="sequence"
                            id="sequence"
                            class="form-control"
                            value="{{ old(
                                'sequence',
                                $fundingSource->sequence
                            ) }}"
                            min="0"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- Amounts --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Funding Amount</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label
                            for="planned_amount"
                            class="form-label"
                        >
                            Planned Amount
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                {{ $fundingPlan->currency }}
                            </span>

                            <input
                                type="number"
                                name="planned_amount"
                                id="planned_amount"
                                class="form-control"
                                value="{{ old(
                                    'planned_amount',
                                    $fundingSource->planned_amount
                                ) }}"
                                min="0"
                                step="0.01"
                                required
                            >

                        </div>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label
                            for="committed_amount"
                            class="form-label"
                        >
                            Committed Amount
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                {{ $fundingPlan->currency }}
                            </span>

                            <input
                                type="number"
                                name="committed_amount"
                                id="committed_amount"
                                class="form-control"
                                value="{{ old(
                                    'committed_amount',
                                    $fundingSource->committed_amount
                                ) }}"
                                min="0"
                                step="0.01"
                                required
                            >

                        </div>

                        <div class="form-text">
                            Cannot exceed planned amount.
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Debt Details --}}

        <div
            class="card mb-4"
            id="debtDetails"
        >

            <div class="card-header">
                <strong>Debt Details</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label
                            for="interest_rate"
                            class="form-label"
                        >
                            Interest Rate (%)
                        </label>

                        <input
                            type="number"
                            name="interest_rate"
                            id="interest_rate"
                            class="form-control"
                            value="{{ old(
                                'interest_rate',
                                $fundingSource->interest_rate
                            ) }}"
                            min="0"
                            step="0.0001"
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label
                            for="tenure_months"
                            class="form-label"
                        >
                            Tenure (Months)
                        </label>

                        <input
                            type="number"
                            name="tenure_months"
                            id="tenure_months"
                            class="form-control"
                            value="{{ old(
                                'tenure_months',
                                $fundingSource->tenure_months
                            ) }}"
                            min="1"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- Remarks --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Remarks</strong>
            </div>

            <div class="card-body">

                <textarea
                    name="remarks"
                    id="remarks"
                    rows="4"
                    class="form-control"
                >{{ old(
                    'remarks',
                    $fundingSource->remarks
                ) }}</textarea>

            </div>

        </div>


        {{-- Actions --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.funding-plan.show',
                    [
                        'project' => $project->id,
                        'fundingPlan' => $fundingPlan->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Update Funding Source
            </button>

        </div>

    </form>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const sourceType =
            document.getElementById('source_type');

        const debtDetails =
            document.getElementById('debtDetails');


        function toggleDebtDetails()
        {
            debtDetails.style.display =
                sourceType.value === 'Debt'
                    ? ''
                    : 'none';
        }


        sourceType.addEventListener(
            'change',
            toggleDebtDetails
        );

        toggleDebtDetails();

    }
);

</script>

@endsection