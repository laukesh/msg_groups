@php
    $status = $record->workflowStatus();
    $config = $config ?? \App\Support\DesignWorkflowConfig::for(get_class($record));
    $modalPrefix = $modalPrefix ?? str_replace(['/', '\\'], '-', get_class($record));
@endphp

@if(method_exists($record, 'isWorkflowEditable') && $record->isWorkflowEditable() && !empty($editUrl))
    <a href="{{ $editUrl }}" class="btn btn-primary">Edit</a>
@endif

@if(in_array($status, $config['submit_from'] ?? [], true) && !empty($routes['submit']))
    <form method="POST" action="{{ $routes['submit'] }}" class="d-inline" onsubmit="return confirm('Submit this {{ strtolower($config['entity_label']) }} for review?');">
        @csrf
        <button type="submit" class="btn btn-warning">{{ $config['submit_label'] ?? 'Submit for Review' }}</button>
    </form>
@endif

@if(in_array($status, $config['approve_from'] ?? [], true) && !empty($routes['approve']))
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approve{{ $modalPrefix }}Modal">
        {{ $config['approve_label'] ?? 'Approve' }}
    </button>
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#reject{{ $modalPrefix }}Modal">
        {{ $config['reject_label'] ?? 'Reject' }}
    </button>
@endif

@if(($config['supports_revision'] ?? false) && in_array($status, $config['revision_from'] ?? ['Approved'], true) && !empty($routes['revision']))
    <form method="POST" action="{{ $routes['revision'] }}" class="d-inline" onsubmit="return confirm('Create a new revision from this approved record?');">
        @csrf
        <button type="submit" class="btn btn-warning">Create Revision</button>
    </form>
@endif
