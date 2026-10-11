@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Land Ownership
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
                    'admin.land.lands.owners.create',
                    $land
                ) }}"
                class="btn btn-primary">

                + Add Owner

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


    @if(session('error'))

        <div class="alert alert-danger">

            {{ session('error') }}

        </div>

    @endif


    {{-- Ownership Summary --}}

    <div class="row mb-4">

        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <small class="text-muted">
                        Total Owners
                    </small>

                    <h3 class="mb-0">
                        {{ $land->owners()->count() }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <small class="text-muted">
                        Ownership Allocated
                    </small>

                    <h3 class="mb-0">

                        {{ number_format(
                            $land->owners()
                                ->sum('ownership_percentage'),
                            2
                        ) }}%

                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <small class="text-muted">
                        Remaining
                    </small>

                    <h3 class="mb-0">

                        {{ number_format(
                            100 -
                            $land->owners()
                                ->sum('ownership_percentage'),
                            2
                        ) }}%

                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- Owners Table --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Owners
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>

                            <th>
                                Owner
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Ownership
                            </th>

                            <th>
                                Title Reference
                            </th>

                            <th>
                                Start Date
                            </th>

                            <th width="180">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($owners as $owner)

                        <tr>

                            <td>

                                <strong>
                                    {{ $owner->owner_name }}
                                </strong>

                            </td>


                            <td>

                                {{ $owner->owner_type }}

                            </td>


                            <td>

                                {{ number_format(
                                    $owner->ownership_percentage ?? 0,
                                    2
                                ) }}%

                            </td>


                            <td>

                                {{ $owner->title_reference ?? '-' }}

                            </td>


                            <td>

                                {{ $owner->ownership_start_date
                                    ? $owner
                                        ->ownership_start_date
                                        ->format('d-m-Y')
                                    : '-'
                                }}

                            </td>


                            <td>

                                <a
                                    href="{{ route(
                                        'admin.land.lands.owners.show',
                                        [
                                            $land,
                                            $owner
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary">

                                    View

                                </a>


                                <a
                                    href="{{ route(
                                        'admin.land.lands.owners.edit',
                                        [
                                            $land,
                                            $owner
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

                                No ownership records found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <div class="card-footer">

            {{ $owners->links() }}

        </div>

    </div>

</div>

@endsection