@extends('layouts.app')

@section('title', 'Workflow History')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">

        <div>
            <h1 class="h3 mb-1">
                Workflow History
            </h1>

            <p class="text-muted mb-1">
                {{ $developmentBrief->brief_title }}
            </p>

            <small class="text-muted">
                Project:
                <strong>
                    {{ $project->name ?? $project->project_name ?? ('Project #' . $project->id) }}
                </strong>
            </small>
        </div>

        <a
            href="{{ route(
                'admin.projects.development-brief.show',
                [$project, $developmentBrief]
            ) }}"
            class="btn btn-secondary"
        >
            <i class="fas fa-arrow-left"></i>
            Back to Brief
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-history"></i>
                Status Change History
            </h5>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>From Status</th>
                            <th>To Status</th>
                            <th>Action</th>
                            <th>Remarks</th>
                            <th>Performed By</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($history as $item)

                            @php
                                $statusClass = match ($item->to_status) {
                                    'Approved' => 'success',
                                    'Rejected' => 'danger',
                                    'Under Review' => 'warning',
                                    'Superseded' => 'secondary',
                                    'Draft' => 'info',
                                    default => 'dark',
                                };
                            @endphp

                            <tr>

                                <td>
                                    {{ $loop->iteration + (($history->currentPage() - 1) * $history->perPage()) }}
                                </td>

                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $item->from_status ?: '—' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ $item->to_status }}
                                    </span>
                                </td>

                                <td>
                                    <strong>
                                        {{ $item->action }}
                                    </strong>
                                </td>

                                <td style="min-width: 220px;">
                                    {!! nl2br(e($item->remarks ?: '—')) !!}
                                </td>

                                <td>
                                    {{ $item->performer->name
                                        ?? $item->performer->full_name
                                        ?? '—' }}
                                </td>

                                <td>
                                    {{ $item->performed_at
                                        ? $item->performed_at->format('d M Y h:i A')
                                        : '—' }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="7"
                                    class="text-center text-muted py-4"
                                >
                                    No workflow history found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if($history->hasPages())
            <div class="card-footer">
                {{ $history->links() }}
            </div>
        @endif

    </div>

</div>

@endsection