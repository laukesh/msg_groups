@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>CAM Charge</h3>

    <table class="table table-bordered">
        <tr><th>ID</th><td>{{ $camCharge->id }}</td></tr>
        <tr><th>Lease Agreement</th><td>{{ $camCharge->lease_agreement_id }}</td></tr>
        <tr><th>Unit</th><td>{{ $camCharge->unit_id }}</td></tr>
        <tr><th>Period</th><td>{{ $camCharge->period_start }} - {{ $camCharge->period_end }}</td></tr>
        <tr><th>Total</th><td>{{ number_format($camCharge->total_amount, 2) }}</td></tr>
        <tr><th>Remarks</th><td>{{ $camCharge->remarks }}</td></tr>
    </table>

    <a href="{{ route('admin.revenue.cam_charges.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
