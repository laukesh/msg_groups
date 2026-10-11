@extends('layouts.app')

@section('title', 'Edit Development Brief')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Edit Development Brief</h1>

            <p class="text-muted mb-0">
                Project:
                <strong>
                    {{ $project->name ?? $project->project_name ?? ('Project #' . $project->id) }}
                </strong>
            </p>

            @if($developmentBrief->brief_reference)
                <small class="text-muted">
                    Reference:
                    {{ $developmentBrief->brief_reference }}
                </small>
            @endif
        </div>

        <div>
            <a
                href="{{ route('admin.projects.development-brief.show', [$project, $developmentBrief]) }}"
                class="btn btn-info"
            >
                <i class="fas fa-eye"></i>
                View
            </a>

            <a
                href="{{ route('admin.projects.development-brief.index', $project) }}"
                class="btn btn-secondary"
            >
                <i class="fas fa-arrow-left"></i>
                Back
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('admin.projects.development-brief.update', [$project, $developmentBrief]) }}"
        method="POST"
    >
        @csrf
        @method('PUT')

        @include('projects.development-brief._form', [
            'developmentBrief' => $developmentBrief,
            'project' => $project
        ])

    </form>

</div>

@endsection