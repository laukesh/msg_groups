@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4 class="mb-1">
                Work Orders
            </h4>

            <div class="text-muted">
                {{ $project->project_name }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.dashboard',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                Construction Dashboard
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.work-orders.create',
                    $project
                ) }}"
                class="btn btn-primary"
            >
                + Create Work Order
            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Summary --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Work Orders
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $summary['total'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted small">
                        Active
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $summary['active'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted small">
                        Completed
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $summary['completed'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted small">
                        Work Order Value
                    </div>

                    <div class="fs-4 fw-semibold">

                        ${{
                            number_format(
                                $summary['total_value'],
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Register --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Work Order Register
            </strong>

        </div>


        <div class="card-body p-0">

            @if($workOrders->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>
                                    Work Order
                                </th>

                                <th>
                                    Contract
                                </th>

                                <th>
                                    Contractor
                                </th>

                                <th>
                                    Value
                                </th>

                                <th>
                                    Period
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Priority
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $workOrders
                                as $workOrder
                            )

                                @php

                                    $statusClass =
                                        match(
                                            $workOrder->status
                                        ) {

                                            'In Progress' =>
                                                'bg-success',

                                            'Issued' =>
                                                'bg-primary',

                                            'Completed' =>
                                                'bg-secondary',

                                            'Cancelled' =>
                                                'bg-danger',

                                            default =>
                                                'bg-warning text-dark',
                                        };

                                @endphp


                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        <div class="fw-semibold">

                                            {{
                                                $workOrder
                                                    ->work_order_number
                                            }}

                                        </div>

                                        <div class="small text-muted">

                                            {{
                                                $workOrder
                                                    ->work_order_title
                                            }}

                                        </div>

                                    </td>


                                    <td>

                                        {{
                                            $workOrder
                                                ->contract
                                                ?->contract_number
                                            ??
                                            '—'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $workOrder
                                                ->contract
                                                ?->bidder
                                                ?->company_name
                                            ??
                                            $workOrder
                                                ->contract
                                                ?->bidder_name
                                            ??
                                            '—'
                                        }}

                                    </td>


                                    <td>

                                        ${{
                                            number_format(
                                                (float)
                                                $workOrder
                                                    ->work_order_value,
                                                2
                                            )
                                        }}

                                        <div class="small text-muted">
                                            {{ $workOrder->currency }}
                                        </div>

                                    </td>


                                    <td>

                                        {{
                                            $workOrder
                                                ->start_date
                                                ?->format('d-m-Y')
                                            ??
                                            '—'
                                        }}

                                        <div class="small text-muted">

                                            {{
                                                $workOrder
                                                    ->expected_completion_date
                                                    ?->format('d-m-Y')
                                                ??
                                                '—'
                                            }}

                                        </div>

                                    </td>


                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $workOrder->status }}
                                        </span>

                                    </td>


                                    <td>

                                        {{ $workOrder->priority }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="text-muted mb-3">
                        No work orders found.
                    </div>

                    <a
                        href="{{ route(
                            'admin.projects.construction.work-orders.create',
                            $project
                        ) }}"
                        class="btn btn-primary"
                    >
                        Create First Work Order
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection