@extends('layouts.app')

@section('title', 'Risk Register')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Risk Register
            </h4>

            <div class="text-muted">
                {{ $project->project_code ?? $project->project_number }}
                -
                {{ $project->project_name }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.projects.construction.dashboard', $project) }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Construction Dashboard
            </a>

            <a href="{{ route('admin.projects.construction.risks.create', $project) }}"
               class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                Add Risk
            </a>

        </div>

    </div>


    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Total Risks
                    </div>

                    <h3 class="mb-0">
                        {{ $risks->total() }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        High / Critical
                    </div>

                    <h3 class="mb-0 text-danger">
                        {{
                            $risks->getCollection()
                                ->whereIn('risk_rating', ['High', 'Critical'])
                                ->count()
                        }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Under Monitoring
                    </div>

                    <h3 class="mb-0 text-primary">
                        {{
                            $risks->getCollection()
                                ->where('status', 'Monitoring')
                                ->count()
                        }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Open Actions
                    </div>

                    <h3 class="mb-0 text-warning">
                        {{
                            $risks->getCollection()
                                ->sum('actions_count')
                        }}
                    </h3>
                </div>
            </div>
        </div>

    </div>


    {{-- Risk Register --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Risk Register
                </strong>

                <span class="text-muted small">
                    {{ $risks->total() }} records
                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>
                        <th>Risk No.</th>
                        <th>Risk</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Probability</th>
                        <th>Impact</th>
                        <th>Score</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($risks as $risk)

                        @php

                            $ratingClass = match($risk->risk_rating) {
                                'Critical' => 'danger',
                                'High' => 'warning',
                                'Medium' => 'info',
                                default => 'success',
                            };

                            $statusClass = match($risk->status) {
                                'Closed' => 'success',
                                'Escalated' => 'danger',
                                'Monitoring' => 'primary',
                                'Under Assessment' => 'warning',
                                'Mitigation Planned' => 'info',
                                'Accepted' => 'secondary',
                                default => 'light',
                            };

                        @endphp

                        <tr>

                            <td>
                                <strong>
                                    {{ $risk->risk_number }}
                                </strong>
                            </td>

                            <td>
                                <a href="{{ route(
                                    'admin.projects.construction.risks.show',
                                    [$project, $risk]
                                ) }}"
                                class="text-decoration-none">

                                    {{ $risk->risk_title }}

                                </a>
                            </td>

                            <td>
                                {{ $risk->risk_category }}
                            </td>

                            <td>
                                {{ optional($risk->risk_date)->format('d-m-Y') }}
                            </td>

                            <td>
                                {{ $risk->probability }}
                            </td>

                            <td>
                                {{ $risk->impact_level }}
                            </td>

                            <td>
                                <strong>
                                    {{ $risk->risk_score }}
                                </strong>
                            </td>

                            <td>
                                <span class="badge bg-{{ $ratingClass }}">
                                    {{ $risk->risk_rating }}
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-{{ $statusClass }}">
                                    {{ $risk->status }}
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
                                                   'admin.projects.construction.risks.show',
                                                   [$project, $risk]
                                               ) }}">
                                                View
                                            </a>
                                        </li>

                                        @if(in_array($risk->status, [
                                            'Draft',
                                            'Identified',
                                            'Rejected'
                                        ]))

                                            <li>
                                                <a class="dropdown-item"
                                                   href="{{ route(
                                                       'admin.projects.construction.risks.edit',
                                                       [$project, $risk]
                                                   ) }}">
                                                    Edit
                                                </a>
                                            </li>

                                        @endif

                                        <li>
                                            <a class="dropdown-item"
                                               href="{{ route(
                                                   'admin.projects.construction.risks.actions.index',
                                                   [$project, $risk]
                                               ) }}">
                                                Risk Actions
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item"
                                               href="{{ route(
                                                   'admin.projects.construction.risks.documents.index',
                                                   [$project, $risk]
                                               ) }}">
                                                Documents
                                            </a>
                                        </li>

                                    </ul>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="10"
                                class="text-center py-5 text-muted">

                                No risks found.

                                <div class="mt-3">
                                    <a href="{{ route(
                                        'admin.projects.construction.risks.create',
                                        $project
                                    ) }}"
                                    class="btn btn-primary btn-sm">
                                        Add First Risk
                                    </a>
                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($risks->hasPages())

            <div class="card-footer bg-white">
                {{ $risks->links() }}
            </div>

        @endif

    </div>

</div>

@endsection