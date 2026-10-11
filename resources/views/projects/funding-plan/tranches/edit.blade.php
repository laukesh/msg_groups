@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Project / Funding Plan / Source / Commitment / Tranche
            </div>

            <h3 class="mb-1">
                Edit Funding Tranche
            </h3>

            <div class="text-muted">
                {{ $project->project_name }}
                · {{ $fundingPlan->funding_plan_number }}
                · {{ $fundingSource->source_name }}
                · {{ $fundingCommitment->commitment_number }}
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


    @php

        $otherPlanned =
            $fundingCommitment
                ->tranches()
                ->where(
                    'id',
                    '!=',
                    $fundingTranche->id
                )
                ->whereNotIn(
                    'status',
                    ['Cancelled']
                )
                ->sum('planned_amount');

        $remainingAmount =
            max(
                (float) $fundingCommitment->committed_amount
                -
                (float) $otherPlanned,
                0
            );

    @endphp


    {{-- ========================================================= --}}
    {{-- Commitment Summary --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Funding Commitment</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Commitment
                    </div>

                    <div class="fw-semibold">
                        {{ $fundingCommitment->commitment_number }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Committed Amount
                    </div>

                    <div class="fw-semibold">

                        {{ $fundingPlan->currency }}

                        {{
                            number_format(
                                $fundingCommitment->committed_amount,
                                2
                            )
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Current Tranche
                    </div>

                    <div class="fw-semibold">

                        {{ $fundingPlan->currency }}

                        {{
                            number_format(
                                $fundingTranche->planned_amount,
                                2
                            )
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Available
                    </div>

                    <div class="fw-semibold text-success">

                        {{ $fundingPlan->currency }}

                        {{
                            number_format(
                                $remainingAmount,
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    <form
        method="POST"
        action="{{ route(
            'admin.projects.funding-plan.tranches.update',
            [
                'project' => $project->id,
                'fundingPlan' => $fundingPlan->id,
                'fundingSource' => $fundingSource->id,
                'fundingCommitment' => $fundingCommitment->id,
                'fundingTranche' => $fundingTranche->id,
            ]
        ) }}"
    >

        @csrf
        @method('PUT')


        {{-- ===================================================== --}}
        {{-- Tranche Information --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Tranche Information</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label
                            for="tranche_number"
                            class="form-label"
                        >
                            Tranche Number
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            name="tranche_number"
                            id="tranche_number"
                            class="form-control"
                            value="{{ old(
                                'tranche_number',
                                $fundingTranche->tranche_number
                            ) }}"
                            min="1"
                            required
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label
                            for="status"
                            class="form-label"
                        >
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Planned',
                                'Expected',
                                'Received',
                                'Delayed',
                                'Cancelled'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ old(
                                        'status',
                                        $fundingTranche->status
                                    ) === $status
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Planned Funding --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Planned Funding</strong>
            </div>

            <div class="card-body">

                <div class="alert alert-info">

                    Maximum available for this tranche:

                    <strong>

                        {{ $fundingPlan->currency }}

                        {{
                            number_format(
                                $remainingAmount,
                                2
                            )
                        }}

                    </strong>

                </div>


                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label
                            for="planned_date"
                            class="form-label"
                        >
                            Planned Date
                        </label>

                        <input
                            type="date"
                            name="planned_date"
                            id="planned_date"
                            class="form-control"
                            value="{{ old(
                                'planned_date',
                                optional(
                                    $fundingTranche->planned_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                    </div>


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
                                    $fundingTranche->planned_amount
                                ) }}"
                                min="0"
                                max="{{ $remainingAmount }}"
                                step="0.01"
                                required
                            >

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Expected / Actual --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Expected / Actual Receipt</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label
                            for="expected_date"
                            class="form-label"
                        >
                            Expected Date
                        </label>

                        <input
                            type="date"
                            name="expected_date"
                            id="expected_date"
                            class="form-control"
                            value="{{ old(
                                'expected_date',
                                optional(
                                    $fundingTranche->expected_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label
                            for="actual_date"
                            class="form-label"
                        >
                            Actual Receipt Date
                        </label>

                        <input
                            type="date"
                            name="actual_date"
                            id="actual_date"
                            class="form-control"
                            value="{{ old(
                                'actual_date',
                                optional(
                                    $fundingTranche->actual_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label
                            for="actual_amount"
                            class="form-label"
                        >
                            Actual Amount Received
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                {{ $fundingPlan->currency }}
                            </span>

                            <input
                                type="number"
                                name="actual_amount"
                                id="actual_amount"
                                class="form-control"
                                value="{{ old(
                                    'actual_amount',
                                    $fundingTranche->actual_amount
                                ) }}"
                                min="0"
                                step="0.01"
                                required
                            >

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Remarks --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Remarks</strong>
            </div>

            <div class="card-body">

                <textarea
                    name="remarks"
                    rows="4"
                    class="form-control"
                >{{ old(
                    'remarks',
                    $fundingTranche->remarks
                ) }}</textarea>

            </div>

        </div>


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
                Update Funding Tranche
            </button>

        </div>

    </form>

</div>

@endsection