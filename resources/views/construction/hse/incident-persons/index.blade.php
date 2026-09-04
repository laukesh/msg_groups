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
                Incident Persons
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
                    'admin.projects.construction.hse.incidents.persons.create',
                    [
                        'project' => $project,
                        'incident' => $incident,
                    ]
                ) }}"
                class="btn btn-primary"
            >

                <i class="bi bi-plus-lg me-1"></i>

                Add Person

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
                Persons Involved
            </strong>

            <span class="badge bg-primary ms-2">
                {{ $persons->count() }}
            </span>

        </div>


        <div class="card-body p-0">

            @if($persons->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Person
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Company
                            </th>

                            <th>
                                Injury
                            </th>

                            <th>
                                Hospitalized
                            </th>

                            <th>
                                Lost Work Days
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($persons as $person)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.incidents.persons.show',
                                            [
                                                'project' => $project,
                                                'incident' => $incident,
                                                'person' => $person,
                                            ]
                                        ) }}"
                                        class="fw-semibold"
                                    >
                                        {{ $person->person_name }}
                                    </a>


                                    @if($person->employee_code)

                                        <div class="small text-muted">

                                            {{ $person->employee_code }}

                                        </div>

                                    @endif

                                </td>


                                <td>
                                    {{ $person->person_type }}
                                </td>


                                <td>
                                    {{ $person->company_name ?? '—' }}
                                </td>


                                <td>

                                    @if($person->injury_occurred)

                                        <span class="badge bg-danger">
                                            Yes
                                        </span>

                                    @else

                                        <span class="badge bg-success">
                                            No
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if($person->hospitalized)

                                        <span class="badge bg-danger">
                                            Yes
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            No
                                        </span>

                                    @endif

                                </td>


                                <td>
                                    {{ $person->lost_work_days ?? 0 }}
                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.incidents.persons.show',
                                            [
                                                'project' => $project,
                                                'incident' => $incident,
                                                'person' => $person,
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
                        class="bi bi-people"
                        style="font-size: 40px;"
                    ></i>


                    <h6 class="mt-3">
                        No Persons Found
                    </h6>


                    <p class="text-muted">
                        No persons have been added to this incident yet.
                    </p>


                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.incidents.persons.create',
                            [
                                'project' => $project,
                                'incident' => $incident,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-plus-lg me-1"></i>

                        Add Person

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection