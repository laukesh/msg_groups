@extends('layouts.app')

@section('title', 'Payment Certificates')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Payment Certificates
            </h4>

            <div class="text-muted">
                {{ $project->project_number }}
                -
                {{ $project->project_name }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.dashboard',
                $project
            ) }}" class="btn btn-outline-secondary">

                <i class="bi bi-speedometer2"></i>
                Construction Dashboard

            </a>


            <a href="{{ route(
                'admin.projects.construction.payment-certificates.create',
                $project
            ) }}" class="btn btn-primary">

                <i class="bi bi-plus-lg"></i>
                New Certificate

            </a>

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


    <div class="row g-3 mb-4">

        <div class="col-md-2">

            <div class="card shadow-sm h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Total
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $summary['total'] }}
                    </div>

                </div>
            </div>

        </div>


        <div class="col-md-2">

            <div class="card shadow-sm h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Draft
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $summary['draft'] }}
                    </div>

                </div>
            </div>

        </div>


        <div class="col-md-2">

            <div class="card shadow-sm h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        In Review
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $summary['submitted'] }}
                    </div>

                </div>
            </div>

        </div>


        <div class="col-md-2">

            <div class="card shadow-sm h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Approved
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $summary['approved'] }}
                    </div>

                </div>
            </div>

        </div>


        <div class="col-md-2">

            <div class="card shadow-sm h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Paid
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $summary['paid'] }}
                    </div>

                </div>
            </div>

        </div>


        <div class="col-md-2">

            <div class="card shadow-sm h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Certified
                    </div>

                    <div class="fs-6 fw-bold">
                        $ {{ number_format(
                            $summary['certified_amount'],
                            2
                        ) }}
                    </div>

                </div>
            </div>

        </div>

    </div>


    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3">

                    <div class="col-md-3">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Certificate / Work Order / Contract"
                            value="{{ request('search') }}">

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status" class="form-select">

                            <option value="">
                                All Status
                            </option>

                            @foreach([
                                'Draft',
                                'Submitted',
                                'Under Review',
                                'Approved',
                                'Rejected',
                                'Paid',
                                'Cancelled'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ request('status') == $status ? 'selected' : '' }}>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            From
                        </label>

                        <input
                            type="date"
                            name="date_from"
                            class="form-control"
                            value="{{ request('date_from') }}">

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            To
                        </label>

                        <input
                            type="date"
                            name="date_to"
                            class="form-control"
                            value="{{ request('date_to') }}">

                    </div>


                    <div class="col-md-3 d-flex align-items-end gap-2">

                        <button class="btn btn-primary">
                            <i class="bi bi-search"></i>
                            Filter
                        </button>

                        <a href="{{ route(
                            'admin.projects.construction.payment-certificates.index',
                            $project
                        ) }}"
                           class="btn btn-outline-secondary">

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <strong>
                Payment Certificate Register
            </strong>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>Certificate</th>
                        <th>Date</th>
                        <th>Work Order</th>
                        <th>Contract</th>
                        <th>Current Certified</th>
                        <th>Net Certified</th>
                        <th>Status</th>
                        <th width="100">Action</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($certificates as $certificate)

                        <tr>

                            <td>
                                <strong>
                                    {{ $certificate->certificate_number }}
                                </strong>
                            </td>


                            <td>
                                {{ optional(
                                    $certificate->certificate_date
                                )->format('d M Y') }}
                            </td>


                            <td>

                                @if($certificate->workOrder)

                                    {{ $certificate->workOrder->work_order_number }}

                                    <div class="small text-muted">
                                        {{ $certificate->workOrder->work_order_title }}
                                    </div>

                                @else
                                    —
                                @endif

                            </td>


                            <td>

                                @if($certificate->procurementContract)

                                    <strong>
                                        {{ $certificate->procurementContract->contract_number }}
                                    </strong>

                                    @if($certificate->procurementContract->bidder_name)

                                        <div class="small text-muted">
                                            {{ $certificate->procurementContract->bidder_name }}
                                        </div>

                                    @endif

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            <td>
                                $ {{ number_format(
                                    $certificate->current_certified_amount,
                                    2
                                ) }}
                            </td>


                            <td class="fw-semibold">

                                $ {{ number_format(
                                    $certificate->net_certified_amount,
                                    2
                                ) }}

                            </td>


                            <td>

                                @php

                                    $badge = match(
                                        $certificate->status
                                    ) {
                                        'Draft' => 'secondary',
                                        'Submitted' => 'info',
                                        'Under Review' => 'warning',
                                        'Approved' => 'primary',
                                        'Rejected' => 'danger',
                                        'Paid' => 'success',
                                        'Cancelled' => 'dark',
                                        default => 'secondary',
                                    };

                                @endphp

                                <span class="badge bg-{{ $badge }}">
                                    {{ $certificate->status }}
                                </span>

                            </td>


                            <td>

                                <a href="{{ route(
                                    'admin.projects.construction.payment-certificates.show',
                                    [
                                        'project' => $project,
                                        'payment_certificate' => $certificate
                                    ]
                                ) }}"
                                   class="btn btn-sm btn-outline-primary">

                                    View

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center text-muted py-5">

                                No payment certificates found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($certificates->hasPages())

            <div class="card-footer bg-white">

                {{ $certificates->links() }}

            </div>

        @endif

    </div>

</div>

@endsection