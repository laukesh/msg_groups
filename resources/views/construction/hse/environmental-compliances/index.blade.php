@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project:
                <strong>
                    {{ $project->project_code ?? '—' }}
                </strong>
            </div>

            <h3 class="mb-1">
                Environmental Compliance
            </h3>

            <div class="text-muted">
                Track environmental legal and regulatory compliance.
            </div>

        </div>
        <div class="d-flex gap-2">
            <a
                href="{{ route(
                    'admin.projects.construction.hse.index',
                    [
                        'project' => $project,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back to HSE
            </a>
            <a
                href="{{ route(
                    'admin.projects.construction.hse.environmental.compliances.create',
                    [
                        'project' => $project,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Add Compliance
            </a>
        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Summary --}}

    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted small">
                        Total
                    </div>

                    <h4 class="mb-0">
                        {{ $compliances->count() }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted small">
                        Compliant
                    </div>

                    <h4 class="mb-0 text-success">
                        {{ $compliances->where(
                            'compliance_status',
                            'Compliant'
                        )->count() }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted small">
                        Non-Compliant
                    </div>

                    <h4 class="mb-0 text-danger">
                        {{ $compliances->where(
                            'compliance_status',
                            'Non-Compliant'
                        )->count() }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted small">
                        Overdue
                    </div>

                    <h4 class="mb-0 text-warning">
                        {{ $compliances->filter(
                            fn ($item) => $item->isOverdue()
                        )->count() }}
                    </h4>

                </div>

            </div>

        </div>

    </div>


    <div class="card">

        <div class="card-header">

            <strong>
                Environmental Compliance Register
            </strong>

        </div>


        <div class="card-body p-0">

            @if($compliances->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Compliance</th>

                            <th>Type</th>

                            <th>Authority</th>

                            <th>Due Date</th>

                            <th>Risk</th>

                            <th>Compliance</th>

                            <th>Status</th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($compliances as $compliance)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.environmental.compliances.show',
                                            [
                                                'project' => $project,
                                                'compliance' => $compliance,
                                            ]
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >
                                        {{ $compliance->compliance_number }}
                                    </a>

                                    <div class="small text-muted">
                                        {{ $compliance->compliance_title }}
                                    </div>

                                </td>


                                <td>
                                    {{ $compliance->compliance_type }}
                                </td>


                                <td>
                                    {{ $compliance->regulatory_authority ?? '—' }}
                                </td>


                                <td>

                                    @if($compliance->due_date)

                                        {{ $compliance->due_date->format('d-m-Y') }}

                                        @if($compliance->isOverdue())

                                            <span class="badge bg-danger ms-1">
                                                Overdue
                                            </span>

                                        @endif

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    @switch($compliance->risk_level)

                                        @case('Critical')
                                            <span class="badge bg-danger">
                                                Critical
                                            </span>
                                            @break

                                        @case('High')
                                            <span class="badge bg-warning text-dark">
                                                High
                                            </span>
                                            @break

                                        @case('Medium')
                                            <span class="badge bg-info text-dark">
                                                Medium
                                            </span>
                                            @break

                                        @default
                                            <span class="badge bg-success">
                                                Low
                                            </span>

                                    @endswitch

                                </td>


                                <td>

                                    @switch($compliance->compliance_status)

                                        @case('Compliant')
                                            <span class="badge bg-success">
                                                Compliant
                                            </span>
                                            @break

                                        @case('Non-Compliant')
                                            <span class="badge bg-danger">
                                                Non-Compliant
                                            </span>
                                            @break

                                        @case('Pending')
                                            <span class="badge bg-warning text-dark">
                                                Pending
                                            </span>
                                            @break

                                        @default
                                            <span class="badge bg-secondary">
                                                {{ $compliance->compliance_status }}
                                            </span>

                                    @endswitch

                                </td>


                                <td>

                                    <span class="badge bg-secondary">
                                        {{ $compliance->status }}
                                    </span>

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.environmental.compliances.show',
                                            [
                                                'project' => $project,
                                                'compliance' => $compliance,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <i
                        class="bi bi-shield-check"
                        style="font-size:42px;"
                    ></i>

                    <h6 class="mt-3">
                        No Compliance Records
                    </h6>

                    <p class="text-muted">
                        Add environmental compliance requirements
                        for this project.
                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.environmental.compliances.create',
                            [
                                'project' => $project,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        Add Compliance
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection