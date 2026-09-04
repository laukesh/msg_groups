@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Development Rights
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
                    'admin.land.lands.development-rights.create',
                    $land
                ) }}"
                class="btn btn-primary">

                + Add Development Right

            </a>


            <a
                href="{{ route(
                    'admin.land.lands.show',
                    $land
                ) }}"
                class="btn btn-secondary">

                Back to Land

            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif


    {{-- Summary --}}

    <div class="row mb-4">

        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <small class="text-muted">
                        Total Development Rights
                    </small>

                    <h3 class="mb-0">

                        {{ $land->developmentRights()->count() }}

                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <small class="text-muted">
                        Land
                    </small>

                    <h5 class="mb-0">

                        {{ $land->land_code }}

                    </h5>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <small class="text-muted">
                        Location
                    </small>

                    <h5 class="mb-0">

                        {{ $land->city ?? '-' }}

                        @if($land->state)
                            , {{ $land->state }}
                        @endif

                    </h5>

                </div>

            </div>

        </div>

    </div>


    {{-- Table --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Development Rights
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>

                            <th>
                                Right Type
                            </th>

                            <th>
                                Authority
                            </th>

                            <th>
                                Reference No.
                            </th>

                            <th>
                                Effective Date
                            </th>

                            <th>
                                Expiry Date
                            </th>

                            <th width="180">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse(
                        $developmentRights
                        as $developmentRight
                    )

                        <tr>

                            <td>

                                <strong>

                                    {{ $developmentRight->right_type }}

                                </strong>

                            </td>


                            <td>

                                {{ $developmentRight->authority ?? '-' }}

                            </td>


                            <td>

                                {{ $developmentRight->reference_number ?? '-' }}

                            </td>


                            <td>

                                {{ $developmentRight->effective_date
                                    ? $developmentRight
                                        ->effective_date
                                        ->format('d-m-Y')
                                    : '-'
                                }}

                            </td>


                            <td>

                                {{ $developmentRight->expiry_date
                                    ? $developmentRight
                                        ->expiry_date
                                        ->format('d-m-Y')
                                    : '-'
                                }}

                            </td>


                            <td>

                                <a
                                    href="{{ route(
                                        'admin.land.lands.development-rights.show',
                                        [
                                            $land,
                                            $developmentRight
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary">

                                    View

                                </a>


                                <a
                                    href="{{ route(
                                        'admin.land.lands.development-rights.edit',
                                        [
                                            $land,
                                            $developmentRight
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-secondary">

                                    Edit

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center py-5">

                                No development rights found.

                                <br>

                                <a
                                    href="{{ route(
                                        'admin.land.lands.development-rights.create',
                                        $land
                                    ) }}"
                                    class="btn btn-sm btn-primary mt-2">

                                    Add First Development Right

                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <div class="card-footer">

            {{ $developmentRights->links() }}

        </div>

    </div>

</div>

@endsection