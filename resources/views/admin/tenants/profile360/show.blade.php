@extends('layouts.app')

@section('title', 'Tenant Profile 360')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <a href="{{ route('admin.tenants.profile360.index') }}"
               class="text-decoration-none">
                <i class="ri-arrow-left-line"></i> Tenant Directory
            </a>

            <h3 class="mt-2 mb-1">
                {{ $tenant->company_name }}
            </h3>

            <div class="text-muted">
                {{ $tenant->brand_name ?: 'No brand recorded' }}
                · {{ $tenant->tenant_code ?: 'No tenant code' }}
            </div>
        </div>

        <span class="badge {{ $tenant->status === 'Active' ? 'bg-success' : 'bg-secondary' }} fs-6">
            {{ $tenant->status ?: 'Unknown' }}
        </span>
    </div>

    {{-- Summary cards --}}
    <div class="row g-3 mb-4">
        @php
            $cards = [
                ['Active leases', $summary['active_leases'], 'ri-file-list-3-line'],
                ['Expiring within 90 days', $summary['expiring_leases'], 'ri-calendar-event-line'],
                ['Invoices', $summary['invoices'], 'ri-receipt-line'],
                ['Outstanding balance', number_format($summary['outstanding_balance'], 2), 'ri-money-dollar-circle-line'],
                ['Open service requests', $summary['open_requests'], 'ri-tools-line'],
                ['Fit-out requests', $summary['fitout_requests'], 'ri-hammer-line'],
            ];
        @endphp

        @foreach($cards as [$label, $value, $icon])
            <div class="col-sm-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">{{ $label }}</div>
                            <h4 class="mb-0 mt-2">{{ $value }}</h4>
                        </div>
                        <i class="{{ $icon }} fs-2 text-primary"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Renewal risk --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between gap-2">
                <h5 class="mb-0">Renewal risk review</h5>

                <span class="badge
                    {{ $renewalRisk === 'High' ? 'bg-danger' :
                       ($renewalRisk === 'Medium' ? 'bg-warning text-dark' : 'bg-success') }}">
                    {{ $renewalRisk }}
                </span>
            </div>

            @if(count($riskReasons))
                <ul class="mt-3 mb-0">
                    @foreach($riskReasons as $reason)
                        <li>{{ $reason }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted mt-3 mb-0">
                    No risk indicators were detected by the current basic rules.
                    Review the full tenant record before making renewal decisions.
                </p>
            @endif
        </div>
    </div>

    {{-- Profile navigation --}}
    <ul class="nav nav-tabs mb-3" role="tablist">
        @foreach([
            'overview' => 'Overview',
            'leases' => 'Leases',
            'invoices' => 'Invoices',
            'payments' => 'Collections',
            'sales' => 'Sales',
            'requests' => 'Maintenance',
            'fitout' => 'Fit-out',
            'documents' => 'Documents',
            'communications' => 'Correspondence',
        ] as $key => $label)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                        data-bs-toggle="tab"
                        data-bs-target="#tab-{{ $key }}"
                        type="button"
                        role="tab">
                    {{ $label }}
                </button>
            </li>
        @endforeach
    </ul>

    <div class="tab-content">

        {{-- Overview --}}
        <div class="tab-pane fade show active" id="tab-overview">
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Tenant details</h5>
                        </div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">Legal name</dt>
                                <dd class="col-sm-7">{{ $tenant->company_name ?: '—' }}</dd>

                                <dt class="col-sm-5">Brand</dt>
                                <dd class="col-sm-7">{{ $tenant->brand_name ?: '—' }}</dd>

                                <dt class="col-sm-5">Registration number</dt>
                                <dd class="col-sm-7">{{ $tenant->company_registration_no ?: '—' }}</dd>

                                <dt class="col-sm-5">GST number</dt>
                                <dd class="col-sm-7">{{ $tenant->gst_number ?: '—' }}</dd>

                                <dt class="col-sm-5">Email</dt>
                                <dd class="col-sm-7">{{ $tenant->email ?: '—' }}</dd>

                                <dt class="col-sm-5">Phone</dt>
                                <dd class="col-sm-7">{{ $tenant->phone ?: '—' }}</dd>

                                <dt class="col-sm-5">Website</dt>
                                <dd class="col-sm-7">
                                    @if($tenant->website)
                                        <a href="{{ $tenant->website }}"
                                           target="_blank"
                                           rel="noopener noreferrer">
                                            {{ $tenant->website }}
                                        </a>
                                    @else
                                        —
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Primary contacts</h5>
                        </div>
                        <div class="card-body">
                            @forelse($contacts as $contact)
                                <div class="border-bottom pb-2 mb-2">
                                    <strong>{{ $contact->contact_name }}</strong>
                                    @if($contact->is_primary)
                                        <span class="badge bg-primary">Primary</span>
                                    @endif
                                    <div class="text-muted small">
                                        {{ $contact->designation ?: '—' }}
                                    </div>
                                    <div>{{ $contact->mobile ?: '—' }}</div>
                                    <div>{{ $contact->email ?: '—' }}</div>
                                </div>
                            @empty
                                <p class="text-muted mb-0">No contacts recorded.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Addresses</h5>
                </div>
                <div class="card-body">
                    @forelse($addresses as $address)
                        <div class="mb-3">
                            <strong>{{ $address->address_type ?: 'Address' }}</strong>
                            @if($address->is_default)
                                <span class="badge bg-success">Default</span>
                            @endif

                            <div>
                                {{ $address->address_line1 ?? '' }}
                                {{ $address->address_line2 ?? '' }}
                            </div>
                            <div class="text-muted">
                                {{ collect([
                                    $address->city ?? null,
                                    $address->state ?? null,
                                    $address->country ?? null,
                                    $address->pincode ?? null
                                ])->filter()->implode(', ') }}
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No addresses recorded.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Leases --}}
        <div class="tab-pane fade" id="tab-leases">
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Agreement</th>
                                <th>Status</th>
                                <th>Start date</th>
                                <th>End date</th>
                                <th class="text-end">Monthly rent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leases as $lease)
                                <tr>
                                    <td>{{ $lease->agreement_no ?? ('Lease #' . $lease->id) }}</td>
                                    <td>{{ $lease->agreement_status ?? '—' }}</td>
                                    <td>{{ $lease->lease_start_date ?? '—' }}</td>
                                    <td>{{ $lease->lease_end_date ?? '—' }}</td>
                                    <td class="text-end">
                                        {{ number_format((float) ($lease->monthly_rent ?? 0), 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        No leases found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Invoices --}}
        <div class="tab-pane fade" id="tab-invoices">
            @include('admin.tenants.profile360.partials.records', [
                'rows' => $invoices,
                'title' => 'Invoices',
                'columns' => ['invoice_no', 'invoice_date', 'due_date', 'invoice_status', 'total_amount', 'balance_amount']
            ])
        </div>

        {{-- Collections --}}
        <div class="tab-pane fade" id="tab-payments">
            @include('admin.tenants.profile360.partials.records', [
                'rows' => $payments,
                'title' => 'Collections',
                'columns' => ['receipt_no', 'payment_date', 'payment_method', 'payment_amount', 'amount', 'status']
            ])
        </div>

        {{-- Sales --}}
        <div class="tab-pane fade" id="tab-sales">
            @include('admin.tenants.profile360.partials.records', [
                'rows' => $sales,
                'title' => 'Sales',
                'columns' => ['sales_date', 'period_start', 'period_end', 'gross_sales', 'net_sales', 'status']
            ])
        </div>

        {{-- Maintenance --}}
        <div class="tab-pane fade" id="tab-requests">
            <h5 class="mb-3">Maintenance requests</h5>
            @include('admin.tenants.profile360.partials.records', [
                'rows' => $maintenanceRequests,
                'title' => 'Maintenance requests',
                'columns' => ['request_no', 'title', 'subject', 'status', 'priority', 'created_at']
            ])

            <h5 class="mt-4 mb-3">Work orders</h5>
            @include('admin.tenants.profile360.partials.records', [
                'rows' => $workOrders,
                'title' => 'Work orders',
                'columns' => ['work_order_no', 'title', 'status', 'work_order_status', 'created_at']
            ])
        </div>

        {{-- Fit-out --}}
        <div class="tab-pane fade" id="tab-fitout">
            @include('admin.tenants.profile360.partials.records', [
                'rows' => $fitoutRequests,
                'title' => 'Fit-out requests',
                'columns' => ['request_no', 'fitout_type', 'fitout_status', 'proposed_start_date', 'proposed_end_date', 'estimated_cost']
            ])
        </div>

        {{-- Documents --}}
        <div class="tab-pane fade" id="tab-documents">
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Document</th>
                                <th>Document number</th>
                                <th>Verification</th>
                                <th>Expiry date</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documents as $document)
                                <tr>
                                    <td>
                                        {{ $document->file_name ?: ('Document #' . $document->id) }}
                                    </td>
                                    <td>{{ $document->document_number ?: '—' }}</td>
                                    <td>{{ $document->verification_status ?: 'Pending' }}</td>
                                    <td>{{ $document->expiry_date ?: '—' }}</td>
                                    <td>
                                        @if($document->file_path)
                                            <a href="{{ \Illuminate\Support\Facades\Storage::url($document->file_path) }}"
                                               target="_blank"
                                               rel="noopener noreferrer">
                                                View
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        No documents found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Correspondence --}}
        <div class="tab-pane fade" id="tab-communications">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Tenant notes</h5>
                </div>
                <div class="card-body">
                    @forelse($notes as $note)
                        <div class="border-bottom mb-3 pb-3">
                            <strong>{{ $note->note_title ?: 'Tenant note' }}</strong>
                            <span class="badge bg-secondary">
                                {{ $note->visibility ?? 'Internal' }}
                            </span>
                            <p class="mb-1 mt-2">{{ $note->note }}</p>
                            <small class="text-muted">{{ $note->created_at }}</small>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No notes recorded.</p>
                    @endforelse
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Tenant activity history</h5>
                </div>
                <div class="card-body">
                    @forelse($history as $activity)
                        <div class="border-bottom pb-2 mb-2">
                            <strong>
                                {{ $activity->activity_type ?? 'Activity' }}
                            </strong>
                            <p class="mb-1">
                                {{ $activity->description ?? '' }}
                            </p>
                            <small class="text-muted">
                                {{ $activity->activity_date ?? '' }}
                            </small>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No activity history found.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection