@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small">
                Project / Procurement Strategy
            </div>

            <h3 class="mb-1">
                Procurement Strategy
            </h3>

            <div class="text-muted">
                {{ $project->project_name }}
                · {{ $project->project_number }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.projects.show', ['project' => $project->id]) }}"
                class="btn btn-outline-secondary"
            >
                ← Project
            </a>

            <a
                href="{{ route(
                    'admin.projects.procurement-strategy.create',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-primary"
            >
                + New Procurement Strategy
            </a>

        </div>
    </div>


    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    <div class="card">

        <div class="card-header">
            <strong>Procurement Strategy Versions</strong>
        </div>

        <div class="card-body p-0">

            @if($procurementStrategies->count())

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead>
                            <tr>
                                <th>Version</th>
                                <th>Strategy Number</th>
                                <th>Title</th>
                                <th>Procurement Model</th>
                                <th>Status</th>
                                <th>Effective Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($procurementStrategies as $strategy)

                                @php
                                    $statusClass = match($strategy->status) {
                                        'Approved' => 'bg-success',
                                        'Submitted',
                                        'Under Review' => 'bg-warning text-dark',
                                        'Rejected' => 'bg-danger',
                                        default => 'bg-secondary',
                                    };
                                @endphp

                                <tr>

                                    <td>
                                        <strong>
                                            V{{ $strategy->version_number }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $strategy->strategy_number }}
                                    </td>

                                    <td>
                                        {{ $strategy->title }}
                                    </td>

                                    <td>
                                        {{ $strategy->procurement_model }}
                                    </td>

                                    <td>
                                        <span class="badge {{ $statusClass }}">
                                            {{ $strategy->status }}
                                        </span>
                                    </td>

                                    <td>
                                        {{
                                            $strategy->effective_date
                                                ? $strategy->effective_date->format('d M Y')
                                                : '—'
                                        }}
                                    </td>

                                    <td class="text-end">

                                        <a
                                            href="{{ route(
                                                'admin.projects.procurement-strategy.show',
                                                [
                                                    'project' => $project->id,
                                                    'procurementStrategy' => $strategy->id,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            View
                                        </a>

                                        @if($strategy->status !== 'Approved')

                                            <a
                                                href="{{ route(
                                                    'admin.projects.procurement-strategy.edit',
                                                    [
                                                        'project' => $project->id,
                                                        'procurementStrategy' => $strategy->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Edit
                                            </a>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="p-5 text-center">

                    <div class="text-muted mb-3">
                        No Procurement Strategy has been created yet.
                    </div>

                    <a
                        href="{{ route(
                            'admin.projects.procurement-strategy.create',
                            ['project' => $project->id]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Create Procurement Strategy
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection