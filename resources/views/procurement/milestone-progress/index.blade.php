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
                Progress Updates
            </h4>

            <div class="text-muted">
                {{ $milestone->milestone_number }}
                -
                {{ $milestone->milestone_title }}
            </div>

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


            {{-- Back to Contract --}}
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
                <i class="bi bi-file-earmark-text me-1"></i>
                Back to Contract
            </a>

            <a
                href="{{ route(
                    'admin.procurement.tenders.contracts.milestones.show',
                    [
                        'procurementTender' => $procurementTender,
                        'contract' => $contract,
                        'milestone' => $milestone,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Milestone
            </a>


            @if(
                $contract->status === 'Active' &&
                $milestone->status !== 'Completed'
            )

                <a
                    href="{{ route(
                        'admin.procurement.tenders.contracts.milestones.progress.create',
                        [
                            'procurementTender' => $procurementTender,
                            'contract' => $contract,
                            'milestone' => $milestone,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    + Add Progress
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


    <div class="card mb-4">

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Current Progress
                    </small>

                    <h4>
                        {{ $milestone->progress_percentage }}%
                    </h4>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Milestone Status
                    </small>

                    <div class="mt-2">

                        @php

                            $statusClass = match(
                                $milestone->status
                            ) {

                                'Completed' =>
                                    'bg-success',

                                'In Progress' =>
                                    'bg-primary',

                                default =>
                                    'bg-secondary',

                            };

                        @endphp

                        <span class="badge {{ $statusClass }}">
                            {{ $milestone->status }}
                        </span>

                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Milestone Amount
                    </small>

                    <h6 class="mt-2">

                        {{
                            number_format(
                                (float)
                                $milestone->milestone_amount,
                                2
                            )
                        }}

                        {{ $milestone->currency }}

                    </h6>

                </div>

            </div>

        </div>

    </div>


    <div class="card">

        <div class="card-header">

            <strong>
                Progress History
            </strong>

        </div>


        <div class="card-body p-0">

            @if($progressUpdates->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>
                                Date
                            </th>

                            <th>
                                Previous
                            </th>

                            <th>
                                Progress
                            </th>

                            <th>
                                Work Completed
                            </th>

                            <th>
                                Work Pending
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($progressUpdates as $progress)

                            <tr>

                                <td>
                                    {{ $progress->progress_date?->format('d-m-Y') }}
                                </td>


                                <td>
                                    {{ $progress->previous_progress_percentage }}%
                                </td>


                                <td>

                                    <strong>
                                        {{ $progress->progress_percentage }}%
                                    </strong>

                                </td>


                                <td>
                                    {{ $progress->work_completed ?: '—' }}
                                </td>


                                <td>
                                    {{ $progress->work_pending ?: '—' }}
                                </td>


                                <td>

                                    @php

                                        $progressClass = match(
                                            $progress->status
                                        ) {

                                            'Verified' =>
                                                'bg-success',

                                            'Rejected' =>
                                                'bg-danger',

                                            default =>
                                                'bg-warning text-dark',

                                        };

                                    @endphp

                                    <span
                                        class="badge {{ $progressClass }}"
                                    >
                                        {{ $progress->status }}
                                    </span>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5 text-muted">

                    No progress updates recorded.

                </div>

            @endif

        </div>

    </div>

</div>

@endsection