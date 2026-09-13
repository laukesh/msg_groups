@extends('layouts.app')

@section('content')

<div class="container-fluid">

    @php

        /*
        |--------------------------------------------------------------------------
        | Contract Creation Availability
        |--------------------------------------------------------------------------
        |
        | Contract can only be created when there is an LOA Issued award
        | which does not already have a contract.
        |
        */

        $availableAward = $procurementTender
            ->awards()
            ->where('status', 'LOA Issued')
            ->whereDoesntHave('contracts')
            ->exists();

    @endphp


    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">

                Tender:

                <strong>
                    {{ $procurementTender->tender_number }}
                </strong>

            </div>


            <h4 class="mb-1">
                Contracts
            </h4>


            <div class="text-muted">
                {{ $procurementTender->tender_title }}
            </div>

        </div>


        <div class="d-flex gap-2">

            {{-- Back to Tender --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.show',
                    $procurementTender
                ) }}"
                class="btn btn-outline-secondary"
            >

                <i class="ri-arrow-left-line me-1"></i>

                Tender

            </a>


            {{-- Create Contract --}}
            @if($availableAward)

                <a
                    href="{{ route(
                        'admin.procurement.tenders.contracts.create',
                        $procurementTender
                    ) }}"
                    class="btn btn-primary"
                >

                    <i class="ri-add-line me-1"></i>

                    Create Contract

                </a>

            @endif

        </div>

    </div>


    {{-- =========================================================
        SUCCESS
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
        ERROR
    ========================================================== --}}

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
        CONTRACT CREATION INFORMATION
    ========================================================== --}}

    @if(!$availableAward && $contracts->isEmpty())

        <div class="alert alert-info d-flex align-items-start">

            <div class="me-2">

                <i class="ri-information-line"></i>

            </div>


            <div>

                <strong>
                    Contract cannot be created yet.
                </strong>

                <div class="small mt-1">

                    A Contract can only be created after an
                    Award has been issued with status
                    <strong>LOA Issued</strong>.

                </div>

            </div>

        </div>

    @elseif(!$availableAward && $contracts->isNotEmpty())

        <div class="alert alert-secondary d-flex align-items-start">

            <div class="me-2">

                <i class="ri-lock-line"></i>

            </div>


            <div>

                <strong>
                    Contract creation is complete.
                </strong>

                <div class="small mt-1">

                    The available LOA Issued Award already has
                    a Contract. No additional Contract can be
                    created for the same Award.

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        CONTRACT REGISTER
    ========================================================== --}}

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Contract Register
            </strong>


            <span class="badge bg-primary">

                {{ $contracts->count() }}

            </span>

        </div>


        <div class="card-body p-0">

            @if($contracts->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Contract
                            </th>

                            <th>
                                Bidder
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

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($contracts as $contract)

                            <tr>

                                {{-- =================================================
                                    NUMBER
                                ================================================== --}}

                                <td>

                                    {{ $loop->iteration }}

                                </td>


                                {{-- =================================================
                                    CONTRACT
                                ================================================== --}}

                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.procurement.tenders.contracts.show',
                                            [
                                                'procurementTender' =>
                                                    $procurementTender,

                                                'contract' =>
                                                    $contract,
                                            ]
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >

                                        {{ $contract->contract_number }}

                                    </a>


                                    <div class="small text-muted">

                                        {{ $contract->contract_title }}

                                    </div>

                                </td>


                                {{-- =================================================
                                    BIDDER
                                ================================================== --}}

                                <td>

                                    <div class="fw-semibold">

                                        {{ $contract->bidder_name }}

                                    </div>


                                    @if($contract->award)

                                        <div class="small text-muted">

                                            Award:

                                            {{ $contract->award->award_number }}

                                        </div>

                                    @endif

                                </td>


                                {{-- =================================================
                                    AMOUNT
                                ================================================== --}}

                                <td>

                                    <strong>

                                        {{
                                            number_format(
                                                (float)
                                                $contract->contract_amount,
                                                2
                                            )
                                        }}

                                    </strong>


                                    <span class="text-muted">

                                        {{ $contract->currency }}

                                    </span>

                                </td>


                                {{-- =================================================
                                    START
                                ================================================== --}}

                                <td>

                                    {{
                                        $contract->contract_start_date
                                            ?->format('d-m-Y')
                                        ?? '—'
                                    }}

                                </td>


                                {{-- =================================================
                                    END
                                ================================================== --}}

                                <td>

                                    {{
                                        $contract->contract_end_date
                                            ?->format('d-m-Y')
                                        ?? '—'
                                    }}

                                </td>


                                {{-- =================================================
                                    STATUS
                                ================================================== --}}

                                <td>

                                    @php

                                        $statusClass = match(
                                            $contract->status
                                        ) {

                                            'Draft' =>
                                                'bg-secondary',

                                            'Under Review' =>
                                                'bg-warning text-dark',

                                            'Approved' =>
                                                'bg-success',

                                            'Active' =>
                                                'bg-primary',

                                            'Completed' =>
                                                'bg-info text-dark',

                                            'Closed' =>
                                                'bg-dark',

                                            'Terminated' =>
                                                'bg-danger',

                                            default =>
                                                'bg-secondary',

                                        };

                                    @endphp


                                    <span
                                        class="badge {{ $statusClass }}"
                                    >

                                        {{ $contract->status }}

                                    </span>

                                </td>


                                {{-- =================================================
                                    ACTION
                                ================================================== --}}

                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.procurement.tenders.contracts.show',
                                            [
                                                'procurementTender' =>
                                                    $procurementTender,

                                                'contract' =>
                                                    $contract,
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

                {{-- =================================================
                    EMPTY STATE
                ================================================== --}}

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i
                            class="ri-file-list-3-line"
                            style="font-size: 42px;"
                        ></i>

                    </div>


                    <h6>
                        No Contracts Found
                    </h6>


                    @if($availableAward)

                        <p class="text-muted mb-3">

                            An LOA Issued Award is available.
                            You can now create the Contract.

                        </p>


                        <a
                            href="{{ route(
                                'admin.procurement.tenders.contracts.create',
                                $procurementTender
                            ) }}"
                            class="btn btn-primary"
                        >

                            <i class="ri-add-line me-1"></i>

                            Create Contract

                        </a>

                    @else

                        <p class="text-muted mb-0">

                            No Contract is available for this
                            Tender yet.

                            A Contract can be created only after
                            an Award reaches <strong>LOA Issued</strong>.

                        </p>

                    @endif

                </div>

            @endif

        </div>

    </div>

</div>

@endsection