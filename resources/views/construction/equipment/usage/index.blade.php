@extends('layouts.app')

@section('title', 'Equipment Usage')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Equipment Usage
            </h4>

            <div class="text-muted">
                {{ $project->project_number }}
                -
                {{ $project->project_name }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.equipment.index',
                $project
            ) }}"
               class="btn btn-outline-secondary">

                Equipment

            </a>

            <a href="{{ route(
                'admin.projects.construction.equipment.deployments.index',
                $project
            ) }}"
               class="btn btn-outline-primary">

                Deployments

            </a>

            <a href="{{ route(
                'admin.projects.construction.equipment.usage.create',
                $project
            ) }}"
               class="btn btn-success">

                + Add Usage

            </a>

        </div>

    </div>


    {{-- Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Logs
                    </div>

                    <h3 class="mb-0">
                        {{ $totalLogs }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Operating Hours
                    </div>

                    <h3 class="mb-0">
                        {{ number_format($totalOperatingHours, 2) }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Idle Hours
                    </div>

                    <h3 class="mb-0">
                        {{ number_format($totalIdleHours, 2) }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Breakdown Hours
                    </div>

                    <h3 class="mb-0">
                        {{ number_format($totalBreakdownHours, 2) }}
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

                    <div class="col-md-4">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Usage no, equipment..."
                        >

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            From Date
                        </label>

                        <input
                            type="date"
                            name="from_date"
                            class="form-control"
                            value="{{ request('from_date') }}"
                        >

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            To Date
                        </label>

                        <input
                            type="date"
                            name="to_date"
                            class="form-control"
                            value="{{ request('to_date') }}"
                        >

                    </div>


                    <div class="col-md-2 d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            Filter

                        </button>

                        <a
                            href="{{ route(
                                'admin.projects.construction.equipment.usage.index',
                                $project
                            ) }}"
                            class="btn btn-outline-secondary">

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Usage Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <strong>
                Usage Logs
            </strong>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>Usage No.</th>

                            <th>Date</th>

                            <th>Equipment</th>

                            <th>Work Order</th>

                            <th>Operator</th>

                            <th class="text-end">
                                Operating
                            </th>

                            <th class="text-end">
                                Idle
                            </th>

                            <th class="text-end">
                                Breakdown
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($usageLogs as $usage)

                            <tr>

                                <td>

                                    <strong>
                                        {{ $usage->usage_number }}
                                    </strong>

                                </td>

                                <td>
                                    {{ optional($usage->usage_date)->format('d M Y') }}
                                </td>

                                <td>

                                    @if($usage->equipment)

                                        <div class="fw-semibold">
                                            {{ $usage->equipment->equipment_code }}
                                        </div>

                                        <div class="small text-muted">
                                            {{ $usage->equipment->equipment_name }}
                                        </div>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    {{ $usage->workOrder?->work_order_number ?? '—' }}

                                </td>

                                <td>

                                    {{ $usage->operator?->name ?? '—' }}

                                </td>

                                <td class="text-end">

                                    {{ number_format($usage->operating_hours, 2) }}

                                </td>

                                <td class="text-end">

                                    {{ number_format($usage->idle_hours, 2) }}

                                </td>

                                <td class="text-end">

                                    {{ number_format($usage->breakdown_hours, 2) }}

                                </td>

                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.equipment.usage.show',
                                            [
                                                'project' => $project,
                                                'usageLog' => $usage
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
                                    colspan="9"
                                    class="text-center py-5 text-muted">

                                    No equipment usage records found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if($usageLogs->hasPages())

            <div class="card-footer bg-white">

                {{ $usageLogs->links() }}

            </div>

        @endif

    </div>

</div>

@endsection