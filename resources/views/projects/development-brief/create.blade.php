@extends('layouts.app')

@section('title', 'Create Development Brief')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Create Development Brief</h1>

            <p class="text-muted mb-0">
                Project:
                <strong>
                    {{ $project->name ?? $project->project_name ?? ('Project #' . $project->id) }}
                </strong>
            </p>
        </div>

        <a href="{{ route('admin.projects.development-brief.index', $project) }}"
           class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Development Briefs
        </a>
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
        action="{{ route('admin.projects.development-brief.store', $project) }}"
        method="POST"
    >
        @csrf

        @include('projects.development-brief._form', [
            'developmentBrief' => null,
            'project' => $project
        ])

    </form>

</div>

@endsection