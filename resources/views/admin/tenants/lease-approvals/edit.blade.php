
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <h4 class="mb-4">Edit Lease Approval: {{ $approval->approval_number }}</h4>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('admin.tenants.lease-approvals.update', $approval) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-body">
                @include('admin.tenants.lease-approvals._form')
            </div>
            <div class="card-footer">
                <button class="btn btn-primary">Update Draft</button>
                <a href="{{ route('admin.tenants.lease-approvals.show', $approval) }}"
                   class="btn btn-light">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection
