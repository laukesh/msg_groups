<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Prepared By</div>
                <div class="fw-semibold mt-1">{{ $record->preparer?->name ?? '—' }}</div>
                <div class="text-muted small mt-1">{{ $record->prepared_at?->format('d M Y H:i') ?? '—' }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Submitted By</div>
                <div class="fw-semibold mt-1">{{ $record->submitter?->name ?? '—' }}</div>
                <div class="text-muted small mt-1">{{ $record->submitted_at?->format('d M Y H:i') ?? '—' }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Approved By</div>
                <div class="fw-semibold mt-1">{{ $record->approver?->name ?? '—' }}</div>
                <div class="text-muted small mt-1">
                    @php
                        $approvedAt = $record->approved_at ?? $record->approval_date ?? null;
                    @endphp
                    {{ $approvedAt ? \Illuminate\Support\Carbon::parse($approvedAt)->format('d M Y H:i') : '—' }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Rejected By</div>
                <div class="fw-semibold mt-1">{{ $record->rejector?->name ?? '—' }}</div>
                <div class="text-muted small mt-1">{{ $record->rejected_at?->format('d M Y H:i') ?? '—' }}</div>
            </div>
        </div>
    </div>
</div>

@if(!empty($record->rejection_reason))
    <div class="alert alert-danger">
        <strong>Rejection Reason</strong>
        <div class="mt-1">{{ $record->rejection_reason }}</div>
    </div>
@endif

@if(!empty($record->approval_remarks))
    <div class="alert alert-success">
        <strong>Approval Remarks</strong>
        <div class="mt-1">{{ $record->approval_remarks }}</div>
    </div>
@endif
