@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between mb-4">

        <div>

            <div class="text-muted small">
                Cost Control
            </div>

            <h4>
                Approved Variations
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

            <strong>
                Approved Variation Register
            </strong>

        </div>


        <div class="card-body p-0">

            @if($variations->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>
                                <th>Variation</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Contract</th>
                                <th>Work Order</th>
                                <th class="text-end">
                                    Amount
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($variations as $variation)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.projects.construction.variations.show',
                                                [
                                                    'project' =>
                                                        $project,

                                                    'variation' =>
                                                        $variation,
                                                ]
                                            ) }}"
                                            class="fw-semibold"
                                        >

                                            {{
                                                $variation
                                                    ->variation_number
                                            }}

                                        </a>

                                        <div class="small text-muted">
                                            {{ $variation->title }}
                                        </div>

                                    </td>


                                    <td>

                                        {{
                                            $variation
                                                ->variation_date
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


                                    <td class="text-end fw-semibold">

                                        {{
                                            number_format(
                                                (float)
                                                $variation->amount,
                                                2
                                            )
                                        }}

                                        {{ $variation->currency }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>


                        <tfoot class="table-light">

                            <tr>

                                <th colspan="6">
                                    Total Approved Variations
                                </th>

                                <th class="text-end">

                                    {{
                                        number_format(
                                            $variations->sum('amount'),
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
                    No approved variations found.
                </div>

            @endif

        </div>

    </div>

</div>

@endsection