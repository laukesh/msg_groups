@extends('layouts.app')

@section('title', 'Development Brief Version')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">

        <div>
            <h1 class="h3 mb-1">
                Development Brief Version {{ $version->version }}
            </h1>

            <p class="text-muted mb-1">
                {{ $developmentBrief->brief_title }}
            </p>

            <small class="text-muted">
                Revision:
                <strong>
                    {{ $version->revision_number ?: '—' }}
                </strong>
            </small>
        </div>

        <a
            href="{{ route(
                'admin.projects.development-brief.versions',
                [$project, $developmentBrief]
            ) }}"
            class="btn btn-secondary"
        >
            <i class="fas fa-arrow-left"></i>
            Back to Versions
        </a>

    </div>

    <div class="card mb-4">

        <div class="card-header">
            <h5 class="mb-0">
                Version Information
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <small class="text-muted d-block">
                        Version
                    </small>

                    <strong>
                        {{ $version->version }}
                    </strong>
                </div>

                <div class="col-md-3 mb-3">
                    <small class="text-muted d-block">
                        Revision Number
                    </small>

                    <strong>
                        {{ $version->revision_number ?: '—' }}
                    </strong>
                </div>

                <div class="col-md-3 mb-3">
                    <small class="text-muted d-block">
                        Status
                    </small>

                    <span class="badge bg-secondary">
                        {{ $version->status ?: '—' }}
                    </span>
                </div>

                <div class="col-md-3 mb-3">
                    <small class="text-muted d-block">
                        Created By
                    </small>

                    <strong>
                        {{ $version->creator->name
                            ?? $version->creator->full_name
                            ?? '—' }}
                    </strong>
                </div>

            </div>

            <hr>

            <div>
                <strong class="d-block text-muted mb-2">
                    Change Summary
                </strong>

                {!! nl2br(e($version->change_summary ?: 'No change summary provided.')) !!}
            </div>

        </div>

    </div>

    {{-- Snapshot Data --}}
    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-file-code"></i>
                Snapshot Data
            </h5>
        </div>

        <div class="card-body">

            @if(is_array($version->snapshot_data))

                @foreach($version->snapshot_data as $field => $value)

                    @if(!in_array($field, ['created_at', 'updated_at']))

                        <div class="mb-4">

                            <strong class="d-block text-muted mb-1">
                                {{ ucwords(str_replace('_', ' ', $field)) }}
                            </strong>

                            @if(is_array($value))

                                <pre class="bg-light p-3 rounded">{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>

                            @else

                                {!! nl2br(e($value ?: '—')) !!}

                            @endif

                        </div>

                    @endif

                @endforeach

            @else

                <p class="text-muted mb-0">
                    Snapshot data is not available.
                </p>

            @endif

        </div>

    </div>

</div>

@endsection