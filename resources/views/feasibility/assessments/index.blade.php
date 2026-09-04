@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Feasibility & Investment
            </h3>

            <p class="text-muted mb-0">

                {{ $land->land_code }}
                -
                {{ $land->land_name }}

            </p>

        </div>


        <div>

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.create',
                    $land
                ) }}"
                class="btn btn-primary"
            >
                + New Feasibility Assessment
            </a>


            <a
                href="{{ route(
                    'admin.land.lands.show',
                    $land
                ) }}"
                class="btn btn-secondary"
            >
                Back to Land
            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    <div class="card">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>

                            <th>
                                Assessment No.
                            </th>

                            <th>
                                Title
                            </th>

                            <th>
                                Development Type
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse(
                        $feasibilities as $feasibility
                    )

                        <tr>

                            <td>
                                <strong>
                                    {{ $feasibility->assessment_number }}
                                </strong>
                            </td>


                            <td>
                                {{ $feasibility->title }}
                            </td>


                            <td>
                                {{ $feasibility->development_type ?? '-' }}
                            </td>


                            <td>

                                {{ $feasibility->assessment_date
                                    ? $feasibility
                                        ->assessment_date
                                        ->format('d-m-Y')
                                    : '-'
                                }}

                            </td>


                            <td>

                                <span class="badge bg-secondary">

                                    {{ $feasibility->status }}

                                </span>

                            </td>


                            <td>

                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.show',
                                        [
                                            $land,
                                            $feasibility
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    View
                                </a>


                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.edit',
                                        [
                                            $land,
                                            $feasibility
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-secondary"
                                >
                                    Edit
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center py-5"
                            >

                                No feasibility assessments found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <div class="card-footer">

            {{ $feasibilities->links() }}

        </div>

    </div>

</div>

@endsection