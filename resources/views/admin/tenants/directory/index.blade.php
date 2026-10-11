@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Tenant Directory</h4>
            <p class="text-muted mb-0">
                Central register of tenant companies, brands and parent groups.
            </p>
        </div>

        <div class="d-flex gap-2 mt-2">
            @can('tenants.create')
                <a href="{{ route('admin.tenants.create') }}"
                   class="btn btn-primary">
                    <i class="ri-add-line"></i> Add Tenant
                </a>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row g-3 mb-4">
        @foreach([
            'Total Tenants' => $stats['total'],
            'Active' => $stats['active'],
            'Inactive' => $stats['inactive'],
            'Parent Groups' => $stats['parent_groups'],
        ] as $label => $value)
            <div class="col-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">{{ $label }}</div>
                        <h3 class="mb-0 mt-2">{{ number_format($value) }}</h3>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET"
                  action="{{ route('admin.leasing.tenant-directory.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Search tenants</label>
                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Legal name, brand, code, registration, tax ID">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All statuses</option>
                            <option value="Active" @selected(request('status') === 'Active')>
                                Active
                            </option>
                            <option value="Inactive" @selected(request('status') === 'Inactive')>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All categories</option>
                            @foreach($categories as $categoryOption)
                                <option value="{{ $categoryOption->id }}"
                                    @selected((string) request('category') === (string) $categoryOption->id)>
                                    {{ $categoryOption->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Parent group</label>
                        <select name="parent_group_id" class="form-select">
                            <option value="">All groups</option>
                            @foreach($parentGroups as $group)
                                <option value="{{ $group->id }}"
                                    @selected((string) request('parent_group_id') === (string) $group->id)>
                                    {{ $group->company_name }}
                                    @if($group->brand_name)
                                        — {{ $group->brand_name }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 d-flex gap-2">
                        <button class="btn btn-primary">
                            <i class="ri-search-line"></i> Search
                        </button>
                        <a href="{{ route('admin.leasing.tenant-directory.index') }}"
                           class="btn btn-outline-secondary">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <strong>Registered Tenant Companies</strong>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tenant Code</th>
                        <th>Legal Company / Brand</th>
                        <th>Category</th>
                        <th>Parent Group</th>
                        <th>Contact Details</th>
                        <th>Contacts</th>
                        <th>Leases</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($tenants as $tenant)
                        <tr>
                            <td>
                                <span class="fw-semibold">
                                    {{ $tenant->tenant_code }}
                                </span>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $tenant->company_name }}
                                </div>
                                <div class="text-muted small">
                                    Brand: {{ $tenant->brand_name ?: '—' }}
                                </div>
                                <div class="text-muted small">
                                    Registration:
                                    {{ $tenant->company_registration_no ?: '—' }}
                                </div>
                            </td>

                            <td>
                                {{ $categories->firstWhere(
                                    'id',
                                    $tenant->business_category_id
                                )?->category_name ?? '—' }}
                            </td>

                            <td>
                                {{ $tenant->parentGroup?->company_name ?? 'Independent' }}
                            </td>

                            <td>
                                <div>{{ $tenant->email ?: '—' }}</div>
                                <div class="text-muted small">
                                    {{ $tenant->phone ?: '—' }}
                                </div>
                            </td>

                            <td>{{ $tenant->contacts_count }}</td>
                            <td>{{ $tenant->leases_count }}</td>

                            <td>
                                <span class="badge {{ $tenant->status === 'Active'
                                    ? 'bg-success'
                                    : 'bg-secondary' }}">
                                    {{ $tenant->status }}
                                </span>
                            </td>

                            <td class="text-end text-nowrap">
                                <a href="{{ route(
                                    'admin.leasing.tenant-directory.show',
                                    $tenant
                                ) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="View tenant">
                                    <i class="ri-eye-line"></i>
                                </a>

                                @can('tenants.edit')
                                    <a href="{{ route('admin.tenants.edit', $tenant) }}"
                                       class="btn btn-sm btn-outline-secondary"
                                       title="Edit tenant">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="ri-building-2-line fs-2 text-muted"></i>
                                <p class="mb-0 mt-2">No tenants found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-body">
            {{ $tenants->links() }}
        </div>
    </div>
</div>
@endsection