@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        HEADER
    ============================================================ --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Charge-wise Revenue
            </h4>

            <p class="text-muted mb-0">
                Revenue breakdown by charge type.
            </p>

        </div>

    </div>


    {{-- ============================================================
        SUMMARY
    ============================================================ --}}

    <div class="row g-3 mb-4">

        <div class="col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Total Revenue
                    </div>

                    <h4 class="mb-0">

                        ${{ number_format(
                            (float) $totalRevenue,
                            2
                        ) }}

                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Charge Types
                    </div>

                    <h4 class="mb-0">
                        {{ $chargeCount }}
                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        FILTERS
    ============================================================ --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route(
                    'admin.revenue.reports.charge-wise'
                ) }}"
            >

                <div class="row g-3 align-items-end">

                    {{-- From Date --}}

                    <div class="col-lg-3">

                        <label class="form-label">
                            From Date
                        </label>

                        <input
                            type="date"
                            name="from_date"
                            value="{{ request('from_date') }}"
                            class="form-control"
                        >

                    </div>


                    {{-- To Date --}}

                    <div class="col-lg-3">

                        <label class="form-label">
                            To Date
                        </label>

                        <input
                            type="date"
                            name="to_date"
                            value="{{ request('to_date') }}"
                            class="form-control"
                        >

                    </div>


                    {{-- Charge Type --}}

                    <div class="col-lg-3">

                        <label class="form-label">
                            Charge Type
                        </label>

                        <select
                            name="charge_type_id"
                            class="form-select"
                        >

                            <option value="">
                                All Charges
                            </option>

                            @foreach($chargeTypes as $chargeType)

                                <option
                                    value="{{ $chargeType->id }}"
                                    {{ (string) request('charge_type_id') ===
                                        (string) $chargeType->id
                                        ? 'selected'
                                        : ''
                                    }}
                                >

                                    {{ $chargeType->charge_name }}
                                    ({{ $chargeType->charge_code }})

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Search --}}

                    <div class="col-lg-1">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Go
                        </button>

                    </div>


                    {{-- Reset --}}

                    <div class="col-lg-2">

                        <a
                            href="{{ route(
                                'admin.revenue.reports.charge-wise'
                            ) }}"
                            class="btn btn-light border w-100"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ============================================================
        TABLE
    ============================================================ --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-1">
                        Charge Revenue
                    </h5>

                    <small class="text-muted">
                        Revenue grouped by charge type
                    </small>

                </div>

                <span class="badge bg-light text-dark">

                    {{ $chargeCount }}
                    Charges

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Charge Type
                            </th>

                            <th>
                                Code
                            </th>

                            <th class="text-center">
                                Invoices
                            </th>

                            <th class="text-end">
                                Revenue
                            </th>

                            <th class="text-end">
                                % of Revenue
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($chargeWise as $index => $row)

                            @php

                                $percentage = $totalRevenue > 0
                                    ? (
                                        (float) $row->total_revenue
                                        / (float) $totalRevenue
                                    ) * 100
                                    : 0;

                            @endphp

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>


                                <td>

                                    <div class="fw-semibold">

                                        {{ $row->charge_name }}

                                    </div>

                                </td>


                                <td>

                                    <span class="badge bg-light text-dark">

                                        {{ $row->charge_code }}

                                    </span>

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $row->invoice_count
                                    ) }}

                                </td>


                                <td class="text-end fw-semibold">

                                    ${{ number_format(
                                        (float) $row->total_revenue,
                                        2
                                    ) }}

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        $percentage,
                                        2
                                    ) }}%

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        No charge revenue found.

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>


                    @if($chargeWise->count() > 0)

                        <tfoot class="table-light">

                            <tr>

                                <th colspan="4" class="text-end">
                                    Total
                                </th>

                                <th class="text-end">

                                    ${{ number_format(
                                        (float) $totalRevenue,
                                        2
                                    ) }}

                                </th>

                                <th class="text-end">
                                    100%
                                </th>

                            </tr>

                        </tfoot>

                    @endif

                </table>

            </div>

        </div>

    </div>

</div>

@endsection