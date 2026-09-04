@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4>
                Construction Variations
            </h4>

            <div class="text-muted">
                {{ $project->project_name }}
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
                    'admin.projects.construction.variations.create',
                    $project
                ) }}"
                class="btn btn-primary"
            >
                + Create Variation
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
                        Total Variations
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
                        Submitted
                    </div>

                    <div class="fs-4 fw-semibold">

                        {{
                            number_format(
                                $summary['submitted'],
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
                        Draft / Rejected
                    </div>

                    <div class="fs-4 fw-semibold">

                        {{
                            number_format(
                                $summary['draft']
                                +
                                $summary['rejected'],
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
                Variation Register
            </strong>

        </div>


        <div class="card-body p-0">

            @if($variations->isNotEmpty())

                <div class="table-responsive">

                    <table
                        class="
                            table
                            table-hover
                            align-middle
                            mb-0
                        "
                    >

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>
                                    Variation
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Contract
                                </th>

                                <th>
                                    Work Order
                                </th>

                                <th class="text-end">
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

                            @foreach($variations as $variation)

                                @php

                                    $statusClass =
                                        match(
                                            $variation->status
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
                                                'admin.projects.construction.variations.show',
                                                [
                                                    'project' => $project,
                                                    'variation' => $variation,
                                                ]
                                            ) }}"
                                            class="fw-semibold"
                                        >
                                            {{ $variation->variation_number }}
                                        </a>

                                        <div class="small text-muted">
                                            {{ $variation->title }}
                                        </div>

                                    </td>


                                    <td>

                                        {{
                                            $variation->variation_date
                                                ?->format('d-m-Y')
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>
                                        {{ $variation->variation_type }}
                                    </td>


                                    <td>

                                        {{
                                            $variation->contract
                                                ?->contract_number
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $variation->workOrder
                                                ?->work_order_number
                                            ?? '—'
                                        }}

                                    </td>


                                    <td class="text-end">

                                        <strong>

                                            {{
                                                number_format(
                                                    (float)
                                                    $variation->amount,
                                                    2
                                                )
                                            }}

                                        </strong>

                                        {{ $variation->currency }}

                                    </td>


                                    <td>

                                        <span
                                            class="
                                                badge
                                                {{ $statusClass }}
                                            "
                                        >
                                            {{ $variation->status }}
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
                                                    'admin.projects.construction.variations.show',
                                                    [
                                                        'project' => $project,
                                                        'variation' => $variation,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>


                                            @if(
                                                in_array(
                                                    $variation->status,
                                                    [
                                                        'Draft',
                                                        'Rejected',
                                                    ],
                                                    true
                                                )
                                            )

                                                <a
                                                    href="{{ route(
                                                        'admin.projects.construction.variations.edit',
                                                        [
                                                            'project' => $project,
                                                            'variation' => $variation,
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

                <div class="text-center py-5 text-muted">

                    No construction variations found.

                    <div class="mt-3">

                        <a
                            href="{{ route(
                                'admin.projects.construction.variations.create',
                                $project
                            ) }}"
                            class="btn btn-primary"
                        >
                            Create First Variation
                        </a>

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection