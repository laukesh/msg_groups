@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4 class="mb-1">
                Contracts
            </h4>

            <div class="text-muted">
                {{ $project->project_name }}

                @if($project->project_code)
                    · {{ $project->project_code }}
                @endif
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.dashboard',
                $project
            ) }}"
            class="btn btn-outline-secondary"
        >
            Construction Dashboard
        </a>

    </div>


    {{-- Flash Message --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Summary --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Contracts
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $summary['total_contracts'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Active Contracts
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $summary['active_contracts'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Completed
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $summary['completed_contracts'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Contract Value
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


    {{-- Contract Register --}}

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

                                <th>
                                    Contract
                                </th>

                                <th>
                                    Contractor / Supplier
                                </th>

                                <th>
                                    Procurement Package
                                </th>

                                <th>
                                    Amount
                                </th>

                                <th>
                                    Period
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

                            @foreach(
                                $contracts
                                as $contract
                            )

                                @php

                                    $statusClass =
                                        match(
                                            $contract->status
                                        ) {

                                            'Active',
                                            'In Progress' =>
                                                'bg-success',

                                            'Approved' =>
                                                'bg-primary',

                                            'Completed',
                                            'Closed' =>
                                                'bg-secondary',

                                            'Terminated' =>
                                                'bg-danger',

                                            default =>
                                                'bg-warning text-dark',
                                        };

                                    $package =
                                        $contract
                                            ->tender
                                            ?->package;

                                    $plan =
                                        $package
                                            ?->procurementPlan;

                                    $bidder =
                                        $contract
                                            ->bidder;

                                @endphp


                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    {{-- Contract --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{
                                                $contract
                                                    ->contract_number
                                            }}

                                        </div>

                                        <div class="small text-muted">

                                            {{
                                                $contract
                                                    ->contract_title
                                            }}

                                        </div>

                                    </td>


                                    {{-- Contractor --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{
                                                $bidder
                                                    ?->company_name
                                                ??
                                                $contract
                                                    ->bidder_name
                                                ??
                                                '—'
                                            }}

                                        </div>

                                        @if($bidder?->bidder_code)

                                            <div class="small text-muted">

                                                {{
                                                    $bidder
                                                        ->bidder_code
                                                }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Package --}}

                                    <td>

                                        @if($package)

                                            <div class="fw-semibold">

                                                {{
                                                    $package
                                                        ->package_number
                                                }}

                                            </div>

                                            <div class="small text-muted">

                                                {{
                                                    $package
                                                        ->package_title
                                                }}

                                            </div>

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Amount --}}

                                    <td>

                                        <strong>

                                            ${{
                                                number_format(
                                                    (float)
                                                    $contract
                                                        ->contract_amount,
                                                    2
                                                )
                                            }}

                                        </strong>

                                        <div class="small text-muted">
                                            {{ $contract->currency }}
                                        </div>

                                    </td>


                                    {{-- Period --}}

                                    <td>

                                        <div>

                                            {{
                                                $contract
                                                    ->contract_start_date
                                                    ?->format('d-m-Y')
                                                ??
                                                '—'
                                            }}

                                        </div>

                                        <div class="small text-muted">

                                            to

                                            {{
                                                $contract
                                                    ->contract_end_date
                                                    ?->format('d-m-Y')
                                                ??
                                                '—'
                                            }}

                                        </div>

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $contract->status }}
                                        </span>

                                    </td>


                                    {{-- Action --}}

                                    <td class="text-end">

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
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            View Contract
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="text-muted mb-2">
                        No contracts found for this project.
                    </div>

                    <div class="small text-muted">
                        Contracts will appear here after procurement
                        contracts are linked to this project.
                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection