@extends('layouts.app')

@section('title', 'Equipment Details')

@section('content')

<div class="container-fluid">

    {{-- ============================================================= --}}
    {{-- HEADER --}}
    {{-- ============================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Equipment Details
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
                    'admin.projects.construction.equipment.deployments.index',
                    $project
                ) }}"
                class="btn btn-outline-primary"
            >
                Deployments
            </a>

            <a
                href="{{ route(
                    'admin.projects.construction.equipment.deployments.create',
                    $project
                ) }}"
                class="btn btn-success"
            >
                + Deploy Equipment
            </a>

            <a
                href="{{ route(
                    'admin.projects.construction.equipment.edit',
                    [
                        'project' => $project,
                        'equipment' => $equipment,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit Equipment
            </a>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- SUCCESS / ERROR --}}
    {{-- ============================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif

    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    {{-- ============================================================= --}}
    {{-- EQUIPMENT SUMMARY --}}
    {{-- ============================================================= --}}

    @php

        $statusClass = match($equipment->status) {

            'Available'
                => 'bg-success',

            'Deployed'
                => 'bg-primary',

            'Under Maintenance',
            'Breakdown'
                => 'bg-warning text-dark',

            'Retired'
                => 'bg-secondary',

            default
                => 'bg-secondary',
        };

    @endphp


    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="row g-4 align-items-center">

                <div class="col-md-6">

                    <div class="text-muted small mb-1">
                        Equipment
                    </div>

                    <h3 class="fw-bold mb-1">

                        {{ $equipment->equipment_name }}

                    </h3>

                    <div class="text-muted">

                        {{ $equipment->equipment_code }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small mb-1">
                        Ownership
                    </div>

                    <div class="fw-semibold">
                        {{ $equipment->ownership_type }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small mb-1">
                        Status
                    </div>

                    <span class="badge {{ $statusClass }} px-3 py-2">

                        {{ $equipment->status }}

                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- EQUIPMENT KPIs --}}
    {{-- ============================================================= --}}

    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Operating Hours
                    </small>

                    <h3 class="fw-bold mt-2 mb-0">

                        {{ number_format(
                            $totalOperatingHours,
                            2
                        ) }}

                    </h3>

                    <small class="text-muted">
                        Total recorded usage
                    </small>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Idle Hours
                    </small>

                    <h3 class="fw-bold mt-2 mb-0">

                        {{ number_format(
                            $totalIdleHours,
                            2
                        ) }}

                    </h3>

                    <small class="text-muted">
                        Recorded idle time
                    </small>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Maintenance Cost
                    </small>

                    <h3 class="fw-bold mt-2 mb-0">

                        {{ number_format(
                            $totalMaintenanceCost,
                            2
                        ) }}

                    </h3>

                    <small class="text-muted">
                        Recorded maintenance cost
                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- GENERAL INFORMATION --}}
    {{-- ============================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h6 class="mb-0 fw-bold">
                Equipment Information
            </h6>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Category
                    </div>

                    <div class="fw-semibold">
                        {{ $equipment->category ?: '—' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Make
                    </div>

                    <div class="fw-semibold">
                        {{ $equipment->make ?: '—' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Model
                    </div>

                    <div class="fw-semibold">
                        {{ $equipment->model ?: '—' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Serial Number
                    </div>

                    <div class="fw-semibold">
                        {{ $equipment->serial_number ?: '—' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Registration Number
                    </div>

                    <div class="fw-semibold">
                        {{ $equipment->registration_number ?: '—' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Capacity
                    </div>

                    <div class="fw-semibold">

                        @if($equipment->capacity !== null)

                            {{ number_format(
                                $equipment->capacity,
                                4
                            ) }}

                            {{ $equipment->capacity_unit }}

                        @else

                            —

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- COMMERCIAL INFORMATION --}}
    {{-- ============================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h6 class="mb-0 fw-bold">
                Commercial Information
            </h6>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Purchase Date
                    </div>

                    <div class="fw-semibold">

                        {{ $equipment->purchase_date
                            ? $equipment->purchase_date->format('d M Y')
                            : '—' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Purchase Value
                    </div>

                    <div class="fw-semibold">

                        @if($equipment->purchase_value !== null)

                            {{ number_format(
                                $equipment->purchase_value,
                                2
                            ) }}

                        @else

                            —

                        @endif

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Hire Rate
                    </div>

                    <div class="fw-semibold">

                        @if($equipment->hire_rate !== null)

                            {{ number_format(
                                $equipment->hire_rate,
                                2
                            ) }}

                            / {{ $equipment->hire_rate_unit }}

                        @else

                            —

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- DEPLOYMENT HISTORY --}}
    {{-- ============================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3 d-flex justify-content-between">

            <h6 class="mb-0 fw-bold">
                Deployment History
            </h6>

            <span class="badge bg-light text-dark">
                {{ $equipment->deployments->count() }}
            </span>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-4">
                                Deployment No.
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Work Order
                            </th>

                            <th>
                                Location
                            </th>

                            <th>
                                Operator
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($equipment->deployments as $deployment)

                        <tr>

                            <td class="ps-4 fw-semibold">

                                {{ $deployment->deployment_number }}

                            </td>

                            <td>

                                {{ optional(
                                    $deployment->deployment_date
                                )->format('d M Y') }}

                            </td>

                            <td>

                                @if($deployment->workOrder)

                                    {{
                                        $deployment
                                            ->workOrder
                                            ->work_order_number
                                        ?? $deployment
                                            ->workOrder
                                            ->work_order_no
                                        ?? '#'
                                            . $deployment
                                            ->construction_work_order_id
                                    }}

                                @else

                                    —

                                @endif

                            </td>

                            <td>
                                {{ $deployment->location ?: '—' }}
                            </td>

                            <td>

                                {{ $deployment->operator?->name
                                    ?? '—' }}

                            </td>

                            <td>

                                <span class="badge bg-secondary">

                                    {{ $deployment->status }}

                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center text-muted py-4"
                            >

                                No deployment records yet.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- USAGE HISTORY --}}
    {{-- ============================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h6 class="mb-0 fw-bold">
                Recent Usage
            </h6>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-4">
                                Usage No.
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Operating Hours
                            </th>

                            <th>
                                Idle Hours
                            </th>

                            <th>
                                Fuel
                            </th>

                            <th>
                                Work Order
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($equipment->usageLogs as $usage)

                        <tr>

                            <td class="ps-4 fw-semibold">

                                {{ $usage->usage_number }}

                            </td>

                            <td>

                                {{ optional(
                                    $usage->usage_date
                                )->format('d M Y') }}

                            </td>

                            <td>

                                {{ number_format(
                                    $usage->operating_hours,
                                    2
                                ) }}

                            </td>

                            <td>

                                {{ number_format(
                                    $usage->idle_hours,
                                    2
                                ) }}

                            </td>

                            <td>

                                @if($usage->fuel_consumed !== null)

                                    {{ number_format(
                                        $usage->fuel_consumed,
                                        4
                                    ) }}

                                    {{ $usage->fuel_unit }}

                                @else

                                    —

                                @endif

                            </td>

                            <td>

                                @if($usage->workOrder)

                                    {{
                                        $usage
                                            ->workOrder
                                            ->work_order_number
                                        ?? $usage
                                            ->workOrder
                                            ->work_order_no
                                        ?? '#'
                                            . $usage
                                            ->construction_work_order_id
                                    }}

                                @else

                                    —

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center text-muted py-4"
                            >

                                No usage records yet.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- MAINTENANCE HISTORY --}}
    {{-- ============================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h6 class="mb-0 fw-bold">
                Maintenance History
            </h6>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-4">
                                Maintenance No.
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Cost
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse(
                        $equipment->maintenanceRecords
                        as $maintenance
                    )

                        <tr>

                            <td class="ps-4 fw-semibold">

                                {{ $maintenance->maintenance_number }}

                            </td>

                            <td>

                                {{ $maintenance->maintenance_type }}

                            </td>

                            <td>

                                {{ optional(
                                    $maintenance->maintenance_date
                                )->format('d M Y') }}

                            </td>

                            <td>

                                {{ number_format(
                                    $maintenance->cost,
                                    2
                                ) }}

                            </td>

                            <td>

                                <span class="badge bg-secondary">

                                    {{ $maintenance->status }}

                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center text-muted py-4"
                            >

                                No maintenance records yet.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- DESCRIPTION --}}
    {{-- ============================================================= --}}

    @if(
        $equipment->description ||
        $equipment->remarks
    )

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h6 class="mb-0 fw-bold">
                    Description & Remarks
                </h6>

            </div>

            <div class="card-body">

                @if($equipment->description)

                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            Description
                        </div>

                        <div>
                            {!! nl2br(
                                e($equipment->description)
                            ) !!}
                        </div>

                    </div>

                @endif


                @if($equipment->remarks)

                    <div>

                        <div class="text-muted small mb-1">
                            Remarks
                        </div>

                        <div>
                            {!! nl2br(
                                e($equipment->remarks)
                            ) !!}
                        </div>

                    </div>

                @endif

            </div>

        </div>

    @endif


    {{-- ============================================================= --}}
    {{-- ACTIONS --}}
    {{-- ============================================================= --}}

    <div class="d-flex justify-content-end gap-2 mb-4">

        <a
            href="{{ route(
                'admin.projects.construction.equipment.index',
                $project
            ) }}"
            class="btn btn-secondary"
        >
            Back
        </a>
        <a
            href="{{ route(
                'admin.projects.construction.equipment.maintenance.index',
                $project
            ) }}"
            class="btn btn-outline-primary">

            Maintenance

        </a>

        <a
            href="{{ route(
                'admin.projects.construction.equipment.maintenance.create',
                $project
            ) }}"
            class="btn btn-success">

            + Maintenance

        </a>
        <a
            href="{{ route(
                'admin.projects.construction.equipment.usage.index',
                $project
            ) }}"
            class="btn btn-outline-primary">

            Usage Logs

        </a>

        <a
            href="{{ route(
                'admin.projects.construction.equipment.usage.create',
                $project
            ) }}"
            class="btn btn-success">

            + Add Usage

        </a>

        <a
            href="{{ route(
                'admin.projects.construction.equipment.edit',
                [
                    'project' => $project,
                    'equipment' => $equipment,
                ]
            ) }}"
            class="btn btn-primary"
        >
            Edit Equipment
        </a>

    </div>

</div>

@endsection