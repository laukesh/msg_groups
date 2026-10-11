@extends('layouts.app')

@section('title', 'Create Asset')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1"><i class="fas fa-boxes me-2"></i>Create Asset</h4>
            <div class="text-muted">Add a new asset.</div>
        </div>
        <a href="{{ route('admin.assets.assets.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Asset Information</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.assets.assets.store') }}">
                @csrf
                @include('admin.assets.assets._form')

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.assets.assets.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Create Asset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
