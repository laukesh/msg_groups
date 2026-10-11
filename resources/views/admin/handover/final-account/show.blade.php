@extends('layouts.app')

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.projects.handover.final-account.index', $project) }}"
                   class="btn btn-light btn-sm">
                    <i class="ri-arrow-left-line"></i>
                </a>

                <div>
                    <h4 class="mb-0">{{ $account->final_account_no }}</h4>
                    <div class="text-muted small mt-1">
                        {{ $project->project_name ?? 'Project' }}
                        ·
                        {{ $contract?->contract_no ?? $contract?->contract_number ?? 'Procurement Contract' }}
                    </div>
                </div>
            </div>
        </div>


        <div class="d-flex gap-2">

            @if(in_array($account->status, ['Draft','Prepared','Rejected']))
                <a href="{{ route('admin.projects.handover.final-account.edit', [$project, $account]) }}"
                   class="btn btn-outline-primary">
                    <i class="ri-edit-line me-1"></i>
                    Edit
                </a>
            @endif

            @if(in_array($account->status, ['Prepared','Rejected']))
                <form method="POST"
                      action="{{ route('admin.projects.handover.final-account.submit', [$project, $account]) }}"
                      onsubmit="return confirm('Submit this Final Account for review?');">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-send-plane-line me-1"></i>
                        Submit for Review
                    </button>
                </form>
            @endif

            @if($account->status === 'Submitted')
                <form method="POST"
                      action="{{ route('admin.projects.handover.final-account.review', [$project, $account]) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-search-eye-line me-1"></i>
                        Start Review
                    </button>
                </form>
            @endif

            @if($account->status === 'Under Review')
                <form method="POST"
                      action="{{ route('admin.projects.handover.final-account.approve', [$project, $account]) }}"
                      onsubmit="return confirm('Approve this Final Account?');">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="ri-checkbox-circle-line me-1"></i>
                        Approve
                    </button>
                </form>

                <a href="{{ route('admin.projects.handover.final-account.reject.form', [$project, $account]) }}"
                   class="btn btn-outline-danger">
                    <i class="ri-close-circle-line me-1"></i>
                    Reject
                </a>
            @endif

        </div>
    </div>


    <div class="row g-4">

        <div class="col-xl-8">

            {{-- FINANCIAL SUMMARY --}}
            <div class="row g-3 mb-4">

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <small class="text-muted">Contract Value</small>
                            <h4 class="mb-0 mt-2">
                                {{ $currency }} {{ number_format($account->contract_value, 2) }}
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <small class="text-muted">Approved Variations</small>
                            <h4 class="mb-0 mt-2 text-success">
                                {{ $currency }} {{ number_format($account->approved_variations, 2) }}
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <small class="text-muted">Approved Claims</small>
                            <h4 class="mb-0 mt-2 text-success">
                                {{ $currency }} {{ number_format($account->approved_claims, 2) }}
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <small class="text-muted">Gross Final Amount</small>
                            <h4 class="mb-0 mt-2">
                                {{ $currency }} {{ number_format($account->gross_final_amount, 2) }}
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <small class="text-muted">Amount Paid</small>
                            <h4 class="mb-0 mt-2 text-primary">
                                {{ $currency }} {{ number_format($account->amount_paid, 2) }}
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <small class="text-muted">Balance Payable</small>
                            <h4 class="mb-0 mt-2 {{ $account->balance_payable > 0 ? 'text-danger' : 'text-success' }}">
                                {{ $currency }} {{ number_format($account->balance_payable, 2) }}
                            </h4>
                        </div>
                    </div>
                </div>

            </div>


            {{-- FINANCE STATEMENT --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Final Account Statement</h5>
                </div>

                <div class="card-body">

                    <table class="table table-bordered mb-0">
                        <tbody>

                            <tr>
                                <th width="55%">Contract Value</th>
                                <td class="text-end">
                                    {{ $currency }} {{ number_format($account->contract_value, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <th>Approved Variations</th>
                                <td class="text-end text-success">
                                    + {{ $currency }} {{ number_format($account->approved_variations, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <th>Approved Claims</th>
                                <td class="text-end text-success">
                                    + {{ $currency }} {{ number_format($account->approved_claims, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <th>Other Adjustments</th>
                                <td class="text-end">
                                    {{ $currency }} {{ number_format($account->other_adjustments, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <th>Advance Recovery</th>
                                <td class="text-end text-danger">
                                    - {{ $currency }} {{ number_format($account->advance_recovery, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <th>Retention</th>
                                <td class="text-end text-danger">
                                    - {{ $currency }} {{ number_format($account->retention_amount, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <th>Deductions</th>
                                <td class="text-end text-danger">
                                    - {{ $currency }} {{ number_format($account->deductions, 2) }}
                                </td>
                            </tr>

                            <tr class="table-light">
                                <th>Gross Final Amount</th>
                                <td class="text-end fw-bold">
                                    {{ $currency }} {{ number_format($account->gross_final_amount, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <th>Amount Paid</th>
                                <td class="text-end text-primary">
                                    {{ $currency }} {{ number_format($account->amount_paid, 2) }}
                                </td>
                            </tr>

                            <tr class="table-light">
                                <th>Balance Payable</th>
                                <td class="text-end fw-bold {{ $account->balance_payable > 0 ? 'text-danger' : 'text-success' }}">
                                    {{ $currency }} {{ number_format($account->balance_payable, 2) }}
                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>
            </div>


            {{-- VARIATIONS --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between">
                    <h5 class="mb-0">Approved Variations</h5>
                    <span class="badge bg-success">{{ $variations->count() }}</span>
                </div>

                <div class="card-body p-0">
                    @if($variations->count())
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Variation</th>
                                        <th>Date</th>
                                        <th>Title</th>
                                        <th class="text-end pe-4">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($variations as $variation)
                                    <tr>
                                        <td class="ps-4">{{ $variation->variation_number }}</td>
                                        <td>{{ $variation->variation_date?->format('d M Y') ?? '-' }}</td>
                                        <td>{{ $variation->title }}</td>
                                        <td class="text-end pe-4">
                                            {{ $variation->currency ?? $currency }}
                                            {{ number_format($variation->amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center text-muted">
                            No approved variations.
                        </div>
                    @endif
                </div>
            </div>


            {{-- CLAIMS --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between">
                    <h5 class="mb-0">Approved Claims</h5>
                    <span class="badge bg-success">{{ $claims->count() }}</span>
                </div>

                <div class="card-body p-0">
                    @if($claims->count())
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Claim</th>
                                        <th>Date</th>
                                        <th>Subject</th>
                                        <th class="text-end pe-4">Approved Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($claims as $claim)
                                    <tr>
                                        <td class="ps-4">{{ $claim->claim_number }}</td>
                                        <td>{{ $claim->claim_date?->format('d M Y') ?? '-' }}</td>
                                        <td>{{ $claim->subject }}</td>
                                        <td class="text-end pe-4">
                                            {{ $currency }}
                                            {{ number_format($claim->approved_amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center text-muted">
                            No approved claims.
                        </div>
                    @endif
                </div>
            </div>

        </div>


        <div class="col-xl-4">

            {{-- STATUS --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Workflow</h6>
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <small class="text-muted d-block">Current Status</small>
                        <strong>{{ $account->status }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Prepared Date</small>
                        {{ $account->prepared_date?->format('d M Y') ?? '-' }}
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Submitted Date</small>
                        {{ $account->submitted_date?->format('d M Y') ?? '-' }}
                    </div>

                    <div>
                        <small class="text-muted d-block">Approved Date</small>
                        {{ $account->approved_date?->format('d M Y') ?? '-' }}
                    </div>

                    @if($account->status === 'Rejected')
                        <hr>
                        <small class="text-danger d-block">Rejection Reason</small>
                        <div class="text-danger">
                            {{ $account->rejection_reason }}
                        </div>
                    @endif

                </div>
            </div>


            {{-- CONTRACT --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Procurement Contract</h6>
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <small class="text-muted d-block">Contract No.</small>
                        <strong>
                            {{ $contract?->contract_no ?? $contract?->contract_number ?? '-' }}
                        </strong>
                    </div>

                    @if($contract?->bidder)
                        <div class="mb-3">
                            <small class="text-muted d-block">Contractor / Bidder</small>
                            {{ $contract->bidder->name ?? $contract->bidder->company_name ?? '-' }}
                        </div>
                    @endif

                    <a href="{{ route('admin.projects.handover.final-account.payments', [$project, $account]) }}"
                       class="btn btn-outline-primary w-100">
                        <i class="ri-bank-card-line me-1"></i>
                        View Payment History
                    </a>

                </div>
            </div>


            {{-- REMARKS --}}
            @if($account->contractor_statement || $account->finance_remarks || $account->remarks)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Remarks</h6>
                    </div>
                    <div class="card-body">

                        @if($account->contractor_statement)
                            <div class="mb-3">
                                <small class="text-muted d-block">Contractor Statement</small>
                                {!! nl2br(e($account->contractor_statement)) !!}
                            </div>
                        @endif

                        @if($account->finance_remarks)
                            <div class="mb-3">
                                <small class="text-muted d-block">Finance Remarks</small>
                                {!! nl2br(e($account->finance_remarks)) !!}
                            </div>
                        @endif

                        @if($account->remarks)
                            <div>
                                <small class="text-muted d-block">Remarks</small>
                                {!! nl2br(e($account->remarks)) !!}
                            </div>
                        @endif

                    </div>
                </div>
            @endif

        </div>

    </div>
</div>
@endsection
