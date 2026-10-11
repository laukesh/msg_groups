@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Deposit Receipt</h3>

    <table class="table table-bordered">
        <tr><th>ID</th><td>{{ $receipt->id }}</td></tr>
        <tr><th>Deposit</th><td>{{ $receipt->deposit_id }}</td></tr>
        <tr><th>Receipt No</th><td>{{ $receipt->receipt_no }}</td></tr>
        <tr><th>Date</th><td>{{ optional($receipt->receipt_date)->format('Y-m-d') }}</td></tr>
        <tr><th>Amount</th><td>{{ number_format($receipt->payment_amount, 2) }}</td></tr>
        <tr><th>Mode</th><td>{{ $receipt->payment_mode }}</td></tr>
        <tr><th>Bank</th><td>{{ $receipt->bank_name }}</td></tr>
        <tr><th>Transaction Ref</th><td>{{ $receipt->transaction_reference }}</td></tr>
        <tr><th>Status</th><td>{{ $receipt->payment_status }}</td></tr>
        <tr><th>Received By</th><td>{{ $receipt->received_by }}</td></tr>
        <tr><th>Remarks</th><td>{{ $receipt->remarks }}</td></tr>
    </table>

    <a href="{{ route('admin.revenue.deposit_receipts.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
