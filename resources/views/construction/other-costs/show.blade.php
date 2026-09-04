@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4 class="mb-1">
                Other Construction Cost
            </h4>

            <div class="text-muted">
                {{ $cost->cost_number }}
            </div>

        </div>


        <div class="d-flex gap-2">

            @if(
                in_array(
                    $cost->status,
                    [
                        'Draft',
                        'Rejected',
                    ],
                    true
                )
            )

                <a
                    href="{{ route(
                        'admin.projects.construction.other-costs.edit',
                        [
                            'project' => $project,
                            'cost' => $cost,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    Edit
                </a>

            @endif


            <a
                href="{{ route(
                    'admin.projects.construction.other-costs.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
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


    @php

        $statusClass =
            match($cost->status) {

                'Approved' =>
                    'bg-success',

                'Submitted' =>
                    'bg-primary',

                'Rejected' =>
                    'bg-danger',

                default =>
                    'bg-secondary',

            };

    @endphp


    <div class="row g-4">

        <div class="col-lg-8">

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Cost Details</strong>
                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Cost Number
                            </div>

                            <div class="fw-semibold">
                                {{ $cost->cost_number }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Cost Date
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $cost->cost_date
                                        ?->format('d-m-Y')
                                    ?? '—'
                                }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Cost Type
                            </div>

                            <div class="fw-semibold">
                                {{ $cost->cost_type }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Work Order
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $cost->workOrder
                                        ?->work_order_number
                                    ?? '—'
                                }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Amount
                            </div>

                            <div class="fs-4 fw-semibold">

                                {{
                                    number_format(
                                        (float) $cost->amount,
                                        2
                                    )
                                }}

                                {{ $cost->currency }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Status
                            </div>

                            <div class="mt-1">

                                <span
                                    class="badge {{ $statusClass }}"
                                >
                                    {{ $cost->status }}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="card mb-4">

                <div class="card-header">
                    <strong>Description</strong>
                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $cost->description
                            ?? 'No description.'
                        )
                    ) !!}

                </div>

            </div>


            <div class="card">

                <div class="card-header">
                    <strong>Remarks</strong>
                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $cost->remarks
                            ?? 'No remarks.'
                        )
                    ) !!}

                </div>

            </div>

        </div>


        <div class="col-lg-4">

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Record Information</strong>
                </div>

                <div class="card-body">

                    <div class="text-muted small">
                        Created At
                    </div>

                    <div class="fw-semibold mb-3">

                        {{
                            $cost->created_at
                                ?->format('d-m-Y H:i')
                            ?? '—'
                        }}

                    </div>


                    <div class="text-muted small">
                        Updated At
                    </div>

                    <div class="fw-semibold">

                        {{
                            $cost->updated_at
                                ?->format('d-m-Y H:i')
                            ?? '—'
                        }}

                    </div>

                </div>

            </div>


            @if(
                in_array(
                    $cost->status,
                    [
                        'Draft',
                        'Rejected',
                    ],
                    true
                )
            )

                <div class="card border-danger">

                    <div class="card-header text-danger">

                        <strong>
                            Danger Zone
                        </strong>

                    </div>


                    <div class="card-body">

                        <p class="small text-muted">
                            Only Draft or Rejected costs
                            can be deleted.
                        </p>


                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.construction.other-costs.destroy',
                                [
                                    'project' => $project,
                                    'cost' => $cost,
                                ]
                            ) }}"
                            onsubmit="
                                return confirm(
                                    'Are you sure you want to delete this cost?'
                                );
                            "
                        >

                            @csrf

                            @method('DELETE')


                            <button
                                type="submit"
                                class="btn btn-outline-danger w-100"
                            >
                                Delete Cost
                            </button>

                        </form>

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection