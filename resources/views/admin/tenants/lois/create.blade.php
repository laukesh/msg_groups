
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Create Letter of Intent</h4>
        <a href="{{ route('admin.tenants.lois.index') }}" class="btn btn-outline-secondary">
            Back to LOI Register
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            Please correct the highlighted fields.
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.tenants.lois.store') }}"
          enctype="multipart/form-data">
        @csrf

        <div class="card">
            <div class="card-body">
                @include('admin.tenants.lois._form')
            </div>
            <div class="card-footer d-flex gap-2">
                <button class="btn btn-primary" type="submit">Save LOI Draft</button>
                <a href="{{ route('admin.tenants.lois.index') }}" class="btn btn-light">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection
