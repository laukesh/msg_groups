@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Contract Payments
            </h4>

            <div class="text-muted">
                {{ $contract->contract_number ?? 'Contract #' . $contract->id }}
            </div>
        </div>

        <div class="d-flex gap-2">

            {{-- Back to Tender --}}
            <a href="{{ route('admin.projects.procurement.tenders.show', [
                'project' => $project,
                'procurementTender' => $procurementTender,
            ]) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Back to Tender
            </a>

            {{-- Back to Contract --}}
            <a href="{{ route('admin.projects.procurement.tenders.contracts.show', [
                'project' => $project,
                'procurementTender' => $procurementTender,
                'contract' => $contract,
            ]) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-file-earmark-text"></i>
                Contract
            </a>

            {{-- Create Payment --}}
            <a href="{{ route('admin.projects.procurement.tenders.contracts.payments.create', [
                'project' => $project,
                'procurementTender' => $procurementTender,
                'contract' => $contract,
            ]) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>
                Create Payment
            </a>

        </div>
    </div>


    {{-- =========================================================
         FLASH MESSAGES
    ========================================================== --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())
        <div class="alert alert-danger">

            <strong>Please fix the following:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif


    {{-- =========================================================
         CONTRACT SUMMARY
    ========================================================== --}}

    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Project
                    </div>

                    <div class="fw-semibold mt-1">
                        {{ $project->name ?? $project->project_name ?? 'Project #' . $project->id }}
                    </div>

                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Tender
                    </div>

                    <div class="fw-semibold mt-1">
                        {{ $procurementTender->tender_number
                            ?? $procurementTender->tender_no
                            ?? 'Tender #' . $procurementTender->id }}
                    </div>

                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Contract
                    </div>

                    <div class="fw-semibold mt-1">
                        {{ $contract->contract_number
                            ?? $contract->contract_no
                            ?? 'Contract #' . $contract->id }}
                    </div>

                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Total Payments
                    </div>

                    <div class="fw-semibold fs-4 mt-1">
                        {{ $payments->total() }}
                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- =========================================================
         PAYMENTS TABLE
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="mb-0">
                        Payment Records
                    </h5>

                    <small class="text-muted">
                        Payments recorded against this contract
                    </small>
                </div>

                <span class="badge bg-light text-dark">
                    {{ $payments->total() }} Records
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($payments->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-3">
                                    Payment No.
                                </th>

                                <th>
                                    Invoice
                                </th>

                                <th>
                                    Payment Date
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Amount
                                </th>

                                <th>
                                    Method
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end px-3">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        @foreach($payments as $payment)

                            <tr>

                                {{-- Payment Number --}}
                                <td class="px-3">

                                    <a href="{{ route(
                                        'admin.projects.procurement.tenders.contracts.payments.show',
                                        [
                                            'project' => $project,
                                            'procurementTender' => $procurementTender,
                                            'contract' => $contract,
                                            'payment' => $payment,
                                        ]
                                    ) }}"
                                       class="fw-semibold text-decoration-none">

                                        {{ $payment->payment_number }}

                                    </a>

                                </td>


                                {{-- Invoice --}}
                                <td>

                                    @if($payment->invoice)

                                        <div class="fw-semibold">
                                            {{ $payment->invoice->invoice_number }}
                                        </div>

                                        @if($payment->invoice->invoice_date)
                                            <small class="text-muted">
                                                {{ $payment->invoice->invoice_date->format('d M Y') }}
                                            </small>
                                        @endif

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Payment Date --}}
                                <td>

                                    @if($payment->payment_date)
                                        {{ $payment->payment_date->format('d M Y') }}
                                    @else
                                        —
                                    @endif

                                </td>


                                {{-- Payment Type --}}
                                <td>
                                    {{ $payment->payment_type ?: '—' }}
                                </td>


                                {{-- Amount --}}
                                <td>

                                    <div class="fw-semibold">

                                        {{ $payment->currency ?? 'INR' }}

                                        {{ number_format(
                                            (float) $payment->amount,
                                            2
                                        ) }}

                                    </div>

                                </td>


                                {{-- Payment Method --}}
                                <td>
                                    {{ $payment->payment_method ?: '—' }}
                                </td>


                                {{-- Status --}}
                                <td>

                                    @php
                                        $statusClass = match($payment->status) {
                                            'Draft' => 'bg-secondary',
                                            'Submitted' => 'bg-warning text-dark',
                                            'Approved' => 'bg-info text-dark',
                                            'Processed' => 'bg-success',
                                            'Rejected' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp

                                    <span class="badge {{ $statusClass }}">
                                        {{ $payment->status }}
                                    </span>

                                </td>


                                {{-- Action --}}
                                <td class="text-end px-3">

                                    <a href="{{ route(
                                        'admin.projects.procurement.tenders.contracts.payments.show',
                                        [
                                            'project' => $project,
                                            'procurementTender' => $procurementTender,
                                            'contract' => $contract,
                                            'payment' => $payment,
                                        ]
                                    ) }}"
                                       class="btn btn-sm btn-outline-primary">

                                        <i class="bi bi-eye"></i>
                                        View

                                    </a>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="mb-3">
                        <i class="bi bi-cash-stack fs-1 text-muted"></i>
                    </div>

                    <h5>
                        No Payments Found
                    </h5>

                    <p class="text-muted mb-4">
                        No payment has been recorded against this contract yet.
                    </p>

                    <a href="{{ route(
                        'admin.projects.procurement.tenders.contracts.payments.create',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                            'contract' => $contract,
                        ]
                    ) }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-circle"></i>
                        Create First Payment

                    </a>

                </div>

            @endif

        </div>


        {{-- =====================================================
             PAGINATION
        ====================================================== --}}

        @if($payments->hasPages())

            <div class="card-footer bg-white">

                {{ $payments->withQueryString()->links() }}

            </div>

        @endif

    </div>

</div>
@endsection