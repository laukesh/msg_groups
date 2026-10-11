@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div><h4 class="mb-1">Edit Review</h4><div class="text-muted">{{ $review->review_number }}</div></div>
        <a href="{{ route('admin.projects.design-management.reviews.show', [$project, $review]) }}" class="btn btn-outline-secondary">Back</a>
    </div>
    @include('design-management.partials.alerts')
    <form method="POST" action="{{ route('admin.projects.design-management.reviews.update', [$project, $review]) }}">@csrf @method('PUT')
        <div class="card"><div class="card-body">@include('design-management.reviews._form')</div>
        <div class="card-footer bg-white"><button class="btn btn-primary">Update Review</button></div></div>
    </form>
</div>
@endsection
