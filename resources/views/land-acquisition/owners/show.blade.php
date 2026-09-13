@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                {{ $owner->owner_name }}
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
                    'admin.land.lands.owners.edit',
                    [
                        $land,
                        $owner
                    ]
                ) }}"
                class="btn btn-primary">

                Edit

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


    <div class="card">

        <div class="card-header">

            <strong>
                Ownership Details
            </strong>

        </div>


        <div class="card-body">

            <div class="row">


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Owner Type
                    </small>

                    <div class="fw-semibold">
                        {{ $owner->owner_type }}
                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Owner Name
                    </small>

                    <div class="fw-semibold">
                        {{ $owner->owner_name }}
                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Ownership Percentage
                    </small>

                    <div class="fw-semibold">

                        {{ number_format(
                            $owner->ownership_percentage ?? 0,
                            2
                        ) }}%

                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Title Reference
                    </small>

                    <div>
                        {{ $owner->title_reference ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Ownership Start
                    </small>

                    <div>

                        {{ $owner->ownership_start_date
                            ? $owner
                                ->ownership_start_date
                                ->format('d-m-Y')
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Ownership End
                    </small>

                    <div>

                        {{ $owner->ownership_end_date
                            ? $owner
                                ->ownership_end_date
                                ->format('d-m-Y')
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-12">

                    <small class="text-muted">
                        Remarks
                    </small>

                    <div>
                        {{ $owner->remarks ?? '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection