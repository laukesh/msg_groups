@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Deposit</h3>

    <table class="table table-bordered">
        <tr><th>ID</th><td>{{ $deposit->id }}</td></tr>
        <tr><th>Lease Agreement</th><td>{{ $deposit->lease_agreement_id }}</td></tr>
        <tr><th>Type</th><td>{{ $deposit->deposit_type }}</td></tr>
        <tr><th>Amount</th><td>{{ number_format($deposit->deposit_amount, 2) }}</td></tr>
        <tr><th>Received</th><td>{{ number_format($deposit->received_amount, 2) }}</td></tr>
        <tr><th>Balance</th><td>{{ number_format($deposit->balance_amount, 2) }}</td></tr>
        <tr><th>Due Date</th><td>{{ optional($deposit->due_date)->format('Y-m-d') }}</td></tr>
        <tr><th>Refundable</th><td>{{ number_format($deposit->refundable_amount ?? 0, 2) }}</td></tr>
        <tr><th>Remarks</th><td>{{ $deposit->remarks }}</td></tr>
    </table>

    <a href="{{ route('admin.revenue.deposits.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
