@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h3>CAM Charges</h3>
        <a href="{{ route('admin.revenue.cam_charges.create') }}" class="btn btn-primary">New CAM Charge</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Lease</th>
                <th>Unit</th>
                <th>Period</th>
                <th>Total</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($camCharges as $c)
                <tr>
                    <td>{{ $c->id }}</td>
                    <td>{{ $c->lease_agreement_id }}</td>
                    <td>{{ $c->unit_id }}</td>
                    <td>{{ $c->period_start }} - {{ $c->period_end }}</td>
                    <td>{{ number_format($c->total_amount, 2) }}</td>
                    <td>{{ $c->charge_status }}</td>
                    <td>
                        <a href="{{ route('admin.revenue.cam_charges.edit', $c->id) }}" class="btn btn-sm btn-secondary">Edit</a>
                        <form action="{{ route('admin.revenue.cam_charges.destroy', $c->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">No records found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $camCharges->links() }}
</div>
@endsection
