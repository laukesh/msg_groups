@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>New Deposit Refund</h3>

    <form action="{{ route('admin.revenue.deposit_refunds.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Deposit ID</label>
            <input name="deposit_id" class="form-control" value="{{ old('deposit_id') }}">
        </div>

        <div class="mb-3">
            <label>Refund No</label>
            <input name="refund_no" class="form-control" value="{{ old('refund_no') }}">
        </div>

        <div class="mb-3">
            <label>Refund Date</label>
            <input type="date" name="refund_date" class="form-control" value="{{ old('refund_date') }}">
        </div>

        <div class="mb-3">
            <label>Original Deposit</label>
            <input name="original_deposit" class="form-control" value="{{ old('original_deposit') }}">
        </div>

        <div class="mb-3">
            <label>Outstanding Rent</label>
            <input name="outstanding_rent" class="form-control" value="{{ old('outstanding_rent') }}">
        </div>

        <div class="mb-3">
            <label>CAM Deduction</label>
            <input name="cam_deduction" class="form-control" value="{{ old('cam_deduction') }}">
        </div>

        <div class="mb-3">
            <label>Utility Deduction</label>
            <input name="utility_deduction" class="form-control" value="{{ old('utility_deduction') }}">
        </div>

        <div class="mb-3">
            <label>Damage Deduction</label>
            <input name="damage_deduction" class="form-control" value="{{ old('damage_deduction') }}">
        </div>

        <div class="mb-3">
            <label>Penalty Deduction</label>
            <input name="penalty_deduction" class="form-control" value="{{ old('penalty_deduction') }}">
        </div>

        <div class="mb-3">
            <label>Other Deduction</label>
            <input name="other_deduction" class="form-control" value="{{ old('other_deduction') }}">
        </div>

        <div class="mb-3">
            <label>Total Deduction</label>
            <input name="total_deduction" class="form-control" value="{{ old('total_deduction') }}">
        </div>

        <div class="mb-3">
            <label>Refund Amount</label>
            <input name="refund_amount" class="form-control" value="{{ old('refund_amount') }}">
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
            <label>Refund Status</label>
            <input name="refund_status" class="form-control" value="{{ old('refund_status') }}">
        </div>

        <div class="mb-3">
            <label>Approved By (User ID)</label>
            <input name="approved_by" class="form-control" value="{{ old('approved_by') }}">
        </div>

        <div class="mb-3">
            <label>Approved At</label>
            <input type="date" name="approved_at" class="form-control" value="{{ old('approved_at') }}">
        </div>

        <div class="mb-3">
            <label>Remarks</label>
            <textarea name="remarks" class="form-control">{{ old('remarks') }}</textarea>
        </div>

        <button class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
