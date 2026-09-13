@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div><h4 class="mb-1">Edit Design Change</h4><div class="text-muted">{{ $change->change_code }}</div></div>
        <a href="{{ route('admin.projects.design-management.changes.show', [$project, $change]) }}" class="btn btn-outline-secondary">Back</a>
    </div>
    @include('design-management.partials.alerts')
    <form method="POST" action="{{ route('admin.projects.design-management.changes.update', [$project, $change]) }}">@csrf @method('PUT')
        <div class="card"><div class="card-body">@include('design-management.changes._form')</div>
        <div class="card-footer bg-white"><button class="btn btn-primary">Update Design Change</button></div></div>
    </form>
</div>
@endsection
