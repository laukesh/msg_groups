@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4 class="mb-1">
                Other Construction Costs
            </h4>

            <div class="text-muted">
                {{ $project->project_name }}

                @if($project->project_code)
                    · {{ $project->project_code }}
                @endif
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.cost-control.index',
                    $project
                ) }}"
                class="btn btn-outline-primary"
            >
                Cost Control
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.other-costs.create',
                    $project
                ) }}"
                class="btn btn-primary"
            >
                + Add Other Cost
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


    {{-- Summary --}}

    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Entries
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $summary['total'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Approved
                    </div>

                    <div class="fs-4 fw-semibold text-success">

                        {{
                            number_format(
                                $summary['approved'],
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Draft
                    </div>

                    <div class="fs-4 fw-semibold">

                        {{
                            number_format(
                                $summary['draft'],
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Rejected
                    </div>

                    <div class="fs-4 fw-semibold text-danger">

                        {{
                            number_format(
                                $summary['rejected'],
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="card">

        <div class="card-header">

            <strong>
                Other Construction Cost Register
            </strong>

        </div>


        <div class="card-body p-0">

            @if($costs->isNotEmpty())

                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle mb-0"
                    >

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>
                                    Cost
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Work Order
                                </th>

                                <th>
                                    Amount
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($costs as $cost)

                                @php

                                    $statusClass =
                                        match(
                                            $cost->status
                                        ) {

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


                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.projects.construction.other-costs.show',
                                                [
                                                    'project' => $project,
                                                    'cost' => $cost,
                                                ]
                                            ) }}"
                                            class="fw-semibold"
                                        >
                                            {{ $cost->cost_number }}
                                        </a>

                                        @if($cost->description)

                                            <div class="small text-muted">

                                                {{
                                                    \Illuminate\Support\Str::limit(
                                                        $cost->description,
                                                        60
                                                    )
                                                }}

                                            </div>

                                        @endif

                                    </td>


                                    <td>

                                        {{
                                            $cost->cost_date
                                                ?->format('d-m-Y')
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>
                                        {{ $cost->cost_type }}
                                    </td>


                                    <td>

                                        {{
                                            $cost->workOrder
                                                ?->work_order_number
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        <strong>

                                            {{
                                                number_format(
                                                    (float)
                                                    $cost->amount,
                                                    2
                                                )
                                            }}

                                        </strong>

                                        {{ $cost->currency }}

                                    </td>


                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $cost->status }}
                                        </span>

                                    </td>


                                    <td class="text-end">

                                        <div
                                            class="
                                                d-flex
                                                justify-content-end
                                                gap-1
                                            "
                                        >

                                            <a
                                                href="{{ route(
                                                    'admin.projects.construction.other-costs.show',
                                                    [
                                                        'project' => $project,
                                                        'cost' => $cost,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>


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
                                                    class="btn btn-sm btn-outline-secondary"
                                                >
                                                    Edit
                                                </a>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="text-muted mb-3">
                        No other construction costs found.
                    </div>

                    <a
                        href="{{ route(
                            'admin.projects.construction.other-costs.create',
                            $project
                        ) }}"
                        class="btn btn-primary"
                    >
                        Add First Cost
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection