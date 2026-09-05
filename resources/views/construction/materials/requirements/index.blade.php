@extends('layouts.app')

@section('title', 'Material Requirements')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Material Requirements
            </h4>

            <div class="text-muted">
                {{ $project->project_name }}
                @if($project->project_number)
                    <span class="mx-1">•</span>
                    {{ $project->project_number }}
                @endif
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.projects.construction.dashboard', $project) }}"
               class="btn btn-secondary">
                ← Back to Construction
            </a>

            <a href="{{ route('admin.projects.construction.materials.index', ['project' => $project->id]) }}"
               class="btn btn-outline-primary">
                Materials
            </a>

            <a href="{{ route('admin.projects.construction.materials.requirements.create', ['project' => $project->id]) }}"
               class="btn btn-primary">
                + New Requirement
            </a>

        </div>

    </div>


    {{-- Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Requirements
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $summary['total'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Draft
                    </div>

                    <div class="fs-3 fw-bold text-secondary">
                        {{ $summary['draft'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Requested
                    </div>

                    <div class="fs-3 fw-bold text-primary">
                        {{ $summary['requested'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Fulfilled
                    </div>

                    <div class="fs-3 fw-bold text-success">
                        {{ $summary['fulfilled'] }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.projects.construction.materials.requirements.index', ['project' => $project->id]) }}">

                <div class="row g-3 align-items-end">

                    <div class="col-md-6">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Material, code, work order or purpose">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Statuses
                            </option>

                            @foreach([
                                'Draft',
                                'Requested',
                                'Partially Fulfilled',
                                'Fulfilled',
                                'Cancelled'
                            ] as $status)

                                <option value="{{ $status }}"
                                    @selected(request('status') === $status)>
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3 d-flex gap-2">

                        <button type="submit"
                                class="btn btn-primary">
                            Search
                        </button>

                        <a href="{{ route('admin.projects.construction.materials.requirements.index', ['project' => $project->id]) }}"
                           class="btn btn-outline-secondary">
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Material
                            </th>

                            <th>
                                Work Order
                            </th>

                            <th>
                                Required Qty
                            </th>

                            <th>
                                Required Date
                            </th>

                            <th>
                                Purpose
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($requirements as $requirement)

                        <tr>

                            <td>
                                {{ $requirements->firstItem() + $loop->index }}
                            </td>


                            <td>

                                @if($requirement->material)

                                    <div class="fw-semibold">
                                        {{ $requirement->material->material_name }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $requirement->material->material_code }}
                                    </small>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            <td>

                                @if($requirement->workOrder)

                                    <div class="fw-semibold">
                                        {{ $requirement->workOrder->work_order_number }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $requirement->workOrder->work_order_title }}
                                    </small>

                                @else

                                    <span class="text-muted">
                                        General Project
                                    </span>

                                @endif

                            </td>


                            <td>

                                <span class="fw-semibold">
                                    {{ number_format((float) $requirement->required_quantity, 4) }}
                                </span>

                                <span class="text-muted">
                                    {{ $requirement->unit }}
                                </span>

                            </td>


                            <td>

                                @if($requirement->required_date)

                                    {{ $requirement->required_date->format('d M Y') }}

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            <td>

                                {{ $requirement->purpose ?: '—' }}

                            </td>


                            <td>

                                @php

                                    $badgeClass = match($requirement->status) {

                                        'Draft' =>
                                            'bg-secondary',

                                        'Requested' =>
                                            'bg-primary',

                                        'Partially Fulfilled' =>
                                            'bg-warning text-dark',

                                        'Fulfilled' =>
                                            'bg-success',

                                        'Cancelled' =>
                                            'bg-danger',

                                        default =>
                                            'bg-secondary',
                                    };

                                @endphp

                                <span class="badge {{ $badgeClass }}">
                                    {{ $requirement->status }}
                                </span>

                            </td>


                            <td class="text-end">

                                <a href="{{ route('admin.projects.construction.materials.requirements.show', [
                                    'project' => $project->id,
                                    'requirement' => $requirement->id,
                                ]) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    View
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8"
                                class="text-center py-5">

                                <div class="text-muted mb-2">
                                    No material requirements found.
                                </div>

                                <a href="{{ route('admin.projects.construction.materials.requirements.create', ['project' => $project->id]) }}"
                                   class="btn btn-primary btn-sm">
                                    + Create Requirement
                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($requirements->hasPages())

            <div class="card-footer bg-white">

                {{ $requirements->links() }}

            </div>

        @endif

    </div>

</div>

@endsection