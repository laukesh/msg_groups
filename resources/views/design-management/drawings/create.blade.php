@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div><h4 class="mb-1">Register Drawing</h4><div class="text-muted">{{ $project->project_name }}</div></div>
        <a href="{{ route('admin.projects.design-management.drawings.index', $project) }}" class="btn btn-outline-secondary">Back</a>
    </div>
    @include('design-management.partials.alerts')
    <form method="POST" action="{{ route('admin.projects.design-management.drawings.store', $project) }}" enctype="multipart/form-data">@csrf
        <div class="card"><div class="card-body">@include('design-management.drawings._form')</div>
        <div class="card-footer bg-white"><button class="btn btn-primary">Save Drawing</button></div></div>
    </form>
</div>
@endsection
