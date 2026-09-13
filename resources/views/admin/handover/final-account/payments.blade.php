@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.projects.handover.final-account.show', [$project, $account]) }}"
           class="btn btn-light btn-sm">
            <i class="ri-arrow-left-line"></i>
        </a>
        <div>
            <h4 class="mb-0">Payment History</h4>
            <div class="text-muted small">
                {{ $account->final_account_no }}
                · {{ $contract?->contract_no ?? $contract?->contract_number ?? '-' }}
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Final Account Amount Paid</small>
                    <h4 class="mb-0 mt-1 text-primary">
                        {{ $currency }} {{ number_format($account->amount_paid, 2) }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Completed Payments</small>
                    <h4 class="mb-0 mt-1">
                        {{ $payments->whereIn('status', ['Approved','Processed'])->count() }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Balance Payable</small>
                    <h4 class="mb-0 mt-1 text-danger">
                        {{ $currency }} {{ number_format($account->balance_payable, 2) }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-1">Payment Transactions</h5>
            <small class="text-muted">
                All payment statuses are shown for audit visibility.
            </small>
        </div>

        <div class="card-body p-0">
            @if($payments->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Payment No.</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Method</th>
                                <th>Reference</th>
                                <th>Currency</th>
                                <th class="text-end">Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $payment->payment_number }}</td>
                                <td>{{ $payment->payment_date?->format('d M Y') ?? '-' }}</td>
                                <td>{{ $payment->payment_type ?: '-' }}</td>
                                <td>{{ $payment->payment_method ?: '-' }}</td>
                                <td>{{ $payment->transaction_reference ?: '-' }}</td>
                                <td>{{ $payment->currency ?: '-' }}</td>
                                <td class="text-end">
                                    {{ number_format($payment->amount, 2) }}
                                </td>
                                <td>
                                    @php
                                        $badge = match($payment->status) {
                                            'Processed' => 'bg-success',
                                            'Approved' => 'bg-primary',
                                            'Rejected' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }}">
                                        {{ $payment->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    No payment transactions found.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
