@extends('layouts.app')

@section('title', 'Equipment Deployments')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Equipment Deployments
            </h4>

            <p class="text-muted mb-0">

                {{ $project->project_number }}

                <span class="mx-1">•</span>

                {{ $project->project_name }}

            </p>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.equipment.index',
                    $project
                ) }}"
                class="btn btn-light border"
            >
                ← Back to Equipment
            </a>

            <a
                href="{{ route(
                    'admin.projects.construction.equipment.deployments.create',
                    $project
                ) }}"
                class="btn btn-primary"
            >
                + New Deployment
            </a>

        </div>

    </div>


    {{-- Summary --}}

    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Total Deployments
                    </small>

                    <h3 class="fw-bold mt-2 mb-0">
                        {{ $totalDeployments }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Planned
                    </small>

                    <h3 class="fw-bold mt-2 mb-0 text-warning">
                        {{ $plannedDeployments }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Currently Deployed
                    </small>

                    <h3 class="fw-bold mt-2 mb-0 text-primary">
                        {{ $activeDeployments }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Returned
                    </small>

                    <h3 class="fw-bold mt-2 mb-0 text-success">
                        {{ $returnedDeployments }}
                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- Search --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3 align-items-end">

                    <div class="col-md-6">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Deployment number, equipment or location"
                        >

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            @foreach([
                                'Planned',
                                'Deployed',
                                'Returned',
                                'Cancelled'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        request('status') === $status
                                    )
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Search
                            </button>

                            <a
                                href="{{ route(
                                    'admin.projects.construction.equipment.deployments.index',
                                    $project
                                ) }}"
                                class="btn btn-secondary"
                            >
                                Reset
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Table --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <h6 class="mb-0 fw-bold">
                Deployment Records
            </h6>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th>#</th>
                            <th>Deployment</th>
                            <th>Equipment</th>
                            <th>Date</th>
                            <th>Work Order</th>
                            <th>Location</th>
                            <th>Operator</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($deployments as $deployment)

                        @php

                            $statusClass = match(
                                $deployment->status
                            ) {

                                'Planned'
                                    => 'bg-warning text-dark',

                                'Deployed'
                                    => 'bg-primary',

                                'Returned'
                                    => 'bg-success',

                                'Cancelled'
                                    => 'bg-secondary',

                                default
                                    => 'bg-secondary',
                            };

                        @endphp

                        <tr>

                            <td>
                                {{ $deployments->firstItem() + $loop->index }}
                            </td>

                            <td>

                                <a
                                    href="{{ route(
                                        'admin.projects.construction.equipment.deployments.show',
                                        [
                                            'project' => $project,
                                            'deployment' => $deployment,
                                        ]
                                    ) }}"
                                    class="fw-bold text-primary text-decoration-none"
                                >
                                    {{ $deployment->deployment_number }}
                                </a>

                            </td>

                            <td>

                                @if($deployment->equipment)

                                    <strong>
                                        {{ $deployment->equipment->equipment_code }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ $deployment->equipment->equipment_name }}
                                    </small>

                                @else

                                    —

                                @endif

                            </td>

                            <td>

                                {{ optional(
                                    $deployment->deployment_date
                                )->format('d M Y') }}

                            </td>

                            <td>

                                @if($deployment->workOrder)

                                    {{
                                        $deployment->workOrder->work_order_number
                                        ?? $deployment->workOrder->work_order_no
                                        ?? $deployment->workOrder->order_number
                                        ?? '#'.$deployment->construction_work_order_id
                                    }}

                                @else

                                    —

                                @endif

                            </td>

                            <td>
                                {{ $deployment->location ?: '—' }}
                            </td>

                            <td>
                                {{ $deployment->operator?->name ?? '—' }}
                            </td>

                            <td>

                                <span class="badge {{ $statusClass }}">
                                    {{ $deployment->status }}
                                </span>

                            </td>

                            <td>

                                <a
                                    href="{{ route(
                                        'admin.projects.construction.equipment.deployments.show',
                                        [
                                            'project' => $project,
                                            'deployment' => $deployment,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-info"
                                >
                                    View
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="9"
                                class="text-center text-muted py-5"
                            >

                                No equipment deployments found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>


            <div class="mt-3">

                {{ $deployments->links() }}

            </div>

        </div>

    </div>

</div>

@endsection