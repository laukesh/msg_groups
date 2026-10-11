@extends('layouts.app')

@section('title', 'Handover Details')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <a href="{{ route('admin.tenants.openings.index') }}">← Handover Register</a>
            <h3 class="mt-2 mb-1">{{ $handover->handover_number }}</h3>
            <p class="text-muted mb-0">
                {{ $handover->handover_type }} · {{ $handover->handover_date ?: 'Date not set' }}
            </p>
        </div>

        <span class="badge fs-6
            {{ $handover->status === 'Completed' ? 'bg-success' :
               (in_array($handover->status, ['Rejected', 'Cancelled']) ? 'bg-danger' : 'bg-warning text-dark') }}">
            {{ $handover->status }}
        </span>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please review:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white"><h5 class="mb-0">Handover details</h5></div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Tenant ID</dt>
                        <dd class="col-sm-7">{{ $handover->tenant_id }}</dd>

                        <dt class="col-sm-5">Unit ID</dt>
                        <dd class="col-sm-7">{{ $handover->unit_id }}</dd>

                        <dt class="col-sm-5">Fit-out request ID</dt>
                        <dd class="col-sm-7">{{ $handover->fitout_request_id }}</dd>

                        <dt class="col-sm-5">Unit condition</dt>
                        <dd class="col-sm-7">{{ $handover->unit_condition ?: '—' }}</dd>

                        <dt class="col-sm-5">Keys handed over</dt>
                        <dd class="col-sm-7">{{ $handover->key_count }}</dd>

                        <dt class="col-sm-5">Access cards issued</dt>
                        <dd class="col-sm-7">{{ $handover->access_card_count }}</dd>

                        <dt class="col-sm-5">Electricity meter</dt>
                        <dd class="col-sm-7">
                            {{ $handover->electricity_meter_no ?: '—' }}
                            / {{ $handover->electricity_meter_reading ?? '—' }}
                        </dd>

                        <dt class="col-sm-5">Water meter</dt>
                        <dd class="col-sm-7">
                            {{ $handover->water_meter_no ?: '—' }}
                            / {{ $handover->water_meter_reading ?? '—' }}
                        </dd>

                        <dt class="col-sm-5">Outstanding items</dt>
                        <dd class="col-sm-7">
                            {!! nl2br(e($handover->outstanding_items ?: 'None recorded')) !!}
                        </dd>

                        <dt class="col-sm-5">Remarks</dt>
                        <dd class="col-sm-7">
                            {!! nl2br(e($handover->remarks ?: '—')) !!}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white"><h5 class="mb-0">Sign-off status</h5></div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Tenant acceptance:</strong>
                        {{ $handover->tenant_accepted_at ?: 'Pending' }}
                    </p>
                    <p class="mb-2">
                        <strong>Mall approval:</strong>
                        {{ $handover->mall_approved_at ?: 'Pending' }}
                    </p>
                    <p class="mb-0">
                        <strong>Final status:</strong> {{ $handover->status }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white"><h5 class="mb-0">Checklist</h5></div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr><th>Item</th><th>Result</th></tr>
                </thead>
                <tbody>
                    @foreach($checklistItems as $key => $label)
                        @php $value = $checklist[$key] ?? 'pending'; @endphp
                        <tr>
                            <td>{{ $label }}</td>
                            <td>
                                <span class="badge
                                    {{ $value === 'done' ? 'bg-success' :
                                       ($value === 'not_applicable' ? 'bg-secondary' : 'bg-warning text-dark') }}">
                                    {{ str_replace('_', ' ', ucfirst($value)) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white"><h5 class="mb-0">Workflow actions</h5></div>
        <div class="card-body d-flex flex-wrap gap-2">

            @if(in_array($handover->status, ['Pending', 'Scheduled']))
                @can('tenant-openings.manage')
                    <form method="POST" action="{{ route('admin.tenants.openings.start', $handover->id) }}">
                        @csrf @method('PATCH')
                        <button class="btn btn-outline-primary">Start Inspection</button>
                    </form>
                @endcan
            @endif

            @if(in_array($handover->status, ['In Progress', 'Scheduled']))
                @can('tenant-openings.accept')
                    <form method="POST" action="{{ route('admin.tenants.openings.accept', $handover->id) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="acceptance_notes" value="Tenant acceptance recorded by authenticated user">
                        <button class="btn btn-success">Record Tenant Acceptance</button>
                    </form>
                @endcan
            @endif

            @if($handover->status === 'Accepted' && !$handover->mall_approved_at)
                @can('tenant-openings.approve')
                    <form method="POST" action="{{ route('admin.tenants.openings.approve', $handover->id) }}">
                        @csrf @method('PATCH')
                        <button class="btn btn-primary">Approve / Mall Sign-off</button>
                    </form>
                @endcan
            @endif

            @if($handover->status === 'Accepted' && $handover->mall_approved_at)
                @can('tenant-openings.approve')
                    <form method="POST" action="{{ route('admin.tenants.openings.complete', $handover->id) }}">
                        @csrf @method('PATCH')
                        <button class="btn btn-success">Complete Handover</button>
                    </form>
                @endcan
            @endif

            @if(in_array($handover->status, ['Pending', 'Scheduled', 'In Progress']))
                @can('tenant-openings.edit')
                    <a href="{{ route('admin.tenants.openings.edit', $handover->id) }}"
                       class="btn btn-outline-secondary">Edit Record</a>
                @endcan
            @endif
        </div>
    </div>
</div>
@endsection