@extends('layouts.app')

@section('title', 'Equipment')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Equipment
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
                    'admin.projects.construction.dashboard',
                    $project
                ) }}"
                class="btn btn-light border"
            >
                ← Back to Construction
            </a>

            <a
                href="{{ route(
                    'admin.projects.construction.equipment.create',
                    $project
                ) }}"
                class="btn btn-primary"
            >
                + Add Equipment
            </a>

        </div>

    </div>


    {{-- Summary --}}

    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Total Equipment
                    </small>

                    <h3 class="fw-bold mb-0 mt-2">
                        {{ $totalEquipment }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Available
                    </small>

                    <h3 class="fw-bold mb-0 mt-2 text-success">
                        {{ $availableEquipment }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Deployed
                    </small>

                    <h3 class="fw-bold mb-0 mt-2 text-primary">
                        {{ $deployedEquipment }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Maintenance / Breakdown
                    </small>

                    <h3 class="fw-bold mb-0 mt-2 text-warning">
                        {{ $maintenanceEquipment }}
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

                    <div class="col-md-5">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Equipment code, name, serial or registration"
                        >

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Category
                        </label>

                        <select
                            name="category"
                            class="form-select"
                        >

                            <option value="">
                                All Categories
                            </option>

                            @foreach($categories as $category)

                                <option
                                    value="{{ $category }}"
                                    @selected(
                                        request('category') === $category
                                    )
                                >
                                    {{ $category }}
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
                            class="form-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            @foreach([
                                'Available',
                                'Deployed',
                                'Under Maintenance',
                                'Breakdown',
                                'Retired'
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


                    <div class="col-md-2">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Search
                            </button>

                            <a
                                href="{{ route(
                                    'admin.projects.construction.equipment.index',
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


    {{-- Equipment Table --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <h6 class="mb-0 fw-bold">
                Equipment Master
            </h6>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th>#</th>
                            <th>Equipment</th>
                            <th>Category</th>
                            <th>Ownership</th>
                            <th>Make / Model</th>
                            <th>Registration</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($equipment as $item)

                        @php

                            $statusClass = match($item->status) {

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

                        <tr>

                            <td>
                                {{ $equipment->firstItem() + $loop->index }}
                            </td>

                            <td>

                                <strong>
                                    {{ $item->equipment_code }}
                                </strong>

                                <br>

                                <small class="text-muted">
                                    {{ $item->equipment_name }}
                                </small>

                            </td>

                            <td>
                                {{ $item->category ?: '—' }}
                            </td>

                            <td>
                                {{ $item->ownership_type }}
                            </td>

                            <td>

                                {{ $item->make ?: '—' }}

                                @if($item->model)
                                    / {{ $item->model }}
                                @endif

                            </td>

                            <td>
                                {{ $item->registration_number ?: '—' }}
                            </td>

                            <td>

                                <span class="badge {{ $statusClass }}">
                                    {{ $item->status }}
                                </span>

                            </td>

                            <td>

                                <a
                                    href="{{ route(
                                        'admin.projects.construction.equipment.show',
                                        [
                                            'project' => $project,
                                            'equipment' => $item,
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
                                colspan="8"
                                class="text-center text-muted py-5"
                            >

                                No equipment found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $equipment->links() }}

            </div>

        </div>

    </div>

</div>

@endsection