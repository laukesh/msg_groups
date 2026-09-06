@extends('layouts.app')

@section('title', 'Equipment Maintenance')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Equipment Maintenance
            </h4>

            <div class="text-muted">
                {{ $project->project_number }}
                -
                {{ $project->project_name }}
            </div>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.equipment.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary">

                Equipment

            </a>

            <a
                href="{{ route(
                    'admin.projects.construction.equipment.usage.index',
                    $project
                ) }}"
                class="btn btn-outline-primary">

                Usage

            </a>

            <a
                href="{{ route(
                    'admin.projects.construction.equipment.maintenance.create',
                    $project
                ) }}"
                class="btn btn-success">

                + Add Maintenance

            </a>

        </div>

    </div>


    {{-- Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Maintenance
                    </div>

                    <h3 class="mb-0">
                        {{ $totalMaintenance }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Scheduled
                    </div>

                    <h3 class="mb-0">
                        {{ $scheduled }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        In Progress
                    </div>

                    <h3 class="mb-0">
                        {{ $inProgress }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Maintenance Cost
                    </div>

                    <h3 class="mb-0">
                        ${{ number_format($totalCost, 2) }}
                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3 align-items-end">

                    <div class="col-md-3">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Maintenance no, equipment..."
                        >

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Type
                        </label>

                        <select
                            name="maintenance_type"
                            class="form-select">

                            <option value="">
                                All Types
                            </option>

                            @foreach([
                                'Preventive',
                                'Corrective',
                                'Breakdown',
                                'Inspection',
                                'Servicing'
                            ] as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(
                                        request('maintenance_type') === $type
                                    )>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select">

                            <option value="">
                                All Status
                            </option>

                            @foreach([
                                'Scheduled',
                                'In Progress',
                                'Completed',
                                'Cancelled'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        request('status') === $status
                                    )>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            From
                        </label>

                        <input
                            type="date"
                            name="from_date"
                            class="form-control"
                            value="{{ request('from_date') }}">

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            To
                        </label>

                        <input
                            type="date"
                            name="to_date"
                            class="form-control"
                            value="{{ request('to_date') }}">

                    </div>


                    <div class="col-md-1">

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            Go

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <strong>
                Maintenance Records
            </strong>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>Maintenance No.</th>

                            <th>Date</th>

                            <th>Equipment</th>

                            <th>Type</th>

                            <th>Vendor</th>

                            <th>Cost</th>

                            <th>Status</th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($maintenances as $maintenance)

                            <tr>

                                <td>

                                    <strong>
                                        {{ $maintenance->maintenance_number }}
                                    </strong>

                                </td>

                                <td>

                                    {{ optional(
                                        $maintenance->maintenance_date
                                    )->format('d M Y')
                                    ?? optional(
                                        $maintenance->scheduled_date
                                    )->format('d M Y')
                                    ?? '—' }}

                                </td>

                                <td>

                                    <div class="fw-semibold">

                                        {{ $maintenance->equipment?->equipment_code
                                            ?? '—' }}

                                    </div>

                                    <div class="small text-muted">

                                        {{ $maintenance->equipment?->equipment_name
                                            ?? '' }}

                                    </div>

                                </td>

                                <td>

                                    <span class="badge bg-light text-dark">

                                        {{ $maintenance->maintenance_type }}

                                    </span>

                                </td>

                                <td>
                                    {{ $maintenance->maintenance_vendor ?? '—' }}
                                </td>

                                <td>

                                    ${{ number_format(
                                        $maintenance->cost,
                                        2
                                    ) }}

                                </td>

                                <td>

                                    @php

                                        $statusClass = match(
                                            $maintenance->status
                                        ) {

                                            'Scheduled' =>
                                                'bg-warning text-dark',

                                            'In Progress' =>
                                                'bg-primary',

                                            'Completed' =>
                                                'bg-success',

                                            'Cancelled' =>
                                                'bg-secondary',

                                            default =>
                                                'bg-light text-dark',
                                        };

                                    @endphp

                                    <span
                                        class="badge {{ $statusClass }}">

                                        {{ $maintenance->status }}

                                    </span>

                                </td>

                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.equipment.maintenance.show',
                                            [
                                                'project' => $project,
                                                'maintenance' => $maintenance
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary">

                                        View

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5 text-muted">

                                    No maintenance records found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if($maintenances->hasPages())

            <div class="card-footer bg-white">

                {{ $maintenances->links() }}

            </div>

        @endif

    </div>

</div>

@endsection