@extends('layouts.app')

@section('title', 'Schedule Tenant Handover')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="{{ route('admin.tenants.openings.index') }}">← Handover Register</a>
        <h3 class="mt-2">Schedule Tenant Opening / Handover</h3>
    </div>

    <form method="POST" action="{{ route('admin.tenants.openings.store') }}">
        @csrf
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @include('admin.tenants.openings._form')
                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-primary">Save Handover</button>
                    <a href="{{ route('admin.tenants.openings.index') }}"
                       class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection