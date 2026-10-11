@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Cost Control
            </div>

            <h4>
                Approved Other Costs
            </h4>

            <div class="text-muted">
                {{ $project->project_name }}
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.cost-control.index',
                $project
            ) }}"
            class="btn btn-outline-secondary"
        >
            Back to Cost Control
        </a>

    </div>


    <div class="card">

        <div class="card-header">
            <strong>Other Cost Register</strong>
        </div>


        <div class="card-body p-0">

            @if($otherCosts->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>
                                    Cost Number
                                </th>

                                <th>
                                    Cost Date
                                </th>

                                <th>
                                    Cost Type
                                </th>

                                <th>
                                    Description
                                </th>

                                <th>
                                    Work Order
                                </th>

                                <th class="text-end">
                                    Amount
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($otherCosts as $cost)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        <strong>
                                            {{ $cost->cost_number }}
                                        </strong>

                                    </td>


                                    <td>

                                        {{
                                            $cost->cost_date
                                                ?->format('d-m-Y')
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $cost->cost_type
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $cost->description
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $cost->workOrder
                                                ?->work_order_number
                                            ?? '—'
                                        }}

                                    </td>


                                    <td class="text-end fw-semibold">

                                        {{
                                            number_format(
                                                (float)
                                                $cost->amount,
                                                2
                                            )
                                        }}

                                        {{ $cost->currency }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>


                        <tfoot class="table-light">

                            <tr>

                                <th colspan="6">
                                    Total Approved Other Costs
                                </th>

                                <th class="text-end">

                                    {{
                                        number_format(
                                            $otherCosts->sum('amount'),
                                            2
                                        )
                                    }}

                                </th>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            @else

                <div class="text-center py-5 text-muted">
                    No approved other costs found.
                </div>

            @endif

        </div>

    </div>

</div>

@endsection