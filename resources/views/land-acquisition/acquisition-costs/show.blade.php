@extends('layouts.app')

@section('content')

<div class="container-fluid">


    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Acquisition Cost Details
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
                    'admin.land.lands.acquisition-costs.edit',
                    [
                        $land,
                        $acquisitionCost
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


    {{-- Cost Information --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Cost Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row">


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Cost Category
                    </small>

                    <div class="fw-semibold">

                        {{ $acquisitionCost->cost_category }}

                    </div>

                </div>


                <div class="col-md-8 mb-4">

                    <small class="text-muted">
                        Description
                    </small>

                    <div>

                        {{ $acquisitionCost->cost_description ?? '-' }}

                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Amount
                    </small>

                    <div class="fw-semibold">

                        {{ $acquisitionCost->currency }}

                        {{ number_format(
                            $acquisitionCost->amount,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Tax Amount
                    </small>

                    <div>

                        {{ $acquisitionCost->currency }}

                        {{ number_format(
                            $acquisitionCost->tax_amount,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Total Amount
                    </small>

                    <div class="fw-bold">

                        {{ $acquisitionCost->currency }}

                        {{ number_format(
                            $acquisitionCost->total_amount,
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Payment --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Payment Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row">


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Cost Date
                    </small>

                    <div>

                        {{ $acquisitionCost->cost_date
                            ? $acquisitionCost
                                ->cost_date
                                ->format('d-m-Y')
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Payment Status
                    </small>

                    <div>

                        @if(
                            $acquisitionCost
                                ->payment_status
                                === 'Paid'
                        )

                            <span class="badge bg-success">
                                Paid
                            </span>

                        @elseif(
                            $acquisitionCost
                                ->payment_status
                                === 'Partially Paid'
                        )

                            <span class="badge bg-warning text-dark">
                                Partially Paid
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                Pending
                            </span>

                        @endif

                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Paid Date
                    </small>

                    <div>

                        {{ $acquisitionCost->paid_date
                            ? $acquisitionCost
                                ->paid_date
                                ->format('d-m-Y')
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-6">

                    <small class="text-muted">
                        Reference Number
                    </small>

                    <div>

                        {{ $acquisitionCost->reference_number ?? '-' }}

                    </div>

                </div>

            </div>

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

            {!! nl2br(
                e(
                    $acquisitionCost->remarks ?? '-'
                )
            ) !!}

        </div>

    </div>


    {{-- Delete --}}

    <div class="card border-danger">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong class="text-danger">
                        Delete Acquisition Cost
                    </strong>

                    <div class="text-muted">
                        This action cannot be undone.
                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.land.lands.acquisition-costs.destroy',
                        [
                            $land,
                            $acquisitionCost
                        ]
                    ) }}"
                    onsubmit="return confirm('Are you sure you want to delete this acquisition cost?');">

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-danger">

                        Delete

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection