@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Risk Register
            </div>

            <h3 class="mb-1">
                {{ $risk->risk_title }}
            </h3>

            <div class="text-muted">

                {{ $project->project_name }}

                · {{ $risk->risk_number }}

            </div>

        </div>


        <div class="d-flex gap-2 flex-wrap">

            <a
                href="{{ route(
                    'admin.projects.risks.index',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Risk Register
            </a>


            <a
                href="{{ route(
                    'admin.projects.risks.edit',
                    [
                        'project' => $project->id,
                        'risk' => $risk->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>


            <form
                method="POST"
                action="{{ route(
                    'admin.projects.risks.destroy',
                    [
                        'project' => $project->id,
                        'risk' => $risk->id,
                    ]
                ) }}"
                onsubmit="return confirm(
                    'Delete this risk?'
                );"
            >

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="btn btn-outline-danger"
                >
                    Delete
                </button>

            </form>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Risk Summary --}}

    @php

        $levelClass =
            match($risk->risk_level) {

                'Critical'
                    => 'bg-danger',

                'High'
                    => 'bg-warning text-dark',

                'Medium'
                    => 'bg-info text-dark',

                default
                    => 'bg-success',

            };


        $statusClass =
            match($risk->status) {

                'Closed'
                    => 'bg-success',

                'Mitigated'
                    => 'bg-info text-dark',

                'Occurred'
                    => 'bg-danger',

                'Monitoring'
                    => 'bg-warning text-dark',

                default
                    => 'bg-secondary',

            };

    @endphp


    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Risk Number
                    </div>

                    <div class="fs-5 fw-semibold">
                        {{ $risk->risk_number }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Risk Score
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $risk->risk_score }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Risk Level
                    </div>

                    <div class="mt-2">

                        <span
                            class="badge {{ $levelClass }} fs-6"
                        >
                            {{ $risk->risk_level }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Status
                    </div>

                    <div class="mt-2">

                        <span
                            class="badge {{ $statusClass }} fs-6"
                        >
                            {{ $risk->status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Risk Details --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Risk Details</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Category
                    </div>

                    <div class="fw-semibold">
                        {{ $risk->risk_category }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Probability
                    </div>

                    <div class="fw-semibold">
                        {{ $risk->probability }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Impact
                    </div>

                    <div class="fw-semibold">
                        {{ $risk->impact }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Risk Owner
                    </div>

                    <div class="fw-semibold">

                        {{
                            $risk->riskOwner
                                ? $risk->riskOwner->name
                                : 'Unassigned'
                        }}

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Identified Date
                    </div>

                    <div class="fw-semibold">

                        {{
                            $risk->identified_date
                                ? $risk->identified_date
                                    ->format('d M Y')
                                : '—'
                        }}

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Target Date
                    </div>

                    <div class="fw-semibold">

                        {{
                            $risk->target_date
                                ? $risk->target_date
                                    ->format('d M Y')
                                : '—'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Description --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Risk Description</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e($risk->risk_description)
            ) !!}

        </div>

    </div>


    {{-- Cause / Consequence --}}

    <div class="row">

        <div class="col-md-6">

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Cause</strong>
                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $risk->cause
                            ?? 'Not defined.'
                        )
                    ) !!}

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Consequence</strong>
                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $risk->consequence
                            ?? 'Not defined.'
                        )
                    ) !!}

                </div>

            </div>

        </div>

    </div>


    {{-- Response --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Risk Response</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Response Strategy
                    </div>

                    <div class="fw-semibold">
                        {{ $risk->response_strategy }}
                    </div>

                </div>

            </div>


            <div class="mb-4">

                <div class="text-muted small mb-2">
                    Mitigation Plan
                </div>

                <div class="border rounded p-3">

                    {!! nl2br(
                        e(
                            $risk->mitigation_plan
                            ?? 'No mitigation plan defined.'
                        )
                    ) !!}

                </div>

            </div>


            <div>

                <div class="text-muted small mb-2">
                    Contingency Plan
                </div>

                <div class="border rounded p-3">

                    {!! nl2br(
                        e(
                            $risk->contingency_plan
                            ?? 'No contingency plan defined.'
                        )
                    ) !!}

                </div>

            </div>

        </div>

    </div>


    {{-- Residual Risk --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Residual Risk</strong>
        </div>

        <div class="card-body">

            @if(
                $risk->residual_probability &&
                $risk->residual_impact
            )

                @php

                    $residualClass =
                        match($risk->residual_risk_level) {

                            'Critical'
                                => 'bg-danger',

                            'High'
                                => 'bg-warning text-dark',

                            'Medium'
                                => 'bg-info text-dark',

                            default
                                => 'bg-success',

                        };

                @endphp


                <div class="row">

                    <div class="col-md-4">

                        <div class="text-muted small">
                            Probability
                        </div>

                        <div class="fw-semibold">
                            {{ $risk->residual_probability }}
                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Impact
                        </div>

                        <div class="fw-semibold">
                            {{ $risk->residual_impact }}
                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Residual Score
                        </div>

                        <div>

                            <strong>
                                {{ $risk->residual_score }}
                            </strong>

                            <span
                                class="badge {{ $residualClass }} ms-2"
                            >
                                {{ $risk->residual_risk_level }}
                            </span>

                        </div>

                    </div>

                </div>

            @else

                <div class="text-muted">
                    Residual risk has not been assessed.
                </div>

            @endif

        </div>

    </div>


    {{-- Status Update --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Risk Status</strong>
        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.risks.status',
                    [
                        'project' => $project->id,
                        'risk' => $risk->id,
                    ]
                ) }}"
            >

                @csrf

                <div class="row align-items-end">

                    <div class="col-md-6">

                        <label class="form-label">
                            Update Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            @foreach([
                                'Open',
                                'Monitoring',
                                'Mitigated',
                                'Closed',
                                'Occurred',
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        $risk->status === $status
                                    )
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Update Status
                        </button>

                    </div>

                </div>

            </form>


            @if($risk->closed_date)

                <div class="text-muted small mt-3">

                    Closed Date:

                    {{
                        $risk->closed_date
                            ->format('d M Y')
                    }}

                </div>

            @endif

        </div>

    </div>


    {{-- Remarks --}}

    @if($risk->remarks)

        <div class="card mb-4">

            <div class="card-header">
                <strong>Remarks</strong>
            </div>

            <div class="card-body">

                {!! nl2br(
                    e($risk->remarks)
                ) !!}

            </div>

        </div>

    @endif

</div>

@endsection