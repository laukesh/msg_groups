
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h4>Lease Approval Register</h4>
            <p class="text-muted mb-0">Commercial, legal and finance approvals before lease execution.</p>
        </div>
        @can('lease-approvals.create')
            <a class="btn btn-primary"
               href="{{ route('admin.tenants.lease-approvals.create') }}">
                Create Approval
            </a>
        @endcan
    </div>

    <div class="row g-3 mb-4">
        @foreach([
            'Total' => $stats['total'],
            'Draft' => $stats['draft'],
            'Pending' => $stats['pending'],
            'Approved' => $stats['approved'],
            'Rejected' => $stats['rejected'],
            'Overdue' => $stats['overdue'],
        ] as $label => $count)
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="small text-muted">{{ $label }}</div>
                        <h4 class="mb-0">{{ $count }}</h4>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Search</label>
                    <input name="search" class="form-control"
                           value="{{ request('search') }}"
                           placeholder="Approval number or lease summary">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All statuses</option>
                        @foreach(['DRAFT','PENDING_APPROVAL','IN_REVIEW','CHANGES_REQUESTED','APPROVED','REJECTED'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>
                                {{ str_replace('_', ' ', $status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.tenants.lease-approvals.index') }}"
                       class="btn btn-outline-secondary">Reset</a>
                </div>
                <div class="col-12">
                    <a href="{{ route('admin.tenants.lease-approvals.index', ['sla' => 'overdue']) }}">
                        Show overdue approvals
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Approval No.</th>
                        <th>Tenant</th>
                        <th>Unit</th>
                        <th>Proposed Rent</th>
                        <th>Status</th>
                        <th>SLA Due</th>
                        <th>Approvals</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($approvals as $approval)
                        @php
                            $tenant = $approval->tenant;
                            $unit = $approval->unit;
                            $overdue = $approval->is_overdue;
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $approval->approval_number }}</strong>
                                <div class="small text-muted">
                                    {{ $approval->created_at?->format('d M Y') }}
                                </div>
                            </td>
                            <td>
                                {{ $tenant->company_name ?? $tenant->tenant_name ?? $tenant->name ?? ('Tenant #' . $approval->tenant_id) }}
                            </td>
                            <td>{{ $unit->unit_no ?? ('Unit #' . $approval->unit_id) }}</td>
                            <td>{{ number_format((float) $approval->proposed_rent, 2) }}</td>
                            <td>
                                <span class="badge {{ $overdue ? 'bg-danger' : 'bg-secondary' }}">
                                    {{ str_replace('_', ' ', $approval->status) }}
                                </span>
                                @if($overdue)
                                    <div class="small text-danger">SLA overdue</div>
                                @endif
                            </td>
                            <td>{{ $approval->due_at?->format('d M Y H:i') ?? '—' }}</td>
                            <td>
                                {{ $approval->steps->where('decision', 'APPROVED')->count() }}
                                /
                                {{ $approval->steps->count() }}
                            </td>
                            <td class="text-end text-nowrap">
                                <a class="btn btn-sm btn-outline-primary"
                                   href="{{ route('admin.tenants.lease-approvals.show', $approval) }}">
                                    View
                                </a>
                                @if(in_array($approval->status, ['DRAFT', 'CHANGES_REQUESTED'], true))
                                    @can('lease-approvals.edit')
                                        <a class="btn btn-sm btn-outline-secondary"
                                           href="{{ route('admin.tenants.lease-approvals.edit', $approval) }}">
                                            Edit
                                        </a>
                                    @endcan
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                No lease approval requests found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $approvals->links() }}</div>
    </div>
</div>
@endsection
