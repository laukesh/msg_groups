@php
    $modalPrefix = $modalPrefix ?? str_replace(['/', '\\'], '-', get_class($record));
@endphp

@if(!empty($routes['approve']))
<div class="modal fade" id="approve{{ $modalPrefix }}Modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ $routes['approve'] }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ $config['approve_label'] ?? 'Approve' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Approval Remarks</label>
                <textarea name="approval_remarks" class="form-control" rows="3" placeholder="Optional approval remarks..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">{{ $config['approve_label'] ?? 'Approve' }}</button>
            </div>
        </form>
    </div>
</div>
@endif

@if(!empty($routes['reject']))
<div class="modal fade" id="reject{{ $modalPrefix }}Modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ $routes['reject'] }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ $config['reject_label'] ?? 'Reject' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Rejection Reason</label>
                <textarea name="rejection_reason" class="form-control" rows="3" placeholder="Reason for rejection..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">{{ $config['reject_label'] ?? 'Reject' }}</button>
            </div>
        </form>
    </div>
</div>
@endif
