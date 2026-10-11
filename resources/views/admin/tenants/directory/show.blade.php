@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <a href="{{ route('admin.leasing.tenant-directory.index') }}"
               class="text-decoration-none small">
                <i class="ri-arrow-left-line"></i> Tenant Directory
            </a>

            <h4 class="mt-2 mb-1">{{ $tenant->company_name }}</h4>

            <div class="text-muted">
                {{ $tenant->brand_name ?: 'No brand registered' }}
                · {{ $tenant->tenant_code }}
            </div>
        </div>

        <div class="d-flex gap-2">
            @can('tenants.edit')
                <a href="{{ route('admin.tenants.edit', $tenant) }}"
                   class="btn btn-primary">
                    <i class="ri-edit-line"></i> Edit Tenant
                </a>
            @endcan
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Tenant Status</div>
                    <h5 class="mt-2">
                        <span class="badge {{ $tenant->status === 'Active'
                            ? 'bg-success'
                            : 'bg-secondary' }}">
                            {{ $tenant->status }}
                        </span>
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Registered Contacts</div>
                    <h3 class="mt-2">{{ $tenant->contacts->count() }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Lease Agreements</div>
                    <h3 class="mt-2">{{ $tenant->leases->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Company information --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <strong><i class="ri-building-line me-1"></i> Company Information</strong>
        </div>

        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-muted small">Legal Company Name</div>
                    <div class="fw-semibold">{{ $tenant->company_name }}</div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted small">Brand Name</div>
                    <div>{{ $tenant->brand_name ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted small">Tenant Code</div>
                    <div>{{ $tenant->tenant_code }}</div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted small">Business Category</div>
                    <div>{{ $category ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted small">Parent Group</div>
                    <div>
                        @if($tenant->parentGroup)
                            <a href="{{ route(
                                'admin.leasing.tenant-directory.show',
                                $tenant->parentGroup
                            ) }}">
                                {{ $tenant->parentGroup->company_name }}
                            </a>
                        @else
                            Independent company
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted small">Website</div>
                    <div>
                        @if($tenant->website)
                            <a href="{{ $tenant->website }}"
                               target="_blank"
                               rel="noopener noreferrer">
                                {{ $tenant->website }}
                            </a>
                        @else
                            —
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted small">Company Registration Number</div>
                    <div>{{ $tenant->company_registration_no ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted small">GST / Tax Number</div>
                    <div>{{ $tenant->gst_number ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted small">PAN / Tax Identifier</div>
                    <div>{{ $tenant->pan_number ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted small">Email</div>
                    <div>{{ $tenant->email ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted small">Phone</div>
                    <div>{{ $tenant->phone ?: '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Parent group's other companies --}}
    @if($tenant->childGroups->isNotEmpty())
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <strong>Companies and Brands in This Group</strong>
            </div>

            <div class="list-group list-group-flush">
                @foreach($tenant->childGroups as $child)
                    <a href="{{ route(
                        'admin.leasing.tenant-directory.show',
                        $child
                    ) }}"
                       class="list-group-item list-group-item-action d-flex justify-content-between">
                        <span>
                            {{ $child->company_name }}
                            <small class="text-muted">
                                {{ $child->brand_name ?: '' }}
                            </small>
                        </span>
                        <span class="badge {{ $child->status === 'Active'
                            ? 'bg-success'
                            : 'bg-secondary' }}">
                            {{ $child->status }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Existing contacts --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <strong><i class="ri-contacts-line me-1"></i> Contacts</strong>

            @can('tenant-contacts.view')
                <a href="{{ route('admin.tenants.contacts.index', $tenant) }}"
                   class="btn btn-sm btn-outline-primary">
                    Manage Contacts
                </a>
            @endcan
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Primary</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenant->contacts as $contact)
                        <tr>
                            <td>{{ $contact->contact_name }}</td>
                            <td>{{ $contact->designation ?: '—' }}</td>
                            <td>{{ $contact->mobile ?: '—' }}</td>
                            <td>{{ $contact->email ?: '—' }}</td>
                            <td>
                                @if($contact->is_primary)
                                    <span class="badge bg-success">Primary</span>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                No contacts registered.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Existing addresses --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <strong><i class="ri-map-pin-line me-1"></i> Registered Addresses</strong>
        </div>

        <div class="card-body">
            @forelse($tenant->addresses as $address)
                <div class="border rounded p-3 mb-2">
                    <div class="fw-semibold">
                        {{ $address->address_type ?: 'Address' }}
                        @if($address->is_default)
                            <span class="badge bg-primary">Default</span>
                        @endif
                    </div>

                    <div>
                        {{ $address->address_line1 }}
                        {{ $address->address_line2 }}
                    </div>

                    <div class="text-muted">
                        {{ collect([
                            $address->city,
                            $address->state,
                            $address->pincode,
                            $address->country,
                        ])->filter()->implode(', ') ?: '—' }}
                    </div>
                </div>
            @empty
                <p class="text-muted mb-0">No addresses registered.</p>
            @endforelse

            @can('tenant-addresses.view')
                <a href="{{ route('admin.tenants.addresses.index', $tenant) }}"
                   class="btn btn-sm btn-outline-primary mt-2">
                    Manage Addresses
                </a>
            @endcan
        </div>
    </div>

    {{-- Existing leases --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <strong><i class="ri-file-list-3-line me-1"></i> Lease Agreements</strong>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Agreement No.</th>
                        <th>Agreement Date</th>
                        <th>Lease Start</th>
                        <th>Lease End</th>
                        <th>Monthly Rent</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenant->leases as $lease)
                        <tr>
                            <td>{{ $lease->agreement_no }}</td>
                            <td>{{ $lease->agreement_date?->format('d M Y') ?? '—' }}</td>
                            <td>{{ $lease->lease_start_date?->format('d M Y') ?? '—' }}</td>
                            <td>{{ $lease->lease_end_date?->format('d M Y') ?? '—' }}</td>
                            <td>{{ number_format((float) $lease->monthly_rent, 2) }}</td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ $lease->agreement_status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                No lease agreements found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Existing bank accounts and documents --}}
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <strong><i class="ri-bank-line me-1"></i> Bank Accounts</strong>
                </div>
                <div class="card-body">
                    @forelse($tenant->bankAccounts as $bank)
                        <div class="border-bottom pb-2 mb-2">
                            <div class="fw-semibold">{{ $bank->bank_name }}</div>
                            <div>{{ $bank->account_holder }}</div>
                            <div class="text-muted small">
                                Account ending:
                                {{ substr((string) $bank->account_number, -4) }}
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No bank accounts registered.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <strong><i class="ri-folder-line me-1"></i> Documents</strong>
                </div>
                <div class="card-body">
                    @forelse($tenant->documents as $document)
                        <div class="border-bottom pb-2 mb-2">
                            <div class="fw-semibold">
                                {{ $document->file_name ?: 'Tenant document' }}
                            </div>
                            <div class="text-muted small">
                                Document no.: {{ $document->document_number ?: '—' }}
                                · {{ $document->verification_status ?: 'Pending' }}
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No documents registered.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection