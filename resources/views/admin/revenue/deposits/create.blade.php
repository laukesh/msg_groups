@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>New Deposit</h3>

    <form action="{{ route('admin.revenue.deposits.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Lease Agreement ID</label>
            <input name="lease_agreement_id" class="form-control" value="{{ old('lease_agreement_id') }}">
        </div>

        <div class="mb-3">
            <label>Deposit Type</label>
            <input name="deposit_type" class="form-control" value="{{ old('deposit_type') }}">
        </div>

        <div class="mb-3">
            <label>Deposit Amount</label>
            <input name="deposit_amount" class="form-control" value="{{ old('deposit_amount') }}">
        </div>

        <div class="mb-3">
            <label>Received Amount</label>
            <input name="received_amount" class="form-control" value="{{ old('received_amount') }}">
        </div>

        <div class="mb-3">
            <label>Balance Amount</label>
            <input name="balance_amount" class="form-control" value="{{ old('balance_amount') }}">
        </div>

        <div class="mb-3">
            <label>Due Date</label>
            <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}">
        </div>

        <div class="mb-3">
            <label>Payment Status</label>
            <input name="payment_status" class="form-control" value="{{ old('payment_status') }}">
        </div>

        <div class="mb-3">
            <label>Refundable Amount</label>
            <input name="refundable_amount" class="form-control" value="{{ old('refundable_amount') }}">
        </div>

        <div class="mb-3">
            <label>Remarks</label>
            <textarea name="remarks" class="form-control">{{ old('remarks') }}</textarea>
        </div>

        <button class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
