@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small mb-1">Design Management / Project Brief</div>
            <h3 class="mb-1">Create Project Brief</h3>
            <div class="text-muted">{{ $project->project_name }}</div>
        </div>
        <a href="{{ route('admin.projects.design-management.briefs.index', $project) }}" class="btn btn-outline-secondary">← Back to Briefs</a>
    </div>

    @include('design-management.partials.alerts')

    <form method="POST" action="{{ route('admin.projects.design-management.briefs.store', $project) }}">
        @csrf
        @include('design-management.briefs._form')

        <div class="d-flex justify-content-end gap-2 mb-5">
            <a href="{{ route('admin.projects.design-management.briefs.index', $project) }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Brief</button>
        </div>
    </form>
</div>
@endsection
