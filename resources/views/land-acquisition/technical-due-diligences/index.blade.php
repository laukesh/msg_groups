@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Technical Due Diligence
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
                    'admin.land.lands.technical-due-diligences.create',
                    $land
                ) }}"
                class="btn btn-primary"
            >
                + Add Technical Due Diligence
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

        <div class="card-header">

            <strong>
                Technical Due Diligence Records
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>

                            <th>
                                Reference
                            </th>

                            <th>
                                Assessment Date
                            </th>

                            <th>
                                Conducted By
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="180">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse(
                        $dueDiligences
                        as $dueDiligence
                    )

                        <tr>

                            <td>

                                <strong>
                                    {{ $dueDiligence->reference_no ?? '-' }}
                                </strong>

                            </td>


                            <td>

                                {{ $dueDiligence->assessment_date
                                    ? $dueDiligence
                                        ->assessment_date
                                        ->format('d-m-Y')
                                    : '-'
                                }}

                            </td>


                            <td>

                                {{ $dueDiligence->conducted_by ?? '-' }}

                            </td>


                            <td>

                                {{ $dueDiligence->status }}

                            </td>


                            <td>

                                <a
                                    href="{{ route(
                                        'admin.land.lands.technical-due-diligences.show',
                                        [
                                            $land,
                                            $dueDiligence
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    View
                                </a>


                                <a
                                    href="{{ route(
                                        'admin.land.lands.technical-due-diligences.edit',
                                        [
                                            $land,
                                            $dueDiligence
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
                                colspan="5"
                                class="text-center py-5"
                            >

                                No technical due diligence records found.

                                <br>

                                <a
                                    href="{{ route(
                                        'admin.land.lands.technical-due-diligences.create',
                                        $land
                                    ) }}"
                                    class="btn btn-sm btn-primary mt-2"
                                >
                                    Add First Review
                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <div class="card-footer">

            {{ $dueDiligences->links() }}

        </div>

    </div>

</div>

@endsection