@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        HEADER
    ============================================================ --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Tenant Outstanding
            </h4>

            <p class="text-muted mb-0">
                View outstanding balances grouped by tenant.
            </p>

        </div>

        <a
            href="{{ route('admin.revenue.outstanding.index') }}"
            class="btn btn-light border"
        >
            ← Outstanding
        </a>

    </div>


    {{-- ============================================================
        SUMMARY CARDS
    ============================================================ --}}

    <div class="row g-3 mb-4">

        <div class="col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Tenants With Outstanding
                    </div>

                    <h4 class="mb-0">
                        {{ number_format($totalTenants) }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-6">

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

    </div>


    {{-- ============================================================
        SEARCH
    ============================================================ --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.revenue.outstanding.tenants') }}"
            >

                <div class="row g-3 align-items-end">

                    <div class="col-md-8">

                        <label class="form-label">
                            Search Tenant
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Tenant name or tenant code"
                        >

                    </div>

                    <div class="col-md-2">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Search
                        </button>

                    </div>

                    <div class="col-md-2">

                        <a
                            href="{{ route('admin.revenue.outstanding.tenants') }}"
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
                        Tenant Outstanding
                    </h5>

                    <small class="text-muted">
                        Outstanding balance by tenant
                    </small>

                </div>

                <span class="badge bg-light text-dark">
                    {{ $tenants->total() }} Tenants
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Tenant
                            </th>

                            <th class="text-center">
                                Invoices
                            </th>

                            <th class="text-end">
                                Total Invoiced
                            </th>

                            <th class="text-end">
                                Total Paid
                            </th>

                            <th class="text-end">
                                Outstanding
                            </th>

                            <th class="text-center">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($tenants as $row)

                            <tr>

                                {{-- Tenant --}}

                                <td>

                                    @if($row->tenant)

                                        <div class="fw-semibold">

                                            {{ $row->tenant->company_name }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $row->tenant->tenant_code }}

                                        </small>

                                    @else

                                        <span class="text-muted">
                                            Unknown Tenant
                                        </span>

                                    @endif

                                </td>


                                {{-- Invoice Count --}}

                                <td class="text-center">

                                    <span class="badge bg-light text-dark">

                                        {{ $row->invoice_count }}

                                    </span>

                                </td>


                                {{-- Total Invoiced --}}

                                <td class="text-end">

                                    ${{ number_format(
                                        (float) $row->total_invoiced,
                                        2
                                    ) }}

                                </td>


                                {{-- Total Paid --}}

                                <td class="text-end text-success">

                                    ${{ number_format(
                                        (float) $row->total_paid,
                                        2
                                    ) }}

                                </td>


                                {{-- Outstanding --}}

                                <td class="text-end">

                                    <span class="fw-semibold text-danger">

                                        ${{ number_format(
                                            (float) $row->total_outstanding,
                                            2
                                        ) }}

                                    </span>

                                </td>


                                {{-- Action --}}

                                <td class="text-center">

                                    @if($row->tenant)

                                        <a
                                            href="{{ route(
                                                'admin.revenue.outstanding.index',
                                                [
                                                    'search' =>
                                                        $row->tenant->tenant_code
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            View Invoices
                                        </a>

                                    @else

                                        —

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-5"
                                >

                                    <div class="text-success fs-3">
                                        ✓
                                    </div>

                                    <div class="fw-semibold">
                                        No outstanding balances
                                    </div>

                                    <small class="text-muted">
                                        No tenants currently have
                                        outstanding invoices.
                                    </small>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ========================================================
            PAGINATION
        ========================================================= --}}

        @if($tenants->hasPages())

            <div class="card-footer bg-white">

                {{ $tenants->links() }}

            </div>

        @endif

    </div>

</div>

@endsection