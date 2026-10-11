@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Deposit Refund</h3>

    <table class="table table-bordered">
        <tr><th>ID</th><td>{{ $refund->id }}</td></tr>
        <tr><th>Deposit</th><td>{{ $refund->deposit_id }}</td></tr>
        <tr><th>Refund No</th><td>{{ $refund->refund_no }}</td></tr>
        <tr><th>Date</th><td>{{ optional($refund->refund_date)->format('Y-m-d') }}</td></tr>
        <tr><th>Original Deposit</th><td>{{ number_format($refund->original_deposit, 2) }}</td></tr>
        <tr><th>Total Deduction</th><td>{{ number_format($refund->total_deduction, 2) }}</td></tr>
        <tr><th>Refund Amount</th><td>{{ number_format($refund->refund_amount, 2) }}</td></tr>
        <tr><th>Payment Mode</th><td>{{ $refund->payment_mode }}</td></tr>
        <tr><th>Bank</th><td>{{ $refund->bank_name }}</td></tr>
        <tr><th>Transaction Ref</th><td>{{ $refund->transaction_reference }}</td></tr>
        <tr><th>Status</th><td>{{ $refund->refund_status }}</td></tr>
        <tr><th>Approved By</th><td>{{ $refund->approved_by }}</td></tr>
        <tr><th>Approved At</th><td>{{ optional($refund->approved_at)->format('Y-m-d') }}</td></tr>
        <tr><th>Remarks</th><td>{{ $refund->remarks }}</td></tr>
    </table>

    <a href="{{ route('admin.revenue.deposit_refunds.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
