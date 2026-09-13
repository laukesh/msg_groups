@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.projects.handover.index', $project) }}"
                   class="btn btn-light btn-sm">
                    <i class="ri-arrow-left-line"></i>
                </a>
                <h4 class="mb-0">Final Account</h4>
            </div>
            <div class="text-muted small mt-1">
                {{ $project->project_name ?? 'Project' }}
                @if($project->project_code) · {{ $project->project_code }} @endif
                · Handover {{ $handover->handover_no }}
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route(
                    'admin.projects.handover.index',
                    $project
                ) }}"
                   class="btn btn-light">
                    <i class="ri-arrow-left-line"></i>
                    Handover
            </a>

            <a href="{{ route('admin.projects.handover.final-account.create', $project) }}"
               class="btn btn-primary">
                <i class="ri-add-line me-1"></i>
                New Final Account
            </a>
        </div>
    </div>

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

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Final Accounts</small>
                    <h3 class="mb-0 mt-1">{{ $totalAccounts }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Gross Final Amount</small>
                    <h4 class="mb-0 mt-1">{{ number_format($totalGrossFinalAmount, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Amount Paid</small>
                    <h4 class="mb-0 mt-1 text-primary">{{ number_format($totalAmountPaid, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Balance Payable</small>
                    <h4 class="mb-0 mt-1 text-danger">{{ number_format($totalBalancePayable, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-1">Final Account Register</h5>
            <small class="text-muted">
                One Final Account per Procurement Contract
            </small>
        </div>

        <div class="card-body p-0">
            @if($accounts->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Final Account</th>
                                <th>Contract</th>
                                <th>Contract Value</th>
                                <th>Variations</th>
                                <th>Claims</th>
                                <th>Gross Final</th>
                                <th>Paid</th>
                                <th>Balance</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($accounts as $account)
                            @php
                                $contract = $account->procurementContract;
                                $currency = $contract?->currency ?? $contract?->contract_currency ?? '';
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <a class="fw-semibold text-decoration-none"
                                       href="{{ route('admin.projects.handover.final-account.show', [$project, $account]) }}">
                                        {{ $account->final_account_no }}
                                    </a>
                                    <div class="small text-muted">
                                        {{ $account->prepared_date?->format('d M Y') ?? 'Not prepared' }}
                                    </div>
                                </td>

                                <td>
                                    @if($contract)
                                        <div class="fw-semibold">
                                            {{ $contract->contract_no ?? $contract->contract_number ?? ('Contract #'.$contract->id) }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $contract->bidder->name ?? $contract->bidder->company_name ?? '' }}
                                        </small>
                                    @else
                                        <span class="text-danger">Contract not found</span>
                                    @endif
                                </td>

                                <td>{{ $currency }} {{ number_format($account->contract_value, 2) }}</td>
                                <td class="text-success">{{ $currency }} {{ number_format($account->approved_variations, 2) }}</td>
                                <td class="text-success">{{ $currency }} {{ number_format($account->approved_claims, 2) }}</td>
                                <td class="fw-semibold">{{ $currency }} {{ number_format($account->gross_final_amount, 2) }}</td>
                                <td class="text-primary">{{ $currency }} {{ number_format($account->amount_paid, 2) }}</td>
                                <td class="{{ $account->balance_payable > 0 ? 'text-danger' : 'text-success' }} fw-semibold">
                                    {{ $currency }} {{ number_format($account->balance_payable, 2) }}
                                </td>

                                <td>
                                    @php
                                        $badge = match($account->status) {
                                            'Approved' => 'bg-success',
                                            'Submitted' => 'bg-primary',
                                            'Under Review' => 'bg-warning text-dark',
                                            'Rejected' => 'bg-danger',
                                            'Prepared' => 'bg-info',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ $account->status }}</span>
                                </td>

                                <td class="text-end pe-4">
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.projects.handover.final-account.show', [$project, $account]) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>
                                        @if(in_array($account->status, ['Draft','Prepared','Rejected']))
                                            <a href="{{ route('admin.projects.handover.final-account.edit', [$project, $account]) }}"
                                               class="btn btn-sm btn-outline-secondary">
                                                Edit
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ri-file-list-3-line fs-1 text-muted"></i>
                    <h5 class="mt-3">No Final Accounts</h5>
                    <p class="text-muted">Create a Final Account for a Procurement Contract.</p>
                    <a href="{{ route('admin.projects.handover.final-account.create', $project) }}"
                       class="btn btn-primary">
                        <i class="ri-add-line me-1"></i> New Final Account
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
