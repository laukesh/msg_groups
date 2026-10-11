@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>
            <div class="text-muted small">
                Project / Stakeholder Register
            </div>

            <h3 class="mb-1">
                Stakeholder Register
            </h3>

            <div class="text-muted">
                {{ $project->project_name }}
                @if($project->project_number)
                    · {{ $project->project_number }}
                @endif
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
                    'admin.projects.stakeholders.create',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-primary"
            >
                + Add Stakeholder
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
                        Total Stakeholders
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $summary['total'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Active
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $summary['active'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100 border-danger">

                <div class="card-body">

                    <div class="text-muted small">
                        Critical Priority
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
                        High Priority
                    </div>

                    <div class="fs-3 fw-semibold text-warning">
                        {{ $summary['high'] }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Influence / Interest Matrix --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Influence × Interest Matrix
            </strong>

            <span class="text-muted small ms-2">
                Stakeholder engagement classification
            </span>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered text-center mb-0">

                    <thead>

                        <tr>

                            <th rowspan="2">
                                Influence
                            </th>

                            <th colspan="5">
                                Interest
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
                                'Very Low',
                                'Low',
                                'Medium',
                                'High',
                                'Very High',
                            ];

                        @endphp


                        @foreach($levels as $influence)

                            <tr>

                                <th>
                                    {{ $influence }}
                                </th>


                                @foreach($levels as $interest)

                                    @php

                                        $matrixCount =
                                            $stakeholders
                                                ->where(
                                                    'influence_level',
                                                    $influence
                                                )
                                                ->where(
                                                    'interest_level',
                                                    $interest
                                                )
                                                ->count();

                                    @endphp


                                    <td>

                                        @if($matrixCount > 0)

                                            <span
                                                class="badge bg-primary fs-6"
                                            >
                                                {{ $matrixCount }}
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>

                                @endforeach

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            <div class="mt-3 text-muted small">

                <strong>Management approach:</strong>

                High influence / high interest stakeholders
                generally require close management.

                High influence / low interest stakeholders
                generally require regular monitoring and satisfaction.

                Low influence / high interest stakeholders
                should be kept informed.

            </div>

        </div>

    </div>


    {{-- Stakeholder List --}}

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Stakeholders
                </strong>

                <span class="text-muted small">
                    {{ $stakeholders->count() }} records
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($stakeholders->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead>

                            <tr>

                                <th>
                                    No.
                                </th>

                                <th>
                                    Stakeholder
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Organization
                                </th>

                                <th>
                                    Influence
                                </th>

                                <th>
                                    Interest
                                </th>

                                <th>
                                    Engagement
                                </th>

                                <th>
                                    Priority
                                </th>

                                <th>
                                    Owner
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($stakeholders as $stakeholder)

                                @php

                                    $priorityClass =
                                        match(
                                            $stakeholder->priority
                                        ) {

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
                                        $stakeholder->status === 'Active'
                                            ? 'bg-success'
                                            : 'bg-secondary';

                                @endphp


                                <tr>

                                    <td>

                                        <strong>
                                            {{
                                                $stakeholder
                                                    ->stakeholder_number
                                            }}
                                        </strong>

                                    </td>


                                    <td>

                                        <div class="fw-semibold">
                                            {{
                                                $stakeholder
                                                    ->stakeholder_name
                                            }}
                                        </div>

                                        @if($stakeholder->role)

                                            <div class="text-muted small">
                                                {{ $stakeholder->role }}
                                            </div>

                                        @endif

                                    </td>


                                    <td>
                                        {{ $stakeholder->stakeholder_type }}
                                    </td>


                                    <td>

                                        {{
                                            $stakeholder
                                                ->organization_name
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>
                                        {{ $stakeholder->influence_level }}
                                    </td>


                                    <td>
                                        {{ $stakeholder->interest_level }}
                                    </td>


                                    <td>
                                        {{ $stakeholder->engagement_level }}
                                    </td>


                                    <td>

                                        <span
                                            class="badge {{ $priorityClass }}"
                                        >
                                            {{ $stakeholder->priority }}
                                        </span>

                                    </td>


                                    <td>

                                        {{
                                            $stakeholder
                                                ->stakeholderOwner
                                                ? $stakeholder
                                                    ->stakeholderOwner
                                                    ->name
                                                : 'Unassigned'
                                        }}

                                    </td>


                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $stakeholder->status }}
                                        </span>

                                    </td>


                                    <td class="text-end">

                                        <a
                                            href="{{ route(
                                                'admin.projects.stakeholders.show',
                                                [
                                                    'project' =>
                                                        $project->id,
                                                    'stakeholder' =>
                                                        $stakeholder->id,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            View
                                        </a>


                                        <a
                                            href="{{ route(
                                                'admin.projects.stakeholders.edit',
                                                [
                                                    'project' =>
                                                        $project->id,
                                                    'stakeholder' =>
                                                        $stakeholder->id,
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
                        No stakeholders have been registered
                        for this project yet.
                    </div>

                    <a
                        href="{{ route(
                            'admin.projects.stakeholders.create',
                            ['project' => $project->id]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Add First Stakeholder
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection