@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>New Deposit Receipt</h3>

    <form action="{{ route('admin.revenue.deposit_receipts.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Deposit ID</label>
            <input name="deposit_id" class="form-control" value="{{ old('deposit_id') }}">
        </div>

        <div class="mb-3">
            <label>Receipt No</label>
            <input name="receipt_no" class="form-control" value="{{ old('receipt_no') }}">
        </div>

        <div class="mb-3">
            <label>Receipt Date</label>
            <input type="date" name="receipt_date" class="form-control" value="{{ old('receipt_date') }}">
        </div>

        <div class="mb-3">
            <label>Payment Amount</label>
            <input name="payment_amount" class="form-control" value="{{ old('payment_amount') }}">
        </div>

        <div class="mb-3">
            <label>Payment Mode</label>
            <input name="payment_mode" class="form-control" value="{{ old('payment_mode') }}">
        </div>

        <div class="mb-3">
            <label>Bank Name</label>
            <input name="bank_name" class="form-control" value="{{ old('bank_name') }}">
        </div>

        <div class="mb-3">
            <label>Transaction Reference</label>
            <input name="transaction_reference" class="form-control" value="{{ old('transaction_reference') }}">
        </div>

        <div class="mb-3">
            <label>Payment Status</label>
            <input name="payment_status" class="form-control" value="{{ old('payment_status') }}">
        </div>

        <div class="mb-3">
            <label>Received By (User ID)</label>
            <input name="received_by" class="form-control" value="{{ old('received_by') }}">
        </div>

        <div class="mb-3">
            <label>Remarks</label>
            <textarea name="remarks" class="form-control">{{ old('remarks') }}</textarea>
        </div>

        <button class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
