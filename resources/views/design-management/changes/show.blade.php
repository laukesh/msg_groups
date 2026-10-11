@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small mb-1">Design Management / Design Changes</div>
            <h3 class="mb-1">{{ $change->change_title }}</h3>
            <div class="text-muted">{{ $project->project_name }}</div>
            <div class="mt-2">
                <span class="badge bg-secondary">{{ $change->change_code }}</span>
                @include('design-management.partials.status-badge', ['status' => $change->workflowStatus()])
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap justify-content-end">
            <a href="{{ route('admin.projects.design-management.changes.index', $project) }}" class="btn btn-outline-secondary">← Changes</a>
            @include('design-management.partials.workflow-actions', [
                'record' => $change,
                'config' => $workflowConfig,
                'routes' => $workflowRoutes,
                'editUrl' => $change->isWorkflowEditable() ? route('admin.projects.design-management.changes.edit', [$project, $change]) : null,
            ])
        </div>
    </div>

    @include('design-management.partials.alerts')
    @include('design-management.partials.workflow-governance', ['record' => $change])

    <div class="card mb-3"><div class="card-body row g-3">
        <div class="col-md-3"><div class="text-muted small">Type</div>{{ $change->change_type ?? '—' }}</div>
        <div class="col-md-3"><div class="text-muted small">Cost Impact</div>{{ $change->currency }} {{ number_format((float) $change->cost_impact, 2) }}</div>
        <div class="col-md-3"><div class="text-muted small">Time Impact</div>{{ $change->time_impact_days }} days</div>
        <div class="col-md-3"><div class="text-muted small">Requested By</div>{{ $change->requester?->name ?? '—' }}</div>
        <div class="col-12"><div class="text-muted small">Description</div>{{ $change->description ?: '—' }}</div>
    </div></div>

    <div class="card mb-4">
        <div class="card-header bg-white fw-semibold">Cost Impact Breakdown</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light"><tr><th>Discipline</th><th>Category</th><th>Estimated</th><th>Approved</th><th></th></tr></thead>
                <tbody>
                    @forelse($change->costImpacts as $impact)
                        <tr>
                            <td>{{ $impact->discipline?->name ?? '—' }}</td>
                            <td>{{ $impact->cost_category ?? '—' }}</td>
                            <td>{{ $impact->currency }} {{ number_format((float) $impact->estimated_amount, 2) }}</td>
                            <td>{{ $impact->currency }} {{ number_format((float) $impact->approved_amount, 2) }}</td>
                            <td class="text-end">
                                <form method="POST" action="{{ route('admin.projects.design-management.changes.cost-impacts.destroy', [$project, $change, $impact]) }}" class="d-inline" onsubmit="return confirm('Remove this cost impact line?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">No cost impact lines yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body border-top">
            <form method="POST" action="{{ route('admin.projects.design-management.changes.cost-impacts.store', [$project, $change]) }}" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-3">
                    <label class="form-label">Discipline</label>
                    <select name="design_discipline_id" class="form-select">
                        <option value="">Select</option>
                        @foreach($disciplines as $discipline)
                            <option value="{{ $discipline->id }}">{{ $discipline->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select name="cost_category" class="form-select">
                        <option value="">Select</option>
                        @foreach($costCategories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Estimated</label>
                    <input type="number" step="0.01" name="estimated_amount" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Approved</label>
                    <input type="number" step="0.01" name="approved_amount" class="form-control" value="0">
                </div>
                <div class="col-md-1">
                    <label class="form-label">Currency</label>
                    <select name="currency" class="form-select">
                        @foreach($currencies as $currency)
                            <option value="{{ $currency }}">{{ $currency }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary w-100">Add</button>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <input type="text" name="description" class="form-control">
                </div>
            </form>
        </div>
    </div>

    @include('design-management.partials.workflow-show-footer', [
        'record' => $change,
        'codeField' => 'change_code',
        'titleField' => 'change_title',
        'versionField' => 'version_number',
        'revisionShowRoute' => fn ($revision) => route('admin.projects.design-management.changes.show', [$project, $revision]),
    ])
</div>
@endsection
