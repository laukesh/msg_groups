@extends('layouts.app')

@section('title', 'Edit Tenant Handover')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="{{ route('admin.tenants.openings.show', $handover->id) }}">← Handover Details</a>
        <h3 class="mt-2">Edit {{ $handover->handover_number }}</h3>
    </div>

    <form method="POST"
          action="{{ route('admin.tenants.openings.update', $handover->id) }}">
        @csrf
        @method('PUT')
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @include('admin.tenants.openings._form')
                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-primary">Update Handover</button>
                    <a href="{{ route('admin.tenants.openings.show', $handover->id) }}"
                       class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection