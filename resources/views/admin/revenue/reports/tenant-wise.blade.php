@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        HEADER
    ============================================================ --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Tenant-wise Revenue
            </h4>

            <p class="text-muted mb-0">
                Revenue, collections and outstanding balances by tenant.
            </p>

        </div>

    </div>


    {{-- ============================================================
        SUMMARY
    ============================================================ --}}

    <div class="row g-3 mb-4">

        {{-- Revenue --}}

        <div class="col-xl-3 col-md-6">

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


        {{-- Collected --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Total Collected
                    </div>

                    <h4 class="mb-0 text-success">

                        ${{ number_format(
                            (float) $totalCollected,
                            2
                        ) }}

                    </h4>

                </div>

            </div>

        </div>


        {{-- Outstanding --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Total Outstanding
                    </div>

                    <h4 class="mb-0 text-danger">

                        ${{ number_format(
                            (float) $totalOutstanding,
                            2
                        ) }}

                    </h4>

                </div>

            </div>

        </div>


        {{-- Tenant Count --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Tenants
                    </div>

                    <h4 class="mb-0">

                        {{ number_format($tenantCount) }}

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
                    'admin.revenue.reports.tenant-wise'
                ) }}"
            >

                <div class="row g-3 align-items-end">

                    {{-- Search --}}

                    <div class="col-lg-4">

                        <label class="form-label">
                            Tenant
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Tenant name or tenant code"
                        >

                    </div>


                    {{-- From Date --}}

                    <div class="col-lg-2">

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

                    <div class="col-lg-2">

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


                    {{-- Search --}}

                    <div class="col-lg-2">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Search
                        </button>

                    </div>


                    {{-- Reset --}}

                    <div class="col-lg-2">

                        <a
                            href="{{ route(
                                'admin.revenue.reports.tenant-wise'
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
        TENANT TABLE
    ============================================================ --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-1">
                        Tenant Revenue
                    </h5>

                    <small class="text-muted">
                        Revenue grouped by tenant
                    </small>

                </div>

                <span class="badge bg-light text-dark">

                    {{ $tenantCount }}
                    Tenants

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
                                Tenant
                            </th>

                            <th class="text-center">
                                Invoices
                            </th>

                            <th class="text-end">
                                Revenue
                            </th>

                            <th class="text-end">
                                Collected
                            </th>

                            <th class="text-end">
                                Outstanding
                            </th>

                            <th class="text-end">
                                Collection %
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($tenantWise as $index => $row)

                            @php

                                $collectionPercentage =
                                    (float) $row->total_invoiced > 0
                                        ? (
                                            (float) $row->total_collected
                                            /
                                            (float) $row->total_invoiced
                                        ) * 100
                                        : 0;

                            @endphp

                            <tr>

                                {{-- Number --}}

                                <td>
                                    {{ $index + 1 }}
                                </td>


                                {{-- Tenant --}}

                                <td>

                                    <div class="fw-semibold">

                                        {{ $row->company_name }}

                                    </div>

                                    <small class="text-muted">

                                        {{ $row->tenant_code }}

                                    </small>

                                </td>


                                {{-- Invoices --}}

                                <td class="text-center">

                                    {{ number_format(
                                        $row->invoice_count
                                    ) }}

                                </td>


                                {{-- Revenue --}}

                                <td class="text-end fw-semibold">

                                    ${{ number_format(
                                        (float) $row->total_invoiced,
                                        2
                                    ) }}

                                </td>


                                {{-- Collected --}}

                                <td class="text-end text-success">

                                    ${{ number_format(
                                        (float) $row->total_collected,
                                        2
                                    ) }}

                                </td>


                                {{-- Outstanding --}}

                                <td class="text-end text-danger">

                                    ${{ number_format(
                                        (float) $row->total_outstanding,
                                        2
                                    ) }}

                                </td>


                                {{-- Collection Percentage --}}

                                <td class="text-end">

                                    @if($collectionPercentage >= 90)

                                        <span class="badge bg-success">

                                            {{ number_format(
                                                $collectionPercentage,
                                                1
                                            ) }}%

                                        </span>

                                    @elseif($collectionPercentage >= 50)

                                        <span class="badge bg-warning text-dark">

                                            {{ number_format(
                                                $collectionPercentage,
                                                1
                                            ) }}%

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            {{ number_format(
                                                $collectionPercentage,
                                                1
                                            ) }}%

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        No tenant revenue found.

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>


                    @if($tenantWise->count() > 0)

                        <tfoot class="table-light">

                            <tr>

                                <th colspan="3" class="text-end">
                                    Total
                                </th>

                                <th class="text-end">

                                    ${{ number_format(
                                        (float) $totalRevenue,
                                        2
                                    ) }}

                                </th>

                                <th class="text-end text-success">

                                    ${{ number_format(
                                        (float) $totalCollected,
                                        2
                                    ) }}

                                </th>

                                <th class="text-end text-danger">

                                    ${{ number_format(
                                        (float) $totalOutstanding,
                                        2
                                    ) }}

                                </th>

                                <th class="text-end">
                                    —
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