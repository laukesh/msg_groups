@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div><h4 class="mb-1">Edit RFI</h4><div class="text-muted">{{ $rfi->rfi_number }}</div></div>
        <a href="{{ route('admin.projects.design-management.rfis.show', [$project, $rfi]) }}" class="btn btn-outline-secondary">Back</a>
    </div>
    @include('design-management.partials.alerts')
    <form method="POST" action="{{ route('admin.projects.design-management.rfis.update', [$project, $rfi]) }}">@csrf @method('PUT')
        <div class="card"><div class="card-body">@include('design-management.rfis._form')</div>
        <div class="card-footer bg-white"><button class="btn btn-primary">Update RFI</button></div></div>
    </form>
</div>
@endsection
