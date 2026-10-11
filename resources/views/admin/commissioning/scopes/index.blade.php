@extends('layouts.app')

@section('title', 'Commissioning Scopes')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Commissioning Scopes
            </h4>

            <div class="text-muted">

                {{ $project->project_number ?? $project->project_code ?? '' }}

                -

                {{ $project->project_name ?? 'Project' }}

            </div>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.commissioning.index',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-speedometer2 me-1"></i>
                Dashboard
            </a>

            <a
                href="{{ route(
                    'admin.projects.commissioning.scopes.create',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Add Scope
            </a>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-circle me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
        SCOPE TABLE
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-1">
                        Commissioning Scope Register
                    </h5>

                    <div class="text-muted small">
                        Commissioning scopes linked to Construction Work Orders.
                    </div>

                </div>

                <span class="badge bg-light text-dark border">

                    {{ $scopes->total() }}

                    {{ $scopes->total() == 1 ? 'Scope' : 'Scopes' }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-3">
                                Scope
                            </th>

                            <th>
                                Construction Work Order
                            </th>

                            <th>
                                Location
                            </th>

                            <th style="min-width: 170px;">
                                Progress
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Planned Dates
                            </th>

                            <th class="text-end pe-3">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($scopes as $scope)

                            @php

                                $progress = min(
                                    100,
                                    max(
                                        0,
                                        (float) ($scope->progress_percentage ?? 0)
                                    )
                                );

                                $statusClass = match($scope->status) {

                                    'Accepted' =>
                                        'bg-success-subtle text-success',

                                    'Completed' =>
                                        'bg-success-subtle text-success',

                                    'Testing' =>
                                        'bg-info-subtle text-info',

                                    'In Progress' =>
                                        'bg-primary-subtle text-primary',

                                    'On Hold' =>
                                        'bg-warning-subtle text-warning',

                                    default =>
                                        'bg-secondary-subtle text-secondary',

                                };

                            @endphp


                            <tr>

                                {{-- =================================================
                                    SCOPE
                                ================================================== --}}
                                <td class="ps-3">

                                    <div class="fw-semibold">

                                        {{ $scope->scope_code }}

                                    </div>

                                    <div>

                                        {{ $scope->scope_name }}

                                    </div>

                                    @if($scope->scope_type)

                                        <small class="text-muted">

                                            {{ $scope->scope_type }}

                                        </small>

                                    @endif

                                </td>


                                {{-- =================================================
                                    WORK ORDER
                                ================================================== --}}
                                <td>

                                    @if($scope->workOrder)

                                        <div class="fw-semibold">

                                            {{ $scope->workOrder->work_order_number }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $scope->workOrder->work_order_title }}

                                        </small>

                                    @else

                                        <span class="text-muted">
                                            Work Order not found
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    LOCATION
                                ================================================== --}}
                                <td>

                                    {{ $scope->location ?: '-' }}

                                </td>


                                {{-- =================================================
                                    PROGRESS
                                ================================================== --}}
                                <td>

                                    <div class="d-flex justify-content-between mb-1">

                                        <small class="text-muted">
                                            Progress
                                        </small>

                                        <small class="fw-semibold">
                                            {{ number_format($progress, 1) }}%
                                        </small>

                                    </div>

                                    <div
                                        class="progress"
                                        style="height: 7px;"
                                    >

                                        <div
                                            class="progress-bar"
                                            role="progressbar"
                                            style="width: {{ $progress }}%;"
                                            aria-valuenow="{{ $progress }}"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                        ></div>

                                    </div>

                                </td>


                                {{-- =================================================
                                    STATUS
                                ================================================== --}}
                                <td>

                                    <span class="badge {{ $statusClass }}">

                                        {{ $scope->status }}

                                    </span>

                                </td>


                                {{-- =================================================
                                    DATES
                                ================================================== --}}
                                <td>

                                    <div>

                                        <small class="text-muted">
                                            Start
                                        </small>

                                        <br>

                                        {{ $scope->planned_start_date
                                            ? $scope->planned_start_date->format('d-m-Y')
                                            : '-' }}

                                    </div>

                                    <div class="mt-1">

                                        <small class="text-muted">
                                            Completion
                                        </small>

                                        <br>

                                        {{ $scope->planned_completion_date
                                            ? $scope->planned_completion_date->format('d-m-Y')
                                            : '-' }}

                                    </div>

                                </td>


                                {{-- =================================================
                                    ACTIONS
                                ================================================== --}}
                                <td class="text-end pe-3">

                                    <div class="d-inline-flex gap-1">

                                        {{-- View --}}
                                        <a
                                            href="{{ route(
                                                'admin.projects.commissioning.scopes.show',
                                                [
                                                    'project' => $project->id,
                                                    'scope' => $scope->id,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-secondary"
                                            title="View"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </a>


                                        {{-- Edit --}}
                                        <a
                                            href="{{ route(
                                                'admin.projects.commissioning.scopes.edit',
                                                [
                                                    'project' => $project->id,
                                                    'scope' => $scope->id,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            title="Edit"
                                        >
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>


                                        {{-- Delete --}}
                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.projects.commissioning.scopes.destroy',
                                                [
                                                    'project' => $project->id,
                                                    'scope' => $scope->id,
                                                ]
                                            ) }}"
                                            onsubmit="return confirm(
                                                'Are you sure you want to delete this commissioning scope?'
                                            );"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Delete"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5"
                                >

                                    <div class="mb-3">

                                        <i class="bi bi-diagram-3 fs-1 text-muted"></i>

                                    </div>

                                    <div class="fw-semibold">
                                        No commissioning scopes found.
                                    </div>

                                    <div class="text-muted small mb-3">
                                        Create a commissioning scope against a Construction Work Order.
                                    </div>

                                    <a
                                        href="{{ route(
                                            'admin.projects.commissioning.scopes.create',
                                            ['project' => $project->id]
                                        ) }}"
                                        class="btn btn-sm btn-primary"
                                    >
                                        <i class="bi bi-plus-lg me-1"></i>
                                        Add First Scope
                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =========================================================
            PAGINATION
        ========================================================== --}}
        @if($scopes->hasPages())

            <div class="card-footer bg-white">

                {{ $scopes->links() }}

            </div>

        @endif

    </div>

</div>

@endsection