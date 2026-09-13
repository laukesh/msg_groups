@extends('layouts.app')

@section('title', 'Payment Certificate')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                {{ $payment_certificate->certificate_number }}
            </h4>

            <div class="text-muted">

                {{ $project->project_number }}
                -
                {{ $project->project_name }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.payment-certificates.index',
                $project
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>


            @if(in_array(
                $payment_certificate->status,
                ['Draft', 'Rejected']
            ))

                <a href="{{ route(
                    'admin.projects.construction.payment-certificates.edit',
                    [
                        'project' => $project,
                        'payment_certificate' => $payment_certificate
                    ]
                ) }}"
                   class="btn btn-outline-primary">

                    <i class="bi bi-pencil"></i>
                    Edit

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


    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <span class="text-muted">
                        Status
                    </span>

                    <div class="mt-1">

                        @php

                            $badge = match(
                                $payment_certificate->status
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

                        <span class="badge bg-{{ $badge }} fs-6">

                            {{ $payment_certificate->status }}

                        </span>

                    </div>

                </div>


                <div class="d-flex gap-2">

                    @if($payment_certificate->status === 'Draft')

                        <form method="POST"
                              action="{{ route(
                                'admin.projects.construction.payment-certificates.submit',
                                [
                                    'project' => $project,
                                    'payment_certificate' => $payment_certificate
                                ]
                              ) }}">

                            @csrf

                            <button
                                class="btn btn-primary"
                                onclick="return confirm('Submit this certificate for review?')">

                                <i class="bi bi-send"></i>
                                Submit

                            </button>

                        </form>

                    @endif


                    @if($payment_certificate->status === 'Submitted')

                        <form method="POST"
                              action="{{ route(
                                'admin.projects.construction.payment-certificates.review',
                                [
                                    'project' => $project,
                                    'payment_certificate' => $payment_certificate
                                ]
                              ) }}">

                            @csrf

                            <button class="btn btn-warning">

                                <i class="bi bi-search"></i>
                                Start Review

                            </button>

                        </form>

                    @endif


                    @if(in_array(
                        $payment_certificate->status,
                        ['Submitted', 'Under Review']
                    ))

                        <form method="POST"
                              action="{{ route(
                                'admin.projects.construction.payment-certificates.approve',
                                [
                                    'project' => $project,
                                    'payment_certificate' => $payment_certificate
                                ]
                              ) }}">

                            @csrf

                            <button
                                class="btn btn-success"
                                onclick="return confirm('Approve this payment certificate?')">

                                <i class="bi bi-check-lg"></i>
                                Approve

                            </button>

                        </form>


                        <button
                            class="btn btn-outline-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#rejectModal">

                            Reject

                        </button>

                    @endif


                    @if($payment_certificate->status === 'Approved')

                        <form method="POST"
                              action="{{ route(
                                'admin.projects.construction.payment-certificates.paid',
                                [
                                    'project' => $project,
                                    'payment_certificate' => $payment_certificate
                                ]
                              ) }}">

                            @csrf

                            <button
                                class="btn btn-success"
                                onclick="return confirm('Mark this certificate as Paid?')">

                                <i class="bi bi-cash-stack"></i>
                                Mark Paid

                            </button>

                        </form>

                    @endif

                </div>

            </div>

        </div>

    </div>


    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Gross Amount
                    </div>

                    <div class="fs-4 fw-bold">

                        $ {{ number_format(
                            $payment_certificate->gross_amount,
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Previous Certified
                    </div>

                    <div class="fs-4 fw-bold">

                        $ {{ number_format(
                            $payment_certificate->previous_certified_amount,
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Current Certified
                    </div>

                    <div class="fs-4 fw-bold">

                        $ {{ number_format(
                            $payment_certificate->current_certified_amount,
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Net Certified
                    </div>

                    <div class="fs-4 fw-bold text-success">

                        $ {{ number_format(
                            $payment_certificate->net_certified_amount,
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="row">

        <div class="col-lg-8">

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-white">
                    <strong>Certificate Details</strong>
                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Certificate Date
                            </div>

                            <strong>
                                {{ optional(
                                    $payment_certificate->certificate_date
                                )->format('d M Y') }}
                            </strong>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Certification Period
                            </div>

                            <strong>

                                {{ optional(
                                    $payment_certificate->period_from
                                )->format('d M Y') ?? '—' }}

                                -

                                {{ optional(
                                    $payment_certificate->period_to
                                )->format('d M Y') ?? '—' }}

                            </strong>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Work Order
                            </div>

                            @if($payment_certificate->workOrder)

                                <strong>
                                    {{ $payment_certificate->workOrder->work_order_number }}
                                </strong>

                                <div class="small text-muted">
                                    {{ $payment_certificate->workOrder->work_order_title }}
                                </div>

                            @else
                                —
                            @endif

                        </div>


                        <div class="col-md-6">

						    <div class="text-muted small">
						        Procurement Contract
						    </div>

						    @if($payment_certificate->procurementContract)

						        <strong>
						            {{ $payment_certificate->procurementContract->contract_number }}
						        </strong>

						        @if($payment_certificate->procurementContract->bidder_name)

						            <div class="small text-muted">
						                {{ $payment_certificate->procurementContract->bidder_name }}
						            </div>

						        @endif

						    @else

						        <span class="text-muted">
						            —
						        </span>

						    @endif

						</div>

                    </div>

                </div>

            </div>


            <div class="card shadow-sm mb-4">

                <div class="card-header bg-white">
                    <strong>Financial Calculation</strong>
                </div>

                <div class="table-responsive">

                    <table class="table mb-0">

                        <tbody>

                            <tr>
                                <td>Current Certified Amount</td>
                                <td class="text-end">
                                    $ {{ number_format(
                                        $payment_certificate->current_certified_amount,
                                        2
                                    ) }}
                                </td>
                            </tr>

                            <tr>
                                <td>Retention</td>
                                <td class="text-end text-danger">
                                    - $ {{ number_format(
                                        $payment_certificate->retention_amount,
                                        2
                                    ) }}
                                </td>
                            </tr>

                            <tr>
                                <td>Advance Recovery</td>
                                <td class="text-end text-danger">
                                    - $ {{ number_format(
                                        $payment_certificate->advance_recovery,
                                        2
                                    ) }}
                                </td>
                            </tr>

                            <tr>
                                <td>Other Deductions</td>
                                <td class="text-end text-danger">
                                    - $ {{ number_format(
                                        $payment_certificate->other_deductions,
                                        2
                                    ) }}
                                </td>
                            </tr>

                            <tr class="table-light">

                                <th>
                                    Net Certified Amount
                                </th>

                                <th class="text-end text-success">

                                    $ {{ number_format(
                                        $payment_certificate->net_certified_amount,
                                        2
                                    ) }}

                                </th>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <div class="col-lg-4">

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-white">
                    <strong>Approval Information</strong>
                </div>

                <div class="card-body">

                    @if($payment_certificate->submittedBy)

                        <div class="mb-3">

                            <div class="text-muted small">
                                Submitted By
                            </div>

                            <strong>
                                {{ $payment_certificate->submittedBy->name ?? 'User' }}
                            </strong>

                            <div class="small text-muted">
                                {{ optional(
                                    $payment_certificate->submitted_date
                                )->format('d M Y') }}
                            </div>

                        </div>

                    @endif


                    @if($payment_certificate->approvedBy)

                        <div class="mb-3">

                            <div class="text-muted small">
                                Approved By
                            </div>

                            <strong>
                                {{ $payment_certificate->approvedBy->name ?? 'User' }}
                            </strong>

                            <div class="small text-muted">
                                {{ optional(
                                    $payment_certificate->approval_date
                                )->format('d M Y') }}
                            </div>

                        </div>

                    @endif


                    @if($payment_certificate->rejectedBy)

                        <div>

                            <div class="text-muted small">
                                Rejected By
                            </div>

                            <strong>
                                {{ $payment_certificate->rejectedBy->name ?? 'User' }}
                            </strong>

                            <div class="small text-muted">
                                {{ optional(
                                    $payment_certificate->rejection_date
                                )->format('d M Y') }}
                            </div>

                        </div>

                    @endif

                </div>

            </div>


            <div class="card shadow-sm mb-4">

                <div class="card-header bg-white">
                    <strong>Remarks</strong>
                </div>

                <div class="card-body">

                    {{ $payment_certificate->remarks ?: 'No remarks.' }}

                </div>

            </div>


            @if(in_array(
                $payment_certificate->status,
                ['Draft', 'Rejected']
            ))

                <div class="card shadow-sm">

                    <div class="card-body">

                        <form method="POST"
                              action="{{ route(
                                'admin.projects.construction.payment-certificates.destroy',
                                [
                                    'project' => $project,
                                    'payment_certificate' => $payment_certificate
                                ]
                              ) }}">

                            @csrf
                            @method('DELETE')

                            <button
                                class="btn btn-outline-danger w-100"
                                onclick="return confirm('Delete this certificate?')">

                                <i class="bi bi-trash"></i>
                                Delete Certificate

                            </button>

                        </form>

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>


<div class="modal fade" id="rejectModal" tabindex="-1">

    <div class="modal-dialog">

        <form method="POST"
              action="{{ route(
                'admin.projects.construction.payment-certificates.reject',
                [
                    'project' => $project,
                    'payment_certificate' => $payment_certificate
                ]
              ) }}">

            @csrf

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Reject Payment Certificate
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <label class="form-label">
                        Rejection Remarks
                    </label>

                    <textarea
                        name="rejection_remarks"
                        class="form-control"
                        rows="4"
                        required></textarea>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button
                        type="submit"
                        class="btn btn-danger">

                        Reject Certificate

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection