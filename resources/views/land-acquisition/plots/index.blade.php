@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Plot Information
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
                    'admin.land.lands.plots.create',
                    $land
                ) }}"
                class="btn btn-primary">

                + Add Plot

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


    {{-- Success Message --}}

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif


    {{-- Plot Summary --}}

    <div class="row mb-4">

        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <small class="text-muted">
                        Total Plots
                    </small>

                    <h3 class="mb-0">

                        {{ $land->plots()->count() }}

                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <small class="text-muted">
                        Land Area
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
                        Plot Area
                    </small>

                    <h3 class="mb-0">

                        {{ number_format(
                            $land->plots()
                                ->sum('plot_area'),
                            4
                        ) }}

                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- Plots Table --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Plots
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>

                            <th>
                                Plot Number
                            </th>

                            <th>
                                Survey Number
                            </th>

                            <th>
                                Parcel Number
                            </th>

                            <th>
                                Area
                            </th>

                            <th>
                                Plot Type
                            </th>

                            <th width="180">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($plots as $plot)

                        <tr>

                            <td>

                                <strong>

                                    {{ $plot->plot_number ?? '-' }}

                                </strong>

                            </td>


                            <td>

                                {{ $plot->survey_number ?? '-' }}

                            </td>


                            <td>

                                {{ $plot->parcel_number ?? '-' }}

                            </td>


                            <td>

                                {{ $plot->plot_area ?? '-' }}

                                {{ $plot->area_unit ?? '' }}

                            </td>


                            <td>

                                {{ $plot->plot_type ?? '-' }}

                            </td>


                            <td>

                                <a
                                    href="{{ route(
                                        'admin.land.lands.plots.show',
                                        [
                                            $land,
                                            $plot
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary">

                                    View

                                </a>


                                <a
                                    href="{{ route(
                                        'admin.land.lands.plots.edit',
                                        [
                                            $land,
                                            $plot
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

                                No plot information found.

                                <br>

                                <a
                                    href="{{ route(
                                        'admin.land.lands.plots.create',
                                        $land
                                    ) }}"
                                    class="btn btn-sm btn-primary mt-2">

                                    Add First Plot

                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <div class="card-footer">

            {{ $plots->links() }}

        </div>

    </div>

</div>

@endsection