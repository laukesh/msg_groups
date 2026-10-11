
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Letters of Intent</h4>
            <p class="text-muted mb-0">Manage commercial terms, approvals and tenant acceptance.</p>
        </div>

        @can('lois.create')
            <a href="{{ route('admin.tenants.lois.create') }}" class="btn btn-primary">
                <i class="ri-add-line"></i> Create LOI
            </a>
        @endcan
    </div>

    @foreach(['success', 'error'] as $type)
        @if(session($type))
            <div class="alert alert-{{ $type === 'success' ? 'success' : 'danger' }} alert-dismissible fade show">
                {{ session($type) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    <div class="row g-3 mb-4">
        @foreach([
            'Total' => $stats['total'],
            'Draft' => $stats['draft'],
            'Pending Approval' => $stats['pending'],
            'Approved' => $stats['approved'],
            'Accepted' => $stats['accepted'],
            'Expired' => $stats['expired'],
        ] as $label => $count)
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted small">{{ $label }}</div>
                        <h4 class="mb-0">{{ $count }}</h4>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.tenants.lois.index') }}"
                  class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control"
                           placeholder="LOI number or title"
                           value="{{ request('search') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All statuses</option>
                        @foreach(['DRAFT', 'PENDING_APPROVAL', 'APPROVED', 'ISSUED', 'ACCEPTED', 'REJECTED', 'EXPIRED'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>
                                {{ str_replace('_', ' ', $status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Filter</button>
                    <a href="{{ route('admin.tenants.lois.index') }}"
                       class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>LOI Number</th>
                        <th>Tenant</th>
                        <th>Unit</th>
                        <th>Proposed Rent</th>
                        <th>Issue Date</th>
                        <th>Expiry Date</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lois as $loi)
                        @php
                            $tenant = $loi->tenant;
                            $tenantName = $tenant->company_name
                                ?? $tenant->tenant_name
                                ?? $tenant->name
                                ?? ('Tenant #' . $loi->tenant_id);

                            $unit = $loi->unit;
                            $unitName = $unit->unit_no ?? ('Unit #' . $loi->unit_id);
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('admin.tenants.lois.show', $loi) }}">
                                    <strong>{{ $loi->loi_number }}</strong>
                                </a>
                                <div class="small text-muted">{{ $loi->title }}</div>
                            </td>
                            <td>{{ $tenantName }}</td>
                            <td>{{ $unitName }}</td>
                            <td>{{ number_format((float) $loi->proposed_rent, 2) }}</td>
                            <td>{{ $loi->issue_date?->format('d M Y') ?? '—' }}</td>
                            <td>{{ $loi->expiry_date?->format('d M Y') ?? '—' }}</td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ str_replace('_', ' ', $loi->status) }}
                                </span>
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.tenants.lois.show', $loi) }}"
                                   class="btn btn-sm btn-outline-primary">View</a>

                                @if(in_array($loi->status, ['DRAFT', 'REJECTED'], true))
                                    @can('lois.edit')
                                        <a href="{{ route('admin.tenants.lois.edit', $loi) }}"
                                           class="btn btn-sm btn-outline-secondary">Edit</a>
                                    @endcan
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                No LOIs found. Create your first letter of intent.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $lois->links() }}
        </div>
    </div>
</div>
@endsection
