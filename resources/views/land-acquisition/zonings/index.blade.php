@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Zoning Information
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
                    'admin.land.lands.zonings.create',
                    $land
                ) }}"
                class="btn btn-primary">

                + Add Zoning

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


    {{-- Messages --}}

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
                        Total Zoning Records
                    </small>

                    <h3 class="mb-0">

                        {{ $land->zonings()->count() }}

                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <small class="text-muted">
                        Current Land Area
                    </small>

                    <h3 class="mb-0">

                        {{ $land->total_area ?? '-' }}

                        {{ $land->area_unit ?? '' }}

                    </h3>

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


    {{-- Zoning Table --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Zoning Records
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>

                            <th>
                                Zoning Code
                            </th>

                            <th>
                                Zoning Type
                            </th>

                            <th>
                                Authority
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

                    @forelse($zonings as $zoning)

                        <tr>

                            <td>

                                <strong>

                                    {{ $zoning->zoning_code ?? '-' }}

                                </strong>

                            </td>


                            <td>

                                {{ $zoning->zoning_type }}

                            </td>


                            <td>

                                {{ $zoning->authority ?? '-' }}

                            </td>


                            <td>

                                {{ $zoning->effective_date
                                    ? $zoning
                                        ->effective_date
                                        ->format('d-m-Y')
                                    : '-'
                                }}

                            </td>


                            <td>

                                {{ $zoning->expiry_date
                                    ? $zoning
                                        ->expiry_date
                                        ->format('d-m-Y')
                                    : '-'
                                }}

                            </td>


                            <td>

                                <a
                                    href="{{ route(
                                        'admin.land.lands.zonings.show',
                                        [
                                            $land,
                                            $zoning
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary">

                                    View

                                </a>


                                <a
                                    href="{{ route(
                                        'admin.land.lands.zonings.edit',
                                        [
                                            $land,
                                            $zoning
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

                                No zoning records found.

                                <br>

                                <a
                                    href="{{ route(
                                        'admin.land.lands.zonings.create',
                                        $land
                                    ) }}"
                                    class="btn btn-sm btn-primary mt-2">

                                    Add First Zoning

                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <div class="card-footer">

            {{ $zonings->links() }}

        </div>

    </div>

</div>

@endsection