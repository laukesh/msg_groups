
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4>Edit Letter of Intent</h4>
            <div class="text-muted">{{ $loi->loi_number }}</div>
        </div>
        <a href="{{ route('admin.tenants.lois.show', $loi) }}" class="btn btn-outline-secondary">
            Back to LOI
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.tenants.lois.update', $loi) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-body">
                @include('admin.tenants.lois._form')
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" type="submit">Update LOI</button>
            </div>
        </div>
    </form>
</div>
@endsection
