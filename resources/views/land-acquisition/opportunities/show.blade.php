@extends('layouts.app')

@section('content')

<div class="container-fluid">


    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                {{ $opportunity->opportunity_name }}
            </h3>

            <div class="text-muted">

                {{ $opportunity->opportunity_no }}

            </div>

        </div>


        <div>

            <a
                href="{{ route(
                    'admin.land.opportunities.edit',
                    $opportunity
                ) }}"
                class="btn btn-primary">

                Edit

            </a>


            <a
                href="{{ route(
                    'admin.land.opportunities.index'
                ) }}"
                class="btn btn-secondary">

                Back

            </a>

        </div>

    </div>


    <div class="row">


        <div class="col-md-8">


            {{-- Opportunity Details --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Opportunity Information
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row">


                        <div class="col-md-6 mb-3">

                            <small class="text-muted">
                                Opportunity No.
                            </small>

                            <div>
                                {{ $opportunity->opportunity_no }}
                            </div>

                        </div>


                        <div class="col-md-6 mb-3">

                            <small class="text-muted">
                                Status
                            </small>

                            <div>

                                <span class="badge bg-secondary">

                                    {{ $opportunity->status }}

                                </span>

                            </div>

                        </div>


                        <div class="col-md-6 mb-3">

                            <small class="text-muted">
                                Source
                            </small>

                            <div>
                                {{ $opportunity->source ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-6 mb-3">

                            <small class="text-muted">
                                Identified Date
                            </small>

                            <div>

                                {{ $opportunity->identified_date
                                    ? $opportunity->identified_date
                                        ->format('d-m-Y')
                                    : '-' }}

                            </div>

                        </div>


                        <div class="col-md-12 mb-3">

                            <small class="text-muted">
                                Location
                            </small>

                            <div>

                                {{ $opportunity->location_text ?? '-' }}

                            </div>

                        </div>


                        <div class="col-md-4 mb-3">

                            <small class="text-muted">
                                Estimated Area
                            </small>

                            <div>

                                {{ $opportunity->estimated_area ?? '-' }}

                                {{ $opportunity->area_unit }}

                            </div>

                        </div>


                        <div class="col-md-4 mb-3">

                            <small class="text-muted">
                                Estimated Cost
                            </small>

                            <div>

                                @if($opportunity->estimated_acquisition_cost)

                                    {{ $opportunity->currency ?? 'INR' }}

                                    {{ number_format(
                                        $opportunity->estimated_acquisition_cost,
                                        2
                                    ) }}

                                @else

                                    -

                                @endif

                            </div>

                        </div>


                        <div class="col-md-4 mb-3">

                            <small class="text-muted">
                                Linked Lands
                            </small>

                            <div>

                                {{ $opportunity->lands->count() }}

                            </div>

                        </div>


                    </div>

                </div>

            </div>


            {{-- Description --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Description
                    </strong>

                </div>

                <div class="card-body">

                    {{ $opportunity->description ?? '-' }}

                </div>

            </div>


            {{-- Remarks --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Remarks
                    </strong>

                </div>

                <div class="card-body">

                    {{ $opportunity->remarks ?? '-' }}

                </div>

            </div>


        </div>


        {{-- Linked Land --}}

        <div class="col-md-4">

            <div class="card">

                <div class="card-header">

                    <strong>
                        Linked Land Records
                    </strong>

                </div>


                <div class="card-body p-0">

                    @forelse($opportunity->lands as $land)

                        <div class="p-3 border-bottom">

                            <a
                                href="{{ route(
                                    'admin.land.lands.show',
                                    $land
                                ) }}"
                                class="fw-bold">

                                {{ $land->land_code }}

                            </a>

                            <div>

                                {{ $land->land_name }}

                            </div>

                            <small class="text-muted">

                                {{ $land->acquisition_status }}

                            </small>

                        </div>

                    @empty

                        <div class="p-3 text-muted">

                            No land has been created from this opportunity yet.

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>

@endsection