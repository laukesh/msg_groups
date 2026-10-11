@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ================================================================
         HEADER
    ================================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4 class="mb-1">
                Contractors
            </h4>

            <div class="text-muted">

                {{ $project->project_name ?? 'Project' }}

                @if(!empty($project->project_code))
                    · {{ $project->project_code }}
                @endif

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
                <i class="bi bi-arrow-left me-1"></i>
                Construction Dashboard
            </a>

        </div>

    </div>


    {{-- ================================================================
         SUMMARY
    ================================================================= --}}

    <div class="row g-3 mb-4">

        {{-- Total Contractors --}}

        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Contractors
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $summary['total_contractors'] }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Total Contracts --}}

        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Contracts
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $summary['total_contracts'] }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Active Contracts --}}

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


        {{-- Contract Value --}}

        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Contract Value
                    </div>

                    <div class="fs-4 fw-semibold">

                        ${{
                            number_format(
                                $summary['total_contract_value'],
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
         CONTRACTOR REGISTER
    ================================================================= --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Contractor Register
            </strong>

        </div>


        <div class="card-body p-0">

            @if($contractors->isNotEmpty())

                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle mb-0"
                    >

                        <thead class="table-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Contractor / Supplier
                                </th>

                                <th>
                                    Contact
                                </th>

                                <th>
                                    Contracts
                                </th>

                                <th>
                                    Active
                                </th>

                                <th>
                                    Contract Value
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        {{-- =================================================
                             IMPORTANT:
                             tbody contains ONLY tr elements.
                             Modals are NOT placed here.
                        ================================================== --}}

                        <tbody>

                            @foreach($contractors as $contractor)

                                <tr>

                                    {{-- # --}}

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    {{-- Contractor --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{
                                                $contractor[
                                                    'bidder_name'
                                                ]
                                            }}

                                        </div>

                                        <div class="small text-muted">

                                            Code:

                                            {{
                                                $contractor[
                                                    'bidder_code'
                                                ]
                                            }}

                                        </div>

                                    </td>


                                    {{-- Contact --}}

                                    <td>

                                        <div>

                                            {{
                                                $contractor[
                                                    'contact_person'
                                                ]
                                            }}

                                        </div>

                                        <div class="small text-muted">

                                            {{
                                                $contractor[
                                                    'email'
                                                ]
                                            }}

                                        </div>

                                        <div class="small text-muted">

                                            {{
                                                $contractor[
                                                    'phone'
                                                ]
                                            }}

                                        </div>

                                    </td>


                                    {{-- Contracts --}}

                                    <td>

                                        <span
                                            class="badge bg-secondary"
                                        >

                                            {{
                                                $contractor[
                                                    'contract_count'
                                                ]
                                            }}

                                        </span>

                                    </td>


                                    {{-- Active --}}

                                    <td>

                                        @if(
                                            $contractor[
                                                'active_contracts'
                                            ] > 0
                                        )

                                            <span
                                                class="badge bg-success"
                                            >

                                                {{
                                                    $contractor[
                                                        'active_contracts'
                                                    ]
                                                }}

                                            </span>

                                        @else

                                            <span
                                                class="text-muted"
                                            >
                                                0
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Value --}}

                                    <td>

                                        ${{
                                            number_format(
                                                $contractor[
                                                    'total_contract_value'
                                                ],
                                                2
                                            )
                                        }}

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        @if(
                                            $contractor[
                                                'active_contracts'
                                            ] > 0
                                        )

                                            <span
                                                class="badge bg-success"
                                            >
                                                Active
                                            </span>

                                        @elseif(
                                            $contractor[
                                                'completed_contracts'
                                            ]
                                            ==
                                            $contractor[
                                                'contract_count'
                                            ]
                                        )

                                            <span
                                                class="badge bg-secondary"
                                            >
                                                Completed
                                            </span>

                                        @else

                                            <span
                                                class="badge bg-secondary"
                                            >
                                                Contracted
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Action --}}

                                    <td class="text-end">

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#contractorModal{{ $loop->iteration }}"
                                        >
                                            <i class="bi bi-eye me-1"></i>
                                            View
                                        </button>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="text-muted mb-2">

                        No contractors found for this project.

                    </div>

                    <div class="small text-muted">

                        Contractors will appear here after a procurement
                        contract is awarded and linked to this project.

                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- ================================================================
         CONTRACTOR MODALS

         IMPORTANT:
         These modals are deliberately OUTSIDE the table.
         Never put .modal divs inside <tbody>.
    ================================================================= --}}

    @if($contractors->isNotEmpty())

        @foreach($contractors as $contractor)

            <div
                class="modal fade"
                id="contractorModal{{ $loop->iteration }}"
                tabindex="-1"
                aria-labelledby="contractorModalLabel{{ $loop->iteration }}"
                aria-hidden="true"
            >

                <div
                    class="modal-dialog modal-lg modal-dialog-scrollable"
                >

                    <div class="modal-content">


                        {{-- =================================================
                             MODAL HEADER
                        ================================================== --}}

                        <div class="modal-header">

                            <div>

                                <h5
                                    class="modal-title"
                                    id="contractorModalLabel{{ $loop->iteration }}"
                                >

                                    {{
                                        $contractor[
                                            'bidder_name'
                                        ]
                                    }}

                                </h5>

                                <div class="small text-muted">

                                    {{
                                        $contractor[
                                            'bidder_code'
                                        ]
                                    }}

                                </div>

                            </div>


                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            ></button>

                        </div>


                        {{-- =================================================
                             MODAL BODY
                        ================================================== --}}

                        <div class="modal-body">


                            {{-- =================================================
                                 CONTRACTOR INFORMATION
                            ================================================== --}}

                            <div class="row g-3 mb-4">


                                {{-- Contact Person --}}

                                <div class="col-md-6">

                                    <div class="text-muted small">
                                        Contact Person
                                    </div>

                                    <div class="fw-semibold">

                                        {{
                                            $contractor[
                                                'contact_person'
                                            ]
                                        }}

                                    </div>

                                </div>


                                {{-- Phone --}}

                                <div class="col-md-6">

                                    <div class="text-muted small">
                                        Phone
                                    </div>

                                    <div class="fw-semibold">

                                        {{
                                            $contractor[
                                                'phone'
                                            ]
                                        }}

                                    </div>

                                </div>


                                {{-- Email --}}

                                <div class="col-md-6">

                                    <div class="text-muted small">
                                        Email
                                    </div>

                                    <div class="fw-semibold">

                                        {{
                                            $contractor[
                                                'email'
                                            ]
                                        }}

                                    </div>

                                </div>


                                {{-- Location --}}

                                <div class="col-md-6">

                                    <div class="text-muted small">
                                        Location
                                    </div>

                                    <div class="fw-semibold">

                                        {{ $contractor['city'] }}

                                        @if(
                                            $contractor['state']
                                            !==
                                            '—'
                                        )

                                            ,
                                            {{ $contractor['state'] }}

                                        @endif

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                                 CONTRACTS
                            ================================================== --}}

                            <h6 class="mb-3">
                                Project Contracts
                            </h6>


                            @if(
                                !empty(
                                    $contractor['contracts']
                                )
                            )

                                <div class="table-responsive">

                                    <table
                                        class="table table-sm align-middle"
                                    >

                                        <thead>

                                            <tr>

                                                <th>
                                                    Contract
                                                </th>

                                                <th>
                                                    Amount
                                                </th>

                                                <th>
                                                    Start
                                                </th>

                                                <th>
                                                    End
                                                </th>

                                                <th>
                                                    Status
                                                </th>

                                            </tr>

                                        </thead>


                                        <tbody>

                                            @foreach(
                                                $contractor[
                                                    'contracts'
                                                ]
                                                as $contract
                                            )

                                                <tr>


                                                    {{-- Contract --}}

                                                    <td>

                                                        <div
                                                            class="fw-semibold"
                                                        >

                                                            {{
                                                                $contract
                                                                    ->contract_number
                                                            }}

                                                        </div>

                                                        <div
                                                            class="small text-muted"
                                                        >

                                                            {{
                                                                $contract
                                                                    ->contract_title
                                                            }}

                                                        </div>

                                                    </td>


                                                    {{-- Amount --}}

                                                    <td>

                                                        ${{
                                                            number_format(
                                                                (float)
                                                                $contract
                                                                    ->contract_amount,
                                                                2
                                                            )
                                                        }}

                                                    </td>


                                                    {{-- Start --}}

                                                    <td>

                                                        {{
                                                            $contract
                                                                ->contract_start_date
                                                                ?->format(
                                                                    'd-m-Y'
                                                                )
                                                            ??
                                                            '—'
                                                        }}

                                                    </td>


                                                    {{-- End --}}

                                                    <td>

                                                        {{
                                                            $contract
                                                                ->contract_end_date
                                                                ?->format(
                                                                    'd-m-Y'
                                                                )
                                                            ??
                                                            '—'
                                                        }}

                                                    </td>


                                                    {{-- Status --}}

                                                    <td>

                                                        @if(
                                                            $contract->status
                                                            ===
                                                            'Active'
                                                        )

                                                            <span
                                                                class="badge bg-success"
                                                            >
                                                                Active
                                                            </span>

                                                        @elseif(
                                                            $contract->status
                                                            ===
                                                            'Completed'
                                                        )

                                                            <span
                                                                class="badge bg-secondary"
                                                            >
                                                                Completed
                                                            </span>

                                                        @elseif(
                                                            $contract->status
                                                            ===
                                                            'Terminated'
                                                        )

                                                            <span
                                                                class="badge bg-danger"
                                                            >
                                                                Terminated
                                                            </span>

                                                        @else

                                                            <span
                                                                class="badge bg-secondary"
                                                            >
                                                                {{
                                                                    $contract
                                                                        ->status
                                                                }}
                                                            </span>

                                                        @endif

                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

                            @else

                                <div class="text-muted small">

                                    No contracts found for this contractor.

                                </div>

                            @endif

                        </div>


                        {{-- =================================================
                             MODAL FOOTER
                        ================================================== --}}

                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                data-bs-dismiss="modal"
                            >
                                Close
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        @endforeach

    @endif

</div>

@endsection