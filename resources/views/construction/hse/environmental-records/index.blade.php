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
                Environmental Records
            </h3>

            <div class="text-muted">
                Environmental monitoring, compliance and records.
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
                    'admin.projects.construction.hse.environmental.records.create',
                    [
                        'project' => $project,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Add Environmental Record
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
                        Total Records
                    </div>

                    <h4 class="mb-0">
                        {{ $records->count() }}
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
                        {{ $records->where(
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
                        {{ $records->where(
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
                        Pending
                    </div>

                    <h4 class="mb-0 text-warning">
                        {{ $records->where(
                            'compliance_status',
                            'Pending'
                        )->count() }}
                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- Records --}}

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Environmental Monitoring Register
            </strong>

            <span class="badge bg-primary">
                {{ $records->count() }}
            </span>

        </div>


        <div class="card-body p-0">

            @if($records->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>
                                Record
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Monitoring Date
                            </th>

                            <th>
                                Location
                            </th>

                            <th>
                                Parameter
                            </th>

                            <th>
                                Value
                            </th>

                            <th>
                                Compliance
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

                        @foreach($records as $record)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.environmental.records.show',
                                            [
                                                'project' => $project,
                                                'record' => $record,
                                            ]
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >
                                        {{ $record->record_number }}
                                    </a>

                                    <div class="small text-muted">
                                        {{ $record->record_title }}
                                    </div>

                                </td>


                                <td>
                                    {{ $record->record_type }}
                                </td>


                                <td>

                                    {{ $record->monitoring_date
                                        ? $record->monitoring_date->format('d-m-Y')
                                        : '—'
                                    }}

                                </td>


                                <td>
                                    {{ $record->location ?? '—' }}
                                </td>


                                <td>
                                    {{ $record->environmental_parameter ?? '—' }}
                                </td>


                                <td>

                                    @if($record->parameter_value !== null)

                                        {{ $record->parameter_value }}

                                        @if($record->unit)
                                            {{ $record->unit }}
                                        @endif

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    @switch($record->compliance_status)

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
                                                {{ $record->compliance_status }}
                                            </span>

                                    @endswitch

                                </td>


                                <td>

                                    <span class="badge bg-secondary">
                                        {{ $record->status }}
                                    </span>

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.environmental.records.show',
                                            [
                                                'project' => $project,
                                                'record' => $record,
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
                        class="bi bi-tree"
                        style="font-size:42px;"
                    ></i>

                    <h6 class="mt-3">
                        No Environmental Records
                    </h6>

                    <p class="text-muted mb-3">
                        Start recording environmental monitoring
                        activities for this project.
                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.environmental.records.create',
                            [
                                'project' => $project,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        Add Environmental Record
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection