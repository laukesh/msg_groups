@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small mb-1">Design Management / Project Brief</div>
            <h3 class="mb-1">Edit Project Brief</h3>
            <div class="text-muted">
                {{ $project->project_name }}
                · {{ $brief->brief_code }}
                · Version {{ $brief->version }}
            </div>
        </div>
        <a href="{{ route('admin.projects.design-management.briefs.show', [$project, $brief]) }}" class="btn btn-outline-secondary">← Back</a>
    </div>

    @include('design-management.partials.alerts')

    <form method="POST" action="{{ route('admin.projects.design-management.briefs.update', [$project, $brief]) }}">
        @csrf
        @method('PUT')
        @include('design-management.briefs._form')

        <div class="d-flex justify-content-end gap-2 mb-5">
            <a href="{{ route('admin.projects.design-management.briefs.show', [$project, $brief]) }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Brief</button>
        </div>
    </form>
</div>
@endsection
