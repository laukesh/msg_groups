@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between mb-4">

        <div>

            <div class="text-muted small">
                Cost Control
            </div>

            <h4>
                Project Contracts
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
                Contract Register
            </strong>

        </div>


        <div class="card-body p-0">

            @if($contracts->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>
                                <th>Contract</th>
                                <th>Title</th>
                                <th>Contractor</th>
                                <th>Status</th>
                                <th class="text-end">
                                    Contract Amount
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($contracts as $contract)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.procurement.tenders.contracts.show',
                                                [
                                                    'procurementTender' =>
                                                        $contract->tender,

                                                    'contract' =>
                                                        $contract,
                                                ]
                                            ) }}"
                                            class="fw-semibold"
                                        >

                                            {{
                                                $contract->contract_number
                                            }}

                                        </a>

                                    </td>


                                    <td>
                                        {{ $contract->contract_title }}
                                    </td>


                                    <td>
                                        {{ $contract->bidder_name ?? '—' }}
                                    </td>


                                    <td>

                                        <span class="badge bg-secondary">
                                            {{ $contract->status }}
                                        </span>

                                    </td>


                                    <td class="text-end">

                                        {{
                                            number_format(
                                                (float)
                                                $contract->contract_amount,
                                                2
                                            )
                                        }}

                                        {{ $contract->currency }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5 text-muted">
                    No contracts found.
                </div>

            @endif

        </div>

    </div>

</div>

@endsection