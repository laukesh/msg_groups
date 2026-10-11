@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>New CAM Charge</h3>

    <form action="{{ route('admin.revenue.cam_charges.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Lease Agreement ID</label>
            <input name="lease_agreement_id" class="form-control" value="{{ old('lease_agreement_id') }}">
        </div>

        <div class="mb-3">
            <label>Unit ID</label>
            <input name="unit_id" class="form-control" value="{{ old('unit_id') }}">
        </div>

        <div class="mb-3">
            <label>Period Start</label>
            <input type="date" name="period_start" class="form-control" value="{{ old('period_start') }}">
        </div>

        <div class="mb-3">
            <label>Period End</label>
            <input type="date" name="period_end" class="form-control" value="{{ old('period_end') }}">
        </div>

        <div class="mb-3">
            <label>Total Amount</label>
            <input name="total_amount" class="form-control" value="{{ old('total_amount') }}">
        </div>

        <div class="mb-3">
            <label>Remarks</label>
            <textarea name="remarks" class="form-control">{{ old('remarks') }}</textarea>
        </div>

        <button class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
