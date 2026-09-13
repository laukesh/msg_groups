@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Contract:
                {{ $contract->contract_number }}
            </div>

            <h4>
                Contract Milestones
            </h4>

        </div>


        <div class="d-flex gap-2">

            {{-- Back to Tender --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.show',
                    $procurementTender
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Tender
            </a>

            <a
                href="{{ route(
                    'admin.procurement.tenders.contracts.show',
                    [
                        'procurementTender' => $procurementTender,
                        'contract' => $contract,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Contract
            </a>


            @if($contract->status === 'Active')

                <a
                    href="{{ route(
                        'admin.procurement.tenders.contracts.milestones.create',
                        [
                            'procurementTender' => $procurementTender,
                            'contract' => $contract,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    + Add Milestone
                </a>

            @endif

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


    <div class="card">

        <div class="card-header">

            <strong>
                Milestone Register
            </strong>

        </div>


        <div class="card-body p-0">

            @if($milestones->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Milestone
                            </th>

                            <th>
                                Planned Dates
                            </th>

                            <th>
                                Amount
                            </th>

                            <th>
                                Progress
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

                        @foreach($milestones as $milestone)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.procurement.tenders.contracts.milestones.show',
                                            [
                                                'procurementTender' => $procurementTender,
                                                'contract' => $contract,
                                                'milestone' => $milestone,
                                            ]
                                        ) }}"
                                        class="fw-semibold"
                                    >
                                        {{ $milestone->milestone_number }}
                                    </a>

                                    <div class="small text-muted">
                                        {{ $milestone->milestone_title }}
                                    </div>

                                </td>


                                <td>

                                    {{
                                        $milestone->planned_start_date
                                            ?->format('d-m-Y')
                                        ?? '—'
                                    }}

                                    -

                                    {{
                                        $milestone->planned_end_date
                                            ?->format('d-m-Y')
                                        ?? '—'
                                    }}

                                </td>


                                <td>

                                    {{
                                        number_format(
                                            (float)
                                            $milestone->milestone_amount,
                                            2
                                        )
                                    }}

                                    {{ $milestone->currency }}

                                </td>


                                <td style="min-width:160px">

                                    <div class="progress">

                                        <div
                                            class="progress-bar"
                                            role="progressbar"
                                            style="width: {{ $milestone->progress_percentage }}%"
                                        >
                                            {{ $milestone->progress_percentage }}%
                                        </div>

                                    </div>

                                </td>


                                <td>

                                    @php

                                        $class = match(
                                            $milestone->status
                                        ) {

                                            'Completed' =>
                                                'bg-success',

                                            'In Progress' =>
                                                'bg-primary',

                                            'Delayed' =>
                                                'bg-danger',

                                            default =>
                                                'bg-secondary',

                                        };

                                    @endphp


                                    <span class="badge {{ $class }}">
                                        {{ $milestone->status }}
                                    </span>

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.procurement.tenders.contracts.milestones.show',
                                            [
                                                'procurementTender' => $procurementTender,
                                                'contract' => $contract,
                                                'milestone' => $milestone,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="text-muted mb-3">
                        No milestones found.
                    </div>


                    @if($contract->status === 'Active')

                        <a
                            href="{{ route(
                                'admin.procurement.tenders.contracts.milestones.create',
                                [
                                    'procurementTender' => $procurementTender,
                                    'contract' => $contract,
                                ]
                            ) }}"
                            class="btn btn-primary"
                        >
                            + Add First Milestone
                        </a>

                    @endif

                </div>

            @endif

        </div>

    </div>

</div>

@endsection