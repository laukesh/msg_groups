@extends('layouts.app')

@section('content')

<div class="container-fluid">


    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="text-muted small">

                Incident:

                <strong>
                    {{ $incident->incident_number }}
                </strong>

            </div>


            <h3 class="mb-1">
                Incident Witnesses
            </h3>


            <div class="text-muted">

                {{ $project->project_code ?? '—' }}

                -

                {{ $project->project_name ?? 'Project' }}

            </div>

        </div>


        <div class="d-flex gap-2">


            <a
                href="{{ route(
                    'admin.projects.construction.hse.incidents.show',
                    [
                        'project' => $project,
                        'incident' => $incident,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Incident
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.incidents.witnesses.create',
                    [
                        'project' => $project,
                        'incident' => $incident,
                    ]
                ) }}"
                class="btn btn-primary"
            >

                <i class="bi bi-plus-lg me-1"></i>

                Add Witness

            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    <div class="card">

        <div class="card-header">

            <strong>
                Witness Register
            </strong>

            <span class="badge bg-primary ms-2">
                {{ $witnesses->count() }}
            </span>

        </div>


        <div class="card-body p-0">

            @if($witnesses->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>
                                Witness
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Company
                            </th>

                            <th>
                                Phone
                            </th>

                            <th>
                                Statement Date
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($witnesses as $witness)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.incidents.witnesses.show',
                                            [
                                                'project' => $project,
                                                'incident' => $incident,
                                                'witness' => $witness,
                                            ]
                                        ) }}"
                                        class="fw-semibold"
                                    >

                                        {{ $witness->witness_name }}

                                    </a>


                                    @if($witness->employee_code)

                                        <div class="small text-muted">

                                            {{ $witness->employee_code }}

                                        </div>

                                    @endif

                                </td>


                                <td>
                                    {{ $witness->witness_type }}
                                </td>


                                <td>
                                    {{ $witness->company_name ?? '—' }}
                                </td>


                                <td>
                                    {{ $witness->phone ?? '—' }}
                                </td>


                                <td>

                                    {{ $witness->statement_date
                                        ? $witness->statement_date->format('d-m-Y')
                                        : '—'
                                    }}

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.incidents.witnesses.show',
                                            [
                                                'project' => $project,
                                                'incident' => $incident,
                                                'witness' => $witness,
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
                        class="bi bi-person-lines-fill"
                        style="font-size: 40px;"
                    ></i>


                    <h6 class="mt-3">
                        No Witnesses Found
                    </h6>


                    <p class="text-muted">
                        No witnesses have been added to this incident yet.
                    </p>


                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.incidents.witnesses.create',
                            [
                                'project' => $project,
                                'incident' => $incident,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-plus-lg me-1"></i>

                        Add Witness

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection