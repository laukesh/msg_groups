@extends('layouts.app')

@section('title', 'Tenant Opening / Handover Register')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="mb-1">Tenant Opening / Handover</h3>
            <p class="text-muted mb-0">Unit condition, access, utilities, outstanding items and sign-off.</p>
        </div>

        @can('tenant-openings.create')
            <a href="{{ route('admin.tenants.openings.create') }}" class="btn btn-primary">
                <i class="ri-add-line"></i> Schedule Handover
            </a>
        @endcan
    </div>

    <div class="row g-3 mb-4">
        @foreach([
            ['Total records', $stats['total']],
            ['Pending / in progress', $stats['pending']],
            ['Completed', $stats['completed']],
            ['Rejected', $stats['rejected']],
        ] as [$label, $value])
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">{{ $label }}</div>
                        <h3 class="mt-2 mb-0">{{ $value }}</h3>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.tenants.openings.index') }}"
                  class="row g-3">
                <div class="col-md-6">
                    <input name="search" class="form-control"
                           value="{{ request('search') }}"
                           placeholder="Handover number, tenant or unit">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All statuses</option>
                        @foreach(['Pending', 'Scheduled', 'In Progress', 'Accepted', 'Rejected', 'Completed', 'Cancelled'] as $status)
                            <option value="{{ $status }}"
                                @selected(request('status') === $status)>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.tenants.openings.index') }}"
                       class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Handover No.</th>
                        <th>Tenant</th>
                        <th>Unit</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Condition</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($handovers as $row)
                        <tr>
                            <td class="fw-semibold">{{ $row->handover_number }}</td>
                            <td>
                                {{ $row->company_name ?: $row->brand_name ?: '—' }}
                            </td>
                            <td>{{ $row->unit_no ?: '—' }}</td>
                            <td>{{ $row->handover_type }}</td>
                            <td>{{ $row->handover_date ?: 'Not scheduled' }}</td>
                            <td>{{ $row->unit_condition ?: 'Not inspected' }}</td>
                            <td>
                                <span class="badge
                                    {{ $row->status === 'Completed' ? 'bg-success' :
                                       (in_array($row->status, ['Rejected', 'Cancelled']) ? 'bg-danger' : 'bg-warning text-dark') }}">
                                    {{ $row->status }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary"
                                   href="{{ route('admin.tenants.openings.show', $row->id) }}">
                                    View
                                </a>
                                @if(in_array($row->status, ['Pending', 'Scheduled', 'In Progress']))
                                    @can('tenant-openings.edit')
                                        <a class="btn btn-sm btn-outline-secondary"
                                           href="{{ route('admin.tenants.openings.edit', $row->id) }}">
                                            Edit
                                        </a>
                                    @endcan
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No handover records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $handovers->links() }}</div>
    </div>
</div>
@endsection