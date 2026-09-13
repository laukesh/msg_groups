@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Project / Funding Plan / Source / Commitment
            </div>

            <h3 class="mb-1">
                Edit Funding Commitment
            </h3>

            <div class="text-muted">
                {{ $project->project_name }}
                · {{ $fundingPlan->funding_plan_number }}
                · {{ $fundingSource->source_name }}
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


    {{-- Source Summary --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Funding Source</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Source
                    </div>

                    <div class="fw-semibold">
                        {{ $fundingSource->source_name }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Planned
                    </div>

                    <div class="fw-semibold">
                        {{ $fundingPlan->currency }}
                        {{ number_format(
                            $fundingSource->planned_amount,
                            2
                        ) }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Current Committed
                    </div>

                    <div class="fw-semibold">
                        {{ $fundingPlan->currency }}
                        {{ number_format(
                            $fundingSource->committed_amount,
                            2
                        ) }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Commitment
                    </div>

                    <div class="fw-semibold">
                        {{ $fundingCommitment->commitment_number }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    <form
        method="POST"
        action="{{ route(
            'admin.projects.funding-plan.commitments.update',
            [
                'project' => $project->id,
                'fundingPlan' => $fundingPlan->id,
                'fundingSource' => $fundingSource->id,
                'fundingCommitment' => $fundingCommitment->id,
            ]
        ) }}"
    >

        @csrf
        @method('PUT')


        {{-- Commitment Information --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Commitment Information</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label
                            for="commitment_number"
                            class="form-label"
                        >
                            Commitment Number
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="commitment_number"
                            id="commitment_number"
                            class="form-control @error('commitment_number') is-invalid @enderror"
                            value="{{ old(
                                'commitment_number',
                                $fundingCommitment->commitment_number
                            ) }}"
                            required
                        >

                        @error('commitment_number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-4 mb-3">

                        <label
                            for="commitment_date"
                            class="form-label"
                        >
                            Commitment Date
                        </label>

                        <input
                            type="date"
                            name="commitment_date"
                            id="commitment_date"
                            class="form-control"
                            value="{{ old(
                                'commitment_date',
                                optional(
                                    $fundingCommitment
                                        ->commitment_date
                                )->format('Y-m-d')
                            ) }}"
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
                                'Submitted',
                                'Approved',
                                'Rejected',
                                'Cancelled'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ old(
                                        'status',
                                        $fundingCommitment->status
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
                                $fundingCommitment->provider_name
                            ) }}"
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label
                            for="reference_number"
                            class="form-label"
                        >
                            Reference Number
                        </label>

                        <input
                            type="text"
                            name="reference_number"
                            id="reference_number"
                            class="form-control"
                            value="{{ old(
                                'reference_number',
                                $fundingCommitment->reference_number
                            ) }}"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- Amounts --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Commitment Amount</strong>
            </div>

            <div class="card-body">

                @php

                    $otherCommitted =
                        $fundingSource
                            ->commitments()
                            ->where(
                                'id',
                                '!=',
                                $fundingCommitment->id
                            )
                            ->whereNotIn(
                                'status',
                                [
                                    'Rejected',
                                    'Cancelled'
                                ]
                            )
                            ->sum('committed_amount');

                    $remainingAmount =
                        max(
                            (float)
                            $fundingSource
                                ->planned_amount
                            -
                            (float)
                            $otherCommitted,
                            0
                        );

                @endphp


                <div class="alert alert-info">

                    Maximum available for this commitment:

                    <strong>
                        {{ $fundingPlan->currency }}
                        {{ number_format(
                            $remainingAmount,
                            2
                        ) }}
                    </strong>

                </div>


                <div class="row">

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
                                    $fundingCommitment
                                        ->committed_amount
                                ) }}"
                                min="0"
                                max="{{ $remainingAmount }}"
                                step="0.01"
                                required
                            >

                        </div>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label
                            for="approved_amount"
                            class="form-label"
                        >
                            Approved Amount
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                {{ $fundingPlan->currency }}
                            </span>

                            <input
                                type="number"
                                name="approved_amount"
                                id="approved_amount"
                                class="form-control"
                                value="{{ old(
                                    'approved_amount',
                                    $fundingCommitment
                                        ->approved_amount
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
                    $fundingCommitment->remarks
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
                Update Commitment
            </button>

        </div>

    </form>

</div>

@endsection