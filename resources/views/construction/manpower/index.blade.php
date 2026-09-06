@extends('layouts.app')

@section('title', 'Manpower')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Manpower
            </h4>

            <div class="text-muted">
                Construction manpower master
            </div>
        </div>

         <div class="d-flex gap-2">
            <a
                href="{{ route(
                    'admin.projects.construction.dashboard',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                Construction Dashboard
            </a>

            <a href="{{ route(
                'admin.projects.construction.manpower.assignments.index',
                $project
            ) }}"
               class="btn btn-outline-primary">

                <i class="bi bi-person-check"></i>
                Assignments

            </a>

            <a href="{{ route(
                'admin.projects.construction.manpower.create',
                $project
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-lg"></i>
                Add Manpower

            </a>

            

        </div>

    </div>


    {{-- Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Total Manpower
                    </div>
                    <h3 class="mb-0">
                        {{ $total }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Active
                    </div>
                    <h3 class="mb-0">
                        {{ $active }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Inactive
                    </div>
                    <h3 class="mb-0">
                        {{ $inactive }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Active Skilled
                    </div>
                    <h3 class="mb-0">
                        {{ $skilled }}
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
                            placeholder="Code, name, trade, phone">

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Type
                        </label>

                        <select
                            name="manpower_type"
                            class="form-select">

                            <option value="">
                                All
                            </option>

                            @foreach([
                                'Skilled',
                                'Semi-Skilled',
                                'Unskilled',
                                'Supervisor',
                                'Engineer',
                                'Technician',
                                'Operator',
                                'Other'
                            ] as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(
                                        request('manpower_type') === $type
                                    )>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Employment
                        </label>

                        <select
                            name="employment_type"
                            class="form-select">

                            <option value="">
                                All
                            </option>

                            @foreach([
                                'Direct',
                                'Contract',
                                'Subcontract',
                                'Temporary'
                            ] as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(
                                        request('employment_type') === $type
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
                                All
                            </option>

                            <option
                                value="Active"
                                @selected(request('status') === 'Active')>

                                Active

                            </option>

                            <option
                                value="Inactive"
                                @selected(request('status') === 'Inactive')>

                                Inactive

                            </option>

                        </select>

                    </div>


                    <div class="col-md-2 d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            Filter

                        </button>

                        <a
                            href="{{ route(
                                'admin.projects.construction.manpower.index',
                                $project
                            ) }}"
                            class="btn btn-outline-secondary"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <strong>
                Manpower Master
            </strong>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>Code</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Trade</th>
                            <th>Employment</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($manpower as $person)

                            <tr>

                                <td>
                                    <strong>
                                        {{ $person->manpower_code }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $person->manpower_name }}
                                </td>

                                <td>
                                    {{ $person->manpower_type }}
                                </td>

                                <td>
                                    {{ $person->trade ?? '—' }}
                                </td>

                                <td>
                                    {{ $person->employment_type }}
                                </td>

                                <td>
                                    {{ $person->phone ?? '—' }}
                                </td>

                                <td>

                                    @if($person->status === 'Active')

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Inactive
                                        </span>

                                    @endif

                                </td>

                                <td class="text-end">

                                    <a
                                        href="{{ route(
        'admin.projects.construction.manpower.show',
        [
            'project' => $project,
            'manpower' => $person
        ]
    ) }}"
                                        class="btn btn-sm btn-outline-primary">

                                        View

                                    </a>

                                    <a
                                        href="{{ route(
        'admin.projects.construction.manpower.edit',
        [
            'project' => $project,
            'manpower' => $person
        ]
    ) }}"
                                        class="btn btn-sm btn-outline-secondary">

                                        Edit

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5 text-muted">

                                    No manpower records found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($manpower->hasPages())

            <div class="card-footer bg-white">

                {{ $manpower->links() }}

            </div>

        @endif

    </div>

</div>

@endsection