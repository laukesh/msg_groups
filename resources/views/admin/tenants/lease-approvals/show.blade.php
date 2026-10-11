
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h4>{{ $approval->approval_number }}</h4>
            <span class="badge bg-secondary">
                {{ str_replace('_', ' ', $approval->status) }}
            </span>
            @if($approval->is_overdue)
                <span class="badge bg-danger">SLA Overdue</span>
            @endif
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.tenants.lease-approvals.index') }}"
               class="btn btn-outline-secondary">Back</a>
            @if(in_array($approval->status, ['DRAFT', 'CHANGES_REQUESTED'], true))
                @can('lease-approvals.edit')
                    <a href="{{ route('admin.tenants.lease-approvals.edit', $approval) }}"
                       class="btn btn-outline-primary">Edit</a>
                @endcan
            @endif
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><strong>Lease Summary</strong></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="text-muted small">Tenant</div>
                    {{ $approval->tenant->company_name ?? $approval->tenant->tenant_name ?? $approval->tenant->name ?? ('Tenant #' . $approval->tenant_id) }}
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Unit</div>
                    {{ $approval->unit->unit_no ?? ('Unit #' . $approval->unit_id) }}
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Related LOI</div>
                    {{ $approval->loi?->loi_number ?? '—' }}
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Proposed Rent</div>
                    {{ number_format((float) $approval->proposed_rent, 2) }}
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Agreed Rent</div>
                    {{ number_format((float) $approval->agreed_rent, 2) }}
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Service Charge</div>
                    {{ number_format((float) $approval->service_charge, 2) }}
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Security Deposit</div>
                    {{ number_format((float) $approval->security_deposit, 2) }}
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Lease Term</div>
                    {{ $approval->lease_term_months ? $approval->lease_term_months . ' months' : '—' }}
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Proposed Dates</div>
                    {{ $approval->proposed_start_date?->format('d M Y') ?? '—' }}
                    to
                    {{ $approval->proposed_end_date?->format('d M Y') ?? '—' }}
                </div>
                <div class="col-12">
                    <div class="text-muted small">Summary</div>
                    <div class="text-break">{{ $approval->lease_summary }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Deviations</div>
                    <div class="text-break">{{ $approval->deviations ?: '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Exceptions</div>
                    <div class="text-break">{{ $approval->exceptions ?: '—' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Credit Check</div>
                    {{ $approval->credit_check_status }}
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Credit Risk</div>
                    {{ $approval->credit_risk_rating ?: 'Not rated' }}
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">SLA Due</div>
                    {{ $approval->due_at?->format('d M Y H:i') ?? 'Not submitted' }}
                </div>
                <div class="col-12">
                    <div class="text-muted small">Credit Notes</div>
                    <div class="text-break">{{ $approval->credit_check_notes ?: '—' }}</div>
                </div>
                <div class="col-12">
                    <div class="text-muted small">Conditions Before Execution</div>
                    <div class="text-break">{{ $approval->conditions ?: '—' }}</div>
                </div>
                @if($approval->attachment_path)
                    <div class="col-12">
                        <a href="{{ asset('storage/' . $approval->attachment_path) }}"
                           target="_blank" rel="noopener">View Attachment</a>
                    </div>
                @endif
                @if($approval->final_notes)
                    <div class="col-12">
                        <div class="text-muted small">Final Notes</div>
                        {{ $approval->final_notes }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($approval->status === 'DRAFT' || $approval->status === 'CHANGES_REQUESTED')
        @can('lease-approvals.submit')
            <div class="card mb-4">
                <div class="card-body">
                    <h6>Submit for Approval</h6>
                    @if($approval->credit_check_status === 'FAILED')
                        <div class="alert alert-warning">
                            Credit check is marked FAILED. Resolve the credit issue before submitting.
                        </div>
                    @else
                        <form method="POST"
                              action="{{ route('admin.tenants.lease-approvals.submit', $approval) }}">
                            @csrf
                            <button class="btn btn-primary">Submit Approval Request</button>
                        </form>
                    @endif
                </div>
            </div>
        @endif
        @endcan
    @endif

    <div class="card">
        <div class="card-header"><strong>Authority Matrix and Decisions</strong></div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Review</th>
                        <th>Assigned Approver</th>
                        <th>Decision</th>
                        <th>Comments</th>
                        <th>Decision Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($approval->steps as $step)
                        <tr>
                            <td>{{ $step->step_name }}</td>
                            <td>{{ $step->approver?->name ?? 'Unassigned' }}</td>
                            <td>{{ str_replace('_', ' ', $step->decision) }}</td>
                            <td>{{ $step->comments ?: '—' }}</td>
                            <td>{{ $step->decided_at?->format('d M Y H:i') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted text-center">
                                No approval steps yet. Submit this request to create them.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(in_array($approval->status, ['PENDING_APPROVAL', 'IN_REVIEW'], true))
        @can('lease-approvals.decide')
            <div class="card mt-4">
                <div class="card-header"><strong>Record a Decision</strong></div>
                <div class="card-body">
                    <form method="POST"
                          action="{{ route('admin.tenants.lease-approvals.decide', $approval) }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Review Step</label>
                                <select name="step_name" class="form-select" required>
                                    @foreach(['COMMERCIAL', 'LEGAL', 'FINANCE'] as $stepName)
                                        <option value="{{ $stepName }}">{{ $stepName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Decision</label>
                                <select name="decision" class="form-select" required>
                                    <option value="APPROVED">Approve</option>
                                    <option value="CHANGES_REQUESTED">Request Changes</option>
                                    <option value="REJECTED">Reject</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Comments / Conditions</label>
                                <textarea name="comments" class="form-control"
                                          rows="3">{{ old('comments') }}</textarea>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-success">Save Decision</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endcan
    @endif
</div>
@endsection
