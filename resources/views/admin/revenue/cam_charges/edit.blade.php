@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Edit CAM Charge</h3>

    <form action="{{ route('admin.revenue.cam_charges.update', $camCharge->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Lease Agreement ID</label>
            <input name="lease_agreement_id" class="form-control" value="{{ old('lease_agreement_id', $camCharge->lease_agreement_id) }}">
        </div>

        <div class="mb-3">
            <label>Unit ID</label>
            <input name="unit_id" class="form-control" value="{{ old('unit_id', $camCharge->unit_id) }}">
        </div>

        <div class="mb-3">
            <label>Period Start</label>
            <input type="date" name="period_start" class="form-control" value="{{ old('period_start', optional($camCharge->period_start)->format('Y-m-d')) }}">
        </div>

        <div class="mb-3">
            <label>Period End</label>
            <input type="date" name="period_end" class="form-control" value="{{ old('period_end', optional($camCharge->period_end)->format('Y-m-d')) }}">
        </div>

        <div class="mb-3">
            <label>Total Amount</label>
            <input name="total_amount" class="form-control" value="{{ old('total_amount', $camCharge->total_amount) }}">
        </div>

        <div class="mb-3">
            <label>Remarks</label>
            <textarea name="remarks" class="form-control">{{ old('remarks', $camCharge->remarks) }}</textarea>
        </div>

        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
