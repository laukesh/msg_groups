
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @foreach(['success', 'error'] as $type)
        @if(session($type))
            <div class="alert alert-{{ $type === 'success' ? 'success' : 'danger' }}">
                {{ session($type) }}
            </div>
        @endif
    @endforeach

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1">LOI Details</h4>
            <div class="text-muted">{{ $loi->loi_number }}</div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.tenants.lois.index') }}" class="btn btn-outline-secondary">
                Back
            </a>

            @if(in_array($loi->status, ['DRAFT', 'REJECTED'], true))
                @can('lois.edit')
                    <a href="{{ route('admin.tenants.lois.edit', $loi) }}" class="btn btn-outline-primary">
                        Edit
                    </a>
                @endcan
            @endif
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><strong>Commercial Summary</strong></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="text-muted small">Tenant</div>
                    <strong>
                        {{ $loi->tenant->company_name
                            ?? $loi->tenant->tenant_name
                            ?? $loi->tenant->name
                            ?? ('Tenant #' . $loi->tenant_id) }}
                    </strong>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Unit</div>
                    <strong>{{ $loi->unit->unit_no ?? ('Unit #' . $loi->unit_id) }}</strong>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Status</div>
                    <span class="badge bg-secondary">
                        {{ str_replace('_', ' ', $loi->status) }}
                    </span>
                </div>

                <div class="col-md-4">
                    <div class="text-muted small">Proposed Rent</div>
                    {{ number_format((float) $loi->proposed_rent, 2) }}
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Service Charge</div>
                    {{ number_format((float) $loi->service_charge, 2) }}
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Security Deposit</div>
                    {{ number_format((float) $loi->security_deposit, 2) }}
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Proposed Area</div>
                    {{ $loi->proposed_area ?? '—' }}
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Lease Term</div>
                    {{ $loi->lease_term_months ? $loi->lease_term_months . ' months' : '—' }}
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Issue Date</div>
                    {{ $loi->issue_date?->format('d M Y') ?? '—' }}
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Expiry Date</div>
                    {{ $loi->expiry_date?->format('d M Y') ?? '—' }}
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Acceptance Date</div>
                    {{ $loi->acceptance_date?->format('d M Y') ?? '—' }}
                </div>

                <div class="col-12">
                    <div class="text-muted small">Key Terms</div>
                    <div class="text-break">{{ $loi->key_terms ?: '—' }}</div>
                </div>
                <div class="col-12">
                    <div class="text-muted small">Conditions</div>
                    <div class="text-break">{{ $loi->conditions ?: '—' }}</div>
                </div>
                <div class="col-12">
                    <div class="text-muted small">Remarks</div>
                    <div class="text-break">{{ $loi->remarks ?: '—' }}</div>
                </div>

                @if($loi->attachment_path)
                    <div class="col-12">
                        <a href="{{ asset('storage/' . $loi->attachment_path) }}"
                           target="_blank" rel="noopener">
                            View LOI Attachment
                        </a>
                    </div>
                @endif

                @if($loi->approval_notes)
                    <div class="col-12">
                        <div class="text-muted small">Approval Notes</div>
                        {{ $loi->approval_notes }}
                    </div>
                @endif

                @if($loi->rejection_reason)
                    <div class="col-12">
                        <div class="text-muted small">Rejection Reason</div>
                        {{ $loi->rejection_reason }}
                    </div>
                @endif

                @if($loi->acceptance_notes)
                    <div class="col-12">
                        <div class="text-muted small">Acceptance Notes</div>
                        {{ $loi->acceptance_notes }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Draft / rejected: submit for approval --}}
    @if(in_array($loi->status, ['DRAFT', 'REJECTED'], true))
        @can('lois.submit')
            <div class="card mb-3">
                <div class="card-body">
                    <h6>Submit for Approval</h6>
                    <form method="POST" action="{{ route('admin.tenants.lois.submit', $loi) }}">
                        @csrf
                        <button class="btn btn-primary" type="submit">
                            Submit LOI
                        </button>
                    </form>
                </div>
            </div>
        @endcan
    @endif

    {{-- Pending approval --}}
    @if($loi->status === 'PENDING_APPROVAL')
        @can('lois.approve')
            <div class="card mb-3">
                <div class="card-body">
                    <h6>Approval Decision</h6>

                    <form method="POST"
                          action="{{ route('admin.tenants.lois.approve', $loi) }}"
                          class="mb-3">
                        @csrf
                        <label class="form-label">Approval Notes</label>
                        <textarea name="approval_notes" class="form-control mb-2" rows="2"></textarea>
                        <button class="btn btn-success" type="submit">Approve LOI</button>
                    </form>

                    <form method="POST" action="{{ route('admin.tenants.lois.reject', $loi) }}">
                        @csrf
                        <label class="form-label">Reason for Rejection</label>
                        <textarea name="rejection_reason" class="form-control mb-2"
                                  rows="2" required></textarea>
                        <button class="btn btn-danger" type="submit">Reject LOI</button>
                    </form>
                </div>
            </div>
        @endcan
    @endif

    {{-- Approved: issue the LOI --}}
    @if($loi->status === 'APPROVED')
        @can('lois.issue')
            <div class="card mb-3">
                <div class="card-body">
                    <h6>Issue Letter of Intent</h6>
                    <p class="text-muted">Issue the approved LOI to the tenant.</p>
                    <form method="POST" action="{{ route('admin.tenants.lois.issue', $loi) }}">
                        @csrf
                        <button class="btn btn-primary" type="submit">Mark as Issued</button>
                    </form>
                </div>
            </div>
        @endcan
    @endif

    {{-- Issued: record tenant acceptance --}}
    @if($loi->status === 'ISSUED')
        @can('lois.accept')
            <div class="card mb-3">
                <div class="card-body">
                    <h6>Record Tenant Acceptance</h6>
                    <form method="POST" action="{{ route('admin.tenants.lois.accept', $loi) }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Acceptance Date</label>
                                <input type="date" name="acceptance_date"
                                       class="form-control" required
                                       value="{{ old('acceptance_date', today()->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Acceptance Notes</label>
                                <input type="text" name="acceptance_notes"
                                       class="form-control"
                                       value="{{ old('acceptance_notes') }}">
                            </div>
                            <div class="col-12">
                                <button class="btn btn-success" type="submit">
                                    Record Acceptance
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endcan
    @endif

    @can('lois.delete')
        @if(in_array($loi->status, ['DRAFT', 'REJECTED'], true))
            <form method="POST"
                  action="{{ route('admin.tenants.lois.destroy', $loi) }}"
                  onsubmit="return confirm('Delete this LOI?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger" type="submit">Delete LOI</button>
            </form>
        @endif
    @endcan
</div>
@endsection
