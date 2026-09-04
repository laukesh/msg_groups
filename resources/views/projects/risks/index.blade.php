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
                Risk Register
            </h3>

            <div class="text-muted">
                {{ $project->project_name }}
                · {{ $project->project_number }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.show',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Project
            </a>

            <a
                href="{{ route(
                    'admin.projects.risks.create',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-primary"
            >
                + Add Risk
            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Risks
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $summary['total'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100 border-danger">

                <div class="card-body">

                    <div class="text-muted small">
                        Critical
                    </div>

                    <div class="fs-3 fw-semibold text-danger">
                        {{ $summary['critical'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100 border-warning">

                <div class="card-body">

                    <div class="text-muted small">
                        High
                    </div>

                    <div class="fs-3 fw-semibold text-warning">
                        {{ $summary['high'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Open / Monitoring
                    </div>

                    <div class="fs-3 fw-semibold">

                        {{
                            $summary['open']
                            +
                            $summary['monitoring']
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Status Summary --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Risk Status Summary</strong>
        </div>

        <div class="card-body">

            <div class="row text-center">

                <div class="col-md-2">

                    <div class="text-muted small">
                        Open
                    </div>

                    <div class="fs-4 fw-semibold">
                        {{ $summary['open'] }}
                    </div>

                </div>


                <div class="col-md-2">

                    <div class="text-muted small">
                        Monitoring
                    </div>

                    <div class="fs-4 fw-semibold">
                        {{ $summary['monitoring'] }}
                    </div>

                </div>


                <div class="col-md-2">

                    <div class="text-muted small">
                        Mitigated
                    </div>

                    <div class="fs-4 fw-semibold">
                        {{ $summary['mitigated'] }}
                    </div>

                </div>


                <div class="col-md-2">

                    <div class="text-muted small">
                        Closed
                    </div>

                    <div class="fs-4 fw-semibold">
                        {{ $summary['closed'] }}
                    </div>

                </div>


                <div class="col-md-2">

                    <div class="text-muted small">
                        Occurred
                    </div>

                    <div class="fs-4 fw-semibold">
                        {{ $summary['occurred'] }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Risk Matrix --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Risk Matrix
            </strong>

            <span class="text-muted small ms-2">
                Probability × Impact
            </span>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered text-center mb-0">

                    <thead>

                        <tr>

                            <th rowspan="2">
                                Probability
                            </th>

                            <th colspan="5">
                                Impact
                            </th>

                        </tr>

                        <tr>

                            <th>Very Low</th>
                            <th>Low</th>
                            <th>Medium</th>
                            <th>High</th>
                            <th>Very High</th>

                        </tr>

                    </thead>


                    <tbody>

                        @php

                            $levels = [
                                'Very Low' => 1,
                                'Low' => 2,
                                'Medium' => 3,
                                'High' => 4,
                                'Very High' => 5,
                            ];

                        @endphp


                        @foreach($levels as $probability => $pValue)

                            <tr>

                                <th>
                                    {{ $probability }}
                                </th>


                                @foreach($levels as $impact => $iValue)

                                    @php

                                        $score =
                                            $pValue * $iValue;

                                        $level =
                                            match(true) {

                                                $score >= 17
                                                    => 'Critical',

                                                $score >= 10
                                                    => 'High',

                                                $score >= 5
                                                    => 'Medium',

                                                default
                                                    => 'Low',

                                            };

                                    @endphp


                                    <td>

                                        <div class="fw-semibold">
                                            {{ $score }}
                                        </div>

                                        <div class="small">
                                            {{ $level }}
                                        </div>

                                    </td>

                                @endforeach

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Risk List --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Project Risks
            </strong>

        </div>


        <div class="card-body p-0">

            @if($risks->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead>

                            <tr>

                                <th>Risk No.</th>

                                <th>Risk</th>

                                <th>Category</th>

                                <th>Probability</th>

                                <th>Impact</th>

                                <th>Score</th>

                                <th>Level</th>

                                <th>Owner</th>

                                <th>Status</th>

                                <th class="text-end">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($risks as $risk)

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


                                <tr>

                                    <td>

                                        <strong>
                                            {{ $risk->risk_number }}
                                        </strong>

                                    </td>


                                    <td>

                                        <div class="fw-semibold">
                                            {{ $risk->risk_title }}
                                        </div>

                                        <div class="text-muted small">

                                            {{
                                                \Illuminate\Support\Str::limit(
                                                    $risk->risk_description,
                                                    70
                                                )
                                            }}

                                        </div>

                                    </td>


                                    <td>
                                        {{ $risk->risk_category }}
                                    </td>


                                    <td>
                                        {{ $risk->probability }}
                                    </td>


                                    <td>
                                        {{ $risk->impact }}
                                    </td>


                                    <td>

                                        <strong>
                                            {{ $risk->risk_score }}
                                        </strong>

                                    </td>


                                    <td>

                                        <span
                                            class="badge {{ $levelClass }}"
                                        >
                                            {{ $risk->risk_level }}
                                        </span>

                                    </td>


                                    <td>

                                        {{
                                            $risk->riskOwner
                                                ? $risk->riskOwner->name
                                                : 'Unassigned'
                                        }}

                                    </td>


                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $risk->status }}
                                        </span>

                                    </td>


                                    <td class="text-end">

                                        <a
                                            href="{{ route(
                                                'admin.projects.risks.show',
                                                [
                                                    'project' =>
                                                        $project->id,
                                                    'risk' =>
                                                        $risk->id,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            View
                                        </a>


                                        <a
                                            href="{{ route(
                                                'admin.projects.risks.edit',
                                                [
                                                    'project' =>
                                                        $project->id,
                                                    'risk' =>
                                                        $risk->id,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-secondary"
                                        >
                                            Edit
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="p-5 text-center">

                    <div class="text-muted mb-3">
                        No risks have been registered for this project.
                    </div>

                    <a
                        href="{{ route(
                            'admin.projects.risks.create',
                            ['project' => $project->id]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Add First Risk
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection