@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted">
                Contract Management
            </div>

            <h4 class="mb-1">
                Advance Payment Management
            </h4>

            <div class="text-muted">

                {{ $contract->contract_code }}

                <span class="mx-1">|</span>

                {{ $contract->contract_title }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.contract-management.contracts.show',
                [$project, $contract]
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>

                Contract

            </a>


            <a href="{{ route(
                'admin.projects.contract-management.contracts.advance-payments.create',
                [$project, $contract]
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-lg me-1"></i>

                Add Transaction

            </a>

        </div>

    </div>


    {{-- Success --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Contract Term --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Contract Advance Payment Term
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Advance Payment Required
                    </div>

                    <div class="fs-5 fw-semibold">

                        @if($summary['advance_required'])

                            <span class="text-success">
                                Yes
                            </span>

                        @else

                            <span class="text-secondary">
                                No
                            </span>

                        @endif

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Contract Advance Amount
                    </div>

                    <div class="fs-5 fw-semibold text-primary">

                        {{ $contract->currency ?? 'USD' }}

                        {{ number_format(
                            $summary['contract_advance_amount'],
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Overall Status
                    </div>

                    <div class="fs-5 fw-semibold">

                        @if(
                            $summary['overall_status']
                            === 'Fully Recovered'
                        )

                            <span class="text-success">
                                Fully Recovered
                            </span>

                        @elseif(
                            $summary['overall_status']
                            === 'Partially Recovered'
                        )

                            <span class="text-warning">
                                Partially Recovered
                            </span>

                        @elseif(
                            $summary['overall_status']
                            === 'Released'
                        )

                            <span class="text-primary">
                                Released
                            </span>

                        @else

                            <span class="text-secondary">
                                Not Released
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            @if(!$summary['advance_required'])

                <div class="alert alert-warning mt-4 mb-0">

                    <i class="bi bi-exclamation-triangle me-1"></i>

                    Advance payment is currently marked as
                    <strong>not required</strong> for this contract.

                </div>

            @endif

        </div>

    </div>


    {{-- Summary Cards --}}

    <div class="row g-3 mb-4">


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Released
                    </div>

                    <div class="fs-4 fw-semibold text-primary">

                        {{ $contract->currency ?? 'USD' }}

                        {{ number_format(
                            $summary['total_released'],
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Recovered
                    </div>

                    <div class="fs-4 fw-semibold text-success">

                        {{ $contract->currency ?? 'USD' }}

                        {{ number_format(
                            $summary['total_recovered'],
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Adjustments / Refunds
                    </div>

                    <div class="fs-4 fw-semibold text-warning">

                        {{ $contract->currency ?? 'USD' }}

                        {{ number_format(
                            $summary['total_adjustments']
                            +
                            $summary['total_refunds'],
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Outstanding Advance
                    </div>

                    <div class="fs-4 fw-semibold text-danger">

                        {{ $contract->currency ?? 'USD' }}

                        {{ number_format(
                            $summary['outstanding'],
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Transaction Counts --}}

    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card border-0 bg-light">

                <div class="card-body">

                    <div class="text-muted small">
                        Advance Releases
                    </div>

                    <div class="fs-4 fw-semibold">
                        {{ $summary['released_count'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 bg-light">

                <div class="card-body">

                    <div class="text-muted small">
                        Recovery Transactions
                    </div>

                    <div class="fs-4 fw-semibold">
                        {{ $summary['recovery_count'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 bg-light">

                <div class="card-body">

                    <div class="text-muted small">
                        Upcoming Recoveries
                    </div>

                    <div class="fs-4 fw-semibold text-info">
                        {{ $summary['upcoming_recoveries'] }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Register --}}

    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between">

                <h5 class="mb-0">
                    Advance Payment Ledger
                </h5>

                <span class="text-muted small">

                    {{ $summary['total_transactions'] }}
                    transaction(s)

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($transactions->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-3">
                                    Transaction No.
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Reference
                                </th>

                                <th>
                                    Advance
                                </th>

                                <th>
                                    Recovery
                                </th>

                                <th>
                                    Balance
                                </th>

                                <th>
                                    Recovery Date
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

                            @foreach($transactions as $transaction)

                                @php

                                    $statusClass = match(
                                        $transaction->status
                                    ) {

                                        'Fully Recovered'
                                            => 'success',

                                        'Partially Recovered'
                                            => 'warning',

                                        'Released'
                                            => 'primary',

                                        'Not Released'
                                            => 'secondary',

                                        default
                                            => 'secondary',

                                    };

                                @endphp


                                <tr>

                                    <td class="px-3">

                                        <strong>
                                            {{ $transaction->advance_number }}
                                        </strong>

                                    </td>


                                    <td>

                                        {{
                                            $transaction
                                                ->transaction_date
                                                ?->format('d M Y')
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        @if(
                                            $transaction->transaction_type
                                            === 'Advance Released'
                                        )

                                            <span class="badge bg-primary">
                                                Advance Released
                                            </span>

                                        @elseif(
                                            $transaction->transaction_type
                                            === 'Advance Recovery'
                                        )

                                            <span class="badge bg-success">
                                                Advance Recovery
                                            </span>

                                        @elseif(
                                            $transaction->transaction_type
                                            === 'Adjustment'
                                        )

                                            <span class="badge bg-warning text-dark">
                                                Adjustment
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                Refund
                                            </span>

                                        @endif

                                    </td>


                                    <td>
                                        {{ $transaction->reference_number ?? '—' }}
                                    </td>


                                    <td>

                                        @if(
                                            (float)
                                            $transaction->advance_amount > 0
                                        )

                                            {{ $transaction->currency }}

                                            {{ number_format(
                                                $transaction->advance_amount,
                                                2
                                            ) }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        @if(
                                            (float)
                                            $transaction->recovered_amount > 0
                                        )

                                            {{ $transaction->currency }}

                                            {{ number_format(
                                                $transaction->recovered_amount,
                                                2
                                            ) }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        <strong>

                                            {{ $transaction->currency }}

                                            {{ number_format(
                                                $transaction->balance_amount,
                                                2
                                            ) }}

                                        </strong>

                                    </td>


                                    <td>

                                        @if(
                                            $transaction->recovery_date
                                        )

                                            {{
                                                $transaction
                                                    ->recovery_date
                                                    ->format('d M Y')
                                            }}

                                        @elseif(
                                            $transaction->expected_recovery_date
                                        )

                                            <span class="text-muted">

                                                Expected:

                                                {{
                                                    $transaction
                                                        ->expected_recovery_date
                                                        ->format('d M Y')
                                                }}

                                            </span>

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        <span class="badge bg-{{ $statusClass }}">

                                            {{ $transaction->status }}

                                        </span>

                                    </td>


                                    <td class="text-end">

                                        <a href="{{ route(
                                            'admin.projects.contract-management.contracts.advance-payments.edit',
                                            [$project, $contract, $transaction]
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary">

                                            <i class="fa fa-edit"></i>

                                        </a>


                                        <form method="POST"
                                              action="{{ route(
                                                  'admin.projects.contract-management.contracts.advance-payments.destroy',
                                                  [$project, $contract, $transaction]
                                              ) }}"
                                              class="d-inline">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Delete this transaction?');">

                                                <i class="fa fa-trash"></i>

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <i class="bi bi-wallet2 fs-1 text-muted"></i>

                    <h5 class="mt-3">
                        No Advance Payment Transactions
                    </h5>

                    <p class="text-muted">
                        No advance payment has been released or recovered
                        against this contract.
                    </p>

                    <a href="{{ route(
                        'admin.projects.contract-management.contracts.advance-payments.create',
                        [$project, $contract]
                    ) }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-lg me-1"></i>

                        Add First Transaction

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection