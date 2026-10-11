@extends('layouts.app')

@section('title', 'Material Stock Transactions')

@section('content')

<div class="container-fluid">

    {{-- ============================================================= --}}
    {{-- HEADER --}}
    {{-- ============================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Material Stock Transactions
            </h4>

            <div class="text-muted">

                {{ $project->project_number ?? $project->project_code }}

                <span class="mx-1">•</span>

                {{ $project->project_name }}

            </div>

        </div>

        <div>

            <a
                href="{{ route(
                    'admin.projects.construction.materials.stock.index',
                    $project
                ) }}"
                class="btn btn-light border"
            >
                ← Back to Stock
            </a>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- FILTER --}}
    {{-- ============================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route(
                    'admin.projects.construction.materials.stock.transactions',
                    $project
                ) }}"
            >

                <div class="row g-3 align-items-end">

                    <div class="col-md-5">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Transaction or material"
                        >

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Transaction Type
                        </label>

                        <select
                            name="transaction_type"
                            class="form-select"
                        >

                            <option value="">
                                All Types
                            </option>

                            @foreach([
                                'Receipt',
                                'Issue',
                                'Return',
                                'Consumption',
                                'Wastage',
                                'Adjustment',
                                'Transfer In',
                                'Transfer Out'
                            ] as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(
                                        request('transaction_type') === $type
                                    )
                                >
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Search
                            </button>

                            <a
                                href="{{ route(
                                    'admin.projects.construction.materials.stock.transactions',
                                    $project
                                ) }}"
                                class="btn btn-secondary"
                            >
                                Reset
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- TRANSACTIONS --}}
    {{-- ============================================================= --}}

    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-4">
                                #
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Transaction No.
                            </th>

                            <th>
                                Material
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Quantity
                            </th>

                            <th>
                                Batch
                            </th>

                            <th>
                                Reference
                            </th>

                            <th>
                                Work Order
                            </th>

                            <th class="text-end pe-4">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($transactions as $index => $transaction)

                            @php

                                $badgeClass = match (
                                    $transaction->transaction_type
                                ) {

                                    'Receipt',
                                    'Transfer In',
                                    'Return'
                                        => 'bg-success',

                                    'Issue',
                                    'Consumption',
                                    'Transfer Out'
                                        => 'bg-primary',

                                    'Wastage'
                                        => 'bg-danger',

                                    'Adjustment'
                                        => 'bg-warning text-dark',

                                    default
                                        => 'bg-secondary',
                                };

                            @endphp

                            <tr>

                                {{-- # --}}

                                <td class="ps-4">

                                    {{
                                        $transactions->firstItem()
                                        + $index
                                    }}

                                </td>


                                {{-- Date --}}

                                <td>

                                    <div class="fw-semibold">

                                        {{
                                            optional(
                                                $transaction->transaction_date
                                            )->format('d M Y')
                                        }}

                                    </div>

                                    <div class="text-muted small">

                                        {{
                                            optional(
                                                $transaction->transaction_date
                                            )->format('H:i')
                                        }}

                                    </div>

                                </td>


                                {{-- Transaction Number --}}

                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.materials.stock.transactions.show',
                                            [
                                                $project,
                                                $transaction
                                            ]
                                        ) }}"
                                        class="fw-bold text-primary text-decoration-none"
                                    >

                                        {{ $transaction->transaction_number }}

                                    </a>

                                </td>


                                {{-- Material --}}

                                <td>

                                    @if($transaction->material)

                                        <div class="fw-semibold">

                                            {{
                                                $transaction
                                                    ->material
                                                    ->material_code
                                            }}

                                        </div>

                                        <div class="text-muted small">

                                            {{
                                                $transaction
                                                    ->material
                                                    ->material_name
                                            }}

                                        </div>

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Type --}}

                                <td>

                                    <span
                                        class="badge {{ $badgeClass }}"
                                    >

                                        {{
                                            $transaction->transaction_type
                                        }}

                                    </span>

                                </td>


                                {{-- Quantity --}}

                                <td>

                                    <div class="fw-semibold">

                                        {{
                                            number_format(
                                                (float)
                                                $transaction->quantity,
                                                4
                                            )
                                        }}

                                    </div>

                                    <div class="text-muted small">

                                        {{ $transaction->unit }}

                                    </div>

                                </td>


                                {{-- Batch --}}

                                <td>

                                    {{ $transaction->batch_number ?: '—' }}

                                </td>


                                {{-- ================================================= --}}
                                {{-- REFERENCE --}}
                                {{-- ================================================= --}}

                                <td>

                                    @if(
                                        $transaction->reference_number
                                    )

                                        <div class="fw-semibold">

                                            {{
                                                $transaction
                                                    ->reference_number
                                            }}

                                        </div>

                                        <div class="text-muted small">

                                            {{
                                                $transaction
                                                    ->reference_label
                                            }}

                                        </div>

                                    @elseif(
                                        $transaction->reference_label !== '—'
                                    )

                                        <div class="fw-semibold">

                                            {{
                                                $transaction
                                                    ->reference_label
                                            }}

                                        </div>

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- WORK ORDER --}}
                                {{-- ================================================= --}}

                                <td>

                                    @if($transaction->workOrder)

                                        {{
                                            $transaction
                                                ->workOrder
                                                ->work_order_number
                                                ?? $transaction
                                                    ->workOrder
                                                    ->work_order_no
                                                ?? $transaction
                                                    ->workOrder
                                                    ->order_number
                                                ?? '#'
                                                    . $transaction
                                                    ->construction_work_order_id
                                        }}

                                    @elseif(
                                        $transaction
                                            ->construction_work_order_id
                                    )

                                        #{{ $transaction->construction_work_order_id }}

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Action --}}

                                <td class="text-end pe-4">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.materials.stock.transactions.show',
                                            [
                                                $project,
                                                $transaction
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-primary"
                                    >
                                        View
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="10"
                                    class="text-center py-5 text-muted"
                                >

                                    No stock transactions found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($transactions->hasPages())

            <div class="card-footer bg-white">

                {{ $transactions->links() }}

            </div>

        @endif

    </div>

</div>

@endsection