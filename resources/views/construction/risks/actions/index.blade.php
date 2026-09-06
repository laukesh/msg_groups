@extends('layouts.app')

@section('title', 'Risk Actions')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Risk Actions
            </h4>

            <div class="text-muted">
                {{ $risk->risk_number }}
                -
                {{ $risk->risk_title }}
            </div>

            <div class="text-muted small">
                {{ $project->project_code ?? $project->project_number }}
                -
                {{ $project->project_name }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.risks.show',
                [$project, $risk]
            ) }}"
            class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Back to Risk

            </a>

            <a href="{{ route(
                'admin.projects.construction.risks.actions.create',
                [$project, $risk]
            ) }}"
            class="btn btn-primary">

                <i class="bi bi-plus-lg"></i>
                Add Action

            </a>

        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Risk Summary --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Risk Number
                    </div>

                    <div class="fw-semibold">
                        {{ $risk->risk_number }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Category
                    </div>

                    <div class="fw-semibold">
                        {{ $risk->risk_category }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Risk Rating
                    </div>

                    <div>

                        @php
                            $ratingClass = match($risk->risk_rating) {
                                'Critical' => 'danger',
                                'High' => 'warning',
                                'Medium' => 'info',
                                default => 'success',
                            };
                        @endphp

                        <span class="badge bg-{{ $ratingClass }}">
                            {{ $risk->risk_rating }}
                        </span>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Risk Status
                    </div>

                    <div class="fw-semibold">
                        {{ $risk->status }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Actions Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Mitigation / Preventive Actions
                </strong>

                <span class="text-muted small">
                    {{ $actions->total() }} records
                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th width="25%">
                            Action
                        </th>

                        <th>
                            Type
                        </th>

                        <th>
                            Assigned To
                        </th>

                        <th>
                            Target Date
                        </th>

                        <th>
                            Completion Date
                        </th>

                        <th>
                            Priority
                        </th>

                        <th>
                            Status
                        </th>

                        <th width="120">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($actions as $action)

                        @php

                            $statusClass = match($action->status) {
                                'Completed' => 'success',
                                'In Progress' => 'primary',
                                'Overdue' => 'danger',
                                'Cancelled' => 'secondary',
                                default => 'warning',
                            };

                            $priorityClass = match($action->priority) {
                                'Critical' => 'danger',
                                'High' => 'warning',
                                'Medium' => 'info',
                                default => 'secondary',
                            };

                        @endphp

                        <tr>

                            <td>

                                <div class="fw-semibold">
                                    {{ $action->action_title }}
                                </div>

                                @if($action->action_description)

                                    <div class="text-muted small mt-1">
                                        {{ \Illuminate\Support\Str::limit(
                                            $action->action_description,
                                            100
                                        ) }}
                                    </div>

                                @endif

                            </td>


                            <td>
                                {{ $action->action_type }}
                            </td>


                            <td>

                                @if($action->assignedTo)

                                    {{ $action->assignedTo->name }}

                                @elseif($action->assigned_to_name)

                                    {{ $action->assigned_to_name }}

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            <td>

                                @if($action->target_date)

                                    {{ $action->target_date->format('d-m-Y') }}

                                @else

                                    —

                                @endif

                            </td>


                            <td>

                                @if($action->completion_date)

                                    {{ $action->completion_date->format('d-m-Y') }}

                                @else

                                    —

                                @endif

                            </td>


                            <td>

                                <span class="badge bg-{{ $priorityClass }}">
                                    {{ $action->priority }}
                                </span>

                            </td>


                            <td>

                                <span class="badge bg-{{ $statusClass }}">
                                    {{ $action->status }}
                                </span>

                            </td>


                            <td>

                                <div class="dropdown">

                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                            type="button"
                                            data-bs-toggle="dropdown">

                                        Actions

                                    </button>


                                    <ul class="dropdown-menu dropdown-menu-end">

                                        <li>

                                            <a class="dropdown-item"
                                               href="{{ route(
                                                   'admin.projects.construction.risks.actions.edit',
                                                   [$project, $risk, $action]
                                               ) }}">

                                                <i class="bi bi-pencil me-1"></i>
                                                Edit

                                            </a>

                                        </li>


                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>


                                        <li>

                                            <form method="POST"
                                                  action="{{ route(
                                                      'admin.projects.construction.risks.actions.destroy',
                                                      [$project, $risk, $action]
                                                  ) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this action?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="dropdown-item text-danger">

                                                    <i class="bi bi-trash me-1"></i>
                                                    Delete

                                                </button>

                                            </form>

                                        </li>

                                    </ul>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8"
                                class="text-center py-5">

                                <div class="text-muted mb-3">
                                    No risk actions found.
                                </div>

                                <a href="{{ route(
                                    'admin.projects.construction.risks.actions.create',
                                    [$project, $risk]
                                ) }}"
                                class="btn btn-primary btn-sm">

                                    <i class="bi bi-plus-lg"></i>
                                    Add First Action

                                </a>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($actions->hasPages())

            <div class="card-footer bg-white">

                {{ $actions->links() }}

            </div>

        @endif

    </div>

</div>

@endsection