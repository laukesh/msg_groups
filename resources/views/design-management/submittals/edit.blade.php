@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div><h4 class="mb-1">Edit Submittal</h4><div class="text-muted">{{ $submittal->submittal_number }}</div></div>
        <a href="{{ route('admin.projects.design-management.submittals.show', [$project, $submittal]) }}" class="btn btn-outline-secondary">Back</a>
    </div>
    @include('design-management.partials.alerts')
    <form method="POST" action="{{ route('admin.projects.design-management.submittals.update', [$project, $submittal]) }}">@csrf @method('PUT')
        <div class="card"><div class="card-body">@include('design-management.submittals._form')</div>
        <div class="card-footer bg-white"><button class="btn btn-primary">Update Submittal</button></div></div>
    </form>
</div>
@endsection
